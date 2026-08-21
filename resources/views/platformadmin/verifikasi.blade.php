<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('admin.verification_content') }} — Linkan</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', Arial, sans-serif;
        }

        body {
            background-color: #f5f6fa;
            color: #334155;
            display: flex;
            min-height: 100vh;
        }

        /* ---- Main Layout ---- */
        .platform-main {
            margin-left: 250px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            min-width: 0;
            transition: margin-left 0.3s ease;
        }

        /* ---- Header Bar ---- */
        .platform-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #ffffff;
            padding: 16px 40px;
            border-bottom: 1px solid #f0f2f5;
            min-height: 64px;
            position: sticky;
            top: 0;
            z-index: 99;
        }

        .platform-header-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .hamburger-btn {
            display: none;
            font-size: 22px;
            color: #5A5BF1;
            cursor: pointer;
            background: none;
            border: none;
            padding: 4px 6px;
        }

        .platform-header h1 {
            font-size: 22px;
            font-weight: 800;
            color: #5A5BF1;
            letter-spacing: -0.5px;
            margin: 0;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .header-user {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #f8f9ff;
            border: 1px solid #eef0fe;
            border-radius: 30px;
            padding: 5px 14px 5px 5px;
        }

        .header-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #5A5BF1;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 13px;
        }

        .header-user span {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
        }

        /* ---- Content Wrapper ---- */
        .content-wrapper {
            padding: 28px 40px;
            flex: 1;
        }

        /* ---- Tabs ---- */
        .tabs-container {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
            border-bottom: 1.5px solid #e2e8f0;
            padding-bottom: 0;
            overflow-x: auto;
        }

        .tab-btn {
            background: none;
            border: none;
            font-size: 14px;
            font-weight: 700;
            color: #64748b;
            padding: 12px 18px;
            cursor: pointer;
            position: relative;
            transition: all 0.2s ease;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .tab-btn:hover {
            color: #5A5BF1;
        }

        .tab-btn.active {
            color: #5A5BF1;
        }

        .tab-btn.active::after {
            content: '';
            position: absolute;
            bottom: -1.5px;
            left: 0;
            width: 100%;
            height: 3px;
            background: #5A5BF1;
            border-radius: 3px 3px 0 0;
        }

        /* ---- Filter & Search Bar ---- */
        .filter-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 18px 24px;
            margin-bottom: 24px;
            box-shadow: 0 1px 6px rgba(0, 0, 0, 0.04);
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            align-items: center;
            justify-content: space-between;
        }

        .search-box {
            position: relative;
            flex: 1 1 260px;
        }

        .search-box i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 14px;
        }

        .search-box input {
            width: 100%;
            padding: 11px 16px 11px 44px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-size: 14px;
            font-family: inherit;
            background: #f8fafc;
            outline: none;
            transition: all 0.2s ease;
        }

        .search-box input:focus {
            border-color: #5A5BF1;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(90, 91, 241, 0.1);
        }

        .filter-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
        }

        .filter-select {
            padding: 11px 16px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            background: #f8fafc;
            font-size: 14px;
            font-weight: 600;
            color: #334155;
            outline: none;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: inherit;
        }

        .filter-select:focus {
            border-color: #5A5BF1;
            background: #ffffff;
        }

        .date-range-box {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .date-input {
            padding: 10px 14px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            background: #f8fafc;
            font-size: 13px;
            font-family: inherit;
            color: #334155;
            outline: none;
            transition: all 0.2s ease;
        }

        .date-input:focus {
            border-color: #5A5BF1;
            background: #ffffff;
        }

        /* ---- Table Container ---- */
        .table-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 1px 8px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            border: 1px solid #f0f2f5;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        thead th {
            background: #f8f9ff;
            padding: 14px 20px;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #64748b;
            border-bottom: 1px solid #eef0fe;
            white-space: nowrap;
        }

        tbody tr {
            border-bottom: 1px solid #f1f5f9;
            transition: background 0.15s ease;
        }

        tbody tr:last-child {
            border-bottom: none;
        }

        tbody tr:hover {
            background: #f8faff;
        }

        tbody td {
            padding: 16px 20px;
            font-size: 14px;
            vertical-align: middle;
            color: #334155;
        }

        /* Thumbnail Image */
        .content-thumb {
            width: 72px;
            height: 48px;
            border-radius: 8px;
            object-fit: cover;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
            display: block;
        }

        /* User & Description */
        .user-name-text {
            font-weight: 700;
            color: #1e293b;
        }

        .desc-text {
            max-width: 220px;
            font-size: 13px;
            line-height: 1.5;
            color: #64748b;
        }

        .read-more-link {
            color: #5A5BF1;
            font-weight: 700;
            cursor: pointer;
            text-decoration: underline;
            margin-left: 4px;
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
        }

        .badge-approved {
            background: #dcfce7;
            color: #15803d;
        }

        .badge-pending {
            background: #fef3c7;
            color: #b45309;
        }

        .badge-rejected {
            background: #fee2e2;
            color: #b91c1c;
        }

        .badge-platform {
            background: #EEF0FE;
            color: #5A5BF1;
            text-transform: capitalize;
        }

        /* Action Buttons */
        .action-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
            min-width: 140px;
        }

        .btn-act {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 8px 14px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            border: none;
            transition: all 0.2s ease;
            text-decoration: none;
            font-family: inherit;
        }

        .btn-view {
            background: #EEF0FE;
            color: #5A5BF1;
        }

        .btn-view:hover {
            background: #e0e3fd;
        }

        .btn-approve {
            background: #10b981;
            color: #ffffff;
        }

        .btn-approve:hover {
            background: #059669;
        }

        .btn-reject {
            background: #ef4444;
            color: #ffffff;
        }

        .btn-reject:hover {
            background: #dc2626;
        }

        .btn-disabled {
            background: #e2e8f0;
            color: #94a3b8;
            cursor: default;
        }

        .rejection-note {
            font-size: 12px;
            color: #ef4444;
            background: #fef2f2;
            border: 1px solid #fee2e2;
            padding: 6px 10px;
            border-radius: 8px;
            margin-top: 4px;
            line-height: 1.4;
        }

        /* Empty State */
        .empty-box {
            text-align: center;
            padding: 60px 20px;
            color: #94a3b8;
        }

        .empty-box i {
            font-size: 48px;
            color: #cbd5e1;
            margin-bottom: 12px;
        }

        .empty-box p {
            font-size: 15px;
            font-weight: 600;
        }

        /* ---- Modals ---- */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(4px);
            z-index: 1050;
            align-items: center;
            justify-content: center;
        }

        .modal.show {
            display: flex;
        }

        .modal-card {
            background: #ffffff;
            width: 520px;
            max-width: 90%;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            animation: modalFadeIn 0.2s ease-out;
        }

        @keyframes modalFadeIn {
            from { opacity: 0; transform: translateY(15px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .modal-card-header {
            padding: 20px 24px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-card-header h3 {
            font-size: 17px;
            font-weight: 800;
            color: #1e293b;
        }

        .modal-close-btn {
            background: none;
            border: none;
            font-size: 20px;
            color: #94a3b8;
            cursor: pointer;
            transition: color 0.2s;
        }

        .modal-close-btn:hover {
            color: #ef4444;
        }

        .modal-card-body {
            padding: 24px;
            color: #334155;
            font-size: 14px;
            line-height: 1.6;
        }

        .modal-card-footer {
            padding: 16px 24px;
            background: #f8fafc;
            border-top: 1px solid #f1f5f9;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .form-group-custom {
            margin-bottom: 18px;
        }

        .form-group-custom label {
            display: block;
            font-weight: 700;
            font-size: 13px;
            color: #1e293b;
            margin-bottom: 6px;
        }

        .form-control-custom {
            width: 100%;
            padding: 12px 16px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-size: 14px;
            font-family: inherit;
            outline: none;
            transition: border-color 0.2s;
            resize: vertical;
        }

        .form-control-custom:focus {
            border-color: #5A5BF1;
            box-shadow: 0 0 0 3px rgba(90, 91, 241, 0.1);
        }

        .btn-cancel {
            background: #e2e8f0;
            color: #475569;
            padding: 10px 18px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 13px;
            border: none;
            cursor: pointer;
            transition: background 0.2s;
            font-family: inherit;
        }

        .btn-cancel:hover {
            background: #cbd5e1;
        }

        .btn-submit-reject {
            background: #ef4444;
            color: #ffffff;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 13px;
            border: none;
            cursor: pointer;
            transition: background 0.2s;
            font-family: inherit;
        }

        .btn-submit-reject:hover {
            background: #dc2626;
        }

        /* Responsive */
        @media (max-width: 900px) {
            .platform-main { margin-left: 0 !important; }
            .hamburger-btn { display: block; }
            .platform-header { padding: 14px 20px; }
            .content-wrapper { padding: 20px 16px; }
            .filter-card { flex-direction: column; align-items: stretch; }
            .filter-actions { flex-direction: column; align-items: stretch; }
            .date-range-box { flex-direction: column; width: 100%; }
            .date-input { width: 100%; }
        }
    </style>
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
    function showPlatformModal(productId, platformType, platformUrl, platformFile) {
        document.getElementById('platformType').textContent = platformType.charAt(0).toUpperCase() + platformType.slice(1);
        
        const urlGroup = document.getElementById('platformUrlGroup');
        const fileGroup = document.getElementById('platformFileGroup');
        
        if (platformType === 'upload') {
            urlGroup.style.display = 'none';
            fileGroup.style.display = 'block';
            document.getElementById('platformFile').innerHTML = `<a href="{{ asset('storage/') }}/${platformFile}" target="_blank" style="display:inline-flex; align-items:center; gap:6px; color:#5A5BF1; font-weight:700; text-decoration:none; background:#EEF0FE; padding:8px 14px; border-radius:8px;"><i class="fas fa-download"></i> {{ __('admin.view_file') }}</a>`;
        } else {
            urlGroup.style.display = 'block';
            fileGroup.style.display = 'none';
            document.getElementById('platformUrl').innerHTML = `<a href="${platformUrl}" target="_blank" style="color:#5A5BF1; font-weight:700; text-decoration:underline;">${platformUrl}</a>`;
        }
        
        document.getElementById('platformModal').classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closePlatformModal() {
        document.getElementById('platformModal').classList.remove('show');
        document.body.style.overflow = 'auto';
    }

    function showRejectModal(productId) {
        document.getElementById('rejectForm').action = `{{ url('platform-admin/verifikasi') }}/${productId}`;
        document.getElementById('rejectModal').classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.remove('show');
        document.body.style.overflow = 'auto';
    }

    function showDescriptionModal(element) {
        const title = element.dataset.title || '{{ __("admin.product_description") }}';
        const description = element.dataset.fullDescription;
        document.getElementById('descModalTitle').textContent = title;
        document.getElementById('fullDescription').textContent = description;
        document.getElementById('descriptionModal').classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeDescriptionModal() {
        document.getElementById('descriptionModal').classList.remove('show');
        document.body.style.overflow = 'auto';
    }

    // Close modal on backdrop click
    window.addEventListener('click', function(event) {
        if (event.target.classList.contains('modal')) {
            closeRejectModal();
            closePlatformModal();
            closeDescriptionModal();
        }
    });

    // Filter and Search
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('.tab-btn');
        const searchInput = document.getElementById('searchInput');
        const platformFilter = document.getElementById('platformFilter');
        const startDate = document.getElementById('startDate');
        const endDate = document.getElementById('endDate');
        const productRows = document.querySelectorAll('.product-row');
        const noDataMessage = document.getElementById('noDataMessage');

        function filterProducts() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            const platformValue = platformFilter.value;
            const startDateValue = startDate.value;
            const endDateValue = endDate.value;
            const activeTabElement = document.querySelector('.tab-btn.active');
            const activeTab = activeTabElement ? activeTabElement.dataset.tab : 'pending';
            
            let visibleCount = 0;

            productRows.forEach(row => {
                const status = row.dataset.status;
                const platform = row.dataset.platform;
                const date = row.dataset.date;
                const title = row.dataset.title || '';
                const username = row.querySelector('td:nth-child(2)') ? row.querySelector('td:nth-child(2)').textContent.toLowerCase() : '';
                const description = row.querySelector('td:nth-child(4)') ? row.querySelector('td:nth-child(4)').textContent.toLowerCase() : '';

                const matchesSearch = !searchTerm || 
                                      title.includes(searchTerm) || 
                                      username.includes(searchTerm) || 
                                      description.includes(searchTerm);
                
                const matchesPlatform = !platformValue || platform === platformValue;
                const matchesDate = (!startDateValue || date >= startDateValue) && 
                                    (!endDateValue || date <= endDateValue);

                let matchesStatus = false;
                if (activeTab === 'pending') {
                    matchesStatus = status === 'pending';
                } else if (activeTab === 'approved') {
                    matchesStatus = status === 'approved';
                } else if (activeTab === 'rejected') {
                    matchesStatus = status === 'rejected';
                } else if (activeTab === 'archive') {
                    matchesStatus = status !== 'pending'; 
                } else {
                    matchesStatus = true; 
                }

                if (matchesSearch && matchesPlatform && matchesDate && matchesStatus) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            if (noDataMessage) {
                noDataMessage.style.display = visibleCount === 0 ? 'block' : 'none';
            }
        }

        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                tabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                filterProducts();
            });
        });

        if (searchInput) searchInput.addEventListener('input', filterProducts);
        if (platformFilter) platformFilter.addEventListener('change', filterProducts);
        if (startDate) startDate.addEventListener('change', filterProducts);
        if (endDate) endDate.addEventListener('change', filterProducts);

        filterProducts();
    });

    function confirmApproveProduct(form) {
        showConfirmModal({
            title: 'Setujui Verifikasi Produk?',
            text: 'Produk digital ini akan langsung diverifikasi, disetujui, dan berstatus live di etalase microsite seller.',
            icon: 'question',
            confirmText: '<i class="fas fa-check"></i> {{ __('platform.approve') }}',
            onConfirm: () => {
                form.submit();
            }
        });
    }
    </script>
</body>
</html>
