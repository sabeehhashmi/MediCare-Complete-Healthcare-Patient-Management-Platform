<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HospitalInsurancePolicy extends Model
{
    use HasFactory;

    protected $fillable = [
        'hospital_id',
        'insurance_id',
        'sub_insurance_id',
    ];

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function insurance()
    {
        return $this->belongsTo(InsurencePolicy::class);
    }

    public function subInsurance()
    {
        return $this->belongsTo(SubInsurencePolicy::class);
    }
}
