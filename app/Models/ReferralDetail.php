<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReferralDetail extends Model
{
    use HasFactory,SoftDeletes;


    protected $table = 'refferal_details';
    
    protected $fillable = [
        'appointment_id',
        'refferal_id',
        'doctor_id',
        'reason',
        'summery',
        'reason_for_second_opinion',
        'status',
        'created_by',
        'last_updated_by',
        'department_id'
    ];

    public function refferal_doctor()
    {
        return $this->belongsTo(RefferalDoctor::class,'refferal_id');
    }
    
    public function doctor()
    {
        return $this->belongsTo(Doctor::class,'doctor_id');
    }
    public function referral()
    {
        return $this->belongsTo(Referral::class,'refferal_id');
    }
    public function department()
    {
        return $this->belongsTo(DepartmentModel::class,'department_id');
    }

}
