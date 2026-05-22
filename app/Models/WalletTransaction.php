<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    protected $table = 'wallet_transactions';
    
    protected $fillable = [
        'doctor_id',
        'appointment_id',
        'withdrawal_id',
        'type',
        'amount',
        'status',
        'description',
        'reference_id',
        'transaction_date',
        'created_by'
    ];
    
    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'datetime'
    ];
    
    const TYPE_CREDIT = 'credit';
    const TYPE_DEBIT = 'debit';
    
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';
    
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }
    
    public function appointment()
    {
        return $this->belongsTo(DoctorPatientAppointment::class, 'appointment_id');
    }
    
    public function withdrawal()
    {
        return $this->belongsTo(WithdrawalRequest::class, 'withdrawal_id');
    }
}