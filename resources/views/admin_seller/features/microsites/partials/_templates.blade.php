    <!-- TEMPLATES FOR DYNAMIC ELEMENTS -->
    <template id="image-block-template">
    <x-microsite.blocks.image 
        elementId="__ELEMENT_ID__" 
        :data="null" 
        :isActive="true" 
    />
</template>

    <template id="image-live-template">
        <div id="live___ELEMENT_ID__" class="microsite-live-element live-element-pointer js-toggle-edit-form" style="display: none;" data-type="Image" data-target-id="__ELEMENT_ID__" data-force-open="true">
            <a id="liveLink___ELEMENT_ID__" class="live-element-link pointer-events-none">
                <img id="liveImg___ELEMENT_ID__" src="" class="live-element-img" alt="Live Image Element">
            </a>
        </div>
    </template>

    <template id="divider-block-template">
    <x-microsite.blocks.divider 
        elementId="__ELEMENT_ID__" 
        :data="null" 
        :isActive="true" 
    />
</template>

    <template id="divider-live-template">
        <div id="live___ELEMENT_ID__" class="microsite-live-element live-divider-wrapper live-divider-padding js-toggle-edit-form" data-type="Divider" data-target-id="__ELEMENT_ID__" data-force-open="true">
            <div id="liveDivider___ELEMENT_ID__" class="live-divider-inner live-divider-line"></div>
        </div>
    </template>

    <template id="text-block-template">
    <x-microsite.blocks.text 
        elementId="__ELEMENT_ID__" 
        :data="null" 
        :isActive="true" 
    />
</template>

    <template id="video-live-template">
        <div class="video-container" id="liveVideoContainer___ELEMENT_ID__">
            <!-- iframe will be generated here -->
            <div class="live-video-placeholder">
                <i class="fab fa-youtube live-video-placeholder-icon"></i>
                {{ __('microsite.enter_youtube_url') }}
            </div>
        </div>
    </template>

    <template id="video-block-template">
    <x-microsite.blocks.video 
        elementId="__ELEMENT_ID__" 
        :data="null" 
        :isActive="true" 
    />
</template>

    <!-- CUSTOM DELETE CONFIRMATION MODAL -->

    <template id="social-live-template">
        <div id="live___ELEMENT_ID__" class="microsite-live-element live-social-wrapper js-toggle-edit-form" data-type="Social" data-target-id="__ELEMENT_ID__" data-force-open="true">
            <div id="liveSocialContainer___ELEMENT_ID__" class="live-social-container live-social-container-styled">
                <div class="live-social-placeholder">
                    <i class="fas fa-share-alt live-social-placeholder-icon"></i>
                    {{ __('microsite.setup_social_media') }}
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
                        <button type="button" class="btn-remove-platform js-remove-social-platform" data-target-id="__ELEMENT_ID__" data-platform="__PLATFORM__" title="{{ __('microsite.btn_remove_platform') }}">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
            <div class="platform-input-container">
                <label class="form-label-custom">{{ __('microsite.url_or_username') }} __PLATFORM_NAME__</label>
                <input type="text" id="input___PLATFORM_____ELEMENT_ID__" class="form-input-custom platform-input-trigger js-update-social-preview js-update-social-preview" data-platform="__PLATFORM__" data-element="__ELEMENT_ID__" placeholder="__PLACEHOLDER__" data-target-id="__ELEMENT_ID__" data-target-id="__ELEMENT_ID__">
            </div>
        </div>
    </template>
    <template id="social-block-template">
    <x-microsite.blocks.social 
        elementId="__ELEMENT_ID__" 
        :data="null" 
        :isActive="true" 
    />
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
                <img id="cropperImageTarget" src="" style="max-width: 100%; display: block;" alt="Cropper Target">
            </div>
            
            <div class="custom-confirm-actions">
                <button type="button" class="btn-custom-confirm-cancel js-close-cropper-modal">Batal</button>
                <button type="button" class="btn-custom-confirm-submit js-apply-crop" style="background-color: #3b82f6; border-color: #3b82f6;">Gunakan Foto</button>
            </div>
        </div>
    </div>


