<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('platform.user_management') }} — Platform Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/platform/global.css') }}?v={{ time() }}">
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

                {{-- Tabs Navigation (Terpisah Mandiri) --}}
                <div class="tabs-container">
                    <a href="{{ route('platform-admin.users', array_merge(request()->except('filter', 'page'), ['filter' => 'all', 'view' => 'users'])) }}"
                       class="tab-link {{ ($filter ?? 'all') === 'all' ? 'active' : '' }}">
                        <i class="fas fa-users"></i> <span class="tab-label">{{ __('platform.all') }} ({{ $totalUsers }})</span>
                    </a>
                    <a href="{{ route('platform-admin.users', array_merge(request()->except('filter', 'page'), ['filter' => 'active', 'view' => 'users'])) }}"
                       class="tab-link {{ ($filter ?? '') === 'active' ? 'active' : '' }}">
                        <i class="fas fa-user-check"></i> <span class="tab-label">{{ __('platform.active') }} ({{ $totalActive }})</span>
                    </a>
                    <a href="{{ route('platform-admin.users', array_merge(request()->except('filter', 'page'), ['filter' => 'suspended', 'view' => 'users'])) }}"
                       class="tab-link {{ ($filter ?? '') === 'suspended' ? 'active' : '' }}">
                        <i class="fas fa-user-slash"></i> <span class="tab-label">{{ __('platform.suspended') }} ({{ $totalSuspended }})</span>
                    </a>
                </div>

                {{-- Toolbar Filter & Search --}}
                <div class="toolbar" style="display: flex; align-items: center; justify-content: flex-start; gap: 16px; flex-wrap: wrap;">
                    <form method="GET" action="{{ route('platform-admin.users') }}" class="search-form" style="max-width: none; width: 100%; display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
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
                </div>

                {{-- Users Table --}}
                <div class="table-card">
                    @if ($users->count() > 0)
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><i class="fas fa-user"></i> User</th>
                                <th><i class="fas fa-user-tag"></i> {{ __('platform.role') }}</th>
                                <th><i class="fas fa-calendar-alt"></i> {{ __('platform.joined_at') }}</th>
                                <th><i class="fas fa-shield-alt"></i> {{ __('platform.status') }}</th>
                                <th><i class="fas fa-cog"></i> {{ __('platform.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $i => $user)
                            <tr>
                                <td>
                                    <span class="table-index-badge">{{ $users->firstItem() + $i }}</span>
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
                                        <button type="button" class="btn-action btn-detail" onclick="openSellerModal({{ $user->id }})" title="{{ __('platform.view_seller_detail') }}">
                                            <i class="fas fa-id-badge"></i> <span>Detail</span>
                                        </button>

                                        @if ($user->isSuspended())
                                            <button type="button" class="btn-action btn-activate"
                                                    onclick="openActivateModal({{ $user->id }}, '{{ addslashes($user->name) }}')" title="{{ __('platform.activate') }}">
                                                <i class="fas fa-check"></i> <span>{{ __('platform.activate') }}</span>
                                            </button>
                                        @else
                                            <button type="button" class="btn-action btn-suspend"
                                                    onclick="openSuspendModal({{ $user->id }}, '{{ addslashes($user->name) }}')" title="{{ __('platform.suspend') }}">
                                                <i class="fas fa-ban"></i> <span>{{ __('platform.suspend') }}</span>
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
                                                    ]) }})" title="Tinjau Permohonan Banding">
                                                <i class="fas fa-file-contract"></i> <span>Banding</span><span class="appeal-badge-dot"></span>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if ($users->hasPages())
                        <div class="pagination-container">
                            {{ $users->links('platformadmin.partials.pagination') }}
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
        <div class="modal-container modal-action-container">
            <div class="modal-header">
                <div class="modal-header-left">
                    <div class="modal-header-icon danger">
                        <i class="fas fa-user-slash"></i>
                    </div>
                    <div>
                        <h3 class="modal-title">{{ __('platform.suspend_account') }}</h3>
                        <p class="modal-subtitle">Penangguhan akses akun & layanan seller secara terukur</p>
                    </div>
                </div>
                <button type="button" class="modal-close-round" onclick="closeSuspendModal()" aria-label="Tutup">&times;</button>
            </div>
            
            <form id="suspendForm" method="POST" action="">
                @csrf
                <div class="modal-body modal-action-body">
                    <!-- Target User Info Card -->
                    <div class="action-target-card danger">
                        <div class="action-target-avatar danger" id="suspendTargetAvatar">
                            <i class="fas fa-user-slash"></i>
                        </div>
                        <div class="action-target-meta">
                            <div class="action-target-label">Target Akun Seller</div>
                            <div class="action-target-name" id="suspendTargetName">-</div>
                        </div>
                        <div class="action-target-badge danger">
                            <i class="fas fa-shield-alt"></i> Penangguhan
                        </div>
                    </div>

                    <!-- Pilihan Durasi -->
                    <div class="form-group" style="margin-bottom: 12px;">
                        <div class="form-label-row">
                            <label class="form-section-label">
                                <i class="fas fa-hourglass-half"></i> {{ __('platform.suspend_duration') }}
                            </label>
                            <span class="form-label-hint">Pilih masa berlaku</span>
                        </div>
                        
                        <div class="duration-grid">
                            <label class="duration-option">
                                <input type="radio" name="duration" value="1_day" required>
                                <div class="duration-card">
                                    <div class="duration-card-icon">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div class="duration-card-content">
                                        <span class="duration-title">1 Hari</span>
                                        <span class="duration-sub">Masa singkat</span>
                                    </div>
                                    <div class="duration-radio-indicator"></div>
                                </div>
                            </label>
                            
                            <label class="duration-option">
                                <input type="radio" name="duration" value="3_days">
                                <div class="duration-card">
                                    <div class="duration-card-icon">
                                        <i class="fas fa-history"></i>
                                    </div>
                                    <div class="duration-card-content">
                                        <span class="duration-title">3 Hari</span>
                                        <span class="duration-sub">Peringatan awal</span>
                                    </div>
                                    <div class="duration-radio-indicator"></div>
                                </div>
                            </label>

                            <label class="duration-option">
                                <input type="radio" name="duration" value="7_days" checked>
                                <div class="duration-card">
                                    <div class="duration-card-icon">
                                        <i class="fas fa-calendar-week"></i>
                                    </div>
                                    <div class="duration-card-content">
                                        <span class="duration-title">7 Hari <span class="duration-pill">1 Minggu</span></span>
                                        <span class="duration-sub">Standar sanksi</span>
                                    </div>
                                    <div class="duration-radio-indicator"></div>
                                </div>
                            </label>

                            <label class="duration-option">
                                <input type="radio" name="duration" value="30_days">
                                <div class="duration-card">
                                    <div class="duration-card-icon">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    <div class="duration-card-content">
                                        <span class="duration-title">30 Hari <span class="duration-pill">1 Bulan</span></span>
                                        <span class="duration-sub">Pelanggaran berat</span>
                                    </div>
                                    <div class="duration-radio-indicator"></div>
                                </div>
                            </label>

                            <label class="duration-option duration-full">
                                <input type="radio" name="duration" value="permanent">
                                <div class="duration-card">
                                    <div class="duration-card-icon danger">
                                        <i class="fas fa-infinity"></i>
                                    </div>
                                    <div class="duration-card-content">
                                        <span class="duration-title">Permanen <span class="duration-pill danger">Selamanya</span></span>
                                        <span class="duration-sub">Blokir akun tanpa batas waktu (hanya bisa dibuka manual oleh admin)</span>
                                    </div>
                                    <div class="duration-radio-indicator"></div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Alasan Penangguhan -->
                    <div class="form-group" style="margin-bottom: 6px;">
                        <div class="form-label-row">
                            <label for="suspend_reason" class="form-section-label">
                                <i class="fas fa-comment-alt"></i> {{ __('platform.suspend_reason_label') }} <span class="required-star">*</span>
                            </label>
                            <span class="form-label-hint">Wajib diisi</span>
                        </div>

                        <!-- Quick Reason Preset Chips -->
                        <div class="reason-preset-chips">
                            <button type="button" class="reason-chip" onclick="applyReasonPreset('Pelanggaran Ketentuan Layanan (ToS)')">
                                <i class="fas fa-plus" style="font-size: 9px;"></i> ToS Platform
                            </button>
                            <button type="button" class="reason-chip" onclick="applyReasonPreset('Unggahan produk digital dilarang / melanggar hak cipta')">
                                <i class="fas fa-plus" style="font-size: 9px;"></i> Produk Dilarang
                            </button>
                            <button type="button" class="reason-chip" onclick="applyReasonPreset('Aktivitas mencurigakan / terindikasi penipuan (fraud)')">
                                <i class="fas fa-plus" style="font-size: 9px;"></i> Indikasi Penipuan
                            </button>
                            <button type="button" class="reason-chip" onclick="applyReasonPreset('Tindakan spam atau penyalahgunaan link microsite')">
                                <i class="fas fa-plus" style="font-size: 9px;"></i> Spam Link
                            </button>
                        </div>

                        <textarea id="suspend_reason" name="suspend_reason" rows="2" class="action-textarea"
                                  placeholder="{{ __('platform.suspend_reason_placeholder') }}" required></textarea>
                    </div>

                    <!-- Notice Callout -->
                    <div class="modal-notice-banner danger">
                        <i class="fas fa-info-circle"></i>
                        <span>Selama masa suspend, akses login dinonaktifkan dan halaman microsite seller dialihkan ke pemberitahuan sistem.</span>
                    </div>
                </div>
                
                <div class="modal-action-footer">
                    <button type="button" class="btn-modal-cancel-pill" onclick="closeSuspendModal()">{{ __('platform.cancel') }}</button>
                    <button type="submit" class="btn-modal-danger-pill">
                        <i class="fas fa-ban"></i>
                        <span>{{ __('platform.suspend') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Aktifkan Kembali Akun (Konfirmasi & Catatan Opsional) -->
    <div id="activateModal" class="modal">
        <div class="modal-container modal-action-container">
            <div class="modal-header">
                <div class="modal-header-left">
                    <div class="modal-header-icon success">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div>
                        <h3 class="modal-title">Aktifkan Kembali Akun</h3>
                        <p class="modal-subtitle">Pulihkan hak akses dashboard dan tautan microsite seller</p>
                    </div>
                </div>
                <button type="button" class="modal-close-round" onclick="closeActivateModal()" aria-label="Tutup">&times;</button>
            </div>
            
            <form id="activateForm" method="POST" action="">
                @csrf
                <div class="modal-body modal-action-body">
                    <!-- Target User Info Card -->
                    <div class="action-target-card success">
                        <div class="action-target-avatar success" id="activateTargetAvatar">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div class="action-target-meta">
                            <div class="action-target-label">Target Akun Seller</div>
                            <div class="action-target-name" id="activateTargetName">-</div>
                        </div>
                        <div class="action-target-badge success">
                            <i class="fas fa-check-circle"></i> Pemulihan Akses
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 8px;">
                        <div class="form-label-row">
                            <label for="activate_reason" class="form-section-label">
                                <i class="fas fa-sticky-note"></i> Catatan / Alasan Aktivasi
                            </label>
                            <span class="form-label-hint">Opsional</span>
                        </div>
                        <textarea id="activate_reason" name="activate_reason" rows="3" class="action-textarea"
                                  placeholder="Tuliskan catatan alasan pengaktifan kembali akun (opsional)..."></textarea>
                    </div>

                    <div class="modal-notice-banner success">
                        <i class="fas fa-check-circle"></i>
                        <span>Status penangguhan akan langsung dicabut dan seluruh tautan microsite seller kembali aktif.</span>
                    </div>
                </div>
                
                <div class="modal-action-footer">
                    <button type="button" class="btn-modal-cancel-pill" onclick="closeActivateModal()">{{ __('platform.cancel') }}</button>
                    <button type="submit" class="btn-modal-success-pill">
                        <i class="fas fa-check"></i>
                        <span>{{ __('platform.activate') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Tolak Permohonan Banding -->
    <div id="rejectAppealModal" class="modal">
        <div class="modal-container modal-action-container">
            <div class="modal-header">
                <div class="modal-header-left">
                    <div class="modal-header-icon danger">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div>
                        <h3 class="modal-title">{{ __('platform.reject_appeal') }}</h3>
                        <p class="modal-subtitle">Tolak permohonan banding pembukaan penangguhan akun</p>
                    </div>
                </div>
                <button type="button" class="modal-close-round" onclick="closeRejectAppealModal()" aria-label="Tutup">&times;</button>
            </div>
            
            <form id="rejectAppealForm" method="POST" action="">
                @csrf
                <div class="modal-body modal-action-body">
                    <!-- Target User Info Card -->
                    <div class="action-target-card danger">
                        <div class="action-target-avatar danger" id="rejectTargetAvatar">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div class="action-target-meta">
                            <div class="action-target-label">Pemohon Banding</div>
                            <div class="action-target-name" id="rejectTargetName">-</div>
                        </div>
                        <div class="action-target-badge danger">
                            <i class="fas fa-ban"></i> Tolak Banding
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 8px;">
                        <div class="form-label-row">
                            <label for="admin_notes" class="form-section-label">
                                <i class="fas fa-pen-alt"></i> {{ __('platform.rejection_reason_notes') }} <span class="required-star">*</span>
                            </label>
                            <span class="form-label-hint">Wajib diisi</span>
                        </div>
                        <textarea id="admin_notes" name="admin_notes" rows="3" class="action-textarea"
                                  placeholder="Tuliskan catatan alasan penolakan banding..." required></textarea>
                    </div>

                    <div class="modal-notice-banner danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>Catatan penolakan ini akan dikirimkan ke seller dan akun tetap berstatus ditangguhkan.</span>
                    </div>
                </div>
                
                <div class="modal-action-footer">
                    <button type="button" class="btn-modal-cancel-pill" onclick="closeRejectAppealModal()">{{ __('platform.cancel') }}</button>
                    <button type="submit" class="btn-modal-danger-pill">
                        <i class="fas fa-times"></i>
                        <span>{{ __('platform.reject_appeal') }}</span>
                    </button>
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
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 36px; height: 36px; border-radius: 10px; background: #fff7ed; color: #ed842c; display: flex; align-items: center; justify-content: center; font-size: 15px;">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <h3 style="margin: 0; font-size: 16px; font-weight: 800; color: #0f172a;">{{ __('platform.seller_profile_inspection') }}</h3>
                </div>
                <button type="button" class="modal-close" onclick="closeSellerModal()" aria-label="Tutup">&times;</button>
            </div>
            <div class="modal-body" id="sellerModalBody">
                <div class="seller-skeleton">
                    <!-- Skeleton Banner -->
                    <div class="seller-skeleton-banner">
                        <div class="seller-skeleton-banner-left">
                            <div class="skeleton-elem skeleton-avatar" style="width: 58px; height: 58px; border-radius: 50%;"></div>
                            <div class="seller-skeleton-info" style="gap: 8px;">
                                <div class="skeleton-elem" style="width: 170px; height: 20px; border-radius: 6px;"></div>
                                <div class="skeleton-elem" style="width: 250px; height: 13px; border-radius: 4px;"></div>
                                <div class="skeleton-elem" style="width: 140px; height: 22px; border-radius: 20px;"></div>
                            </div>
                        </div>
                        <div>
                            <div class="skeleton-elem" style="width: 85px; height: 26px; border-radius: 20px;"></div>
                        </div>
                    </div>

                    <!-- Skeleton 4 Mini Financial Stats Grid -->
                    <div class="modal-stats-grid">
                        <div class="modal-stat-card" style="padding: 14px;">
                            <div class="skeleton-elem" style="width: 60%; height: 11px; margin-bottom: 8px; border-radius: 4px;"></div>
                            <div class="skeleton-elem" style="width: 85%; height: 22px; border-radius: 6px;"></div>
                        </div>
                        <div class="modal-stat-card" style="padding: 14px;">
                            <div class="skeleton-elem" style="width: 60%; height: 11px; margin-bottom: 8px; border-radius: 4px;"></div>
                            <div class="skeleton-elem" style="width: 85%; height: 22px; border-radius: 6px;"></div>
                        </div>
                        <div class="modal-stat-card" style="padding: 14px;">
                            <div class="skeleton-elem" style="width: 60%; height: 11px; margin-bottom: 8px; border-radius: 4px;"></div>
                            <div class="skeleton-elem" style="width: 85%; height: 22px; border-radius: 6px;"></div>
                        </div>
                        <div class="modal-stat-card" style="padding: 14px;">
                            <div class="skeleton-elem" style="width: 60%; height: 11px; margin-bottom: 8px; border-radius: 4px;"></div>
                            <div class="skeleton-elem" style="width: 85%; height: 22px; border-radius: 6px;"></div>
                        </div>
                    </div>

                    <!-- Skeleton Extra Details Grid -->
                    <div class="seller-extra-grid">
                        <div class="extra-stat-box" style="padding: 14px;">
                            <div class="skeleton-elem" style="width: 45%; height: 13px; margin-bottom: 10px; border-radius: 4px;"></div>
                            <div style="display: flex; gap: 6px;">
                                <div class="skeleton-elem" style="width: 65px; height: 24px; border-radius: 6px;"></div>
                                <div class="skeleton-elem" style="width: 65px; height: 24px; border-radius: 6px;"></div>
                                <div class="skeleton-elem" style="width: 75px; height: 24px; border-radius: 6px;"></div>
                            </div>
                        </div>
                        <div class="extra-stat-box" style="padding: 14px;">
                            <div class="skeleton-elem" style="width: 45%; height: 13px; margin-bottom: 10px; border-radius: 4px;"></div>
                            <div style="display: flex; gap: 6px;">
                                <div class="skeleton-elem" style="width: 75px; height: 24px; border-radius: 6px;"></div>
                                <div class="skeleton-elem" style="width: 75px; height: 24px; border-radius: 6px;"></div>
                                <div class="skeleton-elem" style="width: 65px; height: 24px; border-radius: 6px;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Skeleton Tabs -->
                    <div class="modal-tabs-wrapper">
                        <div class="modal-tabs-nav">
                            <div class="skeleton-elem" style="width: 120px; height: 32px; border-radius: 8px;"></div>
                            <div class="skeleton-elem" style="width: 140px; height: 32px; border-radius: 8px;"></div>
                            <div class="skeleton-elem" style="width: 130px; height: 32px; border-radius: 8px;"></div>
                        </div>
                    </div>

                    <!-- Skeleton Mini Table -->
                    <div style="border: 1px solid #f1f5f9; border-radius: 12px; overflow: hidden; background: #fff;">
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
