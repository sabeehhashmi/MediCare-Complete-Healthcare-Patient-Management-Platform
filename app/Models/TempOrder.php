<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempOrder extends Model
{
    use HasFactory;

    protected $table = 'temp_orders';

    protected $fillable = [
        'order_number',
        'user_id',
        'address_id',
        'subtotal',
        'shipping_fee',
        'total',
        'prescription_path',
        'notes',
        'payment_method',
        'payment_intent_id',
        'session_id',
        'status',
        'cart_data',
        'applied_coupon_id',
        'coupon_discount',
        'coupon_data'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'total' => 'decimal:2',
        'status' => 'integer',
        'cart_data' => 'array',
        'coupon_discount' => 'decimal:2',
        'coupon_data' => 'array'
    ];

    const STATUS_PENDING = 0;
    const STATUS_PROCESSING = 1;
    const STATUS_COMPLETED = 2;
    const STATUS_FAILED = 3;

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
        return $this->hasMany(TempOrderItem::class);
    }
}