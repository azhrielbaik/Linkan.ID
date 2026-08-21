<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DividerElement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'size',
        'order_position'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
