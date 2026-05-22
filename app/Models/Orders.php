<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;
    function user() {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
    function product() {
        return $this->belongsTo('App\Models\Product', 'product_id', 'id');
    }
}
