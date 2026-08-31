<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Aktivitas Admin — Platform Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/platform/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/platform/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/platform/notifications.css') }}">
    <link rel="stylesheet" href="{{ asset('css/platform/activity.css') }}">
<link rel="stylesheet" href="{{ asset('css/platform/autocomplete.css') }}">
    <link rel="stylesheet" href="{{ asset('css/platform/tabs.css') }}">
</head>
<body>

    {{-- Sidebar --}}
    @include('platformadmin.sidebar.sidebarplatform')

    <div class="platform-main">
        {{-- Header --}}
        <div class="platform-header">
            <div class="platform-header-left">
                <button class="hamburger-btn" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
                <h1>{{ __('platform.activity_logs') }}</h1>
            </div>
            <div class="header-right">
                @include('platformadmin.partials.notifications')
                @include('platformadmin.partials.header_profile')
            </div>
        </div>

        <div class="content-wrapper">
            {{-- Stats Grid --}}
            <div class="stats-grid activity-summary-grid">
                <div class="stat-card total">
                    <div class="stat-icon-wrapper"><i class="fas fa-list"></i></div>
                    <div class="stat-info">
                        <div class="stat-label">{{ __('platform.total_activity') }}</div>
                        <div class="stat-val">{{ $totalLogsCount }} Log</div>
                        <div class="stat-sub">{{ __('platform.recorded_in_system') }}</div>
                    </div>
                </div>

                <div class="stat-card users">
                    <div class="stat-icon-wrapper"><i class="fas fa-user-shield"></i></div>
                    <div class="stat-info">
                        <div class="stat-label">Aksi Admin</div>
                        <div class="stat-val">{{ $adminActionCount }} Aksi</div>
                        <div class="stat-sub">Suspend / Verifikasi / Approval</div>
                    </div>
                </div>

                <div class="stat-card products">
                    <div class="stat-icon-wrapper"><i class="fas fa-key"></i></div>
                    <div class="stat-info">
                        <div class="stat-label">Autentikasi User</div>
                        <div class="stat-val">{{ $authActionCount }} Aksi</div>
                        <div class="stat-sub">Login / Daftar / Reset PW</div>
                    </div>
                </div>

                <div class="stat-card payouts">
                    <div class="stat-icon-wrapper"><i class="fas fa-store"></i></div>
                    <div class="stat-info">
                        <div class="stat-label">Aktivitas Seller</div>
                        <div class="stat-val">{{ $sellerActionCount }} Aksi</div>
                        <div class="stat-sub">Produk / Payout / Shortlink</div>
                    </div>
                </div>
            </div>

            {{-- Tabs --}}
            <div class="tabs-container">
                <a href="{{ route('platform-admin.logs.activity', array_merge(request()->except('category', 'page'), ['category' => 'all'])) }}"
                   class="tab-link {{ ($category ?? 'all') === 'all' ? 'active' : '' }}">
                    <i class="fas fa-th-list"></i> {{ __('platform.all_activities') }}
                </a>
                <a href="{{ route('platform-admin.logs.activity', array_merge(request()->except('category', 'page'), ['category' => 'admin'])) }}"
                   class="tab-link {{ ($category ?? '') === 'admin' ? 'active' : '' }}">
                    <i class="fas fa-user-shield"></i> Aksi Admin Platform
                </a>
                <a href="{{ route('platform-admin.logs.activity', array_merge(request()->except('category', 'page'), ['category' => 'auth'])) }}"
                   class="tab-link {{ ($category ?? '') === 'auth' ? 'active' : '' }}">
                    <i class="fas fa-fingerprint"></i> Autentikasi Pengguna
                </a>
                <a href="{{ route('platform-admin.logs.activity', array_merge(request()->except('category', 'page'), ['category' => 'seller'])) }}"
                   class="tab-link {{ ($category ?? '') === 'seller' ? 'active' : '' }}">
                    <i class="fas fa-store"></i> Aktivitas Seller
                </a>
            </div>

            {{-- Filter & Search Card --}}
            <div class="filter-card">
                <form method="GET" action="{{ route('platform-admin.logs.activity') }}" class="search-form">
                    <input type="hidden" name="category" value="{{ $category }}">

                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="{{ __('platform.search_activity_placeholder') }}" data-autocomplete-type="activity" data-suggest-url="{{ route('platform-admin.logs.activity.suggest') }}" list="autocomplete-activity">
                        <datalist id="autocomplete-activity"></datalist>
                    </div>

                    <select name="action" class="filter-select">
                        <option value="">{{ __('platform.all_action_types') }}</option>
                        <optgroup label="Autentikasi & Akun">
                            <option value="user_login" {{ ($action ?? '') === 'user_login' ? 'selected' : '' }}>User Login</option>
                            <option value="user_logout" {{ ($action ?? '') === 'user_logout' ? 'selected' : '' }}>User Logout</option>
                            <option value="user_register" {{ ($action ?? '') === 'user_register' ? 'selected' : '' }}>User Register (Buat Akun)</option>
                            <option value="password_reset_otp_sent" {{ ($action ?? '') === 'password_reset_otp_sent' ? 'selected' : '' }}>Request OTP Reset Password</option>
                            <option value="password_reset_success" {{ ($action ?? '') === 'password_reset_success' ? 'selected' : '' }}>Reset Password Berhasil</option>
                            <option value="update_account" {{ ($action ?? '') === 'update_account' ? 'selected' : '' }}>Update Pengaturan Akun</option>
                        </optgroup>
                        <optgroup label="Aktivitas Seller">
                            <option value="create_product" {{ ($action ?? '') === 'create_product' ? 'selected' : '' }}>Tambah Produk Digital</option>
                            <option value="update_product" {{ ($action ?? '') === 'update_product' ? 'selected' : '' }}>Update Produk Digital</option>
                            <option value="delete_product" {{ ($action ?? '') === 'delete_product' ? 'selected' : '' }}>Hapus Produk Digital</option>
                            <option value="request_payout" {{ ($action ?? '') === 'request_payout' ? 'selected' : '' }}>Pengajuan Payout / Withdraw</option>
                            <option value="create_shortlink" {{ ($action ?? '') === 'create_shortlink' ? 'selected' : '' }}>Buat Shortlink</option>
                            <option value="update_shortlink" {{ ($action ?? '') === 'update_shortlink' ? 'selected' : '' }}>Update Shortlink</option>
                            <option value="create_support_ticket" {{ ($action ?? '') === 'create_support_ticket' ? 'selected' : '' }}>Buat Tiket Bantuan</option>
                            <option value="reply_support_ticket" {{ ($action ?? '') === 'reply_support_ticket' ? 'selected' : '' }}>Balas Tiket (Seller)</option>
                        </optgroup>
                        <optgroup label="Aksi Admin Platform">
                            <option value="suspend_user" {{ ($action ?? '') === 'suspend_user' ? 'selected' : '' }}>Suspend User</option>
                            <option value="activate_user" {{ ($action ?? '') === 'activate_user' ? 'selected' : '' }}>{{ __('platform.activate') }} User</option>
                            <option value="approve_product" {{ ($action ?? '') === 'approve_product' ? 'selected' : '' }}>Approve Produk</option>
                            <option value="reject_product" {{ ($action ?? '') === 'reject_product' ? 'selected' : '' }}>Reject Produk</option>
                            <option value="approve_payout" {{ ($action ?? '') === 'approve_payout' ? 'selected' : '' }}>Approve Payout</option>
                            <option value="reject_payout" {{ ($action ?? '') === 'reject_payout' ? 'selected' : '' }}>Reject Payout</option>
                            <option value="admin_reply_ticket" {{ ($action ?? '') === 'admin_reply_ticket' ? 'selected' : '' }}>Balas Tiket (Admin)</option>
                            <option value="update_ticket_status" {{ ($action ?? '') === 'update_ticket_status' ? 'selected' : '' }}>Update Status Tiket</option>
                            <option value="update_platform_settings" {{ ($action ?? '') === 'update_platform_settings' ? 'selected' : '' }}>Update Setting Platform</option>
                            <option value="create_broadcast" {{ ($action ?? '') === 'create_broadcast' ? 'selected' : '' }}>Kirim Broadcast</option>
                        </optgroup>
                    </select>

                    <div class="date-picker-box">
                        <i class="fas fa-calendar-alt date-picker-icon"></i>
                        <input type="date" name="start_date" value="{{ $startDate ?? '' }}" class="date-input" title="{{ __('platform.filter_by_date') }}">
                    </div>

                    <button type="submit" class="btn-filter"><i class="fas fa-filter"></i> {{ __('platform.filter') }}</button>
                    @if($search || $action || $startDate || $endDate)
                        <a href="{{ route('platform-admin.logs.activity', ['category' => $category]) }}" class="btn-reset">{{ __('platform.reset') }}</a>
                    @endif
                </form>
            </div>

            {{-- Table Card --}}
            <div class="table-card">
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('platform.time') }}</th>
                                <th>Pengguna / Aktor</th>
                                <th>{{ __('platform.action') }}</th>
                                <th>{{ __('platform.activity_description') }}</th>
                                <th>{{ __('platform.ip_and_device') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $index => $log)
                            <tr>
                                <td style="font-weight: 700; color: #94a3b8;">
                                    {{ $logs->firstItem() + $index }}
                                </td>
                                <td style="white-space: nowrap;">
                                    <div style="font-weight: 700; color: #1e293b;">
                                        {{ $log->created_at->format('d M Y, H:i') }}
                                    </div>
                                    <div style="font-size: 11px; color: #94a3b8;">
                                        {{ $log->created_at->diffForHumans() }}
                                    </div>
                                </td>
                                <td>
                                    <div class="admin-cell">
                                        <div class="admin-avatar" style="{{ ($log->user && $log->user->role === 'admin_seller') ? 'background: #0284c7;' : '' }}">
                                            {{ strtoupper(substr($log->user->name ?? 'U', 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="admin-name">
                                                {{ $log->user->name ?? 'Pengguna Sistem' }}
                                                @if($log->user && $log->user->role === 'admin_platform')
                                                    <span class="user-role-badge role-admin">Admin</span>
                                                @elseif($log->user && $log->user->role === 'admin_seller')
                                                    <span class="user-role-badge role-seller">Seller</span>
                                                @endif
                                            </div>
                                            <div class="admin-email">{{ \App\Models\ActivityLog::maskEmail($log->user->email ?? null) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $badgeClass = 'badge-default';
                                        $icon = 'info-circle';
                                        $label = ucwords(str_replace('_', ' ', $log->action));

                                        switch($log->action) {
                                            case 'user_login':
                                                $badgeClass = 'badge-login';
                                                $icon = 'sign-in-alt';
                                                $label = 'User Login';
                                                break;
                                            case 'user_logout':
                                                $badgeClass = 'badge-logout';
                                                $icon = 'sign-out-alt';
                                                $label = 'User Logout';
                                                break;
                                            case 'user_register':
                                                $badgeClass = 'badge-register';
                                                $icon = 'user-plus';
                                                $label = 'Buat Akun';
                                                break;
                                            case 'password_reset_otp_sent':
                                                $badgeClass = 'badge-reset-otp';
                                                $icon = 'key';
                                                $label = 'Request OTP PW';
                                                break;
                                            case 'password_reset_success':
                                                $badgeClass = 'badge-activate';
                                                $icon = 'check-double';
                                                $label = 'Reset PW Sukses';
                                                break;
                                            case 'create_product':
                                                $badgeClass = 'badge-product-create';
                                                $icon = 'box';
                                                $label = 'Tambah Produk';
                                                break;
                                            case 'update_product':
                                                $badgeClass = 'badge-product-update';
                                                $icon = 'edit';
                                                $label = 'Update Produk';
                                                break;
                                            case 'delete_product':
                                                $badgeClass = 'badge-product-delete';
                                                $icon = 'trash-alt';
                                                $label = 'Hapus Produk';
                                                break;
                                            case 'request_payout':
                                                $badgeClass = 'badge-payout-req';
                                                $icon = 'hand-holding-usd';
                                                $label = 'Ajukan Payout';
                                                break;
                                            case 'create_shortlink':
                                                $badgeClass = 'badge-shortlink';
                                                $icon = 'link';
                                                $label = 'Buat Shortlink';
                                                break;
                                            case 'update_shortlink':
                                                $badgeClass = 'badge-shortlink';
                                                $icon = 'link';
                                                $label = 'Update Shortlink';
                                                break;
                                            case 'update_account':
                                                $badgeClass = 'badge-account';
                                                $icon = 'user-cog';
                                                $label = 'Update Profil';
                                                break;
                                            case 'suspend_user':
                                                $badgeClass = 'badge-suspend';
                                                $icon = 'ban';
                                                $label = 'Suspend User';
                                                break;
                                            case 'activate_user':
                                                $badgeClass = 'badge-activate';
                                                $icon = 'check-circle';
                                                $label = 'Aktivasi User';
                                                break;
                                            case 'approve_product':
                                                $badgeClass = 'badge-approve';
                                                $icon = 'check';
                                                $label = 'Approve Produk';
                                                break;
                                            case 'reject_product':
                                                $badgeClass = 'badge-reject';
                                                $icon = 'times';
                                                $label = 'Reject Produk';
                                                break;
                                            case 'approve_payout':
                                                $badgeClass = 'badge-approve';
                                                $icon = 'money-bill-wave';
                                                $label = 'Approve Payout';
                                                break;
                                            case 'reject_payout':
                                                $badgeClass = 'badge-reject';
                                                $icon = 'times-circle';
                                                $label = 'Reject Payout';
                                                break;
                                            case 'create_support_ticket':
                                                $badgeClass = 'badge-reset-otp';
                                                $icon = 'headset';
                                                $label = 'Buat Tiket';
                                                break;
                                            case 'reply_support_ticket':
                                                $badgeClass = 'badge-shortlink';
                                                $icon = 'comment-dots';
                                                $label = 'Balas Tiket';
                                                break;
                                            case 'admin_reply_ticket':
                                                $badgeClass = 'badge-approve';
                                                $icon = 'reply';
                                                $label = 'Balas Tiket';
                                                break;
                                            case 'update_ticket_status':
                                                $badgeClass = 'badge-activate';
                                                $icon = 'tasks';
                                                $label = 'Status Tiket';
                                                break;
                                            case 'update_platform_settings':
                                                $badgeClass = 'badge-default';
                                                $icon = 'cogs';
                                                $label = 'Setting Platform';
                                                break;
                                            case 'create_broadcast':
                                            case 'delete_broadcast':
                                                $badgeClass = 'badge-default';
                                                $icon = 'bullhorn';
                                                $label = 'Broadcast Pesan';
                                                break;
                                        }
                                    @endphp
                                    <span class="badge-action {{ $badgeClass }}">
                                        <i class="fas fa-{{ $icon }}"></i> {{ $label }}
                                    </span>
                                </td>
                                <td>
                                    <div class="desc-text">{{ \App\Models\ActivityLog::maskSensitiveText($log->description) }}</div>
                                </td>
                                <td>
                                    <div class="ip-badge">{{ \App\Models\ActivityLog::maskIp($log->ip_address) }}</div>
                                    @if($log->user_agent)
                                        <div class="ua-text" title="{{ __('platform.user_agent_hidden') }}">
                                            {{ __('platform.device_recorded') }}
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <i class="fas fa-clipboard"></i>
                                        <p>{{ __('platform.no_activity_logs') }}</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($logs->hasPages())
                    <div class="pagination-container">
                        {{ $logs->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    <script src="{{ asset('js/platform/notifications.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/platform/search-autocomplete.js') }}"></script>
    <script src="{{ asset('js/platform/activity.js') }}"></script>
</body>
</html>
