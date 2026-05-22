<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HospitalDepartmentModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'department_hospital';

    protected $fillable = [
        'department_id',
        'department_name',
        'hospital_id',
        'department_manager',
        'dial_code',
        'phone',
        'email',
        'updated_at',
        'active',
        'deleted',
    ];

    public function departments()
    {
        return $this->belongsToMany(DepartmentModel::class, 'department_hospital')
                    ->withPivot('department_id')
                    ->withTimestamps();
    }

   
}
