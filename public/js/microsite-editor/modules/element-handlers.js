/**
 * Microsite Editor - Element Handlers Module
 * Handles creation, form toggling, editing, saving, deletion, visibility, and emoji logic for all element types.
 */
(function () {
    'use strict';

    window.MicrositeBuilder = window.MicrositeBuilder || {};

    let imageElementCounter = 0;

    /**
     * Helper to get CSRF token
     */
    function getCsrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    /**
     * Helper to get URLs dataset
     */
    function getUrlsDataset() {
        const container = document.getElementById('micrositeEditorUrls');
        return container ? container.dataset : {};
    }

    /**
     * Toggle Add Element Side Panel
     */
    window.MicrositeBuilder.toggleAddElementPanel = function () {
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

            if (icon) icon.className = 'fas fa-plus-circle';
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

            if (icon) icon.className = 'fas fa-chevron-up';
            if (text) text.innerText = 'Tutup Panel Element';
        }
    };

    /**
     * Close all active element edit forms except optionally specified ID
     */
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

        const allEditForms = document.querySelectorAll('.edit-form-body.open');
        allEditForms.forEach(form => {
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

    /**
     * Toggle Profile Edit Form
     */
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
            closeAllEditForms('profile');
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

    /**
     * Generic form toggle helper for standard blocks
     */
    function toggleGenericEditForm(elementId, extraHeight = 300, forceOpen = false) {
        const formBody = document.getElementById('formBody_' + elementId);
        const btnText = document.getElementById('btnText_' + elementId);
        if (!formBody) return;

        const isOpen = formBody.classList.contains('open');
        if (isOpen && !forceOpen) {
            formBody.style.maxHeight = '0px';
            formBody.style.opacity = '0';
            formBody.style.marginTop = '0px';
            formBody.classList.remove('open');
            if (btnText) btnText.innerText = 'Edit';
        } else {
            closeAllEditForms(elementId);
            formBody.classList.add('open');
            formBody.style.marginTop = '8px';
            formBody.style.maxHeight = (formBody.scrollHeight + extraHeight) + 'px';
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

    function toggleImageEditForm(elementId, forceOpen = false) {
        toggleGenericEditForm(elementId, 500, forceOpen);
    }

    function toggleDividerEditForm(id, forceOpen = false) {
        toggleGenericEditForm(id, 100, forceOpen);
    }

    function toggleTextEditForm(id, forceOpen = false) {
        toggleGenericEditForm(id, 300, forceOpen);
    }

    function toggleVideoEditForm(id, forceOpen = false) {
        toggleGenericEditForm(id, 300, forceOpen);
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
            closeAllEditForms(elementId);
            formBody.style.maxHeight = (formBody.scrollHeight + 100) + 'px';
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

    function toggleSocialEditForm(elementId, isFromPreview = false) {
        let blockId = elementId;
        if (elementId.startsWith('live_')) blockId = elementId.substring(5);

        if (isFromPreview) {
            const blockEl = document.getElementById(blockId);
            if (blockEl) {
                blockEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }

        const formBody = document.getElementById('formBody_' + blockId);
        if (!formBody) return;

        if (formBody.style.maxHeight === '0px' || formBody.style.maxHeight === '0' || formBody.style.maxHeight === '') {
            closeAllEditForms(blockId);
            formBody.classList.add('open');
            formBody.style.maxHeight = (formBody.scrollHeight + 500) + 'px';
            formBody.style.opacity = '1';
            formBody.style.marginTop = '12px';

            document.querySelectorAll('.block-item-card.active').forEach(el => el.classList.remove('active'));
            const card = document.getElementById(blockId)?.querySelector('.block-item-card');
            if (card) card.classList.add('active');
        } else {
            formBody.classList.remove('open');
            formBody.style.maxHeight = '0px';
            formBody.style.opacity = '0';
            formBody.style.marginTop = '0px';

            const card = document.getElementById(blockId)?.querySelector('.block-item-card');
            if (card) card.classList.remove('active');
        }
    }

    // ==========================================
    // 1. IMAGE ELEMENT
    // ==========================================
    window.MicrositeBuilder.addGambarElement = function () {
        if (typeof window.MicrositeBuilder.toggleAddElementPanel === 'function') {
            window.MicrositeBuilder.toggleAddElementPanel();
        }

        imageElementCounter++;
        const elementId = 'imageBlock_' + new Date().getTime();
        const list = document.getElementById('elementBlocksList');

        const template = document.getElementById('image-block-template');
        if (!template) return;
        const clone = template.content.cloneNode(true);
        const tempDiv = document.createElement('div');
        tempDiv.appendChild(clone);
        const html = tempDiv.innerHTML.replace(/__ELEMENT_ID__/g, elementId);
        list.insertAdjacentHTML('beforeend', html);

        const phoneContent = document.getElementById('phonePreviewContent');
        if (phoneContent) {
            const liveTemplate = document.getElementById('image-live-template');
            if (liveTemplate) {
                const liveClone = liveTemplate.content.cloneNode(true);
                const liveTempDiv = document.createElement('div');
                liveTempDiv.appendChild(liveClone);
                const liveHtml = liveTempDiv.innerHTML.replace(/__ELEMENT_ID__/g, elementId);
                phoneContent.insertAdjacentHTML('beforeend', liveHtml);
            }
        }

        if (typeof window.initElementDragAndDrop === 'function') window.initElementDragAndDrop();
        if (typeof window.bindDynamicDropzone === 'function') window.bindDynamicDropzone(elementId);
        if (typeof window.syncPhonePreviewOrder === 'function') window.syncPhonePreviewOrder();

        setTimeout(() => toggleImageEditForm(elementId), 50);
    };

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
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function saveDynamicElement(elementId) {
        const block = document.getElementById(elementId);
        if (!block) return;
        const fileInput = block.querySelector('input[type="file"]');
        const linkInput = document.getElementById('link_' + elementId);
        const urls = getUrlsDataset();

        let formData = new FormData();
        formData.append('appearance_id', urls.appearanceId);
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

        const btn = block.querySelector('.btn-save-element') || block.querySelector('button[onclick^="saveDynamicElement"]');
        if (btn) {
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
            btn.disabled = true;
        }

        fetch(urls.routeImageStore, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': getCsrfToken()
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
                    if (typeof window.syncPhonePreviewOrder === 'function') window.syncPhonePreviewOrder();
                    if (typeof window.saveElementsOrder === 'function') window.saveElementsOrder();
                    toggleImageEditForm(elementId);
                    if (typeof window.showSuccessToast === 'function') window.showSuccessToast('Elemen gambar berhasil disimpan!');
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

    function removeDynamicElement(elementId) {
        const block = document.getElementById(elementId);
        if (!block) return;

        const type = block.getAttribute('data-element-type');
        const label = type === 'divider' ? 'pembatas' : 'gambar';

        const showModal = typeof window.showDeleteConfirmModal === 'function' ? window.showDeleteConfirmModal : ((t, cb) => { if (confirm(t)) cb(); });

        showModal(`Yakin ingin menghapus elemen ${label} ini?`, function () {
            const dbId = block.getAttribute('data-db-id');

            if (dbId) {
                const urls = getUrlsDataset();
                const routeDelete = type === 'divider' ? urls.routeDividerDelete : urls.routeImageDelete;

                fetch(`${routeDelete}/${dbId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': getCsrfToken()
                    }
                }).then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            block.remove();
                            const liveEl = document.getElementById('live_' + elementId);
                            if (liveEl) liveEl.remove();
                            if (typeof window.syncPhonePreviewOrder === 'function') window.syncPhonePreviewOrder();
                            if (typeof window.saveElementsOrder === 'function') window.saveElementsOrder();
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
                if (typeof window.syncPhonePreviewOrder === 'function') window.syncPhonePreviewOrder();
                if (typeof window.saveElementsOrder === 'function') window.saveElementsOrder();
            }
        });
    }

    // ==========================================
    // 2. DIVIDER ELEMENT
    // ==========================================
    window.MicrositeBuilder.addDividerElement = function () {
        if (typeof window.MicrositeBuilder.toggleAddElementPanel === 'function') {
            window.MicrositeBuilder.toggleAddElementPanel();
        }
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

        // Auto-save the default divider to database
        const urls = getUrlsDataset();
        const formData = new FormData();
        formData.append('appearance_id', urls.appearanceId);
        formData.append('type', 'line');
        formData.append('size', '20');

        fetch(urls.routeDividerStore, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': getCsrfToken()
            },
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    newBlock.setAttribute('data-db-id', data.id);
                    if (typeof window.saveElementsOrder === 'function') window.saveElementsOrder();
                }
            })
            .catch(err => console.error(err));
    };

    function saveDynamicDivider(id) {
        const typeVal = document.getElementById('dividerType_' + id)?.value || 'line';
        const sizeVal = document.getElementById('dividerSize_' + id)?.value || '20';
        const block = document.getElementById(id);
        if (!block) return;
        const dbId = block.getAttribute('data-db-id');
        const urls = getUrlsDataset();

        const formData = new FormData();
        formData.append('appearance_id', urls.appearanceId);
        formData.append('type', typeVal);
        formData.append('size', sizeVal);
        if (dbId) {
            formData.append('element_id', dbId);
        }

        const btn = document.querySelector(`#formBody_${id} .btn-save-element`);
        if (btn) {
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
            btn.disabled = true;
        }

        fetch(urls.routeDividerStore, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': getCsrfToken()
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
                    if (typeof window.updateDividerPreview === 'function') window.updateDividerPreview(id);
                    toggleDividerEditForm(id);
                    if (typeof window.saveElementsOrder === 'function') window.saveElementsOrder();
                    if (typeof window.showSuccessToast === 'function') window.showSuccessToast('Pembatas berhasil disimpan!');
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
        const showModal = typeof window.showDeleteConfirmModal === 'function' ? window.showDeleteConfirmModal : ((t, cb) => { if (confirm(t)) cb(); });

        showModal('Yakin ingin menghapus pembatas ini?', function () {
            const block = document.getElementById(id);
            const liveBlock = document.getElementById('live_' + id);
            if (!block) return;
            const dbId = block.getAttribute('data-db-id');

            if (dbId) {
                const urls = getUrlsDataset();
                const url = urls.routeDividerDelete + '/' + dbId;
                fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': getCsrfToken()
                    }
                }).then(res => res.json()).then(data => {
                    if (data.success) {
                        block.remove();
                        if (liveBlock) liveBlock.remove();
                        if (typeof window.saveElementsOrder === 'function') window.saveElementsOrder();
                    }
                });
            } else {
                block.remove();
                if (liveBlock) liveBlock.remove();
                if (typeof window.saveElementsOrder === 'function') window.saveElementsOrder();
            }
        });
    }

    // ==========================================
    // 3. TEXT ELEMENT
    // ==========================================
    window.MicrositeBuilder.addTextElement = function () {
        if (typeof window.MicrositeBuilder.toggleAddElementPanel === 'function') {
            window.MicrositeBuilder.toggleAddElementPanel();
        }
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

        const urls = getUrlsDataset();
        const formData = new FormData();
        formData.append('appearance_id', urls.appearanceId);
        formData.append('content', 'Teks Anda di sini...');

        const url = urls.routeTextStore || '/admin/elements/text';
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': getCsrfToken()
            },
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    newBlock.setAttribute('data-db-id', data.id);
                    if (typeof window.saveElementsOrder === 'function') window.saveElementsOrder();
                }
            })
            .catch(err => console.error(err));
    };

    function saveDynamicText(id) {
        const block = document.getElementById(id);
        if (!block) return;
        const dbId = block.getAttribute('data-db-id');
        const editor = document.getElementById('editorContent_' + id);

        if (!editor) return;

        // Validasi jumlah kata sebelum simpan
        const charError = document.getElementById('charError_' + id);
        const charCount = document.getElementById('charCount_' + id);
        const rawText = editor.innerText.trim();
        const wordCount = rawText === '' ? 0 : rawText.split(/\s+/).filter(word => word.length > 0).length;

        if (wordCount > 250) {
            if (charError) charError.style.display = 'block';
            if (charCount) charCount.style.color = '#ef4444';
            return;
        }

        const urls = getUrlsDataset();
        const formData = new FormData();
        formData.append('appearance_id', urls.appearanceId);
        formData.append('content', editor.innerHTML);
        if (dbId) {
            formData.append('element_id', dbId);
        }

        const hasBtn = document.getElementById('hasButton_' + id);
        const btnText = document.getElementById('buttonText_' + id);
        const btnLink = document.getElementById('buttonLink_' + id);
        const btnColor = document.getElementById('buttonColor_' + id);
        const btnIconType = document.getElementById('buttonIconType_' + id);
        const btnIconEmoji = document.getElementById('buttonIconEmoji_' + id);
        const btnIconUrl = document.getElementById('buttonIconUrl_' + id);
        const btnIconUpload = document.getElementById('buttonIconUpload_' + id);

        if (hasBtn) formData.append('has_button', hasBtn.checked ? 1 : 0);
        if (btnText) formData.append('button_text', btnText.value);
        if (btnLink) formData.append('button_link', btnLink.value);
        if (btnColor) formData.append('button_color', btnColor.value);

        if (btnIconType) {
            formData.append('button_icon_type', btnIconType.value);
            if (btnIconType.value === 'emoji' && btnIconEmoji) formData.append('button_icon_emoji', btnIconEmoji.value);
            if (btnIconType.value === 'url' && btnIconUrl) formData.append('button_icon_url', btnIconUrl.value);
            const btnIconFa = document.getElementById('buttonIconFa_' + id);
            if (btnIconType.value === 'fontawesome' && btnIconFa) formData.append('button_icon_emoji', btnIconFa.value);
            if (btnIconType.value === 'upload' && btnIconUpload && btnIconUpload.files[0]) {
                formData.append('button_icon_upload', btnIconUpload.files[0]);
            }
        }

        const url = urls.routeTextStore || '/admin/elements/text';
        const btn = document.getElementById('btnSaveText_' + id);
        if (btn) {
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
            btn.disabled = true;
        }

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': getCsrfToken()
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
                    if (typeof window.updateTextPreview === 'function') window.updateTextPreview(id);
                    toggleTextEditForm(id);
                    if (typeof window.saveElementsOrder === 'function') window.saveElementsOrder();
                    if (typeof window.showSuccessToast === 'function') window.showSuccessToast('Teks berhasil disimpan!');
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
        const showModal = typeof window.showDeleteConfirmModal === 'function' ? window.showDeleteConfirmModal : ((t, cb) => { if (confirm(t)) cb(); });

        showModal('Yakin ingin menghapus teks ini?', function () {
            const block = document.getElementById(id);
            const liveBlock = document.getElementById('live_' + id);
            if (!block) return;
            const dbId = block.getAttribute('data-db-id');

            if (dbId) {
                const urls = getUrlsDataset();
                const deleteUrl = (urls.routeTextDelete || '/admin/elements/text') + '/' + dbId;

                fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': getCsrfToken()
                    }
                }).then(res => res.json()).then(data => {
                    if (data.success) {
                        block.remove();
                        if (liveBlock) liveBlock.remove();
                        if (typeof window.saveElementsOrder === 'function') window.saveElementsOrder();
                    }
                }).catch(err => console.error('Error delete text', err));
            } else {
                block.remove();
                if (liveBlock) liveBlock.remove();
                if (typeof window.saveElementsOrder === 'function') window.saveElementsOrder();
            }
        });
    }

    // ==========================================
    // 4. VIDEO ELEMENT
    // ==========================================
    window.MicrositeBuilder.addVideoElement = function () {
        if (typeof window.MicrositeBuilder.toggleAddElementPanel === 'function') {
            window.MicrositeBuilder.toggleAddElementPanel();
        }
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

        const urls = getUrlsDataset();
        const formData = new FormData();
        formData.append('appearance_id', urls.appearanceId);
        formData.append('video_url', '');
        formData.append('is_autoplay', '0');

        fetch('/admin/elements/video', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': getCsrfToken()
            },
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    newBlock.setAttribute('data-db-id', data.id);
                    if (typeof window.saveElementsOrder === 'function') window.saveElementsOrder();
                }
            })
            .catch(err => console.error(err));
    };

    function saveDynamicVideo(id) {
        const block = document.getElementById(id);
        if (!block) return;
        const dbId = block.getAttribute('data-db-id');
        const urlInput = document.getElementById('videoUrl_' + id);
        const autoplayToggle = document.getElementById('videoAutoplay_' + id);

        if (!urlInput) return;

        const urls = getUrlsDataset();
        const formData = new FormData();
        formData.append('appearance_id', urls.appearanceId);
        formData.append('video_url', urlInput.value.trim());
        formData.append('is_autoplay', autoplayToggle && autoplayToggle.checked ? '1' : '0');

        if (dbId) {
            formData.append('element_id', dbId);
        }

        const btn = document.getElementById('btnSaveVideo_' + id);
        if (btn) {
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
            btn.disabled = true;
        }

        fetch('/admin/elements/video', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': getCsrfToken()
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
                    if (typeof window.updateVideoPreview === 'function') window.updateVideoPreview(id);
                    toggleVideoEditForm(id);
                    if (typeof window.saveElementsOrder === 'function') window.saveElementsOrder();
                    if (typeof window.showSuccessToast === 'function') window.showSuccessToast('Video berhasil disimpan!');
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
        const showModal = typeof window.showDeleteConfirmModal === 'function' ? window.showDeleteConfirmModal : ((t, cb) => { if (confirm(t)) cb(); });

        showModal('Yakin ingin menghapus video ini?', function () {
            const block = document.getElementById(id);
            const liveBlock = document.getElementById('live_' + id);
            if (!block) return;
            const dbId = block.getAttribute('data-db-id');

            if (dbId) {
                fetch('/admin/elements/video/' + dbId, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': getCsrfToken()
                    }
                }).then(res => res.json()).then(data => {
                    if (data.success) {
                        block.remove();
                        if (liveBlock) liveBlock.remove();
                        if (typeof window.saveElementsOrder === 'function') window.saveElementsOrder();
                    }
                }).catch(err => console.error('Error delete video', err));
            } else {
                block.remove();
                if (liveBlock) liveBlock.remove();
                if (typeof window.saveElementsOrder === 'function') window.saveElementsOrder();
            }
        });
    }

    // ==========================================
    // 5. SOCIAL MEDIA ELEMENT
    // ==========================================
    window.MicrositeBuilder.addSocialMediaElement = function () {
        if (typeof window.MicrositeBuilder.toggleAddElementPanel === 'function') {
            window.MicrositeBuilder.toggleAddElementPanel();
        }
        const tempId = 'socialBlock_' + Date.now();
        const list = document.getElementById('elementBlocksList');

        let blockTemplate = document.getElementById('social-block-template').innerHTML;
        blockTemplate = blockTemplate.replace(/__ELEMENT_ID__/g, tempId);
        list.insertAdjacentHTML('beforeend', blockTemplate);

        let liveTemplate = document.getElementById('social-live-template').innerHTML;
        liveTemplate = liveTemplate.replace(/__ELEMENT_ID__/g, tempId);

        const phoneContent = document.getElementById('phonePreviewContent');
        if (phoneContent) {
            phoneContent.insertAdjacentHTML('beforeend', liveTemplate);
        }

        if (typeof window.saveElementsOrder === 'function') window.saveElementsOrder();

        setTimeout(() => {
            toggleSocialEditForm(tempId);
        }, 100);
    };

    function toggleSocialInput(elementId, platform) {
        const toggle = document.getElementById('toggle_' + platform + '_' + elementId);
        const inputContainer = document.getElementById('input_container_' + platform + '_' + elementId);
        const input = document.getElementById('input_' + platform + '_' + elementId);

        if (toggle && toggle.checked) {
            if (inputContainer) inputContainer.style.display = 'block';
        } else {
            if (inputContainer) inputContainer.style.display = 'none';
            if (input) input.value = '';
        }

        if (typeof window.updateSocialPreview === 'function') window.updateSocialPreview(elementId);
    }

    function addSocialPlatformToForm(elementId, platform) {
        if (document.getElementById('platform_item_' + platform + '_' + elementId)) {
            if (typeof window.closeSocialPlatformSelector === 'function') window.closeSocialPlatformSelector();
            return;
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
            'email': { icon: 'fas fa-envelope', color: '#ea4335', name: 'Email', label: 'Alamat Email', placeholder: 'contoh: email@anda.com' }
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

        if (typeof window.closeSocialPlatformSelector === 'function') window.closeSocialPlatformSelector();
        if (typeof window.updateSocialPreview === 'function') window.updateSocialPreview(elementId);
    }

    function removeSocialPlatformFromForm(elementId, platform) {
        const item = document.getElementById('platform_item_' + platform + '_' + elementId);
        if (item) {
            item.remove();
            if (typeof window.updateSocialPreview === 'function') window.updateSocialPreview(elementId);
        }
    }

    function saveDynamicSocialMedia(elementId) {
        const blockEl = document.getElementById(elementId);
        if (!blockEl) return;
        const dbId = blockEl.getAttribute('data-db-id');
        const urls = document.getElementById('micrositeEditorUrls');
        const storeUrl = urls ? urls.getAttribute('data-route-social-store') : '';

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

            const existingError = container ? container.querySelector('.social-error-msg') : null;
            if (existingError) {
                existingError.remove();
            }

            if (val !== '') {
                if (validators[plat] && !validators[plat].test(val)) {
                    hasValidationError = true;
                    const platName = plat.charAt(0).toUpperCase() + plat.slice(1);
                    errorMessage = `Ada format link yang tidak sesuai. Silakan periksa tanda merah.`;

                    input.style.border = '1px solid #ef4444';
                    input.style.backgroundColor = '#fef2f2';

                    if (container) {
                        const errorSpan = document.createElement('div');
                        errorSpan.className = 'social-error-msg';
                        errorSpan.style.color = '#ef4444';
                        errorSpan.style.fontSize = '12px';
                        errorSpan.style.marginTop = '6px';
                        errorSpan.style.fontWeight = '500';
                        errorSpan.innerHTML = `<i class="fas fa-triangle-exclamation"></i>  Format link/username ${platName} tidak valid`;
                        container.appendChild(errorSpan);
                    }
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
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    text: errorMessage
                });
            } else {
                alert(errorMessage);
            }
            return;
        }

        const btnSubmit = blockEl.querySelector('.btn-submit');
        const originalText = btnSubmit ? btnSubmit.innerHTML : 'Simpan';
        if (btnSubmit) {
            btnSubmit.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Menyimpan...';
            btnSubmit.disabled = true;
        }

        fetch(storeUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken()
            },
            body: JSON.stringify({
                element_id: dbId,
                appearance_id: document.getElementById('micrositeEditorUrls')?.dataset.appearanceId,
                platforms: platformsData
            })
        })
            .then(response => response.json())
            .then(data => {
                if (btnSubmit) {
                    btnSubmit.innerHTML = originalText;
                    btnSubmit.disabled = false;
                }

                if (data.success) {
                    if (!dbId && data.id) {
                        blockEl.setAttribute('data-db-id', data.id);
                    }

                    toggleSocialEditForm(elementId);
                    if (typeof window.updateSocialPreview === 'function') window.updateSocialPreview(elementId);

                    const notifySuccess = () => {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Media sosial berhasil disimpan',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        } else if (typeof window.showSuccessToast === 'function') {
                            window.showSuccessToast('Media sosial berhasil disimpan');
                        }
                    };

                    if (typeof window.saveElementsOrder === 'function') {
                        const promise = window.saveElementsOrder();
                        if (promise && typeof promise.then === 'function') {
                            promise.then(notifySuccess).catch(notifySuccess);
                        } else {
                            notifySuccess();
                        }
                    } else {
                        notifySuccess();
                    }
                } else {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire('Error', 'Gagal menyimpan media sosial', 'error');
                    } else {
                        alert('Gagal menyimpan media sosial');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (btnSubmit) {
                    btnSubmit.innerHTML = originalText;
                    btnSubmit.disabled = false;
                }
                if (typeof Swal !== 'undefined') {
                    Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
                } else {
                    alert('Terjadi kesalahan sistem');
                }
            });
    }

    function removeDynamicSocialMedia(elementId) {
        const blockEl = document.getElementById(elementId);
        if (!blockEl) return;
        const dbId = blockEl.getAttribute('data-db-id');
        const urls = document.getElementById('micrositeEditorUrls');
        const deleteUrl = urls ? urls.getAttribute('data-route-social-delete') : '';

        if (!dbId) {
            blockEl.remove();
            const liveEl = document.getElementById('live_' + elementId);
            if (liveEl) liveEl.remove();
            if (typeof window.saveElementsOrder === 'function') window.saveElementsOrder();
            return;
        }

        const showModal = typeof window.showDeleteConfirmModal === 'function' ? window.showDeleteConfirmModal : ((t, cb) => { if (confirm(t)) cb(); });

        showModal('Apakah Anda yakin ingin menghapus elemen media sosial ini?', function () {
            const icon = blockEl.querySelector('.btn-delete-icon');
            const oldHtml = icon ? icon.innerHTML : '';
            if (icon) {
                icon.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                icon.disabled = true;
            }

            fetch(`${deleteUrl}/${dbId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': getCsrfToken()
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        blockEl.remove();
                        const liveEl = document.getElementById('live_' + elementId);
                        if (liveEl) liveEl.remove();
                        if (typeof window.saveElementsOrder === 'function') window.saveElementsOrder();

                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Dihapus',
                                text: 'Elemen media sosial berhasil dihapus',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        }
                    } else {
                        if (icon) {
                            icon.innerHTML = oldHtml;
                            icon.disabled = false;
                        }
                        if (typeof Swal !== 'undefined') Swal.fire('Error', 'Gagal menghapus elemen', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (icon) {
                        icon.innerHTML = oldHtml;
                        icon.disabled = false;
                    }
                    if (typeof Swal !== 'undefined') Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
                });
        });
    }

    // ==========================================
    // 6. DIGITAL PRODUCT ELEMENT
    // ==========================================
    function deleteDynamicDigitalProduct(id) {
        const showModal = typeof window.showDeleteConfirmModal === 'function' ? window.showDeleteConfirmModal : ((t, cb) => { if (confirm(t)) cb(); });

        showModal('Yakin ingin menghapus Produk Digital ini?', function () {
            fetch('/admin/elements/digital-product/' + id, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': getCsrfToken()
                }
            }).then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const block = document.querySelector(`.draggable-element-block[data-db-id="${id}"][data-element-type="digital_product"]`);
                        if (block) block.remove();

                        const liveEl = document.getElementById('live_digitalproduct_' + id);
                        if (liveEl) liveEl.remove();

                        if (typeof window.syncPhonePreviewOrder === 'function') window.syncPhonePreviewOrder();
                        if (typeof window.saveElementsOrder === 'function') window.saveElementsOrder();
                    } else {
                        alert('Gagal menghapus produk dari database.');
                    }
                }).catch(err => {
                    console.error(err);
                    alert('Terjadi kesalahan jaringan.');
                });
        });
    }

    // ==========================================
    // 7. VISIBILITY TOGGLE FUNCTION
    // ==========================================
    function toggleElementVisibility(elementId, checkboxElement) {
        const block = document.getElementById(elementId);
        if (!block) return;

        const statusText = block.querySelector('.visibility-status-text');
        const isActive = checkboxElement.checked;

        let liveElId = 'live_' + elementId;
        if (block.getAttribute('data-element-type') === 'profile') {
            liveElId = 'liveProfileSection';
        }
        const liveEl = document.getElementById(liveElId);

        // Save to database
        if (elementId && elementId.includes('_')) {
            const parts = elementId.split('_');
            let elementType = parts[0].replace('Block', '');
            const dbId = parts[1];

            fetch('/admin/elements/toggle-visibility', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken()
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
            if (statusText) {
                statusText.innerText = 'Aktif';
                statusText.classList.remove('status-inactive');
                statusText.classList.add('status-active');
            }
            block.classList.remove('block-inactive');
            if (liveEl) {
                liveEl.style.display = liveElId === 'liveProfileSection' ? 'flex' : 'block';
                setTimeout(() => {
                    liveEl.style.transition = 'opacity 0.3s ease';
                    liveEl.style.opacity = '1';
                }, 10);
            }
        } else {
            if (statusText) {
                statusText.innerText = 'Tidak Aktif';
                statusText.classList.remove('status-active');
                statusText.classList.add('status-inactive');
            }
            block.classList.add('block-inactive');
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

    // ==========================================
    // 8. EMOJI DICTIONARY & PICKER
    // ==========================================
    window.MicrositeBuilder.EMOJI_DICTIONARY = [
        { category: "Smileys & Emotion", id: "cat_smileys", icons: ["😀","😃","😄","😁","😆","😅","😂","🤣","😊","😇","😍","🥰","😘","😗","😙","😚","😋","😛","😝","😜","🤪","🤨","🧐","🤓","😎","🤩","🥳","😏","😒","😞","😔","😟","😕","🙁","☹️","😣","😖","😫","😩","🥺","😢","😭","😤","😠","😡","🤬","🤯","😳","🥵","🥶","😱","😨","😰","😥","😓","🤗","🤔","🤭","🤫","🤥","😶","😐","😑","😬","🙄","😯","😦","😧","😮","😲","🥱","😴","🤤","😪","😵","🤐","🥴","🤢","🤮","🤧","😷","🤒","🤕","🤑","🤠","😈","👿","👹","👺","🤡","💩","👻","💀","☠️","👽","👾","🤖","🎃","😺","😸","😹","😻","😼","😽","🙀","😿","😾"] },
        { category: "People & Body", id: "cat_people", icons: ["👋","🤚","🖐️","✋","🖖","👌","🤌","🤏","✌️","🤞","🤟","🤘","🤙","👈","👉","👆","🖕","👇","☝️","👍","👎","✊","👊","🤛","🤜","👏","🙌","👐","🤲","🤝","🙏","✍️","💅","🤳","💪","🦾","🦿","🦵","🦶","👂","🦻","👃","🧠","🫀","🫁","🦷","🦴","👀","👁️","👅","👄","💋","🩸"] },
        { category: "Animals & Nature", id: "cat_animals", icons: ["🐶","🐱","🐭","🐹","🐰","🦊","🐻","🐼","🐻‍❄️","🐨","🐯","🦁","🐮","🐷","🐽","🐸","🐵","🙈","🙉","🙊","🐒","🐔","🐧","🐦","🐤","🐣","🐥","🦆","🦅","🦉","🦇","🐺","🐗","🐴","🦄","🐝","🪱","🐛","🦋","🐌","🐞","🐜","🪰","🪲","🪳","🦟","🦗","🕷️","🕸️","🦂","🐢","🐍","🦎","🦖","🦕","🐙","🦑","🦐","🦞","🦀","🐡","🐠","🐟","🐬","🐳","🐋","鲨","🦈","🦭","🐊","🐅","🐆","🦓","🦍","🦧","🦣","🐘","🦛","🦏","🐪","🐫","🦒","🦘","🦬","🐃","🐂","🐄","🐎","🐖","🐏","🐑","🦙","🐐","🦌","🐕","🐩","🦮","🐕‍🦺","🐈","🐈‍⬛","🪶","🐓","🦃","🦤","🦚","🦜","🦢","🦩","🕊️","🐇","🦝","🦨","🦡","🦫","🦦","🦥","🐁","🐀","🐿️","🦔","🐉","🐲","🌵","🎄","🌲","🌳","🌴","🪵","🌱","🌿","☘️","🍀","🎍","🪴","🎋","🍃","🍂","🍁","🍄","🐚","🪨","🌾","💐","🌷","🌹","🥀","🌺","🌸","🌼","🌻","🌞","🌝","🌛","🌜","🌚","🌕","🌖","🌗","🌘","🌑","🌒","🌓","🌔","🌙","🌎","🌍","🌏","🪐","💫","⭐️","🌟","✨","⚡️","☄️","💥","🔥","🌪️","🌈","☀️","🌤️","⛅️","🌥️","☁️","🌦️","🌧️","⛈️","🌩️","🌨️","❄️","☃️","⛄️","🌬️","💨","💧","💦","☔️","☂️","🌊","🌫️"] },
        { category: "Food & Drink", id: "cat_food", icons: ["🍏","🍎","🍐","🍊","🍋","🍌","🍉","🍇","🍓","🍈","🍒","🍑","🥭","🍍","🥥","🥝","🍅","🍆","🥑","🥦","🥬","🥒","🌶️","🫑","🌽","🥕","🧄","🧅","🥔","🍠","🥐","🥯","🍞","🥖","🥨","🧀","🥚","🍳","🧈","🥞","🧇","🥓","🥩","🍗","🍖","🦴","🌭","🍔","🍟","🍕","🫓","🥪","🥙","🧆","🌮","🌯","🫔","🥗","🥘","🫕","🥫","🍝","🍜","🍲","🍛","🍣","🍱","🥟","🦪","🍤","🍙","🍚","🍘","🍥","🥠","🥮","🍢","🍡","🍧","🍨","🍦","🥧","🧁","🍰","🎂","🍮","🍭","🍬","🍫","🍿","🍩","🍪","🌰","🥜","🍯","🥛","🍼","🫖","☕️","🍵","🧃","🥤","🧋","🍶","🍺","🍻","🥂","🍷","🥃","🍸","🍹","🧉","🍾","🧊","🥄","🍴","🍽️","🥣","🥡","🥢","🧂"] },
        { category: "Travel & Places", id: "cat_travel", icons: ["🚗","🚕","🚙","🚌","🚎","🏎️","🚓","🚑","🚒","🚐","🛻","🚚","🚛","🚜","🦯","🦽","🦼","🛴","🚲","🛵","🏍️","🛺","🚨","🚔","🚍","🚘","🚖","🚡","🚠","🚟","🚃","🚋","🚞","🚝","🚄","🚅","🚈","🚂","🚆","🚇","🚊","🚉","✈️","🛫","🛬","🛩️","💺","🛰️","🚀","🛸","🚁","🛶","⛵️","🚤","🛥️","🛳️","⛴️","🚢","⚓️","🪝","⛽️","🚧","🚦","🚥","🚏","🗺️","🗿","🗽","🗼","🏰","🏯","🏟️","🎡","🎢","🎠","⛲️","⛱️","🏖️","🏝️","🏜️","🌋","⛰️","🏔️","🗻","🏕️","⛺️","🛖","🏠","🏡","🏘️","🏚️","🏗️","🏭","🏢","🏬","🏣","🏤","🏥","🏦","🏨","🏪","🏫","🏩","💒","🏛️","⛪️","🕌","🛕","🕍","⛩️","🕋"] },
        { category: "Objects", id: "cat_objects", icons: ["⌚️","📱","📲","💻","⌨️","🖥️","🖨️","🖱️","🖲️","🕹️","🗜️","💽","💾","💿","📀","📼","📷","📸","📹","🎥","📽️","🎞️","📞","☎️","📟","📠","📺","📻","🎙️","🎚️","🎛️","🧭","⏱️","⏲️","⏰","🕰️","⌛️","⏳","📡","🔋","🔌","💡","🔦","🕯️","🪔","🧯","🛢️","💸","💵","💴","💶","💷","🪙","💰","💳","💎","⚖️","🪜","🧰","🪛","🔧","🔨","⚒️","🛠️","⛏️","🪚","🔩","⚙️","🪤","🧱","⛓️","🧲","🔫","💣","🧨","🪓","🔪","🗡️","⚔️","🛡️","🚬","⚰️","🪦","⚱️","🏺","🔮","📿","🧿","💈","⚗️","🔭","🔬","🕳️","🩹","🩺","💊","💉","🩸","🧬","🦠","🧫","🧪","🌡️","🧹","🪠","🧺","🧻","🚽","🚰","🚿","🛁","🛀","🧼","🪥","🪒","🧽","🪣","🧴","🛎️","🔑","🗝️","🚪","🪑","🛋️","🛏️","🛌","🧸","🪆","🖼️","🪞","🪟","🛍️","🛒","🎁","🎈","🎏","🎀","🪄","🪅","🎊","🎉","🎎","🏮","🎐","🧧","✉️","📩","📨","📧","💌","📥","📤","📦","🏷️","🪧","📪","📫","📬","📭","📮","📯","📜","📃","📄","📑","🧾","📊","📈","📉","🗒️","🗓️","📆","📅","🗑️","📇","🗃️","🗳️","🗄️","📋","📁","📂","🗂️","🗞️","📰","📓","📔","📒","📕","📗","📘","📙","📚","📖","🔖","🧷","🔗","📎","🖇️","📐","📏","🧮","📌","📍","✂️","🖊️","🖋️","✒️","🖌️","🖍️","📝","✏️","🔍","🔎","🔏","🔐","🔒","🔓"] }
    ];

    window.MicrositeBuilder.renderEmojiPicker = function (targetId) {
        const container = document.getElementById('emojiScroll_' + targetId);
        if (!container) return;
        if (container.dataset.rendered === 'true') return;

        let html = '';
        window.MicrositeBuilder.EMOJI_DICTIONARY.forEach(cat => {
            html += '<div class="emoji-section-title" id="' + cat.id + '_' + targetId + '">' + cat.category + '</div>';
            html += '<div class="emoji-grid">';
            cat.icons.forEach(emoji => {
                html += '<div class="emoji-item js-pick-emoji" data-target-id="' + targetId + '" data-emoji="' + emoji + '">' + emoji + '</div>';
            });
            html += '</div>';
        });

        container.innerHTML = html;
        container.dataset.rendered = 'true';
    };

    const emojiKeywords = {
        "😀": "smile senyum bahagia", "😃": "smile senyum besar bahagia", "😄": "smile senyum tertawa", "😁": "smile senyum gigi",
        "😆": "tertawa ngakak", "😅": "senyum keringat sweat", "😂": "tertawa air mata joy", "🤣": "ngakak guling",
        "😊": "senyum bahagia seneng", "😇": "malaikat angel", "🙂": "senyum tipis", "😉": "kedip wink",
        "😍": "cinta love hati mata", "🥰": "cinta love sayang", "😘": "cium kiss", "😎": "keren cool kacamata",
        "😭": "nangis sedih cry", "😡": "marah pouting angry", "👍": "jempol bagus mantap ok", "👎": "jelek buruk",
        "👏": "tepuk tangan clap", "🙏": "mohon doa terima kasih", "❤️": "hati cinta love red", "🔥": "api panas fire hot",
        "✨": "bintang kilau sparkle", "⭐": "bintang star", "🚀": "roket rocket", "🎉": "pesta party",
        "🎁": "kado hadiah gift", "🛒": "keranjang belanja cart", "📦": "paket kotak box", "📞": "telepon call",
        "✉️": "surat email amplop", "🌐": "globe internet web dunia", "🎓": "lulus sarjana topi", "💼": "tas kerja",
        "🐶": "anjing dog", "🐱": "kucing cat", "🚗": "mobil car", "🍔": "burger makanan", "🍕": "pizza makanan",
        "⚽": "bola sepak soccer", "🏀": "basket bola", "✔️": "ceklis centang check", "✅": "ceklis hijau",
        "❌": "silang cross", "⚠️": "warning awas", "💡": "lampu ide idea"
    };

    // Event listeners registration (Idempotent: only bound once on document)
    if (!window.__micrositeElementHandlersClicksBound) {
        window.__micrositeElementHandlersClicksBound = true;

        // Emoji search listener
        document.addEventListener('input', function (e) {
            if (e.target.matches('.js-emoji-search')) {
                const query = e.target.value.toLowerCase().trim();
                const id = e.target.dataset.targetId;
                const container = document.getElementById('emojiScroll_' + id);

                if (container) {
                    const sections = container.querySelectorAll('.emoji-section-title');
                    const grids = container.querySelectorAll('.emoji-grid');

                    if (query === '') {
                        sections.forEach(s => s.style.display = 'block');
                        grids.forEach(g => {
                            g.style.display = 'grid';
                            g.querySelectorAll('.emoji-item').forEach(item => item.style.display = 'flex');
                        });
                        return;
                    }

                    sections.forEach(s => s.style.display = 'none');
                    grids.forEach(grid => {
                        let hasVisible = false;
                        grid.querySelectorAll('.emoji-item').forEach(item => {
                            const emoji = item.getAttribute('data-emoji');
                            const keywords = emojiKeywords[emoji] || "";
                            if (keywords.includes(query) || emoji === query) {
                                item.style.display = 'flex';
                                hasVisible = true;
                            } else {
                                item.style.display = 'none';
                            }
                        });
                        grid.style.display = hasVisible ? 'grid' : 'none';
                    });
                }
            }
        });

        // Event delegation for element clicks
        document.addEventListener('click', function (e) {
            let target;

            if ((target = e.target.closest('.js-toggle-edit-form'))) {
                const type = target.dataset.type;
                const id = target.dataset.targetId;
                const forceOpen = target.dataset.forceOpen === 'true';

                if (type === 'Profile') toggleProfileEditForm(forceOpen);
                else if (type === 'Image') toggleImageEditForm(id, forceOpen);
                else if (type === 'Divider') toggleDividerEditForm(id, forceOpen);
                else if (type === 'Text') toggleTextEditForm(id, forceOpen);
                else if (type === 'Video' || type === 'video' || type === 'Embed Video') toggleVideoEditForm(id, forceOpen);
                else if (type === 'Social' || type === 'social' || type === 'SocialMedia') toggleSocialEditForm(id, forceOpen);
                else if (type === 'DigitalProduct' || type === 'digitalproduct' || type === 'digital_product') toggleDigitalProductEditForm(id, forceOpen);

                if (window.innerWidth > 1024) {
                    let liveElementId = type === 'Profile' ? 'liveProfileSection' : 'live_' + id;
                    const liveElement = document.getElementById(liveElementId);
                    if (liveElement) {
                        liveElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            }

            if ((target = e.target.closest('.js-remove-element'))) {
                e.stopPropagation();
                const type = target.dataset.type;
                const id = target.dataset.targetId;
                if (type === 'Element') removeDynamicElement(id);
                else if (type === 'Divider') removeDynamicDivider(id);
                else if (type === 'Text') removeDynamicText(id);
                else if (type === 'Video' || type === 'video' || type === 'Embed Video') removeDynamicVideo(id);
                else if (type === 'SocialMedia' || type === 'Social') removeDynamicSocialMedia(id);
            }

            if ((target = e.target.closest('.js-save-element'))) {
                const type = target.dataset.type;
                const id = target.dataset.targetId;

                if (type === 'Element') saveDynamicElement(id);
                else if (type === 'Divider') saveDynamicDivider(id);
                else if (type === 'Text') saveDynamicText(id);
                else if (type === 'Video' || type === 'video' || type === 'Embed Video') saveDynamicVideo(id);
                else if (type === 'SocialMedia' || type === 'Social') saveDynamicSocialMedia(id);
            }

        if ((target = e.target.closest('.js-remove-social-platform'))) {
            removeSocialPlatformFromForm(target.dataset.targetId, target.dataset.platform);
        }

        // Subtab & Category scrolling for Icon/Emoji pickers
        if (e.target.matches('.js-adv-tab') || e.target.closest('.js-adv-tab')) {
            const btn = e.target.matches('.js-adv-tab') ? e.target : e.target.closest('.js-adv-tab');
            const id = btn.dataset.targetId;
            const tab = btn.dataset.tab;

            btn.parentElement.querySelectorAll('.js-adv-tab').forEach(el => el.classList.remove('active'));
            btn.classList.add('active');

            const tabIcon = document.getElementById('advTab_pilih-icon_' + id);
            const tabUpload = document.getElementById('advTab_unggah-gambar_' + id);
            if (tabIcon) tabIcon.style.display = (tab === 'pilih-icon') ? 'block' : 'none';
            if (tabUpload) tabUpload.style.display = (tab === 'unggah-gambar') ? 'block' : 'none';

            const hiddenType = document.getElementById('buttonIconType_' + id);
            if (hiddenType) {
                if (tab === 'unggah-gambar') {
                    hiddenType.value = 'upload';
                } else {
                    const activeSub = tabIcon ? tabIcon.querySelector('.js-adv-subtab.active') : null;
                    if (activeSub) hiddenType.value = activeSub.dataset.subtab;
                }
                hiddenType.dispatchEvent(new Event('change', { bubbles: true }));
            }
        }

        if (e.target.matches('.js-adv-subtab') || e.target.closest('.js-adv-subtab')) {
            const btn = e.target.matches('.js-adv-subtab') ? e.target : e.target.closest('.js-adv-subtab');
            const id = btn.dataset.targetId;
            const subtab = btn.dataset.subtab;

            btn.parentElement.querySelectorAll('.js-adv-subtab').forEach(el => el.classList.remove('active'));
            btn.classList.add('active');

            if (subtab === 'emoji' && window.MicrositeBuilder.renderEmojiPicker) {
                window.MicrositeBuilder.renderEmojiPicker(id);
            }

            const subEmoji = document.getElementById('advSubTab_emoji_' + id);
            const subFa = document.getElementById('advSubTab_fontawesome_' + id);
            const subUrl = document.getElementById('advSubTab_url_' + id);
            if (subEmoji) subEmoji.style.display = (subtab === 'emoji') ? 'block' : 'none';
            if (subFa) subFa.style.display = (subtab === 'fontawesome') ? 'block' : 'none';
            if (subUrl) subUrl.style.display = (subtab === 'url') ? 'block' : 'none';

            const hiddenType = document.getElementById('buttonIconType_' + id);
            if (hiddenType) {
                hiddenType.value = subtab;
                hiddenType.dispatchEvent(new Event('change', { bubbles: true }));
            }
        }

        if (e.target.matches('.js-scroll-cat') || e.target.closest('.js-scroll-cat')) {
            const btn = e.target.matches('.js-scroll-cat') ? e.target : e.target.closest('.js-scroll-cat');
            const id = btn.dataset.targetId;
            const cat = btn.dataset.cat;

            btn.parentElement.querySelectorAll('.js-scroll-cat').forEach(el => el.classList.remove('active'));
            btn.classList.add('active');

            const scrollContainer = document.getElementById('emojiScroll_' + id);
            const targetSection = document.getElementById('cat_' + cat + '_' + id);
            if (scrollContainer && targetSection) {
                scrollContainer.scrollTo({
                    top: targetSection.offsetTop - scrollContainer.offsetTop - 10,
                    behavior: 'smooth'
                });
            }
        }

        if (e.target.matches('.js-pick-emoji') || e.target.closest('.js-pick-emoji')) {
            const btn = e.target.matches('.js-pick-emoji') ? e.target : e.target.closest('.js-pick-emoji');
            const id = btn.dataset.targetId;
            const emoji = btn.dataset.emoji;
            const input = document.getElementById('buttonIconEmoji_' + id);
            if (input) {
                input.value = emoji;
                if (typeof window.updateTextPreview === 'function') window.updateTextPreview(id);
            }
        }
    });

    // Delegation for changes (visibility checkbox, image preview)
    document.addEventListener('change', function (e) {
        if (e.target.matches('.js-toggle-visibility')) {
            toggleElementVisibility(e.target.dataset.targetId, e.target);
        }

        if (e.target.matches('.js-preview-image')) {
            previewDynamicImage(e.target, e.target.dataset.targetId);
        }

        if (e.target.matches('.js-toggle-text-button')) {
            const id = e.target.dataset.targetId;
            const fields = document.getElementById('buttonFields_' + id);
            if (fields) {
                fields.style.display = e.target.checked ? 'block' : 'none';
            }
        }

        if (e.target.matches('.js-toggle-icon-type')) {
            const id = e.target.dataset.targetId;
            const val = e.target.value;
            const emojiF = document.getElementById('iconEmojiField_' + id);
            const urlF = document.getElementById('iconUrlField_' + id);
            const upF = document.getElementById('iconUploadField_' + id);

            if (emojiF) emojiF.style.display = (val === 'emoji') ? 'block' : 'none';
            if (urlF) urlF.style.display = (val === 'url') ? 'block' : 'none';
            if (upF) upF.style.display = (val === 'upload') ? 'block' : 'none';
        }

        if (e.target.matches('.js-upload-icon-preview')) {
            const file = e.target.files[0];
            const id = e.target.dataset.targetId;
            if (file) {
                const reader = new FileReader();
                reader.onload = function (evt) {
                    const previewDiv = document.getElementById('uploadPreview_' + id);
                    if (previewDiv) {
                        previewDiv.innerHTML = '<img src="' + evt.target.result + '" style="width:32px; height:32px; object-fit:contain; border-radius:6px;"><span style="font-size:12px; margin-left:10px; color:#475569;">' + file.name + '</span>';
                        previewDiv.style.display = 'flex';
                    }
                    if (typeof window.updateTextPreview === 'function') window.updateTextPreview(id);
                };
                reader.readAsDataURL(file);
            }
        }
    });
    }

    // Register module namespace
    window.MicrositeBuilder.ElementHandlers = {
        closeAllEditForms,
        toggleProfileEditForm,
        toggleImageEditForm,
        toggleDividerEditForm,
        toggleTextEditForm,
        toggleVideoEditForm,
        toggleSocialEditForm,
        toggleDigitalProductEditForm,
        previewDynamicImage,
        saveDynamicElement,
        removeDynamicElement,
        saveDynamicDivider,
        removeDynamicDivider,
        saveDynamicText,
        removeDynamicText,
        saveDynamicVideo,
        removeDynamicVideo,
        saveDynamicSocialMedia,
        removeDynamicSocialMedia,
        toggleSocialInput,
        addSocialPlatformToForm,
        removeSocialPlatformFromForm,
        deleteDynamicDigitalProduct,
        toggleElementVisibility
    };

    // Expose global functions for backward compatibility
    window.closeAllEditForms = closeAllEditForms;
    window.toggleProfileEditForm = toggleProfileEditForm;
    window.toggleImageEditForm = toggleImageEditForm;
    window.toggleDividerEditForm = toggleDividerEditForm;
    window.toggleTextEditForm = toggleTextEditForm;
    window.toggleVideoEditForm = toggleVideoEditForm;
    window.toggleSocialEditForm = toggleSocialEditForm;
    window.toggleDigitalProductEditForm = toggleDigitalProductEditForm;
    window.previewDynamicImage = previewDynamicImage;
    window.saveDynamicElement = saveDynamicElement;
    window.removeDynamicElement = removeDynamicElement;
    window.saveDynamicDivider = saveDynamicDivider;
    window.removeDynamicDivider = removeDynamicDivider;
    window.saveDynamicText = saveDynamicText;
    window.removeDynamicText = removeDynamicText;
    window.saveDynamicVideo = saveDynamicVideo;
    window.removeDynamicVideo = removeDynamicVideo;
    window.saveDynamicSocialMedia = saveDynamicSocialMedia;
    window.removeDynamicSocialMedia = removeDynamicSocialMedia;
    window.toggleSocialInput = toggleSocialInput;
    window.addSocialPlatformToForm = addSocialPlatformToForm;
    window.removeSocialPlatformFromForm = removeSocialPlatformFromForm;
    window.deleteDynamicDigitalProduct = deleteDynamicDigitalProduct;
    window.toggleElementVisibility = toggleElementVisibility;
})();
