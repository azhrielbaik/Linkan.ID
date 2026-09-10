// Platform Admin Dashboard Scripts — ApexCharts version

let komisiChartInstance = null;

/**
 * Build ApexCharts options for the komisi line chart.
 * @param {string[]} labels  – X-axis category labels
 * @param {number[]} data    – Series data values
 * @returns {object} ApexCharts options
 */
function buildChartOptions(labels, data) {
    return {
        chart: {
            type: 'line',
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
        colors: ['#ff6b00'],
        stroke: {
            curve: 'straight',
            width: 3
        },
        markers: {
            size: 5,
            colors: ['#ffffff'],
            strokeColors: '#ff6b00',
            strokeWidth: 2,
            hover: {
                size: 7,
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
            min: -1,
            max: 1,
            tickAmount: 10,
            labels: {
                style: {
                    colors: '#94a3b8',
                    fontSize: '11px'
                },
                formatter: function(val) {
                    return 'Rp ' + val.toFixed(1).replace('.', ',');
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
 * Render (or re-render) the komisi chart.
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
 * Toggle between monthly and weekly chart views.
 */
function switchChartPeriod(period) {
    var btnMonthly = document.getElementById('btnMonthly');
    var btnWeekly  = document.getElementById('btnWeekly');
    var d = window.PlatformDashboardData || {};

    if (period === 'monthly') {
        if (btnMonthly) btnMonthly.classList.add('active');
        if (btnWeekly)  btnWeekly.classList.remove('active');
        initKomisiChart(d.monthlyLabels || [], d.monthlyData || []);
    } else {
        if (btnWeekly)  btnWeekly.classList.add('active');
        if (btnMonthly) btnMonthly.classList.remove('active');
        initKomisiChart(d.weeklyLabels || [], d.weeklyData || []);
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
    if (d.monthlyLabels && d.monthlyData) {
        initKomisiChart(d.monthlyLabels, d.monthlyData);
    }
});
