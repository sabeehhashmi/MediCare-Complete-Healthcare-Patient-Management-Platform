<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class AppointmentDoc extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'docment',
        'type'
    ];

    public function getDocmentAttribute($value)
    {
        if($value)
        {
         return get_uploaded_image_url($value,'appointment');
           // return asset($value);
        }
        else
        {
            return '';
        }
    }


    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function department()
    {
        return $this->belongsTo(DepartmentModel::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function created_by_user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function agent()
    {
        return $this->belongsTo(AgentUserDetail::class, 'agent_id');
    }
    public function member()
    {
        return $this->belongsTo(Members::class, 'member_id');
    }
    public function feedback()
    {
        return $this->hasOne(HospitalDoctorFeedback::class, 'appointment_id','id');
    }



    public function doctor_reschedule_appointments()
    {
        return $this->hasMany(DoctorRescheduleAppointment::class, 'patient_appointment_id', 'id');
    }

    public function followups()
    {
        return $this->hasMany(DoctorAppointmentFollowup::class, 'appointment_id', 'id');
    }

    public function status_history()
    {
        return $this->hasMany(DoctorAppointmentsStatus::class, 'appointment_id', 'id')->with('changedBy')->orderBy('changed_at', 'desc');
    }

    public function latestStatus()
    {
        return $this->hasOne(DoctorAppointmentsStatus::class, 'appointment_id')
                    ->latestOfMany('changed_at');
    }
    // public function doctorIntrests()
    // {
    //     return $this->hasMany(DoctorInterest::class);
    // }

}
