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
                <strong>{{ number_format($totalClicksAllTime ?? 0, 0, ',', '.') }}</strong>
            </div>
        </div>
        <div class="stat-box">
            <div class="stat-icon stat-icon-orange"><i class="fas fa-link"></i></div>
            <div class="stat-info">
                <span>{{ __('shortlink.total_links') }}</span>
                <strong>{{ $shortlinks->total() }}</strong>
            </div>
        </div>
    </div>

    <div class="chart-container" style="min-height: 180px; width: 100%; display: block;">
        <div id="performanceStatChart" style="width: 100%; min-height: 180px;"></div>
    </div>
    
@push("scripts")
<script>
    (function() {
        let performanceChartInstance = null;

        function ensureApexCharts(callback, maxWaitMs = 5000) {
            if (typeof window.ApexCharts !== 'undefined') {
                callback();
                return;
            }
            const startTime = Date.now();
            const poll = setInterval(function() {
                if (typeof window.ApexCharts !== 'undefined') {
                    clearInterval(poll);
                    callback();
                } else if (Date.now() - startTime > maxWaitMs) {
                    clearInterval(poll);
                    console.error('ShortlinkStatChart: ApexCharts failed to load within timeout');
                }
            }, 40);
        }

        function initPerformanceChart() {
            const chartContainer = document.querySelector("#performanceStatChart");
            if (!chartContainer) return;

            ensureApexCharts(function() {
                const isDark = document.documentElement.classList.contains('dark');
                const dataVals = @json($chartData ?? []);
                const dataLabels = @json($chartLabels ?? []);
                
                const options = {
                    series: [{
                        name: 'Clicks',
                        data: dataVals
                    }],
                    chart: {
                        type: 'bar',
                        height: 180,
                        width: '100%',
                        toolbar: { show: false },
                        parentHeightOffset: 0,
                        background: 'transparent'
                    },
                    theme: {
                        mode: isDark ? 'dark' : 'light'
                    },
                    plotOptions: {
                        bar: {
                            borderRadius: 4,
                            columnWidth: '80%'
                        }
                    },
                    colors: ['#FF9040'],
                    xaxis: {
                        categories: dataLabels,
                        labels: { style: { colors: isDark ? '#94a3b8' : '#64748b', fontSize: '11px' } },
                        axisBorder: { show: false },
                        axisTicks: { show: false },
                        tooltip: { enabled: false }
                    },
                    yaxis: { show: false },
                    grid: { show: false, padding: { top: 0, bottom: 0, left: 0, right: 0 } },
                    dataLabels: { enabled: false },
                    tooltip: {
                        theme: isDark ? 'dark' : 'light',
                        y: { formatter: function(val) { return val } }
                    }
                };

                if (performanceChartInstance) {
                    try { performanceChartInstance.destroy(); } catch (e) {}
                    performanceChartInstance = null;
                }

                chartContainer.innerHTML = '';
                performanceChartInstance = new window.ApexCharts(chartContainer, options);
                performanceChartInstance.render();
            });
        }

        if (!window._statChartListenersBound) {
            window._statChartListenersBound = true;
            document.addEventListener('turbo:load', initPerformanceChart);
            document.addEventListener('DOMContentLoaded', initPerformanceChart);
            window.addEventListener('theme-changed', initPerformanceChart);

            document.addEventListener('turbo:before-cache', function() {
                if (performanceChartInstance) {
                    try { performanceChartInstance.destroy(); } catch (e) {}
                    performanceChartInstance = null;
                }
                const container = document.querySelector("#performanceStatChart");
                if (container) container.innerHTML = '';
            });

            if (window.MutationObserver) {
                const themeObserver = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.attributeName === 'class' && document.querySelector("#performanceStatChart")) {
                            initPerformanceChart();
                        }
                    });
                });
                themeObserver.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initPerformanceChart);
        } else {
            initPerformanceChart();
        }
    })();
</script>
@endpush
</div>
