<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefferalDoctor extends Model
{
    protected $table = 'refferal_doctors';
    use HasFactory,SoftDeletes;
}
