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

    .qty-wrap {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 30px;
    }
    .label {
        font-size: 16px;
        font-weight: 600;
        color: #334155;
        width: 70px;
    }
    
    .qty-selector {
        display: flex;
        align-items: center;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        overflow: hidden;
    }
    .qty-btn {
        background: #f8fafc;
        border: none;
        padding: 8px 16px;
        cursor: pointer;
        font-size: 18px;
        color: #3b82f6;
        transition: 0.2s;
    }
    .qty-btn:hover {
        background: #e2e8f0;
    }
    .qty-input {
        width: 50px;
        text-align: center;
        border: none;
        border-left: 1px solid #cbd5e1;
        border-right: 1px solid #cbd5e1;
        font-size: 16px;
        padding: 8px 0;
        outline: none;
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
    .btn-cart {
        flex: 1;
        background: #f87171;
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
    .btn-cart:hover {
        background: #ef4444;
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
            
            <div class="qty-wrap">
                <div class="label">QTY:</div>
                <div class="qty-selector">
                    <button type="button" class="qty-btn" onclick="updateMainQty(-1)">-</button>
                    <input type="text" id="mainQtyInput" class="qty-input" value="1" readonly>
                    <button type="button" class="qty-btn" onclick="updateMainQty(1)">+</button>
                </div>
            </div>
            
            <div class="action-buttons">
                <a href="{{ route('admin.digital-products.edit', $product->id) }}" class="btn-buy" style="background:#ED842C;"><i class="fas fa-pen"></i> Edit Produk</a>
                <a href="{{ route('product.show', $product->id) }}" target="_blank" class="btn-cart" style="background:#475569;"><i class="fas fa-external-link-alt"></i> Lihat Halaman Publik</a>
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
    let mainQty = 1;
    const mainQtyInput = document.getElementById('mainQtyInput');
    
    function updateMainQty(change) {
        if (mainQty + change >= 1) {
            mainQty += change;
            mainQtyInput.value = mainQty;
        }
    }
    
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
