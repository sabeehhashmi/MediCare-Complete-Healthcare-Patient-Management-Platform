<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DepartmentModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'departments';
    protected $fillable = [
        'title',
        'title_ar',
        'status'
    ];
    

    public function hospitals()
    {
        return $this->belongsToMany(Hospital::class, 'department_hospital', 'department_id');
    }
    
    public function doctors()
    {
        return $this->belongsToMany(Doctor::class, 'department_doctors', 'department_id');
    }

   
}
