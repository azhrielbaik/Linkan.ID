@extends("admin_seller.layouts.app")

@section("page_title", __('admin.analytics_shortlink'))

@push("styles")
<link rel="stylesheet" href="{{ asset('css/pages/shortlink-analytics.css') }}?v={{ filemtime(public_path('css/pages/shortlink-analytics.css')) }}" data-turbo-track="reload">
@endpush

@section("content")
<!-- ROW 1: Main KPI Cards & Date Filter -->
<div class="analytics-header-card" style="padding: 24px; margin-bottom: 24px; display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px; background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
    <div>
        <a href="{{ route('admin.shortlinks.index') }}" style="display: inline-flex; align-items: center; gap: 6px; color: #64748b; font-size: 13px; font-weight: 600; text-decoration: none; margin-bottom: 12px; transition: color 0.2s;" onmouseover="this.style.color='#5A5BF1'" onmouseout="this.style.color='#64748b'">
            <i class="fas fa-arrow-left"></i> {{ __('admin.back') ?? 'Kembali' }}
        </a>
        <h1 style="font-size: 24px; font-weight: 700; color: #1e293b; margin-top: 0;">{{ __('admin.analytics_shortlink') }}</h1>
        <p style="color: #5A5BF1; margin-top: 4px; font-weight: 600; font-size: 15px;">{{ $shortlink->slug }}</p>
        
        <div style="display: flex; gap: 16px; margin-top: 20px; flex-wrap: wrap;">
            <!-- Scorecard: Total Clicks -->
            <div style="background: #f8fafc; padding: 16px 24px; border-radius: 8px; border: 1px solid #e2e8f0; min-width: 140px;">
                <div style="font-size: 13px; color: #64748b; font-weight: 600; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">{{ __('admin.total_clicks') }}</div>
                <div style="font-size: 32px; font-weight: 800; color: #0f172a;" id="totalClicksValue">{{ $totalClicks }}</div>
            </div>
            
            <!-- Scorecard: Unique Clicks -->
            <div style="background: #f8fafc; padding: 16px 24px; border-radius: 8px; border: 1px solid #e2e8f0; min-width: 140px;">
                <div style="font-size: 13px; color: #64748b; font-weight: 600; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Unique Clicks</div>
                <div style="font-size: 32px; font-weight: 800; color: #0f172a;" id="uniqueClicksValue">{{ $uniqueClicks }}</div>
            </div>
        </div>

        <div style="margin-top: 16px; color: #555; font-size: 14px;" id="topSourcesContainer">
            <strong class="sa-sources-bar-label">{{ __('admin.top_sources') }} (Click to filter):</strong>
            <div class="sa-sources-bar">
                @foreach($sources as $source)
                    <div class="sa-source-tag" onclick="setSourceFilter('{{ addslashes($source) }}')">
                        <span class="sa-source-dot"></span>
                        <span>{{ $source }}</span>
                        <span class="sa-source-tag-count">...</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    
    <!-- Date Filter -->
    <div class="date-range-selector" style="background: #f8fafc; padding: 16px; border-radius: 8px; border: 1px solid #e2e8f0; display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
        <input type="date" id="startDate" class="date-input" value="{{ $startDate }}" style="border: 1px solid #cbd5e1; padding: 8px 12px; border-radius: 6px; font-size: 14px; outline: none;">
        <span style="color: #64748b; font-weight: 500;">{{ __('admin.to') }}</span>
        <input type="date" id="endDate" class="date-input" value="{{ $endDate }}" style="border: 1px solid #cbd5e1; padding: 8px 12px; border-radius: 6px; font-size: 14px; outline: none;">
        <button class="apply-date" onclick="applyDateFilter()" style="background: #5A5BF1; color: #fff; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 14px; transition: background 0.2s;">{{ __('admin.apply') }}</button>
    </div>
</div>

<div class="sa-page" style="margin-top: 24px; display: flex; flex-direction: column; gap: 24px;">
    <!-- ROW 2: Main Trend Chart (Full width) -->
    <div class="sa-card">
        <div class="sa-card-header">
            <div>
                <h3 class="sa-card-title">{{ __('admin.daily_click_chart') }}</h3>
                <p class="sa-card-subtitle">{{ __('admin.destination') }}: {{ $shortlink->destination }}</p>
            </div>
        </div>
        <div class="sa-chart-wrap">
            <div id="shortlinkAnalyticsChart" style="height: 350px;"></div>
        </div>
    </div>

    <!-- ROW 3: Category Breakdown (Grid 3 columns) -->
    <div class="sa-grid-3">
        <!-- Device Breakdown -->
        <div class="sa-card" style="padding: 20px;">
            <div class="sa-card-header" style="margin-bottom: 12px;">
                <div>
                    <h3 class="sa-card-title" style="font-size: 14px;">{{ __('admin.device_breakdown') }}</h3>
                    <p class="sa-card-subtitle" style="font-size: 11.5px;">{{ __('admin.device_breakdown_desc') }}</p>
                </div>
            </div>
            <div class="sa-chart-wrap" style="position: relative; height: 260px;">
                <div id="shortlinkDeviceChart" style="height: 100%;"></div>
                <div id="deviceNoData" class="sa-no-data">
                    <div class="sa-no-data-icon"><i class="fas fa-chart-pie"></i></div>
                    {{ __('admin.no_click_data') }}
                </div>
            </div>
        </div>

        <!-- Source Traffic -->
        <div class="sa-card" style="padding: 20px;">
            <div class="sa-card-header" style="margin-bottom: 12px;">
                <div>
                    <h3 class="sa-card-title" style="font-size: 14px;">{{ __('admin.source_traffic_chart') }}</h3>
                    <p class="sa-card-subtitle" style="font-size: 11.5px;">{{ __('admin.source_traffic_desc') }}</p>
                </div>
            </div>
            <div class="sa-chart-wrap" style="position: relative; height: 260px;">
                <div id="shortlinkSourceChart" style="height: 100%;"></div>
                <div id="sourceNoData" class="sa-no-data">
                    <div class="sa-no-data-icon"><i class="fas fa-chart-pie"></i></div>
                    {{ __('admin.no_click_data') }}
                </div>
            </div>
        </div>

        <!-- Geolocation -->
        <div class="sa-card" style="padding: 20px;">
            <div class="sa-card-header" style="margin-bottom: 12px;">
                <div>
                    <h3 class="sa-card-title" style="font-size: 14px;">Geolocation (Country)</h3>
                    <p class="sa-card-subtitle" style="font-size: 11.5px;">Where your traffic is coming from</p>
                </div>
            </div>
            <div class="sa-chart-wrap" style="position: relative; height: 260px;">
                <div id="shortlinkGeoChart" style="height: 100%;"></div>
                <div id="geoNoData" class="sa-no-data">
                    <div class="sa-no-data-icon"><i class="fas fa-map-marker-alt"></i></div>
                    {{ __('admin.no_click_data') }}
                </div>
            </div>
        </div>
    </div>

    <!-- ROW 4: Deep Analysis (Grid 2 columns, asymmetrical) -->
    <div class="sa-grid-2-asym">
        <!-- Bottom Data Table -->
        <div class="sa-card">
            <div class="sa-card-header">
                <div>
                    <h3 class="sa-card-title">{{ __('admin.ip_breakdown') }}</h3>
                    <p class="sa-card-subtitle">Top IP addresses accessing this link</p>
                </div>
            </div>
            <div class="table-responsive" style="margin-top: 16px;">
                <table class="sa-table">
                    <thead>
                        <tr>
                            <th>IP Address</th>
                            <th>Total Clicks</th>
                        </tr>
                    </thead>
                    <tbody id="ipTableBody">
                        <tr><td colspan="2" style="text-align: center; padding: 32px; color: #94a3b8; font-style: italic;">Loading...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Time Behavior -->
        <div class="sa-card">
            <div class="sa-card-header">
                <div>
                    <h3 class="sa-card-title">Most Active Days</h3>
                    <p class="sa-card-subtitle">Which days generate the most clicks</p>
                </div>
            </div>
            <div class="sa-chart-wrap" style="position: relative; height: 320px;">
                <div id="shortlinkTimeChart" style="height: 100%;"></div>
                <div id="timeNoData" class="sa-no-data">
                    <div class="sa-no-data-icon"><i class="fas fa-calendar-alt"></i></div>
                    {{ __('admin.no_click_data') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push("scripts")
<script src="{{ asset('js/apexcharts.min.js') }}"></script>
@endpush

@push("scripts")
<script>
let chart;
let sourceChart;
let deviceChart;
let geoChart;
let timeChart;

function buildBreakdownChart(elementId, noDataId, dataArray, chartInstance, type = 'donut') {
    const noDataEl = document.getElementById(noDataId);
    if (chartInstance) {
        chartInstance.destroy();
    }

    if (!dataArray || dataArray.length === 0 || dataArray.every(d => d.total === 0)) {
        if (noDataEl) noDataEl.style.display = 'block';
        return null;
    }
    if (noDataEl) noDataEl.style.display = 'none';

    let options = {};

    if (type === 'donut') {
        options = {
            series: dataArray.map(item => item.total),
            chart: { type: 'donut', height: '100%', parentHeightOffset: 0 },
            labels: dataArray.map(item => item.label),
            // Project aligned palette (Blue, Orange, Light Blue, Light Orange, Gray)
            colors: ['#0088FF', '#FF9040', '#00C2FF', '#FFB373', '#94a3b8'],
            legend: { position: 'bottom', offsetY: 0 },
            dataLabels: { enabled: false },
            stroke: { show: true, colors: ['#fff'], width: 2 },
            plotOptions: {
                pie: {
                    donut: { size: '75%' }
                }
            }
        };
    } else if (type === 'bar') {
        options = {
            series: [{ name: 'Clicks', data: dataArray.map(item => item.total) }],
            chart: { type: 'bar', height: '100%', parentHeightOffset: 0, toolbar: { show: false } },
            plotOptions: { 
                bar: { 
                    horizontal: true, 
                    borderRadius: 4,
                    barHeight: '60%'
                } 
            },
            xaxis: { 
                categories: dataArray.map(item => item.label),
                labels: { style: { colors: '#64748b' } },
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                labels: { style: { colors: '#475569', fontWeight: 500 } }
            },
            grid: { show: false },
            colors: ['#0088FF'], // Changed to brand Blue
            dataLabels: { enabled: false }
        };
    }

    const container = document.querySelector("#" + elementId);
    container.innerHTML = '';
    const newChart = new ApexCharts(container, options);
    newChart.render();
    return newChart;
}

let activeSourceFilter = null;

function setSourceFilter(source) {
    activeSourceFilter = source;
    updateChart();
}

function updateChart() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    const params = new URLSearchParams({ start_date: startDate, end_date: endDate });
    if (activeSourceFilter) {
        params.append('source', activeSourceFilter);
    }

    fetch(`{{ route('admin.shortlinks.analytics.chart', $shortlink) }}?${params.toString()}`)
        .then((response) => response.json())
        .then((data) => {
            const totalEl = document.getElementById('totalClicksValue');
            if (totalEl) totalEl.textContent = data.total_clicks;
            
            const uniqueEl = document.getElementById('uniqueClicksValue');
            if (uniqueEl) uniqueEl.textContent = data.unique_total;
            
            document.getElementById('startDate').value = data.start_date;
            document.getElementById('endDate').value = data.end_date;

            // Sources summary text
            const topSourcesContainer = document.getElementById('topSourcesContainer');
            if (topSourcesContainer) {
                let html = `<strong class="sa-sources-bar-label">{{ __('admin.top_sources') }} (Click to filter):</strong>`;
                html += `<div class="sa-sources-bar">`;
                
                if (data.sources && data.sources.length > 0) {
                    data.sources.slice(0, 5).forEach(s => {
                        const isActive = (activeSourceFilter === s.label) ? 'active' : '';
                        html += `
                        <div class="sa-source-tag ${isActive}" onclick="setSourceFilter('${s.label.replace(/'/g, "\\'")}')">
                            <span class="sa-source-dot"></span>
                            <span>${s.label}</span>
                            <span class="sa-source-tag-count">${s.total}</span>
                        </div>`;
                    });
                    
                    if (activeSourceFilter) {
                        html += `
                        <div class="sa-source-tag" onclick="setSourceFilter(null)" style="background: transparent; border-style: dashed; color: #64748b;">
                            <i class="fas fa-times"></i> Clear Filter
                        </div>`;
                    }
                } else {
                    html += `<div style="color: #94a3b8; font-style: italic; font-size: 13px;">No source data</div>`;
                }
                
                html += `</div>`;
                topSourcesContainer.innerHTML = html;
            }

            // IP Table
            const ipTableBody = document.getElementById('ipTableBody');
            if (ipTableBody) {
                if (data.ip_breakdown && data.ip_breakdown.length > 0) {
                    let rows = '';
                    data.ip_breakdown.slice(0, 10).forEach(ip => {
                        rows += `<tr style="transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                            <td style="padding: 14px 16px; border-bottom: 1px solid #f1f5f9; color: #334155;">${ip.label}</td>
                            <td style="padding: 14px 16px; text-align: right; border-bottom: 1px solid #f1f5f9; font-weight: 700; color: #0f172a;">${ip.total}</td>
                        </tr>`;
                    });
                    ipTableBody.innerHTML = rows;
                } else {
                    ipTableBody.innerHTML = `<tr><td colspan="2" style="text-align: center; padding: 32px; color: #94a3b8; font-style: italic;">{{ __('admin.no_click_data') }}</td></tr>`;
                }
            }

            if (chart) chart.destroy();
            
            // Area chart for Total vs Unique Clicks
            const chartOptions = {
                series: [
                    { name: 'Total Clicks', data: data.clicks },
                    { name: 'Unique Clicks', data: data.unique_clicks }
                ],
                chart: {
                    type: 'area',
                    height: '100%',
                    toolbar: { show: false },
                    parentHeightOffset: 0
                },
                stroke: { curve: 'smooth', width: 3 },
                fill: { 
                    type: 'gradient', 
                    gradient: { shadeIntensity: 1, opacityFrom: 0.35, opacityTo: 0.05, stops: [0, 90, 100] } 
                },
                colors: ['#5A5BF1', '#34c759'],
                dataLabels: { enabled: false },
                xaxis: {
                    categories: data.labels,
                    labels: { style: { colors: '#94a3b8', fontWeight: 500 } },
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    crosshairs: { stroke: { color: '#cbd5e1' } }
                },
                yaxis: {
                    labels: { 
                        formatter: (val) => Math.floor(val),
                        style: { colors: '#64748b', fontWeight: 500 }
                    }
                },
                grid: {
                    borderColor: '#f1f5f9',
                    strokeDashArray: 4,
                    xaxis: { lines: { show: true } },
                    yaxis: { lines: { show: true } },
                },
                legend: { position: 'top', horizontalAlign: 'right' }
            };
            const chartContainer = document.querySelector("#shortlinkAnalyticsChart");
            chartContainer.innerHTML = '';
            chart = new ApexCharts(chartContainer, chartOptions);
            chart.render();

            sourceChart = buildBreakdownChart('shortlinkSourceChart', 'sourceNoData', data.sources, sourceChart, 'donut');
            deviceChart = buildBreakdownChart('shortlinkDeviceChart', 'deviceNoData', data.device_breakdown, deviceChart, 'donut');
            geoChart = buildBreakdownChart('shortlinkGeoChart', 'geoNoData', data.geo_breakdown, geoChart, 'bar');
            timeChart = buildBreakdownChart('shortlinkTimeChart', 'timeNoData', data.time_behavior, timeChart, 'bar');
        });
}

function applyDateFilter() {
    updateChart();
}

document.addEventListener('DOMContentLoaded', updateChart);
document.addEventListener('turbo:load', updateChart);
</script>
@endpush
