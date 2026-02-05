<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['name', 'email', 'subject', 'content', 'is_read'];

    protected $casts = [
        'is_read' => 'boolean',
    ];
}
