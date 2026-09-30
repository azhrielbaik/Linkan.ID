/**
 * Microsite Editor - Modal Manager Module
 * Handles opening, closing, navigation, and confirmation dialogs for modals.
 */
(function () {
    'use strict';

    window.MicrositeBuilder = window.MicrositeBuilder || {};

    let currentStep = 1;
    window.confirmDeleteCallback = null;
    window.currentSocialElementId = null;

    /**
     * Copy text to user clipboard with toast notification.
     */
    function copyToClipboard(text) {
        if (!navigator.clipboard) {
            const textarea = document.createElement('textarea');
            textarea.value = text;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);
            if (typeof window.showSuccessToast === 'function') {
                window.showSuccessToast('Tautan berhasil disalin!');
            } else {
                alert('Tautan berhasil disalin ke clipboard!');
            }
            return;
        }

        navigator.clipboard.writeText(text).then(() => {
            if (typeof window.showSuccessToast === 'function') {
                window.showSuccessToast('Tautan berhasil disalin!');
            } else {
                alert('Tautan berhasil disalin ke clipboard!');
            }
        }).catch(err => {
            console.error('Gagal menyalin teks: ', err);
        });
    }

    /**
     * Open New Microsite Creation Modal (Wizard)
     */
    function openNewMicrositeModal() {
        const modal = document.getElementById('newMicrositeModalOverlay');
        if (modal) {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';

            // Reset selection & disable next button
            const hiddenInput = document.getElementById('selectedPurpose');
            if (hiddenInput) hiddenInput.value = '';
            const allCards = document.querySelectorAll('.image-style-option-card');
            allCards.forEach(card => card.classList.remove('active'));

            const btnNext = document.getElementById('btnNextStep1');
            if (btnNext) btnNext.disabled = true;

            goToStep(1);
        }
    }

    /**
     * Close New Microsite Creation Modal
     */
    function closeNewMicrositeModal() {
        const modal = document.getElementById('newMicrositeModalOverlay');
        if (modal) {
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }
    }

    /**
     * Select purpose card in wizard Step 1
     */
    function selectPurposeCard(purpose, cardElement) {
        const hiddenInput = document.getElementById('selectedPurpose');
        if (hiddenInput) {
            hiddenInput.value = purpose;
        }
        const allCards = document.querySelectorAll('.image-style-option-card');
        allCards.forEach(card => card.classList.remove('active'));
        if (cardElement) {
            cardElement.classList.add('active');
        }

        const btnNext = document.getElementById('btnNextStep1');
        if (btnNext) btnNext.disabled = false;
    }

    /**
     * Navigate between wizard steps
     */
    function goToStep(step) {
        if (step === 2) {
            const purposeInput = document.getElementById('selectedPurpose');
            const purpose = purposeInput ? purposeInput.value : '';
            if (!purpose) {
                alert('Silakan pilih salah satu tujuan pembuatan microsite terlebih dahulu!');
                return;
            }
        }

        currentStep = step;
        const step1 = document.getElementById('wizardStep1');
        const step2 = document.getElementById('wizardStep2');
        const dot1 = document.getElementById('dotStep1');
        const dot2 = document.getElementById('dotStep2');
        const subtitleText = document.getElementById('wizardSubtitle');

        if (step === 1) {
            if (step1) step1.style.display = 'block';
            if (step2) step2.style.display = 'none';
            if (dot1) dot1.classList.add('active');
            if (dot2) dot2.classList.remove('active');
            if (subtitleText) subtitleText.innerText = 'Langkah 1 dari 2: Pilih Tujuan Pembuatan Microsite';
        } else if (step === 2) {
            if (step1) step1.style.display = 'none';
            if (step2) step2.style.display = 'block';
            if (dot1) dot1.classList.remove('active');
            if (dot2) dot2.classList.add('active');
            if (subtitleText) subtitleText.innerText = 'Langkah 2 dari 2: Isi Nama & Bio Microsite Baru';
            const nameInput = document.getElementById('micrositeNameInput');
            if (nameInput) nameInput.focus();
        }
    }

    /**
     * Custom Delete Confirmation Modal
     */
    function showDeleteConfirmModal(title, callback) {
        const modal = document.getElementById('customDeleteConfirmModal');
        const titleEl = document.getElementById('customDeleteConfirmTitle');
        if (modal && titleEl) {
            titleEl.textContent = title;
            window.confirmDeleteCallback = callback;
            modal.classList.add('active');
        } else {
            if (confirm(title)) callback();
        }
    }

    function closeDeleteConfirmModal() {
        const modal = document.getElementById('customDeleteConfirmModal');
        if (modal) {
            modal.classList.remove('active');
        }
        window.confirmDeleteCallback = null;
    }

    /**
     * Social Platform Selector Modal
     */
    function openSocialPlatformSelector(elementId) {
        window.currentSocialElementId = elementId;
        const modal = document.getElementById('socialPlatformModal');

        if (modal) {
            const buttons = modal.querySelectorAll('.btn-select-platform');
            buttons.forEach(btn => {
                const platform = btn.getAttribute('data-platform');
                const exists = document.getElementById('platform_item_' + platform + '_' + elementId);
                if (exists) {
                    btn.classList.add('selected');
                    btn.setAttribute('data-originally-selected', 'true');
                } else {
                    btn.classList.remove('selected');
                    btn.removeAttribute('data-originally-selected');
                }
            });

            modal.classList.add('active');
        }
    }

    function closeSocialPlatformSelector() {
        const modal = document.getElementById('socialPlatformModal');
        if (modal) modal.classList.remove('active');
        window.currentSocialElementId = null;
    }

    function toggleSocialPlatformSelection(button) {
        button.classList.toggle('selected');
    }

    function finishSocialPlatformSelection() {
        const buttons = document.querySelectorAll('#socialPlatformModal .btn-select-platform');
        const elementId = window.currentSocialElementId;

        if (!elementId) {
            closeSocialPlatformSelector();
            return;
        }

        buttons.forEach(btn => {
            const platform = btn.getAttribute('data-platform');
            const isSelected = btn.classList.contains('selected');
            const wasSelected = btn.getAttribute('data-originally-selected') === 'true';

            if (isSelected && !wasSelected) {
                if (typeof window.addSocialPlatformToForm === 'function') {
                    window.addSocialPlatformToForm(elementId, platform);
                }
            } else if (!isSelected && wasSelected) {
                if (typeof window.removeSocialPlatformFromForm === 'function') {
                    window.removeSocialPlatformFromForm(elementId, platform);
                }
            }

            btn.classList.remove('selected');
            btn.removeAttribute('data-originally-selected');
        });

        closeSocialPlatformSelector();
    }

    // Modal background overlay & delegation click listeners (Idempotent: bound only once)
    if (!window.__micrositeModalManagerEventsBound) {
        window.__micrositeModalManagerEventsBound = true;

        window.addEventListener('click', function (event) {
            if (event.target && event.target.id === 'newMicrositeModalOverlay') {
                closeNewMicrositeModal();
            }
        });

        document.addEventListener('click', function (e) {
            let target;

            if ((target = e.target.closest('.js-close-delete-modal'))) {
                closeDeleteConfirmModal();
            }

            if ((target = e.target.closest('.js-confirm-delete-modal'))) {
                if (typeof window.confirmDeleteCallback === 'function' && window.confirmDeleteCallback) {
                    window.confirmDeleteCallback();
                }
                closeDeleteConfirmModal();
            }

            if ((target = e.target.closest('.js-open-social-selector'))) {
                openSocialPlatformSelector(target.dataset.targetId);
            }

            if ((target = e.target.closest('.js-close-social-selector'))) {
                closeSocialPlatformSelector();
            }

            if ((target = e.target.closest('.js-toggle-social-selection'))) {
                toggleSocialPlatformSelection(target);
            }

            if ((target = e.target.closest('.js-finish-social-selection'))) {
                finishSocialPlatformSelection();
            }

            if ((target = e.target.closest('.js-copy-url'))) {
                copyToClipboard(target.dataset.url);
            }
        });
    }

    // Register module namespace
    window.MicrositeBuilder.ModalManager = {
        copyToClipboard,
        openNewMicrositeModal,
        closeNewMicrositeModal,
        selectPurposeCard,
        goToStep,
        showDeleteConfirmModal,
        closeDeleteConfirmModal,
        openSocialPlatformSelector,
        closeSocialPlatformSelector,
        toggleSocialPlatformSelection,
        finishSocialPlatformSelection
    };

    // Expose global functions for backward compatibility
    window.copyToClipboard = copyToClipboard;
    window.openNewMicrositeModal = openNewMicrositeModal;
    window.closeNewMicrositeModal = closeNewMicrositeModal;
    window.selectPurposeCard = selectPurposeCard;
    window.goToStep = goToStep;
    window.showDeleteConfirmModal = showDeleteConfirmModal;
    window.closeDeleteConfirmModal = closeDeleteConfirmModal;
    window.openSocialPlatformSelector = openSocialPlatformSelector;
    window.closeSocialPlatformSelector = closeSocialPlatformSelector;
    window.toggleSocialPlatformSelection = toggleSocialPlatformSelection;
    window.finishSocialPlatformSelection = finishSocialPlatformSelection;
})();
