@props(['elementId', 'data', 'isActive' => true])
<div id="{{ $elementId }}" class="draggable-element-block {{ $isActive ? '' : 'block-inactive' }}" data-element-type="text" data-db-id="{{ $data?->id }}">
                                    <div class="block-item-card js-toggle-edit-form" data-type="Text" data-target-id="{{ $elementId }}">
                                        <i class="fas fa-grip-vertical drag-handle drag-handle-icon js-stop-propagation" title="{{ __('microsite.drag_drop') }}"></i>
                                        <div class="block-item-icon-wrapper">
                                            <i class="fas fa-font"></i>
                                        </div>
                                        <div class="block-item-content">
                                            <div class="block-item-title-wrapper">
                                                <span>{{ __('microsite.text_title') }}</span>
                                            </div>
                                        </div>
                                        <div class="block-item-actions js-stop-propagation" >
                                            <div class="element-visibility-container">
                                                <span class="visibility-status-text {{ $isActive ? 'status-active' : 'status-inactive' }}" id="statusText_{{ $elementId }}">{{ $isActive ? 'Aktif' : 'Nonaktif' }}</span>
                                                <label class="toggle-switch">
                                                    <input class="js-toggle-visibility" type="checkbox" id="visibilitySwitch_{{ $elementId }}" data-target-id="{{ $elementId }}" {{ $isActive ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                            <button type="button" class="btn-element-action btn-delete-icon js-remove-element" data-type="Text" data-target-id="{{ $elementId }}" title="Hapus Elemen" aria-label="Hapus Elemen">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                            <button type="button" data-type="Text" data-target-id="{{ $elementId }}" class="btn-edit-block js-toggle-edit-form">
                                                <i class="fas fa-pen" class="btn-edit-icon"></i> <span id="btnText_{{ $elementId }}">{{ __('microsite.btn_edit') }}</span>
                                            </button>
                                        </div>
                                    </div>

                                    <div id="formBody_{{ $elementId }}" class="edit-form-body" style="max-height: 0; opacity: 0; margin-top: 0;">
                                        <div class="edit-form-content">
                                            <div class="profile-form-header dynamic-setting-header">
                                                <i class="fas fa-font dynamic-setting-header-icon-blue"></i> Pengaturan {{ __('microsite.text_title') }}
                                            </div>
                                            <div class="profile-form-group text-form-group-spacing">
                                                <label class="profile-form-label dynamic-form-label-bold">Isi {{ __('microsite.text_title') }} Konten</label>
                                                <div class="text-editor-container text-editor-container-styled">
                                                    <div class="text-editor-toolbar text-editor-toolbar-styled">
                                                    <button type="button" class="toolbar-btn js-exec-cmd" data-target-id="{{ $elementId }}" data-cmd="bold" title="Bold" aria-label="Bold"><i class="fas fa-bold"></i></button>
                                                    <button type="button" class="toolbar-btn js-exec-cmd" data-target-id="{{ $elementId }}" data-cmd="italic" title="Italic" aria-label="Italic"><i class="fas fa-italic"></i></button>
                                                    <button type="button" class="toolbar-btn js-exec-cmd" data-target-id="{{ $elementId }}" data-cmd="underline" title="Underline" aria-label="Underline"><i class="fas fa-underline"></i></button>
                                                    <button type="button" class="toolbar-btn js-exec-cmd" data-target-id="{{ $elementId }}" data-cmd="strikeThrough" title="Strikethrough" aria-label="Strikethrough"><i class="fas fa-strikethrough"></i></button>
                                                    <span class="toolbar-divider"></span>
                                                    <button type="button" class="toolbar-btn js-exec-cmd" data-target-id="{{ $elementId }}" data-cmd="justifyLeft" title="Align Left" aria-label="Align Left"><i class="fas fa-align-left"></i></button>
                                                    <button type="button" class="toolbar-btn js-exec-cmd" data-target-id="{{ $elementId }}" data-cmd="justifyCenter" title="Align Center" aria-label="Align Center"><i class="fas fa-align-center"></i></button>
                                                    <button type="button" class="toolbar-btn js-exec-cmd" data-target-id="{{ $elementId }}" data-cmd="justifyRight" title="Align Right" aria-label="Align Right"><i class="fas fa-align-right"></i></button>
                                                    <span class="toolbar-divider"></span>
                                                    <button type="button" class="toolbar-btn js-exec-cmd" data-target-id="{{ $elementId }}" data-cmd="insertUnorderedList" title="Bullet List" aria-label="Bullet List"><i class="fas fa-list-ul"></i></button>
                                                    <button type="button" class="toolbar-btn js-exec-cmd" data-target-id="{{ $elementId }}" data-cmd="insertOrderedList" title="Numbered List" aria-label="Numbered List"><i class="fas fa-list-ol"></i></button>
                                                    <span class="toolbar-divider"></span>
                                                    <label class="toolbar-color-picker" title="Pilih Warna {{ __('microsite.text_title') }}" aria-label="Pilih Warna {{ __('microsite.text_title') }}" style="cursor: pointer; display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 4px; color: #4b5563; transition: all 0.2s;">
                                                        <i class="fas fa-eye-dropper"></i>
                                                        <input type="color" aria-label="Warna {{ __('microsite.text_title') }}" class="hidden-color-input js-exec-cmd-value" data-target-id="{{ $elementId }}" data-cmd="foreColor" title="Text Color" value="#000000" style="opacity: 0; position: absolute; width: 0; height: 0;">
                                                    </label>
                                                    <span class="toolbar-divider"></span>
                                                    <div class="toolbar-dropdown">
                                                        <select aria-label="Ukuran {{ __('microsite.text_title') }}" data-target-id="{{ $elementId }}" class="toolbar-select js-change-text-size" id="textSizeSelect_{{ $elementId }}">
                                                            <option value="12px">Kecil (12px)</option>
                                                            <option value="16px" selected>Normal (16px)</option>
                                                            <option value="24px">Besar (24px)</option>
                                                            <option value="custom">Custom...</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div id="customSizeWrapper_{{ $elementId }}" class="custom-size-wrapper" style="display: none; align-items: center; gap: 8px; margin-top: 8px; background: #f8fafc; padding: 8px; border-radius: 6px; border: 1px solid #e2e8f0;">
                                                    <input type="number" aria-label="Ukuran Custom (px)" id="customSizeInput_{{ $elementId }}" class="toolbar-input js-apply-custom-size-input" placeholder="Ukuran (px)" min="1" max="99" data-target-id="{{ $elementId }}" style="width: 80px; padding: 4px 8px;">
                                                    <button type="button" class="toolbar-btn-text js-apply-custom-size" data-target-id="{{ $elementId }}">Terapkan</button>
                                                </div>
                                                <div id="editorContent_{{ $elementId }}" class="text-editor-area text-editor-area-styled js-update-text-preview" contenteditable="true" data-target-id="{{ $elementId }}">{!! $data?->content ?? __('microsite.text_title') . ' Anda di sini...' !!}</div>
                                                <div class="text-editor-counter-container" style="display: flex; justify-content: space-between; align-items: center; margin-top: 5px;">
                                                    <span id="charError_{{ $elementId }}" class="char-error" style="color: #ef4444; font-size: 12px; display: none; font-weight: 500;">Maksimal 250 kata! {{ __('microsite.text_title') }} gagal disimpan.</span>
                                                    @php
                                                        $rawText = strip_tags(html_entity_decode($data?->content ?? __('microsite.text_title') . ' Anda di sini...'));
                                                        $wordCount = $rawText === '' ? 0 : str_word_count($rawText);
                                                    @endphp
                                                    <span id="charCount_{{ $elementId }}" class="char-counter" style="font-size: 12px; color: #6b7280; text-align: right; flex-grow: 1;">{{ $wordCount }}/250 Kata</span>
                                                </div>
                                            </div>
                                            </div>

                                            <div class="profile-form-group text-form-group-spacing" style="margin-top: 15px;">
                                                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                                                    <label class="profile-form-label dynamic-form-label-bold" style="margin-bottom: 0;">Gunakan Tombol Accordion</label>
                                                    <label class="toggle-switch">
                                                        <input type="checkbox" id="hasButton_{{ $elementId }}" class="js-toggle-text-button" data-target-id="{{ $elementId }}" {{ ($data?->has_button ?? false) ? 'checked' : '' }}>
                                                        <span class="toggle-slider"></span>
                                                    </label>
                                                </div>
                                                <div id="buttonFields_{{ $elementId }}" style="display: {{ ($data?->has_button ?? false) ? 'block' : 'none' }}; border: 1px solid #e0e0e0; padding: 15px; border-radius: 8px; background: #fafafa;">
                                                    <div style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 15px;">
                                                        <div class="dp-form-row-box" style="margin-bottom: 0; padding: 12px 15px;">
                                                            <label class="dp-row-label">{{ __('microsite.text_title') }} Tombol</label>
                                                            <input type="text" id="buttonText_{{ $elementId }}" class="dp-row-input" placeholder="Contoh: Baca Selengkapnya" value="{{ $data?->button_text ?? '' }}" maxlength="50" pattern="[^<>]*" title="Karakter &lt; dan &gt; tidak diperbolehkan untuk mencegah injeksi keamanan.">
                                                        </div>
                                                        <div class="dp-form-row-box" style="margin-bottom: 0; padding: 12px 15px;">
                                                            <label class="dp-row-label">Warna Tombol</label>
                                                            <div style="display: flex; align-items: center; width: 100%;">
                                                                <input type="color" id="buttonColor_{{ $elementId }}" class="dp-row-input" style="padding: 0; height: 32px; width: 48px; cursor: pointer; border-radius: 6px; border: 1px solid #e5e7eb;" value="{{ $data?->button_color ?? '#f8f9fa' }}">
                                                                <span style="margin-left: 12px; font-size: 13px; color: #6b7280;">Sesuaikan warna latar belakang tombol</span>
                                                            </div>
                                                        </div>
                                                    </div>

<div class="adv-icon-picker">
    <div class="adv-icon-picker-header">
        @php
            $curType = $data?->button_icon_type ?? 'none';
            $isUpload = ($curType === 'upload');
            $isFa = ($curType === 'fontawesome');
            $isUrl = ($curType === 'url');
            $isEmoji = (!$isUpload && !$isFa && !$isUrl);
        @endphp
        <div class="adv-picker-tab js-adv-tab {{ !$isUpload ? 'active' : '' }}" data-target-id="{{ $elementId }}" data-tab="pilih-icon">Pilih Ikon</div>
        <div class="adv-picker-tab js-adv-tab {{ $isUpload ? 'active' : '' }}" data-target-id="{{ $elementId }}" data-tab="unggah-gambar">Unggah gambar</div>
    </div>
    <div class="adv-picker-body">
            <div id="advTab_pilih-icon_{{ $elementId }}" style="display: {{ !$isUpload ? 'block' : 'none' }};">
            <div class="adv-subtabs">
                <div class="adv-subtab js-adv-subtab {{ $isEmoji ? 'active' : '' }}" data-target-id="{{ $elementId }}" data-subtab="emoji">Emoji</div>
                <div class="adv-subtab js-adv-subtab {{ $isFa ? 'active' : '' }}" data-target-id="{{ $elementId }}" data-subtab="fontawesome">FontAwesome</div>
                <div class="adv-subtab js-adv-subtab {{ $isUrl ? 'active' : '' }}" data-target-id="{{ $elementId }}" data-subtab="url">URL</div>
            </div>
            <div id="advSubTab_emoji_{{ $elementId }}" style="display: {{ $isEmoji ? 'block' : 'none' }};">
                <div class="emoji-category-nav">
                    <i class="far fa-clock emoji-cat-icon" title="Frequently used"></i>
                    <i class="far fa-smile emoji-cat-icon js-scroll-cat" data-target-id="{{ $elementId }}" data-cat="smileys" title="Smileys & Emotion"></i>
                    <i class="fas fa-user-friends emoji-cat-icon js-scroll-cat" data-target-id="{{ $elementId }}" data-cat="people" title="People & Body"></i>
                    <i class="fas fa-paw emoji-cat-icon js-scroll-cat" data-target-id="{{ $elementId }}" data-cat="animals" title="Animals & Nature"></i>
                    <i class="fas fa-hamburger emoji-cat-icon js-scroll-cat" data-target-id="{{ $elementId }}" data-cat="food" title="Food & Drink"></i>
                    <i class="fas fa-car emoji-cat-icon js-scroll-cat" data-target-id="{{ $elementId }}" data-cat="travel" title="Travel & Places"></i>
                    <i class="fas fa-lightbulb emoji-cat-icon js-scroll-cat" data-target-id="{{ $elementId }}" data-cat="objects" title="Objects"></i>
                </div>
                <div class="profile-form-group" style="margin-bottom: 10px;">
                    <input type="text" id="buttonIconEmoji_{{ $elementId }}" class="profile-input js-emoji-search" data-target-id="{{ $elementId }}" placeholder="🔍 Cari emoji..." value="{{ ($data?->button_icon_type ?? '') === 'emoji' ? ($data?->button_icon_value ?? '') : '' }}" style="padding: 8px 12px; font-size: 13px;" maxlength="30" pattern="[^<>]*" title="Karakter &lt; dan &gt; tidak diperbolehkan untuk mencegah injeksi keamanan.">
                </div>
                                <div class="emoji-scroll-container" id="emojiScroll_{{ $elementId }}">
                    <!-- Akan diisi secara dinamis oleh JavaScript (Lazy Load) -->
                </div>
            </div>
            <div id="advSubTab_fontawesome_{{ $elementId }}" style="display: {{ $isFa ? 'block' : 'none' }};">
                <div class="profile-form-group" style="margin-bottom: 0;">
                    <label class="profile-form-label">Class FontAwesome (contoh: fas fa-home)</label>
                    <input type="text" id="buttonIconFa_{{ $elementId }}" class="profile-input js-update-text-preview" placeholder="fas fa-home" value="{{ ($data?->button_icon_type ?? '') === 'fontawesome' ? ($data?->button_icon_value ?? '') : '' }}" maxlength="50" pattern="[a-zA-Z0-9\-\s]+" title="Hanya huruf, angka, spasi, dan strip yang diperbolehkan untuk nama class ikon">
                </div>
            </div>
            <div id="advSubTab_url_{{ $elementId }}" style="display: {{ $isUrl ? 'block' : 'none' }};">
                <div class="profile-form-group" style="margin-bottom: 0;">
                    <label class="profile-form-label">URL Gambar Ikon</label>
                    <input type="url" id="buttonIconUrl_{{ $elementId }}" class="profile-input js-update-text-preview" placeholder="https://contoh.com/ikon.png" value="{{ ($data?->button_icon_type ?? '') === 'url' ? ($data?->button_icon_value ?? '') : '' }}" maxlength="255" pattern="https?://.*" title="Harus berupa URL yang valid (http:// atau https://)">
                </div>
            </div>
        </div>
        <div id="advTab_unggah-gambar_{{ $elementId }}" style="display: {{ $isUpload ? 'block' : 'none' }};">
            <div class="profile-form-group" style="margin-bottom: 0;">
                <div style="border: 2px dashed #cbd5e1; border-radius: 8px; padding: 25px 15px; text-align: center; background: #f8fafc; cursor: pointer; transition: background 0.2s;" onclick="document.getElementById('buttonIconUpload_{{ $elementId }}').click()" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#f8fafc'">
                    <i class="fas fa-cloud-upload-alt" style="font-size: 32px; color: #94a3b8; margin-bottom: 12px;"></i>
                    <div style="font-size: 14px; font-weight: 600; color: #475569; margin-bottom: 4px;">Klik untuk mengunggah gambar</div>
                    <div style="font-size: 12px; color: #64748b;">PNG, JPG, JPEG (Max 2MB)</div>
                </div>
                <input type="file" id="buttonIconUpload_{{ $elementId }}" class="profile-input js-upload-icon-preview" data-target-id="{{ $elementId }}" accept="image/*" style="display: none;">
                <div id="uploadPreview_{{ $elementId }}" style="display: {{ (($data?->button_icon_type ?? '') === 'upload' && !empty($data?->button_icon_value)) ? 'flex' : 'none' }}; align-items: center; margin-top: 15px; padding: 10px; background: #fff; border-radius: 8px; border: 1px solid #e2e8f0; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                    @if(($data?->button_icon_type ?? '') === 'upload' && !empty($data?->button_icon_value))
                        <img src="{{ asset('storage/' . $data?->button_icon_value) }}" style="width:40px; height:40px; object-fit:contain; border-radius:6px;" alt="Icon Preview">
                        <div style="margin-left: 12px;">
                            <div style="font-size: 13px; font-weight: 600; color: #0f172a;">Gambar Tersimpan</div>
                            <a href="{{ asset('storage/' . $data?->button_icon_value) }}" target="_blank" style="font-size: 11px; color: #3b82f6; text-decoration: none;">Lihat gambar asli</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <input type="hidden" id="buttonIconType_{{ $elementId }}" class="js-update-text-preview" value="{{ $data?->button_icon_type ?? 'none' }}">
    </div>
</div>
<div style="margin-bottom: 15px; display: flex; align-items: center;">
    <label class="profile-form-label" style="margin-bottom: 0; margin-right: 15px;">Gunakan Ikon?</label>
    <div style="display: flex; gap: 15px;">
        <label style="display: flex; align-items: center; gap: 5px; font-size: 14px; cursor: pointer;">
            <input type="radio" name="use_icon_{{ $elementId }}" class="js-toggle-use-icon" data-target-id="{{ $elementId }}" value="yes" {{ ($data?->button_icon_type ?? 'none') !== 'none' ? 'checked' : '' }}> Ya
        </label>
        <label style="display: flex; align-items: center; gap: 5px; font-size: 14px; cursor: pointer;">
            <input type="radio" name="use_icon_{{ $elementId }}" class="js-toggle-use-icon" data-target-id="{{ $elementId }}" value="none" {{ ($data?->button_icon_type ?? 'none') === 'none' ? 'checked' : '' }}> Tidak (Tanpa Ikon)
        </label>
    </div>
</div>
</div>
</div>
                                            <div class="element-action-footer action-footer-spacing">
                                                <button type="button" data-type="Text" data-target-id="{{ $elementId }}" class="btn-delete-element js-remove-element">
                                                    <i class="fas fa-trash-alt"></i> Hapus Elemen
                                                </button>
                                                <button type="button" data-type="Text" data-target-id="{{ $elementId }}" class="btn-save-element js-save-element" id="btnSaveText_{{ $elementId }}">
                                                    Simpan
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>