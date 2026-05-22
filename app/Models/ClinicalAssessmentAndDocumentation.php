<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClinicalAssessmentAndDocumentation extends Model
{
    use HasFactory,SoftDeletes;


    protected $table = 'clinical_assessment_and_documentation';
    
    protected $fillable = [
        'appointment_id',
        'symptoms',
        'present_illness',
        'past_history',
        'created_by',
        'last_updated_by'
    ];

}
