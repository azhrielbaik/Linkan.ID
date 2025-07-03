<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Payout Method</title>
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
        .card {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            text-align: center;
        }
        .card h2 {
            margin-bottom: 20px;
            color: #333;
        }
        .form-group {
            margin-bottom: 18px;
            text-align: left;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }
        .form-control {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
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
    <div class="card">
        <h2>Set Payout Method</h2>

        @if ($errors->any())
            <div class="alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('payout.saveMethod') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="method_type">Method Type</label>
                <select id="method_type" name="method_type" class="form-control" required>
                    <option value="">Select a method</option>
                    <option value="Bank" {{ old('method_type', $payoutDetail->method_type ?? '') == 'Bank' ? 'selected' : '' }}>Via Bank</option>
                    <option value="DANA" {{ old('method_type', $payoutDetail->method_type ?? '') == 'DANA' ? 'selected' : '' }}>DANA</option>
                    <option value="ShopeePay" {{ old('method_type', $payoutDetail->method_type ?? '') == 'ShopeePay' ? 'selected' : '' }}>ShopeePay</option>
                </select>
            </div>

            <div class="form-group">
                <label for="account_name">Account Name</label>
                <input type="text" id="account_name" name="account_name" class="form-control" value="{{ old('account_name', $payoutDetail->account_name ?? '') }}" placeholder="e.g., Budi Fulan" required>
            </div>

            <div class="form-group">
                <label for="account_number">Account Number / Phone Number</label>
                <input type="text" id="account_number" name="account_number" class="form-control" value="{{ old('account_number', $payoutDetail->account_number ?? '') }}" placeholder="Enter account number or phone number" required>
            </div>

            <div class="form-group" id="bank_name_group" style="display: none;">
                <label for="bank_name">Bank Name</label>
                <input type="text" id="bank_name" name="bank_name" class="form-control" value="{{ old('bank_name', $payoutDetail->bank_name ?? '') }}" placeholder="e.g., Bank BJB">
            </div>

            <div class="form-actions">
                <button type="submit">Save Payout Method</button>
                <button type="button" onclick="window.history.back()" class="btn-cancel">Cancel</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const methodTypeSelect = document.getElementById('method_type');
            const bankNameGroup = document.getElementById('bank_name_group');
            const bankNameInput = document.getElementById('bank_name');

            function toggleBankNameField() {
                if (methodTypeSelect.value === 'Bank') {
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
            methodTypeSelect.addEventListener('change', toggleBankNameField);
        });
    </script>
</body>
</html> 