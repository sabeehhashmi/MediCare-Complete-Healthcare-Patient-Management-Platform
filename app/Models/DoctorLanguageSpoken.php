<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class DoctorLanguageSpoken extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'language_spoken_id',
    ];
         /**
     * Get the doctor that owns the DoctorLanguageSpoken.
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function languageSpoken()
{
    return $this->belongsTo(Languages::class, 'language_spoken_id');
}
   
}
