<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appearance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'banner',
        'profile_image',
        'name',
        'bio',
        'theme_color',
        'font_family',
        'background_color',
        'is_active',
        'instagram',
        'tiktok',
        'whatsapp'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
