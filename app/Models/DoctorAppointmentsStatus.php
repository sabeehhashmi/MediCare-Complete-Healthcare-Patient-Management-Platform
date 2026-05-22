<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorAppointmentsStatus extends Model
{
    use HasFactory;

    protected $table = 'doctor_appointments_statuses';

    protected $fillable = [
        'appointment_id',
        'status',
        'changed_by',
        'changed_at',
    ];

    public function appointment()
    {
        return $this->belongsTo(DoctorPatientAppointment::class, 'appointment_id');
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
    public function changed_by()
{
    return $this->belongsTo(User::class, 'changed_by');
}
}
