@extends('admin_seller.layouts.app')
@section('title', 'Detail Produk Digital')
@section('page_title', 'Detail Produk Digital')

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

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    /* Scoped Styles for Admin Product Detail */
    .product-detail-admin {
        font-family: 'Inter', sans-serif;
        background: #ffffff;
        border-radius: 12px;
        padding: 40px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        color: #333;
    }

    .product-detail-admin * {
        box-sizing: border-box;
    }

    .product-top {
        display: flex;
        gap: 50px;
        margin-bottom: 60px;
    }

    .product-left {
        flex: 1;
        max-width: 500px;
    }
    .main-img-wrap {
        width: 100%;
        height: 500px;
        background: #f8fafc;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 16px;
    }
    .main-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .thumbnail-gallery {
        display: flex;
        gap: 12px;
        overflow-x: auto;
    }
    .thumbnail-gallery img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
        cursor: pointer;
        border: 2px solid transparent;
        transition: 0.2s;
    }
    .thumbnail-gallery img:hover, .thumbnail-gallery img.active {
        border-color: #ED842C;
    }

    .product-right {
        flex: 1;
        padding-top: 10px;
    }
    .tags {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 15px;
    }
    .tag-stock {
        background: #10b981;
        color: #fff;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .tag-category {
        color: #64748b;
        font-size: 14px;
    }
    
    .title {
        font-size: 32px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 12px;
    }
    .short-desc {
        font-size: 15px;
        color: #64748b;
        line-height: 1.6;
        margin-bottom: 24px;
    }
    
    .price-wrap {
        display: flex;
        align-items: baseline;
        gap: 12px;
        margin-bottom: 20px;
    }
    .price-current {
        font-size: 32px;
        font-weight: 700;
        color: #0f172a;
    }
    .price-old {
        font-size: 20px;
        color: #94a3b8;
        text-decoration: line-through;
        font-weight: 600;
    }
    
    .rating-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 30px;
        padding-bottom: 30px;
        border-bottom: 1px solid #e2e8f0;
    }
    .stars {
        color: #fbbf24;
        font-size: 16px;
    }
    .reviews {
        color: #3b82f6;
        font-size: 14px;
    }

    .action-buttons {
        display: flex;
        gap: 16px;
        margin-bottom: 30px;
        padding-bottom: 30px;
        border-bottom: 1px solid #e2e8f0;
    }
    .btn-buy {
        flex: 1;
        background: #6366f1;
        color: #fff;
        border: none;
        padding: 14px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.2s;
        text-align: center;
        text-decoration: none;
    }
    .btn-buy:hover {
        background: #4f46e5;
    }

    .delivery-info {
        font-size: 14px;
        color: #64748b;
    }
    .delivery-link {
        color: #3b82f6;
        text-decoration: none;
        display: block;
        margin-top: 4px;
    }

    .bottom-section {
        border-top: 1px solid #e2e8f0;
        padding-top: 40px;
    }
    .tabs {
        display: flex;
        gap: 40px;
        border-bottom: 1px solid #e2e8f0;
        margin-bottom: 30px;
    }
    .tab {
        font-size: 16px;
        font-weight: 600;
        color: #64748b;
        padding-bottom: 12px;
        cursor: pointer;
        position: relative;
    }
    .tab.active {
        color: #3b82f6;
    }
    .tab.active::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        width: 100%;
        height: 2px;
        background: #3b82f6;
    }
    
    .tab-content {
        font-size: 16px;
        color: #475569;
        line-height: 1.8;
        display: none;
    }
    .tab-content.active {
        display: block;
    }

    /* Tab Description Rich Text & List Styling */
    #tab-description {
        font-size: 16px;
        color: #475569;
        line-height: 1.8;
        word-break: break-word;
        overflow-wrap: break-word;
    }
    #tab-description p {
        margin-bottom: 12px;
        line-height: 1.8;
    }
    #tab-description ul,
    #tab-description ol {
        margin-top: 8px;
        margin-bottom: 16px;
        padding-left: 28px;
        list-style-position: outside;
    }
    #tab-description ul {
        list-style-type: disc;
    }
    #tab-description ol {
        list-style-type: decimal;
    }
    #tab-description li {
        margin-bottom: 6px;
        line-height: 1.7;
        padding-left: 4px;
    }
    #tab-description ul ul,
    #tab-description ol ol,
    #tab-description ul ol,
    #tab-description ol ul {
        margin-top: 4px;
        margin-bottom: 4px;
        padding-left: 22px;
    }
    #tab-description img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 12px 0;
    }
    #tab-description table {
        width: 100%;
        border-collapse: collapse;
        margin: 16px 0;
        display: block;
        overflow-x: auto;
    }
    #tab-description blockquote {
        border-left: 4px solid #ed842c;
        padding-left: 14px;
        margin: 12px 0;
        color: #64748b;
        font-style: italic;
    }
    
    @media (max-width: 992px) {
        .product-top {
            flex-direction: column;
        }
        .product-left, .product-right {
            max-width: 100%;
        }
        .action-buttons {
            flex-direction: column;
        }
    }

    @media (max-width: 768px) {
        .product-detail-admin {
            /* Break out of the .content-wrapper 16px padding on mobile */
            margin-left: -16px;
            margin-right: -16px;
            padding: 16px 16px;
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
        }
        /* Mobile List & Tab Description: Ensure bullet & numbers are never cut off */
        #tab-description ul,
        #tab-description ol {
            padding-left: 28px !important;
            margin-left: 0 !important;
            margin-bottom: 14px !important;
        }
        #tab-description li {
            padding-left: 4px !important;
            margin-bottom: 6px !important;
        }
        #tab-description ul ul,
        #tab-description ol ol,
        #tab-description ul ol,
        #tab-description ol ul {
            padding-left: 20px !important;
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

    /* Dark Mode Support */
    html.dark .product-detail-admin {
        background: #1e293b;
        color: #f8fafc;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
    html.dark .title,
    html.dark .price-current {
        color: #f8fafc;
    }
    html.dark .short-desc,
    html.dark .tab-content,
    html.dark #tab-description {
        color: #cbd5e1;
    }
    html.dark .main-img-wrap {
        background: #0f172a;
    }
    html.dark .rating-wrap,
    html.dark .action-buttons,
    html.dark .bottom-section,
    html.dark .tabs {
        border-color: #334155;
    }
    html.dark .tab {
        color: #94a3b8;
    }
    html.dark .tab.active {
        color: #ed842c;
    }
    html.dark .tab.active::after {
        background: #ed842c;
    }
</style>

@php
    $images = [];
    $mediaFiles = is_string($product->media_files) ? json_decode($product->media_files, true) : $product->media_files;
    if (is_array($mediaFiles) && count($mediaFiles) > 0) {
        foreach ($mediaFiles as $media) {
            if (isset($media['url']) || isset($media['path'])) {
                $images[] = $media['url'] ?? $media['path'];
            }
        }
    }
    if (empty($images) && $product->image) {
        $images[] = $product->image;
    }
    
    $mainImage = count($images) > 0 ? resolveProductImageUrl($images[0]) : 'https://via.placeholder.com/600x600?text=No+Image';
    $originalPrice = $product->sale_price ? $product->price : ($product->price * 1.2);
    $currentPrice = $product->sale_price ? $product->sale_price : $product->price;
@endphp

<div class="product-detail-admin">
    <div style="margin-bottom: 20px;">
        <a href="{{ route('admin.digital-products.index') }}" style="color: #64748b; text-decoration: none; font-weight: 500;">
            <i class="fas fa-arrow-left"></i> Kembali ke Toko
        </a>
    </div>

    <div class="product-top">
        <div class="product-left">
            <div class="main-img-wrap">
                <img id="mainDisplayImage" src="{{ $mainImage }}" alt="{{ $product->title }}">
            </div>
            
            <div class="thumbnail-gallery">
                @if(count($images) > 0)
                    @foreach($images as $index => $img)
                        <img src="{{ resolveProductImageUrl($img) }}" alt="Thumbnail" class="{{ $index === 0 ? 'active' : '' }}" onclick="changeImage(this, '{{ resolveProductImageUrl($img) }}')">
                    @endforeach
                @else
                    <img src="{{ $mainImage }}" alt="Thumbnail" class="active">
                @endif
            </div>
        </div>

        <div class="product-right">
            <div class="tags">
                <span class="tag-stock">In Stock</span>
                <span class="tag-category">Digital Product</span>
            </div>
            
            <h1 class="title">{{ $product->title }}</h1>
            <p class="short-desc">
                {{ Str::limit(strip_tags($product->description), 100) }}
            </p>
            
            <div class="price-wrap">
                <div class="price-current">Rp {{ number_format($currentPrice, 0, ',', '.') }}</div>
                @if($originalPrice > $currentPrice)
                    <div class="price-old">Rp {{ number_format($originalPrice, 0, ',', '.') }}</div>
                @endif
            </div>
            
            <div class="rating-wrap">
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="far fa-star"></i>
                </div>
                <div class="reviews">(236 reviews)</div>
            </div>
            
            <div class="action-buttons">
                <a href="{{ route('admin.digital-products.edit', $product->id) }}" class="btn-buy" style="background:#ED842C;"><i class="fas fa-pen"></i> Edit Produk</a>
            </div>
        </div>
    </div>

    <div class="bottom-section">
        <div class="tabs">
            <div class="tab active" onclick="switchTab('description', this)">Description</div>
            <div class="tab" onclick="switchTab('reviews', this)">Reviews</div>
        </div>
        
        <div id="tab-description" class="tab-content active">
            {!! $product->description !!}
        </div>
        
        <div id="tab-reviews" class="tab-content">
            <p>Customer reviews will appear here.</p>
        </div>
    </div>
</div>

<script>
    function changeImage(el, src) {
        document.getElementById('mainDisplayImage').src = src;
        document.querySelectorAll('.thumbnail-gallery img').forEach(img => img.classList.remove('active'));
        el.classList.add('active');
    }
    
    function switchTab(tabId, el) {
        document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        el.classList.add('active');
        document.getElementById('tab-' + tabId).classList.add('active');
    }
</script>
@endsection
