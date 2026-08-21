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
        reader.onload = function(e) {
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
        reader.onload = function(e) {
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
        reader.onload = function(e) {
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
    
    if(confirm(`Hapus elemen ${label} ini?`)) {
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
    }
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
    if(btn) {
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
    if(!list) return;
    const blocks = list.querySelectorAll('.draggable-element-block');
    let order = [];
    blocks.forEach(block => {
        const type = block.getAttribute('data-element-type');
        if (type === 'profile') {
            order.push('profile');
        } else if (type === 'image') {
            const dbId = block.getAttribute('data-db-id');
            if (dbId) order.push('image_' + dbId);
        } else if (type === 'divider') {
            const dbId = block.getAttribute('data-db-id');
            if (dbId) order.push('divider_' + dbId);
        } else if (type === 'text') {
            const dbId = block.getAttribute('data-db-id');
            if (dbId) order.push('text_' + dbId);
        }
    });

    const blocksOrderStr = order.join(',');

    fetch(document.getElementById('micrositeEditorUrls').dataset.routeOrderUpdate, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ blocks_order: blocksOrderStr })
    });
}

function bindDynamicDropzone(elementId) {
    const block = document.getElementById(elementId);
    if(!block) return;
    const zone = block.querySelector('.dynamic-dropzone');
    const input = zone.querySelector('input[type="file"]');
    if(input && zone) {
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
                el = document.getElementById('profileBlockCard');
            } else if (blockId.startsWith('image_')) {
                const dbId = blockId.split('_')[1];
                el = document.querySelector(`.draggable-element-block[data-db-id="${dbId}"][data-element-type="image"]`);
            } else if (blockId.startsWith('divider_')) {
                const dbId = blockId.split('_')[1];
                el = document.querySelector(`.draggable-element-block[data-db-id="${dbId}"][data-element-type="divider"]`);
            } else if (blockId.startsWith('text_')) {
                const dbId = blockId.split('_')[1];
                el = document.querySelector(`.draggable-element-block[data-db-id="${dbId}"][data-element-type="text"]`);
            }
            if (el) {
                list.appendChild(el);
            }
        });
    }

    initElementDragAndDrop();
    syncPhonePreviewOrder();
    updatePhonePreviewVisibility();

    // Initialize drag and drop visual states for upload zones
    const dropzones = document.querySelectorAll('.upload-dropzone');
    dropzones.forEach(zone => {
        const input = zone.querySelector('input[type="file"]');
        if(input && !input.dataset.dragbound) {
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
        profileForm.addEventListener('submit', function(e) {
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

window.onclick = function(event) {
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
        } else if (type === 'image' || type === 'divider') {
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
    if(nameEditor) updateLiveProfileName(nameEditor.innerHTML);
    
    const bioEditor = document.getElementById('editorProfileBio');
    if(bioEditor) updateLiveProfileBio(bioEditor.innerHTML);
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
    if(btn) {
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
        if(btn) {
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
        if(btn) {
            btn.innerHTML = 'Simpan';
            btn.disabled = false;
        }
        console.error(err);
        alert('Terjadi kesalahan saat menyimpan.');
    });
}

function removeDynamicDivider(id) {
    if (!confirm('Yakin ingin menghapus pembatas ini?')) return;
    
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
    }
}

function execCmd(id, command, value = null) {
    const editor = document.getElementById('editorContent_' + id);
    if (editor) {
        editor.focus();
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
        execCmd(id, 'fontSize', 7);
        replaceFontSize(id, input.value + 'px');
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
    if (editor && liveDiv) {
        liveDiv.innerHTML = editor.innerHTML;
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
    if(btn) {
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
        if(btn) {
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
        if(btn) {
            btn.innerHTML = 'Simpan';
            btn.disabled = false;
        }
        console.error(err);
        alert('Terjadi kesalahan saat menyimpan.');
    });
}

function removeDynamicText(id) {
    if (!confirm('Yakin ingin menghapus teks ini?')) return;
    
    const block = document.getElementById(id);
    const liveBlock = document.getElementById('live_' + id);
    const dbId = block.getAttribute('data-db-id');
    
    if (dbId) {
        const url = (document.getElementById('micrositeEditorUrls').dataset.routeTextStore || '/admin/elements/text') + '/' + dbId;
        // The store url doesn't have ID, so we use string manipulation or assume /text/{id}
        const deleteUrl = url.replace('/text', '/text/' + dbId);
        
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
        });
    } else {
        block.remove();
        if (liveBlock) liveBlock.remove();
        if (typeof saveElementsOrder === 'function') saveElementsOrder();
    }
}
