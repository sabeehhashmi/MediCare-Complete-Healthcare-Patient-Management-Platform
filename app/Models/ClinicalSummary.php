<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClinicalSummary extends Model
{

    protected $table = 'clinic_summaries';
    use SoftDeletes;

    protected $fillable = [
        'appointment_id',
        'summary',
        'follow_up',
        'status',
        'created_by',
        'last_updated_by',
    ];
}


