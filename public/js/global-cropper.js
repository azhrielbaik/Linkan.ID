/**
 * ============================================================================
 * LINKAN.ID - GLOBAL IMAGE CROPPER LOGIC (global-cropper.js)
 * Extracted from resources/views/admin_seller/layouts/app.blade.php
 * ============================================================================
 */

(function () {
    'use strict';

    let globalCropperInstance = null;
    let globalCropperInput = null;

    function closeGlobalCropper() {
        const modal = document.getElementById('global-cropper-modal');
        if (modal) modal.style.display = 'none';

        if (globalCropperInstance) {
            globalCropperInstance.destroy();
            globalCropperInstance = null;
        }
        if (globalCropperInput) {
            globalCropperInput.value = ''; // Reset input if cancelled so user can re-select
            globalCropperInput = null;
        }
    }

    // Expose closeGlobalCropper globally for button onclick
    window.closeGlobalCropper = closeGlobalCropper;

    // Use capture phase so we intercept before other bubble change listeners (like previewImage)
    document.addEventListener('change', function (e) {
        if (e.target && e.target.matches('.image-cropper') && e.target.files && e.target.files.length > 0) {
            const file = e.target.files[0];

            // If it's already cropped (our flag), let the normal process continue
            if (file.isCropped) return;

            // Stop the event from propagating to other listeners yet
            e.preventDefault();
            e.stopImmediatePropagation();

            // Check if it's an image
            if (!file.type.match(/^image\//)) {
                alert('Silakan pilih file gambar yang valid.');
                e.target.value = '';
                return;
            }

            globalCropperInput = e.target;

            let ratio = parseFloat(globalCropperInput.getAttribute('data-crop-ratio'));
            if (isNaN(ratio)) ratio = NaN; // Free ratio

            let shape = globalCropperInput.getAttribute('data-crop-shape');
            const modalEl = document.getElementById('global-cropper-modal');
            if (modalEl) {
                if (shape === 'circle') {
                    modalEl.classList.add('cropper-circle-mode');
                } else {
                    modalEl.classList.remove('cropper-circle-mode');
                }
            }

            const reader = new FileReader();
            reader.onload = function (evt) {
                const imageElement = document.getElementById('global-cropper-image');
                if (imageElement) imageElement.src = evt.target.result;
                if (modalEl) modalEl.style.display = 'flex';

                if (globalCropperInstance) {
                    globalCropperInstance.destroy();
                }

                if (typeof Cropper === 'function' && imageElement) {
                    globalCropperInstance = new Cropper(imageElement, {
                        aspectRatio: ratio,
                        viewMode: 1, // Restrict crop box not to exceed size of canvas
                        dragMode: 'move', // Allow moving image instead of creating a new crop box
                        autoCropArea: 0.9, // 90% of container
                        cropBoxMovable: false, // Fix crop box position
                        cropBoxResizable: false, // Fix crop box size
                        toggleDragModeOnDblclick: false,
                        background: true,
                        responsive: true,
                        restore: false,
                    });
                }
            };
            reader.readAsDataURL(file);
        }
    }, true);

    function initCropperSaveListener() {
        const saveBtn = document.getElementById('global-cropper-save');
        if (!saveBtn || saveBtn._hasCropperListener) return;
        saveBtn._hasCropperListener = true;

        saveBtn.addEventListener('click', function () {
            if (!globalCropperInstance || !globalCropperInput) return;

            const btn = this;
            const originalText = btn.innerText;
            btn.innerText = 'Memproses...';
            btn.disabled = true;

            globalCropperInstance.getCroppedCanvas({
                imageSmoothingQuality: 'high',
            }).toBlob(function (blob) {
                if (!blob) {
                    alert('Gagal memproses gambar. Silakan coba lagi.');
                    btn.innerText = originalText;
                    btn.disabled = false;
                    return;
                }

                const originalName = globalCropperInput.files[0].name;
                const extension = originalName.substring(originalName.lastIndexOf('.')) || '.jpg';
                const newName = originalName.replace(extension, '_cropped.jpg');

                const file = new File([blob], newName, {
                    type: 'image/jpeg',
                    lastModified: new Date().getTime()
                });
                file.isCropped = true;

                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);

                const inputElement = globalCropperInput; // store ref before closing
                inputElement.files = dataTransfer.files;

                // Close modal
                const modal = document.getElementById('global-cropper-modal');
                if (modal) modal.style.display = 'none';

                if (globalCropperInstance) {
                    globalCropperInstance.destroy();
                    globalCropperInstance = null;
                }
                globalCropperInput = null;

                btn.innerText = originalText;
                btn.disabled = false;

                // Trigger change event manually so original preview scripts can run
                const newEvent = new Event('change', { bubbles: true });
                inputElement.dispatchEvent(newEvent);

            }, 'image/jpeg', 0.9);
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCropperSaveListener);
    } else {
        initCropperSaveListener();
    }
    document.addEventListener('turbo:load', initCropperSaveListener);

})();
