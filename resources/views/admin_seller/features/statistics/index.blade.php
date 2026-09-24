@extends("admin_seller.layouts.app")

@section("page_title", __('admin.statistic_title'))

@push("styles")
<link rel="stylesheet" href="{{ asset('css/pages/shortlink-analytics.css') }}?v={{ filemtime(public_path('css/pages/shortlink-analytics.css')) }}" data-turbo-track="reload">
@endpush

@section("content")
<div class="sa-analytics-wrapper sa-page">
    <div class="sa-skymetrics-header">
        <div class="sa-header-left">
            <h1 class="sa-page-title">Global Analytics</h1>
            <p class="sa-page-subtitle">Your overall performance metrics</p>
        </div>
        
        <div class="sa-header-right">
            <div class="sa-date-filter">
                <div class="sa-date-input-group">
                    <i class="far fa-calendar-alt sa-date-icon"></i>
                    <input type="date" id="startDate" class="sa-date-input">
                    <span class="sa-date-sep">-</span>
                    <input type="date" id="endDate" class="sa-date-input">
                </div>
                <button onclick="applyDateFilter()" class="sa-btn-apply">{{ __('admin.apply') ?? 'Terapkan' }}</button>
            </div>
        </div>
    </div>

    <!-- ROW 1: 4 Scorecards -->
    <div class="sa-scorecard-grid">
        <div class="sa-scorecard">
            <div class="sa-sc-header">
                <span class="sa-sc-title">Total Views</span>
                <div class="sa-sc-icon sa-icon-views"><i class="far fa-eye"></i></div>
            </div>
            <div class="sa-sc-value">{{ number_format($totalViews, 0, ',', '.') }}</div>
            <div class="sa-sc-footer">all shortlinks & microsites</div>
        </div>
        <div class="sa-scorecard">
            <div class="sa-sc-header">
                <span class="sa-sc-title">Total Clicks</span>
                <div class="sa-sc-icon sa-icon-clicks"><i class="fas fa-mouse-pointer"></i></div>
            </div>
            <div class="sa-sc-value">{{ number_format($totalClicks, 0, ',', '.') }}</div>
            <div class="sa-sc-footer">all shortlinks & microsites</div>
        </div>
        <div class="sa-scorecard">
            <div class="sa-sc-header">
                <span class="sa-sc-title">Total Sales</span>
                <div class="sa-sc-icon sa-icon-sales"><i class="fas fa-wallet"></i></div>
            </div>
            <div class="sa-sc-value">Rp {{ number_format($totalSales, 0, ',', '.') }}</div>
            <div class="sa-sc-footer">from digital products</div>
        </div>
        <div class="sa-scorecard">
            <div class="sa-sc-header">
                <span class="sa-sc-title">Total Transactions</span>
                <div class="sa-sc-icon sa-icon-trans"><i class="fas fa-shopping-bag"></i></div>
            </div>
            <div class="sa-sc-value">{{ number_format($totalTransactions ?? 0, 0, ',', '.') }}</div>
            <div class="sa-sc-footer">successful orders</div>
        </div>
    </div>

    <!-- ROW 2: Main Charts -->
    <div class="sa-charts-row">
        <!-- Sales Chart -->
        <div class="sa-chart-main">
            <div class="sa-card-header-clean">
                <h3 class="sa-card-title-clean">Sales & Revenue</h3>
                <div class="sa-chart-legend">
                    <span class="sa-legend-item"><span class="sa-dot-green"></span> Sales (IDR)</span>
                </div>
            </div>
            <div class="sa-chart-wrap">
                <div id="statsChart2" style="height: 100%;"></div>
            </div>
        </div>
        
        <!-- Clicks/Views Chart -->
        <div class="sa-chart-side">
            <div class="sa-card-header-clean">
                <h3 class="sa-card-title-clean">Traffic (Clicks & Views)</h3>
                <div class="sa-chart-legend">
                    <span class="sa-legend-item" style="margin-right: 10px;"><span class="sa-dot-green" style="background: #ed842c;"></span> Views</span>
                    <span class="sa-legend-item"><span class="sa-dot-gray" style="background: #f59e0b;"></span> Clicks</span>
                </div>
            </div>
            <div class="sa-chart-wrap">
                <div id="statsChart1" style="height: 100%;"></div>
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
let chart1, chart2;
let startDateVal = null, endDateVal = null;

function updateCharts() {
    const params = new URLSearchParams();
    if (startDateVal) params.append('start_date', startDateVal);
    if (endDateVal) params.append('end_date', endDateVal);

    fetch(`{{ route('admin.statistics.chart-data') }}?${params.toString()}`)
        .then(response => response.json())
        .then(data => {
            if (chart1) chart1.destroy();
            if (chart2) chart2.destroy();

            // Update date inputs
            document.getElementById('startDate').value = data.start_date;
            document.getElementById('endDate').value = data.end_date;
            startDateVal = data.start_date;
            endDateVal = data.end_date;

            const isDark = document.documentElement.classList.contains('dark');
            const labelColor = isDark ? '#94a3b8' : '#9ca3af';
            const gridColor = isDark ? '#2a3241' : '#f3f4f6';

            // Chart 1: Traffic (Views & Clicks)
            const options1 = {
                series: [
                    { name: 'Views', data: data.views }, 
                    { name: 'Clicks', data: data.clicks }
                ],
                chart: {
                    type: 'line',
                    height: '100%',
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
                stroke: { curve: 'smooth', width: 3 },
                colors: ['#ed842c', '#f59e0b'],
                dataLabels: { enabled: false },
                xaxis: {
                    categories: data.labels,
                    labels: { 
                        rotate: window.innerWidth <= 768 ? -30 : 0,
                        hideOverlappingLabels: true,
                        style: { colors: labelColor, fontFamily: 'Inter, sans-serif' } 
                    },
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    tooltip: { enabled: false }
                },
                yaxis: {
                    labels: { 
                        formatter: (val) => Math.floor(val),
                        style: { colors: labelColor, fontFamily: 'Inter, sans-serif' }
                    }
                },
                grid: {
                    borderColor: gridColor,
                    strokeDashArray: 0,
                    xaxis: { lines: { show: false } },
                    yaxis: { lines: { show: true } },
                    padding: { top: 0, right: 0, bottom: 0, left: 10 }
                },
                legend: { show: false },
                responsive: [{
                    breakpoint: 768,
                    options: {
                        chart: { height: 280 },
                        xaxis: {
                            tickAmount: 5,
                            labels: { rotate: -30, hideOverlappingLabels: true, style: { fontSize: '10px' } }
                        },
                        yaxis: {
                            labels: { style: { fontSize: '10px' } }
                        }
                    }
                }]
            };
            const container1 = document.querySelector("#statsChart1");
            container1.innerHTML = '';
            chart1 = new ApexCharts(container1, options1);
            chart1.render();

            // Chart 2: Sales
            const options2 = {
                series: [{
                    name: 'Sales',
                    data: data.sales
                }],
                chart: {
                    type: 'area',
                    height: '100%',
                    toolbar: { show: false },
                    parentHeightOffset: 0,
                },
                colors: ['#ed842c'], // Project Primary Orange
                fill: { 
                    type: 'gradient', 
                    gradient: { shadeIntensity: 1, opacityFrom: 0.35, opacityTo: 0.05, stops: [0, 90, 100] } 
                },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                dataLabels: { enabled: false },
                xaxis: {
                    categories: data.labels,
                    labels: { 
                        rotate: window.innerWidth <= 768 ? -30 : 0,
                        hideOverlappingLabels: true,
                        style: { colors: labelColor, fontFamily: 'Inter, sans-serif' } 
                    },
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    tooltip: { enabled: false }
                },
                yaxis: {
                    labels: {
                        formatter: function(value) {
                            if (window.innerWidth <= 768) {
                                if (value >= 1000000000) return (value / 1000000000).toFixed(1) + 'M';
                                if (value >= 1000000) return (value / 1000000).toFixed(1) + 'jt';
                                if (value >= 1000) return (value / 1000).toFixed(0) + 'rb';
                                return value;
                            }
                            return 'Rp ' + Number(value).toLocaleString('id-ID');
                        },
                        style: { colors: labelColor, fontFamily: 'Inter, sans-serif' }
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(value) {
                            return 'Rp ' + Number(value).toLocaleString('id-ID');
                        }
                    }
                },
                grid: {
                    borderColor: gridColor,
                    strokeDashArray: 0,
                    xaxis: { lines: { show: false } },
                    yaxis: { lines: { show: true } },
                    padding: { top: 0, right: 0, bottom: 0, left: 10 }
                },
                legend: { show: false },
                responsive: [{
                    breakpoint: 768,
                    options: {
                        chart: { height: 280 },
                        xaxis: {
                            tickAmount: 5,
                            labels: { rotate: -30, hideOverlappingLabels: true, style: { fontSize: '10px' } }
                        },
                        yaxis: {
                            labels: {
                                formatter: function(value) {
                                    if (value >= 1000000000) return (value / 1000000000).toFixed(1) + 'M';
                                    if (value >= 1000000) return (value / 1000000).toFixed(1) + 'jt';
                                    if (value >= 1000) return (value / 1000).toFixed(0) + 'rb';
                                    return value;
                                },
                                style: { fontSize: '10px' }
                            }
                        }
                    }
                }]
            };
            const container2 = document.querySelector("#statsChart2");
            container2.innerHTML = '';
            chart2 = new ApexCharts(container2, options2);
            chart2.render();
        });
}

function applyDateFilter() {
    startDateVal = document.getElementById('startDate').value;
    endDateVal = document.getElementById('endDate').value;
    updateCharts();
}

document.addEventListener('DOMContentLoaded', function() {
    updateCharts();
});

document.addEventListener('turbo:load', function() {
    updateCharts();
});

// Refresh chart styles on dark mode change
const observer = new MutationObserver((mutations) => {
    mutations.forEach((mutation) => {
        if (mutation.attributeName === 'class') {
            updateCharts();
        }
    });
});
observer.observe(document.documentElement, { attributes: true });
</script>
@endpush
