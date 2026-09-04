<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('platform.user_management') }} — Platform Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/platform/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/platform/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/platform/notifications.css') }}">
    <link rel="stylesheet" href="{{ asset('css/platform/users.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/platform/tabs.css') }}">
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
            <div class="header-right">
                @include('platformadmin.partials.notifications')
                @include('platformadmin.partials.header_profile')
            </div>
        </div>

        <div class="content-wrapper">
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
                <div class="toolbar" style="display: flex; align-items: center; justify-content: flex-start; gap: 16px; flex-wrap: wrap;">
                    <form method="GET" action="{{ route('platform-admin.users') }}" class="search-form" style="max-width: none; flex: 0 0 auto; display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                        <input type="hidden" name="view" value="users">
                        @if($filter && $filter !== 'all')
                            <input type="hidden" name="filter" value="{{ $filter }}">
                        @endif
                        <div class="search-wrap">
                            <i class="fas fa-search"></i>
                            <input type="text" name="search" placeholder="{{ __('platform.search_user_placeholder') }}" value="{{ $search ?? '' }}" data-autocomplete-type="users" data-suggest-url="{{ route('platform-admin.users.suggest') }}" list="autocomplete-users">
                            <datalist id="autocomplete-users"></datalist>
                        </div>

                        <div class="date-picker-box" data-start-name="start_date" data-end-name="end_date" data-start-value="{{ $startDate ?? '' }}" data-end-value="{{ $endDate ?? '' }}" data-placeholder="Tanggal Daftar">
                            <i class="fas fa-calendar-alt date-picker-icon"></i>
                            <span class="date-range-display">Tanggal Daftar</span>
                            <button type="button" class="date-range-clear-btn" title="Reset Tanggal" style="display: none;"><i class="fas fa-times"></i></button>
                            <input type="hidden" name="start_date" value="{{ $startDate ?? '' }}" class="date-range-hidden-input">
                            <input type="hidden" name="end_date" value="{{ $endDate ?? '' }}" class="date-range-hidden-input">
                        </div>

                        <button type="submit" class="btn-filter"><i class="fas fa-filter"></i> {{ __('platform.filter') }}</button>

                        @if($search || $startDate || $endDate)
                            <a href="{{ route('platform-admin.users', ['view' => 'users', 'filter' => $filter ?? 'all']) }}" class="btn-reset"><i class="fas fa-rotate-left"></i> {{ __('platform.reset') }}</a>
                        @endif
                    </form>

                    <div class="filter-tabs">
                        <a href="{{ route('platform-admin.users', ['view' => 'users', 'filter' => 'all', 'search' => $search]) }}"
                           class="filter-tab {{ ($filter ?? 'all') === 'all' ? 'active' : '' }}">
                            <i class="fas fa-users"></i> {{ __('platform.all') }}
                        </a>
                        <a href="{{ route('platform-admin.users', ['view' => 'users', 'filter' => 'active', 'search' => $search]) }}"
                           class="filter-tab {{ ($filter ?? '') === 'active' ? 'active' : '' }}">
                            <i class="fas fa-user-check"></i> {{ __('platform.active') }}
                        </a>
                        <a href="{{ route('platform-admin.users', ['view' => 'users', 'filter' => 'suspended', 'search' => $search]) }}"
                           class="filter-tab {{ ($filter ?? '') === 'suspended' ? 'active' : '' }}">
                            <i class="fas fa-user-slash"></i> {{ __('platform.suspended') }}
                        </a>
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
                                            <div class="user-email">{{ \App\Models\ActivityLog::maskEmail($user->email) }}</div>
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

                                        {{-- Tombol Detail Banding (hanya jika ada banding pending) --}}
                                        @php $pendingAppeal = $user->suspensionAppeals->first(); @endphp
                                        @if ($pendingAppeal)
                                            <button type="button" class="btn-action btn-appeal"
                                                    onclick="openAppealDetailModal({{ json_encode([
                                                        'id'            => $pendingAppeal->id,
                                                        'user_name'     => $user->name,
                                                        'user_email'    => \App\Models\ActivityLog::maskEmail($user->email),
                                                        'appeal_reason' => $pendingAppeal->appeal_reason,
                                                        'submitted_at'  => $pendingAppeal->created_at->format('d M Y, H:i'),
                                                        'attempt'       => \App\Models\SuspensionAppeal::where('user_id', $user->id)->where('id', '<=', $pendingAppeal->id)->count(),
                                                        'approve_url'   => route('platform-admin.users.appeals.approve', $pendingAppeal->id),
                                                        'reject_url'    => route('platform-admin.users.appeals.reject', $pendingAppeal->id),
                                                    ]) }})">
                                                <i class="fas fa-file-contract"></i> Detail Banding
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

    <!-- Modal Detail Permohonan Banding Seller -->
    <div id="appealDetailModal" class="modal">
        <div class="modal-container modal-container-large">
            <div class="modal-header">
                <h3><i class="fas fa-file-contract" style="color: #ED842C;"></i> Detail Permohonan Banding</h3>
                <button type="button" class="modal-close" onclick="closeAppealDetailModal()">&times;</button>
            </div>
            <div class="modal-body" id="appealDetailBody">
                {{-- Filled by JS --}}
            </div>
            <div class="modal-footer" id="appealDetailFooter">
                <button type="button" class="btn-modal-cancel" onclick="closeAppealDetailModal()">{{ __('platform.close') }}</button>
            </div>
        </div>
    </div>

    <!-- Modal Tolak Permintaan Reset Password -->
    <!-- Modal Detail / Inspeksi Lengkap Seller -->
    <div id="sellerModal" class="modal">
        <div class="modal-container modal-container-large">
            <div class="modal-header">
                <h3><i class="fas fa-user-shield" style="color: #ED842C;"></i> {{ __('platform.seller_profile_inspection') }}</h3>
                <button type="button" class="modal-close" onclick="closeSellerModal()">&times;</button>
            </div>
            <div class="modal-body" id="sellerModalBody">
                <div class="seller-skeleton">
                    <!-- Skeleton Banner -->
                    <div class="seller-skeleton-banner">
                        <div class="seller-skeleton-banner-left">
                            <div class="skeleton-elem skeleton-avatar"></div>
                            <div class="seller-skeleton-info">
                                <div class="skeleton-elem" style="width: 150px; height: 18px; border-radius: 4px;"></div>
                                <div class="skeleton-elem" style="width: 230px; height: 13px; border-radius: 4px;"></div>
                                <div class="skeleton-elem" style="width: 120px; height: 12px; border-radius: 4px;"></div>
                            </div>
                        </div>
                        <div>
                            <div class="skeleton-elem" style="width: 80px; height: 26px; border-radius: 20px;"></div>
                        </div>
                    </div>

                    <!-- Skeleton 4 Mini Financial Stats Grid -->
                    <div class="modal-stats-grid">
                        <div class="modal-stat-box" style="padding: 16px 12px;">
                            <div class="skeleton-elem" style="width: 65%; height: 11px; margin-bottom: 8px; border-radius: 4px;"></div>
                            <div class="skeleton-elem" style="width: 85%; height: 20px; border-radius: 4px;"></div>
                        </div>
                        <div class="modal-stat-box" style="padding: 16px 12px;">
                            <div class="skeleton-elem" style="width: 65%; height: 11px; margin-bottom: 8px; border-radius: 4px;"></div>
                            <div class="skeleton-elem" style="width: 85%; height: 20px; border-radius: 4px;"></div>
                        </div>
                        <div class="modal-stat-box" style="padding: 16px 12px;">
                            <div class="skeleton-elem" style="width: 65%; height: 11px; margin-bottom: 8px; border-radius: 4px;"></div>
                            <div class="skeleton-elem" style="width: 85%; height: 20px; border-radius: 4px;"></div>
                        </div>
                        <div class="modal-stat-box" style="padding: 16px 12px;">
                            <div class="skeleton-elem" style="width: 65%; height: 11px; margin-bottom: 8px; border-radius: 4px;"></div>
                            <div class="skeleton-elem" style="width: 85%; height: 20px; border-radius: 4px;"></div>
                        </div>
                    </div>

                    <!-- Skeleton Extra Details Grid -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px;">
                        <div style="background: #f8fafc; padding: 14px 16px; border-radius: 10px; border: 1px solid #f1f5f9;">
                            <div class="skeleton-elem" style="width: 50%; height: 14px; margin-bottom: 8px; border-radius: 4px;"></div>
                            <div class="skeleton-elem" style="width: 80%; height: 12px; border-radius: 4px;"></div>
                        </div>
                        <div style="background: #f8fafc; padding: 14px 16px; border-radius: 10px; border: 1px solid #f1f5f9;">
                            <div class="skeleton-elem" style="width: 50%; height: 14px; margin-bottom: 8px; border-radius: 4px;"></div>
                            <div class="skeleton-elem" style="width: 80%; height: 12px; border-radius: 4px;"></div>
                        </div>
                    </div>

                    <!-- Skeleton Tabs -->
                    <div class="modal-tabs" style="margin-bottom: 16px; display: flex; gap: 10px;">
                        <div class="skeleton-elem" style="width: 110px; height: 32px; border-radius: 8px;"></div>
                        <div class="skeleton-elem" style="width: 140px; height: 32px; border-radius: 8px;"></div>
                        <div class="skeleton-elem" style="width: 130px; height: 32px; border-radius: 8px;"></div>
                    </div>

                    <!-- Skeleton Mini Table -->
                    <div style="border: 1px solid #f1f5f9; border-radius: 10px; overflow: hidden; background: #fff;">
                        <div style="padding: 12px 16px; background: #f8fafc; border-bottom: 1px solid #f1f5f9; display: flex; gap: 16px;">
                            <div class="skeleton-elem" style="width: 30%; height: 13px; border-radius: 4px;"></div>
                            <div class="skeleton-elem" style="width: 20%; height: 13px; border-radius: 4px;"></div>
                            <div class="skeleton-elem" style="width: 15%; height: 13px; border-radius: 4px;"></div>
                            <div class="skeleton-elem" style="width: 15%; height: 13px; border-radius: 4px;"></div>
                            <div class="skeleton-elem" style="width: 20%; height: 13px; border-radius: 4px;"></div>
                        </div>
                        <div style="padding: 14px 16px; border-bottom: 1px solid #f8fafc; display: flex; gap: 16px; align-items: center;">
                            <div class="skeleton-elem" style="width: 30%; height: 12px; border-radius: 4px;"></div>
                            <div class="skeleton-elem" style="width: 20%; height: 12px; border-radius: 4px;"></div>
                            <div class="skeleton-elem" style="width: 15%; height: 12px; border-radius: 4px;"></div>
                            <div class="skeleton-elem" style="width: 15%; height: 12px; border-radius: 4px;"></div>
                            <div class="skeleton-elem" style="width: 20%; height: 12px; border-radius: 4px;"></div>
                        </div>
                        <div style="padding: 14px 16px; display: flex; gap: 16px; align-items: center;">
                            <div class="skeleton-elem" style="width: 30%; height: 12px; border-radius: 4px;"></div>
                            <div class="skeleton-elem" style="width: 20%; height: 12px; border-radius: 4px;"></div>
                            <div class="skeleton-elem" style="width: 15%; height: 12px; border-radius: 4px;"></div>
                            <div class="skeleton-elem" style="width: 15%; height: 12px; border-radius: 4px;"></div>
                            <div class="skeleton-elem" style="width: 20%; height: 12px; border-radius: 4px;"></div>
                        </div>
                    </div>
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
            csrfToken: '{{ csrf_token() }}',
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
    @vite(['resources/js/app.js'])
    <script src="{{ asset('js/platform/notifications.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/platform/users.js') }}?v={{ time() }}"></script>
</body>
</html>
