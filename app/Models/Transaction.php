<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'order_id', 'product_id', 'buyer_name', 'buyer_email', 'qty', 'total_price', 'status'
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'qty' => 'integer'
    ];

    protected $with = ['product']; // Eager load product relationship

    public function product()
    {
        return $this->belongsTo(DigitalProduct::class, 'product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'buyer_email', 'email');
    }

    // Helper method untuk mendapatkan status yang valid
    public static function getValidStatuses()
    {
        return ['success', 'pending', 'failed'];
    }
}

