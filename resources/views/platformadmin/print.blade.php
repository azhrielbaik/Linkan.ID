<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Data</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: #fff;
            padding: 20px;
        }

        .print-container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
        }

        .print-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #eee;
        }

        .print-header h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .print-content {
            margin-bottom: 30px;
        }

        .total-earnings {
            background: #ff7f2a;
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }

        .total-earnings h2 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .total-earnings h3 {
            font-size: 24px;
            font-weight: bold;
        }

        .commission-list {
            margin-top: 20px;
        }

        .commission-item {
            border-bottom: 1px solid #eee;
            padding: 15px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .commission-item:last-child {
            border-bottom: none;
        }

        .commission-info {
            flex: 1;
        }

        .commission-info strong {
            display: block;
            color: #333;
            margin-bottom: 5px;
        }

        .commission-info small {
            color: #666;
        }

        .commission-details {
            text-align: right;
        }

        .commission-details .date {
            color: #666;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .commission-details .amount {
            color: #333;
            font-weight: bold;
        }

        .print-footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #eee;
            font-size: 12px;
            color: #666;
        }

        .no-print {
            text-align: center;
            margin-top: 20px;
        }

        .print-button {
            background: #ff7f2a;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin: 0 5px;
        }

        .print-button:hover {
            background: #ff6a00;
        }

        @media print {
            .no-print {
                display: none;
            }
            body {
                padding: 0;
            }
            .print-container {
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="print-container">
        <div class="print-header">
            <h1>Commission Report</h1>
            <p>Generated on: {{ date('Y-m-d H:i:s') }}</p>
        </div>

        <div class="print-content">
            <div class="total-earnings">
                <h2>Total Earnings</h2>
                <h3>{{ $data['total_earnings'] ?? 'IDR 0' }}</h3>
            </div>

            <div class="commission-list">
                <h3>Commission Details</h3>
                @if(isset($data['commission_details']) && !empty($data['commission_details']))
                    @foreach($data['commission_details'] as $commission)
                        <div class="commission-item">
                            <div class="commission-info">
                                <strong>{{ $commission['name'] }}</strong>
                                <small>{{ $commission['email'] }}</small>
                            </div>
                            <div class="commission-details">
                                <div class="date">{{ $commission['date'] }}</div>
                                <div class="amount">{{ $commission['amount'] }}</div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p>No commission data available.</p>
                @endif
            </div>
        </div>

        <div class="print-footer">
            <p>© {{ date('Y') }} Linkan.ID - Platform Admin</p>
        </div>

        <div class="no-print">
            <button class="print-button" onclick="window.print()">
                <i class="fas fa-print"></i> Print Report
            </button>
            <button class="print-button" onclick="window.close()">
                <i class="fas fa-times"></i> Close
            </button>
        </div>
    </div>

    <script>
        let lastFetchedCommissions = [];
        let lastFetchedTotalEarnings = 0;

        // Auto print when page loads
        window.onload = function() {
            // Uncomment the line below if you want to auto-print
            // window.print();
        }

        function printCommissionReport() {
            // Buat form untuk mengirim data
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("platformadmin.print.post") }}';
            form.target = '_blank';

            // Tambahkan CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            // Siapkan data yang akan dikirim
            const data = {
                total_earnings: 'IDR ' + Number(lastFetchedTotalEarnings).toLocaleString('id-ID'),
                commission_details: lastFetchedCommissions.map(commission => ({
                    name: commission.seller_name,
                    email: commission.seller_email,
                    date: new Date(commission.created_at).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }),
                    amount: 'Rp ' + Number(commission.commission).toLocaleString('id-ID')
                }))
            };

            // Tambahkan data ke form
            const dataInput = document.createElement('input');
            dataInput.type = 'hidden';
            dataInput.name = 'data';
            dataInput.value = JSON.stringify(data);
            form.appendChild(dataInput);

            // Submit form
            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        }
    </script>
</body>
</html>
