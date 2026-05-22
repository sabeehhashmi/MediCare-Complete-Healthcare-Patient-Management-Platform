<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'carts';

    protected $fillable = [
        'user_id',
        'session_id',
        'medicine_id',
        'quantity',
        'price',
        'total',
        'applied_coupon_id',
        'coupon_discount',
        'coupon_data'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'total' => 'decimal:2',
        'coupon_discount' => 'decimal:2',
        'coupon_data' => 'array'
    ];

    public function appliedCoupon()
    {
        return $this->belongsTo(Coupon::class, 'applied_coupon_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}