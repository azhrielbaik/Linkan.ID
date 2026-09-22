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
    const modal = document.getElementById('financialPasswordModal');

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
    const modal = document.getElementById('financialPasswordModal');
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

// Emergency Switches Handlers
function handleEmergencyToggle(checkbox) {
    const isChecked = checkbox.checked;

    if (checkbox.id === 'toggle_freeze_payouts') {
        const card = document.getElementById('card_freeze_payouts');
        const badge = document.getElementById('badge_freeze_status');
        if (card) {
            if (isChecked) {
                card.classList.add('active-warning');
            } else {
                card.classList.remove('active-warning');
            }
        }
        if (badge) {
            badge.className = isChecked ? 'status-pill-switch pill-danger' : 'status-pill-switch pill-success';
            badge.textContent = isChecked ? 'DIBEKUKAN' : 'NORMAL';
        }
    } else if (checkbox.id === 'toggle_disable_checkout') {
        const card = document.getElementById('card_disable_checkout');
        const badge = document.getElementById('badge_checkout_status');
        if (card) {
            if (isChecked) {
                card.classList.add('active-danger');
            } else {
                card.classList.remove('active-danger');
            }
        }
        if (badge) {
            badge.className = isChecked ? 'status-pill-switch pill-danger' : 'status-pill-switch pill-success';
            badge.textContent = isChecked ? 'DINONAKTIFKAN' : 'NORMAL';
        }
    }

    // Update global status badge
    updateGlobalEmergencyStatus();
}

function updateGlobalEmergencyStatus() {
    const freezeToggle = document.getElementById('toggle_freeze_payouts');
    const checkoutToggle = document.getElementById('toggle_disable_checkout');
    const globalBadge = document.getElementById('globalEmergencyBadge');

    const isAnyActive = (freezeToggle && freezeToggle.checked) || (checkoutToggle && checkoutToggle.checked);

    if (globalBadge) {
        if (isAnyActive) {
            globalBadge.className = 'emergency-status-badge status-danger';
            globalBadge.innerHTML = '<span class="pulse-dot"></span><span>Mode Darurat Aktif</span>';
        } else {
            globalBadge.className = 'emergency-status-badge status-normal';
            globalBadge.innerHTML = '<span class="status-dot-green"></span><span>Semua Layanan Normal</span>';
        }
    }
}

function applyMessageTemplate(inputId, templateText) {
    const input = document.getElementById(inputId);
    if (input) {
        input.value = templateText;
        input.focus();
        input.style.borderColor = '#f59e0b';
        input.style.boxShadow = '0 0 0 3px rgba(245, 158, 11, 0.25)';
        setTimeout(() => {
            input.style.borderColor = '';
            input.style.boxShadow = '';
        }, 500);
    }
}

function openEmergencyPasswordModal() {
    const freezeToggle = document.getElementById('toggle_freeze_payouts');
    const checkoutToggle = document.getElementById('toggle_disable_checkout');

    const summaryFreeze = document.getElementById('summary_freeze_payouts');
    const summaryCheckout = document.getElementById('summary_disable_checkout');
    const passInput = document.getElementById('modal_emergency_admin_password');
    const errBox = document.getElementById('emergencyPasswordErrorMsg');
    const modal = document.getElementById('emergencyPasswordModal');

    const isFreeze = freezeToggle ? freezeToggle.checked : false;
    const isCheckout = checkoutToggle ? checkoutToggle.checked : false;

    if (summaryFreeze) {
        summaryFreeze.innerHTML = isFreeze 
            ? '<span style="color: #dc2626; background: #fee2e2; padding: 2px 8px; border-radius: 6px; font-size: 11px;">DIBEKUKAN (Aktif)</span>' 
            : '<span style="color: #059669; background: #ecfdf5; padding: 2px 8px; border-radius: 6px; font-size: 11px;">NORMAL (Terbuka)</span>';
    }

    if (summaryCheckout) {
        summaryCheckout.innerHTML = isCheckout 
            ? '<span style="color: #dc2626; background: #fee2e2; padding: 2px 8px; border-radius: 6px; font-size: 11px;">DINONAKTIFKAN (Aktif)</span>' 
            : '<span style="color: #059669; background: #ecfdf5; padding: 2px 8px; border-radius: 6px; font-size: 11px;">NORMAL (Terbuka)</span>';
    }

    if (passInput) passInput.value = '';
    if (errBox) errBox.style.display = 'none';

    if (modal) {
        modal.classList.add('show');
        setTimeout(() => {
            if (passInput) passInput.focus();
        }, 150);
    }
}

function closeEmergencyPasswordModal() {
    const modal = document.getElementById('emergencyPasswordModal');
    if (modal) modal.classList.remove('show');
}

function toggleEmergencyPasswordVisibility() {
    const passInput = document.getElementById('modal_emergency_admin_password');
    const passIcon = document.getElementById('toggleEmergencyPasswordIcon');
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

function submitEmergencySettings() {
    const passInput = document.getElementById('modal_emergency_admin_password');
    const pass = passInput ? passInput.value : '';
    const errBox = document.getElementById('emergencyPasswordErrorMsg');

    if (!pass.trim()) {
        if (errBox) {
            errBox.textContent = 'Silakan masukkan kata sandi admin terlebih dahulu.';
            errBox.style.display = 'block';
        }
        if (passInput) passInput.focus();
        return;
    }

    const hiddenPass = document.getElementById('form_emergency_admin_password');
    const form = document.getElementById('emergencySettingsForm');
    if (hiddenPass) hiddenPass.value = pass;
    if (form) form.submit();
}
