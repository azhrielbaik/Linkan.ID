<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Payout — Linkan Platform</title>
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

        .stat-card.pending .stat-icon-wrapper { background: #fef3c7; color: #d97706; }
        .stat-card.approved .stat-icon-wrapper { background: #dcfce7; color: #16a34a; }
        .stat-card.rejected .stat-icon-wrapper { background: #fee2e2; color: #dc2626; }
        .stat-card.commission .stat-icon-wrapper { background: #EEF0FE; color: #5A5BF1; }

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

        /* ---- Alerts ---- */
        .alert-box {
            padding: 14px 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .alert-error { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }

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

        .tab-counter {
            padding: 2px 7px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: 800;
        }

        .tab-link.active .tab-counter { background: #5A5BF1; color: #ffffff; }
        .tab-link:not(.active) .tab-counter { background: #e2e8f0; color: #64748b; }

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
            flex: 1 1 240px;
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

        .filter-select {
            padding: 10px 14px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            background: #f8fafc;
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            outline: none;
            cursor: pointer;
            font-family: inherit;
        }

        .filter-select:focus { border-color: #5A5BF1; background: #fff; }

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

        /* Seller Cell */
        .seller-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .seller-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, #5A5BF1, #818cf8);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 13px;
            flex-shrink: 0;
        }

        .seller-name {
            font-weight: 700;
            color: #1e293b;
            font-size: 14px;
        }

        .seller-email {
            font-size: 12px;
            color: #94a3b8;
        }

        /* Method Badge */
        .method-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 12px;
            margin-bottom: 4px;
        }

        .method-bank { background: #eff6ff; color: #1d4ed8; }
        .method-dana { background: #ecfeff; color: #0891b2; }
        .method-shopeepay { background: #fff7ed; color: #ea580c; }

        .account-name-val {
            font-weight: 700;
            color: #1e293b;
            font-size: 13px;
        }

        .account-num-val {
            color: #64748b;
            font-size: 12px;
            font-family: monospace;
        }

        /* Nominal Chips */
        .amount-net {
            font-size: 14px;
            font-weight: 800;
            color: #16a34a;
        }

        .amount-breakdown {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 2px;
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

        .badge-pending { background: #fef3c7; color: #b45309; }
        .badge-approved { background: #dcfce7; color: #15803d; }
        .badge-rejected { background: #fee2e2; color: #b91c1c; }

        /* Action Buttons */
        .action-btns {
            display: flex;
            gap: 6px;
            align-items: center;
        }

        .btn-act {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            padding: 7px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            border: none;
            transition: all 0.2s ease;
            font-family: inherit;
            text-decoration: none;
        }

        .btn-approve {
            background: #10b981;
            color: #ffffff;
        }

        .btn-approve:hover { background: #059669; }

        .btn-reject {
            background: #ef4444;
            color: #ffffff;
        }

        .btn-reject:hover { background: #dc2626; }

        .btn-detail {
            background: #f1f5f9;
            color: #475569;
        }

        .btn-detail:hover { background: #e2e8f0; }

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

        /* ---- Modal ---- */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(4px);
            z-index: 1050;
            align-items: center;
            justify-content: center;
        }

        .modal.show { display: flex; }

        .modal-card {
            background: #ffffff;
            width: 480px;
            max-width: 90%;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            animation: modalFadeIn 0.2s ease-out;
        }

        @keyframes modalFadeIn {
            from { opacity: 0; transform: translateY(12px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .modal-header {
            padding: 20px 24px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-header h3 {
            font-size: 16px;
            font-weight: 800;
            color: #1e293b;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 20px;
            color: #94a3b8;
            cursor: pointer;
        }

        .modal-close:hover { color: #ef4444; }

        .modal-body {
            padding: 24px;
            font-size: 14px;
            color: #334155;
            line-height: 1.6;
        }

        .modal-footer {
            padding: 16px 24px;
            background: #f8fafc;
            border-top: 1px solid #f1f5f9;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .form-group-custom {
            margin-bottom: 16px;
        }

        .form-group-custom label {
            display: block;
            font-weight: 700;
            font-size: 13px;
            color: #1e293b;
            margin-bottom: 6px;
        }

        .form-control-custom {
            width: 100%;
            padding: 12px 16px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-size: 14px;
            font-family: inherit;
            outline: none;
            resize: vertical;
        }

        .form-control-custom:focus {
            border-color: #5A5BF1;
            box-shadow: 0 0 0 3px rgba(90, 91, 241, 0.1);
        }

        .btn-modal-cancel {
            background: #e2e8f0;
            color: #475569;
            padding: 10px 18px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 13px;
            border: none;
            cursor: pointer;
            font-family: inherit;
        }

        .btn-modal-submit {
            background: #ef4444;
            color: #ffffff;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 13px;
            border: none;
            cursor: pointer;
            font-family: inherit;
        }

        .btn-modal-submit:hover { background: #dc2626; }

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
                <h1>{{ __('platform.payout_management') }}</h1>
            </div>
            <div class="header-right">
                <div class="header-user">
                    <div class="header-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                    <span>{{ Auth::user()->name }}</span>
                </div>
            </div>
        </div>

        <div class="content-wrapper">

            {{-- Alerts --}}
            @if(session('success'))
                <div class="alert-box alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert-box alert-error">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif

            {{-- Stats Grid --}}
            <div class="stats-grid">
                <div class="stat-card pending">
                    <div class="stat-icon-wrapper"><i class="fas fa-clock"></i></div>
                    <div class="stat-info">
                        <div class="stat-label">{{ __('platform.pending_process') }}</div>
                        <div class="stat-val">{{ $totalPendingCount }} Request</div>
                        <div class="stat-sub">Rp {{ number_format($totalPendingAmount, 0, ',', '.') }}</div>
                    </div>
                </div>

                <div class="stat-card approved">
                    <div class="stat-icon-wrapper"><i class="fas fa-check-circle"></i></div>
                    <div class="stat-info">
                        <div class="stat-label">{{ __('platform.payout_approved') }}</div>
                        <div class="stat-val">Rp {{ number_format($totalApprovedAmount, 0, ',', '.') }}</div>
                        <div class="stat-sub">{{ $totalApprovedCount }} {{ __('platform.approved_status') }}</div>
                    </div>
                </div>

                <div class="stat-card rejected">
                    <div class="stat-icon-wrapper"><i class="fas fa-times-circle"></i></div>
                    <div class="stat-info">
                        <div class="stat-label">{{ __('platform.payout_rejected') }}</div>
                        <div class="stat-val">{{ $totalRejectedCount }} Request</div>
                        <div class="stat-sub">{{ __('platform.rejected_status') }}</div>
                    </div>
                </div>

                <div class="stat-card commission">
                    <div class="stat-icon-wrapper"><i class="fas fa-coins"></i></div>
                    <div class="stat-info">
                        <div class="stat-label">{{ __('platform.platform_commission_5') }}</div>
                        <div class="stat-val">Rp {{ number_format($totalCommissionEarned, 0, ',', '.') }}</div>
                        <div class="stat-sub">{{ __('platform.total_commission') }}</div>
                    </div>
                </div>
            </div>

            {{-- Tabs --}}
            <div class="tabs-container">
                <a href="{{ route('platform-admin.payouts.index', array_merge(request()->except('tab', 'page'), ['tab' => 'all'])) }}"
                   class="tab-link {{ ($tab ?? 'all') === 'all' ? 'active' : '' }}">
                    {{ __('platform.all_requests') }}
                </a>
                <a href="{{ route('platform-admin.payouts.index', array_merge(request()->except('tab', 'page'), ['tab' => 'pending'])) }}"
                   class="tab-link {{ ($tab ?? '') === 'pending' ? 'active' : '' }}">
                    {{ __('platform.pending_verification') }}
                    @if($totalPendingCount > 0)
                        <span class="tab-counter">{{ $totalPendingCount }}</span>
                    @endif
                </a>
                <a href="{{ route('platform-admin.payouts.index', array_merge(request()->except('tab', 'page'), ['tab' => 'approved'])) }}"
                   class="tab-link {{ ($tab ?? '') === 'approved' ? 'active' : '' }}">
                    {{ __('platform.approved_status') }}
                </a>
                <a href="{{ route('platform-admin.payouts.index', array_merge(request()->except('tab', 'page'), ['tab' => 'rejected'])) }}"
                   class="tab-link {{ ($tab ?? '') === 'rejected' ? 'active' : '' }}">
                    {{ __('platform.rejected_status') }}
                </a>
            </div>

            {{-- Filter & Search Card --}}
            <div class="filter-card">
                <form method="GET" action="{{ route('platform-admin.payouts.index') }}" class="search-form">
                    <input type="hidden" name="tab" value="{{ $tab }}">
                    
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="{{ __('platform.search_payout_placeholder') }}">
                    </div>

                    <select name="method" class="filter-select">
                        <option value="">{{ __('platform.all_methods') }}</option>
                        <option value="Bank" {{ ($method ?? '') === 'Bank' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="DANA" {{ ($method ?? '') === 'DANA' ? 'selected' : '' }}>DANA</option>
                        <option value="ShopeePay" {{ ($method ?? '') === 'ShopeePay' ? 'selected' : '' }}>ShopeePay</option>
                    </select>

                    <div style="display: flex; align-items: center; gap: 6px;">
                        <input type="date" name="start_date" value="{{ $startDate ?? '' }}" class="date-input" title="{{ __('platform.from_date') }}">
                        <span style="color: #94a3b8;">-</span>
                        <input type="date" name="end_date" value="{{ $endDate ?? '' }}" class="date-input" title="{{ __('platform.to_date') }}">
                    </div>

                    <button type="submit" class="btn-filter"><i class="fas fa-filter"></i> {{ __('platform.filter') }}</button>
                    @if($search || $method || $startDate || $endDate)
                        <a href="{{ route('platform-admin.payouts.index', ['tab' => $tab]) }}" class="btn-reset">{{ __('platform.reset') }}</a>
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
                                <th>{{ __('platform.seller') }}</th>
                                <th>{{ __('platform.account_details') }}</th>
                                <th>{{ __('platform.net_amount') }}</th>
                                <th>{{ __('platform.gross_and_fee') }}</th>
                                <th>{{ __('platform.request_date') }}</th>
                                <th>{{ __('platform.status') }}</th>
                                <th style="text-align: center;">{{ __('platform.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payouts as $index => $payout)
                            <tr>
                                <td style="font-weight: 700; color: #94a3b8;">
                                    {{ $payouts->firstItem() + $index }}
                                </td>
                                <td>
                                    <div class="seller-cell">
                                        <div class="seller-avatar">
                                            {{ strtoupper(substr($payout->user->name ?? 'User', 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="seller-name">{{ $payout->user->name ?? '-' }}</div>
                                            <div class="seller-email">{{ $payout->user->email ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $methodClass = 'method-' . strtolower($payout->method);
                                        $icon = $payout->method === 'Bank' ? 'university' : 'wallet';
                                    @endphp
                                    <div class="method-tag {{ $methodClass }}">
                                        <i class="fas fa-{{ $icon }}"></i> {{ $payout->method }} {{ $payout->bank_name ? '(' . $payout->bank_name . ')' : '' }}
                                    </div>
                                    <div class="account-name-val">{{ $payout->account_name ?? '-' }}</div>
                                    <div class="account-num-val">{{ $payout->account_number ?? '-' }}</div>
                                </td>
                                <td>
                                    <div class="amount-net">Rp {{ number_format($payout->amount, 0, ',', '.') }}</div>
                                </td>
                                <td>
                                    <div style="font-weight: 600; color: #475569;">
                                        Rp {{ number_format($payout->gross_amount ?? $payout->amount, 0, ',', '.') }}
                                    </div>
                                    <div class="amount-breakdown">
                                        {{ __('platform.fee') }}: Rp {{ number_format($payout->commission ?? 0, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td style="white-space: nowrap; color: #64748b; font-size: 12px;">
                                    {{ $payout->created_at->format('d M Y, H:i') }}
                                </td>
                                <td>
                                    @if($payout->status === 'approved')
                                        <span class="badge badge-approved">
                                            <i class="fas fa-check-circle"></i> {{ __('platform.approved_status') }}
                                        </span>
                                    @elseif($payout->status === 'rejected')
                                        <span class="badge badge-rejected">
                                            <i class="fas fa-times-circle"></i> {{ __('platform.rejected_status') }}
                                        </span>
                                    @else
                                        <span class="badge badge-pending">
                                            <i class="fas fa-clock"></i> {{ __('platform.pending_status') }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($payout->status === 'pending')
                                        <div class="action-btns">
                                            <form action="{{ route('platform-admin.payouts.approve', $payout->id) }}" method="POST" style="margin: 0;">
                                                @csrf
                                                <button type="button" class="btn-act btn-approve" onclick="confirmApprovePayout(this.form, '{{ addslashes($payout->user->name ?? 'Seller') }}', 'Rp {{ number_format($payout->amount, 0, ',', '.') }}')">
                                                    <i class="fas fa-check"></i> {{ __('platform.approve') }}
                                                </button>
                                            </form>
                                            <button type="button" class="btn-act btn-reject" onclick="showRejectModal({{ $payout->id }}, '{{ addslashes($payout->user->name ?? 'Seller') }}', 'Rp {{ number_format($payout->amount, 0, ',', '.') }}')">
                                                <i class="fas fa-times"></i> {{ __('platform.reject') }}
                                            </button>
                                        </div>
                                    @elseif($payout->status === 'rejected')
                                        <button type="button" class="btn-act btn-detail" onclick="showReasonModal('{{ addslashes($payout->rejection_reason ?? 'Tidak ada catatan.') }}', '{{ $payout->processed_at ? $payout->processed_at->format('d M Y H:i') : '-' }}')">
                                            <i class="fas fa-info-circle"></i> {{ __('platform.reason') }}
                                        </button>
                                    @else
                                        <span style="font-size: 11px; color: #94a3b8;">
                                            {{ $payout->processed_at ? $payout->processed_at->format('d M Y') : 'OK' }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <i class="fas fa-inbox"></i>
                                        <p>{{ __('platform.no_payouts_found') }}</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($payouts->hasPages())
                    <div class="pagination-container">
                        {{ $payouts->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    <!-- Modal Reject Payout -->
    <div id="rejectPayoutModal" class="modal">
        <div class="modal-card">
            <div class="modal-header">
                <h3><i class="fas fa-times-circle" style="color: #ef4444; margin-right: 6px;"></i>{{ __('platform.reject_withdraw_title') }}</h3>
                <button class="modal-close" onclick="closeRejectModal()">&times;</button>
            </div>
            <form id="rejectPayoutForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div style="background: #fef2f2; border: 1px solid #fee2e2; border-radius: 10px; padding: 12px 16px; margin-bottom: 16px; font-size: 13px; color: #991b1b;">
                        <i class="fas fa-info-circle"></i> {{ __('platform.reject_withdraw_warning') }}
                    </div>

                    <div style="margin-bottom: 14px; font-size: 14px;">
                        <div><strong>{{ __('platform.seller') }}:</strong> <span id="modalSellerName">-</span></div>
                        <div><strong>{{ __('platform.amount') }}:</strong> <span id="modalPayoutAmount">-</span></div>
                    </div>

                    <div class="form-group-custom">
                        <label>{{ __('platform.rejection_reason') }} <span style="color: #ef4444;">*</span></label>
                        <textarea name="rejection_reason" class="form-control-custom" rows="4" required placeholder="{{ __('platform.enter_rejection_reason') }}"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal-cancel" onclick="closeRejectModal()">{{ __('platform.cancel') }}</button>
                    <button type="submit" class="btn-modal-submit"><i class="fas fa-times"></i> {{ __('platform.reject_and_refund') }}</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Detail Alasan Penolakan -->
    <div id="reasonDetailModal" class="modal">
        <div class="modal-card">
            <div class="modal-header">
                <h3><i class="fas fa-info-circle" style="color: #5A5BF1; margin-right: 6px;"></i>{{ __('platform.rejection_notes') }}</h3>
                <button class="modal-close" onclick="closeReasonModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div style="margin-bottom: 12px; font-size: 12px; color: #94a3b8;">
                    {{ __('platform.processed_at') }}: <span id="reasonProcessedAt" style="color: #1e293b; font-weight: 600;">-</span>
                </div>
                <div class="form-group-custom">
                    <label>{{ __('platform.rejection_reason') }}:</label>
                    <div id="reasonContent" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px; font-size: 14px; line-height: 1.6; white-space: pre-wrap;"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-modal-cancel" onclick="closeReasonModal()">{{ __('platform.close') }}</button>
            </div>
        </div>
    </div>

    <script>
    function showRejectModal(id, sellerName, amount) {
        document.getElementById('rejectPayoutForm').action = `{{ url('platform-admin/payouts') }}/${id}/reject`;
        document.getElementById('modalSellerName').textContent = sellerName;
        document.getElementById('modalPayoutAmount').textContent = amount;
        document.getElementById('rejectPayoutModal').classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeRejectModal() {
        document.getElementById('rejectPayoutModal').classList.remove('show');
        document.body.style.overflow = 'auto';
    }

    function showReasonModal(reason, processedAt) {
        document.getElementById('reasonContent').textContent = reason;
        document.getElementById('reasonProcessedAt').textContent = processedAt;
        document.getElementById('reasonDetailModal').classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeReasonModal() {
        document.getElementById('reasonDetailModal').classList.remove('show');
        document.body.style.overflow = 'auto';
    }

    window.addEventListener('click', function(event) {
        if (event.target.classList.contains('modal')) {
            closeRejectModal();
            closeReasonModal();
        }
    });

    function confirmApprovePayout(form, sellerName, amount) {
        showConfirmModal({
            title: 'Setujui Transfer Payout?',
            text: `Apakah Anda yakin menyetujui transfer penarikan dana sebesar ${amount} untuk ${sellerName}?`,
            icon: 'question',
            confirmText: '<i class="fas fa-check"></i> {{ __('platform.approve') }}',
            onConfirm: () => {
                form.submit();
            }
        });
    }
    </script>
</body>
</html>
