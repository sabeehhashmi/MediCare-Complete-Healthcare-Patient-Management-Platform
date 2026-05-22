<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DoctorSpecialities extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'speciality_id',
    ];


    /**
     * Get the doctor that owns the DoctorSpecialities.
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
    public function speciality()
{
    return $this->belongsTo(Specialty::class, 'speciality_id');
}
}
