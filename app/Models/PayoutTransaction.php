<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayoutTransaction extends Model
{
    use HasFactory;

    protected $table = 'payout_transactions';

    protected $fillable = [
        'user_id',
        'amount',
        'gross_amount',
        'commission',
        'method',
        'account_name',
        'account_number',
        'bank_name',
        'status',
        'rejection_reason',
        'processed_at',
        'processed_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'gross_amount' => 'decimal:2',
        'commission' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    /**
     * Relasi ke User (Seller yang mengajukan).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke Admin yang memproses.
     */
    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            broadcast(new \App\Events\PlatformNotificationEvent());
        });

        static::updated(function ($model) {
            if ($model->isDirty('status')) {
                broadcast(new \App\Events\PlatformNotificationEvent());
            }
        });
    }
}
