@extends("layouts.admin")

@section("page_title", __('admin.analytics_shortlink'))

@push("styles")
<link rel="stylesheet" href="{{ asset('css/pages/shortlink-analytics.css') }}" data-turbo-track="reload">
@endpush

@section("content")
<div class="analytics-header-card" style="padding: 24px; margin-bottom: 24px;">
    <h1>{{ __('admin.analytics_shortlink') }}</h1>
    <p style="color: #FF9040; margin-top: 8px; font-weight: 600;">{{ $shortlink->slug }}</p>
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
                        <canvas id="shortlinkAnalyticsChart"></canvas>
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
                        <canvas id="shortlinkSourceChart"></canvas>
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
                        <canvas id="shortlinkIpChart"></canvas>
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
                        <canvas id="shortlinkDeviceChart"></canvas>
                        <div id="deviceNoData" style="display:none; position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); color:#999; font-style:italic;">{{ __('admin.no_click_data') }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

@push("scripts")
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@push("scripts")
<script>
let chart;
        let sourceChart;
        let ipChart;
        let deviceChart;

        function buildBreakdownChart(canvasId, noDataId, dataArray, chartInstance) {
            const noDataEl = document.getElementById(noDataId);
            if (chartInstance) {
                chartInstance.destroy();
            }

            if (!dataArray || dataArray.length === 0 || dataArray.every(d => d.total === 0)) {
                if (noDataEl) noDataEl.style.display = 'block';
                return null;
            }
            if (noDataEl) noDataEl.style.display = 'none';

            return new Chart(document.getElementById(canvasId).getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: dataArray.map(item => item.label),
                    datasets: [{
                        data: dataArray.map(item => item.total),
                        backgroundColor: ['#FF9040', '#4a90e2', '#34c759', '#af52de', '#ffcc00', '#ff3b30', '#8e8e93', '#5ac8fa'],
                        borderWidth: 0,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { boxWidth: 12, usePointStyle: true, pointStyle: 'circle' },
                        },
                    },
                },
            });
        }

        function updateChart() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            const params = new URLSearchParams({ start_date: startDate, end_date: endDate });

            fetch(`{{ route('admin.shortlinks.analytics.chart', $shortlink) }}?${params.toString()}`)
                .then((response) => response.json())
                .then((data) => {
                    document.getElementById('totalClicksValue').textContent = data.total_clicks;
                    document.getElementById('startDate').value = data.start_date;
                    document.getElementById('endDate').value = data.end_date;

                    if (chart) {
                        chart.destroy();
                    }

                    if (sourceChart) {
                        sourceChart.destroy();
                    }

                    chart = new Chart(document.getElementById('shortlinkAnalyticsChart').getContext('2d'), {
                        type: 'bar',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'Clicks',
                                data: data.clicks,
                                backgroundColor: '#4a90e2',
                                borderRadius: 4,
                                maxBarThickness: 18,
                            }],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: { beginAtZero: true, grid: { color: '#f0f0f0' } },
                                x: { grid: { display: false } },
                            },
                            plugins: {
                                legend: {
                                    position: 'top',
                                    align: 'start',
                                    labels: { boxWidth: 12, usePointStyle: true, pointStyle: 'circle' },
                                },
                            },
                        },
                    });

                    sourceChart = new Chart(document.getElementById('shortlinkSourceChart').getContext('2d'), {
                        type: 'doughnut',
                        data: {
                            labels: data.sources.map((item) => item.label),
                            datasets: [{
                                label: 'Sources',
                                data: data.sources.map((item) => item.total),
                                backgroundColor: ['#FF9040', '#4a90e2', '#34c759', '#af52de', '#ffcc00', '#ff3b30'],
                                borderWidth: 0,
                            }],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: { boxWidth: 12, usePointStyle: true, pointStyle: 'circle' },
                                },
                            },
                        },
                    });

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
