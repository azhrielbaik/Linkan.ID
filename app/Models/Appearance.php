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
        'background_type',
        'profile_layout',
        'block_shape',
        'is_active',
        'instagram',
        'tiktok',
        'whatsapp',
        'blocks_order'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
