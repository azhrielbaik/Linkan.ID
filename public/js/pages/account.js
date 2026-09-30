/**
 * Account Settings Scripts
 */

function togglePasswordVisibility(fieldId, buttonElement) {
    const input = document.getElementById(fieldId);
    if (!input) return;

    const icon = buttonElement.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        if (icon) {
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        }
    } else {
        input.type = 'password';
        if (icon) {
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
}

function toggleEmailChangeForm() {
    const collapse = document.getElementById('email-change-collapse');
    const btn = document.getElementById('btn-toggle-email');
    if (!collapse) return;

    if (collapse.classList.contains('hidden')) {
        collapse.classList.remove('hidden');
        if (btn) {
            btn.innerHTML = '<i class="fas fa-times text-slate-400 text-[11px]"></i> Tutup';
        }
    } else {
        collapse.classList.add('hidden');
        if (btn) {
            btn.innerHTML = '<i class="fas fa-pen text-slate-400 text-[11px]"></i> Ganti Email';
        }
    }
}

function showDisconnectGoogleModal() {
    const modal = document.getElementById('disconnectGoogleModal');
    if (modal) {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
}

function closeDisconnectGoogleModal() {
    const modal = document.getElementById('disconnectGoogleModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }
}

function showDeletePopup() {
    const modal = document.getElementById('deleteConfirmationModal');
    if (modal) {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
}

function closeDeletePopup() {
    const modal = document.getElementById('deleteConfirmationModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }
}

// Close on backdrop overlay click
window.addEventListener('click', function(event) {
    const deleteModal = document.getElementById('deleteConfirmationModal');
    const googleModal = document.getElementById('disconnectGoogleModal');
    if (event.target === deleteModal) {
        closeDeletePopup();
    }
    if (event.target === googleModal) {
        closeDisconnectGoogleModal();
    }
});

// Close on ESC key press
window.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeDeletePopup();
        closeDisconnectGoogleModal();
    }
});
