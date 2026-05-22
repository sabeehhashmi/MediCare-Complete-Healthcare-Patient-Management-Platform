<?php
// app/Models/ProductTag.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductTag extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'product_tags';
    
    protected $fillable = [
        'name_en',
        'name_ar',
        'name_bn',
        'slug',
        'description',
        'color',
        'status',
        'created_by',
        'last_updated_by'
    ];
    
    protected $casts = [
        'status' => 'boolean'
    ];
    
    public function medicines()
    {
        return $this->belongsToMany(Medicine::class, 'medicine_product_tags', 'product_tag_id', 'medicine_id');
    }
}