// Platform Admin Disputes & Refund Management Scripts

let activeDisputeData = null;

/**
 * Escape HTML to prevent XSS.
 */
function escapeHtml(text) {
    if (!text) return '';
    return String(text)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

/**
 * Buka modal detail investigasi sengketa.
 */
function openDisputeDetailModal(disputeId) {
    const modal = document.getElementById('disputeDetailModal');
    if (!modal) return;

    // Tampilkan modal dan kunci scroll body
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';

    // Coba baca data JSON yang sudah di-embed di halaman
    const scriptEl = document.getElementById('dsp-data-' + disputeId);
    if (scriptEl) {
        try {
            const data = JSON.parse(scriptEl.textContent);
            renderDisputeDetail(data);
            return;
        } catch (e) {
            console.error('Gagal parse embedded data sengketa:', e);
        }
    }

    // Fallback: Fetch via AJAX jika script JSON tidak ada
    renderDisputeSkeleton();
    const config = window.PlatformDisputesConfig || {};
    const baseUrl = config.baseUrl || '/platform-admin/disputes';

    fetch(`${baseUrl}/${disputeId}`, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => {
        if (!res.ok) throw new Error('Network response was not ok');
        return res.json();
    })
    .then(data => {
        if (data.success && data.dispute) {
            renderDisputeDetail(data.dispute);
        } else {
            showDisputeError('Data sengketa tidak ditemukan atau gagal dimuat.');
        }
    })
    .catch(err => {
        console.error('Gagal memuat detail sengketa:', err);
        showDisputeError('Terjadi kesalahan saat memuat data sengketa.');
    });
}

/**
 * Tutup modal detail investigasi.
 */
function closeDisputeDetailModal() {
    const modal = document.getElementById('disputeDetailModal');
    if (modal) {
        modal.classList.remove('show');
    }
    // Kembalikan overflow body hanya jika modal confirm tidak terbuka
    const confirmRefund = document.getElementById('modalApproveRefund');
    const confirmReject = document.getElementById('modalRejectDispute');
    const isAnyConfirmOpen = (confirmRefund && confirmRefund.classList.contains('show')) ||
                             (confirmReject && confirmReject.classList.contains('show'));
    if (!isAnyConfirmOpen) {
        document.body.style.overflow = '';
    }
}

/**
 * Tampilkan state skeleton saat loading AJAX.
 */
function renderDisputeSkeleton() {
    const headerTitle = document.getElementById('dspModalCode');
    const headerBadge = document.getElementById('dspModalBadge');
    const bodyContent = document.getElementById('dspModalBodyContent');

    if (headerTitle) headerTitle.textContent = 'Memuat Kasus...';
    if (headerBadge) headerBadge.innerHTML = '';
    if (bodyContent) {
        bodyContent.innerHTML = `
            <div class="dsp-loading-skeleton">
                <div class="dsp-skeleton-bar" style="width: 45%; height: 24px; margin-bottom: 20px;"></div>
                <div class="dsp-skeleton-bar" style="width: 100%; height: 80px; margin-bottom: 16px;"></div>
                <div class="dsp-skeleton-bar" style="width: 80%; height: 20px; margin-bottom: 12px;"></div>
                <div class="dsp-skeleton-bar" style="width: 60%; height: 20px; margin-bottom: 24px;"></div>
                <div class="dsp-skeleton-bar" style="width: 100%; height: 120px;"></div>
            </div>
        `;
    }
}

/**
 * Tampilkan pesan error jika data sengketa gagal diambil.
 */
function showDisputeError(message) {
    const bodyContent = document.getElementById('dspModalBodyContent');
    if (bodyContent) {
        bodyContent.innerHTML = `
            <div style="padding: 40px 20px; text-align: center; color: #dc2626;">
                <i class="fas fa-triangle-exclamation" style="font-size: 36px; margin-bottom: 12px;"></i>
                <div style="font-weight: 700; font-size: 15px;">${escapeHtml(message)}</div>
                <button type="button" class="btn-dsp-action" onclick="closeDisputeDetailModal()" style="margin-top: 16px;">
                    Tutup
                </button>
            </div>
        `;
    }
}

/**
 * Render data sengketa ke dalam popup modal.
 */
function renderDisputeDetail(d) {
    activeDisputeData = d;

    // 1. Header
    const codeElem = document.getElementById('dspModalCode');
    const badgeElem = document.getElementById('dspModalBadge');
    if (codeElem) codeElem.textContent = 'Kasus ' + (d.dispute_code || '-');
    if (badgeElem) {
        badgeElem.innerHTML = `<span class="${d.status_badge_class}">${escapeHtml(d.status_label)}</span>`;
    }

    // 2. Evidence Content
    let evidenceHtml = '';
    if (d.evidence_file) {
        const ext = d.evidence_file_ext || '';
        const isImage = ['jpg', 'jpeg', 'png', 'webp', 'gif'].includes(ext);

        if (isImage) {
            evidenceHtml = `
                <div class="dsp-evidence-box">
                    <a href="${escapeHtml(d.evidence_file)}" target="_blank" title="Klik untuk memperbesar gambar">
                        <img src="${escapeHtml(d.evidence_file)}" alt="Bukti Sengketa" class="dsp-evidence-img">
                    </a>
                    <div style="margin-top: 8px; font-size: 11px; color: #64748b;">
                        <i class="fas fa-up-right-from-square"></i> Klik gambar untuk melihat dalam ukuran penuh
                    </div>
                </div>
            `;
        } else {
            evidenceHtml = `
                <div style="padding: 14px; background: #f1f5f9; border-radius: 10px; display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-file-pdf" style="font-size: 24px; color: #dc2626;"></i>
                        <div>
                            <div style="font-weight: 700; color: #0f172a; font-size: 13px;">Dokumen Bukti Pendukung</div>
                            <div style="font-size: 11px; color: #64748b;">Format berkas resmi (.${escapeHtml(ext)})</div>
                        </div>
                    </div>
                    <a href="${escapeHtml(d.evidence_file)}" target="_blank" class="btn-dsp-action btn-dsp-detail">
                        <i class="fas fa-download"></i> Buka Berkas
                    </a>
                </div>
            `;
        }
    } else {
        evidenceHtml = `
            <div style="padding: 12px; background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 8px; color: #94a3b8; font-size: 12px; text-align: center;">
                Pembeli tidak melampirkan file berkas bukti pendukung.
            </div>
        `;
    }

    // 3. Admin Decision Card Content
    let decisionHtml = '';
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    if (d.status === 'pending' || d.status === 'under_review') {
        let reviewBtnHtml = '';
        if (d.status === 'pending') {
            reviewBtnHtml = `
                <form action="${escapeHtml(d.review_url)}" method="POST" style="margin-bottom: 14px;">
                    <input type="hidden" name="_token" value="${csrfToken}">
                    <button type="submit" style="width: 100%; padding: 10px 14px; background: #eff6ff; border: 1px solid #bfdbfe; color: #1d4ed8; border-radius: 9px; font-weight: 700; font-size: 12.5px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 7px;">
                        <i class="fas fa-magnifying-glass"></i> Tandai "Sedang Diinvestigasi"
                    </button>
                </form>
            `;
        }

        decisionHtml = `
            <div style="background: #fff7ed; border: 1.5px solid #fed7aa; border-radius: 12px; padding: 14px; margin-bottom: 16px;">
                <div style="display: flex; align-items: center; gap: 8px; font-weight: 800; color: #ea580c; font-size: 12.5px; margin-bottom: 4px;">
                    <i class="fas fa-lock"></i> Saldo Seller Sedang Ditahan (Freeze)
                </div>
                <div style="font-size: 12px; color: #7c2d12; line-height: 1.45;">
                    Dana sebesar <strong>${escapeHtml(d.amount_formatted)}</strong> saat ini dikunci dari saldo penarikan seller hingga ada keputusan resmi admin.
                </div>
            </div>

            ${reviewBtnHtml}

            <div style="display: flex; flex-direction: column; gap: 10px;">
                <button type="button" onclick="triggerRefundFromDetail()" style="width: 100%; padding: 12px 16px; background: linear-gradient(135deg, #059669 0%, #047857 100%); color: #ffffff; border: none; border-radius: 10px; font-weight: 700; font-size: 13px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 2px 8px rgba(5, 150, 105, 0.25);">
                    <i class="fas fa-rotate-left"></i> Setujui & Terbitkan Refund
                </button>

                <button type="button" onclick="triggerRejectFromDetail()" style="width: 100%; padding: 10px 16px; background: #ffffff; color: #dc2626; border: 1.5px solid #fca5a5; border-radius: 10px; font-weight: 700; font-size: 12.5px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
                    <i class="fas fa-times-circle"></i> Tolak Sengketa (Lepas Freeze)
                </button>
            </div>
        `;
    } else if (d.status === 'resolved_refunded') {
        decisionHtml = `
            <div style="background: #ecfdf5; border: 1.5px solid #a7f3d0; border-radius: 12px; padding: 16px;">
                <div style="display: flex; align-items: center; gap: 8px; font-weight: 800; color: #047857; font-size: 13.5px; margin-bottom: 6px;">
                    <i class="fas fa-circle-check"></i> Refund Berhasil Diterbitkan
                </div>
                <div style="font-size: 12.5px; color: #065f46; margin-bottom: 4px;">
                    Ref Transfer: <strong style="font-family: monospace;">${escapeHtml(d.refund_reference || '-')}</strong>
                </div>
                <div style="font-size: 11.5px; color: #047857;">
                    Waktu Eksekusi: ${escapeHtml(d.resolved_at_formatted || '-')}
                </div>
                ${d.admin_notes ? `
                    <div style="margin-top: 10px; padding-top: 10px; border-top: 1px dashed #a7f3d0; font-size: 12px; color: #065f46;">
                        <strong>Catatan Admin:</strong> ${escapeHtml(d.admin_notes)}
                    </div>
                ` : ''}
            </div>
        `;
    } else if (d.status === 'resolved_rejected') {
        decisionHtml = `
            <div style="background: #f8fafc; border: 1.5px solid #cbd5e1; border-radius: 12px; padding: 16px;">
                <div style="display: flex; align-items: center; gap: 8px; font-weight: 800; color: #475569; font-size: 13.5px; margin-bottom: 6px;">
                    <i class="fas fa-circle-xmark"></i> Sengketa Ditolak Admin
                </div>
                <div style="font-size: 12.5px; color: #64748b; margin-bottom: 4px;">
                    Penahanan saldo seller telah dilepaskan dan transaksi dinyatakan sah.
                </div>
                ${d.resolved_at_formatted ? `
                    <div style="font-size: 11.5px; color: #64748b; margin-bottom: 4px;">
                        Waktu Keputusan: ${escapeHtml(d.resolved_at_formatted)}
                    </div>
                ` : ''}
                ${d.admin_notes ? `
                    <div style="margin-top: 10px; padding-top: 10px; border-top: 1px dashed #cbd5e1; font-size: 12px; color: #334155;">
                        <strong>Alasan Penolakan:</strong> ${escapeHtml(d.admin_notes)}
                    </div>
                ` : ''}
            </div>
        `;
    }

    // Buyer Phone WhatsApp link
    let buyerPhoneHtml = '';
    if (d.buyer_phone) {
        const cleanPhone = String(d.buyer_phone).replace(/\D/g, '');
        buyerPhoneHtml = `
            <div style="font-size: 11.5px; color: #15803d; margin-top: 4px;">
                <a href="https://wa.me/${cleanPhone}" target="_blank" style="color: #15803d; text-decoration: none; display: inline-flex; align-items: center; gap: 5px;">
                    <i class="fab fa-whatsapp"></i> ${escapeHtml(d.buyer_phone)}
                </a>
            </div>
        `;
    }

    // 4. Construct Complete Body
    const bodyContent = document.getElementById('dspModalBodyContent');
    if (bodyContent) {
        bodyContent.innerHTML = `
            <div class="dsp-detail-grid">
                <!-- Panel Kiri: Investigasi & Bukti -->
                <div>
                    <!-- Card 1: Laporan Kendala Pembeli -->
                    <div class="dsp-detail-card">
                        <div class="dsp-detail-header">
                            <h3><i class="fas fa-comment-dots" style="color: #ea580c;"></i> Laporan Kendala Pembeli</h3>
                            <span class="dsp-reason-tag" style="font-size: 12px; padding: 3px 10px;">${escapeHtml(d.reason_label)}</span>
                        </div>

                        <div style="background: #f8fafc; border-left: 4px solid #ea580c; border-radius: 4px 10px 10px 4px; padding: 16px; margin-bottom: 18px;">
                            <div style="font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 6px;">Uraian Masalah:</div>
                            <p style="margin: 0; font-size: 13.5px; line-height: 1.6; color: #1e293b; white-space: pre-line;">${escapeHtml(d.description || '-')}</p>
                        </div>

                        <div>
                            <div style="font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 8px;">
                                <i class="fas fa-paperclip"></i> Bukti Pendukung yang Diunggah Pembeli:
                            </div>
                            ${evidenceHtml}
                        </div>
                    </div>

                    <!-- Card 2: Informasi Pesanan & Transaksi -->
                    <div class="dsp-detail-card">
                        <div class="dsp-detail-header">
                            <h3><i class="fas fa-receipt" style="color: #2563eb;"></i> Data Transaksi Terkait</h3>
                            <span style="font-family: monospace; font-size: 12px; font-weight: 700; color: #2563eb;">${escapeHtml(d.order_id)}</span>
                        </div>

                        <div class="dsp-info-row">
                            <span class="dsp-info-label">Produk Digital</span>
                            <span class="dsp-info-val">${escapeHtml(d.product_title)}</span>
                        </div>
                        <div class="dsp-info-row">
                            <span class="dsp-info-label">Harga Total</span>
                            <span class="dsp-info-val" style="color: #ea580c; font-size: 14px; font-weight: 700;">${escapeHtml(d.amount_formatted)}</span>
                        </div>
                        <div class="dsp-info-row">
                            <span class="dsp-info-label">Metode Pembayaran</span>
                            <span class="dsp-info-val">${escapeHtml(d.transaction_payment_method)}</span>
                        </div>
                        <div class="dsp-info-row">
                            <span class="dsp-info-label">Waktu Transaksi</span>
                            <span class="dsp-info-val">${escapeHtml(d.transaction_created_at)}</span>
                        </div>
                        <div class="dsp-info-row">
                            <span class="dsp-info-label">Status Transaksi</span>
                            <span class="dsp-info-val">
                                <span style="text-transform: uppercase; font-size: 11px; padding: 2px 7px; border-radius: 4px; background: #f1f5f9; font-weight: 700;">
                                    ${escapeHtml(d.transaction_status)}
                                </span>
                            </span>
                        </div>
                    </div>

                    <!-- Card 3: Pihak Terkait (Buyer & Seller) -->
                    <div class="dsp-detail-card" style="margin-bottom: 0;">
                        <div class="dsp-detail-header">
                            <h3><i class="fas fa-users" style="color: #64748b;"></i> Pihak Terkait (Buyer &amp; Seller)</h3>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px;">
                                <div style="font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 6px;">
                                    <i class="fas fa-user"></i> Pembeli (Pelapor)
                                </div>
                                <div style="font-weight: 800; color: #0f172a; font-size: 13.5px;">${escapeHtml(d.buyer_name)}</div>
                                <div style="font-size: 12px; color: #64748b; margin-top: 2px;">${escapeHtml(d.buyer_email)}</div>
                                ${buyerPhoneHtml}
                            </div>

                            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px;">
                                <div style="font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 6px;">
                                    <i class="fas fa-store"></i> Seller (Pemilik Produk)
                                </div>
                                <div style="font-weight: 800; color: #0f172a; font-size: 13.5px;">${escapeHtml(d.seller_name)}</div>
                                <div style="font-size: 12px; color: #64748b; margin-top: 2px;">${escapeHtml(d.seller_email)}</div>
                                <div style="font-size: 11.5px; color: #64748b; margin-top: 4px;">
                                    Username: <span style="font-weight: 700;">${escapeHtml(d.seller_username)}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panel Kanan: Rekening Refund & Keputusan Admin -->
                <div>
                    <!-- Card: Rekening Tujuan Refund -->
                    <div class="dsp-detail-card">
                        <div class="dsp-detail-header">
                            <h3><i class="fas fa-building-columns" style="color: #059669;"></i> Rekening Tujuan Refund</h3>
                        </div>

                        <div class="dsp-bank-box">
                            <span class="dsp-bank-badge">${escapeHtml(d.refund_bank_name)}</span>
                            <div class="dsp-acc-number">${escapeHtml(d.refund_account_number)}</div>
                            <div class="dsp-acc-name">Atas Nama: <strong>${escapeHtml(d.refund_account_name)}</strong></div>
                        </div>

                        <div style="font-size: 12px; color: #64748b; line-height: 1.45;">
                            <i class="fas fa-info-circle" style="color: #2563eb;"></i>
                            Jika sengketa disetujui, kirimkan dana refund sebesar <strong>${escapeHtml(d.amount_formatted)}</strong> ke rekening di atas, lalu masukkan nomor referensi transfer.
                        </div>
                    </div>

                    <!-- Card: Keputusan Admin -->
                    <div class="dsp-detail-card" style="margin-bottom: 0;">
                        <div class="dsp-detail-header">
                            <h3><i class="fas fa-gavel" style="color: #0f172a;"></i> Keputusan Admin</h3>
                        </div>

                        ${decisionHtml}
                    </div>
                </div>
            </div>
        `;
    }
}

/**
 * Trigger konfirmasi refund dari dalam modal detail.
 */
function triggerRefundFromDetail() {
    if (!activeDisputeData) return;
    openRefundModal(activeDisputeData);
}

/**
 * Trigger konfirmasi tolak sengketa dari dalam modal detail.
 */
function triggerRejectFromDetail() {
    if (!activeDisputeData) return;
    openRejectModal(activeDisputeData);
}

/**
 * Buka modal konfirmasi Approve Refund.
 */
function openRefundModal(dataOrId) {
    let d = dataOrId;
    if (typeof dataOrId === 'number' || typeof dataOrId === 'string') {
        const scriptEl = document.getElementById('dsp-data-' + dataOrId);
        if (scriptEl) {
            try { d = JSON.parse(scriptEl.textContent); } catch (e) {}
        }
    }
    if (!d) return;

    const modal = document.getElementById('modalApproveRefund');
    const form = document.getElementById('formApproveRefund');
    const summaryElem = document.getElementById('modalRefundSummary');

    if (form) {
        form.action = d.refund_url || `/platform-admin/disputes/${d.id}/refund`;
    }

    if (summaryElem) {
        summaryElem.innerHTML = `Anda akan menerbitkan pengembalian dana sebesar <strong>${escapeHtml(d.amount_formatted)}</strong> ke <strong>${escapeHtml(d.buyer_name)}</strong> (${escapeHtml(d.refund_bank_name)} - ${escapeHtml(d.refund_account_number)}).`;
    }

    if (modal) {
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}

/**
 * Tutup modal konfirmasi Approve Refund.
 */
function closeRefundModal() {
    const modal = document.getElementById('modalApproveRefund');
    if (modal) {
        modal.classList.remove('show');
    }
    const detailModal = document.getElementById('disputeDetailModal');
    if (!detailModal || !detailModal.classList.contains('show')) {
        document.body.style.overflow = '';
    }
}

/**
 * Buka modal konfirmasi Tolak Sengketa.
 */
function openRejectModal(dataOrId) {
    let d = dataOrId;
    if (typeof dataOrId === 'number' || typeof dataOrId === 'string') {
        const scriptEl = document.getElementById('dsp-data-' + dataOrId);
        if (scriptEl) {
            try { d = JSON.parse(scriptEl.textContent); } catch (e) {}
        }
    }
    if (!d) return;

    const modal = document.getElementById('modalRejectDispute');
    const form = document.getElementById('formRejectDispute');
    const summaryElem = document.getElementById('modalRejectSummary');

    if (form) {
        form.action = d.reject_url || `/platform-admin/disputes/${d.id}/reject`;
    }

    if (summaryElem) {
        summaryElem.innerHTML = `Menolak sengketa akan melepaskan penahanan saldo seller sebesar <strong>${escapeHtml(d.amount_formatted)}</strong> dan menutup kasus ini.`;
    }

    if (modal) {
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}

/**
 * Tutup modal konfirmasi Tolak Sengketa.
 */
function closeRejectModal() {
    const modal = document.getElementById('modalRejectDispute');
    if (modal) {
        modal.classList.remove('show');
    }
    const detailModal = document.getElementById('disputeDetailModal');
    if (!detailModal || !detailModal.classList.contains('show')) {
        document.body.style.overflow = '';
    }
}

// Global Keyboard Escape Listener
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const confirmRefund = document.getElementById('modalApproveRefund');
        if (confirmRefund && confirmRefund.classList.contains('show')) {
            closeRefundModal();
            return;
        }

        const confirmReject = document.getElementById('modalRejectDispute');
        if (confirmReject && confirmReject.classList.contains('show')) {
            closeRejectModal();
            return;
        }

        const detailModal = document.getElementById('disputeDetailModal');
        if (detailModal && detailModal.classList.contains('show')) {
            closeDisputeDetailModal();
        }
    }
});
