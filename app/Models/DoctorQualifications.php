<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class DoctorQualifications extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'qualification_id',
    ];
       /**
     * Get the doctor that owns the DoctorQualifications.
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
    public function qualification()
{
    return $this->belongsTo(Qualifications::class, 'qualification_id');
}
}
