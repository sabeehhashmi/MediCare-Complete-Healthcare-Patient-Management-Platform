<?php
// app/Http/Controllers/Admin/CouponController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\MedicinCategory;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Exception;

class CouponController extends Controller
{
    public function index()
    {
        if (!get_user_permission('coupons', 'r')) {
            return redirect()->route('admin.restricted_page');
        }

        $page_heading = "Coupons";
        $list = Coupon::withCount('usages')
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.coupons.list', compact('page_heading', 'list'));
    }

    public function create($id = '')
    {
        if (!get_user_permission('coupons', 'c')) {
            return redirect()->route('admin.restricted_page');
        }

        $page_heading = 'Coupon';
        $coupon = null;
        $selected_products = [];
        $selected_categories = [];

        if ($id) {
            $id = decrypt($id);
            $coupon = Coupon::with(['products', 'categories'])->find($id);
            if ($coupon) {
                $selected_products = $coupon->products->pluck('id')->toArray();
                $selected_categories = $coupon->categories->pluck('id')->toArray();
            }
        }

        $products = Medicine::where('status', 1)
            ->orderBy('title_en', 'asc')
            ->get(['id', 'title_en', 'sku']);

        $categories = MedicinCategory::where('status', 1)
            ->orderBy('title', 'asc')
            ->get();

        return view('admin.coupons.create', compact(
            'page_heading', 
            'id', 
            'coupon', 
            'products', 
            'categories',
            'selected_products',
            'selected_categories'
        ));
    }

    public function submit(Request $request)
    {
        $status = "0";
        $message = "";
        $errors = [];
        $o_data = ['redirect' => route('admin.coupons.list')];

        $rules = [
            'code' => [
                'required',
                'min:3',
                'max:50',
                'regex:/^[A-Za-z0-9\-_]+$/',
                Rule::unique('coupons', 'code')
                    ->ignore($request->id)
                    ->whereNull('deleted_at'),
            ],
            'title_en' => 'required|min:2',
            'title_ar' => 'nullable',
            'title_bn' => 'nullable',
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'max_discount' => 'nullable|required_if:type,percentage|numeric|min:0',
            'total_uses' => 'nullable|integer|min:1',
            'per_user_uses' => 'required|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'min_order_amount' => 'nullable|numeric|min:0',
            'apply_on' => 'required|in:all,specific_products,specific_categories',
            'status' => 'required|in:0,1'
        ];

        // Conditional validation based on apply_on
        if ($request->apply_on === 'specific_products') {
            $rules['products'] = 'required|array|min:1';
            $rules['products.*'] = 'exists:medicines,id';
        }

        if ($request->apply_on === 'specific_categories') {
            $rules['categories'] = 'required|array|min:1';
            $rules['categories.*'] = 'exists:medicine_categories,id';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occurred";
            $errors = $validator->messages();
        } else {
            DB::beginTransaction();
            try {
                $data = [
                    'code' => strtoupper($request->code),
                    'title_en' => $request->title_en,
                    'title_ar' => $request->title_ar,
                    'title_bn' => $request->title_bn,
                    'description' => $request->description,
                    'type' => $request->type,
                    'value' => $request->value,
                    'max_discount' => $request->type === 'percentage' ? $request->max_discount : null,
                    'total_uses' => $request->total_uses,
                    'per_user_uses' => $request->per_user_uses ?? 1,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'min_order_amount' => $request->min_order_amount,
                    'for_new_users_only' => $request->for_new_users_only ?? 0,
                    'for_first_order_only' => $request->for_first_order_only ?? 0,
                    'apply_on' => $request->apply_on,
                    'status' => $request->status,
                    'settings' => [
                        'exclude_discounted' => $request->exclude_discounted ?? false,
                        'stackable' => $request->stackable ?? false,
                    ],
                    'last_updated_by' => Auth::user()->id ?? 0
                ];

                if ($request->id) {
                    $coupon = Coupon::find($request->id);
                    if (!$coupon) {
                        throw new Exception("Coupon not found");
                    }
                    
                    $coupon->update($data);

                    // Sync products/categories
                    if ($request->apply_on === 'specific_products') {
                        $coupon->products()->sync($request->products ?? []);
                        $coupon->categories()->sync([]);
                    } elseif ($request->apply_on === 'specific_categories') {
                        $coupon->products()->sync([]);
                        $coupon->categories()->sync($request->categories ?? []);
                    } else {
                        $coupon->products()->sync([]);
                        $coupon->categories()->sync([]);
                    }

                    $message = "Coupon updated successfully";
                } else {
                    $data['created_by'] = Auth::user()->id ?? 0;
                    $coupon = Coupon::create($data);

                    // Attach products/categories
                    if ($request->apply_on === 'specific_products' && $request->has('products')) {
                        $coupon->products()->attach($request->products);
                    }

                    if ($request->apply_on === 'specific_categories' && $request->has('categories')) {
                        $coupon->categories()->attach($request->categories);
                    }

                    $message = "Coupon added successfully";
                }

                DB::commit();
                $status = "1";

            } catch (Exception $e) {
                DB::rollback();
                $message = "Failed to save coupon: " . $e->getMessage();
            }
        }

        return response()->json([
            'status' => $status, 
            'errors' => $errors, 
            'message' => $message, 
            'oData' => $o_data
        ]);
    }

    public function change_status(Request $request)
    {
        $status = "0";
        $message = "";
        $id = $request->id;

        $coupon = Coupon::find($id);
        if ($coupon) {
            $coupon->status = $request->status;
            $coupon->save();
            $status = "1";
            $message = "Status changed successfully";
        } else {
            $message = "Failed to change status";
        }

        return response()->json(['status' => $status, 'message' => $message]);
    }

    public function delete($id)
    {
        $status = "0";
        $message = "";

        try {
            $id = decrypt($id);
            $coupon = Coupon::find($id);

            if ($coupon) {
                // Check if coupon has been used
                if ($coupon->usages()->count() > 0) {
                    $message = "Cannot delete coupon that has been used";
                } else {
                    $coupon->delete();
                    $message = "Coupon deleted successfully";
                    $status = "1";
                }
            } else {
                $message = "Coupon not found";
            }
        } catch (Exception $e) {
            $message = "Invalid ID format";
        }

        return response()->json(['status' => $status, 'message' => $message]);
    }

    public function report(Request $request)
    {
        

        $page_heading = "Coupon Usage Report";

        $query = DB::table('coupon_usages')
            ->join('coupons', 'coupons.id', '=', 'coupon_usages.coupon_id')
            ->join('users', 'users.id', '=', 'coupon_usages.user_id')
            ->leftJoin('orders', 'orders.id', '=', 'coupon_usages.order_id')
            ->select(
                'coupons.code',
                'coupons.title_en as coupon_name',
                'coupon_usages.created_at as used_at',
                'coupon_usages.discount_amount',
                'users.name as user_name',
                'users.email as user_email',
                'orders.order_number',
                'orders.id as order_id',
            );

        // Apply filters
        if ($request->filled('coupon_id')) {
            $query->where('coupon_usages.coupon_id', $request->coupon_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('coupon_usages.created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('coupon_usages.created_at', '<=', $request->date_to);
        }

        if ($request->filled('user')) {
            $query->where(function($q) use ($request) {
                $q->where('users.name', 'like', '%' . $request->user . '%')
                  ->orWhere('users.email', 'like', '%' . $request->user . '%');
            });
        }

        $usages = $query->orderBy('coupon_usages.created_at', 'desc')->get();

        // Get summary statistics
        $summary = [
            'total_discount' => $usages->sum('discount_amount'),
            'total_uses' => $usages->count(),
            'unique_coupons' => $usages->unique('code')->count(),
            'unique_users' => $usages->unique('user_email')->count(),
        ];

        $coupons = Coupon::orderBy('code')->get(['id', 'code', 'title_en']);

        return view('admin.coupons.report', compact(
            'page_heading', 
            'usages', 
            'summary', 
            'coupons',
            'request'
        ));
    }

    public function validateCoupon(Request $request)
    {
        $status = "0";
        $message = "";
        $data = [];

        $validator = Validator::make($request->all(), [
            'code' => 'required|string',
            'subtotal' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            $message = "Validation error occurred";
        } else {
            $coupon = Coupon::where('code', strtoupper($request->code))
                ->active()
                ->first();

            if (!$coupon) {
                $message = "Invalid coupon code";
            } else {
                $validation = $coupon->isValidForUser(
                    Auth::id(), 
                    $request->subtotal,
                    $request->items ?? []
                );

                if ($validation['valid']) {
                    $discount = $coupon->calculateDiscount(
                        $request->subtotal,
                        $request->items ?? []
                    );

                    $status = "1";
                    $message = "Coupon applied successfully";
                    $data = [
                        'coupon_id' => $coupon->id,
                        'code' => $coupon->code,
                        'discount' => $discount,
                        'type' => $coupon->type,
                        'value' => $coupon->value,
                    ];
                } else {
                    $message = $validation['message'];
                }
            }
        }

        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ]);
    }
}