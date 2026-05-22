<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Members extends Model
{
    use HasFactory,SoftDeletes;
    public $hidden = ['deleted_at'];
    protected $appends = ['user_img_url'];

    public function getUserImgUrlAttribute()
    {
        if ($this->user_image) {
            return get_uploaded_image_url($this->user_image, 'user_image_upload_dir');
        }
        return asset('admin-assets/assets/images/placeholder.jpg');
    }

    public function insurence_policy(){
        return $this->belongsTo(InsurencePolicy::class,'insurence_id','id');
    }
    public function sub_insurence_policy(){
        return $this->belongsTo(SubInsurencePolicy::class,'sub_insurence_id','id');
    }
    
    public function user(){
        return $this->belongsTo(User::class);
    }
    
    public function appointments()
    {
        return $this->hasMany(DoctorPatientAppointment::class, 'user_id','id');
    }

}
