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

function showApproveModal(id, sellerName, amount, bank, accountNumber, accountName) {
    const config = window.PlatformPayoutsConfig || {};
    const baseUrl = config.payoutsBaseUrl || '/platform-admin/payouts';

    const form = document.getElementById('approvePayoutForm');
    const nameElem = document.getElementById('approveModalSellerName');
    const amountElem = document.getElementById('approveModalPayoutAmount');
    const destElem = document.getElementById('approveModalDestination');
    const pwdInput = document.getElementById('approve_admin_password');
    const modal = document.getElementById('approvePayoutModal');

    if (form) form.action = `${baseUrl}/${id}/approve`;
    if (nameElem) nameElem.textContent = sellerName;
    if (amountElem) amountElem.textContent = amount;
    if (destElem) {
        destElem.textContent = `${bank} - ${accountNumber} (a.n ${accountName})`;
    }
    if (pwdInput) {
        pwdInput.value = '';
        setTimeout(() => pwdInput.focus(), 150);
    }
    if (modal) modal.classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeApproveModal() {
    const modal = document.getElementById('approvePayoutModal');
    if (modal) modal.classList.remove('show');
    document.body.style.overflow = 'auto';
}

function toggleApprovePasswordVisibility() {
    const input = document.getElementById('approve_admin_password');
    const icon = document.getElementById('toggleApprovePasswordIcon');
    if (!input || !icon) return;

    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

window.addEventListener('click', function(event) {
    if (event.target.classList.contains('modal')) {
        closeRejectModal();
        closeReasonModal();
        closeApproveModal();
    }
});
