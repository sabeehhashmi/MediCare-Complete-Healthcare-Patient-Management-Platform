<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hospital extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name_en', 
        'name_ar',
        'agent_id',
        'user_id',
        'country_id',
        'emirate_id',
        'area_id',
        'address',
        'website',
        'profile_description',
        'trade_licenece',
    ];
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($hospital) {
            $hospital->hospital_patient_appointments()->delete();
            $hospital->doctors()->delete();
            $hospital->images()->delete();
            $hospital->insurences()->delete();
        });
    }

    protected $appends = ['trade_licence_url'];

    public function getTradeLicenceUrl($value){
        if($value){
            return get_uploaded_image_url($value,'trade_licenece_image_upload_dir');
        }
    }

    public function emirate()
    {
        return $this->belongsTo(Emirate::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }
   
    public function country()
    {
        return $this->belongsTo(CountryModel::class);
    }
    public function location()
    {
        return $this->hasMany(HospitalLocation::class);
    }
    public function locations()
    {
        return $this->hasMany(HospitalLocation::class);
    }
    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }
    public function images()
    {
        return $this->hasMany(HospitalImage::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function specialities()
    {
        return $this->belongsToMany(Specialty::class, 'hospital_specialities');
    }

    public function services()
    {
        return $this->belongsToMany(Services::class, 'hospital_services', 'hospital_id', 'service_id');
    }

    public function departments()
    {
        return $this->belongsToMany(DepartmentModel::class, 'department_hospital', 'hospital_id', 'department_id')
                    ->where('status', 1);
    }

    public function getTradeLicenceUrlAttribute()
    {
        return $this->getTradeLicenceUrl($this->trade_licenece);
    }

    public function insurences()
    {
        return $this->hasMany(HospitalInsurancePolicy::class);
    }

    public function hospital_patient_appointments()
    {
        return $this->hasMany(DoctorPatientAppointment::class, 'hospital_id', 'id');
    }
    
    public function hospital_specialities()
    {
        return $this->hasMany(HospitalSpecialities::class, 'hospital_id', 'id');
    }
}
