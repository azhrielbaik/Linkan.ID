/**
 * Microsite Editor - Preview Synchronization Module
 * Handles live synchronization between editor inputs and phone mockup preview.
 */
(function () {
    'use strict';

    window.MicrositeBuilder = window.MicrositeBuilder || {};

    let profileCropper = null;
    window.savedSelectionRange = null;

    /**
     * Update Live Profile Name in phone preview
     */
    function updateLiveProfileName(val) {
        const liveName = document.getElementById('livePhoneName');
        if (liveName) {
            liveName.innerHTML = val || window.MicrositeConfig?.authUserName || 'Your Name';
        }
    }

    /**
     * Update Live Profile Bio in phone preview
     */
    function updateLiveProfileBio(val) {
        const liveBio = document.getElementById('livePhoneBio');
        if (liveBio) {
            liveBio.innerHTML = val || '';
        }
    }

    /**
     * Sync ContentEditable Profile Name to hidden form input and validate word count
     */
    function syncProfileName() {
        const editor = document.getElementById('editorProfileName');
        const input = document.getElementById('inputProfileName');
        if (editor && input) {
            input.value = editor.innerHTML;
            const rawText = editor.innerText.trim();
            const wordCount = rawText === '' ? 0 : rawText.split(/\s+/).filter(word => word.length > 0).length;
            const charCount = document.getElementById('charCountProfileName');
            const charError = document.getElementById('charErrorProfileName');
            if (charCount) {
                charCount.textContent = wordCount + '/50 Kata';
                if (wordCount > 50) {
                    charCount.style.color = '#ef4444';
                } else {
                    charCount.style.color = '#6b7280';
                    if (charError) charError.style.display = 'none';
                }
            }
        }
    }

    /**
     * Sync ContentEditable Profile Bio to hidden form input and validate word count
     */
    function syncProfileBio() {
        const editor = document.getElementById('editorProfileBio');
        const input = document.getElementById('inputProfileBio');
        if (editor && input) {
            input.value = editor.innerHTML;
            const rawText = editor.innerText.trim();
            const wordCount = rawText === '' ? 0 : rawText.split(/\s+/).filter(word => word.length > 0).length;
            const charCount = document.getElementById('charCountProfileBio');
            const charError = document.getElementById('charErrorProfileBio');
            if (charCount) {
                charCount.textContent = wordCount + '/250 Kata';
                if (wordCount > 250) {
                    charCount.style.color = '#ef4444';
                } else {
                    charCount.style.color = '#6b7280';
                    if (charError) charError.style.display = 'none';
                }
            }
        }
    }

    /**
     * Format Rich Text in Profile editor or general editors
     */
    function formatText(command, value = null, editorId = null) {
        if (editorId) {
            const editor = document.getElementById(editorId);
            if (editor) {
                editor.focus();
                const selection = window.getSelection();
                if (window.savedSelectionRange) {
                    selection.removeAllRanges();
                    selection.addRange(window.savedSelectionRange);
                }
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

    /**
     * Update Profile Avatar Border Radius Shape
     */
    function updateProfileShape(shape) {
        const liveAvatarContainer = document.getElementById('livePhoneAvatarContainer');
        const avatarPreviewContainer = document.getElementById('avatarPreviewContainer');

        let radius = '50%';
        if (shape === 'rounded') radius = '14px';
        if (shape === 'square') radius = '0px';

        if (liveAvatarContainer) liveAvatarContainer.style.borderRadius = radius;
        if (avatarPreviewContainer) avatarPreviewContainer.style.borderRadius = radius;
    }

    /**
     * Preview Profile Banner Image
     */
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
                const liveProfileSection = document.getElementById('liveProfileSection');

                if (liveBannerContainer) liveBannerContainer.style.display = 'block';
                if (liveBannerImg) liveBannerImg.src = e.target.result;
                if (liveProfileSection) liveProfileSection.classList.add('has-banner');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    /**
     * Preview Profile Avatar and initialize Cropper.js modal
     */
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
                const modal = document.getElementById('cropperModal');
                const imageTarget = document.getElementById('cropperImageTarget');

                if (modal && imageTarget) {
                    imageTarget.src = e.target.result;
                    modal.style.display = 'flex';
                    modal.classList.add('active');

                    if (profileCropper) {
                        profileCropper.destroy();
                    }

                    if (typeof Cropper !== 'undefined') {
                        try {
                            profileCropper = new Cropper(imageTarget, {
                                aspectRatio: 1, // Profile picture
                                viewMode: 1,
                                autoCropArea: 1,
                                dragMode: 'move',
                            });
                        } catch (err) {
                            console.error('Cropper initialization failed:', err);
                        }
                    }
                }

                input.value = ''; // Reset to allow re-selection
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    /**
     * Handle Cropper Modal Actions (Apply Crop & Close)
     */
    if (!window.__micrositePreviewSyncEventsBound) {
        window.__micrositePreviewSyncEventsBound = true;

        document.addEventListener('click', function (e) {
        if (e.target.closest('.js-apply-crop')) {
            if (profileCropper) {
                const canvas = profileCropper.getCroppedCanvas({
                    width: 500,
                    height: 500,
                });

                if (canvas) {
                    const base64Data = canvas.toDataURL('image/webp', 0.9);

                    const hiddenInput = document.getElementById('inputAvatarBase64');
                    if (hiddenInput) {
                        hiddenInput.value = base64Data;
                    }

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
                    img.style.display = 'block';
                    img.src = base64Data;

                    const liveAvatarImg = document.getElementById('livePhoneAvatarImg');
                    const liveAvatarPlaceholder = document.getElementById('livePhoneAvatarPlaceholder');
                    const liveAvatarContainer = document.getElementById('livePhoneAvatarContainer');

                    if (liveAvatarPlaceholder) liveAvatarPlaceholder.style.display = 'none';
                    if (liveAvatarImg) {
                        liveAvatarImg.src = base64Data;
                    } else if (liveAvatarContainer) {
                        const newImg = document.createElement('img');
                        newImg.id = 'livePhoneAvatarImg';
                        newImg.style.width = '100%';
                        newImg.style.height = '100%';
                        newImg.style.objectFit = 'cover';
                        newImg.src = base64Data;
                        liveAvatarContainer.appendChild(newImg);
                    }
                }

                const modal = document.getElementById('cropperModal');
                if (modal) {
                    modal.classList.remove('active');
                    setTimeout(() => { modal.style.display = 'none'; }, 300);
                }
                profileCropper.destroy();
                profileCropper = null;
            }
        }

        if (e.target.closest('.js-close-cropper-modal')) {
            const modal = document.getElementById('cropperModal');
            if (modal) {
                modal.classList.remove('active');
                setTimeout(() => { modal.style.display = 'none'; }, 300);
            }
            if (profileCropper) {
                profileCropper.destroy();
                profileCropper = null;
            }
        }
    });
    }

    /**
     * Live Preview & Adjustments for Divider Element
     */
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

            const liveContainer = document.getElementById('live_' + id);
            if (type === 'line') {
                liveDivider.style.borderTop = '2px solid #cbd5e1';
                liveDivider.style.height = '0';
                if (liveContainer) liveContainer.style.padding = (size / 2) + 'px 0';
            } else {
                liveDivider.style.borderTop = 'none';
                liveDivider.style.height = size + 'px';
                if (liveContainer) liveContainer.style.padding = '0';
            }
        }
    }

    function updateSegmentedControl(radio) {
        const group = radio.closest('div');
        if (!group) return;
        const labels = group.querySelectorAll('label');
        labels.forEach(label => {
            const input = label.querySelector('input');
            const btn = label.querySelector('.segment-btn');
            if (btn && input) {
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
            }
        });
    }

    /**
     * Live Preview & Adjustments for Text Element
     */
    function execCmd(id, command, value = null) {
        const editor = document.getElementById('editorContent_' + id);
        if (editor) {
            editor.focus();
            const selection = window.getSelection();
            if (window.savedSelectionRange) {
                selection.removeAllRanges();
                selection.addRange(window.savedSelectionRange);
            }
            document.execCommand(command, false, value);
            updateTextPreview(id);
        }
    }

    function changeTextSize(id, value) {
        const customWrapper = document.getElementById('customSizeWrapper_' + id);
        if (value === 'custom') {
            if (customWrapper) customWrapper.style.display = 'flex';
            const formBody = document.getElementById('formBody_' + id);
            if (formBody) formBody.style.maxHeight = (formBody.scrollHeight + 50) + 'px';
        } else {
            if (customWrapper) customWrapper.style.display = 'none';
            execCmd(id, 'fontSize', 7);
            replaceFontSize(id, value);
        }
    }

    function applyCustomSize(id) {
        const input = document.getElementById('customSizeInput_' + id);
        if (input && input.value) {
            let size = parseInt(input.value);
            if (size > 99) size = 99;
            else if (size < 1) size = 1;
            input.value = size;
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
            // Update word count
            const rawText = editor.innerText.trim();
            const wordCount = rawText === '' ? 0 : rawText.split(/\s+/).filter(word => word.length > 0).length;
            const charCount = document.getElementById('charCount_' + id);
            const charError = document.getElementById('charError_' + id);
            if (charCount) {
                charCount.textContent = wordCount + '/250 Kata';
                if (wordCount > 250) {
                    charCount.style.color = '#ef4444';
                } else {
                    charCount.style.color = '#6b7280';
                    if (charError) charError.style.display = 'none';
                }
            }

            // Sync list item marker size with inner text font-size
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
                let html = '';
                const hasBtn = document.getElementById('hasButton_' + id);
                if (hasBtn && hasBtn.checked) {
                    const btnText = document.getElementById('buttonText_' + id)?.value || 'Tombol';
                    const btnColor = document.getElementById('buttonColor_' + id)?.value || '#f8f9fa';
                    const iconType = document.getElementById('buttonIconType_' + id)?.value || 'none';

                    let iconHtml = '<i class="fas fa-align-left"></i>';

                    if (iconType === 'emoji') {
                        const emojiVal = document.getElementById('buttonIconEmoji_' + id)?.value || '';
                        if (emojiVal) iconHtml = `<span style="font-size:18px;">${emojiVal}</span>`;
                    } else if (iconType === 'fontawesome') {
                        const faVal = document.getElementById('buttonIconFa_' + id)?.value || '';
                        if (faVal) iconHtml = `<i class="${faVal}"></i>`;
                    } else if (iconType === 'url') {
                        const urlVal = document.getElementById('buttonIconUrl_' + id)?.value || '';
                        if (urlVal) iconHtml = `<img src="${urlVal}" style="width:20px; height:20px; object-fit:contain; border-radius:4px;">`;
                    } else if (iconType === 'upload') {
                        const fileInput = document.getElementById('buttonIconUpload_' + id);
                        if (fileInput && fileInput.files && fileInput.files[0]) {
                            const url = URL.createObjectURL(fileInput.files[0]);
                            iconHtml = `<img src="${url}" style="width:20px; height:20px; object-fit:contain; border-radius:4px;">`;
                        } else {
                            iconHtml = '<i class="fas fa-image"></i>';
                        }
                    }

                    html += `
                        <div class="text-element-accordion" style="width: 100%; margin: 15px 0;">
                            <div class="text-element-button-wrapper">
                                <button class="text-element-button" style="background-color: ${btnColor} !important;" onclick="this.parentElement.nextElementSibling.classList.toggle('show'); this.classList.toggle('active')">
                                    <div class="text-element-btn-icon-left">
                                        ${iconHtml}
                                    </div>
                                    <span class="btn-text">${btnText}</span>
                                    <div class="text-element-btn-icon-right">
                                        <i class="fas fa-chevron-down"></i>
                                    </div>
                                </button>
                            </div>
                            <div class="text-element-content-sliding">
                                <div class="text-element-content-inner">
                                    ${editor.innerHTML}
                                </div>
                            </div>
                        </div>
                    `;
                } else {
                    html += `<div style="width: 100%; word-break: break-word; color: #1e293b; font-size: 16px; margin: 15px 0;">${editor.innerHTML}</div>`;
                }
                liveDiv.innerHTML = html;
            }
        }

        // Update max height if content grows
        const formBody = document.getElementById('formBody_' + id);
        if (formBody && formBody.classList.contains('open')) {
            formBody.style.maxHeight = (formBody.scrollHeight + 50) + 'px';
        }
    }

    /**
     * Live Preview for Video Element
     */
    function updateVideoPreview(id) {
        const urlInput = document.getElementById('videoUrl_' + id);
        const autoplayToggle = document.getElementById('videoAutoplay_' + id);
        const container = document.getElementById('liveVideoContainer_' + id);

        if (!urlInput || !container) return;

        const videoUrl = urlInput.value.trim();
        const isAutoplay = autoplayToggle ? autoplayToggle.checked : false;

        if (videoUrl) {
            const match = videoUrl.match(/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?|shorts)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i);
            if (match && match[1]) {
                const videoId = match[1];
                const autoplayParam = isAutoplay ? '&autoplay=1&mute=1' : '';
                const embedUrl = `https://www.youtube.com/embed/${videoId}?rel=0${autoplayParam}`;

                container.innerHTML = `
                    <div style="position: relative; width: 100%; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 8px; pointer-events: none; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                        <iframe title="YouTube video player" src="${embedUrl}" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
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

    /**
     * Live Preview for Social Media Element
     */
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

                html += `<a href="${url}" target="_blank" rel="noopener noreferrer" style="display: inline-flex; justify-content: center; align-items: center; background-color: #111827; color: white; width: 35px; height: 35px; border-radius: 50%; text-decoration: none; transition: all 0.2s; margin: 0 4px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);" onmouseover="this.style.transform='translateY(-3px) scale(1.1)';" onmouseout="this.style.transform='translateY(0) scale(1)';">
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

    /**
     * Preview handler for dynamic image element link (no-op in preview to avoid navigation)
     */
    function updateDynamicImageLink(elementId, url) {
        // In preview mode, image links are not followed so users can click to edit
    }

    // Listeners for live sync and controls (Idempotent: bound only once)
    if (!window.__micrositePreviewSyncInputsBound) {
        window.__micrositePreviewSyncInputsBound = true;

        // Mousedown listener to preserve rich-text selection range on toolbar interaction
        document.addEventListener('mousedown', function (e) {
            if (e.target.closest('.toolbar-btn') || e.target.closest('.js-prevent-default') || e.target.closest('.toolbar-color-picker') || e.target.closest('.toolbar-select') || e.target.closest('.toolbar-dropdown')) {
                const selection = window.getSelection();
                if (selection.rangeCount > 0) {
                    window.savedSelectionRange = selection.getRangeAt(0);
                }
            }

            if (e.target.closest('.toolbar-btn') || e.target.closest('.js-prevent-default')) {
                e.preventDefault();
            }
        });

        // Delegation listener for live sync on inputs
        document.addEventListener('input', function (e) {
            if (e.target.matches('.js-format-profile-text-val') && e.target.type === 'color') {
                formatText(e.target.dataset.cmd, e.target.value, e.target.dataset.target);
            }

            if (e.target.matches('.js-exec-cmd-value') && e.target.type === 'color') {
                execCmd(e.target.dataset.targetId, e.target.dataset.cmd, e.target.value);
            }

            if (e.target.matches('.js-update-image-link')) {
                updateDynamicImageLink(e.target.dataset.targetId, e.target.value);
            }

            if (e.target.matches('.js-update-divider-preview')) {
                updateDividerPreview(e.target.dataset.targetId);
            }

            if (e.target.matches('.js-update-text-preview')) {
                updateTextPreview(e.target.dataset.targetId);
            }

            if (e.target.matches('.js-update-video-preview')) {
                updateVideoPreview(e.target.dataset.targetId);
            }
        });

        // Keyup listeners for live sync
        document.addEventListener('keyup', function (e) {
            if (e.target.matches('.js-update-social-preview')) {
                updateSocialPreview(e.target.dataset.targetId);
            }

            if (e.target.matches('.js-sync-profile-name')) {
                syncProfileName();
                updateLiveProfileName(e.target.innerHTML);
            }

            if (e.target.matches('.js-sync-profile-bio')) {
                syncProfileBio();
                updateLiveProfileBio(e.target.innerHTML);
            }
        });

        // Change listeners for controls
        document.addEventListener('change', function (e) {
            if (e.target.matches('.js-change-divider-type')) {
                const id = e.target.dataset.targetId;
                const input = document.getElementById('dividerType_' + id);
                if (input) input.value = e.target.value;
                updateDividerPreview(id);
                updateSegmentedControl(e.target);
            }

            if (e.target.matches('.js-exec-cmd-value')) {
                execCmd(e.target.dataset.targetId, e.target.dataset.cmd, e.target.value);
            }

            if (e.target.matches('.js-change-text-size')) {
                changeTextSize(e.target.dataset.targetId, e.target.value);
            }

            if (e.target.matches('.js-apply-custom-size-input')) {
                applyCustomSize(e.target.dataset.targetId);
            }

            if (e.target.matches('.js-update-video-preview')) {
                updateVideoPreview(e.target.dataset.targetId);
            }

            if (e.target.matches('.js-update-social-preview')) {
                updateSocialPreview(e.target.dataset.targetId);
            }

            if (e.target.matches('.js-preview-profile-avatar')) {
                previewProfileAvatar(e.target);
            }

            if (e.target.matches('.js-update-profile-shape')) {
                updateProfileShape(e.target.dataset.shape);
            }

            if (e.target.matches('.js-preview-profile-banner')) {
                previewProfileBanner(e.target);
            }

            if (e.target.matches('.js-format-profile-text-val')) {
                formatText(e.target.dataset.cmd, e.target.value, e.target.dataset.target);
            }
        });
    }

    // Register module namespace
    window.MicrositeBuilder.PreviewSync = {
        updateLiveProfileName,
        updateLiveProfileBio,
        syncProfileName,
        syncProfileBio,
        formatText,
        updateProfileShape,
        previewProfileBanner,
        previewProfileAvatar,
        adjustDividerSize,
        updateDividerPreview,
        updateSegmentedControl,
        execCmd,
        changeTextSize,
        applyCustomSize,
        replaceFontSize,
        updateTextPreview,
        updateVideoPreview,
        updateSocialPreview,
        updateDynamicImageLink
    };

    // Expose global functions for backward compatibility
    window.updateLiveProfileName = updateLiveProfileName;
    window.updateLiveProfileBio = updateLiveProfileBio;
    window.syncProfileName = syncProfileName;
    window.syncProfileBio = syncProfileBio;
    window.formatText = formatText;
    window.updateProfileShape = updateProfileShape;
    window.previewProfileBanner = previewProfileBanner;
    window.previewProfileAvatar = previewProfileAvatar;
    window.adjustDividerSize = adjustDividerSize;
    window.updateDividerPreview = updateDividerPreview;
    window.updateSegmentedControl = updateSegmentedControl;
    window.execCmd = execCmd;
    window.changeTextSize = changeTextSize;
    window.applyCustomSize = applyCustomSize;
    window.replaceFontSize = replaceFontSize;
    window.updateTextPreview = updateTextPreview;
    window.updateVideoPreview = updateVideoPreview;
    window.updateSocialPreview = updateSocialPreview;
    window.updateDynamicImageLink = updateDynamicImageLink;
})();
