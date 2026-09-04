<div class="new-microsite-modal-overlay" id="newMicrositeModalOverlay">
    <div class="new-microsite-modal-wrapper">
        
        <!-- FLOATING DETACHED CLOSE BUTTON -->
        <button type="button" class="floating-close-btn" onclick="closeNewMicrositeModal()" title="Tutup Modal">&times;</button>

        <div class="new-microsite-modal-card">
            
            <!-- MODAL HEADER & STEP DOTS -->
            <div class="new-microsite-modal-header">
                <div>
                    <h3 class="new-microsite-modal-title" id="wizardTitle">
                        <i class="fas fa-brush" class="upload-text-highlight"></i> {{ __('admin.create_new_microsite') }}
                    </h3>
                    <p class="new-microsite-modal-subtitle" id="wizardSubtitle">
                        Langkah 1 dari 2: Pilih Tujuan Pembuatan Microsite
                    </p>
                </div>
                
                <div class="wizard-header-right">
                    <!-- STEP DOTS INDICATOR -->
                    <div class="wizard-step-dots">
                        <span class="step-dot active" id="dotStep1" onclick="goToStep(1)" title="Langkah 1: Tujuan Pembuatan"></span>
                        <span class="step-dot" id="dotStep2" onclick="goToStep(2)" title="Langkah 2: Detail Microsite"></span>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.microsite.store') }}" method="POST" id="newMicrositeForm">
                @csrf
                <input type="hidden" name="purpose" id="selectedPurpose" value="">

                <!-- STEP 1: PURPOSE SELECTION (SINGLE PAGE VIEW) -->
                <div class="wizard-step-content active" id="wizardStep1">
                    <div class="purpose-vertical-list">
                        
                        <!-- Card 1: Portofolio -->
                        <div class="image-style-option-card" onclick="selectPurposeCard('portofolio', this)">
                            <div class="option-thumb-box">
                                <div class="option-mobile-icon-badge" class="wizard-card-portfolio-bg">
                                    <i class="fas fa-user-tie" class="wizard-card-portfolio-icon"></i>
                                </div>
                                <div class="thumb-ui-mockup portfolio-mockup">
                                    <div class="mock-avatar-circle">
                                        <i class="fas fa-user-tie" class="wizard-card-portfolio-icon-sm"></i>
                                    </div>
                                    <div class="mock-lines">
                                        <div class="mock-line-title"></div>
                                        <div class="mock-line-sub"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="option-text-info">
                                <span class="option-main-title">{{ __('admin.purpose_portfolio') }}</span>
                                <span class="option-sub-desc">{{ __('admin.purpose_portfolio_desc') }}</span>
                            </div>
                            <i class="fas fa-chevron-right option-chevron-arrow"></i>
                        </div>

                        <!-- Card 2: Jualan Produk / Marketing -->
                        <div class="image-style-option-card" onclick="selectPurposeCard('marketing', this)">
                            <div class="option-thumb-box">
                                <div class="option-mobile-icon-badge" class="wizard-card-marketing-bg">
                                    <i class="fas fa-store" class="wizard-card-marketing-icon"></i>
                                </div>
                                <div class="thumb-ui-mockup store-mockup">
                                    <div class="mock-store-card">
                                        <div class="mock-store-icon">
                                            <i class="fas fa-shopping-bag" class="wizard-card-marketing-icon-sm"></i>
                                        </div>
                                        <div class="mock-store-badge">STORE</div>
                                    </div>
                                </div>
                            </div>
                            <div class="option-text-info">
                                <span class="option-main-title">{{ __('admin.purpose_marketing') }}</span>
                                <span class="option-sub-desc">{{ __('admin.purpose_marketing_desc') }}</span>
                            </div>
                            <i class="fas fa-chevron-right option-chevron-arrow"></i>
                        </div>

                        <!-- Card 3: Affiliate -->
                        <div class="image-style-option-card" onclick="selectPurposeCard('affiliate', this)">
                            <div class="option-thumb-box">
                                <div class="option-mobile-icon-badge" class="wizard-card-affiliate-bg">
                                    <i class="fas fa-link" class="wizard-card-affiliate-icon"></i>
                                </div>
                                <div class="thumb-ui-mockup affiliate-mockup">
                                    <div class="mock-link-pill">
                                        <i class="fas fa-link" class="wizard-card-affiliate-icon-sm"></i>
                                        <span>affiliate.link</span>
                                    </div>
                                    <div class="mock-link-pill sub">
                                        <i class="fas fa-share-alt" class="wizard-card-affiliate-icon-sm-blue"></i>
                                        <span>ref=linkan</span>
                                    </div>
                                </div>
                            </div>
                            <div class="option-text-info">
                                <span class="option-main-title">{{ __('admin.purpose_affiliate') }}</span>
                                <span class="option-sub-desc">{{ __('admin.purpose_affiliate_desc') }}</span>
                            </div>
                            <i class="fas fa-chevron-right option-chevron-arrow"></i>
                        </div>

                        <!-- Card 4: Lainnya -->
                        <div class="image-style-option-card" onclick="selectPurposeCard('lainnya', this)">
                            <div class="option-thumb-box">
                                <div class="option-mobile-icon-badge" class="wizard-card-other-bg">
                                    <i class="fas fa-layer-group" class="wizard-card-other-icon"></i>
                                </div>
                                <div class="thumb-ui-mockup custom-mockup">
                                    <div class="mock-layer-box top"></div>
                                    <div class="mock-layer-box mid"></div>
                                    <div class="mock-layer-box bot"></div>
                                </div>
                            </div>
                            <div class="option-text-info">
                                <span class="option-main-title">{{ __('admin.purpose_other') }}</span>
                                <span class="option-sub-desc">{{ __('admin.purpose_other_desc') }}</span>
                            </div>
                            <i class="fas fa-chevron-right option-chevron-arrow"></i>
                        </div>

                    </div>

                    <div class="wizard-modal-footer">
                        <button type="button" class="btn-action-secondary" onclick="closeNewMicrositeModal()">
                            {{ __('admin.cancel') }}
                        </button>
                        <button type="button" class="btn-action-primary" id="btnNextStep1" onclick="goToStep(2)" disabled>
                            Lanjut <i class="fas fa-arrow-right" class="wizard-icon-secondary"></i>
                        </button>
                    </div>
                </div>

                <!-- STEP 2: MICROSITE DETAILS (SINGLE PAGE VIEW) -->
                <div class="wizard-step-content" id="wizardStep2" style="display: none;">
                    <div class="step-fields-container">
                        
                        <div class="form-field-item card-title-row">
                            <label class="field-label">{{ __('admin.microsite_title_label') }} <span class="required-asterisk">*</span></label>
                            <input type="text" name="title" id="micrositeNameInput" class="form-control-input" placeholder="{{ __('admin.microsite_title_placeholder') }}" value="{{ old('title', Auth::user()->name) }}" required>
                        </div>

                        <div class="form-field-item form-field-margin">
                            <label class="field-label">Alamat Microsite <span class="required-asterisk">*</span></label>
                            <div style="display: flex; align-items: stretch; width: 100%;">
                                <span style="background-color: #f3f4f6; border: 1px solid #d1d5db; border-right: none; border-top-left-radius: 0.375rem; border-bottom-left-radius: 0.375rem; padding: 0.5rem 0.75rem; color: #4b5563; font-size: 0.875rem; white-space: nowrap; display: flex; align-items: center;">linkan.id/</span>
                                <input type="text" name="alias" id="micrositeAliasInput" class="form-control-input" style="border-top-left-radius: 0; border-bottom-left-radius: 0; flex: 1;" placeholder="alamat-anda" maxlength="12" value="{{ old('alias', Auth::user()->username) }}" required>
                            </div>
                            <small style="color: #6b7280; font-size: 0.75rem; margin-top: 4px; display: block;">Maksimal 12 karakter. Hanya huruf, angka, dan tanda hubung (-).</small>
                        </div>

                        <div class="form-field-item form-field-margin">
                            <label class="field-label">{{ __('admin.microsite_bio_label') }}</label>
                            <textarea name="bio" class="form-control-input" rows="3" placeholder="{{ __('admin.microsite_bio_placeholder') }}">{{ old('bio') }}</textarea>
                        </div>

                    </div>

                    <div class="wizard-modal-footer">
                        <button type="button" class="btn-action-secondary" onclick="goToStep(1)">
                            <i class="fas fa-arrow-left" class="wizard-icon-back"></i> Kembali
                        </button>
                        <button type="submit" class="btn-action-primary">
                            <i class="fas fa-brush" class="wizard-icon-back"></i> {{ __('admin.create_microsite_btn') }}
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('micrositeNameInput');
    const aliasInput = document.getElementById('micrositeAliasInput');

    if (nameInput && aliasInput) {
        // Hanya auto-generate jika alias masih sama dengan default/kosong
        let isManuallyEdited = false;
        
        aliasInput.addEventListener('input', function() {
            isManuallyEdited = true;
            let slug = this.value
                .toLowerCase()
                .replace(/[^a-z0-9-]/g, '-')
                .replace(/-+/g, '-');
            this.value = slug.substring(0, 12);
        });

        nameInput.addEventListener('input', function() {
            if (!isManuallyEdited) {
                let slug = this.value
                    .toLowerCase()
                    .replace(/[^a-z0-9-]/g, '-')
                    .replace(/-+/g, '-')
                    .replace(/^-|-$/g, '');
                aliasInput.value = slug.substring(0, 12);
            }
        });
    }
});
</script>
