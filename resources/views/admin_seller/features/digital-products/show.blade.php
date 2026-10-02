@extends('admin_seller.layouts.app')

@section('title', 'Detail Produk — ' . $product->title)
@section('page_title', 'Detail Produk Digital')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/pages/digital-product-detail.css') }}?v={{ file_exists(public_path('css/pages/digital-product-detail.css')) ? filemtime(public_path('css/pages/digital-product-detail.css')) : '1.0' }}">
@endpush

@section('content')
<div class="product-detail-admin">
    {{-- Tombol Kembali --}}
    <div class="detail-back-bar">
        <a href="{{ route('admin.digital-products.index') }}" class="detail-back-link">
            <i class="fas fa-arrow-left"></i> Kembali ke Toko
        </a>
    </div>

    {{-- Grid Atas: Media & Info Pembelian --}}
    <div class="product-top">
        <div class="product-left">
            <div class="main-img-wrap">
                <img id="mainDisplayImage" src="{{ $mainImage }}" alt="{{ $product->title }}">
            </div>
            <div class="thumbnail-gallery">
                @foreach($images as $index => $img)
                    <img src="{{ resolveProductImageUrl($img) }}" 
                         alt="Thumbnail {{ $index + 1 }}" 
                         class="{{ $index === 0 ? 'active' : '' }}" 
                         onclick="changeProductImage(this, '{{ resolveProductImageUrl($img) }}')">
                @endforeach
            </div>
        </div>

        <div class="product-right">
            <div class="tags">
                <span class="tag-stock">In Stock</span>
                <span class="tag-category">Digital Product</span>
            </div>
            
            <h1 class="title">{{ $product->title }}</h1>
            <p class="short-desc">{{ Str::limit(strip_tags($product->description), 100) }}</p>
            
            <div class="price-wrap">
                <div class="price-current">Rp {{ number_format($currentPrice, 0, ',', '.') }}</div>
                @if($originalPrice > $currentPrice)
                    <div class="price-old">Rp {{ number_format($originalPrice, 0, ',', '.') }}</div>
                @endif
            </div>
            
            <div class="rating-wrap">
                <div class="stars">
                    @for ($i = 1; $i <= 5; $i++)
                        @if ($i <= floor($avgRating))
                            <i class="fas fa-star"></i>
                        @elseif ($i - $avgRating < 1 && $avgRating - floor($avgRating) >= 0.5)
                            <i class="fas fa-star-half-alt"></i>
                        @else
                            <i class="far fa-star"></i>
                        @endif
                    @endfor
                </div>
                <div class="reviews">
                    {{ $reviewCount > 0 ? number_format($avgRating, 1) . ' / 5.0' : 'Belum ada ulasan' }} ({{ $reviewCount }} ulasan)
                </div>
            </div>
            
            <div class="action-buttons">
                <a href="{{ route('admin.digital-products.edit', $product->id) }}" class="btn-buy btn-edit-product">
                    <i class="fas fa-pen"></i> Edit Produk
                </a>
            </div>
        </div>
    </div>

    {{-- Section Bawah: Tabs Deskripsi & Review --}}
    <div class="bottom-section">
        <div class="tabs">
            <div class="tab active" onclick="switchProductTab('description', this)">Description</div>
            <div class="tab" onclick="switchProductTab('reviews', this)">Reviews ({{ $reviewCount }})</div>
        </div>
        
        <div id="tab-description" class="tab-content active">
            {!! $product->description !!}
        </div>
        
        <div id="tab-reviews" class="tab-content">
            @if($reviewCount === 0)
                <div class="review-empty-state">
                    <i class="far fa-comment-dots"></i>
                    <p class="empty-title">Belum ada ulasan untuk produk ini.</p>
                    <p class="empty-desc">Ulasan akan muncul setelah pembeli memberikan penilaian melalui email konfirmasi pembelian.</p>
                </div>
            @else
                <div class="review-summary-card">
                    <div class="review-summary-score">
                        <div class="review-avg-number">{{ number_format($avgRating, 1) }}</div>
                        <div class="review-stars-row">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="{{ $i <= round($avgRating) ? 'fas' : 'far' }} fa-star"></i>
                            @endfor
                        </div>
                        <div class="review-total-count">{{ $reviewCount }} ulasan</div>
                    </div>
                    <div class="review-distribution-bars">
                        @foreach ($ratingDistribution as $star => $data)
                            <div class="review-bar-row">
                                <span class="star-num">{{ $star }}</span>
                                <i class="fas fa-star star-icon"></i>
                                <div class="review-bar-track">
                                    <div class="review-bar-fill" style="width: {{ $data['percent'] }}%;"></div>
                                </div>
                                <span class="star-count">{{ $data['count'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Daftar Ulasan Pembeli --}}
                <div class="reviews-list">
                    @foreach ($reviews as $review)
                        <div class="review-item">
                            <div class="review-header">
                                <div class="review-avatar">{{ strtoupper(substr($review->buyer_name, 0, 1)) }}</div>
                                <div class="review-author-meta">
                                    <div class="review-buyer-name">{{ $review->buyer_name }}</div>
                                    <div class="review-stars-date">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star"></i>
                                        @endfor
                                        <span class="review-time">{{ $review->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                            @if ($review->comment)
                                <p class="review-comment-body">"{{ $review->comment }}"</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function changeProductImage(el, src) {
        const main = document.getElementById('mainDisplayImage');
        if (main) main.src = src;
        document.querySelectorAll('.thumbnail-gallery img').forEach(img => img.classList.remove('active'));
        if (el) el.classList.add('active');
    }
    
    function switchProductTab(tabId, el) {
        document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        if (el) el.classList.add('active');
        const target = document.getElementById('tab-' + tabId);
        if (target) target.classList.add('active');
    }
</script>
@endpush
