<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HospitalInsurancesModel extends Model
{
    use HasFactory;
    protected $table = 'hospital_insurances';
    protected $fillable = [
        'hospital_id',
        'insurance_id',
        'sub_insurance_id',
    ];
    

   

   
}
