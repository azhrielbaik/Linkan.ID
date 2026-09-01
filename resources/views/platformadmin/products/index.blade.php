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
    <link rel="stylesheet" href="{{ asset('css/platform/products.css') }}">
    <link rel="stylesheet" href="{{ asset('css/platform/tabs.css') }}">
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

                <div class="stat-card pending">
                    <div class="stat-icon-wrapper"><i class="fas fa-clock"></i></div>
                    <div class="stat-info">
                        <div class="stat-label">{{ __('platform.pending_verification') }}</div>
                        <div class="stat-val">{{ number_format($pendingCount, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>

            {{-- Tabs --}}
            <div class="tabs-container">
                <a href="{{ route('platform-admin.products.index', array_merge(request()->except('tab', 'page'), ['tab' => 'all'])) }}"
                   class="tab-link {{ ($tab ?? 'all') === 'all' ? 'active' : '' }}">
                    <i class="fas fa-boxes"></i> {{ __('platform.all_products') }} ({{ $totalProductsCount }})
                </a>
                <a href="{{ route('platform-admin.products.index', array_merge(request()->except('tab', 'page'), ['tab' => 'active'])) }}"
                   class="tab-link {{ ($tab ?? '') === 'active' ? 'active' : '' }}">
                    <i class="fas fa-check-circle"></i> {{ __('platform.active_products') }} ({{ $activeProductsCount }})
                </a>
                <a href="{{ route('platform-admin.products.index', array_merge(request()->except('tab', 'page'), ['tab' => 'takedown'])) }}"
                   class="tab-link {{ ($tab ?? '') === 'takedown' ? 'active' : '' }}">
                    <i class="fas fa-ban"></i> {{ __('platform.takedown_products') }} ({{ $takedownCount }})
                </a>
                <a href="{{ route('platform-admin.products.index', array_merge(request()->except('tab', 'page'), ['tab' => 'pending'])) }}"
                   class="tab-link {{ ($tab ?? '') === 'pending' ? 'active' : '' }}">
                    <i class="fas fa-clock"></i> {{ __('platform.pending_verification') }} ({{ $pendingCount }})
                </a>
            </div>

            {{-- Filter & Search Card --}}
            <div class="filter-card">
                <form method="GET" action="{{ route('platform-admin.products.index') }}" class="filter-form">
                    <input type="hidden" name="tab" value="{{ $tab }}">

                    <div class="filter-row-top">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="{{ __('platform.search_product_placeholder') }}">
                        </div>

                        {{-- Filter by Seller --}}
                        <select name="seller_id" class="filter-select">
                            <option value="">{{ __('platform.filter_seller') }}</option>
                            @foreach($sellers as $s)
                                <option value="{{ $s->id }}" {{ ($sellerId ?? '') == $s->id ? 'selected' : '' }}>
                                    {{ $s->name }} ({{ $s->email }})
                                </option>
                            @endforeach
                        </select>

                        {{-- Filter by Platform / Kategori --}}
                        <select name="platform_type" class="filter-select">
                            <option value="">{{ __('platform.filter_platform') }}</option>
                            <option value="upload" {{ ($platformType ?? '') === 'upload' ? 'selected' : '' }}>Upload File</option>
                            <option value="dropbox" {{ ($platformType ?? '') === 'dropbox' ? 'selected' : '' }}>Dropbox</option>
                            <option value="gdrive" {{ ($platformType ?? '') === 'gdrive' ? 'selected' : '' }}>G-Drive</option>
                            <option value="other" {{ ($platformType ?? '') === 'other' ? 'selected' : '' }}>Lainnya / Other</option>
                        </select>

                        {{-- Filter by Verifikasi --}}
                        <select name="verification_status" class="filter-select">
                            <option value="">{{ __('platform.filter_verification') }}</option>
                            <option value="approved" {{ ($verificationStatus ?? '') === 'approved' ? 'selected' : '' }}>{{ __('platform.approved') }}</option>
                            <option value="pending" {{ ($verificationStatus ?? '') === 'pending' ? 'selected' : '' }}>{{ __('platform.pending_status') }}</option>
                            <option value="rejected" {{ ($verificationStatus ?? '') === 'rejected' ? 'selected' : '' }}>{{ __('platform.rejected') }}</option>
                        </select>
                    </div>

                    <div class="filter-row-bottom">
                        {{-- Filter Rentang Harga --}}
                        <input type="number" name="min_price" value="{{ $minPrice ?? '' }}" placeholder="{{ __('platform.min_price') }} (Rp)" class="price-input" min="0">
                        <span style="color: #94a3b8;">-</span>
                        <input type="number" name="max_price" value="{{ $maxPrice ?? '' }}" placeholder="{{ __('platform.max_price') }} (Rp)" class="price-input" min="0">

                        {{-- Filter Rentang Tanggal --}}
                        <div class="date-picker-box" data-start-name="start_date" data-end-name="end_date" data-start-value="{{ $startDate ?? '' }}" data-end-value="{{ $endDate ?? '' }}" data-placeholder="Tanggal Upload">
                            <i class="fas fa-calendar-alt date-picker-icon"></i>
                            <span class="date-range-display">Tanggal Upload</span>
                            <button type="button" class="date-range-clear-btn" title="Reset Tanggal" style="display: none;"><i class="fas fa-times"></i></button>
                            <input type="hidden" name="start_date" value="{{ $startDate ?? '' }}" class="date-range-hidden-input">
                            <input type="hidden" name="end_date" value="{{ $endDate ?? '' }}" class="date-range-hidden-input">
                        </div>

                        {{-- Sort By --}}
                        <select name="sort" class="filter-select">
                            <option value="latest" {{ ($sortBy ?? '') === 'latest' ? 'selected' : '' }}>{{ __('platform.sort_latest') }}</option>
                            <option value="oldest" {{ ($sortBy ?? '') === 'oldest' ? 'selected' : '' }}>{{ __('platform.sort_oldest') }}</option>
                            <option value="price_low" {{ ($sortBy ?? '') === 'price_low' ? 'selected' : '' }}>{{ __('platform.sort_price_low') }}</option>
                            <option value="price_high" {{ ($sortBy ?? '') === 'price_high' ? 'selected' : '' }}>{{ __('platform.sort_price_high') }}</option>
                        </select>

                        <button type="submit" class="btn-filter"><i class="fas fa-filter"></i> {{ __('platform.filter') }}</button>
                        @if($search || $sellerId || $platformType || $verificationStatus || $minPrice || $maxPrice || $startDate || $endDate || ($sortBy && $sortBy !== 'latest'))
                            <a href="{{ route('platform-admin.products.index', ['tab' => $tab]) }}" class="btn-reset">{{ __('platform.reset') }}</a>
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
                                <th>#</th>
                                <th>{{ __('platform.content') }}</th>
                                <th>{{ __('platform.seller') }}</th>
                                <th>{{ __('platform.price') }}</th>
                                <th>{{ __('platform.platform_type') }}</th>
                                <th>{{ __('platform.status') }}</th>
                                <th style="text-align: center;">{{ __('platform.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $index => $p)
                            <tr>
                                <td style="font-weight: 700; color: #94a3b8;">
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
                                            <div class="product-desc">{{ Str::limit($p->description, 50) }}</div>
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
                                    {{-- Status Verifikasi --}}
                                    <div style="margin-bottom: 4px;">
                                        @if($p->verification_status === 'approved')
                                            <span class="badge badge-approved">
                                                <i class="fas fa-check-circle"></i> {{ __('platform.approved') }}
                                            </span>
                                        @elseif($p->verification_status === 'rejected')
                                            <span class="badge badge-rejected">
                                                <i class="fas fa-times-circle"></i> {{ __('platform.rejected') }}
                                            </span>
                                        @else
                                            <span class="badge badge-pending">
                                                <i class="fas fa-clock"></i> {{ __('platform.pending_status') }}
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Status Live / Takedown --}}
                                    <div>
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
                                            <button type="button" class="btn-act btn-takedown" onclick="showTakedownModal({{ $p->id }}, '{{ addslashes($p->title) }}', '{{ addslashes($p->user->name ?? 'Seller') }}')">
                                                <i class="fas fa-ban"></i> {{ __('platform.takedown') }}
                                            </button>
                                        @else
                                            <form action="{{ route('platform-admin.products.restore', $p->id) }}" method="POST" style="margin: 0;">
                                                @csrf
                                                <button type="button" class="btn-act btn-restore" onclick="confirmRestoreProduct(this.form, '{{ addslashes($p->title) }}')">
                                                    <i class="fas fa-undo"></i> {{ __('platform.restore_product') }}
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Tombol View Platform --}}
                                        <button type="button" class="btn-act btn-view-platform" onclick="showPlatformModal({{ $p->id }}, '{{ $p->platform_type }}', '{{ $p->platform_url }}', '{{ $p->platform_file }}')">
                                            <i class="fas fa-eye"></i> {{ __('platform.view_platform') }}
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

    <!-- Modal Platform Details -->
    <div id="platformModal" class="modal">
        <div class="modal-card">
            <div class="modal-header">
                <h3><i class="fas fa-info-circle" style="color: #ED842C; margin-right: 6px;"></i>{{ __('platform.platform_details') }}</h3>
                <button class="modal-close" onclick="closePlatformModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group-custom">
                    <label>{{ __('platform.platform_type') }}</label>
                    <p id="platformType" style="font-weight: 700; color: #ED842C; font-size: 15px;"></p>
                </div>
                <div class="form-group-custom" id="platformUrlGroup">
                    <label>{{ __('platform.platform_url') }}</label>
                    <p id="platformUrl" style="word-break: break-all;"></p>
                </div>
                <div class="form-group-custom" id="platformFileGroup">
                    <label>{{ __('platform.platform_file') }}</label>
                    <p id="platformFile"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-modal-cancel" onclick="closePlatformModal()">{{ __('platform.close') }}</button>
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
    <script src="{{ asset('js/platform/notifications.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/platform/products.js') }}"></script>
</body>
</html>
