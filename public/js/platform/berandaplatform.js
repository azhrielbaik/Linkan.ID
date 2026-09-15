// Platform Admin Dashboard Scripts — Daily ApexCharts version

let komisiChartInstance = null;

/**
 * Build ApexCharts options for the daily komisi chart.
 * @param {string[]} labels  – X-axis category labels (dates)
 * @param {number[]} data    – Series data values (commissions)
 * @returns {object} ApexCharts options
 */
function buildChartOptions(labels, data) {
    return {
        chart: {
            type: 'area',
            height: 300,
            fontFamily: 'Plus Jakarta Sans, sans-serif',
            toolbar: { show: false },
            zoom: { enabled: false },
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 600
            }
        },
        series: [{
            name: 'Komisi Platform',
            data: data
        }],
        colors: ['#ed842c'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.35,
                opacityTo: 0.05,
                stops: [0, 90, 100]
            }
        },
        stroke: {
            curve: 'smooth',
            width: 3
        },
        markers: {
            size: 4,
            colors: ['#ffffff'],
            strokeColors: '#ed842c',
            strokeWidth: 2,
            hover: {
                size: 6,
                sizeOffset: 2
            }
        },
        xaxis: {
            categories: labels,
            axisBorder: { show: false },
            axisTicks: { show: false },
            labels: {
                style: {
                    colors: '#94a3b8',
                    fontSize: '12px',
                    fontWeight: 600
                }
            }
        },
        yaxis: {
            min: 0,
            forceNiceScale: true,
            labels: {
                style: {
                    colors: '#94a3b8',
                    fontSize: '11px'
                },
                formatter: function(val) {
                    if (val >= 1000000) {
                        return 'Rp ' + (val / 1000000).toFixed(1) + ' jt';
                    } else if (val >= 1000) {
                        return 'Rp ' + (val / 1000).toFixed(0) + ' rb';
                    }
                    return 'Rp ' + Math.round(val);
                }
            }
        },
        grid: {
            borderColor: '#f1f5f9',
            strokeDashArray: 0,
            xaxis: { lines: { show: false } },
            yaxis: { lines: { show: true } }
        },
        tooltip: {
            theme: 'dark',
            style: {
                fontSize: '12px',
                fontFamily: 'Plus Jakarta Sans, sans-serif'
            },
            y: {
                formatter: function(val) {
                    return 'Rp ' + Number(val).toLocaleString('id-ID');
                }
            }
        },
        legend: { show: false }
    };
}

/**
 * Render (or re-render) the daily komisi chart.
 * @param {string[]} labels
 * @param {number[]} data
 */
function initKomisiChart(labels, data) {
    var el = document.querySelector('#komisi-chart');
    if (!el) return;

    if (komisiChartInstance) {
        komisiChartInstance.destroy();
        komisiChartInstance = null;
    }

    var options = buildChartOptions(labels, data);
    komisiChartInstance = new ApexCharts(el, options);
    komisiChartInstance.render();
}

/**
 * Toggle between daily chart views (7 days and 30 days).
 */
function switchChartPeriod(period) {
    var btnDaily7  = document.getElementById('btnDaily7');
    var btnDaily30 = document.getElementById('btnDaily30');
    var d = window.PlatformDashboardData || {};

    if (period === '30days') {
        if (btnDaily30) btnDaily30.classList.add('active');
        if (btnDaily7)  btnDaily7.classList.remove('active');
        initKomisiChart(d.daily30Labels || [], d.daily30Data || []);
    } else {
        if (btnDaily7)  btnDaily7.classList.add('active');
        if (btnDaily30) btnDaily30.classList.remove('active');
        initKomisiChart(d.daily7Labels || [], d.daily7Data || []);
    }
}

// Print / PDF Laporan
function printCommissionReport() {
    var urlPrint = window.PlatformDashboardData && window.PlatformDashboardData.printUrl;
    if (urlPrint) {
        window.open(urlPrint, '_blank');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    var d = window.PlatformDashboardData || {};
    if (d.daily7Labels && d.daily7Data) {
        initKomisiChart(d.daily7Labels, d.daily7Data);
    }
});
