<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payout History</title>
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
            min-height: 100vh;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        .main-content {
            flex: 1;
            padding: 25px 30px;
            margin-left: 250px;
            background: transparent;
        }

        .header {
            margin-bottom: 30px;
            animation: slideDown 0.6s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .header h1 {
            font-size: 28px;
            font-weight: 700;
            color: #000;
            text-shadow: none;
        }

        .header a {
            color: #000 !important;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
        }

        .header a:hover {
            color: #FF9040 !important;
            text-shadow: none;
        }

        .header a::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: #FF9040;
            transition: width 0.3s ease;
        }

        .header a:hover::after {
            width: 100%;
        }

        .history-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            animation: fadeInUp 0.8s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .history-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid;
            border-image: linear-gradient(90deg, #FF9040, #FF9040, #FF9040) 1;
            position: relative;
        }

        .history-header::before {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 0;
            width: 50px;
            height: 3px;
            background: linear-gradient(90deg, #FF9040, #FF9040);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(200px); }
        }

        .history-header i {
            font-size: 28px;
            background: linear-gradient(135deg, #FF9040, #FF9040);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .history-header h2 {
            font-size: 24px;
            font-weight: 700;
            background: linear-gradient(135deg, #FF9040, #FF9040);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
            transition: all 0.3s ease;
            animation: fadeInUp 0.8s ease-out 0.2s both;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(102, 126, 234, 0.4);
        }

        .stat-card:nth-child(2) {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            box-shadow: 0 10px 20px rgba(240, 147, 251, 0.3);
            animation-delay: 0.4s;
        }

        .stat-card:nth-child(2):hover {
            box-shadow: 0 15px 30px rgba(240, 147, 251, 0.4);
        }

        .stat-card:nth-child(3) {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            box-shadow: 0 10px 20px rgba(79, 172, 254, 0.3);
            animation-delay: 0.6s;
        }

        .stat-card:nth-child(3):hover {
            box-shadow: 0 15px 30px rgba(79, 172, 254, 0.4);
        }

        .stat-card i {
            font-size: 32px;
            margin-bottom: 10px;
            opacity: 0.9;
        }

        .stat-card .stat-number {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-card .stat-label {
            font-size: 14px;
            opacity: 0.9;
        }

        .history-table {
            overflow-x: auto;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .history-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .history-table th {
            background: linear-gradient(135deg, #FF9040 0%, #FF9040 100%);
            color: white;
            font-weight: 600;
            padding: 18px 15px;
            text-align: left;
            font-size: 14px;
            letter-spacing: 0.5px;
            position: relative;
        }

        .history-table th::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.5), transparent);
        }

        .history-table td {
            padding: 18px 15px;
            border-bottom: 1px solid #f0f0f0;
            font-size: 14px;
            color: #555;
            transition: all 0.3s ease;
        }

        .history-table tr {
            transition: all 0.3s ease;
        }

        .history-table tr:hover {
            background: linear-gradient(90deg, rgba(255,144,64,0.05), rgba(255,144,64,0.05));
            transform: scale(1.01);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .history-table tr:last-child td {
            border-bottom: none;
        }

        .amount-cell {
            font-weight: 700;
            color: #28a745;
            font-size: 16px;
            position: relative;
        }

        .amount-cell::before {
            content: '💰';
            margin-right: 8px;
            font-size: 14px;
        }

        .method-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .method-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            color: white;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
        }

        .method-icon:hover {
            transform: scale(1.1) rotate(5deg);
        }

        .method-bank {
            background: linear-gradient(135deg, #007bff, #0056b3);
        }

        .method-dana {
            background: linear-gradient(135deg, #00d4aa, #00b894);
        }

        .method-ovo {
            background: linear-gradient(135deg, #4c3498, #6c5ce7);
        }

        .method-gopay {
            background: linear-gradient(135deg, #00aae4, #0984e3);
        }

        .method-shopee {
            background: linear-gradient(135deg, #ee4d2d, #e17055);
        }

        .no-records {
            text-align: center;
            padding: 80px 20px;
            color: #777;
            background: linear-gradient(135deg, rgba(255,255,255,0.8), rgba(255,255,255,0.6));
            border-radius: 20px;
            margin: 20px 0;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.3);
        }

        .no-records i {
            font-size: 64px;
            background: linear-gradient(135deg, #FF9040, #FF9040);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 20px;
            display: block;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-10px); }
            60% { transform: translateY(-5px); }
        }

        .no-records p {
            font-size: 18px;
            margin: 0;
            font-weight: 500;
        }

        .floating-action {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #FF9040, #FF9040);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            box-shadow: 0 10px 30px rgba(255,144,64,0.4);
            cursor: pointer;
            transition: all 0.3s ease;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .floating-action:hover {
            transform: scale(1.1);
            box-shadow: 0 15px 40px rgba(255,144,64,0.6);
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 15px;
            }
            
            .history-container {
                padding: 20px;
                border-radius: 15px;
            }
            
            .stats-cards {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .history-table {
                font-size: 13px;
            }
            
            .history-table th,
            .history-table td {
                padding: 12px 8px;
            }

            .floating-action {
                bottom: 20px;
                right: 20px;
                width: 50px;
                height: 50px;
                font-size: 20px;
            }
        }

        @media (max-width: 900px) {
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <?php echo $__env->make('homeadminS.sidebar.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="main-content">
            <div class="header">
                <h1><a href="<?php echo e(route('payout.index')); ?>">Payout Settings</a> &gt; <span>Payout History</span></h1>
            </div>

            <div class="history-container">
                <div class="history-header">
                    <i class="fas fa-history"></i>
                    <h2>Payout History</h2>
                </div>

                <!-- Stats Cards -->
                <div class="stats-cards">
                    <div class="stat-card">
                        <i class="fas fa-wallet"></i>
                        <div class="stat-number"><?php echo e($history->count()); ?></div>
                        <div class="stat-label">Total Transaksi</div>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-money-bill-wave"></i>
                        <div class="stat-number">Rp <?php echo e(number_format($history->sum('amount'), 0, ',', '.')); ?></div>
                        <div class="stat-label">Total Penarikan</div>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-chart-line"></i>
                        <div class="stat-number"><?php echo e($history->groupBy('method')->count()); ?></div>
                        <div class="stat-label">Metode Pembayaran</div>
                    </div>
                </div>

                <?php if($history->isEmpty()): ?>
                    <div class="no-records">
                        <i class="fas fa-inbox"></i>
                        <p>Belum ada riwayat penarikan</p>
                    </div>
                <?php else: ?>
                    <div class="history-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>User ID</th>
                                    <th>Jumlah</th>
                                    <th>Metode Pembayaran</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($record->user_id); ?></td>
                                        <td class="amount-cell">Rp <?php echo e(number_format($record->amount, 0, ',', '.')); ?></td>
                                        <td>
                                            <div class="method-cell">
                                                <div class="method-icon method-<?php echo e(strtolower($record->method)); ?>">
                                                    <i class="fas fa-<?php echo e($record->method == 'Bank' ? 'university' : 'wallet'); ?>"></i>
                                                </div>
                                                <?php echo e($record->method); ?>

                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Floating Action Button -->
        <div class="floating-action" onclick="window.location.href='<?php echo e(route('payout.index')); ?>'">
            <i class="fas fa-arrow-left"></i>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\LINKAN_ID\resources\views/homeadminS/payout_history.blade.php ENDPATH**/ ?>