@extends("layouts.admin")

@section("page_title", "Analytics Shortlink")

@push("styles")
<style>
.analytics-header-card, .card { background: #fff; border-radius: 14px; box-shadow: 0 2px 8px rgba(24,24,24,0.06); }
        
        .analytics-header-card h1 { margin: 0; font-size: 18px; color: #181818; }
        .back-link { color: #FF9040; text-decoration: none; font-weight: 700; }
        .grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 18px; margin-bottom: 24px; }
        .card { padding: 20px; }
        .label { color: #666; font-size: 13px; margin-bottom: 10px; }
        .value { color: #181818; font-size: 28px; font-weight: 800; word-break: break-word; }
        .sources { color: #444; line-height: 1.7; font-size: 15px; }
        .chart-header { display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-bottom: 20px; }
        .date-range-selector { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
        .date-input { padding: 7px 12px; border: 1px solid #eee; border-radius: 6px; background: white; font-size: 13px; color: #666; }
        .apply-date { padding: 7px 12px; background: #FF9040; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; }
        .chart-wrap { height: 320px; }
        .stack { display: grid; gap: 24px; }
        .muted { color: #666; font-size: 14px; }
        @media (max-width: 900px) { .main-content { margin-left: 0; } .grid { grid-template-columns: 1fr; } .analytics-header-card, .chart-header { flex-direction: column; align-items: flex-start; } }
</style>
@endpush

@section("content")
<div class="analytics-header-card" style="padding: 24px; margin-bottom: 24px;">
    <h1>Analytics Shortlink</h1>
    <p style="color: #FF9040; margin-top: 8px; font-weight: 600;">{{ $shortlink->slug }}</p>
    <h2 style="font-size: 32px; margin-top: 12px; font-weight: 800;">{{ $totalClicks }} <span style="font-size: 16px; color: #666; font-weight: normal;">Total Clicks</span></h2>
    <div style="margin-top: 15px; color: #555;">
        <strong>Top Sources:</strong>
        @foreach($sources as $source)
            <span style="display: inline-block; background: #eee; padding: 4px 8px; border-radius: 4px; margin-right: 5px; font-size: 13px;">{{ $source }}</span>
        @endforeach
    </div>
</div>
<div class="stack">
                <div class="card">
                    <div class="chart-header">
                        <div>
                            <div class="label">Grafik Click Harian</div>
                            <div class="muted">Destination: {{ $shortlink->destination }}</div>
                        </div>
                        <div class="date-range-selector">
                            <input type="date" id="startDate" class="date-input" value="{{ $startDate }}">
                            <span>to</span>
                            <input type="date" id="endDate" class="date-input" value="{{ $endDate }}">
                            <button class="apply-date" onclick="applyDateFilter()">Apply</button>
                        </div>
                    </div>
                    <div class="chart-wrap">
                        <canvas id="shortlinkAnalyticsChart"></canvas>
                    </div>
                </div>

                <div class="card">
                    <div class="chart-header">
                        <div>
                            <div class="label">Grafik Source Traffic</div>
                            <div class="muted">Distribusi sumber trafik untuk shortlink ini</div>
                        </div>
                    </div>
                    <div class="chart-wrap">
                        <canvas id="shortlinkSourceChart"></canvas>
                    </div>
                </div>

                <div class="card">
                    <div class="chart-header">
                        <div>
                            <div class="label">IP Address Breakdown</div>
                            <div class="muted">Distribusi klik berdasarkan IP Address</div>
                        </div>
                    </div>
                    <div class="chart-wrap" style="position: relative;">
                        <canvas id="shortlinkIpChart"></canvas>
                        <div id="ipNoData" style="display:none; position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); color:#999; font-style:italic;">Belum ada data klik.</div>
                    </div>
                </div>

                <div class="card">
                    <div class="chart-header">
                        <div>
                            <div class="label">Device Breakdown</div>
                            <div class="muted">Distribusi perangkat pengunjung (Mobile/Tablet/Desktop)</div>
                        </div>
                    </div>
                    <div class="chart-wrap" style="position: relative;">
                        <canvas id="shortlinkDeviceChart"></canvas>
                        <div id="deviceNoData" style="display:none; position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); color:#999; font-style:italic;">Belum ada data klik.</div>
                    </div>
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
