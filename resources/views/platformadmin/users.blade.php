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
            <div class="header-right">
                @include('platformadmin.partials.notifications')
                <div class="header-user">
                    <div class="header-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                    <span>{{ Auth::user()->name }}</span>
                </div>
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
            @if (session('password_reset_result'))
                @php $res = session('password_reset_result'); @endphp
                <div class="alert alert-success" style="display: block; padding: 16px 20px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                        <div>
                            <i class="fas fa-key" style="color: #16a34a; margin-right: 6px;"></i>
                            <strong>Password Berhasil Direset untuk {{ $res['user_name'] }} ({{ $res['user_email'] }}):</strong>
                            <div style="margin-top: 6px; font-size: 13px;">
                                Password Sementara: <strong style="font-family: monospace; font-size: 15px; background: #fff; padding: 3px 8px; border-radius: 6px; border: 1px solid #86efac; color: #15803d;" id="flashTempPw">{{ $res['temp_password'] }}</strong>
                            </div>
                        </div>
                        <button type="button" class="btn-copy-pw" onclick="copyPasswordDirect('{{ $res['temp_password'] }}', this)">
                            <i class="fas fa-copy"></i> Salin Password
                        </button>
                    </div>
                </div>
            @endif

            @if (session('otp_approved_result'))
                @php $otpRes = session('otp_approved_result'); @endphp
                <div class="alert alert-success" style="display: block; padding: 14px 18px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                        <div>
                            <i class="fas fa-check-circle" style="color: #16a34a; margin-right: 6px;"></i>
                            <strong>Permintaan Reset Password untuk {{ $otpRes['email'] }} Berhasil Disetujui!</strong>
                            <div style="margin-top: 4px; font-size: 13px; color: #166534;">
                                Kode OTP (<strong style="font-family: monospace; letter-spacing: 2px;">******</strong>) telah diaktifkan dan otomatis tersinkronisasi ke halaman reset password seller.
                            </div>
                        </div>
                    </div>
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
                <a href="{{ route('platform-admin.users', ['view' => 'reset_requests']) }}" 
                   class="view-tab-link {{ $viewType === 'reset_requests' ? 'active' : '' }}">
                    <i class="fas fa-key"></i> Permintaan Reset OTP
                    @if($pendingResetRequestsCount > 0)
                        <span class="tab-counter" style="background: #d97706;">{{ $pendingResetRequestsCount }}</span>
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

                                        {{-- Tombol Reset Password User --}}
                                        <button type="button" class="btn-action btn-reset-pw" 
                                                onclick="openResetPasswordModal({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ addslashes($user->email) }}')"
                                                title="Reset Password Pengguna">
                                            <i class="fas fa-key"></i> Reset PW
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

            @elseif($viewType === 'appeals')
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
                                            @php
                                                $userAppealAttempt = \App\Models\SuspensionAppeal::where('user_id', $appeal->user_id)
                                                    ->where('id', '<=', $appeal->id)
                                                    ->count();
                                            @endphp
                                            <div class="user-name" style="display: flex; align-items: center; gap: 6px;">
                                                <span>{{ $appeal->user->name ?? 'User Telah Dihapus' }}</span>
                                                <span style="font-size: 10px; font-weight: 800; background: #fff0e2; color: #ED842C; padding: 1px 6px; border-radius: 4px;">Ke-{{ $userAppealAttempt }}/3</span>
                                            </div>
                                            <div class="user-email">{{ $appeal->user->email ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div style="font-size: 13px; color: #1e293b; line-height: 1.4; max-width: 380px;">
                                        {{ $appeal->appeal_reason }}
                                    </div>
                                    @if($appeal->admin_notes)
                                        <div style="font-size: 11px; color: #64748b; margin-top: 6px; background: #f8fafc; padding: 4px 8px; border-radius: 6px; border-left: 3px solid #ED842C;">
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

            @elseif($viewType === 'reset_requests')
                {{-- Reset Password Requests View --}}
                <div class="table-card">
                    @if ($resetRequests->count() > 0)
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Seller / Akun</th>
                                <th>Alasan / Catatan Kendala</th>
                                <th>Waktu Pengajuan</th>
                                <th>Status</th>
                                <th>Kode OTP</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($resetRequests as $req)
                            <tr>
                                <td style="color: #94a3b8; font-weight: 700;">#{{ $req->id }}</td>
                                <td>
                                    <div class="user-info-cell">
                                        <div class="user-avatar" style="background: #d97706;">
                                            {{ strtoupper(substr($req->user->name ?? $req->email, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="user-name">{{ $req->user->name ?? 'Pengguna' }}</div>
                                            <div class="user-email">{{ $req->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div style="font-size: 13px; color: #334155; max-width: 260px; line-height: 1.4;">
                                        {{ $req->reason ?? '-' }}
                                    </div>
                                    @if($req->admin_notes)
                                        <div style="font-size: 11px; color: #64748b; margin-top: 4px;">
                                            <em>Catatan Admin: {{ $req->admin_notes }}</em>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div style="font-size: 13px; color: #1e293b; font-weight: 600;">
                                        {{ $req->created_at->format('d M Y') }}
                                    </div>
                                    <div style="font-size: 11px; color: #94a3b8;">
                                        {{ $req->created_at->format('H:i') }} ({{ $req->created_at->diffForHumans() }})
                                    </div>
                                </td>
                                <td>
                                    @if($req->status === 'approved')
                                        <span class="badge badge-active">
                                            <i class="fas fa-check-circle"></i> OTP Aktif
                                        </span>
                                    @elseif($req->status === 'completed')
                                        <span class="badge badge-role">
                                            <i class="fas fa-check-double"></i> Selesai Direset
                                        </span>
                                    @elseif($req->status === 'rejected')
                                        <span class="badge badge-suspended">
                                            <i class="fas fa-times-circle"></i> Ditolak
                                        </span>
                                    @else
                                        <span class="badge badge-pending">
                                            <i class="fas fa-clock"></i> Menunggu Persetujuan
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($req->otp_code && in_array($req->status, ['approved', 'completed']))
                                        <span style="display: inline-flex; align-items: center; background: #fff8f2; border: 1.5px solid rgba(237, 132, 44, 0.25); padding: 4px 12px; border-radius: 6px; font-family: monospace; font-size: 15px; font-weight: 800; color: #ED842C; letter-spacing: 2px;" title="Kode OTP Terenkripsi (Tersinkronisasi Otomatis ke Seller)">
                                            ******
                                        </span>
                                    @else
                                        <span style="color: #94a3b8; font-size: 12px;">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($req->status === 'pending')
                                        <div class="action-group">
                                            <form action="{{ route('platform-admin.reset-requests.approve', $req->id) }}" method="POST" style="margin: 0;">
                                                @csrf
                                                <button type="button" class="btn-action btn-approve" 
                                                        onclick="confirmApproveResetReq(this.form, '{{ addslashes($req->user->name ?? $req->email) }}')">
                                                    <i class="fas fa-key"></i> Setujui & Buat OTP
                                                </button>
                                            </form>
                                            <button type="button" class="btn-action btn-reject" 
                                                    onclick="openRejectResetReqModal({{ $req->id }}, '{{ addslashes($req->user->name ?? $req->email) }}')">
                                                <i class="fas fa-times"></i> Tolak
                                            </button>
                                        </div>
                                    @else
                                        <span style="font-size: 12px; color: #94a3b8; font-weight: 600;">Selesai Diproses</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if ($resetRequests->hasPages())
                        <div class="pagination-wrap">
                            {{ $resetRequests->links() }}
                        </div>
                    @endif
                    @else
                    <div class="empty-state">
                        <i class="fas fa-key" style="color: #cbd5e1;"></i>
                        <p>Tidak ada permohonan reset password dari seller saat ini.</p>
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

    <!-- Modal Tolak Permintaan Reset Password -->
    <div id="rejectResetReqModal" class="modal">
        <div class="modal-container">
            <div class="modal-header">
                <h3><i class="fas fa-times-circle" style="color: #dc2626;"></i> Tolak Permintaan Reset Password</h3>
                <button type="button" class="modal-close" onclick="closeRejectResetReqModal()">&times;</button>
            </div>
            <form id="rejectResetReqForm" method="POST" action="">
                @csrf
                <div class="modal-body">
                    <p style="font-size: 13px; color: #64748b; margin-bottom: 14px;">
                        Tolak permintaan reset password untuk akun <strong id="rejectResetTargetName" style="color: #1e293b;"></strong>. Berikan alasan penolakan:
                    </p>
                    <div class="form-group">
                        <label for="reject_reset_admin_notes">Catatan Alasan Penolakan</label>
                        <textarea id="reject_reset_admin_notes" name="admin_notes" rows="3" class="form-control" 
                                  placeholder="Contoh: Email tidak sesuai dengan data terverifikasi..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal-cancel" onclick="closeRejectResetReqModal()">Batal</button>
                    <button type="submit" class="btn-modal-submit-danger"><i class="fas fa-times"></i> Tolak Permintaan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Detail / Inspeksi Lengkap Seller -->
    <div id="sellerModal" class="modal">
        <div class="modal-container modal-container-large">
            <div class="modal-header">
                <h3><i class="fas fa-user-shield" style="color: #ED842C;"></i> {{ __('platform.seller_profile_inspection') }}</h3>
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

    <!-- Modal Reset Password User -->
    <div id="resetPasswordModal" class="modal">
        <div class="modal-container">
            <div class="modal-header">
                <h3><i class="fas fa-key" style="color: #d97706;"></i> Reset Password Pengguna</h3>
                <button type="button" class="modal-close" onclick="closeResetPasswordModal()">&times;</button>
            </div>
            <form id="resetPasswordForm" onsubmit="submitResetPassword(event)">
                @csrf
                <input type="hidden" id="resetUserId" value="">
                <div class="modal-body">
                    <p style="font-size: 13px; color: #64748b; margin-bottom: 16px;">
                        Reset password akun <strong id="resetTargetName" style="color: #1e293b;"></strong> (<span id="resetTargetEmail" style="color: #ED842C;"></span>):
                    </p>

                    <div class="form-group">
                        <label>Metode Reset Password</label>
                        <div class="mode-selector">
                            <label class="mode-card" onclick="selectResetMode('auto')">
                                <input type="radio" name="reset_mode" value="auto" id="modeAuto" checked>
                                <div>
                                    <div class="mode-card-title"><i class="fas fa-magic" style="color: #ED842C;"></i> Buat Acak</div>
                                    <div class="mode-card-desc">Generate password sementara acak otomatis.</div>
                                </div>
                            </label>
                            <label class="mode-card" onclick="selectResetMode('manual')">
                                <input type="radio" name="reset_mode" value="manual" id="modeManual">
                                <div>
                                    <div class="mode-card-title"><i class="fas fa-keyboard" style="color: #d97706;"></i> Manual</div>
                                    <div class="mode-card-desc">Tentukan password baru secara manual.</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="form-group" id="manualPasswordGroup" style="display: none;">
                        <label for="manualNewPassword">Password Baru (Minimal 8 Karakter)</label>
                        <div style="position: relative;">
                            <input type="password" id="manualNewPassword" name="new_password" class="form-control" 
                                   placeholder="Ketik password baru minimal 8 karakter..." style="padding-right: 40px;">
                            <button type="button" onclick="togglePasswordVisibility('manualNewPassword', this)" 
                                    style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #94a3b8; cursor: pointer;">
                                <i class="far fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="alert alert-info" style="background: #f8f9ff; border: 1px solid #e0e7ff; color: #4338ca; padding: 10px 14px; border-radius: 8px; font-size: 12px; margin-top: 12px;">
                        <i class="fas fa-info-circle"></i> Setelah direset, Anda akan mendapatkan password sementara untuk dibagikan kepada seller terkait.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal-cancel" onclick="closeResetPasswordModal()">Batal</button>
                    <button type="submit" class="btn-modal-submit-primary" id="btnSubmitReset">
                        <i class="fas fa-key"></i> Reset Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Hasil Reset Password (Temp Password + Copy) -->
    <div id="resetResultModal" class="modal">
        <div class="modal-container">
            <div class="modal-header">
                <h3><i class="fas fa-check-circle" style="color: #16a34a;"></i> Password Berhasil Direset!</h3>
                <button type="button" class="modal-close" onclick="closeResetResultModal()">&times;</button>
            </div>
            <div class="modal-body">
                <p style="font-size: 13px; color: #475569; margin-bottom: 12px;">
                    Password baru untuk <strong id="resultUserName" style="color: #1e293b;"></strong> (<span id="resultUserEmail"></span>) telah aktif.
                </p>

                <div class="temp-pw-box">
                    <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 6px;">
                        Password Baru / Sementara:
                    </div>
                    <div class="temp-pw-text" id="resultTempPassword">--------</div>
                    <button type="button" class="btn-copy-pw" id="btnCopyResultModal" onclick="copyResultPassword(this)">
                        <i class="fas fa-copy"></i> Salin Password
                    </button>
                </div>

                <div style="background: #fffbeb; border: 1px solid #fef3c7; color: #92400e; padding: 12px 14px; border-radius: 8px; font-size: 12px; line-height: 1.5;">
                    <i class="fas fa-exclamation-triangle" style="color: #d97706; margin-right: 4px;"></i>
                    <strong>Penting:</strong> Harap catat dan berikan password ini kepada seller. Anjurkan seller untuk segera memperbarui password mereka melalui menu <em>Pengaturan Akun</em> setelah berhasil masuk.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-modal-cancel" onclick="closeResetResultModal()">Tutup</button>
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
    <script src="{{ asset('js/platform/notifications.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/platform/users.js') }}?v={{ time() }}"></script>
</body>
</html>
