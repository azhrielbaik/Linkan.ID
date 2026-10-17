@extends("admin_seller.layouts.app")

@section("page_title", __('admin.analytics_shortlink'))

@push("styles")
<link rel="stylesheet" href="{{ asset('css/pages/shortlink-analytics.css') }}?v={{ file_exists(public_path('css/pages/shortlink-analytics.css')) ? filemtime(public_path('css/pages/shortlink-analytics.css')) : '1.0' }}">
@endpush

@section("content")
<div 
    class="sa-analytics-wrapper" 
    id="shortlinkAnalyticsApp"
    data-chart-url="{{ route('admin.shortlinks.analytics.chart', $shortlink) }}"
    data-start-date="{{ $startDate }}"
    data-end-date="{{ $endDate }}"
    data-i18n-apply="{{ __('admin.apply') ?? 'Terapkan' }}"
    data-i18n-loading="{{ __('admin.loading') ?? 'Loading...' }}"
    data-i18n-no-data="{{ __('admin.no_click_data') ?? 'Tidak ada data klik' }}"
    data-i18n-total-clicks="{{ __('admin.total_clicks') ?? 'Total Clicks' }}"
    data-i18n-unique-clicks="Unique Clicks"
    data-i18n-total-visits="Total visits"
    data-i18n-sessions-by-device="Sessions by device"
>
    {{-- Initial chart data payload for zero-latency SSR hydration --}}
    <script id="shortlink-initial-data" type="application/json">@json($initialChartData ?? null)</script>

    <div class="sa-skymetrics-header">
        <div class="sa-header-left">
            <div class="sa-title-row">
                <h1 class="sa-page-title">Overview</h1>
                <span class="sa-slug-badge">/{{ $shortlink->slug }}</span>
            </div>
            <p class="sa-page-subtitle">
                <i class="fas fa-link sa-dest-icon"></i>
                <a href="{{ $shortlink->destination }}" target="_blank" rel="noopener noreferrer" class="sa-dest-url" title="{{ $shortlink->destination }}">
                    {{ $shortlink->destination }}
                </a>
            </p>
        </div>
        
        <div class="sa-header-right">
            <div class="sa-date-filter">
                <div class="sa-date-input-group">
                    <i class="far fa-calendar-alt sa-date-icon"></i>
                    <input type="date" id="startDate" value="{{ $startDate }}" class="sa-date-input" aria-label="Tanggal Mulai">
                    <span class="sa-date-sep">-</span>
                    <input type="date" id="endDate" value="{{ $endDate }}" class="sa-date-input" aria-label="Tanggal Akhir">
                </div>
                <button type="button" id="applyDateFilterBtn" class="sa-btn-apply">{{ __('admin.apply') }}</button>
            </div>
            <a href="{{ route('admin.shortlinks.index') }}" class="sa-btn-outline">
                <i class="fas fa-arrow-left"></i> <span>{{ __('admin.back') ?? 'Kembali' }}</span>
            </a>
        </div>
    </div>

    <!-- ROW 1: 4 Scorecards -->
    <div class="sa-scorecard-grid">
        <div class="sa-scorecard">
            <div class="sa-sc-header">
                <span class="sa-sc-title">{{ __('admin.total_clicks') }}</span>
            </div>
            <div class="sa-sc-value" id="totalClicksValue">{{ number_format($totalClicks) }}</div>
            <div class="sa-sc-footer">vs previous period</div>
        </div>
        <div class="sa-scorecard">
            <div class="sa-sc-header">
                <span class="sa-sc-title">Unique Clicks</span>
            </div>
            <div class="sa-sc-value" id="uniqueClicksValue">{{ number_format($uniqueClicks) }}</div>
            <div class="sa-sc-footer">vs previous period</div>
        </div>
        <div class="sa-scorecard">
            <div class="sa-sc-header">
                <span class="sa-sc-title">Top Source</span>
            </div>
            <div class="sa-sc-value" id="topSourceValue" title="{{ $initialChartData['sources'][0]['label'] ?? '-' }}">{{ $initialChartData['sources'][0]['label'] ?? '-' }}</div>
            <div class="sa-sc-footer">highest converting channel</div>
        </div>
        <div class="sa-scorecard">
            <div class="sa-sc-header">
                <span class="sa-sc-title">Top Device</span>
            </div>
            <div class="sa-sc-value" id="topDeviceValue" title="{{ $initialChartData['device_breakdown'][0]['label'] ?? '-' }}">{{ $initialChartData['device_breakdown'][0]['label'] ?? '-' }}</div>
            <div class="sa-sc-footer">most popular platform</div>
        </div>
    </div>

    <!-- ROW 2: Main Charts -->
    <div class="sa-charts-row">
        <div class="sa-chart-main">
            <div class="sa-card-header-clean">
                <h3 class="sa-card-title-clean">Clicks over time</h3>
                <div class="sa-chart-legend">
                    <span class="sa-legend-item"><span class="sa-dot-green"></span> Total Clicks</span>
                    <span class="sa-legend-item"><span class="sa-dot-amber"></span> Unique Clicks</span>
                </div>
            </div>
            <div class="sa-chart-wrap sa-chart-timeline-wrap">
                <div id="shortlinkAnalyticsChart" class="sa-chart-canvas"></div>
            </div>
        </div>
        
        <div class="sa-chart-side">
            <div class="sa-card-header-clean">
                <h3 class="sa-card-title-clean">{{ __('admin.source_traffic_chart') }}</h3>
            </div>
            <div class="sa-chart-wrap sa-chart-donut-wrap">
                <div id="shortlinkSourceChart" class="sa-chart-canvas"></div>
                <div id="sourceNoData" class="sa-no-data">
                    <i class="fas fa-chart-pie"></i> {{ __('admin.no_click_data') }}
                </div>
            </div>
            @if(!empty($sources))
            <div class="sa-sources-summary">
                @foreach($sources as $sourceText)
                    <span class="badge">{{ $sourceText }}</span>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    <!-- ROW 3: Deep Analysis -->
    <div class="sa-tables-row">
        <div class="sa-table-main">
            <div class="sa-card-header-clean">
                <h3 class="sa-card-title-clean">{{ __('admin.ip_breakdown') }}</h3>
                <div class="sa-card-actions">
                    <i class="fas fa-sort"></i>
                </div>
            </div>
            <div class="sa-table-responsive">
                <table class="sa-table-clean">
                    <thead>
                        <tr>
                            <th>IP Address</th>
                            <th>Total Clicks</th>
                        </tr>
                    </thead>
                    <tbody id="ipTableBody">
                        <tr><td colspan="2" class="sa-table-loading">{{ __('admin.loading') ?? 'Loading...' }}</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="sa-table-side">
            <div class="sa-card-header-clean">
                <h3 class="sa-card-title-clean">{{ __('admin.device_breakdown') }}</h3>
                <div class="sa-card-actions">
                    <i class="fas fa-sort"></i>
                </div>
            </div>
            <div class="sa-list-wrap" id="deviceListWrap">
                <div id="deviceNoData" class="sa-table-loading">{{ __('admin.loading') ?? 'Loading...' }}</div>
            </div>
        </div>
    </div>
</div>
@endsection

@push("scripts")
<script src="{{ asset('js/pages/shortlink-analytics.js') }}?v={{ file_exists(public_path('js/pages/shortlink-analytics.js')) ? filemtime(public_path('js/pages/shortlink-analytics.js')) : '1.0' }}" defer></script>
@endpush
