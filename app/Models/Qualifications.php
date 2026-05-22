<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Qualifications extends Model
{
    use HasFactory,SoftDeletes;
    public function getStatusText()
    {
        if ($this->status == 1) {
            return 'Active';
        } else {
            return 'Inactive';
        }
    }
}
