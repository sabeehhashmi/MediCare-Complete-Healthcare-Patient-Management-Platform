<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DoctorHolidays extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'doctor_id',
        'holiday_name',
        'holiday_date',
    ];
}
