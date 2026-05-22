<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'title_en',
        'title_ar',
        'title_bn',
        'description',
        'type',
        'value',
        'max_discount',
        'total_uses',
        'per_user_uses',
        'used_count',
        'start_date',
        'end_date',
        'min_order_amount',
        'for_new_users_only',
        'for_first_order_only',
        'apply_on',
        'status',
        'settings',
        'created_by',
        'last_updated_by'
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'for_new_users_only' => 'boolean',
        'for_first_order_only' => 'boolean',
        'status' => 'boolean',
        'settings' => 'array'
    ];

    // Relationships
    public function products()
    {
        return $this->belongsToMany(Medicine::class, 'coupon_products', 'coupon_id', 'medicine_id');
    }

    public function categories()
    {
        return $this->belongsToMany(MedicinCategory::class, 'coupon_categories', 'coupon_id', 'medicine_category_id');
    }

    public function usages()
    {
        return $this->hasMany(CouponUsage::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'last_updated_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', true)
            ->where(function($q) {
                $q->whereNull('start_date')
                  ->orWhere('start_date', '<=', now());
            })
            ->where(function($q) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', now());
            })
            ->where(function($q) {
                $q->whereNull('total_uses')
                  ->orWhere('used_count', '<', \DB::raw('total_uses'));
            });
    }

    public function scopeValidForUser($query, $userId)
    {
        return $query->where(function($q) use ($userId) {
            $q->whereDoesntHave('usages', function($usageQuery) use ($userId) {
                $usageQuery->where('user_id', $userId);
            })->orWhereHas('usages', function($usageQuery) use ($userId) {
                $usageQuery->where('user_id', $userId)
                    ->havingRaw('COUNT(*) < per_user_uses');
            });
        });
    }

    // Helper Methods
    public function isValid()
    {
        // Check status
        if (!$this->status) {
            return false;
        }

        // Check date range
        if ($this->start_date && now()->lt($this->start_date)) {
            return false;
        }

        if ($this->end_date && now()->gt($this->end_date)) {
            return false;
        }

        // Check total uses
        if ($this->total_uses && $this->used_count >= $this->total_uses) {
            return false;
        }

        return true;
    }

    public function isValidForUser($userId, $orderTotal = null, $items = [])
    {
        // Check if coupon is active
        if (!$this->isValid()) {
            return ['valid' => false, 'message' => 'Coupon is not valid'];
        }

        // Check per user usage
        $userUsageCount = $this->usages()->where('user_id', $userId)->count();
        if ($userUsageCount >= $this->per_user_uses) {
            return ['valid' => false, 'message' => 'You have already used this coupon maximum times'];
        }

        // Check new user only
        if ($this->for_new_users_only) {
            $orderCount = Order::where('user_id', $userId)->count();
            if ($orderCount > 0) {
                return ['valid' => false, 'message' => 'This coupon is only for new users'];
            }
        }

        // Check first order only
        if ($this->for_first_order_only) {
            $orderCount = Order::where('user_id', $userId)->count();
            if ($orderCount > 0) {
                return ['valid' => false, 'message' => 'This coupon is only for first order'];
            }
        }

        // Check minimum order amount
        if ($this->min_order_amount && $orderTotal < $this->min_order_amount) {
            return ['valid' => false, 'message' => "Minimum order amount should be {$this->min_order_amount}"];
        }

        // Check product/category restrictions
        if ($this->apply_on !== 'all' && !empty($items)) {
            $applicableItems = $this->getApplicableItems($items);
            if (empty($applicableItems)) {
                return ['valid' => false, 'message' => 'Coupon is not applicable on selected items'];
            }
        }

        return ['valid' => true, 'message' => 'Coupon is valid'];
    }

    public function getApplicableItems($items)
    {
        if ($this->apply_on === 'all') {
            return $items;
        }

        $applicableItems = [];

        if ($this->apply_on === 'specific_products') {
            $productIds = $this->products->pluck('id')->toArray();
            foreach ($items as $item) {
                if (in_array($item['medicine_id'], $productIds)) {
                    $applicableItems[] = $item;
                }
            }
        }

        if ($this->apply_on === 'specific_categories') {
            $categoryIds = $this->categories->pluck('id')->toArray();
            foreach ($items as $item) {
                if (in_array($item['category_id'], $categoryIds)) {
                    $applicableItems[] = $item;
                }
            }
        }

        return $applicableItems;
    }

    public function calculateDiscount($subtotal, $items = [])
    {
        // Get applicable items for discount calculation
        $applicableItems = $this->getApplicableItems($items);
        $applicableTotal = !empty($applicableItems) 
            ? collect($applicableItems)->sum('total') 
            : $subtotal;

        if ($applicableTotal <= 0) {
            return 0;
        }

        $discount = 0;

        if ($this->type === 'fixed') {
            $discount = min($this->value, $applicableTotal);
        } else {
            $discount = ($applicableTotal * $this->value) / 100;
            if ($this->max_discount) {
                $discount = min($discount, $this->max_discount);
            }
        }

        return round($discount, 2);
    }

    public function incrementUsage($userId, $orderId = null, $discountAmount = 0)
    {
        $this->increment('used_count');
        
        return $this->usages()->create([
            'user_id' => $userId,
            'order_id' => $orderId,
            'discount_amount' => $discountAmount
        ]);
    }
}