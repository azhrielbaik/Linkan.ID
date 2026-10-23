/**
 * ============================================================================
 * LINKAN.ID - SHORTLINK ANALYTICS MODULE (shortlink-analytics.js)
 * High-performance, clean, modular, and maintainable controller for shortlink analytics.
 * Strictly adheres to Single Responsibility Principle (SRP) and Separation of Concerns (SoC).
 * ============================================================================
 */

(function (window, document) {
    'use strict';

    /**
     * Escape special characters to prevent HTML/XSS injection.
     * @param {string|number} rawValue
     * @returns {string}
     */
    function escapeHtml(rawValue) {
        if (rawValue === null || rawValue === undefined) {
            return '';
        }
        return String(rawValue)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    /**
     * Centralized controller module for shortlink analytics.
     */
    const ShortlinkAnalytics = {
        config: null,
        currentData: null,
        activeSourceFilter: null,
        abortController: null,
        isInitialized: false,
        themeObserver: null,

        charts: {
            timeline: null,
            source: null
        },

        elements: {},

        /**
         * Initialize the analytics page application.
         */
        init() {
            const root = document.getElementById('shortlinkAnalyticsApp');
            if (!root) {
                return;
            }

            this.cacheElements(root);
            this.loadConfiguration(root);
            this.bindEvents();

            // Handle initial state without duplicate network calls
            const initialDataScript = document.getElementById('shortlink-initial-data');
            let initialData = null;

            if (initialDataScript && initialDataScript.textContent.trim()) {
                try {
                    initialData = JSON.parse(initialDataScript.textContent);
                } catch (err) {
                    console.error('ShortlinkAnalytics: Failed to parse initial data payload.', err);
                }
            }

            if (initialData) {
                this.renderAll(initialData);
            } else {
                this.applyDateFilter();
            }

            this.isInitialized = true;
        },

        /**
         * Cache necessary DOM nodes.
         * @param {HTMLElement} root
         */
        cacheElements(root) {
            this.elements = {
                root,
                startDateInput: document.getElementById('startDate'),
                endDateInput: document.getElementById('endDate'),
                applyBtn: document.getElementById('applyDateFilterBtn'),
                totalClicksValue: document.getElementById('totalClicksValue'),
                uniqueClicksValue: document.getElementById('uniqueClicksValue'),
                topSourceValue: document.getElementById('topSourceValue'),
                topDeviceValue: document.getElementById('topDeviceValue'),
                timelineChartContainer: document.getElementById('shortlinkAnalyticsChart'),
                sourceChartContainer: document.getElementById('shortlinkSourceChart'),
                sourceNoData: document.getElementById('sourceNoData'),
                deviceListWrap: document.getElementById('deviceListWrap'),
                ipTableBody: document.getElementById('ipTableBody')
            };
        },

        /**
         * Parse dynamic configuration & i18n text from root data-* attributes.
         * @param {HTMLElement} root
         */
        loadConfiguration(root) {
            const dataset = root.dataset;
            this.config = {
                chartUrl: dataset.chartUrl || '',
                i18n: {
                    apply: dataset.i18nApply || 'Terapkan',
                    loading: dataset.i18nLoading || 'Loading...',
                    noData: dataset.i18nNoData || 'Tidak ada data klik',
                    totalClicks: dataset.i18nTotalClicks || 'Total Clicks',
                    uniqueClicks: dataset.i18nUniqueClicks || 'Unique Clicks',
                    totalVisits: dataset.i18nTotalVisits || 'Total visits',
                    sessionsByDevice: dataset.i18nSessionsByDevice || 'Sessions by device'
                }
            };
        },

        /**
         * Attach DOM event listeners cleanly without inline onclick.
         */
        bindEvents() {
            if (this.isInitialized) {
                return;
            }

            const { applyBtn, startDateInput, endDateInput } = this.elements;

            if (applyBtn) {
                applyBtn.addEventListener('click', () => this.applyDateFilter());
            }

            const handleEnterKey = (event) => {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    this.applyDateFilter();
                }
            };

            if (startDateInput) {
                startDateInput.addEventListener('keydown', handleEnterKey);
            }

            if (endDateInput) {
                endDateInput.addEventListener('keydown', handleEnterKey);
            }

            // Observe theme mutations for live dark mode chart synchronization
            if (!this.themeObserver && window.MutationObserver) {
                this.themeObserver = new MutationObserver((mutations) => {
                    for (const mutation of mutations) {
                        if (mutation.attributeName === 'class' && this.currentData) {
                            this.renderTimelineChart(this.currentData);
                            this.renderSourceDonut(this.currentData);
                            break;
                        }
                    }
                });

                this.themeObserver.observe(document.documentElement, { attributes: true });
            }
        },

        /**
         * Retrieve active design theme tokens.
         * @returns {Object}
         */
        getThemeTokens() {
            const isDark = document.documentElement.classList.contains('dark');
            return {
                isDark,
                textColor: isDark ? '#94a3b8' : '#6b7280',
                titleColor: isDark ? '#f1f5f9' : '#111827',
                gridColor: isDark ? '#2a3241' : '#f3f4f6',
                tooltipTheme: isDark ? 'dark' : 'light',
                strokeBg: isDark ? '#1c212e' : '#ffffff'
            };
        },

        /**
         * Fetch analytics dataset via AJAX.
         * Single Responsibility: Network request & error handling.
         * @param {Object} params
         * @returns {Promise<void>}
         */
        async fetchAnalytics(params = {}) {
            if (!this.config || !this.config.chartUrl) {
                console.warn('ShortlinkAnalytics: Chart endpoint URL is not configured.');
                return;
            }

            if (this.abortController) {
                this.abortController.abort();
            }
            this.abortController = new AbortController();

            this.setLoadingState(true);

            try {
                const queryParams = new URLSearchParams(params);
                const fullUrl = `${this.config.chartUrl}?${queryParams.toString()}`;

                const response = await fetch(fullUrl, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    signal: this.abortController.signal
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                this.renderAll(data);
            } catch (error) {
                if (error.name !== 'AbortError') {
                    console.error('ShortlinkAnalytics: Failed to fetch analytics data:', error);
                }
            } finally {
                this.setLoadingState(false);
            }
        },

        /**
         * Trigger analytics update with selected date range and active source filter.
         */
        applyDateFilter() {
            const { startDateInput, endDateInput } = this.elements;
            const params = {};

            if (startDateInput && startDateInput.value) {
                params.start_date = startDateInput.value;
            }
            if (endDateInput && endDateInput.value) {
                params.end_date = endDateInput.value;
            }
            if (this.activeSourceFilter) {
                params.source = this.activeSourceFilter;
            }

            this.fetchAnalytics(params);
        },

        /**
         * Toggle active source filter from donut slice interaction.
         * @param {string|null} source
         */
        setSourceFilter(source) {
            this.activeSourceFilter = this.activeSourceFilter === source ? null : source;
            this.applyDateFilter();
        },

        /**
         * Wait until ApexCharts is available before executing chart operations.
         * @param {Function} callback
         * @param {number} maxWaitMs
         */
        ensureApexCharts(callback, maxWaitMs = 5000) {
            if (typeof window.ApexCharts !== 'undefined') {
                callback();
                return;
            }
            const startTime = Date.now();
            const poll = setInterval(() => {
                if (typeof window.ApexCharts !== 'undefined') {
                    clearInterval(poll);
                    callback();
                } else if (Date.now() - startTime > maxWaitMs) {
                    clearInterval(poll);
                    console.error('ShortlinkAnalytics: ApexCharts failed to load within timeout');
                }
            }, 30);
        },

        /**
         * Render all UI sections with the given dataset.
         * @param {Object} data
         */
        renderAll(data) {
            this.currentData = data;
            this.updateScorecards(data);
            this.renderDeviceList(data.device_breakdown || []);
            this.renderIpTable(data.ip_breakdown || []);

            this.ensureApexCharts(() => {
                requestAnimationFrame(() => {
                    this.renderTimelineChart(data);
                    this.renderSourceDonut(data);
                });
            });
        },

        /**
         * Update KPI scorecards numbers.
         * Single Responsibility: Metric displays & date sync.
         * @param {Object} data
         */
        updateScorecards(data) {
            const {
                totalClicksValue,
                uniqueClicksValue,
                topSourceValue,
                topDeviceValue,
                startDateInput,
                endDateInput
            } = this.elements;

            if (totalClicksValue && typeof data.total_clicks !== 'undefined') {
                totalClicksValue.textContent = Number(data.total_clicks).toLocaleString();
            }

            if (uniqueClicksValue && typeof data.unique_total !== 'undefined') {
                uniqueClicksValue.textContent = Number(data.unique_total).toLocaleString();
            }

            if (topSourceValue) {
                const hasSources = Array.isArray(data.sources) && data.sources.length > 0;
                const sourceLabel = hasSources ? data.sources[0].label : '-';
                topSourceValue.textContent = sourceLabel;
                topSourceValue.title = sourceLabel;
            }

            if (topDeviceValue) {
                const hasDevices = Array.isArray(data.device_breakdown) && data.device_breakdown.length > 0;
                const deviceLabel = hasDevices ? data.device_breakdown[0].label : '-';
                topDeviceValue.textContent = deviceLabel;
                topDeviceValue.title = deviceLabel;
            }

            if (startDateInput && data.start_date) {
                startDateInput.value = data.start_date;
            }

            if (endDateInput && data.end_date) {
                endDateInput.value = data.end_date;
            }
        },

        /**
         * Manage lifecycle & options for the timeline line chart.
         * Single Responsibility: Timeline Line Chart.
         * @param {Object} data
         */
        renderTimelineChart(data) {
            const container = this.elements.timelineChartContainer;
            if (!container || typeof window.ApexCharts === 'undefined') {
                return;
            }

            if (this.charts.timeline) {
                this.charts.timeline.destroy();
                this.charts.timeline = null;
            }

            const theme = this.getThemeTokens();

            const chartOptions = {
                series: [
                    { name: this.config.i18n.totalClicks, data: data.clicks || [] },
                    { name: this.config.i18n.uniqueClicks, data: data.unique_clicks || [] }
                ],
                chart: {
                    type: 'line',
                    height: '100%',
                    width: '100%',
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
                responsive: [
                    {
                        breakpoint: 768,
                        options: {
                            chart: { height: 220 },
                            xaxis: {
                                tickAmount: 5,
                                labels: {
                                    rotate: 0,
                                    hideOverlappingLabels: true,
                                    style: { fontSize: '10px' }
                                }
                            },
                            yaxis: {
                                labels: {
                                    style: { fontSize: '10px' }
                                }
                            }
                        }
                    },
                    {
                        breakpoint: 480,
                        options: {
                            chart: { height: 210 },
                            stroke: { width: [2.5, 1.8] },
                            xaxis: {
                                tickAmount: 4,
                                labels: {
                                    rotate: -15,
                                    rotateAlways: false,
                                    hideOverlappingLabels: true,
                                    style: { fontSize: '9px' }
                                }
                            },
                            yaxis: {
                                labels: {
                                    style: { fontSize: '9px' }
                                }
                            },
                            grid: {
                                padding: { left: 4, right: 4, top: 0, bottom: 0 }
                            }
                        }
                    }
                ],
                stroke: { curve: 'smooth', width: [3, 2], dashArray: [0, 0] },
                colors: ['#ed842c', '#f59e0b'],
                dataLabels: { enabled: false },
                xaxis: {
                    categories: data.labels || [],
                    labels: {
                        style: {
                            colors: theme.textColor,
                            fontFamily: 'Inter, sans-serif',
                            fontWeight: 500
                        }
                    },
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    tooltip: { enabled: false }
                },
                yaxis: {
                    labels: {
                        formatter: (val) => Math.floor(val),
                        style: {
                            colors: theme.textColor,
                            fontFamily: 'Inter, sans-serif',
                            fontWeight: 500
                        }
                    }
                },
                grid: {
                    borderColor: theme.gridColor,
                    strokeDashArray: 0,
                    xaxis: { lines: { show: false } },
                    yaxis: { lines: { show: true } },
                    padding: { top: 0, right: 0, bottom: 0, left: 10 }
                },
                legend: { show: false },
                tooltip: {
                    theme: theme.tooltipTheme,
                    x: { show: true, format: 'dd MMM' },
                    style: { fontSize: '13px', fontFamily: 'Inter, sans-serif' },
                    marker: { show: false }
                }
            };

            container.innerHTML = '';
            this.charts.timeline = new window.ApexCharts(container, chartOptions);
            this.charts.timeline.render();
        },

        /**
         * Manage lifecycle & options for the source traffic donut chart.
         * Single Responsibility: Source Donut Chart.
         * @param {Object} data
         */
        renderSourceDonut(data) {
            const container = this.elements.sourceChartContainer;
            const noDataEl = this.elements.sourceNoData;

            if (!container || typeof window.ApexCharts === 'undefined') {
                return;
            }

            if (this.charts.source) {
                this.charts.source.destroy();
                this.charts.source = null;
            }

            const sources = data.sources || [];
            const hasData = sources.length > 0 && sources.some((item) => item.total > 0);

            if (!hasData) {
                container.innerHTML = '';
                if (noDataEl) {
                    noDataEl.style.display = 'block';
                }
                return;
            }

            if (noDataEl) {
                noDataEl.style.display = 'none';
            }

            const theme = this.getThemeTokens();
            const totalSum = sources.reduce((acc, val) => acc + val.total, 0);

            const donutOptions = {
                series: sources.map((item) => item.total),
                chart: {
                    type: 'donut',
                    height: '100%',
                    width: '100%',
                    parentHeightOffset: 0,
                    events: {
                        dataPointSelection: (event, chartContext, config) => {
                            const selectedItem = sources[config.dataPointIndex];
                            if (selectedItem && selectedItem.label) {
                                this.setSourceFilter(selectedItem.label);
                            }
                        }
                    }
                },
                responsive: [
                    {
                        breakpoint: 768,
                        options: {
                            chart: { height: 220 },
                            plotOptions: {
                                pie: {
                                    donut: {
                                        size: '72%',
                                        labels: {
                                            name: { fontSize: '11px', offsetY: 14 },
                                            value: { fontSize: '20px', offsetY: -6 },
                                            total: { fontSize: '10px' }
                                        }
                                    }
                                }
                            }
                        }
                    },
                    {
                        breakpoint: 480,
                        options: {
                            chart: { height: 210 },
                            plotOptions: {
                                pie: {
                                    donut: {
                                        size: '70%',
                                        labels: {
                                            name: { fontSize: '10px', offsetY: 12 },
                                            value: { fontSize: '17px', offsetY: -4 },
                                            total: { fontSize: '9px' }
                                        }
                                    }
                                }
                            }
                        }
                    }
                ],
                labels: sources.map((item) => item.label),
                colors: ['#ed842c', '#f59e0b', '#14b8a6', '#f97316', '#94a3b8'],
                legend: { show: false },
                dataLabels: { enabled: false },
                stroke: { show: true, colors: [theme.strokeBg], width: 3 },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '75%',
                            labels: {
                                show: true,
                                name: {
                                    show: true,
                                    fontSize: '12px',
                                    fontFamily: 'Inter, sans-serif',
                                    fontWeight: 600,
                                    color: theme.textColor,
                                    offsetY: 20
                                },
                                value: {
                                    show: true,
                                    fontSize: '28px',
                                    fontFamily: 'Inter, sans-serif',
                                    fontWeight: 700,
                                    color: theme.titleColor,
                                    offsetY: -10,
                                    formatter: (val) => val
                                },
                                total: {
                                    show: true,
                                    showAlways: true,
                                    label: this.config.i18n.totalVisits,
                                    fontSize: '12px',
                                    fontFamily: 'Inter, sans-serif',
                                    fontWeight: 500,
                                    color: theme.textColor,
                                    formatter: () => totalSum
                                }
                            }
                        }
                    }
                }
            };

            container.innerHTML = '';
            this.charts.source = new window.ApexCharts(container, donutOptions);
            this.charts.source.render();
        },

        /**
         * Render device breakdown list items.
         * Single Responsibility: Device list DOM rendering.
         * @param {Array} devices
         */
        renderDeviceList(devices) {
            const container = this.elements.deviceListWrap;
            if (!container) {
                return;
            }

            if (!Array.isArray(devices) || devices.length === 0) {
                container.innerHTML = `<div class="sa-table-loading">${escapeHtml(this.config.i18n.noData)}</div>`;
                return;
            }

            const itemsHtml = devices.slice(0, 5).map((device) => {
                const labelLower = (device.label || '').toLowerCase();
                let iconClass = 'fa-desktop';

                if (labelLower.includes('mobile')) {
                    iconClass = 'fa-mobile-alt';
                } else if (labelLower.includes('tablet')) {
                    iconClass = 'fa-tablet-alt';
                }

                return `
                    <div class="sa-list-item">
                        <div class="sa-li-icon"><i class="fas ${iconClass}"></i></div>
                        <div class="sa-li-content">
                            <div class="sa-li-title">${escapeHtml(device.label)}</div>
                            <div class="sa-li-subtitle">${escapeHtml(this.config.i18n.sessionsByDevice)}</div>
                        </div>
                        <div class="sa-li-val">${Number(device.total).toLocaleString()}</div>
                    </div>
                `;
            }).join('');

            container.innerHTML = itemsHtml;
        },

        /**
         * Render IP breakdown table rows.
         * Single Responsibility: IP table DOM rendering.
         * @param {Array} ips
         */
        renderIpTable(ips) {
            const tableBody = this.elements.ipTableBody;
            if (!tableBody) {
                return;
            }

            if (!Array.isArray(ips) || ips.length === 0) {
                tableBody.innerHTML = `<tr><td colspan="2" class="sa-table-loading">${escapeHtml(this.config.i18n.noData)}</td></tr>`;
                return;
            }

            const rowsHtml = ips.slice(0, 5).map((ip) => {
                return `
                    <tr>
                        <td>
                            <div class="sa-ip-cell">
                                <div class="sa-ip-avatar"><i class="fas fa-network-wired"></i></div>
                                <span title="${escapeHtml(ip.label)}">${escapeHtml(ip.label)}</span>
                            </div>
                        </td>
                        <td class="sa-ip-count">${Number(ip.total).toLocaleString()}</td>
                    </tr>
                `;
            }).join('');

            tableBody.innerHTML = rowsHtml;
        },

        /**
         * Toggle loading states for smooth UX feedback.
         * @param {boolean} isLoading
         */
        setLoadingState(isLoading) {
            const { root, applyBtn } = this.elements;

            if (root) {
                root.classList.toggle('sa-is-loading', isLoading);
            }

            if (applyBtn) {
                applyBtn.disabled = isLoading;
                applyBtn.classList.toggle('sa-btn-loading', isLoading);

                if (isLoading) {
                    applyBtn.innerHTML = `<i class="fas fa-spinner fa-spin"></i> <span>${escapeHtml(this.config.i18n.loading)}</span>`;
                } else {
                    applyBtn.textContent = this.config.i18n.apply;
                }
            }
        },

        /**
         * Teardown chart instances to prevent memory leaks during page navigation.
         */
        destroyCharts() {
            if (this.charts.timeline) {
                try { this.charts.timeline.destroy(); } catch (e) {}
                this.charts.timeline = null;
            }
            if (this.charts.source) {
                try { this.charts.source.destroy(); } catch (e) {}
                this.charts.source = null;
            }
            if (this.elements.timelineChartContainer) {
                this.elements.timelineChartContainer.innerHTML = '';
            }
            if (this.elements.sourceChartContainer) {
                this.elements.sourceChartContainer.innerHTML = '';
            }
        }
    };

    // Expose module globally for debugging or external triggers
    window.ShortlinkAnalytics = ShortlinkAnalytics;

    // Handle lifecycle events (DOMContentLoaded and Hotwire/Turbo)
    if (!window._shortlinkAnalyticsListenersBound) {
        window._shortlinkAnalyticsListenersBound = true;
        document.addEventListener('DOMContentLoaded', () => ShortlinkAnalytics.init());
        document.addEventListener('turbo:load', () => ShortlinkAnalytics.init());
        document.addEventListener('turbo:before-cache', () => ShortlinkAnalytics.destroyCharts());
    }

    if (document.readyState !== 'loading') {
        ShortlinkAnalytics.init();
    }

})(window, document);
