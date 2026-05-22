<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ContactUsEntry extends Model
{
    protected $fillable = ['name', 'email', 'phone', 'subject', 'message','file'];

    public function getFileAttribute($value)
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
