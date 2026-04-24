<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shortlink extends Model
{
    protected $fillable = ['user_id', 'slug', 'destination'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
