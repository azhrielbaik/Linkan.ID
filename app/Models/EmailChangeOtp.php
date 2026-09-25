<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailChangeOtp extends Model
{
    protected $fillable = [
        'user_id',
        'new_email',
        'otp_hash',
        'attempts',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'attempts' => 'integer',
    ];

    public function isExpired(): bool
    {
        return now()->isAfter($this->expires_at);
    }

    public function isExceededAttempts(): bool
    {
        return $this->attempts >= 3;
    }
}
