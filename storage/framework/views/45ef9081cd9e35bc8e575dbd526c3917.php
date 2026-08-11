

<?php $__env->startSection("page_title", "Statistic"); ?>

<?php $__env->startPush("styles"); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/pages/statistic.css')); ?>" data-turbo-track="reload">
<?php $__env->stopPush(); ?>

<?php $__env->startSection("content"); ?>
<div class="dashboard-statistic-page">



            <!-- Grafik Total Clicks & Views -->
            <div class="stats-section">
                <div class="stats-header">
                    <h3>Total Click & Views</h3>
                    <div class="date-range-selector">
                        <input type="date" id="startDate1" class="date-input">
                        <span>to</span>
                        <input type="date" id="endDate1" class="date-input">
                        <button class="apply-date" onclick="applyDateFilter1()">Apply</button>
                    </div>
                </div>
                <div class="stats-numbers">
                    <span>Views: <?php echo e($totalViews); ?></span>
                    <span>Clicks: <?php echo e($totalClicks); ?></span>
                </div>
                <div class="stats-chart">
                    <canvas id="statsChart1"></canvas>
                </div>
            </div>

            <!-- Grafik Total Sales -->
            <div class="stats-section">
                <div class="stats-header">
                    <h3>Total Sales</h3>
                    <div class="date-range-selector">
                        <input type="date" id="startDate2" class="date-input">
                        <span>to</span>
                        <input type="date" id="endDate2" class="date-input">
                        <button class="apply-date" onclick="applyDateFilter2()">Apply</button>
                    </div>
                </div>
                <div class="stats-numbers">
                    <span>Total Sales: IDR <?php echo e(number_format($totalSales, 0, ',', '.')); ?></span>
                </div>
                <div class="stats-chart">
                    <canvas id="statsChart2"></canvas>
                </div>
            </div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush("scripts"); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush("scripts"); ?>
<script>
let chart1, chart2;
        let startDate1 = null, endDate1 = null;
        let startDate2 = null, endDate2 = null;

        function updateChart1() {
            const params = new URLSearchParams();
            if (startDate1) params.append('start_date', startDate1);
            if (endDate1) params.append('end_date', endDate1);

            fetch(`<?php echo e(route('admin.statistics.chart-data')); ?>?${params.toString()}`)
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

                    const ctx = document.getElementById('statsChart1').getContext('2d');
                    chart1 = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'Views',
                                data: data.views,
                                backgroundColor: '#ff4500',
                                borderRadius: 4,
                                maxBarThickness: 12
                            }, {
                                label: 'Clicks',
                                data: data.clicks,
                                backgroundColor: '#4a90e2',
                                borderRadius: 4,
                                maxBarThickness: 12
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: '#f0f0f0'
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    position: 'top',
                                    align: 'start',
                                    labels: {
                                        boxWidth: 12,
                                        usePointStyle: true,
                                        pointStyle: 'circle'
                                    }
                                }
                            }
                        }
                    });
                });
        }

        function updateChart2() {
            const params = new URLSearchParams();
            if (startDate2) params.append('start_date', startDate2);
            if (endDate2) params.append('end_date', endDate2);

            fetch(`<?php echo e(route('admin.statistics.chart-data')); ?>?${params.toString()}`)
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

                    const ctx = document.getElementById('statsChart2').getContext('2d');
                    chart2 = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'Sales',
                                data: data.sales,
                                borderColor: '#FF9040',
                                backgroundColor: 'rgba(255, 144, 64, 0.1)',
                                fill: true,
                                tension: 0.4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: '#f0f0f0'
                                    },
                                    ticks: {
                                        callback: function(value) {
                                            return 'IDR ' + value.toLocaleString('id-ID');
                                        }
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    position: 'top',
                                    align: 'start',
                                    labels: {
                                        boxWidth: 12,
                                        usePointStyle: true,
                                        pointStyle: 'circle'
                                    }
                                }
                            }
                        }
                    });
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
<?php $__env->stopPush(); ?>

<?php echo $__env->make("layouts.admin", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\TUGAS PKL\Linkan.ID\resources\views/homeadminS/statistic.blade.php ENDPATH**/ ?>