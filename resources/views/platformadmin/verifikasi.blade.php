<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('admin.verification_content') }} — Linkan</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/platform/verifikasi.css') }}">
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
                <div class="header-user">
                    <div class="header-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                    <span>{{ Auth::user()->name }}</span>
                </div>
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

            {{-- Table Card --}}
            <div class="table-card">
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('platform.seller') }}</th>
                                <th>{{ __('platform.content') }}</th>
                                <th>{{ __('platform.description') }}</th>
                                <th>{{ __('platform.price') }}</th>
                                <th>{{ __('platform.platform_type') }}</th>
                                <th>{{ __('platform.quantity') }}</th>
                                <th>{{ __('platform.date') }}</th>
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
                                data-title="{{ strtolower($product->title) }}">
                                <td style="font-weight: 700; color: #94a3b8;">{{ $index + 1 }}</td>
                                <td>
                                    <div class="user-name-text">{{ $product->user->name ?? '-' }}</div>
                                    <small style="color: #94a3b8;">{{ $product->user->email ?? '' }}</small>
                                </td>
                                <td>
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image" class="content-thumb">
                                    @else
                                        <div style="width:72px; height:48px; border-radius:8px; background:#e2e8f0; display:flex; align-items:center; justify-content:center; color:#94a3b8; font-size:12px;">
                                            No Img
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="desc-text">
                                        <div style="font-weight: 700; color:#1e293b; margin-bottom: 2px;">{{ $product->title }}</div>
                                        {{ Str::limit($product->description, 45) }}
                                        @if(strlen($product->description) > 45)
                                            <span class="read-more-link" onclick="showDescriptionModal(this)" data-title="{{ addslashes($product->title) }}" data-full-description="{{ addslashes($product->description) }}">{{ __('platform.read_more') }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($product->sale_price)
                                        <div style="font-weight: 700; color: #10b981;">Rp {{ number_format($product->sale_price) }}</div>
                                        <div style="font-size: 11px; text-decoration: line-through; color: #94a3b8;">Rp {{ number_format($product->price) }}</div>
                                    @else
                                        <div style="font-weight: 700; color: #1e293b;">Rp {{ number_format($product->price) }}</div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-platform">
                                        <i class="fas fa-layer-group" style="font-size: 10px;"></i>
                                        {{ ucfirst($product->platform_type) }}
                                    </span>
                                </td>
                                <td>
                                    <span style="font-weight: 600; color: #475569;">
                                        @if($product->has_quantity_limit)
                                            {{ $product->quantity }}
                                        @else
                                            {{ __('platform.unlimited') }}
                                        @endif
                                    </span>
                                </td>
                                <td style="font-size: 13px; color: #64748b; white-space: nowrap;">
                                    {{ $product->created_at->format('d M Y') }}
                                </td>
                                <td>
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
                                <td>
                                    @if($product->verification_status == 'pending')
                                        <div class="action-group">
                                            <button type="button" class="btn-act btn-view" onclick="showPlatformModal({{ $product->id }}, '{{ $product->platform_type }}', '{{ $product->platform_url }}', '{{ $product->platform_file }}')">
                                                <i class="fas fa-eye"></i> {{ __('platform.view_platform') }}
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

    <!-- Modal Platform Details -->
    <div id="platformModal" class="modal">
        <div class="modal-card">
            <div class="modal-card-header">
                <h3><i class="fas fa-info-circle" style="color: #5A5BF1; margin-right: 6px;"></i>{{ __('platform.platform_details') }}</h3>
                <button class="modal-close-btn" onclick="closePlatformModal()">&times;</button>
            </div>
            <div class="modal-card-body">
                <div class="form-group-custom">
                    <label>{{ __('platform.platform_type') }}</label>
                    <p id="platformType" style="font-weight: 700; color: #5A5BF1; font-size: 15px;"></p>
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
    <script src="{{ asset('js/platform/verifikasi.js') }}"></script>
</body>
</html>
