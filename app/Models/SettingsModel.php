<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class SettingsModel extends Model
{
    protected $table = "settings";
    protected $primaryKey = "id";
    public $timestamps = false;


    public $guarded = [];
     protected $appends = ['consent_url'];
   

    public function getConsentUrlAttribute()
    {
        if ($this->consent) {
            return get_uploaded_image_url($this->consent, 'user_image_upload_dir');
        }
    
    }
}
