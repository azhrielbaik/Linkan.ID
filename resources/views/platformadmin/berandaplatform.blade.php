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
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', Arial, sans-serif; }

        body { background: #f5f6fa; color: #334155; display: flex; min-height: 100vh; }

        /* ---- Header ---- */
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
            z-index: 100;
        }
        .platform-header-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .platform-header h1 {
            font-size: 22px;
            font-weight: 800;
            color: #5A5BF1;
            letter-spacing: -0.5px;
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
        .header-right { display: flex; align-items: center; gap: 12px; }
        .header-user {
            display: flex; align-items: center; gap: 10px;
            background: #f8f9ff; border: 1px solid #eef0fe;
            border-radius: 30px;
            padding: 5px 14px 5px 5px;
        }
        .header-avatar {
            width: 34px; height: 34px; border-radius: 50%;
            background: #5A5BF1; color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 13px;
        }
        .header-user span { font-size: 14px; font-weight: 600; color: #1e293b; }

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

        .content-wrapper {
            padding: 28px 40px;
            flex: 1;
        }

        /* ---- Stats Cards (4 Grid) ---- */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin-bottom: 24px;
        }
        .stat-card {
            background: #fff;
            border-radius: 16px;
            padding: 22px;
            box-shadow: 0 1px 6px rgba(0,0,0,0.04);
            display: flex;
            align-items: center;
            gap: 16px;
            border: 1px solid #f0f2f5;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(90,91,241,0.08);
        }
        .stat-icon-wrap {
            width: 50px; height: 50px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }
        .stat-card.users .stat-icon-wrap { background: #EEF0FE; color: #5A5BF1; }
        .stat-card.tx    .stat-icon-wrap { background: #dcfce7; color: #16a34a; }
        .stat-card.comm  .stat-icon-wrap { background: #fef3c7; color: #d97706; }
        .stat-card.prod  .stat-icon-wrap { background: #f1f5f9; color: #475569; }

        .stat-info .label {
            font-size: 12px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 4px;
        }
        .stat-info .value {
            font-size: 22px;
            font-weight: 800;
            color: #1e293b;
            line-height: 1.2;
        }

        /* ---- Action Toolbar / Export ---- */
        .dashboard-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 14px;
        }
        .toolbar-title {
            font-size: 18px;
            font-weight: 800;
            color: #1e293b;
        }
        .export-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .btn-export {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #ffffff;
            color: #1e293b;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 9px 16px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            font-family: inherit;
            transition: all 0.2s ease;
        }
        .btn-export:hover {
            border-color: #5A5BF1;
            color: #5A5BF1;
            background: #f8f9ff;
        }
        .btn-export.btn-primary {
            background: #5A5BF1;
            border-color: #5A5BF1;
            color: #ffffff;
        }
        .btn-export.btn-primary:hover {
            background: #4748d0;
            box-shadow: 0 4px 12px rgba(90,91,241,0.25);
        }

        /* ---- Chart Section ---- */
        .chart-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 1px 6px rgba(0,0,0,0.04);
            border: 1px solid #f0f2f5;
        }
        .chart-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 12px;
        }
        .chart-header h3 {
            font-size: 16px;
            font-weight: 800;
            color: #1e293b;
        }
        .chart-period-tabs {
            display: flex;
            background: #f1f5f9;
            padding: 4px;
            border-radius: 10px;
            gap: 4px;
        }
        .chart-tab-btn {
            background: transparent;
            border: none;
            padding: 6px 14px;
            font-size: 12px;
            font-weight: 700;
            color: #64748b;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: inherit;
        }
        .chart-tab-btn.active {
            background: #ffffff;
            color: #5A5BF1;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        }
        .chart-canvas-wrapper {
            position: relative;
            height: 300px;
            width: 100%;
        }

        /* ---- Two Column Layout: Top Sellers & Recent Commissions ---- */
        .two-col-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .section-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 1px 6px rgba(0,0,0,0.04);
            border: 1px solid #f0f2f5;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        .section-card-header {
            padding: 18px 24px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .section-card-header h3 {
            font-size: 15px;
            font-weight: 800;
            color: #1e293b;
        }

        /* Top Seller List */
        .seller-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .seller-list-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 20px;
            border-bottom: 1px solid #f8f9ff;
            transition: background 0.15s ease;
        }
        .seller-list-item:last-child { border-bottom: none; }
        .seller-list-item:hover { background: #f8f9ff; }

        .seller-left-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .rank-badge {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 800;
            flex-shrink: 0;
        }
        .rank-1 { background: #fef08a; color: #854d0e; }
        .rank-2 { background: #e2e8f0; color: #475569; }
        .rank-3 { background: #fed7aa; color: #9a3412; }
        .rank-other { background: #f1f5f9; color: #64748b; }

        .seller-avatar-small {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #5A5BF1, #818cf8);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 12px;
            flex-shrink: 0;
        }

        .seller-text .name {
            font-weight: 700;
            font-size: 13px;
            color: #1e293b;
        }
        .seller-text .sub {
            font-size: 11px;
            color: #94a3b8;
        }

        .seller-stats-right {
            text-align: right;
        }
        .seller-commission-earned {
            font-weight: 800;
            color: #16a34a;
            font-size: 13px;
        }
        .seller-sales-count {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 1px;
        }

        /* Commission Table */
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
            padding: 12px 18px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            border-bottom: 1px solid #eef0fe;
            white-space: nowrap;
        }
        tbody tr {
            border-bottom: 1px solid #f8f9ff;
            transition: background 0.15s;
        }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: #f8f9ff; }
        tbody td {
            padding: 12px 18px;
            font-size: 13px;
            color: #334155;
            vertical-align: middle;
        }

        .amount-chip {
            display: inline-flex;
            align-items: center;
            background: #EEF0FE;
            color: #5A5BF1;
            font-weight: 800;
            font-size: 12px;
            padding: 4px 10px;
            border-radius: 16px;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #94a3b8;
        }
        .empty-state i { font-size: 2rem; margin-bottom: 8px; color: #cbd5e1; }
        .empty-state p { font-size: 13px; font-weight: 600; }

        /* Responsive */
        @media (max-width: 1100px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .two-col-grid { grid-template-columns: 1fr; }
        }

        @media (max-width: 900px) {
            .platform-main { margin-left: 0 !important; }
            .hamburger-btn { display: block; }
            .platform-header { padding: 14px 20px; }
            .content-wrapper { padding: 20px 16px; }
            .stats-grid { grid-template-columns: 1fr; }
            .dashboard-toolbar { flex-direction: column; align-items: stretch; }
            .export-actions { flex-direction: column; width: 100%; }
            .btn-export { justify-content: center; width: 100%; }
        }
    </style>
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
                <div class="header-user">
                    <div class="header-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                    <span>{{ Auth::user()->name }}</span>
                </div>
            </div>
        </div>

        <div class="content-wrapper">

            {{-- 1. Dashboard Ringkasan (4 Stats Cards) --}}
            <div class="stats-grid">
                <div class="stat-card users">
                    <div class="stat-icon-wrap"><i class="fas fa-users"></i></div>
                    <div class="stat-info">
                        <div class="label">{{ __('platform.total_users') }}</div>
                        <div class="value">{{ number_format($totalUsers, 0, ',', '.') }}</div>
                    </div>
                </div>

                <div class="stat-card tx">
                    <div class="stat-icon-wrap"><i class="fas fa-receipt"></i></div>
                    <div class="stat-info">
                        <div class="label">{{ __('platform.total_transactions') }}</div>
                        <div class="value">{{ number_format($totalTransactions, 0, ',', '.') }}</div>
                    </div>
                </div>

                <div class="stat-card comm">
                    <div class="stat-icon-wrap"><i class="fas fa-coins"></i></div>
                    <div class="stat-info">
                        <div class="label">{{ __('platform.total_commission') }}</div>
                        <div class="value">Rp {{ number_format($totalCommission, 0, ',', '.') }}</div>
                    </div>
                </div>

                <div class="stat-card prod">
                    <div class="stat-icon-wrap"><i class="fas fa-box-open"></i></div>
                    <div class="stat-info">
                        <div class="label">{{ __('platform.total_products') }}</div>
                        <div class="value">{{ number_format($totalProducts, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>

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
                        <div style="font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase;">Trend Komisi Platform</div>
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
                        <h3><i class="fas fa-trophy" style="color: #eab308; margin-right: 6px;"></i>{{ __('platform.top_sellers') }}</h3>
                        <span style="font-size: 12px; color: #94a3b8;">Top 5 Seller</span>
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
                        <h3><i class="fas fa-history" style="color: #5A5BF1; margin-right: 6px;"></i>{{ __('platform.recent_commissions') }}</h3>
                        <span style="font-size: 12px; color: #94a3b8;">Live Feed</span>
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
                                        <div style="font-weight: 700; color: #1e293b;">{{ $c->seller_name }}</div>
                                        <div style="font-size: 11px; color: #94a3b8;">{{ $c->seller_email }}</div>
                                    </td>
                                    <td style="color: #64748b; font-size: 12px; white-space: nowrap;">
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
        // Data Chart dari Backend Controller
        const monthlyLabels = @json($monthlyLabels);
        const monthlyData   = @json($monthlyData);
        const weeklyLabels  = @json($weeklyLabels);
        const weeklyData    = @json($weeklyData);

        let earningsChartInstance = null;

        function initEarningsChart(labels, data) {
            const ctx = document.getElementById('earningsChart').getContext('2d');
            
            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(90, 91, 241, 0.28)');
            gradient.addColorStop(1, 'rgba(90, 91, 241, 0.00)');

            if (earningsChartInstance) {
                earningsChartInstance.destroy();
            }

            earningsChartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Komisi Platform (Rp)',
                        data: data,
                        borderColor: '#5A5BF1',
                        borderWidth: 2.5,
                        backgroundColor: gradient,
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#5A5BF1',
                        pointBorderWidth: 2.5,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointHoverBackgroundColor: '#5A5BF1',
                        pointHoverBorderColor: '#ffffff',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            titleFont: { family: 'Plus Jakarta Sans', size: 13, weight: '700' },
                            bodyFont: { family: 'Plus Jakarta Sans', size: 12 },
                            padding: 12,
                            cornerRadius: 10,
                            callbacks: {
                                label: function(context) {
                                    return 'Komisi: Rp ' + Number(context.raw).toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: {
                                font: { family: 'Plus Jakarta Sans', size: 12, weight: '600' },
                                color: '#94a3b8'
                            }
                        },
                        y: {
                            grid: { color: '#f1f5f9' },
                            ticks: {
                                font: { family: 'Plus Jakarta Sans', size: 11 },
                                color: '#94a3b8',
                                callback: function(value) {
                                    return 'Rp ' + Number(value).toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });
        }

        function switchChartPeriod(period) {
            const btnMonthly = document.getElementById('btnMonthly');
            const btnWeekly  = document.getElementById('btnWeekly');

            if (period === 'monthly') {
                btnMonthly.classList.add('active');
                btnWeekly.classList.remove('active');
                initEarningsChart(monthlyLabels, monthlyData);
            } else {
                btnWeekly.classList.add('active');
                btnMonthly.classList.remove('active');
                initEarningsChart(weeklyLabels, weeklyData);
            }
        }

        // Inisialisasi Chart Default Bulanan
        document.addEventListener('DOMContentLoaded', function() {
            initEarningsChart(monthlyLabels, monthlyData);
        });

        // Print / PDF Laporan
        function printCommissionReport() {
            fetch('{{ route('platform-admin.commissions') }}')
                .then(res => res.json())
                .then(data => {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("platform-admin.print") }}';
                    form.target = '_blank';

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);

                    const postData = {
                        total_earnings: 'IDR ' + Number(data.total_earnings).toLocaleString('id-ID'),
                        commission_details: data.commissions.map(c => ({
                            name: c.seller_name,
                            email: c.seller_email,
                            date: new Date(c.created_at).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }),
                            amount: 'Rp ' + Number(c.commission).toLocaleString('id-ID')
                        }))
                    };

                    const dataInput = document.createElement('input');
                    dataInput.type = 'hidden';
                    dataInput.name = 'data';
                    dataInput.value = JSON.stringify(postData);
                    form.appendChild(dataInput);

                    document.body.appendChild(form);
                    form.submit();
                    document.body.removeChild(form);
                });
        }
    </script>
</body>
</html>
