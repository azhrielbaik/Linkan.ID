<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('platform.product_management') }} — Platform Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/platform/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/platform/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/platform/notifications.css') }}">
    <link rel="stylesheet" href="{{ asset('css/platform/products.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/platform/tabs.css') }}">
    <link rel="stylesheet" href="{{ asset('css/platform/custom-datepicker.css') }}?v={{ time() }}">
</head>
<body>

    {{-- Sidebar --}}
    @include('platformadmin.sidebar.sidebarplatform')

    <div class="platform-main">
        {{-- Header --}}
        <div class="platform-header">
            <div class="platform-header-left">
                <button class="hamburger-btn" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
                <h1>{{ __('platform.product_management') }}</h1>
            </div>
            <div class="header-right">
                @include('platformadmin.partials.notifications')
                @include('platformadmin.partials.header_profile')
            </div>
        </div>

        <div class="content-wrapper">
            {{-- Stats Grid --}}
            <div class="stats-grid">
                <div class="stat-card total">
                    <div class="stat-icon-wrapper"><i class="fas fa-boxes"></i></div>
                    <div class="stat-info">
                        <div class="stat-label">{{ __('platform.total_products') }}</div>
                        <div class="stat-val">{{ number_format($totalProductsCount, 0, ',', '.') }}</div>
                    </div>
                </div>

                <div class="stat-card active">
                    <div class="stat-icon-wrapper"><i class="fas fa-check-circle"></i></div>
                    <div class="stat-info">
                        <div class="stat-label">{{ __('platform.active_products') }}</div>
                        <div class="stat-val">{{ number_format($activeProductsCount, 0, ',', '.') }}</div>
                    </div>
                </div>

                <div class="stat-card takedown">
                    <div class="stat-icon-wrapper"><i class="fas fa-ban"></i></div>
                    <div class="stat-info">
                        <div class="stat-label">{{ __('platform.takedown_products') }}</div>
                        <div class="stat-val">{{ number_format($takedownCount, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>

            {{-- Tabs --}}
            <div class="tabs-container">
                <a href="{{ route('platform-admin.products.index', array_merge(request()->except('tab', 'page'), ['tab' => 'all'])) }}"
                   class="tab-link {{ ($tab ?? 'all') === 'all' ? 'active' : '' }}">
                    <i class="fas fa-boxes"></i> <span class="tab-label">{{ __('platform.all_products') }} ({{ $totalProductsCount }})</span>
                </a>
                <a href="{{ route('platform-admin.products.index', array_merge(request()->except('tab', 'page'), ['tab' => 'active'])) }}"
                   class="tab-link {{ ($tab ?? '') === 'active' ? 'active' : '' }}">
                    <i class="fas fa-check-circle"></i> <span class="tab-label">{{ __('platform.active_products') }} ({{ $activeProductsCount }})</span>
                </a>
                <a href="{{ route('platform-admin.products.index', array_merge(request()->except('tab', 'page'), ['tab' => 'takedown'])) }}"
                   class="tab-link {{ ($tab ?? '') === 'takedown' ? 'active' : '' }}">
                    <i class="fas fa-ban"></i> <span class="tab-label">{{ __('platform.takedown_products') }} ({{ $takedownCount }})</span>
                </a>
            </div>

            {{-- Filter & Search Card --}}
            <div class="filter-card">
                <form method="GET" action="{{ route('platform-admin.products.index') }}" class="filter-form">
                    <input type="hidden" name="tab" value="{{ $tab }}">

                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="{{ __('platform.search_product_placeholder') }}">
                    </div>

                    {{-- Filter by Seller --}}
                    <select name="seller_id" class="filter-select select-seller">
                        <option value="">{{ __('platform.filter_seller') }}</option>
                        @foreach($sellers as $s)
                            <option value="{{ $s->id }}" {{ ($sellerId ?? '') == $s->id ? 'selected' : '' }}>
                                {{ $s->name }} ({{ $s->email }})
                            </option>
                        @endforeach
                    </select>

                    {{-- Filter by Platform / Kategori --}}
                    <select name="platform_type" class="filter-select select-platform">
                        <option value="">{{ __('platform.filter_platform') }}</option>
                        <option value="upload" {{ ($platformType ?? '') === 'upload' ? 'selected' : '' }}>Upload File</option>
                        <option value="dropbox" {{ ($platformType ?? '') === 'dropbox' ? 'selected' : '' }}>Dropbox</option>
                        <option value="gdrive" {{ ($platformType ?? '') === 'gdrive' ? 'selected' : '' }}>G-Drive</option>
                        <option value="other" {{ ($platformType ?? '') === 'other' ? 'selected' : '' }}>Lainnya / Other</option>
                    </select>

                    {{-- Filter Rentang Tanggal --}}
                    <div class="date-picker-box" data-start-name="start_date" data-end-name="end_date" data-placeholder="Tanggal Upload">
                        <i class="fas fa-calendar-alt date-picker-icon"></i>
                        <span class="date-range-display">{{ $startDate && $endDate ? \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') . ' - ' . \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') : ($startDate ? \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') : 'Tanggal Upload') }}</span>
                        <button type="button" class="date-range-clear-btn" title="Reset Tanggal" style="{{ $startDate ? '' : 'display: none;' }}"><i class="fas fa-times"></i></button>
                        <input type="hidden" name="start_date" value="{{ $startDate ?? '' }}" class="date-range-hidden-input">
                        <input type="hidden" name="end_date" value="{{ $endDate ?? '' }}" class="date-range-hidden-input">
                    </div>

                    {{-- Sort By --}}
                    <select name="sort" class="filter-select select-sort">
                        <option value="latest" {{ ($sortBy ?? '') === 'latest' ? 'selected' : '' }}>{{ __('platform.sort_latest') }}</option>
                        <option value="oldest" {{ ($sortBy ?? '') === 'oldest' ? 'selected' : '' }}>{{ __('platform.sort_oldest') }}</option>
                        <option value="price_low" {{ ($sortBy ?? '') === 'price_low' ? 'selected' : '' }}>{{ __('platform.sort_price_low') }}</option>
                        <option value="price_high" {{ ($sortBy ?? '') === 'price_high' ? 'selected' : '' }}>{{ __('platform.sort_price_high') }}</option>
                    </select>

                    <div class="filter-btns-group">
                        <button type="submit" class="btn-filter"><i class="fas fa-filter"></i> {{ __('platform.filter') }}</button>
                        @if($search || $sellerId || $platformType || $startDate || $endDate || ($sortBy && $sortBy !== 'latest'))
                            <a href="{{ route('platform-admin.products.index', ['tab' => $tab]) }}" class="btn-reset" title="Reset Filter">
                                <i class="fas fa-redo"></i> {{ __('platform.reset') }}
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Table Card --}}
            <div class="table-card">
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 44px; text-align: center;">#</th>
                                <th>{{ __('platform.content') }}</th>
                                <th style="width: 170px;">{{ __('platform.seller') }}</th>
                                <th style="width: 115px;">{{ __('platform.price') }}</th>
                                <th style="width: 105px;">{{ __('platform.platform_type') }}</th>
                                <th style="width: 115px;">{{ __('platform.status') }}</th>
                                <th style="text-align: center; width: 160px;">{{ __('platform.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $index => $p)
                            <tr>
                                <td style="font-weight: 700; color: #94a3b8; text-align: center;">
                                    {{ $products->firstItem() + $index }}
                                </td>
                                <td>
                                    <div class="product-cell">
                                        @if($p->image)
                                            <img src="{{ asset('storage/' . $p->image) }}" alt="{{ $p->title }}" class="product-thumb">
                                        @else
                                            <div class="product-no-thumb"><i class="fas fa-image"></i></div>
                                        @endif
                                        <div>
                                            <div class="product-title">{{ $p->title }}</div>
                                            <div class="product-desc">{{ Str::limit(strip_tags($p->description), 50) }}</div>
                                            <div style="font-size: 11px; color: #94a3b8; margin-top: 2px;">
                                                <i class="fas fa-calendar-alt"></i> {{ $p->created_at->format('d M Y') }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="seller-cell">
                                        <div class="seller-avatar">
                                            {{ strtoupper(substr($p->user->name ?? 'U', 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="seller-name">{{ $p->user->name ?? '-' }}</div>
                                            <div class="seller-email">{{ $p->user->email ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($p->sale_price)
                                        <div class="sale-price-text">Rp {{ number_format($p->sale_price, 0, ',', '.') }}</div>
                                        <div class="strike-price">Rp {{ number_format($p->price, 0, ',', '.') }}</div>
                                    @else
                                        <div class="price-text">Rp {{ number_format($p->price, 0, ',', '.') }}</div>
                                    @endif
                                    <div class="qty-badge">
                                        {{ $p->has_quantity_limit ? $p->quantity . ' Stok' : __('platform.unlimited') }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-platform">
                                        <i class="fas fa-layer-group"></i> {{ ucfirst($p->platform_type) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="status-wrapper">
                                        @if($p->is_active)
                                            <span class="badge badge-live">
                                                <i class="fas fa-circle" style="font-size: 7px;"></i> {{ __('platform.active_status') }}
                                            </span>
                                        @else
                                            <span class="badge badge-takedown">
                                                <i class="fas fa-ban"></i> {{ __('platform.takedown_status') }}
                                            </span>
                                            @if($p->takedown_reason)
                                                <div class="takedown-reason-box" title="{{ $p->takedown_reason }}">
                                                    <strong>Alasan:</strong> {{ Str::limit($p->takedown_reason, 35) }}
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="action-btns">
                                        {{-- Tombol Takedown atau Restore --}}
                                        @if($p->is_active)
                                            <button type="button" class="btn-act btn-takedown" onclick="showTakedownModal({{ $p->id }}, '{{ addslashes($p->title) }}', '{{ addslashes($p->user->name ?? 'Seller') }}')" title="{{ __('platform.takedown') }}">
                                                <i class="fas fa-ban"></i> <span>Takedown</span>
                                            </button>
                                        @else
                                            <button type="button" class="btn-act btn-restore" onclick="showRestoreModal({{ $p->id }}, '{{ addslashes($p->title) }}', '{{ addslashes($p->user->name ?? 'Seller') }}')" title="{{ __('platform.restore_product') }}">
                                                <i class="fas fa-undo"></i> <span>Pulihkan</span>
                                            </button>
                                        @endif

                                        {{-- Tombol View Platform / Detail --}}
                                        @php
                                            $mediaUrls = collect($p->media_files ?? [])->map(function($f) {
                                                if (is_array($f)) {
                                                    $path = $f['path'] ?? $f['url'] ?? '';
                                                } else {
                                                    $path = (string)$f;
                                                }
                                                return $path ? (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://']) ? $path : asset('storage/' . $path)) : null;
                                            })->filter()->values()->all();

                                            $modalData = [
                                                'id' => $p->id,
                                                'title' => (string)$p->title,
                                                'description' => (string)($p->description ?? ''),
                                                'image' => $p->image ? asset('storage/' . $p->image) : null,
                                                'price' => number_format($p->price, 0, ',', '.'),
                                                'sale_price' => $p->sale_price ? number_format($p->sale_price, 0, ',', '.') : null,
                                                'has_quantity_limit' => (bool)$p->has_quantity_limit,
                                                'quantity' => $p->quantity,
                                                'platform_type' => (string)($p->platform_type ?? 'other'),
                                                'platform_url' => (string)($p->platform_url ?? ''),
                                                'platform_file' => $p->platform_file ? asset('storage/' . $p->platform_file) : null,
                                                'platform_file_name' => $p->platform_file ? basename($p->platform_file) : null,
                                                'deliverable_type' => (string)($p->deliverable_type ?? ''),
                                                'deliverable_url' => (string)($p->deliverable_url ?? ''),
                                                'button_text' => (string)($p->button_text ?? ''),
                                                'is_active' => (bool)$p->is_active,
                                                'takedown_reason' => (string)($p->takedown_reason ?? ''),
                                                'takedown_at' => $p->takedown_at ? $p->takedown_at->format('d M Y, H:i') : null,
                                                'created_at' => $p->created_at ? $p->created_at->format('d M Y, H:i') : '-',
                                                'seller_name' => $p->user->name ?? 'Seller',
                                                'seller_email' => $p->user->email ?? '-',
                                                'seller_username' => $p->user->username ?? '',
                                                'seller_url' => $p->user ? url('/' . ($p->user->custom_link ?: $p->user->username)) : null,
                                                'total_sold' => $p->transactions ? $p->transactions->where('status', \App\Enums\TransactionStatus::SUCCESS)->count() : 0,
                                                'total_revenue' => $p->transactions ? number_format($p->transactions->where('status', \App\Enums\TransactionStatus::SUCCESS)->sum('total_price'), 0, ',', '.') : '0',
                                                'media_files' => $mediaUrls,
                                            ];
                                        @endphp
                                        <button type="button" class="btn-act btn-view-platform" onclick='showPlatformModal(@json($modalData))' title="Detail & Inspeksi Platform">
                                            <i class="fas fa-eye"></i> <span>Platform</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <i class="fas fa-boxes"></i>
                                        <p>{{ __('platform.no_products_catalog') }}</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($products->hasPages())
                    <div class="pagination-container">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    <!-- Modal Takedown Produk -->
    <div id="takedownModal" class="modal">
        <div class="modal-card">
            <div class="modal-header">
                <h3><i class="fas fa-exclamation-triangle" style="color: #ef4444; margin-right: 6px;"></i>{{ __('platform.takedown_product') }}</h3>
                <button class="modal-close" onclick="closeTakedownModal()">&times;</button>
            </div>
            <form id="takedownForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div style="background: #fef2f2; border: 1px solid #fee2e2; border-radius: 10px; padding: 12px 16px; margin-bottom: 16px; font-size: 13px; color: #991b1b;">
                        <i class="fas fa-info-circle"></i> {{ __('platform.takedown_warning') }}
                    </div>

                    <div style="margin-bottom: 14px; font-size: 14px;">
                        <div><strong>Produk:</strong> <span id="modalProductTitle">-</span></div>
                        <div><strong>{{ __('platform.seller') }}:</strong> <span id="modalProductSeller">-</span></div>
                    </div>

                    <div class="form-group-custom">
                        <label>{{ __('platform.takedown_reason') }} <span style="color: #ef4444;">*</span></label>
                        <textarea name="reason" class="form-control-custom" rows="4" required placeholder="{{ __('platform.enter_takedown_reason') }}"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal-cancel" onclick="closeTakedownModal()">{{ __('platform.cancel') }}</button>
                    <button type="submit" class="btn-modal-submit-takedown"><i class="fas fa-ban"></i> {{ __('platform.takedown') }}</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Restore Produk (Konfirmasi & Alasan Opsional) -->
    <div id="restoreModal" class="modal">
        <div class="modal-card">
            <div class="modal-header">
                <h3><i class="fas fa-undo" style="color: #10b981; margin-right: 6px;"></i>{{ __('platform.restore_product') }}</h3>
                <button class="modal-close" onclick="closeRestoreModal()">&times;</button>
            </div>
            <form id="restoreForm" method="POST" action="">
                @csrf
                <div class="modal-body">
                    <div style="background: #ecfdf5; border: 1px solid #d1fae5; border-radius: 10px; padding: 12px 16px; margin-bottom: 16px; font-size: 13px; color: #065f46;">
                        <i class="fas fa-check-circle"></i> Produk yang dipulihkan akan aktif kembali dan dapat diakses pembeli di etalase microsite seller.
                    </div>

                    <div style="margin-bottom: 14px; font-size: 14px;">
                        <div><strong>Produk:</strong> <span id="modalRestoreProductTitle">-</span></div>
                        <div><strong>{{ __('platform.seller') }}:</strong> <span id="modalRestoreProductSeller">-</span></div>
                    </div>

                    <div class="form-group-custom">
                        <label>Catatan / Alasan Pemulihan <span style="font-size: 11px; color: #64748b;">(Opsional)</span></label>
                        <textarea name="restore_reason" id="modalRestoreReason" class="form-control-custom" rows="3" placeholder="Contoh: Seller telah memperbaiki deskripsi dan file konten sesuai kebijakan platform..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal-cancel" onclick="closeRestoreModal()">{{ __('platform.cancel') }}</button>
                    <button type="submit" class="btn-modal-submit-restore"><i class="fas fa-undo"></i> {{ __('platform.restore_product') }}</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Detail & Inspeksi Platform Produk -->
    <div id="platformModal" class="modal">
        <div class="modal-card modal-card-detail">
            <div class="modal-header">
                <div class="modal-header-title">
                    <div class="modal-icon-badge"><i class="fas fa-cube"></i></div>
                    <div>
                        <h3>Detail & Inspeksi Produk</h3>
                        <p class="modal-subtitle">Informasi lengkap konten, akses platform, dan performa penjualan</p>
                    </div>
                </div>
                <button class="modal-close" onclick="closePlatformModal()">&times;</button>
            </div>
            <div class="modal-body modal-body-scrollable">
                {{-- Banner Takedown Notice (Muncul jika sedang ditakedown) --}}
                <div id="modalDetailTakedownAlert" class="detail-takedown-alert" style="display: none;">
                    <div class="alert-icon"><i class="fas fa-ban"></i></div>
                    <div class="alert-content">
                        <strong class="alert-title">Produk Sedang Ditakedown / Ditangguhkan</strong>
                        <div class="alert-date" id="modalDetailTakedownDate"></div>
                        <div class="alert-reason" id="modalDetailTakedownReason"></div>
                    </div>
                </div>

                {{-- Overview Header Card --}}
                <div class="detail-overview-card">
                    <div class="detail-thumb-box" id="modalDetailThumbBox">
                        <img id="modalDetailThumb" src="" alt="Cover Produk" class="detail-thumb-img">
                        <div id="modalDetailThumbPlaceholder" class="detail-thumb-placeholder"><i class="fas fa-box-open"></i></div>
                    </div>
                    <div class="detail-overview-info">
                        <div class="detail-title-row">
                            <h4 id="modalDetailTitle" class="detail-product-title">-</h4>
                            <span id="modalDetailStatusBadge" class="badge badge-live">Aktif</span>
                        </div>
                        <div class="detail-seller-meta">
                            <div class="detail-seller-avatar" id="modalDetailSellerAvatar">U</div>
                            <div class="detail-seller-info">
                                <span class="detail-seller-name" id="modalDetailSellerName">-</span>
                                <span class="detail-seller-email" id="modalDetailSellerEmail">-</span>
                            </div>
                            <span class="detail-created-badge" id="modalDetailCreatedAt"><i class="fas fa-calendar-alt"></i> -</span>
                        </div>
                        <div class="detail-metrics-chips">
                            <div class="metric-chip price">
                                <i class="fas fa-tag"></i>
                                <span id="modalDetailPriceDisplay">Rp 0</span>
                                <span id="modalDetailStrikePrice" class="strike-price-mini" style="display: none;"></span>
                            </div>
                            <div class="metric-chip stock" id="modalDetailStockChip">
                                <i class="fas fa-boxes"></i>
                                <span id="modalDetailStockDisplay">Unlimited</span>
                            </div>
                            <div class="metric-chip sales">
                                <i class="fas fa-shopping-bag"></i>
                                <span id="modalDetailSoldDisplay">0 Terjual</span>
                            </div>
                            <div class="metric-chip revenue">
                                <i class="fas fa-coins"></i>
                                <span id="modalDetailRevenueDisplay">Rp 0 Omzet</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Akses & Pengiriman Konten Card --}}
                <div class="detail-section-card">
                    <div class="detail-section-header">
                        <i class="fas fa-truck-loading" style="color: #ed842c;"></i>
                        <span>Akses Pengiriman Konten (*Deliverable & Fulfillment*)</span>
                    </div>

                    <div class="detail-fulfillment-grid">
                        <div class="fulfillment-row">
                            <span class="fulfillment-label">Tipe Integrasi Platform:</span>
                            <div id="modalDetailPlatformBadge" class="platform-type-pill">
                                <i class="fas fa-layer-group"></i> <span>Other</span>
                            </div>
                        </div>

                        {{-- URL Platform --}}
                        <div class="fulfillment-row" id="modalDetailUrlRow" style="display: none;">
                            <span class="fulfillment-label">Tautan Akses Konten (Platform URL):</span>
                            <div class="url-action-wrapper">
                                <input type="text" id="modalDetailUrlInput" readonly class="url-input-field">
                                <button type="button" class="btn-copy-url" onclick="copyModalUrl()" title="Salin Tautan">
                                    <i class="fas fa-copy"></i> <span id="copyUrlText">Salin</span>
                                </button>
                                <a id="modalDetailUrlLink" href="#" target="_blank" class="btn-open-url" title="Buka Link di Tab Baru">
                                    <i class="fas fa-external-link-alt"></i> Buka
                                </a>
                            </div>
                        </div>

                        {{-- Deliverable URL jika ada (Zoom/Notion/Telegram) --}}
                        <div class="fulfillment-row" id="modalDetailDeliverableRow" style="display: none;">
                            <span class="fulfillment-label">Tautan Tambahan / Deliverable URL:</span>
                            <div class="url-action-wrapper">
                                <input type="text" id="modalDetailDeliverableInput" readonly class="url-input-field">
                                <a id="modalDetailDeliverableLink" href="#" target="_blank" class="btn-open-url">
                                    <i class="fas fa-external-link-alt"></i> Akses
                                </a>
                            </div>
                        </div>

                        {{-- File Download jika ada --}}
                        <div class="fulfillment-row" id="modalDetailFileRow" style="display: none;">
                            <span class="fulfillment-label">File Digital Terunggah:</span>
                            <div class="file-action-wrapper">
                                <div class="file-meta-box">
                                    <i class="fas fa-file-archive file-icon"></i>
                                    <div class="file-name-info">
                                        <span id="modalDetailFileName" class="file-name-text">file.zip</span>
                                        <span class="file-subtext">File tersimpan aman di cloud storage Linkan</span>
                                    </div>
                                </div>
                                <a id="modalDetailFileDownloadBtn" href="#" target="_blank" download class="btn-download-asset">
                                    <i class="fas fa-download"></i> Unduh File
                                </a>
                            </div>
                        </div>

                        {{-- Jika tidak ada URL dan tidak ada File --}}
                        <div id="modalDetailNoFulfillment" class="no-fulfillment-note" style="display: none;">
                            <i class="fas fa-exclamation-circle"></i> Tidak ada file terunggah maupun link eksternal yang tercantum pada produk ini.
                        </div>
                    </div>
                </div>

                {{-- Deskripsi Lengkap Produk --}}
                <div class="detail-section-card">
                    <div class="detail-section-header">
                        <i class="fas fa-align-left" style="color: #64748b;"></i>
                        <span>Deskripsi Lengkap Produk</span>
                    </div>
                    <div class="detail-description-box" id="modalDetailDescription">
                        -
                    </div>
                </div>

                {{-- Galeri Media Tambahan (Media Files) --}}
                <div class="detail-section-card" id="modalDetailGalleryCard" style="display: none;">
                    <div class="detail-section-header">
                        <i class="fas fa-images" style="color: #64748b;"></i>
                        <span>Galeri / Screenshot Pendukung</span>
                    </div>
                    <div class="detail-gallery-grid" id="modalDetailGalleryGrid"></div>
                </div>
            </div>

            <div class="modal-footer modal-footer-split">
                <div class="footer-left">
                    <a id="modalDetailSellerStoreLink" href="#" target="_blank" class="btn-store-external" style="display: none;">
                        <i class="fas fa-store"></i> Kunjungi Etalase Seller
                    </a>
                </div>
                <div class="footer-right">
                    <button type="button" class="btn-modal-cancel" onclick="closePlatformModal()">Tutup</button>
                    <button type="button" id="modalDetailQuickTakedownBtn" class="btn-modal-action-takedown" onclick="triggerTakedownFromDetail()">
                        <i class="fas fa-ban"></i> Takedown Produk
                    </button>
                    <button type="button" id="modalDetailQuickRestoreBtn" class="btn-modal-action-restore" onclick="triggerRestoreFromDetail()" style="display: none;">
                        <i class="fas fa-undo"></i> Pulihkan Produk
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.PlatformProductsConfig = {
            productsBaseUrl: '{{ url('/platform-admin/products') }}',
            storageBaseUrl: '{{ asset('storage') }}',
            viewFileText: '{{ __('platform.view_file') }}',
            restoreProductText: '{{ __('platform.restore_product') }}'
        };
    </script>
    @vite(['resources/js/app.js'])
    <script src="{{ asset('js/platform/notifications.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/platform/custom-datepicker.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/platform/products.js') }}?v={{ time() }}"></script>
</body>
</html>
