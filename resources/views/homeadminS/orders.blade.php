@extends("layouts.admin")

@section("page_title", __('admin.orders_title'))

@push("page-styles")
    <link rel="stylesheet" href="{{ asset('css/pages/orders.css') }}" data-turbo-track="reload">
@endpush

@section("content")
        <div class="page-orders">
            <div class="page-orders__link-share">
                <div class="page-orders__url">
                    <a href="{{ url('/linkan.id/' . Auth::user()->username) }}" style="color: #FF9040;">
                        {{ url('/linkan.id/' . Auth::user()->username) }}
                    </a>
                </div>
                <button class="page-orders__btn-share">{{ __('admin.share') }}</button>
            </div>
            <div class="page-orders__content">
                <div class="page-orders__product-orders">
                    <h3>{{ __('admin.product_orders') }}</h3>
                    <div class="page-orders__filter-bar">
                        <input type="date" id="dateFilter">
                    </div>
                    <select id="statusFilter" class="page-orders__select">
                        <option value="">{{ __('admin.all_transaction') }}</option>
                        <option value="success">{{ __('admin.success') }}</option>
                        <option value="pending">{{ __('admin.pending') }}</option>
                        <option value="failed">{{ __('admin.failed') }}</option>
                    </select>
                    <div class="page-orders__search-bar">
                        <input type="text" value="Product Title" readonly>
                        <input type="text" id="searchInput" placeholder="{{ __('admin.search_by_product_buyer') }}">
                    </div>
                    @foreach($transactions as $transaction)
                    <div class="page-orders__item" data-id="{{ $transaction->id }}" data-status="{{ $transaction->status }}" data-date="{{ $transaction->created_at->format('Y-m-d') }}">
                        <img src="{{ asset('storage/' . $transaction->product->image) }}" alt="{{ $transaction->product->title }}" class="page-orders__product-image">
                        <div class="page-orders__product-info">
                            <div class="page-orders__product-title">{{ $transaction->product->title }}</div>
                            <div class="page-orders__product-meta">
                                {{ $transaction->buyer_name }} • {{ $transaction->created_at->format('d M Y') }}
                            </div>
                        </div>
                        <button class="page-orders__btn-detail" onclick="loadOrderDetail({{ $transaction->id }})">{{ __('admin.detail') }}</button>
                    </div>
                    @endforeach
                </div>
                
                <div class="page-orders__order-details" id="orderDetails">
                    <div class="page-orders__empty-detail">
                        <div class="page-orders__icon-detail">📋</div>
                        <p>{!! __('admin.transaction_detail_empty') !!}</p>
                    </div>
                </div>
            </div>
        </div>
@endsection

@push("scripts")
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function loadOrderDetail(id) {
            // Ambil status filter saat ini
            const currentStatus = $('#statusFilter').val();
            const currentDate = $('#dateFilter').val();
            const currentSearch = $('#searchInput').val().trim();

            // Tambahkan parameter filter ke request
            $.get(`/admin/orders/${id}`, {
                status: currentStatus,
                date: currentDate,
                search: currentSearch
            }, function(data) {
                const detail = data;
                const statusClass = {
                    'success': 'page-orders__status-success',
                    'failed': 'page-orders__status-failed',
                    'pending': 'page-orders__status-pending'
                }[detail.status] || '';

                const html = `
                    <h3>Order Details</h3>
                    <div class="page-orders__detail-item">
                        <span class="page-orders__detail-label">Product</span>
                        <span class="page-orders__detail-value">${detail.product.title}</span>
                    </div>
                    <div class="page-orders__detail-item">
                        <span class="page-orders__detail-label">Buyer Name</span>
                        <span class="page-orders__detail-value">${detail.buyer_name}</span>
                    </div>
                    <div class="page-orders__detail-item">
                        <span class="page-orders__detail-label">Buyer Email</span>
                        <span class="page-orders__detail-value">${detail.buyer_email}</span>
                    </div>
                    <div class="page-orders__detail-item">
                        <span class="page-orders__detail-label">Quantity</span>
                        <span class="page-orders__detail-value">${detail.qty}</span>
                    </div>
                    <div class="page-orders__detail-item">
                        <span class="page-orders__detail-label">Total Price</span>
                        <span class="page-orders__detail-value">Rp ${detail.total_price}</span>
                    </div>
                    <div class="page-orders__detail-item">
                        <span class="page-orders__detail-label">Status</span>
                        <span class="page-orders__detail-value">
                            <span class="page-orders__status-badge ${statusClass}">${detail.status}</span>
                        </span>
                    </div>
                    <div class="page-orders__detail-item">
                        <span class="page-orders__detail-label">Date</span>
                        <span class="page-orders__detail-value">${new Date(detail.created_at).toLocaleString()}</span>
                    </div>
                `;
                $('#orderDetails').html(html);
            });
        }

        // Filter functionality
        let filterTimeout;
        $('#statusFilter, #dateFilter, #searchInput').on('change keyup', function() {
            // Reset order details ke tampilan awal
            $('#orderDetails').html(`
                <div class="page-orders__empty-detail">
                    <div class="page-orders__icon-detail">📋</div>
                    <p>{!! __('admin.transaction_detail_empty') !!}</p>
                </div>
            `);

            clearTimeout(filterTimeout);
            filterTimeout = setTimeout(function() {
                loadOrders(1); // Reset ke halaman 1 saat filter berubah
            }, 300);
        });

        // Function to load orders with pagination
        function loadOrders(page = 1) {
            const status = $('#statusFilter').val();
            const date = $('#dateFilter').val();
            const search = $('#searchInput').val().trim();

            // Tampilkan loading state
            $('.page-orders__product-orders').append('<div class="page-orders__loading">Loading...</div>');

            // Hapus pesan "tidak ada transaksi" yang mungkin ada
            $('.page-orders__no-data, .page-orders__error').remove();

            // Siapkan data untuk request
            const requestData = { page: page };
            if (status) requestData.status = status;
            if (date) requestData.date = date;
            if (search) requestData.search = search;

            // Kirim request ke server
            $.ajax({
                url: '/admin/orders',
                method: 'GET',
                data: requestData,
                success: function(response) {
                    // Hapus loading state
                    $('.page-orders__loading').remove();
                    
                    // Update tampilan dengan data baru
                    const $orderList = $('.page-orders__product-orders');
                    $orderList.find('.page-orders__item').remove();
                    $orderList.find('.pagination-container').remove();

                    if (response.transactions && response.transactions.length > 0) {
                        response.transactions.forEach(function(transaction) {
                            const statusClass = {
                                'success': 'page-orders__status-success',
                                'failed': 'page-orders__status-failed',
                                'pending': 'page-orders__status-pending'
                            }[transaction.status] || '';

                            const html = `
                                <div class="page-orders__item" data-id="${transaction.id}" data-status="${transaction.status}" data-date="${transaction.created_at}">
                                    <img src="/storage/${transaction.product.image}" alt="${transaction.product.title}" class="page-orders__product-image">
                                    <div class="page-orders__product-info">
                                        <div class="page-orders__product-title">${transaction.product.title}</div>
                                        <div class="page-orders__product-meta">
                                            ${transaction.buyer_name} • ${new Date(transaction.created_at).toLocaleDateString()}
                                        </div>
                                    </div>
                                    <button class="page-orders__btn-detail" onclick="loadOrderDetail(${transaction.id})">Detail</button>
                                </div>
                            `;
                            $orderList.append(html);
                        });

                        // Add pagination controls
                        if (response.pagination && response.pagination.last_page > 1) {
                            const pagination = response.pagination;
                            let paginationHtml = `
                                <div class="pagination-container" style="margin-top: 20px; text-align: center;">
                                    <div class="pagination-info" style="margin-bottom: 10px; color: #666; font-size: 14px;">
                                        Showing ${((pagination.current_page - 1) * pagination.per_page) + 1} to ${Math.min(pagination.current_page * pagination.per_page, pagination.total)} of ${pagination.total} results
                                    </div>
                                    <div class="pagination-links">
                            `;

                            // Previous button
                            if (pagination.current_page > 1) {
                                paginationHtml += `<a href="#" onclick="loadOrders(${pagination.current_page - 1}); return false;" class="pagination-link" style="padding: 8px 12px; margin: 0 5px; background: #FFA86A; color: white; text-decoration: none; border-radius: 5px;">Previous</a>`;
                            } else {
                                paginationHtml += `<span class="pagination-disabled" style="padding: 8px 12px; margin: 0 5px; background: #f5f5f5; color: #999; border-radius: 5px; cursor: not-allowed;">Previous</span>`;
                            }

                            // Page numbers
                            for (let i = 1; i <= pagination.last_page; i++) {
                                if (i == pagination.current_page) {
                                    paginationHtml += `<span class="pagination-current" style="padding: 8px 12px; margin: 0 5px; background: #FF9040; color: white; border-radius: 5px;">${i}</span>`;
                                } else {
                                    paginationHtml += `<a href="#" onclick="loadOrders(${i}); return false;" class="pagination-link" style="padding: 8px 12px; margin: 0 5px; background: #f5f5f5; color: #666; text-decoration: none; border-radius: 5px;">${i}</a>`;
                                }
                            }

                            // Next button
                            if (pagination.has_more_pages) {
                                paginationHtml += `<a href="#" onclick="loadOrders(${pagination.current_page + 1}); return false;" class="pagination-link" style="padding: 8px 12px; margin: 0 5px; background: #FFA86A; color: white; text-decoration: none; border-radius: 5px;">Next</a>`;
                            } else {
                                paginationHtml += `<span class="pagination-disabled" style="padding: 8px 12px; margin: 0 5px; background: #f5f5f5; color: #999; border-radius: 5px; cursor: not-allowed;">Next</span>`;
                            }

                            paginationHtml += `
                                    </div>
                                </div>
                            `;
                            $orderList.append(paginationHtml);
                        }
                    } else {
                        // Hapus semua order-item yang ada sebelum menampilkan pesan tidak ada data
                        $('.page-orders__item').remove();
                        
                        let debugInfo = '';
                        if (response.debug) {
                            debugInfo = `
                                <div class="page-orders__debug-info">
                                    <p>User ID: ${response.debug.user_id}</p>
                                    <p>Query: ${response.debug.query}</p>
                                    <p>Bindings: ${JSON.stringify(response.debug.bindings)}</p>
                                    <p>Count: ${response.debug.count}</p>
                                    <p>Active Filters:</p>
                                    <ul>
                                        ${Object.entries(response.debug.filters)
                                            .filter(([_, value]) => value)
                                            .map(([key, value]) => `<li>${key}: ${value}</li>`)
                                            .join('')}
                                    </ul>
                                </div>
                            `;
                        }
                        $orderList.append(`
                            <div class="page-orders__no-data">
                                <div class="page-orders__empty-detail">
                                    <div class="page-orders__icon-detail">📋</div>
                                    <p>Belum ada transaksi dengan status ini</p>
                                </div>
                            </div>
                        `);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    $('.page-orders__loading').remove();
                    $('.page-orders__product-orders').append(`
                        <div class="page-orders__error">
                            Error loading data
                            <div class="page-orders__debug-info">
                                <p>Status: ${status}</p>
                                <p>Error: ${error}</p>
                            </div>
                        </div>
                    `);
                }
            });
        }

        // Load initial data
        $(document).ready(function() {
            loadOrders(1);
        });
</script>
@endpush
