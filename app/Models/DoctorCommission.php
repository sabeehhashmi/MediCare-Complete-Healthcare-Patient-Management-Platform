<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorCommission extends Model
{
    use HasFactory;
    
    protected $table = 'doctor_commissions';
    
    protected $fillable = [
        'doctor_id',
        'appointment_id',
        'consultation_fee',
        'admin_commission',
        'doctor_earning',
        'status', // pending, approved, paid, rejected
        'payment_date',
        'transaction_id',
        'notes',
        'approved_by',
        'approved_at'
    ];
    
    protected $casts = [
        'payment_date' => 'date',
        'approved_at' => 'datetime'
    ];
    
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
    
    public function appointment()
    {
        return $this->belongsTo(DoctorPatientAppointment::class, 'appointment_id');
    }
    
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}