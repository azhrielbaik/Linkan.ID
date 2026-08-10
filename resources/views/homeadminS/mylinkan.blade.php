@extends("layouts.admin")

@section("page_title", "Microsite Management")

@push("styles")
<style>
    /* PAGE BASE STYLES */
    .microsite-container {
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        color: #1f2937;
        padding-bottom: 50px;
    }

    /* HEADER & STATS BAR */
    .header-title-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .header-title-section h1 {
        font-size: 18px;
        font-weight: 800;
        color: #111827;
        margin: 0 0 4px 0;
        letter-spacing: -0.02em;
    }

    .header-title-section p {
        color: #6b7280;
        font-size: 14px;
        margin: 0;
    }

    .mode-switch-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #f3f4f6;
        padding: 6px;
        border-radius: 30px;
        border: 1px solid #e5e7eb;
    }

    .mode-switch-btn a {
        padding: 8px 18px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 700;
        color: #4b5563;
        text-decoration: none;
        transition: all 0.25s ease;
    }

    .mode-switch-btn a.active {
        background: #FF9040;
        color: white;
        box-shadow: 0 4px 12px rgba(255, 144, 64, 0.35);
    }

    /* STATS CARDS GRID */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 18px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        border: 1px solid #f0f0f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 50% !important;
        background: #FFF3E6;
        color: #FF9040;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .stat-info .stat-value {
        font-size: 22px;
        font-weight: 800;
        color: #111827;
        line-height: 1.2;
    }

    .stat-info .stat-label {
        font-size: 12px;
        font-weight: 600;
        color: #6b7280;
        margin-top: 2px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    /* GALLERY GRID SECTION */
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .section-header h2 {
        font-size: 19px;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .microsite-gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
        gap: 24px;
    }

    /* MICROSITE CARD */
    .microsite-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid #eaeaea;
        box-shadow: 0 6px 24px rgba(0, 0, 0, 0.04);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
    }

    .microsite-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 36px rgba(0, 0, 0, 0.08);
        border-color: #ffd8bd;
    }

    /* CARD HEADER & THUMBNAIL AREA */
    .card-thumbnail-container {
        background: linear-gradient(135deg, #FFF3E6 0%, #FFE5D3 100%);
        padding: 24px 20px 16px;
        display: flex;
        justify-content: center;
        align-items: center;
        position: relative;
        overflow: hidden;
        border-bottom: 1px solid #f0f0f0;
    }

    /* PHONE SCREENSHOT THUMBNAIL */
    .phone-thumbnail {
        width: 170px;
        height: 310px;
        border-radius: 24px;
        background: #ffffff;
        border: 5px solid #1f2937;
        box-shadow: 0 12px 28px rgba(0,0,0,0.15);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        position: relative;
        transition: transform 0.3s ease;
    }

    .microsite-card:hover .phone-thumbnail {
        transform: scale(1.03);
    }

    .phone-thumbnail-screen {
        width: 100%;
        height: 100%;
        padding: 12px 8px;
        display: flex;
        flex-direction: column;
        align-items: center;
        overflow: hidden;
        background-size: cover;
        background-position: center;
    }

    .phone-thumb-banner {
        width: 100%;
        height: 48px;
        border-radius: 6px;
        background: #e5e7eb;
        margin-bottom: 8px;
        overflow: hidden;
        flex-shrink: 0;
    }

    .phone-thumb-banner img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .phone-thumb-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: #d1d5db;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border: 2px solid white;
        margin-bottom: 6px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        flex-shrink: 0;
    }

    .phone-thumb-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .phone-thumb-name {
        font-size: 11px;
        font-weight: 700;
        text-align: center;
        margin-bottom: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        width: 100%;
    }

    .phone-thumb-bio {
        font-size: 8px;
        color: #6b7280;
        text-align: center;
        line-height: 1.2;
        max-height: 20px;
        overflow: hidden;
        margin-bottom: 8px;
        width: 100%;
        padding: 0 4px;
    }

    .phone-thumb-block {
        width: 100%;
        background: rgba(255, 255, 255, 0.9);
        border-radius: 6px;
        padding: 4px 6px;
        margin-bottom: 4px;
        font-size: 8px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 4px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .phone-thumb-block i {
        font-size: 8px;
        color: #FF9040;
    }

    /* BADGES */
    .status-badge {
        position: absolute;
        top: 14px;
        left: 14px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        z-index: 2;
        backdrop-filter: blur(8px);
    }

    .status-badge.active {
        background: rgba(16, 185, 129, 0.15);
        color: #059669;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }

    .status-badge .dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    /* CARD BODY DETAILS */
    .card-body-details {
        padding: 20px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .card-title-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 6px;
    }

    .microsite-name {
        font-size: 18px;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }

    .url-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f8f9fa;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 6px 12px;
        font-size: 12px;
        color: #FF9040;
        font-weight: 600;
        margin-bottom: 14px;
        text-decoration: none;
        width: fit-content;
    }

    .url-pill:hover {
        background: #FFF3E6;
    }

    .card-stats-tags {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .stat-tag {
        font-size: 12px;
        color: #4b5563;
        background: #f3f4f6;
        padding: 4px 10px;
        border-radius: 6px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    /* ACTIONS BUTTON GRID */
    .card-actions-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-top: auto;
    }

    .btn-action-primary {
        background: #FF9040;
        color: white;
        border: none;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-action-primary:hover {
        background: #e57c2e;
        color: white;
        box-shadow: 0 4px 12px rgba(255, 144, 64, 0.3);
    }

    .btn-action-secondary {
        background: #f3f4f6;
        color: #374151;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-action-secondary:hover {
        background: #e5e7eb;
        color: #111827;
    }

    /* CREATE NEW CARD */
    .create-new-card {
        background: #fafafa;
        border: 2px dashed #d1d5db;
        border-radius: 20px;
        padding: 30px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        min-height: 420px;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .create-new-card:hover {
        border-color: #FF9040;
        background: #FFF9F5;
        transform: translateY(-4px);
    }

    .create-icon-wrapper {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: #FFF3E6;
        color: #FF9040;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        margin-bottom: 16px;
        box-shadow: 0 4px 14px rgba(255, 144, 64, 0.2);
    }

    .create-title {
        font-size: 17px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 6px;
    }

    .create-subtitle {
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 20px;
        max-width: 240px;
    }

    /* INTERACTIVE PHONE PREVIEW MODAL */
    .preview-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(17, 24, 39, 0.75);
        backdrop-filter: blur(8px);
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .preview-modal-overlay.active {
        display: flex;
        opacity: 1;
    }

    .phone-modal-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        max-height: 90vh;
    }

    .phone-modal-header {
        display: flex;
        align-items: center;
        gap: 12px;
        background: rgba(255, 255, 255, 0.95);
        padding: 10px 20px;
        border-radius: 30px;
        margin-bottom: 16px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.2);
    }

    .phone-modal-header span {
        font-weight: 700;
        font-size: 14px;
        color: #1f2937;
    }

    .phone-device-frame {
        width: 360px;
        height: 720px;
        max-height: 75vh;
        background: #000000;
        border-radius: 46px;
        padding: 12px;
        box-shadow: 0 25px 60px -15px rgba(0,0,0,0.6);
        position: relative;
        border: 4px solid #374151;
    }

    /* iPhone Notch */
    .phone-notch {
        position: absolute;
        top: 18px;
        left: 50%;
        transform: translateX(-50%);
        width: 120px;
        height: 22px;
        background: #000000;
        border-bottom-left-radius: 14px;
        border-bottom-right-radius: 14px;
        z-index: 10;
    }

    .phone-iframe {
        width: 100%;
        height: 100%;
        border-radius: 36px;
        border: none;
        background: white;
    }

    .btn-close-modal {
        position: absolute;
        top: -15px;
        right: -15px;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: #ffffff;
        color: #111827;
        border: none;
        font-size: 18px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        z-index: 20;
    }

    /* EDITOR STYLES (WHEN MODE IS EDIT) */
    .editor-layout {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 24px;
    }

    @media (max-width: 1024px) {
        .editor-layout {
            grid-template-columns: 1fr;
        }
    }

    .editor-left-panel {
        background: white;
        border-radius: 20px;
        padding: 24px;
        border: 1px solid #eaeaea;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    }

    .editor-sticky-preview {
        position: sticky;
        top: 20px;
        background: #f8f9fa;
        border-radius: 20px;
        padding: 20px;
        border: 1px solid #eaeaea;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .block-item-card {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 14px 18px;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 14px;
        transition: all 0.2s ease;
    }

    .block-item-card:hover {
        background: #ffffff;
        border-color: #FF9040;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
</style>
@endpush

@section("content")
<div class="microsite-container">
    
    <!-- HEADER TITLE & MODE SWITCH -->
    <div class="header-title-section">
        <div>
            <h1>{{ __('sidebar.microsite') }} Management</h1>
            <p>Kelola galeri microsite, lihat screenshot preview, dan kustomisasi konten publik Anda.</p>
        </div>
        
        <div class="mode-switch-btn">
            <a href="{{ route('admin.mylinkan', ['mode' => 'gallery']) }}" class="{{ $viewMode == 'gallery' ? 'active' : '' }}">
                <i class="fas fa-th-large"></i> Galeri Microsite
            </a>
            <a href="{{ route('admin.mylinkan', ['mode' => 'edit']) }}" class="{{ $viewMode == 'edit' ? 'active' : '' }}">
                <i class="fas fa-edit"></i> Edit Konten & Blok
            </a>
        </div>
    </div>

    <!-- STATS SUMMARY ROW -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: #FFF3E6; color: #FF9040;">
                <i class="fas fa-pager"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">1</div>
                <div class="stat-label">Microsite Aktif</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: #E6F6FF; color: #0088FF;">
                <i class="fas fa-eye"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">{{ number_format($totalViews ?? 0) }}</div>
                <div class="stat-label">Total Penayangan</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: #E6FFFA; color: #00B894;">
                <i class="fas fa-box-open"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">{{ $totalProducts ?? 0 }}</div>
                <div class="stat-label">Produk Terpasang</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: #FFF0F6; color: #E83E8C;">
                <i class="fas fa-link"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">{{ $totalShortlinks ?? 0 }}</div>
                <div class="stat-label">Shortlink Terhubung</div>
            </div>
        </div>
    </div>

    @if($viewMode == 'gallery')
        <!-- GALLERY LIST VIEW -->
        <div class="section-header">
            <h2><i class="fas fa-layer-group" style="color: #FF9040;"></i> Daftar Microsite Saya</h2>
        </div>

        <div class="microsite-gallery-grid">
            
            <!-- MAIN MICROSITE CARD WITH PHONE SCREENSHOT THUMBNAIL -->
            <div class="microsite-card">
                
                <!-- CARD HEADER & THUMBNAIL CONTAINER -->
                <div class="card-thumbnail-container">
                    <span class="status-badge active">
                        <span class="dot"></span> Live Profil Utama
                    </span>

                    <!-- REAL PHONE THUMBNAIL REPRESENTATION -->
                    <div class="phone-thumbnail">
                        <div class="phone-thumbnail-screen" style="
                            background-image: url('{{ $appearance && $appearance->background_color ? asset('images/background/' . $appearance->background_color) : '' }}');
                            background-color: {{ $appearance && $appearance->background_color ? 'transparent' : '#f8f9fa' }};
                        ">
                            <!-- Banner -->
                            <div class="phone-thumb-banner">
                                @if($appearance && $appearance->banner)
                                    <img src="{{ asset('storage/' . $appearance->banner) }}" alt="Banner">
                                @endif
                            </div>

                            <!-- Avatar -->
                            <div class="phone-thumb-avatar">
                                @if($appearance && $appearance->profile_image)
                                    <img src="{{ asset('storage/' . $appearance->profile_image) }}" alt="Avatar">
                                @else
                                    <i class="fas fa-user" style="color: #888; font-size: 16px;"></i>
                                @endif
                            </div>

                            <!-- Name & Bio -->
                            <div class="phone-thumb-name" style="color: {{ $appearance ? $appearance->theme_color : '#FF9040' }}">
                                {{ $appearance ? $appearance->name : Auth::user()->name }}
                            </div>
                            <div class="phone-thumb-bio" style="color: {{ $appearance ? $appearance->theme_color : '#666' }}">
                                {!! strip_tags($appearance->bio ?? 'Selamat datang di linkan saya!') !!}
                            </div>

                            <!-- Block Preview Snippets -->
                            @if($digitalProducts && $digitalProducts->count() > 0)
                                @foreach($digitalProducts->take(2) as $prod)
                                    <div class="phone-thumb-block">
                                        <i class="fas fa-box"></i>
                                        <span>{{ $prod->title }}</span>
                                    </div>
                                @endforeach
                            @endif

                            @if($shortlinks && $shortlinks->count() > 0)
                                @foreach($shortlinks->take(2) as $sl)
                                    <div class="phone-thumb-block">
                                        <i class="fas fa-link"></i>
                                        <span>{{ $sl->title ?: $sl->slug }}</span>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <!-- CARD BODY DETAILS -->
                <div class="card-body-details">
                    <div class="card-title-row">
                        <h3 class="microsite-name">{{ $appearance->name ?? Auth::user()->name }}</h3>
                    </div>

                    <a href="{{ url('/linkan.id/' . Auth::user()->username) }}" target="_blank" class="url-pill">
                        <i class="fas fa-globe"></i> linkan.id/{{ Auth::user()->username }}
                    </a>

                    <div class="card-stats-tags">
                        <span class="stat-tag"><i class="fas fa-eye"></i> {{ number_format($totalViews) }} views</span>
                        <span class="stat-tag"><i class="fas fa-cube"></i> {{ $digitalProducts->count() }} produk</span>
                        <span class="stat-tag"><i class="fas fa-link"></i> {{ $shortlinks->total() }} link</span>
                    </div>

                    <!-- ACTION BUTTONS -->
                    <div class="card-actions-grid">
                        <button type="button" class="btn-action-secondary" onclick="openPreviewModal('{{ url('/linkan.id/' . Auth::user()->username) }}')">
                            <i class="fas fa-mobile-alt"></i> Preview
                        </button>
                        <a href="{{ route('admin.mylinkan', ['mode' => 'edit']) }}" class="btn-action-primary">
                            <i class="fas fa-edit"></i> Edit Blok
                        </a>
                        <a href="{{ route('admin.appearance') }}" class="btn-action-secondary">
                            <i class="fas fa-paint-brush"></i> Tampilan
                        </a>
                        <button type="button" class="btn-action-secondary" onclick="copyToClipboard('{{ url('/linkan.id/' . Auth::user()->username) }}')">
                            <i class="fas fa-copy"></i> Salin Link
                        </button>
                    </div>
                </div>

            </div>

            <!-- CREATE NEW MICROSITE / TEMPLATE CARD -->
            <div class="create-new-card" onclick="showAddBlockModal()">
                <div class="create-icon-wrapper">
                    <i class="fas fa-plus"></i>
                </div>
                <div class="create-title">Tambah Blok Baru</div>
                <div class="create-subtitle">Tambahkan produk digital atau tautan singkat ke microsite Anda</div>
                <button type="button" class="btn-action-primary">
                    <i class="fas fa-plus-circle"></i> Tambah Blok
                </button>
            </div>

        </div>

    @else
        <!-- EDITOR VIEW MODE -->
        <div class="section-header">
            <h2>
                <a href="{{ route('admin.mylinkan', ['mode' => 'gallery']) }}" style="color: #6b7280; text-decoration: none; margin-right: 10px;">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <i class="fas fa-sliders-h" style="color: #FF9040;"></i> Editor Konten & Blok
            </h2>
            <button class="btn-action-primary" onclick="showAddBlockModal()">
                <i class="fas fa-plus"></i> Add New Block
            </button>
        </div>

        <div class="editor-layout">
            <!-- LEFT PANEL: BLOCK MANAGEMENT -->
            <div class="editor-left-panel">
                
                <!-- URL HEADER BAR -->
                <div style="background: #f9fafb; padding: 14px 18px; border-radius: 12px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; border: 1px solid #e5e7eb;">
                    <span style="font-weight: 600; font-size: 14px; color: #374151;">
                        <i class="fas fa-link" style="color: #FF9040; margin-right: 8px;"></i> {{ url('/linkan.id/' . Auth::user()->username) }}
                    </span>
                    <button type="button" class="btn-action-secondary" style="padding: 6px 12px; font-size: 12px;" onclick="copyToClipboard('{{ url('/linkan.id/' . Auth::user()->username) }}')">
                        <i class="fas fa-copy"></i> Salin
                    </button>
                </div>

                <!-- DIGITAL PRODUCTS BLOCKS -->
                @if($digitalProducts->count())
                    <div style="margin-bottom: 30px;">
                        <h3 style="font-size: 16px; font-weight: 700; color: #111827; margin-bottom: 14px;">Produk Digital Saya</h3>
                        @foreach($digitalProducts as $product)
                            <div class="block-item-card" onclick="showActionModal({{ $product->id }}, '{{ $product->title }}')">
                                <i class="fas fa-grip-vertical" style="color: #9ca3af; cursor: move;"></i>
                                <div style="width: 36px; height: 36px; border-radius: 8px; background: #FFE5D3; color: #FF9040; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <div style="flex: 1; min-width: 0;">
                                    <div style="font-weight: 700; font-size: 14px; color: #111827;">{{ $product->title }}</div>
                                    @if($product->verification_status == 'pending')
                                        <span class="status pending" style="font-size: 11px; background: #fff3cd; color: #856404; padding: 2px 8px; border-radius: 4px;">Menunggu Verifikasi</span>
                                    @elseif($product->verification_status == 'rejected')
                                        <span class="status rejected" style="font-size: 11px; background: #f8d7da; color: #721c24; padding: 2px 8px; border-radius: 4px;">Ditolak</span>
                                    @else
                                        <span class="status approved" style="font-size: 11px; background: #d4edda; color: #155724; padding: 2px 8px; border-radius: 4px;">Terverifikasi</span>
                                    @endif
                                </div>
                                <i class="fas fa-ellipsis-v" style="color: #6b7280; cursor: pointer;"></i>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- SHORTLINKS BLOCKS -->
                @if($shortlinks->count())
                    <div>
                        <h3 style="font-size: 16px; font-weight: 700; color: #111827; margin-bottom: 14px;">Tautan Pendek (Shortlinks)</h3>
                        @foreach($shortlinks as $link)
                            <div class="block-item-card" onclick="window.location.href='{{ route('admin.shortlinks.index') }}'">
                                <i class="fas fa-grip-vertical" style="color: #9ca3af; cursor: move;"></i>
                                <div style="width: 36px; height: 36px; border-radius: 8px; background: #E6F6FF; color: #0088FF; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-link"></i>
                                </div>
                                <div style="flex: 1; min-width: 0;">
                                    <div style="font-weight: 700; font-size: 14px; color: #111827;">{{ $link->title ?: $link->slug }}</div>
                                    <div style="font-size: 12px; color: #FF9040;">linkan.id/{{ $link->slug }}</div>
                                </div>
                                <i class="fas fa-external-link-alt" style="color: #6b7280;"></i>
                            </div>
                        @endforeach
                        <div style="margin-top: 15px;">
                            {{ $shortlinks->appends(request()->except('links_page'))->links() }}
                        </div>
                    </div>
                @endif
            </div>

            <!-- RIGHT PANEL: STICKY PHONE PREVIEW -->
            <div class="editor-sticky-preview">
                <h3 style="font-size: 16px; font-weight: 700; color: #1f2937; margin-bottom: 14px;">Live Phone Preview</h3>
                <div class="phone-preview" style="width: 100%; max-width: 320px; aspect-ratio: 9/19; border-radius: 36px; background: white; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border: 4px solid #111827;">
                    <div class="phone-content" style="width: 100%; height: 100%; padding: 18px 14px; overflow-y: auto; background-image: url('{{ $appearance && $appearance->background_color ? asset('images/background/' . $appearance->background_color) : '' }}'); background-size: cover; background-position: center;">
                        @if($appearance && $appearance->banner)
                            <div style="width: 100%; height: 100px; border-radius: 10px; overflow: hidden; margin-bottom: 14px;">
                                <img src="{{ asset('storage/' . $appearance->banner) }}" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        @endif

                        <div style="width: 70px; height: 70px; border-radius: 50%; overflow: hidden; margin: 0 auto 10px; background: #e5e7eb; display: flex; align-items: center; justify-content: center;">
                            @if($appearance && $appearance->profile_image)
                                <img src="{{ asset('storage/' . $appearance->profile_image) }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <i class="fas fa-user" style="font-size: 24px; color: #888;"></i>
                            @endif
                        </div>

                        <div style="font-size: 16px; font-weight: 700; text-align: center; margin-bottom: 6px; color: {{ $appearance ? $appearance->theme_color : '#FF9040' }}">
                            {{ $appearance ? $appearance->name : Auth::user()->name }}
                        </div>

                        @if($appearance && $appearance->bio)
                            <div style="font-size: 12px; text-align: center; margin-bottom: 14px; color: {{ $appearance ? $appearance->theme_color : '#666' }}">
                                {!! $appearance->bio !!}
                            </div>
                        @endif

                        @if($digitalProducts->count() > 0)
                            <div style="display: flex; flex-direction: column; gap: 8px; width: 100%;">
                                @foreach($digitalProducts as $product)
                                    <div style="background: white; border-radius: 8px; padding: 8px 10px; display: flex; align-items: center; gap: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                        <div style="width: 32px; height: 32px; border-radius: 6px; background: #FFE5D3; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                            <i class="fas fa-file-alt" style="color: #FF9040; font-size: 12px;"></i>
                                        </div>
                                        <div style="font-size: 12px; font-weight: 600; color: #333; flex: 1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $product->title }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>

<!-- INTERACTIVE PHONE PREVIEW MODAL -->
<div class="preview-modal-overlay" id="previewModalOverlay">
    <div class="phone-modal-wrapper">
        <button type="button" class="btn-close-modal" onclick="closePreviewModal()">&times;</button>
        
        <div class="phone-modal-header">
            <i class="fas fa-globe" style="color: #FF9040;"></i>
            <span id="modalUrlText">linkan.id/{{ Auth::user()->username }}</span>
            <button type="button" class="btn-action-secondary" style="padding: 4px 10px; font-size: 11px;" onclick="copyToClipboard('{{ url('/linkan.id/' . Auth::user()->username) }}')">
                <i class="fas fa-copy"></i>
            </button>
            <a href="{{ url('/linkan.id/' . Auth::user()->username) }}" target="_blank" class="btn-action-primary" style="padding: 4px 10px; font-size: 11px;">
                <i class="fas fa-external-link-alt"></i> Buka
            </a>
        </div>

        <div class="phone-device-frame">
            <div class="phone-notch"></div>
            <iframe src="" class="phone-iframe" id="phonePreviewIframe"></iframe>
        </div>
    </div>
</div>

<!-- MODAL ADD BLOCK -->
<div id="addBlockModal" class="modal">
    <div class="modal-content" style="border-radius: 20px; overflow: hidden;">
        <div class="modal-header" style="background: #f9fafb; padding: 18px 24px; border-bottom: 1px solid #e5e7eb;">
            <h2 style="font-size: 18px; font-weight: 700; margin: 0;">Tambah Blok Baru</h2>
            <button class="close-button" onclick="closeModal()">×</button>
        </div>
        <div class="modal-body" style="padding: 24px;">
            <div class="block-option" onclick="selectBlockType('digital')" style="border: 1px solid #e5e7eb; border-radius: 14px; padding: 16px; margin-bottom: 14px; cursor: pointer; transition: all 0.2s ease;">
                <div class="block-icon">
                    <img src="{{ asset('images/productdigital.png') }}" alt="Digital Product" style="width: 28px;">
                </div>
                <div class="block-info">
                    <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 4px;">Produk Digital</h3>
                    <p style="font-size: 13px; color: #6b7280; margin: 0;">Jual produk digital, e-book, lisensi, atau file</p>
                </div>
            </div>

            <div class="block-option" onclick="selectBlockType('shortlink')" style="border: 1px solid #e5e7eb; border-radius: 14px; padding: 16px; cursor: pointer; transition: all 0.2s ease;">
                <div class="block-icon">
                    <i class="fas fa-link" style="color: #FF9040; font-size: 24px;"></i>
                </div>
                <div class="block-info">
                    <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 4px;">Tautan Pendek</h3>
                    <p style="font-size: 13px; color: #6b7280; margin: 0;">Buat dan hubungkan tautan singkat kustom</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ACTION MODAL & DELETE CONFIRMATION -->
<div id="actionModal" class="modal">
    <div class="modal-content" style="border-radius: 20px; padding: 20px;">
        <div class="modal-header">
            <h2>Aksi Produk</h2>
            <button class="close-button" onclick="closeActionModal()">×</button>
        </div>
        <div class="modal-body" style="padding-top: 14px;">
            <a href="#" id="editButton" class="btn-action-primary" style="margin-bottom: 10px; width: 100%;">
                <i class="fas fa-edit"></i> Edit Produk
            </a>
            <form id="deleteForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <button type="button" class="btn-action-secondary" style="color: #ef4444; border-color: #fca5a5; width: 100%;" onclick="confirmDelete()">
                    <i class="fas fa-trash"></i> Hapus Produk
                </button>
            </form>
        </div>
    </div>
</div>

<div id="confirmDeleteModal" class="modal">
    <div class="modal-content" style="border-radius: 20px; padding: 24px;">
        <div class="modal-header">
            <h2>Konfirmasi Hapus</h2>
            <button class="close-button" onclick="closeConfirmDeleteModal()">×</button>
        </div>
        <div class="modal-body">
            <p id="deleteMessage" style="margin-bottom: 20px; font-size: 14px; color: #4b5563;"></p>
            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeConfirmDeleteModal()" class="btn-action-secondary">Batal</button>
                <form id="finalDeleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-action-primary" style="background: #ef4444;">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push("scripts")
<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert('Tautan berhasil disalin ke clipboard!');
        }).catch(err => {
            console.error('Gagal menyalin teks: ', err);
        });
    }

    function openPreviewModal(url) {
        const modal = document.getElementById('previewModalOverlay');
        const iframe = document.getElementById('phonePreviewIframe');
        iframe.src = url;
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closePreviewModal() {
        const modal = document.getElementById('previewModalOverlay');
        const iframe = document.getElementById('phonePreviewIframe');
        modal.classList.remove('active');
        iframe.src = '';
        document.body.style.overflow = 'auto';
    }

    function showAddBlockModal() {
        document.getElementById('addBlockModal').style.display = 'block';
        document.body.classList.add('modal-open');
    }

    function closeModal() {
        document.getElementById('addBlockModal').style.display = 'none';
        document.body.classList.remove('modal-open');
    }

    function selectBlockType(type) {
        if(type === 'digital') {
            window.location.href = "{{ route('admin.digital-products.create') }}";
        } else if(type === 'shortlink') {
            window.location.href = "{{ route('admin.shortlinks.index') }}";
        }
    }

    function showActionModal(productId, productTitle) {
        window.currentDeleteId = productId;
        window.currentDeleteTitle = productTitle;
        document.getElementById('deleteForm').action = `/admin/digital-products/${productId}`;
        document.getElementById('editButton').href = `/admin/digital-products/${productId}/edit`;
        document.getElementById('actionModal').style.display = 'block';
        document.body.classList.add('modal-open');
    }

    function closeActionModal() {
        document.getElementById('actionModal').style.display = 'none';
        document.body.classList.remove('modal-open');
    }

    function confirmDelete() {
        const title = window.currentDeleteTitle;
        const productId = window.currentDeleteId;
        document.getElementById('deleteMessage').innerText = `Apakah Anda yakin ingin menghapus produk "${title}"?`;
        document.getElementById('finalDeleteForm').action = `/admin/digital-products/${productId}`;
        closeActionModal();
        document.getElementById('confirmDeleteModal').style.display = 'block';
        document.body.classList.add('modal-open');
    }

    function closeConfirmDeleteModal() {
        document.getElementById('confirmDeleteModal').style.display = 'none';
        document.body.classList.remove('modal-open');
    }

    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            closeModal();
            closeActionModal();
            closeConfirmDeleteModal();
        }
        if (event.target.id === 'previewModalOverlay') {
            closePreviewModal();
        }
    }
</script>
@endpush
