<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\DatabaseNotification;
class Notification extends DatabaseNotification
{
    protected $table = 'notifications';
protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];
    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'url',
        'is_read',
    ];
}
