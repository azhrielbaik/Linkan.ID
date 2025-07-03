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
</body>
<script>
    function printCommissionReport() {
        // Create a new window for printing
        const printWindow = window.open('', '_blank');

        // Get the content to print
        const content = `
            <html>
            <head>
                <title>Commission Report</title>
                <style>
                    body { font-family: Arial, sans-serif; padding: 20px; }
                    .header { text-align: center; margin-bottom: 30px; }
                    .total-earnings {
                        background: #ff7f2a;
                        color: white;
                        padding: 20px;
                        border-radius: 8px;
                        margin-bottom: 20px;
                    }
                    .commission-list { margin-top: 20px; }
                    .commission-item {
                        border-bottom: 1px solid #eee;
                        padding: 10px 0;
                        display: flex;
                        justify-content: space-between;
                    }
                    @media print {
                        .no-print { display: none; }
                    }
                </style>
            </head>
            <body>
                <div class="header">
                    <h1>Commission Report</h1>
                    <p>Generated on: ${new Date().toLocaleDateString()}</p>
                </div>

                <div class="total-earnings">
                    <h2>Total Earnings</h2>
                    <h3>IDR 242.200</h3>
                </div>

                <div class="commission-list">
                    <h3>Commission Details</h3>
                    <div class="commission-item">
                        <div>
                            <strong>Budi</strong><br>
                            <small>Budi@gmail.com</small>
                        </div>
                        <div>
                            <div>17 Apr 2025</div>
                            <div><strong>Rp 153.800</strong></div>
                        </div>
                    </div>
                    <div class="commission-item">
                        <div>
                            <strong>Fajar</strong><br>
                            <small>Fajar@gmail.com</small>
                        </div>
                        <div>
                            <div>17 Apr 2025</div>
                            <div><strong>Rp 88.400</strong></div>
                        </div>
                    </div>
                </div>

                <div class="no-print" style="margin-top: 20px; text-align: center;">
                    <button onclick="window.print()">Print Report</button>
                </div>
            </body>
            </html>
        `;

        // Write the content to the new window
        printWindow.document.write(content);
        printWindow.document.close();
    }
</script>
</html>
<?php /**PATH C:\Ardy\2025\Semester 4\Project2\LINKAN_ID-ardy-branch\resources\views/platformadmin/berandaplatform.blade.php ENDPATH**/ ?>