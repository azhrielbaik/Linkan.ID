@extends("admin_seller.layouts.app")

@section("page_title", __('admin.analytics_shortlink'))

@push("styles")
<link rel="stylesheet" href="{{ asset('css/pages/shortlink-analytics.css') }}" data-turbo-track="reload">
@endpush

@section("content")
<div class="analytics-header-card" style="padding: 24px; margin-bottom: 24px;">
    <h1>{{ __('admin.analytics_shortlink') }}</h1>
    <p style="color: #5A5BF1; margin-top: 8px; font-weight: 600;">{{ $shortlink->slug }}</p>
    <h2 style="font-size: 32px; margin-top: 12px; font-weight: 800;">{{ $totalClicks }} <span style="font-size: 16px; color: #666; font-weight: normal;">{{ __('admin.total_clicks') }}</span></h2>
    <div style="margin-top: 15px; color: #555;">
        <strong>{{ __('admin.top_sources') }}</strong>
        @foreach($sources as $source)
            <span style="display: inline-block; background: #eee; padding: 4px 8px; border-radius: 4px; margin-right: 5px; font-size: 13px;">{{ $source }}</span>
        @endforeach
    </div>
</div>
<div class="stack">
                <div class="card">
                    <div class="chart-header">
                        <div>
                            <div class="label">{{ __('admin.daily_click_chart') }}</div>
                            <div class="muted">{{ __('admin.destination') }} {{ $shortlink->destination }}</div>
                        </div>
                        <div class="date-range-selector">
                            <input type="date" id="startDate" class="date-input" value="{{ $startDate }}">
                            <span>{{ __('admin.to') }}</span>
                            <input type="date" id="endDate" class="date-input" value="{{ $endDate }}">
                            <button class="apply-date" onclick="applyDateFilter()">{{ __('admin.apply') }}</button>
                        </div>
                    </div>
                    <div class="chart-wrap">
                        <div id="shortlinkAnalyticsChart" style="height: 300px;"></div>
                    </div>
                </div>

                <div class="card">
                    <div class="chart-header">
                        <div>
                            <div class="label">{{ __('admin.source_traffic_chart') }}</div>
                            <div class="muted">{{ __('admin.source_traffic_desc') }}</div>
                        </div>
                    </div>
                    <div class="chart-wrap">
                        <div id="shortlinkSourceChart" style="height: 300px;"></div>
                    </div>
                </div>

                <div class="card">
                    <div class="chart-header">
                        <div>
                            <div class="label">{{ __('admin.ip_breakdown') }}</div>
                            <div class="muted">{{ __('admin.ip_breakdown_desc') }}</div>
                        </div>
                    </div>
                    <div class="chart-wrap" style="position: relative;">
                        <div id="shortlinkIpChart" style="height: 300px;"></div>
                        <div id="ipNoData" style="display:none; position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); color:#999; font-style:italic;">{{ __('admin.no_click_data') }}</div>
                    </div>
                </div>

                <div class="card">
                    <div class="chart-header">
                        <div>
                            <div class="label">{{ __('admin.device_breakdown') }}</div>
                            <div class="muted">{{ __('admin.device_breakdown_desc') }}</div>
                        </div>
                    </div>
                    <div class="chart-wrap" style="position: relative;">
                        <div id="shortlinkDeviceChart" style="height: 300px;"></div>
                        <div id="deviceNoData" style="display:none; position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); color:#999; font-style:italic;">{{ __('admin.no_click_data') }}</div>
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
let ipChart;
let deviceChart;

function buildBreakdownChart(elementId, noDataId, dataArray, chartInstance) {
    const noDataEl = document.getElementById(noDataId);
    if (chartInstance) {
        chartInstance.destroy();
    }

    if (!dataArray || dataArray.length === 0 || dataArray.every(d => d.total === 0)) {
        if (noDataEl) noDataEl.style.display = 'block';
        return null;
    }
    if (noDataEl) noDataEl.style.display = 'none';

    const options = {
        series: dataArray.map(item => item.total),
        chart: {
            type: 'donut',
            height: '100%',
            parentHeightOffset: 0
        },
        labels: dataArray.map(item => item.label),
        colors: ['#5A5BF1', '#4a90e2', '#34c759', '#af52de', '#ffcc00', '#ff3b30', '#8e8e93', '#5ac8fa'],
        legend: {
            position: 'bottom'
        },
        dataLabels: {
            enabled: false
        }
    };

    const container = document.querySelector("#" + elementId);
    container.innerHTML = '';
    const newChart = new ApexCharts(container, options);
    newChart.render();
    return newChart;
}

function updateChart() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    const params = new URLSearchParams({ start_date: startDate, end_date: endDate });

    fetch(`{{ route('admin.shortlinks.analytics.chart', $shortlink) }}?${params.toString()}`)
        .then((response) => response.json())
        .then((data) => {
            const totalEl = document.getElementById('totalClicksValue');
            if (totalEl) totalEl.textContent = data.total_clicks;
            
            document.getElementById('startDate').value = data.start_date;
            document.getElementById('endDate').value = data.end_date;

            if (chart) chart.destroy();
            if (sourceChart) sourceChart.destroy();

            const chartOptions = {
                series: [{
                    name: 'Clicks',
                    data: data.clicks
                }],
                chart: {
                    type: 'bar',
                    height: '100%',
                    toolbar: { show: false },
                    parentHeightOffset: 0
                },
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        columnWidth: '50%',
                    }
                },
                colors: ['#4a90e2'],
                dataLabels: { enabled: false },
                xaxis: {
                    categories: data.labels,
                    labels: { show: false },
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: {
                    labels: { formatter: (val) => Math.floor(val) }
                },
                grid: {
                    borderColor: '#f0f0f0',
                    strokeDashArray: 4,
                }
            };
            const chartContainer = document.querySelector("#shortlinkAnalyticsChart");
            chartContainer.innerHTML = '';
            chart = new ApexCharts(chartContainer, chartOptions);
            chart.render();

            const sourceOptions = {
                series: data.sources.map(item => item.total),
                chart: {
                    type: 'donut',
                    height: '100%',
                    parentHeightOffset: 0
                },
                labels: data.sources.map(item => item.label),
                colors: ['#5A5BF1', '#4a90e2', '#34c759', '#af52de', '#ffcc00', '#ff3b30'],
                legend: { position: 'bottom' },
                dataLabels: { enabled: false }
            };
            const sourceChartContainer = document.querySelector("#shortlinkSourceChart");
            sourceChartContainer.innerHTML = '';
            sourceChart = new ApexCharts(sourceChartContainer, sourceOptions);
            sourceChart.render();

            ipChart = buildBreakdownChart('shortlinkIpChart', 'ipNoData', data.ip_breakdown, ipChart);
            deviceChart = buildBreakdownChart('shortlinkDeviceChart', 'deviceNoData', data.device_breakdown, deviceChart);
        });
}

function applyDateFilter() {
    updateChart();
}

document.addEventListener('DOMContentLoaded', updateChart);
</script>
@endpush
