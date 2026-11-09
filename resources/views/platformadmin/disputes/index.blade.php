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

            @if($errors->any())
                <div class="alert alert-danger" style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 14px 18px; border-radius: 12px; margin-bottom: 20px; font-size: 13px;">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
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
                                        <button type="button" class="btn-dsp-action" onclick="openDisputeDetailModal({{ $dsp->id }})" title="Lihat Detail Investigasi">
                                            <i class="fas fa-eye"></i> Detail
                                        </button>
                                        <script type="application/json" id="dsp-data-{{ $dsp->id }}">
                                            {!! json_encode([
                                                'id' => $dsp->id,
                                                'dispute_code' => $dsp->dispute_code,
                                                'order_id' => $dsp->order_id,
                                                'reason' => $dsp->reason,
                                                'reason_label' => $dsp->reason_label,
                                                'description' => $dsp->description,
                                                'evidence_file' => $dsp->evidence_file ? asset('storage/' . $dsp->evidence_file) : null,
                                                'evidence_file_ext' => $dsp->evidence_file ? strtolower(pathinfo($dsp->evidence_file, PATHINFO_EXTENSION)) : null,
                                                'amount' => (float) $dsp->amount,
                                                'amount_formatted' => 'Rp ' . number_format($dsp->amount, 0, ',', '.'),
                                                'buyer_name' => $dsp->buyer_name,
                                                'buyer_email' => $dsp->buyer_email,
                                                'buyer_phone' => $dsp->buyer_phone,
                                                'refund_bank_name' => strtoupper($dsp->refund_bank_name ?? ''),
                                                'refund_account_number' => $dsp->refund_account_number,
                                                'refund_account_name' => $dsp->refund_account_name,
                                                'status' => $dsp->status,
                                                'status_label' => $dsp->status_label,
                                                'status_badge_class' => $dsp->status_badge_class,
                                                'admin_notes' => $dsp->admin_notes,
                                                'refund_reference' => $dsp->refund_reference,
                                                'created_at_formatted' => $dsp->created_at->format('d M Y, H:i'),
                                                'resolved_at_formatted' => $dsp->resolved_at ? $dsp->resolved_at->format('d M Y, H:i') : null,
                                                'product_title' => $dsp->product->title ?? 'Produk Digital',
                                                'seller_name' => $dsp->seller->name ?? 'Seller Linkan',
                                                'seller_email' => $dsp->seller->email ?? '-',
                                                'seller_username' => $dsp->seller->username ?? '-',
                                                'transaction_payment_method' => strtoupper($dsp->transaction->payment_method ?? 'QRIS'),
                                                'transaction_created_at' => $dsp->transaction ? $dsp->transaction->created_at->format('d M Y, H:i') : '-',
                                                'transaction_status' => $dsp->transaction ? (is_object($dsp->transaction->status) ? $dsp->transaction->status->value : $dsp->transaction->status) : '-',
                                                'review_url' => route('platform-admin.disputes.review', $dsp->id),
                                                'refund_url' => route('platform-admin.disputes.refund', $dsp->id),
                                                'reject_url' => route('platform-admin.disputes.reject', $dsp->id),
                                            ]) !!}
                                        </script>
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

    {{-- Modal Detail Investigasi Sengketa (Pop up Layar) --}}
    <div id="disputeDetailModal" class="dsp-detail-modal" onclick="if(event.target === this) closeDisputeDetailModal()">
        <div class="dsp-detail-modal-card">
            <div class="dsp-detail-modal-header">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <h2 id="dspModalCode" style="margin: 0; font-size: 17px; font-weight: 800; color: #0f172a;">Kasus Sengketa</h2>
                    <div id="dspModalBadge"></div>
                </div>
                <button type="button" class="dsp-modal-close-btn" onclick="closeDisputeDetailModal()" aria-label="Tutup Modal" title="Tutup">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="dspModalBodyContent" class="dsp-detail-modal-body">
                <!-- Konten dinamis dimuat via disputes.js -->
            </div>
            <div class="dsp-detail-modal-footer">
                <button type="button" class="btn-dsp-action" onclick="closeDisputeDetailModal()">
                    <i class="fas fa-xmark"></i> Tutup
                </button>
            </div>
        </div>
    </div>

    {{-- Modal Konfirmasi Setujui Refund --}}
    <div id="modalApproveRefund" class="dsp-modal-confirm" onclick="if(event.target === this) closeRefundModal()">
        <div class="dsp-modal-box">
            <h3 style="margin: 0 0 10px 0; font-size: 16px; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-rotate-left" style="color: #059669;"></i> Setujui & Terbitkan Refund
            </h3>
            <p id="modalRefundSummary" style="font-size: 12.5px; color: #64748b; line-height: 1.5; margin-bottom: 16px;">
                Anda akan menerbitkan pengembalian dana pembeli.
            </p>

            <form id="formApproveRefund" action="" method="POST">
                @csrf
                <div style="margin-bottom: 14px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #1e293b; margin-bottom: 6px;">
                        Nomor Referensi Transfer / Bukti Pembayaran <span style="color: #dc2626;">*</span>
                    </label>
                    <input type="text" name="refund_reference" required placeholder="Contoh: TF-BCA-982312 atau No Resi/Mutasi"
                           style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 12.5px; outline: none;">
                </div>

                <div style="margin-bottom: 14px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #1e293b; margin-bottom: 6px;">
                        Catatan Internal Admin (Opsional)
                    </label>
                    <textarea name="admin_notes" rows="2" placeholder="Catatan hasil investigasi produk..."
                              style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 12.5px; outline: none;"></textarea>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #1e293b; margin-bottom: 6px;">
                        Kata Sandi Admin Anda <span style="color: #dc2626;">*</span>
                    </label>
                    <input type="password" name="admin_password" required placeholder="Konfirmasi kata sandi admin..."
                           style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 12.5px; outline: none;">
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="button" onclick="closeRefundModal()" style="padding: 8px 16px; border-radius: 8px; border: 1px solid #cbd5e1; background: #fff; font-size: 12.5px; font-weight: 600; cursor: pointer;">
                        Batal
                    </button>
                    <button type="submit" style="padding: 8px 18px; border-radius: 8px; border: none; background: #059669; color: #fff; font-size: 12.5px; font-weight: 700; cursor: pointer;">
                        <i class="fas fa-check"></i> Konfirmasi Refund
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Konfirmasi Tolak Sengketa --}}
    <div id="modalRejectDispute" class="dsp-modal-confirm" onclick="if(event.target === this) closeRejectModal()">
        <div class="dsp-modal-box">
            <h3 style="margin: 0 0 10px 0; font-size: 16px; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-times-circle" style="color: #dc2626;"></i> Tolak Pengajuan Sengketa
            </h3>
            <p id="modalRejectSummary" style="font-size: 12.5px; color: #64748b; line-height: 1.5; margin-bottom: 16px;">
                Menolak sengketa akan melepaskan penahanan saldo seller dan menutup kasus ini.
            </p>

            <form id="formRejectDispute" action="" method="POST">
                @csrf
                <div style="margin-bottom: 14px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #1e293b; margin-bottom: 6px;">
                        Alasan Penolakan Sengketa <span style="color: #dc2626;">*</span>
                    </label>
                    <textarea name="admin_notes" rows="3" required placeholder="Jelaskan alasan penolakan (misal file digital terbukti valid & aktif)..."
                              style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 12.5px; outline: none;"></textarea>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #1e293b; margin-bottom: 6px;">
                        Kata Sandi Admin Anda <span style="color: #dc2626;">*</span>
                    </label>
                    <input type="password" name="admin_password" required placeholder="Konfirmasi kata sandi admin..."
                           style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 12.5px; outline: none;">
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="button" onclick="closeRejectModal()" style="padding: 8px 16px; border-radius: 8px; border: 1px solid #cbd5e1; background: #fff; font-size: 12.5px; font-weight: 600; cursor: pointer;">
                        Batal
                    </button>
                    <button type="submit" style="padding: 8px 18px; border-radius: 8px; border: none; background: #dc2626; color: #fff; font-size: 12.5px; font-weight: 700; cursor: pointer;">
                        <i class="fas fa-times"></i> Tolak Sengketa
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        window.PlatformDisputesConfig = {
            baseUrl: '{{ route('platform-admin.disputes.index') }}',
            csrfToken: '{{ csrf_token() }}'
        };
    </script>
    @vite(['resources/js/app.js'])
    <script src="{{ asset('js/platform/notifications.js') }}"></script>
    <script src="{{ asset('js/platform/disputes.js') }}?v={{ file_exists(public_path('js/platform/disputes.js')) ? filemtime(public_path('js/platform/disputes.js')) : time() }}"></script>
</body>
</html>
