<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('platform.user_management') }} — Platform Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/platform/users.css') }}">
</head>
<body>

    @include('platformadmin.sidebar.sidebarplatform')

    <div class="platform-main">
        {{-- Header --}}
        <div class="platform-header">
            <div class="platform-header-left">
                <button class="hamburger-btn" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
                <h1>{{ __('platform.user_management') }}</h1>
            </div>
            <div class="header-user">
                <div class="header-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                <span>{{ Auth::user()->name }}</span>
            </div>
        </div>

        <div class="content-wrapper">

            {{-- Alerts --}}
            @if (session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif

            {{-- View Switcher Tabs --}}
            <div class="view-switcher">
                <a href="{{ route('platform-admin.users', ['view' => 'users']) }}" 
                   class="view-tab-link {{ $viewType === 'users' ? 'active' : '' }}">
                    <i class="fas fa-users"></i> {{ __('platform.user_management') }}
                </a>
                <a href="{{ route('platform-admin.users', ['view' => 'appeals']) }}" 
                   class="view-tab-link {{ $viewType === 'appeals' ? 'active' : '' }}">
                    <i class="fas fa-file-contract"></i> {{ __('platform.seller_appeals') }}
                    @if($pendingAppealsCount > 0)
                        <span class="tab-counter">{{ $pendingAppealsCount }}</span>
                    @endif
                </a>
            </div>

            @if($viewType === 'users')
                {{-- Stats Row --}}
                <div class="stats-row">
                    <div class="stat-card total">
                        <div class="stat-icon-wrap"><i class="fas fa-users"></i></div>
                        <div class="stat-info">
                            <div class="label">{{ __('platform.total_users') }}</div>
                            <div class="value">{{ $totalUsers }}</div>
                        </div>
                    </div>
                    <div class="stat-card active">
                        <div class="stat-icon-wrap"><i class="fas fa-user-check"></i></div>
                        <div class="stat-info">
                            <div class="label">{{ __('platform.active_users') }}</div>
                            <div class="value">{{ $totalActive }}</div>
                        </div>
                    </div>
                    <div class="stat-card susp">
                        <div class="stat-icon-wrap"><i class="fas fa-user-slash"></i></div>
                        <div class="stat-info">
                            <div class="label">{{ __('platform.suspended_users') }}</div>
                            <div class="value">{{ $totalSuspended }}</div>
                        </div>
                    </div>
                </div>

                {{-- Toolbar --}}
                <div class="toolbar">
                    <form method="GET" action="{{ route('platform-admin.users') }}" class="search-form">
                        <input type="hidden" name="view" value="users">
                        <div class="search-wrap">
                            <i class="fas fa-search"></i>
                            <input type="text" name="search"
                                   placeholder="{{ __('platform.search_user_placeholder') }}"
                                   value="{{ $search ?? '' }}">
                        </div>
                        <button type="submit" class="btn-search">
                            <i class="fas fa-search"></i> {{ __('platform.search') }}
                        </button>
                    </form>

                    <div class="filter-tabs">
                        <a href="{{ route('platform-admin.users', ['view' => 'users', 'filter' => 'all', 'search' => $search]) }}"
                           class="filter-tab {{ ($filter ?? 'all') === 'all' ? 'active' : '' }}">{{ __('platform.all') }}</a>
                        <a href="{{ route('platform-admin.users', ['view' => 'users', 'filter' => 'active', 'search' => $search]) }}"
                           class="filter-tab {{ ($filter ?? '') === 'active' ? 'active' : '' }}">{{ __('platform.active') }}</a>
                        <a href="{{ route('platform-admin.users', ['view' => 'users', 'filter' => 'suspended', 'search' => $search]) }}"
                           class="filter-tab {{ ($filter ?? '') === 'suspended' ? 'active' : '' }}">{{ __('platform.suspended') }}</a>
                    </div>
                </div>

                {{-- Users Table --}}
                <div class="table-card">
                    @if ($users->count() > 0)
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>{{ __('platform.role') }}</th>
                                <th>{{ __('platform.joined_at') }}</th>
                                <th>{{ __('platform.status') }}</th>
                                <th>{{ __('platform.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $i => $user)
                            <tr>
                                <td style="color:#94a3b8; font-weight:600; font-size:13px;">
                                    {{ $users->firstItem() + $i }}
                                </td>
                                <td>
                                    <div class="user-cell">
                                        <div class="user-avatar">
                                            @if ($user->avatar)
                                                <img src="{{ Str::startsWith($user->avatar, ['http://', 'https://']) ? $user->avatar : asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}">
                                            @else
                                                {{ strtoupper(substr($user->name, 0, 2)) }}
                                            @endif
                                        </div>
                                        <div>
                                            <div class="user-name">{{ $user->name }}</div>
                                            <div class="user-email">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-role">
                                        <i class="fas fa-tag" style="font-size:10px;"></i>
                                        {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                    </span>
                                </td>
                                <td style="color:#64748b; font-size:13px;">
                                    {{ $user->created_at->format('d M Y') }}
                                </td>
                                <td>
                                    @if ($user->isSuspended())
                                        <div>
                                            <span class="badge badge-suspended">
                                                <i class="fas fa-ban" style="font-size:10px;"></i> {{ __('platform.suspended') }}
                                            </span>
                                            <div style="font-size: 11px; color: #ef4444; margin-top: 4px; font-weight: 600;">
                                                @if($user->suspended_until)
                                                    s.d {{ $user->suspended_until->format('d M Y, H:i') }}
                                                @else
                                                    Permanen
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <span class="badge badge-active">
                                            <i class="fas fa-circle" style="font-size:8px;"></i> {{ __('platform.active') }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-group">
                                        {{-- Tombol Detail / Inspeksi Seller --}}
                                        <button type="button" class="btn-action btn-detail" onclick="openSellerModal({{ $user->id }})">
                                            <i class="fas fa-id-badge"></i> {{ __('platform.view_seller_detail') }}
                                        </button>

                                        @if ($user->isSuspended())
                                            <form method="POST"
                                                  action="{{ route('platform-admin.users.activate', $user->id) }}"
                                                  style="display:inline;">
                                                @csrf
                                                <button type="button" class="btn-action btn-activate"
                                                        onclick="confirmActivateUser(this.form, '{{ addslashes($user->name) }}')">
                                                    <i class="fas fa-check"></i> {{ __('platform.activate') }}
                                                </button>
                                            </form>
                                        @else
                                            <button type="button" class="btn-action btn-suspend" 
                                                    onclick="openSuspendModal({{ $user->id }}, '{{ addslashes($user->name) }}')">
                                                <i class="fas fa-ban"></i> {{ __('platform.suspend') }}
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if ($users->hasPages())
                        <div class="pagination-wrap">
                            {{ $users->links() }}
                        </div>
                    @endif
                    @else
                    <div class="empty-state">
                        <i class="fas fa-users-slash"></i>
                        <p>{{ __('platform.no_users_found') }}</p>
                    </div>
                    @endif
                </div>

            @else
                {{-- Appeals View --}}
                <div class="table-card">
                    @if ($appeals->count() > 0)
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Seller</th>
                                <th>{{ __('platform.appeal_reason') }}</th>
                                <th>{{ __('platform.time') }}</th>
                                <th>{{ __('platform.status') }}</th>
                                <th>{{ __('platform.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($appeals as $i => $appeal)
                            <tr>
                                <td style="color:#94a3b8; font-weight:600; font-size:13px;">
                                    {{ $appeals->firstItem() + $i }}
                                </td>
                                <td>
                                    <div class="user-cell">
                                        <div class="user-avatar">
                                            @if ($appeal->user && $appeal->user->avatar)
                                                <img src="{{ Str::startsWith($appeal->user->avatar, ['http://', 'https://']) ? $appeal->user->avatar : asset('storage/' . $appeal->user->avatar) }}" alt="{{ $appeal->user->name }}">
                                            @else
                                                {{ strtoupper(substr($appeal->user->name ?? 'User', 0, 2)) }}
                                            @endif
                                        </div>
                                        <div>
                                            <div class="user-name">{{ $appeal->user->name ?? 'User Telah Dihapus' }}</div>
                                            <div class="user-email">{{ $appeal->user->email ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div style="font-size: 13px; color: #1e293b; line-height: 1.4; max-width: 380px;">
                                        {{ $appeal->appeal_reason }}
                                    </div>
                                    @if($appeal->admin_notes)
                                        <div style="font-size: 11px; color: #64748b; margin-top: 6px; background: #f8fafc; padding: 4px 8px; border-radius: 6px; border-left: 3px solid #5A5BF1;">
                                            <strong>{{ __('platform.admin_notes') }}:</strong> {{ $appeal->admin_notes }}
                                        </div>
                                    @endif
                                </td>
                                <td style="color:#64748b; font-size:12px; white-space: nowrap;">
                                    {{ $appeal->created_at->format('d M Y, H:i') }}
                                </td>
                                <td>
                                    @if($appeal->status === 'approved')
                                        <span class="badge badge-active">
                                            <i class="fas fa-check-circle"></i> {{ __('platform.appeal_approved') }}
                                        </span>
                                    @elseif($appeal->status === 'rejected')
                                        <span class="badge badge-suspended">
                                            <i class="fas fa-times-circle"></i> {{ __('platform.appeal_rejected') }}
                                        </span>
                                    @else
                                        <span class="badge badge-pending">
                                            <i class="fas fa-clock"></i> {{ __('platform.pending_review') }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($appeal->status === 'pending')
                                        <div class="action-group">
                                            <form action="{{ route('platform-admin.users.appeals.approve', $appeal->id) }}" method="POST" style="margin: 0;">
                                                @csrf
                                                <button type="button" class="btn-action btn-approve" onclick="confirmApproveAppeal(this.form, '{{ addslashes($appeal->user->name ?? 'Seller') }}')">
                                                    <i class="fas fa-check"></i> {{ __('platform.approve_appeal') }}
                                                </button>
                                            </form>
                                            <button type="button" class="btn-action btn-reject" onclick="openRejectAppealModal({{ $appeal->id }}, '{{ addslashes($appeal->user->name ?? 'Seller') }}')">
                                                <i class="fas fa-times"></i> {{ __('platform.reject_appeal') }}
                                            </button>
                                        </div>
                                    @else
                                        <span style="font-size: 12px; color: #94a3b8; font-weight: 600;">Selesai Ditinjau</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if ($appeals->hasPages())
                        <div class="pagination-wrap">
                            {{ $appeals->links() }}
                        </div>
                    @endif
                    @else
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <p>{{ __('platform.no_appeals_found') }}</p>
                    </div>
                    @endif
                </div>
            @endif

        </div>
    </div>

    <!-- Modal Suspend Akun (Pilihan Durasi & Alasan) -->
    <div id="suspendModal" class="modal">
        <div class="modal-container">
            <div class="modal-header">
                <h3><i class="fas fa-user-slash" style="color: #dc2626;"></i> {{ __('platform.suspend_account') }}</h3>
                <button type="button" class="modal-close" onclick="closeSuspendModal()">&times;</button>
            </div>
            <form id="suspendForm" method="POST" action="">
                @csrf
                <div class="modal-body">
                    <p style="font-size: 13px; color: #64748b; margin-bottom: 16px;">
                        Tentukan durasi penangguhan dan alasan suspend untuk akun <strong id="suspendTargetName" style="color: #1e293b;"></strong>:
                    </p>

                    <div class="form-group">
                        <label>{{ __('platform.suspend_duration') }}</label>
                        <div class="duration-grid">
                            <label class="duration-option">
                                <input type="radio" name="duration" value="1_day" required>
                                <div class="duration-card"><i class="fas fa-clock"></i> {{ __('platform.duration_1_day') }}</div>
                            </label>
                            <label class="duration-option">
                                <input type="radio" name="duration" value="3_days">
                                <div class="duration-card"><i class="fas fa-clock"></i> {{ __('platform.duration_3_days') }}</div>
                            </label>
                            <label class="duration-option">
                                <input type="radio" name="duration" value="7_days" checked>
                                <div class="duration-card"><i class="fas fa-calendar-week"></i> {{ __('platform.duration_7_days') }}</div>
                            </label>
                            <label class="duration-option">
                                <input type="radio" name="duration" value="30_days">
                                <div class="duration-card"><i class="fas fa-calendar-alt"></i> {{ __('platform.duration_30_days') }}</div>
                            </label>
                            <label class="duration-option" style="grid-column: span 2;">
                                <input type="radio" name="duration" value="permanent">
                                <div class="duration-card"><i class="fas fa-infinity"></i> {{ __('platform.duration_permanent') }}</div>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="suspend_reason">{{ __('platform.suspend_reason_label') }}</label>
                        <textarea id="suspend_reason" name="suspend_reason" rows="3" class="form-control" 
                                  placeholder="{{ __('platform.suspend_reason_placeholder') }}" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal-cancel" onclick="closeSuspendModal()">{{ __('platform.cancel') }}</button>
                    <button type="submit" class="btn-modal-submit-danger"><i class="fas fa-ban"></i> {{ __('platform.suspend') }}</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Tolak Permohonan Banding -->
    <div id="rejectAppealModal" class="modal">
        <div class="modal-container">
            <div class="modal-header">
                <h3><i class="fas fa-times-circle" style="color: #dc2626;"></i> {{ __('platform.reject_appeal') }}</h3>
                <button type="button" class="modal-close" onclick="closeRejectAppealModal()">&times;</button>
            </div>
            <form id="rejectAppealForm" method="POST" action="">
                @csrf
                <div class="modal-body">
                    <p style="font-size: 13px; color: #64748b; margin-bottom: 14px;">
                        Tolak permohonan banding dari <strong id="rejectTargetName" style="color: #1e293b;"></strong>. Berikan catatan penjelasan:
                    </p>
                    <div class="form-group">
                        <label for="admin_notes">{{ __('platform.rejection_reason_notes') }}</label>
                        <textarea id="admin_notes" name="admin_notes" rows="3" class="form-control" 
                                  placeholder="Tuliskan catatan alasan penolakan banding..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal-cancel" onclick="closeRejectAppealModal()">{{ __('platform.cancel') }}</button>
                    <button type="submit" class="btn-modal-submit-danger"><i class="fas fa-times"></i> {{ __('platform.reject_appeal') }}</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Detail / Inspeksi Lengkap Seller -->
    <div id="sellerModal" class="modal">
        <div class="modal-container modal-container-large">
            <div class="modal-header">
                <h3><i class="fas fa-user-shield" style="color: #5A5BF1;"></i> {{ __('platform.seller_profile_inspection') }}</h3>
                <button type="button" class="modal-close" onclick="closeSellerModal()">&times;</button>
            </div>
            <div class="modal-body" id="sellerModalBody">
                <div class="loading-spinner">
                    <i class="fas fa-spinner fa-spin" style="font-size: 28px; margin-bottom: 8px; display: block;"></i>
                    {{ __('platform.loading_data') }}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-modal-cancel" onclick="closeSellerModal()">{{ __('platform.close') }}</button>
            </div>
        </div>
    </div>

    <script>
        window.PlatformUsersConfig = {
            userBaseUrl: '{{ url('/platform-admin/users') }}',
            appealsBaseUrl: '{{ url('/platform-admin/users/appeals') }}',
            lang: {
                loading: '{{ __('platform.loading_data') }}',
                failed: '{{ __('platform.failed_to_load') }}',
                suspended: '{{ __('platform.suspended') }}',
                active: '{{ __('platform.active') }}',
                total_turnover: '{{ __('platform.total_turnover') }}',
                current_balance: '{{ __('platform.current_balance') }}',
                total_withdrawn: '{{ __('platform.total_withdrawn') }}',
                total_orders: '{{ __('platform.total_orders') }}',
                products_tab: '{{ __('platform.products_tab') }}',
                payouts_tab: '{{ __('platform.payouts_tab') }}',
                no_products: '{{ __('platform.no_products_seller') }}',
                no_payouts: '{{ __('platform.no_payouts_seller') }}'
            }
        };
    </script>
    <script src="{{ asset('js/platform/users.js') }}"></script>
</body>
</html>
