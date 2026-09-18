<!-- PERFORMANCE CARD -->
<div class="card performance-card">
    <div class="card-header">
        <div class="card-title">{{ __('shortlink.performance') }}</div>
        <div class="card-subtitle">{{ __('shortlink.data_preview') }}</div>
    </div>

    <div class="stats-row">
        <div class="stat-box">
            <div class="stat-icon stat-icon-orange"><i class="fas fa-chart-line"></i></div>
            <div class="stat-info">
                <span>{{ __('shortlink.total_clicks') }}</span>
                <strong>2,280</strong>
            </div>
        </div>
        <div class="stat-box">
            <div class="stat-icon stat-icon-blue"><i class="fas fa-link"></i></div>
            <div class="stat-info">
                <span>{{ __('shortlink.total_links') }}</span>
                <strong>{{ $shortlinks->total() }}</strong>
            </div>
        </div>
    </div>

    <div class="chart-container" style="min-height: 180px; width: 100%;">
        <div id="performanceStatChart"></div>
    </div>
    
@push("scripts")
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dataVals = [300, 280, 260, 310, 250, 400, 220, 350, 450, 240, 410, 380];
        const dataLabels = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        
        const options = {
            series: [{
                name: 'Clicks',
                data: dataVals
            }],
            chart: {
                type: 'bar',
                height: 180,
                toolbar: { show: false },
                parentHeightOffset: 0
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    columnWidth: '50%'
                }
            },
            colors: ['#5A5BF1'],
            xaxis: {
                categories: dataLabels,
                labels: { style: { colors: '#94a3b8', fontSize: '11px' } },
                axisBorder: { show: false },
                axisTicks: { show: false },
                tooltip: { enabled: false }
            },
            yaxis: { show: false },
            grid: { show: false, padding: { top: 0, bottom: 0, left: 0, right: 0 } },
            dataLabels: { enabled: false },
            tooltip: {
                y: { formatter: function(val) { return val } }
            }
        };

        const chartContainer = document.querySelector("#performanceStatChart");
        if (chartContainer) {
            const chart = new ApexCharts(chartContainer, options);
            chart.render();
        }
    });
</script>
@endpush
    <button class="hide-chart-btn"><i class="fas fa-chevron-up"></i> {{ __('shortlink.hide_chart') }}</button>
</div>
