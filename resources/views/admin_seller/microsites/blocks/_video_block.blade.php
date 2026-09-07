                                @php 
                                    $elementId = 'videoBlock_' . $videoEl->id; 
                                    $isActive = $videoEl->is_active ?? true;
                                    $isAutoplay = $videoEl->is_autoplay ?? false;
                                @endphp
                                <div id="{{ $elementId }}" class="draggable-element-block {{ $isActive ? '' : 'block-inactive' }}" data-element-type="video" data-db-id="{{ $videoEl->id }}">
                                    <div class="block-item-card js-toggle-edit-form" data-type="Video" data-target-id="{{ $elementId }}">
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
                                                <span class="visibility-status-text {{ $isActive ? 'status-active' : 'status-inactive' }}" id="statusText_{{ $elementId }}">{{ $isActive ? 'Aktif' : 'Nonaktif' }}</span>
                                                <label class="toggle-switch">
                                                    <input class="js-toggle-visibility" type="checkbox" id="visibilitySwitch_{{ $elementId }}" data-target-id="{{ $elementId }}" {{ $isActive ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                            <button type="button" class="btn-element-action btn-delete-icon js-remove-element" data-type="Video" data-target-id="{{ $elementId }}" title="Hapus Elemen">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                            <button type="button" data-type="Video" data-target-id="{{ $elementId }}" class="btn-edit-block js-toggle-edit-form">
                                                <i class="fas fa-pen" class="btn-edit-icon"></i> <span id="btnText_{{ $elementId }}">Edit</span>
                                            </button>
                                        </div>
                                    </div>

                                    <div id="formBody_{{ $elementId }}" class="edit-form-body" style="max-height: 0; opacity: 0; margin-top: 0;">
                                        <div class="edit-form-content">
                                            <div class="profile-form-header dynamic-setting-header">
                                                <i class="fab fa-youtube dynamic-setting-header-icon-red"></i> Pengaturan Video
                                            </div>
                                            
                                            <div class="profile-form-group">
                                                <label class="profile-form-label dynamic-form-label-bold">Tautan Video YouTube</label>
                                                <div class="video-input-wrapper">
                                                    <div class="video-input-icon-left">
                                                        <i class="fab fa-youtube"></i>
                                                    </div>
                                                    <input type="text" id="videoUrl_{{ $elementId }}" class="form-control-input video-input-styled js-update-video-preview" value="{{ $videoEl->video_url }}" placeholder="Tempel URL YouTube di sini..."  data-target-id="{{ $elementId }}">
                                                </div>
                                            </div>
                                            
                                            <div class="autoplay-setting-card">
                                                <div class="autoplay-setting-left">
                                                    <div class="autoplay-icon-wrapper">
                                                        <i class="fas fa-play autoplay-icon-small"></i>
                                                    </div>
                                                    <div>
                                                        <label class="profile-form-label autoplay-label-title">Putar Otomatis (Autoplay)</label>
                                                        <span class="autoplay-label-desc">Video akan otomatis diputar saat diakses.</span>
                                                    </div>
                                                </div>
                                                <label class="toggle-switch toggle-switch-shrink">
                                                    <input class="js-update-video-preview" type="checkbox" id="videoAutoplay_{{ $elementId }}" data-target-id="{{ $elementId }}" {{ $isAutoplay ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>

                                            <div class="element-action-footer">
                                                <button type="button" data-type="Video" data-target-id="{{ $elementId }}" class="btn-delete-element js-remove-element">
                                                    <i class="fas fa-trash-alt"></i> Hapus Elemen
                                                </button>
                                                <button type="button" data-type="Video" data-target-id="{{ $elementId }}" class="btn-save-element js-save-element" id="btnSaveVideo_{{ $elementId }}">
                                                    Simpan
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
