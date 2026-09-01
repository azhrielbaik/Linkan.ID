<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TextElement extends Model
{
    use HasFactory;

    protected $casts = ['is_active' => 'boolean', 'has_button' => 'boolean'];

    protected $fillable = [
        'user_id',
        'content',
        'order_position', 'is_active',
        'has_button', 'button_text', 'button_link',
        'button_icon_type', 'button_icon_value', 'button_color'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
