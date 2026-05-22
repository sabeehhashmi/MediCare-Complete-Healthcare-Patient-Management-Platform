<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactQuery extends Model
{
    protected $fillable = [
      'name','email','message','mobile_number','reply','replied_by','status'
    ];
    use HasFactory;
}
