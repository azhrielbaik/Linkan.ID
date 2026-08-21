// Platform Admin Settings Scripts

function openPasswordConfirmationModal() {
    const commInput = document.getElementById('commission_percent');
    const minWInput = document.getElementById('min_withdraw');

    if (!commInput.checkValidity() || !minWInput.checkValidity()) {
        const form = document.getElementById('financialSettingsForm');
        if (form) form.reportValidity();
        return;
    }

    const summaryComm = document.getElementById('summary_commission');
    const summaryMinW = document.getElementById('summary_min_withdraw');
    const passInput = document.getElementById('modal_admin_password');
    const errBox = document.getElementById('passwordErrorMsg');
    const modal = document.getElementById('adminPasswordModal');

    if (summaryComm) summaryComm.textContent = commInput.value + '%';
    if (summaryMinW) summaryMinW.textContent = 'Rp ' + Number(minWInput.value).toLocaleString('id-ID');
    if (passInput) passInput.value = '';
    if (errBox) errBox.style.display = 'none';

    if (modal) {
        modal.classList.add('show');
        setTimeout(() => {
            if (passInput) passInput.focus();
        }, 150);
    }
}

function closePasswordConfirmationModal() {
    const modal = document.getElementById('adminPasswordModal');
    if (modal) modal.classList.remove('show');
}

function togglePasswordVisibility() {
    const passInput = document.getElementById('modal_admin_password');
    const passIcon = document.getElementById('togglePasswordIcon');
    if (passInput && passIcon) {
        if (passInput.type === 'password') {
            passInput.type = 'text';
            passIcon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            passInput.type = 'password';
            passIcon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
}

function submitFinancialSettings() {
    const passInput = document.getElementById('modal_admin_password');
    const pass = passInput ? passInput.value : '';
    const errBox = document.getElementById('passwordErrorMsg');

    if (!pass.trim()) {
        if (errBox) {
            errBox.textContent = 'Silakan masukkan password admin terlebih dahulu.';
            errBox.style.display = 'block';
        }
        if (passInput) passInput.focus();
        return;
    }

    const hiddenPass = document.getElementById('form_admin_password');
    const form = document.getElementById('financialSettingsForm');
    if (hiddenPass) hiddenPass.value = pass;
    if (form) form.submit();
}

function confirmDeleteAnnouncement(form, title) {
    const config = window.PlatformSettingsConfig || {};
    const deleteText = config.deleteText || 'Hapus';

    if (typeof showConfirmModal === 'function') {
        showConfirmModal({
            title: 'Hapus Pengumuman Siaran?',
            text: `Pengumuman "${title}" akan dihapus secara permanen dari beranda seluruh seller.`,
            icon: 'warning',
            confirmText: `<i class="fas fa-trash"></i> ${deleteText}`,
            confirmDanger: true,
            onConfirm: () => {
                form.submit();
            }
        });
    } else {
        if (confirm(`Hapus pengumuman "${title}"?`)) {
            form.submit();
        }
    }
}
