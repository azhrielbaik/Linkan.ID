<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Transaksi Global — Platform Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/platform/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/platform/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/platform/notifications.css') }}">
    <link rel="stylesheet" href="{{ asset('css/platform/transactions.css') }}">
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
                <h1>{{ __('platform.transaction_logs') }}</h1>
            </div>
            <div class="header-right">
                @include('platformadmin.partials.notifications')
                @include('platformadmin.partials.header_profile')
            </div>
        </div>

        <div class="content-wrapper">

            {{-- Stats Grid --}}
            <div class="stats-grid transaction-summary-grid">
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

    <script src="{{ asset('js/platform/notifications.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/platform/transactions.js') }}"></script>
</body>
</html>
