<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorAppointmentFollowup extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'doctor_appointment_followups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'appointment_id',
        'doctor_id',
        'followup_date',
        'notes',
    ];

    /**
     * Get the appointment that owns the followup.
     */
    public function appointment()
    {
        return $this->belongsTo(DoctorPatientAppointment::class, 'id', 'appointment_id');
    }

    /**
     * Get the doctor that owns the followup.
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
