<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordResetRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email',
        'reset_token_hash',
        'otp_hash',
        'reason',
        'otp_code',
        'status',
        'admin_notes',
        'expires_at',
        'resolved_at',
        'used_at',
        'attempts',
    ];

    protected $casts = [
        'expires_at'  => 'datetime',
        'resolved_at' => 'datetime',
        'used_at'     => 'datetime',
        'attempts'    => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }
}
