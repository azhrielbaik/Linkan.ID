{{-- Reusable Platform Admin Profile & Quick Account Dropdown --}}
<div class="header-user-wrapper" id="platformProfileDropdownWrapper">
    <div class="top-profile" id="platformProfileBtn" onclick="togglePlatformThemeDropdown(event)" aria-haspopup="true" aria-expanded="false">
        <div class="top-avatar" id="headerUserAvatar">
            @if(!empty(Auth::user()->avatar))
                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}" class="avatar-fit-img" id="headerUserAvatarImg">
            @else
                <span class="avatar-initials" id="headerUserAvatarInitials">{{ strtoupper(substr(Auth::user()->name ?? 'AD', 0, 2)) }}</span>
            @endif
        </div>
        <div class="top-user-info">
            <span class="top-user-name" id="topUserName">{{ Auth::user()->name ?? 'Admin' }}</span>
            <i class="fas fa-caret-down top-profile-arrow"></i>
        </div>
    </div>

    {{-- Dropdown Card (Compact & Sleek) --}}
    <div class="platform-theme-dropdown" id="platformThemeDropdown" onclick="event.stopPropagation()">
        {{-- Profile Info Header --}}
        <div class="ptd-header">
            <div class="ptd-avatar" id="dropdownUserAvatar">
                @if(!empty(Auth::user()->avatar))
                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}" class="avatar-fit-img" id="dropdownUserAvatarImg">
                @else
                    <span class="avatar-initials" id="dropdownUserAvatarInitials">{{ strtoupper(substr(Auth::user()->name ?? 'AD', 0, 2)) }}</span>
                @endif
            </div>
            <div class="ptd-user-info">
                <div class="ptd-name" id="dropdownUserName">{{ Auth::user()->name ?? 'Admin Platform' }}</div>
                <div class="ptd-email">{{ Auth::user()->email ?? 'admin@linkan.id' }}</div>
                <span class="ptd-role-pill"><i class="fas fa-shield-alt"></i> Platform Admin</span>
            </div>
        </div>

        {{-- Dropdown Menu Items --}}
        <div class="ptd-menu-list">
            <button type="button" class="ptd-menu-item" onclick="openAdminProfileModal()">
                <i class="fas fa-user-gear ptd-item-icon"></i>
                <span class="ptd-item-text">Profil & Avatar</span>
            </button>

            <button type="button" class="ptd-menu-item" onclick="openAdminSessionsModal()">
                <i class="fas fa-desktop ptd-item-icon"></i>
                <span class="ptd-item-text">Sesi Login Aktif</span>
            </button>

            <button type="button" class="ptd-menu-item" onclick="openAdminPasswordModal()">
                <i class="fas fa-key ptd-item-icon"></i>
                <span class="ptd-item-text">Ubah Kata Sandi</span>
            </button>

            <a href="{{ url('/') }}" target="_blank" class="ptd-menu-item">
                <i class="fas fa-globe ptd-item-icon"></i>
                <span class="ptd-item-text">Lihat Web Publik</span>
                <i class="fas fa-arrow-up-right-from-square ptd-external-icon"></i>
            </a>
        </div>

        {{-- Security Status Badge --}}
        <div class="ptd-session-box">
            <span class="ptd-dot"></span>
            <span>Sesi Aktif & Terlindungi</span>
        </div>

        {{-- Footer Logout --}}
        <div class="ptd-footer">
            <button type="button" class="ptd-logout-btn" onclick="confirmPlatformLogout()">
                <i class="fas fa-sign-out-alt"></i> {{ __('sidebar.logout') }}
            </button>
        </div>
    </div>
</div>

{{-- Modal 1: Profil & Avatar Admin --}}
<div class="modal admin-profile-modal" id="adminProfileModal" onclick="if(event.target === this) closeAdminProfileModal()">
    <div class="admin-profile-modal-content">
        <div class="admin-pwd-header">
            <div class="admin-pwd-header-title">
                <i class="fas fa-user-gear"></i>
                <h3>Pengaturan Profil & Avatar</h3>
            </div>
            <button type="button" class="btn-close-pwd-modal" onclick="closeAdminProfileModal()" aria-label="Tutup">&times;</button>
        </div>

        <form id="adminProfileForm" onsubmit="submitAdminProfile(event)">
            <div class="admin-pwd-body">
                <div class="admin-pwd-alert" id="adminProfileAlert" style="display: none;"></div>

                {{-- Avatar Upload & Preview Section --}}
                <div class="profile-avatar-section">
                    <div class="avatar-preview-box" id="profileAvatarPreview">
                        @if(!empty(Auth::user()->avatar))
                            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar" class="avatar-fit-img" id="previewAvatarImg">
                        @else
                            <div class="avatar-preview-initials" id="previewAvatarInitials">
                                {{ strtoupper(substr(Auth::user()->name ?? 'AD', 0, 2)) }}
                            </div>
                        @endif
                    </div>
                    
                    <input type="file" id="admin_avatar_input" name="avatar" accept="image/png, image/jpeg, image/jpg, image/webp" style="display: none;" onchange="previewSelectedAvatar(event)">
                    
                    <div class="avatar-actions-row">
                        <button type="button" class="btn-avatar-action btn-avatar-choose" onclick="document.getElementById('admin_avatar_input').click()">
                            <i class="fas fa-camera"></i> Ganti Foto
                        </button>
                        <button type="button" class="btn-avatar-action btn-avatar-delete" id="btnDeleteAvatar" onclick="deleteAdminAvatar()" style="{{ !empty(Auth::user()->avatar) ? '' : 'display: none;' }}">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                    <p class="avatar-upload-hint">Format PNG, JPG, WEBP. Maks. 2MB</p>
                </div>

                {{-- Name Field --}}
                <div class="admin-pwd-field">
                    <label for="admin_profile_name">Nama Lengkap Admin</label>
                    <div class="admin-pwd-input-wrap">
                        <input type="text" id="admin_profile_name" name="name" value="{{ Auth::user()->name ?? 'Admin Platform' }}" required maxlength="100" placeholder="Nama Admin" autocomplete="name">
                    </div>
                </div>

                {{-- Email Field (Protected) --}}
                <div class="admin-pwd-field">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 5px;">
                        <label for="admin_profile_email" style="margin-bottom: 0;">Email Akun</label>
                        <span class="field-badge-readonly"><i class="fas fa-lock"></i> Utama & Terproteksi</span>
                    </div>
                    <div class="admin-pwd-input-wrap">
                        <input type="email" id="admin_profile_email" value="{{ Auth::user()->email ?? 'admin@linkan.id' }}" readonly style="background: #f1f5f9; cursor: not-allowed; color: #64748b;">
                    </div>
                </div>

                {{-- Role Badge --}}
                <div class="admin-pwd-field">
                    <label>Tingkat Otoritas Sistem</label>
                    <div class="field-badge-role">
                        <i class="fas fa-shield-halved"></i>
                        <span>Platform Administrator (Super Admin - Akses Penuh Sistem)</span>
                    </div>
                </div>
            </div>

            <div class="admin-pwd-footer">
                <button type="button" class="btn-pwd-cancel" onclick="closeAdminProfileModal()">Batal</button>
                <button type="submit" class="btn-pwd-submit" id="btnSubmitAdminProfile">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal 2: Manajemen Sesi Login Aktif --}}
<div class="modal admin-sessions-modal" id="adminSessionsModal" onclick="if(event.target === this) closeAdminSessionsModal()">
    <div class="admin-sessions-modal-content">
        <div class="admin-pwd-header">
            <div class="admin-pwd-header-title">
                <i class="fas fa-desktop"></i>
                <h3>Sesi Login Aktif</h3>
            </div>
            <button type="button" class="btn-close-pwd-modal" onclick="closeAdminSessionsModal()" aria-label="Tutup">&times;</button>
        </div>

        <div class="admin-pwd-body">
            <p class="sessions-modal-subtitle">
                Berikut adalah daftar perangkat dan peramban yang saat ini memiliki akses aktif ke akun admin Anda.
            </p>

            <div class="sessions-list-container" id="adminSessionsList">
                <div style="text-align: center; padding: 24px; color: #94a3b8; font-size: 12px;">
                    <i class="fas fa-spinner fa-spin" style="font-size: 18px; margin-bottom: 6px; color: #ed842c; display: block;"></i>
                    Memuat daftar sesi aktif...
                </div>
            </div>
        </div>

        <div class="admin-pwd-footer" style="justify-content: space-between;">
            <button type="button" class="btn-revoke-others" id="btnRevokeOthers" onclick="revokeOtherSessions()" style="display: none;">
                <i class="fas fa-sign-out-alt"></i> Keluar dari Perangkat Lain
            </button>
            <button type="button" class="btn-pwd-cancel" onclick="closeAdminSessionsModal()" style="margin-left: auto;">Tutup</button>
        </div>
    </div>
</div>

{{-- Modal 3: Ubah Password Admin --}}
<div class="modal admin-password-modal" id="adminPasswordModal" onclick="if(event.target === this) closeAdminPasswordModal()">
    <div class="admin-pwd-modal-content">
        <div class="admin-pwd-header">
            <div class="admin-pwd-header-title">
                <i class="fas fa-key"></i>
                <h3>Ubah Kata Sandi Admin</h3>
            </div>
            <button type="button" class="btn-close-pwd-modal" onclick="closeAdminPasswordModal()" aria-label="Tutup">&times;</button>
        </div>

        <form id="adminPasswordForm" onsubmit="submitAdminPassword(event)">
            <div class="admin-pwd-body">
                <div class="admin-pwd-alert" id="adminPwdAlert" style="display: none;"></div>

                <div class="admin-pwd-field">
                    <label for="admin_current_password">Kata Sandi Saat Ini</label>
                    <div class="admin-pwd-input-wrap">
                        <input type="password" id="admin_current_password" name="current_password" required placeholder="Masukkan kata sandi saat ini" autocomplete="current-password">
                        <button type="button" class="btn-toggle-eye" onclick="togglePasswordVisibility('admin_current_password', this)" tabindex="-1">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="admin-pwd-field">
                    <label for="admin_new_password">Kata Sandi Baru</label>
                    <div class="admin-pwd-input-wrap">
                        <input type="password" id="admin_new_password" name="new_password" required minlength="8" placeholder="Minimal 8 karakter" autocomplete="new-password">
                        <button type="button" class="btn-toggle-eye" onclick="togglePasswordVisibility('admin_new_password', this)" tabindex="-1">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="admin-pwd-field">
                    <label for="admin_new_password_confirmation">Konfirmasi Kata Sandi Baru</label>
                    <div class="admin-pwd-input-wrap">
                        <input type="password" id="admin_new_password_confirmation" name="new_password_confirmation" required minlength="8" placeholder="Ulangi kata sandi baru" autocomplete="new-password">
                        <button type="button" class="btn-toggle-eye" onclick="togglePasswordVisibility('admin_new_password_confirmation', this)" tabindex="-1">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="admin-pwd-footer">
                <button type="button" class="btn-pwd-cancel" onclick="closeAdminPasswordModal()">Batal</button>
                <button type="submit" class="btn-pwd-submit" id="btnSubmitAdminPwd">
                    <i class="fas fa-save"></i> Simpan Kata Sandi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    /* =========================================================
       HELPER: POPUP MODAL LINKAN (SWEETALERT2 STYLED)
       ========================================================= */
    function showPlatformSwal(options) {
        const iconType = options.icon || 'success';
        const isDanger = options.confirmDanger || (iconType === 'error' || iconType === 'warning');

        let iconHtml = options.iconHtml || '';
        if (!iconHtml) {
            if (iconType === 'success') {
                iconHtml = '<i class="fas fa-check"></i>';
            } else if (iconType === 'error' || iconType === 'warning') {
                iconHtml = '<i class="fas fa-exclamation-triangle"></i>';
            } else {
                iconHtml = '<i class="fas fa-info-circle"></i>';
            }
        }

        let confirmBtnClass = isDanger ? 'linkan-swal-confirm-danger-btn' : 'linkan-swal-confirm-btn';
        if (!options.showCancelButton) {
            confirmBtnClass += ' linkan-swal-single-btn';
        }

        if (typeof Swal !== 'undefined') {
            return Swal.fire({
                title: options.title || (iconType === 'success' ? 'Berhasil' : 'Pemberitahuan'),
                text: options.text || '',
                html: options.html || undefined,
                icon: iconType,
                iconHtml: iconHtml,
                showCancelButton: options.showCancelButton || false,
                confirmButtonText: options.confirmText || 'Oke, Mengerti',
                cancelButtonText: options.cancelText || 'Batal',
                timer: options.timer || undefined,
                timerProgressBar: Boolean(options.timer),
                customClass: {
                    popup: 'linkan-swal-popup',
                    title: 'linkan-swal-title',
                    htmlContainer: 'linkan-swal-html',
                    confirmButton: confirmBtnClass,
                    cancelButton: 'linkan-swal-cancel-btn',
                    actions: 'linkan-swal-actions'
                },
                buttonsStyling: false,
                reverseButtons: true,
                showClass: {
                    popup: 'linkan-swal-show'
                },
                hideClass: {
                    popup: 'linkan-swal-hide'
                },
                backdrop: 'rgba(15, 23, 42, 0.65)'
            }).then(options.onResult || (() => {}));
        } else if (typeof window.showToast === 'function') {
            window.showToast(options.text || options.title, iconType);
        } else {
            alert(options.text || options.title);
        }
    }

    /* =========================================================
       MODAL: PROFIL & AVATAR ADMIN
       ========================================================= */
    function openAdminProfileModal() {
        const dropdown = document.getElementById('platformThemeDropdown');
        if (dropdown) dropdown.classList.remove('show');
        const modal = document.getElementById('adminProfileModal');
        if (modal) {
            modal.classList.add('show');
            const alertBox = document.getElementById('adminProfileAlert');
            if (alertBox) {
                alertBox.style.display = 'none';
                alertBox.textContent = '';
            }
        }
    }

    function closeAdminProfileModal() {
        const modal = document.getElementById('adminProfileModal');
        if (modal) modal.classList.remove('show');
    }

    function previewSelectedAvatar(event) {
        const file = event.target.files?.[0];
        if (!file) return;

        if (file.size > 2 * 1024 * 1024) {
            showPlatformSwal({
                icon: 'warning',
                title: 'Ukuran File Terlalu Besar',
                text: 'Ukuran file foto maksimal adalah 2MB.',
                confirmDanger: true,
                confirmText: 'Mengerti'
            });
            event.target.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            const previewBox = document.getElementById('profileAvatarPreview');
            if (previewBox) {
                previewBox.innerHTML = `<img src="${e.target.result}" alt="Preview Avatar" class="avatar-fit-img" id="previewAvatarImg">`;
            }
            const btnDelete = document.getElementById('btnDeleteAvatar');
            if (btnDelete) btnDelete.style.display = 'inline-flex';
        };
        reader.readAsDataURL(file);
    }

    function submitAdminProfile(e) {
        e.preventDefault();
        const submitBtn = document.getElementById('btnSubmitAdminProfile');
        const alertBox = document.getElementById('adminProfileAlert');
        const nameInput = document.getElementById('admin_profile_name');
        const fileInput = document.getElementById('admin_avatar_input');

        const formData = new FormData();
        formData.append('name', nameInput.value.trim());
        if (fileInput.files.length > 0) {
            formData.append('avatar', fileInput.files[0]);
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
        alertBox.style.display = 'none';

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        fetch('{{ route("platform-admin.profile.update") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(async res => {
            const data = await res.json();
            if (!res.ok) {
                throw new Error(data.message || 'Gagal menyimpan profil.');
            }
            return data;
        })
        .then(data => {
            // Update nama user di header & dropdown
            const topUserName = document.getElementById('topUserName');
            const dropdownUserName = document.getElementById('dropdownUserName');
            if (topUserName) topUserName.textContent = data.name;
            if (dropdownUserName) dropdownUserName.textContent = data.name;

            // Update avatar di header & dropdown
            updateUIAvatar(data.avatar_url, data.initials);

            closeAdminProfileModal();

            showPlatformSwal({
                icon: 'success',
                title: 'Profil Berhasil Diperbarui!',
                text: data.message || 'Perubahan informasi akun dan foto profil Anda telah disimpan.',
                confirmText: 'Oke, Mengerti'
            });

            if (typeof window.showToast === 'function') {
                window.showToast('Profil admin berhasil diperbarui.', 'success');
            }
        })
        .catch(err => {
            alertBox.className = 'admin-pwd-alert alert-danger';
            alertBox.textContent = err.message;
            alertBox.style.display = 'block';
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save"></i> Simpan Perubahan';
        });
    }

    function deleteAdminAvatar() {
        showPlatformSwal({
            title: 'Hapus Foto Profil?',
            text: 'Foto profil akun Anda akan dihapus dan kembali menggunakan inisial nama.',
            icon: 'warning',
            showCancelButton: true,
            confirmDanger: true,
            confirmText: '<i class="fas fa-trash"></i> Ya, Hapus',
            cancelText: 'Batal',
            onResult: (result) => {
                if (!result.isConfirmed) return;

                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                fetch('{{ route("platform-admin.profile.avatar.delete") }}', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(async res => {
                    const data = await res.json();
                    if (!res.ok) throw new Error(data.message || 'Gagal menghapus avatar.');
                    return data;
                })
                .then(data => {
                    const fileInput = document.getElementById('admin_avatar_input');
                    if (fileInput) fileInput.value = '';

                    const previewBox = document.getElementById('profileAvatarPreview');
                    if (previewBox) {
                        previewBox.innerHTML = `<div class="avatar-preview-initials" id="previewAvatarInitials">${data.initials}</div>`;
                    }

                    const btnDelete = document.getElementById('btnDeleteAvatar');
                    if (btnDelete) btnDelete.style.display = 'none';

                    updateUIAvatar(null, data.initials);

                    showPlatformSwal({
                        icon: 'success',
                        title: 'Foto Profil Dihapus',
                        text: data.message || 'Foto profil Anda berhasil dihapus.',
                        confirmText: 'Oke, Mengerti'
                    });

                    if (typeof window.showToast === 'function') {
                        window.showToast('Foto profil berhasil dihapus.', 'info');
                    }
                })
                .catch(err => {
                    showPlatformSwal({
                        icon: 'error',
                        title: 'Gagal Menghapus Foto',
                        text: err.message,
                        confirmDanger: true,
                        confirmText: 'Tutup'
                    });
                });
            }
        });
    }

    function updateUIAvatar(avatarUrl, initials) {
        const headerAvatar = document.getElementById('headerUserAvatar');
        const dropdownAvatar = document.getElementById('dropdownUserAvatar');

        const htmlContent = avatarUrl 
            ? `<img src="${avatarUrl}" alt="Avatar" class="avatar-fit-img">`
            : `<span class="avatar-initials">${initials}</span>`;

        if (headerAvatar) headerAvatar.innerHTML = htmlContent;
        if (dropdownAvatar) dropdownAvatar.innerHTML = htmlContent;
    }

    /* =========================================================
       MODAL: MANAJEMEN SESI LOGIN AKTIF
       ========================================================= */
    function openAdminSessionsModal() {
        const dropdown = document.getElementById('platformThemeDropdown');
        if (dropdown) dropdown.classList.remove('show');
        const modal = document.getElementById('adminSessionsModal');
        if (modal) {
            modal.classList.add('show');
            loadAdminSessions();
        }
    }

    function closeAdminSessionsModal() {
        const modal = document.getElementById('adminSessionsModal');
        if (modal) modal.classList.remove('show');
    }

    function loadAdminSessions() {
        const container = document.getElementById('adminSessionsList');
        const btnRevoke = document.getElementById('btnRevokeOthers');

        container.innerHTML = `
            <div style="text-align: center; padding: 24px; color: #94a3b8; font-size: 12px;">
                <i class="fas fa-spinner fa-spin" style="font-size: 18px; margin-bottom: 6px; color: #ed842c; display: block;"></i>
                Memuat daftar sesi aktif...
            </div>
        `;

        fetch('{{ route("platform-admin.sessions.index") }}', {
            headers: { 'Accept': 'application/json' }
        })
        .then(async res => {
            const data = await res.json();
            if (!res.ok) throw new Error(data.message || 'Gagal memuat sesi.');
            return data;
        })
        .then(data => {
            const sessions = data.sessions || [];
            if (sessions.length === 0) {
                container.innerHTML = `<div style="text-align: center; padding: 20px; color: #94a3b8; font-size: 12px;">Tidak ada sesi aktif ditemukan.</div>`;
                if (btnRevoke) btnRevoke.style.display = 'none';
                return;
            }

            let hasOtherSessions = false;
            let html = '';

            sessions.forEach(s => {
                if (!s.is_current) hasOtherSessions = true;

                let iconClass = 'fa-desktop';
                if (s.device_type === 'mobile') iconClass = 'fa-mobile-screen-button';
                else if (s.platform.toLowerCase().includes('mac')) iconClass = 'fa-laptop';

                html += `
                    <div class="session-item-card ${s.is_current ? 'is-current' : ''}">
                        <div class="session-card-icon">
                            <i class="fas ${iconClass}"></i>
                        </div>
                        <div class="session-card-main">
                            <div class="session-card-header">
                                <span class="session-card-title">${escapeHtml(s.browser)} di ${escapeHtml(s.platform)}</span>
                                ${s.is_current ? `<span class="session-badge-current"><span class="session-pulse-dot"></span> Sesi Ini</span>` : ''}
                            </div>
                            <div class="session-card-meta">
                                <span><i class="fas fa-network-wired" style="font-size: 10px; opacity: 0.7;"></i> ${escapeHtml(s.ip_address)}</span>
                                <span>•</span>
                                <span><i class="fas fa-clock" style="font-size: 10px; opacity: 0.7;"></i> ${escapeHtml(s.last_active)}</span>
                            </div>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;
            if (btnRevoke) {
                btnRevoke.style.display = hasOtherSessions ? 'inline-flex' : 'none';
            }
        })
        .catch(err => {
            container.innerHTML = `
                <div style="text-align: center; padding: 18px; color: #ef4444; font-size: 12px;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 16px; margin-bottom: 4px; display: block;"></i>
                    ${err.message}
                </div>
            `;
        });
    }

    function revokeOtherSessions() {
        showPlatformSwal({
            title: 'Keluar dari Sesi Lain?',
            text: 'Semua perangkat dan peramban lain yang terhubung dengan akun ini akan dipaksa logout.',
            icon: 'warning',
            showCancelButton: true,
            confirmDanger: true,
            confirmText: '<i class="fas fa-sign-out-alt"></i> Ya, Keluarkan',
            cancelText: 'Batal',
            onResult: (result) => {
                if (!result.isConfirmed) return;

                const btnRevoke = document.getElementById('btnRevokeOthers');
                if (btnRevoke) {
                    btnRevoke.disabled = true;
                    btnRevoke.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
                }

                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                fetch('{{ route("platform-admin.sessions.revoke-others") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(async res => {
                    const data = await res.json();
                    if (!res.ok) throw new Error(data.message || 'Gagal mengeluarkan sesi.');
                    return data;
                })
                .then(data => {
                    showPlatformSwal({
                        icon: 'success',
                        title: 'Sesi Berhasil Dihentikan!',
                        text: data.message || 'Semua sesi perangkat lain telah berhasil di-logout.',
                        confirmText: 'Oke, Mengerti'
                    });
                    loadAdminSessions();
                })
                .catch(err => {
                    showPlatformSwal({
                        icon: 'error',
                        title: 'Terjadi Kendala',
                        text: err.message,
                        confirmDanger: true,
                        confirmText: 'Tutup'
                    });
                })
                .finally(() => {
                    if (btnRevoke) {
                        btnRevoke.disabled = false;
                        btnRevoke.innerHTML = '<i class="fas fa-sign-out-alt"></i> Keluar dari Perangkat Lain';
                    }
                });
            }
        });
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    /* =========================================================
       MODAL: UBAH KATA SANDI ADMIN
       ========================================================= */
    function openAdminPasswordModal() {
        const dropdown = document.getElementById('platformThemeDropdown');
        if (dropdown) dropdown.classList.remove('show');
        const modal = document.getElementById('adminPasswordModal');
        if (modal) {
            modal.classList.add('show');
            const form = document.getElementById('adminPasswordForm');
            if (form) form.reset();
            const alertBox = document.getElementById('adminPwdAlert');
            if (alertBox) {
                alertBox.style.display = 'none';
                alertBox.textContent = '';
            }
            setTimeout(() => {
                const firstInput = document.getElementById('admin_current_password');
                if (firstInput) firstInput.focus();
            }, 100);
        }
    }

    function closeAdminPasswordModal() {
        const modal = document.getElementById('adminPasswordModal');
        if (modal) modal.classList.remove('show');
    }

    function togglePasswordVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        if (!input) return;
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            if (icon) {
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        } else {
            input.type = 'password';
            if (icon) {
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    }

    function submitAdminPassword(e) {
        e.preventDefault();
        const submitBtn = document.getElementById('btnSubmitAdminPwd');
        const alertBox = document.getElementById('adminPwdAlert');
        const currentPassword = document.getElementById('admin_current_password').value;
        const newPassword = document.getElementById('admin_new_password').value;
        const confirmPassword = document.getElementById('admin_new_password_confirmation').value;

        if (newPassword !== confirmPassword) {
            alertBox.className = 'admin-pwd-alert alert-danger';
            alertBox.textContent = 'Konfirmasi kata sandi baru tidak sesuai.';
            alertBox.style.display = 'block';
            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
        alertBox.style.display = 'none';

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        fetch('{{ route("platform-admin.password.update") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                current_password: currentPassword,
                new_password: newPassword,
                new_password_confirmation: confirmPassword
            })
        })
        .then(async res => {
            const data = await res.json();
            if (!res.ok) {
                throw new Error(data.message || 'Terjadi kesalahan saat memperbarui kata sandi.');
            }
            return data;
        })
        .then(data => {
            closeAdminPasswordModal();
            showPlatformSwal({
                icon: 'success',
                title: 'Kata Sandi Diperbarui!',
                text: data.message || 'Kata sandi akun Platform Admin Anda berhasil diperbarui.',
                confirmText: 'Oke, Mengerti'
            });

            if (typeof window.showToast === 'function') {
                window.showToast('Kata sandi berhasil diperbarui.', 'success');
            }
        })
        .catch(err => {
            alertBox.className = 'admin-pwd-alert alert-danger';
            alertBox.textContent = err.message;
            alertBox.style.display = 'block';
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save"></i> Simpan Kata Sandi';
        });
    }
</script>
