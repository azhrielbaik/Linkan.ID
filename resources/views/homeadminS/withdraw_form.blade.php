<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Withdraw Funds</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f6fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .withdraw-card {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .withdraw-card h2 {
            margin-bottom: 20px;
            color: #333;
        }
        .withdraw-card p {
            color: #666;
            margin-bottom: 25px;
        }
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }
        .form-group input[type="number"] {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }
        .form-group .currency-prefix {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
        }
        .form-group .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        .form-group .input-wrapper input {
            padding-left: 40px; /* Make space for 'IDR' */
        }
        .form-actions button {
            background-color: #FF9040;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s ease;
            width: 100%;
        }
        .form-actions button:hover {
            background-color: #e67e30;
        }
        .form-actions .btn-cancel {
            background-color: #ccc;
            margin-top: 10px;
        }
        .form-actions .btn-cancel:hover {
            background-color: #bbb;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="withdraw-card">
        <h2>Withdraw Funds</h2>
        <p>Current balance: Rp {{ number_format($currentEarnings ?? 0, 0, ',', '.') }}</p>

        @if ($errors->any())
            <div class="alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('payout.processWithdrawal') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="amount">Amount to Withdraw</label>
                <div class="input-wrapper">
                    <span class="currency-prefix"></span>
                    <input style="width: calc(100% - 20px); padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px; box-sizing: border-box;" type="text" id="amount" name="amount" placeholder="Rp 0" required
                        value="{{ old('amount_raw') ? 'Rp ' . number_format(old('amount_raw'), 0, ',', '.') : '' }}"
                        autocomplete="off">
                    <input type="hidden" id="amount_raw" name="amount_raw" value="{{ old('amount_raw') }}">
                </div>
            </div>
            <script>
                // Format currency Rupiah
                function formatRupiah(angka) {
                    var number_string = angka.replace(/[^,\d]/g, '').toString(),
                        split = number_string.split(','),
                        sisa = split[0].length % 3,
                        rupiah = split[0].substr(0, sisa),
                        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                    if (ribuan) {
                        separator = sisa ? '.' : '';
                        rupiah += separator + ribuan.join('.');
                    }

                    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                    return 'Rp ' + rupiah;
                }

                function unformatRupiah(rupiah) {
                    return rupiah.replace(/[^\d]/g, '');
                }

                // Amount input formatting
                const amountInput = document.getElementById('amount');
                const amountRaw = document.getElementById('amount_raw');

                amountInput.addEventListener('input', function(e) {
                    let value = e.target.value;
                    let unformatted = unformatRupiah(value);
                    
                    if (unformatted !== '') {
                        let formatted = formatRupiah(unformatted);
                        e.target.value = formatted;
                        amountRaw.value = unformatted;
                    } else {
                        e.target.value = '';
                        amountRaw.value = '';
                    }
                });

                amountInput.addEventListener('blur', function(e) {
                    let value = e.target.value;
                    if (value === '' || value === 'Rp ') {
                        e.target.value = '';
                        amountRaw.value = '';
                    }
                });
            </script>

         <div class="form-group">
    <label for="method">Withdrawal Method</label>
    <input
        type="text"
        id="method"
        name="method"
        class="form-control"
        value="{{ old('method', $payoutDetail->method_type ?? '-') }}"
        readonly
        style="width: calc(100% - 20px); padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px; box-sizing: border-box;"
    >
</div>


           <div class="form-group" id="account-detail-group">
    <label for="account_detail">Account Number / Phone Number</label>
    <input
        type="text"
        id="account_detail"
        name="account_detail"
        class="form-control"
        placeholder="Enter account number or phone number"
        style="width: calc(100% - 20px); padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px; box-sizing: border-box;"
        value="{{ old('account_detail', $payoutDetail->account_number ?? '') }}"
        readonly
        required
    >
</div>


            <input type="hidden" id="account_name_hidden" name="account_name" value="{{ old('account_name', $payoutDetail->account_name ?? '') }}">

            <div class="form-group" id="bank_name_group" style="display: none;">
                <label for="bank_name">Bank Name</label>
                <input type="text" id="bank_name" name="bank_name" class="form-control" value="{{ old('bank_name', $payoutDetail->bank_name ?? '') }}" placeholder="e.g., Bank BJB">
            </div>

            <div class="form-actions">
                <button type="submit">Withdraw Now</button>
                <button type="button" onclick="window.history.back()" class="btn-cancel">Cancel</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const methodSelect = document.getElementById('method');
            const bankNameGroup = document.getElementById('bank_name_group');
            const bankNameInput = document.getElementById('bank_name');

            function toggleBankNameField() {
                if (methodSelect.value === 'Bank') {
                    bankNameGroup.style.display = 'block';
                    bankNameInput.setAttribute('required', 'required');
                } else {
                    bankNameGroup.style.display = 'none';
                    bankNameInput.removeAttribute('required');
                }
            }

            // Initial check on page load
            toggleBankNameField();

            // Add event listener for changes
            methodSelect.addEventListener('change', toggleBankNameField);
        });
    </script>
</body>
</html> 