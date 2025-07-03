<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Linkan Dashboard</title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/favicon.png')); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: #f5f6fa;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        .main-content {
            flex: 1;
            padding: 25px 30px;
            background-color: #f5f6fa;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .header h1 {
            font-size: 26px;
            font-weight: bold;
            color: #000;
        }

        .notification-icon {
            background-color: #fff;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .notification-icon i {
            color: #333;
            font-size: 16px;
        }

        /* Account Section */
        .account-section {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .profile {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            position: relative;
        }

        .profile-image {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            margin-right: 15px;
            background-color: #edf1f7;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .profile-image i {
            font-size: 22px;
            color: #666;
        }

        .profile-info h3 {
            font-size: 17px;
            color: #333;
            margin-bottom: 4px;
            font-weight: 500;
        }

        .profile-info a {
            color: #ff4500;
            text-decoration: none;
            font-size: 13px;
        }

        .share-button {
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #FF9040;
            font-size: 15px;
            cursor: pointer;
            padding: 8px;
        }

        .start-creating {
            font-size: 15px;
            color: #333;
            font-weight: 600;
            margin: 15px 0;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .action-button {
            padding: 8px 12px;
            border: none;
            border-radius: 6px;
            background-color: #f5f5f5;
            cursor: pointer;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 6px;
            color: #333;
            transition: all 0.2s ease;
            flex: 1;
            text-decoration: none;
        }

        .action-button i {
            font-size: 13px;
            color: #666;
        }

        /* Earnings Section */
        .earnings-section {
            background: #FF9040;
            border-radius: 12px;
            padding: 20px;
            color: white;
            margin-bottom: 20px;
            position: relative;
            box-shadow: 0 2px 8px rgba(255,69,0,0.2);
        }

        .earnings-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .earnings-header span {
            font-size: 15px;
            font-weight: 500;
        }

        .earnings-header i {
            font-size: 18px;
            cursor: pointer;
        }

        .earnings-amount {
            font-size: 26px;
            font-weight: 600;
        }

        /* Stats Section */
        .stats-section {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .stats-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .stats-header h3 {
            font-size: 15px;
            color: #333;
            font-weight: 500;
        }

        .date-range-selector {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .date-input {
            padding: 7px 12px;
            border: 1px solid #eee;
            border-radius: 6px;
            background: white;
            font-size: 13px;
            color: #666;
        }

        .apply-date {
            padding: 7px 12px;
            background: #FF9040;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
        }

        .apply-date:hover {
            opacity: 0.9;
        }

        .stats-numbers {
            display: flex;
            gap: 25px;
            margin-bottom: 20px;
            font-size: 14px;
            color: #666;
        }

        .stats-chart {
            height: 200px;
            margin-top: 15px;
        }

        /* Summary Cards */
        .summary-section {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 35px;
        }

        .summary-card {
            background: white;
            border-radius: 12px;
            padding: 18px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .summary-card i {
            font-size: 18px;
            color: #666;
            margin-bottom: 12px;
        }

        .summary-card .label {
            font-size: 13px;
            color: #666;
            margin-bottom: 8px;
        }

        .summary-card .number {
            font-size: 22px;
            font-weight: 600;
            color: #333;
        }

        @media (max-width: 1024px) {
            .summary-section {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 20px;
            }
            
            .action-buttons {
                flex-wrap: wrap;
            }
            
            .action-button {
                min-width: calc(50% - 5px);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <?php echo $__env->make('homeadminS.sidebar.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        
        <div class="main-content">
            <div class="header">
                <h1>HOME</h1>
                <div class="notification-icon">
                    <i class="fas fa-bell"></i>
                </div>
            </div>

            <div class="account-section">
                <div class="profile">
                    <div class="profile-image">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="profile-info">
                        <h3><?php echo e(Auth::user()->name); ?></h3>
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
                    <a href="<?php echo e(route('mylinkan')); ?>" class="action-button">
                        <i class="fas fa-qrcode"></i> add Linkan
                    </a>
                    <a href="<?php echo e(route('digital-product.create')); ?>" class="action-button">
                        <i class="fas fa-box"></i> Digital Product
                    </a>
                    <a href="https://indobuzz.id/about-us" class="action-button">
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('statsChart').getContext('2d');
        let myChart;
        let startDate = null;
        let endDate = null;

        function createTrackingLink(targetUrl, linkId) {
            return `/track-click?link_id=${linkId}&target=${encodeURIComponent(targetUrl)}`;
        }

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
                // Update total views & clicks angka di bawah judul
                document.getElementById('totalViews').textContent = data.views.reduce((a, b) => a + b, 0);
                document.getElementById('totalClicks').textContent = data.clicks.reduce((a, b) => a + b, 0);

                // Update date input fields dengan data dari server
                document.getElementById('startDate').value = data.start_date;
                document.getElementById('endDate').value = data.end_date;

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

        function applyDateFilter() {
            startDate = document.getElementById('startDate').value;
            endDate = document.getElementById('endDate').value;
            updateChart();
        }

        // Set default dates (7 hari terakhir) saat halaman selesai dimuat
        document.addEventListener('DOMContentLoaded', () => {
            const today = new Date();
            const sevenDaysAgo = new Date();
            sevenDaysAgo.setDate(today.getDate() - 6);

            document.getElementById('startDate').value = sevenDaysAgo.toISOString().split('T')[0];
            document.getElementById('endDate').value = today.toISOString().split('T')[0];

            startDate = document.getElementById('startDate').value;
            endDate = document.getElementById('endDate').value;

            updateChart();

            // Kalau perlu, fungsi copy link juga sudah siap
        });

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('Link copied to clipboard!');
            }).catch((err) => {
                console.error('Failed to copy text: ', err);
            });
        }
    </script>
</body>
</html><?php /**PATH C:\Ardy\2025\Semester 4\Project2\LINKAN_ID-ardy-branch\resources\views/homeadminS/beranda.blade.php ENDPATH**/ ?>