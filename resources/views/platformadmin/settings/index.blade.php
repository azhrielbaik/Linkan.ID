<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('platform.platform_settings') }} — Platform Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/platform/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/platform/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/platform/notifications.css') }}">
    <link rel="stylesheet" href="{{ asset('css/platform/settings.css') }}?v={{ time() }}">
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
                            <label for="commission_percent">{{ __('platform.platform_commission_percent') }}</label>
                            <div class="input-group">
                                <input type="number" step="0.1" min="0" max="100" id="commission_percent" name="commission_percent"
                                       value="{{ old('commission_percent', $commissionPercent) }}" class="form-control has-suffix" required>
                                <span class="input-suffix">%</span>
                            </div>
                            <div class="form-hint">{{ __('platform.platform_commission_hint') }}</div>
                        </div>

                        {{-- Set Batas Minimum Withdraw --}}
                        <div class="form-group">
                            <label for="min_withdraw">{{ __('platform.min_withdraw_amount') }}</label>
                            <div class="input-group">
                                <span class="input-prefix">Rp</span>
                                <input type="number" step="1000" min="0" id="min_withdraw" name="min_withdraw"
                                       value="{{ old('min_withdraw', $minWithdrawAmount) }}" class="form-control has-prefix" required>
                            </div>
                            <div class="form-hint">{{ __('platform.min_withdraw_hint') }}</div>
                        </div>

                        <button type="button" class="btn-save-settings" onclick="openPasswordConfirmationModal()">
                            <i class="fas fa-save"></i> {{ __('platform.save_settings') }}
                        </button>
                    </form>
                </div>

                {{-- Card 2: Broadcast Pengumuman --}}
                <div class="setting-card">
                    <div class="setting-card-header">
                        <div class="setting-card-icon" style="background: rgba(14, 165, 233, 0.1); color: #0284c7;">
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
                                <label for="ann_title">{{ __('platform.announcement_title') }}</label>
                                <input type="text" id="ann_title" name="title" placeholder="{{ __('platform.announcement_title_placeholder') }}" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="ann_type">{{ __('platform.announcement_type') }}</label>
                                <select id="ann_type" name="type" class="form-control filter-select" required>
                                    <option value="info">{{ __('platform.type_info') }}</option>
                                    <option value="warning">{{ __('platform.type_warning') }}</option>
                                    <option value="success">{{ __('platform.type_success') }}</option>
                                    <option value="danger">{{ __('platform.type_danger') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="ann_message">{{ __('platform.announcement_message') }}</label>
                            <textarea id="ann_message" name="message" rows="3" class="form-control" placeholder="{{ __('platform.announcement_message_placeholder') }}" required></textarea>
                        </div>

                        {{-- Opsi Kirim Email Massal --}}
                        <label class="email-broadcast-card">
                            <input type="checkbox" name="send_email" value="1">
                            <div>
                                <div class="email-broadcast-title">
                                    <i class="fas fa-envelope" style="color: #ed842c;"></i> Kirim Notifikasi via Email ke Semua Seller
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

                <div style="background: #fff8f2; border: 1px solid rgba(237, 132, 44, 0.2); border-radius: 12px; padding: 12px 16px; margin-bottom: 18px; font-size: 13px;">
                    <div style="margin-bottom: 6px; color: #475569;">
                        <strong>{{ __('platform.platform_commission_percent') }}:</strong> <span id="summary_commission" style="font-weight: 800; color: #ED842C;"></span>
                    </div>
                    <div style="color: #475569;">
                        <strong>{{ __('platform.min_withdraw_amount') }}:</strong> <span id="summary_min_withdraw" style="font-weight: 800; color: #16a34a;"></span>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label for="modal_admin_password" style="font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 6px; display: block;">
                        <i class="fas fa-key" style="color: #ED842C;"></i> {{ __('platform.admin_password_label') }}
                    </label>
                    <div style="position: relative;">
                        <input type="password" id="modal_admin_password" class="form-control"
                               placeholder="{{ __('platform.admin_password_placeholder') }}"
                               style="width: 100%; padding: 10px 40px 10px 14px; box-sizing: border-box;"
                               onkeydown="if(event.key === 'Enter'){ event.preventDefault(); submitFinancialSettings(); }">
                        <button type="button" onclick="togglePasswordVisibility()" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #94a3b8; cursor: pointer;">
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

    <script>
        window.PlatformSettingsConfig = {
            deleteText: '{{ __('platform.delete') }}'
        };
    </script>
    @vite(['resources/js/app.js'])
    <script src="{{ asset('js/platform/notifications.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/platform/settings.js') }}?v={{ time() }}"></script>
</body>
</html>
