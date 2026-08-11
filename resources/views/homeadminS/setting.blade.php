@extends("layouts.admin")

@section("page_title", "Settings")

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
                    <h3>My Account</h3>
                    <p>Account detail, shop information, etc</p>
                </div>
            </div>

            <div class="settings-card" onclick="window.location.href='{{ route('admin.payout.index') }}'">
                <div class="settings-card-icon">
                    <i class="fas fa-wallet"></i>
                </div>
                <div class="settings-card-content">
                    <h3>Payout Settings</h3>
                    <p>Withdraw earnings, Bank account, etc</p>
                </div>
            </div>
            </div>

</div>
@endsection

