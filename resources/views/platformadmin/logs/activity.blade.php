<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Aktivitas Admin — Platform Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/platform/notifications.css') }}">
    <link rel="stylesheet" href="{{ asset('css/platform/activity.css') }}">
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
                @include('platformadmin.partials.notifications')
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

    <script src="{{ asset('js/platform/notifications.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/platform/activity.js') }}"></script>
</body>
</html>
