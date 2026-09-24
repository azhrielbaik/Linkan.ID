@extends("admin_seller.layouts.app")

@section("page_title", 'Toko Saya')

@push("styles")
<link rel="stylesheet" href="{{ asset('css/pages/beranda.css') }}" data-turbo-track="reload">
<style>
    /* Header Layout */
    .store-header-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }
    .store-header-row h2 {
        font-size: 20px;
        font-weight: 800;
        color: #1e293b;
        margin: 0;
    }
    .store-header-actions {
        display: flex;
        gap: 16px;
        align-items: center;
    }
    
    /* Search Bar */
    .store-search-box {
        display: flex;
        align-items: center;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 8px 16px;
        width: 260px;
    }
    .store-search-box i {
        color: #94a3b8;
        font-size: 14px;
        margin-right: 8px;
    }
    .store-search-box input {
        border: none;
        outline: none;
        width: 100%;
        font-size: 13px;
        color: #1e293b;
    }
    .store-search-box input::placeholder {
        color: #cbd5e1;
    }

    .btn-create {
        background: #ED842C;
        color: #fff;
        padding: 9px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 700;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: 0.2s;
        border: none;
    }
    .btn-create:hover {
        background: #4a4bd1;
        color: #fff;
    }

    /* Grid Layout */
    .store-product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 24px;
        margin-bottom: 40px;
    }

    /* Card Design */
    .store-product-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        display: flex;
        flex-direction: column;
        overflow: visible; /* To allow floating icon to pop out */
        border: 1px solid #f1f5f9;
        transition: transform 0.3s, box-shadow 0.3s;
        position: relative;
    }
    .store-product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.05);
    }
    
    .store-card-img-wrap {
        position: relative;
        width: 100%;
        padding-top: 100%; /* 1:1 Aspect ratio */
        border-radius: 16px 16px 0 0;
        background: #f8fafc;
        overflow: hidden; /* Contains the image */
    }
    .store-card-img-wrap img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .store-card-img-wrap .no-img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 40px;
        color: #cbd5e1;
    }
    
    /* Delete Button on top right */
    .btn-delete-float {
        position: absolute;
        top: 12px;
        right: 12px;
        width: 32px;
        height: 32px;
        background: rgba(255,255,255,0.9);
        color: #ef4444;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
        opacity: 0;
        transition: 0.2s;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        z-index: 10;
    }
    .store-product-card:hover .btn-delete-float {
        opacity: 1;
    }
    .btn-delete-float:hover {
        background: #ef4444;
        color: #fff;
    }

    /* Floating Edit Icon */
    .store-floating-icon {
        position: absolute;
        bottom: -16px;
        right: 20px;
        width: 32px;
        height: 32px;
        background: #ED842C;
        color: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 10px rgba(90,91,241,0.3);
        border: 2px solid #fff;
        z-index: 10;
        text-decoration: none;
        transition: transform 0.2s;
    }
    .store-floating-icon:hover {
        transform: scale(1.1);
        color: #fff;
    }

    /* Card Content */
    .store-card-content {
        padding: 24px 20px 20px 20px;
        display: flex;
        flex-direction: column;
    }
    .store-card-title {
        font-size: 15px;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 12px 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .store-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .store-card-price-col {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .store-price-main {
        font-size: 14px;
        font-weight: 800;
        color: #1e293b;
    }
    .store-price-strike {
        font-size: 11px;
        font-weight: 600;
        color: #94a3b8;
        text-decoration: line-through;
    }
    
    .store-card-rating {
        display: flex;
        gap: 2px;
        font-size: 11px;
    }
    .star-filled {
        color: #fbbf24;
    }
    .star-empty {
        color: #e2e8f0;
    }

    @media (max-width: 768px) {
        .store-header-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 14px;
            margin-bottom: 20px;
        }
        .store-header-actions {
            width: 100%;
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .store-search-box {
            flex: 1;
            width: auto;
        }
        .btn-create {
            text-align: center;
            justify-content: center;
            white-space: nowrap;
            padding: 9px 14px;
        }

        /* 2-Column Mobile Grid for Store Product Cards */
        .store-product-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            gap: 12px !important;
            margin-bottom: 30px;
        }

        .store-product-card {
            border-radius: 12px !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03) !important;
        }

        .store-card-img-wrap {
            border-radius: 12px 12px 0 0 !important;
        }

        .store-card-img-wrap .no-img {
            font-size: 26px !important;
        }

        /* Floating Delete button on mobile */
        .btn-delete-float {
            top: 6px !important;
            right: 6px !important;
            width: 26px !important;
            height: 26px !important;
            font-size: 11px !important;
            opacity: 0.9 !important;
        }

        /* Floating Edit Icon on mobile */
        .store-floating-icon {
            bottom: -12px !important;
            right: 10px !important;
            width: 26px !important;
            height: 26px !important;
        }
        .store-floating-icon i {
            font-size: 10px !important;
        }

        /* Card Content on mobile */
        .store-card-content {
            padding: 12px 8px 10px 8px !important;
        }

        .store-card-title {
            font-size: 12.5px !important;
            line-height: 1.35 !important;
            margin: 0 0 6px 0 !important;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            white-space: normal !important;
            height: 2.7em;
            word-break: break-word;
        }

        .store-card-footer {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 4px !important;
        }

        .store-card-price-col {
            display: flex;
            flex-wrap: wrap;
            align-items: baseline;
            gap: 3px;
        }

        .store-price-main {
            font-size: 12.5px !important;
            font-weight: 800;
        }

        .store-price-strike {
            font-size: 10px !important;
        }

        .store-card-rating {
            font-size: 9px !important;
            gap: 1.5px !important;
        }
    }

    /* Dark Mode Support */
    html.dark .store-product-card {
        background: #1c212e !important;
        border-color: #2a3241 !important;
    }
    html.dark .store-card-img-wrap {
        background: #141824 !important;
    }
    html.dark .store-card-title,
    html.dark .store-price-main,
    html.dark .store-header-row h2 {
        color: #ffffff !important;
    }
    html.dark .store-search-box {
        background: #1c212e !important;
        border-color: #2a3241 !important;
    }
    html.dark .store-search-box input {
        background: transparent !important;
        color: #ffffff !important;
    }
</style>
@endpush

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

@section("content")
<div class="dashboard-beranda-page" style="padding-top: 10px;">

    <!-- Top Header matched to design -->
    <div class="store-header-row">
        <h2>Produk Saya</h2>
        <div class="store-header-actions">
            <div class="store-search-box">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search Product">
            </div>
            <a href="{{ route('admin.digital-products.create') }}" class="btn-create" data-turbo="false">
                <i class="fas fa-plus"></i> Tambah
            </a>
        </div>
    </div>

    <!-- Product Grid -->
    <div class="store-product-grid">
        @forelse($products as $product)
            <div class="store-product-card" onclick="window.location.href = '{{ route('admin.digital-products.show', $product->id) }}'" style="cursor: pointer;">
                
                <!-- Delete Form (appears on hover) -->
                <form action="{{ route('admin.digital-products.destroy', $product->id) }}" method="POST" style="margin:0;" onsubmit="event.stopPropagation(); return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-delete-float" title="Hapus Produk" onclick="event.stopPropagation();">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>

                <!-- Image Section -->
                <div style="position: relative;">
                    <div class="store-card-img-wrap">
                        @php
                            $imageUrl = null;
                            if (is_array($product->media_files) && count($product->media_files) > 0) {
                                $imageUrl = $product->media_files[0]['url'] ?? $product->media_files[0]['path'] ?? null;
                            }
                            if (!$imageUrl && $product->image) {
                                $imageUrl = $product->image;
                            }
                        @endphp

                        @if($imageUrl)
                            <img src="{{ resolveProductImageUrl($imageUrl) }}" alt="{{ $product->title }}">
                        @else
                            <div class="no-img"><i class="fas fa-image"></i></div>
                        @endif
                    </div>
                    <!-- Floating Edit Button -->
                    <a href="{{ route('admin.digital-products.edit', $product->id) }}" class="store-floating-icon" data-turbo="false" title="Edit Produk" onclick="event.stopPropagation();">
                        <i class="fas fa-pen" style="font-size: 12px;"></i>
                    </a>
                </div>

                <!-- Content Section -->
                <div class="store-card-content">
                    <h4 class="store-card-title">{{ $product->title }}</h4>
                    
                    <div class="store-card-footer">
                        <div class="store-card-price-col">
                            @if($product->sale_price && $product->sale_price < $product->price)
                                <span class="store-price-main">Rp {{ number_format($product->sale_price, 0, ',', '.') }}</span>
                                <span class="store-price-strike">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            @else
                                <span class="store-price-main">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            @endif
                        </div>
                        
                        <!-- Hardcoded Rating to match design aesthetic -->
                        <div class="store-card-rating">
                            <i class="fas fa-star star-filled"></i>
                            <i class="fas fa-star star-filled"></i>
                            <i class="fas fa-star star-filled"></i>
                            <i class="fas fa-star star-filled"></i>
                            <i class="fas fa-star star-empty"></i>
                        </div>
                    </div>
                </div>

            </div>
        @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; background: #fff; border-radius: 16px; border: 1px dashed #cbd5e1; color: #64748b;">
                <i class="fas fa-store-slash" style="font-size: 32px; color: #cbd5e1; margin-bottom: 16px; display: block;"></i>
                <h4 style="font-size: 16px; color: #1e293b; margin-bottom: 8px; font-weight: 700;">Toko Anda Masih Kosong</h4>
                <p style="font-size: 14px; margin-bottom: 20px;">Anda belum menambahkan produk digital apapun.</p>
                <a href="{{ route('admin.digital-products.create') }}" class="btn-create" data-turbo="false">
                    <i class="fas fa-plus"></i> Tambah Produk Pertama
                </a>
            </div>
        @endforelse
    </div>

</div>
@endsection
