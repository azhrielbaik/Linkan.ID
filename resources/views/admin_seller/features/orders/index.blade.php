@extends("admin_seller.layouts.app")

@section("page_title", __('admin.orders_title'))

@section("content")
@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/order-history.css') }}">
@endpush

<div class="order-history-card">
    <div class="order-history-title">Pesanan Masuk</div>
    
    <form method="GET" action="{{ url()->current() }}" id="filterForm">
        <input type="hidden" name="status" value="{{ request('status') }}">
        <div class="oh-controls">
            <div class="oh-tabs">
                <a href="{{ request()->fullUrlWithQuery(['status' => '']) }}" class="oh-tab {{ request('status') == '' ? 'active' : '' }}" style="text-decoration: none;">All Order</a>
                <a href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}" class="oh-tab {{ request('status') == 'pending' ? 'active' : '' }}" style="text-decoration: none;">Pending</a>
                <a href="{{ request()->fullUrlWithQuery(['status' => 'success']) }}" class="oh-tab {{ request('status') == 'success' ? 'active' : '' }}" style="text-decoration: none;">Completed</a>
                <a href="{{ request()->fullUrlWithQuery(['status' => 'failed']) }}" class="oh-tab {{ request('status') == 'failed' ? 'active' : '' }}" style="text-decoration: none;">Cancelled</a>
            </div>
            
            <div style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
                <div class="oh-search">
                    <i class="fas fa-search" style="color:#94a3b8;"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search">
                </div>
                <div class="oh-date-picker">
                    <i class="far fa-calendar-alt"></i>
                    <input type="date" name="date" value="{{ request('date') }}" onchange="document.getElementById('filterForm').submit()">
                </div>
                <button type="submit" style="display: none;"></button>
            </div>
        </div>
    </form>

    <div style="overflow-x: auto;">
        <table class="oh-table" id="ordersTable">
            <thead>
                <tr>
                    <th>Id <i class="fas fa-caret-down"></i></th>
                    <th>Name</th>
                    <th>Payment</th>
                    <th>Date <i class="fas fa-caret-down"></i></th>
                    <th>Product</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th style="text-align: center;">Action</th>
                </tr>
            </thead>
            <tbody id="ordersListContainer">
                @forelse($transactions as $transaction)
                    @php
                        $productTitle = $transaction->product ? $transaction->product->title : 'Digital Product';
                        $bgColor = ['#f87171', '#fb923c', '#fbbf24', '#34d399', '#38bdf8', '#818cf8', '#a78bfa', '#f472b6'][$transaction->id % 8];
                        $buyerName = $transaction->buyer_name ?: 'Anonymous';
                        $initial = strtoupper(substr($buyerName, 0, 1));
                        
                        $statusClass = 'status-pending';
                        $statusText = 'Pending';
                        if ($transaction->status === 'success') {
                            $statusClass = 'status-success';
                            $statusText = 'Completed';
                        } elseif ($transaction->status === 'failed') {
                            $statusClass = 'status-failed';
                            $statusText = 'Cancelled';
                        }
                        
                        $paymentMethod = $transaction->payment_method ?: '-';
                        if ($paymentMethod !== '-') {
                            $parts = explode('-', $paymentMethod);
                            $mainType = ucwords(str_replace('_', ' ', $parts[0]));
                            if (strtolower($mainType) === 'qris') $mainType = 'QRIS';
                            if (strtolower($mainType) === 'gopay') $mainType = 'GoPay';
                            if (strtolower($mainType) === 'shopeepay') $mainType = 'ShopeePay';
                            
                            if (count($parts) > 1) {
                                $subType = strtoupper($parts[1]);
                                $paymentMethod = "{$mainType} ({$subType})";
                            } else {
                                $paymentMethod = $mainType;
                            }
                        }
                    @endphp
                    <tr class="mobile-row-click" onclick="if(window.innerWidth <= 768) openDetailModal({{ $transaction->id }})">
                        <td data-label="Id">#{{ $transaction->id }}</td>
                        <td data-label="Name">
                            <div class="buyer-info">
                                <div class="buyer-avatar" style="background-color: {{ $bgColor }};">{{ $initial }}</div>
                                {{ $buyerName }}
                            </div>
                        </td>
                        <td>{{ $paymentMethod }}</td>
                        <td><i class="far fa-clock" style="color: #cbd5e1; margin-right: 6px;"></i> {{ $transaction->created_at->format('H:i') }}</td>
                        <td style="color: #ED842C;">{{ \Illuminate\Support\Str::limit($productTitle, 15) }}</td>
                        <td>
                            <div class="status-badge {{ $statusClass }}">
                                <span class="status-dot"></span> {{ $statusText }}
                            </div>
                        </td>
                        <td>Rp{{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                        <td style="text-align: center; position: relative;">
                            <div class="action-dropdown">
                                <button class="action-btn" onclick="toggleDropdown({{ $transaction->id }}, event)">⋮</button>
                                <div id="dropdown_{{ $transaction->id }}" class="dropdown-content">
                                    <div class="dropdown-item" onclick="openDetailModal({{ $transaction->id }})" style="color: #ED842C;">Detail</div>
                                    <a href="mailto:{{ $transaction->buyer_email }}?subject=Order #{{ $transaction->id }}" class="dropdown-item">Message</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="oh-empty">No orders match your filter.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div id="ordersPaginationContainer" style="display: flex; justify-content: center; margin-top: 30px; width: 100%;">
        <div class="sl-pagination-container">
            @php
                $p = max(1, (int) request('p', 1));
            @endphp
            
            @if ($transactions->previousCursor())
                <a href="{{ $transactions->appends(request()->except(['cursor', 'p']))->previousPageUrl() }}&p={{ $p - 1 }}" class="sl-page-btn" style="text-decoration: none;">
                    <span>{{ $p - 1 }}</span>
                </a>
            @endif
            
            <button type="button" class="sl-page-btn active" style="pointer-events: none;">
                <span>{{ $p }}</span>
            </button>
            
            @if ($transactions->nextCursor())
                <a href="{{ $transactions->appends(request()->except(['cursor', 'p']))->nextPageUrl() }}&p={{ $p + 1 }}" class="sl-page-btn" style="text-decoration: none;">
                    <span>{{ $p + 1 }}</span>
                </a>
            @endif
        </div>
    </div>
</div>

<!-- Slide-out Panel Overlay -->
<div id="orderPanelOverlay" class="oh-panel-overlay" onclick="closeDetailPanel()"></div>

<!-- Slide-out Panel -->
<div id="orderDetailPanel" class="oh-side-panel">
    <div class="oh-panel-header">
        <h3 class="oh-panel-title" id="panelOrderTitle">Order Detail</h3>
        <button class="btn-close-panel" onclick="closeDetailPanel()"><i class="fas fa-times"></i></button>
    </div>
    <div class="oh-panel-body" id="orderDetailContent">
        <!-- Injected by AJAX -->
    </div>
</div>
@endsection

@push("scripts")
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function toggleDropdown(id, event) {
        event.stopPropagation();
        const dropdown = document.getElementById('dropdown_' + id);
        const btn = event.currentTarget;
        
        // Close others
        $('.dropdown-content').removeClass('show');
        $('.action-btn').removeClass('active');
        
        if (!dropdown.classList.contains('show')) {
            dropdown.classList.add('show');
            btn.classList.add('active');
        }
    }

    // Close dropdowns when clicking outside
    window.onclick = function(event) {
        if (!event.target.closest('.action-dropdown')) {
            $('.dropdown-content').removeClass('show');
            $('.action-btn').removeClass('active');
        }
    }

    window.openDetailModal = function(id) {
        $('.dropdown-content').removeClass('show');
        $('.action-btn').removeClass('active');
        
        $('#orderDetailContent').html(`<div style="text-align:center; padding: 40px; color: #94a3b8;"><i class="fas fa-circle-notch fa-spin"></i> Loading details...</div>`);
        
        const overlay = document.getElementById('orderPanelOverlay');
        const panel = document.getElementById('orderDetailPanel');
        
        overlay.style.display = 'block';
        setTimeout(() => {
            overlay.style.opacity = '1';
            panel.classList.add('is-open');
        }, 10);

        $.get(`/admin/orders/${id}`, function(htmlResponse) {
            $('#orderDetailContent').html(htmlResponse);
            const hiddenTitle = document.getElementById('panelOrderTitleHidden');
            if (hiddenTitle) {
                $('#panelOrderTitle').text(hiddenTitle.innerText);
            }
        });
    }

    window.closeDetailPanel = function() {
        const overlay = document.getElementById('orderPanelOverlay');
        const panel = document.getElementById('orderDetailPanel');
        
        panel.classList.remove('is-open');
        overlay.style.opacity = '0';
        
        setTimeout(() => { 
            overlay.style.display = 'none'; 
        }, 300);
    }
</script>
@endpush
