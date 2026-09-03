                                @php 
                                    $elementId = 'dividerBlock_' . $dividerEl->id; 
                                    $isActive = $dividerEl->is_active ?? true;
                                @endphp
                                <div id="{{ $elementId }}" class="draggable-element-block {{ $isActive ? '' : 'block-inactive' }}" data-element-type="divider" data-db-id="{{ $dividerEl->id }}">
                                    <div class="block-item-card js-toggle-edit-form" data-type="Divider" data-target-id="{{ $elementId }}">
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
                                                <span class="visibility-status-text {{ $isActive ? 'status-active' : 'status-inactive' }}" id="statusText_{{ $elementId }}">{{ $isActive ? 'Aktif' : 'Nonaktif' }}</span>
                                                <label class="toggle-switch">
                                                    <input class="js-toggle-visibility" type="checkbox" id="visibilitySwitch_{{ $elementId }}" data-target-id="{{ $elementId }}" {{ $isActive ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                            <button type="button" class="btn-element-action btn-delete-icon js-remove-element" data-type="Divider" data-target-id="{{ $elementId }}" title="Hapus Elemen">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                            <button type="button" data-type="Divider" data-target-id="{{ $elementId }}" class="btn-edit-block js-toggle-edit-form">
                                                <i class="fas fa-pen" class="btn-edit-icon"></i> <span id="btnText_{{ $elementId }}">Edit</span>
                                            </button>
                                        </div>
                                    </div>

                                    <div id="formBody_{{ $elementId }}" class="edit-form-body" style="max-height: 0; opacity: 0; margin-top: 0;">
                                        <div class="edit-form-content">
                                            <div class="edit-form-group">
                                                <label class="profile-form-label">Jenis Pembatas</label>
                                                <div class="segment-control-wrapper">
                                                    <label class="segment-control-label">
                                                        <input type="radio" name="dividerTypeGroup_{{ $elementId }}" value="line" class="hidden-radio js-change-divider-type" data-target-id="{{ $elementId }}" {{ $dividerEl->type === 'line' ? 'checked' : '' }}>
                                                        <div class="segment-btn {{ $dividerEl->type === 'line' ? 'active' : '' }}">
                                                            <i class="fas fa-minus"></i> Garis
                                                        </div>
                                                    </label>
                                                    <label class="segment-control-label">
                                                        <input type="radio" name="dividerTypeGroup_{{ $elementId }}" value="space" class="hidden-radio js-change-divider-type" data-target-id="{{ $elementId }}" {{ $dividerEl->type === 'space' ? 'checked' : '' }}>
                                                        <div class="segment-btn {{ $dividerEl->type === 'space' ? 'active' : '' }}">
                                                            <i class="fas fa-arrows-alt-v"></i> Spasi Kosong
                                                        </div>
                                                    </label>
                                                    <input type="hidden" id="dividerType_{{ $elementId }}" value="{{ $dividerEl->type }}">
                                                </div>
                                            </div>

                                            <div class="slider-control-container">
                                                <label class="profile-form-label slider-control-header">
                                                    <span class="slider-control-title">Ukuran Jarak</span>
                                                    <div class="slider-value-badge" id="dividerSizeValue_{{ $elementId }}">{{ $dividerEl->size }}px</div>
                                                </label>
                                                <div class="slider-input-wrapper">
                                                    <button type="button" class="btn-slider-adjust js-adjust-divider-size" data-target-id="{{ $elementId }}" data-step="-5">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                    <input type="range" id="dividerSize_{{ $elementId }}" class="modern-range js-update-divider-preview" min="10" max="100" step="5" value="{{ $dividerEl->size }}"  data-target-id="{{ $elementId }}">
                                                    <button type="button" class="btn-slider-adjust js-adjust-divider-size" data-target-id="{{ $elementId }}" data-step="5">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="element-action-footer">
                                                <button type="button" data-type="Divider" data-target-id="{{ $elementId }}" class="btn-delete-element js-remove-element">
                                                    <i class="fas fa-trash-alt"></i> Hapus Elemen
                                                </button>
                                                <button type="button" data-type="Divider" data-target-id="{{ $elementId }}" class="btn-save-element js-save-element">
                                                    Simpan
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
