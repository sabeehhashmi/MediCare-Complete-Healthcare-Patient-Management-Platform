<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'user_type',
        'action',
        'description',
        'meta'
    ];

    protected $casts = [
        'meta' => 'array'
    ];

    // ✅ Get user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
