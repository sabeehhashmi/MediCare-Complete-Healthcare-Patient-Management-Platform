<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HospitalImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'hospital_id', 
        'image_name',
        'created_at',
        'updated_at'
    ];

    protected $appends = ['image_url'];

    public function getImageUrl($value){
        if($value){
            return get_uploaded_image_url($value,'hospital_image_upload_dir');
        }
    }

    public function hospital()
{
    return $this->belongsTo(Hospital::class);
}
    public function getImageUrlAttribute()
    {
        return $this->getImageUrl($this->image_name);
    }
}
