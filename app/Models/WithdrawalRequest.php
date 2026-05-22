<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WithdrawalRequest extends Model
{
    protected $table = 'withdrawal_requests';
    
    protected $fillable = [
        'doctor_id',
        'amount',
        'status',
        'payment_method',
        'account_details',
        'notes',
        'admin_notes',
        'approved_by',
        'approved_at',
        'paid_at',
        'transaction_id'
    ];
    
    protected $casts = [
        'account_details' => 'array',
        'approved_at' => 'datetime',
        'paid_at' => 'datetime',
        'amount' => 'decimal:2'
    ];
    
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_PAID = 'paid';
    const STATUS_REJECTED = 'rejected';
    const STATUS_CANCELLED = 'cancelled';
    
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }
    
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}