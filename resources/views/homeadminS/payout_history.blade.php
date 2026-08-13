@extends("layouts.admin")

@section("page_title", __('admin.payout_history'))

@push("styles")
<link rel="stylesheet" href="{{ asset('css/pages/payout-history.css') }}" data-turbo-track="reload">
@endpush

@section("content")
<div class="dashboard-payout-history-page">



            <div class="history-container">
                <div class="history-header">
                    <i class="fas fa-history"></i>
                    <h2>{{ __('admin.payout_history') }}</h2>
                </div>

                <!-- Stats Cards -->
                <div class="stats-cards">
                    <div class="stat-card">
                        <i class="fas fa-wallet"></i>
                        <div class="stat-number">{{ $history->count() }}</div>
                        <div class="stat-label">{{ __('admin.total_transaction') }}</div>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-money-bill-wave"></i>
                        <div class="stat-number">Rp {{ number_format($history->sum('amount'), 0, ',', '.') }}</div>
                        <div class="stat-label">{{ __('admin.total_withdrawal') }}</div>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-chart-line"></i>
                        <div class="stat-number">{{ $history->groupBy('method')->count() }}</div>
                        <div class="stat-label">{{ __('admin.payment_method') }}</div>
                    </div>
                </div>

                @if ($history->isEmpty())
                    <div class="no-records">
                        <i class="fas fa-inbox"></i>
                        <p>{{ __('admin.no_withdrawal_history') }}</p>
                    </div>
                @else
                    <div class="history-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>User ID</th>
                                    <th>{{ __('admin.amount') }}</th>
                                    <th>{{ __('admin.payment_method') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($history as $record)
                                    <tr>
                                        <td>{{ $record->user_id }}</td>
                                        <td class="amount-cell">Rp {{ number_format($record->amount, 0, ',', '.') }}</td>
                                        <td>
                                            <div class="method-cell">
                                                <div class="method-icon method-{{ strtolower($record->method) }}">
                                                    <i class="fas fa-{{ $record->method == 'Bank' ? 'university' : 'wallet' }}"></i>
                                                </div>
                                                {{ $record->method }}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <!-- Floating Action Button -->
        <div class="floating-action" onclick="window.location.href='{{ route('admin.payout.index') }}'">
            <i class="fas fa-arrow-left"></i>

</div>
@endsection

