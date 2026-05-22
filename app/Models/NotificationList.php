<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationList extends Model
{
    use HasFactory;

    protected $table = 'notification_list';

    protected $fillable = [
        'user_types',
        'user_ids',
        'title',
        'description',
        'status',
    ];

    protected $casts = [
        'user_types' => 'array',
        'user_ids' => 'array',
    ];
}
