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
        'is_active',
        'takedown_reason',
        'takedown_at',
        'button_text',
        'verification_status',
        'rejection_reason',
        // New fields
        'media_files',
        'pricing_type',
        'price_min',
        'price_max',
        'quantity_min',
        'is_scheduled',
        'start_time',
        'end_time',
        'deliverable_type',
        'deliverable_url'
    ];

    protected $casts = [
        'media_files' => 'array',
        'is_scheduled' => 'boolean',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
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

    public function reviews()
    {
        return $this->hasMany(ProductReview::class, 'product_id');
    }

    public static function resolveImageUrl(?string $path): string
    {
        if (empty($path)) {
            return 'https://via.placeholder.com/600x600?text=No+Image';
        }

        if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://', 'data:image/', '/storage/'])) {
            return $path;
        }

        return \Illuminate\Support\Facades\Storage::url($path);
    }

    public function getImageUrlAttribute(): string
    {
        $path = null;
        if (!empty($this->media_files) && is_array($this->media_files) && count($this->media_files) > 0) {
            $path = $this->media_files[0]['url'] ?? $this->media_files[0]['path'] ?? null;
        }
        if (empty($path)) {
            $path = $this->image;
        }

        return static::resolveImageUrl($path);
    }

    public function getCleanDescriptionAttribute(): string
    {
        $desc = $this->description ?? '';
        if (!empty($desc)) {
            $desc = preg_replace('/\s*style\s*=\s*(["\']).*?\1/i', '', $desc);
            $desc = preg_replace('/<\/?span[^>]*>/i', '', $desc);
            $desc = preg_replace('/<\/?font[^>]*>/i', '', $desc);
        }
        return $desc;
    }

    public function getExistingPhotosListAttribute(): array
    {
        $photos = [];
        $mf = is_string($this->media_files) ? json_decode($this->media_files, true) : $this->media_files;
        if (is_array($mf) && count($mf) > 0) {
            foreach ($mf as $item) {
                $path = $item['path'] ?? $item['url'] ?? null;
                if ($path) {
                    $url = static::resolveImageUrl($path);
                    $photos[] = [
                        'path' => $path,
                        'url'  => $url,
                    ];
                }
            }
        }
        if (empty($photos) && !empty($this->image)) {
            $photos[] = [
                'path' => $this->image,
                'url'  => static::resolveImageUrl($this->image),
            ];
        }
        return $photos;
    }

    public function getHasPlatformFileAttribute(): bool
    {
        return !empty($this->platform_file) || (!empty($this->deliverable_url) && ($this->deliverable_type ?? 'upload') === 'upload');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            broadcast(new \App\Events\PlatformNotificationEvent());
        });

        static::updated(function ($model) {
            if ($model->isDirty('verification_status')) {
                broadcast(new \App\Events\PlatformNotificationEvent());
            }
        });
    }
}
