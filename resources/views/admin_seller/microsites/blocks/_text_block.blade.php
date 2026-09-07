                                @php 
                                    $elementId = 'textBlock_' . $textEl->id; 
                                    $isActive = $textEl->is_active ?? true;
                                @endphp
                                <div id="{{ $elementId }}" class="draggable-element-block {{ $isActive ? '' : 'block-inactive' }}" data-element-type="text" data-db-id="{{ $textEl->id }}">
                                    <div class="block-item-card js-toggle-edit-form" data-type="Text" data-target-id="{{ $elementId }}">
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
                                                <span class="visibility-status-text {{ $isActive ? 'status-active' : 'status-inactive' }}" id="statusText_{{ $elementId }}">{{ $isActive ? 'Aktif' : 'Nonaktif' }}</span>
                                                <label class="toggle-switch">
                                                    <input class="js-toggle-visibility" type="checkbox" id="visibilitySwitch_{{ $elementId }}" data-target-id="{{ $elementId }}" {{ $isActive ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                            <button type="button" class="btn-element-action btn-delete-icon js-remove-element" data-type="Text" data-target-id="{{ $elementId }}" title="Hapus Elemen">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                            <button type="button" data-type="Text" data-target-id="{{ $elementId }}" class="btn-edit-block js-toggle-edit-form">
                                                <i class="fas fa-pen" class="btn-edit-icon"></i> <span id="btnText_{{ $elementId }}">Edit</span>
                                            </button>
                                        </div>
                                    </div>

                                    <div id="formBody_{{ $elementId }}" class="edit-form-body" style="max-height: 0; opacity: 0; margin-top: 0;">
                                        <div class="edit-form-content">
                                            <div class="profile-form-header dynamic-setting-header">
                                                <i class="fas fa-font dynamic-setting-header-icon-blue"></i> Pengaturan Teks
                                            </div>
                                            <div class="profile-form-group text-form-group-spacing">
                                                <label class="profile-form-label dynamic-form-label-bold">Isi Teks Konten</label>
                                                <div class="text-editor-container text-editor-container-styled">
                                                    <div class="text-editor-toolbar text-editor-toolbar-styled">
                                                    <button type="button" class="toolbar-btn js-exec-cmd" data-target-id="{{ $elementId }}" data-cmd="bold" title="Bold"><i class="fas fa-bold"></i></button>
                                                    <button type="button" class="toolbar-btn js-exec-cmd" data-target-id="{{ $elementId }}" data-cmd="italic" title="Italic"><i class="fas fa-italic"></i></button>
                                                    <button type="button" class="toolbar-btn js-exec-cmd" data-target-id="{{ $elementId }}" data-cmd="underline" title="Underline"><i class="fas fa-underline"></i></button>
                                                    <button type="button" class="toolbar-btn js-exec-cmd" data-target-id="{{ $elementId }}" data-cmd="strikeThrough" title="Strikethrough"><i class="fas fa-strikethrough"></i></button>
                                                    <span class="toolbar-divider"></span>
                                                    <button type="button" class="toolbar-btn js-exec-cmd" data-target-id="{{ $elementId }}" data-cmd="justifyLeft" title="Align Left"><i class="fas fa-align-left"></i></button>
                                                    <button type="button" class="toolbar-btn js-exec-cmd" data-target-id="{{ $elementId }}" data-cmd="justifyCenter" title="Align Center"><i class="fas fa-align-center"></i></button>
                                                    <button type="button" class="toolbar-btn js-exec-cmd" data-target-id="{{ $elementId }}" data-cmd="justifyRight" title="Align Right"><i class="fas fa-align-right"></i></button>
                                                    <span class="toolbar-divider"></span>
                                                    <button type="button" class="toolbar-btn js-exec-cmd" data-target-id="{{ $elementId }}" data-cmd="insertUnorderedList" title="Bullet List"><i class="fas fa-list-ul"></i></button>
                                                    <button type="button" class="toolbar-btn js-exec-cmd" data-target-id="{{ $elementId }}" data-cmd="insertOrderedList" title="Numbered List"><i class="fas fa-list-ol"></i></button>
                                                    <span class="toolbar-divider"></span>
                                                    <label class="toolbar-color-picker" title="Pilih Warna Teks" style="cursor: pointer; display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 4px; color: #4b5563; transition: all 0.2s;">
                                                        <i class="fas fa-eye-dropper"></i>
                                                        <input type="color" class="hidden-color-input js-exec-cmd-value" data-target-id="{{ $elementId }}" data-cmd="foreColor" title="Text Color" value="#000000" style="opacity: 0; position: absolute; width: 0; height: 0;">
                                                    </label>
                                                    <span class="toolbar-divider"></span>
                                                    <div class="toolbar-dropdown">
                                                        <select data-target-id="{{ $elementId }}" class="toolbar-select js-change-text-size" id="textSizeSelect_{{ $elementId }}">
                                                            <option value="12px">Kecil (12px)</option>
                                                            <option value="16px" selected>Normal (16px)</option>
                                                            <option value="24px">Besar (24px)</option>
                                                            <option value="custom">Custom...</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div id="customSizeWrapper_{{ $elementId }}" class="custom-size-wrapper" style="display: none; align-items: center; gap: 8px; margin-top: 8px; background: #f8fafc; padding: 8px; border-radius: 6px; border: 1px solid #e2e8f0;">
                                                    <input type="number" id="customSizeInput_{{ $elementId }}" class="toolbar-input js-apply-custom-size-input" placeholder="Ukuran (px)" min="1" max="99" data-target-id="{{ $elementId }}" style="width: 80px; padding: 4px 8px;">
                                                    <button type="button" class="toolbar-btn-text js-apply-custom-size" data-target-id="{{ $elementId }}">Terapkan</button>
                                                </div>
                                                <div id="editorContent_{{ $elementId }}" class="text-editor-area text-editor-area-styled js-update-text-preview" contenteditable="true" data-target-id="{{ $elementId }}">{!! $textEl->content ?? 'Teks Anda di sini...' !!}</div>
                                                <div class="text-editor-counter-container" style="display: flex; justify-content: space-between; align-items: center; margin-top: 5px;">
                                                    <span id="charError_{{ $elementId }}" class="char-error" style="color: #ef4444; font-size: 12px; display: none; font-weight: 500;">Maksimal 250 kata! Teks gagal disimpan.</span>
                                                    @php
                                                        $rawText = strip_tags(html_entity_decode($textEl->content ?? 'Teks Anda di sini...'));
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
                                                        <input type="checkbox" id="hasButton_{{ $elementId }}" class="js-toggle-text-button" data-target-id="{{ $elementId }}" {{ ($textEl->has_button ?? false) ? 'checked' : '' }}>
                                                        <span class="toggle-slider"></span>
                                                    </label>
                                                </div>
                                                <div id="buttonFields_{{ $elementId }}" style="display: {{ ($textEl->has_button ?? false) ? 'block' : 'none' }}; border: 1px solid #e0e0e0; padding: 15px; border-radius: 8px; background: #fafafa;">
                                                    <div style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 15px;">
                                                        <div class="dp-form-row-box" style="margin-bottom: 0; padding: 12px 15px;">
                                                            <label class="dp-row-label">Teks Tombol</label>
                                                            <input type="text" id="buttonText_{{ $elementId }}" class="dp-row-input" placeholder="Contoh: Baca Selengkapnya" value="{{ $textEl->button_text ?? '' }}" maxlength="50" pattern="[^<>]*" title="Karakter &lt; dan &gt; tidak diperbolehkan untuk mencegah injeksi keamanan.">
                                                        </div>
                                                        <div class="dp-form-row-box" style="margin-bottom: 0; padding: 12px 15px;">
                                                            <label class="dp-row-label">Warna Tombol</label>
                                                            <div style="display: flex; align-items: center; width: 100%;">
                                                                <input type="color" id="buttonColor_{{ $elementId }}" class="dp-row-input" style="padding: 0; height: 32px; width: 48px; cursor: pointer; border-radius: 6px; border: 1px solid #e5e7eb;" value="{{ $textEl->button_color ?? '#f8f9fa' }}">
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
        @php
            $curType = $textEl->button_icon_type ?? 'none';
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
                    <i class="far fa-smile emoji-cat-icon js-scroll-cat" data-target-id="{{ $elementId }}" data-cat="4c2fe5cabef81537c1921551e7e7b679" title="Smileys & Emotion"></i>
                    <i class="fas fa-user-friends emoji-cat-icon js-scroll-cat" data-target-id="{{ $elementId }}" data-cat="d1310ead50484fb1c2564b043ea17413" title="People & Body"></i>
                    <i class="fas fa-paw emoji-cat-icon js-scroll-cat" data-target-id="{{ $elementId }}" data-cat="cb6bd7088e49721cf521ce24d552586d" title="Animals & Nature"></i>
                    <i class="fas fa-hamburger emoji-cat-icon js-scroll-cat" data-target-id="{{ $elementId }}" data-cat="614adc8c0f0f72ef1192968ea945c510" title="Food & Drink"></i>
                    <i class="fas fa-car emoji-cat-icon js-scroll-cat" data-target-id="{{ $elementId }}" data-cat="b6ad445740b20f7373d22a1cb6c4e9af" title="Travel & Places"></i>
                    <i class="fas fa-lightbulb emoji-cat-icon js-scroll-cat" data-target-id="{{ $elementId }}" data-cat="c8308b1eba7ba926a61b8fd802194386" title="Objects"></i>
                </div>
                <div class="profile-form-group" style="margin-bottom: 10px;">
                    <input type="text" id="buttonIconEmoji_{{ $elementId }}" class="profile-input js-emoji-search" data-target-id="{{ $elementId }}" placeholder="🔍 Cari emoji..." value="{{ ($textEl->button_icon_type ?? '') === 'emoji' ? ($textEl->button_icon_value ?? '') : '' }}" style="padding: 8px 12px; font-size: 13px;" maxlength="30" pattern="[^<>]*" title="Karakter &lt; dan &gt; tidak diperbolehkan untuk mencegah injeksi keamanan.">
                </div>
                <div class="emoji-scroll-container" id="emojiScroll_{{ $elementId }}">
                    <div class="emoji-section-title" id="cat_4c2fe5cabef81537c1921551e7e7b679_{{ $elementId }}">Smileys & Emotion</div>
                    <div class="emoji-grid">
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😀">😀</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😃">😃</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😄">😄</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😁">😁</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😆">😆</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😅">😅</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😂">😂</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🤣">🤣</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😊">😊</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😇">😇</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🙂">🙂</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🙃">🙃</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😉">😉</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😌">😌</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😍">😍</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🥰">🥰</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😘">😘</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😗">😗</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😙">😙</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😚">😚</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😋">😋</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😛">😛</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😝">😝</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😜">😜</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🤪">🤪</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🤨">🤨</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🧐">🧐</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🤓">🤓</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😎">😎</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🤩">🤩</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🥳">🥳</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😏">😏</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😒">😒</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😞">😞</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😔">😔</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😟">😟</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😕">😕</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🙁">🙁</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="☹️">☹️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😣">😣</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😖">😖</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😫">😫</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😩">😩</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🥺">🥺</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😢">😢</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😭">😭</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😤">😤</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😠">😠</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😡">😡</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🤬">🤬</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🤯">🤯</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😳">😳</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🥵">🥵</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🥶">🥶</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😱">😱</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😨">😨</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😰">😰</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😥">😥</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😓">😓</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🤗">🤗</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🤔">🤔</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🤭">🤭</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🤫">🤫</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🤥">🤥</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😶">😶</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😐">😐</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😑">😑</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😬">😬</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🙄">🙄</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😯">😯</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😦">😦</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😧">😧</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😮">😮</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😲">😲</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🥱">🥱</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😴">😴</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🤤">🤤</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😪">😪</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😵">😵</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🤐">🤐</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🥴">🥴</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🤢">🤢</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🤮">🤮</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🤧">🤧</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😷">😷</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🤒">🤒</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🤕">🤕</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🤑">🤑</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🤠">🤠</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="😈">😈</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="👿">👿</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="👹">👹</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="👺">👺</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🤡">🤡</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="💩">💩</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="👻">👻</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="💀">💀</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="☠️">☠️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="👽">👽</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="👾">👾</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🤖">🤖</div>
                    </div>
                    <div class="emoji-section-title" id="cat_d1310ead50484fb1c2564b043ea17413_{{ $elementId }}">People & Body</div>
                    <div class="emoji-grid">
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="👋">👋</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🤚">🤚</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🖐️">🖐️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="✋">✋</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🖖">🖖</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="👌">👌</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🤌">🤌</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🤏">🤏</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="✌️">✌️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🤞">🤞</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🤟">🤟</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🤘">🤘</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🤙">🤙</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="👈">👈</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="👉">👉</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="👆">👆</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🖕">🖕</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="👇">👇</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="☝️">☝️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="👍">👍</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="👎">👎</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="✊">✊</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="👊">👊</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🤛">🤛</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🤜">🤜</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="👏">👏</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🙌">🙌</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="👐">👐</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🤲">🤲</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🤝">🤝</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🙏">🙏</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="✍️">✍️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="💅">💅</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🤳">🤳</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="💪">💪</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦾">🦾</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦿">🦿</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦵">🦵</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦶">🦶</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="👂">👂</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦻">🦻</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="👃">👃</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🧠">🧠</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🫀">🫀</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🫁">🫁</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦷">🦷</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="骨">骨</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="👀">👀</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="👁️">👁️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="👅">👅</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="👄">👄</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="💋">💋</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🩸">🩸</div>
                    </div>
                    <div class="emoji-section-title" id="cat_cb6bd7088e49721cf521ce24d552586d_{{ $elementId }}">Animals & Nature</div>
                    <div class="emoji-grid">
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐶">🐶</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐱">🐱</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐭">🐭</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐹">🐹</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐰">🐰</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦊">🦊</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐻">🐻</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐼">🐼</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐻‍❄️">🐻‍❄️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐨">🐨</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐯">🐯</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦁">🦁</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐮">🐮</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐷">🐷</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐽">🐽</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐸">🐸</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐵">🐵</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🙈">🙈</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🙉">🙉</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🙊">🙊</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐒">🐒</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐔">🐔</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐧">🐧</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐦">🐦</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐤">🐤</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐣">🐣</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐥">🐥</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦆">🦆</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦅">🦅</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦉">🦉</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦇">🦇</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐺">🐺</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐗">🐗</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐴">🐴</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦄">🦄</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐝">🐝</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🪱">🪱</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐛">🐛</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦋">🦋</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐌">🐌</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐞">🐞</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐜">🐜</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🪰">🪰</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🪲">🪲</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🪳">🪳</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦟">🦟</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦗">🦗</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🕷️">🕷️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🕸️">🕸️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦂">🦂</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐢">🐢</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐍">🐍</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦎">🦎</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦖">🦖</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦕">🦕</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐙">🐙</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦑">🦑</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦐">🦐</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦞">🦞</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦀">🦀</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐡">🐡</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐠">🐠</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐟">🐟</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐬">🐬</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐳">🐳</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐋">🐋</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦈">🦈</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦭">🦭</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐊">🐊</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐅">🐅</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐆">🐆</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦓">🦓</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦍">🦍</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦧">🦧</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦣">🦣</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐘">🐘</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦛">🦛</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦏">🦏</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐪">🐪</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐫">🐫</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦒">🦒</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦘">🦘</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦬">🦬</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐃">🐃</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐂">🐂</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐄">🐄</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐎">🐎</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐖">🐖</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐏">🐏</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐑">🐑</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦙">🦙</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐐">🐐</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦌">🦌</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐕">🐕</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐩">🐩</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦮">🦮</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐕‍🦺">🐕‍🦺</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐈">🐈</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐈‍⬛">🐈‍⬛</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🪶">🪶</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐓">🐓</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦃">🦃</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦤">🦤</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦚">🦚</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦜">🦜</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦢">🦢</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦩">🦩</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🕊️">🕊️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐇">🐇</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦝">🦝</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦨">🦨</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦡">🦡</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦫">🦫</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦦">🦦</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦥">🦥</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐁">🐁</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐀">🐀</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐿️">🐿️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦔">🦔</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐾">🐾</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐉">🐉</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐲">🐲</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🌵">🌵</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🎄">🎄</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🌲">🌲</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🌳">🌳</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🌴">🌴</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🪵">🪵</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🌱">🌱</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🌿">🌿</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="☘️">☘️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍀">🍀</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🎍">🎍</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🪴">🪴</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🎋">🎋</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍃">🍃</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍂">🍂</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍁">🍁</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍄">🍄</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🐚">🐚</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🪨">🪨</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🌾">🌾</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="💐">💐</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🌷">🌷</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🌹">🌹</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🥀">🥀</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🌺">🌺</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🌸">🌸</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🌼">🌼</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🌻">🌻</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🌞">🌞</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🌝">🌝</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🌛">🌛</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🌜">🌜</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🌚">🌚</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🌕">🌕</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🌖">🌖</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🌗">🌗</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🌘">🌘</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🌑">🌑</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🌒">🌒</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🌓">🌓</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🌔">🌔</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🌙">🌙</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🌎">🌎</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🌍">🌍</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🌏">🌏</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🪐">🪐</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="💫">💫</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="⭐️">⭐️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🌟">🌟</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="✨">✨</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="⚡️">⚡️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="☄️">☄️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="💥">💥</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🔥">🔥</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🌪️">🌪️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🌈">🌈</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="☀️">☀️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🌤️">🌤️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="⛅️">⛅️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🌥️">🌥️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="☁️">☁️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🌦️">🌦️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🌧️">🌧️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="⛈️">⛈️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🌩️">🌩️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🌨️">🌨️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="❄️">❄️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="☃️">☃️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="⛄️">⛄️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🌬️">🌬️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="💨">💨</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="💧">💧</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="💦">💦</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="☔️">☔️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="☂️">☂️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🌊">🌊</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🌫️">🌫️</div>
                    </div>
                    <div class="emoji-section-title" id="cat_614adc8c0f0f72ef1192968ea945c510_{{ $elementId }}">Food & Drink</div>
                    <div class="emoji-grid">
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍏">🍏</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍎">🍎</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍐">🍐</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍊">🍊</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍋">🍋</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍌">🍌</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍉">🍉</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍇">🍇</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍓">🍓</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🫐">🫐</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍈">🍈</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍒">🍒</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍑">🍑</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🥭">🥭</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍍">🍍</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🥥">🥥</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🥝">🥝</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍅">🍅</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍆">🍆</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🥑">🥑</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🥦">🥦</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🥬">🥬</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🥒">🥒</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🌶️">🌶️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🫑">🫑</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🌽">🌽</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🥕">🥕</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🫒">🫒</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🧄">🧄</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🧅">🧅</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🥔">🥔</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍠">🍠</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🥐">🥐</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🥯">🥯</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍞">🍞</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🥖">🥖</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🥨">🥨</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🧀">🧀</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🥚">🥚</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍳">🍳</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🧈">🧈</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🥞">🥞</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🧇">🧇</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🥓">🥓</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🥩">🥩</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍗">🍗</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍖">🍖</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦴">🦴</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🌭">🌭</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍔">🍔</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍟">🍟</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍕">🍕</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🫓">🫓</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🥪">🥪</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🥙">🥙</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🧆">🧆</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🌮">🌮</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🌯">🌯</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🫔">🫔</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🥗">🥗</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🥘">🥘</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🫕">🫕</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🥫">🥫</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍝">🍝</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍜">🍜</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍲">🍲</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍛">🍛</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍣">🍣</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍱">🍱</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🥟">🥟</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦪">🦪</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍤">🍤</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍙">🍙</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍚">🍚</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍘">🍘</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍥">🍥</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🥠">🥠</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🥮">🥮</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍢">🍢</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍡">🍡</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍧">🍧</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍨">🍨</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍦">🍦</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🥧">🥧</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🧁">🧁</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍰">🍰</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🎂">🎂</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍮">🍮</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍭">🍭</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍬">🍬</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍫">🍫</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍿">🍿</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍩">🍩</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍪">🍪</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🌰">🌰</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🥜">🥜</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍯">🍯</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🥛">🥛</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍼">🍼</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🫖">🫖</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="☕️">☕️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍵">🍵</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🧃">🧃</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🥤">🥤</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🧋">🧋</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍶">🍶</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍺">🍺</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍻">🍻</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🥂">🥂</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍷">🍷</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🥃">🥃</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍸">🍸</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍹">🍹</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🧉">🧉</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍾">🍾</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🧊">🧊</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🥄">🥄</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍴">🍴</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🍽️">🍽️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🥣">🥣</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🥡">🥡</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🥢">🥢</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🧂">🧂</div>
                    </div>
                    <div class="emoji-section-title" id="cat_b6ad445740b20f7373d22a1cb6c4e9af_{{ $elementId }}">Travel & Places</div>
                    <div class="emoji-grid">
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🚗">🚗</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🚕">🚕</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🚙">🚙</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🚌">🚌</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🚎">🚎</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🏎️">🏎️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🚓">🚓</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🚑">🚑</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🚒">🚒</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🚐">🚐</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🛻">🛻</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🚚">🚚</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🚛">🚛</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🚜">🚜</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦯">🦯</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦽">🦽</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦼">🦼</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🛴">🛴</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🚲">🚲</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🛵">🛵</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🏍️">🏍️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🛺">🛺</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🚨">🚨</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🚔">🚔</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🚍">🚍</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🚘">🚘</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🚖">🚖</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🚡">🚡</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🚠">🚠</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🚟">🚟</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🚃">🚃</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🚋">🚋</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🚞">🚞</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🚝">🚝</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🚄">🚄</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🚅">🚅</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🚈">🚈</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🚂">🚂</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🚆">🚆</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🚇">🚇</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🚊">🚊</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🚉">🚉</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="✈️">✈️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🛫">🛫</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🛬">🛬</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🛩️">🛩️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="💺">💺</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🛰️">🛰️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🚀">🚀</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🛸">🛸</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🚁">🚁</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🛶">🛶</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="⛵️">⛵️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🚤">🚤</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🛥️">🛥️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🛳️">🛳️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="⛴️">⛴️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🚢">🚢</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="⚓️">⚓️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🪝">🪝</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="⛽️">⛽️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🚧">🚧</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🚦">🚦</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🚥">🚥</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🚏">🚏</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🗺️">🗺️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🗿">🗿</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🗽">🗽</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🗼">🗼</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🏰">🏰</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🏯">🏯</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🏟️">🏟️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🎡">🎡</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🎢">🎢</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🎠">🎠</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="⛲️">⛲️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="⛱️">⛱️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🏖️">🏖️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🏝️">🏝️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🏜️">🏜️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🌋">🌋</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="⛰️">⛰️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🏔️">🏔️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🗻">🗻</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🏕️">🏕️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="⛺️">⛺️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🛖">🛖</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🏠">🏠</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🏡">🏡</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🏘️">🏘️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🏚️">🏚️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🏗️">🏗️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🏭">🏭</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🏢">🏢</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🏬">🏬</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🏣">🏣</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🏤">🏤</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🏥">🏥</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🏦">🏦</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🏨">🏨</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🏪">🏪</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🏫">🏫</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🏩">🏩</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="💒">💒</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🏛️">🏛️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="⛪️">⛪️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🕌">🕌</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🛕">🛕</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🕍">🕍</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="⛩️">⛩️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🕋">🕋</div>
                    </div>
                    <div class="emoji-section-title" id="cat_c8308b1eba7ba926a61b8fd802194386_{{ $elementId }}">Objects</div>
                    <div class="emoji-grid">
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="⌚️">⌚️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="📱">📱</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="📲">📲</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="💻">💻</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="⌨️">⌨️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🖥️">🖥️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🖨️">🖨️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🖱️">🖱️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🖲️">🖲️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🕹️">🕹️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🗜️">🗜️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="💽">💽</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="💾">💾</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="💿">💿</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="📀">📀</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="📼">📼</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="📷">📷</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="📸">📸</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="📹">📹</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🎥">🎥</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="📽️">📽️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🎞️">🎞️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="📞">📞</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="☎️">☎️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="📟">📟</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="📠">📠</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="📺">📺</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="📻">📻</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🎙️">🎙️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🎚️">🎚️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🎛️">🎛️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🧭">🧭</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="⏱️">⏱️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="⏲️">⏲️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="⏰">⏰</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🕰️">🕰️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="⌛️">⌛️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="⏳">⏳</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="📡">📡</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🔋">🔋</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🔌">🔌</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="💡">💡</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🔦">🔦</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🕯️">🕯️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🪔">🪔</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🧯">🧯</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🛢️">🛢️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="💸">💸</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="💵">💵</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="💴">💴</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="💶">💶</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="💷">💷</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🪙">🪙</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="💰">💰</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="💳">💳</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="💎">💎</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="⚖️">⚖️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🪜">🪜</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🧰">🧰</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🪛">🪛</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🔧">🔧</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🔨">🔨</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="⚒️">⚒️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🛠️">🛠️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="⛏️">⛏️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🪚">🪚</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🔩">🔩</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="⚙️">⚙️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🪤">🪤</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🧱">🧱</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="⛓️">⛓️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🧲">🧲</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🔫">🔫</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="💣">💣</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🧨">🧨</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🪓">🪓</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🔪">🔪</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🗡️">🗡️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="⚔️">⚔️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🛡️">🛡️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🚬">🚬</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="⚰️">⚰️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🪦">🪦</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="⚱️">⚱️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🏺">🏺</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🔮">🔮</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="📿">📿</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🧿">🧿</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="💈">💈</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="⚗️">⚗️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🔭">🔭</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🔬">🔬</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🕳️">🕳️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🩹">🩹</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🩺">🩺</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="💊">💊</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="💉">💉</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🩸">🩸</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🧬">🧬</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🦠">🦠</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🧫">🧫</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🧪">🧪</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🌡️">🌡️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🧹">🧹</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🪠">🪠</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🧺">🧺</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🧻">🧻</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🚽">🚽</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🚰">🚰</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🚿">🚿</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🛁">🛁</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🛀">🛀</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🧼">🧼</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🪥">🪥</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🪒">🪒</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🧽">🧽</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🪣">🪣</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🧴">🧴</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🛎️">🛎️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🔑">🔑</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🗝️">🗝️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🚪">🚪</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🪑">🪑</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🛋️">🛋️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🛏️">🛏️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🛌">🛌</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🧸">🧸</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🪆">🪆</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🖼️">🖼️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🪞">🪞</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🪟">🪟</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🛍️">🛍️</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🛒">🛒</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🎁">🎁</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🎈">🎈</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🎏">🎏</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🎀">🎀</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🪄">🪄</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🪅">🪅</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🎊">🎊</div>
                        <div class="emoji-item js-pick-emoji" data-target-id="{{ $elementId }}" data-emoji="🎉">🎉</div>
                    </div>
                </div>
            </div>
            <div id="advSubTab_fontawesome_{{ $elementId }}" style="display: {{ $isFa ? 'block' : 'none' }};">
                <div class="profile-form-group" style="margin-bottom: 0;">
                    <label class="profile-form-label">Class FontAwesome (contoh: fas fa-home)</label>
                    <input type="text" id="buttonIconFa_{{ $elementId }}" class="profile-input js-update-text-preview" placeholder="fas fa-home" value="{{ ($textEl->button_icon_type ?? '') === 'fontawesome' ? ($textEl->button_icon_value ?? '') : '' }}" maxlength="50" pattern="[a-zA-Z0-9\-\s]+" title="Hanya huruf, angka, spasi, dan strip yang diperbolehkan untuk nama class ikon">
                </div>
            </div>
            <div id="advSubTab_url_{{ $elementId }}" style="display: {{ $isUrl ? 'block' : 'none' }};">
                <div class="profile-form-group" style="margin-bottom: 0;">
                    <label class="profile-form-label">URL Gambar Ikon</label>
                    <input type="url" id="buttonIconUrl_{{ $elementId }}" class="profile-input js-update-text-preview" placeholder="https://contoh.com/ikon.png" value="{{ ($textEl->button_icon_type ?? '') === 'url' ? ($textEl->button_icon_value ?? '') : '' }}" maxlength="255" pattern="https?://.*" title="Harus berupa URL yang valid (http:// atau https://)">
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
                <div id="uploadPreview_{{ $elementId }}" style="display: {{ (($textEl->button_icon_type ?? '') === 'upload' && !empty($textEl->button_icon_value)) ? 'flex' : 'none' }}; align-items: center; margin-top: 15px; padding: 10px; background: #fff; border-radius: 8px; border: 1px solid #e2e8f0; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                    @if(($textEl->button_icon_type ?? '') === 'upload' && !empty($textEl->button_icon_value))
                        <img src="{{ asset('storage/' . $textEl->button_icon_value) }}" style="width:40px; height:40px; object-fit:contain; border-radius:6px;">
                        <div style="margin-left: 12px;">
                            <div style="font-size: 13px; font-weight: 600; color: #0f172a;">Gambar Tersimpan</div>
                            <a href="{{ asset('storage/' . $textEl->button_icon_value) }}" target="_blank" style="font-size: 11px; color: #3b82f6; text-decoration: none;">Lihat gambar asli</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <input type="hidden" id="buttonIconType_{{ $elementId }}" class="js-update-text-preview" value="{{ $textEl->button_icon_type ?? 'none' }}">
    </div>
</div>
<div style="margin-bottom: 15px; display: flex; align-items: center;">
    <label class="profile-form-label" style="margin-bottom: 0; margin-right: 15px;">Gunakan Ikon?</label>
    <div style="display: flex; gap: 15px;">
        <label style="display: flex; align-items: center; gap: 5px; font-size: 14px; cursor: pointer;">
            <input type="radio" name="use_icon_{{ $elementId }}" class="js-toggle-use-icon" data-target-id="{{ $elementId }}" value="yes" {{ ($textEl->button_icon_type ?? 'none') !== 'none' ? 'checked' : '' }}> Ya
        </label>
        <label style="display: flex; align-items: center; gap: 5px; font-size: 14px; cursor: pointer;">
            <input type="radio" name="use_icon_{{ $elementId }}" class="js-toggle-use-icon" data-target-id="{{ $elementId }}" value="none" {{ ($textEl->button_icon_type ?? 'none') === 'none' ? 'checked' : '' }}> Tidak (Tanpa Ikon)
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
