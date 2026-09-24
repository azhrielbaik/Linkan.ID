<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'order_id', 'product_id', 'buyer_name', 'buyer_email', 'qty', 'total_price', 'status', 'payment_method'
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'qty' => 'integer',
        'status' => \App\Enums\TransactionStatus::class
    ];

    protected $with = ['product']; // Eager load product relationship

    public static function getValidStatuses(): array
    {
        return array_column(\App\Enums\TransactionStatus::cases(), 'value');
    }

    public function getStatusValueAttribute(): string
    {
        return $this->status instanceof \BackedEnum ? $this->status->value : (string) ($this->attributes['status'] ?? 'pending');
    }

    public function getStatusLabelAttribute(): string
    {
        $status = strtolower($this->status_value);
        return match ($status) {
            'success', 'completed', 'complete' => 'Completed',
            'failed', 'cancelled', 'cancel' => 'Cancelled',
            default => 'Pending',
        };
    }

    public function getStatusClassAttribute(): string
    {
        $status = strtolower($this->status_value);
        return match ($status) {
            'success', 'completed', 'complete' => 'status-success',
            'failed', 'cancelled', 'cancel' => 'status-failed',
            default => 'status-pending',
        };
    }

    public function product()
    {
        return $this->belongsTo(DigitalProduct::class, 'product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'buyer_email', 'email');
    }



    protected static function boot()
    {
        parent::boot();

        static::updated(function ($model) {
            if ($model->isDirty('status')) {
                if ($model->product && $model->product->user_id) {
                    broadcast(new \App\Events\SellerNotificationEvent($model->product->user_id));
                }
            }
        });
    }
}

