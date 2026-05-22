<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentHospital extends Model
{
    use HasFactory;

    protected $table = 'department_hospital';

    protected $fillable = [
        'department_id',
        'hospital_id',
        'manager_name',
        'phone',
        'email'
    ];

    // Define relationships if necessary
    public function department()
    {
        return $this->belongsTo(DepartmentModel::class);
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }
}
