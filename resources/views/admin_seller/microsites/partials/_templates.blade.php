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

                    <div class="profile-form-group text-form-group-spacing" style="margin-top: 15px;">
                                                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                                                    <label class="profile-form-label dynamic-form-label-bold" style="margin-bottom: 0;">Gunakan Tombol Accordion</label>
                                                    <label class="toggle-switch">
                                                        <input type="checkbox" id="hasButton___ELEMENT_ID__" class="js-toggle-text-button" data-target-id="__ELEMENT_ID__" >
                                                        <span class="toggle-slider"></span>
                                                    </label>
                                                </div>
                                                <div id="buttonFields___ELEMENT_ID__" style="display: none; border: 1px solid #e0e0e0; padding: 15px; border-radius: 8px; background: #fafafa;">
                                                    <div style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 15px;">
                                                        <div class="dp-form-row-box" style="margin-bottom: 0; padding: 12px 15px;">
                                                            <label class="dp-row-label">Teks Tombol</label>
                                                            <input type="text" id="buttonText___ELEMENT_ID__" class="dp-row-input" placeholder="Contoh: Baca Selengkapnya" value="" maxlength="50" pattern="[^<>]*" title="Karakter &lt; dan &gt; tidak diperbolehkan untuk mencegah injeksi keamanan.">
                                                        </div>
                                                        <div class="dp-form-row-box" style="margin-bottom: 0; padding: 12px 15px;">
                                                            <label class="dp-row-label">Warna Tombol</label>
                                                            <div style="display: flex; align-items: center; width: 100%;">
                                                                <input type="color" id="buttonColor___ELEMENT_ID__" class="dp-row-input" style="padding: 0; height: 32px; width: 48px; cursor: pointer; border-radius: 6px; border: 1px solid #e5e7eb;" value="#f8f9fa">
                                                                <span style="margin-left: 12px; font-size: 13px; color: #6b7280;">Sesuaikan warna latar belakang tombol</span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <style>
/* Advanced Icon Picker Styles */
.adv-icon-picker {
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    background: #fff;
    overflow: hidden;
    margin-bottom: 15px;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
}
.adv-icon-picker-header {
    display: flex;
    background: #f8fafc;
    padding: 8px;
    border-bottom: 1px solid #e2e8f0;
}
.adv-picker-tab {
    flex: 1;
    text-align: center;
    padding: 8px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 13px;
    font-weight: 600;
    color: #64748b;
    transition: all 0.2s;
}
.adv-picker-tab.active {
    background: #fff;
    color: #0f172a;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}
.adv-picker-body {
    padding: 15px;
}
.adv-subtabs {
    display: flex;
    gap: 15px;
    border-bottom: 1px solid #e2e8f0;
    margin-bottom: 15px;
    padding-bottom: 8px;
}
.adv-subtab {
    font-size: 13px;
    font-weight: 600;
    color: #94a3b8;
    cursor: pointer;
    position: relative;
}
.adv-subtab.active {
    color: #0f172a;
}
.adv-subtab.active::after {
    content: '';
    position: absolute;
    bottom: -9px;
    left: 0;
    width: 100%;
    height: 2px;
    background: #3b82f6;
}
.emoji-category-nav {
    display: flex;
    justify-content: space-between;
    padding-bottom: 10px;
    border-bottom: 1px solid #f1f5f9;
    margin-bottom: 10px;
}
.emoji-cat-icon {
    color: #94a3b8;
    font-size: 16px;
    cursor: pointer;
    padding: 4px;
    transition: color 0.2s;
}
.emoji-cat-icon:hover, .emoji-cat-icon.active {
    color: #3b82f6;
}
.emoji-scroll-container {
    height: 250px;
    overflow-y: auto;
    padding-right: 5px;
}
.emoji-scroll-container::-webkit-scrollbar {
    width: 4px;
}
.emoji-scroll-container::-webkit-scrollbar-thumb {
    background-color: #cbd5e1;
    border-radius: 4px;
}
.emoji-section-title {
    font-size: 12px;
    color: #64748b;
    font-weight: 600;
    margin-top: 10px;
    margin-bottom: 8px;
}
.emoji-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(32px, 1fr));
    gap: 4px;
}
.emoji-item {
    font-size: 20px;
    text-align: center;
    padding: 4px 0;
    cursor: pointer;
    border-radius: 4px;
    transition: background 0.2s;
}
.emoji-item:hover {
    background: #f1f5f9;
}
</style>
<div class="adv-icon-picker">
    <div class="adv-icon-picker-header">
        <div class="adv-picker-tab js-adv-tab active" data-target-id="__ELEMENT_ID__" data-tab="pilih-icon">Pilih Ikon</div>
        <div class="adv-picker-tab js-adv-tab " data-target-id="__ELEMENT_ID__" data-tab="unggah-gambar">Unggah gambar</div>
    </div>
    <div class="adv-picker-body">
            <div id="advTab_pilih-icon___ELEMENT_ID__" style="display: block;">
            <div class="adv-subtabs">
                <div class="adv-subtab js-adv-subtab active" data-target-id="__ELEMENT_ID__" data-subtab="emoji">Emoji</div>
                <div class="adv-subtab js-adv-subtab " data-target-id="__ELEMENT_ID__" data-subtab="fontawesome">FontAwesome</div>
                <div class="adv-subtab js-adv-subtab " data-target-id="__ELEMENT_ID__" data-subtab="url">URL</div>
            </div>
            <div id="advSubTab_emoji___ELEMENT_ID__" style="display: block;">
                <div class="emoji-category-nav">
                    <i class="far fa-clock emoji-cat-icon" title="Frequently used"></i>
                    <i class="far fa-smile emoji-cat-icon js-scroll-cat" data-target-id="__ELEMENT_ID__" data-cat="4c2fe5cabef81537c1921551e7e7b679" title="Smileys & Emotion"></i>
                    <i class="fas fa-user-friends emoji-cat-icon js-scroll-cat" data-target-id="__ELEMENT_ID__" data-cat="d1310ead50484fb1c2564b043ea17413" title="People & Body"></i>
                    <i class="fas fa-paw emoji-cat-icon js-scroll-cat" data-target-id="__ELEMENT_ID__" data-cat="cb6bd7088e49721cf521ce24d552586d" title="Animals & Nature"></i>
                    <i class="fas fa-hamburger emoji-cat-icon js-scroll-cat" data-target-id="__ELEMENT_ID__" data-cat="614adc8c0f0f72ef1192968ea945c510" title="Food & Drink"></i>
                    <i class="fas fa-car emoji-cat-icon js-scroll-cat" data-target-id="__ELEMENT_ID__" data-cat="b6ad445740b20f7373d22a1cb6c4e9af" title="Travel & Places"></i>
                    <i class="fas fa-lightbulb emoji-cat-icon js-scroll-cat" data-target-id="__ELEMENT_ID__" data-cat="c8308b1eba7ba926a61b8fd802194386" title="Objects"></i>
                </div>
                <div class="profile-form-group" style="margin-bottom: 10px;">
                    <input type="text" id="buttonIconEmoji___ELEMENT_ID__" class="profile-input js-emoji-search" data-target-id="__ELEMENT_ID__" placeholder="🔍 Cari emoji..." value="" style="padding: 8px 12px; font-size: 13px;" maxlength="30" pattern="[^<>]*" title="Karakter &lt; dan &gt; tidak diperbolehkan untuk mencegah injeksi keamanan.">
                </div>
                <div class="emoji-scroll-container" id="emojiScroll___ELEMENT_ID__">
                    <div class="emoji-section-title" id="cat_4c2fe5cabef81537c1921551e7e7b679___ELEMENT_ID__">Smileys & Emotion</div>
                    <div class="emoji-grid">
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😀">😀</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😃">😃</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😄">😄</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😁">😁</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😆">😆</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😅">😅</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😂">😂</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🤣">🤣</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😊">😊</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😇">😇</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🙂">🙂</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🙃">🙃</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😉">😉</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😌">😌</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😍">😍</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🥰">🥰</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😘">😘</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😗">😗</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😙">😙</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😚">😚</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😋">😋</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😛">😛</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😝">😝</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😜">😜</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🤪">🤪</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🤨">🤨</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🧐">🧐</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🤓">🤓</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😎">😎</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🤩">🤩</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🥳">🥳</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😏">😏</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😒">😒</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😞">😞</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😔">😔</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😟">😟</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😕">😕</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🙁">🙁</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="☹️">☹️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😣">😣</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😖">😖</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😫">😫</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😩">😩</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🥺">🥺</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😢">😢</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😭">😭</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😤">😤</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😠">😠</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😡">😡</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🤬">🤬</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🤯">🤯</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😳">😳</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🥵">🥵</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🥶">🥶</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😱">😱</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😨">😨</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😰">😰</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😥">😥</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😓">😓</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🤗">🤗</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🤔">🤔</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🤭">🤭</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🤫">🤫</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🤥">🤥</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😶">😶</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😐">😐</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😑">😑</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😬">😬</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🙄">🙄</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😯">😯</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😦">😦</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😧">😧</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😮">😮</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😲">😲</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🥱">🥱</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😴">😴</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🤤">🤤</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😪">😪</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😵">😵</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🤐">🤐</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🥴">🥴</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🤢">🤢</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🤮">🤮</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🤧">🤧</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😷">😷</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🤒">🤒</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🤕">🤕</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🤑">🤑</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🤠">🤠</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="😈">😈</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="👿">👿</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="👹">👹</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="👺">👺</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🤡">🤡</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="💩">💩</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="👻">👻</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="💀">💀</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="☠️">☠️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="👽">👽</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="👾">👾</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🤖">🤖</div>
                    </div>
                    <div class="emoji-section-title" id="cat_d1310ead50484fb1c2564b043ea17413___ELEMENT_ID__">People & Body</div>
                    <div class="emoji-grid">
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="👋">👋</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🤚">🤚</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🖐️">🖐️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="✋">✋</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🖖">🖖</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="👌">👌</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🤌">🤌</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🤏">🤏</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="✌️">✌️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🤞">🤞</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🤟">🤟</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🤘">🤘</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🤙">🤙</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="👈">👈</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="👉">👉</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="👆">👆</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🖕">🖕</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="👇">👇</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="☝️">☝️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="👍">👍</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="👎">👎</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="✊">✊</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="👊">👊</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🤛">🤛</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🤜">🤜</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="👏">👏</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🙌">🙌</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="👐">👐</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🤲">🤲</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🤝">🤝</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🙏">🙏</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="✍️">✍️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="💅">💅</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🤳">🤳</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="💪">💪</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦾">🦾</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦿">🦿</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦵">🦵</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦶">🦶</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="👂">👂</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦻">🦻</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="👃">👃</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🧠">🧠</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🫀">🫀</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🫁">🫁</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦷">🦷</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="骨">骨</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="👀">👀</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="👁️">👁️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="👅">👅</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="👄">👄</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="💋">💋</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🩸">🩸</div>
                    </div>
                    <div class="emoji-section-title" id="cat_cb6bd7088e49721cf521ce24d552586d___ELEMENT_ID__">Animals & Nature</div>
                    <div class="emoji-grid">
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐶">🐶</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐱">🐱</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐭">🐭</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐹">🐹</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐰">🐰</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦊">🦊</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐻">🐻</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐼">🐼</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐻‍❄️">🐻‍❄️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐨">🐨</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐯">🐯</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦁">🦁</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐮">🐮</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐷">🐷</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐽">🐽</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐸">🐸</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐵">🐵</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🙈">🙈</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🙉">🙉</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🙊">🙊</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐒">🐒</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐔">🐔</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐧">🐧</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐦">🐦</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐤">🐤</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐣">🐣</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐥">🐥</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦆">🦆</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦅">🦅</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦉">🦉</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦇">🦇</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐺">🐺</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐗">🐗</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐴">🐴</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦄">🦄</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐝">🐝</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🪱">🪱</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐛">🐛</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦋">🦋</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐌">🐌</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐞">🐞</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐜">🐜</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🪰">🪰</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🪲">🪲</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🪳">🪳</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦟">🦟</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦗">🦗</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🕷️">🕷️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🕸️">🕸️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦂">🦂</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐢">🐢</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐍">🐍</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦎">🦎</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦖">🦖</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦕">🦕</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐙">🐙</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦑">🦑</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦐">🦐</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦞">🦞</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦀">🦀</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐡">🐡</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐠">🐠</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐟">🐟</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐬">🐬</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐳">🐳</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐋">🐋</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦈">🦈</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦭">🦭</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐊">🐊</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐅">🐅</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐆">🐆</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦓">🦓</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦍">🦍</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦧">🦧</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦣">🦣</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐘">🐘</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦛">🦛</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦏">🦏</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐪">🐪</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐫">🐫</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦒">🦒</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦘">🦘</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦬">🦬</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐃">🐃</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐂">🐂</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐄">🐄</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐎">🐎</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐖">🐖</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐏">🐏</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐑">🐑</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦙">🦙</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐐">🐐</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦌">🦌</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐕">🐕</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐩">🐩</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦮">🦮</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐕‍🦺">🐕‍🦺</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐈">🐈</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐈‍⬛">🐈‍⬛</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🪶">🪶</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐓">🐓</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦃">🦃</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦤">🦤</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦚">🦚</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦜">🦜</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦢">🦢</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦩">🦩</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🕊️">🕊️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐇">🐇</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦝">🦝</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦨">🦨</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦡">🦡</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦫">🦫</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦦">🦦</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦥">🦥</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐁">🐁</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐀">🐀</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐿️">🐿️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦔">🦔</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐾">🐾</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐉">🐉</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐲">🐲</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🌵">🌵</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🎄">🎄</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🌲">🌲</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🌳">🌳</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🌴">🌴</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🪵">🪵</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🌱">🌱</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🌿">🌿</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="☘️">☘️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍀">🍀</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🎍">🎍</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🪴">🪴</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🎋">🎋</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍃">🍃</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍂">🍂</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍁">🍁</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍄">🍄</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🐚">🐚</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🪨">🪨</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🌾">🌾</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="💐">💐</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🌷">🌷</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🌹">🌹</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🥀">🥀</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🌺">🌺</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🌸">🌸</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🌼">🌼</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🌻">🌻</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🌞">🌞</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🌝">🌝</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🌛">🌛</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🌜">🌜</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🌚">🌚</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🌕">🌕</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🌖">🌖</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🌗">🌗</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🌘">🌘</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🌑">🌑</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🌒">🌒</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🌓">🌓</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🌔">🌔</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🌙">🌙</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🌎">🌎</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🌍">🌍</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🌏">🌏</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🪐">🪐</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="💫">💫</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="⭐️">⭐️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🌟">🌟</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="✨">✨</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="⚡️">⚡️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="☄️">☄️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="💥">💥</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🔥">🔥</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🌪️">🌪️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🌈">🌈</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="☀️">☀️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🌤️">🌤️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="⛅️">⛅️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🌥️">🌥️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="☁️">☁️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🌦️">🌦️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🌧️">🌧️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="⛈️">⛈️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🌩️">🌩️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🌨️">🌨️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="❄️">❄️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="☃️">☃️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="⛄️">⛄️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🌬️">🌬️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="💨">💨</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="💧">💧</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="💦">💦</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="☔️">☔️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="☂️">☂️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🌊">🌊</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🌫️">🌫️</div>
                    </div>
                    <div class="emoji-section-title" id="cat_614adc8c0f0f72ef1192968ea945c510___ELEMENT_ID__">Food & Drink</div>
                    <div class="emoji-grid">
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍏">🍏</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍎">🍎</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍐">🍐</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍊">🍊</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍋">🍋</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍌">🍌</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍉">🍉</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍇">🍇</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍓">🍓</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🫐">🫐</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍈">🍈</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍒">🍒</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍑">🍑</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🥭">🥭</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍍">🍍</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🥥">🥥</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🥝">🥝</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍅">🍅</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍆">🍆</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🥑">🥑</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🥦">🥦</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🥬">🥬</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🥒">🥒</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🌶️">🌶️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🫑">🫑</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🌽">🌽</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🥕">🥕</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🫒">🫒</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🧄">🧄</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🧅">🧅</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🥔">🥔</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍠">🍠</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🥐">🥐</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🥯">🥯</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍞">🍞</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🥖">🥖</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🥨">🥨</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🧀">🧀</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🥚">🥚</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍳">🍳</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🧈">🧈</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🥞">🥞</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🧇">🧇</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🥓">🥓</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🥩">🥩</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍗">🍗</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍖">🍖</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦴">🦴</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🌭">🌭</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍔">🍔</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍟">🍟</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍕">🍕</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🫓">🫓</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🥪">🥪</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🥙">🥙</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🧆">🧆</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🌮">🌮</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🌯">🌯</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🫔">🫔</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🥗">🥗</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🥘">🥘</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🫕">🫕</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🥫">🥫</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍝">🍝</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍜">🍜</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍲">🍲</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍛">🍛</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍣">🍣</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍱">🍱</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🥟">🥟</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦪">🦪</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍤">🍤</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍙">🍙</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍚">🍚</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍘">🍘</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍥">🍥</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🥠">🥠</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🥮">🥮</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍢">🍢</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍡">🍡</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍧">🍧</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍨">🍨</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍦">🍦</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🥧">🥧</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🧁">🧁</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍰">🍰</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🎂">🎂</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍮">🍮</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍭">🍭</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍬">🍬</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍫">🍫</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍿">🍿</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍩">🍩</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍪">🍪</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🌰">🌰</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🥜">🥜</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍯">🍯</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🥛">🥛</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍼">🍼</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🫖">🫖</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="☕️">☕️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍵">🍵</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🧃">🧃</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🥤">🥤</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🧋">🧋</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍶">🍶</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍺">🍺</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍻">🍻</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🥂">🥂</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍷">🍷</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🥃">🥃</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍸">🍸</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍹">🍹</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🧉">🧉</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍾">🍾</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🧊">🧊</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🥄">🥄</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍴">🍴</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🍽️">🍽️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🥣">🥣</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🥡">🥡</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🥢">🥢</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🧂">🧂</div>
                    </div>
                    <div class="emoji-section-title" id="cat_b6ad445740b20f7373d22a1cb6c4e9af___ELEMENT_ID__">Travel & Places</div>
                    <div class="emoji-grid">
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🚗">🚗</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🚕">🚕</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🚙">🚙</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🚌">🚌</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🚎">🚎</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🏎️">🏎️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🚓">🚓</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🚑">🚑</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🚒">🚒</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🚐">🚐</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🛻">🛻</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🚚">🚚</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🚛">🚛</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🚜">🚜</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦯">🦯</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦽">🦽</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦼">🦼</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🛴">🛴</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🚲">🚲</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🛵">🛵</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🏍️">🏍️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🛺">🛺</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🚨">🚨</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🚔">🚔</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🚍">🚍</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🚘">🚘</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🚖">🚖</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🚡">🚡</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🚠">🚠</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🚟">🚟</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🚃">🚃</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🚋">🚋</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🚞">🚞</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🚝">🚝</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🚄">🚄</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🚅">🚅</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🚈">🚈</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🚂">🚂</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🚆">🚆</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🚇">🚇</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🚊">🚊</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🚉">🚉</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="✈️">✈️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🛫">🛫</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🛬">🛬</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🛩️">🛩️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="💺">💺</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🛰️">🛰️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🚀">🚀</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🛸">🛸</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🚁">🚁</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🛶">🛶</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="⛵️">⛵️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🚤">🚤</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🛥️">🛥️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🛳️">🛳️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="⛴️">⛴️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🚢">🚢</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="⚓️">⚓️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🪝">🪝</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="⛽️">⛽️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🚧">🚧</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🚦">🚦</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🚥">🚥</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🚏">🚏</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🗺️">🗺️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🗿">🗿</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🗽">🗽</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🗼">🗼</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🏰">🏰</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🏯">🏯</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🏟️">🏟️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🎡">🎡</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🎢">🎢</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🎠">🎠</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="⛲️">⛲️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="⛱️">⛱️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🏖️">🏖️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🏝️">🏝️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🏜️">🏜️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🌋">🌋</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="⛰️">⛰️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🏔️">🏔️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🗻">🗻</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🏕️">🏕️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="⛺️">⛺️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🛖">🛖</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🏠">🏠</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🏡">🏡</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🏘️">🏘️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🏚️">🏚️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🏗️">🏗️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🏭">🏭</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🏢">🏢</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🏬">🏬</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🏣">🏣</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🏤">🏤</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🏥">🏥</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🏦">🏦</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🏨">🏨</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🏪">🏪</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🏫">🏫</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🏩">🏩</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="💒">💒</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🏛️">🏛️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="⛪️">⛪️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🕌">🕌</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🛕">🛕</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🕍">🕍</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="⛩️">⛩️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🕋">🕋</div>
                    </div>
                    <div class="emoji-section-title" id="cat_c8308b1eba7ba926a61b8fd802194386___ELEMENT_ID__">Objects</div>
                    <div class="emoji-grid">
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="⌚️">⌚️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="📱">📱</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="📲">📲</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="💻">💻</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="⌨️">⌨️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🖥️">🖥️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🖨️">🖨️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🖱️">🖱️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🖲️">🖲️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🕹️">🕹️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🗜️">🗜️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="💽">💽</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="💾">💾</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="💿">💿</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="📀">📀</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="📼">📼</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="📷">📷</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="📸">📸</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="📹">📹</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🎥">🎥</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="📽️">📽️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🎞️">🎞️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="📞">📞</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="☎️">☎️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="📟">📟</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="📠">📠</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="📺">📺</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="📻">📻</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🎙️">🎙️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🎚️">🎚️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🎛️">🎛️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🧭">🧭</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="⏱️">⏱️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="⏲️">⏲️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="⏰">⏰</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🕰️">🕰️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="⌛️">⌛️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="⏳">⏳</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="📡">📡</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🔋">🔋</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🔌">🔌</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="💡">💡</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🔦">🔦</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🕯️">🕯️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🪔">🪔</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🧯">🧯</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🛢️">🛢️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="💸">💸</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="💵">💵</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="💴">💴</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="💶">💶</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="💷">💷</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🪙">🪙</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="💰">💰</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="💳">💳</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="💎">💎</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="⚖️">⚖️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🪜">🪜</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🧰">🧰</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🪛">🪛</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🔧">🔧</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🔨">🔨</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="⚒️">⚒️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🛠️">🛠️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="⛏️">⛏️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🪚">🪚</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🔩">🔩</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="⚙️">⚙️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🪤">🪤</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🧱">🧱</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="⛓️">⛓️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🧲">🧲</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🔫">🔫</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="💣">💣</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🧨">🧨</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🪓">🪓</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🔪">🔪</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🗡️">🗡️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="⚔️">⚔️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🛡️">🛡️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🚬">🚬</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="⚰️">⚰️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🪦">🪦</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="⚱️">⚱️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🏺">🏺</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🔮">🔮</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="📿">📿</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🧿">🧿</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="💈">💈</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="⚗️">⚗️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🔭">🔭</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🔬">🔬</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🕳️">🕳️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🩹">🩹</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🩺">🩺</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="💊">💊</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="💉">💉</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🩸">🩸</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🧬">🧬</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🦠">🦠</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🧫">🧫</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🧪">🧪</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🌡️">🌡️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🧹">🧹</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🪠">🪠</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🧺">🧺</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🧻">🧻</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🚽">🚽</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🚰">🚰</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🚿">🚿</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🛁">🛁</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🛀">🛀</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🧼">🧼</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🪥">🪥</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🪒">🪒</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🧽">🧽</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🪣">🪣</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🧴">🧴</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🛎️">🛎️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🔑">🔑</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🗝️">🗝️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🚪">🚪</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🪑">🪑</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🛋️">🛋️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🛏️">🛏️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🛌">🛌</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🧸">🧸</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🪆">🪆</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🖼️">🖼️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🪞">🪞</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🪟">🪟</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🛍️">🛍️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🛒">🛒</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🎁">🎁</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🎈">🎈</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🎏">🎏</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🎀">🎀</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🪄">🪄</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🪅">🪅</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🎊">🎊</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="__ELEMENT_ID__" data-emoji="🎉">🎉</div>
                    </div>
                </div>
            </div>
            <div id="advSubTab_fontawesome___ELEMENT_ID__" style="display: none;">
                <div class="profile-form-group" style="margin-bottom: 0;">
                    <label class="profile-form-label">Class FontAwesome (contoh: fas fa-home)</label>
                    <input type="text" id="buttonIconFa___ELEMENT_ID__" class="profile-input js-update-text-preview" placeholder="fas fa-home" value="" maxlength="50" pattern="[a-zA-Z0-9\-\s]+" title="Hanya huruf, angka, spasi, dan strip yang diperbolehkan untuk nama class ikon">
                </div>
            </div>
            <div id="advSubTab_url___ELEMENT_ID__" style="display: none;">
                <div class="profile-form-group" style="margin-bottom: 0;">
                    <label class="profile-form-label">URL Gambar Ikon</label>
                    <input type="url" id="buttonIconUrl___ELEMENT_ID__" class="profile-input js-update-text-preview" placeholder="https://contoh.com/ikon.png" value="" maxlength="255" pattern="https?://.*" title="Harus berupa URL yang valid (http:// atau https://)">
                </div>
            </div>
        </div>
        <div id="advTab_unggah-gambar___ELEMENT_ID__" style="display: none;">
            <div class="profile-form-group" style="margin-bottom: 0;">
                <div style="border: 2px dashed #cbd5e1; border-radius: 8px; padding: 25px 15px; text-align: center; background: #f8fafc; cursor: pointer; transition: background 0.2s;" onclick="document.getElementById('buttonIconUpload___ELEMENT_ID__').click()" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#f8fafc'">
                    <i class="fas fa-cloud-upload-alt" style="font-size: 32px; color: #94a3b8; margin-bottom: 12px;"></i>
                    <div style="font-size: 14px; font-weight: 600; color: #475569; margin-bottom: 4px;">Klik untuk mengunggah gambar</div>
                    <div style="font-size: 12px; color: #64748b;">PNG, JPG, JPEG (Max 2MB)</div>
                </div>
                <input type="file" id="buttonIconUpload___ELEMENT_ID__" class="profile-input js-upload-icon-preview" data-target-id="__ELEMENT_ID__" accept="image/*" style="display: none;">
                <div id="uploadPreview___ELEMENT_ID__" style="display: none; align-items: center; margin-top: 15px; padding: 10px; background: #fff; border-radius: 8px; border: 1px solid #e2e8f0; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                    
                        
                        <div style="margin-left: 12px;">
                            <div style="font-size: 13px; font-weight: 600; color: #0f172a;">Gambar Tersimpan</div>
                            
                        </div>
                    
                </div>
            </div>
        </div>
        <input type="hidden" id="buttonIconType___ELEMENT_ID__" class="js-update-text-preview" value="none">
    </div>
</div>
<div style="margin-bottom: 15px; display: flex; align-items: center;">
    <label class="profile-form-label" style="margin-bottom: 0; margin-right: 15px;">Gunakan Ikon?</label>
    <div style="display: flex; gap: 15px;">
        <label style="display: flex; align-items: center; gap: 5px; font-size: 14px; cursor: pointer;">
            <input type="radio" name="use_icon___ELEMENT_ID__" class="js-toggle-use-icon" data-target-id="__ELEMENT_ID__" value="yes" > Ya
        </label>
        <label style="display: flex; align-items: center; gap: 5px; font-size: 14px; cursor: pointer;">
            <input type="radio" name="use_icon___ELEMENT_ID__" class="js-toggle-use-icon" data-target-id="__ELEMENT_ID__" value="none" checked> Tidak (Tanpa Ikon)
        </label>
    </div>
</div>
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
    
    <!-- Image Cropper Modal -->
    <div id="cropperModal" class="custom-confirm-modal-overlay" style="display: none; z-index: 9999;">
        <div class="custom-confirm-modal-box" style="max-width: 600px; width: 90%;">
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e2e8f0; padding-bottom: 12px; margin-bottom: 16px;">
                <h3 class="custom-confirm-title" style="margin: 0;">Sesuaikan Foto Profil</h3>
                <button class="custom-confirm-close-btn js-close-cropper-modal" style="position: static;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div style="width: 100%; max-height: 400px; background-color: #f8fafc; display: flex; justify-content: center; align-items: center; overflow: hidden; margin-bottom: 20px;">
                <img id="cropperImageTarget" src="" style="max-width: 100%; display: block;">
            </div>
            
            <div class="custom-confirm-actions">
                <button type="button" class="btn-custom-confirm-cancel js-close-cropper-modal">Batal</button>
                <button type="button" class="btn-custom-confirm-submit js-apply-crop" style="background-color: #3b82f6; border-color: #3b82f6;">Gunakan Foto</button>
            </div>
        </div>
    </div>


