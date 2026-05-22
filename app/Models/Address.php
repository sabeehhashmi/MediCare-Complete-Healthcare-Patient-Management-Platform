<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'location_name',
        'plot_office_no',
        'building_name',
        'emirates',
        'locality',
        'name',
        'mobile_number',
        'address_type',
        'latitude',
        'longitude',
        'is_default'
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function tempOrders()
    {
        return $this->hasMany(TempOrder::class);
    }

    public function getFullAddressAttribute()
    {
        return "{$this->plot_office_no}, {$this->building_name}, {$this->locality}, {$this->emirates}";
    }
}