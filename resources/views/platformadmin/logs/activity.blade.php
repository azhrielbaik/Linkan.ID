<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Aktivitas Admin — Platform Admin</title>
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

        .stat-card.total .stat-icon-wrapper { background: #EEF0FE; color: #5A5BF1; }
        .stat-card.users .stat-icon-wrapper { background: #fee2e2; color: #dc2626; }
        .stat-card.products .stat-icon-wrapper { background: #dcfce7; color: #16a34a; }
        .stat-card.payouts .stat-icon-wrapper { background: #fef3c7; color: #d97706; }

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

        /* Admin Cell */
        .admin-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .admin-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #5A5BF1;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 12px;
            flex-shrink: 0;
        }

        .admin-name {
            font-weight: 700;
            color: #1e293b;
            font-size: 13px;
        }

        .admin-email {
            font-size: 11px;
            color: #94a3b8;
        }

        /* Action Badges */
        .badge-action {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 12px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 12px;
            white-space: nowrap;
        }

        .badge-suspend { background: #fee2e2; color: #dc2626; }
        .badge-activate { background: #dcfce7; color: #16a34a; }
        .badge-approve { background: #dcfce7; color: #15803d; }
        .badge-reject { background: #fee2e2; color: #b91c1c; }
        .badge-default { background: #EEF0FE; color: #5A5BF1; }

        .desc-text {
            font-size: 13px;
            color: #1e293b;
            line-height: 1.5;
            max-width: 360px;
        }

        .ip-badge {
            font-family: monospace;
            background: #f1f5f9;
            color: #475569;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
        }

        .ua-text {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 3px;
            max-width: 180px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

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
                <h1>{{ __('platform.activity_logs') }}</h1>
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
                <div class="stat-card total">
                    <div class="stat-icon-wrapper"><i class="fas fa-list"></i></div>
                    <div class="stat-info">
                        <div class="stat-label">{{ __('platform.total_activity') }}</div>
                        <div class="stat-val">{{ $totalLogsCount }} Log</div>
                        <div class="stat-sub">{{ __('platform.recorded_in_system') }}</div>
                    </div>
                </div>

                <div class="stat-card users">
                    <div class="stat-icon-wrapper"><i class="fas fa-user-shield"></i></div>
                    <div class="stat-info">
                        <div class="stat-label">{{ __('platform.user_management') }}</div>
                        <div class="stat-val">{{ $userActionCount }} {{ __('platform.actions') }}</div>
                        <div class="stat-sub">Suspend / {{ __('platform.activate') }}</div>
                    </div>
                </div>

                <div class="stat-card products">
                    <div class="stat-icon-wrapper"><i class="fas fa-check-double"></i></div>
                    <div class="stat-info">
                        <div class="stat-label">{{ __('platform.product_verification') }}</div>
                        <div class="stat-val">{{ $productActionCount }} {{ __('platform.actions') }}</div>
                        <div class="stat-sub">{{ __('platform.approve') }} / {{ __('platform.reject') }}</div>
                    </div>
                </div>

                <div class="stat-card payouts">
                    <div class="stat-icon-wrapper"><i class="fas fa-money-check-alt"></i></div>
                    <div class="stat-info">
                        <div class="stat-label">{{ __('platform.payout_management') }}</div>
                        <div class="stat-val">{{ $payoutActionCount }} {{ __('platform.actions') }}</div>
                        <div class="stat-sub">{{ __('platform.approve') }} / {{ __('platform.reject') }}</div>
                    </div>
                </div>
            </div>

            {{-- Tabs --}}
            <div class="tabs-container">
                <a href="{{ route('platform-admin.logs.activity', array_merge(request()->except('category', 'page'), ['category' => 'all'])) }}"
                   class="tab-link {{ ($category ?? 'all') === 'all' ? 'active' : '' }}">
                    {{ __('platform.all_activities') }}
                </a>
                <a href="{{ route('platform-admin.logs.activity', array_merge(request()->except('category', 'page'), ['category' => 'user'])) }}"
                   class="tab-link {{ ($category ?? '') === 'user' ? 'active' : '' }}">
                    {{ __('platform.user_management') }}
                </a>
                <a href="{{ route('platform-admin.logs.activity', array_merge(request()->except('category', 'page'), ['category' => 'product'])) }}"
                   class="tab-link {{ ($category ?? '') === 'product' ? 'active' : '' }}">
                    {{ __('platform.product_verification') }}
                </a>
                <a href="{{ route('platform-admin.logs.activity', array_merge(request()->except('category', 'page'), ['category' => 'payout'])) }}"
                   class="tab-link {{ ($category ?? '') === 'payout' ? 'active' : '' }}">
                    {{ __('platform.payout_management') }}
                </a>
            </div>

            {{-- Filter & Search Card --}}
            <div class="filter-card">
                <form method="GET" action="{{ route('platform-admin.logs.activity') }}" class="search-form">
                    <input type="hidden" name="category" value="{{ $category }}">
                    
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="{{ __('platform.search_activity_placeholder') }}">
                    </div>

                    <select name="action" class="filter-select">
                        <option value="">{{ __('platform.all_action_types') }}</option>
                        <option value="suspend_user" {{ ($action ?? '') === 'suspend_user' ? 'selected' : '' }}>Suspend User</option>
                        <option value="activate_user" {{ ($action ?? '') === 'activate_user' ? 'selected' : '' }}>{{ __('platform.activate') }} User</option>
                        <option value="approve_product" {{ ($action ?? '') === 'approve_product' ? 'selected' : '' }}>Approve Produk</option>
                        <option value="reject_product" {{ ($action ?? '') === 'reject_product' ? 'selected' : '' }}>Reject Produk</option>
                        <option value="approve_payout" {{ ($action ?? '') === 'approve_payout' ? 'selected' : '' }}>Approve Payout</option>
                        <option value="reject_payout" {{ ($action ?? '') === 'reject_payout' ? 'selected' : '' }}>Reject Payout</option>
                    </select>

                    <div style="display: flex; align-items: center; gap: 6px;">
                        <input type="date" name="start_date" value="{{ $startDate ?? '' }}" class="date-input" title="{{ __('platform.from_date') }}">
                        <span style="color: #94a3b8;">-</span>
                        <input type="date" name="end_date" value="{{ $endDate ?? '' }}" class="date-input" title="{{ __('platform.to_date') }}">
                    </div>

                    <button type="submit" class="btn-filter"><i class="fas fa-filter"></i> {{ __('platform.filter') }}</button>
                    @if($search || $action || $startDate || $endDate)
                        <a href="{{ route('platform-admin.logs.activity', ['category' => $category]) }}" class="btn-reset">{{ __('platform.reset') }}</a>
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
                                <th>{{ __('platform.time') }}</th>
                                <th>{{ __('platform.admin') }}</th>
                                <th>{{ __('platform.action') }}</th>
                                <th>{{ __('platform.activity_description') }}</th>
                                <th>{{ __('platform.ip_and_device') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $index => $log)
                            <tr>
                                <td style="font-weight: 700; color: #94a3b8;">
                                    {{ $logs->firstItem() + $index }}
                                </td>
                                <td style="white-space: nowrap;">
                                    <div style="font-weight: 700; color: #1e293b;">
                                        {{ $log->created_at->format('d M Y, H:i') }}
                                    </div>
                                    <div style="font-size: 11px; color: #94a3b8;">
                                        {{ $log->created_at->diffForHumans() }}
                                    </div>
                                </td>
                                <td>
                                    <div class="admin-cell">
                                        <div class="admin-avatar">
                                            {{ strtoupper(substr($log->user->name ?? 'A', 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="admin-name">{{ $log->user->name ?? 'Admin Sistem' }}</div>
                                            <div class="admin-email">{{ $log->user->email ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $badgeClass = 'badge-default';
                                        $icon = 'info-circle';
                                        $label = ucwords(str_replace('_', ' ', $log->action));

                                        if (str_contains($log->action, 'suspend')) {
                                            $badgeClass = 'badge-suspend';
                                            $icon = 'ban';
                                            $label = 'Suspend User';
                                        } elseif (str_contains($log->action, 'activate')) {
                                            $badgeClass = 'badge-activate';
                                            $icon = 'check-circle';
                                            $label = __('platform.activate') . ' User';
                                        } elseif (str_contains($log->action, 'approve')) {
                                            $badgeClass = 'badge-approve';
                                            $icon = 'check';
                                            $label = str_contains($log->action, 'product') ? 'Approve Produk' : 'Approve Payout';
                                        } elseif (str_contains($log->action, 'reject')) {
                                            $badgeClass = 'badge-reject';
                                            $icon = 'times';
                                            $label = str_contains($log->action, 'product') ? 'Reject Produk' : 'Reject Payout';
                                        }
                                    @endphp
                                    <span class="badge-action {{ $badgeClass }}">
                                        <i class="fas fa-{{ $icon }}"></i> {{ $label }}
                                    </span>
                                </td>
                                <td>
                                    <div class="desc-text">{{ $log->description }}</div>
                                </td>
                                <td>
                                    <div class="ip-badge">{{ $log->ip_address ?? '127.0.0.1' }}</div>
                                    @if($log->user_agent)
                                        <div class="ua-text" title="{{ $log->user_agent }}">
                                            {{ Str::limit($log->user_agent, 28) }}
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <i class="fas fa-clipboard"></i>
                                        <p>{{ __('platform.no_activity_logs') }}</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($logs->hasPages())
                    <div class="pagination-container">
                        {{ $logs->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

</body>
</html>
