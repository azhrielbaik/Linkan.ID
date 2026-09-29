<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductReview extends Model
{
    protected $fillable = [
        'product_id',
        'order_id',
        'buyer_name',
        'buyer_email',
        'rating',
        'comment',
        'is_visible',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_visible' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(DigitalProduct::class, 'product_id');
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'order_id', 'order_id');
    }
}
