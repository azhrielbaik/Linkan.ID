<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoElement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'video_url',
        'is_autoplay',
        'order_position',
        'is_active',
    ];

    protected $casts = [
        'is_autoplay' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
