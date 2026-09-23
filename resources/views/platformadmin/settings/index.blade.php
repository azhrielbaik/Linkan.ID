<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('platform.platform_settings') }} — Platform Admin</title>
    @include('platformadmin.partials.head_assets')
    <link rel="stylesheet" href="{{ asset('css/platform/settings.css') }}?v={{ file_exists(public_path('css/platform/settings.css')) ? filemtime(public_path('css/platform/settings.css')) : time() }}">
</head>
<body>

    {{-- Sidebar --}}
    @include('platformadmin.sidebar.sidebarplatform')

    <div class="platform-main">
        {{-- Header --}}
        <div class="platform-header">
            <div class="platform-header-left">
                <button class="hamburger-btn" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
                <h1>{{ __('platform.platform_settings') }}</h1>
            </div>
            <div class="header-right">
                @include('platformadmin.partials.notifications')
                @include('platformadmin.partials.header_profile')
            </div>
        </div>

        <div class="content-wrapper">
            <div class="settings-grid">

                {{-- Card 1: Pengaturan Komisi & Withdraw --}}
                <div class="setting-card">
                    <div class="setting-card-header">
                        <div class="setting-card-icon">
                            <i class="fas fa-coins"></i>
                        </div>
                        <div class="setting-card-title-wrap">
                            <h2>{{ __('platform.commission_and_withdrawal') }}</h2>
                            <p>{{ __('platform.commission_and_withdrawal_desc') }}</p>
                        </div>
                    </div>

                    {{-- Quick Stats Box --}}
                    <div class="settings-stats-preview">
                        <div class="settings-stat-item">
                            <span class="settings-stat-lbl">Komisi Saat Ini</span>
                            <span class="settings-stat-val highlight">{{ $commissionPercent }}%</span>
                        </div>
                        <div class="settings-stat-item">
                            <span class="settings-stat-lbl">Batas Min. Withdraw</span>
                            <span class="settings-stat-val">Rp {{ number_format($minWithdrawAmount, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <form id="financialSettingsForm" action="{{ route('platform-admin.settings.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="admin_password" id="form_admin_password">

                        {{-- Set Persentase Komisi --}}
                        <div class="form-group">
                            <label for="commission_percent">
                                <i class="fas fa-percentage form-label-icon"></i>
                                <span>{{ __('platform.platform_commission_percent') }}</span>
                            </label>
                            <div class="input-group">
                                <input type="number" step="0.1" min="0" max="100" id="commission_percent" name="commission_percent"
                                       value="{{ old('commission_percent', $commissionPercent) }}" class="form-control has-suffix" required>
                                <span class="input-suffix">%</span>
                            </div>
                            <div class="form-hint">
                                <i class="fas fa-info-circle"></i>
                                <span>{{ __('platform.platform_commission_hint') }}</span>
                            </div>
                        </div>

                        {{-- Set Batas Minimum Withdraw --}}
                        <div class="form-group">
                            <label for="min_withdraw">
                                <i class="fas fa-wallet form-label-icon"></i>
                                <span>{{ __('platform.min_withdraw_amount') }}</span>
                            </label>
                            <div class="input-group">
                                <span class="input-prefix">Rp</span>
                                <input type="number" step="1000" min="0" id="min_withdraw" name="min_withdraw"
                                       value="{{ old('min_withdraw', $minWithdrawAmount) }}" class="form-control has-prefix" required>
                            </div>
                            <div class="form-hint">
                                <i class="fas fa-info-circle"></i>
                                <span>{{ __('platform.min_withdraw_hint') }}</span>
                            </div>
                        </div>

                        <button type="button" class="btn-save-settings" onclick="openPasswordConfirmationModal()">
                            <i class="fas fa-save"></i> {{ __('platform.save_settings') }}
                        </button>
                    </form>
                </div>

                {{-- Card 2: Broadcast Pengumuman --}}
                <div class="setting-card">
                    <div class="setting-card-header">
                        <div class="setting-card-icon broadcast-icon">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <div class="setting-card-title-wrap">
                            <h2>{{ __('platform.broadcast_seller_announcement') }}</h2>
                            <p>{{ __('platform.broadcast_seller_desc') }}</p>
                        </div>
                    </div>

                    <form action="{{ route('platform-admin.settings.broadcast.store') }}" method="POST">
                        @csrf

                        <div class="broadcast-form-grid">
                            <div class="form-group">
                                <label for="ann_title">
                                    <i class="fas fa-heading form-label-icon"></i>
                                    <span>{{ __('platform.announcement_title') }}</span>
                                </label>
                                <input type="text" id="ann_title" name="title" placeholder="{{ __('platform.announcement_title_placeholder') }}" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="ann_type">
                                    <i class="fas fa-tag form-label-icon"></i>
                                    <span>{{ __('platform.announcement_type') }}</span>
                                </label>
                                <div class="select-wrapper">
                                    <select id="ann_type" name="type" class="form-control filter-select" required>
                                        <option value="info">{{ __('platform.type_info') }}</option>
                                        <option value="warning">{{ __('platform.type_warning') }}</option>
                                        <option value="success">{{ __('platform.type_success') }}</option>
                                        <option value="danger">{{ __('platform.type_danger') }}</option>
                                    </select>
                                    <i class="fas fa-chevron-down select-chevron"></i>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="ann_message">
                                <i class="fas fa-comment-alt form-label-icon"></i>
                                <span>{{ __('platform.announcement_message') }}</span>
                            </label>
                            <textarea id="ann_message" name="message" rows="3" class="form-control" placeholder="{{ __('platform.announcement_message_placeholder') }}" required></textarea>
                        </div>

                        {{-- Opsi Kirim Email Massal --}}
                        <label class="email-broadcast-card">
                            <input type="checkbox" name="send_email" value="1" id="send_email_check">
                            <span class="custom-checkbox">
                                <i class="fas fa-check"></i>
                            </span>
                            <div class="email-broadcast-content">
                                <div class="email-broadcast-title">
                                    <i class="fas fa-envelope-open-text" style="color: #ed842c;"></i>
                                    <span>Kirim Notifikasi via Email ke Semua Seller</span>
                                </div>
                                <div class="email-broadcast-desc">
                                    Kirimkan salinan pengumuman resmi langsung ke inbox email seluruh seller aktif melalui SMTP Gmail.
                                </div>
                            </div>
                        </label>

                        <button type="submit" class="btn-send-broadcast">
                            <i class="fas fa-paper-plane"></i> {{ __('platform.send_broadcast') }}
                        </button>
                    </form>
                </div>

            </div>

            {{-- Card: Sakelar Darurat (Emergency Switches / Maintenance Mode Parsial) --}}
            <div class="setting-card emergency-card" style="margin-bottom: 24px;">
                <div class="setting-card-header emergency-card-header">
                    <div class="setting-card-icon emergency-icon">
                        <i class="fas fa-power-off"></i>
                    </div>
                    <div class="setting-card-title-wrap">
                        <div class="emergency-header-flex">
                            <div>
                                <div class="emergency-title-row">
                                    <h2>Sakelar Darurat (Emergency Kill-Switch)</h2>
                                    <span class="emergency-sub-tag"><i class="fas fa-bolt"></i> Pemeliharaan Parsial</span>
                                </div>
                                <p class="emergency-header-desc">
                                    Kendalikan fitur-fitur transaksi & pencairan dana secara terisolasi untuk perlindungan instan tanpa mematikan seluruh situs web.
                                </p>
                            </div>
                            <div class="emergency-status-wrapper">
                                @if($freezePayouts || $disableCheckout)
                                    <div class="emergency-status-badge status-danger" id="globalEmergencyBadge">
                                        <span class="pulse-dot"></span>
                                        <span>Mode Darurat Aktif</span>
                                    </div>
                                @else
                                    <div class="emergency-status-badge status-normal" id="globalEmergencyBadge">
                                        <span class="status-dot-green"></span>
                                        <span>Semua Layanan Normal</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <form id="emergencySettingsForm" action="{{ route('platform-admin.settings.emergency') }}" method="POST">
                    @csrf
                    <input type="hidden" name="admin_password" id="form_emergency_admin_password">

                    <div class="emergency-switches-grid">
                        {{-- Switch 1: Freeze Payouts --}}
                        <div class="emergency-item {{ $freezePayouts ? 'active-warning' : '' }}" id="card_freeze_payouts">
                            <div class="emergency-item-top">
                                <div class="emergency-item-icon icon-payout">
                                    <i class="fas fa-money-bill-transfer"></i>
                                </div>
                                <div class="emergency-item-info">
                                    <div class="emergency-item-title-wrap">
                                        <span class="emergency-item-title">Freeze Payouts</span>
                                        <span class="status-pill-switch {{ $freezePayouts ? 'pill-danger' : 'pill-success' }}" id="badge_freeze_status">
                                            {{ $freezePayouts ? 'DIBEKUKAN' : 'NORMAL' }}
                                        </span>
                                    </div>
                                    <p class="emergency-item-desc">
                                        Kunci pengajuan penarikan dana baru seller dan bekukan persetujuan pencairan sementara saat audit pembukuan atau kendala bank.
                                    </p>
                                </div>
                                <div class="switch-toggle-wrap">
                                    <label class="switch-toggle switch-payout" title="Aktifkan/Nonaktifkan Freeze Payouts">
                                        <input type="checkbox" name="freeze_payouts" id="toggle_freeze_payouts" value="1" {{ $freezePayouts ? 'checked' : '' }} onchange="handleEmergencyToggle(this)">
                                        <span class="switch-slider"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="emergency-custom-msg-box">
                                <label for="freeze_payouts_message" class="msg-box-label">
                                    <i class="fas fa-bullhorn" style="color: #f59e0b;"></i>
                                    <span>Pesan Peringatan untuk Seller</span>
                                </label>
                                <div class="msg-input-wrapper">
                                    <i class="fas fa-comment-dots msg-input-icon"></i>
                                    <input type="text" id="freeze_payouts_message" name="freeze_payouts_message" 
                                           value="{{ old('freeze_payouts_message', $freezePayoutsMessage) }}"
                                           placeholder="Contoh: Layanan penarikan dana sedang dibekukan sementara untuk audit sistem..." 
                                           class="msg-input-field" maxlength="255">
                                </div>
                                <div class="quick-chips">
                                    <span class="chip-label">Template:</span>
                                    <button type="button" class="quick-chip" onclick="applyMessageTemplate('freeze_payouts_message', 'Layanan penarikan dana sedang ditangguhkan sementara untuk audit pembukuan rutin.')">Audit Rutin</button>
                                    <button type="button" class="quick-chip" onclick="applyMessageTemplate('freeze_payouts_message', 'Layanan penarikan dana sedang dibekukan sementara karena gangguan jaringan perbankan nasional.')">Gangguan Bank</button>
                                </div>
                            </div>
                        </div>

                        {{-- Switch 2: Disable Checkout --}}
                        <div class="emergency-item {{ $disableCheckout ? 'active-danger' : '' }}" id="card_disable_checkout">
                            <div class="emergency-item-top">
                                <div class="emergency-item-icon icon-checkout">
                                    <i class="fas fa-cart-shopping"></i>
                                </div>
                                <div class="emergency-item-info">
                                    <div class="emergency-item-title-wrap">
                                        <span class="emergency-item-title">Disable Checkout</span>
                                        <span class="status-pill-switch {{ $disableCheckout ? 'pill-danger' : 'pill-success' }}" id="badge_checkout_status">
                                            {{ $disableCheckout ? 'DINONAKTIFKAN' : 'NORMAL' }}
                                        </span>
                                    </div>
                                    <p class="emergency-item-desc">
                                        Tutup sementara proses transaksi pembelian produk digital publik saat pemeliharaan payment gateway atau sinkronisasi katalog.
                                    </p>
                                </div>
                                <div class="switch-toggle-wrap">
                                    <label class="switch-toggle switch-checkout" title="Aktifkan/Nonaktifkan Checkout">
                                        <input type="checkbox" name="disable_checkout" id="toggle_disable_checkout" value="1" {{ $disableCheckout ? 'checked' : '' }} onchange="handleEmergencyToggle(this)">
                                        <span class="switch-slider"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="emergency-custom-msg-box">
                                <label for="disable_checkout_message" class="msg-box-label">
                                    <i class="fas fa-bullhorn" style="color: #ef4444;"></i>
                                    <span>Pesan Peringatan untuk Pembeli</span>
                                </label>
                                <div class="msg-input-wrapper">
                                    <i class="fas fa-comment-dots msg-input-icon"></i>
                                    <input type="text" id="disable_checkout_message" name="disable_checkout_message" 
                                           value="{{ old('disable_checkout_message', $disableCheckoutMessage) }}"
                                           placeholder="Contoh: Layanan checkout sedang dinonaktifkan sementara untuk pemeliharaan sistem..." 
                                           class="msg-input-field" maxlength="255">
                                </div>
                                <div class="quick-chips">
                                    <span class="chip-label">Template:</span>
                                    <button type="button" class="quick-chip" onclick="applyMessageTemplate('disable_checkout_message', 'Layanan checkout sedang dinonaktifkan sementara untuk pemeliharaan sistem berkala.')">Maintenance Rutin</button>
                                    <button type="button" class="quick-chip" onclick="applyMessageTemplate('disable_checkout_message', 'Layanan checkout sedang dinonaktifkan sementara karena peningkatan sistem payment gateway.')">Upgrade Gateway</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Footer Action Toolbar --}}
                    <div class="emergency-footer-bar">
                        <div class="emergency-footer-info">
                            <i class="fas fa-lock emergency-security-icon"></i>
                            <span>Perubahan sakelar darurat diproteksi kata sandi admin dan terekam dalam Log Audit.</span>
                        </div>
                        <button type="button" class="btn-save-emergency-pro" onclick="openEmergencyPasswordModal()">
                            <i class="fas fa-lock"></i>
                            <span>Simpan Status Sakelar</span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Card 3: Tabel Riwayat Broadcast (Clean Full Width Card) --}}
            <div class="setting-card settings-table-card" style="margin-top: 24px;">
                <div class="settings-table-header">
                    <div class="settings-table-title">
                        <h3><i class="fas fa-history" style="color: #64748b; margin-right: 6px;"></i> Riwayat Siaran Pengumuman</h3>
                        <span class="settings-table-badge">{{ $announcements->count() }} Total</span>
                    </div>
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 38%;"><i class="fas fa-bullhorn"></i> {{ __('platform.announcement') }}</th>
                                <th style="width: 14%;"><i class="fas fa-tag"></i> {{ __('platform.announcement_type') }}</th>
                                <th style="width: 16%;"><i class="fas fa-paper-plane"></i> Pengiriman</th>
                                <th style="width: 16%;"><i class="fas fa-calendar-alt"></i> {{ __('platform.time') }}</th>
                                <th style="width: 10%;"><i class="fas fa-toggle-on"></i> {{ __('platform.status') }}</th>
                                <th style="width: 6%; text-align: center;"><i class="fas fa-trash-alt"></i> {{ __('platform.delete') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($announcements as $ann)
                            <tr>
                                <td>
                                    <div class="ann-title">{{ $ann->title }}</div>
                                    <div class="ann-msg">{{ Str::limit($ann->message, 85) }}</div>
                                </td>
                                <td>
                                    <span class="badge-type type-{{ $ann->type }}">
                                        {{ $ann->type }}
                                    </span>
                                </td>
                                <td>
                                    @if($ann->send_email)
                                        <span style="display: inline-flex; align-items: center; gap: 4px; background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; padding: 4px 8px; border-radius: 6px; font-size: 11px; font-weight: 700;">
                                            <i class="fas fa-paper-plane"></i> Email ({{ $ann->emails_sent_count }})
                                        </span>
                                    @else
                                        <span style="display: inline-flex; align-items: center; gap: 4px; background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; padding: 4px 8px; border-radius: 6px; font-size: 11px; font-weight: 600;">
                                            <i class="fas fa-desktop"></i> Banner
                                        </span>
                                    @endif
                                </td>
                                <td style="font-size: 12px; color: #64748b; white-space: nowrap;">
                                    {{ $ann->created_at->format('d M Y, H:i') }}
                                </td>
                                <td>
                                    <form action="{{ route('platform-admin.settings.broadcast.toggle', $ann->id) }}" method="POST" style="margin: 0;">
                                        @csrf
                                        <button type="submit" class="btn-toggle-status {{ $ann->is_active ? 'btn-status-active' : 'btn-status-inactive' }}" title="{{ __('platform.toggle_status_title') }}">
                                            <i class="fas fa-{{ $ann->is_active ? 'check-circle' : 'times-circle' }}"></i>
                                            {{ $ann->is_active ? __('platform.active') : __('platform.inactive') }}
                                        </button>
                                    </form>
                                </td>
                                <td style="text-align: center;">
                                    <form action="{{ route('platform-admin.settings.broadcast.delete', $ann->id) }}" method="POST" style="margin: 0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn-del-ann" title="{{ __('platform.delete') }}" onclick="confirmDeleteAnnouncement(this.form, '{{ addslashes($ann->title) }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <i class="fas fa-bullhorn" style="font-size: 26px; color: #cbd5e1; margin-bottom: 8px; display: block;"></i>
                                        {{ __('platform.no_announcements_yet') }}
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Password Admin untuk Pengaturan Finansial -->
    <div id="financialPasswordModal" class="modal" onclick="if(event.target === this) closePasswordConfirmationModal()">
        <div class="modal-container">
            <div class="modal-header">
                <h3><i class="fas fa-shield-alt" style="color: #ED842C;"></i> {{ __('platform.confirm_admin_password_title') }}</h3>
                <button type="button" class="modal-close" onclick="closePasswordConfirmationModal()">&times;</button>
            </div>
            <div class="modal-body">
                <p style="font-size: 13px; color: #64748b; line-height: 1.5; margin-bottom: 16px;">
                    {{ __('platform.confirm_admin_password_desc') }}
                </p>

                <div class="modal-summary-box">
                    <div style="margin-bottom: 6px; color: #475569;">
                        <strong>{{ __('platform.platform_commission_percent') }}:</strong> <span id="summary_commission" style="font-weight: 800; color: #ED842C;"></span>
                    </div>
                    <div style="color: #475569;">
                        <strong>{{ __('platform.min_withdraw_amount') }}:</strong> <span id="summary_min_withdraw" style="font-weight: 800; color: #16a34a;"></span>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label for="modal_admin_password" style="font-size: 12.5px; font-weight: 700; color: #1e293b; margin-bottom: 8px; display: flex; align-items: center; gap: 6px;">
                        <i class="fas fa-key" style="color: #ED842C;"></i>
                        <span>{{ __('platform.admin_password_label') }}</span>
                    </label>
                    <div style="position: relative;">
                        <input type="password" id="modal_admin_password" class="form-control"
                               placeholder="{{ __('platform.admin_password_placeholder') }}"
                               style="width: 100%; padding-right: 44px;"
                               onkeydown="if(event.key === 'Enter'){ event.preventDefault(); submitFinancialSettings(); }">
                        <button type="button" onclick="togglePasswordVisibility()" class="btn-toggle-eye">
                            <i class="fas fa-eye" id="togglePasswordIcon"></i>
                        </button>
                    </div>
                    <div id="passwordErrorMsg" style="display: none; color: #dc2626; font-size: 12px; font-weight: 600; margin-top: 6px;"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-modal-cancel" onclick="closePasswordConfirmationModal()">{{ __('platform.cancel') }}</button>
                <button type="button" class="btn-modal-submit-primary" onclick="submitFinancialSettings()">
                    <i class="fas fa-lock"></i> {{ __('platform.confirm_and_save') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Password Admin untuk Sakelar Darurat -->
    <div id="emergencyPasswordModal" class="modal" onclick="if(event.target === this) closeEmergencyPasswordModal()">
        <div class="modal-container">
            <div class="modal-header">
                <h3><i class="fas fa-power-off" style="color: #ea580c;"></i> Konfirmasi Kata Sandi Admin</h3>
                <button type="button" class="modal-close" onclick="closeEmergencyPasswordModal()">&times;</button>
            </div>
            <div class="modal-body">
                <p style="font-size: 13px; color: #64748b; line-height: 1.5; margin-bottom: 16px;">
                    Anda akan mengubah konfigurasi <strong>Sakelar Darurat (Maintenance Mode Parsial)</strong> platform. Masukkan kata sandi admin Anda untuk memverifikasi otorisasi keamanan.
                </p>

                <div class="modal-summary-box" style="background: #fff7ed; border-color: #fed7aa;">
                    <div style="margin-bottom: 8px; color: #1e293b; display: flex; align-items: center; justify-content: space-between;">
                        <span><strong>Freeze Payouts:</strong></span>
                        <span id="summary_freeze_payouts" style="font-weight: 800;"></span>
                    </div>
                    <div style="color: #1e293b; display: flex; align-items: center; justify-content: space-between;">
                        <span><strong>Disable Checkout:</strong></span>
                        <span id="summary_disable_checkout" style="font-weight: 800;"></span>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label for="modal_emergency_admin_password" style="font-size: 12.5px; font-weight: 700; color: #1e293b; margin-bottom: 8px; display: flex; align-items: center; gap: 6px;">
                        <i class="fas fa-key" style="color: #ea580c;"></i>
                        <span>Kata Sandi Admin Platform</span>
                    </label>
                    <div style="position: relative;">
                        <input type="password" id="modal_emergency_admin_password" class="form-control"
                               placeholder="Masukkan kata sandi admin Anda..."
                               style="width: 100%; padding-right: 44px;"
                               onkeydown="if(event.key === 'Enter'){ event.preventDefault(); submitEmergencySettings(); }">
                        <button type="button" onclick="toggleEmergencyPasswordVisibility()" class="btn-toggle-eye">
                            <i class="fas fa-eye" id="toggleEmergencyPasswordIcon"></i>
                        </button>
                    </div>
                    <div id="emergencyPasswordErrorMsg" style="display: none; color: #dc2626; font-size: 12px; font-weight: 600; margin-top: 6px;"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-modal-cancel" onclick="closeEmergencyPasswordModal()">{{ __('platform.cancel') }}</button>
                <button type="button" class="btn-modal-submit-primary" style="background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%);" onclick="submitEmergencySettings()">
                    <i class="fas fa-lock"></i> Konfirmasi & Terapkan
                </button>
            </div>
        </div>
    </div>

    <script>
        window.PlatformSettingsConfig = {
            deleteText: '{{ __('platform.delete') }}'
        };
    </script>
    @vite(['resources/js/app.js'])
    <script src="{{ asset('js/platform/notifications.js') }}"></script>
    <script src="{{ asset('js/platform/settings.js') }}?v={{ file_exists(public_path('js/platform/settings.js')) ? filemtime(public_path('js/platform/settings.js')) : time() }}"></script>
</body>
</html>
