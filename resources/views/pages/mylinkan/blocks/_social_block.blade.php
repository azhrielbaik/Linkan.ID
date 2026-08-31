                                @php 
                                    $elementId = 'socialBlock_' . $socialEl->id; 
                                    $isActive = $socialEl->is_active ?? true;
                                    $platforms = is_string($socialEl->platforms) ? json_decode($socialEl->platforms, true) : ($socialEl->platforms ?? []);
                                @endphp
                                <div id="{{ $elementId }}" class="draggable-element-block {{ $isActive ? '' : 'block-inactive' }}" data-element-type="social" data-db-id="{{ $socialEl->id }}">
                                    <div class="block-item-card js-toggle-edit-form" data-type="Social" data-target-id="{{ $elementId }}">
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
                                                <span class="visibility-status-text {{ $isActive ? 'status-active' : 'status-inactive' }}" id="statusText_{{ $elementId }}">{{ $isActive ? 'Aktif' : 'Nonaktif' }}</span>
                                                <label class="toggle-switch">
                                                    <input class="js-toggle-visibility" type="checkbox" id="visibilitySwitch_{{ $elementId }}" data-target-id="{{ $elementId }}" {{ $isActive ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                            <button type="button" class="btn-element-action btn-delete-icon js-remove-element" data-type="SocialMedia" data-target-id="{{ $elementId }}" title="Hapus Elemen">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                            <button type="button" data-type="Social" data-target-id="{{ $elementId }}" class="btn-edit-block js-toggle-edit-form">
                                                <i class="fas fa-pen" class="btn-edit-icon"></i> <span id="btnText_{{ $elementId }}">Edit</span>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Form Edit untuk Media Sosial -->
                                    <div id="formBody_{{ $elementId }}" class="edit-form-body" style="max-height: 0; opacity: 0; margin-top: 0;">
                                        <div class="edit-form-content">
                                            <form id="socialForm_{{ $elementId }}">
                                                <div class="social-edit-header">
                                                    <h4 class="form-section-title" style="margin-bottom: 5px;"><i class="fas fa-share-alt"></i> Pengaturan Media Sosial</h4>
                                                    <p style="font-size: 13px; color: #6b7280; margin-bottom: 20px;">Aktifkan platform yang ingin Anda tampilkan.</p>
                                                </div>

                                                <div class="social-platforms-list" id="social_platforms_list_{{ $elementId }}">
                                                    @php
                                                        $availablePlatforms = [
                                                            'linkedin' => ['icon' => 'fab fa-linkedin', 'color' => '#0077b5', 'label' => 'LinkedIn', 'placeholder' => 'contoh: https://linkedin.com/in/username'],
                                                            'reddit' => ['icon' => 'fab fa-reddit', 'color' => '#FF4500', 'label' => 'Reddit', 'placeholder' => 'contoh: https://reddit.com/user/username'],
                                                            'instagram' => ['icon' => 'fab fa-instagram', 'color' => '#E1306C', 'label' => 'Instagram', 'placeholder' => 'contoh: https://instagram.com/username'],
                                                            'facebook' => ['icon' => 'fab fa-facebook', 'color' => '#1877F2', 'label' => 'Facebook', 'placeholder' => 'contoh: https://facebook.com/username'],
                                                            'youtube' => ['icon' => 'fab fa-youtube', 'color' => '#FF0000', 'label' => 'YouTube', 'placeholder' => 'contoh: https://youtube.com/c/username'],
                                                            'whatsapp' => ['icon' => 'fab fa-whatsapp', 'color' => '#25D366', 'label' => 'WhatsApp', 'placeholder' => 'contoh: 628123456789'],
                                                            'telegram' => ['icon' => 'fab fa-telegram', 'color' => '#0088cc', 'label' => 'Telegram', 'placeholder' => 'contoh: username_anda'],
                                                            'tiktok' => ['icon' => 'fab fa-tiktok', 'color' => '#000000', 'label' => 'TikTok', 'placeholder' => 'contoh: https://tiktok.com/@username'],
                                                            'twitter' => ['icon' => 'fab fa-x-twitter', 'color' => '#000000', 'label' => 'X (Twitter)', 'placeholder' => 'contoh: https://x.com/username'],
                                                            'email' => ['icon' => 'fas fa-envelope', 'color' => '#ea4335', 'label' => 'Email', 'placeholder' => 'contoh: email@anda.com'],
                                                        ];
                                                    @endphp

                                                    @foreach($availablePlatforms as $platKey => $platInfo)
                                                        @if(array_key_exists($platKey, $platforms) && !empty($platforms[$platKey]))
                                                            <div class="social-platform-item" id="platform_item_{{ $platKey }}_{{ $elementId }}">
                                                                <div class="platform-header">
                                                                    <div class="platform-info">
                                                                        <i class="{{ $platInfo['icon'] }}" style="color: {{ $platInfo['color'] }}; font-size: 20px; width: 24px; text-align: center;"></i>
                                                                        <span class="platform-name">{{ $platInfo['label'] }}</span>
                                                                    </div>
                                                                    <button type="button" class="btn-remove-platform js-remove-social-platform" data-target-id="{{ $elementId }}" data-platform="{{ $platKey }}" title="Hapus Platform">
                                                                        <i class="fas fa-trash-alt"></i>
                                                                    </button>
                                                                </div>
                                                                <div class="platform-input-container">
                                                                    <label class="form-label-custom">URL atau Username {{ $platInfo['label'] }}</label>
                                                                    <input type="text" id="input_{{ $platKey }}_{{ $elementId }}" class="form-input-custom platform-input-trigger js-update-social-preview js-update-social-preview" data-platform="{{ $platKey }}" data-element="{{ $elementId }}" value="{{ $platforms[$platKey] }}" placeholder="{{ $platInfo['placeholder'] }}" data-target-id="{{ $elementId }}" data-target-id="{{ $elementId }}">
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>

                                                <!-- ADD PLATFORM BUTTON -->
                                                <div style="margin-top: 16px; text-align: center;">
                                                    <button type="button" class="btn btn-outline btn-primary btn-sm js-open-social-selector" data-target-id="{{ $elementId }}" style="border-radius: 8px; width: 100%; border: 1px dashed #cbd5e1; color: #64748b; background: white; padding: 12px; transition: all 0.2s;"  >
                                                        <i class="fas fa-plus"></i> Tambah Platform Media Sosial
                                                    </button>
                                                </div>

                                                <div class="form-actions-wrapper">
                                                    <button type="button" class="btn-secondary js-toggle-edit-form" data-type="Social" data-target-id="{{ $elementId }}">
                                                        Batal
                                                    </button>
                                                    <button type="button" class="btn-primary btn-submit js-save-element" data-type="SocialMedia" data-target-id="{{ $elementId }}">
                                                    Simpan
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
