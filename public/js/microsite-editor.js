/**
 * Microsite Editor JavaScript
 * Refactored using Unobtrusive JS and Event Delegation pattern.
 */
(function () {
    'use strict';

    let currentStep = 1;

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert('Tautan berhasil disalin ke clipboard!');
        }).catch(err => {
            console.error('Gagal menyalin teks: ', err);
        });
    }

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

    function closeNewMicrositeModal() {
        const modal = document.getElementById('newMicrositeModalOverlay');
        if (modal) {
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }
    }

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

        // Enable next button
        const btnNext = document.getElementById('btnNextStep1');
        if (btnNext) btnNext.disabled = false;
    }

    function goToStep(step) {
        if (step === 2) {
            const purpose = document.getElementById('selectedPurpose').value;
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

    function toggleAddElementPanel() {
        const panel = document.getElementById('addElementPanel');
        const btn = document.getElementById('btnToggleAddElement');
        const icon = document.getElementById('btnToggleIcon');
        const text = document.getElementById('btnToggleText');
        const digitalProductsSection = document.getElementById('digitalProductsSection');

        if (!panel || !btn) return;

        const isOpen = panel.classList.contains('open');

        if (isOpen) {
            panel.style.maxHeight = '0px';
            panel.style.opacity = '0';
            panel.style.marginTop = '0px';
            panel.classList.remove('open');
            btn.classList.remove('active');
            btn.style.backgroundColor = '#FF9040';

            if (digitalProductsSection) {
                digitalProductsSection.style.display = 'block';
                setTimeout(() => {
                    digitalProductsSection.style.opacity = '1';
                    digitalProductsSection.style.transform = 'translateY(0)';
                }, 20);
            }

            if (icon) {
                icon.className = 'fas fa-plus-circle';
            }
            if (text) text.innerText = window.MicrositeConfig?.translations?.addElement || 'Tambah Elemen';
        } else {
            panel.classList.add('open');
            panel.style.marginTop = '12px';
            panel.style.maxHeight = (panel.scrollHeight + 100) + 'px';
            panel.style.opacity = '1';
            btn.classList.add('active');
            btn.style.backgroundColor = '#374151';

            if (digitalProductsSection) {
                digitalProductsSection.style.opacity = '0';
                digitalProductsSection.style.transform = 'translateY(10px)';
                setTimeout(() => {
                    if (panel.classList.contains('open')) {
                        digitalProductsSection.style.display = 'none';
                    }
                }, 250);
            }

            if (icon) {
                icon.className = 'fas fa-chevron-up';
            }
            if (text) text.innerText = 'Tutup Panel Element';
        }
    }

    function closeAllEditForms(exceptId = null) {
        if (exceptId !== 'profile') {
            const profileForm = document.getElementById('profileEditFormBody');
            const profileBtnText = document.getElementById('profileEditBtnText');
            if (profileForm && profileForm.classList.contains('open')) {
                profileForm.style.maxHeight = '0px';
                profileForm.style.opacity = '0';
                profileForm.style.marginTop = '0px';
                profileForm.classList.remove('open');
                if (profileBtnText) profileBtnText.innerText = 'Edit';
            }
        }

        const allImageForms = document.querySelectorAll('.edit-form-body.open');
        allImageForms.forEach(form => {
            const idStr = form.id.replace('formBody_', '');
            if (exceptId !== idStr) {
                form.style.maxHeight = '0px';
                form.style.opacity = '0';
                form.style.marginTop = '0px';
                form.classList.remove('open');
                const btnText = document.getElementById('btnText_' + idStr);
                if (btnText) btnText.innerText = 'Edit';
            }
        });
    }

    function toggleProfileEditForm(forceOpen = false) {
        const formBody = document.getElementById('profileEditFormBody');
        const btnText = document.getElementById('profileEditBtnText');

        if (!formBody) return;

        const isOpen = formBody.classList.contains('open');

        if (isOpen && !forceOpen) {
            formBody.style.maxHeight = '0px';
            formBody.style.opacity = '0';
            formBody.style.marginTop = '0px';
            formBody.classList.remove('open');
            if (btnText) btnText.innerText = 'Edit';
        } else {
            if (typeof closeAllEditForms === 'function') closeAllEditForms('profile');
            formBody.classList.add('open');
            formBody.style.marginTop = '8px';
            formBody.style.maxHeight = (formBody.scrollHeight + 600) + 'px';
            formBody.style.opacity = '1';
            if (btnText) btnText.innerText = 'Tutup';
            setTimeout(() => {
                const block = formBody.closest('.draggable-element-block') || formBody.closest('.profile-block-wrapper') || formBody;
                if (block) {
                    block.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }, 300);
        }
    }


    function previewProfileBanner(input, maxMb = 2) {
        const errorDiv = document.getElementById('bannerSizeError');
        if (errorDiv) errorDiv.style.display = 'none';

        if (input.files && input.files[0]) {
            if (input.files[0].size > maxMb * 1024 * 1024) {
                if (errorDiv) errorDiv.style.display = 'block';
                input.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = function (e) {
                const bannerContainer = document.getElementById('bannerPreviewContainer');
                let img = document.getElementById('bannerPreviewImg');
                const placeholder = document.getElementById('bannerPreviewPlaceholder');

                if (placeholder) placeholder.style.display = 'none';
                if (bannerContainer) bannerContainer.style.display = 'block';

                if (!img) {
                    img = document.createElement('img');
                    img.id = 'bannerPreviewImg';
                    img.style.width = '100%';
                    img.style.height = '100%';
                    img.style.objectFit = 'cover';
                    bannerContainer.appendChild(img);
                }
                img.style.display = 'block';
                img.src = e.target.result;

                const liveBannerContainer = document.getElementById('livePhoneBannerContainer');
                const liveBannerImg = document.getElementById('livePhoneBannerImg');
                if (liveBannerContainer) liveBannerContainer.style.display = 'block';
                if (liveBannerImg) liveBannerImg.src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewProfileAvatar(input, maxMb = 2) {
        const errorDiv = document.getElementById('avatarSizeError');
        if (errorDiv) errorDiv.style.display = 'none';

        if (input.files && input.files[0]) {
            if (input.files[0].size > maxMb * 1024 * 1024) {
                if (errorDiv) errorDiv.style.display = 'block';
                input.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = function (e) {
                const avatarContainer = document.getElementById('avatarPreviewContainer');
                let img = document.getElementById('avatarPreviewImg');
                const placeholder = document.getElementById('avatarPreviewPlaceholder');

                if (placeholder) placeholder.style.display = 'none';
                if (!img) {
                    img = document.createElement('img');
                    img.id = 'avatarPreviewImg';
                    img.className = 'w-full h-full object-cover';
                    avatarContainer.appendChild(img);
                }
                img.src = e.target.result;

                const liveAvatarImg = document.getElementById('livePhoneAvatarImg');
                const liveAvatarPlaceholder = document.getElementById('livePhoneAvatarPlaceholder');
                const liveAvatarContainer = document.getElementById('livePhoneAvatarContainer');

                if (liveAvatarPlaceholder) liveAvatarPlaceholder.style.display = 'none';
                if (liveAvatarImg) {
                    liveAvatarImg.src = e.target.result;
                } else if (liveAvatarContainer) {
                    const newImg = document.createElement('img');
                    newImg.id = 'livePhoneAvatarImg';
                    newImg.style.width = '100%';
                    newImg.style.height = '100%';
                    newImg.style.objectFit = 'cover';
                    newImg.src = e.target.result;
                    liveAvatarContainer.appendChild(newImg);
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function updateProfileShape(shape) {
        const liveAvatarContainer = document.getElementById('livePhoneAvatarContainer');
        const avatarPreviewContainer = document.getElementById('avatarPreviewContainer');

        let radius = '50%';
        if (shape === 'rounded') radius = '14px';
        if (shape === 'square') radius = '0px';

        if (liveAvatarContainer) liveAvatarContainer.style.borderRadius = radius;
        if (avatarPreviewContainer) avatarPreviewContainer.style.borderRadius = radius;
    }

    function updateLiveProfileName(val) {
        const liveName = document.getElementById('livePhoneName');
        if (liveName) liveName.innerHTML = val || window.MicrositeConfig?.authUserName || 'Your Name';
    }

    function updateLiveProfileBio(val) {
        const liveBio = document.getElementById('livePhoneBio');
        if (liveBio) liveBio.innerHTML = val;
    }

    function previewDynamicImage(input, elementId, maxMb = 2) {
        const errorDiv = document.getElementById('error_' + elementId);
        if (errorDiv) errorDiv.style.display = 'none';

        if (input.files && input.files[0]) {
            if (input.files[0].size > maxMb * 1024 * 1024) {
                if (errorDiv) errorDiv.style.display = 'block';
                input.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = function (e) {
                const placeholder = document.getElementById('placeholder_' + elementId);
                const previewCont = document.getElementById('previewCont_' + elementId);
                const previewImg = document.getElementById('previewImg_' + elementId);

                if (placeholder) placeholder.style.display = 'none';
                if (previewCont) previewCont.style.display = 'block';
                if (previewImg) previewImg.src = e.target.result;

                const liveEl = document.getElementById('live_' + elementId);
                const liveImg = document.getElementById('liveImg_' + elementId);
                if (liveEl && liveImg) {
                    liveEl.style.display = 'block';
                    liveImg.src = e.target.result;
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }


    function removeDynamicElement(elementId) {
        const block = document.getElementById(elementId);
        if (!block) return;

        const type = block.getAttribute('data-element-type');
        const label = type === 'divider' ? 'pembatas' : 'gambar';

        showDeleteConfirmModal(`Yakin ingin menghapus elemen ${label} ini?`, function () {
            const dbId = block.getAttribute('data-db-id');

            if (dbId) {
                const urls = document.getElementById('micrositeEditorUrls').dataset;
                const routeDelete = type === 'divider' ? urls.routeDividerDelete : urls.routeImageDelete;

                fetch(`${routeDelete}/${dbId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                }).then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            block.remove();
                            const liveEl = document.getElementById('live_' + elementId);
                            if (liveEl) liveEl.remove();
                            syncPhonePreviewOrder();
                            saveElementsOrder();
                        } else {
                            alert('Gagal menghapus dari database.');
                        }
                    }).catch(err => {
                        console.error(err);
                        alert('Terjadi kesalahan saat menghapus.');
                    });
            } else {
                block.remove();
                const liveEl = document.getElementById('live_' + elementId);
                if (liveEl) liveEl.remove();
                syncPhonePreviewOrder();
                saveElementsOrder();
            }
        });
    }

    function saveDynamicElement(elementId) {
        const block = document.getElementById(elementId);
        const fileInput = block.querySelector('input[type="file"]');
        const linkInput = document.getElementById('link_' + elementId);
        const originalBtnText = document.getElementById('btnText_' + elementId);

        let formData = new FormData();
        if (fileInput && fileInput.files && fileInput.files[0]) {
            formData.append('image', fileInput.files[0]);
        }
        if (linkInput && linkInput.value) {
            formData.append('link_url', linkInput.value);
        }

        const dbId = block.getAttribute('data-db-id');
        if (dbId) {
            formData.append('element_id', dbId);
        }

        const btn = block.querySelector('button[onclick^="saveDynamicElement"]');
        if (btn) {
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
            btn.disabled = true;
        }

        fetch(document.getElementById('micrositeEditorUrls').dataset.routeImageStore, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                if (btn) {
                    btn.innerHTML = 'Simpan';
                    btn.disabled = false;
                }
                if (data.success) {
                    block.setAttribute('data-db-id', data.id);
                    // Also update the live preview image if needed (already handled by onchange locally, but good to have)
                    syncPhonePreviewOrder();
                    saveElementsOrder();
                    toggleImageEditForm(elementId);
                    showSuccessToast('Elemen gambar berhasil disimpan!');
                }
            })
            .catch(err => {
                if (btn) {
                    btn.innerHTML = 'Simpan';
                    btn.disabled = false;
                }
                console.error(err);
                alert('Terjadi kesalahan saat menyimpan. Pastikan file max 2MB.');
            });
    }

    function saveElementsOrder() {
        const list = document.getElementById('elementBlocksList');
        if (!list) return;

        const blocks = list.querySelectorAll('.draggable-element-block, .element-block');
        const order = ['profile']; // Profile is strictly always the first element in DB
        blocks.forEach(block => {
            const type = block.getAttribute('data-element-type');
            if (type === 'image') {
                const dbId = block.getAttribute('data-db-id');
                if (dbId) order.push('image_' + dbId);
            } else if (type === 'divider') {
                const dbId = block.getAttribute('data-db-id');
                if (dbId) order.push('divider_' + dbId);
            } else if (type === 'text') {
                const dbId = block.getAttribute('data-db-id');
                if (dbId) order.push('text_' + dbId);
            } else if (type === 'video') {
                const dbId = block.getAttribute('data-db-id');
                if (dbId) order.push('video_' + dbId);
            } else if (type === 'social') {
                const dbId = block.getAttribute('data-db-id');
                if (dbId) order.push('social_' + dbId);
            } else if (type === 'digital_product') {
                const dbId = block.getAttribute('data-db-id');
                if (dbId) order.push('digitalproduct_' + dbId);
            }
        });

        const blocksOrderStr = order.join(',');

        return fetch(document.getElementById('micrositeEditorUrls').dataset.routeOrderUpdate, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ blocks_order: blocksOrderStr }),
            keepalive: true
        });
    }

    function bindDynamicDropzone(elementId) {
        const block = document.getElementById(elementId);
        if (!block) return;
        const zone = block.querySelector('.dynamic-dropzone');
        const input = zone.querySelector('input[type="file"]');
        if (input && zone) {
            input.addEventListener('dragenter', () => zone.classList.add('drag-over-active'));
            input.addEventListener('dragleave', () => zone.classList.remove('drag-over-active'));
            input.addEventListener('drop', () => zone.classList.remove('drag-over-active'));
        }
    }

    function initPageEvents() {
        // Sort blocks based on DB order
        const list = document.getElementById('elementBlocksList');
        const dbOrderStr = document.getElementById('micrositeEditorUrls').dataset.appearanceBlocksOrder || '';
        if (list && dbOrderStr) {
            const dbOrder = dbOrderStr.split(',');
            dbOrder.forEach(blockId => {
                let el = null;
                if (blockId === 'profile') {
                    // Profile is statically positioned outside the list, do nothing
                    return;
                } else if (blockId.startsWith('image_')) {
                    const dbId = blockId.split('_')[1];
                    el = document.querySelector(`.draggable-element-block[data-db-id="${dbId}"][data-element-type="image"]`);
                } else if (blockId.startsWith('divider_')) {
                    const dbId = blockId.split('_')[1];
                    el = document.querySelector(`.draggable-element-block[data-db-id="${dbId}"][data-element-type="divider"]`);
                } else if (blockId.startsWith('text_')) {
                    const dbId = blockId.split('_')[1];
                    el = document.querySelector(`.draggable-element-block[data-db-id="${dbId}"][data-element-type="text"]`);
                } else if (blockId.startsWith('video_')) {
                    const dbId = blockId.split('_')[1];
                    el = document.querySelector(`.draggable-element-block[data-db-id="${dbId}"][data-element-type="video"]`);
                } else if (blockId.startsWith('social_') || blockId.startsWith('socialBlock_')) {
                    const dbId = blockId.split('_')[1];
                    el = document.querySelector(`.draggable-element-block[data-db-id="${dbId}"][data-element-type="social"]`);
                } else if (blockId.startsWith('digitalproduct_')) {
                    const dbId = blockId.split('_')[1];
                    el = document.querySelector(`.draggable-element-block[data-db-id="${dbId}"][data-element-type="digital_product"]`);
                }
                if (el) {
                    list.appendChild(el);
                }
            });
        }

        initElementDragAndDrop();
        syncPhonePreviewOrder();
        updatePhonePreviewVisibility();

        // Initialize video previews
        document.querySelectorAll('.draggable-element-block[data-element-type="video"]').forEach(block => {
            updateVideoPreview(block.id);
        });

        // Initialize drag and drop visual states for upload zones
        const dropzones = document.querySelectorAll('.upload-dropzone');
        dropzones.forEach(zone => {
            const input = zone.querySelector('input[type="file"]');
            if (input && !input.dataset.dragbound) {
                input.dataset.dragbound = 'true';
                input.addEventListener('dragenter', () => {
                    zone.classList.add('drag-over-active');
                });
                input.addEventListener('dragleave', () => {
                    zone.classList.remove('drag-over-active');
                });
                input.addEventListener('drop', () => {
                    zone.classList.remove('drag-over-active');
                });
            }
        });

        // AJAX PROFILE FORM SUBMISSION (NO RELOAD NEEDED)
        const profileForm = document.getElementById('profileBlockForm');
        if (profileForm && !profileForm.dataset.initialized) {
            profileForm.dataset.initialized = 'true';
            profileForm.addEventListener('submit', function (e) {
                e.preventDefault();
                const submitBtn = this.querySelector('button[type="submit"]');
                const origText = submitBtn ? submitBtn.innerText : 'Simpan Perubahan';

                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerText = 'Menyimpan...';
                }

                if (typeof syncProfileName === 'function') syncProfileName();
                if (typeof syncProfileBio === 'function') syncProfileBio();
                const formData = new FormData(this);

                fetch(document.getElementById('micrositeEditorUrls').dataset.routeAppearanceUpdate, {
                    method: "POST",
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                })
                    .then(async res => {
                        if (!res.ok) {
                            const errData = await res.json().catch(() => ({}));
                            throw errData;
                        }
                        return res.json();
                    })
                    .then(data => {
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.innerText = origText;
                        }

                        if (data && data.success) {
                            const card = document.getElementById('profileBlockCard');
                            if (card) card.style.display = 'block';

                            updatePhonePreviewVisibility();
                            toggleProfileEditForm();

                            showSuccessToast('Profil berhasil disimpan!');
                        }
                    })
                    .catch(err => {
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.innerText = origText;
                        }

                        if (err && err.errors) {
                            // Extract first validation error
                            const firstError = Object.values(err.errors)[0][0];
                            alert('Gagal menyimpan: ' + firstError);
                        } else {
                            // If it's a server error or non-JSON response, fallback to normal submission
                            HTMLFormElement.prototype.submit.call(this);
                        }
                    });
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPageEvents);
    } else {
        initPageEvents();
    }
    document.addEventListener('turbo:load', initPageEvents);
    document.addEventListener('turbolinks:load', initPageEvents);

    function showSuccessToast(message) {
        let toast = document.getElementById('profileSuccessToast');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'profileSuccessToast';
            toast.style.cssText = 'position: fixed; bottom: 24px; right: 24px; background: #10B981; color: #ffffff; padding: 12px 20px; border-radius: 10px; font-weight: 700; font-size: 13px; box-shadow: 0 4px 14px rgba(16,185,129,0.3); z-index: 9999; display: flex; align-items: center; gap: 8px; transition: all 0.3s ease; opacity: 0; transform: translateY(10px);';
            toast.innerHTML = '<i class="fas fa-check-circle"></i> <span>' + message + '</span>';
            document.body.appendChild(toast);
        }
        setTimeout(() => {
            toast.style.opacity = '1';
            toast.style.transform = 'translateY(0)';
        }, 10);
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(10px)';
        }, 3000);
    }

    window.onclick = function (event) {
        if (event.target.id === 'newMicrositeModalOverlay') {
            closeNewMicrositeModal();
        }
    }

    function initElementDragAndDrop() {
        const list = document.getElementById('elementBlocksList');
        if (!list) return;

        if (elementSortable) {
            elementSortable.destroy();
        }

        elementSortable = new Sortable(list, {
            animation: 150,
            handle: '.drag-handle', // Dragging is only allowed when clicking the handle icon
            filter: '#profileBlockCard', // Prevent dragging the profile block entirely
            ghostClass: 'sortable-ghost', // Styling for drop placeholder
            onEnd: function (evt) {
                // Trigger visual sync and database save on drop
                syncPhonePreviewOrder();
                if (typeof saveElementsOrder === 'function') saveElementsOrder();
            }
        });
    }

    function syncPhonePreviewOrder() {
        const list = document.getElementById('elementBlocksList');
        const phoneContent = document.getElementById('phonePreviewContent');
        if (!list || !phoneContent) return;

        const blocks = list.querySelectorAll('.draggable-element-block');
        blocks.forEach(block => {
            const type = block.getAttribute('data-element-type');
            if (type === 'profile') {
                const liveProfile = document.getElementById('liveProfileSection');
                if (liveProfile) {
                    phoneContent.appendChild(liveProfile);
                }
            } else if (type === 'image' || type === 'divider' || type === 'text' || type === 'video' || type === 'social' || type === 'digital_product') {
                const liveElement = document.getElementById('live_' + block.id);
                if (liveElement) {
                    phoneContent.appendChild(liveElement);
                }
            }
        });
    }


    function updatePhonePreviewVisibility() {
        const card = document.getElementById('profileBlockCard');
        const liveProfile = document.getElementById('liveProfileSection');
        const emptyState = document.getElementById('phoneEmptyState');

        let isProfileActive = false;
        if (card) {
            const computedDisplay = window.getComputedStyle(card).display;
            isProfileActive = (card.style.display !== 'none') && (computedDisplay !== 'none');
        }

        if (liveProfile) {
            liveProfile.style.display = isProfileActive ? 'block' : 'none';
        }

        if (emptyState) {
            emptyState.style.display = isProfileActive ? 'none' : 'flex';
        }
    }







    function formatText(command, value = null, editorId = null) {
        if (editorId) {
            const editor = document.getElementById(editorId);
            if (editor) {
                editor.focus();
                const range = document.createRange();
                range.selectNodeContents(editor);
                const selection = window.getSelection();
                selection.removeAllRanges();
                selection.addRange(range);
            }
        }

        document.execCommand(command, false, value);

        if (editorId) {
            const selection = window.getSelection();
            if (selection) {
                selection.removeAllRanges();
            }
        }

        syncProfileName();
        syncProfileBio();

        const nameEditor = document.getElementById('editorProfileName');
        if (nameEditor) updateLiveProfileName(nameEditor.innerHTML);

        const bioEditor = document.getElementById('editorProfileBio');
        if (bioEditor) updateLiveProfileBio(bioEditor.innerHTML);
    }

    function syncProfileName() {
        const editor = document.getElementById('editorProfileName');
        const input = document.getElementById('inputProfileName');
        if (editor && input) input.value = editor.innerHTML;
    }

    function syncProfileBio() {
        const editor = document.getElementById('editorProfileBio');
        const input = document.getElementById('inputProfileBio');
        if (editor && input) input.value = editor.innerHTML;
    }

    // Wait for DOM to add modal listeners
    window.confirmDeleteCallback = null;

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

    // ==========================================
    // DRAG AND DROP (SortableJS) INITIALIZATION
    // ==========================================

    // DRAG AND DROP REORDERING SYSTEM FOR MICROSITE ELEMENTS
    let elementSortable = null;


    // SYNC DOM ORDER OF PHONE MOCKUP PREVIEW ACCORDING TO LEFT PANEL BLOCKS

    // DYNAMIC IMAGE ELEMENT LOGIC
    let imageElementCounter = 0;

    function addGambarElement() {
        toggleAddElementPanel(); // Hide side menu

        imageElementCounter++;
        const elementId = 'imageBlock_' + new Date().getTime();
        const list = document.getElementById('elementBlocksList');

        // Gunakan template sesuai permintaan untuk element list
        const template = document.getElementById('image-block-template');
        const clone = template.content.cloneNode(true);
        const tempDiv = document.createElement('div');
        tempDiv.appendChild(clone);
        const html = tempDiv.innerHTML.replace(/__ELEMENT_ID__/g, elementId);

        list.insertAdjacentHTML('beforeend', html);

        // Add to Phone Preview menggunakan template
        const phoneContent = document.getElementById('phonePreviewContent');
        if (phoneContent) {
            const liveTemplate = document.getElementById('image-live-template');
            const liveClone = liveTemplate.content.cloneNode(true);
            const liveTempDiv = document.createElement('div');
            liveTempDiv.appendChild(liveClone);
            const liveHtml = liveTempDiv.innerHTML.replace(/__ELEMENT_ID__/g, elementId);

            phoneContent.insertAdjacentHTML('beforeend', liveHtml);
        }

        initElementDragAndDrop();
        bindDynamicDropzone(elementId);
        syncPhonePreviewOrder();

        setTimeout(() => toggleImageEditForm(elementId), 50);
    }

    function toggleImageEditForm(elementId, forceOpen = false) {
        const formBody = document.getElementById('formBody_' + elementId);
        const btnText = document.getElementById('btnText_' + elementId);
        if (!formBody) return;

        if (formBody.classList.contains('open') && !forceOpen) {
            formBody.style.maxHeight = '0px';
            formBody.style.opacity = '0';
            formBody.style.marginTop = '0px';
            formBody.classList.remove('open');
            if (btnText) btnText.innerText = 'Edit';
        } else {
            if (typeof closeAllEditForms === 'function') closeAllEditForms(elementId);
            formBody.classList.add('open');
            formBody.style.marginTop = '8px';
            formBody.style.maxHeight = (formBody.scrollHeight + 500) + 'px';
            formBody.style.opacity = '1';
            if (btnText) btnText.innerText = 'Tutup';
            setTimeout(() => {
                const block = formBody.closest('.draggable-element-block') || formBody.closest('.profile-block-wrapper') || formBody;
                if (block) {
                    block.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }, 300);
        }
    }


    function updateDynamicImageLink(elementId, url) {
        // Di preview, elemen gambar tidak boleh bisa di klik untuk navigasi (agar bisa di klik untuk membuka form edit).
        // Oleh karena itu, kita biarkan saja tanpa href dan pointer-events tetap none di `a` tag-nya.
    }
    // DIVIDER ELEMENT LOGIC
    function addDividerElement() {
        if (typeof toggleAddElementPanel === 'function') toggleAddElementPanel();
        const tempId = 'temp_' + Date.now();
        const list = document.getElementById('elementBlocksList');

        let blockTemplate = document.getElementById('divider-block-template').innerHTML;
        blockTemplate = blockTemplate.replace(/__ELEMENT_ID__/g, tempId);

        let liveTemplate = document.getElementById('divider-live-template').innerHTML;
        liveTemplate = liveTemplate.replace(/__ELEMENT_ID__/g, tempId);

        const wrapper = document.createElement('div');
        wrapper.innerHTML = blockTemplate;
        const newBlock = wrapper.firstElementChild;
        list.appendChild(newBlock);

        const liveWrapper = document.createElement('div');
        liveWrapper.innerHTML = liveTemplate;
        const newLive = liveWrapper.firstElementChild;

        const phoneContent = document.getElementById('phonePreviewContent');
        if (phoneContent) {
            phoneContent.appendChild(newLive);
        }

        toggleDividerEditForm(tempId, true);

        // Auto-save the default divider to the database
        const formData = new FormData();
        formData.append('type', 'line');
        formData.append('size', '20');

        const url = document.getElementById('micrositeEditorUrls').dataset.routeDividerStore;
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    newBlock.setAttribute('data-db-id', data.id);
                    if (typeof saveElementsOrder === 'function') saveElementsOrder();
                }
            })
            .catch(err => console.error(err));
    }

    function toggleDividerEditForm(id, forceOpen = false) {
        const formBody = document.getElementById('formBody_' + id);
        const btnText = document.getElementById('btnText_' + id);
        if (!formBody) return;

        const isOpen = formBody.classList.contains('open');
        if (isOpen && !forceOpen) {
            formBody.style.maxHeight = '0px';
            formBody.style.opacity = '0';
            formBody.style.marginTop = '0px';
            formBody.classList.remove('open');
            if (btnText) {
                btnText.innerText = 'Edit';
            }
        } else {
            if (typeof closeAllEditForms === 'function') closeAllEditForms(id);
            formBody.classList.add('open');
            formBody.style.marginTop = '8px';
            formBody.style.maxHeight = (formBody.scrollHeight + 100) + 'px';
            formBody.style.opacity = '1';
            if (btnText) {
                btnText.innerText = 'Tutup';
            }
            setTimeout(() => {
                const block = formBody.closest('.draggable-element-block') || formBody.closest('.profile-block-wrapper') || formBody;
                if (block) {
                    block.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }, 300);
        }
    }

    function adjustDividerSize(id, change) {
        const input = document.getElementById('dividerSize_' + id);
        if (input) {
            let val = parseInt(input.value) + change;
            if (val < parseInt(input.min)) val = parseInt(input.min);
            if (val > parseInt(input.max)) val = parseInt(input.max);
            input.value = val;
            updateDividerPreview(id);
        }
    }

    function updateDividerPreview(id) {
        const typeSelect = document.getElementById('dividerType_' + id);
        const sizeInput = document.getElementById('dividerSize_' + id);
        const sizeLabel = document.getElementById('dividerSizeValue_' + id);
        const liveDivider = document.getElementById('liveDivider_' + id);

        if (typeSelect && sizeInput && sizeLabel && liveDivider) {
            const type = typeSelect.value;
            const size = sizeInput.value;
            sizeLabel.innerText = size + 'px';

            if (type === 'line') {
                liveDivider.style.borderTop = '2px solid #cbd5e1';
                liveDivider.style.height = '0';
                document.getElementById('live_' + id).style.padding = (size / 2) + 'px 0';
            } else {
                liveDivider.style.borderTop = 'none';
                liveDivider.style.height = size + 'px';
                document.getElementById('live_' + id).style.padding = '0';
            }
        }
    }

    function updateSegmentedControl(radio) {
        const group = radio.closest('div');
        const labels = group.querySelectorAll('label');
        labels.forEach(label => {
            const input = label.querySelector('input');
            const btn = label.querySelector('.segment-btn');
            if (input.checked) {
                btn.classList.add('active');
                btn.style.color = '#1e293b';
                btn.style.background = '#ffffff';
                btn.style.boxShadow = '0 1px 3px rgba(0,0,0,0.1)';
            } else {
                btn.classList.remove('active');
                btn.style.color = '#64748b';
                btn.style.background = 'transparent';
                btn.style.boxShadow = 'none';
            }
        });
    }

    function saveDynamicDivider(id) {
        const typeVal = document.getElementById('dividerType_' + id).value;
        const sizeVal = document.getElementById('dividerSize_' + id).value;
        const block = document.getElementById(id);
        const dbId = block.getAttribute('data-db-id');

        const formData = new FormData();
        formData.append('type', typeVal);
        formData.append('size', sizeVal);
        if (dbId) {
            formData.append('element_id', dbId);
        }

        const url = document.getElementById('micrositeEditorUrls').dataset.routeDividerStore;

        const btn = document.querySelector(`#formBody_${id} .btn-save-element`);
        if (btn) {
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
            btn.disabled = true;
        }

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                if (btn) {
                    btn.innerHTML = 'Simpan';
                    btn.disabled = false;
                }
                if (data.success) {
                    block.setAttribute('data-db-id', data.id);
                    updateDividerPreview(id);
                    toggleDividerEditForm(id);
                    if (typeof saveElementsOrder === 'function') saveElementsOrder();
                    if (typeof showSuccessToast === 'function') showSuccessToast('Pembatas berhasil disimpan!');
                } else {
                    alert('Gagal menyimpan pembatas.');
                }
            })
            .catch(err => {
                if (btn) {
                    btn.innerHTML = 'Simpan';
                    btn.disabled = false;
                }
                console.error(err);
                alert('Terjadi kesalahan saat menyimpan.');
            });
    }

    function removeDynamicDivider(id) {
        showDeleteConfirmModal('Yakin ingin menghapus pembatas ini?', function () {
            const block = document.getElementById(id);
            const liveBlock = document.getElementById('live_' + id);
            const dbId = block.getAttribute('data-db-id');

            if (dbId) {
                const url = document.getElementById('micrositeEditorUrls').dataset.routeDividerDelete + '/' + dbId;
                fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                }).then(res => res.json()).then(data => {
                    if (data.success) {
                        block.remove();
                        if (liveBlock) liveBlock.remove();
                        if (typeof saveElementsOrder === 'function') saveElementsOrder();
                    }
                });
            } else {
                block.remove();
                if (liveBlock) liveBlock.remove();
                if (typeof saveElementsOrder === 'function') saveElementsOrder();
            }
        });
    }

    // TEXT ELEMENT LOGIC
    function addTextElement() {
        if (typeof toggleAddElementPanel === 'function') toggleAddElementPanel();
        const tempId = 'textBlock_' + Date.now();
        const list = document.getElementById('elementBlocksList');

        let blockTemplate = document.getElementById('text-block-template').innerHTML;
        blockTemplate = blockTemplate.replace(/__ELEMENT_ID__/g, tempId);

        const wrapper = document.createElement('div');
        wrapper.innerHTML = blockTemplate;
        const newBlock = wrapper.firstElementChild;
        list.appendChild(newBlock);

        const phoneContent = document.getElementById('phonePreviewContent');
        if (phoneContent) {
            const liveDiv = document.createElement('div');
            liveDiv.id = 'live_' + tempId;
            liveDiv.className = 'live-text-element';
            liveDiv.innerHTML = 'Teks Anda di sini...';
            phoneContent.appendChild(liveDiv);
        }

        toggleTextEditForm(tempId, true);

        const formData = new FormData();
        formData.append('content', 'Teks Anda di sini...');

        // Auto-save the default text to the database
        const url = document.getElementById('micrositeEditorUrls').dataset.routeTextStore || '/admin/elements/text';
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    newBlock.setAttribute('data-db-id', data.id);
                    if (typeof saveElementsOrder === 'function') saveElementsOrder();
                }
            })
            .catch(err => console.error(err));
    }

    function toggleTextEditForm(id, forceOpen = false) {
        const formBody = document.getElementById('formBody_' + id);
        const btnText = document.getElementById('btnText_' + id);
        if (!formBody) return;

        const isOpen = formBody.classList.contains('open');
        if (isOpen && !forceOpen) {
            formBody.style.maxHeight = '0px';
            formBody.style.opacity = '0';
            formBody.style.marginTop = '0px';
            formBody.classList.remove('open');
            if (btnText) btnText.innerText = 'Edit';
        } else {
            if (typeof closeAllEditForms === 'function') closeAllEditForms(id);
            formBody.classList.add('open');
            formBody.style.marginTop = '8px';
            formBody.style.maxHeight = (formBody.scrollHeight + 300) + 'px'; // +300 for editor flexibility
            formBody.style.opacity = '1';
            if (btnText) btnText.innerText = 'Tutup';
            setTimeout(() => {
                const block = formBody.closest('.draggable-element-block') || formBody.closest('.profile-block-wrapper') || formBody;
                if (block) {
                    block.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }, 300);
        }
    }

    function execCmd(id, command, value = null) {
        const editor = document.getElementById('editorContent_' + id);
        if (editor) {
            editor.focus();

            const selection = window.getSelection();
            if (selection.toString().length === 0) {
                document.execCommand('selectAll', false, null);
            }

            document.execCommand(command, false, value);
            updateTextPreview(id);
        }
    }

    function changeTextSize(id, value) {
        const customWrapper = document.getElementById('customSizeWrapper_' + id);
        if (value === 'custom') {
            customWrapper.style.display = 'flex';
            // adjust max-height
            const formBody = document.getElementById('formBody_' + id);
            if (formBody) formBody.style.maxHeight = (formBody.scrollHeight + 50) + 'px';
        } else {
            customWrapper.style.display = 'none';
            execCmd(id, 'fontSize', 7); // Use a dummy font size 7
            // Then replace it with exact px
            replaceFontSize(id, value);
        }
    }

    function applyCustomSize(id) {
        const input = document.getElementById('customSizeInput_' + id);
        if (input && input.value) {
            let size = parseInt(input.value);
            if (size > 99) {
                size = 99;
                input.value = 99;
            } else if (size < 1) {
                size = 1;
                input.value = 1;
            }
            execCmd(id, 'fontSize', 7);
            replaceFontSize(id, size + 'px');
        }
    }

    function replaceFontSize(id, sizePx) {
        const editor = document.getElementById('editorContent_' + id);
        if (editor) {
            const fonts = editor.querySelectorAll('font[size="7"]');
            fonts.forEach(f => {
                f.removeAttribute('size');
                f.style.fontSize = sizePx;
            });
            updateTextPreview(id);
        }
    }

    function updateTextPreview(id) {
        const editor = document.getElementById('editorContent_' + id);
        const liveDiv = document.getElementById('live_' + id);

        if (editor) {
            // [Refactor] Sync list item marker size with inner text font-size
            const lis = editor.querySelectorAll('li');
            lis.forEach(li => {
                const fontEl = li.querySelector('font, span[style*="font-size"]');
                if (fontEl && fontEl.style.fontSize) {
                    li.style.fontSize = fontEl.style.fontSize;
                } else {
                    li.style.fontSize = '';
                }
            });

            if (liveDiv) {
                liveDiv.innerHTML = editor.innerHTML;
            }
        }

        // Update max height if content grows
        const formBody = document.getElementById('formBody_' + id);
        if (formBody && formBody.classList.contains('open')) {
            formBody.style.maxHeight = (formBody.scrollHeight + 50) + 'px';
        }
    }

    function saveDynamicText(id) {
        const block = document.getElementById(id);
        const dbId = block.getAttribute('data-db-id');
        const editor = document.getElementById('editorContent_' + id);

        if (!editor) return;

        const formData = new FormData();
        formData.append('content', editor.innerHTML);
        if (dbId) {
            formData.append('element_id', dbId);
        }

        const url = document.getElementById('micrositeEditorUrls').dataset.routeTextStore || '/admin/elements/text';
        const btn = document.getElementById('btnSaveText_' + id);
        if (btn) {
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
            btn.disabled = true;
        }

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                if (btn) {
                    btn.innerHTML = 'Simpan';
                    btn.disabled = false;
                }
                if (data.success) {
                    block.setAttribute('data-db-id', data.id);
                    updateTextPreview(id);
                    toggleTextEditForm(id);
                    if (typeof saveElementsOrder === 'function') saveElementsOrder();
                    if (typeof showSuccessToast === 'function') showSuccessToast('Teks berhasil disimpan!');
                } else {
                    alert('Gagal menyimpan teks.');
                }
            })
            .catch(err => {
                if (btn) {
                    btn.innerHTML = 'Simpan';
                    btn.disabled = false;
                }
                console.error(err);
                alert('Terjadi kesalahan saat menyimpan.');
            });
    }

    function removeDynamicText(id) {
        showDeleteConfirmModal('Yakin ingin menghapus teks ini?', function () {
            const block = document.getElementById(id);
            const liveBlock = document.getElementById('live_' + id);
            const dbId = block.getAttribute('data-db-id');

            if (dbId) {
                const deleteUrl = (document.getElementById('micrositeEditorUrls').dataset.routeTextDelete || '/admin/elements/text') + '/' + dbId;

                fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                }).then(res => res.json()).then(data => {
                    if (data.success) {
                        block.remove();
                        if (liveBlock) liveBlock.remove();
                        if (typeof saveElementsOrder === 'function') saveElementsOrder();
                    }
                }).catch(err => console.error('Error delete text', err));
            } else {
                block.remove();
                if (liveBlock) liveBlock.remove();
                if (typeof saveElementsOrder === 'function') saveElementsOrder();
            }
        });
    }

    // VIDEO ELEMENT LOGIC
    function addVideoElement() {
        if (typeof toggleAddElementPanel === 'function') toggleAddElementPanel();
        const tempId = 'videoBlock_' + Date.now();
        const list = document.getElementById('elementBlocksList');

        let blockTemplate = document.getElementById('video-block-template').innerHTML;
        blockTemplate = blockTemplate.replace(/__ELEMENT_ID__/g, tempId);

        const wrapper = document.createElement('div');
        wrapper.innerHTML = blockTemplate;
        const newBlock = wrapper.firstElementChild;
        list.appendChild(newBlock);

        const phoneContent = document.getElementById('phonePreviewContent');
        if (phoneContent) {
            const liveDiv = document.createElement('div');
            liveDiv.id = 'live_' + tempId;
            liveDiv.className = 'live-video-wrapper';
            liveDiv.style.cursor = 'pointer';
            liveDiv.setAttribute('onclick', `if(typeof toggleVideoEditForm === 'function') toggleVideoEditForm('${tempId}', true);`);

            let liveTemplate = document.getElementById('video-live-template').innerHTML;
            liveTemplate = liveTemplate.replace(/__ELEMENT_ID__/g, tempId);
            liveDiv.innerHTML = liveTemplate;

            phoneContent.appendChild(liveDiv);
        }

        toggleVideoEditForm(tempId, true);

        const formData = new FormData();
        formData.append('video_url', '');
        formData.append('is_autoplay', '0');

        const url = '/admin/elements/video';
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    newBlock.setAttribute('data-db-id', data.id);
                    if (typeof saveElementsOrder === 'function') saveElementsOrder();
                }
            })
            .catch(err => console.error(err));
    }

    function toggleVideoEditForm(id, forceOpen = false) {
        const formBody = document.getElementById('formBody_' + id);
        const btnText = document.getElementById('btnText_' + id);
        if (!formBody) return;

        const isOpen = formBody.classList.contains('open');
        if (isOpen && !forceOpen) {
            formBody.style.maxHeight = '0px';
            formBody.style.opacity = '0';
            formBody.style.marginTop = '0px';
            formBody.classList.remove('open');
            if (btnText) btnText.innerText = 'Edit';
        } else {
            if (typeof closeAllEditForms === 'function') closeAllEditForms(id);
            formBody.classList.add('open');
            formBody.style.marginTop = '8px';
            formBody.style.maxHeight = (formBody.scrollHeight + 300) + 'px';
            formBody.style.opacity = '1';
            if (btnText) btnText.innerText = 'Tutup';
            setTimeout(() => {
                const block = formBody.closest('.draggable-element-block');
                if (block) {
                    block.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }, 300);
        }
    }

    function updateVideoPreview(id) {
        const urlInput = document.getElementById('videoUrl_' + id);
        const autoplayToggle = document.getElementById('videoAutoplay_' + id);
        const container = document.getElementById('liveVideoContainer_' + id);

        if (!urlInput || !container) return;

        const videoUrl = urlInput.value.trim();
        let isAutoplay = false;
        if (autoplayToggle) {
            isAutoplay = autoplayToggle.checked;
        }

        if (videoUrl) {
            // Enhanced regex to match youtube video IDs from various formats including shorts
            const match = videoUrl.match(/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?|shorts)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i);
            if (match && match[1]) {
                const videoId = match[1];
                const autoplayParam = isAutoplay ? '&autoplay=1&mute=1' : '';
                const embedUrl = `https://www.youtube.com/embed/${videoId}?rel=0${autoplayParam}`;

                container.innerHTML = `
                <div style="position: relative; width: 100%; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 8px; pointer-events: none; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <iframe src="${embedUrl}" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            `;
            } else {
                container.innerHTML = `
                <div style="background: #f3f4f6; padding: 40px 20px; text-align: center; border-radius: 8px; color: #6b7280; font-size: 14px;">
                    <i class="fab fa-youtube" style="font-size: 24px; margin-bottom: 8px; display: block;"></i>
                    URL YouTube Tidak Valid
                </div>
            `;
            }
        } else {
            container.innerHTML = `
            <div style="background: #f3f4f6; padding: 40px 20px; text-align: center; border-radius: 8px; color: #6b7280; font-size: 14px;">
                <i class="fab fa-youtube" style="font-size: 24px; margin-bottom: 8px; display: block;"></i>
                Masukkan URL YouTube
            </div>
        `;
        }
    }

    function saveDynamicVideo(id) {
        const block = document.getElementById(id);
        const dbId = block.getAttribute('data-db-id');
        const urlInput = document.getElementById('videoUrl_' + id);
        const autoplayToggle = document.getElementById('videoAutoplay_' + id);

        if (!urlInput) return;

        const formData = new FormData();
        formData.append('video_url', urlInput.value.trim());
        formData.append('is_autoplay', autoplayToggle && autoplayToggle.checked ? '1' : '0');

        if (dbId) {
            formData.append('element_id', dbId);
        }

        const url = '/admin/elements/video';
        const btn = document.getElementById('btnSaveVideo_' + id);
        if (btn) {
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
            btn.disabled = true;
        }

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                if (btn) {
                    btn.innerHTML = 'Simpan';
                    btn.disabled = false;
                }
                if (data.success) {
                    block.setAttribute('data-db-id', data.id);
                    updateVideoPreview(id);
                    toggleVideoEditForm(id);
                    if (typeof saveElementsOrder === 'function') saveElementsOrder();
                    if (typeof showSuccessToast === 'function') showSuccessToast('Video berhasil disimpan!');
                } else {
                    alert('Gagal menyimpan video.');
                }
            })
            .catch(err => {
                if (btn) {
                    btn.innerHTML = 'Simpan';
                    btn.disabled = false;
                }
                console.error(err);
                alert('Terjadi kesalahan saat menyimpan.');
            });
    }

    function removeDynamicVideo(id) {
        showDeleteConfirmModal('Yakin ingin menghapus video ini?', function () {
            const block = document.getElementById(id);
            const liveBlock = document.getElementById('live_' + id);
            const dbId = block.getAttribute('data-db-id');

            if (dbId) {
                const deleteUrl = '/admin/elements/video/' + dbId;

                fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                }).then(res => res.json()).then(data => {
                    if (data.success) {
                        block.remove();
                        if (liveBlock) liveBlock.remove();
                        if (typeof saveElementsOrder === 'function') saveElementsOrder();
                    }
                }).catch(err => console.error('Error delete video', err));
            } else {
                block.remove();
                if (liveBlock) liveBlock.remove();
                if (typeof saveElementsOrder === 'function') saveElementsOrder();
            }
        });
    }

    // VISIBILITY TOGGLE FUNCTION
    function toggleElementVisibility(elementId, checkboxElement) {
        const block = document.getElementById(elementId);
        if (!block) return;

        const statusText = block.querySelector('.visibility-status-text');
        const isActive = checkboxElement.checked;

        let liveElId = 'live_' + elementId;
        if (block.getAttribute('data-element-type') === 'profile') {
            liveElId = 'liveProfileSection'; // Profile has a different ID
        }
        const liveEl = document.getElementById(liveElId);

        // Save to database
        if (elementId && elementId.includes('_')) {
            const parts = elementId.split('_');
            let elementType = parts[0]; // e.g., imageBlock, dividerBlock, textBlock
            elementType = elementType.replace('Block', ''); // becomes image, divider, text
            const dbId = parts[1];

            fetch('/admin/elements/toggle-visibility', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    element_type: elementType,
                    element_id: parseInt(dbId),
                    is_active: isActive
                })
            }).then(async response => {
                if (!response.ok) {
                    console.error('Failed to save visibility:', await response.text());
                }
            }).catch(err => console.error('Error saving visibility:', err));
        }

        if (isActive) {
            // Change text
            if (statusText) {
                statusText.innerText = 'Aktif';
                statusText.classList.remove('status-inactive');
                statusText.classList.add('status-active');
            }

            // Remove inactive style from block
            block.classList.remove('block-inactive');

            // Show live element
            if (liveEl) {
                liveEl.style.display = 'block';
                if (liveElId === 'liveProfileSection') {
                    liveEl.style.display = 'flex'; // Profile usually uses flex
                }
                setTimeout(() => {
                    liveEl.style.transition = 'opacity 0.3s ease';
                    liveEl.style.opacity = '1';
                }, 10);
            }
        } else {
            // Change text
            if (statusText) {
                statusText.innerText = 'Tidak Aktif';
                statusText.classList.remove('status-active');
                statusText.classList.add('status-inactive');
            }

            // Add inactive style to block
            block.classList.add('block-inactive');

            // Hide live element
            if (liveEl) {
                liveEl.style.transition = 'opacity 0.3s ease';
                liveEl.style.opacity = '0';
                setTimeout(() => {
                    if (!checkboxElement.checked) {
                        liveEl.style.display = 'none';
                    }
                }, 300);
            }
        }
    }

    // SOCIAL MEDIA ELEMENT LOGIC
    function addSocialMediaElement() {
        if (typeof toggleAddElementPanel === 'function') toggleAddElementPanel();
        const tempId = 'socialBlock_' + Date.now();
        const list = document.getElementById('elementBlocksList');

        let blockTemplate = document.getElementById('social-block-template').innerHTML;
        blockTemplate = blockTemplate.replace(/__ELEMENT_ID__/g, tempId);
        list.insertAdjacentHTML('beforeend', blockTemplate);

        let liveTemplate = document.getElementById('social-live-template').innerHTML;
        liveTemplate = liveTemplate.replace(/__ELEMENT_ID__/g, tempId);

        // Insert live template before the preview-url-browser-bar or at end of container
        const phoneContent = document.getElementById('phonePreviewContent');
        if (phoneContent) {
            phoneContent.insertAdjacentHTML('beforeend', liveTemplate);
        }

        saveElementsOrder();

        setTimeout(() => {
            toggleSocialEditForm(tempId);
        }, 100);
    }

    function toggleSocialEditForm(elementId, isFromPreview = false) {
        // Determine the actual elementId if prefix varies
        let blockId = elementId;
        if (elementId.startsWith('live_')) blockId = elementId.substring(5);

        // If coming from preview click, smoothly scroll the edit panel into view
        if (isFromPreview) {
            const blockEl = document.getElementById(blockId);
            if (blockEl) {
                blockEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }

        const formBody = document.getElementById('formBody_' + blockId);
        const formElement = document.getElementById('socialForm_' + blockId);
        if (!formBody) return;

        if (formBody.style.maxHeight === '0px' || formBody.style.maxHeight === '0' || formBody.style.maxHeight === '') {
            // CLOSE OTHERS FIRST
            if (typeof closeAllEditForms === 'function') closeAllEditForms(blockId);

            // Expand
            formBody.classList.add('open');
            formBody.style.maxHeight = (formBody.scrollHeight + 500) + 'px';
            formBody.style.opacity = '1';
            formBody.style.marginTop = '12px';

            // Highlight active card
            document.querySelectorAll('.block-item-card.active').forEach(el => {
                el.classList.remove('active');
            });
            const card = document.getElementById(blockId).querySelector('.block-item-card');
            if (card) card.classList.add('active');

            // Populate form if data-db-id exists
            const blockEl = document.getElementById(blockId);
            const dbId = blockEl.getAttribute('data-db-id');
            if (dbId) {
                // Already saved in DB, logic to populate inputs from live preview DOM if needed
                // Currently values are set in HTML template directly during page load
            }
        } else {
            // Collapse
            formBody.classList.remove('open');
            formBody.style.maxHeight = '0px';
            formBody.style.opacity = '0';
            formBody.style.marginTop = '0px';

            const card = document.getElementById(blockId).querySelector('.block-item-card');
            if (card) card.classList.remove('active');
        }
    }

    function toggleSocialInput(elementId, platform) {
        const isChecked = document.getElementById('toggle_' + platform + '_' + elementId).checked;
        const inputContainer = document.getElementById('input_container_' + platform + '_' + elementId);

        if (isChecked) {
            inputContainer.style.display = 'block';
        } else {
            inputContainer.style.display = 'none';
            document.getElementById('input_' + platform + '_' + elementId).value = '';
        }

        updateSocialPreview(elementId);
    }

    function updateSocialPreview(elementId) {
        const liveContainer = document.getElementById('liveSocialContainer_' + elementId);
        if (!liveContainer) return;

        const availableIcons = {
            'linkedin': { icon: 'fab fa-linkedin', color: '#0077b5' },
            'reddit': { icon: 'fab fa-reddit', color: '#FF4500' },
            'instagram': { icon: 'fab fa-instagram', color: '#E1306C' },
            'facebook': { icon: 'fab fa-facebook', color: '#1877F2' },
            'youtube': { icon: 'fab fa-youtube', color: '#FF0000' },
            'whatsapp': { icon: 'fab fa-whatsapp', color: '#25D366' },
            'telegram': { icon: 'fab fa-telegram', color: '#0088cc' },
            'tiktok': { icon: 'fab fa-tiktok', color: '#000000' },
            'twitter': { icon: 'fab fa-x-twitter', color: '#000000' },
            'email': { icon: 'fas fa-envelope', color: '#ea4335' }
        };

        let html = '';
        const inputs = document.querySelectorAll(`#social_platforms_list_${elementId} .platform-input-trigger`);

        inputs.forEach(input => {
            const plat = input.getAttribute('data-platform');

            if (availableIcons[plat]) {
                let url = input.value.trim();
                if (url === '') {
                    url = 'javascript:void(0)';
                } else {
                    if (plat === 'email' && !url.startsWith('mailto:')) {
                        url = 'mailto:' + url;
                    } else if (plat === 'whatsapp') {
                        url = 'https://wa.me/' + url.replace(/[^0-9]/g, '');
                    }
                }

                html += `<a href="${url}" target="_blank" style="display: inline-flex; justify-content: center; align-items: center; background-color: #111827; color: white; width: 35px; height: 35px; border-radius: 50%; text-decoration: none; transition: all 0.2s; margin: 0 4px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);" onmouseover="this.style.transform='translateY(-3px) scale(1.1)';" onmouseout="this.style.transform='translateY(0) scale(1)';">
                        <i class="${availableIcons[plat].icon}" style="font-size: 18px;"></i>
                     </a>`;
            }
        });

        liveContainer.innerHTML = html;

        const liveWrapper = document.getElementById('live_' + elementId);
        if (liveWrapper) {
            liveWrapper.style.display = html !== '' ? 'block' : 'none';
        }
    }

    function saveDynamicSocialMedia(elementId) {
        const blockEl = document.getElementById(elementId);
        const dbId = blockEl.getAttribute('data-db-id');
        const urls = document.getElementById('micrositeEditorUrls');
        const storeUrl = urls.getAttribute('data-route-social-store');

        const platformsData = {};
        const inputs = document.querySelectorAll(`#social_platforms_list_${elementId} .platform-input-trigger`);

        let hasValidationError = false;
        let errorMessage = '';

        const validators = {
            'linkedin': /linkedin\.com/i,
            'reddit': /reddit\.com/i,
            'instagram': /instagram\.com/i,
            'facebook': /facebook\.com/i,
            'youtube': /youtube\.com/i,
            'whatsapp': /^(\+?\d{9,15})$|whatsapp\.com|wa\.me/i,
            'telegram': /telegram\.me|t\.me|^@?[a-zA-Z0-9_]+$/i,
            'tiktok': /tiktok\.com/i,
            'twitter': /x\.com|twitter\.com/i,
            'email': /^[^@\s]+@[^@\s]+\.[^@\s]+$|mailto:/i
        };

        inputs.forEach(input => {
            const plat = input.getAttribute('data-platform');
            let val = input.value.trim();
            const container = input.closest('.platform-input-container');

            // Bersihkan error lama jika ada
            const existingError = container.querySelector('.social-error-msg');
            if (existingError) {
                existingError.remove();
            }

            if (val !== '') {
                if (validators[plat] && !validators[plat].test(val)) {
                    hasValidationError = true;
                    const platName = plat.charAt(0).toUpperCase() + plat.slice(1);
                    errorMessage = `Ada format link yang tidak sesuai. Silakan periksa tanda merah.`;

                    input.style.border = '1px solid #ef4444';
                    input.style.backgroundColor = '#fef2f2'; // Warna merah sangat muda

                    // Tambahkan teks peringatan di bawah input
                    const errorSpan = document.createElement('div');
                    errorSpan.className = 'social-error-msg';
                    errorSpan.style.color = '#ef4444';
                    errorSpan.style.fontSize = '12px';
                    errorSpan.style.marginTop = '6px';
                    errorSpan.style.fontWeight = '500';
                    errorSpan.innerHTML = `<i class="fas fa-triangle-exclamation"></i>  Format link/username ${platName} tidak valid`;
                    container.appendChild(errorSpan);
                } else {
                    input.style.border = '';
                    input.style.backgroundColor = '';
                    platformsData[plat] = val;
                }
            } else {
                input.style.border = '';
                input.style.backgroundColor = '';
            }
        });

        if (hasValidationError) {
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                text: errorMessage
            });
            return;
        }

        const btnSubmit = blockEl.querySelector('.btn-submit');
        const originalText = btnSubmit.innerHTML;
        btnSubmit.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Menyimpan...';
        btnSubmit.disabled = true;

        fetch(storeUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                element_id: dbId,
                platforms: platformsData
            })
        })
            .then(response => response.json())
            .then(data => {
                btnSubmit.innerHTML = originalText;
                btnSubmit.disabled = false;

                if (data.success) {
                    if (!dbId && data.id) {
                        blockEl.setAttribute('data-db-id', data.id);
                    }

                    toggleSocialEditForm(elementId);
                    updateSocialPreview(elementId);

                    const saveOrderPromise = saveElementsOrder();
                    if (saveOrderPromise) {
                        saveOrderPromise.then(() => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Media sosial berhasil disimpan',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        }).catch(() => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Media sosial berhasil disimpan',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        });
                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Media sosial berhasil disimpan',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                } else {
                    Swal.fire('Error', 'Gagal menyimpan media sosial', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                btnSubmit.innerHTML = originalText;
                btnSubmit.disabled = false;
                Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
            });
    }

    function removeDynamicSocialMedia(elementId) {
        const blockEl = document.getElementById(elementId);
        const dbId = blockEl.getAttribute('data-db-id');
        const urls = document.getElementById('micrositeEditorUrls');
        const deleteUrl = urls.getAttribute('data-route-social-delete');

        if (!dbId) {
            blockEl.remove();
            const liveEl = document.getElementById('live_' + elementId);
            if (liveEl) liveEl.remove();
            saveElementsOrder();
            return;
        }

        // Show delete confirmation
        window.confirmDeleteCallback = function () {
            const icon = blockEl.querySelector('.btn-delete-icon');
            const oldHtml = icon.innerHTML;
            icon.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            icon.disabled = true;

            fetch(`${deleteUrl}/${dbId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        blockEl.remove();
                        const liveEl = document.getElementById('live_' + elementId);
                        if (liveEl) liveEl.remove();
                        saveElementsOrder();

                        Swal.fire({
                            icon: 'success',
                            title: 'Dihapus',
                            text: 'Elemen media sosial berhasil dihapus',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    } else {
                        icon.innerHTML = oldHtml;
                        icon.disabled = false;
                        Swal.fire('Error', 'Gagal menghapus elemen', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    icon.innerHTML = oldHtml;
                    icon.disabled = false;
                    Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
                });
        };

        const modal = document.getElementById('customDeleteConfirmModal');
        const title = document.getElementById('customDeleteConfirmTitle');
        title.textContent = "Apakah Anda yakin ingin menghapus elemen media sosial ini?";
        modal.classList.add('active');
    }
    // NEW SOCIAL MEDIA LOGIC
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

    function addSocialPlatformToForm(elementId, platform) {
        // Check if it already exists
        if (document.getElementById('platform_item_' + platform + '_' + elementId)) {
            closeSocialPlatformSelector();
            return; // Already added
        }

        const availablePlatforms = {
            'linkedin': { icon: 'fab fa-linkedin', color: '#0077b5', name: 'LinkedIn', label: 'URL Profil LinkedIn', placeholder: 'contoh: https://linkedin.com/in/username' },
            'reddit': { icon: 'fab fa-reddit', color: '#FF4500', name: 'Reddit', label: 'URL atau Username Reddit', placeholder: 'contoh: https://reddit.com/user/username' },
            'instagram': { icon: 'fab fa-instagram', color: '#E1306C', name: 'Instagram', label: 'URL atau Username Instagram', placeholder: 'contoh: https://instagram.com/username' },
            'facebook': { icon: 'fab fa-facebook', color: '#1877F2', name: 'Facebook', label: 'URL Facebook', placeholder: 'contoh: https://facebook.com/username' },
            'youtube': { icon: 'fab fa-youtube', color: '#FF0000', name: 'YouTube', label: 'URL Channel YouTube', placeholder: 'contoh: https://youtube.com/c/username' },
            'whatsapp': { icon: 'fab fa-whatsapp', color: '#25D366', name: 'WhatsApp', label: 'Nomor WhatsApp (dengan kode negara)', placeholder: 'contoh: 628123456789' },
            'telegram': { icon: 'fab fa-telegram', color: '#0088cc', name: 'Telegram', label: 'Username Telegram (tanpa @)', placeholder: 'contoh: username_anda' },
            'tiktok': { icon: 'fab fa-tiktok', color: '#000000', name: 'TikTok', label: 'Username atau URL TikTok', placeholder: 'contoh: https://tiktok.com/@username' },
            'twitter': { icon: 'fab fa-x-twitter', color: '#000000', name: 'X (Twitter)', label: 'URL atau Username X (Twitter)', placeholder: 'contoh: https://x.com/username' },
            'email': { icon: 'fas fa-envelope', color: '#ea4335', name: 'Email', label: 'Alamat Email', placeholder: 'contoh: email@anda.com' },
        };

        const plat = availablePlatforms[platform];
        if (!plat) return;

        let template = document.getElementById('social-platform-item-template').innerHTML;
        template = template.replace(/__ELEMENT_ID__/g, elementId);
        template = template.replace(/__PLATFORM__/g, platform);
        template = template.replace(/__ICON_CLASS__/g, plat.icon);
        template = template.replace(/__COLOR__/g, plat.color);
        template = template.replace(/__PLATFORM_NAME__/g, plat.name);
        template = template.replace(/__LABEL__/g, plat.label);
        template = template.replace(/__PLACEHOLDER__/g, plat.placeholder);

        const list = document.getElementById('social_platforms_list_' + elementId);
        if (list) {
            list.insertAdjacentHTML('beforeend', template);
        }

        closeSocialPlatformSelector();

        // update preview container immediately (even though empty, it sets up layout)
        updateSocialPreview(elementId);
    }

    function removeSocialPlatformFromForm(elementId, platform) {
        const item = document.getElementById('platform_item_' + platform + '_' + elementId);
        if (item) {
            item.remove();
            updateSocialPreview(elementId);
        }
    }

    // Redesigned Social Platform Modal logic
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
                // Newly selected, add it
                addSocialPlatformToForm(elementId, platform);
            } else if (!isSelected && wasSelected) {
                // Deselected, remove it
                removeSocialPlatformFromForm(elementId, platform);
            }

            // Cleanup classes and attributes
            btn.classList.remove('selected');
            btn.removeAttribute('data-originally-selected');
        });

        closeSocialPlatformSelector();
    }



    // ==========================================
    // EVENT DELEGATION SYSTEM (UNOBTRUSIVE JS)
    // ==========================================
    document.addEventListener('click', function (e) {
        let target;

        if (e.target.closest('.js-stop-propagation')) {
            e.stopPropagation();
        }

        if ((target = e.target.closest('.js-toggle-edit-form'))) {
            const type = target.dataset.type;
            const id = target.dataset.targetId;
            const forceOpen = target.dataset.forceOpen === 'true';

            if (type === 'Profile') toggleProfileEditForm(forceOpen);
            else if (type === 'Image') toggleImageEditForm(id, forceOpen);
            else if (type === 'Divider') toggleDividerEditForm(id, forceOpen);
            else if (type === 'Text') toggleTextEditForm(id, forceOpen);
            else if (type === 'Video') toggleVideoEditForm(id, forceOpen);
            else if (type === 'Social') toggleSocialEditForm(id, forceOpen);
            else if (type === 'DigitalProduct') toggleDigitalProductEditForm(id, forceOpen);
        }

        if ((target = e.target.closest('.js-remove-element'))) {
            e.stopPropagation();
            const type = target.dataset.type;
            const id = target.dataset.targetId;
            if (type === 'Element') removeDynamicElement(id);
            else if (type === 'Divider') removeDynamicDivider(id);
            else if (type === 'Text') removeDynamicText(id);
            else if (type === 'Video') removeDynamicVideo(id);
            else if (type === 'SocialMedia') removeDynamicSocialMedia(id);
        }

        if ((target = e.target.closest('.js-save-element'))) {
            const type = target.dataset.type;
            const id = target.dataset.targetId;

            if (type === 'Element') saveDynamicElement(id);
            else if (type === 'Divider') saveDynamicDivider(id);
            else if (type === 'Text') saveDynamicText(id);
            else if (type === 'Video') saveDynamicVideo(id);
            else if (type === 'SocialMedia') saveDynamicSocialMedia(id);
        }

        if ((target = e.target.closest('.js-adjust-divider-size'))) {
            if (typeof adjustDividerSize === 'function') {
                adjustDividerSize(target.dataset.targetId, parseInt(target.dataset.step, 10));
            }
        }

        if ((target = e.target.closest('.js-exec-cmd'))) {
            if (typeof execCmd === 'function') {
                execCmd(target.dataset.targetId, target.dataset.cmd);
            }
        }

        if ((target = e.target.closest('.js-apply-custom-size'))) {
            if (typeof applyCustomSize === 'function') {
                applyCustomSize(target.dataset.targetId);
            }
        }

        if ((target = e.target.closest('.js-open-social-selector'))) {
            if (typeof openSocialPlatformSelector === 'function') {
                openSocialPlatformSelector(target.dataset.targetId);
            }
        }

        if ((target = e.target.closest('.js-remove-social-platform'))) {
            if (typeof removeSocialPlatformFromForm === 'function') {
                removeSocialPlatformFromForm(target.dataset.targetId, target.dataset.platform);
            }
        }

        if ((target = e.target.closest('.js-toggle-social-selection'))) {
            if (typeof toggleSocialPlatformSelection === 'function') {
                toggleSocialPlatformSelection(target);
            }
        }

        if ((target = e.target.closest('.js-close-social-selector'))) {
            if (typeof closeSocialPlatformSelector === 'function') {
                closeSocialPlatformSelector();
            }
        }

        if ((target = e.target.closest('.js-finish-social-selection'))) {
            if (typeof finishSocialPlatformSelection === 'function') {
                finishSocialPlatformSelection();
            }
        }

        if ((target = e.target.closest('.js-close-delete-modal'))) {
            if (typeof closeDeleteConfirmModal === 'function') closeDeleteConfirmModal();
        }

        if ((target = e.target.closest('.js-confirm-delete-modal'))) {
            if (typeof window.confirmDeleteCallback === 'function' && window.confirmDeleteCallback) {
                window.confirmDeleteCallback();
            }
            if (typeof closeDeleteConfirmModal === 'function') closeDeleteConfirmModal();
        }

        if ((target = e.target.closest('.js-format-profile-text'))) {
            if (typeof formatText === 'function') {
                formatText(target.dataset.cmd);
            }
        }

        if ((target = e.target.closest('.js-copy-url'))) {
            if (typeof copyToClipboard === 'function') {
                copyToClipboard(target.dataset.url);
            }
        }

        if ((target = e.target.closest('.js-prevent-default'))) {
            e.preventDefault();
        }
    });

    document.addEventListener('change', function (e) {
        if (e.target.matches('.js-toggle-visibility')) {
            if (typeof toggleElementVisibility === 'function') {
                toggleElementVisibility(e.target.dataset.targetId, e.target);
            }
        }

        if (e.target.matches('.js-preview-image')) {
            if (typeof previewDynamicImage === 'function') {
                previewDynamicImage(e.target, e.target.dataset.targetId);
            }
        }

        if (e.target.matches('.js-change-divider-type')) {
            const id = e.target.dataset.targetId;
            const input = document.getElementById('dividerType_' + id);
            if (input) input.value = e.target.value;
            if (typeof updateDividerPreview === 'function') updateDividerPreview(id);
            if (typeof updateSegmentedControl === 'function') updateSegmentedControl(e.target);
        }

        if (e.target.matches('.js-exec-cmd-value')) {
            if (typeof execCmd === 'function') {
                execCmd(e.target.dataset.targetId, e.target.dataset.cmd, e.target.value);
            }
        }

        if (e.target.matches('.js-change-text-size')) {
            if (typeof changeTextSize === 'function') {
                changeTextSize(e.target.dataset.targetId, e.target.value);
            }
        }

        if (e.target.matches('.js-apply-custom-size-input')) {
            if (typeof applyCustomSize === 'function') {
                applyCustomSize(e.target.dataset.targetId);
            }
        }

        if (e.target.matches('.js-update-video-preview')) {
            if (typeof updateVideoPreview === 'function') {
                updateVideoPreview(e.target.dataset.targetId);
            }
        }

        if (e.target.matches('.js-update-social-preview')) {
            if (typeof updateSocialPreview === 'function') {
                updateSocialPreview(e.target.dataset.targetId);
            }
        }

        if (e.target.matches('.js-preview-profile-avatar')) {
            if (typeof previewProfileAvatar === 'function') {
                previewProfileAvatar(e.target);
            }
        }

        if (e.target.matches('.js-update-profile-shape')) {
            if (typeof updateProfileShape === 'function') {
                updateProfileShape(e.target.dataset.shape);
            }
        }

        if (e.target.matches('.js-preview-profile-banner')) {
            if (typeof previewProfileBanner === 'function') {
                previewProfileBanner(e.target);
            }
        }

        if (e.target.matches('.js-format-profile-text-val')) {
            if (typeof formatText === 'function') {
                formatText(e.target.dataset.cmd, e.target.value, e.target.dataset.target);
            }
        }
    });

    document.addEventListener('input', function (e) {
        if (e.target.matches('.js-update-image-link')) {
            if (typeof updateDynamicImageLink === 'function') {
                updateDynamicImageLink(e.target.dataset.targetId, e.target.value);
            }
        }

        if (e.target.matches('.js-update-divider-preview')) {
            if (typeof updateDividerPreview === 'function') {
                updateDividerPreview(e.target.dataset.targetId);
            }
        }

        if (e.target.matches('.js-update-text-preview')) {
            if (typeof updateTextPreview === 'function') {
                updateTextPreview(e.target.dataset.targetId);
            }
        }

        if (e.target.matches('.js-update-video-preview')) {
            if (typeof updateVideoPreview === 'function') {
                updateVideoPreview(e.target.dataset.targetId);
            }
        }

        if (e.target.matches('.js-format-profile-text-val')) {
            if (typeof formatText === 'function') {
                formatText(e.target.dataset.cmd, e.target.value, e.target.dataset.target);
            }
        }
    });

    document.addEventListener('keyup', function (e) {
        if (e.target.matches('.js-update-social-preview')) {
            if (typeof updateSocialPreview === 'function') {
                updateSocialPreview(e.target.dataset.targetId);
            }
        }

        if (e.target.matches('.js-sync-profile-name')) {
            if (typeof syncProfileName === 'function') syncProfileName();
            if (typeof updateLiveProfileName === 'function') updateLiveProfileName(e.target.innerHTML);
        }

        if (e.target.matches('.js-sync-profile-bio')) {
            if (typeof syncProfileBio === 'function') syncProfileBio();
            if (typeof updateLiveProfileBio === 'function') updateLiveProfileBio(e.target.innerHTML);
        }
    });


    // Expose functions globally for remaining inline handlers in index.blade.php
    window.copyToClipboard = copyToClipboard;
    window.openNewMicrositeModal = openNewMicrositeModal;
    window.closeNewMicrositeModal = closeNewMicrositeModal;
    window.selectPurposeCard = selectPurposeCard;
    window.goToStep = goToStep;
    window.toggleAddElementPanel = toggleAddElementPanel;
    window.closeAllEditForms = closeAllEditForms;
    window.toggleProfileEditForm = toggleProfileEditForm;
    window.previewProfileBanner = previewProfileBanner;
    window.previewProfileAvatar = previewProfileAvatar;
    window.updateProfileShape = updateProfileShape;
    window.updateLiveProfileName = updateLiveProfileName;
    window.updateLiveProfileBio = updateLiveProfileBio;
    window.previewDynamicImage = previewDynamicImage;
    window.removeDynamicElement = removeDynamicElement;
    window.saveDynamicElement = saveDynamicElement;
    window.saveElementsOrder = saveElementsOrder;
    window.bindDynamicDropzone = bindDynamicDropzone;
    window.initPageEvents = initPageEvents;
    window.showSuccessToast = showSuccessToast;
    window.initElementDragAndDrop = initElementDragAndDrop;
    window.syncPhonePreviewOrder = syncPhonePreviewOrder;
    window.updatePhonePreviewVisibility = updatePhonePreviewVisibility;
    window.formatText = formatText;
    window.syncProfileName = syncProfileName;
    window.syncProfileBio = syncProfileBio;
    window.showDeleteConfirmModal = showDeleteConfirmModal;
    window.closeDeleteConfirmModal = closeDeleteConfirmModal;
    window.addGambarElement = addGambarElement;
    window.toggleImageEditForm = toggleImageEditForm;
    window.updateDynamicImageLink = updateDynamicImageLink;
    window.addDividerElement = addDividerElement;
    window.toggleDividerEditForm = toggleDividerEditForm;
    window.adjustDividerSize = adjustDividerSize;
    window.updateDividerPreview = updateDividerPreview;
    window.updateSegmentedControl = updateSegmentedControl;
    window.saveDynamicDivider = saveDynamicDivider;
    window.removeDynamicDivider = removeDynamicDivider;
    window.addTextElement = addTextElement;
    window.toggleTextEditForm = toggleTextEditForm;
    window.execCmd = execCmd;
    window.changeTextSize = changeTextSize;
    window.applyCustomSize = applyCustomSize;
    window.replaceFontSize = replaceFontSize;
    window.updateTextPreview = updateTextPreview;
    window.saveDynamicText = saveDynamicText;
    window.removeDynamicText = removeDynamicText;
    window.addVideoElement = addVideoElement;
    window.toggleVideoEditForm = toggleVideoEditForm;
    window.updateVideoPreview = updateVideoPreview;
    window.saveDynamicVideo = saveDynamicVideo;
    window.removeDynamicVideo = removeDynamicVideo;
    window.toggleElementVisibility = toggleElementVisibility;
    window.addSocialMediaElement = addSocialMediaElement;
    window.toggleSocialEditForm = toggleSocialEditForm;
    window.toggleSocialInput = toggleSocialInput;
    window.updateSocialPreview = updateSocialPreview;
    window.saveDynamicSocialMedia = saveDynamicSocialMedia;
    window.removeDynamicSocialMedia = removeDynamicSocialMedia;
    window.openSocialPlatformSelector = openSocialPlatformSelector;
    window.closeSocialPlatformSelector = closeSocialPlatformSelector;
    window.addSocialPlatformToForm = addSocialPlatformToForm;
    window.removeSocialPlatformFromForm = removeSocialPlatformFromForm;
    window.toggleSocialPlatformSelection = toggleSocialPlatformSelection;
    window.finishSocialPlatformSelection = finishSocialPlatformSelection;

})();

function deleteDynamicDigitalProduct(id) {
    showDeleteConfirmModal('Yakin ingin menghapus Produk Digital ini?', function() {
        const routeBase = document.getElementById('micrositeEditorUrls').dataset.routeImageStore; // We will use base route but replace
        // Since we don't have a dataset URL, we'll hardcode the base for now or use window location

        fetch('/admin/elements/digital-product/' + id, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).then(res => res.json())
            .then(data => {
                if (data.success) {
                    const block = document.querySelector(`.draggable-element-block[data-db-id="${id}"][data-element-type="digital_product"]`);
                    if (block) block.remove();

                    const liveEl = document.getElementById('live_digitalproduct_' + id);
                    if (liveEl) liveEl.remove();

                    syncPhonePreviewOrder();
                    saveElementsOrder();
                } else {
                    alert('Gagal menghapus produk dari database.');
                }
            }).catch(err => {
                console.error(err);
                alert('Terjadi kesalahan jaringan.');
            });
    });
}

function toggleDigitalProductEditForm(elementId, forceOpen = false) {
    const formBody = document.getElementById('formBody_' + elementId);
    const btnText = document.getElementById('btnText_' + elementId);
    if (!formBody) return;

    if (formBody.classList.contains('open') && !forceOpen) {
        formBody.style.maxHeight = '0px';
        formBody.style.opacity = '0';
        formBody.style.marginTop = '0px';
        formBody.classList.remove('open');
        if (btnText) btnText.innerText = 'Detail';
    } else {
        closeAllEditForms();
        formBody.style.maxHeight = formBody.scrollHeight + 100 + 'px';
        formBody.style.opacity = '1';
        formBody.style.marginTop = '16px';
        formBody.classList.add('open');
        if (btnText) btnText.innerText = 'Tutup';

        const card = formBody.closest('.block-item-card');
        if (card) {
            card.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }
}
