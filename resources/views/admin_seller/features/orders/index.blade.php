@extends("admin_seller.layouts.app")

@section("page_title", __('admin.orders_title'))

@section("content")
<style>
    .order-history-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.02);
        font-family: 'Inter', -apple-system, sans-serif;
    }
    .order-history-title {
        font-size: 24px;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 30px;
    }
    
    /* Tabs & Controls */
    .oh-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        flex-wrap: wrap;
        gap: 20px;
    }
    .oh-tabs {
        display: flex;
        gap: 30px;
        border-bottom: 2px solid transparent; /* Container visual alignment */
    }
    .oh-tab {
        font-size: 15px;
        font-weight: 700;
        color: #cbd5e1;
        cursor: pointer;
        padding-bottom: 10px;
        position: relative;
        transition: color 0.2s;
    }
    .oh-tab:hover {
        color: #94a3b8;
    }
    .oh-tab.active {
        color: #ED842C; /* Bringova red highlight */
    }
    .oh-tab.active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 100%;
        height: 2px;
        background: #ED842C;
        border-radius: 2px;
    }
    
    /* Date Filter */
    .oh-date-picker {
        display: flex;
        align-items: center;
        gap: 12px;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 8px 16px;
        font-size: 13px;
        font-weight: 700;
        color: #1e293b;
    }

    .oh-date-picker input[type="date"] {
        border: none;
        outline: none;
        font-size: 13px;
        font-weight: 700;
        color: #64748b;
        background: transparent;
        cursor: pointer;
    }
    .oh-date-picker i {
        color: #94a3b8;
    }
    
    /* Search Bar */
    .oh-search {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 8px 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .oh-search input {
        border: none; outline: none;
        font-size: 13px; font-weight: 600;
        color: #475569;
        width: 150px;
    }
    
    /* Table */
    .oh-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 12px;
    }
    .oh-table th {
        padding: 0 20px 10px 20px;
        text-align: left;
        font-size: 13px;
        font-weight: 800;
        color: #1e293b;
        text-transform: capitalize;
    }
    .oh-table th i {
        color: #94a3b8;
        margin-left: 4px;
    }
    .oh-table td {
        padding: 16px 20px;
        font-size: 14px;
        font-weight: 700;
        color: #1e293b;
        background: white;
        border-top: 1px solid #f1f5f9;
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.2s, box-shadow 0.2s;
    }
    .oh-table tr td:first-child {
        border-left: 1px solid #f1f5f9;
        border-top-left-radius: 12px;
        border-bottom-left-radius: 12px;
    }
    .oh-table tr td:last-child {
        border-right: 1px solid #f1f5f9;
        border-top-right-radius: 12px;
        border-bottom-right-radius: 12px;
    }
    .oh-table tr:hover td {
        background: #f8fafc;
    }
    
    /* Avatar */
    .buyer-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .buyer-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #cbd5e1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        color: white;
        font-weight: bold;
        object-fit: cover;
    }
    
    /* Status Dots */
    .status-badge {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 700;
    }
    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }
    .status-success .status-dot { background: #10b981; } /* Completed = Green */
    .status-success { color: #047857; }
    
    .status-failed .status-dot { background: #ef4444; } /* Cancelled = Red */
    .status-failed { color: #dc2626; }
    
    .status-pending .status-dot { background: #fbbf24; } /* Pending = Yellow */
    .status-pending { color: #d97706; }
    
    /* Actions Dropdown */
    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: none;
        background: transparent;
        color: #1e293b;
        font-size: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.2s;
    }
    .action-btn:hover, .action-btn.active { 
        background: #f1f5f9; 
    }
    
    .action-dropdown {
        position: relative;
        display: inline-block;
    }
    .dropdown-content {
        display: none;
        position: absolute;
        right: 0;
        top: 36px;
        background-color: white;
        min-width: 140px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        border-radius: 12px;
        z-index: 100;
        padding: 8px 0;
        border: 1px solid #f1f5f9;
    }
    .dropdown-content.show {
        display: block;
    }
    .dropdown-item {
        padding: 10px 20px;
        font-size: 13px;
        font-weight: 700;
        color: #475569;
        cursor: pointer;
        display: block;
        text-align: left;
        transition: background 0.2s, color 0.2s;
        text-decoration: none;
    }
    .dropdown-item:hover {
        background: #f8fafc;
        color: #ED842C;
    }
    
    /* Slide-out Panel Overlay */
    .oh-panel-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.4);
        z-index: 99998;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    /* Slide-out Panel */
    .oh-side-panel {
        position: fixed;
        top: 0; right: -450px; /* Hidden initially */
        width: 100%; max-width: 450px;
        height: 100vh;
        background: white;
        z-index: 99999;
        box-shadow: -4px 0 24px rgba(0,0,0,0.1);
        transition: right 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow-y: auto;
        display: flex;
        flex-direction: column;
    }
    .oh-side-panel.is-open {
        right: 0;
    }
    .oh-panel-header {
        padding: 24px 30px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: sticky;
        top: 0;
        background: white;
        z-index: 10;
    }
    .oh-panel-title {
        font-size: 18px;
        font-weight: 800;
        color: #1e293b;
        margin: 0;
    }
    .btn-close-panel {
        background: #f1f5f9;
        border: none;
        width: 32px; height: 32px;
        border-radius: 8px;
        color: #64748b;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s;
    }
    .btn-close-panel:hover { background: #e2e8f0; }
    
    .oh-panel-body {
        padding: 30px;
    }
    
    /* Empty State */
    .oh-empty {
        text-align: center;
        padding: 60px 0;
        color: #94a3b8;
        font-weight: 600;
    }
    /* Shortlink-style Pagination */
    .sl-pagination-container {
        display: inline-flex;
        background: #fff;
        border-radius: 50px;
        padding: 6px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        border: 1px solid #f0f0f0;
        gap: 6px;
        align-items: center;
    }
    .sl-page-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 42px;
        height: 42px;
        padding: 0 12px;
        border-radius: 21px;
        font-size: 15px;
        font-weight: 700;
        color: #666;
        background: transparent;
        border: none;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        cursor: pointer;
    }
    .sl-page-btn:hover:not(.active) {
        background: #fff4ed;
        color: #ED842C;
        transform: scale(1.1);
    }
    .sl-page-btn.active {
        background: #ED842C;
        color: #fff;
        box-shadow: 0 4px 12px rgba(237, 132, 44, 0.3);
        animation: slRollIn 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    }
    @keyframes slRollIn {
        0% { transform: translateX(-30px) rotate(-180deg) scale(0.5); opacity: 0; background: #fff; }
        50% { background: #ED842C; }
        100% { transform: translateX(0) rotate(0deg) scale(1); opacity: 1; background: #ED842C; }
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .order-history-card {
            margin: -16px; /* Offset the .content-wrapper padding */
            border-radius: 0;
            padding: 15px 10px;
        }
        .oh-tabs {
            display: none;
        }
        .oh-controls {
            flex-direction: column;
            align-items: stretch;
            gap: 15px;
        }
        .oh-controls > div:last-child {
            flex-direction: column;
            align-items: stretch !important;
        }
        .oh-search {
            width: 100%;
            box-sizing: border-box;
        }
        .oh-search input {
            width: 100%;
        }
        .oh-date-picker {
            flex-wrap: wrap;
            justify-content: space-between;
            box-sizing: border-box;
        }
        .oh-date-picker input[type="date"] {
            flex: 1;
            min-width: 100px;
        }
        .oh-table th {
            padding: 0 10px 10px 10px;
            font-size: 12px;
            white-space: nowrap;
        }
        .oh-table td {
            padding: 12px 10px;
            font-size: 13px;
            white-space: nowrap;
        }
        .buyer-info {
            gap: 8px;
        }
        .buyer-avatar {
            width: 28px;
            height: 28px;
            font-size: 11px;
        }
        .oh-side-panel {
            max-width: 100%;
            right: -100%;
        }
        .mobile-row-click {
            cursor: pointer;
            -webkit-tap-highlight-color: rgba(237, 132, 44, 0.1);
        }
    }
</style>

<div class="order-history-card">
    <div class="order-history-title">Pesanan Masuk</div>
    
    <div class="oh-controls">
        <div class="oh-tabs">
            <div class="oh-tab active" data-status="" onclick="setTabFilter(this)">All Order</div>
            <div class="oh-tab" data-status="pending" onclick="setTabFilter(this)">Pending</div>
            <div class="oh-tab" data-status="success" onclick="setTabFilter(this)">Completed</div>
            <div class="oh-tab" data-status="failed" onclick="setTabFilter(this)">Cancelled</div>
        </div>
        
        <div style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
            <div class="oh-search">
                <i class="fas fa-search" style="color:#94a3b8;"></i>
                <input type="text" id="searchInput" placeholder="Search">
            </div>
            <div class="oh-date-picker">
                <i class="far fa-calendar-alt"></i>
                <input type="date" id="dateFilterStart" onchange="loadOrders()">
                <span>To</span>
                <i class="far fa-calendar-alt"></i>
                <input type="date" id="dateFilterEnd" onchange="loadOrders()">
            </div>
        </div>
    </div>

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
                <!-- Data populated by AJAX -->
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div id="ordersPaginationContainer" style="display: flex; justify-content: center; margin-top: 30px; width: 100%;"></div>
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
    let currentStatus = '';
    let currentPageIndex = 1;

    function setTabFilter(elem) {
        $('.oh-tab').removeClass('active');
        $(elem).addClass('active');
        currentStatus = $(elem).data('status');
        loadOrders(null);
    }

    let searchTimeout;
    $('#searchInput').on('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => loadOrders(null), 300);
    });

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

    function loadOrders(cursor = null, targetPage = 1) {
        if (!cursor) {
            currentPageIndex = 1;
        } else {
            currentPageIndex = targetPage;
        }

        const dateStart = $('#dateFilterStart').val();
        const search = $('#searchInput').val().trim();

        $('#ordersListContainer').html(`
            <tr><td colspan="8" class="oh-empty"><i class="fas fa-circle-notch fa-spin"></i> Loading...</td></tr>
        `);

        const requestData = {};
        if (cursor) requestData.cursor = cursor;
        if (currentStatus) requestData.status = currentStatus;
        if (dateStart) requestData.date = dateStart; // Backend receives 'date'
        if (search) requestData.search = search;

        $.ajax({
            url: '/admin/orders',
            method: 'GET',
            data: requestData,
            success: function(response) {
                const $orderList = $('#ordersListContainer');
                $orderList.empty();

                if (response.transactions && response.transactions.length > 0) {
                    response.transactions.forEach(function(transaction) {
                        const productTitle = transaction.product ? transaction.product.title : 'Digital Product';
                        
                        // Parse date
                        const d = new Date(transaction.created_at);
                        const timeStr = d.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit', hour12: false});
                        
                        const priceFormatted = new Intl.NumberFormat('en-GB', {
                            style: 'currency', currency: 'IDR', minimumFractionDigits: 0
                        }).format(transaction.total_price || 0).replace('IDR', 'Rp');

                        // Status Badge Mapping
                        let statusClass = 'status-pending';
                        let statusText = 'Pending'; // Diubah dari Collected
                        let paymentMethod = transaction.payment_method || '-';
                        // Clean up and capitalize payment method (e.g. bank_transfer-bca -> Bank Transfer (BCA))
                        if (paymentMethod !== '-') {
                            let parts = paymentMethod.split('-');
                            let mainType = parts[0].split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
                            if (mainType.toLowerCase() === 'qris') mainType = 'QRIS';
                            if (mainType.toLowerCase() === 'gopay') mainType = 'GoPay';
                            if (mainType.toLowerCase() === 'shopeepay') mainType = 'ShopeePay';
                            
                            if (parts.length > 1) {
                                let subType = parts[1].toUpperCase();
                                paymentMethod = `${mainType} (${subType})`;
                            } else {
                                paymentMethod = mainType;
                            }
                        }
                        
                        if (transaction.status === 'success') {
                            statusClass = 'status-success';
                            statusText = 'Completed';
                        } else if (transaction.status === 'failed') {
                            statusClass = 'status-failed';
                            statusText = 'Cancelled';
                        }

                        const buyerName = transaction.buyer_name || 'Anonymous';
                        
                        // Fake avatar generation based on reference
                        const colors = ['#f87171', '#fb923c', '#fbbf24', '#34d399', '#38bdf8', '#818cf8', '#a78bfa', '#f472b6'];
                        const bgColor = colors[transaction.id % colors.length];
                        const initial = buyerName.charAt(0).toUpperCase();

                        const html = `
                            <tr class="mobile-row-click" onclick="if(window.innerWidth <= 768) openDetailModal(${transaction.id})">
                                <td data-label="Id">#${transaction.id}</td>
                                <td data-label="Name">
                                    <div class="buyer-info">
                                        <div class="buyer-avatar" style="background-color: ${bgColor};">${initial}</div>
                                        ${buyerName}
                                    </div>
                                </td>
                                <td>${paymentMethod}</td>
                                <td><i class="far fa-clock" style="color: #cbd5e1; margin-right: 6px;"></i> ${timeStr}</td>
                                <td style="color: #ED842C;">${productTitle.substring(0, 15)}${productTitle.length > 15 ? '...' : ''}</td>
                                <td>
                                    <div class="status-badge ${statusClass}">
                                        <span class="status-dot"></span> ${statusText}
                                    </div>
                                </td>
                                <td>${priceFormatted}</td>
                                <td style="text-align: center; position: relative;">
                                    <div class="action-dropdown">
                                        <button class="action-btn" onclick="toggleDropdown(${transaction.id}, event)">⋮</button>
                                        <div id="dropdown_${transaction.id}" class="dropdown-content">
                                            <div class="dropdown-item" onclick="openDetailModal(${transaction.id})" style="color: #ED842C;">Detail</div>
                                            <a href="mailto:${transaction.buyer_email || ''}?subject=Order #${transaction.id}" class="dropdown-item">Message</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        `;
                        $orderList.append(html);
                    });

                    // Pagination logic
                    if (response.pagination && (response.pagination.next_cursor || response.pagination.prev_cursor)) {
                        const p = response.pagination;
                        let pagHtml = '<div class="sl-pagination-container">';
                        
                        // Previous page button (N-1)
                        if (p.prev_cursor && currentPageIndex > 1) {
                            pagHtml += `<button type="button" class="sl-page-btn" onclick="loadOrders('${p.prev_cursor}', ${currentPageIndex - 1})"><span>${currentPageIndex - 1}</span></button>`;
                        }

                        // Current page button (N) - Active
                        pagHtml += `<button type="button" class="sl-page-btn active" style="pointer-events: none;"><span>${currentPageIndex}</span></button>`;

                        // Next page button (N+1)
                        if (p.next_cursor) {
                            pagHtml += `<button type="button" class="sl-page-btn" onclick="loadOrders('${p.next_cursor}', ${currentPageIndex + 1})"><span>${currentPageIndex + 1}</span></button>`;
                        }
                        
                        pagHtml += '</div>';
                        $('#ordersPaginationContainer').html(pagHtml);
                    } else {
                        $('#ordersPaginationContainer').empty();
                    }
                } else {
                    $orderList.html(`<tr><td colspan="8" class="oh-empty">No orders match your filter.</td></tr>`);
                    $('#ordersPaginationContainer').empty();
                }
            },
            error: function() {
                $('#ordersListContainer').html(`<tr><td colspan="8" class="oh-empty" style="color: #ef4444;">Failed to load orders.</td></tr>`);
            }
        });
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

        $.get(`/admin/orders/${id}`, function(detail) {
            if (!detail) return;
            $('#panelOrderTitle').text(`Order #${detail.id}`);
            
            const priceFormatted = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(detail.total_price || 0);
            const dateStr = new Date(detail.created_at).toLocaleString();
            const productTitle = detail.product ? detail.product.title : 'Digital Product';
            
            const html = `
                <div style="background: #f8fafc; border-radius: 12px; padding: 20px; margin-bottom: 25px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                        <span style="color: #64748b; font-weight: 600; font-size: 13px;">Status</span>
                        <span style="font-weight: 800; color: ${detail.status === 'success' ? '#ED842C' : (detail.status === 'failed' ? '#ef4444' : '#1e293b')}; text-transform: capitalize; font-size: 13px;">${detail.status}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                        <span style="color: #64748b; font-weight: 600; font-size: 13px;">Date</span>
                        <span style="font-weight: 700; color: #1e293b; font-size: 13px;">${dateStr}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #64748b; font-weight: 600; font-size: 13px;">Total</span>
                        <span style="font-weight: 800; color: #ED842C; font-size: 16px;">${priceFormatted}</span>
                    </div>
                </div>

                <h4 style="font-size: 15px; font-weight: 800; color: #1e293b; margin-bottom: 15px;">Product Information</h4>
                <div style="border: 1px solid #f1f5f9; border-radius: 12px; padding: 15px; margin-bottom: 25px;">
                    <div style="font-weight: 700; color: #1e293b;">${productTitle}</div>
                </div>

                <h4 style="font-size: 15px; font-weight: 800; color: #1e293b; margin-bottom: 15px;">Buyer Information</h4>
                <div style="border: 1px solid #f1f5f9; border-radius: 12px; padding: 15px;">
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                        <div style="width: 40px; height: 40px; border-radius: 50%; background: #ED842C; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 16px;">
                            ${detail.buyer_name ? detail.buyer_name.charAt(0).toUpperCase() : '?'}
                        </div>
                        <div>
                            <div style="font-weight: 700; color: #1e293b; margin-bottom: 2px;">${detail.buyer_name || '-'}</div>
                            <div style="color: #64748b; font-size: 13px;">Buyer Name</div>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <div style="width: 40px; height: 40px; border-radius: 50%; background: #f1f5f9; color: #64748b; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                            <i class="far fa-envelope"></i>
                        </div>
                        <div>
                            <div style="font-weight: 700; color: #1e293b; margin-bottom: 2px;">${detail.buyer_email || '-'}</div>
                            <div style="color: #64748b; font-size: 13px;">Email Address</div>
                        </div>
                    </div>
                </div>
            `;
            $('#orderDetailContent').html(html);
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

    function initOrdersPage() {
        if (typeof jQuery === 'undefined') {
            setTimeout(initOrdersPage, 50);
            return;
        }
        loadOrders(null);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initOrdersPage);
    } else {
        initOrdersPage();
    }
    
    if (!window.hasOrdersTurboListener) {
        document.addEventListener('turbo:load', function() {
            // Only run if we are actually on the orders page
            if (window.location.pathname.includes('/admin/orders')) {
                initOrdersPage();
            }
        });
        window.hasOrdersTurboListener = true;
    }
</script>
@endpush
