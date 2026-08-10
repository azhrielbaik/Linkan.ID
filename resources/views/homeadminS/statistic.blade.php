@extends("layouts.admin")

@section("page_title", "Statistic")

@push("styles")
<style>
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .stats-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .stats-header h3 {
            font-size: 15px;
            color: #333;
            font-weight: 500;
        }

        .date-range-selector {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .date-input {
            padding: 7px 12px;
            border: 1px solid #eee;
            border-radius: 6px;
            background: white;
            font-size: 13px;
            color: #666;
        }

        .apply-date {
            padding: 7px 12px;
            background: #FF9040;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
        }

        .apply-date:hover {
            opacity: 0.9;
        }

        .stats-numbers {
            display: flex;
            gap: 25px;
            margin-bottom: 20px;
            font-size: 14px;
            color: #666;
        }

        .stats-chart {
            height: 300px;
            margin-top: 15px;
        }

        @media (max-width: 900px) {
            .main-content {
                margin-left: 0;
            }
        }
</style>
@endpush

@section("content")


            <!-- Grafik Total Clicks & Views -->
            <div class="stats-section">
                <div class="stats-header">
                    <h3>Total Click & Views</h3>
                    <div class="date-range-selector">
                        <input type="date" id="startDate1" class="date-input">
                        <span>to</span>
                        <input type="date" id="endDate1" class="date-input">
                        <button class="apply-date" onclick="applyDateFilter1()">Apply</button>
                    </div>
                </div>
                <div class="stats-numbers">
                    <span>Views: {{ $totalViews }}</span>
                    <span>Clicks: {{ $totalClicks }}</span>
                </div>
                <div class="stats-chart">
                    <canvas id="statsChart1"></canvas>
                </div>
            </div>

            <!-- Grafik Total Sales -->
            <div class="stats-section">
                <div class="stats-header">
                    <h3>Total Sales</h3>
                    <div class="date-range-selector">
                        <input type="date" id="startDate2" class="date-input">
                        <span>to</span>
                        <input type="date" id="endDate2" class="date-input">
                        <button class="apply-date" onclick="applyDateFilter2()">Apply</button>
                    </div>
                </div>
                <div class="stats-numbers">
                    <span>Total Sales: IDR {{ number_format($totalSales, 0, ',', '.') }}</span>
                </div>
                <div class="stats-chart">
                    <canvas id="statsChart2"></canvas>
                </div>
            </div>
@endsection

@push("scripts")
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@push("scripts")
<script>
let chart1, chart2;
        let startDate1 = null, endDate1 = null;
        let startDate2 = null, endDate2 = null;

        function updateChart1() {
            const params = new URLSearchParams();
            if (startDate1) params.append('start_date', startDate1);
            if (endDate1) params.append('end_date', endDate1);

            fetch(`{{ route('admin.statistics.chart-data') }}?${params.toString()}`)
                .then(response => response.json())
                .then(data => {
                    if (chart1) {
                        chart1.destroy();
                    }

                    // Update date inputs
                    document.getElementById('startDate1').value = data.start_date;
                    document.getElementById('endDate1').value = data.end_date;
                    startDate1 = data.start_date;
                    endDate1 = data.end_date;

                    const ctx = document.getElementById('statsChart1').getContext('2d');
                    chart1 = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'Views',
                                data: data.views,
                                backgroundColor: '#ff4500',
                                borderRadius: 4,
                                maxBarThickness: 12
                            }, {
                                label: 'Clicks',
                                data: data.clicks,
                                backgroundColor: '#4a90e2',
                                borderRadius: 4,
                                maxBarThickness: 12
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: '#f0f0f0'
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    position: 'top',
                                    align: 'start',
                                    labels: {
                                        boxWidth: 12,
                                        usePointStyle: true,
                                        pointStyle: 'circle'
                                    }
                                }
                            }
                        }
                    });
                });
        }

        function updateChart2() {
            const params = new URLSearchParams();
            if (startDate2) params.append('start_date', startDate2);
            if (endDate2) params.append('end_date', endDate2);

            fetch(`{{ route('admin.statistics.chart-data') }}?${params.toString()}`)
                .then(response => response.json())
                .then(data => {
                    if (chart2) {
                        chart2.destroy();
                    }

                    // Update date inputs
                    document.getElementById('startDate2').value = data.start_date;
                    document.getElementById('endDate2').value = data.end_date;
                    startDate2 = data.start_date;
                    endDate2 = data.end_date;

                    const ctx = document.getElementById('statsChart2').getContext('2d');
                    chart2 = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'Sales',
                                data: data.sales,
                                borderColor: '#FF9040',
                                backgroundColor: 'rgba(255, 144, 64, 0.1)',
                                fill: true,
                                tension: 0.4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: '#f0f0f0'
                                    },
                                    ticks: {
                                        callback: function(value) {
                                            return 'IDR ' + value.toLocaleString('id-ID');
                                        }
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    position: 'top',
                                    align: 'start',
                                    labels: {
                                        boxWidth: 12,
                                        usePointStyle: true,
                                        pointStyle: 'circle'
                                    }
                                }
                            }
                        }
                    });
                });
        }

        function applyDateFilter1() {
            startDate1 = document.getElementById('startDate1').value;
            endDate1 = document.getElementById('endDate1').value;
            updateChart1();
        }

        function applyDateFilter2() {
            startDate2 = document.getElementById('startDate2').value;
            endDate2 = document.getElementById('endDate2').value;
            updateChart2();
        }

        // Set default dates (7 days ago to today)
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date();
            const sevenDaysAgo = new Date();
            sevenDaysAgo.setDate(today.getDate() - 6);

            // Set default dates for both charts
            document.getElementById('startDate1').value = sevenDaysAgo.toISOString().split('T')[0];
            document.getElementById('endDate1').value = today.toISOString().split('T')[0];
            document.getElementById('startDate2').value = sevenDaysAgo.toISOString().split('T')[0];
            document.getElementById('endDate2').value = today.toISOString().split('T')[0];

            startDate1 = document.getElementById('startDate1').value;
            endDate1 = document.getElementById('endDate1').value;
            startDate2 = document.getElementById('startDate2').value;
            endDate2 = document.getElementById('endDate2').value;

            updateChart1();
            updateChart2();
        });
</script>
@endpush
