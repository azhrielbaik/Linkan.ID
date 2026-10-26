<!-- Recent Transactions (Your Lesson Alternative) -->
<div class="recent-transactions-section">
    <div class="section-header">
        <h3>{{ __('dashboard.recent_transactions') }}</h3>
        <a href="{{ route('admin.orders') }}" class="see-all">{{ __('dashboard.see_all') }}</a>
    </div>
    
    <div class="transactions-list">
        <table class="tx-table">
            <thead>
                <tr>
                    <th>{{ __('dashboard.th_buyer') }}</th>
                    <th>{{ __('dashboard.th_product') }}</th>
                    <th>{{ __('dashboard.th_status') }}</th>
                    <th>{{ __('dashboard.th_action') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentTransactions ?? [] as $tx)
                    <tr class="tx-row">
                        <td class="td-buyer" data-label="{{ __('dashboard.th_buyer') }}">
                            <div class="tx-buyer">
                                <div class="tx-avatar">{{ strtoupper(substr($tx->buyer_name, 0, 1)) }}</div>
                                <div class="tx-buyer-info">
                                    <span class="tx-name">{{ $tx->buyer_name }}</span>
                                    <span class="tx-date">{{ \Carbon\Carbon::parse($tx->created_at)->format('d/m/Y') }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="td-product" data-label="{{ __('dashboard.th_product') }}">
                            <div class="tx-item-title">{{ $tx->product_title }}</div>
                        </td>
                        <td class="td-status" data-label="{{ __('dashboard.th_status') }}">
                            @php
                                $txStatus = $tx->status instanceof \BackedEnum ? $tx->status->value : (string) $tx->status;
                            @endphp
                            @if($txStatus === 'success')
                                <span class="tx-status"><i class="fas fa-check"></i> {{ __('dashboard.status_success') }}</span>
                            @elseif($txStatus === 'pending')
                                <span class="tx-status pending"><i class="fas fa-clock"></i> {{ __('dashboard.status_pending') }}</span>
                            @else
                                <span class="tx-status failed"><i class="fas fa-times"></i> {{ ucfirst($txStatus) }}</span>
                            @endif
                        </td>
                        <td class="td-action" data-label="{{ __('dashboard.th_action') }}">
                            <a href="{{ route('admin.orders') }}" class="tx-action" aria-label="Detail Pesanan"><i class="fas fa-arrow-right"></i></a>
                        </td>
                    </tr>
                @empty
                    <tr class="tx-empty-row">
                        <td colspan="4" class="td-empty" style="text-align: center; padding: 30px; color: #64748b;">{{ __('dashboard.no_recent_transactions') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
