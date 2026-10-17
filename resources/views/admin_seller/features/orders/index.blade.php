@extends("admin_seller.layouts.app")

@section("page_title", __('admin.orders_title'))

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/order-history.css') }}?v={{ file_exists(public_path('css/admin/order-history.css')) ? filemtime(public_path('css/admin/order-history.css')) : '1.0' }}">
@endpush

@section("content")
<div class="order-history-card">
    <div class="order-history-title">{{ __('admin.orders_title') }}</div>
    
    <form method="GET" action="{{ url()->current() }}" id="filterForm">
        <input type="hidden" name="status" id="filterStatusInput" value="{{ request('status') }}">
        <div class="oh-controls">
            <!-- Desktop Tabs -->
            <div class="oh-tabs">
                <a href="{{ request()->fullUrlWithQuery(['status' => '']) }}" class="oh-tab {{ request('status') == '' ? 'active' : '' }}" style="text-decoration: none;">{{ __('admin.all_orders') }}</a>
                <a href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}" class="oh-tab {{ request('status') == 'pending' ? 'active' : '' }}" style="text-decoration: none;">{{ __('admin.status_pending') }}</a>
                <a href="{{ request()->fullUrlWithQuery(['status' => 'success']) }}" class="oh-tab {{ in_array(request('status'), ['success', 'completed', 'complete']) ? 'active' : '' }}" style="text-decoration: none;">{{ __('admin.status_completed') }}</a>
                <a href="{{ request()->fullUrlWithQuery(['status' => 'failed']) }}" class="oh-tab {{ in_array(request('status'), ['failed', 'cancelled', 'cancel']) ? 'active' : '' }}" style="text-decoration: none;">{{ __('admin.status_cancelled') }}</a>
            </div>

            <!-- Mobile Status Select Dropdown ("Kotak Drop Down") -->
            <div class="oh-mobile-status-wrapper">
                <div class="oh-status-select-container">
                    <i class="fas fa-layer-group oh-status-icon"></i>
                    <select id="mobileStatusSelect" class="oh-status-select" onchange="document.getElementById('filterStatusInput').value = this.value; document.getElementById('filterForm').submit();">
                        <option value="" {{ request('status') == '' ? 'selected' : '' }}>{{ __('admin.all_status') }}</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('admin.status_pending') }}</option>
                        <option value="success" {{ in_array(request('status'), ['success', 'completed', 'complete']) ? 'selected' : '' }}>{{ __('admin.status_completed') }}</option>
                        <option value="failed" {{ in_array(request('status'), ['failed', 'cancelled', 'cancel']) ? 'selected' : '' }}>{{ __('admin.status_cancelled') }}</option>
                    </select>
                    <i class="fas fa-chevron-down oh-status-chevron"></i>
                </div>
            </div>
            
            <div class="oh-filters-right">
                <div class="oh-search">
                    <i class="fas fa-search" style="color:#94a3b8;"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('admin.search_orders') }}">
                </div>
                <div class="oh-date-picker">
                    <i class="far fa-calendar-alt"></i>
                    <input type="date" name="date" value="{{ request('date') }}" onchange="document.getElementById('filterForm').submit()">
                </div>
                <button type="submit" style="display: none;"></button>
            </div>
        </div>
    </form>

    <div class="oh-table-responsive">
        <table class="oh-table" id="ordersTable">
            <thead>
                <tr>
                    <th>{{ __('admin.order_col_id') }} <i class="fas fa-caret-down"></i></th>
                    <th>{{ __('admin.order_col_name') }}</th>
                    <th>{{ __('admin.order_col_payment') }}</th>
                    <th>{{ __('admin.order_col_date') }} <i class="fas fa-caret-down"></i></th>
                    <th>{{ __('admin.order_col_product') }}</th>
                    <th>{{ __('admin.order_col_status') }}</th>
                    <th>{{ __('admin.order_col_total') }}</th>
                    <th style="text-align: center;">{{ __('admin.order_col_action') }}</th>
                </tr>
            </thead>
            <tbody id="ordersListContainer">
                @forelse($transactions as $transaction)
                    @php
                        $productTitle = $transaction->product ? $transaction->product->title : __('admin.digital_product');
                        $bgColor = ['#f87171', '#fb923c', '#fbbf24', '#34d399', '#38bdf8', '#818cf8', '#a78bfa', '#f472b6'][$transaction->id % 8];
                        $buyerName = $transaction->buyer_name ?: __('admin.anonymous');
                        $initial = strtoupper(substr($buyerName, 0, 1));
                        
                        $statusClass = $transaction->status_class;
                        $statusText = $transaction->status_label;
                        
                        $paymentMethod = $transaction->formatted_payment_method;
                    @endphp
                    <tr class="mobile-row-click" onclick="if(window.innerWidth <= 768) openDetailModal({{ $transaction->id }})">
                        <td data-label="{{ __('admin.order_col_id') }}">#{{ $transaction->id }}</td>
                        <td data-label="{{ __('admin.order_col_name') }}">
                            <div class="buyer-info">
                                <div class="buyer-avatar" style="background-color: {{ $bgColor }};">{{ $initial }}</div>
                                {{ $buyerName }}
                            </div>
                        </td>
                        <td data-label="{{ __('admin.order_col_payment') }}">{{ $paymentMethod }}</td>
                        <td data-label="{{ __('admin.order_col_date') }}" title="{{ $transaction->created_at ? $transaction->created_at->translatedFormat('d M Y, H:i') : '' }}"><i class="far fa-calendar-alt" style="color: #cbd5e1; margin-right: 6px;"></i> {{ $transaction->created_at ? $transaction->created_at->translatedFormat('d M Y') : '-' }}</td>
                        <td data-label="{{ __('admin.order_col_product') }}" style="color: #ED842C; font-weight: 700;" title="{{ $productTitle }}">{{ \Illuminate\Support\Str::limit($productTitle, 30) }}</td>
                        <td data-label="{{ __('admin.order_col_status') }}">
                            <div class="status-badge {{ $statusClass }}">
                                <span class="status-dot"></span> {{ $statusText }}
                            </div>
                        </td>
                        <td data-label="{{ __('admin.order_col_total') }}">Rp{{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                        <td data-label="{{ __('admin.order_col_action') }}" style="text-align: center; position: relative;">
                            <div class="action-dropdown">
                                <button class="action-btn" onclick="toggleDropdown({{ $transaction->id }}, event)">⋮</button>
                                <div id="dropdown_{{ $transaction->id }}" class="dropdown-content">
                                    <div class="dropdown-item" onclick="openDetailModal({{ $transaction->id }})" style="color: #ED842C;">{{ __('admin.detail') }}</div>
                                    <a href="mailto:{{ $transaction->buyer_email }}?subject={{ __('admin.order') }} #{{ $transaction->id }}" class="dropdown-item">{{ __('admin.message') }}</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="oh-empty">{{ __('admin.no_orders_match') }}</td></tr>
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
                <a href="{{ $transactions->appends(array_merge(request()->except(['cursor', 'p']), ['p' => $p - 1]))->previousPageUrl() }}" class="sl-page-btn" style="text-decoration: none;">
                    <span>{{ $p - 1 }}</span>
                </a>
            @endif
            
            <button type="button" class="sl-page-btn active" style="pointer-events: none;">
                <span>{{ $p }}</span>
            </button>
            
            @if ($transactions->nextCursor())
                <a href="{{ $transactions->appends(array_merge(request()->except(['cursor', 'p']), ['p' => $p + 1]))->nextPageUrl() }}" class="sl-page-btn" style="text-decoration: none;">
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
        <h3 class="oh-panel-title" id="panelOrderTitle">{{ __('admin.order_detail') }}</h3>
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
            titleContainer.textContent = `{{ __('admin.order') }} #${id}`;
        }
        
        if (contentContainer) {
            contentContainer.innerHTML = `
                <div class="od-loading-state">
                    <i class="fas fa-circle-notch fa-spin" style="font-size: 28px; color: #ED842C; margin-bottom: 12px; display: inline-block;"></i>
                    <p style="font-size: 14px; margin: 0; font-weight: 500;">{{ __('admin.loading_detail') }}</p>
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
                    <div class="od-error-state">
                        <i class="fas fa-exclamation-triangle" style="font-size: 32px; color: #f59e0b; margin-bottom: 12px; display: inline-block;"></i>
                        <h4 class="od-error-title">{{ __('admin.load_failed') }}</h4>
                        <p class="od-error-desc">{{ __('admin.load_failed_desc') }}</p>
                        <button type="button" onclick="openDetailModal(${id})" style="padding: 9px 20px; border-radius: 8px; background: #ED842C; color: #ffffff; border: none; font-weight: 600; font-size: 13px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(237, 132, 44, 0.25);">
                            <i class="fas fa-redo"></i> {{ __('admin.retry') }}
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
