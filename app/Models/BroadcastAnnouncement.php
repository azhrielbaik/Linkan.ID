<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BroadcastAnnouncement extends Model
{
    protected $table = 'broadcast_announcements';

    protected $fillable = [
        'admin_id',
        'title',
        'message',
        'type',
        'target_role',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
