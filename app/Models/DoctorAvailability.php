<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class DoctorAvailability extends Model
{
    use HasFactory, SoftDeletes;
     protected $primaryKey = 'doctor_id'; 
    protected $fillable = [
        'doctor_id',
        'sunday_availability',
        'sunday_time_slot',
        'monday_availability',
        'monday_time_slot',
        'tuesday_availability',
        'tuesday_time_slot',   
        'wednesday_availability',
        'wednesday_time_slot',
        'thursday_availability',
        'thursday_time_slot',
        'friday_availability',
        'friday_time_slot',
        'saturday_availability',
        'saturday_time_slot',
    ];
   
}
