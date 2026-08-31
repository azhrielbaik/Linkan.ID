<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('admin.verification_content') }} — Linkan</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/platform/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/platform/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/platform/notifications.css') }}">
    <link rel="stylesheet" href="{{ asset('css/platform/verifikasi.css') }}">
    <link rel="stylesheet" href="{{ asset('css/platform/tabs.css') }}">
</head>
<body>

    {{-- Sidebar --}}
    @include('platformadmin.sidebar.sidebarplatform')

    <div class="platform-main">
        {{-- Header Bar --}}
        <div class="platform-header">
            <div class="platform-header-left">
                <button class="hamburger-btn" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
                <h1>{{ __('platform.product_verification') }}</h1>
            </div>
            <div class="header-right">
                @include('platformadmin.partials.notifications')
                @include('platformadmin.partials.header_profile')
            </div>
        </div>

        <div class="content-wrapper">

            {{-- Tabs --}}
            <div class="tabs-container">
                <button class="tab-btn active" data-tab="pending">
                    {{ __('platform.pending_verification') }}
                </button>
                <button class="tab-btn" data-tab="approved">
                    {{ __('platform.approved') }}
                </button>
                <button class="tab-btn" data-tab="rejected">
                    {{ __('platform.rejected') }}
                </button>
                <button class="tab-btn" data-tab="archive">
                    {{ __('platform.archive') }}
                </button>
            </div>

            {{-- Filter & Search Card --}}
            <div class="filter-card">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="{{ __('platform.search_verification') }}">
                </div>

                <div class="filter-actions">
                    <select class="filter-select" id="platformFilter">
                        <option value="">{{ __('platform.all_platforms') }}</option>
                        <option value="upload">Upload File</option>
                        <option value="dropbox">Dropbox</option>
                        <option value="gdrive">G-Drive</option>
                        <option value="other">Lainnya / Other</option>
                    </select>

                    <div class="date-range-box">
                        <input type="date" class="date-input" id="startDate" title="{{ __('platform.from_date') }}">
                        <span style="color:#94a3b8; font-size:13px;">-</span>
                        <input type="date" class="date-input" id="endDate" title="{{ __('platform.to_date') }}">
                    </div>
                </div>
            </div>

            <form id="bulkActionForm" action="{{ route('platform-admin.verifikasi.bulk') }}" method="POST" class="bulk-toolbar">
    @csrf

                <input type="hidden" name="status" id="bulkStatus">
                <div id="bulkProductIds"></div>
                <div class="bulk-selection-info">
                    <i class="fas fa-layer-group"></i>
                    <strong id="selectedCount">0</strong> {{ __('platform.selected_products') }}
                </div>
                <div class="bulk-actions">
                    <button type="button" class="bulk-btn bulk-approve" onclick="submitBulkAction('approved')" disabled>
                        <i class="fas fa-check"></i> {{ __('platform.bulk_approve') }}
                    </button>
                    <button type="button" class="bulk-btn bulk-reject" onclick="openBulkRejectModal()" disabled>
                        <i class="fas fa-times"></i> {{ __('platform.bulk_reject') }}
                    </button>
                </div>
            </form>

            {{-- Table Card --}}
            <div class="table-card">
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th class="select-column"><input type="checkbox" id="selectAllProducts" aria-label="{{ __('platform.select_all') }}"></th>
                                <th>#</th>
                                <th>{{ __('platform.seller') }}</th>
                                <th>{{ __('admin.product') }}</th>
                                <th>{{ __('platform.price') }}</th>
                                <th>{{ __('platform.status') }}</th>
                                <th style="text-align: center;">{{ __('platform.action') }}</th>
                            </tr>
                        </thead>
                        <tbody id="productTableBody">
                            @forelse($products as $index => $product)
                            <tr class="product-row"
                                data-status="{{ $product->verification_status }}"
                                data-platform="{{ $product->platform_type }}"
                                data-date="{{ $product->created_at->format('Y-m-d') }}"
                                data-title="{{ strtolower($product->title) }}"
                                data-description="{{ e(strtolower($product->description ?? '')) }}">
                                <td data-label="{{ __('platform.select') }}" class="select-column">
                                    @if($product->verification_status == 'pending')
                                        <input type="checkbox" class="product-checkbox" value="{{ $product->id }}" aria-label="{{ __('platform.select_product') }}: {{ $product->title }}">
                                    @endif
                                </td>
                                <td data-label="#" class="row-number" style="font-weight: 700; color: #94a3b8;">{{ $index + 1 }}</td>
                                <td data-label="{{ __('platform.seller') }}">
                                    <div class="user-name-text">{{ $product->user->name ?? '-' }}</div>
                                    <small style="color: #94a3b8;">{{ $product->user->email ?? '' }}</small>
                                </td>
                                <td data-label="{{ __('admin.product') }}">
                                    <div class="product-cell">
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->title }}" class="content-thumb">
                                        @else
                                            <div class="content-thumb content-thumb-empty"><i class="fas fa-box"></i></div>
                                        @endif
                                        <div class="product-cell-info">
                                            <div class="product-title">{{ $product->title }}</div>
                                            <button type="button" class="product-detail-link" onclick="showProductDetail(this)"
                                                data-title="{{ e($product->title) }}"
                                                data-description="{{ e($product->description ?? '') }}"
                                                data-platform="{{ e(ucfirst($product->platform_type)) }}"
                                                data-platform-url="{{ e($product->platform_url ?? '') }}"
                                                data-platform-file="{{ e($product->platform_file ?? '') }}"
                                                data-price="Rp {{ number_format($product->sale_price ?: $product->price) }}"
                                                data-quantity="{{ $product->has_quantity_limit ? $product->quantity : __('platform.unlimited') }}"
                                                data-date="{{ $product->created_at->format('d M Y') }}"
                                                data-image="{{ $product->image ? asset('storage/' . $product->image) : '' }}">
                                                <i class="fas fa-arrow-up-right-from-square"></i> {{ __('platform.view_details') }}
                                            </button>
                                        </div>
                                    </div>
                                </td>
                                <td data-label="{{ __('platform.price') }}">
                                    @if($product->sale_price)
                                        <div style="font-weight: 700; color: #10b981;">Rp {{ number_format($product->sale_price) }}</div>
                                        <div style="font-size: 11px; text-decoration: line-through; color: #94a3b8;">Rp {{ number_format($product->price) }}</div>
                                    @else
                                        <div style="font-weight: 700; color: #1e293b;">Rp {{ number_format($product->price) }}</div>
                                    @endif
                                </td>
                                <td data-label="{{ __('platform.status') }}">
                                    @if($product->verification_status == 'approved')
                                        <span class="badge badge-approved">
                                            <i class="fas fa-check-circle"></i> {{ __('platform.approved') }}
                                        </span>
                                    @elseif($product->verification_status == 'rejected')
                                        <span class="badge badge-rejected">
                                            <i class="fas fa-times-circle"></i> {{ __('platform.rejected') }}
                                        </span>
                                    @else
                                        <span class="badge badge-pending">
                                            <i class="fas fa-clock"></i> {{ __('platform.pending_status') }}
                                        </span>
                                    @endif
                                </td>
                                <td data-label="{{ __('platform.action') }}" class="row-actions">
                                    @if($product->verification_status == 'pending')
                                        <div class="action-group">
                                            <button type="button" class="btn-act btn-view" onclick="showProductDetail(this)"
                                                data-title="{{ e($product->title) }}"
                                                data-description="{{ e($product->description ?? '') }}"
                                                data-platform="{{ e(ucfirst($product->platform_type)) }}"
                                                data-platform-url="{{ e($product->platform_url ?? '') }}"
                                                data-platform-file="{{ e($product->platform_file ?? '') }}"
                                                data-price="Rp {{ number_format($product->sale_price ?: $product->price) }}"
                                                data-quantity="{{ $product->has_quantity_limit ? $product->quantity : __('platform.unlimited') }}"
                                                data-date="{{ $product->created_at->format('d M Y') }}"
                                                data-image="{{ $product->image ? asset('storage/' . $product->image) : '' }}">
                                                <i class="fas fa-eye"></i> {{ __('platform.view_details') }}
                                            </button>
                                            <form action="{{ route('platform-admin.verifikasi.verify', $product->id) }}" method="POST" style="margin: 0;">
                                                @csrf
                                                <input type="hidden" name="status" value="approved">
                                                <button type="button" class="btn-act btn-approve" style="width: 100%;" onclick="confirmApproveProduct(this.form)">
                                                    <i class="fas fa-check"></i> {{ __('platform.approve') }}
                                                </button>
                                            </form>
                                            <button type="button" class="btn-act btn-reject" onclick="showRejectModal({{ $product->id }})">
                                                <i class="fas fa-times"></i> {{ __('platform.reject') }}
                                            </button>
                                        </div>
                                    @else
                                        <div class="action-group">
                                            <button class="btn-act btn-disabled" disabled>
                                                <i class="fas fa-check-circle"></i> {{ __('platform.' . $product->verification_status) ?? ucfirst($product->verification_status) }}
                                            </button>
                                            @if($product->verification_status == 'rejected' && $product->rejection_reason)
                                                <div class="rejection-note">
                                                    <strong>{{ __('platform.reason') }}</strong> {{ $product->rejection_reason }}
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div id="noDataMessage" class="empty-box" style="display: none;">
                    <i class="fas fa-box-open"></i>
                    <p>{{ __('platform.no_products_found') }}</p>
                </div>
            </div>

        </div>
    </div>

    <div id="bulkRejectModal" class="modal">
        <div class="modal-card">
            <div class="modal-card-header">
                <h3><i class="fas fa-exclamation-triangle" style="color: #ef4444; margin-right: 6px;"></i>{{ __('platform.bulk_reject_title') }}</h3>
                <button type="button" class="modal-close-btn" onclick="closeBulkRejectModal()" aria-label="{{ __('platform.close') }}">&times;</button>
            </div>
            <div class="modal-card-body">
                <p class="bulk-reject-copy">{{ __('platform.bulk_reject_message') }}</p>
                <div class="form-group-custom">
                    <label for="bulkRejectionReason">{{ __('platform.rejection_reason') }} <span style="color: #ef4444;">*</span></label>
                    <textarea id="bulkRejectionReason" class="form-control-custom" rows="4" required placeholder="{{ __('platform.enter_rejection_reason') }}"></textarea>
                </div>
            </div>
            <div class="modal-card-footer">
                <button type="button" class="btn-cancel" onclick="closeBulkRejectModal()">{{ __('platform.cancel') }}</button>
                <button type="button" class="btn-submit-reject" onclick="submitBulkReject()"><i class="fas fa-times"></i> {{ __('platform.bulk_reject') }}</button>
            </div>
        </div>
    </div>

    <!-- Modal Platform Details -->
    <div id="platformModal" class="modal">
        <div class="modal-card">
            <div class="modal-card-header">
                <h3><i class="fas fa-box-open" style="color: #ED842C; margin-right: 6px;"></i>{{ __('platform.product_details') }}</h3>
                <button type="button" class="modal-close-btn" onclick="closePlatformModal()" aria-label="{{ __('platform.close') }}">&times;</button>
            </div>
            <div class="modal-card-body">
                <div class="product-modal-overview">
                    <div id="productImageWrap" class="product-modal-image-wrap"></div>
                    <div>
                        <div class="modal-label">{{ __('admin.product') }}</div>
                        <p id="productTitle" class="product-modal-title"></p>
                    </div>
                </div>
                <div class="product-detail-grid">
                    <div>
                        <div class="modal-label">{{ __('platform.price') }}</div>
                        <p id="productPrice"></p>
                    </div>
                    <div>
                        <div class="modal-label">{{ __('platform.date') }}</div>
                        <p id="productDate"></p>
                    </div>
                    <div>
                        <div class="modal-label">{{ __('platform.quantity') }}</div>
                        <p id="productQuantity"></p>
                    </div>
                </div>
                <div class="form-group-custom">
                    <label>{{ __('platform.description') }}</label>
                    <p id="productDescription" class="product-modal-description"></p>
                </div>
                <div style="margin-bottom: 12px;">
                    <label style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase;">{{ __('platform.platform_type') }}</label>
                    <p id="platformType" style="font-weight: 700; color: #ED842C; font-size: 15px;"></p>
                </div>
                <div class="form-group-custom" id="platformUrlGroup">
                    <label>{{ __('platform.platform_url') }}</label>
                    <p id="platformUrl" style="word-break: break-all;"></p>
                </div>
                <div class="form-group-custom" id="platformFileGroup">
                    <label>{{ __('platform.platform_file') }}</label>
                    <p id="platformFile"></p>
                </div>
            </div>
            <div class="modal-card-footer">
                <button type="button" class="btn-cancel" onclick="closePlatformModal()">{{ __('platform.close') }}</button>
            </div>
        </div>
    </div>

    <!-- Modal Reject -->
    <div id="rejectModal" class="modal">
        <div class="modal-card">
            <div class="modal-card-header">
                <h3><i class="fas fa-exclamation-triangle" style="color: #ef4444; margin-right: 6px;"></i>{{ __('platform.reject_product_title') }}</h3>
                <button class="modal-close-btn" onclick="closeRejectModal()">&times;</button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-card-body">
                    <input type="hidden" name="status" value="rejected">
                    <div class="form-group-custom">
                        <label>{{ __('platform.rejection_reason') }} <span style="color: #ef4444;">*</span></label>
                        <textarea name="rejection_reason" class="form-control-custom" rows="4" required placeholder="{{ __('platform.enter_rejection_reason') }}"></textarea>
                    </div>
                </div>
                <div class="modal-card-footer">
                    <button type="button" class="btn-cancel" onclick="closeRejectModal()">{{ __('platform.cancel') }}</button>
                    <button type="submit" class="btn-submit-reject"><i class="fas fa-times"></i> {{ __('platform.reject') }}</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Description -->
    <div id="descriptionModal" class="modal">
        <div class="modal-card">
            <div class="modal-card-header">
                <h3 id="descModalTitle">{{ __('platform.product_description') }}</h3>
                <button class="modal-close-btn" onclick="closeDescriptionModal()">&times;</button>
            </div>
            <div class="modal-card-body">
                <div id="fullDescription" style="white-space: pre-line; line-height: 1.7;"></div>
            </div>
            <div class="modal-card-footer">
                <button type="button" class="btn-cancel" onclick="closeDescriptionModal()">{{ __('platform.close') }}</button>
            </div>
        </div>
    </div>

    <script>
        window.PlatformVerifikasiConfig = {
            storageBaseUrl: '{{ asset('storage') }}',
            rejectBaseUrl: '{{ url('platform-admin/verifikasi') }}',
            viewFileText: '{{ __('admin.view_file') }}',
            approveText: '{{ __('platform.approve') }}'
        };
    </script>
    <script src="{{ asset('js/platform/notifications.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/platform/verifikasi.js') }}"></script>
</body>
</html>
