<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appearance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'alias',
        'title',
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

    /**
     * Get IDs of digital products included in this microsite from blocks_order.
     *
     * @return array<int>
     */
    public function getDigitalProductIds(): array
    {
        if (empty($this->blocks_order)) {
            return [];
        }

        $blocks = is_array($this->blocks_order) 
            ? $this->blocks_order 
            : explode(',', (string) $this->blocks_order);

        $productIds = [];
        foreach ($blocks as $block) {
            $block = trim((string) $block);
            if (str_starts_with($block, 'digitalproduct_')) {
                $id = (int) str_replace('digitalproduct_', '', $block);
                if ($id > 0) {
                    $productIds[] = $id;
                }
            }
        }

        return array_values(array_unique($productIds));
    }

    /**
     * Accessor for the count of valid digital products in this microsite.
     */
    public function getDigitalProductsCountAttribute(): int
    {
        if (array_key_exists('digital_products_count', $this->attributes)) {
            return (int) $this->attributes['digital_products_count'];
        }

        $ids = $this->getDigitalProductIds();
        if (empty($ids)) {
            return 0;
        }

        return DigitalProduct::whereIn('id', $ids)->count();
    }

    /**
     * Query builder for digital products included in this microsite.
     */
    public function digitalProducts()
    {
        return DigitalProduct::whereIn('id', $this->getDigitalProductIds());
    }
}
