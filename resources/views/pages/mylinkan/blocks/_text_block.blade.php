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
                                                    <input type="color" class="toolbar-color-picker js-exec-cmd-value" data-target-id="{{ $elementId }}" data-cmd="foreColor" title="Text Color" value="#000000">
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
                                                <div id="customSizeWrapper_{{ $elementId }}" class="custom-size-wrapper" class="d-none">
                                                    <input type="number" id="customSizeInput_{{ $elementId }}" class="toolbar-input js-apply-custom-size-input" placeholder="Ukuran (px)" min="1" max="99" data-target-id="{{ $elementId }}">
                                                    <button type="button" class="toolbar-btn-text js-apply-custom-size" data-target-id="{{ $elementId }}">Terapkan</button>
                                                </div>
                                                <div id="editorContent_{{ $elementId }}" class="text-editor-area text-editor-area-styled js-update-text-preview" contenteditable="true" data-target-id="{{ $elementId }}">{!! $textEl->content ?? 'Teks Anda di sini...' !!}</div>
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
