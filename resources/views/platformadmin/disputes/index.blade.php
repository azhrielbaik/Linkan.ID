<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Manajemen Sengketa & Refund — Platform Admin</title>
    @include('platformadmin.partials.head_assets')
    <link rel="stylesheet" href="{{ asset('css/platform/transactions.css') }}">
    <link rel="stylesheet" href="{{ asset('css/platform/disputes.css') }}?v={{ file_exists(public_path('css/platform/disputes.css')) ? filemtime(public_path('css/platform/disputes.css')) : time() }}">
</head>
<body>

    {{-- Sidebar --}}
    @include('platformadmin.sidebar.sidebarplatform')

    <div class="platform-main">
        {{-- Header --}}
        <div class="platform-header">
            <div class="platform-header-left">
                <button type="button" class="hamburger-btn" onclick="toggleSidebar()" aria-label="Toggle Sidebar" title="Buka/Tutup Menu"><i class="fas fa-bars"></i></button>
                <h1>Manajemen Sengketa & Refund</h1>
            </div>
            <div class="header-right">
                @include('platformadmin.partials.notifications')
                @include('platformadmin.partials.header_profile')
            </div>
        </div>

        <div class="content-wrapper">

            {{-- Alert Notifikasi Session --}}
            @if(session('success'))
                <div class="alert alert-success" style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 14px 18px; border-radius: 12px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; font-size: 13px; font-weight: 600;">
                    <i class="fas fa-check-circle" style="color: #10b981; font-size: 16px;"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger" style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 14px 18px; border-radius: 12px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; font-size: 13px; font-weight: 600;">
                    <i class="fas fa-exclamation-circle" style="color: #ef4444; font-size: 16px;"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            {{-- 1. Stats Grid (Standard Platform Admin Theme) --}}
            <div class="stats-grid">
                {{-- Total Sengketa --}}
                <div class="stat-card volume">
                    <div class="stat-icon-wrapper"><i class="fas fa-scale-balanced"></i></div>
                    <div class="stat-info">
                        <div class="stat-label">Total Sengketa</div>
                        <div class="stat-val">{{ number_format($metrics['total']) }} Kasus</div>
                        <div class="stat-sub">Seluruh Komplain Pembeli</div>
                    </div>
                </div>

                {{-- Menunggu Tindakan --}}
                <div class="stat-card pending">
                    <div class="stat-icon-wrapper"><i class="fas fa-clock"></i></div>
                    <div class="stat-info">
                        <div class="stat-label">Perlu Tindakan</div>
                        <div class="stat-val">{{ number_format($metrics['pending']) }} Kasus</div>
                        <div class="stat-sub">Menunggu Keputusan Admin</div>
                    </div>
                </div>

                {{-- Dana Dibekukan --}}
                <div class="stat-card failed">
                    <div class="stat-icon-wrapper"><i class="fas fa-hand-holding-dollar"></i></div>
                    <div class="stat-info">
                        <div class="stat-label">Dana Dibekukan (Hold)</div>
                        <div class="stat-val">Rp {{ number_format($metrics['frozen_amount'], 0, ',', '.') }}</div>
                        <div class="stat-sub">Saldo Seller Ditahan</div>
                    </div>
                </div>

                {{-- Refund Disetujui --}}
                <div class="stat-card success">
                    <div class="stat-icon-wrapper"><i class="fas fa-rotate-left"></i></div>
                    <div class="stat-info">
                        <div class="stat-label">Refund Disetujui</div>
                        <div class="stat-val">Rp {{ number_format($metrics['refunded_amount'], 0, ',', '.') }}</div>
                        <div class="stat-sub">{{ $metrics['refunded'] }} Kasus Selesai</div>
                    </div>
                </div>
            </div>

            {{-- 2. Expanding Capsule Tabs Navigation --}}
            <div class="tabs-container">
                <a href="{{ route('platform-admin.disputes.index', array_merge(request()->except('status', 'page'), ['status' => 'all'])) }}"
                   class="tab-link {{ ($status ?? 'all') === 'all' ? 'active' : '' }}">
                    <i class="fas fa-scale-balanced"></i> <span class="tab-label">Semua Sengketa ({{ $metrics['total'] }})</span>
                </a>
                <a href="{{ route('platform-admin.disputes.index', array_merge(request()->except('status', 'page'), ['status' => 'pending'])) }}"
                   class="tab-link {{ ($status ?? '') === 'pending' ? 'active' : '' }}">
                    <i class="fas fa-clock"></i> <span class="tab-label">Menunggu Tindakan</span>
                    @if($metrics['pending'] > 0)
                        <span class="tab-counter">{{ $metrics['pending'] }}</span>
                    @endif
                </a>
                <a href="{{ route('platform-admin.disputes.index', array_merge(request()->except('status', 'page'), ['status' => 'under_review'])) }}"
                   class="tab-link {{ ($status ?? '') === 'under_review' ? 'active' : '' }}">
                    <i class="fas fa-magnifying-glass"></i> <span class="tab-label">Dalam Investigasi ({{ $metrics['under_review'] }})</span>
                </a>
                <a href="{{ route('platform-admin.disputes.index', array_merge(request()->except('status', 'page'), ['status' => 'resolved_refunded'])) }}"
                   class="tab-link {{ ($status ?? '') === 'resolved_refunded' ? 'active' : '' }}">
                    <i class="fas fa-check-circle"></i> <span class="tab-label">Refund Selesai ({{ $metrics['refunded'] }})</span>
                </a>
                <a href="{{ route('platform-admin.disputes.index', array_merge(request()->except('status', 'page'), ['status' => 'resolved_rejected'])) }}"
                   class="tab-link {{ ($status ?? '') === 'resolved_rejected' ? 'active' : '' }}">
                    <i class="fas fa-times-circle"></i> <span class="tab-label">Ditolak ({{ $metrics['rejected'] }})</span>
                </a>
            </div>

            {{-- 3. Filter Card (Integrated Platform Admin Search Bar) --}}
            <div class="filter-card">
                <form method="GET" action="{{ route('platform-admin.disputes.index') }}" class="search-form">
                    @if($status !== 'all')
                        <input type="hidden" name="status" value="{{ $status }}">
                    @endif

                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Kode Sengketa, Order ID, Pembeli, Seller...">
                    </div>

                    <div class="date-picker-box" data-start-name="start_date" data-end-name="end_date" data-start-value="{{ request('start_date') ?? '' }}" data-end-value="{{ request('end_date') ?? '' }}" data-placeholder="{{ __('platform.filter_by_date') }}">
                        <i class="fas fa-calendar-alt date-picker-icon"></i>
                        <span class="date-range-display">{{ __('platform.filter_by_date') }}</span>
                        <button type="button" class="date-range-clear-btn" title="Reset Tanggal" style="display: none;"><i class="fas fa-times"></i></button>
                        <input type="hidden" name="start_date" value="{{ request('start_date') ?? '' }}" class="date-range-hidden-input">
                        <input type="hidden" name="end_date" value="{{ request('end_date') ?? '' }}" class="date-range-hidden-input">
                    </div>

                    <button type="submit" class="btn-filter"><i class="fas fa-filter"></i> {{ __('platform.filter') }}</button>
                    @if(request('search') || request('start_date') || request('end_date'))
                        <a href="{{ route('platform-admin.disputes.index', ['status' => $status]) }}" class="btn-reset">{{ __('platform.reset') }}</a>
                    @endif
                </form>
            </div>

            {{-- 4. Table Card (Clean Platform Admin Table Format) --}}
            <div class="table-card">
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 44px; text-align: center;">#</th>
                                <th style="width: 17%;"><i class="fas fa-hashtag"></i> Kode & Waktu</th>
                                <th style="width: 18%;"><i class="fas fa-receipt"></i> Order ID & Produk</th>
                                <th style="width: 15%;"><i class="fas fa-user"></i> Pembeli</th>
                                <th style="width: 13%;"><i class="fas fa-store"></i> Seller</th>
                                <th style="width: 12%;"><i class="fas fa-money-bill-wave"></i> Nominal</th>
                                <th style="width: 12%;"><i class="fas fa-building-columns"></i> Tujuan Refund</th>
                                <th style="width: 13%;"><i class="fas fa-chart-line"></i> Status</th>
                                <th style="width: 80px; text-align: center;"><i class="fas fa-cog"></i> Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($disputes as $index => $dsp)
                                <tr>
                                    <td style="text-align: center;">
                                        <span class="table-index-badge">{{ $disputes->firstItem() + $index }}</span>
                                    </td>
                                    <td>
                                        <div style="font-weight: 800; color: #0f172a;">{{ $dsp->dispute_code }}</div>
                                        <div style="font-size: 11px; color: #94a3b8; margin-top: 2px;">{{ $dsp->created_at->format('d M Y, H:i') }}</div>
                                        <div style="margin-top: 4px;">
                                            <span class="dsp-reason-tag">{{ $dsp->reason_label }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="order-code">{{ $dsp->order_id }}</div>
                                        <div class="product-title" style="margin-top: 3px;">{{ Str::limit($dsp->product->title ?? 'Produk Digital', 28) }}</div>
                                    </td>
                                    <td>
                                        <div class="buyer-name">{{ $dsp->buyer_name }}</div>
                                        <div class="buyer-email">{{ $dsp->buyer_email }}</div>
                                        @if($dsp->buyer_phone)
                                            <div style="font-size: 11px; color: #16a34a; margin-top: 2px;">
                                                <i class="fab fa-whatsapp"></i> {{ $dsp->buyer_phone }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div style="font-weight: 700; color: #0f172a;">{{ $dsp->seller->name ?? 'Seller Linkan' }}</div>
                                        <div style="font-size: 11.5px; color: #64748b;">{{ $dsp->seller->email ?? '-' }}</div>
                                    </td>
                                    <td>
                                        <div class="amount-text">Rp {{ number_format($dsp->amount, 0, ',', '.') }}</div>
                                        @if(in_array($dsp->status, ['pending', 'under_review']))
                                            <div style="margin-top: 3px;">
                                                <span style="font-size: 10px; font-weight: 700; color: #dc2626; background: #fee2e2; padding: 1px 6px; border-radius: 4px; display: inline-flex; align-items: center; gap: 3px;">
                                                    <i class="fas fa-lock"></i> Saldo Tertahan
                                                </span>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div style="font-weight: 700; color: #0f172a; font-size: 12px;">{{ $dsp->refund_bank_name }}</div>
                                        <div style="font-family: monospace; font-size: 11.5px; color: #334155;">{{ $dsp->refund_account_number }}</div>
                                        <div style="font-size: 11px; color: #64748b;">a.n {{ Str::limit($dsp->refund_account_name, 16) }}</div>
                                    </td>
                                    <td>
                                        @if($dsp->status === 'pending')
                                            <span class="badge-dsp-pending">
                                                <i class="fas fa-clock"></i> Menunggu Tindakan
                                            </span>
                                        @elseif($dsp->status === 'under_review')
                                            <span class="badge-dsp-review">
                                                <i class="fas fa-magnifying-glass"></i> Dalam Investigasi
                                            </span>
                                        @elseif($dsp->status === 'resolved_refunded')
                                            <span class="badge-dsp-refunded">
                                                <i class="fas fa-check-circle"></i> Refund Selesai
                                            </span>
                                        @elseif($dsp->status === 'resolved_rejected')
                                            <span class="badge-dsp-rejected">
                                                <i class="fas fa-times-circle"></i> Ditolak
                                            </span>
                                        @endif
                                    </td>
                                    <td style="text-align: center;">
                                        <a href="{{ route('platform-admin.disputes.show', $dsp->id) }}" class="btn-dsp-action" title="Lihat Detail Investigasi">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9">
                                        <div class="empty-state">
                                            <i class="fas fa-scale-balanced" style="font-size: 32px; color: #cbd5e1; margin-bottom: 8px;"></i>
                                            <p>Belum ada data sengketa atau komplain pembeli yang ditemukan.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($disputes->hasPages())
                    <div class="pagination-container" style="padding: 16px 8px 6px 8px;">
                        {{ $disputes->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    @vite(['resources/js/app.js'])
    <script src="{{ asset('js/platform/notifications.js') }}"></script>
</body>
</html>
