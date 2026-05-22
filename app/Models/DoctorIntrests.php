<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class DoctorIntrests extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'special_intrest_id',
    ];

    /**
     * Get the doctor that owns the DoctorIntrests.
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
    public function specialInterest()
{
    return $this->belongsTo(SpecialIntrests::class, 'special_intrest_id');
}
}
