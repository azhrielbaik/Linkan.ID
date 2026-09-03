    <!-- TEMPLATES FOR DYNAMIC ELEMENTS -->
    <template id="image-block-template">
        <div id="__ELEMENT_ID__" class="draggable-element-block" data-element-type="image" class="draggable-element-block-inner">
            <div class="block-item-card js-toggle-edit-form" data-type="Image" data-target-id="__ELEMENT_ID__">
                <i class="fas fa-grip-vertical drag-handle drag-handle-icon js-stop-propagation" title="Tarik ke atas/bawah untuk ubah urutan"></i>
                <div class="block-item-icon-wrapper">
                    <i class="fas fa-image"></i>
                </div>
                <div class="block-item-content">
                    <div class="block-item-title-wrapper">
                        <span>Gambar</span>
                    </div>
                </div>
                <div class="block-item-actions js-stop-propagation" >
                    <div class="element-visibility-container">
                        <span class="visibility-status-text status-active" id="statusText___ELEMENT_ID__">Aktif</span>
                        <label class="toggle-switch">
                            <input class="js-toggle-visibility" type="checkbox" id="visibilitySwitch___ELEMENT_ID__" data-target-id="__ELEMENT_ID__" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    <button type="button" class="btn-element-action btn-delete-icon js-remove-element" data-type="Element" data-target-id="__ELEMENT_ID__" title="Hapus Elemen">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                    <button type="button" data-type="Image" data-target-id="__ELEMENT_ID__" class="btn-edit-block js-toggle-edit-form">
                        <i class="fas fa-pen" class="btn-edit-icon"></i> <span id="btnText___ELEMENT_ID__">Edit</span>
                    </button>
                </div>
            </div>

            <div id="formBody___ELEMENT_ID__" class="edit-form-body" style="max-height: 0; opacity: 0; margin-top: 0;">
                <div class="profile-form-padding">
                    <div class="profile-form-header" style="margin-bottom: 16px;">
                        Pengaturan Elemen Gambar
                    </div>
                    
                    <div>
                        <label class="form-label-custom">Unggah Gambar</label>
                        <div class="upload-dropzone dynamic-dropzone image-block-dropzone">
                            <input type="file" accept="image/jpeg, image/png, image/gif" class="hidden-file-input js-preview-image" data-target-id="__ELEMENT_ID__">
                            
                            <div id="placeholder___ELEMENT_ID__" class="dropzone-placeholder-flex">
                                <i class="fas fa-cloud-upload-alt upload-icon-indigo"></i>
                                <div class="upload-text-main">Seret gambar ke sini atau <span class="upload-text-highlight-indigo">browse</span></div>
                                <div class="upload-text-sub">supports JPG, JPEG, PNG & GIF</div>
                            </div>

                            <div id="previewCont___ELEMENT_ID__" class="dynamic-preview-container" style="display: none;">
                                                        <img src="" id="previewImg___ELEMENT_ID__" class="preview-img-contain">
                                                        <div class="edit-image-overlay">
                                                            <i class="fa-solid fa-pen-to-square"></i>
                                                        </div>
                                                    </div>
                            
                            <div id="error___ELEMENT_ID__" class="upload-size-error" style="display: none;">
                                <i class="fas fa-exclamation-circle" class="wizard-icon-back"></i> Gagal: Ukuran maksimal gambar adalah 2MB!
                            </div>
                        </div>
                    </div>

                    <div class="form-field-margin">
                        <label class="form-label-custom">URL Tautan (Opsional)</label>
                        <input type="url" id="link___ELEMENT_ID__" placeholder="https://..." class="form-input-custom js-update-image-link" data-target-id="__ELEMENT_ID__">
                    </div>

                    <div class="element-action-footer">
                        <button type="button" data-type="Element" data-target-id="__ELEMENT_ID__" class="btn-delete-element js-remove-element">
                            <i class="fas fa-trash-alt"></i> Hapus Elemen
                        </button>
                        <button type="button" data-type="Element" data-target-id="__ELEMENT_ID__" class="btn-save-element js-save-element">
                            Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <template id="image-live-template">
        <div id="live___ELEMENT_ID__" class="microsite-live-element live-element-pointer js-toggle-edit-form" style="display: none;" data-type="Image" data-target-id="__ELEMENT_ID__" data-force-open="true">
            <a id="liveLink___ELEMENT_ID__" class="live-element-link pointer-events-none">
                <img id="liveImg___ELEMENT_ID__" src="" class="live-element-img">
            </a>
        </div>
    </template>

    <template id="divider-block-template">
        <div id="__ELEMENT_ID__" class="draggable-element-block" data-element-type="divider">
            <div class="block-item-card js-toggle-edit-form" data-type="Divider" data-target-id="__ELEMENT_ID__">
                <i class="fas fa-grip-vertical drag-handle drag-handle-icon js-stop-propagation" title="Tarik ke atas/bawah untuk ubah urutan"></i>
                <div class="block-item-icon-wrapper">
                    <i class="fas fa-minus"></i>
                </div>
                <div class="block-item-content">
                    <div class="block-item-title-wrapper">
                        <span>Pembatas</span>
                    </div>
                </div>
                <div class="block-item-actions js-stop-propagation" >
                    <div class="element-visibility-container">
                        <span class="visibility-status-text status-active" id="statusText___ELEMENT_ID__">Aktif</span>
                        <label class="toggle-switch">
                            <input class="js-toggle-visibility" type="checkbox" id="visibilitySwitch___ELEMENT_ID__" data-target-id="__ELEMENT_ID__" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    <button type="button" class="btn-element-action btn-delete-icon js-remove-element" data-type="Divider" data-target-id="__ELEMENT_ID__" title="Hapus Elemen">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                    <button type="button" data-type="Divider" data-target-id="__ELEMENT_ID__" class="btn-edit-block js-toggle-edit-form">
                        <i class="fas fa-pen" class="btn-edit-icon"></i> <span id="btnText___ELEMENT_ID__">Edit</span>
                    </button>
                </div>
            </div>

            <div id="formBody___ELEMENT_ID__" class="edit-form-body" style="max-height: 0; opacity: 0; margin-top: 0;">
                <div class="edit-form-content">
                    <div class="edit-form-group">
                        <label class="profile-form-label">Jenis Pembatas</label>
                        <div class="segment-control-wrapper">
                            <label class="segment-control-label">
                                <input type="radio" name="dividerTypeGroup___ELEMENT_ID__" value="line" class="hidden-radio js-change-divider-type" data-target-id="__ELEMENT_ID__" checked>
                                <div class="segment-btn active">
                                    <i class="fas fa-minus"></i> Garis
                                </div>
                            </label>
                            <label class="segment-control-label">
                                <input type="radio" name="dividerTypeGroup___ELEMENT_ID__" value="space" class="hidden-radio js-change-divider-type" data-target-id="__ELEMENT_ID__">
                                <div class="segment-btn">
                                    <i class="fas fa-arrows-alt-v"></i> Spasi Kosong
                                </div>
                            </label>
                            <input type="hidden" id="dividerType___ELEMENT_ID__" value="line">
                        </div>
                    </div>

                    <div class="slider-control-container">
                        <label class="profile-form-label slider-control-header">
                            <span class="slider-control-title">Ukuran Jarak</span>
                            <div class="slider-value-badge" id="dividerSizeValue___ELEMENT_ID__">20px</div>
                        </label>
                        <div class="slider-input-wrapper">
                            <button type="button" class="btn-slider-adjust js-adjust-divider-size" data-target-id="__ELEMENT_ID__" data-step="-5">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="range" id="dividerSize___ELEMENT_ID__" class="modern-range js-update-divider-preview" min="10" max="100" step="5" value="20" data-target-id="__ELEMENT_ID__">
                            <button type="button" class="btn-slider-adjust js-adjust-divider-size" data-target-id="__ELEMENT_ID__" data-step="5">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>

                    <div class="element-action-footer">
                        <button type="button" data-type="Divider" data-target-id="__ELEMENT_ID__" class="btn-delete-element js-remove-element">
                            <i class="fas fa-trash-alt"></i> Hapus Elemen
                        </button>
                        <button type="button" data-type="Divider" data-target-id="__ELEMENT_ID__" class="btn-save-element js-save-element">
                            Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <template id="divider-live-template">
        <div id="live___ELEMENT_ID__" class="microsite-live-element live-divider-wrapper live-divider-padding js-toggle-edit-form" data-type="Divider" data-target-id="__ELEMENT_ID__" data-force-open="true">
            <div id="liveDivider___ELEMENT_ID__" class="live-divider-inner live-divider-line"></div>
        </div>
    </template>

    <template id="text-block-template">
        <div id="__ELEMENT_ID__" class="draggable-element-block" data-element-type="text">
            <div class="block-item-card js-toggle-edit-form" data-type="Text" data-target-id="__ELEMENT_ID__">
                <i class="fas fa-grip-vertical drag-handle drag-handle-icon js-stop-propagation" title="Tarik ke atas/bawah untuk ubah urutan"></i>
                <div class="block-item-icon-wrapper">
                    <i class="fas fa-font"></i>
                </div>
                <div class="block-item-content">
                    <div class="block-item-title-wrapper">
                        <span>Teks</span>
                    </div>
                </div>
                <div class="block-item-actions js-stop-propagation" >
                    <div class="element-visibility-container">
                        <span class="visibility-status-text status-active" id="statusText___ELEMENT_ID__">Aktif</span>
                        <label class="toggle-switch">
                            <input class="js-toggle-visibility" type="checkbox" id="visibilitySwitch___ELEMENT_ID__" data-target-id="__ELEMENT_ID__" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    <button type="button" class="btn-element-action btn-delete-icon js-remove-element" data-type="Text" data-target-id="__ELEMENT_ID__" title="Hapus Elemen">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                    <button type="button" data-type="Text" data-target-id="__ELEMENT_ID__" class="btn-edit-block js-toggle-edit-form">
                        <i class="fas fa-pen" class="btn-edit-icon"></i> <span id="btnText___ELEMENT_ID__">Edit</span>
                    </button>
                </div>
            </div>

            <div id="formBody___ELEMENT_ID__" class="edit-form-body" style="max-height: 0; opacity: 0; margin-top: 0;">
                <div class="edit-form-content">
                    <div class="profile-form-header" style="margin-bottom: 20px; font-weight: 600; color: #0f172a; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                        <i class="fas fa-font" style="color: #3b82f6; margin-right: 8px;"></i> Pengaturan Teks
                    </div>
                    <div class="profile-form-group" style="margin-bottom: 24px;">
                        <label class="profile-form-label" style="font-weight: 600; color: #334155; margin-bottom: 8px; display: block;">Isi Teks Konten</label>
                        <div class="text-editor-container" style="box-shadow: 0 1px 3px rgba(0,0,0,0.05); transition: all 0.3s ease; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden;">
                            <div class="text-editor-toolbar" style="background: #f8fafc; padding: 8px; display: flex; flex-wrap: wrap; gap: 4px; border-bottom: 1px solid #e2e8f0; align-items: center;">
                            <button type="button" class="toolbar-btn js-exec-cmd" data-target-id="__ELEMENT_ID__" data-cmd="bold" title="Bold"><i class="fas fa-bold"></i></button>
                            <button type="button" class="toolbar-btn js-exec-cmd" data-target-id="__ELEMENT_ID__" data-cmd="italic" title="Italic"><i class="fas fa-italic"></i></button>
                            <button type="button" class="toolbar-btn js-exec-cmd" data-target-id="__ELEMENT_ID__" data-cmd="underline" title="Underline"><i class="fas fa-underline"></i></button>
                            <button type="button" class="toolbar-btn js-exec-cmd" data-target-id="__ELEMENT_ID__" data-cmd="strikeThrough" title="Strikethrough"><i class="fas fa-strikethrough"></i></button>
                            <span class="toolbar-divider"></span>
                            <button type="button" class="toolbar-btn js-exec-cmd" data-target-id="__ELEMENT_ID__" data-cmd="justifyLeft" title="Align Left"><i class="fas fa-align-left"></i></button>
                            <button type="button" class="toolbar-btn js-exec-cmd" data-target-id="__ELEMENT_ID__" data-cmd="justifyCenter" title="Align Center"><i class="fas fa-align-center"></i></button>
                            <button type="button" class="toolbar-btn js-exec-cmd" data-target-id="__ELEMENT_ID__" data-cmd="justifyRight" title="Align Right"><i class="fas fa-align-right"></i></button>
                            <span class="toolbar-divider"></span>
                            <button type="button" class="toolbar-btn js-exec-cmd" data-target-id="__ELEMENT_ID__" data-cmd="insertUnorderedList" title="Bullet List"><i class="fas fa-list-ul"></i></button>
                            <button type="button" class="toolbar-btn js-exec-cmd" data-target-id="__ELEMENT_ID__" data-cmd="insertOrderedList" title="Numbered List"><i class="fas fa-list-ol"></i></button>
                            <span class="toolbar-divider"></span>
                            <input type="color" class="toolbar-color-picker js-exec-cmd-value" data-target-id="__ELEMENT_ID__" data-cmd="foreColor" title="Text Color" value="#000000">
                            <span class="toolbar-divider"></span>
                            <div class="toolbar-dropdown">
                                <select data-target-id="__ELEMENT_ID__" class="toolbar-select js-change-text-size" id="textSizeSelect___ELEMENT_ID__">
                                    <option value="12px">Kecil (12px)</option>
                                    <option value="16px" selected>Normal (16px)</option>
                                    <option value="24px">Besar (24px)</option>
                                    <option value="custom">Custom...</option>
                                </select>
                            </div>
                        </div>
                        <div id="customSizeWrapper___ELEMENT_ID__" class="custom-size-wrapper" style="display: none;">
                            <input type="number" id="customSizeInput___ELEMENT_ID__" class="toolbar-input js-apply-custom-size-input" placeholder="Ukuran (px)" min="1" max="99" data-target-id="__ELEMENT_ID__">
                            <button type="button" class="toolbar-btn-text js-apply-custom-size" data-target-id="__ELEMENT_ID__">Terapkan</button>
                        </div>
                        <div id="editorContent___ELEMENT_ID__" class="text-editor-area js-update-text-preview" contenteditable="true" data-target-id="__ELEMENT_ID__" style="padding: 16px; min-height: 120px; font-size: 16px; line-height: 1.5; outline: none;">Teks Anda di sini...</div>
                    </div>
                    </div>

                    <div class="element-action-footer" style="margin-top: 15px;">
                        <button type="button" data-type="Text" data-target-id="__ELEMENT_ID__" class="btn-delete-element js-remove-element">
                            <i class="fas fa-trash-alt"></i> Hapus Elemen
                        </button>
                        <button type="button" data-type="Text" data-target-id="__ELEMENT_ID__" class="btn-save-element js-save-element" id="btnSaveText___ELEMENT_ID__">
                            Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <template id="video-live-template">
        <div class="video-container" id="liveVideoContainer___ELEMENT_ID__">
            <!-- iframe will be generated here -->
            <div class="live-video-placeholder">
                <i class="fab fa-youtube live-video-placeholder-icon"></i>
                Masukkan URL YouTube
            </div>
        </div>
    </template>

    <template id="video-block-template">
        <div id="__ELEMENT_ID__" class="draggable-element-block" data-element-type="video">
            <div class="block-item-card js-toggle-edit-form" data-type="Video" data-target-id="__ELEMENT_ID__">
                <i class="fas fa-grip-vertical drag-handle drag-handle-icon js-stop-propagation" title="Tarik ke atas/bawah untuk ubah urutan"></i>
                <div class="block-item-icon-wrapper">
                    <i class="fab fa-youtube"></i>
                </div>
                <div class="block-item-content">
                    <div class="block-item-title-wrapper">
                        <span>Video</span>
                    </div>
                </div>
                <div class="block-item-actions js-stop-propagation" >
                    <div class="element-visibility-container">
                        <span class="visibility-status-text status-active" id="statusText___ELEMENT_ID__">Aktif</span>
                        <label class="toggle-switch">
                            <input class="js-toggle-visibility" type="checkbox" id="visibilitySwitch___ELEMENT_ID__" data-target-id="__ELEMENT_ID__" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    <button type="button" class="btn-element-action btn-delete-icon js-remove-element" data-type="Video" data-target-id="__ELEMENT_ID__" title="Hapus Elemen">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                    <button type="button" data-type="Video" data-target-id="__ELEMENT_ID__" class="btn-edit-block js-toggle-edit-form">
                        <i class="fas fa-pen" class="btn-edit-icon"></i> <span id="btnText___ELEMENT_ID__">Edit</span>
                    </button>
                </div>
            </div>

            <div id="formBody___ELEMENT_ID__" class="edit-form-body" style="max-height: 0; opacity: 0; margin-top: 0;">
                <div class="edit-form-content">
                    <div class="profile-form-header" style="margin-bottom: 20px; font-weight: 600; color: #0f172a; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                        <i class="fab fa-youtube" style="color: #ef4444; margin-right: 8px;"></i> Pengaturan Video
                    </div>
                    
                    <div class="profile-form-group">
                        <label class="profile-form-label" style="font-weight: 600; color: #334155; margin-bottom: 8px; display: block;">Tautan Video YouTube</label>
                        <div style="position: relative;">
                            <div style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #ef4444; font-size: 18px; pointer-events: none;">
                                <i class="fab fa-youtube"></i>
                            </div>
                            <input type="text" id="videoUrl___ELEMENT_ID__" class="form-control-input js-update-video-preview" value="" placeholder="Tempel URL YouTube di sini..." data-target-id="__ELEMENT_ID__" style="padding-left: 42px; border-radius: 12px; background-color: #f8fafc; border: 1px solid #e2e8f0; transition: all 0.3s ease;">
                        </div>
                    </div>
                    
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; margin-top: 20px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="background: #fff; padding: 10px; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); color: #3b82f6; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-play" style="font-size: 14px;"></i>
                            </div>
                            <div>
                                <label class="profile-form-label" style="margin-bottom: 2px; font-weight: 600; color: #334155;">Putar Otomatis (Autoplay)</label>
                                <span style="font-size: 12px; color: #64748b; line-height: 1.4; display: block;">Video akan otomatis diputar saat diakses.</span>
                            </div>
                        </div>
                        <label class="toggle-switch" style="margin: 0; flex-shrink: 0;">
                            <input class="js-update-video-preview" type="checkbox" id="videoAutoplay___ELEMENT_ID__" data-target-id="__ELEMENT_ID__">
                            <span class="toggle-slider"></span>
                        </label>
                    </div>

                    <div class="element-action-footer">
                        <button type="button" data-type="Video" data-target-id="__ELEMENT_ID__" class="btn-delete-element js-remove-element">
                            <i class="fas fa-trash-alt"></i> Hapus Elemen
                        </button>
                        <button type="button" data-type="Video" data-target-id="__ELEMENT_ID__" class="btn-save-element js-save-element" id="btnSaveVideo___ELEMENT_ID__">
                            Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <!-- CUSTOM DELETE CONFIRMATION MODAL -->

    <template id="social-live-template">
        <div id="live___ELEMENT_ID__" class="microsite-live-element live-social-wrapper js-toggle-edit-form" data-type="Social" data-target-id="__ELEMENT_ID__" data-force-open="true">
            <div id="liveSocialContainer___ELEMENT_ID__" class="live-social-container live-social-container-styled">
                <div class="live-social-placeholder">
                    <i class="fas fa-share-alt live-social-placeholder-icon"></i>
                    Atur Media Sosial
                </div>
            </div>
        </div>
    </template>

    <template id="social-platform-item-template">
        <div class="social-platform-item" id="platform_item___PLATFORM_____ELEMENT_ID__">
            <div class="platform-header">
                <div class="platform-info">
                    <i class="__ICON_CLASS__" style="color: __COLOR__; font-size: 20px; width: 24px; text-align: center;"></i>
                    <span class="platform-name">__PLATFORM_NAME__</span>
                </div>
                        <button type="button" class="btn-remove-platform js-remove-social-platform" data-target-id="__ELEMENT_ID__" data-platform="__PLATFORM__" title="Hapus Platform">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
            <div class="platform-input-container">
                <label class="form-label-custom">URL atau Username __PLATFORM_NAME__</label>
                <input type="text" id="input___PLATFORM_____ELEMENT_ID__" class="form-input-custom platform-input-trigger js-update-social-preview js-update-social-preview" data-platform="__PLATFORM__" data-element="__ELEMENT_ID__" placeholder="__PLACEHOLDER__" data-target-id="__ELEMENT_ID__" data-target-id="__ELEMENT_ID__">
            </div>
        </div>
    </template>
    <template id="social-block-template">
        <div id="__ELEMENT_ID__" class="draggable-element-block" data-element-type="social">
            <div class="block-item-card js-toggle-edit-form" data-type="Social" data-target-id="__ELEMENT_ID__">
                <i class="fas fa-grip-vertical drag-handle drag-handle-icon js-stop-propagation" title="Tarik ke atas/bawah untuk ubah urutan"></i>
                <div class="block-item-icon-wrapper">
                    <i class="fas fa-share-alt"></i>
                </div>
                <div class="block-item-content">
                    <div class="block-item-title-wrapper">
                        <span>Media Sosial</span>
                    </div>
                </div>
                <div class="block-item-actions js-stop-propagation" >
                    <div class="element-visibility-container">
                        <span class="visibility-status-text status-active" id="statusText___ELEMENT_ID__">Aktif</span>
                        <label class="toggle-switch">
                            <input class="js-toggle-visibility" type="checkbox" id="visibilitySwitch___ELEMENT_ID__" data-target-id="__ELEMENT_ID__" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    <button type="button" class="btn-element-action btn-delete-icon js-remove-element" data-type="Element" data-target-id="__ELEMENT_ID__" title="Hapus Elemen">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                    <button type="button" data-type="Social" data-target-id="__ELEMENT_ID__" class="btn-edit-block js-toggle-edit-form">
                        <i class="fas fa-pen" class="btn-edit-icon"></i> <span id="btnText___ELEMENT_ID__">Edit</span>
                    </button>
                </div>
            </div>

            <!-- Form Edit untuk Media Sosial -->
            <div id="formBody___ELEMENT_ID__" class="edit-form-body" style="max-height: 0; opacity: 0; margin-top: 0;">
                <div class="edit-form-content">
                    <form id="socialForm___ELEMENT_ID__">
                        <div class="social-edit-header">
                            <h4 class="form-section-title social-edit-title"><i class="fas fa-share-alt"></i> Pengaturan Media Sosial</h4>
                            <p class="social-edit-desc">Aktifkan platform yang ingin Anda tampilkan.</p>
                        </div>

                        <!-- PLATFORM LIST CONTAINER -->
                        <div class="social-platforms-list" id="social_platforms_list___ELEMENT_ID__">
                            <!-- Selected platforms will be appended here via JS -->
                        </div>

                        <!-- ADD PLATFORM BUTTON -->
                        <div class="social-add-platform-container">
                            <button type="button" class="btn btn-outline btn-primary btn-sm js-open-social-selector" data-target-id="__ELEMENT_ID__" class="btn-add-platform-dashed">
                                <i class="fas fa-plus"></i> Tambah Platform Media Sosial
                            </button>
                        </div>

                        <div class="form-actions-wrapper">
                            <button type="button" class="btn-secondary js-toggle-edit-form" data-type="Social" data-target-id="__ELEMENT_ID__">
                                Batal
                            </button>
                            <button type="button" class="btn-primary btn-submit js-save-element" data-type="SocialMedia" data-target-id="__ELEMENT_ID__">
                                <i class="fas fa-save submit-icon-margin"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>
    <!-- Social Platform Selection Modal -->
    <div id="socialPlatformModal" class="custom-confirm-modal-overlay">
        <div class="custom-confirm-modal-box platform-modal">
            <h3 class="platform-modal-title">Choose your platforms</h3>
            
            <div class="social-platform-grid">
                <button type="button" class="btn-select-platform js-toggle-social-selection" data-platform="linkedin" >
                    <i class="fab fa-linkedin platform-icon" style="color: #0077b5;"></i>
                    <div class="platform-info">
                        <div class="platform-name">Linkedin</div>
                        <div class="platform-desc">Good for B2B businesses</div>
                    </div>
                    <div class="platform-checkbox"></div>
                </button>
                <button type="button" class="btn-select-platform js-toggle-social-selection" data-platform="twitter" >
                    <i class="fab fa-x-twitter platform-icon" style="color: #000000;"></i>
                    <div class="platform-info">
                        <div class="platform-name">X (Twitter)</div>
                        <div class="platform-desc">Good for B2B businesses</div>
                    </div>
                    <div class="platform-checkbox"></div>
                </button>
                <button type="button" class="btn-select-platform js-toggle-social-selection" data-platform="facebook" >
                    <i class="fab fa-facebook platform-icon" style="color: #1877F2;"></i>
                    <div class="platform-info">
                        <div class="platform-name">Facebook</div>
                        <div class="platform-desc">Good for B2B businesses</div>
                    </div>
                    <div class="platform-checkbox"></div>
                </button>
                <button type="button" class="btn-select-platform js-toggle-social-selection" data-platform="instagram" >
                    <i class="fab fa-instagram platform-icon" style="color: #E1306C;"></i>
                    <div class="platform-info">
                        <div class="platform-name">Instagram</div>
                        <div class="platform-desc">Good for B2B businesses</div>
                    </div>
                    <div class="platform-checkbox"></div>
                </button>
                <button type="button" class="btn-select-platform js-toggle-social-selection" data-platform="reddit" >
                    <i class="fab fa-reddit platform-icon" style="color: #FF4500;"></i>
                    <div class="platform-info">
                        <div class="platform-name">Reddit</div>
                        <div class="platform-desc">Good for B2B businesses</div>
                    </div>
                    <div class="platform-checkbox"></div>
                </button>
                <button type="button" class="btn-select-platform js-toggle-social-selection" data-platform="youtube" >
                    <i class="fab fa-youtube platform-icon" style="color: #FF0000;"></i>
                    <div class="platform-info">
                        <div class="platform-name">YouTube</div>
                        <div class="platform-desc">Good for B2B businesses</div>
                    </div>
                    <div class="platform-checkbox"></div>
                </button>
                <button type="button" class="btn-select-platform js-toggle-social-selection" data-platform="whatsapp" >
                    <i class="fab fa-whatsapp platform-icon" style="color: #25D366;"></i>
                    <div class="platform-info">
                        <div class="platform-name">WhatsApp</div>
                        <div class="platform-desc">Good for B2B businesses</div>
                    </div>
                    <div class="platform-checkbox"></div>
                </button>
                <button type="button" class="btn-select-platform js-toggle-social-selection" data-platform="tiktok" >
                    <i class="fab fa-tiktok platform-icon" style="color: #000000;"></i>
                    <div class="platform-info">
                        <div class="platform-name">TikTok</div>
                        <div class="platform-desc">Good for B2B businesses</div>
                    </div>
                    <div class="platform-checkbox"></div>
                </button>
            </div>
            
            <div class="platform-modal-actions">
                <button type="button" class="btn-platform-back js-close-social-selector" >Back to Previous</button>
                <button type="button" class="btn-platform-finish js-finish-social-selection" >Finish Steps</button>
            </div>
        </div>
    </div>
    <div id="customDeleteConfirmModal" class="custom-confirm-modal-overlay">
        <div class="custom-confirm-modal-box">
            <button class="custom-confirm-close-btn js-close-delete-modal" >
                <i class="fas fa-times"></i>
            </button>
            <div class="custom-confirm-icon-wrapper">
                <i class="fas fa-trash-alt"></i>
            </div>
            <h3 class="custom-confirm-title" id="customDeleteConfirmTitle">Are you sure you want to delete this product?</h3>
            <div class="custom-confirm-actions">
                <button class="btn-custom-confirm-cancel js-close-delete-modal" >Batal</button>
                <button class="btn-custom-confirm-submit js-confirm-delete-modal" id="btnConfirmDelete" >Ya, Hapus</button>
            </div>
        </div>
    </div>

