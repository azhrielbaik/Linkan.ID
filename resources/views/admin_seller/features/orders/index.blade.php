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
                <a href="{{ request()->fullUrlWithQuery(['status' => 'success']) }}" class="oh-tab {{ in_array(request('status'), ['success', 'completed', 'complete']) ? 'active' : '' }}" style="text-decoration: none;">Completed</a>
                <a href="{{ request()->fullUrlWithQuery(['status' => 'failed']) }}" class="oh-tab {{ in_array(request('status'), ['failed', 'cancelled', 'cancel']) ? 'active' : '' }}" style="text-decoration: none;">Cancelled</a>
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
                        
                        $statusClass = $transaction->status_class;
                        $statusText = $transaction->status_label;
                        
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
<script>
    function toggleDropdown(id, event) {
        event.stopPropagation();
        const dropdown = document.getElementById('dropdown_' + id);
        const btn = event.currentTarget;
        const isShown = dropdown && dropdown.classList.contains('show');
        
        // Close others
        document.querySelectorAll('.dropdown-content').forEach(el => el.classList.remove('show'));
        document.querySelectorAll('.action-btn').forEach(el => el.classList.remove('active'));
        
        if (dropdown && !isShown) {
            dropdown.classList.add('show');
            if (btn) btn.classList.add('active');
        }
    }

    // Close dropdowns when clicking outside
    window.addEventListener('click', function(event) {
        if (!event.target.closest('.action-dropdown')) {
            document.querySelectorAll('.dropdown-content').forEach(el => el.classList.remove('show'));
            document.querySelectorAll('.action-btn').forEach(el => el.classList.remove('active'));
        }
    });

    const orderDetailBaseUrl = "{{ route('admin.orders.detail', ['id' => '__ID__']) }}";

    window.openDetailModal = function(id) {
        document.querySelectorAll('.dropdown-content').forEach(el => el.classList.remove('show'));
        document.querySelectorAll('.action-btn').forEach(el => el.classList.remove('active'));
        
        const contentContainer = document.getElementById('orderDetailContent');
        const titleContainer = document.getElementById('panelOrderTitle');
        const overlay = document.getElementById('orderPanelOverlay');
        const panel = document.getElementById('orderDetailPanel');

        if (titleContainer) {
            titleContainer.textContent = `Order #${id}`;
        }
        
        if (contentContainer) {
            contentContainer.innerHTML = `
                <div style="text-align:center; padding: 50px 20px; color: #94a3b8;">
                    <i class="fas fa-circle-notch fa-spin" style="font-size: 28px; color: #ED842C; margin-bottom: 12px; display: inline-block;"></i>
                    <p style="font-size: 14px; margin: 0; font-weight: 500;">Memuat detail pesanan...</p>
                </div>
            `;
        }
        
        if (overlay) {
            overlay.style.display = 'block';
            setTimeout(() => {
                overlay.style.opacity = '1';
                if (panel) panel.classList.add('is-open');
            }, 10);
        }

        const fetchUrl = orderDetailBaseUrl.replace('__ID__', id);

        fetch(fetchUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }
            return response.text();
        })
        .then(htmlResponse => {
            if (contentContainer) {
                contentContainer.innerHTML = htmlResponse;
            }
            const hiddenTitle = document.getElementById('panelOrderTitleHidden');
            if (hiddenTitle && titleContainer) {
                titleContainer.textContent = hiddenTitle.textContent.trim();
            }
        })
        .catch(error => {
            console.error('Error fetching order detail:', error);
            if (contentContainer) {
                contentContainer.innerHTML = `
                    <div style="text-align: center; padding: 45px 20px; color: #ef4444;">
                        <i class="fas fa-exclamation-triangle" style="font-size: 32px; color: #f59e0b; margin-bottom: 12px; display: inline-block;"></i>
                        <h4 style="font-size: 16px; font-weight: 700; color: #1e293b; margin-bottom: 6px;">Gagal Memuat Detail</h4>
                        <p style="font-size: 13px; color: #64748b; margin-bottom: 20px;">Terjadi kendala saat mengambil data pesanan. Silakan coba kembali.</p>
                        <button type="button" onclick="openDetailModal(${id})" style="padding: 9px 20px; border-radius: 8px; background: #ED842C; color: #ffffff; border: none; font-weight: 600; font-size: 13px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(237, 132, 44, 0.25);">
                            <i class="fas fa-redo"></i> Coba Lagi
                        </button>
                    </div>
                `;
            }
        });
    };

    window.closeDetailPanel = function() {
        const overlay = document.getElementById('orderPanelOverlay');
        const panel = document.getElementById('orderDetailPanel');
        
        if (panel) panel.classList.remove('is-open');
        if (overlay) {
            overlay.style.opacity = '0';
            setTimeout(() => { 
                overlay.style.display = 'none'; 
            }, 300);
        }
    };
</script>
@endpush
