<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Cart;
use App\Models\TempOrder;
use App\Models\TempOrderItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Coupon;
use App\Models\CouponUsage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Exception;

class CheckoutController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }
     protected function getShippingPrice()
    {
        $settings = \App\Models\SettingsModel::first();
        return $settings->shipping_price ?? 10.00;
    }

    public function index()
    {
        $page_heading = "Checkout";
        
        // Get cart items
        $cart_items = Cart::with('medicine')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        if ($cart_items->isEmpty()) {
            return redirect()->route('front.cart')->with('error', 'Your cart is empty');
        }

        // Check stock availability
        foreach ($cart_items as $item) {
            if ($item->medicine->stock_quantity < $item->quantity) {
                return redirect()->route('front.cart')->with('error', 
                    "Insufficient stock for {$item->medicine->title_en}. Only {$item->medicine->stock_quantity} available."
                );
            }
        }

        // Check if any prescription required medicine is in cart
        $prescription_required = $cart_items->contains(function ($item) {
            return $item->medicine->prescription_required;
        });

        // Get user addresses
        $addresses = Address::where('user_id', Auth::id())
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $appliedCoupon = null;
        $couponDiscount = 0;
        
        $firstCartItem = $cart_items->first();
        if ($firstCartItem && $firstCartItem->applied_coupon_id) {
            $appliedCoupon = Coupon::find($firstCartItem->applied_coupon_id);
            $couponDiscount = $firstCartItem->coupon_discount ?? 0;
        }

        $subtotal = $cart_items->sum('total');
        $shipping_fee = $this->getShippingPrice();
        $total = $subtotal + $shipping_fee - $couponDiscount;

        return view('front.checkout', compact(
            'page_heading', 
            'cart_items', 
            'addresses', 
            'subtotal', 
            'shipping_fee', 
            'total',
            'prescription_required',
            'appliedCoupon', 
           'couponDiscount' 
        ));
    }

    public function process(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'notes' => 'nullable|string|max:500',
            'prescription' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB max
        ]);

        DB::beginTransaction();
        
        try {
            // Get cart items
            $cart_items = Cart::with('medicine')
                ->where('user_id', Auth::id())
                ->get();

            if ($cart_items->isEmpty()) {
                throw new Exception('Your cart is empty');
            }

            // Check stock again
            foreach ($cart_items as $item) {
                if ($item->medicine->stock_quantity < $item->quantity) {
                    throw new Exception("Insufficient stock for {$item->medicine->title_en}");
                }
            }

            // Upload prescription if provided
            $prescriptionPath = null;
            if ($request->hasFile('prescription')) {
                $file = $request->file('prescription');
                $file_name = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                
                $config = config('global');
                $disk = $config['upload_bucket'] ?? 'public';
                $uploadDir = $config['prescription_upload_dir'] ?? 'prescriptions/';
                
                if ($disk === 's3') {
                    $path = $file->storeAs($uploadDir . 'temp/', $file_name, 's3');
                    Storage::disk('s3')->setVisibility($path, 'public');
                    $prescriptionPath = 'temp/' . $file_name;
                } else {
                    $file->storeAs($uploadDir . 'temp/', $file_name, 'public');
                    $prescriptionPath = 'temp/' . $file_name;
                }
            }
            $appliedCouponId = null;
            $couponDiscount = 0;
            $couponData = null;
            
            $firstCartItem = $cart_items->first();
            if ($firstCartItem && $firstCartItem->applied_coupon_id) {
                $appliedCouponId = $firstCartItem->applied_coupon_id;
                $couponDiscount = $firstCartItem->coupon_discount ?? 0;
                $couponData = $firstCartItem->coupon_data;
            }

            // Calculate totals
            $subtotal = $cart_items->sum('total');
            $shipping_fee = $this->getShippingPrice();
            $total = $subtotal + $shipping_fee - $couponDiscount;

            // Prepare cart data for JSON storage
            $cartData = $cart_items->map(function($item) {
                return [
                    'id' => $item->id,
                    'medicine_id' => $item->medicine_id,
                    'medicine_name' => $item->medicine->title_en,
                    'sku' => $item->medicine->sku,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total' => $item->total,
                    'prescription_required' => $item->medicine->prescription_required
                ];
            })->toArray();

            // Create temp order
            $tempOrder = TempOrder::create([
                'order_number' => 'TMP-' . strtoupper(uniqid()),
                'user_id' => Auth::id(),
                'address_id' => $request->address_id,
                'subtotal' => $subtotal,
                'shipping_fee' => $shipping_fee,
                'total' => $total,
                'prescription_path' => $prescriptionPath,
                'notes' => $request->notes,
                'payment_method' => 'stripe',
                'status' => TempOrder::STATUS_PENDING,
                'cart_data' => $cartData,
                'applied_coupon_id' => $appliedCouponId,
                'coupon_discount' => $couponDiscount,
                'coupon_data' => $couponData
            ]);

            // Create temp order items
            foreach ($cart_items as $item) {
                TempOrderItem::create([
                    'temp_order_id' => $tempOrder->id,
                    'medicine_id' => $item->medicine_id,
                    'medicine_name' => $item->medicine->title_en,
                    'sku' => $item->medicine->sku,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'total' => $item->total,
                    'prescription_required' => $item->medicine->prescription_required ?? false,
                    'medicine_details' => [
                        'id' => $item->medicine->id,
                        'title_en' => $item->medicine->title_en,
                        'sku' => $item->medicine->sku,
                        'price' => $item->medicine->price,
                        'discount_price' => $item->medicine->discount_price
                    ]
                ]);
            }

            // Store temp order in session
            Session::put('temp_order_id', $tempOrder->id);

            DB::commit();

            // Redirect to Stripe checkout
            return $this->redirectToStripe($tempOrder);

        } catch (Exception $e) {
            DB::rollBack();
            
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function redirectToStripe($tempOrder)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        // Get cart items for line items
        $cart_items = Cart::with('medicine')
            ->where('user_id', Auth::id())
            ->get();

        $lineItems[] = [
            'price_data' => [
                'currency' => 'aed',
                'product_data' => [
                    'name' => 'Order Total',
                ],
                'unit_amount' => round($tempOrder->total * 100),
            ],
            'quantity' => 1,
        ];

       

        
       

        try {
            $checkoutSession = StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('front.payment.success') . '?stripe_session={CHECKOUT_SESSION_ID}',
                'cancel_url'  => route('front.payment.cancel', [], true),
                'client_reference_id' => $tempOrder->id,
                'metadata' => [
                    'temp_order_id' => $tempOrder->id,
                    'order_number' => $tempOrder->order_number,
                    'user_id' => Auth::id()
                ]
            ]);

            // Update temp order with session_id
            $tempOrder->update([
                'session_id' => $checkoutSession->id
            ]);

            return redirect()->away($checkoutSession->url);

        } catch (Exception $e) {
            return redirect()->route('front.checkout')->with('error', 'Stripe session creation failed: ' . $e->getMessage());
        }
    }

    public function paymentSuccess(Request $request)
    {
        $sessionId = $request->query('stripe_session');
        
        if (!$sessionId) {
            return redirect()->route('front.cart')->with('error', 'Invalid payment session');
        }
        

        DB::beginTransaction();
        
        try {
            // Verify session with Stripe
            Stripe::setApiKey(env('STRIPE_SECRET'));
            $checkoutSession = StripeSession::retrieve($sessionId);

            if ($checkoutSession->payment_status !== 'paid') {
                throw new Exception('Payment not completed');
            }

            // Get temp order
            $tempOrder = TempOrder::with('items')
                ->where('id', $checkoutSession->metadata->temp_order_id)
                ->where('user_id', Auth::id())
                ->first();

            if (!$tempOrder) {
                throw new Exception('Temp order not found');
            }

            // Create final order
            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'user_id' => Auth::id(),
                'address_id' => $tempOrder->address_id,
                'subtotal' => $tempOrder->subtotal,
                'shipping_fee' => $tempOrder->shipping_fee,
                'total' => $tempOrder->total,
                'prescription_path' => $tempOrder->prescription_path,
                'notes' => $tempOrder->notes,
                'payment_method' => 'stripe',
                'payment_intent_id' => $checkoutSession->payment_intent,
                'stripe_session_id' => $sessionId,
                'order_status' => 1, // Confirmed
                'payment_status' => 1 ,// Paid
                'applied_coupon_id' => $tempOrder->applied_coupon_id,
                'coupon_discount' => $tempOrder->coupon_discount,
                'coupon_data' => $tempOrder->coupon_data
            ]);

            // Create order items
            foreach ($tempOrder->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'medicine_id' => $item->medicine_id,
                    'medicine_name' => $item->medicine_name,
                    'sku' => $item->sku,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'total' => $item->total,
                    'prescription_required' => $item->prescription_required
                ]);

                // Update stock
                \DB::table('medicines')
                    ->where('id', $item->medicine_id)
                    ->decrement('stock_quantity', $item->quantity);
            }
            if ($tempOrder->applied_coupon_id && $tempOrder->coupon_discount > 0) {
                CouponUsage::create([
                    'coupon_id' => $tempOrder->applied_coupon_id,
                    'user_id' => Auth::id(),
                    'order_id' => $order->id,
                    'discount_amount' => $tempOrder->coupon_discount
                ]);
                
                // Increment coupon usage count
                Coupon::where('id', $tempOrder->applied_coupon_id)
                    ->increment('used_count');
            }

            // Move prescription from temp to permanent
            if ($tempOrder->prescription_path) {
                $oldPath = $tempOrder->prescription_path;
                $newPath = str_replace('temp/', '', $oldPath);
                
                $config = config('global');
                $disk = $config['upload_bucket'] ?? 'public';
                $uploadDir = $config['prescription_upload_dir'] ?? 'prescriptions/';
                
                if ($disk === 's3') {
                    Storage::disk('s3')->copy($uploadDir . $oldPath, $uploadDir . $newPath);
                    Storage::disk('s3')->delete($uploadDir . $oldPath);
                } else {
                    Storage::disk('public')->copy($uploadDir . $oldPath, $uploadDir . $newPath);
                    Storage::disk('public')->delete($uploadDir . $oldPath);
                }
                
                $order->update(['prescription_path' => $newPath]);
            }

            // Clear cart
            Cart::where('user_id', Auth::id())->delete();

            // Update temp order status
            $tempOrder->update(['status' => TempOrder::STATUS_COMPLETED]);

            // Clear session
            Session::forget('temp_order_id');

            DB::commit();
            exec("php " . base_path() . "/artisan app:send-medicine-order-payment-notification " . $order->id . " > /dev/null 2>&1 & ");
            if ($order->user && $order->user->email) {
                try {
                    $user = $order->user; 
                    $mailbody = view('mail.order-confirmation', compact('order', 'user'));
                    send_email($order->user->email, "Order Confirmation - " . env('APP_NAME'), $mailbody);
                } catch (Exception $e) {
                    \Log::error('Order confirmation email failed: ' . $e->getMessage());
                }
            }

            return redirect()->route('front.order.success', ['order' => $order->id]);

        } catch (Exception $e) {
            DB::rollBack();
            
            // Update temp order status to failed
            if (isset($tempOrder)) {
                $tempOrder->update(['status' => TempOrder::STATUS_FAILED]);
            }
            
            return redirect()->route('front.checkout')->with('error', 'Payment processing failed: ' . $e->getMessage());
        }
    }

    public function paymentCancel()
    {
        $tempOrderId = Session::get('temp_order_id');
        
        if ($tempOrderId) {
            TempOrder::where('id', $tempOrderId)->update(['status' => TempOrder::STATUS_FAILED]);
            Session::forget('temp_order_id');
        }

        return redirect()->route('front.checkout')->with('error', 'Payment was cancelled');
    }

    public function success($order)
    {
        $order = Order::with(['items', 'address'])
            ->where('user_id', Auth::id())
            ->findOrFail($order);

        return view('front.order-success', compact('order'));
    }
}