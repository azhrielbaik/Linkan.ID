<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payout Setting</title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/favicon.png')); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        * { 
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
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
            padding: 20px;
        }

        .header {
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 24px;
            color: #333;
        }

        .payout-section {
            display: flex;
            gap: 20px;
        }

        .earnings-card, .payment-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            flex: 1;
        }

        .earnings-card {
            background: #FF9040;
            color: white;
            position: relative;
        }

        .earnings-card h2 {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .earnings-card p {
            font-size: 16px;
            margin: 5px 0;
        }

        .earnings-card p:last-of-type {
            font-style: italic;
            color: #f0f0f0;
        }

        .earnings-card .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: white;
            color: #FF9040;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            border: 2px solid #FF9040;
            cursor: pointer;
        }

        .earnings-card .btn:hover {
            background: #f0f0f0;
        }

        .earnings-card .btn-withdraw {
            position: absolute;
            top: 20px;
            right: 20px;
        }

        .earnings-card .btn-history {
            position: absolute;
            bottom: 20px;
            right: 20px;
        }

        .payment-card {
            text-align: center;
        }

        .payment-card h2 {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .payment-card p {
            font-size: 14px;
            color: #666;
        }

        .payment-card .bank-info {
            margin-top: 15px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background: #f9f9f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .payment-card .bank-info strong {
            font-size: 16px;
            color: #333;
        }

        .payment-card .bank-info img {
            width: 40px;
            height: 40px;
        }
        .header a {
            color: black !important;
            text-decoration: none; /* kalau mau hilangkan garis bawah juga */
        }

    </style>
</head>
<body>
    <div class="container">
        <?php echo $__env->make('homeadminS.sidebar.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="main-content">
            <div class="header">
                <h1><a href="<?php echo e(route('settings')); ?>">Settings</a> &gt; <span>Payout Settings</h1>
            </div>

            <div class="payout-section">
                <!-- Earnings Card -->
                <div class="earnings-card">
                    <h2>My Earnings</h2>
                    <p>IDR 0</p>
                    <p>Last Withdraw</p>
                    <p>IDR 0</p>
                    <a href="#" class="btn btn-withdraw">
                        <i class="fas fa-paper-plane"></i> Withdraw
                    </a>
                    <a href="#" class="btn btn-history">
                        <i class="fas fa-history"></i> History
                    </a>
                </div>

                <!-- Payment Card -->
                <div class="payment-card">
                    <h2>Get Paid With</h2>
                    <p>Your money will be transferred to</p>
                    <div class="bank-info">
                    <img src="/images/creditcard.png" alt="Bank">

                        <div>
                            <strong>Budi Fulan</strong><br>
                            Bank BJB - 123******45
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html><?php /**PATH C:\Ardy\2025\Semester 4\Project2\LINKAN_ID-ardy-branch\resources\views/homeadminS/payout.blade.php ENDPATH**/ ?>