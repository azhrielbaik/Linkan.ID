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
        'blocks_order',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Parse blocks_order into an array regardless of whether it's stored as JSON or comma-separated string.
     */
    public function getBlocksOrderArray(): array
    {
        $value = $this->attributes['blocks_order'] ?? null;
        if (empty($value)) {
            return [];
        }
        if (is_array($value)) {
            return array_values(array_filter($value));
        }
        $decoded = json_decode($value, true);
        if (is_array($decoded)) {
            return array_values(array_filter($decoded));
        }

        return array_values(array_filter(array_map('trim', explode(',', (string) $value))));
    }

    /**
     * Accessor for blocks_order_array.
     */
    public function getBlocksOrderArrayAttribute(): array
    {
        return $this->getBlocksOrderArray();
    }

    /**
     * Mutator tolerant to array, JSON, or comma-separated string input.
     */
    public function setBlocksOrderAttribute($value): void
    {
        if (empty($value)) {
            $this->attributes['blocks_order'] = null;

            return;
        }

        if (is_array($value)) {
            $this->attributes['blocks_order'] = implode(',', array_values(array_unique(array_filter($value))));

            return;
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (is_array($decoded)) {
                $this->attributes['blocks_order'] = implode(',', array_values(array_unique(array_filter($decoded))));

                return;
            }

            $items = array_values(array_unique(array_filter(array_map('trim', explode(',', $value)))));
            $this->attributes['blocks_order'] = implode(',', $items);

            return;
        }

        $this->attributes['blocks_order'] = null;
    }

    /**
     * Append a block identifier to the end of blocks_order if not already present.
     */
    public function addBlock(string $blockIdentifier): void
    {
        $blocks = $this->getBlocksOrderArray();
        if (! in_array($blockIdentifier, $blocks, true)) {
            $blocks[] = $blockIdentifier;
            $this->blocks_order = $blocks;
            $this->save();
        }
    }

    /**
     * Remove a block identifier from blocks_order.
     */
    public function removeBlock(string $blockIdentifier): void
    {
        $blocks = array_values(array_filter($this->getBlocksOrderArray(), fn ($b) => $b !== $blockIdentifier));
        $this->blocks_order = $blocks;
        $this->save();
    }

    /**
     * Set a new order for blocks_order.
     */
    public function reorderBlocks(array $newOrder): void
    {
        $this->blocks_order = array_values(array_filter($newOrder));
        $this->save();
    }

    /**
     * Get IDs of digital products included in this microsite from blocks_order.
     *
     * @return array<int>
     */
    public function getDigitalProductIds(): array
    {
        $blocks = $this->getBlocksOrderArray();
        if (empty($blocks)) {
            return [];
        }

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
