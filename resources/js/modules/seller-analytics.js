/**
 * seller-analytics.js
 * Modular analytics & chart engine for seller dashboard and statistics views.
 */

export class SellerAnalyticsManager {
    constructor(options = {}) {
        this.containerSelector = options.containerSelector || '#statsChart';
        this.wrapperSelector = options.wrapperSelector || '#dashboardChartWrapper, .chart-wrapper';
        this.chartInstance = null;
        this.fullData = null;
        this.zoomLevels = [7, 14, 30, 60];
        this.zoomIndex = 2; // Default 30 days
        this.panOffset = 0;

        if (options.autoInit !== false) {
            this.init();
        }
    }

    init() {
        const container = document.querySelector(this.containerSelector);
        if (!container) return;

        const url = container.dataset.chartUrl || this.resolveChartUrl();
        const csrfToken = container.dataset.csrf || this.resolveCsrfToken();

        if (!url) return;
        this.fetchChartData(url, csrfToken);
    }

    resolveChartUrl() {
        const meta = document.querySelector('meta[name="chart-url"]');
        return meta ? meta.getAttribute('content') : null;
    }

    resolveCsrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    fetchChartData(url, csrfToken) {
        const separator = url.includes('?') ? '&' : '?';
        fetch(`${url}${separator}days=60`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then((res) => {
            if (!res.ok) throw new Error('Network response was not ok');
            return res.json();
        })
        .then((data) => {
            this.fullData = data;
            this.renderChart();
            this.setupInteractions();
        })
        .catch((err) => {
            console.warn('Failed to load seller analytics data:', err);
        });
    }

    getVisibleSlice() {
        if (!this.fullData || !this.fullData.labels) {
            return { labels: [], views: [], clicks: [] };
        }

        const totalPoints = this.fullData.labels.length;
        const windowSize = Math.min(this.zoomLevels[this.zoomIndex], totalPoints);
        const maxOffset = totalPoints - windowSize;
        const offset = Math.max(0, Math.min(this.panOffset, maxOffset));

        const start = totalPoints - windowSize - offset;
        const end = totalPoints - offset;

        return {
            labels: this.fullData.labels.slice(start, end),
            views: (this.fullData.views || []).slice(start, end),
            clicks: (this.fullData.clicks || []).slice(start, end)
        };
    }

    renderChart() {
        const container = document.querySelector(this.containerSelector);
        if (!container || typeof window.ApexCharts === 'undefined') return;

        const slice = this.getVisibleSlice();

        if (this.chartInstance) {
            this.chartInstance.updateOptions({
                xaxis: { categories: slice.labels },
                series: [
                    { name: 'Views', data: slice.views },
                    { name: 'Pesanan', data: slice.clicks }
                ]
            });
            return;
        }

        container.innerHTML = '';
        const options = {
            series: [
                { name: 'Views', data: slice.views },
                { name: 'Pesanan', data: slice.clicks }
            ],
            chart: {
                type: 'area',
                height: 120,
                width: '100%',
                parentHeightOffset: 0,
                sparkline: { enabled: true },
                toolbar: { show: false },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 250
                }
            },
            colors: ['#FF9040', '#3B82F6'],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.4,
                    opacityTo: 0.05,
                    stops: [0, 90, 100]
                }
            },
            stroke: {
                curve: 'smooth',
                width: 2
            },
            xaxis: {
                categories: slice.labels,
                crosshairs: { width: 1 }
            },
            tooltip: {
                fixed: { enabled: false },
                x: { show: true },
                marker: { show: true }
            }
        };

        this.chartInstance = new window.ApexCharts(container, options);
        this.chartInstance.render();
    }

    setupInteractions() {
        const wrapper = document.querySelector(this.wrapperSelector);
        if (!wrapper || wrapper._zoomBound) return;
        wrapper._zoomBound = true;

        wrapper.addEventListener('wheel', (e) => {
            if (e.deltaY < 0 && this.zoomIndex > 0) {
                this.zoomIndex--;
                this.renderChart();
            } else if (e.deltaY > 0 && this.zoomIndex < this.zoomLevels.length - 1) {
                this.zoomIndex++;
                this.renderChart();
            }
        }, { passive: true });
    }

    destroy() {
        if (this.chartInstance) {
            this.chartInstance.destroy();
            this.chartInstance = null;
        }
    }
}

export function initSellerAnalytics(options = {}) {
    return new SellerAnalyticsManager(options);
}
