<?php

function patchFile($file) {
    $content = file_get_contents($file);
    
    // Create a robust helper
    $helper = <<<'HTML'
@php
    if (!function_exists('resolveProductImageUrl')) {
        function resolveProductImageUrl($path) {
            if (empty($path)) return 'https://via.placeholder.com/600x600?text=No+Image';
            if (Str::startsWith($path, ['http://', 'https://', 'data:image/', '/storage/'])) {
                return $path;
            }
            return Storage::url($path);
        }
    }
@endphp
HTML;
    
    // Only add if not already there
    if (strpos($content, 'resolveProductImageUrl') === false) {
        $content = str_replace('@section("content")', $helper . "\n\n@section(\"content\")", $content);
        $content = str_replace("@section('content')", $helper . "\n\n@section('content')", $content);
        
        // In show.blade.php
        $content = str_replace("Storage::url(\$img)", "resolveProductImageUrl(\$img)", $content);
        $content = str_replace("Storage::url(\$images[0])", "resolveProductImageUrl(\$images[0])", $content);
        
        // In index.blade.php
        $content = str_replace("Storage::url(\$imageUrl)", "resolveProductImageUrl(\$imageUrl)", $content);
        
        file_put_contents($file, $content);
        echo "Patched $file\n";
    } else {
        echo "Already patched $file\n";
    }
}

patchFile('resources/views/admin_seller/features/digital_products/show.blade.php');
patchFile('resources/views/admin_seller/features/digital_products/index.blade.php');
patchFile('resources/views/public/product-detail.blade.php');
