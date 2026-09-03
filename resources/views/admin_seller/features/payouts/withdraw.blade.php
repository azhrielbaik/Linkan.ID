@extends("admin_seller.layouts.app")

@section("page_title", __('admin.form'))

@push("styles")
<link rel="stylesheet" href="{{ asset('css/pages/withdraw-form.css') }}" data-turbo-track="reload">
@endpush

@section("content")
<div class="dashboard-withdraw-page">

<div class="withdraw-card">
        <h2>{{ __('admin.withdraw_funds') }}</h2>
        <p>{{ __('admin.current_balance') }} Rp {{ number_format($currentEarnings ?? 0, 0, ',', '.') }}</p>

        @if ($errors->any())
            <div class="alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.payout.withdraw.process') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="amount">{{ __('admin.amount_to_withdraw') }}</label>
                <div class="input-wrapper">
                    <span class="currency-prefix"></span>
                    <input style="width: calc(100% - 20px); padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px; box-sizing: border-box;" type="text" id="amount" name="amount" placeholder="Rp 0" required
                        value="{{ old('amount_raw') ? 'Rp ' . number_format(old('amount_raw'), 0, ',', '.') : '' }}"
                        autocomplete="off">
                    <input type="hidden" id="amount_raw" name="amount_raw" value="{{ old('amount_raw') }}">
                </div>
                <small style="color: #64748b; font-size: 12px; margin-top: 4px; display: block;">
                    * Minimum penarikan: <strong>Rp {{ number_format($minWithdraw ?? 10000, 0, ',', '.') }}</strong> | Potongan komisi platform: <strong>{{ $commissionPercent ?? 5 }}%</strong>
                </small>
            </div>
            

         <div class="form-group">
    <label for="method">{{ __('admin.withdrawal_method') }}</label>
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
    <label for="account_detail">{{ __('admin.account_number_phone') }}</label>
    <input
        type="text"
        id="account_detail"
        name="account_detail"
        class="form-control"
        placeholder="{{ __('admin.account_number_placeholder') }}"
        style="width: calc(100% - 20px); padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px; box-sizing: border-box;"
        value="{{ old('account_detail', $payoutDetail->account_number ?? '') }}"
        readonly
        required
    >
</div>


            <input type="hidden" id="account_name_hidden" name="account_name" value="{{ old('account_name', $payoutDetail->account_name ?? '') }}">

            <div class="form-group" id="bank_name_group" style="display: none;">
                <label for="bank_name">{{ __('admin.bank_name') }}</label>
                <input type="text" id="bank_name" name="bank_name" class="form-control" value="{{ old('bank_name', $payoutDetail->bank_name ?? '') }}" placeholder="{{ __('admin.bank_name_placeholder') }}">
            </div>

            <div class="form-actions">
                <button type="submit">{{ __('admin.withdraw_now') }}</button>
                <button type="button" onclick="window.history.back()" class="btn-cancel">{{ __('admin.cancel') }}</button>
            </div>
        </form>
    </div>

</div>
@endsection

@push("scripts")
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
@endpush
