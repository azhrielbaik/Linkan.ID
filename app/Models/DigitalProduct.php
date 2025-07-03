<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DigitalProduct extends Model
{
    use SoftDeletes;

    protected $table = 'digital_products';

    protected $fillable = [
        'id',
        'user_id',
        'image',
        'title',
        'description',
        'platform_type',
        'platform_url',
        'platform_file',
        'price',
        'sale_price',
        'has_quantity_limit',
        'quantity',
        'button_text',
        'verification_status',
        'rejection_reason'
    ];
     // ✅ Tambahkan ini
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function transactions()
{
    return $this->hasMany(Transaction::class, 'product_id');
}
}
