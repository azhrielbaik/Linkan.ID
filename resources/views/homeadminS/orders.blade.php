@extends("layouts.admin")

@section("page_title", __('admin.orders_title'))

@push("styles")
    <link rel="stylesheet" href="{{ asset('css/pages/orders.css') }}" data-turbo-track="reload">
@endpush

@section("content")
<div class="page-orders-view">

    <!-- TOP SHARE URL BAR -->
    <div class="orders-top-bar">
        <div class="orders-url-box">
            <i class="fas fa-globe" style="color: #5A5BF1; font-size: 16px;"></i>
            <a href="{{ url('/linkan.id/' . Auth::user()->username) }}" target="_blank" class="orders-url-link">
                {{ url('/linkan.id/' . Auth::user()->username) }}
            </a>
        </div>
        <button type="button" class="btn-orders-share" onclick="copyOrdersLink('{{ url('/linkan.id/' . Auth::user()->username) }}', this)">
            <i class="fas fa-share-alt"></i>
            <span>{{ __('admin.share') }}</span>
        </button>
    </div>

    <!-- MAIN TWO-COLUMN ORDERS LAYOUT -->
    <div class="orders-main-grid">

        <!-- LEFT COLUMN: PRODUCT ORDERS LIST -->
        <div class="orders-card orders-list-card">
            <div class="orders-card-header">
                <h3><i class="fas fa-store"></i> {{ __('admin.product_orders') }}</h3>
                <span class="orders-badge-count" id="totalOrdersCount">0 {{ __('admin.order_count') }}</span>
            </div>

            <!-- FILTERS & SEARCH -->
            <div class="orders-filters-container">
                <div class="filter-row-top">
                    <div class="filter-date-wrapper">
                        <i class="fas fa-calendar-alt"></i>
                        <input type="date" id="dateFilter" class="filter-date-input" title="{{ __('admin.filter_date_title') }}">
                    </div>
                    <div class="filter-select-wrapper">
                        <select id="statusFilter" class="filter-status-select">
                            <option value="">{{ __('admin.all_transaction') }}</option>
                            <option value="success">{{ __('admin.success') }}</option>
                            <option value="pending">{{ __('admin.pending') }}</option>
                            <option value="failed">{{ __('admin.failed') }}</option>
                        </select>
                        <i class="fas fa-chevron-down select-chevron"></i>
                    </div>
                </div>

                <div class="search-input-wrapper">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" id="searchInput" class="search-input-field" placeholder="{{ __('admin.search_by_product_buyer') }}">
                </div>
            </div>

            <!-- ORDERS LIST CONTAINER -->
            <div id="ordersListContainer" class="orders-items-list">
                @if(isset($transactions) && $transactions->count() > 0)
                    @foreach($transactions as $transaction)
                    <div class="order-item-card" data-id="{{ $transaction->id }}" onclick="loadOrderDetail({{ $transaction->id }})">
                        @if($transaction->product && $transaction->product->image)
                            <img src="{{ asset('storage/' . $transaction->product->image) }}" alt="{{ $transaction->product->title }}" class="order-item-thumb">
                        @else
                            <div class="order-item-thumb-placeholder">
                                <i class="fas fa-box"></i>
                            </div>
                        @endif
                        <div class="order-item-info">
                            <div class="order-item-title">{{ $transaction->product ? $transaction->product->title : __('admin.digital_product') }}</div>
                            <div class="order-item-meta">
                                <span><i class="far fa-user"></i> {{ $transaction->buyer_name }}</span>
                                <span>•</span>
                                <span><i class="far fa-calendar"></i> {{ $transaction->created_at ? $transaction->created_at->format('d M Y') : '-' }}</span>
                            </div>
                        </div>
                        <div class="order-item-right">
                            <div class="order-item-price">Rp {{ number_format($transaction->total_price ?? 0, 0, ',', '.') }}</div>
                            <button type="button" class="btn-item-detail" onclick="event.stopPropagation(); loadOrderDetail({{ $transaction->id }})">
                                {{ __('admin.detail') }} <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="orders-empty-state">
                        <div class="empty-icon-circle">
                            <i class="fas fa-shopping-basket"></i>
                        </div>
                        <h4>{{ __('admin.no_orders_yet') }}</h4>
                        <p>{{ __('admin.no_orders_desc') }}</p>
                    </div>
                @endif
            </div>

            <!-- PAGINATION -->
            <div id="ordersPaginationContainer" class="orders-pagination-box"></div>
        </div>

        <!-- RIGHT COLUMN: ORDER DETAILS -->
        <div class="orders-card orders-detail-card">
            <div class="orders-card-header">
                <h3><i class="fas fa-receipt"></i> {{ __('admin.order_detail') }}</h3>
            </div>

            <div class="order-detail-content" id="orderDetails">
                <div class="orders-empty-detail">
                    <div class="empty-icon-circle large">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <h4>{{ __('admin.select_order') }}</h4>
                    <p>{!! __('admin.select_order_desc') !!}</p>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

@push("scripts")
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Pass translated strings to JavaScript
    const LANG = {
        copied: @json(__('admin.copied')),
        share: @json(__('admin.share')),
        loading_detail: @json(__('admin.loading_detail')),
        loading_orders: @json(__('admin.loading_orders')),
        data_not_found: @json(__('admin.data_not_found')),
        data_not_found_desc: @json(__('admin.data_not_found_desc')),
        status_success: @json(__('admin.status_success')),
        status_pending: @json(__('admin.status_pending')),
        status_failed: @json(__('admin.status_failed')),
        digital_product: @json(__('admin.digital_product')),
        buyer_name: @json(__('admin.buyer_name')),
        buyer_email: @json(__('admin.buyer_email')),
        quantity: @json(__('admin.quantity')),
        transaction_date: @json(__('admin.transaction_date')),
        total_payment: @json(__('admin.total_payment')),
        contact_buyer: @json(__('admin.contact_buyer')),
        no_transactions: @json(__('admin.no_transactions')),
        no_transactions_desc: @json(__('admin.no_transactions_desc')),
        error_occurred: @json(__('admin.error_occurred')),
        error_load_desc: @json(__('admin.error_load_desc')),
        load_failed: @json(__('admin.load_failed')),
        load_failed_desc: @json(__('admin.load_failed_desc')),
        order_count: @json(__('admin.order_count')),
        detail: @json(__('admin.detail')),
    };

    const currentLocale = @json(App::getLocale());

    function copyOrdersLink(url, btn) {
        navigator.clipboard.writeText(url).then(() => {
            const originalHtml = $(btn).html();
            $(btn).html(`<i class="fas fa-check"></i> <span>${LANG.copied}</span>`);
            setTimeout(() => {
                $(btn).html(originalHtml);
            }, 2000);
        }).catch(err => {
            console.error('Failed to copy: ', err);
        });
    }

    function loadOrderDetail(id) {
        // Highlight active order card
        $('.order-item-card').removeClass('active');
        $(`.order-item-card[data-id="${id}"]`).addClass('active');

        // Loading state in detail card
        $('#orderDetails').html(`
            <div class="orders-detail-loading">
                <div class="spinner-blue"></div>
                <p>${LANG.loading_detail}</p>
            </div>
        `);

        $.get(`/admin/orders/${id}`, function(detail) {
            if (!detail) {
                $('#orderDetails').html(`
                    <div class="orders-empty-detail">
                        <div class="empty-icon-circle large"><i class="fas fa-exclamation-circle"></i></div>
                        <h4>${LANG.data_not_found}</h4>
                        <p>${LANG.data_not_found_desc}</p>
                    </div>
                `);
                return;
            }

            const statusBadgeClass = {
                'success': 'status-pill-success',
                'pending': 'status-pill-pending',
                'failed': 'status-pill-failed'
            }[detail.status] || 'status-pill-pending';

            const statusLabel = {
                'success': LANG.status_success,
                'pending': LANG.status_pending,
                'failed': LANG.status_failed
            }[detail.status] || detail.status;

            const productTitle = detail.product ? detail.product.title : LANG.digital_product;
            const productImage = detail.product && detail.product.image 
                ? `/storage/${detail.product.image}` 
                : null;

            const dateLocale = currentLocale === 'id' ? 'id-ID' : 'en-US';
            const dateFormatted = new Date(detail.created_at).toLocaleString(dateLocale, {
                dateStyle: 'medium',
                timeStyle: 'short'
            });

            const priceFormatted = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(detail.total_price || 0);

            const html = `
                <div class="order-detail-header-box">
                    <div class="order-detail-product-hero">
                        ${productImage ? `<img src="${productImage}" alt="${productTitle}" class="order-detail-prod-img">` : `<div class="order-detail-prod-img-placeholder"><i class="fas fa-box"></i></div>`}
                        <div>
                            <div class="order-detail-prod-title">${productTitle}</div>
                            <div class="order-detail-id-badge">ID: #${detail.id}</div>
                        </div>
                    </div>
                    <div class="status-pill ${statusBadgeClass}">
                        <span class="status-dot"></span> ${statusLabel}
                    </div>
                </div>

                <div class="order-detail-rows-container">
                    <div class="detail-row">
                        <span class="detail-label"><i class="far fa-user"></i> ${LANG.buyer_name}</span>
                        <span class="detail-value font-bold">${detail.buyer_name || '-'}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label"><i class="far fa-envelope"></i> ${LANG.buyer_email}</span>
                        <span class="detail-value">${detail.buyer_email || '-'}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label"><i class="fas fa-layer-group"></i> ${LANG.quantity}</span>
                        <span class="detail-value">${detail.qty || 1} item</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label"><i class="far fa-clock"></i> ${LANG.transaction_date}</span>
                        <span class="detail-value">${dateFormatted}</span>
                    </div>
                    <div class="detail-row total-row">
                        <span class="detail-label"><i class="fas fa-wallet"></i> ${LANG.total_payment}</span>
                        <span class="detail-value price-total">${priceFormatted}</span>
                    </div>
                </div>

                ${detail.buyer_email ? `
                <div class="order-detail-actions">
                    <a href="mailto:${detail.buyer_email}?subject=Order%20${encodeURIComponent(productTitle)}" class="btn-email-buyer">
                        <i class="fas fa-envelope"></i> ${LANG.contact_buyer}
                    </a>
                </div>
                ` : ''}
            `;
            $('#orderDetails').html(html);
        }).fail(function() {
            $('#orderDetails').html(`
                <div class="orders-empty-detail">
                    <div class="empty-icon-circle large"><i class="fas fa-exclamation-triangle"></i></div>
                    <h4>${LANG.load_failed}</h4>
                    <p>${LANG.load_failed_desc}</p>
                </div>
            `);
        });
    }

    let filterTimeout;
    $('#statusFilter, #dateFilter, #searchInput').on('change keyup', function() {
        clearTimeout(filterTimeout);
        filterTimeout = setTimeout(function() {
            loadOrders(1);
        }, 300);
    });

    function loadOrders(page = 1) {
        const status = $('#statusFilter').val();
        const date = $('#dateFilter').val();
        const search = $('#searchInput').val().trim();

        $('#ordersListContainer').html(`
            <div class="orders-list-loading">
                <div class="spinner-blue"></div>
                <p>${LANG.loading_orders}</p>
            </div>
        `);

        const requestData = { page: page };
        if (status) requestData.status = status;
        if (date) requestData.date = date;
        if (search) requestData.search = search;

        $.ajax({
            url: '/admin/orders',
            method: 'GET',
            data: requestData,
            success: function(response) {
                const $orderList = $('#ordersListContainer');
                $orderList.empty();

                if (response.transactions && response.transactions.length > 0) {
                    const total = response.pagination ? response.pagination.total : response.transactions.length;
                    $('#totalOrdersCount').text(`${total} ${LANG.order_count}`);

                    response.transactions.forEach(function(transaction) {
                        const productTitle = transaction.product ? transaction.product.title : LANG.digital_product;
                        const productImage = transaction.product && transaction.product.image 
                            ? `/storage/${transaction.product.image}` 
                            : null;

                        const dateLocale = currentLocale === 'id' ? 'id-ID' : 'en-US';
                        const dateFormatted = new Date(transaction.created_at).toLocaleDateString(dateLocale, {
                            day: 'numeric',
                            month: 'short',
                            year: 'numeric'
                        });

                        const priceFormatted = new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            minimumFractionDigits: 0
                        }).format(transaction.total_price || 0);

                        const html = `
                            <div class="order-item-card" data-id="${transaction.id}" onclick="loadOrderDetail(${transaction.id})">
                                ${productImage ? `<img src="${productImage}" alt="${productTitle}" class="order-item-thumb">` : `<div class="order-item-thumb-placeholder"><i class="fas fa-box"></i></div>`}
                                <div class="order-item-info">
                                    <div class="order-item-title">${productTitle}</div>
                                    <div class="order-item-meta">
                                        <span><i class="far fa-user"></i> ${transaction.buyer_name || '-'}</span>
                                        <span>•</span>
                                        <span><i class="far fa-calendar"></i> ${dateFormatted}</span>
                                    </div>
                                </div>
                                <div class="order-item-right">
                                    <div class="order-item-price">${priceFormatted}</div>
                                    <button type="button" class="btn-item-detail" onclick="event.stopPropagation(); loadOrderDetail(${transaction.id})">
                                        ${LANG.detail} <i class="fas fa-chevron-right"></i>
                                    </button>
                                </div>
                            </div>
                        `;
                        $orderList.append(html);
                    });

                    // Pagination controls
                    if (response.pagination && response.pagination.last_page > 1) {
                        const pagination = response.pagination;
                        let paginationHtml = `<div class="orders-pagination-wrapper">`;

                        if (pagination.current_page > 1) {
                            paginationHtml += `<button type="button" onclick="loadOrders(${pagination.current_page - 1})" class="btn-pag-nav"><i class="fas fa-chevron-left"></i></button>`;
                        } else {
                            paginationHtml += `<button type="button" class="btn-pag-nav disabled" disabled><i class="fas fa-chevron-left"></i></button>`;
                        }

                        for (let i = 1; i <= pagination.last_page; i++) {
                            if (i == pagination.current_page) {
                                paginationHtml += `<button type="button" class="btn-pag-num active">${i}</button>`;
                            } else {
                                paginationHtml += `<button type="button" onclick="loadOrders(${i})" class="btn-pag-num">${i}</button>`;
                            }
                        }

                        if (pagination.has_more_pages) {
                            paginationHtml += `<button type="button" onclick="loadOrders(${pagination.current_page + 1})" class="btn-pag-nav"><i class="fas fa-chevron-right"></i></button>`;
                        } else {
                            paginationHtml += `<button type="button" class="btn-pag-nav disabled" disabled><i class="fas fa-chevron-right"></i></button>`;
                        }

                        paginationHtml += `</div>`;
                        $('#ordersPaginationContainer').html(paginationHtml);
                    } else {
                        $('#ordersPaginationContainer').empty();
                    }
                } else {
                    $('#totalOrdersCount').text(`0 ${LANG.order_count}`);
                    $orderList.html(`
                        <div class="orders-empty-state">
                            <div class="empty-icon-circle">
                                <i class="fas fa-shopping-basket"></i>
                            </div>
                            <h4>${LANG.no_transactions}</h4>
                            <p>${LANG.no_transactions_desc}</p>
                        </div>
                    `);
                    $('#ordersPaginationContainer').empty();
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                $('#ordersListContainer').html(`
                    <div class="orders-empty-state">
                        <div class="empty-icon-circle"><i class="fas fa-exclamation-circle" style="color: #EF4444;"></i></div>
                        <h4>${LANG.error_occurred}</h4>
                        <p>${LANG.error_load_desc}</p>
                    </div>
                `);
            }
        });
    }

    $(document).ready(function() {
        loadOrders(1);
    });
</script>
@endpush
