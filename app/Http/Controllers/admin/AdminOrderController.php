<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Exception;

class AdminOrderController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Display a listing of orders
     */
    public function index(Request $request)
    {
        if (!get_user_permission('orders', 'r')) {
            return redirect()->route('admin.restricted_page');
        }

        $page_heading = "Orders";
        
        $query = Order::with(['user', 'items'])
            ->orderBy('created_at', 'desc');

        // Filter by order status
        if ($request->has('status') && $request->status != '') {
            $query->where('order_status', $request->status);
        }

        // Filter by payment status
        if ($request->has('payment_status') && $request->payment_status != '') {
            $query->where('payment_status', $request->payment_status);
        }

        // Search by order number or customer name
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'LIKE', "%{$search}%")
                               ->orWhere('email', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Date range filter
        if ($request->has('from_date') && !empty($request->from_date)) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        
        if ($request->has('to_date') && !empty($request->to_date)) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $orders = $query->paginate(15);

        // Get order status counts for dashboard
        $status_counts = [
            'all' => Order::count(),
            'pending' => Order::where('order_status', 1)->count(),
            'confirmed' => Order::where('order_status', 2)->count(),
            'processing' => Order::where('order_status', 3)->count(),
            'dispatched' => Order::where('order_status', 4)->count(),
            'delivered' => Order::where('order_status', 5)->count(),
            'cancelled' => Order::where('order_status', 6)->count(),
            'refunded' => Order::where('order_status', 7)->count(),
        ];

        return view('admin.adminorders.list', compact('page_heading', 'orders', 'status_counts'));
    }

    /**
     * View order details
     */
    public function view($id)
    {
        if (!get_user_permission('orders', 'r')) {
            return redirect()->route('admin.restricted_page');
        }

        $page_heading = "Order Details";
        
        try {
            $id = decrypt($id);
            $order = Order::with(['user', 'address', 'items.medicine'])
                ->findOrFail($id);

            // Get storage configuration for prescription
            $disk = config('global.upload_bucket') ?? 'public';
            $dir = config('global.prescription_upload_dir') ?? 'prescriptions/';
            $prescription_url = null;
            
            if ($order->prescription_path) {
                $prescription_url = Storage::disk($disk)->url($dir . $order->prescription_path);
            }

            return view('admin.adminorders.view', compact('page_heading', 'order', 'prescription_url', 'disk', 'dir'));

        } catch (Exception $e) {
            return redirect()->route('admin.orders.list')->with('error', 'Order not found');
        }
    }

    /**
     * Change order status
     */
    public function change_status(Request $request)
    {
        if (!get_user_permission('orders', 'u')) {
            return response()->json([
                'status' => '0',
                'message' => 'You do not have permission to change order status'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:orders,id',
            'status' => 'required|in:1,2,3,4,5,6,7'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => '0',
                'message' => 'Invalid data provided',
                'errors' => $validator->messages()
            ]);
        }

        DB::beginTransaction();
        
        try {
            $order = Order::with('items')->find($request->id);

            // Check if order is already delivered
            if ($order->order_status == 5) {
                return response()->json([
                    'status' => '0',
                    'message' => 'Cannot change status of a delivered order'
                ]);
            }

            // If changing to cancelled, restore stock
            if ($request->status == 6 && $order->order_status != 6) {
                foreach ($order->items as $item) {
                    if ($item->medicine_id) {
                        Medicine::where('id', $item->medicine_id)
                            ->increment('stock_quantity', $item->quantity);
                    }
                }
                $order->cancelled_at = now();
            }

            // If changing from cancelled to another status, deduct stock again
            if ($order->order_status == 6 && $request->status != 6) {
                foreach ($order->items as $item) {
                    if ($item->medicine_id) {
                        $medicine = Medicine::find($item->medicine_id);
                        if ($medicine && $medicine->stock_quantity < $item->quantity) {
                            throw new Exception("Insufficient stock for {$item->medicine_name}");
                        }
                        Medicine::where('id', $item->medicine_id)
                            ->decrement('stock_quantity', $item->quantity);
                    }
                }
                $order->cancelled_at = null;
            }

            // If changing to delivered, set delivered_at
            if ($request->status == 5 && $order->order_status != 5) {
                $order->delivered_at = now();
            }

            // Update order status
            $order->order_status = $request->status;
            $order->save();

            DB::commit();

            return response()->json([
                'status' => '1',
                'message' => 'Order status updated successfully',
                'order_status' => $order->status_text,
                'status_badge' => $order->status_badge_class
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'status' => '0',
                'message' => 'Failed to update order status: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Print invoice
     */
    public function printInvoice($id)
    {
        try {
            $id = decrypt($id);
            $order = Order::with(['user', 'address', 'items'])
                ->findOrFail($id);

            $disk = config('global.upload_bucket') ?? 'public';
            $dir = config('global.prescription_upload_dir') ?? 'prescriptions/';

            return view('admin.adminorders.invoice', compact('order', 'disk', 'dir'));

        } catch (Exception $e) {
            return redirect()->route('admin.orders.list')->with('error', 'Order not found');
        }
    }

    /**
     * Get order status options for dropdown
     */
    public function getStatusOptions()
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

        return response()->json($statuses);
    }

    /**
     * Export orders (optional)
     */
    public function export(Request $request)
    {
        // Implement export functionality if needed
    }
}