@extends("layouts.admin")

@section("page_title", "Settings")

@push("styles")
<style>
.header h1 {
            font-size: 24px;
            color: #333;
        }

        .settings-card {
            display: flex;
            align-items: center;
            background: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .settings-card:hover {
            background-color: #f0f0f0;
        }

        .settings-card-icon {
            width: 50px;
            height: 50px;
            background: #FFE5D3;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }

        .settings-card-icon i {
            color: #FF9040;
            font-size: 24px;
        }

        .settings-card-content h3 {
            font-size: 18px;
            color: #333;
            margin-bottom: 5px;
        }

        .settings-card-content p {
            font-size: 14px;
            color: #666;
            margin: 0;
        }

        @media (max-width: 900px) {
            .main-content {
                margin-left: 0;
            }
        }
</style>
@endpush

@section("content")


            <div class="settings-card" onclick="window.location.href='{{ route('account.settings') }}'">
                <div class="settings-card-icon">
                    <i class="fas fa-user"></i>
                </div>
                <div class="settings-card-content">
                    <h3>My Account</h3>
                    <p>Account detail, shop information, etc</p>
                </div>
            </div>

            <div class="settings-card" onclick="window.location.href='{{ route('payout.index') }}'">
                <div class="settings-card-icon">
                    <i class="fas fa-wallet"></i>
                </div>
                <div class="settings-card-content">
                    <h3>Payout Settings</h3>
                    <p>Withdraw earnings, Bank account, etc</p>
                </div>
            </div>
            </div>
@endsection

