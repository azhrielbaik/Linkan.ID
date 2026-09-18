    <!-- COMBINED HEADER & MODE SWITCH -->
    <div class="section-header microsite-main-header">
        @include('admin_seller.features.microsites.partials._editor-header')
    </div>

        <!-- EDITOR VIEW MODE -->

        <div class="editor-layout">
            <!-- LEFT PANEL: BLOCK MANAGEMENT -->
            <div class="editor-left-panel">

                {{-- PANEL ELEMEN: konten default (tambah & edit elemen) --}}
                <div id="editorPanelElemen" role="tabpanel" aria-labelledby="tab-btn-elemen">

                <!-- ACTION: TAMBAH ELEMENT BUTTON & SLIDE-DOWN PANEL -->
                <div class="add-element-wrapper">
                    <!-- BUTTON -->
                    <button type="button" id="btnToggleAddElement" class="btn-add-element" >
                        <i id="btnToggleIcon" class="fas fa-plus-circle" style="font-size: 18px; transition: transform 0.3s ease;"></i>
                        <span id="btnToggleText">{{ __('admin.add_element') }}</span>
                    </button>

                    <!-- INLINE SLIDE-DOWN PANEL -->
                    <div id="addElementPanel" class="add-element-panel">
                        <div class="add-element-panel-inner">
                            <!-- PANEL CONTENT AREA (COMPONENT CARDS SELECTOR) -->
                            <div id="addElementPanelBody" class="add-element-grid">
                                
                                <!-- Profile Block is default and cannot be added/removed -->
                                
                                <!-- Element Option 1: Gambar -->
                                <div class="element-option-card" id="btnOptionGambar">
                                    <div class="option-card-title">{{ __('microsite.image_title') }}</div>
                                    <div class="option-card-desc">{{ __('microsite.image_desc') }}</div>
                                </div>

                                <!-- Element Option 2: Pembatas -->
                                <div class="element-option-card" id="btnOptionDivider">
                                    <div class="option-card-title">{{ __('microsite.divider_title') }}</div>
                                    <div class="option-card-desc">{{ __('microsite.divider_desc') }}</div>
                                </div>

                                <!-- Element Option 3: Teks -->
                                <div class="element-option-card" id="btnOptionText">
                                    <div class="option-card-title">{{ __('microsite.text_title') }}</div>
                                    <div class="option-card-desc">{{ __('microsite.text_desc') }}</div>
                                </div>

                                <!-- Element Option 4: Video -->
                                <div class="element-option-card" id="btnOptionVideo">
                                    <div class="option-card-title">{{ __('microsite.video_title') }}</div>
                                    <div class="option-card-desc">{{ __('microsite.video_desc') }}</div>
                                </div>

                                <!-- Element Option 5: Social Media -->
                                <div class="element-option-card" id="btnOptionSocialMedia">
                                    <div class="option-card-title">{{ __('microsite.social_title') }}</div>
                                    <div class="option-card-desc">{{ __('microsite.social_desc') }}</div>
                                </div>

                                <!-- Element Option 6: Digital Product -->
                                <div class="element-option-card" id="btnOptionDigitalProduct">
                                    <div class="option-card-title">{{ __('microsite.dp_title') }}</div>
                                    <div class="option-card-desc">{{ __('microsite.dp_desc') }}</div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- EDIT ELEMENT SECTION CONTAINER (DRAGGABLE BLOCKS) -->
                <div id="digitalProductsSection" class="digital-products-section">
                    <div id="elementSectionHeader" class="edit-element-header">
                        <h3 class="element-header-title">
                            <i class="fas fa-layer-group text-brand-orange"></i> {{ __('microsite.edit_element') }}
                        </h3>
                        <span class="element-header-subtitle">
                            <i class="fas fa-arrows-alt-v"></i> {{ __('microsite.drag_drop') }}
                        </span>
                    </div>

                    <!-- 1. PROFILE BLOCK CARD (STATIC, NOT DRAGGABLE, PINNED AT TOP) -->
                    @include('admin_seller.features.microsites.blocks._profile-block')

                        <!-- DRAGGABLE ELEMENT BLOCKS LIST CONTAINER -->
                        <div id="elementBlocksList" style="display: flex; flex-direction: column;">

                        @if(isset($allElements) && $allElements->count() > 0)
                            @foreach($allElements as $element)
                                @switch($element->type)
                                    @case('image')
                                        @include('admin_seller.features.microsites.blocks._image-block', ['imageEl' => $element])
                                        @break
                                    @case('divider')
                                        @include('admin_seller.features.microsites.blocks._divider-block', ['dividerEl' => $element])
                                        @break
                                    @case('text')
                                        @include('admin_seller.features.microsites.blocks._text-block', ['textEl' => $element])
                                        @break
                                    @case('video')
                                        @include('admin_seller.features.microsites.blocks._video-block', ['videoEl' => $element])
                                        @break
                                    @case('social')
                                        @include('admin_seller.features.microsites.blocks._social-block', ['socialEl' => $element])
                                        @break
                                    @case('digitalproduct')
                                        @include('admin_seller.features.microsites.blocks._digital-product-block', ['digitalProduct' => $element])
                                        @break
                                @endswitch
                            @endforeach
                        @endif

                    </div> <!-- Closes elementBlocksList -->
                </div> <!-- Closes digitalProductsSection -->

                </div> {{-- Closes #editorPanelElemen --}}

                {{-- ============================================================
                     PANEL PENGATURAN: Background, Layout Profil, Bentuk Blok
                     ============================================================ --}}
                <div id="editorPanelPengaturan" role="tabpanel" aria-labelledby="tab-btn-pengaturan" hidden>

                    @include('admin_seller.features.microsites.settings._background')
                    @include('admin_seller.features.microsites.settings._layout')
                    @include('admin_seller.features.microsites.settings._shape')
                </div> {{-- Closes #editorPanelPengaturan --}}

                {{-- ============================================================
                     PANEL DIGITAL PRODUCT WIZARD (Hidden by default)
                     ============================================================ --}}
                @push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/digital-product-wizard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/microsite-editor-blocks.css') }}">
@endpush

                @include('admin_seller.features.microsites.partials._dp-wizard')

            </div> <!-- Closes editor-left-panel -->


            <!-- RIGHT PANEL: STICKY PHONE PREVIEW -->
            <x-microsite.phone-preview :appearance="$appearance" :image-elements="$imageElements ?? null" :divider-elements="$dividerElements ?? null" :text-elements="$textElements ?? null" :video-elements="$videoElements ?? null" :social-media-elements="$socialMediaElements ?? null" :digital-products="$digitalProducts ?? null" />
        </div>

@push('scripts')
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <script src="{{ asset('js/admin/digital-product-wizard.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Perbaiki SEO Lighthouse: tambahkan href pada link aksi Quill editor agar crawlable
            const fixQuillLinks = () => {
                document.querySelectorAll('a.ql-action:not([href]), a.ql-remove:not([href])').forEach(el => {
                    el.setAttribute('href', 'javascript:void(0)');
                });
            };
            
            setTimeout(fixQuillLinks, 1000);
            
            const observer = new MutationObserver((mutations) => {
                mutations.forEach(m => {
                    if (m.addedNodes.length > 0) fixQuillLinks();
                });
            });
            observer.observe(document.body, { childList: true, subtree: true });

            // Bind events for element panel
            const btnToggleAddElement = document.getElementById('btnToggleAddElement');
            if (btnToggleAddElement) {
                btnToggleAddElement.addEventListener('click', function() {
                    if (window.MicrositeBuilder) window.MicrositeBuilder.toggleAddElementPanel();
                });
            }

            const btnOptionGambar = document.getElementById('btnOptionGambar');
            if (btnOptionGambar) {
                btnOptionGambar.addEventListener('click', function() {
                    if (window.MicrositeBuilder) window.MicrositeBuilder.addGambarElement();
                });
            }

            const btnOptionDivider = document.getElementById('btnOptionDivider');
            if (btnOptionDivider) {
                btnOptionDivider.addEventListener('click', function() {
                    if (window.MicrositeBuilder) window.MicrositeBuilder.addDividerElement();
                });
            }

            const btnOptionText = document.getElementById('btnOptionText');
            if (btnOptionText) {
                btnOptionText.addEventListener('click', function() {
                    if (window.MicrositeBuilder) window.MicrositeBuilder.addTextElement();
                });
            }

            const btnOptionVideo = document.getElementById('btnOptionVideo');
            if (btnOptionVideo) {
                btnOptionVideo.addEventListener('click', function() {
                    if (window.MicrositeBuilder) window.MicrositeBuilder.addVideoElement();
                });
            }

            const btnOptionSocialMedia = document.getElementById('btnOptionSocialMedia');
            if (btnOptionSocialMedia) {
                btnOptionSocialMedia.addEventListener('click', function() {
                    if (window.MicrositeBuilder) window.MicrositeBuilder.addSocialMediaElement();
                });
            }

            const btnOptionDigitalProduct = document.getElementById('btnOptionDigitalProduct');
            if (btnOptionDigitalProduct) {
                btnOptionDigitalProduct.addEventListener('click', function() {
                    if (window.MicrositeBuilder) window.MicrositeBuilder.openDigitalProductWizard();
                });
            }
        });
    </script>
@endpush
