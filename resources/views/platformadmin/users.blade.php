<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('platform.user_management') }} — Platform Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0; padding: 0; box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', Arial, sans-serif;
        }
        body {
            background: #f5f6fa; color: #334155;
            display: flex; min-height: 100vh;
        }

        /* Main layout */
        .platform-main {
            margin-left: 250px; flex: 1;
            display: flex; flex-direction: column; min-height: 100vh;
            min-width: 0; transition: margin-left 0.3s ease;
        }

        /* Header */
        .platform-header {
            display: flex; justify-content: space-between; align-items: center;
            background: #ffffff; padding: 16px 40px;
            border-bottom: 1px solid #f0f2f5; min-height: 64px;
            position: sticky; top: 0; z-index: 99;
        }
        .platform-header-left { display: flex; align-items: center; gap: 16px; }
        .hamburger-btn {
            display: none; font-size: 22px; color: #5A5BF1;
            cursor: pointer; background: none; border: none; padding: 4px 6px;
        }
        .platform-header h1 {
            font-size: 22px; font-weight: 800; color: #5A5BF1;
            letter-spacing: -0.5px;
        }
        .header-user {
            display: flex; align-items: center; gap: 10px;
            background: #f8f9ff; border: 1px solid #eef0fe;
            border-radius: 30px; padding: 5px 14px 5px 5px;
        }
        .header-avatar {
            width: 34px; height: 34px; border-radius: 50%;
            background: #5A5BF1; color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 13px;
        }
        .header-user span { font-size: 14px; font-weight: 600; color: #1e293b; }

        /* Content */
        .content-wrapper { padding: 28px 40px; flex: 1; }

        /* Alerts */
        .alert {
            padding: 12px 18px; border-radius: 12px;
            font-size: 14px; font-weight: 600; margin-bottom: 20px;
            display: flex; align-items: center; gap: 10px;
        }
        .alert-success { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .alert-error   { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }

        /* Top View Tabs (Users vs Appeals) */
        .view-switcher {
            display: flex; gap: 12px; margin-bottom: 24px;
            border-bottom: 2px solid #e2e8f0; padding-bottom: 2px;
        }
        .view-tab-link {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 10px 18px; font-size: 14px; font-weight: 800;
            text-decoration: none; color: #64748b; border-bottom: 3px solid transparent;
            margin-bottom: -4px; transition: all 0.2s;
        }
        .view-tab-link:hover { color: #5A5BF1; }
        .view-tab-link.active {
            color: #5A5BF1; border-bottom-color: #5A5BF1;
        }
        .tab-counter {
            background: #ef4444; color: #fff; font-size: 11px;
            padding: 2px 8px; border-radius: 20px; font-weight: 800;
        }

        /* Stats cards */
        .stats-row {
            display: grid; grid-template-columns: repeat(3, 1fr);
            gap: 16px; margin-bottom: 24px;
        }
        .stat-card {
            background: #fff; border-radius: 16px; padding: 20px 24px;
            box-shadow: 0 1px 6px rgba(0,0,0,0.04);
            display: flex; align-items: center; gap: 16px;
            border: 1px solid #f0f2f5;
        }
        .stat-icon-wrap {
            width: 48px; height: 48px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; flex-shrink: 0;
        }
        .stat-card.total .stat-icon-wrap { background: #EEF0FE; color: #5A5BF1; }
        .stat-card.active .stat-icon-wrap { background: #dcfce7; color: #16a34a; }
        .stat-card.susp   .stat-icon-wrap { background: #fee2e2; color: #dc2626; }
        .stat-info .label {
            font-size: 12px; font-weight: 700; color: #64748b;
            text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 4px;
        }
        .stat-info .value {
            font-size: 24px; font-weight: 800; color: #1e293b; line-height: 1;
        }

        /* Toolbar */
        .toolbar {
            background: #fff; border-radius: 16px; padding: 16px 20px;
            margin-bottom: 20px; box-shadow: 0 1px 6px rgba(0,0,0,0.04);
            border: 1px solid #f0f2f5; display: flex;
            justify-content: space-between; align-items: center;
            flex-wrap: wrap; gap: 12px;
        }
        .search-form { display: flex; gap: 10px; flex: 1; max-width: 420px; }
        .search-wrap { position: relative; flex: 1; }
        .search-wrap i {
            position: absolute; left: 14px; top: 50%;
            transform: translateY(-50%); color: #94a3b8; font-size: 14px;
        }
        .search-wrap input {
            width: 100%; padding: 10px 14px 10px 38px;
            border: 1.5px solid #e2e8f0; border-radius: 10px;
            font-size: 13px; font-family: 'Plus Jakarta Sans', sans-serif;
            outline: none; background: #f8fafc; color: #1e293b;
            transition: border-color 0.2s;
        }
        .search-wrap input:focus { border-color: #5A5BF1; background: #fff; }
        .btn-search {
            background: #5A5BF1; color: #fff; border: none;
            padding: 10px 18px; border-radius: 10px;
            font-size: 13px; font-weight: 700; cursor: pointer;
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: background 0.2s;
        }
        .btn-search:hover { background: #4748d0; }

        .filter-tabs { display: flex; gap: 6px; }
        .filter-tab {
            padding: 8px 16px; border-radius: 10px; font-size: 13px;
            font-weight: 700; text-decoration: none; color: #64748b;
            background: #f1f5f9; transition: all 0.2s;
        }
        .filter-tab:hover { color: #5A5BF1; }
        .filter-tab.active { background: #5A5BF1; color: #fff; }

        /* Table Card */
        .table-card {
            background: #fff; border-radius: 16px;
            box-shadow: 0 1px 6px rgba(0,0,0,0.04);
            border: 1px solid #f0f2f5; overflow: hidden;
        }
        table { width: 100%; border-collapse: collapse; }
        thead th {
            background: #f8f9ff; padding: 13px 20px;
            text-align: left; font-size: 11px; font-weight: 800;
            text-transform: uppercase; letter-spacing: 0.06em;
            color: #94a3b8; border-bottom: 1px solid #f0f0f8;
        }
        tbody tr { border-bottom: 1px solid #f8f9ff; transition: background 0.15s; }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: #f8f9ff; }
        tbody td {
            padding: 14px 20px; font-size: 14px;
            color: #334155; vertical-align: middle;
        }

        /* User cell */
        .user-cell { display: flex; align-items: center; gap: 12px; }
        .user-avatar {
            width: 40px; height: 40px; border-radius: 50%;
            background: linear-gradient(135deg, #5A5BF1, #818cf8);
            color: #fff; display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 14px; flex-shrink: 0; overflow: hidden;
        }
        .user-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .user-name  { font-weight: 700; color: #1e293b; font-size: 14px; }
        .user-email { font-size: 12px; color: #94a3b8; margin-top: 1px; }

        /* Badges */
        .badge {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 4px 10px; border-radius: 20px;
            font-size: 12px; font-weight: 700;
        }
        .badge-active    { background: #dcfce7; color: #16a34a; }
        .badge-suspended { background: #fee2e2; color: #dc2626; }
        .badge-pending   { background: #fef3c7; color: #d97706; }
        .badge-role      { background: #EEF0FE; color: #5A5BF1; }

        /* Action buttons */
        .action-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-action {
            padding: 7px 14px; border-radius: 8px;
            font-size: 12px; font-weight: 700; border: none;
            cursor: pointer; display: inline-flex; align-items: center; gap: 6px;
            transition: all 0.2s; font-family: 'Plus Jakarta Sans', sans-serif;
            text-decoration: none;
        }
        .btn-detail { background: #EEF0FE; color: #5A5BF1; }
        .btn-detail:hover { background: #e0e3fd; }
        .btn-suspend  { background: #fee2e2; color: #dc2626; }
        .btn-suspend:hover  { background: #fecaca; }
        .btn-activate { background: #dcfce7; color: #16a34a; }
        .btn-activate:hover { background: #bbf7d0; }
        .btn-approve  { background: #dcfce7; color: #16a34a; }
        .btn-approve:hover  { background: #bbf7d0; }
        .btn-reject   { background: #fee2e2; color: #dc2626; }
        .btn-reject:hover   { background: #fecaca; }

        /* Empty state */
        .empty-state { text-align: center; padding: 60px 20px; color: #94a3b8; }
        .empty-state i { font-size: 2.5rem; margin-bottom: 12px; color: #c7d2fe; display: block; }
        .empty-state p { font-size: 14px; font-weight: 600; }

        /* Pagination */
        .pagination-wrap {
            display: flex; justify-content: center;
            padding: 18px; border-top: 1px solid #f0f0f8;
        }
        .pagination-wrap nav { display: flex; gap: 6px; flex-wrap: wrap; justify-content: center; }

        nav[role="navigation"] span[aria-current="page"] span {
            background: #5A5BF1 !important; color: #fff !important;
            border-color: #5A5BF1 !important; border-radius: 8px !important;
            font-weight: 700; font-family: 'Plus Jakarta Sans', sans-serif;
            padding: 7px 13px;
        }
        nav[role="navigation"] a {
            border-radius: 8px !important; font-weight: 600;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #5A5BF1 !important; padding: 7px 13px;
        }
        nav[role="navigation"] a:hover { background: #EEF0FE !important; }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(15, 23, 42, 0.5);
            backdrop-filter: blur(4px);
            z-index: 1050;
            align-items: center;
            justify-content: center;
        }
        .modal.show { display: flex; }

        .modal-container {
            background: #ffffff;
            width: 600px;
            max-width: 92%;
            max-height: 90vh;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            animation: modalPop 0.2s ease-out;
        }
        .modal-container-large { width: 740px; }

        @keyframes modalPop {
            from { opacity: 0; transform: scale(0.95) translateY(10px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }

        .modal-header {
            padding: 20px 24px;
            background: #f8f9ff;
            border-bottom: 1px solid #eef0fe;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .modal-header h3 {
            font-size: 16px;
            font-weight: 800;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .modal-close {
            background: none;
            border: none;
            font-size: 20px;
            color: #94a3b8;
            cursor: pointer;
            transition: color 0.2s;
        }
        .modal-close:hover { color: #ef4444; }

        .modal-body {
            padding: 24px;
            overflow-y: auto;
            flex: 1;
        }

        /* Form styling */
        .form-group { margin-bottom: 18px; }
        .form-group label {
            display: block; font-size: 13px; font-weight: 700;
            color: #334155; margin-bottom: 6px;
        }
        .form-control {
            width: 100%; padding: 10px 14px;
            border: 1.5px solid #e2e8f0; border-radius: 10px;
            font-size: 13px; font-family: inherit;
            background: #f8fafc; outline: none; transition: border-color 0.2s;
            color: #1e293b;
        }
        .form-control:focus { border-color: #5A5BF1; background: #fff; }

        /* Radio cards for duration */
        .duration-grid {
            display: grid; grid-template-columns: repeat(2, 1fr);
            gap: 10px; margin-bottom: 6px;
        }
        .duration-option {
            position: relative;
        }
        .duration-option input[type="radio"] {
            position: absolute; opacity: 0; cursor: pointer;
        }
        .duration-card {
            border: 1.5px solid #e2e8f0; border-radius: 10px;
            padding: 10px 12px; cursor: pointer; transition: all 0.2s;
            display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 700;
            color: #475569; background: #f8fafc;
        }
        .duration-option input[type="radio"]:checked + .duration-card {
            border-color: #dc2626; background: #fef2f2; color: #dc2626;
        }

        .modal-footer {
            padding: 14px 24px; background: #f8fafc;
            border-top: 1px solid #f1f5f9; display: flex;
            justify-content: flex-end; gap: 10px;
        }
        .btn-modal-cancel {
            background: #e2e8f0; color: #475569; padding: 9px 18px;
            border-radius: 8px; font-weight: 700; font-size: 13px;
            border: none; cursor: pointer; font-family: inherit;
        }
        .btn-modal-submit-danger {
            background: #dc2626; color: #fff; padding: 9px 20px;
            border-radius: 8px; font-weight: 700; font-size: 13px;
            border: none; cursor: pointer; font-family: inherit;
            transition: background 0.2s;
        }
        .btn-modal-submit-danger:hover { background: #b91c1c; }

        /* Seller Profile Banner inside Modal */
        .seller-banner {
            display: flex; align-items: center; justify-content: space-between;
            gap: 16px; padding: 16px 20px; background: #f8f9ff;
            border: 1px solid #eef0fe; border-radius: 14px; margin-bottom: 20px;
        }
        .seller-banner-left { display: flex; align-items: center; gap: 14px; }
        .seller-banner-avatar {
            width: 52px; height: 52px; border-radius: 50%;
            background: #5A5BF1; color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 18px; overflow: hidden; flex-shrink: 0;
        }
        .seller-banner-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .seller-banner-info .name { font-size: 16px; font-weight: 800; color: #1e293b; }
        .seller-banner-info .email { font-size: 12px; color: #64748b; margin-bottom: 4px; }
        .seller-banner-info .link {
            font-size: 12px; color: #5A5BF1; font-weight: 700; text-decoration: none;
            display: inline-flex; align-items: center; gap: 4px;
        }
        .seller-banner-info .link:hover { text-decoration: underline; }

        .modal-stats-grid {
            display: grid; grid-template-columns: repeat(4, 1fr);
            gap: 12px; margin-bottom: 20px;
        }
        .modal-stat-box {
            background: #ffffff; border: 1px solid #e2e8f0;
            border-radius: 12px; padding: 14px; text-align: center;
        }
        .modal-stat-box .box-lbl {
            font-size: 11px; font-weight: 700; color: #64748b;
            text-transform: uppercase; margin-bottom: 4px;
        }
        .modal-stat-box .box-val { font-size: 16px; font-weight: 800; color: #1e293b; }

        .modal-tabs {
            display: flex; gap: 10px; border-bottom: 1.5px solid #e2e8f0;
            margin-bottom: 16px;
        }
        .modal-tab-btn {
            background: none; border: none; font-size: 13px;
            font-weight: 700; color: #64748b; padding: 10px 14px;
            cursor: pointer; position: relative; font-family: inherit;
        }
        .modal-tab-btn.active { color: #5A5BF1; }
        .modal-tab-btn.active::after {
            content: ''; position: absolute; bottom: -1.5px; left: 0;
            width: 100%; height: 3px; background: #5A5BF1;
            border-radius: 3px 3px 0 0;
        }

        .modal-tab-content { display: none; }
        .modal-tab-content.active { display: block; }

        .modal-mini-table { width: 100%; border-collapse: collapse; font-size: 12px; }
        .modal-mini-table th {
            background: #f8fafc; padding: 10px 12px;
            font-weight: 800; color: #64748b; text-transform: uppercase;
            font-size: 10px; letter-spacing: 0.05em; border-bottom: 1px solid #e2e8f0;
        }
        .modal-mini-table td {
            padding: 10px 12px; border-bottom: 1px solid #f1f5f9; color: #334155;
        }

        .loading-spinner { text-align: center; padding: 40px; color: #5A5BF1; font-weight: 700; }

        @media (max-width: 900px) {
            .platform-main { margin-left: 0; }
            .content-wrapper { padding: 16px; }
            .stats-row { grid-template-columns: 1fr; }
            .platform-header { padding: 14px 16px; }
            .toolbar { flex-direction: column; }
            .modal-stats-grid { grid-template-columns: repeat(2, 1fr); }
            .duration-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    @include('platformadmin.sidebar.sidebarplatform')

    <div class="platform-main">
        {{-- Header --}}
        <div class="platform-header">
            <div class="platform-header-left">
                <button class="hamburger-btn" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
                <h1>{{ __('platform.user_management') }}</h1>
            </div>
            <div class="header-user">
                <div class="header-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                <span>{{ Auth::user()->name }}</span>
            </div>
        </div>

        <div class="content-wrapper">

            {{-- Alerts --}}
            @if (session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif

            {{-- View Switcher Tabs --}}
            <div class="view-switcher">
                <a href="{{ route('platform-admin.users', ['view' => 'users']) }}" 
                   class="view-tab-link {{ $viewType === 'users' ? 'active' : '' }}">
                    <i class="fas fa-users"></i> {{ __('platform.user_management') }}
                </a>
                <a href="{{ route('platform-admin.users', ['view' => 'appeals']) }}" 
                   class="view-tab-link {{ $viewType === 'appeals' ? 'active' : '' }}">
                    <i class="fas fa-file-contract"></i> {{ __('platform.seller_appeals') }}
                    @if($pendingAppealsCount > 0)
                        <span class="tab-counter">{{ $pendingAppealsCount }}</span>
                    @endif
                </a>
            </div>

            @if($viewType === 'users')
                {{-- Stats Row --}}
                <div class="stats-row">
                    <div class="stat-card total">
                        <div class="stat-icon-wrap"><i class="fas fa-users"></i></div>
                        <div class="stat-info">
                            <div class="label">{{ __('platform.total_users') }}</div>
                            <div class="value">{{ $totalUsers }}</div>
                        </div>
                    </div>
                    <div class="stat-card active">
                        <div class="stat-icon-wrap"><i class="fas fa-user-check"></i></div>
                        <div class="stat-info">
                            <div class="label">{{ __('platform.active_users') }}</div>
                            <div class="value">{{ $totalActive }}</div>
                        </div>
                    </div>
                    <div class="stat-card susp">
                        <div class="stat-icon-wrap"><i class="fas fa-user-slash"></i></div>
                        <div class="stat-info">
                            <div class="label">{{ __('platform.suspended_users') }}</div>
                            <div class="value">{{ $totalSuspended }}</div>
                        </div>
                    </div>
                </div>

                {{-- Toolbar --}}
                <div class="toolbar">
                    <form method="GET" action="{{ route('platform-admin.users') }}" class="search-form">
                        <input type="hidden" name="view" value="users">
                        <div class="search-wrap">
                            <i class="fas fa-search"></i>
                            <input type="text" name="search"
                                   placeholder="{{ __('platform.search_user_placeholder') }}"
                                   value="{{ $search ?? '' }}">
                        </div>
                        <button type="submit" class="btn-search">
                            <i class="fas fa-search"></i> {{ __('platform.search') }}
                        </button>
                    </form>

                    <div class="filter-tabs">
                        <a href="{{ route('platform-admin.users', ['view' => 'users', 'filter' => 'all', 'search' => $search]) }}"
                           class="filter-tab {{ ($filter ?? 'all') === 'all' ? 'active' : '' }}">{{ __('platform.all') }}</a>
                        <a href="{{ route('platform-admin.users', ['view' => 'users', 'filter' => 'active', 'search' => $search]) }}"
                           class="filter-tab {{ ($filter ?? '') === 'active' ? 'active' : '' }}">{{ __('platform.active') }}</a>
                        <a href="{{ route('platform-admin.users', ['view' => 'users', 'filter' => 'suspended', 'search' => $search]) }}"
                           class="filter-tab {{ ($filter ?? '') === 'suspended' ? 'active' : '' }}">{{ __('platform.suspended') }}</a>
                    </div>
                </div>

                {{-- Users Table --}}
                <div class="table-card">
                    @if ($users->count() > 0)
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>{{ __('platform.role') }}</th>
                                <th>{{ __('platform.joined_at') }}</th>
                                <th>{{ __('platform.status') }}</th>
                                <th>{{ __('platform.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $i => $user)
                            <tr>
                                <td style="color:#94a3b8; font-weight:600; font-size:13px;">
                                    {{ $users->firstItem() + $i }}
                                </td>
                                <td>
                                    <div class="user-cell">
                                        <div class="user-avatar">
                                            @if ($user->avatar)
                                                <img src="{{ Str::startsWith($user->avatar, ['http://', 'https://']) ? $user->avatar : asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}">
                                            @else
                                                {{ strtoupper(substr($user->name, 0, 2)) }}
                                            @endif
                                        </div>
                                        <div>
                                            <div class="user-name">{{ $user->name }}</div>
                                            <div class="user-email">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-role">
                                        <i class="fas fa-tag" style="font-size:10px;"></i>
                                        {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                    </span>
                                </td>
                                <td style="color:#64748b; font-size:13px;">
                                    {{ $user->created_at->format('d M Y') }}
                                </td>
                                <td>
                                    @if ($user->isSuspended())
                                        <div>
                                            <span class="badge badge-suspended">
                                                <i class="fas fa-ban" style="font-size:10px;"></i> {{ __('platform.suspended') }}
                                            </span>
                                            <div style="font-size: 11px; color: #ef4444; margin-top: 4px; font-weight: 600;">
                                                @if($user->suspended_until)
                                                    s.d {{ $user->suspended_until->format('d M Y, H:i') }}
                                                @else
                                                    Permanen
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <span class="badge badge-active">
                                            <i class="fas fa-circle" style="font-size:8px;"></i> {{ __('platform.active') }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-group">
                                        {{-- Tombol Detail / Inspeksi Seller --}}
                                        <button type="button" class="btn-action btn-detail" onclick="openSellerModal({{ $user->id }})">
                                            <i class="fas fa-id-badge"></i> {{ __('platform.view_seller_detail') }}
                                        </button>

                                        @if ($user->isSuspended())
                                            <form method="POST"
                                                  action="{{ route('platform-admin.users.activate', $user->id) }}"
                                                  style="display:inline;">
                                                @csrf
                                                <button type="button" class="btn-action btn-activate"
                                                        onclick="confirmActivateUser(this.form, '{{ addslashes($user->name) }}')">
                                                    <i class="fas fa-check"></i> {{ __('platform.activate') }}
                                                </button>
                                            </form>
                                        @else
                                            <button type="button" class="btn-action btn-suspend" 
                                                    onclick="openSuspendModal({{ $user->id }}, '{{ addslashes($user->name) }}')">
                                                <i class="fas fa-ban"></i> {{ __('platform.suspend') }}
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if ($users->hasPages())
                        <div class="pagination-wrap">
                            {{ $users->links() }}
                        </div>
                    @endif
                    @else
                    <div class="empty-state">
                        <i class="fas fa-users-slash"></i>
                        <p>{{ __('platform.no_users_found') }}</p>
                    </div>
                    @endif
                </div>

            @else
                {{-- Appeals View --}}
                <div class="table-card">
                    @if ($appeals->count() > 0)
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Seller</th>
                                <th>{{ __('platform.appeal_reason') }}</th>
                                <th>{{ __('platform.time') }}</th>
                                <th>{{ __('platform.status') }}</th>
                                <th>{{ __('platform.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($appeals as $i => $appeal)
                            <tr>
                                <td style="color:#94a3b8; font-weight:600; font-size:13px;">
                                    {{ $appeals->firstItem() + $i }}
                                </td>
                                <td>
                                    <div class="user-cell">
                                        <div class="user-avatar">
                                            @if ($appeal->user && $appeal->user->avatar)
                                                <img src="{{ Str::startsWith($appeal->user->avatar, ['http://', 'https://']) ? $appeal->user->avatar : asset('storage/' . $appeal->user->avatar) }}" alt="{{ $appeal->user->name }}">
                                            @else
                                                {{ strtoupper(substr($appeal->user->name ?? 'User', 0, 2)) }}
                                            @endif
                                        </div>
                                        <div>
                                            <div class="user-name">{{ $appeal->user->name ?? 'User Telah Dihapus' }}</div>
                                            <div class="user-email">{{ $appeal->user->email ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div style="font-size: 13px; color: #1e293b; line-height: 1.4; max-width: 380px;">
                                        {{ $appeal->appeal_reason }}
                                    </div>
                                    @if($appeal->admin_notes)
                                        <div style="font-size: 11px; color: #64748b; margin-top: 6px; background: #f8fafc; padding: 4px 8px; border-radius: 6px; border-left: 3px solid #5A5BF1;">
                                            <strong>{{ __('platform.admin_notes') }}:</strong> {{ $appeal->admin_notes }}
                                        </div>
                                    @endif
                                </td>
                                <td style="color:#64748b; font-size:12px; white-space: nowrap;">
                                    {{ $appeal->created_at->format('d M Y, H:i') }}
                                </td>
                                <td>
                                    @if($appeal->status === 'approved')
                                        <span class="badge badge-active">
                                            <i class="fas fa-check-circle"></i> {{ __('platform.appeal_approved') }}
                                        </span>
                                    @elseif($appeal->status === 'rejected')
                                        <span class="badge badge-suspended">
                                            <i class="fas fa-times-circle"></i> {{ __('platform.appeal_rejected') }}
                                        </span>
                                    @else
                                        <span class="badge badge-pending">
                                            <i class="fas fa-clock"></i> {{ __('platform.pending_review') }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($appeal->status === 'pending')
                                        <div class="action-group">
                                            <form action="{{ route('platform-admin.users.appeals.approve', $appeal->id) }}" method="POST" style="margin: 0;">
                                                @csrf
                                                <button type="button" class="btn-action btn-approve" onclick="confirmApproveAppeal(this.form, '{{ addslashes($appeal->user->name ?? 'Seller') }}')">
                                                    <i class="fas fa-check"></i> {{ __('platform.approve_appeal') }}
                                                </button>
                                            </form>
                                            <button type="button" class="btn-action btn-reject" onclick="openRejectAppealModal({{ $appeal->id }}, '{{ addslashes($appeal->user->name ?? 'Seller') }}')">
                                                <i class="fas fa-times"></i> {{ __('platform.reject_appeal') }}
                                            </button>
                                        </div>
                                    @else
                                        <span style="font-size: 12px; color: #94a3b8; font-weight: 600;">Selesai Ditinjau</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if ($appeals->hasPages())
                        <div class="pagination-wrap">
                            {{ $appeals->links() }}
                        </div>
                    @endif
                    @else
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <p>{{ __('platform.no_appeals_found') }}</p>
                    </div>
                    @endif
                </div>
            @endif

        </div>
    </div>

    <!-- Modal Suspend Akun (Pilihan Durasi & Alasan) -->
    <div id="suspendModal" class="modal">
        <div class="modal-container">
            <div class="modal-header">
                <h3><i class="fas fa-user-slash" style="color: #dc2626;"></i> {{ __('platform.suspend_account') }}</h3>
                <button type="button" class="modal-close" onclick="closeSuspendModal()">&times;</button>
            </div>
            <form id="suspendForm" method="POST" action="">
                @csrf
                <div class="modal-body">
                    <p style="font-size: 13px; color: #64748b; margin-bottom: 16px;">
                        Tentukan durasi penangguhan dan alasan suspend untuk akun <strong id="suspendTargetName" style="color: #1e293b;"></strong>:
                    </p>

                    <div class="form-group">
                        <label>{{ __('platform.suspend_duration') }}</label>
                        <div class="duration-grid">
                            <label class="duration-option">
                                <input type="radio" name="duration" value="1_day" required>
                                <div class="duration-card"><i class="fas fa-clock"></i> {{ __('platform.duration_1_day') }}</div>
                            </label>
                            <label class="duration-option">
                                <input type="radio" name="duration" value="3_days">
                                <div class="duration-card"><i class="fas fa-clock"></i> {{ __('platform.duration_3_days') }}</div>
                            </label>
                            <label class="duration-option">
                                <input type="radio" name="duration" value="7_days" checked>
                                <div class="duration-card"><i class="fas fa-calendar-week"></i> {{ __('platform.duration_7_days') }}</div>
                            </label>
                            <label class="duration-option">
                                <input type="radio" name="duration" value="30_days">
                                <div class="duration-card"><i class="fas fa-calendar-alt"></i> {{ __('platform.duration_30_days') }}</div>
                            </label>
                            <label class="duration-option" style="grid-column: span 2;">
                                <input type="radio" name="duration" value="permanent">
                                <div class="duration-card"><i class="fas fa-infinity"></i> {{ __('platform.duration_permanent') }}</div>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="suspend_reason">{{ __('platform.suspend_reason_label') }}</label>
                        <textarea id="suspend_reason" name="suspend_reason" rows="3" class="form-control" 
                                  placeholder="{{ __('platform.suspend_reason_placeholder') }}" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal-cancel" onclick="closeSuspendModal()">{{ __('platform.cancel') }}</button>
                    <button type="submit" class="btn-modal-submit-danger"><i class="fas fa-ban"></i> {{ __('platform.suspend') }}</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Tolak Permohonan Banding -->
    <div id="rejectAppealModal" class="modal">
        <div class="modal-container">
            <div class="modal-header">
                <h3><i class="fas fa-times-circle" style="color: #dc2626;"></i> {{ __('platform.reject_appeal') }}</h3>
                <button type="button" class="modal-close" onclick="closeRejectAppealModal()">&times;</button>
            </div>
            <form id="rejectAppealForm" method="POST" action="">
                @csrf
                <div class="modal-body">
                    <p style="font-size: 13px; color: #64748b; margin-bottom: 14px;">
                        Tolak permohonan banding dari <strong id="rejectTargetName" style="color: #1e293b;"></strong>. Berikan catatan penjelasan:
                    </p>
                    <div class="form-group">
                        <label for="admin_notes">{{ __('platform.rejection_reason_notes') }}</label>
                        <textarea id="admin_notes" name="admin_notes" rows="3" class="form-control" 
                                  placeholder="Tuliskan catatan alasan penolakan banding..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal-cancel" onclick="closeRejectAppealModal()">{{ __('platform.cancel') }}</button>
                    <button type="submit" class="btn-modal-submit-danger"><i class="fas fa-times"></i> {{ __('platform.reject_appeal') }}</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Detail / Inspeksi Lengkap Seller -->
    <div id="sellerModal" class="modal">
        <div class="modal-container modal-container-large">
            <div class="modal-header">
                <h3><i class="fas fa-user-shield" style="color: #5A5BF1;"></i> {{ __('platform.seller_profile_inspection') }}</h3>
                <button type="button" class="modal-close" onclick="closeSellerModal()">&times;</button>
            </div>
            <div class="modal-body" id="sellerModalBody">
                <div class="loading-spinner">
                    <i class="fas fa-spinner fa-spin" style="font-size: 28px; margin-bottom: 8px; display: block;"></i>
                    {{ __('platform.loading_data') }}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-modal-cancel" onclick="closeSellerModal()">{{ __('platform.close') }}</button>
            </div>
        </div>
    </div>

    <script>
        function openSuspendModal(userId, userName) {
            document.getElementById('suspendTargetName').textContent = userName;
            document.getElementById('suspendForm').action = `{{ url('/platform-admin/users') }}/${userId}/suspend`;
            document.getElementById('suspendModal').classList.add('show');
        }

        function closeSuspendModal() {
            document.getElementById('suspendModal').classList.remove('show');
        }

        function openRejectAppealModal(appealId, userName) {
            document.getElementById('rejectTargetName').textContent = userName;
            document.getElementById('rejectAppealForm').action = `{{ url('/platform-admin/users/appeals') }}/${appealId}/reject`;
            document.getElementById('rejectAppealModal').classList.add('show');
        }

        function closeRejectAppealModal() {
            document.getElementById('rejectAppealModal').classList.remove('show');
        }

        function openSellerModal(userId) {
            const modal = document.getElementById('sellerModal');
            const modalBody = document.getElementById('sellerModalBody');
            
            modal.classList.add('show');
            modalBody.innerHTML = `
                <div class="loading-spinner">
                    <i class="fas fa-spinner fa-spin" style="font-size: 28px; margin-bottom: 8px; display: block;"></i>
                    {{ __('platform.loading_data') }}
                </div>
            `;

            fetch(`{{ url('/platform-admin/users') }}/${userId}/detail`)
                .then(res => res.json())
                .then(data => {
                    if (data.status !== 'success') {
                        modalBody.innerHTML = `<div class="alert alert-error">{{ __('platform.failed_to_load') }}</div>`;
                        return;
                    }

                    const u = data.user;
                    const s = data.stats;
                    const products = data.recent_products;
                    const payouts = data.recent_payouts;

                    let avatarHtml = u.avatar 
                        ? `<img src="${u.avatar}" alt="${u.name}">` 
                        : u.name.substring(0, 2).toUpperCase();

                    let statusBadge = u.is_suspended 
                        ? `<span class="badge badge-suspended"><i class="fas fa-ban" style="font-size:10px;"></i> {{ __('platform.suspended') }}</span>`
                        : `<span class="badge badge-active"><i class="fas fa-circle" style="font-size:8px;"></i> {{ __('platform.active') }}</span>`;

                    let html = `
                        <!-- Seller Header Banner -->
                        <div class="seller-banner">
                            <div class="seller-banner-left">
                                <div class="seller-banner-avatar">${avatarHtml}</div>
                                <div class="seller-banner-info">
                                    <div class="name">${u.name}</div>
                                    <div class="email">${u.email} &bull; Bergabung: ${u.joined_at}</div>
                                    <a href="${u.microsite_url}" target="_blank" class="link">
                                        <i class="fas fa-external-link-alt"></i> ${u.microsite_url}
                                    </a>
                                </div>
                            </div>
                            <div>
                                ${statusBadge}
                            </div>
                        </div>

                        <!-- 4 Mini Financial Stats Grid -->
                        <div class="modal-stats-grid">
                            <div class="modal-stat-box">
                                <div class="box-lbl">{{ __('platform.total_turnover') }}</div>
                                <div class="box-val" style="color: #16a34a;">Rp ${Number(s.total_turnover).toLocaleString('id-ID')}</div>
                            </div>
                            <div class="modal-stat-box">
                                <div class="box-lbl">{{ __('platform.current_balance') }}</div>
                                <div class="box-val" style="color: #5A5BF1;">Rp ${Number(s.current_balance).toLocaleString('id-ID')}</div>
                            </div>
                            <div class="modal-stat-box">
                                <div class="box-lbl">{{ __('platform.total_withdrawn') }}</div>
                                <div class="box-val" style="color: #d97706;">Rp ${Number(s.total_withdrawn).toLocaleString('id-ID')}</div>
                            </div>
                            <div class="modal-stat-box">
                                <div class="box-lbl">{{ __('platform.total_orders') }}</div>
                                <div class="box-val">${s.total_orders} Pesanan</div>
                            </div>
                        </div>

                        <!-- Extra Details Grid -->
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px; font-size: 13px;">
                            <div style="background: #f8fafc; padding: 12px 16px; border-radius: 10px; border: 1px solid #f1f5f9;">
                                <strong><i class="fas fa-boxes" style="color: #5A5BF1; margin-right: 6px;"></i> Produk Digital:</strong>
                                <div style="margin-top: 4px; color: #64748b;">
                                    Total: <strong>${s.total_products}</strong> &bull; Live: <strong style="color: #16a34a;">${s.active_products}</strong> &bull; Pending: <strong style="color: #d97706;">${s.pending_products}</strong> &bull; Takedown: <strong style="color: #dc2626;">${s.takedown_products}</strong>
                                </div>
                            </div>
                            <div style="background: #f8fafc; padding: 12px 16px; border-radius: 10px; border: 1px solid #f1f5f9;">
                                <strong><i class="fas fa-chart-line" style="color: #5A5BF1; margin-right: 6px;"></i> Kunjungan Microsite:</strong>
                                <div style="margin-top: 4px; color: #64748b;">
                                    Views: <strong>${s.total_views}</strong> &bull; Clicks: <strong>${s.total_clicks}</strong>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Tabs (Products & Payouts) -->
                        <div class="modal-tabs">
                            <button type="button" class="modal-tab-btn active" onclick="switchModalTab('tabProducts')">
                                <i class="fas fa-box"></i> {{ __('platform.products_tab') }} (${products.length})
                            </button>
                            <button type="button" class="modal-tab-btn" onclick="switchModalTab('tabPayouts')">
                                <i class="fas fa-money-bill-wave"></i> {{ __('platform.payouts_tab') }} (${payouts.length})
                            </button>
                        </div>

                        <!-- Tab 1: Products -->
                        <div id="tabProducts" class="modal-tab-content active">
                            ${products.length > 0 ? `
                                <table class="modal-mini-table">
                                    <thead>
                                        <tr>
                                            <th>Judul Produk</th>
                                            <th>Harga</th>
                                            <th>Platform</th>
                                            <th>Verifikasi</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${products.map(p => `
                                            <tr>
                                                <td style="font-weight: 700;">${p.title}</td>
                                                <td>Rp ${Number(p.sale_price || p.price).toLocaleString('id-ID')}</td>
                                                <td><span class="badge badge-role" style="font-size: 10px;">${p.platform_type}</span></td>
                                                <td>
                                                    <span class="badge ${p.verification_status === 'approved' ? 'badge-active' : (p.verification_status === 'rejected' ? 'badge-suspended' : 'badge-role')}" style="font-size: 10px;">
                                                        ${p.verification_status}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge ${p.is_active ? 'badge-active' : 'badge-suspended'}" style="font-size: 10px;">
                                                        ${p.is_active ? 'Live' : 'Takedown'}
                                                    </span>
                                                </td>
                                            </tr>
                                        `).join('')}
                                    </tbody>
                                </table>
                            ` : `<div style="text-align: center; padding: 20px; color: #94a3b8; font-size: 13px;">{{ __('platform.no_products_seller') }}</div>`}
                        </div>

                        <!-- Tab 2: Payouts -->
                        <div id="tabPayouts" class="modal-tab-content">
                            ${payouts.length > 0 ? `
                                <table class="modal-mini-table">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Nominal Bersih</th>
                                            <th>Fee Platform</th>
                                            <th>Metode</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${payouts.map(po => `
                                            <tr>
                                                <td>${new Date(po.created_at).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })}</td>
                                                <td style="font-weight: 700; color: #16a34a;">Rp ${Number(po.amount).toLocaleString('id-ID')}</td>
                                                <td style="color: #64748b;">Rp ${Number(po.commission || 0).toLocaleString('id-ID')}</td>
                                                <td>${po.method || '-'}</td>
                                                <td>
                                                    <span class="badge ${po.status === 'approved' ? 'badge-active' : (po.status === 'rejected' ? 'badge-suspended' : 'badge-role')}" style="font-size: 10px;">
                                                        ${po.status}
                                                    </span>
                                                </td>
                                            </tr>
                                        `).join('')}
                                    </tbody>
                                </table>
                            ` : `<div style="text-align: center; padding: 20px; color: #94a3b8; font-size: 13px;">{{ __('platform.no_payouts_seller') }}</div>`}
                        </div>
                    `;

                    modalBody.innerHTML = html;
                })
                .catch(err => {
                    modalBody.innerHTML = `<div class="alert alert-error">{{ __('platform.failed_to_load') }}</div>`;
                });
        }

        function switchModalTab(tabId) {
            document.querySelectorAll('.modal-tab-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.modal-tab-content').forEach(content => content.classList.remove('active'));

            event.target.classList.add('active');
            const targetContent = document.getElementById(tabId);
            if (targetContent) targetContent.classList.add('active');
        }

        function closeSellerModal() {
            document.getElementById('sellerModal').classList.remove('show');
        }

        function confirmActivateUser(form, userName) {
            showConfirmModal({
                title: 'Aktifkan Kembali Akun?',
                text: `Status penangguhan (suspend) untuk akun ${userName} akan dicabut dan seluruh akses fitur akan dipulihkan.`,
                icon: 'question',
                confirmText: '<i class="fas fa-check"></i> Ya, Aktifkan',
                onConfirm: () => {
                    form.submit();
                }
            });
        }

        function confirmApproveAppeal(form, userName) {
            showConfirmModal({
                title: 'Setujui Permohonan Banding?',
                text: `Permohonan banding dari ${userName} akan disetujui, dan status suspend akun akan langsung dipulihkan seketika.`,
                icon: 'question',
                confirmText: '<i class="fas fa-check"></i> Ya, Setujui & Pulihkan',
                onConfirm: () => {
                    form.submit();
                }
            });
        }
    </script>
</body>
</html>
