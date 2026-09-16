<?php
$showFile = 'resources/views/admin_seller/features/digital_products/show.blade.php';

$showContent = file_get_contents($showFile);

$oldCss = '    @media (max-width: 768px) {
        .product-detail-admin {
            padding: 16px 1px;
        }
        .bottom-section {
            margin-left: -8px;
            margin-right: -8px;
            padding-left: 8px;
            padding-right: 8px;
        }
        .tab-content {
            padding: 0 4px;
        }';

$newCss = '    @media (max-width: 768px) {
        .product-detail-admin {
            /* Break out of the .content-wrapper 16px padding on mobile */
            margin-left: -16px;
            margin-right: -16px;
            padding: 16px 12px;
            border-radius: 0;
            border-left: none;
            border-right: none;
        }
        .bottom-section {
            margin-left: 0;
            margin-right: 0;
            padding-left: 0;
            padding-right: 0;
        }
        .tab-content {
            padding: 0;
        }';

$showContent = str_replace($oldCss, $newCss, $showContent);
file_put_contents($showFile, $showContent);
echo "Patched successfully\n";
