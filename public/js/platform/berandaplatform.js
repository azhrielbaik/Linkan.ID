// Platform Admin Dashboard Scripts

let earningsChartInstance = null;

function initEarningsChart(labels, data) {
    const chartElem = document.getElementById('earningsChart');
    if (!chartElem) return;
    const ctx = chartElem.getContext('2d');
    
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(237, 132, 44, 0.25)');
    gradient.addColorStop(1, 'rgba(237, 132, 44, 0.00)');

    if (earningsChartInstance) {
        earningsChartInstance.destroy();
    }

    earningsChartInstance = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Komisi Platform (Rp)',
                data: data,
                borderColor: '#ED842C',
                borderWidth: 2.5,
                backgroundColor: gradient,
                fill: true,
                tension: 0.35,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#ED842C',
                pointBorderWidth: 2.5,
                pointRadius: 4,
                pointHoverRadius: 6,
                pointHoverBackgroundColor: '#ED842C',
                pointHoverBorderColor: '#ffffff',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    titleFont: { family: 'Plus Jakarta Sans', size: 13, weight: '700' },
                    bodyFont: { family: 'Plus Jakarta Sans', size: 12 },
                    padding: 12,
                    cornerRadius: 10,
                    callbacks: {
                        label: function(context) {
                            return 'Komisi: Rp ' + Number(context.raw).toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: {
                        font: { family: 'Plus Jakarta Sans', size: 12, weight: '600' },
                        color: '#64748b'
                    }
                },
                y: {
                    grid: { color: '#f1f5f9' },
                    ticks: {
                        font: { family: 'Plus Jakarta Sans', size: 11 },
                        color: '#64748b',
                        callback: function(value) {
                            return 'Rp ' + Number(value).toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
}

function switchChartPeriod(period) {
    const btnMonthly = document.getElementById('btnMonthly');
    const btnWeekly  = document.getElementById('btnWeekly');
    const d = window.PlatformDashboardData || {};

    if (period === 'monthly') {
        if (btnMonthly) btnMonthly.classList.add('active');
        if (btnWeekly) btnWeekly.classList.remove('active');
        initEarningsChart(d.monthlyLabels || [], d.monthlyData || []);
    } else {
        if (btnWeekly) btnWeekly.classList.add('active');
        if (btnMonthly) btnMonthly.classList.remove('active');
        initEarningsChart(d.weeklyLabels || [], d.weeklyData || []);
    }
}

// Print / PDF Laporan
function printCommissionReport(commissionsUrl, printUrl, csrfToken) {
    const urlPrint = printUrl || (window.PlatformDashboardData && window.PlatformDashboardData.printUrl);
    if (urlPrint) {
        window.open(urlPrint, '_blank');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const d = window.PlatformDashboardData || {};
    if (d.monthlyLabels && d.monthlyData) {
        initEarningsChart(d.monthlyLabels, d.monthlyData);
    }
});
