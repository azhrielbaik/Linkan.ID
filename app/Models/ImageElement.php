<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageElement extends Model
{
    use HasFactory;

    protected $casts = ['is_active' => 'boolean'];

    protected $fillable = [
        'user_id',
        'image_path',
        'link_url',
        'order_position', 'is_active'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
