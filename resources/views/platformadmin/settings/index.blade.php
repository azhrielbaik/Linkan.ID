<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('platform.platform_settings') }} — Platform Admin</title>
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

        /* ---- Alerts ---- */
        .alert-box {
            padding: 14px 20px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .alert-error { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }

        /* ---- Layout Grid ---- */
        .settings-grid {
            display: grid;
            grid-template-columns: 380px 1fr;
            gap: 24px;
            align-items: start;
        }

        /* ---- Card Styles ---- */
        .setting-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 26px;
            box-shadow: 0 1px 6px rgba(0, 0, 0, 0.04);
            border: 1px solid #f0f2f5;
        }

        .setting-card-header {
            margin-bottom: 20px;
            padding-bottom: 14px;
            border-bottom: 1px solid #f1f5f9;
        }

        .setting-card-header h2 {
            font-size: 16px;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 4px;
        }

        .setting-card-header p {
            font-size: 12px;
            color: #94a3b8;
        }

        /* Form Controls */
        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            color: #334155;
            margin-bottom: 6px;
        }

        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 14px;
            font-family: inherit;
            background: #f8fafc;
            outline: none;
            transition: all 0.2s ease;
            color: #1e293b;
        }

        .form-control:focus {
            border-color: #5A5BF1;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(90, 91, 241, 0.1);
        }

        .input-group {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-suffix {
            position: absolute;
            right: 14px;
            font-weight: 700;
            color: #64748b;
            font-size: 14px;
        }

        .input-prefix {
            position: absolute;
            left: 14px;
            font-weight: 700;
            color: #64748b;
            font-size: 14px;
        }

        .has-prefix { padding-left: 42px; }
        .has-suffix { padding-right: 36px; }

        .form-hint {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 4px;
            line-height: 1.4;
        }

        .btn-save-settings {
            width: 100%;
            background: #5A5BF1;
            color: #ffffff;
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
            transition: background 0.2s, box-shadow 0.2s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-save-settings:hover {
            background: #4748d0;
            box-shadow: 0 4px 14px rgba(90, 91, 241, 0.25);
        }

        /* Broadcast Form */
        .broadcast-form-grid {
            display: grid;
            grid-template-columns: 1fr 180px;
            gap: 14px;
        }

        /* Type Badges */
        .badge-type {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 700;
            text-transform: capitalize;
        }

        .type-info { background: #eff6ff; color: #2563eb; }
        .type-warning { background: #fefce8; color: #ca8a04; }
        .type-success { background: #f0fdf4; color: #16a34a; }
        .type-danger { background: #fef2f2; color: #dc2626; }

        /* Announcement Table */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        thead th {
            background: #f8f9ff;
            padding: 12px 16px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            border-bottom: 1px solid #eef0fe;
            white-space: nowrap;
        }

        tbody tr {
            border-bottom: 1px solid #f1f5f9;
            transition: background 0.15s ease;
        }

        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: #f8faff; }

        tbody td {
            padding: 14px 16px;
            font-size: 13px;
            vertical-align: middle;
            color: #334155;
        }

        .ann-title {
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 2px;
        }

        .ann-msg {
            font-size: 12px;
            color: #64748b;
            line-height: 1.4;
            max-width: 320px;
        }

        .btn-toggle-status {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: all 0.2s;
            font-family: inherit;
        }

        .btn-status-active { background: #dcfce7; color: #15803d; }
        .btn-status-inactive { background: #fee2e2; color: #b91c1c; }

        .btn-del-ann {
            background: none;
            border: none;
            color: #ef4444;
            cursor: pointer;
            font-size: 14px;
            padding: 6px;
            border-radius: 6px;
            transition: background 0.2s;
        }

        .btn-del-ann:hover { background: #fee2e2; }

        .empty-state {
            text-align: center;
            padding: 30px;
            color: #94a3b8;
            font-size: 13px;
            font-weight: 600;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(15, 23, 42, 0.5);
            backdrop-filter: blur(4px);
            z-index: 1050;
            align-items: center;
            justify-content: center;
        }
        .modal.show { display: flex; }
        .modal-container {
            background: #ffffff;
            width: 520px;
            max-width: 92%;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            overflow: hidden;
            animation: modalPop 0.2s ease-out;
        }
        @keyframes modalPop {
            from { opacity: 0; transform: scale(0.95) translateY(10px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
        .modal-header {
            padding: 20px 24px;
            background: #f8f9ff;
            border-bottom: 1px solid #eef0fe;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .modal-header h3 {
            font-size: 16px;
            font-weight: 800;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .modal-close {
            background: none; border: none; font-size: 20px; color: #94a3b8; cursor: pointer;
        }
        .modal-close:hover { color: #ef4444; }
        .modal-body { padding: 24px; }
        .modal-footer {
            padding: 14px 24px; background: #f8fafc;
            border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end; gap: 10px;
        }
        .btn-modal-cancel {
            background: #e2e8f0; color: #475569; padding: 10px 18px; border-radius: 10px;
            font-weight: 700; font-size: 13px; border: none; cursor: pointer; font-family: inherit;
        }
        .btn-modal-submit-primary {
            background: #5A5BF1; color: #ffffff; padding: 10px 22px; border-radius: 10px;
            font-weight: 700; font-size: 13px; border: none; cursor: pointer; font-family: inherit;
            display: inline-flex; align-items: center; gap: 6px; transition: background 0.2s;
        }
        .btn-modal-submit-primary:hover { background: #4748d0; }

        /* Responsive */
        @media (max-width: 1100px) {
            .settings-grid { grid-template-columns: 1fr; }
        }

        @media (max-width: 900px) {
            .platform-main { margin-left: 0 !important; }
            .hamburger-btn { display: block; }
            .platform-header { padding: 14px 20px; }
            .content-wrapper { padding: 20px 16px; }
            .broadcast-form-grid { grid-template-columns: 1fr; }
        }
    </style>
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
                <div class="header-user">
                    <div class="header-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                    <span>{{ Auth::user()->name }}</span>
                </div>
            </div>
        </div>

        <div class="content-wrapper">

            {{-- Alerts --}}
            @if(session('success'))
                <div class="alert-box alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert-box alert-error">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert-box alert-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div>
                        @foreach($errors->all() as $err)
                            <div>{{ $err }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="settings-grid">
                
                {{-- Card 1: Pengaturan Komisi & Withdraw --}}
                <div class="setting-card">
                    <div class="setting-card-header">
                        <h2>{{ __('platform.commission_and_withdrawal') }}</h2>
                        <p>{{ __('platform.commission_and_withdrawal_desc') }}</p>
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
                        <h2>{{ __('platform.broadcast_seller_announcement') }}</h2>
                        <p>{{ __('platform.broadcast_seller_desc') }}</p>
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
                                <select id="ann_type" name="type" class="form-control" required>
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

                        <button type="submit" class="btn-save-settings" style="width: auto; padding: 10px 22px;">
                            <i class="fas fa-bullhorn"></i> {{ __('platform.send_broadcast') }}
                        </button>
                    </form>

                    {{-- Tabel Riwayat Broadcast --}}
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>{{ __('platform.announcement') }}</th>
                                    <th>{{ __('platform.announcement_type') }}</th>
                                    <th>{{ __('platform.time') }}</th>
                                    <th>{{ __('platform.status') }}</th>
                                    <th style="text-align: center;">{{ __('platform.delete') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($announcements as $ann)
                                <tr>
                                    <td>
                                        <div class="ann-title">{{ $ann->title }}</div>
                                        <div class="ann-msg">{{ Str::limit($ann->message, 80) }}</div>
                                    </td>
                                    <td>
                                        <span class="badge-type type-{{ $ann->type }}">
                                            {{ $ann->type }}
                                        </span>
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
                                    <td colspan="5">
                                        <div class="empty-state">
                                            <i class="fas fa-bullhorn" style="font-size: 24px; color: #cbd5e1; margin-bottom: 6px; display: block;"></i>
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
    </div>

    <!-- Modal Konfirmasi Password Admin untuk Pengaturan Finansial -->
    <div id="adminPasswordModal" class="modal">
        <div class="modal-container">
            <div class="modal-header">
                <h3><i class="fas fa-shield-alt" style="color: #5A5BF1;"></i> {{ __('platform.confirm_admin_password_title') }}</h3>
                <button type="button" class="modal-close" onclick="closePasswordConfirmationModal()">&times;</button>
            </div>
            <div class="modal-body">
                <p style="font-size: 13px; color: #64748b; line-height: 1.5; margin-bottom: 16px;">
                    {{ __('platform.confirm_admin_password_desc') }}
                </p>

                <div style="background: #f8f9ff; border: 1px solid #eef0fe; border-radius: 12px; padding: 12px 16px; margin-bottom: 18px; font-size: 13px;">
                    <div style="margin-bottom: 6px; color: #475569;">
                        <strong>{{ __('platform.platform_commission_percent') }}:</strong> <span id="summary_commission" style="font-weight: 800; color: #5A5BF1;"></span>
                    </div>
                    <div style="color: #475569;">
                        <strong>{{ __('platform.min_withdraw_amount') }}:</strong> <span id="summary_min_withdraw" style="font-weight: 800; color: #16a34a;"></span>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label for="modal_admin_password" style="font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 6px; display: block;">
                        <i class="fas fa-key" style="color: #5A5BF1;"></i> {{ __('platform.admin_password_label') }}
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
        function openPasswordConfirmationModal() {
            const commInput = document.getElementById('commission_percent');
            const minWInput = document.getElementById('min_withdraw');

            if (!commInput.checkValidity() || !minWInput.checkValidity()) {
                document.getElementById('financialSettingsForm').reportValidity();
                return;
            }

            document.getElementById('summary_commission').textContent = commInput.value + '%';
            document.getElementById('summary_min_withdraw').textContent = 'Rp ' + Number(minWInput.value).toLocaleString('id-ID');
            document.getElementById('modal_admin_password').value = '';
            document.getElementById('passwordErrorMsg').style.display = 'none';

            document.getElementById('adminPasswordModal').classList.add('show');
            setTimeout(() => document.getElementById('modal_admin_password').focus(), 150);
        }

        function closePasswordConfirmationModal() {
            document.getElementById('adminPasswordModal').classList.remove('show');
        }

        function togglePasswordVisibility() {
            const passInput = document.getElementById('modal_admin_password');
            const passIcon = document.getElementById('togglePasswordIcon');
            if (passInput.type === 'password') {
                passInput.type = 'text';
                passIcon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passInput.type = 'password';
                passIcon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        function submitFinancialSettings() {
            const pass = document.getElementById('modal_admin_password').value;
            const errBox = document.getElementById('passwordErrorMsg');

            if (!pass.trim()) {
                errBox.textContent = 'Silakan masukkan password admin terlebih dahulu.';
                errBox.style.display = 'block';
                document.getElementById('modal_admin_password').focus();
                return;
            }

            document.getElementById('form_admin_password').value = pass;
            document.getElementById('financialSettingsForm').submit();
        }

        function confirmDeleteAnnouncement(form, title) {
            showConfirmModal({
                title: 'Hapus Pengumuman Siaran?',
                text: `Pengumuman "${title}" akan dihapus secara permanen dari beranda seluruh seller.`,
                icon: 'warning',
                confirmText: '<i class="fas fa-trash"></i> {{ __('platform.delete') }}',
                confirmDanger: true,
                onConfirm: () => {
                    form.submit();
                }
            });
        }
    </script>
</body>
</html>
