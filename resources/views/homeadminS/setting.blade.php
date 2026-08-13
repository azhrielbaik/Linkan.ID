@extends("layouts.admin")

@section("page_title", __('admin.settings_title'))

@push("styles")
<link rel="stylesheet" href="{{ asset('css/pages/setting.css') }}" data-turbo-track="reload">
@endpush

@section("content")
<div class="dashboard-setting-page">



            <div class="settings-card" onclick="window.location.href='{{ route('admin.account') }}'">
                <div class="settings-card-icon">
                    <i class="fas fa-user"></i>
                </div>
                <div class="settings-card-content">
                    <h3>{{ __('admin.my_account_title') }}</h3>
                    <p>{{ __('admin.my_account_desc') }}</p>
                </div>
            </div>

            <div class="settings-card" onclick="window.location.href='{{ route('admin.payout.index') }}'">
                <div class="settings-card-icon">
                    <i class="fas fa-wallet"></i>
                </div>
                <div class="settings-card-content">
                    <h3>{{ __('admin.payout_settings') }}</h3>
                    <p>{{ __('admin.payout_settings_desc') }}</p>
                </div>
            </div>
            </div>

</div>
@endsection

