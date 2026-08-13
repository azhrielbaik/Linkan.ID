@extends("layouts.admin")

@section("page_title", __('admin.form'))

@push("styles")
<link rel="stylesheet" href="{{ asset('css/pages/payout-method-form.css') }}" data-turbo-track="reload">
@endpush

@section("content")
<div class="dashboard-payout-method-page">

<div class="card">
        <h2>{{ __('admin.set_payout_method') }}</h2>

        @if ($errors->any())
            <div class="alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.payout.method.save') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="method_type">{{ __('admin.method_type') }}</label>
                <select id="method_type" name="method_type" class="form-control" required>
                    <option value="">{{ __('admin.select_method') }}</option>
                    <option value="Bank" {{ old('method_type', $payoutDetail->method_type ?? '') == 'Bank' ? 'selected' : '' }}>{{ __('admin.via_bank') }}</option>
                    <option value="DANA" {{ old('method_type', $payoutDetail->method_type ?? '') == 'DANA' ? 'selected' : '' }}>DANA</option>
                    <option value="ShopeePay" {{ old('method_type', $payoutDetail->method_type ?? '') == 'ShopeePay' ? 'selected' : '' }}>ShopeePay</option>
                </select>
            </div>

            <div class="form-group">
                <label for="account_name">{{ __('admin.account_name') }}</label>
                <input type="text" id="account_name" name="account_name" class="form-control" value="{{ old('account_name', $payoutDetail->account_name ?? '') }}" placeholder="{{ __('admin.account_name_placeholder') }}" required>
            </div>

            <div class="form-group">
                <label for="account_number">{{ __('admin.account_number_phone') }}</label>
                <input type="text" id="account_number" name="account_number" class="form-control" value="{{ old('account_number', $payoutDetail->account_number ?? '') }}" placeholder="{{ __('admin.account_number_placeholder') }}" required>
            </div>

            <div class="form-group" id="bank_name_group" style="display: none;">
                <label for="bank_name">{{ __('admin.bank_name') }}</label>
                <input type="text" id="bank_name" name="bank_name" class="form-control" value="{{ old('bank_name', $payoutDetail->bank_name ?? '') }}" placeholder="{{ __('admin.bank_name_placeholder') }}">
            </div>

            <div class="form-actions">
                <button type="submit">{{ __('admin.save_payout_method') }}</button>
                <button type="button" onclick="window.history.back()" class="btn-cancel">{{ __('admin.cancel') }}</button>
            </div>
        </form>
    </div>

</div>
@endsection

@push("scripts")
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
@endpush
