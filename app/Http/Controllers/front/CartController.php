<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Medicine;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Exception;

class CartController extends Controller
{
    protected function getCartIdentifier()
    {
        if (Auth::check()) {
            return ['user_id' => Auth::id()];
        }
        $session_id = Session::getId();
        return ['session_id' => $session_id];
    }

    protected function getShippingPrice()
    {
        $settings = \App\Models\SettingsModel::first();
        return $settings->shipping_price ?? 10.00;
    }

    protected function getCartItems()
    {
        if (Auth::check()) {
            return Cart::with('medicine')
                ->where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->get();
        }
        return Cart::with('medicine')
            ->where('session_id', Session::getId())
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function index()
    {
        $page_heading = "My Cart";
        $cart_items = $this->getCartItems();

         $appliedCoupon = null;
        $couponDiscount = 0;
        
        if (Auth::check()) {
            $firstItem = $cart_items->first();
            if ($firstItem && $firstItem->applied_coupon_id) {
                $appliedCoupon = [
                    'code' => $firstItem->coupon_data['code'] ?? '',
                    'discount' => $firstItem->coupon_discount ?? 0
                ];
                $couponDiscount = $firstItem->coupon_discount ?? 0;
            }
        } else {
            $sessionCoupon = Session::get('applied_coupon');
            if ($sessionCoupon) {
                $appliedCoupon = [
                    'code' => $sessionCoupon['code'] ?? '',
                    'discount' => $sessionCoupon['discount'] ?? 0
                ];
                $couponDiscount = $sessionCoupon['discount'] ?? 0;
            }
        }
        
        $subtotal = $cart_items->sum('total');
        $shipping_fee = $this->getShippingPrice();
        $total = $subtotal + $shipping_fee - $couponDiscount;
        
        return view('front.cart', compact('page_heading', 'cart_items', 'subtotal', 'shipping_fee', 'total','appliedCoupon','couponDiscount'));
    }

    public function addToCart(Request $request)
    {
        $status = "0";
        $message = "";
        $errors = [];
        $cart_count = 0;

        $rules = [
            'medicine_id' => 'required|exists:medicines,id',
            'quantity' => 'required|integer|min:1'
        ];

        $validator = validator()->make($request->all(), $rules);

        if ($validator->fails()) {
            $message = "Validation error occurred";
            $errors = $validator->messages();
        } else {
            DB::beginTransaction();
            try {
                $medicine = Medicine::find($request->medicine_id);
                
                if (!$medicine) {
                    throw new Exception("Medicine not found");
                }

                if ($medicine->stock_quantity < $request->quantity) {
                    throw new Exception("Insufficient stock. Only {$medicine->stock_quantity} items available");
                }

                // Get cart identifier
                $identifier = $this->getCartIdentifier();
                
                $cartItem = Cart::where($identifier)
                    ->where('medicine_id', $request->medicine_id)
                    ->first();

                $price = $medicine->discount_price ?? $medicine->price;
                $quantity = $request->quantity;

                if ($cartItem) {
                    $newQuantity = $cartItem->quantity + $quantity;
                    
                    if ($medicine->stock_quantity < $newQuantity) {
                        throw new Exception("Insufficient stock. Only {$medicine->stock_quantity} items available");
                    }
                    
                    $cartItem->quantity = $newQuantity;
                    $cartItem->total = $newQuantity * $price;
                    $cartItem->save();
                    $message = "Cart updated successfully";
                } else {
                    $cartData = [
                        'medicine_id' => $medicine->id,
                        'quantity' => $quantity,
                        'price' => $price,
                        'total' => $quantity * $price
                    ];
                    
                    if (Auth::check()) {
                        $cartData['user_id'] = Auth::id();
                    } else {
                        $cartData['session_id'] = Session::getId();
                    }
                    
                    Cart::create($cartData);
                    $message = "Item added to cart successfully";
                }

                DB::commit();
                $status = "1";
                $cart_count = $this->getCartItems()->count();
                
            } catch (Exception $e) {
                DB::rollback();
                $message = $e->getMessage();
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'status' => $status,
                'errors' => $errors,
                'message' => $message,
                'cart_count' => $cart_count
            ]);
        }

        return redirect()->back()->with('message', $message);
    }

    public function updateQuantity(Request $request)
    {
        $status = "0";
        $message = "";
        $item_total = 0;
        $subtotal = 0;
        $total = 0;

        $rules = [
            'cart_id' => 'required|exists:carts,id',
            'quantity' => 'required|integer|min:1'
        ];

        $validator = validator()->make($request->all(), $rules);

        if ($validator->fails()) {
            $message = "Validation error occurred";
        } else {
            DB::beginTransaction();
            try {
                $query = Cart::with('medicine')->where('id', $request->cart_id);
                
                if (Auth::check()) {
                    $query->where('user_id', Auth::id());
                } else {
                    $query->where('session_id', Session::getId());
                }
                
                $cartItem = $query->first();

                if (!$cartItem) {
                    throw new Exception("Cart item not found");
                }

                if ($cartItem->medicine->stock_quantity < $request->quantity) {
                    throw new Exception("Insufficient stock. Only {$cartItem->medicine->stock_quantity} items available");
                }

                $cartItem->quantity = $request->quantity;
                $cartItem->total = $request->quantity * $cartItem->price;
                $cartItem->save();

                $item_total = $cartItem->total;

                $cart_items = $this->getCartItems();
                $subtotal = $cart_items->sum('total');
                $appliedCoupon = null;
                $couponDiscount = 0;
                if (Auth::check()) {
                    $firstItem = $cart_items->first();
                    if ($firstItem && $firstItem->applied_coupon_id) {
                        $coupon = Coupon::find($firstItem->applied_coupon_id);
                        if ($coupon) {
                            // Prepare items array
                            $items = $cart_items->map(function($item) {
                                return [
                                    'medicine_id' => $item->medicine_id,
                                    'category_id' => $item->medicine->medicin_category_id,
                                    'price' => $item->price,
                                    'quantity' => $item->quantity,
                                    'total' => $item->total
                                ];
                            })->toArray();

                            $couponDiscount = $coupon->calculateDiscount($subtotal, $items);
                        }
                    }
                } else {
                    // Guest user
                    $sessionCoupon = Session::get('applied_coupon');
                    if ($sessionCoupon) {
                        $couponDiscount = $sessionCoupon['discount'] ?? 0;
                    }
                }
                $shipping_fee = $this->getShippingPrice();
                $total = ($subtotal - $couponDiscount) + $shipping_fee;

                DB::commit();
                $status = "1";
                $message = "Quantity updated successfully";

            } catch (Exception $e) {
                DB::rollback();
                $message = $e->getMessage();
            }
        }

        return response()->json([
            'status' => $status,
            'message' => $message,
            'item_total' => number_format($item_total, 2),
            'subtotal' => number_format($subtotal, 2),
            'coupon_discount' => number_format($couponDiscount, 2),
            'total' => number_format($total, 2)
        ]);
    }

    public function removeFromCart($id)
    {
        $status = "0";
        $message = "";

        try {
            $query = Cart::where('id', $id);
            
            if (Auth::check()) {
                $query->where('user_id', Auth::id());
            } else {
                $query->where('session_id', Session::getId());
            }
            
            $cartItem = $query->first();

            if ($cartItem) {
                $cartItem->delete();
                $status = "1";
                $message = "Item removed from cart successfully";
            } else {
                $message = "Cart item not found";
            }
        } catch (Exception $e) {
            $message = "Failed to remove item";
        }

        return response()->json(['status' => $status, 'message' => $message]);
    }

    public function clearCart()
    {
        $status = "0";
        $message = "";

        try {
            if (Auth::check()) {
                Cart::where('user_id', Auth::id())->delete();
            } else {
                Cart::where('session_id', Session::getId())->delete();
            }
            $status = "1";
            $message = "Cart cleared successfully";
        } catch (Exception $e) {
            $message = "Failed to clear cart";
        }

        return response()->json(['status' => $status, 'message' => $message]);
    }

    public function getCartCount()
    {
        $count = $this->getCartItems()->count();
        return response()->json(['count' => $count]);
    }

    public function getCartSummary()
    {
        $cart_items = $this->getCartItems();
        $subtotal = $cart_items->sum('total');
         $appliedCoupon = null;
        $couponDiscount = 0;

        if (Auth::check()) {
            $firstItem = $cart_items->first();
            if ($firstItem && $firstItem->applied_coupon_id) {
                $coupon = Coupon::find($firstItem->applied_coupon_id);
                if ($coupon) {
                    // Prepare items array
                    $items = $cart_items->map(function($item) {
                        return [
                            'medicine_id' => $item->medicine_id,
                            'category_id' => $item->medicine->medicin_category_id,
                            'price' => $item->price,
                            'quantity' => $item->quantity,
                            'total' => $item->total
                        ];
                    })->toArray();

                    $couponDiscount = $coupon->calculateDiscount($subtotal, $items);
                }
            }
        } else {
            // Guest user
            $sessionCoupon = Session::get('applied_coupon');
            if ($sessionCoupon) {
                $couponDiscount = $sessionCoupon['discount'] ?? 0;
            }
        }
        $shipping_fee = $this->getShippingPrice();
        $total = ($subtotal - $couponDiscount) + $shipping_fee;

        return response()->json([
            'items' => $cart_items,
            'subtotal' => number_format($subtotal, 2),
            'shipping_fee' => number_format($shipping_fee, 2),
            'coupon_discount' => number_format($couponDiscount, 2),
            'total' => number_format($total, 2),
            'count' => $cart_items->count()
        ]);
    }

    public function mergeGuestCart()
    {
        if (Auth::check()) {
            $session_id = Session::getId();
            $guest_carts = Cart::where('session_id', $session_id)->get();
            
            foreach ($guest_carts as $guest_cart) {
                $user_cart = Cart::where('user_id', Auth::id())
                    ->where('medicine_id', $guest_cart->medicine_id)
                    ->first();
                
                if ($user_cart) {
                    $user_cart->quantity += $guest_cart->quantity;
                    $user_cart->total = $user_cart->quantity * $user_cart->price;
                    $user_cart->save();
                    $guest_cart->delete();
                } else {
                    $guest_cart->user_id = Auth::id();
                    $guest_cart->session_id = null;
                    $guest_cart->save();
                }
            }
        }
    }


    public function applyCoupon(Request $request)
    {
        $status = "0";
        $message = "";
        $data = [];

        $validator = Validator::make($request->all(), [
            'code' => 'required|string'
        ]);

        if ($validator->fails()) {
            $message = "Please enter a coupon code";
        } else {
            try {
                $identifier = $this->getCartIdentifier();
                $cartItems = Cart::where($identifier)->with('medicine')->get();

                if ($cartItems->isEmpty()) {
                    $message = "Your cart is empty";
                    return response()->json(['status' => $status, 'message' => $message]);
                }

                $subtotal = $cartItems->sum('total');

                // Prepare items array for validation
                $items = $cartItems->map(function($item) {
                    return [
                        'medicine_id' => $item->medicine_id,
                        'category_id' => $item->medicine->medicin_category_id,
                        'price' => $item->price,
                        'quantity' => $item->quantity,
                        'total' => $item->total
                    ];
                })->toArray();

                // Find and validate coupon
                $coupon = Coupon::where('code', strtoupper($request->code))
                    ->active()
                    ->first();

                if (!$coupon) {
                    $message = "Invalid coupon code";
                } else {
                    $validation = $coupon->isValidForUser(
                        Auth::id() ?? 0,
                        $subtotal,
                        $items
                    );

                    if ($validation['valid']) {
                        $discount = $coupon->calculateDiscount($subtotal, $items);

                        // Store coupon in session for guest users or in cart for logged in
                        if (Auth::check()) {
                            // For logged in users, update all cart items with coupon
                            foreach ($cartItems as $cartItem) {
                                $cartItem->applied_coupon_id = $coupon->id;
                                $cartItem->coupon_discount = $discount;
                                $cartItem->coupon_data = [
                                    'code' => $coupon->code,
                                    'type' => $coupon->type,
                                    'value' => $coupon->value,
                                    'discount' => $discount
                                ];
                                $cartItem->save();
                            }
                        } else {
                            // For guests, store in session
                            Session::put('applied_coupon', [
                                'id' => $coupon->id,
                                'code' => $coupon->code,
                                'discount' => $discount,
                                'data' => $coupon->toArray()
                            ]);
                        }

                        $status = "1";
                        $message = "Coupon applied successfully!";
                        $shipping_fee = $this->getShippingPrice();
                        $data = [
                            'discount' => $discount,
                            'code' => $coupon->code,
                            'new_subtotal' => $subtotal - $discount,
                            'shipping_fee' => $shipping_fee,
                            'total' => ($subtotal - $discount) + $shipping_fee
                        ];
                    } else {
                        $message = $validation['message'];
                    }
                }
            } catch (Exception $e) {
                $message = "Failed to apply coupon: " . $e->getMessage();
            }
        }

        return response()->json(['status' => $status, 'message' => $message, 'data' => $data]);
    }

    public function removeCoupon(Request $request)
    {
        $status = "0";
        $message = "";

        try {
            if (Auth::check()) {
                Cart::where('user_id', Auth::id())
                    ->update([
                        'applied_coupon_id' => null,
                        'coupon_discount' => 0,
                        'coupon_data' => null
                    ]);
            } else {
                Session::forget('applied_coupon');
            }

            // Recalculate cart
            $cart_items = $this->getCartItems();
            $subtotal = $cart_items->sum('total');
            $shipping_fee = $this->getShippingPrice();
            $total = $subtotal + $shipping_fee;

            $status = "1";
            $message = "Coupon removed successfully";

            return response()->json([
                'status' => $status,
                'message' => $message,
                'subtotal' => number_format($subtotal, 2),
                'coupon_discount' => number_format(0, 2),
                'total' => number_format($total, 2)
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => "0",
                'message' => "Failed to remove coupon"
            ]);
        }
    }
}