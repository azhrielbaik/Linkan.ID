<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuspensionAppeal extends Model
{
    use HasFactory;

    protected $table = 'suspension_appeals';

    protected $fillable = [
        'user_id',
        'appeal_reason',
        'status',
        'admin_notes',
        'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'resolved_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
