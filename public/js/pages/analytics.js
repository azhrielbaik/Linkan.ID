/**
 * ============================================================================
 * LINKAN.ID - ANALYTICS PAGE MODULAR SCRIPT (analytics.js)
 * Clean, structured, and modular ApexCharts and UI controller.
 * ============================================================================
 */

(function () {
    'use strict';

    /**
     * Namespace AnalyticsPage
     */
    const AnalyticsPage = {
        state: null,
        activeTab: 'overview',
        charts: {},
        instances: {},
        filters: {},
        tables: {},
        ui: {},
        helpers: {}
    };

    /**
     * Deep merge helper for ApexCharts option objects
     * @param {Object} target
     * @param {Object} source
     * @returns {Object}
     */
    function deepMerge(target, source) {
        const output = Object.assign({}, target);
        if (target && source && typeof target === 'object' && typeof source === 'object') {
            Object.keys(source).forEach(key => {
                if (source[key] && typeof source[key] === 'object' && !Array.isArray(source[key])) {
                    if (!(key in target)) {
                        Object.assign(output, { [key]: source[key] });
                    } else {
                        output[key] = deepMerge(target[key], source[key]);
                    }
                } else {
                    Object.assign(output, { [key]: source[key] });
                }
            });
        }
        return output;
    }

    /**
     * Get active design theme tokens (light/dark)
     * @returns {Object}
     */
    AnalyticsPage.ui.getThemeTokens = function () {
        const isDark = document.documentElement.classList.contains('dark');
        return {
            isDark,
            labelColor: isDark ? '#94a3b8' : '#64748b',
            gridColor: isDark ? '#2a3241' : '#f1f5f9',
            tooltipTheme: isDark ? 'dark' : 'light'
        };
    };

    /**
     * Format currency as full Indonesian Rupiah
     * @param {number|string} val
     * @returns {string}
     */
    AnalyticsPage.helpers.formatIDR = function (val) {
        return 'Rp ' + Number(val).toLocaleString('id-ID');
    };

    /**
     * Format compact currency values (rb, jt, M)
     * @param {number|string} val
     * @returns {string}
     */
    AnalyticsPage.helpers.formatCompactIDR = function (val) {
        val = Number(val) || 0;
        if (val >= 1000000000) return (val / 1000000000).toFixed(1) + 'M';
        if (val >= 1000000) return (val / 1000000).toFixed(1) + 'jt';
        if (val >= 1000) return (val / 1000).toFixed(0) + 'rb';
        return val.toString();
    };

    /**
     * Generate smooth SVG path for Sparklines
     * @param {Array<number>} series
     * @param {string} strokeColor
     * @returns {string}
     */
    AnalyticsPage.helpers.createSparklineSvg = function (series, strokeColor = '#ED842C') {
        if (!series || series.length < 2) {
            return `<svg viewBox="0 0 100 28" class="analytics-sparkline-svg" fill="none"><path d="M 0,14 L 100,14" stroke="${strokeColor}" stroke-width="2" stroke-linecap="round" stroke-dasharray="3 3"/></svg>`;
        }
        const width = 100, height = 28;
        const min = Math.min(...series);
        const max = Math.max(...series);
        const range = (max - min) || 1;
        const step = width / (series.length - 1);

        const points = series.map((val, idx) => {
            const x = (idx * step).toFixed(1);
            const y = (height - 3 - ((val - min) / range) * (height - 6)).toFixed(1);
            return `${x},${y}`;
        });

        const d = `M ${points[0]} ` + points.slice(1).map(pt => `L ${pt}`).join(' ');

        return `
            <svg viewBox="0 0 ${width} ${height}" class="analytics-sparkline-svg" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="${d}" stroke="${strokeColor}" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        `;
    };

    /**
     * Helper to instantiate an ApexChart with standardized options
     * @param {string|HTMLElement} container
     * @param {Object} customOptions
     * @returns {ApexCharts|null}
     */
    AnalyticsPage.charts.buildApexChart = function (container, customOptions = {}) {
        const elem = typeof container === 'string' ? document.querySelector(container) : container;
        if (!elem) return null;
        elem.innerHTML = '';
        const theme = AnalyticsPage.ui.getThemeTokens();

        const baseOptions = {
            chart: {
                toolbar: { show: false },
                parentHeightOffset: 0
            },
            grid: {
                borderColor: theme.gridColor
            },
            tooltip: {
                theme: theme.tooltipTheme
            }
        };

        const merged = deepMerge(baseOptions, customOptions);
        const chart = new ApexCharts(elem, merged);
        chart.render();
        return chart;
    };

    /**
     * Render all Sparklines in the 2x2 Hero KPIs
     * @param {Object} data
     */
    AnalyticsPage.charts.renderSparklines = function (data) {
        if (!data) return;
        const createSvg = AnalyticsPage.helpers.createSparklineSvg;

        const viewsBox = document.getElementById('sparklineViews');
        if (viewsBox) {
            viewsBox.innerHTML = createSvg(data.views || (data.timeline && data.timeline.views), '#06b6d4');
        }

        const clicksBox = document.getElementById('sparklineClicks');
        if (clicksBox) {
            clicksBox.innerHTML = createSvg(data.clicks || (data.timeline && data.timeline.clicks), '#ED842C');
        }

        const salesBox = document.getElementById('sparklineSales');
        if (salesBox) {
            salesBox.innerHTML = createSvg(data.sales || (data.timeline && data.timeline.sales), '#10b981');
        }

        const transBox = document.getElementById('sparklineTrans');
        if (transBox) {
            const transSeries = (data.transaction_statuses && data.transaction_statuses.daily_success)
                ? data.transaction_statuses.daily_success
                : (data.sales || (data.timeline && data.timeline.sales));
            transBox.innerHTML = createSvg(transSeries, '#8b5cf6');
        }
    };

    /**
     * Destroy all existing ApexChart instances
     */
    AnalyticsPage.charts.destroyAll = function () {
        Object.keys(AnalyticsPage.instances).forEach(k => {
            if (AnalyticsPage.instances[k]) {
                AnalyticsPage.instances[k].destroy();
                AnalyticsPage.instances[k] = null;
            }
        });
    };

    /**
     * Render all ApexCharts across tabs
     * @param {Object} data
     */
    AnalyticsPage.charts.renderAll = function (data) {
        if (!data) return;
        const theme = AnalyticsPage.ui.getThemeTokens();
        const build = AnalyticsPage.charts.buildApexChart;
        AnalyticsPage.charts.destroyAll();

        // 1. Semi-Circular Radial Gauge (Store Conversion Health)
        const gaugeElem = document.querySelector("#gaugeConversionHealth");
        if (gaugeElem && data.scorecards && data.scorecards.conversion_rate) {
            const rawConv = parseFloat(data.scorecards.conversion_rate.value) || 0;
            const gaugePct = Math.min(100, Math.max(12, Math.round(rawConv * 20)));

            AnalyticsPage.instances.gauge = build(gaugeElem, {
                series: [gaugePct],
                chart: {
                    type: 'radialBar',
                    height: 180,
                    sparkline: { enabled: true }
                },
                plotOptions: {
                    radialBar: {
                        startAngle: -95,
                        endAngle: 95,
                        hollow: { size: '68%' },
                        track: {
                            background: theme.isDark ? '#242b3b' : '#f1f5f9',
                            strokeWidth: '95%',
                            margin: 4
                        },
                        dataLabels: {
                            name: { show: false },
                            value: {
                                offsetY: -8,
                                fontSize: '24px',
                                fontWeight: '800',
                                color: theme.isDark ? '#ffffff' : '#0f172a',
                                formatter: () => `${data.scorecards.conversion_rate.formatted}`
                            }
                        }
                    }
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'dark',
                        type: 'horizontal',
                        gradientToColors: ['#06b6d4'],
                        stops: [0, 100]
                    }
                },
                colors: ['#ED842C'],
                stroke: { lineCap: 'round' }
            });
        }

        // 2. Traffic Spline (Views vs Clicks)
        const trafficElem = document.querySelector("#chartOverviewTraffic");
        if (trafficElem) {
            AnalyticsPage.instances.traffic = build(trafficElem, {
                series: [
                    { name: 'Views', data: data.views || (data.timeline && data.timeline.views) },
                    { name: 'Clicks', data: data.clicks || (data.timeline && data.timeline.clicks) }
                ],
                chart: { type: 'area', height: '100%' },
                colors: ['#ED842C', '#06b6d4'],
                fill: {
                    type: 'gradient',
                    gradient: { shadeIntensity: 1, opacityFrom: 0.25, opacityTo: 0.02, stops: [0, 95, 100] }
                },
                stroke: { curve: 'smooth', width: 2.8 },
                dataLabels: { enabled: false },
                xaxis: {
                    categories: data.labels || (data.timeline && data.timeline.labels),
                    labels: { style: { colors: theme.labelColor, fontSize: '11px' } },
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: {
                    labels: {
                        formatter: v => Math.floor(v),
                        style: { colors: theme.labelColor }
                    }
                },
                legend: { show: false }
            });
        }

        // 3. Revenue Area Chart (Omset)
        const revElem = document.querySelector("#chartOverviewRevenue");
        if (revElem) {
            AnalyticsPage.instances.revenue = build(revElem, {
                series: [{ name: 'Omset Penjualan', data: data.sales || (data.timeline && data.timeline.sales) }],
                chart: { type: 'area', height: '100%' },
                colors: ['#10B981'],
                fill: {
                    type: 'gradient',
                    gradient: { shadeIntensity: 1, opacityFrom: 0.32, opacityTo: 0.03, stops: [0, 90, 100] }
                },
                stroke: { curve: 'smooth', width: 2.8 },
                dataLabels: { enabled: false },
                xaxis: {
                    categories: data.labels || (data.timeline && data.timeline.labels),
                    labels: { style: { colors: theme.labelColor, fontSize: '11px' } },
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: {
                    labels: {
                        formatter: v => AnalyticsPage.helpers.formatCompactIDR(v),
                        style: { colors: theme.labelColor }
                    }
                },
                tooltip: {
                    y: { formatter: v => AnalyticsPage.helpers.formatIDR(v) }
                }
            });
        }

        // 4. Shortlinks Top 5 Clicks
        const slTopElem = document.querySelector("#chartShortlinksTop");
        if (slTopElem && data.shortlinks) {
            AnalyticsPage.instances.shortlinksTop = build(slTopElem, {
                series: [{ name: 'Total Klik', data: data.shortlinks.top_series.length ? data.shortlinks.top_series : [0] }],
                chart: { type: 'bar', height: '100%' },
                colors: ['#ED842C'],
                plotOptions: { bar: { horizontal: true, borderRadius: 6, barHeight: '48%' } },
                xaxis: {
                    categories: data.shortlinks.top_labels.length ? data.shortlinks.top_labels : ['Belum ada'],
                    labels: { style: { colors: theme.labelColor } }
                },
                yaxis: { labels: { style: { colors: theme.labelColor } } }
            });
        }

        // 5. Shortlinks Peak Hours
        const slHoursElem = document.querySelector("#chartShortlinksPeakHours");
        if (slHoursElem && data.audience) {
            AnalyticsPage.instances.shortlinksHours = build(slHoursElem, {
                series: [{ name: 'Klik', data: data.audience.peak_hours_series }],
                chart: { type: 'bar', height: '100%' },
                colors: ['#06b6d4'],
                plotOptions: { bar: { borderRadius: 3, columnWidth: '55%' } },
                xaxis: {
                    categories: data.audience.peak_hours_labels,
                    labels: { rotate: -45, style: { colors: theme.labelColor, fontSize: '10px' } }
                },
                yaxis: { labels: { style: { colors: theme.labelColor } } }
            });
        }

        // 6. Shortlinks Source Donut
        const slSourceElem = document.querySelector("#chartShortlinksSource");
        if (slSourceElem && data.audience) {
            AnalyticsPage.instances.shortlinksSource = build(slSourceElem, {
                series: data.audience.source_series.length ? data.audience.source_series : [1],
                labels: data.audience.source_labels.length ? data.audience.source_labels : ['Direct'],
                chart: { type: 'donut', height: '100%' },
                colors: ['#ED842C', '#06b6d4', '#10B981', '#F59E0B', '#8B5CF6'],
                legend: { position: 'bottom', labels: { colors: theme.labelColor } }
            });
        }

        // 7. Shortlinks Device Donut
        const slDeviceElem = document.querySelector("#chartShortlinksDevice");
        if (slDeviceElem && data.audience) {
            AnalyticsPage.instances.shortlinksDevice = build(slDeviceElem, {
                series: data.audience.device_series.length ? data.audience.device_series : [1],
                labels: data.audience.device_labels.length ? data.audience.device_labels : ['Mobile'],
                chart: { type: 'donut', height: '100%' },
                colors: ['#10B981', '#06b6d4', '#F59E0B'],
                legend: { position: 'bottom', labels: { colors: theme.labelColor } }
            });
        }

        // 8. Products Revenue Bar
        const prodRevElem = document.querySelector("#chartProductsRevenue");
        if (prodRevElem && data.products) {
            AnalyticsPage.instances.productsRevenue = build(prodRevElem, {
                series: [{ name: 'Revenue', data: data.products.top_revenue_series.length ? data.products.top_revenue_series : [0] }],
                chart: { type: 'bar', height: '100%' },
                colors: ['#ED842C'],
                plotOptions: { bar: { horizontal: true, borderRadius: 6, barHeight: '48%' } },
                xaxis: {
                    categories: data.products.top_revenue_labels.length ? data.products.top_revenue_labels : ['Belum ada'],
                    labels: { formatter: v => AnalyticsPage.helpers.formatCompactIDR(v), style: { colors: theme.labelColor } }
                },
                yaxis: { labels: { style: { colors: theme.labelColor } } },
                tooltip: { y: { formatter: v => AnalyticsPage.helpers.formatIDR(v) } }
            });
        }

        // 9. Products Qty Bar
        const prodQtyElem = document.querySelector("#chartProductsQty");
        if (prodQtyElem && data.products) {
            AnalyticsPage.instances.productsQty = build(prodQtyElem, {
                series: [{ name: 'Terjual (Qty)', data: data.products.top_qty_series.length ? data.products.top_qty_series : [0] }],
                chart: { type: 'bar', height: '100%' },
                colors: ['#10B981'],
                plotOptions: { bar: { horizontal: true, borderRadius: 6, barHeight: '48%' } },
                xaxis: {
                    categories: data.products.top_qty_labels.length ? data.products.top_qty_labels : ['Belum ada'],
                    labels: { style: { colors: theme.labelColor } }
                },
                yaxis: { labels: { style: { colors: theme.labelColor } } }
            });
        }

        // 10. Audience Device Donut
        const audDevElem = document.querySelector("#chartAudienceDevice");
        if (audDevElem && data.audience) {
            AnalyticsPage.instances.audienceDevice = build(audDevElem, {
                series: data.audience.device_series.length ? data.audience.device_series : [1],
                labels: data.audience.device_labels.length ? data.audience.device_labels : ['Mobile'],
                chart: { type: 'donut', height: '100%' },
                colors: ['#10B981', '#06b6d4', '#F59E0B'],
                legend: { position: 'bottom', labels: { colors: theme.labelColor } }
            });
        }

        // 11. Audience Source Donut
        const audSrcElem = document.querySelector("#chartAudienceSource");
        if (audSrcElem && data.audience) {
            AnalyticsPage.instances.audienceSource = build(audSrcElem, {
                series: data.audience.source_series.length ? data.audience.source_series : [1],
                labels: data.audience.source_labels.length ? data.audience.source_labels : ['Direct'],
                chart: { type: 'donut', height: '100%' },
                colors: ['#ED842C', '#06b6d4', '#10B981', '#F59E0B', '#8B5CF6'],
                legend: { position: 'bottom', labels: { colors: theme.labelColor } }
            });
        }

        // 12. Audience Cities Bar
        const audCityElem = document.querySelector("#chartAudienceCity");
        if (audCityElem && data.audience) {
            AnalyticsPage.instances.audienceCity = build(audCityElem, {
                series: [{ name: 'Pengunjung', data: data.audience.cities_series.length ? data.audience.cities_series : [0] }],
                chart: { type: 'bar', height: '100%' },
                colors: ['#06b6d4'],
                plotOptions: { bar: { horizontal: true, borderRadius: 5, barHeight: '50%' } },
                xaxis: {
                    categories: data.audience.cities_labels.length ? data.audience.cities_labels : ['Belum ada'],
                    labels: { style: { colors: theme.labelColor } }
                },
                yaxis: { labels: { style: { colors: theme.labelColor } } }
            });
        }

        // 13. Audience Countries Bar
        const audCountryElem = document.querySelector("#chartAudienceCountry");
        if (audCountryElem && data.audience) {
            AnalyticsPage.instances.audienceCountry = build(audCountryElem, {
                series: [{ name: 'Pengunjung', data: data.audience.countries_series.length ? data.audience.countries_series : [0] }],
                chart: { type: 'bar', height: '100%' },
                colors: ['#10B981'],
                plotOptions: { bar: { horizontal: true, borderRadius: 5, barHeight: '50%' } },
                xaxis: {
                    categories: data.audience.countries_labels.length ? data.audience.countries_labels : ['Belum ada'],
                    labels: { style: { colors: theme.labelColor } }
                },
                yaxis: { labels: { style: { colors: theme.labelColor } } }
            });
        }

        // 14. Audience Peak Hours Bar
        const audHoursElem = document.querySelector("#chartAudienceHours");
        if (audHoursElem && data.audience) {
            AnalyticsPage.instances.audienceHours = build(audHoursElem, {
                series: [{ name: 'Aktivitas Klik', data: data.audience.peak_hours_series }],
                chart: { type: 'bar', height: '100%' },
                colors: ['#ED842C'],
                plotOptions: { bar: { borderRadius: 4, columnWidth: '55%' } },
                xaxis: {
                    categories: data.audience.peak_hours_labels,
                    labels: { rotate: -45, style: { colors: theme.labelColor, fontSize: '10px' } }
                },
                yaxis: { labels: { style: { colors: theme.labelColor } } }
            });
        }

        // 15. Audience Peak Days Bar
        const audDaysElem = document.querySelector("#chartAudienceDays");
        if (audDaysElem && data.audience) {
            AnalyticsPage.instances.audienceDays = build(audDaysElem, {
                series: [{ name: 'Aktivitas Klik', data: data.audience.peak_days_series }],
                chart: { type: 'bar', height: '100%' },
                colors: ['#8B5CF6'],
                plotOptions: { bar: { borderRadius: 6, columnWidth: '45%' } },
                xaxis: {
                    categories: data.audience.peak_days_labels,
                    labels: { style: { colors: theme.labelColor } }
                },
                yaxis: { labels: { style: { colors: theme.labelColor } } }
            });
        }
    };

    /**
     * Switch tab with dynamic re-render & resize trigger
     * @param {string} tabName
     * @param {HTMLElement} btnElem
     */
    AnalyticsPage.ui.switchTab = function (tabName, btnElem) {
        AnalyticsPage.activeTab = tabName;

        document.querySelectorAll('.analytics-nav-pill-btn').forEach(btn => btn.classList.remove('active'));
        if (btnElem) {
            btnElem.classList.add('active');
        }

        document.querySelectorAll('.sa-tab-content').forEach(content => {
            content.classList.remove('active');
            content.style.display = 'none';
        });

        const target = document.getElementById(`tab-${tabName}`);
        if (target) {
            target.classList.add('active');
            target.style.display = (tabName === 'overview') ? 'flex' : 'block';
        }

        setTimeout(() => {
            window.dispatchEvent(new Event('resize'));
        }, 60);
    };

    /**
     * Date Range Picker Controller (Matching Reference Design)
     */
    AnalyticsPage.datepicker = {
        isOpen: false,
        currentMonthLeft: null,
        selectedStart: null,
        selectedEnd: null,
        hoverDate: null,
        isSelectingEnd: false,
        activePreset: '30days',

        monthNames: [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ],

        monthNamesShort: [
            'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
            'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
        ],

        pad: function (n) {
            return n < 10 ? '0' + n : n;
        },

        toYMD: function (d) {
            if (!d) return '';
            return d.getFullYear() + '-' + this.pad(d.getMonth() + 1) + '-' + this.pad(d.getDate());
        },

        parseYMD: function (s) {
            if (!s) return null;
            const parts = s.split('-');
            if (parts.length < 3) return null;
            return new Date(parseInt(parts[0], 10), parseInt(parts[1], 10) - 1, parseInt(parts[2], 10));
        },

        toDisplayUS: function (d) {
            if (!d) return '--/--/----';
            return this.pad(d.getMonth() + 1) + '/' + this.pad(d.getDate()) + '/' + d.getFullYear();
        },

        toDisplayPill: function (d) {
            if (!d) return '';
            return this.monthNamesShort[d.getMonth()] + ' ' + d.getDate() + ', ' + d.getFullYear();
        },

        init: function () {
            const startInput = document.getElementById('inputCustomStart');
            const endInput = document.getElementById('inputCustomEnd');
            const presetInput = document.getElementById('heroPresetSelect');

            const startVal = startInput ? startInput.value : '';
            const endVal = endInput ? endInput.value : '';
            this.activePreset = presetInput ? presetInput.value : '30days';

            this.selectedStart = this.parseYMD(startVal) || new Date();
            this.selectedEnd = this.parseYMD(endVal) || new Date();

            this.currentMonthLeft = new Date(this.selectedStart.getFullYear(), this.selectedStart.getMonth(), 1);

            this.updateInputBoxes();
            this.updateTriggerPill();

            const self = this;
            document.removeEventListener('click', self._outsideClick);
            self._outsideClick = function (e) {
                const anchor = document.getElementById('datePickerAnchor');
                const popover = document.getElementById('customDateExpandableBar');
                if (popover && popover.style.display !== 'none') {
                    if (anchor && !anchor.contains(e.target)) {
                        self.close();
                    }
                }
            };
            document.addEventListener('click', self._outsideClick);
        },

        toggle: function () {
            if (this.isOpen) {
                this.close();
            } else {
                this.open();
            }
        },

        open: function () {
            const popover = document.getElementById('customDateExpandableBar');
            const trigger = document.getElementById('btnDateRangeTrigger');
            const chevron = document.getElementById('dateTriggerChevron');
            const anchor = document.getElementById('datePickerAnchor');

            if (anchor) {
                anchor.style.position = 'relative';
            }

            if (popover) {
                popover.style.display = 'block';
                this.isOpen = true;
                if (trigger) trigger.classList.add('active');
                if (chevron) chevron.style.transform = 'rotate(180deg)';

                if (this.selectedStart) {
                    this.currentMonthLeft = new Date(this.selectedStart.getFullYear(), this.selectedStart.getMonth(), 1);
                }
                this.renderCalendars();
                this.updateSidebarPresetUI();

                // Dynamic boundary check on desktop to stay directly under button
                if (window.innerWidth > 768) {
                    popover.style.top = 'calc(100% + 8px)';
                    popover.style.right = '0';
                    popover.style.left = 'auto';

                    const rect = popover.getBoundingClientRect();
                    if (rect.left < 16) {
                        const shift = 16 - rect.left;
                        popover.style.right = (-shift) + 'px';
                    }
                } else {
                    popover.style.top = '';
                    popover.style.right = '';
                    popover.style.left = '';
                }
            }
        },

        close: function () {
            const popover = document.getElementById('customDateExpandableBar');
            const trigger = document.getElementById('btnDateRangeTrigger');
            const chevron = document.getElementById('dateTriggerChevron');
            if (popover) {
                popover.style.display = 'none';
                this.isOpen = false;
                this.isSelectingEnd = false;
                this.hoverDate = null;
                if (trigger) trigger.classList.remove('active');
                if (chevron) chevron.style.transform = 'rotate(0deg)';
            }
        },

        prevMonth: function () {
            this.currentMonthLeft = new Date(this.currentMonthLeft.getFullYear(), this.currentMonthLeft.getMonth() - 1, 1);
            this.renderCalendars();
        },

        nextMonth: function () {
            this.currentMonthLeft = new Date(this.currentMonthLeft.getFullYear(), this.currentMonthLeft.getMonth() + 1, 1);
            this.renderCalendars();
        },

        selectPreset: function (preset) {
            this.activePreset = preset;
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            let start = new Date(today);
            let end = new Date(today);

            switch (preset) {
                case 'today':
                    break;
                case 'yesterday':
                    start.setDate(today.getDate() - 1);
                    end.setDate(today.getDate() - 1);
                    break;
                case 'this_week': {
                    const dayOfWeek = (today.getDay() + 6) % 7; // Monday = 0
                    start.setDate(today.getDate() - dayOfWeek);
                    break;
                }
                case 'last_week':
                case '7days': {
                    const dayIdx = (today.getDay() + 6) % 7;
                    start = new Date(today.getFullYear(), today.getMonth(), today.getDate() - dayIdx - 7);
                    end = new Date(today.getFullYear(), today.getMonth(), today.getDate() - dayIdx - 1);
                    break;
                }
                case '30days':
                    start.setDate(today.getDate() - 29);
                    break;
                case 'month':
                    start = new Date(today.getFullYear(), today.getMonth(), 1);
                    break;
                case 'last_month':
                    start = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                    end = new Date(today.getFullYear(), today.getMonth(), 0);
                    break;
                case '90days':
                    start.setDate(today.getDate() - 89);
                    break;
                case 'this_year':
                    start = new Date(today.getFullYear(), 0, 1);
                    break;
                case 'last_year':
                    start = new Date(today.getFullYear() - 1, 0, 1);
                    end = new Date(today.getFullYear() - 1, 11, 31);
                    break;
                case 'all_time':
                    start = new Date(today.getFullYear() - 3, 0, 1);
                    break;
                default:
                    start.setDate(today.getDate() - 29);
                    break;
            }

            this.selectedStart = start;
            this.selectedEnd = end;
            this.isSelectingEnd = false;
            this.hoverDate = null;

            this.currentMonthLeft = new Date(start.getFullYear(), start.getMonth(), 1);

            this.updateInputBoxes();
            this.updateSidebarPresetUI();
            this.renderCalendars();
        },

        selectDate: function (dateStr) {
            const clickedDate = this.parseYMD(dateStr);
            if (!clickedDate) return;

            if (!this.isSelectingEnd || (this.selectedStart && this.selectedEnd)) {
                this.selectedStart = clickedDate;
                this.selectedEnd = null;
                this.isSelectingEnd = true;
                this.activePreset = 'custom';
            } else {
                if (clickedDate.getTime() < this.selectedStart.getTime()) {
                    this.selectedEnd = this.selectedStart;
                    this.selectedStart = clickedDate;
                } else {
                    this.selectedEnd = clickedDate;
                }
                this.isSelectingEnd = false;
                this.hoverDate = null;
                this.activePreset = 'custom';
            }

            this.updateInputBoxes();
            this.updateSidebarPresetUI();
            this.renderCalendars();
        },

        onDateHover: function (dateStr) {
            if (!this.isSelectingEnd || !this.selectedStart) return;
            this.hoverDate = this.parseYMD(dateStr);
            this.renderCalendars();
        },

        updateInputBoxes: function () {
            const startStr = this.toDisplayUS(this.selectedStart);
            const endStr = this.toDisplayUS(this.selectedEnd || this.selectedStart);

            document.querySelectorAll('.dp-val-start').forEach(el => el.innerText = startStr);
            document.querySelectorAll('.dp-val-end').forEach(el => el.innerText = endStr);

            const startHidden = document.getElementById('inputCustomStart');
            const endHidden = document.getElementById('inputCustomEnd');
            const presetHidden = document.getElementById('heroPresetSelect');

            if (startHidden) startHidden.value = this.toYMD(this.selectedStart);
            if (endHidden) endHidden.value = this.toYMD(this.selectedEnd || this.selectedStart);
            if (presetHidden) presetHidden.value = this.activePreset;
        },

        updateTriggerPill: function () {
            const label = document.getElementById('dateTriggerLabel');
            if (label && this.selectedStart) {
                const sStr = this.toDisplayPill(this.selectedStart);
                const eStr = this.toDisplayPill(this.selectedEnd || this.selectedStart);
                label.innerText = sStr + ' – ' + eStr;
            }
        },

        updateSidebarPresetUI: function () {
            const buttons = document.querySelectorAll('.analytics-dp-preset-btn');
            buttons.forEach(btn => {
                const btnPreset = btn.getAttribute('data-preset');
                if (btnPreset === this.activePreset || (this.activePreset === '7days' && btnPreset === 'last_week')) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });
        },

        renderCalendars: function () {
            if (!this.currentMonthLeft) return;

            const monthLeft = this.currentMonthLeft;
            const monthRight = new Date(monthLeft.getFullYear(), monthLeft.getMonth() + 1, 1);

            const titleLeft = document.getElementById('dpMonthTitleLeft');
            const titleRight = document.getElementById('dpMonthTitleRight');
            if (titleLeft) {
                titleLeft.innerText = this.monthNames[monthLeft.getMonth()] + ' ' + monthLeft.getFullYear();
            }
            if (titleRight) {
                titleRight.innerText = this.monthNames[monthRight.getMonth()] + ' ' + monthRight.getFullYear();
            }

            const gridLeft = document.getElementById('dpDaysGridLeft');
            const gridRight = document.getElementById('dpDaysGridRight');
            if (gridLeft) gridLeft.innerHTML = this.buildMonthGridHTML(monthLeft);
            if (gridRight) gridRight.innerHTML = this.buildMonthGridHTML(monthRight);
        },

        buildMonthGridHTML: function (monthDate) {
            const year = monthDate.getFullYear();
            const month = monthDate.getMonth();

            const firstDay = new Date(year, month, 1);
            const firstDayWeek = (firstDay.getDay() + 6) % 7;
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const daysInPrevMonth = new Date(year, month, 0).getDate();

            const totalCells = (firstDayWeek + daysInMonth <= 35) ? 35 : 42;
            const nextDays = totalCells - (firstDayWeek + daysInMonth);

            const today = new Date();
            today.setHours(0, 0, 0, 0);
            const todayMs = today.getTime();

            const startMs = this.selectedStart ? this.selectedStart.getTime() : null;
            const effectiveEnd = this.selectedEnd || (this.isSelectingEnd && this.hoverDate ? this.hoverDate : null);
            const endMs = effectiveEnd ? effectiveEnd.getTime() : null;

            const rangeMin = (startMs && endMs) ? Math.min(startMs, endMs) : startMs;
            const rangeMax = (startMs && endMs) ? Math.max(startMs, endMs) : startMs;

            let html = '';

            // Previous Month Days
            for (let i = firstDayWeek - 1; i >= 0; i--) {
                const dayNum = daysInPrevMonth - i;
                html += `<div class="analytics-dp-day out-month">${dayNum}</div>`;
            }

            // Current Month Days
            for (let d = 1; d <= daysInMonth; d++) {
                const cellDate = new Date(year, month, d);
                cellDate.setHours(0, 0, 0, 0);
                const cellMs = cellDate.getTime();
                const cellYMD = this.toYMD(cellDate);

                let classes = ['analytics-dp-day'];

                if (cellMs === todayMs) {
                    classes.push('is-today');
                }

                if (startMs && cellMs === rangeMin) {
                    classes.push('selected-start');
                    if (endMs && endMs > rangeMin) classes.push('range-start-bound');
                } else if (endMs && cellMs === rangeMax) {
                    classes.push('selected-end');
                    classes.push('range-end-bound');
                } else if (rangeMin && rangeMax && cellMs > rangeMin && cellMs < rangeMax) {
                    classes.push('in-range');
                }

                html += `<div class="${classes.join(' ')}" 
                             data-date="${cellYMD}" 
                             onclick="AnalyticsPage.datepicker.selectDate('${cellYMD}')"
                             onmouseenter="AnalyticsPage.datepicker.onDateHover('${cellYMD}')">${d}</div>`;
            }

            // Next Month Days
            for (let n = 1; n <= nextDays; n++) {
                html += `<div class="analytics-dp-day out-month">${n}</div>`;
            }

            return html;
        },

        apply: function () {
            if (!this.selectedStart) {
                alert('Pilih rentang tanggal.');
                return;
            }

            const finalStart = this.toYMD(this.selectedStart);
            const finalEnd = this.toYMD(this.selectedEnd || this.selectedStart);

            this.updateTriggerPill();
            this.close();

            AnalyticsPage.filters.fetchData({
                preset: this.activePreset,
                start_date: finalStart,
                end_date: finalEnd
            });
        }
    };

    /**
     * Backward-compatible toggle custom date range expansion bar
     */
    AnalyticsPage.ui.toggleCustomDateBar = function () {
        AnalyticsPage.datepicker.toggle();
    };

    /**
     * Handle date preset select change
     * @param {string} val
     */
    AnalyticsPage.filters.onPresetChange = function (val) {
        AnalyticsPage.datepicker.selectPreset(val);
        AnalyticsPage.datepicker.apply();
    };

    /**
     * Apply manual custom date filter
     */
    AnalyticsPage.filters.applyManualCustomDate = function () {
        AnalyticsPage.datepicker.apply();
    };

    /**
     * Fetch analytics data via AJAX
     * @param {Object} params
     */
    AnalyticsPage.filters.fetchData = function (params = {}) {
        const configElem = document.getElementById('analytics-config');
        const endpoint = configElem ? configElem.getAttribute('data-chart-url') : '/admin/statistics/chart-data';

        const url = new URL(endpoint, window.location.origin);
        Object.keys(params).forEach(k => url.searchParams.append(k, params[k]));

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => {
            AnalyticsPage.state = data;
            AnalyticsPage.ui.updateUI(data);
        })
        .catch(e => console.error('Fetch analytics error:', e));
    };

    /**
     * Update entire UI scorecard values and re-render charts
     * @param {Object} data
     */
    AnalyticsPage.ui.updateUI = function (data) {
        if (!data) return;

        // Period Summary
        const periodLabel = document.getElementById('labelPeriodSummary');
        if (periodLabel) {
            periodLabel.innerText = `${data.start_date} - ${data.end_date}`;
        }

        // Scorecard KPIs
        const sc = data.scorecards;
        if (sc) {
            const updateKpi = (valId, deltaId, metric) => {
                const val = document.getElementById(valId);
                const delta = document.getElementById(deltaId);
                if (val && metric) val.innerText = metric.formatted;
                if (delta && metric) {
                    delta.className = `analytics-kpi-delta ${metric.trend_class || (metric.trend === 'up' ? 'delta-up' : (metric.trend === 'down' ? 'delta-down' : 'delta-neutral'))}`;
                    delta.innerText = metric.signed_change;
                }
            };

            updateKpi('heroValViews', 'heroDeltaViews', sc.views);
            updateKpi('heroValClicks', 'heroDeltaClicks', sc.clicks);
            updateKpi('heroValSales', 'heroDeltaSales', sc.sales);
            updateKpi('heroValTrans', 'heroDeltaTrans', sc.transactions);

            const aovVal = document.getElementById('chipAovVal');
            if (aovVal && sc.aov) aovVal.innerText = sc.aov.formatted;

            const balVal = document.getElementById('chipBalanceVal');
            if (balVal && sc.balance) balVal.innerText = sc.balance.formatted;

            const prodVal = document.getElementById('chipProdVal');
            if (prodVal && sc.products) prodVal.innerText = sc.products.active;

            const convGauge = document.getElementById('gaugeValCurrent');
            if (convGauge && sc.conversion_rate) convGauge.innerText = sc.conversion_rate.formatted;

            const salesStat = document.getElementById('statsSalesVal');
            if (salesStat && sc.sales) salesStat.innerText = sc.sales.formatted;

            const convStat = document.getElementById('statsConvVal');
            if (convStat && sc.conversion_rate) convStat.innerText = sc.conversion_rate.formatted;
        }

        // Re-render sparklines & charts
        AnalyticsPage.charts.renderSparklines(data);
        AnalyticsPage.charts.renderAll(data);
    };

    /**
     * Filter shortlinks table rows by query and status
     */
    AnalyticsPage.tables.filterShortlinks = function () {
        const q = (document.getElementById('shortlinksSearch').value || '').toLowerCase();
        const status = document.getElementById('shortlinksStatusFilter').value;
        const rows = document.querySelectorAll('#shortlinksTableBody tr');

        rows.forEach(tr => {
            const slug = tr.getAttribute('data-slug') || '';
            const dest = tr.getAttribute('data-dest') || '';
            const rowStatus = tr.getAttribute('data-status') || '';
            const matchesQuery = !q || slug.includes(q) || dest.includes(q);
            const matchesStatus = status === 'all' || rowStatus === status;
            tr.style.display = (matchesQuery && matchesStatus) ? '' : 'none';
        });
    };

    /**
     * Sort shortlinks table rows
     */
    AnalyticsPage.tables.sortShortlinks = function () {
        const sortVal = document.getElementById('shortlinksSort').value;
        const tbody = document.getElementById('shortlinksTableBody');
        if (!tbody) return;
        const rows = Array.from(tbody.querySelectorAll('tr'));

        rows.sort((a, b) => {
            if (sortVal === 'clicks_desc') return (parseInt(b.getAttribute('data-clicks')) || 0) - (parseInt(a.getAttribute('data-clicks')) || 0);
            if (sortVal === 'clicks_asc') return (parseInt(a.getAttribute('data-clicks')) || 0) - (parseInt(b.getAttribute('data-clicks')) || 0);
            if (sortVal === 'slug_asc') return (a.getAttribute('data-slug') || '').localeCompare(b.getAttribute('data-slug') || '');
            return 0;
        });
        rows.forEach(r => tbody.appendChild(r));
    };

    /**
     * Filter digital products table rows
     */
    AnalyticsPage.tables.filterProducts = function () {
        const q = (document.getElementById('productsSearch').value || '').toLowerCase();
        const status = document.getElementById('productsStatusFilter').value;
        const rows = document.querySelectorAll('#productsTableBody tr');

        rows.forEach(tr => {
            const title = tr.getAttribute('data-title') || '';
            const rowStatus = tr.getAttribute('data-status') || '';
            const matchesQuery = !q || title.includes(q);
            const matchesStatus = status === 'all' || rowStatus === status;
            tr.style.display = (matchesQuery && matchesStatus) ? '' : 'none';
        });
    };

    /**
     * Sort digital products table rows
     */
    AnalyticsPage.tables.sortProducts = function () {
        const sortVal = document.getElementById('productsSort').value;
        const tbody = document.getElementById('productsTableBody');
        if (!tbody) return;
        const rows = Array.from(tbody.querySelectorAll('tr'));

        rows.sort((a, b) => {
            if (sortVal === 'revenue_desc') return (parseFloat(b.getAttribute('data-revenue')) || 0) - (parseFloat(a.getAttribute('data-revenue')) || 0);
            if (sortVal === 'qty_desc') return (parseInt(b.getAttribute('data-qty')) || 0) - (parseInt(a.getAttribute('data-qty')) || 0);
            if (sortVal === 'rating_desc') return (parseFloat(b.getAttribute('data-rating')) || 0) - (parseFloat(a.getAttribute('data-rating')) || 0);
            return 0;
        });
        rows.forEach(r => tbody.appendChild(r));
    };

    /**
     * Export HTML table to CSV file
     * @param {string} filename
     * @param {string} tableId
     */
    AnalyticsPage.tables.exportToCSV = function (filename, tableId) {
        const table = document.getElementById(tableId);
        if (!table) return;

        let csv = [];
        const rows = table.querySelectorAll('tr');

        for (let i = 0; i < rows.length; i++) {
            if (rows[i].style.display === 'none') continue;
            let row = [], cols = rows[i].querySelectorAll('td, th');
            for (let j = 0; j < cols.length - 1; j++) {
                let data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, ' ').replace(/\s+/g, ' ').trim().replace(/"/g, '""');
                row.push('"' + data + '"');
            }
            csv.push(row.join(','));
        }

        const blob = new Blob([csv.join('\n')], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        link.download = filename;
        link.href = window.URL.createObjectURL(blob);
        link.style.display = 'none';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    };

    /**
     * Global search handler
     * @param {string} query
     */
    AnalyticsPage.ui.handleGlobalSearch = function (query) {
        const q = (query || '').toLowerCase().trim();
        if (!q) return;

        if (AnalyticsPage.activeTab === 'shortlinks') {
            const input = document.getElementById('shortlinksSearch');
            if (input) { input.value = q; AnalyticsPage.tables.filterShortlinks(); }
        } else if (AnalyticsPage.activeTab === 'products') {
            const input = document.getElementById('productsSearch');
            if (input) { input.value = q; AnalyticsPage.tables.filterProducts(); }
        }
    };

    /**
     * Initialize analytics page
     */
    function initAnalytics() {
        if (AnalyticsPage.datepicker && typeof AnalyticsPage.datepicker.init === 'function') {
            AnalyticsPage.datepicker.init();
        }

        const dataScript = document.getElementById('analytics-data');
        if (!dataScript) return;

        try {
            AnalyticsPage.state = JSON.parse(dataScript.textContent);
        } catch (e) {
            console.error('Error parsing analytics data:', e);
            return;
        }

        AnalyticsPage.charts.renderSparklines(AnalyticsPage.state);
        AnalyticsPage.charts.renderAll(AnalyticsPage.state);
    }

    // Attach to global window
    window.AnalyticsPage = AnalyticsPage;

    // Backward-compatible aliases for inline template handlers
    window.switchAnalyticsTab = AnalyticsPage.ui.switchTab;
    window.toggleCustomDateBar = AnalyticsPage.ui.toggleCustomDateBar;
    window.onPresetChange = AnalyticsPage.filters.onPresetChange;
    window.applyManualCustomDate = AnalyticsPage.filters.applyManualCustomDate;
    window.filterShortlinksTable = AnalyticsPage.tables.filterShortlinks;
    window.sortShortlinksTable = AnalyticsPage.tables.sortShortlinks;
    window.filterProductsTable = AnalyticsPage.tables.filterProducts;
    window.sortProductsTable = AnalyticsPage.tables.sortProducts;
    window.exportTableToCSV = AnalyticsPage.tables.exportToCSV;
    window.handleGlobalSearch = AnalyticsPage.ui.handleGlobalSearch;

    // Global keyboard shortcut ⌘K / Ctrl+K
    document.addEventListener('keydown', function (e) {
        if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
            e.preventDefault();
            const searchInput = document.getElementById('globalAnalyticsSearch');
            if (searchInput) searchInput.focus();
        }
    });

    // Lifecycle events
    document.addEventListener('DOMContentLoaded', initAnalytics);
    document.addEventListener('turbo:load', initAnalytics);

    // Dark mode observer
    const darkObserver = new MutationObserver(mutations => {
        mutations.forEach(m => {
            if (m.attributeName === 'class' && AnalyticsPage.state) {
                AnalyticsPage.charts.renderAll(AnalyticsPage.state);
            }
        });
    });
    darkObserver.observe(document.documentElement, { attributes: true });

    // Turbo cleanup
    document.addEventListener('turbo:before-cache', function () {
        if (darkObserver) darkObserver.disconnect();
        AnalyticsPage.charts.destroyAll();
    }, { once: true });

})();
