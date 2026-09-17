// Platform Admin User Management Scripts

function applyReasonPreset(text) {
    const el = document.getElementById('suspend_reason');
    if (!el) return;
    const currentVal = el.value.trim();
    if (currentVal === '') {
        el.value = text;
    } else if (!currentVal.includes(text)) {
        el.value = currentVal + ' • ' + text;
    }
    el.focus();
}

function openSuspendModal(userId, userName) {
    const target = document.getElementById('suspendTargetName');
    const avatar = document.getElementById('suspendTargetAvatar');
    const form = document.getElementById('suspendForm');
    const modal = document.getElementById('suspendModal');
    const reasonInput = document.getElementById('suspend_reason');
    const baseUrl = (window.PlatformUsersConfig && window.PlatformUsersConfig.userBaseUrl) || '/platform-admin/users';

    if (target) target.textContent = userName || '-';
    if (avatar) {
        const initial = (userName || '').trim().charAt(0).toUpperCase();
        avatar.innerHTML = initial ? `<span style="font-size: 16px; font-weight: 800;">${initial}</span>` : '<i class="fas fa-user-slash"></i>';
    }
    if (form) form.action = `${baseUrl}/${userId}/suspend`;
    if (reasonInput) reasonInput.value = '';
    if (modal) modal.classList.add('show');
}

function closeSuspendModal() {
    const modal = document.getElementById('suspendModal');
    if (modal) modal.classList.remove('show');
}

function openActivateModal(userId, userName) {
    const target = document.getElementById('activateTargetName');
    const avatar = document.getElementById('activateTargetAvatar');
    const form = document.getElementById('activateForm');
    const modal = document.getElementById('activateModal');
    const reasonInput = document.getElementById('activate_reason');
    const baseUrl = (window.PlatformUsersConfig && window.PlatformUsersConfig.userBaseUrl) || '/platform-admin/users';

    if (target) target.textContent = userName || '-';
    if (avatar) {
        const initial = (userName || '').trim().charAt(0).toUpperCase();
        avatar.innerHTML = initial ? `<span style="font-size: 16px; font-weight: 800;">${initial}</span>` : '<i class="fas fa-user-check"></i>';
    }
    if (form) form.action = `${baseUrl}/${userId}/activate`;
    if (reasonInput) reasonInput.value = '';
    if (modal) modal.classList.add('show');
}

function closeActivateModal() {
    const modal = document.getElementById('activateModal');
    if (modal) modal.classList.remove('show');
}

function openRejectAppealModal(appealId, userName) {
    const target = document.getElementById('rejectTargetName');
    const avatar = document.getElementById('rejectTargetAvatar');
    const form = document.getElementById('rejectAppealForm');
    const modal = document.getElementById('rejectAppealModal');
    const notesInput = document.getElementById('admin_notes');
    const baseUrl = (window.PlatformUsersConfig && window.PlatformUsersConfig.appealsBaseUrl) || '/platform-admin/users/appeals';

    if (target) target.textContent = userName || '-';
    if (avatar) {
        const initial = (userName || '').trim().charAt(0).toUpperCase();
        avatar.innerHTML = initial ? `<span style="font-size: 16px; font-weight: 800;">${initial}</span>` : '<i class="fas fa-times-circle"></i>';
    }
    if (notesInput) notesInput.value = '';
    if (form) form.action = `${baseUrl}/${appealId}/reject`;
    if (modal) modal.classList.add('show');
}

function closeRejectAppealModal() {
    const modal = document.getElementById('rejectAppealModal');
    if (modal) modal.classList.remove('show');
}

function getSellerSkeletonHtml() {
    return `
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
    `;
}

function safeEscapeHtml(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function openSellerModal(userId) {
    const modal = document.getElementById('sellerModal');
    const modalBody = document.getElementById('sellerModalBody');
    const baseUrl = (window.PlatformUsersConfig && window.PlatformUsersConfig.userBaseUrl) || '/platform-admin/users';
    const lang = (window.PlatformUsersConfig && window.PlatformUsersConfig.lang) || {};
    
    if (!modal || !modalBody) return;
    
    modal.classList.add('show');
    modalBody.innerHTML = getSellerSkeletonHtml();

    fetch(`${baseUrl}/${userId}/detail`)
        .then(res => res.json())
        .then(data => {
            if (data.status !== 'success') {
                modalBody.innerHTML = `<div class="alert alert-error" style="padding: 16px; border-radius: 12px; margin: 10px 0;">${lang.failed || 'Gagal memuat detail profil.'}</div>`;
                return;
            }

            const u = data.user;
            const s = data.stats;
            const products = data.recent_products || [];
            const payouts = data.recent_payouts || [];
            const appeals = data.appeals_history || [];

            let avatarHtml = u.avatar 
                ? `<img src="${u.avatar}" alt="${safeEscapeHtml(u.name)}">` 
                : safeEscapeHtml(u.name.substring(0, 2).toUpperCase());

            let statusBadge = u.is_suspended 
                ? `<span class="seller-status-chip chip-suspended"><span class="status-pulse-dot dot-suspended"></span> ${lang.suspended || 'Ditangguhkan'}</span>`
                : `<span class="seller-status-chip chip-active"><span class="status-pulse-dot dot-active"></span> ${lang.active || 'Aktif'}</span>`;

            let ctrValue = 0;
            if (s.total_views > 0 && s.total_clicks > 0) {
                ctrValue = ((s.total_clicks / s.total_views) * 100).toFixed(1);
            }

            let html = `
                <!-- Seller Header Banner -->
                <div class="seller-banner">
                    <div class="seller-banner-left">
                        <div class="seller-banner-avatar">${avatarHtml}</div>
                        <div class="seller-banner-info">
                            <div class="seller-banner-top">
                                <h4 class="seller-name">${safeEscapeHtml(u.name)}</h4>
                                ${statusBadge}
                            </div>
                            <div class="seller-meta">
                                <span><i class="far fa-envelope"></i> ${safeEscapeHtml(u.email)}</span>
                                <span class="meta-sep">&bull;</span>
                                <span><i class="far fa-calendar-alt"></i> Bergabung: ${safeEscapeHtml(u.joined_at)}</span>
                            </div>
                            <div class="seller-link-wrap">
                                <a href="${u.microsite_url}" target="_blank" class="seller-microsite-chip" title="Buka Halaman Publik Microsite">
                                    <i class="fas fa-globe"></i>
                                    <span>${safeEscapeHtml(u.microsite_url)}</span>
                                    <i class="fas fa-arrow-up-right-from-square chip-ext-icon"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4 Financial Stats Grid (Clean SaaS Inspector) -->
                <div class="modal-stats-grid">
                    <div class="modal-stat-card">
                        <div class="stat-card-header">
                            <span class="stat-card-lbl">${lang.total_turnover || 'Total Omset'}</span>
                            <div class="stat-card-icon icon-omset"><i class="fas fa-chart-line"></i></div>
                        </div>
                        <div class="stat-card-val val-omset">Rp ${Number(s.total_turnover || 0).toLocaleString('id-ID')}</div>
                        <div class="stat-card-sub">Gross Revenue</div>
                    </div>
                    <div class="modal-stat-card">
                        <div class="stat-card-header">
                            <span class="stat-card-lbl">${lang.current_balance || 'Saldo Akun'}</span>
                            <div class="stat-card-icon icon-balance"><i class="fas fa-wallet"></i></div>
                        </div>
                        <div class="stat-card-val val-balance">Rp ${Number(s.current_balance || 0).toLocaleString('id-ID')}</div>
                        <div class="stat-card-sub">Tersedia untuk Payout</div>
                    </div>
                    <div class="modal-stat-card">
                        <div class="stat-card-header">
                            <span class="stat-card-lbl">${lang.total_withdrawn || 'Total Withdraw'}</span>
                            <div class="stat-card-icon icon-payout"><i class="fas fa-money-bill-transfer"></i></div>
                        </div>
                        <div class="stat-card-val val-payout">Rp ${Number(s.total_withdrawn || 0).toLocaleString('id-ID')}</div>
                        <div class="stat-card-sub">Telah Dicairkan</div>
                    </div>
                    <div class="modal-stat-card">
                        <div class="stat-card-header">
                            <span class="stat-card-lbl">${lang.total_orders || 'Pesanan Sukses'}</span>
                            <div class="stat-card-icon icon-orders"><i class="fas fa-bag-shopping"></i></div>
                        </div>
                        <div class="stat-card-val val-orders">${s.total_orders || 0} <span class="unit">Pesanan</span></div>
                        <div class="stat-card-sub">Transaksi Berhasil</div>
                    </div>
                </div>

                <!-- Extra Overview Grid -->
                <div class="seller-extra-grid">
                    <div class="extra-stat-box">
                        <div class="extra-stat-title">
                            <i class="fas fa-boxes-stacked icon-accent"></i>
                            <span>Inventaris Produk Digital</span>
                        </div>
                        <div class="extra-stat-chips">
                            <span class="stat-chip chip-total">Total: <strong>${s.total_products || 0}</strong></span>
                            <span class="stat-chip chip-live"><span class="chip-dot dot-live"></span> Live: <strong>${s.active_products || 0}</strong></span>
                            <span class="stat-chip chip-pending"><span class="chip-dot dot-pending"></span> Pending: <strong>${s.pending_products || 0}</strong></span>
                            <span class="stat-chip chip-takedown"><span class="chip-dot dot-takedown"></span> Takedown: <strong>${s.takedown_products || 0}</strong></span>
                        </div>
                    </div>
                    <div class="extra-stat-box">
                        <div class="extra-stat-title">
                            <i class="fas fa-chart-simple icon-accent"></i>
                            <span>Trafik & Kunjungan Microsite</span>
                        </div>
                        <div class="extra-stat-chips">
                            <span class="stat-chip chip-traffic"><i class="fas fa-eye"></i> <strong>${Number(s.total_views || 0).toLocaleString('id-ID')}</strong> Views</span>
                            <span class="stat-chip chip-traffic"><i class="fas fa-arrow-pointer"></i> <strong>${Number(s.total_clicks || 0).toLocaleString('id-ID')}</strong> Clicks</span>
                            <span class="stat-chip chip-ctr"><i class="fas fa-percent"></i> CTR: <strong>${ctrValue}%</strong></span>
                        </div>
                    </div>
                </div>

                <!-- Modal Tabs (Products, Payouts & Appeals) -->
                <div class="modal-tabs-wrapper">
                    <div class="modal-tabs-nav">
                        <button type="button" class="modal-tab-pill active" onclick="switchModalTab('tabProducts')">
                            <i class="fas fa-box"></i>
                            <span>${lang.products_tab || 'Produk Digital'}</span>
                            <span class="tab-badge">${products.length}</span>
                        </button>
                        <button type="button" class="modal-tab-pill" onclick="switchModalTab('tabPayouts')">
                            <i class="fas fa-money-bill-wave"></i>
                            <span>${lang.payouts_tab || 'Riwayat Payout'}</span>
                            <span class="tab-badge">${payouts.length}</span>
                        </button>
                        <button type="button" class="modal-tab-pill" onclick="switchModalTab('tabAppeals')">
                            <i class="fas fa-shield-halved"></i>
                            <span>Riwayat Banding</span>
                            ${appeals.length > 0 ? `<span class="tab-badge" style="background:#fff7ed;color:#ea580c;border:1px solid #fed7aa;">${appeals.length}</span>` : `<span class="tab-badge">0</span>`}
                        </button>
                    </div>
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
                                        <td style="font-weight: 700; color: #0f172a;">${safeEscapeHtml(p.title)}</td>
                                        <td style="font-weight: 600;">Rp ${Number(p.sale_price || p.price).toLocaleString('id-ID')}</td>
                                        <td><span class="badge badge-role" style="font-size: 10px;">${safeEscapeHtml(p.platform_type)}</span></td>
                                        <td>
                                            <span class="badge ${p.verification_status === 'approved' ? 'badge-active' : (p.verification_status === 'rejected' ? 'badge-suspended' : 'badge-role')}" style="font-size: 10px;">
                                                ${safeEscapeHtml(p.verification_status)}
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
                    ` : `
                        <div class="modal-empty-state">
                            <div class="empty-state-icon"><i class="fas fa-box-open"></i></div>
                            <div class="empty-state-title">Belum Ada Produk Digital</div>
                            <p class="empty-state-desc">Seller ini belum mengunggah atau mempublikasikan produk digital di Linkan.ID.</p>
                        </div>
                    `}
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
                                        <td>${safeEscapeHtml(po.method || '-')}</td>
                                        <td>
                                            <span class="badge ${po.status === 'approved' ? 'badge-active' : (po.status === 'rejected' ? 'badge-suspended' : 'badge-role')}" style="font-size: 10px;">
                                                ${safeEscapeHtml(po.status)}
                                            </span>
                                        </td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    ` : `
                        <div class="modal-empty-state">
                            <div class="empty-state-icon"><i class="fas fa-receipt"></i></div>
                            <div class="empty-state-title">Belum Ada Riwayat Payout</div>
                            <p class="empty-state-desc">Seller ini belum pernah mengajukan atau menerima pencairan dana.</p>
                        </div>
                    `}
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
                                    <div style="border: 1px solid #f1f5f9; border-left: 4px solid ${cfg.border}; border-radius: 12px; padding: 14px 16px; background: #fafbfc;">
                                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px; flex-wrap: wrap; gap: 8px;">
                                            <div style="display: flex; align-items: center; gap: 8px;">
                                                <span style="font-size: 11px; font-weight: 800; background: #f1f5f9; color: #475569; padding: 2px 8px; border-radius: 20px;">Percobaan #${attemptNum}</span>
                                                <span class="badge ${cfg.cls}" style="font-size: 10px;"><i class="fas ${cfg.icon}"></i> ${cfg.label}</span>
                                            </div>
                                            <span style="font-size: 11px; color: #94a3b8;"><i class="far fa-clock"></i> ${safeEscapeHtml(a.submitted_at)}</span>
                                        </div>
                                        <div style="font-size: 13px; color: #334155; line-height: 1.55; margin-bottom: ${a.admin_notes ? '10px' : '0'}">${safeEscapeHtml(a.appeal_reason)}</div>
                                        ${a.admin_notes ? `
                                            <div style="font-size: 11px; color: #64748b; background: #fff; border: 1px solid #e2e8f0; border-left: 3px solid #ED842C; border-radius: 8px; padding: 8px 12px; margin-top: 8px;">
                                                <strong style="color: #0f172a;">Catatan Admin:</strong> ${safeEscapeHtml(a.admin_notes)}
                                                ${a.resolved_at ? `<span style="float:right;color:#94a3b8;">${safeEscapeHtml(a.resolved_at)}</span>` : ''}
                                            </div>
                                        ` : ''}
                                    </div>
                                `;
                            }).join('')}
                        </div>
                    ` : `
                        <div class="modal-empty-state">
                            <div class="empty-state-icon"><i class="fas fa-shield-check"></i></div>
                            <div class="empty-state-title">Akun Bersih & Normal</div>
                            <p class="empty-state-desc">Tidak ada riwayat permohonan banding suspensi untuk akun seller ini.</p>
                        </div>
                    `}
                </div>
            `;

            modalBody.innerHTML = html;
        })
        .catch(err => {
            modalBody.innerHTML = `<div class="alert alert-error" style="padding: 16px; border-radius: 12px; margin: 10px 0;">${lang.failed || 'Gagal memuat detail profil.'}</div>`;
        });
}

function switchModalTab(tabId) {
    document.querySelectorAll('.modal-tab-pill, .modal-tab-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.modal-tab-content').forEach(content => content.classList.remove('active'));

    if (window.event && window.event.target) {
        const targetBtn = window.event.target.closest('.modal-tab-pill, .modal-tab-btn');
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
            <div id="approveNotesSection" style="display: none;">
                <div style="font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.05em;">
                    <i class="fas fa-check-circle" style="color: #16a34a;"></i> Catatan Persetujuan (Opsional)
                </div>
                <textarea id="appealApproveNotes" rows="3" class="form-control" placeholder="Tuliskan catatan persetujuan pemulihan akun (opsional)..." style="width: 100%; resize: vertical;"></textarea>
            </div>
        </div>
    `;

    footer.innerHTML = `
        <button type="button" class="btn-modal-cancel" onclick="closeAppealDetailModal()">Tutup</button>
        <button type="button" class="btn-action btn-reject" id="btnShowReject" onclick="showAppealRejectSection()">
            <i class="fas fa-times"></i> Tolak Banding
        </button>
        <button type="button" class="btn-action btn-approve" id="btnApprove" onclick="showAppealApproveSection()">
            <i class="fas fa-check"></i> Setujui Banding
        </button>
    `;

    modal.classList.add('show');
}

function showAppealRejectSection() {
    const section        = document.getElementById('rejectNotesSection');
    const approveSection = document.getElementById('approveNotesSection');
    const btnShowReject  = document.getElementById('btnShowReject');
    const btnApprove     = document.getElementById('btnApprove');
    const footer         = document.getElementById('appealDetailFooter');
    if (!section) return;

    if (approveSection) approveSection.style.display = 'none';
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

function showAppealApproveSection() {
    const section       = document.getElementById('approveNotesSection');
    const rejectSection = document.getElementById('rejectNotesSection');
    const btnShowReject = document.getElementById('btnShowReject');
    const btnApprove    = document.getElementById('btnApprove');
    const footer        = document.getElementById('appealDetailFooter');
    if (!section) return;

    if (rejectSection) rejectSection.style.display = 'none';
    section.style.display = 'block';
    if (btnShowReject) btnShowReject.style.display = 'none';
    if (btnApprove)    btnApprove.style.display    = 'none';

    const confirmBtn = document.createElement('button');
    confirmBtn.type = 'button';
    confirmBtn.className = 'btn-modal-submit-primary';
    confirmBtn.style.background = '#16a34a';
    confirmBtn.style.color = '#fff';
    confirmBtn.style.border = 'none';
    confirmBtn.style.padding = '8px 16px';
    confirmBtn.style.borderRadius = '8px';
    confirmBtn.style.fontWeight = '700';
    confirmBtn.style.cursor = 'pointer';
    confirmBtn.innerHTML = '<i class="fas fa-check"></i> Konfirmasi Setujui & Pulihkan';
    confirmBtn.onclick = submitApproveAppeal;
    footer.appendChild(confirmBtn);
}

function submitApproveAppeal() {
    if (!_currentAppealData) return;
    const notes = (document.getElementById('appealApproveNotes') || {}).value || '';
    const userName = _currentAppealData.user_name;
    const url      = _currentAppealData.approve_url;

    if (typeof showConfirmModal === 'function') {
        showConfirmModal({
            title: 'Setujui Permohonan Banding?',
            text: `Permohonan banding dari ${userName} akan disetujui, dan status suspend akun akan langsung dipulihkan seketika.`,
            icon: 'question',
            confirmText: '<i class="fas fa-check"></i> Ya, Setujui & Pulihkan',
            onConfirm: () => { _postAppealAction(url, { admin_notes: notes }); }
        });
    } else {
        if (confirm(`Setujui banding dari ${userName}?`)) {
            _postAppealAction(url, { admin_notes: notes });
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
