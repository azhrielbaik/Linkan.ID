// Platform Admin User Management Scripts

function openSuspendModal(userId, userName) {
    const target = document.getElementById('suspendTargetName');
    const form = document.getElementById('suspendForm');
    const modal = document.getElementById('suspendModal');
    const baseUrl = (window.PlatformUsersConfig && window.PlatformUsersConfig.userBaseUrl) || '/platform-admin/users';

    if (target) target.textContent = userName;
    if (form) form.action = `${baseUrl}/${userId}/suspend`;
    if (modal) modal.classList.add('show');
}

function closeSuspendModal() {
    const modal = document.getElementById('suspendModal');
    if (modal) modal.classList.remove('show');
}

function openRejectAppealModal(appealId, userName) {
    const target = document.getElementById('rejectTargetName');
    const form = document.getElementById('rejectAppealForm');
    const modal = document.getElementById('rejectAppealModal');
    const baseUrl = (window.PlatformUsersConfig && window.PlatformUsersConfig.appealsBaseUrl) || '/platform-admin/users/appeals';

    if (target) target.textContent = userName;
    if (form) form.action = `${baseUrl}/${appealId}/reject`;
    if (modal) modal.classList.add('show');
}

function closeRejectAppealModal() {
    const modal = document.getElementById('rejectAppealModal');
    if (modal) modal.classList.remove('show');
}

function openSellerModal(userId) {
    const modal = document.getElementById('sellerModal');
    const modalBody = document.getElementById('sellerModalBody');
    const baseUrl = (window.PlatformUsersConfig && window.PlatformUsersConfig.userBaseUrl) || '/platform-admin/users';
    const lang = (window.PlatformUsersConfig && window.PlatformUsersConfig.lang) || {};
    
    if (!modal || !modalBody) return;
    
    modal.classList.add('show');
    modalBody.innerHTML = `
        <div class="loading-spinner">
            <i class="fas fa-spinner fa-spin" style="font-size: 28px; margin-bottom: 8px; display: block;"></i>
            ${lang.loading || 'Memuat data seller...'}
        </div>
    `;

    fetch(`${baseUrl}/${userId}/detail`)
        .then(res => res.json())
        .then(data => {
            if (data.status !== 'success') {
                modalBody.innerHTML = `<div class="alert alert-error">${lang.failed || 'Gagal memuat detail profil.'}</div>`;
                return;
            }

            const u = data.user;
            const s = data.stats;
            const products = data.recent_products || [];
            const payouts = data.recent_payouts || [];
            const appeals = data.appeals_history || [];

            let avatarHtml = u.avatar 
                ? `<img src="${u.avatar}" alt="${u.name}">` 
                : u.name.substring(0, 2).toUpperCase();

            let statusBadge = u.is_suspended 
                ? `<span class="badge badge-suspended"><i class="fas fa-ban" style="font-size:10px;"></i> ${lang.suspended || 'Ditangguhkan'}</span>`
                : `<span class="badge badge-active"><i class="fas fa-circle" style="font-size:8px;"></i> ${lang.active || 'Aktif'}</span>`;

            let html = `
                <!-- Seller Header Banner -->
                <div class="seller-banner">
                    <div class="seller-banner-left">
                        <div class="seller-banner-avatar">${avatarHtml}</div>
                        <div class="seller-banner-info">
                            <div class="name">${u.name}</div>
                            <div class="email">${u.email} &bull; Bergabung: ${u.joined_at}</div>
                            <a href="${u.microsite_url}" target="_blank" class="link">
                                <i class="fas fa-external-link-alt"></i> ${u.microsite_url}
                            </a>
                        </div>
                    </div>
                    <div>
                        ${statusBadge}
                    </div>
                </div>

                <!-- 4 Mini Financial Stats Grid -->
                <div class="modal-stats-grid">
                    <div class="modal-stat-box">
                        <div class="box-lbl">${lang.total_turnover || 'Total Omset Produk'}</div>
                        <div class="box-val" style="color: #16a34a;">Rp ${Number(s.total_turnover).toLocaleString('id-ID')}</div>
                    </div>
                    <div class="modal-stat-box">
                        <div class="box-lbl">${lang.current_balance || 'Saldo Seller'}</div>
                        <div class="box-val" style="color: #5A5BF1;">Rp ${Number(s.current_balance).toLocaleString('id-ID')}</div>
                    </div>
                    <div class="modal-stat-box">
                        <div class="box-lbl">${lang.total_withdrawn || 'Sudah Dicairkan'}</div>
                        <div class="box-val" style="color: #d97706;">Rp ${Number(s.total_withdrawn).toLocaleString('id-ID')}</div>
                    </div>
                    <div class="modal-stat-box">
                        <div class="box-lbl">${lang.total_orders || 'Pesanan Sukses'}</div>
                        <div class="box-val">${s.total_orders} Pesanan</div>
                    </div>
                </div>

                <!-- Extra Details Grid -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px; font-size: 13px;">
                    <div style="background: #f8fafc; padding: 12px 16px; border-radius: 10px; border: 1px solid #f1f5f9;">
                        <strong><i class="fas fa-boxes" style="color: #5A5BF1; margin-right: 6px;"></i> Produk Digital:</strong>
                        <div style="margin-top: 4px; color: #64748b;">
                            Total: <strong>${s.total_products}</strong> &bull; Live: <strong style="color: #16a34a;">${s.active_products}</strong> &bull; Pending: <strong style="color: #d97706;">${s.pending_products}</strong> &bull; Takedown: <strong style="color: #dc2626;">${s.takedown_products}</strong>
                        </div>
                    </div>
                    <div style="background: #f8fafc; padding: 12px 16px; border-radius: 10px; border: 1px solid #f1f5f9;">
                        <strong><i class="fas fa-chart-line" style="color: #5A5BF1; margin-right: 6px;"></i> Kunjungan Microsite:</strong>
                        <div style="margin-top: 4px; color: #64748b;">
                            Views: <strong>${s.total_views}</strong> &bull; Clicks: <strong>${s.total_clicks}</strong>
                        </div>
                    </div>
                </div>

                <!-- Modal Tabs (Products, Payouts & Appeals) -->
                <div class="modal-tabs">
                    <button type="button" class="modal-tab-btn active" onclick="switchModalTab('tabProducts')">
                        <i class="fas fa-box"></i> ${lang.products_tab || 'Produk'} (${products.length})
                    </button>
                    <button type="button" class="modal-tab-btn" onclick="switchModalTab('tabPayouts')">
                        <i class="fas fa-money-bill-wave"></i> ${lang.payouts_tab || 'Riwayat Payout'} (${payouts.length})
                    </button>
                    <button type="button" class="modal-tab-btn" onclick="switchModalTab('tabAppeals')">
                        <i class="fas fa-file-contract"></i> Riwayat Banding ${appeals.length > 0 ? `<span style="background:#fff0e2;color:#ED842C;font-size:10px;font-weight:800;padding:1px 6px;border-radius:10px;margin-left:4px;">${appeals.length}</span>` : ''}
                    </button>
                </div>

                <!-- Tab 1: Products -->
                <div id="tabProducts" class="modal-tab-content active">
                    ${products.length > 0 ? `
                        <table class="modal-mini-table">
                            <thead>
                                <tr>
                                    <th>Judul Produk</th>
                                    <th>Harga</th>
                                    <th>Platform</th>
                                    <th>Verifikasi</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${products.map(p => `
                                    <tr>
                                        <td style="font-weight: 700;">${p.title}</td>
                                        <td>Rp ${Number(p.sale_price || p.price).toLocaleString('id-ID')}</td>
                                        <td><span class="badge badge-role" style="font-size: 10px;">${p.platform_type}</span></td>
                                        <td>
                                            <span class="badge ${p.verification_status === 'approved' ? 'badge-active' : (p.verification_status === 'rejected' ? 'badge-suspended' : 'badge-role')}" style="font-size: 10px;">
                                                ${p.verification_status}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge ${p.is_active ? 'badge-active' : 'badge-suspended'}" style="font-size: 10px;">
                                                ${p.is_active ? 'Live' : 'Takedown'}
                                            </span>
                                        </td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    ` : `<div style="text-align: center; padding: 20px; color: #94a3b8; font-size: 13px;">${lang.no_products || 'Belum ada produk digital.'}</div>`}
                </div>

                <!-- Tab 2: Payouts -->
                <div id="tabPayouts" class="modal-tab-content">
                    ${payouts.length > 0 ? `
                        <table class="modal-mini-table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Nominal Bersih</th>
                                    <th>Fee Platform</th>
                                    <th>Metode</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${payouts.map(po => `
                                    <tr>
                                        <td>${new Date(po.created_at).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })}</td>
                                        <td style="font-weight: 700; color: #16a34a;">Rp ${Number(po.amount).toLocaleString('id-ID')}</td>
                                        <td style="color: #64748b;">Rp ${Number(po.commission || 0).toLocaleString('id-ID')}</td>
                                        <td>${po.method || '-'}</td>
                                        <td>
                                            <span class="badge ${po.status === 'approved' ? 'badge-active' : (po.status === 'rejected' ? 'badge-suspended' : 'badge-role')}" style="font-size: 10px;">
                                                ${po.status}
                                            </span>
                                        </td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    ` : `<div style="text-align: center; padding: 20px; color: #94a3b8; font-size: 13px;">${lang.no_payouts || 'Belum ada riwayat penarikan dana.'}</div>`}
                </div>

                <!-- Tab 3: Riwayat Banding -->
                <div id="tabAppeals" class="modal-tab-content">
                    ${appeals.length > 0 ? `
                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            ${appeals.map((a, i) => {
                                const statusConfig = {
                                    approved: { cls: 'badge-active',    icon: 'fa-check-circle',  label: 'Disetujui',   border: '#16a34a' },
                                    rejected: { cls: 'badge-suspended', icon: 'fa-times-circle', label: 'Ditolak',     border: '#dc2626' },
                                    pending:  { cls: 'badge-pending',   icon: 'fa-clock',        label: 'Menunggu',    border: '#d97706' },
                                };
                                const cfg = statusConfig[a.status] || statusConfig.pending;
                                const attemptNum = appeals.length - i;
                                return `
                                    <div style="border: 1px solid #f1f5f9; border-left: 4px solid ${cfg.border}; border-radius: 10px; padding: 14px 16px; background: #fafafa;">
                                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px; flex-wrap: wrap; gap: 8px;">
                                            <div style="display: flex; align-items: center; gap: 8px;">
                                                <span style="font-size: 11px; font-weight: 800; background: #f1f5f9; color: #475569; padding: 2px 8px; border-radius: 20px;">Percobaan #${attemptNum}</span>
                                                <span class="badge ${cfg.cls}" style="font-size: 10px;"><i class="fas ${cfg.icon}"></i> ${cfg.label}</span>
                                            </div>
                                            <span style="font-size: 11px; color: #94a3b8;"><i class="fas fa-clock"></i> ${a.submitted_at}</span>
                                        </div>
                                        <div style="font-size: 13px; color: #334155; line-height: 1.55; margin-bottom: ${a.admin_notes ? '10px' : '0'}">${a.appeal_reason}</div>
                                        ${a.admin_notes ? `
                                            <div style="font-size: 11px; color: #64748b; background: #fff; border: 1px solid #e2e8f0; border-left: 3px solid #ED842C; border-radius: 6px; padding: 8px 12px; margin-top: 8px;">
                                                <strong>Catatan Admin:</strong> ${a.admin_notes}
                                                ${a.resolved_at ? `<span style="float:right;color:#94a3b8;">${a.resolved_at}</span>` : ''}
                                            </div>
                                        ` : ''}
                                    </div>
                                `;
                            }).join('')}
                        </div>
                    ` : `<div style="text-align: center; padding: 28px 20px; color: #94a3b8; font-size: 13px;"><i class="fas fa-file-contract" style="font-size: 28px; display: block; margin-bottom: 10px; opacity: 0.4;"></i>Belum ada riwayat permohonan banding.</div>`}
                </div>
            `;

            modalBody.innerHTML = html;
        })
        .catch(err => {
            modalBody.innerHTML = `<div class="alert alert-error">${lang.failed || 'Gagal memuat detail profil.'}</div>`;
        });
}

function switchModalTab(tabId) {
    document.querySelectorAll('.modal-tab-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.modal-tab-content').forEach(content => content.classList.remove('active'));

    if (window.event && window.event.target) {
        const targetBtn = window.event.target.closest('.modal-tab-btn');
        if (targetBtn) targetBtn.classList.add('active');
    }
    const targetContent = document.getElementById(tabId);
    if (targetContent) targetContent.classList.add('active');
}

function closeSellerModal() {
    const modal = document.getElementById('sellerModal');
    if (modal) modal.classList.remove('show');
}

function confirmActivateUser(form, userName) {
    if (typeof showConfirmModal === 'function') {
        showConfirmModal({
            title: 'Aktifkan Kembali Akun?',
            text: `Status penangguhan (suspend) untuk akun ${userName} akan dicabut dan seluruh akses fitur akan dipulihkan.`,
            icon: 'question',
            confirmText: '<i class="fas fa-check"></i> Ya, Aktifkan',
            onConfirm: () => {
                form.submit();
            }
        });
    } else {
        if (confirm(`Status penangguhan (suspend) untuk akun ${userName} akan dicabut. Lanjutkan?`)) {
            form.submit();
        }
    }
}

function confirmApproveAppeal(form, userName) {
    if (typeof showConfirmModal === 'function') {
        showConfirmModal({
            title: 'Setujui Permohonan Banding?',
            text: `Permohonan banding dari ${userName} akan disetujui, dan status suspend akun akan langsung dipulihkan seketika.`,
            icon: 'question',
            confirmText: '<i class="fas fa-check"></i> Ya, Setujui & Pulihkan',
            onConfirm: () => {
                form.submit();
            }
        });
    } else {
        if (confirm(`Permohonan banding dari ${userName} akan disetujui. Lanjutkan?`)) {
            form.submit();
        }
    }
}

// ── Appeal Detail Modal ──────────────────────────────────────────────────────

let _currentAppealData = null;

function openAppealDetailModal(appeal) {
    _currentAppealData = appeal;
    const modal  = document.getElementById('appealDetailModal');
    const body   = document.getElementById('appealDetailBody');
    const footer = document.getElementById('appealDetailFooter');
    if (!modal || !body || !footer) return;

    body.innerHTML = `
        <div style="display: flex; flex-direction: column; gap: 16px;">
            <div style="background: #f8fafc; border-radius: 12px; padding: 16px; border: 1px solid #f1f5f9; display: flex; align-items: center; gap: 14px;">
                <div style="width: 46px; height: 46px; border-radius: 50%; background: linear-gradient(135deg, #ED842C, #f59e0b); display: flex; align-items: center; justify-content: center; font-weight: 800; color: #fff; font-size: 16px; flex-shrink: 0;">
                    ${appeal.user_name.substring(0, 2).toUpperCase()}
                </div>
                <div>
                    <div style="font-weight: 700; font-size: 15px; color: #1e293b;">${appeal.user_name}</div>
                    <div style="font-size: 12px; color: #64748b;">${appeal.user_email}</div>
                </div>
                <div style="margin-left: auto;">
                    <span style="font-size: 11px; font-weight: 800; background: #fff0e2; color: #ED842C; padding: 3px 10px; border-radius: 20px;">Percobaan Ke-${appeal.attempt}/3</span>
                </div>
            </div>
            <div style="font-size: 12px; color: #64748b;">
                <i class="fas fa-clock" style="color: #ED842C;"></i> Diajukan: <strong>${appeal.submitted_at}</strong>
            </div>
            <div>
                <div style="font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.05em;">
                    <i class="fas fa-comment-alt" style="color: #ED842C;"></i> Alasan Banding
                </div>
                <div style="background: #fff; border: 1px solid #e2e8f0; border-left: 4px solid #ED842C; border-radius: 8px; padding: 14px 16px; font-size: 14px; color: #1e293b; line-height: 1.6;">
                    ${appeal.appeal_reason}
                </div>
            </div>
            <div id="rejectNotesSection" style="display: none;">
                <div style="font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.05em;">
                    <i class="fas fa-pen" style="color: #dc2626;"></i> Catatan Penolakan
                </div>
                <textarea id="appealRejectNotes" rows="3" class="form-control" placeholder="Tuliskan catatan alasan penolakan banding..." style="width: 100%; resize: vertical;"></textarea>
            </div>
        </div>
    `;

    footer.innerHTML = `
        <button type="button" class="btn-modal-cancel" onclick="closeAppealDetailModal()">Tutup</button>
        <button type="button" class="btn-action btn-reject" id="btnShowReject" onclick="showAppealRejectSection()">
            <i class="fas fa-times"></i> Tolak Banding
        </button>
        <button type="button" class="btn-action btn-approve" id="btnApprove" onclick="submitApproveAppeal()">
            <i class="fas fa-check"></i> Setujui Banding
        </button>
    `;

    modal.classList.add('show');
}

function showAppealRejectSection() {
    const section       = document.getElementById('rejectNotesSection');
    const btnShowReject = document.getElementById('btnShowReject');
    const btnApprove    = document.getElementById('btnApprove');
    const footer        = document.getElementById('appealDetailFooter');
    if (!section) return;

    section.style.display = 'block';
    if (btnShowReject) btnShowReject.style.display = 'none';
    if (btnApprove)    btnApprove.style.display    = 'none';

    const confirmBtn = document.createElement('button');
    confirmBtn.type = 'button';
    confirmBtn.className = 'btn-modal-submit-danger';
    confirmBtn.innerHTML = '<i class="fas fa-times"></i> Konfirmasi Tolak';
    confirmBtn.onclick = submitRejectAppeal;
    footer.appendChild(confirmBtn);
}

function submitApproveAppeal() {
    if (!_currentAppealData) return;
    const userName = _currentAppealData.user_name;
    const url      = _currentAppealData.approve_url;

    if (typeof showConfirmModal === 'function') {
        showConfirmModal({
            title: 'Setujui Permohonan Banding?',
            text: `Permohonan banding dari ${userName} akan disetujui, dan status suspend akun akan langsung dipulihkan.`,
            icon: 'question',
            confirmText: '<i class="fas fa-check"></i> Ya, Setujui & Pulihkan',
            onConfirm: () => { _postAppealAction(url, {}); }
        });
    } else {
        if (confirm(`Setujui banding dari ${userName}?`)) {
            _postAppealAction(url, {});
        }
    }
}

function submitRejectAppeal() {
    if (!_currentAppealData) return;
    const notes = (document.getElementById('appealRejectNotes') || {}).value || '';
    if (!notes.trim()) {
        alert('Catatan penolakan wajib diisi.');
        return;
    }
    _postAppealAction(_currentAppealData.reject_url, { admin_notes: notes });
}

function _postAppealAction(url, extraData) {
    const csrfToken = (window.PlatformUsersConfig && window.PlatformUsersConfig.csrfToken) || '';
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = url;
    form.style.display = 'none';

    const csrf = document.createElement('input');
    csrf.type  = 'hidden';
    csrf.name  = '_token';
    csrf.value = csrfToken;
    form.appendChild(csrf);

    for (const [key, val] of Object.entries(extraData)) {
        const input = document.createElement('input');
        input.type  = 'hidden';
        input.name  = key;
        input.value = val;
        form.appendChild(input);
    }

    document.body.appendChild(form);
    form.submit();
}

function closeAppealDetailModal() {
    const modal = document.getElementById('appealDetailModal');
    if (modal) modal.classList.remove('show');
    _currentAppealData = null;
}
