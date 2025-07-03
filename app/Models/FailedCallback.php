<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FailedCallback extends Model
{
    protected $fillable = [
        'order_id',
        'payload',
        'created_at',
        'is_processed',
        'processed_at',
        'error_message'
    ];

    protected $casts = [
        'payload' => 'array',
        'created_at' => 'datetime',
        'processed_at' => 'datetime',
        'is_processed' => 'boolean'
    ];
} 