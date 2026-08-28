    <!-- COMBINED HEADER & MODE SWITCH -->
    <div class="section-header microsite-main-header">
        @include('pages.mylinkan.partials._editor_header')
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
                    <button type="button" id="btnToggleAddElement" class="btn-add-element" onclick="toggleAddElementPanel()">
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
                                <div class="element-option-card" onclick="addGambarElement()">
                                    <div class="option-card-title">Gambar</div>
                                    <div class="option-card-desc">Upload gambar & link</div>
                                </div>

                                <!-- Element Option 2: Pembatas -->
                                <div class="element-option-card" onclick="addDividerElement()">
                                    <div class="option-card-title">Pembatas</div>
                                    <div class="option-card-desc">Garis atau spasi pemisah</div>
                                </div>

                                <!-- Element Option 3: Teks -->
                                <div class="element-option-card" onclick="addTextElement()">
                                    <div class="option-card-title">Teks</div>
                                    <div class="option-card-desc">Tambahkan paragraf</div>
                                </div>

                                <!-- Element Option 4: Video -->
                                <div class="element-option-card" onclick="addVideoElement()">
                                    <div class="option-card-title">Embed Video</div>
                                    <div class="option-card-desc">Embed video Youtube</div>
                                </div>

                                <!-- Element Option 5: Social Media -->
                                <div class="element-option-card" onclick="addSocialMediaElement()">
                                    <div class="option-card-title">Media Sosial</div>
                                    <div class="option-card-desc">Tautkan akun sosial media</div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- EDIT ELEMENT SECTION CONTAINER (DRAGGABLE BLOCKS) -->
                <div id="digitalProductsSection" class="digital-products-section">
                    <div id="elementSectionHeader" class="edit-element-header">
                        <h3 class="element-header-title">
                            <i class="fas fa-layer-group text-brand-orange"></i> Edit Element
                        </h3>
                        <span class="element-header-subtitle">
                            <i class="fas fa-arrows-alt-v"></i> Drag & Drop untuk mengurutkan
                        </span>
                    </div>

                    <!-- 1. PROFILE BLOCK CARD (STATIC, NOT DRAGGABLE, PINNED AT TOP) -->
                    @include('pages.mylinkan.blocks._profile_block')

                        <!-- DRAGGABLE ELEMENT BLOCKS LIST CONTAINER -->
                        <div id="elementBlocksList" style="display: flex; flex-direction: column;">

                        @if(isset($imageElements))
                            @foreach($imageElements as $imageEl)
                                @include('pages.mylinkan.blocks._image_block')
                            @endforeach
                        @endif

                        @if(isset($dividerElements))
                            @foreach($dividerElements as $dividerEl)
                                @include('pages.mylinkan.blocks._divider_block')
                            @endforeach
                        @endif

                        @if(isset($textElements) && $textElements->count() > 0)
                            @foreach($textElements as $textEl)
                                @include('pages.mylinkan.blocks._text_block')
                            @endforeach
                        @endif

                        @if(isset($videoElements) && $videoElements->count() > 0)
                            @foreach($videoElements as $videoEl)
                                @include('pages.mylinkan.blocks._video_block')
                            @endforeach
                        @endif

                        @if(isset($socialMediaElements) && $socialMediaElements->count() > 0)
                            @foreach($socialMediaElements as $socialEl)
                                @include('pages.mylinkan.blocks._social_block')
                            @endforeach
                        @endif

                    </div> <!-- Closes elementBlocksList -->
                </div> <!-- Closes digitalProductsSection -->

                </div> {{-- Closes #editorPanelElemen --}}

                {{-- ============================================================
                     PANEL PENGATURAN: Background, Layout Profil, Bentuk Blok
                     ============================================================ --}}
                <div id="editorPanelPengaturan" role="tabpanel" aria-labelledby="tab-btn-pengaturan" hidden>

                    @include('pages.mylinkan.settings._background')
                    @include('pages.mylinkan.settings._layout')
                    @include('pages.mylinkan.settings._shape')
                </div> {{-- Closes #editorPanelPengaturan --}}

            </div> <!-- Closes editor-left-panel -->


            <!-- RIGHT PANEL: STICKY PHONE PREVIEW -->
            <x-microsite.phone-preview :appearance="$appearance" :image-elements="$imageElements ?? null" :divider-elements="$dividerElements ?? null" :text-elements="$textElements ?? null" :video-elements="$videoElements ?? null" :social-media-elements="$socialMediaElements ?? null" />
        </div>
