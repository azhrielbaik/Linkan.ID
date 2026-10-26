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
