<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prescription extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'appointment_id',
        'language',
        'created_by',
    ];

    public function details()
    {
        return $this->hasMany(PrescriptionDetail::class);
    }


}

