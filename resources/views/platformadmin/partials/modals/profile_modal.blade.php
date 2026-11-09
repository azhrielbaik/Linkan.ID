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
