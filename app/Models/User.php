<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use App\Scopes\ActiveUserScope;
class User extends Authenticatable
{
    use HasFactory, Notifiable,HasApiTokens;


    protected $appends = ['user_img_url','formatted_patient_id'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'email_verification_token',
        'email_verified_at',
        'password',
        'role',
        'first_name',
        'last_name',
        'role_id',
        'active',
        'verified',
        'phone',
        'dial_code',
        'created_at',
        'updated_at',
        'patient_id',
        'identification_document',
        'identification_type',
        'identification_number',
        'aprroval_status',
        'reject_reason',
        'enable_reminder_notification',
        'enable_public_notification',
        'enable_lab_result_notification',
        'enable_payment_notification',
        'enable_prescription_notification',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    // protected static function booted()
    // {
    //     static::addGlobalScope(new ActiveUserScope);
    // }
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */

    // app/Models/User.php

public function getRoleNameAttribute()
{
    switch ($this->role) {
        case DOCTOR_ROLE:
            return 'Doctor';
        case HOSPITAL_ROLE:
            return 'Hospital';
        case CLINIC_ROLE:
            return 'Clinic';
        case AGENT_ROLE:
            return 'Agent';
        case CALL_CENTER_ROLE:
            return 'Call Center';
        default:
            return 'User';
    }
}
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    function user_role() {
        return $this->belongsTo('App\Models\Role', 'role_id', 'id');
    }

    public function getFormattedPatientIdAttribute()
    {
        return $this->patient_id ?? 'N/A';
    }

    public function isHospital()
    {
        return $this->role == 5 && $this->user_type_id == 5;
    }

    public static function update_password($id,$password){
        return DB::table("users")->where("id",'=',$id)->update(['password' =>bcrypt($password)]);
    }
    public function getStatusText()
    {
        if ($this->active == 1) {
            return 'Active';
        } else {
            return 'Inactive';
        }
    }

    public function getUserImgUrlAttribute()
    {
        if ($this->user_image) {
            return get_uploaded_image_url($this->user_image, 'user_image_upload_dir');
        }
        if($this->role == DOCTOR_ROLE){
            return asset('admin-assets/assets/images/doctor_placeholder.jpg');
        }
        if($this->role == HOSPITAL_ROLE || $this->role == CLINIC_ROLE){
            return asset('admin-assets/assets/images/hospital_placeholder.jpg');    
        }
        if($this->role == AGENT_ROLE){
            return asset('admin-assets/assets/images/agent_placeholder.jpg');    
        }
        if($this->role == CALL_CENTER_ROLE){
            return asset('admin-assets/assets/images/callcenter_placeholder.jpg');    
        }
        
        return asset('admin-assets/assets/images/placeholder.jpg');
    }

    public function getIdentificationDocumentUrlAttribute()
    {
        if ($this->identification_document) {
            // Use the same pattern as user_image but with documents directory
            return get_uploaded_image_url($this->identification_document, 'user_documents_dir');
        }
        return null;
    }

    /**
     * Get the document type label (human readable)
     */
    public function getIdentificationTypeLabelAttribute()
    {
        $types = [
            'national_id' => 'National ID (Emirates ID)',
            'passport' => 'Passport',
            'driving_license' => 'Driving License',
            'other' => 'Other'
        ];
        
        return $types[$this->identification_type] ?? ucfirst(str_replace('_', ' ', $this->identification_type));
    }

    /**
     * Check if user has uploaded identification document
     */
    public function hasIdentificationDocument()
    {
        return !is_null($this->identification_document);
    }

    /**
     * Get document file extension
     */
    public function getIdentificationDocumentExtensionAttribute()
    {
        if ($this->identification_document) {
            return pathinfo($this->identification_document, PATHINFO_EXTENSION);
        }
        return null;
    }

    /**
     * Check if document is an image
     */
    public function getIsIdentificationDocumentImageAttribute()
    {
        $ext = $this->identification_document_extension;
        return in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
    }

    /**
     * Check if document is a PDF
     */
    public function getIsIdentificationDocumentPdfAttribute()
    {
        $ext = $this->identification_document_extension;
        return strtolower($ext) === 'pdf';
    }


    public function agentDetails()
    {
        // belongs to relationship
        return $this->hasOne(AgentUserDetail::class, 'user_id', 'id');
    }
    
    public function hospital()
    {
        return $this->hasOne(Hospital::class, 'user_id', 'id');
    }
    // public function call_center()
    // {
    //     return $this->hasOne(CallCenterUserDetail::class, 'user_id', 'id');
    // }
    public function agent()
    {
        return $this->hasOne(AgentUserDetail::class, 'user_id', 'id');
    }
    public function doctor()
    {
        return $this->hasOne(Doctor::class, 'user_id', 'id');
    }
    public function callCenterDetails()
    {
        return $this->hasOne(CallCenterUserDetail::class, 'user_id', 'id');
    }
    public function members()
    {
        return $this->hasMany(Members::class, 'user_id', 'id');
    }
    public function insurence_policy(){
        return $this->belongsTo(InsurencePolicy::class,'insurence_id','id');
    }
    public function sub_insurence_policy(){
        return $this->belongsTo(SubInsurencePolicy::class,'sub_insurence_id','id');
    }
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
    
    
}
