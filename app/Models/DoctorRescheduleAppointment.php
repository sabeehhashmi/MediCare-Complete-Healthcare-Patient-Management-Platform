<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DoctorRescheduleAppointment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'doctor_id',
        'patient_appointment_id',
        'reschedule_patient_booking_date',
        'reschedule_patient_time_slot',
    ];
    public function doctorPatientAppointment()
    {
        return $this->belongsTo(DoctorPatientAppointment::class);
    }
}
