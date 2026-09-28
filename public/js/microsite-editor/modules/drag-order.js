/**
 * Microsite Editor - Drag & Order Module
 * Handles drag-and-drop reordering with SortableJS, DOM preview sync, and DB persistence.
 */
(function () {
    'use strict';

    window.MicrositeBuilder = window.MicrositeBuilder || {};

    let elementSortable = null;

    /**
     * Initialize SortableJS on the element blocks list.
     */
    function initElementDragAndDrop() {
        const list = document.getElementById('elementBlocksList');
        if (!list) return;

        if (typeof Sortable === 'undefined') {
            console.warn('SortableJS library not loaded.');
            return;
        }

        if (elementSortable) {
            elementSortable.destroy();
        }

        elementSortable = new Sortable(list, {
            animation: 150,
            handle: '.drag-handle', // Dragging is only allowed when clicking the handle icon
            filter: '#profileBlockCard', // Prevent dragging the profile block entirely
            ghostClass: 'sortable-ghost', // Styling for drop placeholder
            onEnd: function () {
                // Trigger visual sync and database save on drop
                syncPhonePreviewOrder();
                saveElementsOrder();
            }
        });
    }

    /**
     * Persist elements order to the database via AJAX.
     */
    function saveElementsOrder() {
        const list = document.getElementById('elementBlocksList');
        const urlContainer = document.getElementById('micrositeEditorUrls');
        if (!list || !urlContainer) return;

        const routeOrderUpdate = urlContainer.dataset.routeOrderUpdate;
        const appearanceId = urlContainer.dataset.appearanceId;
        if (!routeOrderUpdate || !appearanceId) return;

        const blocks = list.querySelectorAll('.draggable-element-block, .element-block');
        const order = ['profile']; // Profile is strictly always the first element in DB

        blocks.forEach(block => {
            const type = block.getAttribute('data-element-type');
            const dbId = block.getAttribute('data-db-id');
            if (!dbId) return;

            if (type === 'image') {
                order.push('image_' + dbId);
            } else if (type === 'divider') {
                order.push('divider_' + dbId);
            } else if (type === 'text') {
                order.push('text_' + dbId);
            } else if (type === 'video') {
                order.push('video_' + dbId);
            } else if (type === 'social') {
                order.push('social_' + dbId);
            } else if (type === 'digital_product') {
                order.push('digitalproduct_' + dbId);
            }
        });

        const blocksOrderStr = order.join(',');
        const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';

        return fetch(routeOrderUpdate, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                blocks_order: blocksOrderStr,
                appearance_id: appearanceId
            }),
            keepalive: true
        });
    }

    /**
     * Synchronize DOM element order in the phone mockup preview according to the editor block list.
     */
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
            } else if (['image', 'divider', 'text', 'video', 'social', 'digital_product'].includes(type)) {
                const liveElement = document.getElementById('live_' + block.id);
                if (liveElement) {
                    phoneContent.appendChild(liveElement);
                }
            }
        });
    }

    /**
     * Ensure phone preview visibility state is updated.
     */
    function updatePhonePreviewVisibility() {
        const liveProfile = document.getElementById('liveProfileSection');
        const emptyState = document.getElementById('phoneEmptyState');

        // The profile block is always active as it's a mandatory pinned block.
        if (liveProfile) {
            liveProfile.style.display = 'block';
        }

        // The empty state should be hidden because the profile block is always present as the first element.
        if (emptyState) {
            emptyState.style.display = 'none';
        }
    }

    /**
     * Bind dynamic dropzone drag and drop visual cues for file inputs.
     */
    function bindDynamicDropzone(elementId) {
        const block = document.getElementById(elementId);
        if (!block) return;
        const zone = block.querySelector('.dynamic-dropzone');
        if (!zone) return;
        const input = zone.querySelector('input[type="file"]');
        if (input) {
            input.addEventListener('dragenter', () => zone.classList.add('drag-over-active'));
            input.addEventListener('dragleave', () => zone.classList.remove('drag-over-active'));
            input.addEventListener('drop', () => zone.classList.remove('drag-over-active'));
        }
    }

    /**
     * Initialize drag and drop visual states for all upload dropzones on the page.
     */
    function initDropzoneVisualStates() {
        const dropzones = document.querySelectorAll('.upload-dropzone');
        dropzones.forEach(zone => {
            const input = zone.querySelector('input[type="file"]');
            if (input && !input.dataset.dragbound) {
                input.dataset.dragbound = 'true';
                input.addEventListener('dragenter', () => zone.classList.add('drag-over-active'));
                input.addEventListener('dragleave', () => zone.classList.remove('drag-over-active'));
                input.addEventListener('drop', () => zone.classList.remove('drag-over-active'));
            }
        });
    }

    // Register module namespace
    window.MicrositeBuilder.DragOrder = {
        initElementDragAndDrop,
        saveElementsOrder,
        syncPhonePreviewOrder,
        updatePhonePreviewVisibility,
        bindDynamicDropzone,
        initDropzoneVisualStates
    };

    // Expose global functions for backward compatibility
    window.initElementDragAndDrop = initElementDragAndDrop;
    window.saveElementsOrder = saveElementsOrder;
    window.syncPhonePreviewOrder = syncPhonePreviewOrder;
    window.updatePhonePreviewVisibility = updatePhonePreviewVisibility;
    window.bindDynamicDropzone = bindDynamicDropzone;
    window.initDropzoneVisualStates = initDropzoneVisualStates;
})();
