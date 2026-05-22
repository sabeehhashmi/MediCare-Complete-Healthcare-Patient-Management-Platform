<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

class Doctor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        // 'name_en',
        // 'name_ar',
        'user_id',
        'agent_id',
        'callcenter_id',
        'hospital_id',
        'year_of_experiance',
        'licence_no',
        'country_id' ,
        'licenece_type_id',
        'country_of_orgin',
        'gender',
        'insurence_id',
        'sub_insurence_id',
        'profile_desciription',
        'appointment_dial_code',
        'appointment_phone',
        'signature'
    ];

    protected $appends = ['user_signature','average_rating', 'total_reviews'];
   
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($doctor) {
            $doctor->doctor_patient_appointments()->delete();
            // $doctor->images()->delete();
        });
    }
 
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function agent()
    {
        return $this->belongsTo(AgentUserDetail::class,'agent_id','id');
    }
    

    public function doctor_patient_appointments()
    {
        return $this->hasMany(DoctorPatientAppointment::class, 'doctor_id', 'id');
    }

    public function appointments()
    {
        return $this->hasMany(DoctorPatientAppointment::class, 'doctor_id', 'id');
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function qualification()
    {
        return $this->belongsTo(Qualifications::class);
    }
    
    public function qualifications()
    {
        return $this->belongsToMany(Qualifications::class, 'doctor_qualifications', 'doctor_id', 'qualification_id');
    }

    public function licenseType()
    {
        return $this->belongsTo(LicenceType::class);
    }

    public function country()
    {
        return $this->belongsTo(CountryOfOrigin::class)->select(['id','name','status as active','name_ar'])->WithDefault(['id'=>"0",'name'=>'','active'=>'','name_ar'=>'','prefix'=>'','dial_code'=>'']);
    }

    public function insurance()
    {
        return $this->belongsTo(InsurencePolicy::class);
    }

    public function subInsurance()
    {
        return $this->belongsTo(SubInsurencePolicy::class, 'sub_insurence_id');
    }

    public function specialities(): BelongsToMany
    {
        return $this->belongsToMany(Specialty::class, 'doctor_specialities', 'doctor_id', 'speciality_id');
    }
    
    public function interests(): BelongsToMany
    {
        return $this->belongsToMany(SpecialIntrests::class, DoctorIntrests::class, 'doctor_id', 'special_intrest_id');
    }

    public function intrests(): BelongsToMany
    {
        return $this->belongsToMany(SpecialIntrests::class, DoctorIntrests::class, 'doctor_id', 'special_intrest_id');
    }
    
    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(DepartmentModel::class, 'department_doctors', 'doctor_id', 'department_id');
    }
    
    public function availability()
    {
        return $this->hasOne(DoctorAvailability::class);
    }

    /**
     * Get the DoctorIntrests for the Doctor.
     */
    public function doctorIntrests()
    {
        return $this->hasMany(DoctorIntrests::class);
    }
       /**
     * Get the DoctorIntrests for the Doctor.
     */
    public function doctorSpecialities()
    {
        return $this->hasMany(DoctorSpecialities::class);
    }
       /**
     * Get the DoctorLanguageSpoken for the Doctor.
     */
    public function doctorLanguageSpoken()
    {
        return $this->hasMany(DoctorLanguageSpoken::class);
    }
    
    public function languages()
    {
        return $this->belongsToMany(Languages::class, DoctorLanguageSpoken::class, 'doctor_id', 'language_spoken_id');
    }
       /**
     * Get the DoctorQualifications for the Doctor.
     */
    public function doctorQualifications()
    {
        return $this->hasMany(DoctorQualifications::class);
    }
    
    public function doctorHolidays()
    {
        return $this->hasMany(DoctorHolidays::class);
    }
    
    public function doctorInstantAppointment()
    {
        return $this->hasMany(DoctorInstantAppointment::class);
    }
    public function instantAppointments()
    {
        return $this->hasMany(DoctorInstantAppointment::class);
    }

    public function doctorInstantAppointmentToday()
    {
        return $this->hasOne(DoctorInstantAppointment::class)->whereDate('instant_appointment_date', Carbon::today());
    }

    public function getUserSignatureAttribute()
    {
        if ($this->signature) {
            return get_uploaded_image_url($this->signature, 'user_image_upload_dir');
        }
    
    }

    public function feedbacks()
    {
        return $this->hasMany(HospitalDoctorFeedback::class)
                    ->where('status', 1);
    }

    public function allFeedbacks()
    {
        return $this->hasMany(HospitalDoctorFeedback::class, 'doctor_id');
    }

    // Get average rating attribute
    public function getAverageRatingAttribute()
    {
        $avg = $this->allFeedbacks()
            ->where('status', 1)
            ->avg('rating');
        return $avg ? round($avg, 1) : 0;
    }

    // Get total reviews count attribute
    public function getTotalReviewsAttribute()
    {
        return $this->allFeedbacks()
            ->where('status', 1)
            ->count();
    }

    public function documents()
{
    return $this->hasMany(DoctorDocument::class);
}

}
