<?php

use App\Models\DigitalProduct;

if (!function_exists('resolveProductImageUrl')) {
    /**
     * Resolves the appropriate product image URL from stored path or external URL.
     */
    function resolveProductImageUrl(?string $path): string
    {
        return DigitalProduct::resolveImageUrl($path);
    }
}
