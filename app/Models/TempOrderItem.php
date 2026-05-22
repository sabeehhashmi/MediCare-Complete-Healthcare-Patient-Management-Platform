<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempOrderItem extends Model
{
    use HasFactory;

    protected $table = 'temp_order_items';

    protected $fillable = [
        'temp_order_id',
        'medicine_id',
        'medicine_name',
        'sku',
        'price',
        'quantity',
        'total',
        'prescription_required',
        'medicine_details'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'total' => 'decimal:2',
        'quantity' => 'integer',
        'prescription_required' => 'boolean',
        'medicine_details' => 'array'
    ];

    public function tempOrder()
    {
        return $this->belongsTo(TempOrder::class);
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}