

<?php $__env->startSection("page_title", "Overview Dasbor"); ?>

<?php $__env->startPush("styles"); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/pages/beranda.css')); ?>" data-turbo-track="reload">
<?php $__env->stopPush(); ?>

<?php $__env->startSection("content"); ?>
<div class="dashboard-beranda-page">



            <div class="account-section">
                <div class="profile">
                    <div class="profile-image">
                        <?php if($appearance && $appearance->profile_image): ?>
                            <img src="<?php echo e(asset('storage/' . $appearance->profile_image)); ?>" alt="Profile" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                        <?php else: ?>
                            <i class="fas fa-user"></i>
                        <?php endif; ?>
                    </div>
                    <div class="profile-info">
                        <h3><?php echo e($appearance && $appearance->name ? $appearance->name : Auth::user()->name); ?></h3>
                        <a href="<?php echo e(route('track.view', ['username' => Auth::user()->username])); ?>" style="color: #FF9040;">
                            <?php echo e(url('/linkan.id/' . Auth::user()->username)); ?>

                        </a>
                    </div>
                    <button
                      class="share-button"
                      onclick="copyToClipboard('<?php echo e(route('track.view', ['username' => Auth::user()->username])); ?>')"
                    >
                        <i class="fas fa-share-alt"></i>
                    </button>
                </div>
                <div class="start-creating">START CREATING NOW...!</div>
                <div class="action-buttons">
                    <a href="<?php echo e(route('admin.mylinkan')); ?>" class="action-button">
                        <i class="fas fa-qrcode"></i> add Linkan
                    </a>
                    <a href="<?php echo e(route('admin.digital-products.create')); ?>" class="action-button">
                        <i class="fas fa-box"></i> Digital Product
                    </a>
                    <a href="<?php echo e(route('about')); ?>" class="action-button">
                        <i class="fas fa-headset"></i> About Us
                    </a>
                </div>
            </div>

            <div class="earnings-section">
                <div class="earnings-header">
                    <span>Earnings</span>
                    <i class="fas fa-cog"></i>
                </div>
                <div class="earnings-amount">IDR <?php echo e(number_format($totalEarnings, 0, ',', '.')); ?></div>
            </div>

            <div class="stats-section">
                <div class="stats-header">
                    <h3>Total Click & Views</h3>
                    <div class="date-range-selector">
                        <input type="date" id="startDate" class="date-input" />
                        <span>to</span>
                        <input type="date" id="endDate" class="date-input" />
                        <button class="apply-date" onclick="applyDateFilter()">Apply</button>
                    </div>
                </div>
                <div class="stats-numbers">
                    <span>Views: <span id="totalViews"><?php echo e($totalViews); ?></span></span>
                    <span>Clicks: <span id="totalClicks"><?php echo e($totalClicks); ?></span></span>
                </div>
                <div class="stats-chart">
                    <canvas id="statsChart"></canvas>
                </div>
            </div>

            <div class="summary-section">
                <div class="summary-card">
                    <i class="fas fa-shopping-cart"></i>
                    <div class="label">Lifetime Orders</div>
                    <div class="number"><?php echo e($lifetimeOrders); ?></div>
                </div>
                <div class="summary-card">
                    <i class="fas fa-chart-line"></i>
                    <div class="label">Lifetime sales (IDR)</div>
                    <div class="number"><?php echo e(number_format($totalEarnings, 0, ',', '.')); ?></div>
                </div>
                <div class="summary-card">
                    <i class="fas fa-box"></i>
                    <div class="label">My Blocks</div>
                    <div class="number"><?php echo e($totalProducts); ?></div>
                </div>
            </div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush("scripts"); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush("scripts"); ?>
<script>
document.addEventListener('turbo:load', function() {
    const chartEl = document.getElementById('statsChart');
    if (!chartEl) return;

    const ctx = chartEl.getContext('2d');
    let myChart;
    let startDate = null;
    let endDate = null;

    window.applyDateFilter = function() {
        const startDateEl = document.getElementById('startDate');
        const endDateEl = document.getElementById('endDate');
        if (startDateEl && endDateEl) {
            startDate = startDateEl.value;
            endDate = endDateEl.value;
            updateChart();
        }
    };

    window.copyToClipboard = function(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert('Link copied to clipboard!');
        }).catch((err) => {
            console.error('Failed to copy text: ', err);
        });
    };

    function updateChart() {
        const params = new URLSearchParams();
        if (startDate) params.append('start_date', startDate);
        if (endDate) params.append('end_date', endDate);

        fetch(`/get-chart-data?${params.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then((response) => response.json())
        .then((data) => {
            const totalViewsEl = document.getElementById('totalViews');
            const totalClicksEl = document.getElementById('totalClicks');
            const startDateEl = document.getElementById('startDate');
            const endDateEl = document.getElementById('endDate');

            if (totalViewsEl && data.views) {
                totalViewsEl.textContent = data.views.reduce((a, b) => a + b, 0);
            }
            if (totalClicksEl && data.clicks) {
                totalClicksEl.textContent = data.clicks.reduce((a, b) => a + b, 0);
            }

            if (startDateEl) startDateEl.value = data.start_date;
            if (endDateEl) endDateEl.value = data.end_date;

            startDate = data.start_date;
            endDate = data.end_date;

            if (myChart) {
                myChart.destroy();
            }

            myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [
                        {
                            label: 'Views',
                            data: data.views,
                            backgroundColor: '#ff4500',
                            borderRadius: 4,
                            maxBarThickness: 12,
                        },
                        {
                            label: 'Clicks',
                            data: data.clicks,
                            backgroundColor: '#4a90e2',
                            borderRadius: 4,
                            maxBarThickness: 12,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f0f0f0',
                            },
                        },
                        x: {
                            grid: {
                                display: false,
                            },
                        },
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            align: 'start',
                            labels: {
                                boxWidth: 12,
                                usePointStyle: true,
                                pointStyle: 'circle',
                            },
                        },
                    },
                },
            });
        })
        .catch((err) => {
            console.error('Error fetching chart data:', err);
        });
    }

    const today = new Date();
    const sevenDaysAgo = new Date();
    sevenDaysAgo.setDate(today.getDate() - 6);

    const startDateEl = document.getElementById('startDate');
    const endDateEl = document.getElementById('endDate');

    if (startDateEl && endDateEl) {
        startDateEl.value = sevenDaysAgo.toISOString().split('T')[0];
        endDateEl.value = today.toISOString().split('T')[0];

        startDate = startDateEl.value;
        endDate = endDateEl.value;
    }

    updateChart();
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make("layouts.admin", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\TUGAS PKL\Linkan.ID\resources\views/homeadminS/beranda.blade.php ENDPATH**/ ?>