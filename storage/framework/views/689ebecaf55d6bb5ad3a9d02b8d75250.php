<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Commission History - Admin Platform</title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/favicon.png')); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <style>
        /* --- Styles dari beranda dan styling umum --- */
        body {
            background: #f9f9f9;
            font-family: Arial, sans-serif;
            margin: 0;
            display: flex;
            min-height: 100vh;
        }
        .main {
            padding: 40px;
            flex-grow: 1;
        }
        .title {
            font-size: 2em;
            font-weight: bold;
            margin-bottom: 30px;
        }
        .card-earning {
            background: #ff7f2a;
            color: #fff;
            border-radius: 16px;
            padding: 30px 30px 20px 30px;
            margin-bottom: 30px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
            position: relative;
        }
        .card-earning .total {
            font-size: 1.5em;
            font-weight: bold;
        }
        .card-earning .amount {
            font-size: 2.5em;
            font-weight: bold;
            margin: 10px 0 20px 0;
        }
        .card-earning .actions {
            position: absolute;
            top: 30px;
            right: 30px;
            display: flex;
            gap: 10px;
        }
        .card-earning .actions button {
            background: #fff;
            color: #ff7f2a;
            border: none;
            border-radius: 8px;
            padding: 8px 18px;
            font-weight: bold;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }
        .card-earning .actions button i {
            font-size: 1.1em;
        }
        .card-earning .history {
            margin-top: 30px;
            color: #ffd6b3;
            font-size: 1em;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .list-komisi {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            padding: 20px;
        }
        .komisi-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #fff;
            border-radius: 12px;
            margin-bottom: 16px;
            border: 2px solid #fff;
            box-shadow: 0 1px 4px rgba(0,0,0,0.03);
            padding: 16px 20px;
            transition: border 0.2s;
        }
        .komisi-item.selected {
            border: 2px solid #1a73e8;
        }
        .komisi-item .icon {
            background: orange;
            color: #fff;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3em;
        }
        .komisi-item .info {
            margin-left: 12px;
        }
        .komisi-item .email {
            font-size: 0.95em;
            color: #888;
        }
        .komisi-item .nama {
            font-weight: bold;
            font-size: 1.1em;
        }
        .komisi-item .tanggal {
            color: #888;
            font-size: 0.95em;
            min-width: 110px;
            text-align: center;
        }
        .komisi-item .nominal {
            font-weight: bold;
            font-size: 1.1em;
            min-width: 110px;
            text-align: right;
        }

        @media (max-width: 700px) {
            body {
                flex-direction: column;
            }
            .main {
                padding: 10px;
                margin-left: 0;
            }
            .komisi-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
                padding: 10px;
            }
            .komisi-item .tanggal,
            .komisi-item .nominal {
                min-width: unset;
                text-align: left;
            }
        }
    </style>
</head>
<body>

    
    <?php echo $__env->make('platformadmin.sidebar.sidebarplatform', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="main">
        <!-- Judul -->
        <div class="title">Commission History</div>

        <!-- Card Total Earnings -->
        <div class="card-earning">
            <div class="total">Total Earnings</div>
            <div class="amount">IDR 242.200</div>
            <div class="actions">
                <button><i class="fa fa-paper-plane"></i> Withdraw</button>
                <button onclick="printCommissionReport()">
                    <i class="fa fa-print"></i> Print
                </button>
            </div>
            <div class="history"><i class="fa fa-paperclip"></i> History</div>
        </div>

        <!-- List Komisi Seller -->
        <div class="list-komisi">
            <!-- Item 1 -->
            <div class="komisi-item">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div class="icon"><i class="fa fa-arrow-down"></i></div>
                    <div class="info">
                        <div class="email">Budi@gmail.com</div>
                        <div class="nama">Budi</div>
                    </div>
                </div>
                <div class="tanggal">17 Apr 2025</div>
                <div class="nominal">Rp 153.800</div>
            </div>
            <!-- Item 2 -->
            <div class="komisi-item selected">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div class="icon"><i class="fa fa-arrow-down"></i></div>
                    <div class="info">
                        <div class="email">Fajar@gmail.com</div>
                        <div class="nama">Fajar</div>
                    </div>
                </div>
                <div class="tanggal">17 Apr 2025</div>
                <div class="nominal">Rp 88.400</div>
            </div>
        </div>
    </div>

    <script>
        function printCommissionReport() {
            // Buat form untuk mengirim data
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?php echo e(route("platformadmin.print.post")); ?>';
            form.target = '_blank';

            // Tambahkan CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '<?php echo e(csrf_token()); ?>';
            form.appendChild(csrfToken);

            // Tambahkan data yang akan dicetak
            const data = {
                total_earnings: 'IDR 242.200',
                commission_details: [
                    {
                        name: 'Budi',
                        email: 'Budi@gmail.com',
                        date: '17 Apr 2025',
                        amount: 'Rp 153.800'
                    },
                    {
                        name: 'Fajar',
                        email: 'Fajar@gmail.com',
                        date: '17 Apr 2025',
                        amount: 'Rp 88.400'
                    }
                ]
            };

            // Tambahkan data ke form
            const dataInput = document.createElement('input');
            dataInput.type = 'hidden';
            dataInput.name = 'data';
            dataInput.value = JSON.stringify(data);
            form.appendChild(dataInput);

            // Tambahkan form ke body dan submit
            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        }
    </script>
</body>
</html>
<?php /**PATH C:\Ardy\2025\Semester 4\Project2\LINKAN_ID-ardy\resources\views/platformadmin/berandaplatform.blade.php ENDPATH**/ ?>