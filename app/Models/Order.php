<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'address_id',
        'subtotal',
        'shipping_fee',
        'total',
        'prescription_path',
        'notes',
        'cancellation_reason',
        'cancelled_at',
        'delivered_at',
        'payment_method',
        'payment_intent_id',
        'stripe_session_id',
        'order_status',
        'payment_status',
        'applied_coupon_id',
        'coupon_discount',
        'coupon_data'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'total' => 'decimal:2',
        'order_status' => 'integer',
        'payment_status' => 'integer',
        'cancelled_at' => 'datetime',
        'delivered_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'coupon_discount' => 'decimal:2',
        'coupon_data' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusTextAttribute()
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
        return $statuses[$this->order_status] ?? 'Unknown';
    }

    public function getPaymentStatusTextAttribute()
    {
        $statuses = [
            0 => 'Pending',
            1 => 'Paid',
            2 => 'Failed',
            3 => 'Refunded'
        ];
        return $statuses[$this->payment_status] ?? 'Unknown';
    }

    public function getStatusBadgeClassAttribute()
    {
        $classes = [
            1 => 'bg-warning',
            2 => 'bg-info',
            3 => 'bg-primary',
            4 => 'bg-success',
            5 => 'bg-success',
            6 => 'bg-danger',
            7 => 'bg-secondary'
        ];
        return $classes[$this->order_status] ?? 'bg-secondary';
    }
}