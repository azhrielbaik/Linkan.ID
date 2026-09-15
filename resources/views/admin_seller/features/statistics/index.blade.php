@extends("admin_seller.layouts.app")

@section("page_title", __('admin.statistic_title'))

@push("styles")
<link rel="stylesheet" href="{{ asset('css/pages/statistic.css') }}" data-turbo-track="reload">
@endpush

@section("content")
<div class="dashboard-statistic-page">



            <!-- Grafik Total Clicks & Views -->
            <div class="stats-section">
                <div class="stats-header">
                    <h3>{{ __('admin.total_click_views') }}</h3>
                    <div class="date-range-selector">
                        <input type="date" id="startDate1" class="date-input">
                        <span>{{ __('admin.to') }}</span>
                        <input type="date" id="endDate1" class="date-input">
                        <button class="apply-date" onclick="applyDateFilter1()">{{ __('admin.apply') }}</button>
                    </div>
                </div>
                <div class="stats-numbers">
                    <span>{{ __('admin.views') }}: {{ $totalViews }}</span>
                    <span>{{ __('admin.clicks') }}: {{ $totalClicks }}</span>
                </div>
                <div class="stats-chart">
                    <div id="statsChart1" style="height: 300px;"></div>
                </div>
            </div>

            <!-- Grafik Total Sales -->
            <div class="stats-section">
                <div class="stats-header">
                    <h3>{{ __('admin.total_sales') }}</h3>
                    <div class="date-range-selector">
                        <input type="date" id="startDate2" class="date-input">
                        <span>{{ __('admin.to') }}</span>
                        <input type="date" id="endDate2" class="date-input">
                        <button class="apply-date" onclick="applyDateFilter2()">{{ __('admin.apply') }}</button>
                    </div>
                </div>
                <div class="stats-numbers">
                    <span>{{ __('admin.total_sales') }}: IDR {{ number_format($totalSales, 0, ',', '.') }}</span>
                </div>
                <div class="stats-chart">
                    <div id="statsChart2" style="height: 300px;"></div>
                </div>
            </div>

</div>
@endsection

@push("scripts")
<script src="{{ asset('js/apexcharts.min.js') }}"></script>
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

                    const options1 = {
                        series: [{
                            name: 'Views',
                            data: data.views
                        }, {
                            name: 'Clicks',
                            data: data.clicks
                        }],
                        chart: {
                            type: 'bar',
                            height: 300,
                            toolbar: { show: false }
                        },
                        plotOptions: {
                            bar: {
                                borderRadius: 4,
                                columnWidth: '50%'
                            }
                        },
                        colors: ['#5A5BF1', '#38BDF8'],
                        dataLabels: { enabled: false },
                        xaxis: {
                            categories: data.labels,
                            axisBorder: { show: false },
                            axisTicks: { show: false }
                        },
                        yaxis: {
                            labels: { formatter: (val) => Math.floor(val) }
                        },
                        grid: {
                            borderColor: '#f0f0f0',
                            strokeDashArray: 4
                        },
                        legend: { position: 'top', horizontalAlign: 'left' }
                    };
                    const container1 = document.querySelector("#statsChart1");
                    container1.innerHTML = '';
                    chart1 = new ApexCharts(container1, options1);
                    chart1.render();
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

                    const options2 = {
                        series: [{
                            name: 'Sales',
                            data: data.sales
                        }],
                        chart: {
                            type: 'area',
                            height: 300,
                            toolbar: { show: false }
                        },
                        colors: ['#5A5BF1'],
                        fill: {
                            type: 'solid',
                            opacity: 0.1
                        },
                        stroke: {
                            curve: 'smooth',
                            width: 2
                        },
                        dataLabels: { enabled: false },
                        xaxis: {
                            categories: data.labels,
                            axisBorder: { show: false },
                            axisTicks: { show: false }
                        },
                        yaxis: {
                            labels: {
                                formatter: function(value) {
                                    return 'IDR ' + value.toLocaleString('id-ID');
                                }
                            }
                        },
                        grid: {
                            borderColor: '#f0f0f0',
                            strokeDashArray: 4
                        },
                        legend: { position: 'top', horizontalAlign: 'left' }
                    };
                    const container2 = document.querySelector("#statsChart2");
                    container2.innerHTML = '';
                    chart2 = new ApexCharts(container2, options2);
                    chart2.render();
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
