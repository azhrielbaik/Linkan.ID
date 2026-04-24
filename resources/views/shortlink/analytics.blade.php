<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics Shortlink</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        * { box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { margin: 0; background: #f5f6fa; }
        .container { display: flex; min-height: 100vh; }
        .main-content { flex: 1; margin-left: 250px; padding: 25px 30px; }
        .header, .card { background: #fff; border-radius: 14px; box-shadow: 0 2px 8px rgba(24,24,24,0.06); }
        .header { padding: 18px 24px; margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; gap: 16px; }
        .header h1 { margin: 0; font-size: 26px; color: #181818; }
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
        .muted { color: #666; font-size: 14px; }
        @media (max-width: 900px) { .main-content { margin-left: 0; } .grid { grid-template-columns: 1fr; } .header, .chart-header { flex-direction: column; align-items: flex-start; } }
    </style>
</head>
<body>
    <div class="container">
        @include('homeadminS.sidebar.sidebar')

        <div class="main-content">
            <div class="header">
                <div>
                    <h1>Analytics Shortlink</h1>
                    <div class="muted">Lihat performa klik untuk shortlink `{{ $shortlink->slug }}`</div>
                </div>
                <a href="{{ route('shortlink.index') }}" class="back-link">Kembali ke Shortlink</a>
            </div>

            <div class="grid">
                <div class="card">
                    <div class="label">Shortlink</div>
                    <div class="value">{{ $shortlink->slug }}</div>
                </div>
                <div class="card">
                    <div class="label">Total Clicks</div>
                    <div class="value" id="totalClicksValue">{{ $totalClicks }}</div>
                </div>
                <div class="card">
                    <div class="label">Top Sources</div>
                    <div class="sources">{{ $sources ? implode(', ', $sources) : 'Belum ada data sumber trafik.' }}</div>
                </div>
            </div>

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
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let chart;

        function updateChart() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            const params = new URLSearchParams({ start_date: startDate, end_date: endDate });

            fetch(`{{ route('shortlink.analytics.chart', $shortlink) }}?${params.toString()}`)
                .then((response) => response.json())
                .then((data) => {
                    document.getElementById('totalClicksValue').textContent = data.total_clicks;
                    document.getElementById('startDate').value = data.start_date;
                    document.getElementById('endDate').value = data.end_date;

                    if (chart) {
                        chart.destroy();
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
                });
        }

        function applyDateFilter() {
            updateChart();
        }

        document.addEventListener('DOMContentLoaded', updateChart);
    </script>
</body>
</html>
