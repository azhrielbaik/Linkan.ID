@extends("layouts.admin")

@section("page_title", __('admin.payout_title'))

@push("styles")
<link rel="stylesheet" href="{{ asset('css/pages/payout.css') }}" data-turbo-track="reload">
@endpush

@section("content")
<div class="dashboard-payout-page">

    @if(session('success'))
        <div style="background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 12px 18px; margin-bottom: 20px; border-radius: 10px; font-weight: 500;">
            {{ session('success') }}
        </div>
    @endif

    <div class="payout-main-flex">
        <!-- Earnings Card -->
        <div class="earnings-card" style="padding: 30px 24px; border-radius: 20px; background: #5A5BF1; color: white; box-shadow: 0 4px 16px rgba(90,91,241,0.2); margin-bottom: 0; display: flex; flex-direction: column; justify-content: space-between;">
            <h2 style="font-size: 22px; font-weight: 800; margin-bottom: 24px; letter-spacing: -0.5px;">{{ __('admin.my_earnings') }}</h2>
            <div style="display: flex; flex-wrap: wrap; gap: 16px; justify-content: space-between;">
                <div style="flex: 1 1 180px; min-width: 180px; background: rgba(255,255,255,0.14); border-radius: 14px; padding: 18px 16px; display: flex; align-items: center; gap: 16px;">
                    <div style="background: white; color: #5A5BF1; border-radius: 50%; width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; font-size: 20px;"><i class="fas fa-wallet"></i></div>
                    <div>
                        <div style="font-size: 12.5px; opacity: 0.9; font-weight: 500;">{{ __('admin.total_income') }}</div>
                        <div style="font-size: 20px; font-weight: 800;">Rp {{ number_format($totalEarnings, 0, ',', '.') }}</div>
                    </div>
                </div>
                <div style="flex: 1 1 180px; min-width: 180px; background: rgba(255,255,255,0.14); border-radius: 14px; padding: 18px 16px; display: flex; align-items: center; gap: 16px;">
                    <div style="background: white; color: #10B981; border-radius: 50%; width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; font-size: 20px;"><i class="fas fa-arrow-circle-up"></i></div>
                    <div>
                        <div style="font-size: 12.5px; opacity: 0.9; font-weight: 500;">{{ __('admin.total_withdrawal') }}</div>
                        <div style="font-size: 20px; font-weight: 800;">Rp {{ number_format($totalWithdrawn, 0, ',', '.') }}</div>
                    </div>
                </div>
                <div style="flex: 1 1 180px; min-width: 180px; background: rgba(255,255,255,0.14); border-radius: 14px; padding: 18px 16px; display: flex; align-items: center; gap: 16px;">
                    <div style="background: white; color: #3B82F6; border-radius: 50%; width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; font-size: 20px;"><i class="fas fa-coins"></i></div>
                    <div>
                        <div style="font-size: 12.5px; opacity: 0.9; font-weight: 500;">{{ __('admin.withdrawable_balance') }}</div>
                        <div style="font-size: 20px; font-weight: 800;">Rp {{ number_format($currentBalance, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
            <div style="display: flex; gap: 14px; margin-top: 28px;">
                <a href="{{ route('admin.payout.withdraw') }}" class="btn btn-withdraw" style="background: white; color: #5A5BF1; border: none; font-weight: 700; border-radius: 10px; padding: 12px 28px; font-size: 14.5px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); display: flex; align-items: center; gap: 8px; text-decoration: none; transition: all 0.2s;">
                    <i class="fas fa-paper-plane"></i> {{ __('admin.withdraw') }}
                </a>
                <a href="{{ route('admin.payout.history') }}" class="btn btn-history" style="background: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.4); font-weight: 700; border-radius: 10px; padding: 12px 28px; font-size: 14.5px; display: flex; align-items: center; gap: 8px; text-decoration: none; transition: all 0.2s;">
                    <i class="fas fa-history"></i> {{ __('admin.history') }}
                </a>
            </div>
        </div>

        <!-- Payment Card -->
        <div class="payment-card" style="background: #5A5BF1; border-radius: 20px; box-shadow: 0 4px 16px rgba(90,91,241,0.2); padding: 32px 24px; text-align: center; color: white; display: flex; flex-direction: column; justify-content: center;">
            <h2 style="font-size: 20px; font-weight: 800; color: white; margin-bottom: 8px; letter-spacing: -0.5px;">{{ __('admin.receipt_method') }}</h2>
            <p style="color: rgba(255,255,255,0.9); font-size: 13.5px; margin-bottom: 24px;">{{ __('admin.funds_transferred_to') }}</p>
            @if($payoutDetail)
            <div class="bank-info" style="display: flex; align-items: center; gap: 18px; background: rgba(255,255,255,0.14); border-radius: 14px; padding: 18px; margin-bottom: 18px; justify-content: center;">
                @if($payoutDetail->method_type === 'Bank')
                    <img src="/images/creditcard.png" alt="Bank" style="width: 50px; height: 50px; border-radius: 8px; background: #fff; padding: 6px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                @elseif($payoutDetail->method_type === 'DANA')
                    <img src="/images/dana.png" alt="DANA" style="width: 50px; height: 50px; border-radius: 8px; background: #fff; padding: 6px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                @elseif($payoutDetail->method_type === 'ShopeePay')
                    <img src="/images/shopeepay.png" alt="ShopeePay" style="width: 50px; height: 50px; border-radius: 8px; background: #fff; padding: 6px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                @else
                    <i class="fas fa-wallet" style="font-size: 32px; color: #5A5BF1; background: #fff; border-radius: 8px; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;"></i>
                @endif
                <div style="text-align: left;">
                    <div style="font-size: 16px; font-weight: 700; color: white;">{{ $payoutDetail->account_name }}</div>
                    <div style="font-size: 13.5px; color: rgba(255,255,255,0.9); margin-top: 2px;">{{ $payoutDetail->method_type }} - {{ $payoutDetail->account_number }}</div>
                    @if($payoutDetail->method_type === 'Bank' && $payoutDetail->bank_name)
                        <div style="font-size: 12.5px; color: rgba(255,255,255,0.8); margin-top: 2px;">{{ $payoutDetail->bank_name }}</div>
                    @endif
                </div>
            </div>
            @else
            <div style="padding: 24px; color: rgba(255,255,255,0.9); background: rgba(255,255,255,0.14); border-radius: 14px; margin-bottom: 18px; font-size: 14px;">
                <p>{{ __('admin.no_payment_method') }}</p>
            </div>
            @endif
            <a href="{{ route('admin.payout.method') }}" class="btn" style="margin-top: 10px; display: inline-block; background: white; color: #5A5BF1; font-weight: 700; border-radius: 10px; padding: 12px 32px; font-size: 14.5px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); text-decoration: none; transition: all 0.2s;">
                <i class="fas fa-cog"></i> {{ $payoutDetail ? __('admin.edit_payout_method') : __('admin.set_payout_method') }}
            </a>
        </div>
    </div>

</div>
@endsection
