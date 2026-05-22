<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Appointments extends Model
{

    protected $table = 'appointments';

 public function getFormattedBookingDateAttribute()
    {
        return Carbon::parse($this->booking_date)->format('d-m-Y');
    }
 public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    // App\Models\Appointment.php

public function clinicalAssessment()
{
    return $this->hasOne(ClinicalAssessmentAndDocumentation::class);
}

}
