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
                    <i class="fas fa-clock"></i> <span class="tab-label">Menunggu Tindakan ({{ $metrics['pending'] }})</span>
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

                    @php
                        $startDate = request('start_date');
                        $endDate = request('end_date');
                        $dateDisplay = __('platform.filter_by_date');
                        if ($startDate && $endDate) {
                            $dateDisplay = \Carbon\Carbon::parse($startDate)->format('d M Y') . ' - ' . \Carbon\Carbon::parse($endDate)->format('d M Y');
                        } elseif ($startDate) {
                            $dateDisplay = \Carbon\Carbon::parse($startDate)->format('d M Y');
                        }
                    @endphp
                    <div class="date-picker-box" data-start-name="start_date" data-end-name="end_date" data-start-value="{{ $startDate ?? '' }}" data-end-value="{{ $endDate ?? '' }}" data-placeholder="{{ __('platform.filter_by_date') }}">
                        <i class="fas fa-calendar-alt date-picker-icon"></i>
                        <span class="date-range-display">{{ $dateDisplay }}</span>
                        <button type="button" class="date-range-clear-btn" title="Reset Tanggal" style="{{ ($startDate || $endDate) ? '' : 'display: none;' }}"><i class="fas fa-times"></i></button>
                        <input type="hidden" name="start_date" value="{{ $startDate ?? '' }}" class="date-range-hidden-input">
                        <input type="hidden" name="end_date" value="{{ $endDate ?? '' }}" class="date-range-hidden-input">
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
                    <table class="disputes-table">
                        <thead>
                            <tr>
                                <th class="col-dsp-num">#</th>
                                <th class="col-dsp-code"><i class="fas fa-shield-halved"></i> Kode & Waktu</th>
                                <th class="col-dsp-order"><i class="fas fa-receipt"></i> Order & Produk</th>
                                <th class="col-dsp-buyer"><i class="fas fa-user"></i> Pembeli</th>
                                <th class="col-dsp-seller"><i class="fas fa-store"></i> Seller</th>
                                <th class="col-dsp-amount"><i class="fas fa-money-bill-wave"></i> Nominal</th>
                                <th class="col-dsp-refund"><i class="fas fa-building-columns"></i> Tujuan Refund</th>
                                <th class="col-dsp-status"><i class="fas fa-chart-line"></i> Status</th>
                                <th class="col-dsp-action"><i class="fas fa-cog"></i> Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($disputes as $index => $dsp)
                                <tr>
                                    <td class="col-dsp-num">
                                        <span class="table-index-badge">{{ $disputes->firstItem() + $index }}</span>
                                    </td>
                                    <td class="col-dsp-code">
                                        <div class="dsp-code-val">{{ $dsp->dispute_code }}</div>
                                        <div class="dsp-time-val">{{ $dsp->created_at->format('d M Y, H:i') }}</div>
                                        <div style="margin-top: 3px;">
                                            <span class="dsp-reason-tag" title="{{ $dsp->reason_label }}">{{ $dsp->reason_label }}</span>
                                        </div>
                                    </td>
                                    <td class="col-dsp-order">
                                        <div class="order-code">{{ $dsp->order_id }}</div>
                                        <div class="product-title" title="{{ $dsp->product->title ?? 'Produk Digital' }}">{{ Str::limit($dsp->product->title ?? 'Produk Digital', 24) }}</div>
                                    </td>
                                    <td class="col-dsp-buyer">
                                        <div class="dsp-buyer-name" title="{{ $dsp->buyer_name }}">{{ Str::limit($dsp->buyer_name, 18) }}</div>
                                        <div class="dsp-buyer-email" title="{{ $dsp->buyer_email }}">{{ Str::limit($dsp->buyer_email, 22) }}</div>
                                        @if($dsp->buyer_phone)
                                            <div class="dsp-buyer-phone">
                                                <i class="fab fa-whatsapp"></i> {{ $dsp->buyer_phone }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="col-dsp-seller">
                                        <div class="dsp-seller-name" title="{{ $dsp->seller->name ?? 'Seller Linkan' }}">{{ Str::limit($dsp->seller->name ?? 'Seller Linkan', 18) }}</div>
                                        <div class="dsp-seller-email" title="{{ $dsp->seller->email ?? '-' }}">{{ Str::limit($dsp->seller->email ?? '-', 22) }}</div>
                                    </td>
                                    <td class="col-dsp-amount">
                                        <div class="amount-text">Rp {{ number_format($dsp->amount, 0, ',', '.') }}</div>
                                        @if(in_array($dsp->status, ['pending', 'under_review']))
                                            <div style="margin-top: 3px;">
                                                <span class="dsp-hold-badge">
                                                    <i class="fas fa-lock"></i> Saldo Tertahan
                                                </span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="col-dsp-refund">
                                        <div class="dsp-refund-bank">{{ $dsp->refund_bank_name }}</div>
                                        <div class="dsp-refund-acc">{{ $dsp->refund_account_number }}</div>
                                        <div class="dsp-refund-name" title="a.n {{ $dsp->refund_account_name }}">a.n {{ Str::limit($dsp->refund_account_name, 16) }}</div>
                                    </td>
                                    <td class="col-dsp-status">
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
                                    <td class="col-dsp-action">
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
