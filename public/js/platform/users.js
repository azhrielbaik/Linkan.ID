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

                <!-- Modal Tabs (Products & Payouts) -->
                <div class="modal-tabs">
                    <button type="button" class="modal-tab-btn active" onclick="switchModalTab('tabProducts')">
                        <i class="fas fa-box"></i> ${lang.products_tab || 'Produk'} (${products.length})
                    </button>
                    <button type="button" class="modal-tab-btn" onclick="switchModalTab('tabPayouts')">
                        <i class="fas fa-money-bill-wave"></i> ${lang.payouts_tab || 'Riwayat Payout'} (${payouts.length})
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

