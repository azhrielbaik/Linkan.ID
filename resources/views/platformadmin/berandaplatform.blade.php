<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('platform.dashboard') }} — Platform Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="{{ asset('css/platform/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/platform/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/platform/notifications.css') }}">
    <link rel="stylesheet" href="{{ asset('css/platform/berandaplatform.css') }}">
</head>
<body>

    {{-- Sidebar --}}
    @include('platformadmin.sidebar.sidebarplatform')

    <div class="platform-main">
        {{-- Header Bar --}}
        <div class="platform-header">
            <div class="platform-header-left">
                <button class="hamburger-btn" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
                <h1>{{ __('platform.dashboard') }}</h1>
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

            {{-- Toolbar Export --}}
            <div class="dashboard-toolbar">
                <div class="toolbar-title">{{ __('platform.platform_earnings_chart') }}</div>
                <div class="export-actions">
                    <a href="{{ route('platform-admin.export.excel') }}" class="btn-export">
                        <i class="fas fa-file-excel" style="color: #16a34a;"></i> {{ __('platform.export_excel') }}
                    </a>
                    <button class="btn-export btn-primary" onclick="printCommissionReport()">
                        <i class="fas fa-print"></i> {{ __('platform.export_pdf') }}
                    </button>
                </div>
            </div>

            {{-- 2. Grafik Pendapatan Platform --}}
            <div class="chart-card">
                <div class="chart-header">
                    <div>
                        <div class="chart-header-sub">Trend Komisi Platform</div>
                        <h3>Total Komisi Masuk</h3>
                    </div>
                    <div class="chart-period-tabs">
                        <button type="button" class="chart-tab-btn active" id="btnMonthly" onclick="switchChartPeriod('monthly')">
                            {{ __('platform.monthly') }}
                        </button>
                        <button type="button" class="chart-tab-btn" id="btnWeekly" onclick="switchChartPeriod('weekly')">
                            {{ __('platform.weekly') }}
                        </button>
                    </div>
                </div>
                <div class="chart-canvas-wrapper">
                    <canvas id="earningsChart"></canvas>
                </div>
            </div>

            {{-- 3 & 4. Top Seller & Riwayat Komisi Terkini --}}
            <div class="two-col-grid">
                
                {{-- Top Seller Ranking --}}
                <div class="section-card">
                    <div class="section-card-header">
                        <h3><i class="fas fa-trophy" style="color: #ED842C; margin-right: 6px;"></i>{{ __('platform.top_sellers') }}</h3>
                        <span class="section-card-subtitle">Top 5 Seller</span>
                    </div>
                    <ul class="seller-list">
                        @forelse($topSellers as $idx => $seller)
                        <li class="seller-list-item">
                            <div class="seller-left-info">
                                @php
                                    $rankClass = 'rank-other';
                                    if ($idx === 0) $rankClass = 'rank-1';
                                    elseif ($idx === 1) $rankClass = 'rank-2';
                                    elseif ($idx === 2) $rankClass = 'rank-3';
                                @endphp
                                <div class="rank-badge {{ $rankClass }}">
                                    {{ $idx + 1 }}
                                </div>
                                <div class="seller-avatar-small">
                                    {{ strtoupper(substr($seller->name, 0, 2)) }}
                                </div>
                                <div class="seller-text">
                                    <div class="name">{{ $seller->name }}</div>
                                    <div class="sub">{{ $seller->total_products }} Produk &bull; {{ $seller->total_sales_count }} Penjualan</div>
                                </div>
                            </div>
                            <div class="seller-stats-right">
                                <div class="seller-commission-earned">Rp {{ number_format($seller->total_commission_earned, 0, ',', '.') }}</div>
                                <div class="seller-sales-count">Komisi Platform</div>
                            </div>
                        </li>
                        @empty
                        <li class="empty-state">
                            <i class="fas fa-trophy"></i>
                            <p>{{ __('platform.no_top_sellers') }}</p>
                        </li>
                        @endforelse
                    </ul>
                </div>

                {{-- Riwayat Komisi Terkini --}}
                <div class="section-card">
                    <div class="section-card-header">
                        <h3><i class="fas fa-history" style="color: #ED842C; margin-right: 6px;"></i>{{ __('platform.recent_commissions') }}</h3>
                        <span class="section-card-subtitle">Live Feed</span>
                    </div>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>{{ __('platform.seller') }}</th>
                                    <th>{{ __('platform.date') }}</th>
                                    <th>{{ __('platform.commission') }}</th>
                                </tr>
                            </thead>
                            <tbody id="commissionTableBody">
                                @forelse($commissions as $c)
                                <tr>
                                    <td>
                                        <div class="table-seller-name">{{ $c->seller_name }}</div>
                                        <div class="table-seller-email">{{ $c->seller_email }}</div>
                                    </td>
                                    <td class="table-date">
                                        {{ \Carbon\Carbon::parse($c->created_at)->format('d M Y') }}
                                    </td>
                                    <td>
                                        <span class="amount-chip">Rp {{ number_format($c->commission, 0, ',', '.') }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3">
                                        <div class="empty-state">
                                            <i class="fas fa-inbox"></i>
                                            <p>{{ __('platform.no_commission_data') }}</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <script>
        window.PlatformDashboardData = {
            monthlyLabels: @json($monthlyLabels),
            monthlyData: @json($monthlyData),
            weeklyLabels: @json($weeklyLabels),
            weeklyData: @json($weeklyData),
            commissionsUrl: '{{ route('platform-admin.commissions') }}',
            printUrl: '{{ route("platform-admin.print") }}',
            csrfToken: '{{ csrf_token() }}'
        };
    </script>
    <script src="{{ asset('js/platform/notifications.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/platform/berandaplatform.js') }}"></script>
</body>
</html>
