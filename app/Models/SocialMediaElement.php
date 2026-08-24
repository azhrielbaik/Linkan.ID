<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialMediaElement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'platforms',
        'is_active',
    ];

    protected $casts = [
        'platforms' => 'array',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
