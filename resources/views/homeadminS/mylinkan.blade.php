@extends("layouts.admin")

@section("page_title", __('admin.microsite_management'))

@push("styles")
<link rel="stylesheet" href="{{ asset('css/pages/mylinkan.css') }}" data-turbo-track="reload">
@endpush

@section("content")
<div class="dashboard-mylinkan-page">

<div class="microsite-container">
    
    <!-- COMBINED HEADER & MODE SWITCH -->
    <div class="section-header" style="margin-top: 10px;">
        @if($viewMode == 'gallery')
            <div style="display: flex; justify-content: space-between; align-items: center; width: 100%; flex-wrap: wrap; gap: 15px;">
                <h2 class="gallery-title" style="margin: 0;"><i class="fas fa-layer-group" style="color: #FF9040;"></i> {{ __('admin.my_microsite_list') }}</h2>
                
                <!-- ACTION BAR: SEARCH, FILTER, BUAT BARU -->
                <div class="microsite-actions-bar">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Cari microsite...">
                    </div>
                    <button type="button" class="btn-action-secondary" style="padding: 10px 16px;">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <button type="button" class="btn-action-primary" style="padding: 10px 16px;" onclick="openNewMicrositeModal()">
                        <i class="fas fa-plus"></i> Buat Baru
                    </button>
                </div>
            </div>
        @else
            <h2 style="display: flex; align-items: center; margin: 0;">
                <a href="{{ route('admin.mylinkan', ['mode' => 'gallery']) }}" style="color: #6b7280; text-decoration: none; margin-right: 10px;">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <i class="fas fa-sliders-h" style="color: #FF9040; margin-right: 10px;"></i> {{ __('admin.edit_content_blocks') }}
            </h2>
        @endif
    </div>

    @if($viewMode == 'gallery')
        <!-- GALLERY LIST VIEW -->

        <div class="microsite-gallery-grid">
            
            <!-- MAIN MICROSITE CARD WITH PHONE SCREENSHOT THUMBNAIL -->
            <div class="microsite-card">
                
                <!-- CARD HEADER & THUMBNAIL CONTAINER -->
                <div class="card-thumbnail-container">

                    <!-- REAL PHONE THUMBNAIL REPRESENTATION -->
                    <div class="phone-thumbnail">
                        <div class="phone-thumbnail-screen" style="
                            background-image: url('{{ $appearance && $appearance->background_color ? asset('images/background/' . $appearance->background_color) : '' }}');
                            background-color: {{ $appearance && $appearance->background_color ? 'transparent' : '#f8f9fa' }};
                        ">
                            <!-- Banner -->
                            <div class="phone-thumb-banner">
                                @if($appearance && $appearance->banner)
                                    <img src="{{ asset('storage/' . $appearance->banner) }}" alt="Banner">
                                @endif
                            </div>

                            <!-- Avatar -->
                            <div class="phone-thumb-avatar">
                                @if($appearance && $appearance->profile_image)
                                    <img src="{{ asset('storage/' . $appearance->profile_image) }}" alt="Avatar">
                                @else
                                    <i class="fas fa-user" style="color: #888; font-size: 16px;"></i>
                                @endif
                            </div>

                            <!-- Name & Bio -->
                            <div class="phone-thumb-name" style="color: {{ $appearance ? $appearance->theme_color : '#FF9040' }}">
                                {{ $appearance ? $appearance->name : Auth::user()->name }}
                            </div>
                            <div class="phone-thumb-bio" style="color: {{ $appearance ? $appearance->theme_color : '#666' }}">
                                {!! strip_tags($appearance->bio ?? 'Selamat datang di linkan saya!') !!}
                            </div>

                            <!-- Block Preview Snippets -->
                            @if($digitalProducts && $digitalProducts->count() > 0)
                                @foreach($digitalProducts->take(2) as $prod)
                                    <div class="phone-thumb-block">
                                        <i class="fas fa-box"></i>
                                        <span>{{ $prod->title }}</span>
                                    </div>
                                @endforeach
                            @endif

                            @if($shortlinks && $shortlinks->count() > 0)
                                @foreach($shortlinks->take(2) as $sl)
                                    <div class="phone-thumb-block">
                                        <i class="fas fa-link"></i>
                                        <span>{{ $sl->title ?: $sl->slug }}</span>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <!-- CARD BODY DETAILS -->
                <div class="card-body-details">
                    <div class="card-title-row">
                        <h3 class="microsite-name">{{ $appearance->name ?? Auth::user()->name }}</h3>
                    </div>

                    <a href="{{ url('/linkan.id/' . Auth::user()->username) }}" target="_blank" class="url-pill">
                        <i class="fas fa-globe"></i> linkan.id/{{ Auth::user()->username }}
                    </a>

                    <div class="card-stats-tags">
                        <span class="stat-tag"><i class="fas fa-eye"></i> {{ number_format($totalViews) }} views</span>
                        <span class="stat-tag"><i class="fas fa-cube"></i> {{ $digitalProducts->count() }} {{ __('admin.product') }}</span>
                        <span class="stat-tag"><i class="fas fa-link"></i> {{ $shortlinks->total() }} {{ __('admin.link') }}</span>
                    </div>

                    <!-- ACTION BUTTONS -->
                    <div class="card-actions-grid">
                        <button type="button" class="btn-action-secondary" onclick="openPreviewModal('{{ url('/linkan.id/' . Auth::user()->username) }}')">
                            <i class="fas fa-mobile-alt"></i> {{ __('admin.preview') }}
                        </button>
                        <a href="{{ route('admin.mylinkan', ['mode' => 'edit']) }}" class="btn-action-primary">
                            <i class="fas fa-edit"></i> {{ __('admin.edit_block') }}
                        </a>
                        <a href="{{ route('admin.appearance') }}" class="btn-action-secondary">
                            <i class="fas fa-paint-brush"></i> {{ __('admin.appearance') }}
                        </a>
                        <button type="button" class="btn-action-secondary" onclick="copyToClipboard('{{ url('/linkan.id/' . Auth::user()->username) }}')">
                            <i class="fas fa-copy"></i> {{ __('admin.copy_link') }}
                        </button>
                    </div>
                </div>

            </div>

        </div>

    @else
        <!-- EDITOR VIEW MODE -->

        <div class="editor-layout">
            <!-- LEFT PANEL: BLOCK MANAGEMENT -->
            <div class="editor-left-panel">
                
                <!-- URL HEADER BAR -->
                <div style="background: #f9fafb; padding: 14px 18px; border-radius: 12px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; border: 1px solid #e5e7eb;">
                    <span style="font-weight: 600; font-size: 14px; color: #374151;">
                        <i class="fas fa-link" style="color: #FF9040; margin-right: 8px;"></i> {{ url('/linkan.id/' . Auth::user()->username) }}
                    </span>
                    <button type="button" class="btn-action-secondary" style="padding: 6px 12px; font-size: 12px;" onclick="copyToClipboard('{{ url('/linkan.id/' . Auth::user()->username) }}')">
                        <i class="fas fa-copy"></i> {{ __('admin.copy_link') }}
                    </button>
                </div>

                <!-- DIGITAL PRODUCTS BLOCKS -->
                @if($digitalProducts->count())
                    <div style="margin-bottom: 30px;">
                        <h3 style="font-size: 16px; font-weight: 700; color: #111827; margin-bottom: 14px;">{{ __('admin.my_digital_products') }}</h3>
                        @foreach($digitalProducts as $product)
                            <div class="block-item-card">
                                <i class="fas fa-grip-vertical" style="color: #9ca3af; cursor: move;"></i>
                                <div style="width: 36px; height: 36px; border-radius: 8px; background: #FFE5D3; color: #FF9040; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <div style="flex: 1; min-width: 0;">
                                    <div style="font-weight: 700; font-size: 14px; color: #111827;">{{ $product->title }}</div>
                                    @if($product->verification_status == 'pending')
                                        <span class="status pending" style="font-size: 11px; background: #fff3cd; color: #856404; padding: 2px 8px; border-radius: 4px;">{{ __('admin.waiting_verification') }}</span>
                                    @elseif($product->verification_status == 'rejected')
                                        <span class="status rejected" style="font-size: 11px; background: #f8d7da; color: #721c24; padding: 2px 8px; border-radius: 4px;">{{ __('admin.rejected') }}</span>
                                    @else
                                        <span class="status approved" style="font-size: 11px; background: #d4edda; color: #155724; padding: 2px 8px; border-radius: 4px;">{{ __('admin.verified') }}</span>
                                    @endif
                                </div>
                                <i class="fas fa-ellipsis-v" style="color: #6b7280; cursor: pointer;"></i>
                            </div>
                        @endforeach
                    </div>
                @endif


            </div>

            <!-- RIGHT PANEL: STICKY PHONE PREVIEW -->
            <div class="editor-sticky-preview">
                <h3 style="font-size: 16px; font-weight: 700; color: #1f2937; margin-bottom: 14px;">{{ __('admin.live_phone_preview') }}</h3>
                <div class="phone-preview" style="width: 100%; max-width: 320px; aspect-ratio: 9/19; border-radius: 36px; background: white; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border: 4px solid #111827;">
                    <div class="phone-content" style="width: 100%; height: 100%; padding: 18px 14px; overflow-y: auto; background-image: url('{{ $appearance && $appearance->background_color ? asset('images/background/' . $appearance->background_color) : '' }}'); background-size: cover; background-position: center;">
                        @if($appearance && $appearance->banner)
                            <div style="width: 100%; height: 100px; border-radius: 10px; overflow: hidden; margin-bottom: 14px;">
                                <img src="{{ asset('storage/' . $appearance->banner) }}" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        @endif

                        <div style="width: 70px; height: 70px; border-radius: 50%; overflow: hidden; margin: 0 auto 10px; background: #e5e7eb; display: flex; align-items: center; justify-content: center;">
                            @if($appearance && $appearance->profile_image)
                                <img src="{{ asset('storage/' . $appearance->profile_image) }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <i class="fas fa-user" style="font-size: 24px; color: #888;"></i>
                            @endif
                        </div>

                        <div style="font-size: 16px; font-weight: 700; text-align: center; margin-bottom: 6px; color: {{ $appearance ? $appearance->theme_color : '#FF9040' }}">
                            {{ $appearance ? $appearance->name : Auth::user()->name }}
                        </div>

                        @if($appearance && $appearance->bio)
                            <div style="font-size: 12px; text-align: center; margin-bottom: 14px; color: {{ $appearance ? $appearance->theme_color : '#666' }}">
                                {!! $appearance->bio !!}
                            </div>
                        @endif

                        @if($digitalProducts->count() > 0)
                            <div style="display: flex; flex-direction: column; gap: 8px; width: 100%;">
                                @foreach($digitalProducts as $product)
                                    <div style="background: white; border-radius: 8px; padding: 8px 10px; display: flex; align-items: center; gap: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                        <div style="width: 32px; height: 32px; border-radius: 6px; background: #FFE5D3; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                            <i class="fas fa-file-alt" style="color: #FF9040; font-size: 12px;"></i>
                                        </div>
                                        <div style="font-size: 12px; font-weight: 600; color: #333; flex: 1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $product->title }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>

<!-- INTERACTIVE PHONE PREVIEW MODAL -->
<div class="preview-modal-overlay" id="previewModalOverlay">
    <div class="phone-modal-wrapper">
        <button type="button" class="btn-close-modal" onclick="closePreviewModal()">&times;</button>
        
        <div class="phone-modal-header">
            <i class="fas fa-globe" style="color: #FF9040;"></i>
            <span id="modalUrlText">linkan.id/{{ Auth::user()->username }}</span>
            <button type="button" class="btn-action-secondary" style="padding: 4px 10px; font-size: 11px;" onclick="copyToClipboard('{{ url('/linkan.id/' . Auth::user()->username) }}')">
                <i class="fas fa-copy"></i>
            </button>
            <a href="{{ url('/linkan.id/' . Auth::user()->username) }}" target="_blank" class="btn-action-primary" style="padding: 4px 10px; font-size: 11px;">
                <i class="fas fa-external-link-alt"></i> {{ __('admin.open') }}
            </a>
        </div>

        <div class="phone-device-frame" id="deviceFrame">
            <div class="phone-notch" id="deviceNotch"></div>
            <iframe src="" class="phone-iframe" id="phonePreviewIframe"></iframe>
        </div>
        
        <div class="device-toggle-btns" style="margin-top: 20px; display: flex; gap: 10px; justify-content: center; z-index: 10;">
            <button type="button" class="btn-action-primary" id="btnMobilePreview" onclick="setPreviewDevice('mobile')" style="padding: 8px 16px; min-width: 110px;">
                <i class="fas fa-mobile-alt"></i> Mobile
            </button>
            <button type="button" class="btn-action-secondary" id="btnDesktopPreview" onclick="setPreviewDevice('desktop')" style="padding: 8px 16px; min-width: 110px;">
                <i class="fas fa-desktop"></i> Desktop
            </button>
        </div>
    </div>
</div>

<!-- NEW MICROSITE CREATION MULTI-STEP MODAL -->
<div class="new-microsite-modal-overlay" id="newMicrositeModalOverlay">
    <div class="new-microsite-modal-wrapper">
        
        <!-- FLOATING DETACHED CLOSE BUTTON -->
        <button type="button" class="floating-close-btn" onclick="closeNewMicrositeModal()" title="Tutup Modal">&times;</button>

        <div class="new-microsite-modal-card">
            
            <!-- MODAL HEADER & STEP DOTS -->
            <div class="new-microsite-modal-header">
                <div>
                    <h3 class="new-microsite-modal-title" id="wizardTitle">
                        <i class="fas fa-brush" style="color: #FF9040;"></i> {{ __('admin.create_new_microsite') }}
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
                                <div class="option-mobile-icon-badge" style="background: #EFF6FF;">
                                    <i class="fas fa-user-tie" style="color: #2563eb; font-size: 20px;"></i>
                                </div>
                                <div class="thumb-ui-mockup portfolio-mockup">
                                    <div class="mock-avatar-circle">
                                        <i class="fas fa-user-tie" style="color: #2563eb; font-size: 15px;"></i>
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
                                <div class="option-mobile-icon-badge" style="background: #FFF3E6;">
                                    <i class="fas fa-store" style="color: #ea580c; font-size: 20px;"></i>
                                </div>
                                <div class="thumb-ui-mockup store-mockup">
                                    <div class="mock-store-card">
                                        <div class="mock-store-icon">
                                            <i class="fas fa-shopping-bag" style="color: #ea580c; font-size: 16px;"></i>
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
                                <div class="option-mobile-icon-badge" style="background: #ECFDF5;">
                                    <i class="fas fa-link" style="color: #059669; font-size: 20px;"></i>
                                </div>
                                <div class="thumb-ui-mockup affiliate-mockup">
                                    <div class="mock-link-pill">
                                        <i class="fas fa-link" style="color: #059669; font-size: 11px;"></i>
                                        <span>affiliate.link</span>
                                    </div>
                                    <div class="mock-link-pill sub">
                                        <i class="fas fa-share-alt" style="color: #2563eb; font-size: 11px;"></i>
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
                                <div class="option-mobile-icon-badge" style="background: #F3E8FF;">
                                    <i class="fas fa-layer-group" style="color: #7c3aed; font-size: 20px;"></i>
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
                            Lanjut <i class="fas fa-arrow-right" style="margin-left: 6px;"></i>
                        </button>
                    </div>
                </div>

                <!-- STEP 2: MICROSITE DETAILS (SINGLE PAGE VIEW) -->
                <div class="wizard-step-content" id="wizardStep2" style="display: none;">
                    <div class="step-fields-container">
                        
                        <div class="form-field-item">
                            <label class="field-label">{{ __('admin.microsite_title_label') }} <span style="color: #ef4444;">*</span></label>
                            <input type="text" name="name" id="micrositeNameInput" class="form-control-input" placeholder="{{ __('admin.microsite_title_placeholder') }}" value="{{ old('name', Auth::user()->name) }}" required>
                        </div>

                        <div class="form-field-item" style="margin-top: 16px;">
                            <label class="field-label">{{ __('admin.microsite_bio_label') }}</label>
                            <textarea name="bio" class="form-control-input" rows="3" placeholder="{{ __('admin.microsite_bio_placeholder') }}">{{ old('bio') }}</textarea>
                        </div>

                    </div>

                    <div class="wizard-modal-footer">
                        <button type="button" class="btn-action-secondary" onclick="goToStep(1)">
                            <i class="fas fa-arrow-left" style="margin-right: 6px;"></i> Kembali
                        </button>
                        <button type="submit" class="btn-action-primary">
                            <i class="fas fa-brush" style="margin-right: 6px;"></i> {{ __('admin.create_microsite_btn') }}
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

</div>
@endsection

@push("scripts")
<script>
    let currentStep = 1;

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert('Tautan berhasil disalin ke clipboard!');
        }).catch(err => {
            console.error('Gagal menyalin teks: ', err);
        });
    }

    function openNewMicrositeModal() {
        const modal = document.getElementById('newMicrositeModalOverlay');
        if (modal) {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
            
            // Reset selection & disable next button
            const hiddenInput = document.getElementById('selectedPurpose');
            if (hiddenInput) hiddenInput.value = '';
            const allCards = document.querySelectorAll('.image-style-option-card');
            allCards.forEach(card => card.classList.remove('active'));

            const btnNext = document.getElementById('btnNextStep1');
            if (btnNext) btnNext.disabled = true;

            goToStep(1);
        }
    }

    function closeNewMicrositeModal() {
        const modal = document.getElementById('newMicrositeModalOverlay');
        if (modal) {
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }
    }

    function selectPurposeCard(purpose, cardElement) {
        const hiddenInput = document.getElementById('selectedPurpose');
        if (hiddenInput) {
            hiddenInput.value = purpose;
        }
        const allCards = document.querySelectorAll('.image-style-option-card');
        allCards.forEach(card => card.classList.remove('active'));
        if (cardElement) {
            cardElement.classList.add('active');
        }

        // Enable next button
        const btnNext = document.getElementById('btnNextStep1');
        if (btnNext) btnNext.disabled = false;
    }

    function goToStep(step) {
        if (step === 2) {
            const purpose = document.getElementById('selectedPurpose').value;
            if (!purpose) {
                alert('Silakan pilih salah satu tujuan pembuatan microsite terlebih dahulu!');
                return;
            }
        }

        currentStep = step;
        const step1 = document.getElementById('wizardStep1');
        const step2 = document.getElementById('wizardStep2');
        const dot1 = document.getElementById('dotStep1');
        const dot2 = document.getElementById('dotStep2');
        const subtitleText = document.getElementById('wizardSubtitle');

        if (step === 1) {
            if (step1) step1.style.display = 'block';
            if (step2) step2.style.display = 'none';
            if (dot1) dot1.classList.add('active');
            if (dot2) dot2.classList.remove('active');
            if (subtitleText) subtitleText.innerText = 'Langkah 1 dari 2: Pilih Tujuan Pembuatan Microsite';
        } else if (step === 2) {
            if (step1) step1.style.display = 'none';
            if (step2) step2.style.display = 'block';
            if (dot1) dot1.classList.remove('active');
            if (dot2) dot2.classList.add('active');
            if (subtitleText) subtitleText.innerText = 'Langkah 2 dari 2: Isi Nama & Bio Microsite Baru';
            const nameInput = document.getElementById('micrositeNameInput');
            if (nameInput) nameInput.focus();
        }
    }

    function setPreviewDevice(device) {
        const frame = document.getElementById('deviceFrame');
        const notch = document.getElementById('deviceNotch');
        const btnMobile = document.getElementById('btnMobilePreview');
        const btnDesktop = document.getElementById('btnDesktopPreview');

        if (!frame || !btnMobile || !btnDesktop) return;

        if (device === 'mobile') {
            frame.style.width = '360px';
            frame.style.height = '720px';
            frame.style.borderRadius = '46px';
            if (notch) notch.style.display = 'block';
            
            btnMobile.className = 'btn-action-primary';
            btnDesktop.className = 'btn-action-secondary';
        } else {
            frame.style.width = '90vw';
            frame.style.maxWidth = '1000px';
            frame.style.height = '80vh';
            frame.style.borderRadius = '16px';
            if (notch) notch.style.display = 'none';

            btnDesktop.className = 'btn-action-primary';
            btnMobile.className = 'btn-action-secondary';
        }
    }

    function openPreviewModal(url) {
        const modal = document.getElementById('previewModalOverlay');
        const iframe = document.getElementById('phonePreviewIframe');
        iframe.src = url;
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
        setPreviewDevice('mobile');
    }

    function closePreviewModal() {
        const modal = document.getElementById('previewModalOverlay');
        const iframe = document.getElementById('phonePreviewIframe');
        modal.classList.remove('active');
        iframe.src = '';
        document.body.style.overflow = 'auto';
    }

    window.onclick = function(event) {
        if (event.target.id === 'previewModalOverlay') {
            closePreviewModal();
        }
        if (event.target.id === 'newMicrositeModalOverlay') {
            closeNewMicrositeModal();
        }
    }
</script>
@endpush
