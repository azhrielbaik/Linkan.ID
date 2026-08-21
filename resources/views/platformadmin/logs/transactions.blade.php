<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Transaksi Global — Platform Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', Arial, sans-serif;
        }

        body {
            background-color: #f5f6fa;
            color: #334155;
            display: flex;
            min-height: 100vh;
        }

        /* ---- Main Layout ---- */
        .platform-main {
            margin-left: 250px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            min-width: 0;
            transition: margin-left 0.3s ease;
        }

        /* ---- Header Bar ---- */
        .platform-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #ffffff;
            padding: 16px 40px;
            border-bottom: 1px solid #f0f2f5;
            min-height: 64px;
            position: sticky;
            top: 0;
            z-index: 99;
        }

        .platform-header-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .hamburger-btn {
            display: none;
            font-size: 22px;
            color: #5A5BF1;
            cursor: pointer;
            background: none;
            border: none;
            padding: 4px 6px;
        }

        .platform-header h1 {
            font-size: 22px;
            font-weight: 800;
            color: #5A5BF1;
            letter-spacing: -0.5px;
            margin: 0;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .header-user {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #f8f9ff;
            border: 1px solid #eef0fe;
            border-radius: 30px;
            padding: 5px 14px 5px 5px;
        }

        .header-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #5A5BF1;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 13px;
        }

        .header-user span {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
        }

        /* ---- Content Wrapper ---- */
        .content-wrapper {
            padding: 28px 40px;
            flex: 1;
        }

        /* ---- Stats Grid ---- */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 20px 22px;
            box-shadow: 0 1px 6px rgba(0, 0, 0, 0.04);
            display: flex;
            align-items: center;
            gap: 16px;
            border: 1px solid #f0f2f5;
        }

        .stat-icon-wrapper {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .stat-card.volume .stat-icon-wrapper { background: #EEF0FE; color: #5A5BF1; }
        .stat-card.success .stat-icon-wrapper { background: #dcfce7; color: #16a34a; }
        .stat-card.pending .stat-icon-wrapper { background: #fef3c7; color: #d97706; }
        .stat-card.failed .stat-icon-wrapper { background: #fee2e2; color: #dc2626; }

        .stat-info .stat-label {
            font-size: 12px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 4px;
        }

        .stat-info .stat-val {
            font-size: 20px;
            font-weight: 800;
            color: #1e293b;
            line-height: 1.2;
        }

        .stat-info .stat-sub {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 2px;
        }

        /* ---- Tabs Container ---- */
        .tabs-container {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            border-bottom: 1.5px solid #e2e8f0;
            overflow-x: auto;
        }

        .tab-link {
            text-decoration: none;
            font-size: 14px;
            font-weight: 700;
            color: #64748b;
            padding: 12px 18px;
            position: relative;
            transition: all 0.2s ease;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .tab-link:hover { color: #5A5BF1; }

        .tab-link.active {
            color: #5A5BF1;
        }

        .tab-link.active::after {
            content: '';
            position: absolute;
            bottom: -1.5px;
            left: 0;
            width: 100%;
            height: 3px;
            background: #5A5BF1;
            border-radius: 3px 3px 0 0;
        }

        /* ---- Filter & Search Bar ---- */
        .filter-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 18px 24px;
            margin-bottom: 24px;
            box-shadow: 0 1px 6px rgba(0, 0, 0, 0.04);
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            align-items: center;
            justify-content: space-between;
            border: 1px solid #f0f2f5;
        }

        .search-form {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            flex: 1;
            align-items: center;
        }

        .search-box {
            position: relative;
            flex: 1 1 260px;
        }

        .search-box i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 14px;
        }

        .search-box input {
            width: 100%;
            padding: 10px 14px 10px 40px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 14px;
            font-family: inherit;
            background: #f8fafc;
            outline: none;
            transition: all 0.2s ease;
        }

        .search-box input:focus {
            border-color: #5A5BF1;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(90, 91, 241, 0.1);
        }

        .date-input {
            padding: 9px 12px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            background: #f8fafc;
            font-size: 13px;
            font-family: inherit;
            color: #334155;
            outline: none;
        }

        .date-input:focus { border-color: #5A5BF1; background: #fff; }

        .btn-filter {
            background: #5A5BF1;
            color: #ffffff;
            border: none;
            border-radius: 10px;
            padding: 10px 18px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-family: inherit;
            transition: background 0.2s;
        }

        .btn-filter:hover { background: #4748d0; }

        .btn-reset {
            background: #e2e8f0;
            color: #475569;
            border: none;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            font-family: inherit;
        }

        .btn-reset:hover { background: #cbd5e1; }

        /* ---- Table Card ---- */
        .table-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 1px 8px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            border: 1px solid #f0f2f5;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        thead th {
            background: #f8f9ff;
            padding: 14px 18px;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #64748b;
            border-bottom: 1px solid #eef0fe;
            white-space: nowrap;
        }

        tbody tr {
            border-bottom: 1px solid #f1f5f9;
            transition: background 0.15s ease;
        }

        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: #f8faff; }

        tbody td {
            padding: 16px 18px;
            font-size: 13px;
            vertical-align: middle;
            color: #334155;
        }

        .order-code {
            font-family: monospace;
            background: #f1f5f9;
            color: #5A5BF1;
            font-weight: 800;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 12px;
            display: inline-block;
        }

        .product-title {
            font-weight: 700;
            color: #1e293b;
            font-size: 13px;
        }

        .seller-info {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 2px;
        }

        .buyer-name {
            font-weight: 700;
            color: #1e293b;
        }

        .buyer-email {
            font-size: 11px;
            color: #94a3b8;
        }

        .amount-text {
            font-weight: 800;
            color: #16a34a;
            font-size: 14px;
        }

        /* Status Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
        }

        .badge-success { background: #dcfce7; color: #15803d; }
        .badge-pending { background: #fef3c7; color: #b45309; }
        .badge-failed { background: #fee2e2; color: #b91c1c; }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #94a3b8;
        }

        .empty-state i {
            font-size: 48px;
            color: #cbd5e1;
            margin-bottom: 12px;
        }

        .empty-state p {
            font-size: 14px;
            font-weight: 600;
        }

        /* Pagination */
        .pagination-container {
            padding: 16px 24px;
            border-top: 1px solid #f1f5f9;
            display: flex;
            justify-content: center;
        }

        /* Override Laravel pagination */
        nav[role="navigation"] span[aria-current="page"] span {
            background: #5A5BF1 !important;
            color: #fff !important;
            border-color: #5A5BF1 !important;
            border-radius: 8px !important;
            font-weight: 700;
            padding: 6px 12px;
        }
        nav[role="navigation"] a {
            border-radius: 8px !important;
            font-weight: 600;
            color: #5A5BF1 !important;
            padding: 6px 12px;
        }
        nav[role="navigation"] a:hover { background: #EEF0FE !important; }

        /* Responsive */
        @media (max-width: 1100px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 900px) {
            .platform-main { margin-left: 0 !important; }
            .hamburger-btn { display: block; }
            .platform-header { padding: 14px 20px; }
            .content-wrapper { padding: 20px 16px; }
            .stats-grid { grid-template-columns: 1fr; }
            .filter-card { flex-direction: column; align-items: stretch; }
            .search-form { flex-direction: column; align-items: stretch; }
        }
    </style>
</head>
<body>

    {{-- Sidebar --}}
    @include('platformadmin.sidebar.sidebarplatform')

    <div class="platform-main">
        {{-- Header --}}
        <div class="platform-header">
            <div class="platform-header-left">
                <button class="hamburger-btn" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
                <h1>{{ __('platform.transaction_logs') }}</h1>
            </div>
            <div class="header-right">
                <div class="header-user">
                    <div class="header-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                    <span>{{ Auth::user()->name }}</span>
                </div>
            </div>
        </div>

        <div class="content-wrapper">

            {{-- Stats Grid --}}
            <div class="stats-grid">
                <div class="stat-card volume">
                    <div class="stat-icon-wrapper"><i class="fas fa-chart-pie"></i></div>
                    <div class="stat-info">
                        <div class="stat-label">{{ __('platform.total_volume') }}</div>
                        <div class="stat-val">Rp {{ number_format($totalVolume, 0, ',', '.') }}</div>
                        <div class="stat-sub">{{ $totalSuccessCount }} {{ __('platform.success_status') }}</div>
                    </div>
                </div>

                <div class="stat-card success">
                    <div class="stat-icon-wrapper"><i class="fas fa-check-circle"></i></div>
                    <div class="stat-info">
                        <div class="stat-label">{{ __('platform.successful_payments') }}</div>
                        <div class="stat-val">{{ $totalSuccessCount }} {{ __('platform.all_transactions') }}</div>
                        <div class="stat-sub">{{ __('platform.success_status') }}</div>
                    </div>
                </div>

                <div class="stat-card pending">
                    <div class="stat-icon-wrapper"><i class="fas fa-clock"></i></div>
                    <div class="stat-info">
                        <div class="stat-label">{{ __('platform.pending_payments') }}</div>
                        <div class="stat-val">{{ $totalPendingCount }} {{ __('platform.all_transactions') }}</div>
                        <div class="stat-sub">{{ __('platform.pending_status') }}</div>
                    </div>
                </div>

                <div class="stat-card failed">
                    <div class="stat-icon-wrapper"><i class="fas fa-times-circle"></i></div>
                    <div class="stat-info">
                        <div class="stat-label">{{ __('platform.failed_payments') }}</div>
                        <div class="stat-val">{{ $totalFailedCount }} {{ __('platform.all_transactions') }}</div>
                        <div class="stat-sub">{{ __('platform.failed_status') }}</div>
                    </div>
                </div>
            </div>

            {{-- Tabs --}}
            <div class="tabs-container">
                <a href="{{ route('platform-admin.logs.transactions', array_merge(request()->except('status', 'page'), ['status' => 'all'])) }}"
                   class="tab-link {{ ($status ?? 'all') === 'all' ? 'active' : '' }}">
                    {{ __('platform.all_transactions') }} ({{ $totalTransactionsCount }})
                </a>
                <a href="{{ route('platform-admin.logs.transactions', array_merge(request()->except('status', 'page'), ['status' => 'success'])) }}"
                   class="tab-link {{ ($status ?? '') === 'success' ? 'active' : '' }}">
                    {{ __('platform.success_status') }} ({{ $totalSuccessCount }})
                </a>
                <a href="{{ route('platform-admin.logs.transactions', array_merge(request()->except('status', 'page'), ['status' => 'pending'])) }}"
                   class="tab-link {{ ($status ?? '') === 'pending' ? 'active' : '' }}">
                    {{ __('platform.pending_status') }} ({{ $totalPendingCount }})
                </a>
                <a href="{{ route('platform-admin.logs.transactions', array_merge(request()->except('status', 'page'), ['status' => 'failed'])) }}"
                   class="tab-link {{ ($status ?? '') === 'failed' ? 'active' : '' }}">
                    {{ __('platform.failed_status') }} ({{ $totalFailedCount }})
                </a>
            </div>

            {{-- Filter & Search Card --}}
            <div class="filter-card">
                <form method="GET" action="{{ route('platform-admin.logs.transactions') }}" class="search-form">
                    <input type="hidden" name="status" value="{{ $status }}">
                    
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="{{ __('platform.search_transactions_placeholder') }}">
                    </div>

                    <div style="display: flex; align-items: center; gap: 6px;">
                        <input type="date" name="start_date" value="{{ $startDate ?? '' }}" class="date-input" title="{{ __('platform.from_date') }}">
                        <span style="color: #94a3b8;">-</span>
                        <input type="date" name="end_date" value="{{ $endDate ?? '' }}" class="date-input" title="{{ __('platform.to_date') }}">
                    </div>

                    <button type="submit" class="btn-filter"><i class="fas fa-filter"></i> {{ __('platform.filter') }}</button>
                    @if($search || $startDate || $endDate)
                        <a href="{{ route('platform-admin.logs.transactions', ['status' => $status]) }}" class="btn-reset">{{ __('platform.reset') }}</a>
                    @endif
                </form>
            </div>

            {{-- Table Card --}}
            <div class="table-card">
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('platform.order_id') }}</th>
                                <th>{{ __('platform.product_and_seller') }}</th>
                                <th>{{ __('platform.buyer') }}</th>
                                <th>{{ __('platform.amount') }}</th>
                                <th>{{ __('platform.transaction_time') }}</th>
                                <th>{{ __('platform.payment_status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $index => $tx)
                            <tr>
                                <td style="font-weight: 700; color: #94a3b8;">
                                    {{ $transactions->firstItem() + $index }}
                                </td>
                                <td>
                                    <span class="order-code">{{ $tx->order_id }}</span>
                                </td>
                                <td>
                                    <div class="product-title">{{ $tx->product->title ?? '-' }}</div>
                                    <div class="seller-info">
                                        <i class="fas fa-store" style="font-size: 10px;"></i> 
                                        {{ __('platform.seller') }}: <strong>{{ $tx->product->user->name ?? '-' }}</strong> ({{ $tx->product->user->email ?? '-' }})
                                    </div>
                                </td>
                                <td>
                                    <div class="buyer-name">{{ $tx->buyer_name }}</div>
                                    <div class="buyer-email">{{ $tx->buyer_email }}</div>
                                </td>
                                <td>
                                    <div class="amount-text">Rp {{ number_format($tx->total_price, 0, ',', '.') }}</div>
                                    <div style="font-size: 11px; color: #94a3b8;">Qty: {{ $tx->qty }} pcs</div>
                                </td>
                                <td style="white-space: nowrap;">
                                    <div style="color: #1e293b; font-weight: 600;">
                                        {{ $tx->created_at->format('d M Y, H:i') }}
                                    </div>
                                    <div style="font-size: 11px; color: #94a3b8;">
                                        {{ $tx->created_at->diffForHumans() }}
                                    </div>
                                </td>
                                <td>
                                    @if($tx->status === 'success')
                                        <span class="badge badge-success">
                                            <i class="fas fa-check-circle"></i> {{ __('platform.success_status') }}
                                        </span>
                                    @elseif($tx->status === 'failed')
                                        <span class="badge badge-failed">
                                            <i class="fas fa-times-circle"></i> {{ __('platform.failed_status') }}
                                        </span>
                                    @else
                                        <span class="badge badge-pending">
                                            <i class="fas fa-clock"></i> {{ __('platform.pending_status') }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <i class="fas fa-inbox"></i>
                                        <p>{{ __('platform.no_transactions_found') }}</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($transactions->hasPages())
                    <div class="pagination-container">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

</body>
</html>
