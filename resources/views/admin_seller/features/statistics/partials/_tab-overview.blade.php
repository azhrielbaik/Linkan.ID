{{-- Tab: Overview --}}
<div id="tab-overview" class="sa-tab-content active analytics-tab-stack">

    <!-- -------------------------------------------------------------
         CARD 1: MASTER HERO CARD (ANALISIS PERFORMA & KONVERSI)
    ------------------------------------------------------------- -->
    <div class="analytics-card">
        <div class="analytics-card-header">
            <h3 class="analytics-card-title">
                <i class="fas fa-bullseye text-orange-500"></i>
                <span>Analisis Performa & Konversi</span>
            </h3>

            <!-- Modern Date Range Trigger & Popover -->
            <div class="analytics-datepicker-anchor" id="datePickerAnchor" style="position: relative;">
                <button type="button" id="btnDateRangeTrigger" onclick="AnalyticsPage.datepicker.toggle()" class="analytics-date-trigger-pill">
                    <i class="far fa-calendar-alt text-indigo-500"></i>
                    <span id="dateTriggerLabel">
                        {{ \Carbon\Carbon::parse($analytics['start_date'])->format('M d, Y') }} – {{ \Carbon\Carbon::parse($analytics['end_date'])->format('M d, Y') }}
                    </span>
                    <i class="fas fa-chevron-down text-[10px] text-gray-400" id="dateTriggerChevron"></i>
                </button>

                <!-- Modern SaaS Date Range Picker Popover (Matching Reference Image) -->
                <div id="customDateExpandableBar" class="analytics-datepicker-popover" style="display: none;">
                    <div class="analytics-dp-wrapper">
                        
                        <!-- Left Sidebar: Presets (Desktop) -->
                        <div class="analytics-dp-sidebar">
                            <button type="button" class="analytics-dp-preset-btn {{ ($analytics['preset'] ?? '') === 'today' ? 'active' : '' }}" data-preset="today" onclick="AnalyticsPage.datepicker.selectPreset('today')">Today</button>
                            <button type="button" class="analytics-dp-preset-btn {{ ($analytics['preset'] ?? '') === 'yesterday' ? 'active' : '' }}" data-preset="yesterday" onclick="AnalyticsPage.datepicker.selectPreset('yesterday')">Yesterday</button>
                            <button type="button" class="analytics-dp-preset-btn {{ ($analytics['preset'] ?? '') === 'this_week' ? 'active' : '' }}" data-preset="this_week" onclick="AnalyticsPage.datepicker.selectPreset('this_week')">This week</button>
                            <button type="button" class="analytics-dp-preset-btn {{ in_array(($analytics['preset'] ?? ''), ['last_week', '7days']) ? 'active' : '' }}" data-preset="last_week" onclick="AnalyticsPage.datepicker.selectPreset('last_week')">Last week</button>
                            <button type="button" class="analytics-dp-preset-btn {{ ($analytics['preset'] ?? '') === 'month' ? 'active' : '' }}" data-preset="month" onclick="AnalyticsPage.datepicker.selectPreset('month')">This month</button>
                            <button type="button" class="analytics-dp-preset-btn {{ ($analytics['preset'] ?? '') === 'last_month' ? 'active' : '' }}" data-preset="last_month" onclick="AnalyticsPage.datepicker.selectPreset('last_month')">Last month</button>
                            <button type="button" class="analytics-dp-preset-btn {{ ($analytics['preset'] ?? '') === 'this_year' ? 'active' : '' }}" data-preset="this_year" onclick="AnalyticsPage.datepicker.selectPreset('this_year')">This year</button>
                            <button type="button" class="analytics-dp-preset-btn {{ ($analytics['preset'] ?? '') === 'last_year' ? 'active' : '' }}" data-preset="last_year" onclick="AnalyticsPage.datepicker.selectPreset('last_year')">Last year</button>
                            <button type="button" class="analytics-dp-preset-btn {{ ($analytics['preset'] ?? '') === 'all_time' ? 'active' : '' }}" data-preset="all_time" onclick="AnalyticsPage.datepicker.selectPreset('all_time')">All time</button>
                        </div>

                        <!-- Main Calendar Area -->
                        <div class="analytics-dp-main">
                            <!-- Mobile Header: Inputs & Quick Links (Right side of screenshot) -->
                            <div class="analytics-dp-mobile-top">
                                <div class="analytics-dp-inputs">
                                    <div class="analytics-dp-input-box">
                                        <span class="dp-val-start">{{ \Carbon\Carbon::parse($analytics['start_date'])->format('m/d/Y') }}</span>
                                    </div>
                                    <span class="analytics-dp-separator">–</span>
                                    <div class="analytics-dp-input-box">
                                        <span class="dp-val-end">{{ \Carbon\Carbon::parse($analytics['end_date'])->format('m/d/Y') }}</span>
                                    </div>
                                </div>
                                <div class="analytics-dp-mobile-presets">
                                    <button type="button" class="analytics-dp-quick-link" onclick="AnalyticsPage.datepicker.selectPreset('last_week')">Last week</button>
                                    <button type="button" class="analytics-dp-quick-link" onclick="AnalyticsPage.datepicker.selectPreset('last_month')">Last month</button>
                                    <button type="button" class="analytics-dp-quick-link" onclick="AnalyticsPage.datepicker.selectPreset('last_year')">Last year</button>
                                </div>
                            </div>

                            <!-- Calendars Grid -->
                            <div class="analytics-dp-calendars">
                                <!-- Month 1 (Left) -->
                                <div class="analytics-dp-calendar" id="dpMonthLeft">
                                    <div class="analytics-dp-cal-header">
                                        <button type="button" class="analytics-dp-nav-btn" onclick="AnalyticsPage.datepicker.prevMonth()" title="Bulan Sebelumnya">
                                            <i class="fas fa-chevron-left"></i>
                                        </button>
                                        <span class="analytics-dp-month-title" id="dpMonthTitleLeft"></span>
                                        <button type="button" class="analytics-dp-nav-btn md:hidden" onclick="AnalyticsPage.datepicker.nextMonth()" title="Bulan Berikutnya">
                                            <i class="fas fa-chevron-right"></i>
                                        </button>
                                    </div>
                                    <div class="analytics-dp-weekdays">
                                        <span>Mo</span><span>Tu</span><span>We</span><span>Th</span><span>Fr</span><span>Sa</span><span>Su</span>
                                    </div>
                                    <div class="analytics-dp-days-grid" id="dpDaysGridLeft"></div>
                                </div>

                                <!-- Month 2 (Right - Desktop) -->
                                <div class="analytics-dp-calendar analytics-dp-cal-right" id="dpMonthRight">
                                    <div class="analytics-dp-cal-header">
                                        <div class="w-7"></div>
                                        <span class="analytics-dp-month-title" id="dpMonthTitleRight"></span>
                                        <button type="button" class="analytics-dp-nav-btn" onclick="AnalyticsPage.datepicker.nextMonth()" title="Bulan Berikutnya">
                                            <i class="fas fa-chevron-right"></i>
                                        </button>
                                    </div>
                                    <div class="analytics-dp-weekdays">
                                        <span>Mo</span><span>Tu</span><span>We</span><span>Th</span><span>Fr</span><span>Sa</span><span>Su</span>
                                    </div>
                                    <div class="analytics-dp-days-grid" id="dpDaysGridRight"></div>
                                </div>
                            </div>

                            <!-- Bottom Action Footer (Desktop & Mobile) -->
                            <div class="analytics-dp-footer">
                                <div class="analytics-dp-inputs analytics-dp-desktop-inputs">
                                    <div class="analytics-dp-input-box">
                                        <span class="dp-val-start" id="dpInputDisplayStart">{{ \Carbon\Carbon::parse($analytics['start_date'])->format('m/d/Y') }}</span>
                                    </div>
                                    <span class="analytics-dp-separator">–</span>
                                    <div class="analytics-dp-input-box">
                                        <span class="dp-val-end" id="dpInputDisplayEnd">{{ \Carbon\Carbon::parse($analytics['end_date'])->format('m/d/Y') }}</span>
                                    </div>
                                </div>

                                <div class="analytics-dp-actions">
                                    <button type="button" class="analytics-dp-btn-cancel" onclick="AnalyticsPage.datepicker.close()">Cancel</button>
                                    <button type="button" class="analytics-dp-btn-apply" onclick="AnalyticsPage.datepicker.apply()">Apply</button>
                                </div>

                                <input type="hidden" id="inputCustomStart" value="{{ $analytics['start_date'] }}">
                                <input type="hidden" id="inputCustomEnd" value="{{ $analytics['end_date'] }}">
                                <input type="hidden" id="heroPresetSelect" value="{{ $analytics['preset'] ?? '30days' }}">
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- 3-COLUMNS HERO GRID -->
        <div class="analytics-hero-grid">
            
            <!-- COLUMN A: Semi-circular Gauge (Conversion Health) -->
            <div class="analytics-gauge-col">
                <div class="analytics-widget-subhead">
                    <span>Store Conversion Health</span>
                    <i class="fas fa-info-circle text-gray-300 dark:text-gray-600 text-xs" title="Persentase pengunjung profil Linkan Anda yang bertransaksi"></i>
                </div>

                <div class="analytics-gauge-chart-box">
                    <div id="gaugeConversionHealth" class="chart-inner-fill"></div>
                </div>

                <div class="analytics-gauge-legends">
                    <div class="analytics-gauge-legend-item">
                        <span><span class="analytics-legend-dot dot-brand"></span> Conversion Rate</span>
                        <strong class="text-gray-900 dark:text-white" id="gaugeValCurrent">{{ $analytics['scorecards']['conversion_rate']['formatted'] }}</strong>
                    </div>
                    <div class="analytics-gauge-legend-item">
                        <span><span class="analytics-legend-dot dot-emerald"></span> Benchmark Industri</span>
                        <span class="text-gray-500">2.5%</span>
                    </div>
                </div>
            </div>

            <!-- COLUMN B: 2x2 Key Metrics with Mini Sparklines -->
            <div class="analytics-metrics-col">
                <div class="analytics-widget-subhead">
                    <span>Performa Kunci (KPIs)</span>
                    <i class="fas fa-chart-line text-gray-300 dark:text-gray-600 text-xs"></i>
                </div>

                <div class="analytics-kpis-2x2">
                    
                    <!-- Metric 1: Total Views -->
                    <div class="analytics-kpi-item">
                        <div class="analytics-kpi-header">
                            <span class="analytics-kpi-label">Total Views</span>
                        </div>
                        <div class="analytics-kpi-number-row">
                            <span class="analytics-kpi-val" id="heroValViews">{{ $analytics['scorecards']['views']['formatted'] }}</span>
                            <span class="analytics-kpi-delta {{ $analytics['scorecards']['views']['trend_class'] }}" id="heroDeltaViews">
                                {{ $analytics['scorecards']['views']['signed_change'] }}
                            </span>
                        </div>
                        <div class="analytics-sparkline-box" id="sparklineViews"></div>
                    </div>

                    <!-- Metric 2: Total Clicks -->
                    <div class="analytics-kpi-item">
                        <div class="analytics-kpi-header">
                            <span class="analytics-kpi-label">Total Clicks</span>
                        </div>
                        <div class="analytics-kpi-number-row">
                            <span class="analytics-kpi-val" id="heroValClicks">{{ $analytics['scorecards']['clicks']['formatted'] }}</span>
                            <span class="analytics-kpi-delta {{ $analytics['scorecards']['clicks']['trend_class'] }}" id="heroDeltaClicks">
                                {{ $analytics['scorecards']['clicks']['signed_change'] }}
                            </span>
                        </div>
                        <div class="analytics-sparkline-box" id="sparklineClicks"></div>
                    </div>

                    <!-- Metric 3: Total Omset (Sales) -->
                    <div class="analytics-kpi-item">
                        <div class="analytics-kpi-header">
                            <span class="analytics-kpi-label">Total Omset</span>
                        </div>
                        <div class="analytics-kpi-number-row">
                            <span class="analytics-kpi-val text-currency-hero" id="heroValSales">{{ $analytics['scorecards']['sales']['formatted'] }}</span>
                            <span class="analytics-kpi-delta {{ $analytics['scorecards']['sales']['trend_class'] }}" id="heroDeltaSales">
                                {{ $analytics['scorecards']['sales']['signed_change'] }}
                            </span>
                        </div>
                        <div class="analytics-sparkline-box" id="sparklineSales"></div>
                    </div>

                    <!-- Metric 4: Total Transaksi -->
                    <div class="analytics-kpi-item">
                        <div class="analytics-kpi-header">
                            <span class="analytics-kpi-label">Total Transaksi</span>
                        </div>
                        <div class="analytics-kpi-number-row">
                            <span class="analytics-kpi-val" id="heroValTrans">{{ $analytics['scorecards']['transactions']['formatted'] }}</span>
                            <span class="analytics-kpi-delta {{ $analytics['scorecards']['transactions']['trend_class'] }}" id="heroDeltaTrans">
                                {{ $analytics['scorecards']['transactions']['signed_change'] }}
                            </span>
                        </div>
                        <div class="analytics-sparkline-box" id="sparklineTrans"></div>
                    </div>

                </div>
            </div>

            <!-- COLUMN C: Top Performers Mini-Table -->
            <div class="analytics-table-col">
                <div class="analytics-widget-subhead subhead-between">
                    <span>Top Tautan & Produk</span>
                    <a href="#tab-shortlinks" onclick="AnalyticsPage.ui.switchTab('shortlinks')" class="text-xs text-orange-500 hover:underline">Semua ↗</a>
                </div>

                <div class="table-col-scroll">
                    <table class="analytics-mini-table">
                        <thead>
                            <tr>
                                <th>Item / Judul</th>
                                <th>Aktivitas</th>
                                <th>Kategori</th>
                                <th>Performa</th>
                                <th class="text-right">Hasil</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($analytics['top_items'] ?? [] as $item)
                                <tr>
                                    <td>
                                        <span class="font-bold text-gray-900 dark:text-white truncate block max-w-[130px]" title="{{ $item['title'] }}">
                                            {{ $item['title'] }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-gray-600 dark:text-gray-300 font-semibold">{{ $item['count'] }}</span>
                                    </td>
                                    <td>
                                        <span class="analytics-pill-tag analytics-pill-{{ $item['type_color'] }}">{{ $item['type'] }}</span>
                                    </td>
                                    <td>
                                        <div class="analytics-mini-progress">
                                            <div class="analytics-mini-progress-bar {{ $item['type_color'] === 'teal' ? 'bg-cyan-500' : 'bg-orange-500' }}" style="width: {{ $item['share'] }}%;"></div>
                                        </div>
                                    </td>
                                    <td class="text-right">
                                        <span class="font-bold text-gray-900 dark:text-white text-xs">{{ $item['result'] }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-gray-400 text-xs">
                                        Belum ada aktivitas performa pada rentang tanggal ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- -------------------------------------------------------------
         ROW 2: TWO LARGE CARDS (USER OVERVIEW & SESSIONS & ENGAGEMENT)
    ------------------------------------------------------------- -->
    <div class="analytics-two-cols">
        
        <!-- LEFT CARD: User Overview & Kunjungan -->
        <div class="analytics-card">
            <div class="analytics-card-header">
                <h3 class="analytics-card-title">
                    <i class="far fa-user text-cyan-500"></i>
                    <span>User Overview & Kunjungan</span>
                </h3>
                <div class="sa-chart-legend">
                    <span class="sa-legend-item"><span class="analytics-legend-dot dot-brand"></span> Views</span>
                    <span class="sa-legend-item"><span class="analytics-legend-dot dot-teal"></span> Clicks</span>
                </div>
            </div>

            <!-- Sub-metrics Chips -->
            <div class="analytics-submetrics-chips">
                <div class="analytics-chip-item">
                    <div class="analytics-chip-circle circle-teal">
                        <i class="fas fa-shopping-cart text-xs"></i>
                    </div>
                    <div class="analytics-chip-info">
                        <span class="analytics-chip-title">AOV <i class="fas fa-info-circle text-[10px] text-gray-400"></i></span>
                        <span class="analytics-chip-number">
                            <span id="chipAovVal">{{ $analytics['scorecards']['aov']['formatted'] }}</span>
                            <span class="text-[11px] font-bold {{ $analytics['scorecards']['aov']['trend'] === 'up' ? 'text-emerald-500' : 'text-rose-500' }}">
                                {{ $analytics['scorecards']['aov']['signed_change'] }}
                            </span>
                        </span>
                    </div>
                </div>

                <div class="analytics-chip-item">
                    <div class="analytics-chip-circle circle-emerald">
                        <i class="fas fa-wallet text-xs"></i>
                    </div>
                    <div class="analytics-chip-info">
                        <span class="analytics-chip-title">Saldo Siap Ditarik</span>
                        <span class="analytics-chip-number">
                            <span id="chipBalanceVal">{{ $analytics['scorecards']['balance']['formatted'] }}</span>
                        </span>
                    </div>
                </div>

                <div class="analytics-chip-item">
                    <div class="analytics-chip-circle circle-purple">
                        <i class="fas fa-cube text-xs"></i>
                    </div>
                    <div class="analytics-chip-info">
                        <span class="analytics-chip-title">Produk Aktif</span>
                        <span class="analytics-chip-number">
                            <span id="chipProdVal">{{ $analytics['scorecards']['products']['active'] }}</span>
                            <span class="text-xs font-normal text-gray-400">/ {{ $analytics['scorecards']['products']['total'] }} total</span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Main Traffic Spline Chart -->
            <div class="sa-chart-wrap chart-wrap-lg">
                <div id="chartOverviewTraffic" class="chart-inner-fill"></div>
            </div>
        </div>

        <!-- RIGHT CARD: Sessions & Engagement (Omset) -->
        <div class="analytics-card">
            <div class="analytics-card-header">
                <h3 class="analytics-card-title">
                    <i class="fas fa-chart-area text-emerald-500"></i>
                    <span>Sessions & Engagement (Omset)</span>
                </h3>
                <a href="{{ route('admin.orders') }}" class="analytics-card-link">
                    Lihat Semua Pesanan <i class="fas fa-arrow-up-right-from-square text-[10px]"></i>
                </a>
            </div>

            <!-- Stats Row -->
            <div class="analytics-stats-row">
                <div class="analytics-stat-cell">
                    <span class="analytics-stat-label">Total Omset</span>
                    <span class="analytics-stat-val text-orange-600 dark:text-orange-400 text-currency-sm" id="statsSalesVal">
                        {{ $analytics['scorecards']['sales']['formatted'] }}
                    </span>
                </div>
                <div class="analytics-stat-cell">
                    <span class="analytics-stat-label">Sukses</span>
                    <span class="analytics-stat-val text-emerald-600 dark:text-emerald-400">
                        {{ $analytics['transaction_statuses']['success'] }}
                    </span>
                </div>
                <div class="analytics-stat-cell">
                    <span class="analytics-stat-label">Pending</span>
                    <span class="analytics-stat-val text-amber-500">
                        {{ $analytics['transaction_statuses']['pending'] }}
                    </span>
                </div>
                <div class="analytics-stat-cell">
                    <span class="analytics-stat-label">Gagal / Batal</span>
                    <span class="analytics-stat-val text-rose-500">
                        {{ $analytics['transaction_statuses']['failed'] }}
                    </span>
                </div>
                <div class="analytics-stat-cell">
                    <span class="analytics-stat-label">Konversi</span>
                    <span class="analytics-stat-val" id="statsConvVal">
                        {{ $analytics['scorecards']['conversion_rate']['formatted'] }}
                    </span>
                </div>
            </div>

            <!-- Main Revenue Area Spline Chart -->
            <div class="sa-chart-wrap chart-wrap-lg">
                <div id="chartOverviewRevenue" class="chart-inner-fill"></div>
            </div>
        </div>

    </div>

    <!-- -------------------------------------------------------------
         ROW 3: TOP 10 PAGE TITLES & PRODUK TERPOPULER
    ------------------------------------------------------------- -->
    <x-analytics.chart-card 
        title="Top 10 Page Titles & Produk" 
        icon="fas fa-file-lines" 
        iconColor="text-orange-500" 
        :cardLink="route('admin.digital-products.index')" 
        linkText="Kelola Produk">

        <div class="analytics-pages-list">
            @forelse($analytics['ranked_pages'] ?? [] as $index => $rp)
                <div class="analytics-page-item">
                    <span class="analytics-page-badge {{ $index === 0 ? 'badge-top1' : ($index === 1 ? 'badge-top2' : '') }}">
                        {{ $rp['badge'] }}
                    </span>
                    <div class="analytics-page-info">
                        <a href="{{ $rp['url'] }}" class="analytics-page-title hover:text-orange-500 transition-colors block">
                            {{ $rp['title'] }}
                        </a>
                        <div class="analytics-page-sub">
                            <span>{{ $rp['sub'] }}</span>
                        </div>
                    </div>
                    <a href="{{ $rp['url'] }}" class="text-gray-400 hover:text-orange-500 p-1 text-xs">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
            @empty
                <div class="text-center py-6 text-gray-400 text-xs">
                    Belum ada halaman atau produk aktif.
                </div>
            @endforelse
        </div>

    </x-analytics.chart-card>

</div>
