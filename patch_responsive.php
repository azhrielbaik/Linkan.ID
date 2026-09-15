<?php
$indexFile = 'resources/views/admin_seller/features/digital_products/index.blade.php';
$showFile = 'resources/views/admin_seller/features/digital_products/show.blade.php';

// Patch Index
$indexContent = file_get_contents($indexFile);
$indexCss = "
    @media (max-width: 768px) {
        .store-header-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
        }
        .store-header-actions {
            width: 100%;
            flex-direction: column;
            align-items: stretch;
        }
        .store-search-box {
            width: 100%;
        }
        .btn-create {
            text-align: center;
            justify-content: center;
        }
    }
</style>";
if (strpos($indexContent, 'max-width: 768px') === false) {
    $indexContent = str_replace('</style>', $indexCss, $indexContent);
    file_put_contents($indexFile, $indexContent);
}

// Patch Show
$showContent = file_get_contents($showFile);
$showCss = "
    @media (max-width: 768px) {
        .product-detail-admin {
            padding: 16px;
        }
        .main-img-wrap {
            height: auto;
            aspect-ratio: 1 / 1;
        }
        .title {
            font-size: 24px;
        }
        .price-current {
            font-size: 24px;
        }
        .tabs {
            gap: 20px;
            overflow-x: auto;
            white-space: nowrap;
        }
    }
</style>";
if (strpos($showContent, 'max-width: 768px') === false) {
    $showContent = str_replace('</style>', $showCss, $showContent);
    file_put_contents($showFile, $showContent);
}

echo "Patched successfully\n";
