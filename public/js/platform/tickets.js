// Platform Admin Support Tickets Management Scripts

let currentActiveTicket = null;

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
 * Buka modal pop-up respon tiket.
 */
function openTicketModal(ticketId) {
    const modal = document.getElementById('ticketDetailModal');
    if (!modal) return;

    modal.classList.add('show');
    document.body.style.overflow = 'hidden';

    // 1. Coba baca data JSON yang sudah di-embed di halaman (instan 0ms)
    const scriptEl = document.getElementById('ticket-data-' + ticketId);
    if (scriptEl) {
        try {
            const data = JSON.parse(scriptEl.textContent);
            renderTicketModal(data);
            return;
        } catch (e) {
            console.error('Gagal parse embedded data tiket:', e);
        }
    }

    // 2. Fallback fetch via AJAX jika data embedded tidak tersedia
    renderTicketSkeleton();

    const config = window.PlatformTicketsConfig || {};
    const baseUrl = config.baseUrl || '/platform-admin/tickets';

    fetch(`${baseUrl}/${ticketId}`, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => {
        if (!res.ok) throw new Error('Gagal mengambil data tiket');
        return res.json();
    })
    .then(data => {
        if (data.success && data.ticket) {
            renderTicketModal(data.ticket);
        } else {
            showTicketModalError('Data tiket tidak ditemukan.');
        }
    })
    .catch(err => {
        console.error('Error memuat tiket:', err);
        showTicketModalError('Terjadi kesalahan koneksi saat memuat tiket.');
    });
}

/**
 * Tutup modal pop-up respon tiket.
 */
function closeTicketModal() {
    const modal = document.getElementById('ticketDetailModal');
    if (modal) {
        modal.classList.remove('show');
    }

    const attachModal = document.getElementById('imageAttachmentModal');
    const sellerModal = document.getElementById('sellerModal');
    const isOtherOpen = (attachModal && attachModal.classList.contains('show')) ||
                        (sellerModal && sellerModal.classList.contains('show'));

    if (!isOtherOpen) {
        document.body.style.overflow = '';
    }
}

/**
 * Tampilkan skeleton loader.
 */
function renderTicketSkeleton() {
    const titleCode = document.getElementById('ticketModalCode');
    const badges = document.getElementById('ticketModalBadges');
    const bodyContent = document.getElementById('ticketModalBodyContent');

    if (titleCode) titleCode.textContent = 'Memuat Tiket...';
    if (badges) badges.innerHTML = '';
    if (bodyContent) {
        bodyContent.innerHTML = `
            <div style="padding: 24px;">
                <div class="p-ticket-skeleton-bar" style="width: 50%; height: 24px; margin-bottom: 20px;"></div>
                <div class="p-ticket-skeleton-bar" style="width: 80%; height: 18px; margin-bottom: 24px;"></div>
                <div class="p-ticket-skeleton-bar" style="width: 100%; height: 110px; margin-bottom: 18px;"></div>
                <div class="p-ticket-skeleton-bar" style="width: 100%; height: 110px; margin-bottom: 18px;"></div>
                <div class="p-ticket-skeleton-bar" style="width: 100%; height: 140px;"></div>
            </div>
        `;
    }
}

/**
 * Tampilkan pesan error saat fetch gagal.
 */
function showTicketModalError(msg) {
    const bodyContent = document.getElementById('ticketModalBodyContent');
    if (bodyContent) {
        bodyContent.innerHTML = `
            <div style="padding: 40px 20px; text-align: center; color: #dc2626;">
                <i class="fas fa-triangle-exclamation" style="font-size: 36px; margin-bottom: 12px;"></i>
                <div style="font-weight: 700; font-size: 15px;">${escapeHtml(msg)}</div>
                <button type="button" class="btn-ticket-back" onclick="closeTicketModal()" style="margin-top: 16px;">
                    Tutup
                </button>
            </div>
        `;
    }
}

/**
 * Render seluruh thread dan form respon tiket ke dalam pop-up modal.
 */
function renderTicketModal(t) {
    currentActiveTicket = t;

    // 1. Header Badges & Code
    const titleCode = document.getElementById('ticketModalCode');
    const badges = document.getElementById('ticketModalBadges');
    if (titleCode) titleCode.textContent = 'Tiket #' + t.ticket_code;
    if (badges) {
        badges.innerHTML = `
            <span class="ticket-code-badge">#${escapeHtml(t.ticket_code)}</span>
            <span class="badge-status ${escapeHtml(t.status_badge_class)}">
                <i class="fas fa-circle" style="font-size: 6px;"></i> ${escapeHtml(t.status_label)}
            </span>
            <span class="badge-priority ${escapeHtml(t.priority_badge_class)}">
                <i class="fas fa-flag" style="font-size: 9px;"></i> ${escapeHtml(t.priority)}
            </span>
        `;
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    // 2. Replies Stream Construction
    let repliesHtml = '';
    if (t.replies && t.replies.length > 0) {
        t.replies.forEach(r => {
            if (r.is_admin_reply) {
                repliesHtml += `
                    <div class="p-chat-item">
                        <div class="p-chat-avatar p-chat-avatar-admin">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="p-chat-bubble p-chat-bubble-admin">
                            <div class="p-chat-header">
                                <div class="p-chat-sender">
                                    <span class="p-chat-name">${escapeHtml(r.sender_name)}</span>
                                    <span class="p-chat-role p-chat-role-admin">Admin Platform</span>
                                </div>
                                <span class="p-chat-time">
                                    <i class="far fa-clock"></i> ${escapeHtml(r.created_at_formatted)}
                                </span>
                            </div>
                            <div class="p-chat-text">${escapeHtml(r.message).replace(/\n/g, '<br>')}</div>
                            ${r.attachment_url ? `
                                <div class="bubble-attachment">
                                    <button type="button" class="btn-attachment-preview" onclick="openAttachmentModal('${escapeHtml(r.attachment_url)}', 'Lampiran Balasan Admin')">
                                        <div class="btn-attachment-icon"><i class="fas fa-image"></i></div>
                                        <div class="btn-attachment-info">
                                            <span class="btn-attachment-title">Lampiran Gambar</span>
                                            <span class="btn-attachment-sub">Klik untuk melihat foto di pop-up</span>
                                        </div>
                                        <i class="fas fa-expand-alt btn-attachment-action"></i>
                                    </button>
                                </div>
                            ` : ''}
                        </div>
                    </div>
                `;
            } else {
                repliesHtml += `
                    <div class="p-chat-item">
                        <div class="p-chat-avatar p-chat-avatar-seller">
                            ${escapeHtml(r.sender_initials)}
                        </div>
                        <div class="p-chat-bubble p-chat-bubble-seller">
                            <div class="p-chat-header">
                                <div class="p-chat-sender">
                                    <span class="p-chat-name">${escapeHtml(r.sender_name)}</span>
                                    <span class="p-chat-role p-chat-role-seller">Seller</span>
                                </div>
                                <span class="p-chat-time">
                                    <i class="far fa-clock"></i> ${escapeHtml(r.created_at_formatted)}
                                </span>
                            </div>
                            <div class="p-chat-text">${escapeHtml(r.message).replace(/\n/g, '<br>')}</div>
                            ${r.attachment_url ? `
                                <div class="bubble-attachment">
                                    <button type="button" class="btn-attachment-preview" onclick="openAttachmentModal('${escapeHtml(r.attachment_url)}', 'Lampiran Gambar')">
                                        <div class="btn-attachment-icon"><i class="fas fa-image"></i></div>
                                        <div class="btn-attachment-info">
                                            <span class="btn-attachment-title">Lampiran Gambar</span>
                                            <span class="btn-attachment-sub">Klik untuk melihat foto di pop-up</span>
                                        </div>
                                        <i class="fas fa-expand-alt btn-attachment-action"></i>
                                    </button>
                                </div>
                            ` : ''}
                        </div>
                    </div>
                `;
            }
        });
    }

    // 3. Construct Complete Pop-up Body
    const bodyContent = document.getElementById('ticketModalBodyContent');
    if (bodyContent) {
        bodyContent.innerHTML = `
            <!-- Ticket Subject Header Card -->
            <div class="ticket-header-card" style="margin-bottom: 20px;">
                <h2 class="ticket-subject-title">${escapeHtml(t.subject)}</h2>
                <div class="ticket-meta-pills">
                    <span class="ticket-meta-pill">
                        <i class="fas fa-folder-open" style="color: #ed842c;"></i>
                        <span>Kategori: <strong>${escapeHtml(t.category_label)}</strong></span>
                    </span>
                    <span class="ticket-meta-pill">
                        <i class="far fa-clock"></i>
                        <span>Diajukan: <strong>${escapeHtml(t.created_at_formatted)}</strong></span>
                    </span>
                    <span class="ticket-meta-pill">
                        <i class="far fa-user"></i>
                        <span>Pengaju: <strong>${escapeHtml(t.user.name)}</strong></span>
                    </span>
                </div>
            </div>

            <!-- Two Column Layout Inside Modal -->
            <div class="p-thread-grid">
                <!-- Main Chat Conversation & Reply -->
                <div class="p-thread-main">
                    <div class="p-chat-stream">
                        <!-- Initial Message from Seller -->
                        <div class="p-chat-item">
                            <div class="p-chat-avatar p-chat-avatar-seller">
                                ${escapeHtml(t.user.initials)}
                            </div>
                            <div class="p-chat-bubble p-chat-bubble-seller">
                                <div class="p-chat-header">
                                    <div class="p-chat-sender">
                                        <span class="p-chat-name">${escapeHtml(t.user.name)}</span>
                                        <span class="p-chat-role p-chat-role-seller">Seller</span>
                                    </div>
                                    <span class="p-chat-time">
                                        <i class="far fa-clock"></i> ${escapeHtml(t.created_at_formatted)}
                                    </span>
                                </div>
                                <div class="p-chat-text">
                                    ${escapeHtml(t.message).replace(/\n/g, '<br>')}
                                </div>
                            </div>
                        </div>

                        ${repliesHtml}
                    </div>

                    <!-- Admin Reply Box Form -->
                    <div class="p-reply-card">
                        <form action="${escapeHtml(t.reply_url)}" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="${csrfToken}">

                            <div class="p-reply-header">
                                <div class="p-reply-title">
                                    <i class="fas fa-reply" style="color: #ed842c;"></i>
                                    <span>Tulis Balasan Resmi Platform Admin</span>
                                </div>
                                <div class="p-reply-status-wrap">
                                    <label for="modal_status_select">Ubah Status:</label>
                                    <select name="status" id="modal_status_select" class="p-filter-select" style="min-width: 160px;">
                                        <option value="in_progress" ${t.status === 'in_progress' ? 'selected' : ''}>Sedang Ditangani</option>
                                        <option value="resolved" ${t.status === 'resolved' ? 'selected' : ''}>Selesai</option>
                                        <option value="open" ${t.status === 'open' ? 'selected' : ''}>Menunggu Respon</option>
                                        <option value="closed" ${t.status === 'closed' ? 'selected' : ''}>Ditutup</option>
                                    </select>
                                </div>
                            </div>

                            <textarea name="message" class="p-reply-textarea" placeholder="Tuliskan jawaban atau arahan resmi untuk seller di sini..." required></textarea>

                            <div class="p-reply-actions">
                                <div>
                                    <label for="admin_modal_reply_attachment" class="btn-upload-clean">
                                        <i class="fas fa-paperclip"></i>
                                        <span>Unggah Gambar / Tangkapan Layar</span>
                                    </label>
                                    <input type="file" name="attachment" id="admin_modal_reply_attachment" style="display: none;" accept="image/*" onchange="previewAdminAttachmentName(this)">
                                    <span id="adminModalAttachmentFileName" style="font-size: 11.5px; color: #16a34a; margin-left: 8px; font-weight: 600;"></span>
                                </div>

                                <button type="submit" class="btn-send-admin-reply">
                                    <i class="fas fa-paper-plane"></i>
                                    <span>Kirim Balasan &amp; Notifikasi Email</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Sidebar Inside Modal -->
                <div class="p-thread-sidebar">
                    <!-- Seller Card -->
                    <div class="p-sidebar-section">
                        <div class="p-sidebar-label">
                            <i class="fas fa-user-circle"></i>
                            <span>Profil Pengaju Tiket</span>
                        </div>
                        <div class="p-seller-profile-row">
                            <div class="p-seller-avatar">
                                ${escapeHtml(t.user.initials)}
                            </div>
                            <div class="p-seller-info-col">
                                <div class="p-seller-name">${escapeHtml(t.user.name)}</div>
                                <div class="p-seller-email">${escapeHtml(t.user.email)}</div>
                            </div>
                        </div>

                        ${t.user.id ? `
                            <div style="display: flex; flex-direction: column; gap: 6px;">
                                <a href="/platform-admin/users?search=${encodeURIComponent(t.user.email || t.user.name)}" target="_blank" class="link-seller-mgmt">
                                    <i class="fas fa-external-link-alt"></i>
                                    <span>Buka di Manajemen User &rarr;</span>
                                </a>
                            </div>
                        ` : ''}
                    </div>

                    <!-- Quick Status & Priority Updater Form -->
                    <div class="p-sidebar-section">
                        <div class="p-sidebar-label">
                            <i class="fas fa-sliders-h"></i>
                            <span>Pengaturan Tiket Cepat</span>
                        </div>

                        <form action="${escapeHtml(t.status_url)}" method="POST">
                            <input type="hidden" name="_token" value="${csrfToken}">

                            <div class="p-sidebar-field">
                                <label>Status Tiket</label>
                                <select name="status" class="p-filter-select" style="width: 100%; box-sizing: border-box;">
                                    <option value="open" ${t.status === 'open' ? 'selected' : ''}>Menunggu Respon</option>
                                    <option value="in_progress" ${t.status === 'in_progress' ? 'selected' : ''}>Sedang Ditangani</option>
                                    <option value="resolved" ${t.status === 'resolved' ? 'selected' : ''}>Selesai</option>
                                    <option value="closed" ${t.status === 'closed' ? 'selected' : ''}>Ditutup</option>
                                </select>
                            </div>

                            <div class="p-sidebar-field" style="margin-bottom: 14px;">
                                <label>Tingkat Prioritas</label>
                                <select name="priority" class="p-filter-select" style="width: 100%; box-sizing: border-box;">
                                    <option value="urgent" ${t.priority === 'urgent' ? 'selected' : ''}>Urgent</option>
                                    <option value="high" ${t.priority === 'high' ? 'selected' : ''}>High</option>
                                    <option value="medium" ${t.priority === 'medium' ? 'selected' : ''}>Medium</option>
                                    <option value="low" ${t.priority === 'low' ? 'selected' : ''}>Low</option>
                                </select>
                            </div>

                            <button type="submit" class="btn-update-sidebar">
                                <i class="fas fa-sync-alt"></i>
                                <span>Perbarui Status &amp; Prioritas</span>
                            </button>
                        </form>
                    </div>

                    <!-- Meta Timeline -->
                    <div class="p-sidebar-section">
                        <div class="p-sidebar-label">
                            <i class="fas fa-history"></i>
                            <span>Riwayat Aktivitas</span>
                        </div>
                        <div class="p-timeline-list">
                            <div class="p-timeline-item">
                                <span>Tiket Dibuat</span>
                                <strong>${escapeHtml(t.created_at_formatted)}</strong>
                            </div>
                            <div class="p-timeline-item">
                                <span>Aktivitas Terakhir</span>
                                <strong>${escapeHtml(t.last_replied_formatted)}</strong>
                            </div>
                            <div class="p-timeline-item">
                                <span>Total Percakapan</span>
                                <strong>${escapeHtml(t.total_replies_count)} balasan</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Notice Banner -->
                    <div class="p-sidebar-notice">
                        <i class="fas fa-info-circle"></i>
                        <div>Seller akan menerima notifikasi email resmi setiap kali admin mengirim balasan.</div>
                    </div>
                </div>
            </div>
        `;
    }
}

/**
 * Pratinjau nama file lampiran balasan.
 */
function previewAdminAttachmentName(input) {
    const span = document.getElementById('adminModalAttachmentFileName');
    if (span && input.files && input.files[0]) {
        span.textContent = 'File: ' + input.files[0].name;
    } else if (span) {
        span.textContent = '';
    }
}

/**
 * Buka modal pratinjau lampiran gambar penuh.
 */
function openAttachmentModal(imageUrl, title) {
    const modal = document.getElementById('imageAttachmentModal');
    const img = document.getElementById('attachmentModalImg');
    const openBtn = document.getElementById('attachmentModalDownload');
    const titleEl = document.getElementById('attachmentModalTitle');

    if (img) img.src = imageUrl;
    if (openBtn) openBtn.href = imageUrl;
    if (titleEl && title) titleEl.textContent = title;

    if (modal) {
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}

/**
 * Tutup modal pratinjau lampiran gambar.
 */
function closeAttachmentModal() {
    const modal = document.getElementById('imageAttachmentModal');
    if (modal) {
        modal.classList.remove('show');
    }
    const ticketModal = document.getElementById('ticketDetailModal');
    if (!ticketModal || !ticketModal.classList.contains('show')) {
        document.body.style.overflow = '';
    }
    const img = document.getElementById('attachmentModalImg');
    if (img) img.src = '';
}

// Global Keyboard Escape Listener
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const attachModal = document.getElementById('imageAttachmentModal');
        if (attachModal && attachModal.classList.contains('show')) {
            closeAttachmentModal();
            return;
        }

        const ticketModal = document.getElementById('ticketDetailModal');
        if (ticketModal && ticketModal.classList.contains('show')) {
            closeTicketModal();
        }
    }
});
