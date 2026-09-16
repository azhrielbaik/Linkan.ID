{{-- Reusable Platform Admin Profile & Quick Account Dropdown --}}
<div class="header-user-wrapper" id="platformProfileDropdownWrapper">
    <div class="top-profile" id="platformProfileBtn" onclick="togglePlatformThemeDropdown(event)" aria-haspopup="true" aria-expanded="false">
        <div class="top-avatar" id="headerUserAvatar">
            {{ strtoupper(substr(Auth::user()->name ?? 'AD', 0, 2)) }}
        </div>
        <div class="top-user-info">
            <span class="top-user-name">{{ Auth::user()->name ?? 'Admin' }}</span>
            <i class="fas fa-caret-down top-profile-arrow"></i>
        </div>
    </div>

    {{-- Dropdown Card (Compact & Sleek) --}}
    <div class="platform-theme-dropdown" id="platformThemeDropdown" onclick="event.stopPropagation()">
        {{-- Profile Info Header --}}
        <div class="ptd-header">
            <div class="ptd-avatar" id="dropdownUserAvatar">
                {{ strtoupper(substr(Auth::user()->name ?? 'AD', 0, 2)) }}
            </div>
            <div class="ptd-user-info">
                <div class="ptd-name">{{ Auth::user()->name ?? 'Admin Platform' }}</div>
                <div class="ptd-email">{{ Auth::user()->email ?? 'admin@linkan.id' }}</div>
                <span class="ptd-role-pill"><i class="fas fa-shield-alt"></i> Platform Admin</span>
            </div>
        </div>

        {{-- Dropdown Menu Items --}}
        <div class="ptd-menu-list">
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

{{-- Modal Ubah Password Admin --}}
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
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message || 'Kata sandi akun Anda berhasil diperbarui.',
                    confirmButtonColor: '#ed842c',
                    confirmButtonText: 'OK'
                });
            } else {
                alert(data.message || 'Kata sandi berhasil diperbarui.');
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
