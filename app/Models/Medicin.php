<?php
// app/Models/Medicine.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medicin extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'medicines';
    protected $appends = ['image_url', 'gallery_images_url'];
    protected $fillable = [
        'title_en',
        'title_ar',
        'title_bn',
        'slug',
        'medicin_category_id',
        'description',
        'short_description',
        'price',
        'discount_price',
        'sku',
        'stock_quantity',
        'manufacturer',
        'prescription_required',
        'image',
        'gallery_images',
        'tags',
        'uses',
        'side_effects',
        'benefits',
        'how_to_use',
        'other_info',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'status',
        'featured',
        'created_by',
        'last_updated_by'
    ];
    
    protected $casts = [
        'gallery_images' => 'array',
        'tags' => 'array',
        'prescription_required' => 'boolean',
        'featured' => 'boolean',
        'status' => 'boolean',
        'price' => 'float',
        'discount_price' => 'float'
    ];
    
    public function category()
    {
        return $this->belongsTo(MedicinCategory::class, 'medicin_category_id');
    }
    
    public function productTags()
    {
        return $this->belongsToMany(ProductTag::class, 'medicine_product_tags', 'medicine_id', 'product_tag_id');
    }
    
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return get_uploaded_image_url($this->image, 'medicine_image_upload_dir');
        }
        return asset('admin-assets/assets/images/placeholder.jpg');
    }
    
    public function getGalleryImagesUrlAttribute()
    {
        $images = [];
        
        // Get the gallery images value
        $galleryImages = $this->gallery_images;
        
        // If it's null or empty, return empty array
        if (empty($galleryImages)) {
            return $images;
        }
        
        // If it's already an array, use it directly
        if (is_array($galleryImages)) {
            foreach ($galleryImages as $image) {
                if (!empty($image)) {
                    $images[] = get_uploaded_image_url($image, 'medicine_image_upload_dir');
                }
            }
        } 
        // If it's a string, try to decode it
        elseif (is_string($galleryImages)) {
            $decoded = json_decode($galleryImages, true);
            if (is_array($decoded)) {
                foreach ($decoded as $image) {
                    if (!empty($image)) {
                        $images[] = get_uploaded_image_url($image, 'medicine_image_upload_dir');
                    }
                }
            }
        }
        
        return $images;
    }
    
    // Optional: Add mutators to ensure data is stored correctly
    public function setGalleryImagesAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['gallery_images'] = json_encode($value);
        } elseif (is_string($value) && $this->isJson($value)) {
            $this->attributes['gallery_images'] = $value;
        } else {
            $this->attributes['gallery_images'] = null;
        }
    }
    
    // Helper method to check if string is valid JSON
    private function isJson($string)
    {
        if (!is_string($string)) {
            return false;
        }
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
}