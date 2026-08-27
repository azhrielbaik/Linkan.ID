<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Payout — Linkan Platform</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/platform/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/platform/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/platform/notifications.css') }}">
    <link rel="stylesheet" href="{{ asset('css/platform/payouts.css') }}">
</head>
<body>

    {{-- Sidebar --}}
    @include('platformadmin.sidebar.sidebarplatform')

    <div class="platform-main">
        {{-- Header --}}
        <div class="platform-header">
            <div class="platform-header-left">
                <button class="hamburger-btn" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
                <h1>{{ __('platform.payout_management') }}</h1>
            </div>
            <div class="header-right">
                @include('platformadmin.partials.notifications')
                <div class="header-user">
                    <div class="header-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                    <span>{{ Auth::user()->name }}</span>
                </div>
            </div>
        </div>

        <div class="content-wrapper">
            {{-- Stats Grid --}}
            <div class="stats-grid">
                <div class="stat-card pending">
                    <div class="stat-icon-wrapper"><i class="fas fa-clock"></i></div>
                    <div class="stat-info">
                        <div class="stat-label">{{ __('platform.pending_process') }}</div>
                        <div class="stat-val">{{ $totalPendingCount }} Request</div>
                        <div class="stat-sub">Rp {{ number_format($totalPendingAmount, 0, ',', '.') }}</div>
                    </div>
                </div>

                <div class="stat-card approved">
                    <div class="stat-icon-wrapper"><i class="fas fa-check-circle"></i></div>
                    <div class="stat-info">
                        <div class="stat-label">{{ __('platform.payout_approved') }}</div>
                        <div class="stat-val">Rp {{ number_format($totalApprovedAmount, 0, ',', '.') }}</div>
                        <div class="stat-sub">{{ $totalApprovedCount }} {{ __('platform.approved_status') }}</div>
                    </div>
                </div>

                <div class="stat-card rejected">
                    <div class="stat-icon-wrapper"><i class="fas fa-times-circle"></i></div>
                    <div class="stat-info">
                        <div class="stat-label">{{ __('platform.payout_rejected') }}</div>
                        <div class="stat-val">{{ $totalRejectedCount }} Request</div>
                        <div class="stat-sub">{{ __('platform.rejected_status') }}</div>
                    </div>
                </div>

                <div class="stat-card commission">
                    <div class="stat-icon-wrapper"><i class="fas fa-coins"></i></div>
                    <div class="stat-info">
                        <div class="stat-label">{{ __('platform.platform_commission_5') }}</div>
                        <div class="stat-val">Rp {{ number_format($totalCommissionEarned, 0, ',', '.') }}</div>
                        <div class="stat-sub">{{ __('platform.total_commission') }}</div>
                    </div>
                </div>
            </div>

            {{-- Tabs --}}
            <div class="tabs-container">
                <a href="{{ route('platform-admin.payouts.index', array_merge(request()->except('tab', 'page'), ['tab' => 'all'])) }}"
                   class="tab-link {{ ($tab ?? 'all') === 'all' ? 'active' : '' }}">
                    {{ __('platform.all_requests') }}
                </a>
                <a href="{{ route('platform-admin.payouts.index', array_merge(request()->except('tab', 'page'), ['tab' => 'pending'])) }}"
                   class="tab-link {{ ($tab ?? '') === 'pending' ? 'active' : '' }}">
                    {{ __('platform.pending_verification') }}
                    @if($totalPendingCount > 0)
                        <span class="tab-counter">{{ $totalPendingCount }}</span>
                    @endif
                </a>
                <a href="{{ route('platform-admin.payouts.index', array_merge(request()->except('tab', 'page'), ['tab' => 'approved'])) }}"
                   class="tab-link {{ ($tab ?? '') === 'approved' ? 'active' : '' }}">
                    {{ __('platform.approved_status') }}
                </a>
                <a href="{{ route('platform-admin.payouts.index', array_merge(request()->except('tab', 'page'), ['tab' => 'rejected'])) }}"
                   class="tab-link {{ ($tab ?? '') === 'rejected' ? 'active' : '' }}">
                    {{ __('platform.rejected_status') }}
                </a>
            </div>

            {{-- Filter & Search Card --}}
            <div class="filter-card">
                <form method="GET" action="{{ route('platform-admin.payouts.index') }}" class="search-form">
                    <input type="hidden" name="tab" value="{{ $tab }}">
                    
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="{{ __('platform.search_payout_placeholder') }}">
                    </div>

                    <select name="method" class="filter-select">
                        <option value="">{{ __('platform.all_methods') }}</option>
                        <option value="Bank" {{ ($method ?? '') === 'Bank' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="DANA" {{ ($method ?? '') === 'DANA' ? 'selected' : '' }}>DANA</option>
                        <option value="ShopeePay" {{ ($method ?? '') === 'ShopeePay' ? 'selected' : '' }}>ShopeePay</option>
                    </select>

                    <div style="display: flex; align-items: center; gap: 6px;">
                        <input type="date" name="start_date" value="{{ $startDate ?? '' }}" class="date-input" title="{{ __('platform.from_date') }}">
                        <span style="color: #94a3b8;">-</span>
                        <input type="date" name="end_date" value="{{ $endDate ?? '' }}" class="date-input" title="{{ __('platform.to_date') }}">
                    </div>

                    <button type="submit" class="btn-filter"><i class="fas fa-filter"></i> {{ __('platform.filter') }}</button>
                    @if($search || $method || $startDate || $endDate)
                        <a href="{{ route('platform-admin.payouts.index', ['tab' => $tab]) }}" class="btn-reset">{{ __('platform.reset') }}</a>
                    @endif
                </form>
            </div>

            {{-- Table Card --}}
            <div class="table-card">
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('platform.seller') }}</th>
                                <th>{{ __('platform.account_details') }}</th>
                                <th>{{ __('platform.net_amount') }}</th>
                                <th>{{ __('platform.gross_and_fee') }}</th>
                                <th>{{ __('platform.request_date') }}</th>
                                <th>{{ __('platform.status') }}</th>
                                <th style="text-align: center;">{{ __('platform.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payouts as $index => $payout)
                            <tr>
                                <td style="font-weight: 700; color: #94a3b8;">
                                    {{ $payouts->firstItem() + $index }}
                                </td>
                                <td>
                                    <div class="seller-cell">
                                        <div class="seller-avatar">
                                            {{ strtoupper(substr($payout->user->name ?? 'User', 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="seller-name">{{ $payout->user->name ?? '-' }}</div>
                                            <div class="seller-email">{{ $payout->user->email ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $methodClass = 'method-' . strtolower($payout->method);
                                        $icon = $payout->method === 'Bank' ? 'university' : 'wallet';
                                    @endphp
                                    <div class="method-tag {{ $methodClass }}">
                                        <i class="fas fa-{{ $icon }}"></i> {{ $payout->method }} {{ $payout->bank_name ? '(' . $payout->bank_name . ')' : '' }}
                                    </div>
                                    <div class="account-name-val">{{ $payout->account_name ?? '-' }}</div>
                                    <div class="account-num-val">{{ $payout->account_number ?? '-' }}</div>
                                </td>
                                <td>
                                    <div class="amount-net">Rp {{ number_format($payout->amount, 0, ',', '.') }}</div>
                                </td>
                                <td>
                                    <div style="font-weight: 600; color: #475569;">
                                        Rp {{ number_format($payout->gross_amount ?? $payout->amount, 0, ',', '.') }}
                                    </div>
                                    <div class="amount-breakdown">
                                        {{ __('platform.fee') }}: Rp {{ number_format($payout->commission ?? 0, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td style="white-space: nowrap; color: #64748b; font-size: 12px;">
                                    {{ $payout->created_at->format('d M Y, H:i') }}
                                </td>
                                <td>
                                    @if($payout->status === 'approved')
                                        <span class="badge badge-approved">
                                            <i class="fas fa-check-circle"></i> {{ __('platform.approved_status') }}
                                        </span>
                                    @elseif($payout->status === 'rejected')
                                        <span class="badge badge-rejected">
                                            <i class="fas fa-times-circle"></i> {{ __('platform.rejected_status') }}
                                        </span>
                                    @else
                                        <span class="badge badge-pending">
                                            <i class="fas fa-clock"></i> {{ __('platform.pending_status') }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($payout->status === 'pending')
                                        <div class="action-btns">
                                            <form action="{{ route('platform-admin.payouts.approve', $payout->id) }}" method="POST" style="margin: 0;">
                                                @csrf
                                                <button type="button" class="btn-act btn-approve" onclick="confirmApprovePayout(this.form, '{{ addslashes($payout->user->name ?? 'Seller') }}', 'Rp {{ number_format($payout->amount, 0, ',', '.') }}')">
                                                    <i class="fas fa-check"></i> {{ __('platform.approve') }}
                                                </button>
                                            </form>
                                            <button type="button" class="btn-act btn-reject" onclick="showRejectModal({{ $payout->id }}, '{{ addslashes($payout->user->name ?? 'Seller') }}', 'Rp {{ number_format($payout->amount, 0, ',', '.') }}')">
                                                <i class="fas fa-times"></i> {{ __('platform.reject') }}
                                            </button>
                                        </div>
                                    @elseif($payout->status === 'rejected')
                                        <button type="button" class="btn-act btn-detail" onclick="showReasonModal('{{ addslashes($payout->rejection_reason ?? 'Tidak ada catatan.') }}', '{{ $payout->processed_at ? $payout->processed_at->format('d M Y H:i') : '-' }}')">
                                            <i class="fas fa-info-circle"></i> {{ __('platform.reason') }}
                                        </button>
                                    @else
                                        <span style="font-size: 11px; color: #94a3b8;">
                                            {{ $payout->processed_at ? $payout->processed_at->format('d M Y') : 'OK' }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <i class="fas fa-inbox"></i>
                                        <p>{{ __('platform.no_payouts_found') }}</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($payouts->hasPages())
                    <div class="pagination-container">
                        {{ $payouts->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    <!-- Modal Reject Payout -->
    <div id="rejectPayoutModal" class="modal">
        <div class="modal-card">
            <div class="modal-header">
                <h3><i class="fas fa-times-circle" style="color: #ef4444; margin-right: 6px;"></i>{{ __('platform.reject_withdraw_title') }}</h3>
                <button class="modal-close" onclick="closeRejectModal()">&times;</button>
            </div>
            <form id="rejectPayoutForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div style="background: #fef2f2; border: 1px solid #fee2e2; border-radius: 10px; padding: 12px 16px; margin-bottom: 16px; font-size: 13px; color: #991b1b;">
                        <i class="fas fa-info-circle"></i> {{ __('platform.reject_withdraw_warning') }}
                    </div>

                    <div style="margin-bottom: 14px; font-size: 14px;">
                        <div><strong>{{ __('platform.seller') }}:</strong> <span id="modalSellerName">-</span></div>
                        <div><strong>{{ __('platform.amount') }}:</strong> <span id="modalPayoutAmount">-</span></div>
                    </div>

                    <div class="form-group-custom">
                        <label>{{ __('platform.rejection_reason') }} <span style="color: #ef4444;">*</span></label>
                        <textarea name="rejection_reason" class="form-control-custom" rows="4" required placeholder="{{ __('platform.enter_rejection_reason') }}"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal-cancel" onclick="closeRejectModal()">{{ __('platform.cancel') }}</button>
                    <button type="submit" class="btn-modal-submit"><i class="fas fa-times"></i> {{ __('platform.reject_and_refund') }}</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Detail Alasan Penolakan -->
    <div id="reasonDetailModal" class="modal">
        <div class="modal-card">
            <div class="modal-header">
                <h3><i class="fas fa-info-circle" style="color: #ED842C; margin-right: 6px;"></i>{{ __('platform.rejection_notes') }}</h3>
                <button class="modal-close" onclick="closeReasonModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div style="margin-bottom: 12px; font-size: 12px; color: #94a3b8;">
                    {{ __('platform.processed_at') }}: <span id="reasonProcessedAt" style="color: #1e293b; font-weight: 600;">-</span>
                </div>
                <div class="form-group-custom">
                    <label>{{ __('platform.rejection_reason') }}:</label>
                    <div id="reasonContent" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px; font-size: 14px; line-height: 1.6; white-space: pre-wrap;"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-modal-cancel" onclick="closeReasonModal()">{{ __('platform.close') }}</button>
            </div>
        </div>
    </div>

    <script>
        window.PlatformPayoutsConfig = {
            payoutsBaseUrl: '{{ url('platform-admin/payouts') }}',
            approveText: '{{ __('platform.approve') }}'
        };
    </script>
    <script src="{{ asset('js/platform/notifications.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/platform/payouts.js') }}"></script>
</body>
</html>
