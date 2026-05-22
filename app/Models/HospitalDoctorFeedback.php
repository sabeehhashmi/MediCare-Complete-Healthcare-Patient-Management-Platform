<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class HospitalDoctorFeedback extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        
        'user_id',
        'hospital_id',
        'doctor_id',
        'feeback_message',
        'rating',
        'appointment_id',
        'status'
        
    ];

     public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

      public function hospital()
    {
        return $this->belongsTo(Hospital::class,'hospital_id');
    } 

     public function doctor()
    {
        return $this->belongsTo(Doctor::class,'doctor_id');
    } 
    
    public function appointment()
    {
        return $this->belongsTo(DoctorPatientAppointment::class,'appointment_id');
    }
}
