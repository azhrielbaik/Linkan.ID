@extends("admin_seller.layouts.app")

@section("page_title", __('admin.analytics_shortlink'))

@push("styles")
<link rel="stylesheet" href="{{ asset('css/pages/shortlink-analytics.css') }}?v={{ filemtime(public_path('css/pages/shortlink-analytics.css')) }}" data-turbo-track="reload">
@endpush

@section("content")
<div class="sa-analytics-wrapper">
    <div class="sa-skymetrics-header">
        <div class="sa-header-left">
            <h1 class="sa-page-title">Overview</h1>
            <p class="sa-page-subtitle">
                <span class="sa-slug-badge">/{{ $shortlink->slug }}</span>
                <span class="sa-dest-url" title="{{ $shortlink->destination }}">{{ $shortlink->destination }}</span>
            </p>
        </div>
        
        <div class="sa-header-right">
            <div class="sa-date-filter">
                <div class="sa-date-input-group">
                    <i class="far fa-calendar-alt sa-date-icon"></i>
                    <input type="date" id="startDate" value="{{ $startDate }}" class="sa-date-input">
                    <span class="sa-date-sep">-</span>
                    <input type="date" id="endDate" value="{{ $endDate }}" class="sa-date-input">
                </div>
                <button onclick="applyDateFilter()" class="sa-btn-apply">{{ __('admin.apply') }}</button>
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
        <div class="sa-sc-value" id="totalClicksValue">{{ $totalClicks }}</div>
        <div class="sa-sc-footer">vs previous period</div>
    </div>
    <div class="sa-scorecard">
        <div class="sa-sc-header">
            <span class="sa-sc-title">Unique Clicks</span>
        </div>
        <div class="sa-sc-value" id="uniqueClicksValue">{{ $uniqueClicks }}</div>
        <div class="sa-sc-footer">vs previous period</div>
    </div>
    <div class="sa-scorecard">
        <div class="sa-sc-header">
            <span class="sa-sc-title">Top Source</span>
        </div>
        <div class="sa-sc-value" id="topSourceValue">-</div>
        <div class="sa-sc-footer">highest converting channel</div>
    </div>
    <div class="sa-scorecard">
        <div class="sa-sc-header">
            <span class="sa-sc-title">Top Device</span>
        </div>
        <div class="sa-sc-value" id="topDeviceValue">-</div>
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
                <span class="sa-legend-item"><span class="sa-dot-gray" style="background: #f59e0b;"></span> Unique Clicks</span>
            </div>
        </div>
        <div class="sa-chart-wrap" style="height: 280px; position: relative;">
            <div id="shortlinkAnalyticsChart" style="height: 100%;"></div>
        </div>
    </div>
    
    <div class="sa-chart-side">
        <div class="sa-card-header-clean">
            <h3 class="sa-card-title-clean">Traffic by source</h3>
        </div>
        <div class="sa-chart-wrap" style="height: 250px; position: relative;">
            <div id="shortlinkSourceChart" style="height: 100%;"></div>
            <div id="sourceNoData" class="sa-no-data">
                <i class="fas fa-chart-pie"></i> {{ __('admin.no_click_data') }}
            </div>
        </div>
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
                    <tr><td colspan="2" class="sa-table-loading">Loading...</td></tr>
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
            <div id="deviceNoData" class="sa-table-loading">Loading...</div>
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
        const totalSum = dataArray.reduce((acc, val) => acc + val.total, 0);
        options = {
            series: dataArray.map(item => item.total),
            chart: { 
                type: 'donut', 
                height: '100%', 
                width: '100%',
                parentHeightOffset: 0,
                events: {
                    dataPointSelection: function(event, chartContext, config) {
                        if (elementId === 'shortlinkSourceChart') {
                            const selectedLabel = dataArray[config.dataPointIndex].label;
                            if (activeSourceFilter === selectedLabel) {
                                setSourceFilter(null); // toggle off
                            } else {
                                setSourceFilter(selectedLabel);
                            }
                        }
                    }
                }
            },
            responsive: [{
                breakpoint: 768,
                options: {
                    chart: { height: 220 },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '70%',
                                labels: {
                                    name: { fontSize: '11px', offsetY: 14 },
                                    value: { fontSize: '20px', offsetY: -6 },
                                    total: { fontSize: '10px' }
                                }
                            }
                        }
                    }
                }
            }],
            labels: dataArray.map(item => item.label),
            // Project palette: Orange, Amber, Teal, Slate
            colors: ['#ed842c', '#f59e0b', '#14b8a6', '#f97316', '#94a3b8'],
            legend: { show: false }, // we can use custom legend if needed, or default
            dataLabels: { enabled: false },
            stroke: { show: true, colors: ['#fff'], width: 3 },
            plotOptions: {
                pie: {
                    donut: { 
                        size: '75%',
                        labels: {
                            show: true,
                            name: {
                                show: true,
                                fontSize: '12px',
                                fontFamily: 'Inter, sans-serif',
                                fontWeight: 600,
                                color: '#6b7280',
                                offsetY: 20
                            },
                            value: {
                                show: true,
                                fontSize: '28px',
                                fontFamily: 'Inter, sans-serif',
                                fontWeight: 700,
                                color: '#111827',
                                offsetY: -10,
                                formatter: function (val) {
                                    return val;
                                }
                            },
                            total: {
                                show: true,
                                showAlways: true,
                                label: 'Total visits',
                                fontSize: '12px',
                                fontFamily: 'Inter, sans-serif',
                                fontWeight: 500,
                                color: '#6b7280',
                                formatter: function (w) {
                                    return totalSum;
                                }
                            }
                        }
                    }
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
                labels: { style: { colors: '#6b7280', fontFamily: 'Inter, sans-serif' } },
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                labels: { style: { colors: '#111827', fontWeight: 500, fontFamily: 'Inter, sans-serif' } }
            },
            grid: { show: false },
            colors: ['#ed842c'],
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

            // Populate Top Source & Top Device cards
            const topSourceEl = document.getElementById('topSourceValue');
            if (topSourceEl && data.sources && data.sources.length > 0) {
                topSourceEl.textContent = data.sources[0].label;
            } else if (topSourceEl) {
                topSourceEl.textContent = '-';
            }

            const topDeviceEl = document.getElementById('topDeviceValue');
            if (topDeviceEl && data.device_breakdown && data.device_breakdown.length > 0) {
                topDeviceEl.textContent = data.device_breakdown[0].label;
            } else if (topDeviceEl) {
                topDeviceEl.textContent = '-';
            }

            // Populate Device Breakdown list
            const deviceListWrap = document.getElementById('deviceListWrap');
            if (deviceListWrap) {
                if (data.device_breakdown && data.device_breakdown.length > 0) {
                    let html = '';
                    data.device_breakdown.slice(0, 5).forEach(d => {
                        let icon = 'fa-desktop';
                        if (d.label.toLowerCase().includes('mobile')) icon = 'fa-mobile-alt';
                        if (d.label.toLowerCase().includes('tablet')) icon = 'fa-tablet-alt';
                        
                        html += `
                        <div class="sa-list-item">
                            <div class="sa-li-icon"><i class="fas ${icon}"></i></div>
                            <div class="sa-li-content">
                                <div class="sa-li-title">${d.label}</div>
                                <div class="sa-li-subtitle">Sessions by device</div>
                            </div>
                            <div class="sa-li-val">${d.total}</div>
                        </div>`;
                    });
                    deviceListWrap.innerHTML = html;
                } else {
                    deviceListWrap.innerHTML = `<div class="sa-table-loading">No data</div>`;
                }
            }

            // IP Table
            const ipTableBody = document.getElementById('ipTableBody');
            if (ipTableBody) {
                if (data.ip_breakdown && data.ip_breakdown.length > 0) {
                    let rows = '';
                    data.ip_breakdown.slice(0, 5).forEach(ip => {
                        rows += `<tr>
                            <td>
                                <div class="sa-ip-cell">
                                    <div class="sa-ip-avatar"><i class="fas fa-network-wired"></i></div>
                                    <span>${ip.label}</span>
                                </div>
                            </td>
                            <td style="text-align: right; font-weight: 600; color: #111827;">${ip.total}</td>
                        </tr>`;
                    });
                    ipTableBody.innerHTML = rows;
                } else {
                    ipTableBody.innerHTML = `<tr><td colspan="2" class="sa-table-loading">{{ __('admin.no_click_data') }}</td></tr>`;
                }
            }

            if (chart) chart.destroy();
            
            // Line chart for Total vs Unique Clicks (Skymetrics style)
            const chartOptions = {
                series: [
                    { name: 'Total Clicks', data: data.clicks },
                    { name: 'Unique Clicks', data: data.unique_clicks }
                ],
                chart: {
                    type: 'line',
                    height: '100%',
                    width: '100%',
                    toolbar: { show: false },
                    parentHeightOffset: 0,
                    dropShadow: {
                        enabled: true,
                        top: 8,
                        left: 0,
                        blur: 8,
                        color: ['#ed842c', '#f59e0b'],
                        opacity: 0.2
                    }
                },
                responsive: [{
                    breakpoint: 768,
                    options: {
                        chart: { height: 220 },
                        xaxis: {
                            tickAmount: 5,
                            labels: {
                                rotate: 0,
                                hideOverlappingLabels: true,
                                style: { fontSize: '10px' }
                            }
                        },
                        yaxis: {
                            labels: {
                                style: { fontSize: '10px' }
                            }
                        }
                    }
                }],
                stroke: { curve: 'smooth', width: [3, 2], dashArray: [0, 0] },
                colors: ['#ed842c', '#f59e0b'],
                dataLabels: { enabled: false },
                xaxis: {
                    categories: data.labels,
                    labels: { style: { colors: '#9ca3af', fontFamily: 'Inter, sans-serif', fontWeight: 500 } },
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    tooltip: { enabled: false }
                },
                yaxis: {
                    labels: { 
                        formatter: (val) => Math.floor(val),
                        style: { colors: '#9ca3af', fontFamily: 'Inter, sans-serif', fontWeight: 500 }
                    }
                },
                grid: {
                    borderColor: '#f3f4f6',
                    strokeDashArray: 0,
                    xaxis: { lines: { show: false } },
                    yaxis: { lines: { show: true } },
                    padding: { top: 0, right: 0, bottom: 0, left: 10 }
                },
                legend: { show: false }, // Legend is custom HTML now
                tooltip: {
                    theme: 'light',
                    x: { show: true, format: 'dd MMM' },
                    style: { fontSize: '13px', fontFamily: 'Inter, sans-serif' },
                    marker: { show: false }
                }
            };
            const chartContainer = document.querySelector("#shortlinkAnalyticsChart");
            chartContainer.innerHTML = '';
            chart = new ApexCharts(chartContainer, chartOptions);
            chart.render();

            sourceChart = buildBreakdownChart('shortlinkSourceChart', 'sourceNoData', data.sources, sourceChart, 'donut');
        });
}

function applyDateFilter() {
    updateChart();
}

document.addEventListener('DOMContentLoaded', updateChart);
document.addEventListener('turbo:load', updateChart);
</script>
@endpush
