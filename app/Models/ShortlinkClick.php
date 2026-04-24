<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShortlinkClick extends Model
{
    protected $fillable = [
        'shortlink_id',
        'user_id',
        'source',
        'referer',
        'ip_address',
        'user_agent',
    ];

    public function shortlink(): BelongsTo
    {
        return $this->belongsTo(Shortlink::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
