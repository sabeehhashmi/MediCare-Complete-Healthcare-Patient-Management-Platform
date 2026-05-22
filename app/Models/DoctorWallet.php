<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorWallet extends Model
{
    protected $table = 'doctor_wallets';
    
    protected $fillable = [
        'doctor_id',
        'total_earned',
        'total_withdrawn',
        'current_balance',
        'pending_balance',
        'last_updated_at'
    ];
    
    protected $casts = [
        'total_earned' => 'decimal:2',
        'total_withdrawn' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'pending_balance' => 'decimal:2',
        'last_updated_at' => 'datetime'
    ];
    
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }
}