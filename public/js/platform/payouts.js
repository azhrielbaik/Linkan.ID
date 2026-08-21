// Platform Admin Payouts Management Scripts

function showRejectModal(id, sellerName, amount) {
    const config = window.PlatformPayoutsConfig || {};
    const baseUrl = config.payoutsBaseUrl || '/platform-admin/payouts';

    const form = document.getElementById('rejectPayoutForm');
    const nameElem = document.getElementById('modalSellerName');
    const amountElem = document.getElementById('modalPayoutAmount');
    const modal = document.getElementById('rejectPayoutModal');

    if (form) form.action = `${baseUrl}/${id}/reject`;
    if (nameElem) nameElem.textContent = sellerName;
    if (amountElem) amountElem.textContent = amount;
    if (modal) modal.classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeRejectModal() {
    const modal = document.getElementById('rejectPayoutModal');
    if (modal) modal.classList.remove('show');
    document.body.style.overflow = 'auto';
}

function showReasonModal(reason, processedAt) {
    const reasonElem = document.getElementById('reasonContent');
    const dateElem = document.getElementById('reasonProcessedAt');
    const modal = document.getElementById('reasonDetailModal');

    if (reasonElem) reasonElem.textContent = reason;
    if (dateElem) dateElem.textContent = processedAt;
    if (modal) modal.classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeReasonModal() {
    const modal = document.getElementById('reasonDetailModal');
    if (modal) modal.classList.remove('show');
    document.body.style.overflow = 'auto';
}

window.addEventListener('click', function(event) {
    if (event.target.classList.contains('modal')) {
        closeRejectModal();
        closeReasonModal();
    }
});

function confirmApprovePayout(form, sellerName, amount) {
    const config = window.PlatformPayoutsConfig || {};
    const approveText = config.approveText || 'Setujui';

    if (typeof showConfirmModal === 'function') {
        showConfirmModal({
            title: 'Setujui Transfer Payout?',
            text: `Apakah Anda yakin menyetujui transfer penarikan dana sebesar ${amount} untuk ${sellerName}?`,
            icon: 'question',
            confirmText: `<i class="fas fa-check"></i> ${approveText}`,
            onConfirm: () => {
                form.submit();
            }
        });
    } else {
        if (confirm(`Apakah Anda yakin menyetujui transfer penarikan dana sebesar ${amount} untuk ${sellerName}?`)) {
            form.submit();
        }
    }
}
