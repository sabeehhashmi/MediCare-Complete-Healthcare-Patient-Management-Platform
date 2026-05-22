<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class HpSlide extends Model
{
    use HasFactory;

    public $fillable = [
        'image',
        'status'
    ];

    // image attribute change value
    public function getImageAttribute($value)
    {
        return Storage::disk(config('global.upload_bucket'))->url(config('global.homepage_image_upload_dir') . $value);
        //return get_uploaded_image_url($value, config('global.homepage_image_upload_dir'));
    }
}
