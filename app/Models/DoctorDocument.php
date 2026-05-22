<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorDocument extends Model
{
    protected $fillable = [
        'doctor_id',
        'title',
        'document'
    ];

    public function getDocumentAttribute($value)
    {
        
        if($value)
        {
         return get_uploaded_image_url($value,'user_image_upload_dir');
           // return asset($value);
        }
        else
        {
            return '';
        }
    }
}
