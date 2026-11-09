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
