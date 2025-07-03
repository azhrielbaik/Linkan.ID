<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payout Setting</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
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
            margin-left: 250px;
        }

        .header {
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 24px;
            color: #333;
        }

        .payout-main-flex {
            display: flex;
            gap: 32px;
            align-items: stretch;
            flex-wrap: wrap;
        }
        .earnings-card, .payment-card {
            flex: 1 1 350px;
            min-width: 320px;
            max-width: 600px;
            box-sizing: border-box;
        }
        @media (max-width: 900px) {
            .payout-main-flex {
                flex-direction: column;
                gap: 20px;
            }
            .main-content {
                margin-left: 0;
            }
        }

        .earnings-card, .payment-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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
        @include('homeadminS.sidebar.sidebar')

        <div class="main-content">
            <div class="header">
                <h1><a href="{{ route('settings') }}">Settings</a> &gt; <span>Payout Settings</h1>
            </div>

            @if(session('success'))
                <div style="background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 10px; margin-bottom: 20px; border-radius: 5px;">
                    {{ session('success') }}
                </div>
            @endif

            <div class="payout-main-flex">
                <!-- Earnings Card -->
                <div class="earnings-card" style="padding: 30px 24px; border-radius: 16px; background: linear-gradient(90deg, #FF9040 60%, #ffb380 100%); color: white; box-shadow: 0 4px 16px rgba(255,144,64,0.10); margin-bottom: 0; display: flex; flex-direction: column; justify-content: space-between;">
                    <h2 style="font-size: 22px; font-weight: bold; margin-bottom: 24px; letter-spacing: 1px;">My Earnings</h2>
                    <div style="display: flex; flex-wrap: wrap; gap: 24px; justify-content: space-between;">
                        <div style="flex: 1 1 180px; min-width: 180px; background: rgba(255,255,255,0.10); border-radius: 12px; padding: 18px 16px; display: flex; align-items: center; gap: 16px;">
                            <div style="background: white; color: #FF9040; border-radius: 50%; width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; font-size: 22px;"><i class="fas fa-wallet"></i></div>
                            <div>
                                <div style="font-size: 13px; opacity: 0.85;">Total Pendapatan</div>
                                <div style="font-size: 20px; font-weight: bold;">Rp {{ number_format($totalEarnings, 0, ',', '.') }}</div>
                            </div>
                        </div>
                        <div style="flex: 1 1 180px; min-width: 180px; background: rgba(255,255,255,0.10); border-radius: 12px; padding: 18px 16px; display: flex; align-items: center; gap: 16px;">
                            <div style="background: white; color: #28a745; border-radius: 50%; width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; font-size: 22px;"><i class="fas fa-arrow-circle-up"></i></div>
                            <div>
                                <div style="font-size: 13px; opacity: 0.85;">Total Penarikan</div>
                                <div style="font-size: 20px; font-weight: bold;">Rp {{ number_format($totalWithdrawn, 0, ',', '.') }}</div>
                            </div>
                        </div>
                        <div style="flex: 1 1 180px; min-width: 180px; background: rgba(255,255,255,0.10); border-radius: 12px; padding: 18px 16px; display: flex; align-items: center; gap: 16px;">
                            <div style="background: white; color: #007bff; border-radius: 50%; width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; font-size: 22px;"><i class="fas fa-coins"></i></div>
                            <div>
                                <div style="font-size: 13px; opacity: 0.85;">Saldo Bisa Ditarik</div>
                                <div style="font-size: 20px; font-weight: bold;">Rp {{ number_format($currentBalance, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                    <div style="display: flex; gap: 16px; margin-top: 28px;">
                        <a href="{{ route('payout.showWithdrawForm') }}" class="btn btn-withdraw" style="background: white; color: #FF9040; border: none; font-weight: bold; border-radius: 6px; padding: 12px 28px; font-size: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); display: flex; align-items: center; gap: 8px; text-decoration: none; transition: background 0.2s;">
                            <i class="fas fa-paper-plane"></i> Withdraw
                        </a>
                        <a href="{{ route('payout.showPayoutHistory') }}" class="btn btn-history" style="background: white; color: #FF9040; border: none; font-weight: bold; border-radius: 6px; padding: 12px 28px; font-size: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); display: flex; align-items: center; gap: 8px; text-decoration: none; transition: background 0.2s;">
                            <i class="fas fa-history"></i> History
                        </a>
                    </div>
                </div>
                <!-- Payment Card -->
                <div class="payment-card" style="background: linear-gradient(90deg, #FF9040 60%, #ffb380 100%); border-radius: 16px; box-shadow: 0 4px 16px rgba(255,144,64,0.10); padding: 32px 24px; text-align: center; color: white; display: flex; flex-direction: column; justify-content: center;">
                    <h2 style="font-size: 20px; font-weight: bold; color: white; margin-bottom: 8px; letter-spacing: 1px;">Metode Penerimaan Dana</h2>
                    <p style="color: #fffbe6; font-size: 14px; margin-bottom: 24px;">Dana kamu akan ditransfer ke rekening berikut:</p>
                    @if($payoutDetail)
                    <div class="bank-info" style="display: flex; align-items: center; gap: 18px; background: rgba(255,255,255,0.10); border-radius: 12px; padding: 18px 18px; margin-bottom: 18px; box-shadow: 0 2px 8px rgba(255,144,64,0.06); justify-content: center;">
                        @if($payoutDetail->method_type === 'Bank')
                            <img src="/images/creditcard.png" alt="Bank" style="width: 54px; height: 54px; border-radius: 8px; background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                        @elseif($payoutDetail->method_type === 'DANA')
                            <img src="/images/dana.png" alt="DANA" style="width: 54px; height: 54px; border-radius: 8px; background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                        @elseif($payoutDetail->method_type === 'ShopeePay')
                            <img src="/images/shopeepay.png" alt="ShopeePay" style="width: 54px; height: 54px; border-radius: 8px; background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                        @else
                            <i class="fas fa-wallet" style="font-size: 48px; color: #FF9040; background: #fff; border-radius: 8px; width: 54px; height: 54px; display: flex; align-items: center; justify-content: center;"></i>
                        @endif
                        <div style="text-align: left;">
                            <div style="font-size: 16px; font-weight: bold; color: white;">{{ $payoutDetail->account_name }}</div>
                            <div style="font-size: 14px; color: #fffbe6; margin-top: 2px;">{{ $payoutDetail->method_type }} - {{ $payoutDetail->account_number }}</div>
                            @if($payoutDetail->method_type === 'Bank' && $payoutDetail->bank_name)
                                <div style="font-size: 13px; color: #ffe0b3; margin-top: 2px;">{{ $payoutDetail->bank_name }}</div>
                            @endif
                        </div>
                    </div>
                    @else
                    <div style="padding: 24px; color: #fffbe6; background: rgba(255,255,255,0.10); border-radius: 12px; margin-bottom: 18px;">
                        <p>Belum ada metode pembayaran yang diatur.</p>
                    </div>
                    @endif
                    <a href="{{ route('payout.showMethodForm') }}" class="btn" style="margin-top: 10px; display: inline-block; background: white; color: #FF9040; font-weight: bold; border-radius: 6px; padding: 12px 32px; font-size: 15px; box-shadow: 0 2px 8px rgba(255,144,64,0.10); text-decoration: none; transition: background 0.2s;">
                        <i class="fas fa-cog"></i> {{ $payoutDetail ? 'Edit Payout Method' : 'Set Payout Method' }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    // Auto refresh data setiap 30 detik
    function refreshData() {
        fetch(window.location.href)
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                
                // Update earnings dengan format yang benar
                const newEarnings = doc.querySelector('.earnings-card p:nth-child(2)').textContent;
                const earningsElement = document.querySelector('.earnings-card p:nth-child(2)');
                if (earningsElement) {
                    earningsElement.textContent = newEarnings;
                }
                
                // Update last withdraw
                const newLastWithdraw = doc.querySelector('.earnings-card p:nth-child(4)').textContent;
                const lastWithdrawElement = document.querySelector('.earnings-card p:nth-child(4)');
                if (lastWithdrawElement) {
                    lastWithdrawElement.textContent = newLastWithdraw;
                }
            })
            .catch(error => console.error('Error refreshing data:', error));
    }

    // Refresh data setiap 30 detik
    setInterval(refreshData, 30000);

    // Tambahkan event listener untuk memastikan data diperbarui saat halaman difokuskan
    document.addEventListener('visibilitychange', function() {
        if (document.visibilityState === 'visible') {
            refreshData();
        }
    });

    // Refresh data saat halaman dimuat
    document.addEventListener('DOMContentLoaded', refreshData);
</script>
</html>