/**
 * Microsite Editor Orchestrator
 * Coordinates modules (modal-manager, drag-order, preview-sync, element-handlers)
 * and manages page lifecycle events.
 */
(function () {
    'use strict';

    window.MicrositeBuilder = window.MicrositeBuilder || {};

    /**
     * Initialize Page and Editor Event Listeners
     */
    function initPageEvents() {
        // Sort blocks based on DB order stored in data-appearance-blocks-order
        const list = document.getElementById('elementBlocksList');
        const urlsContainer = document.getElementById('micrositeEditorUrls');
        const dbOrderStr = urlsContainer ? (urlsContainer.dataset.appearanceBlocksOrder || '') : '';

        if (list && dbOrderStr) {
            const allBlocks = Array.from(list.querySelectorAll('.draggable-element-block'));
            const sortedBlocks = new Set();
            const dbOrder = dbOrderStr.split(',');

            dbOrder.forEach(blockId => {
                let el = null;
                if (blockId === 'profile') {
                    return; // Profile is statically pinned
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
                    sortedBlocks.add(el);
                }
            });

            // Append unsorted elements (e.g. newly created ones) to the bottom of the list
            allBlocks.forEach(block => {
                if (!sortedBlocks.has(block)) {
                    list.appendChild(block);
                }
            });
        }

        // Initialize drag and drop & sync phone mockup
        if (typeof window.initElementDragAndDrop === 'function') {
            window.initElementDragAndDrop();
        }
        if (typeof window.syncPhonePreviewOrder === 'function') {
            window.syncPhonePreviewOrder();
        }
        if (typeof window.updatePhonePreviewVisibility === 'function') {
            window.updatePhonePreviewVisibility();
        }

        // Initialize existing video previews
        if (typeof window.updateVideoPreview === 'function') {
            document.querySelectorAll('.draggable-element-block[data-element-type="video"]').forEach(block => {
                window.updateVideoPreview(block.id);
            });
        }

        // Initialize dropzones visual states
        if (typeof window.initDropzoneVisualStates === 'function') {
            window.initDropzoneVisualStates();
        }

        // AJAX PROFILE FORM SUBMISSION (NO RELOAD NEEDED)
        const profileForm = document.getElementById('profileBlockForm');
        if (profileForm && !profileForm.dataset.initialized) {
            profileForm.dataset.initialized = 'true';
            profileForm.addEventListener('submit', function (e) {
                e.preventDefault();

                if (typeof window.syncProfileName === 'function') window.syncProfileName();
                if (typeof window.syncProfileBio === 'function') window.syncProfileBio();

                const nameInput = document.getElementById('inputProfileName');
                const bioInput = document.getElementById('inputProfileBio');

                let nameWords = nameInput && nameInput.value.trim() !== '' ? nameInput.value.replace(/<[^>]*>?/gm, '').trim().split(/\s+/).filter(w => w.length > 0).length : 0;
                let bioWords = bioInput && bioInput.value.trim() !== '' ? bioInput.value.replace(/<[^>]*>?/gm, '').trim().split(/\s+/).filter(w => w.length > 0).length : 0;

                let valid = true;
                if (nameWords > 50) {
                    const charErrorName = document.getElementById('charErrorProfileName');
                    const charCountName = document.getElementById('charCountProfileName');
                    if (charErrorName) charErrorName.style.display = 'block';
                    if (charCountName) charCountName.style.color = '#ef4444';
                    valid = false;
                }
                if (bioWords > 250) {
                    const charErrorBio = document.getElementById('charErrorProfileBio');
                    const charCountBio = document.getElementById('charCountProfileBio');
                    if (charErrorBio) charErrorBio.style.display = 'block';
                    if (charCountBio) charCountBio.style.color = '#ef4444';
                    valid = false;
                }

                if (!valid) return;

                const submitBtn = this.querySelector('button[type="submit"]');
                const origText = submitBtn ? submitBtn.innerText : 'Simpan Perubahan';

                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerText = 'Menyimpan...';
                }

                const urls = document.getElementById('micrositeEditorUrls');
                const formData = new FormData(this);
                if (urls && urls.dataset.appearanceId) {
                    formData.append('appearance_id', urls.dataset.appearanceId);
                }

                const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
                const csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';
                const updateUrl = urls ? urls.dataset.routeAppearanceUpdate : this.action;

                fetch(updateUrl, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken
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

                            if (typeof window.updatePhonePreviewVisibility === 'function') {
                                window.updatePhonePreviewVisibility();
                            }
                            if (typeof window.toggleProfileEditForm === 'function') {
                                window.toggleProfileEditForm();
                            }

                            if (typeof window.showSuccessToast === 'function') {
                                window.showSuccessToast('Profil berhasil disimpan!');
                            }
                        }
                    })
                    .catch(err => {
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.innerText = origText;
                        }

                        if (err && err.errors) {
                            const firstError = Object.values(err.errors)[0][0];
                            alert('Gagal menyimpan: ' + firstError);
                        } else {
                            HTMLFormElement.prototype.submit.call(this);
                        }
                    });
            });
        }
    }

    // Attach lifecycle events (Idempotent)
    if (!window.__micrositeEditorLifecycleBound) {
        window.__micrositeEditorLifecycleBound = true;
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initPageEvents);
        } else {
            initPageEvents();
        }
        document.addEventListener('turbo:load', initPageEvents);
        document.addEventListener('turbolinks:load', initPageEvents);
    } else {
        initPageEvents();
    }

    // Expose orchestrator functions
    window.initPageEvents = initPageEvents;
    window.MicrositeBuilder.initPageEvents = initPageEvents;
})();
