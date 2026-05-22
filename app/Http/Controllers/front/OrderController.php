<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;

class OrderController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Display a listing of user orders
     */
    public function index()
    {
        $page_heading = "My Orders";
        
        $orders = Order::with(['items'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('front.orders', compact('page_heading', 'orders'));
    }

    /**
     * Display order details
     */
    public function show($id)
    {
        $page_heading = "Order Details";
        
        $order = Order::with(['items', 'address'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('front.order-details', compact('page_heading', 'order'));
    }

    /**
     * Cancel an order
     */
    public function cancel(Request $request, $id)
    {
        $request->validate([
            'cancellation_reason' => 'required|string|max:500'
        ]);

        DB::beginTransaction();
        
        try {
            $order = Order::where('user_id', Auth::id())->findOrFail($id);

            // Check if order can be cancelled (only pending orders)
            if ($order->order_status != 1) { // Assuming 1 = pending
                return response()->json([
                    'status' => '0',
                    'message' => 'This order cannot be cancelled at this stage.'
                ], 400);
            }

            // Update order status to cancelled
            $order->update([
                'order_status' => 6, // Assuming 5 = cancelled
                'cancellation_reason' => $request->cancellation_reason,
                'cancelled_at' => now()
            ]);

            // Restore stock if needed
            foreach ($order->items as $item) {
                Medicine::where('id', $item->medicine_id)
                    ->increment('stock_quantity', $item->quantity);
            }

            DB::commit();
            if ($order->user && $order->user->email) {
                try {
                    $user = $order->user; // Get user for the email template
                    $mailbody = view('mail.order-cancellation', compact('order', 'user'));
                    send_email($order->user->email, "Order Cancellation - " . env('APP_NAME'), $mailbody);
                } catch (Exception $e) {
                    // Log email error but don't break the cancellation process
                    \Log::error('Order cancellation email failed: ' . $e->getMessage());
                }
            }

            return response()->json([
                'status' => '1',
                'message' => 'Order cancelled successfully',
                'redirect' => route('front.orders')
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'status' => '0',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reorder previous order
     */
    public function reorder($id)
    {
        DB::beginTransaction();
        
        try {
            $previousOrder = Order::with('items')
                ->where('user_id', Auth::id())
                ->findOrFail($id);

            // Clear existing cart
            Cart::where('user_id', Auth::id())->delete();

            // Add items to cart
            foreach ($previousOrder->items as $item) {
                $medicine = Medicine::find($item->medicine_id);

                if (!$medicine) {
                    throw new Exception("Product {$item->medicine_name} is no longer available.");
                }

                if ($medicine->stock_quantity < $item->quantity) {
                    throw new Exception("Insufficient stock for {$medicine->title_en}. Only {$medicine->stock_quantity} available.");
                }

                Cart::create([
                    'user_id' => Auth::id(),
                    'medicine_id' => $medicine->id,
                    'quantity' => $item->quantity,
                    'price' => $medicine->discount_price ?? $medicine->price,
                    'total' => ($medicine->discount_price ?? $medicine->price) * $item->quantity
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => '1',
                'message' => 'Items added to cart successfully',
                'redirect' => route('front.checkout')
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'status' => '0',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Track order
     */
    public function track($id)
    {
        $order = Order::where('user_id', Auth::id())
            ->with(['items'])
            ->findOrFail($id);

        // Order status tracking steps
        $statuses = [
            1 => ['name' => 'Pending', 'icon' => 'clock', 'completed' => false],
            2 => ['name' => 'Confirmed', 'icon' => 'check-circle', 'completed' => false],
            3 => ['name' => 'Processing', 'icon' => 'refresh-cw', 'completed' => false],
            4 => ['name' => 'Dispatched', 'icon' => 'truck', 'completed' => false],
            5 => ['name' => 'Delivered', 'icon' => 'check-square', 'completed' => false]
        ];

        // Mark completed statuses
        foreach ($statuses as $key => $status) {
            if ($key <= $order->order_status) {
                $statuses[$key]['completed'] = true;
            }
        }

        return response()->json([
            'status' => '1',
            'data' => [
                'order' => $order,
                'tracking' => $statuses
            ]
        ]);
    }

    /**
     * Download invoice
     */
    public function downloadInvoice($id)
    {
      //  try {
            $order = Order::with(['items', 'address', 'user'])
                ->where('user_id', Auth::id())
                ->findOrFail($id);

            // Generate PDF invoice
            $pdf = Pdf::loadView('front.invoice', compact('order'));
            
            // Set paper size and orientation
            $pdf->setPaper('A4', 'portrait');
            
            // Stream the PDF to browser (for testing)
            return $pdf->stream('invoice-' . $order->order_number . '.pdf');

        // } catch (Exception $e) {
        //     return redirect()->back()->with('error', 'Failed to generate invoice: ' . $e->getMessage());
        // }
    }

    /**
     * Get order status text
     */
    private function getOrderStatus($status)
    {
        $statuses = [
            1 => 'Pending',
            2 => 'Confirmed',
            3 => 'Processing',
            4 => 'Dispatched',
            5 => 'Delivered',
            6 => 'Cancelled',
            7 => 'Refunded'
        ];

        return $statuses[$status] ?? 'Unknown';
    }

    /**
     * Get payment status text
     */
    private function getPaymentStatus($status)
    {
        $statuses = [
            0 => 'Pending',
            1 => 'Paid',
            2 => 'Failed',
            3 => 'Refunded'
        ];

        return $statuses[$status] ?? 'Unknown';
    }
}