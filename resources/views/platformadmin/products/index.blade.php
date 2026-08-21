<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('platform.product_management') }} — Platform Admin</title>
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

        /* ---- Alerts ---- */
        .alert-box {
            padding: 14px 20px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .alert-error { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }

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

        .stat-card.total .stat-icon-wrapper { background: #EEF0FE; color: #5A5BF1; }
        .stat-card.active .stat-icon-wrapper { background: #dcfce7; color: #16a34a; }
        .stat-card.takedown .stat-icon-wrapper { background: #fee2e2; color: #dc2626; }
        .stat-card.pending .stat-icon-wrapper { background: #fef3c7; color: #d97706; }

        .stat-info .stat-label {
            font-size: 12px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 4px;
        }

        .stat-info .stat-val {
            font-size: 22px;
            font-weight: 800;
            color: #1e293b;
            line-height: 1.2;
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
            padding: 20px 24px;
            margin-bottom: 24px;
            box-shadow: 0 1px 6px rgba(0, 0, 0, 0.04);
            border: 1px solid #f0f2f5;
        }

        .filter-form {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .filter-row-top {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
        }

        .search-box {
            position: relative;
            flex: 1 1 280px;
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

        .filter-row-bottom {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
        }

        .filter-select {
            padding: 9px 14px;
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

        .price-input {
            width: 130px;
            padding: 9px 12px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            background: #f8fafc;
            font-size: 13px;
            font-family: inherit;
            color: #334155;
            outline: none;
        }

        .price-input:focus { border-color: #5A5BF1; background: #fff; }

        .btn-filter {
            background: #5A5BF1;
            color: #ffffff;
            border: none;
            border-radius: 10px;
            padding: 9px 18px;
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
            padding: 9px 14px;
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

        /* Product Cell */
        .product-cell {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .product-thumb {
            width: 56px;
            height: 56px;
            border-radius: 10px;
            object-fit: cover;
            flex-shrink: 0;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
        }

        .product-no-thumb {
            width: 56px;
            height: 56px;
            border-radius: 10px;
            background: #f1f5f9;
            color: #94a3b8;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .product-title {
            font-weight: 700;
            color: #1e293b;
            font-size: 14px;
            margin-bottom: 2px;
        }

        .product-desc {
            font-size: 12px;
            color: #64748b;
            line-height: 1.4;
            max-width: 260px;
        }

        /* Seller Cell */
        .seller-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .seller-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, #5A5BF1, #818cf8);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 12px;
            flex-shrink: 0;
        }

        .seller-name {
            font-weight: 700;
            color: #1e293b;
            font-size: 13px;
        }

        .seller-email {
            font-size: 11px;
            color: #94a3b8;
        }

        /* Price & Limit */
        .price-text {
            font-weight: 800;
            color: #1e293b;
            font-size: 14px;
        }

        .sale-price-text {
            font-weight: 800;
            color: #16a34a;
            font-size: 14px;
        }

        .strike-price {
            font-size: 11px;
            text-decoration: line-through;
            color: #94a3b8;
        }

        .qty-badge {
            font-size: 11px;
            color: #64748b;
            margin-top: 2px;
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 700;
            white-space: nowrap;
        }

        .badge-live { background: #dcfce7; color: #15803d; }
        .badge-takedown { background: #fee2e2; color: #b91c1c; }
        .badge-approved { background: #dcfce7; color: #16a34a; }
        .badge-rejected { background: #fee2e2; color: #dc2626; }
        .badge-pending { background: #fef3c7; color: #b45309; }
        .badge-platform { background: #EEF0FE; color: #5A5BF1; }

        .takedown-reason-box {
            font-size: 11px;
            color: #b91c1c;
            background: #fef2f2;
            border: 1px solid #fee2e2;
            padding: 4px 8px;
            border-radius: 6px;
            margin-top: 4px;
            max-width: 220px;
            line-height: 1.3;
        }

        /* Action Buttons */
        .action-btns {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .btn-act {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            border: none;
            transition: all 0.2s ease;
            text-decoration: none;
            font-family: inherit;
            white-space: nowrap;
        }

        .btn-takedown { background: #fee2e2; color: #dc2626; }
        .btn-takedown:hover { background: #fecaca; }

        .btn-restore { background: #dcfce7; color: #15803d; }
        .btn-restore:hover { background: #bbf7d0; }

        .btn-view-platform { background: #EEF0FE; color: #5A5BF1; }
        .btn-view-platform:hover { background: #e0e3fd; }

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

        .btn-modal-submit-takedown {
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

        .btn-modal-submit-takedown:hover { background: #dc2626; }

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
            .filter-row-top { flex-direction: column; align-items: stretch; }
            .filter-row-bottom { flex-direction: column; align-items: stretch; }
            .price-input { width: 100%; }
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
                <h1>{{ __('platform.product_management') }}</h1>
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
                    {{ __('platform.all_products') }} ({{ $totalProductsCount }})
                </a>
                <a href="{{ route('platform-admin.products.index', array_merge(request()->except('tab', 'page'), ['tab' => 'active'])) }}"
                   class="tab-link {{ ($tab ?? '') === 'active' ? 'active' : '' }}">
                    {{ __('platform.active_products') }} ({{ $activeProductsCount }})
                </a>
                <a href="{{ route('platform-admin.products.index', array_merge(request()->except('tab', 'page'), ['tab' => 'takedown'])) }}"
                   class="tab-link {{ ($tab ?? '') === 'takedown' ? 'active' : '' }}">
                    {{ __('platform.takedown_products') }} ({{ $takedownCount }})
                </a>
                <a href="{{ route('platform-admin.products.index', array_merge(request()->except('tab', 'page'), ['tab' => 'pending'])) }}"
                   class="tab-link {{ ($tab ?? '') === 'pending' ? 'active' : '' }}">
                    {{ __('platform.pending_verification') }} ({{ $pendingCount }})
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

                        {{-- Sort By --}}
                        <select name="sort" class="filter-select">
                            <option value="latest" {{ ($sortBy ?? '') === 'latest' ? 'selected' : '' }}>{{ __('platform.sort_latest') }}</option>
                            <option value="oldest" {{ ($sortBy ?? '') === 'oldest' ? 'selected' : '' }}>{{ __('platform.sort_oldest') }}</option>
                            <option value="price_low" {{ ($sortBy ?? '') === 'price_low' ? 'selected' : '' }}>{{ __('platform.sort_price_low') }}</option>
                            <option value="price_high" {{ ($sortBy ?? '') === 'price_high' ? 'selected' : '' }}>{{ __('platform.sort_price_high') }}</option>
                        </select>

                        <button type="submit" class="btn-filter"><i class="fas fa-filter"></i> {{ __('platform.filter') }}</button>
                        @if($search || $sellerId || $platformType || $verificationStatus || $minPrice || $maxPrice || ($sortBy && $sortBy !== 'latest'))
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
                <h3><i class="fas fa-info-circle" style="color: #5A5BF1; margin-right: 6px;"></i>{{ __('platform.platform_details') }}</h3>
                <button class="modal-close" onclick="closePlatformModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group-custom">
                    <label>{{ __('platform.platform_type') }}</label>
                    <p id="platformType" style="font-weight: 700; color: #5A5BF1; font-size: 15px;"></p>
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
        function showTakedownModal(productId, title, seller) {
            document.getElementById('modalProductTitle').textContent = title;
            document.getElementById('modalProductSeller').textContent = seller;
            document.getElementById('takedownForm').action = `{{ url('/platform-admin/products') }}/${productId}/takedown`;
            document.getElementById('takedownModal').classList.add('show');
        }

        function closeTakedownModal() {
            document.getElementById('takedownModal').classList.remove('show');
        }

        function showPlatformModal(id, type, url, file) {
            document.getElementById('platformType').textContent = type.toUpperCase();
            
            const urlGroup = document.getElementById('platformUrlGroup');
            const fileGroup = document.getElementById('platformFileGroup');
            
            if (url && url !== 'null') {
                urlGroup.style.display = 'block';
                document.getElementById('platformUrl').innerHTML = `<a href="${url}" target="_blank" style="color: #5A5BF1; text-decoration: underline; font-weight: 600;">${url}</a>`;
            } else {
                urlGroup.style.display = 'none';
            }
            
            if (file && file !== 'null') {
                fileGroup.style.display = 'block';
                document.getElementById('platformFile').innerHTML = `<a href="{{ asset('storage') }}/${file}" target="_blank" style="display: inline-flex; align-items: center; gap: 6px; background: #EEF0FE; color: #5A5BF1; padding: 8px 14px; border-radius: 8px; font-weight: 700; text-decoration: none;"><i class="fas fa-download"></i> {{ __('platform.view_file') }}</a>`;
            } else {
                fileGroup.style.display = 'none';
            }
            
            document.getElementById('platformModal').classList.add('show');
        }

        function closePlatformModal() {
            document.getElementById('platformModal').classList.remove('show');
        }

        function confirmRestoreProduct(form, productTitle) {
            showConfirmModal({
                title: 'Pulihkan Produk Digital?',
                text: `Produk "${productTitle}" akan diaktifkan kembali dan dapat diakses/dibeli pembeli di etalase microsite seller.`,
                icon: 'question',
                confirmText: '<i class="fas fa-undo"></i> {{ __('platform.restore_product') }}',
                onConfirm: () => {
                    form.submit();
                }
            });
        }
    </script>
</body>
</html>
