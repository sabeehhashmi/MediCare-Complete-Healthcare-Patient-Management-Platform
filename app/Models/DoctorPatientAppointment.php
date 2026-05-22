<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class DoctorPatientAppointment extends Model
{
    use HasFactory, SoftDeletes;
    const PAYMENT_STATUS_PENDING = 'pending';
    const PAYMENT_STATUS_PAID = 'paid';
    const PAYMENT_STATUS_FAILED = 'failed';
    protected $fillable = [
        'doctor_id',
        'user_id',
        'callcenter_id',
        'agent_id',
        'patient_id',
        'booking_id',
        'booking_date',
        'booking_time_slot',
        'booking_status',
        'created_by',
        'created_at',
        'updated_at',
        'hospital_id',
        'department_id',
        'is_urgent', 'payment_status', 'consultation_fee', 'admin_commission',
        'doctor_earning', 'payment_token', 'stripe_session_id', 
        'payment_completed_at', 'payment_email_sent_at', 'urgent_notified_at',
        'commission_status',
        'commission_approved_by',
        'commission_approved_at',
        'commission_payment_date',
        'commission_transaction_id',
        'commission_notes',
        'reminder_30m_sent_at',
    ];

    protected $appends = ['consent_url','formatted_consultation_fee', 'payment_status_badge'];

    protected function cast() {
        return [
            'booking_date' => 'date',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function getFormattedConsultationFeeAttribute()
    {
        return number_format($this->consultation_fee, 2) . ' AED';
    }

    public function getPaymentStatusBadgeAttribute()
    {
        $badges = [
            self::PAYMENT_STATUS_PENDING => 'warning',
            self::PAYMENT_STATUS_PAID => 'success',
            self::PAYMENT_STATUS_FAILED => 'danger',
        ];
        $class = $badges[$this->payment_status] ?? 'secondary';
        return '<span class="badge bg-' . $class . '">' . strtoupper($this->payment_status) . '</span>';
    }

    public function getLabReportAttribute($value)
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


    public function clinicalAssessment()
    {
        return $this->hasOne(ClinicalAssessmentAndDocumentation::class,'appointment_id');
    }
    
    public function clinicalSummary()
    {
        return $this->hasOne(ClinicalSummary::class,'appointment_id');
    }

    public function clinicalSummaries()
    {
        return $this->hasMany(ClinicalSummary::class, 'appointment_id', 'id');
    }

    public function doctor_reschedule_appointments()
    {
        return $this->hasMany(DoctorRescheduleAppointment::class, 'patient_appointment_id', 'id');
    }

    public function followups()
    {
        return $this->hasMany(DoctorAppointmentFollowup::class, 'appointment_id', 'id');
    }
    public function docs()
    {
        return $this->hasMany(AppointmentDoc::class, 'appointment_id', 'id');
    }

    public function labReports()
    {
        return $this->hasMany(AppointmentDoc::class, 'appointment_id', 'id')->where('type', 'lab_test');
    }

    public function xrayReports()
    {
        return $this->hasMany(AppointmentDoc::class, 'appointment_id', 'id')->where('type', 'xray');
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

    public function getConsentUrlAttribute()
    {
        if ($this->consent) {
            return get_uploaded_image_url($this->consent, 'user_image_upload_dir');
        }
    
    }

    public function commission()
    {
        return $this->hasOne(DoctorCommission::class, 'appointment_id');
    }


    public function prescription()
{
    return $this->hasOne(Prescription::class, 'appointment_id');
}

public function summaries()
{
    return $this->hasMany(ClinicalSummary::class, 'appointment_id');
}

public function clinical_assessment()
{
    return $this->hasOne(ClinicalAssessmentAndDocumentation::class, 'appointment_id');
}

}
