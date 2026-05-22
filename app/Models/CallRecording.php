<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CallRecording extends Model
{
     protected $fillable = [
        'appointment_id', 
        'uid',
        'resource_id',
        'sid',
        'recording_response',
        'status'
    ];
}
