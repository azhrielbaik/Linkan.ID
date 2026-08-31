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

                                <!-- Element Option 6: Digital Product -->
                                <div class="element-option-card" onclick="openDigitalProductWizard()">
                                    <div class="option-card-title">Digital Product</div>
                                    <div class="option-card-desc">Jual produk digital</div>
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

                        @if(isset($digitalProducts) && $digitalProducts->count() > 0)
                            @foreach($digitalProducts as $digitalProduct)
                                @include('pages.mylinkan.blocks._digital_product_block')
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

                {{-- ============================================================
                     PANEL DIGITAL PRODUCT WIZARD (Hidden by default)
                     ============================================================ --}}
                <style>
                    /* Digital Product Wizard Styles (Matches image reference) */
                    .dp-wizard-container {
                        background: #ffffff;
                        border-radius: 8px;
                        padding: 30px;
                        font-family: 'Plus Jakarta Sans', sans-serif;
                    }
                    
                    /* Stepper UI */
                    .dp-stepper-wrapper {
                        display: flex;
                        align-items: center;
                        justify-content: space-between;
                        margin-bottom: 30px;
                        padding: 0 10px;
                    }
                    .dp-stepper-item {
                        display: flex;
                        align-items: center;
                        gap: 10px;
                        color: #9ca3af;
                        font-weight: 600;
                        font-size: 14px;
                        transition: color 0.2s;
                    }
                    .dp-stepper-item.active {
                        color: #F97316;
                    }
                    .dp-stepper-item.completed {
                        color: #111827;
                    }
                    .dp-stepper-circle {
                        width: 24px;
                        height: 24px;
                        border-radius: 50%;
                        background: #e5e7eb;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 12px;
                        color: #6b7280;
                        font-weight: 700;
                        transition: all 0.2s;
                    }
                    .dp-stepper-item.active .dp-stepper-circle {
                        background: #F97316;
                        color: #fff;
                    }
                    .dp-stepper-item.completed .dp-stepper-circle {
                        background: #d9f99d; 
                        color: #111827;
                    }
                    .dp-stepper-line {
                        flex: 1;
                        height: 1px;
                        background: #e5e7eb;
                        margin: 0 15px;
                    }

                    /* Form Rows */
                    .dp-form-row-box {
                        display: grid;
                        grid-template-columns: 150px 1fr;
                        align-items: center;
                        gap: 15px;
                        border: 1px solid #e5e7eb;
                        border-radius: 6px;
                        padding: 14px 18px;
                        margin-bottom: 16px;
                        background: #fff;
                        transition: all 0.2s ease;
                    }
                    .dp-form-row-box:focus-within {
                        border-color: #F97316;
                        box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
                    }
                    .dp-row-label {
                        font-weight: 700;
                        color: #111827;
                        font-size: 14px;
                        margin: 0;
                    }
                    .dp-row-input {
                        border: none;
                        outline: none;
                        width: 100%;
                        font-size: 14px;
                        color: #111827;
                        background: transparent;
                        font-family: inherit;
                        padding: 0;
                    }
                    .dp-row-input::placeholder {
                        color: #9ca3af;
                    }
                    
                    /* Upload Box */
                    .dp-upload-box {
                        border: 1px dashed #d1d5db;
                        border-radius: 4px;
                        padding: 40px 20px;
                        text-align: center;
                        cursor: pointer;
                        background: #fff;
                        transition: all 0.2s ease;
                        margin-bottom: 24px;
                    }
                    .dp-upload-box:hover {
                        border-color: #F97316;
                        background: #fff8f3;
                    }
                    .dp-upload-box i {
                        font-size: 28px;
                        color: #F97316;
                        margin-bottom: 12px;
                    }
                    .dp-upload-box h4 {
                        margin: 0 0 5px 0;
                        font-size: 14px;
                        font-weight: 700;
                        color: #111827;
                    }
                    .dp-upload-box p {
                        margin: 0;
                        font-size: 13px;
                        color: #6b7280;
                    }

                    /* Radio Cards */
                    .dp-platform-cards-grid {
                        display: grid;
                        grid-template-columns: 1fr 1fr;
                        gap: 16px;
                        margin-bottom: 24px;
                    }
                    .dp-platform-card {
                        border: 1px solid #e5e7eb;
                        border-radius: 4px;
                        padding: 20px;
                        cursor: pointer;
                        transition: all 0.2s ease;
                        background: #fff;
                        display: block;
                        height: 100%;
                    }
                    .dp-platform-card:hover {
                        border-color: #F97316;
                    }
                    .dp-platform-card-wrapper input[type="radio"] {
                        display: none;
                    }
                    .dp-platform-card-wrapper input[type="radio"]:checked ~ .dp-platform-card .dp-platform-radio-circle {
                        border-color: #F97316;
                        background: #F97316;
                        box-shadow: inset 0 0 0 3px #fff;
                    }
                    .dp-platform-card-wrapper.active .dp-platform-card {
                        border-color: #F97316;
                    }
                    .dp-platform-card-header {
                        display: flex;
                        justify-content: space-between;
                        align-items: flex-start;
                        margin-bottom: 12px;
                    }
                    .dp-platform-card-title {
                        font-weight: 700;
                        font-size: 15px;
                        color: #111827;
                    }
                    .dp-platform-radio-circle {
                        width: 18px;
                        height: 18px;
                        border-radius: 50%;
                        border: 1px solid #d1d5db;
                        transition: all 0.2s ease;
                        margin-left: 10px;
                        flex-shrink: 0;
                    }
                    .dp-platform-card-desc {
                        font-size: 13px;
                        color: #6b7280;
                        margin: 0 0 15px 0;
                        line-height: 1.4;
                    }

                    /* Toggle Switch */
                    .dp-toggle-switch {
                        position: relative;
                        display: inline-block;
                        width: 44px;
                        height: 24px;
                    }
                    .dp-toggle-switch input { opacity: 0; width: 0; height: 0; }
                    .dp-toggle-slider {
                        position: absolute;
                        cursor: pointer;
                        top: 0; left: 0; right: 0; bottom: 0;
                        background-color: #e5e7eb;
                        transition: .4s;
                        border-radius: 24px;
                    }
                    .dp-toggle-slider:before {
                        position: absolute; content: "";
                        height: 18px; width: 18px;
                        left: 3px; bottom: 3px;
                        background-color: white;
                        transition: .4s; border-radius: 50%;
                    }
                    .dp-toggle-switch input:checked + .dp-toggle-slider { background-color: #F97316; }
                    .dp-toggle-switch input:checked + .dp-toggle-slider:before { transform: translateX(20px); }

                    /* Footer Buttons */
                    .dp-wizard-footer {
                        display: flex;
                        justify-content: space-between;
                        margin-top: 30px;
                        padding-top: 20px;
                    }
                    .dp-btn-prev {
                        padding: 10px 20px;
                        border: 1px solid #e5e7eb;
                        border-radius: 4px;
                        background: white;
                        cursor: pointer;
                        font-weight: 600;
                        color: #374151;
                        font-size: 14px;
                    }
                    .dp-btn-next {
                        padding: 10px 24px;
                        border: none;
                        border-radius: 4px;
                        background: #F97316;
                        color: white;
                        cursor: pointer;
                        font-weight: 600;
                        font-size: 14px;
                    }

                    /* Mobile Responsiveness for Wizard */
                    @media (max-width: 768px) {
                        .dp-wizard-container {
                            padding: 20px 15px;
                        }
                        .dp-stepper-wrapper {
                            padding: 0;
                            margin-bottom: 25px;
                        }
                        .dp-stepper-item {
                            font-size: 12px;
                            gap: 6px;
                        }
                        /* Hide inactive labels on mobile to prevent cut-off */
                        .dp-stepper-item:not(.active) .dp-stepper-label {
                            display: none;
                        }
                        .dp-stepper-line {
                            margin: 0 8px;
                        }
                    }
                </style>

                <div id="digitalProductWizardPanel" style="display: none;">
                    <div class="dp-wizard-container">
                        <!-- Stepper UI -->
                        <div class="dp-stepper-wrapper">
                            <div class="dp-stepper-item active" id="dp-step-indicator-1">
                                <div class="dp-stepper-circle">
                                    <i class="fas fa-check" id="dp-step-icon-1" style="display:none;"></i>
                                    <span id="dp-step-num-1">1</span>
                                </div>
                                <div class="dp-stepper-label">Detail Produk</div>
                            </div>
                            <div class="dp-stepper-line"></div>
                            <div class="dp-stepper-item" id="dp-step-indicator-2">
                                <div class="dp-stepper-circle">
                                    <i class="fas fa-check" id="dp-step-icon-2" style="display:none;"></i>
                                    <span id="dp-step-num-2">2</span>
                                </div>
                                <div class="dp-stepper-label">Pricing</div>
                            </div>
                            <div class="dp-stepper-line"></div>
                            <div class="dp-stepper-item" id="dp-step-indicator-3">
                                <div class="dp-stepper-circle">
                                    <i class="fas fa-check" id="dp-step-icon-3" style="display:none;"></i>
                                    <span id="dp-step-num-3">3</span>
                                </div>
                                <div class="dp-stepper-label">Penayangan</div>
                            </div>
                        </div>

                        <!-- Wizard Body -->
                        <div class="wizard-body">
                            
                            <!-- Step 1: Detail Produk -->
                            <div class="wizard-step" id="dp-step-1">
                                
                                <div class="dp-form-row-box">
                                    <span class="dp-row-label">Nama Produk:</span>
                                    <input type="text" id="dpTitle" class="dp-row-input" placeholder="Misal: Template Undangan..." oninput="updateDpTitle(this.value)" required>
                                </div>

                                <div class="dp-form-row-box" style="display: block;">
                                    <span class="dp-row-label" style="display: block; margin-bottom: 10px;">Deskripsi:</span>
                                    <div style="width: 100%;">
                                        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
                                        <style>
                                            .ql-toolbar.ql-snow { border: none; border-bottom: 1px solid #e5e7eb; padding: 5px 0; }
                                            .ql-container.ql-snow { border: none; font-family: inherit; font-size: 14px; }
                                            .ql-editor { min-height: 100px; padding: 10px 0; }
                                        </style>
                                        <div id="dpDescriptionEditor"></div>
                                    </div>
                                </div>

                                <!-- Media Upload Box -->
                                <div style="font-size: 11px; font-weight: 700; color: #9ca3af; text-transform: uppercase; margin-bottom: 12px; margin-top: 24px; letter-spacing: 0.05em;">Media Produk (Maks. 5 File)</div>
                                <div class="dp-upload-box" onclick="document.getElementById('dpFiles').click()">
                                    <input type="file" id="dpFiles" accept="image/jpeg,image/png,image/gif,video/mp4" multiple style="display: none;" onchange="handleDpFiles(this)">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <h4>Click to upload or drag and drop</h4>
                                    <p>Format JPG, PNG, GIF, MP4 (Video)</p>
                                </div>
                                <div id="dpFilesError" style="color: #ef4444; font-size: 13px; margin-top: -15px; margin-bottom: 15px; display: none;"></div>
                                <div id="dpFilesPreview" style="display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 24px;"></div>

                                <!-- Deliverable Selection -->
                                <div style="font-size: 11px; font-weight: 700; color: #9ca3af; text-transform: uppercase; margin-bottom: 12px; letter-spacing: 0.05em;">Akses Produk (Deliverable)</div>
                                <div class="dp-platform-cards-grid">
                                    <label class="dp-platform-card-wrapper active" id="dp-deliv-upload-wrapper">
                                        <input type="radio" name="dpDeliverableType" value="upload" onchange="changeDpDeliverableType(this.value)" checked>
                                        <div class="dp-platform-card">
                                            <div class="dp-platform-card-content">
                                                <div class="dp-platform-card-header">
                                                    <span class="dp-platform-card-title">Upload File</span>
                                                    <span class="dp-platform-radio-circle"></span>
                                                </div>
                                                <p class="dp-platform-card-desc">Unggah file digital Anda secara langsung ke server kami.</p>
                                            </div>
                                        </div>
                                    </label>

                                    <label class="dp-platform-card-wrapper" id="dp-deliv-gdrive-wrapper">
                                        <input type="radio" name="dpDeliverableType" value="gdrive" onchange="changeDpDeliverableType(this.value)">
                                        <div class="dp-platform-card">
                                            <div class="dp-platform-card-content">
                                                <div class="dp-platform-card-header">
                                                    <span class="dp-platform-card-title">Google Drive</span>
                                                    <span class="dp-platform-radio-circle"></span>
                                                </div>
                                                <p class="dp-platform-card-desc">Berikan akses lewat tautan Google Drive.</p>
                                            </div>
                                        </div>
                                    </label>

                                    <label class="dp-platform-card-wrapper" id="dp-deliv-external-wrapper">
                                        <input type="radio" name="dpDeliverableType" value="external" onchange="changeDpDeliverableType(this.value)">
                                        <div class="dp-platform-card">
                                            <div class="dp-platform-card-content">
                                                <div class="dp-platform-card-header">
                                                    <span class="dp-platform-card-title">Link Eksternal</span>
                                                    <span class="dp-platform-radio-circle"></span>
                                                </div>
                                                <p class="dp-platform-card-desc">Tautkan file dari platform atau website eksternal lainnya.</p>
                                            </div>
                                        </div>
                                    </label>
                                </div>

                                <!-- Upload Section -->
                                <div id="dpDeliverableUploadSection">
                                    <div class="dp-form-row-box" onclick="document.getElementById('dpDeliverableFile').click()" style="cursor: pointer; justify-content: space-between;">
                                        <span class="dp-row-label" style="min-width: auto; margin-right: 0;">File Produk:</span>
                                        <div style="flex: 1; text-align: right; color: #9ca3af; font-size: 14px;">
                                            <i class="fas fa-paperclip" style="margin-right: 5px;"></i>
                                            <span>Pilih file...</span>
                                        </div>
                                        <input type="file" id="dpDeliverableFile" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" style="display: none;" onchange="handleDpDeliverableFile(this)">
                                    </div>
                                    <div id="dpDeliverableFilePreview" style="margin-top: 10px; display: none; padding: 12px; border: 1px solid #e2e8f0; border-radius: 6px; background: #fff; align-items: center; justify-content: space-between; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                                        <div style="display: flex; align-items: center; gap: 10px; overflow: hidden;">
                                            <i class="fas fa-file-alt" style="font-size: 20px; color: #F97316;"></i>
                                            <span id="dpDeliverableFileName" style="font-size: 14px; color: #334155; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 200px;">filename.pdf</span>
                                        </div>
                                        <button type="button" onclick="removeDpDeliverableFile()" style="background: none; border: none; color: #ef4444; cursor: pointer; display: flex; align-items: center; justify-content: center; width: 24px; height: 24px; border-radius: 50%;"><i class="fas fa-trash-alt"></i></button>
                                    </div>
                                </div>

                                <!-- URL Section -->
                                <div id="dpDeliverableUrlSection" style="display: none;">
                                    <div class="dp-form-row-box">
                                        <span class="dp-row-label">URL Akses:</span>
                                        <input type="url" id="dpDeliverableUrl" class="dp-row-input" placeholder="https://..." oninput="updateDpDeliverableUrl(this.value)">
                                    </div>
                                </div>

                            </div>

                            <!-- Step 2: Pricing -->
                            <div class="wizard-step" id="dp-step-2" style="display: none;">
                                
                                <div style="font-size: 11px; font-weight: 700; color: #9ca3af; text-transform: uppercase; margin-bottom: 12px; letter-spacing: 0.05em;">Tipe Harga</div>
                                <div class="dp-platform-cards-grid">
                                    <label class="dp-platform-card-wrapper active" id="dp-price-fixed-wrapper">
                                        <input type="radio" name="dpPriceType" value="fixed" onchange="changeDpPriceType(this.value)" checked>
                                        <div class="dp-platform-card">
                                            <div class="dp-platform-card-content">
                                                <div class="dp-platform-card-header">
                                                    <span class="dp-platform-card-title">Harga Tetap</span>
                                                    <span class="dp-platform-radio-circle"></span>
                                                </div>
                                                <p class="dp-platform-card-desc">Tentukan satu harga pasti untuk produk digital Anda.</p>
                                            </div>
                                        </div>
                                    </label>

                                    <label class="dp-platform-card-wrapper" id="dp-price-pwyw-wrapper">
                                        <input type="radio" name="dpPriceType" value="pwyw" onchange="changeDpPriceType(this.value)">
                                        <div class="dp-platform-card">
                                            <div class="dp-platform-card-content">
                                                <div class="dp-platform-card-header">
                                                    <span class="dp-platform-card-title">Pay What You Want</span>
                                                    <span class="dp-platform-radio-circle"></span>
                                                </div>
                                                <p class="dp-platform-card-desc">Izinkan pembeli menentukan harga sendiri dengan batas minimal.</p>
                                            </div>
                                        </div>
                                    </label>
                                </div>

                                <!-- Fixed Price Input -->
                                <div id="dpFixedPriceSection">
                                    <div class="dp-form-row-box">
                                        <span class="dp-row-label">Harga (Rp):</span>
                                        <input type="number" id="dpFixedPrice" class="dp-row-input" value="0" min="0" oninput="updateDpPriceField('fixed', this.value)">
                                    </div>
                                </div>

                                <!-- PWYW Input -->
                                <div id="dpPwywSection" style="display: none;">
                                    <div class="dp-form-row-box">
                                        <span class="dp-row-label">Min. Harga (Rp):</span>
                                        <input type="number" id="dpMinPrice" class="dp-row-input" value="0" min="0" oninput="updateDpPriceField('min', this.value)">
                                    </div>
                                    <div class="dp-form-row-box">
                                        <span class="dp-row-label">Maks. Harga (Rp):</span>
                                        <input type="number" id="dpMaxPrice" class="dp-row-input" placeholder="Tak Terbatas" min="0" oninput="updateDpPriceField('max', this.value)">
                                    </div>
                                    <div style="font-size: 12px; color: #9ca3af; margin-top: -5px; margin-bottom: 15px;">Kosongkan harga maksimal jika tidak ada batasan.</div>
                                </div>

                                <div style="font-size: 11px; font-weight: 700; color: #9ca3af; text-transform: uppercase; margin-bottom: 12px; margin-top: 24px; letter-spacing: 0.05em;">Batas Pembelian</div>
                                <div class="dp-form-row-box">
                                    <span class="dp-row-label">Minimal Qty:</span>
                                    <input type="number" id="dpMinQty" class="dp-row-input" value="1" min="1" oninput="updateDpQtyField('min', this.value)">
                                </div>
                                <div style="font-size: 11px; font-weight: 700; color: #9ca3af; text-transform: uppercase; margin-bottom: 12px; margin-top: 24px; letter-spacing: 0.05em;">Tipe Maksimal Qty</div>
                                <div class="dp-platform-cards-grid">
                                    <label class="dp-platform-card-wrapper active" id="dp-qty-unlimited-wrapper">
                                        <input type="radio" name="dpQtyLimitType" value="unlimited" onchange="changeDpQtyLimitType(this.value)" checked>
                                        <div class="dp-platform-card">
                                            <div class="dp-platform-card-content">
                                                <div class="dp-platform-card-header">
                                                    <span class="dp-platform-card-title">Tak Terbatas</span>
                                                    <span class="dp-platform-radio-circle"></span>
                                                </div>
                                                <p class="dp-platform-card-desc">Pembeli dapat membeli produk tanpa batasan jumlah.</p>
                                            </div>
                                        </div>
                                    </label>

                                    <label class="dp-platform-card-wrapper" id="dp-qty-limited-wrapper">
                                        <input type="radio" name="dpQtyLimitType" value="limited" onchange="changeDpQtyLimitType(this.value)">
                                        <div class="dp-platform-card">
                                            <div class="dp-platform-card-content">
                                                <div class="dp-platform-card-header">
                                                    <span class="dp-platform-card-title">Terbatas</span>
                                                    <span class="dp-platform-radio-circle"></span>
                                                </div>
                                                <p class="dp-platform-card-desc">Tentukan batas maksimal jumlah yang bisa dibeli.</p>
                                            </div>
                                        </div>
                                    </label>
                                </div>

                                <div id="dpMaxQtySection" style="display: none;">
                                    <div class="dp-form-row-box">
                                        <span class="dp-row-label">Maksimal Qty:</span>
                                        <input type="number" id="dpMaxQty" class="dp-row-input" placeholder="Misal: 10" min="1" oninput="updateDpQtyField('max', this.value)">
                                    </div>
                                </div>
                            </div>

                            <!-- Step 3: Waktu Penayangan -->
                            <div class="wizard-step" id="dp-step-3" style="display: none;">
                                
                                <div style="display: flex; align-items: center; justify-content: space-between; border: 1px solid #e5e7eb; border-radius: 6px; padding: 14px 18px; margin-bottom: 16px; background: #fff; transition: all 0.2s ease;">
                                    <div>
                                        <span class="dp-row-label" style="display: block; margin-bottom: 4px;">Aktifkan Jadwal</span>
                                        <span style="font-size: 13px; color: #6b7280;">Batasi waktu rilis produk</span>
                                    </div>
                                    <label class="dp-toggle-switch">
                                        <input type="checkbox" id="dpEnableSchedule" onchange="toggleDpSchedule(this.checked)">
                                        <span class="dp-toggle-slider"></span>
                                    </label>
                                </div>

                                <div id="dpScheduleSection" style="display: none; padding-top: 15px;">
                                    <div class="dp-form-row-box">
                                        <span class="dp-row-label">Waktu Mulai:</span>
                                        <input type="datetime-local" id="dpStartTime" class="dp-row-input" onchange="updateDpScheduleField('start', this.value)">
                                    </div>
                                    <div class="dp-form-row-box">
                                        <span class="dp-row-label">Waktu Akhir:</span>
                                        <input type="datetime-local" id="dpEndTime" class="dp-row-input" onchange="updateDpScheduleField('end', this.value)">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Wizard Footer (Navigation) -->
                        <div class="dp-wizard-footer">
                            <button type="button" class="dp-btn-prev" onclick="cancelDigitalProductWizard()">Batal</button>
                            <div style="display: flex; gap: 10px;">
                                <button type="button" class="dp-btn-prev" id="btn-dp-prev" onclick="prevDigitalProductStep()" style="display: none;">Kembali</button>
                                <button type="button" class="dp-btn-next" id="btn-dp-next" onclick="nextDigitalProductStep()">Lanjut <i class="fas fa-arrow-right" style="margin-left: 8px;"></i></button>
                            </div>
                        </div>
                    </div>
                </div> {{-- Closes #digitalProductWizardPanel --}}

            </div> <!-- Closes editor-left-panel -->


            <!-- RIGHT PANEL: STICKY PHONE PREVIEW -->
            <x-microsite.phone-preview :appearance="$appearance" :image-elements="$imageElements ?? null" :divider-elements="$dividerElements ?? null" :text-elements="$textElements ?? null" :video-elements="$videoElements ?? null" :social-media-elements="$socialMediaElements ?? null" :digital-products="$digitalProducts ?? null" />
        </div>

<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
    // State management for Digital Product Wizard
    let currentDpStep = 1;
    const maxDpStep = 3;

    // Form Local State
    let dpFormState = {
        element_id: null,
        title: '',
        description: '',
        files: [], // Media
        deliverableType: 'upload', // 'upload', 'gdrive', 'external'
        deliverableFile: null,
        deliverableUrl: '',
        priceType: 'fixed', // 'fixed', 'pwyw'
        priceFixed: 0,
        priceMin: 0,
        priceMax: '',
        qtyMin: 1,
        qtyMax: '',
        isScheduled: false,
        startTime: '',
        endTime: ''
    };

    // Initialize Quill Editor
    let dpQuill;
    document.addEventListener("DOMContentLoaded", function() {
        var toolbarOptions = [
            [{ 'font': [] }, { 'size': ['small', false, 'large', 'huge'] }],
            ['bold', 'italic', 'underline'],
            [{ 'background': [] }], // Highlight Color
            [{ 'list': 'bullet' }, { 'list': 'ordered' }],
            [{ 'align': [] }],
            ['link']
        ];

        dpQuill = new Quill('#dpDescriptionEditor', {
            theme: 'snow',
            modules: {
                toolbar: toolbarOptions
            },
            placeholder: 'Tuliskan deskripsi lengkap produk digital Anda...'
        });

        // Listen for changes and update state
        dpQuill.on('text-change', function() {
            // Get HTML content. If empty (just <p><br></p>), save as empty string
            let html = dpQuill.root.innerHTML;
            if (html === '<p><br></p>') html = '';
            dpFormState.description = html;
        });
    });

    function openDigitalProductWizard() {
        // Hide add element panel
        document.getElementById('addElementPanel').classList.remove('show');
        const btnToggleIcon = document.getElementById('btnToggleIcon');
        if(btnToggleIcon) {
            btnToggleIcon.style.transform = 'rotate(0deg)';
        }

        // Hide main editor panels
        document.getElementById('editorPanelElemen').style.display = 'none';
        
        // Tab header might need to be hidden or disabled. For now, just hide the tab content.
        const tabHeader = document.querySelector('.editor-panel-tab-switcher');
        if(tabHeader) tabHeader.style.display = 'none';

        // Reset step and state
        currentDpStep = 1;
        
        // Reset state
        dpFormState.element_id = null;
        dpFormState.title = '';
        dpFormState.description = '';
        dpFormState.files = [];
        dpFormState.deliverableType = 'upload';
        dpFormState.deliverableFile = null;
        dpFormState.deliverableUrl = '';
        dpFormState.priceType = 'fixed';
        dpFormState.priceFixed = '';
        dpFormState.priceMin = '';
        dpFormState.priceMax = '';
        dpFormState.qtyMin = 1;
        dpFormState.qtyMax = '';
        dpFormState.isScheduled = false;
        dpFormState.startTime = '';
        dpFormState.endTime = '';
        
        // Reset UI inputs
        document.getElementById('dpTitle').value = '';
        if (dpQuill) {
            dpQuill.setContents([]);
        }
        document.getElementById('dpFilesError').style.display = 'none';
        renderDpFilePreviews();
        
        document.querySelector('input[name="dpDeliverableType"][value="upload"]').checked = true;
        document.getElementById('dpDeliverableUrl').value = '';
        document.getElementById('dpDeliverableFile').value = '';
        document.getElementById('dpDeliverableFilePreview').style.display = 'none';
        changeDpDeliverableType('upload');

        document.querySelector('input[name="dpPriceType"][value="fixed"]').checked = true;
        document.getElementById('dpFixedPrice').value = '';
        document.getElementById('dpMinPrice').value = '';
        document.getElementById('dpMaxPrice').value = '';
        document.getElementById('dpMinQty').value = 1;
        document.getElementById('dpMaxQty').value = '';
        changeDpPriceType('fixed');

        document.getElementById('dpEnableSchedule').checked = false;
        document.getElementById('dpStartTime').value = '';
        document.getElementById('dpEndTime').value = '';
        toggleDpSchedule(false);

        updateDigitalProductWizardUI();

        // Show wizard
        document.getElementById('digitalProductWizardPanel').style.display = 'block';
    }

    function openEditDigitalProductWizard(product) {
        // Hide main panels
        const tabHeader = document.querySelector('.editor-panel-tab-switcher');
        if(tabHeader) tabHeader.style.display = 'none';
        document.getElementById('editorPanelElemen').style.display = 'none';
        
        currentDpStep = 1;
        
        // Populate state
        dpFormState.element_id = product.id;
        dpFormState.title = product.title || '';
        dpFormState.description = product.description || '';
        dpFormState.files = []; // Existing media not fully supported via frontend state yet without pre-fetching files, but we preserve them in backend
        dpFormState.deliverableType = product.deliverable_type || 'upload';
        dpFormState.deliverableFile = null;
        dpFormState.deliverableUrl = product.deliverable_url || '';
        dpFormState.priceType = product.pricing_type || 'fixed';
        dpFormState.priceFixed = product.price || '';
        dpFormState.priceMin = product.price_min || '';
        dpFormState.priceMax = product.price_max || '';
        dpFormState.qtyMin = product.quantity_min || 1;
        dpFormState.qtyMax = product.has_quantity_limit ? product.quantity : '';
        dpFormState.isScheduled = product.is_scheduled ? true : false;
        dpFormState.startTime = product.start_time ? product.start_time.substring(0, 16) : ''; // format YYYY-MM-DDThh:mm
        dpFormState.endTime = product.end_time ? product.end_time.substring(0, 16) : '';
        
        // Populate UI
        document.getElementById('dpTitle').value = dpFormState.title;
        if (dpQuill) {
            dpQuill.root.innerHTML = dpFormState.description;
        }
        renderDpFilePreviews();
        
        document.querySelector(`input[name="dpDeliverableType"][value="${dpFormState.deliverableType}"]`).checked = true;
        document.getElementById('dpDeliverableUrl').value = dpFormState.deliverableUrl;
        changeDpDeliverableType(dpFormState.deliverableType);

        document.querySelector(`input[name="dpPriceType"][value="${dpFormState.priceType}"]`).checked = true;
        document.getElementById('dpFixedPrice').value = dpFormState.priceFixed;
        document.getElementById('dpMinPrice').value = dpFormState.priceMin;
        document.getElementById('dpMaxPrice').value = dpFormState.priceMax;
        document.getElementById('dpMinQty').value = dpFormState.qtyMin;
        document.getElementById('dpMaxQty').value = dpFormState.qtyMax;
        changeDpPriceType(dpFormState.priceType);
        changeDpQtyLimitType(product.has_quantity_limit ? 'limited' : 'unlimited');

        document.getElementById('dpEnableSchedule').checked = dpFormState.isScheduled;
        document.getElementById('dpStartTime').value = dpFormState.startTime;
        document.getElementById('dpEndTime').value = dpFormState.endTime;
        toggleDpSchedule(dpFormState.isScheduled);

        updateDigitalProductWizardUI();
        document.getElementById('digitalProductWizardPanel').style.display = 'block';
    }

    function cancelDigitalProductWizard() {
        // Hide wizard
        document.getElementById('digitalProductWizardPanel').style.display = 'none';

        // Show main editor panels
        document.getElementById('editorPanelElemen').style.display = 'block';
        
        // Show tab header again
        const tabHeader = document.querySelector('.editor-panel-tab-switcher');
        if(tabHeader) tabHeader.style.display = 'flex';
    }

    // Step 1: Form Handlers
    function updateDpTitle(val) {
        dpFormState.title = val;
    }

    function handleDpFiles(input) {
        const errorEl = document.getElementById('dpFilesError');
        errorEl.style.display = 'none';
        
        let newFiles = Array.from(input.files);
        
        // Validation: Max 5 files total
        if (dpFormState.files.length + newFiles.length > 5) {
            errorEl.textContent = 'Maksimal 5 file media yang diizinkan.';
            errorEl.style.display = 'block';
            
            // Allow adding up to the 5 limit
            const availableSlots = 5 - dpFormState.files.length;
            newFiles = newFiles.slice(0, availableSlots);
        }
        
        // Append valid files
        newFiles.forEach(file => {
            dpFormState.files.push(file);
        });
        
        // Clear input value so same file can trigger 'change' event again if removed
        input.value = '';
        
        renderDpFilePreviews();
    }

    function removeDpFile(index) {
        // Revoke Object URL to free memory if needed (optional but good practice)
        // URL.revokeObjectURL(dpFormState.files[index].previewUrl); 
        dpFormState.files.splice(index, 1);
        
        // Clear error if we go below limit
        const errorEl = document.getElementById('dpFilesError');
        if (dpFormState.files.length < 5) {
            errorEl.style.display = 'none';
        }

        renderDpFilePreviews();
    }

    function renderDpFilePreviews() {
        const container = document.getElementById('dpFilesPreview');
        container.innerHTML = ''; // Clear container
        
        dpFormState.files.forEach((file, index) => {
            const item = document.createElement('div');
            item.style.position = 'relative';
            item.style.width = '80px';
            item.style.height = '80px';
            item.style.borderRadius = '8px';
            item.style.overflow = 'hidden';
            item.style.border = '1px solid #e2e8f0';
            item.style.boxShadow = '0 1px 3px rgba(0,0,0,0.1)';
            item.style.backgroundColor = '#f1f5f9';
            item.style.display = 'flex';
            item.style.alignItems = 'center';
            item.style.justifyContent = 'center';
            
            // Remove Button
            const removeBtn = document.createElement('button');
            removeBtn.innerHTML = '<i class="fas fa-times"></i>';
            removeBtn.style.position = 'absolute';
            removeBtn.style.top = '4px';
            removeBtn.style.right = '4px';
            removeBtn.style.background = 'rgba(239, 68, 68, 0.9)'; // Red-500
            removeBtn.style.color = 'white';
            removeBtn.style.border = 'none';
            removeBtn.style.borderRadius = '50%';
            removeBtn.style.width = '20px';
            removeBtn.style.height = '20px';
            removeBtn.style.cursor = 'pointer';
            removeBtn.style.display = 'flex';
            removeBtn.style.alignItems = 'center';
            removeBtn.style.justifyContent = 'center';
            removeBtn.style.fontSize = '10px';
            removeBtn.style.zIndex = '10';
            removeBtn.onclick = (e) => {
                e.stopPropagation();
                removeDpFile(index);
            };
            
            // File Preview (Image vs Video)
            const objectUrl = URL.createObjectURL(file);
            if (file.type.startsWith('image/')) {
                const img = document.createElement('img');
                img.src = objectUrl;
                img.style.width = '100%';
                img.style.height = '100%';
                img.style.objectFit = 'cover';
                
                // Release object URL when loaded
                img.onload = () => URL.revokeObjectURL(objectUrl);
                item.appendChild(img);
            } else if (file.type === 'video/mp4') {
                const video = document.createElement('video');
                video.src = objectUrl;
                video.style.width = '100%';
                video.style.height = '100%';
                video.style.objectFit = 'cover';
                
                video.onloadeddata = () => URL.revokeObjectURL(objectUrl);
                item.appendChild(video);
                
                // Play Icon Overlay
                const playIcon = document.createElement('div');
                playIcon.innerHTML = '<i class="fas fa-play"></i>';
                playIcon.style.position = 'absolute';
                playIcon.style.color = 'rgba(255, 255, 255, 0.9)';
                playIcon.style.fontSize = '24px';
                playIcon.style.textShadow = '0px 2px 4px rgba(0,0,0,0.5)';
                item.appendChild(playIcon);
            } else {
                // Fallback icon for unsupported files (should be filtered by accept)
                const fileIcon = document.createElement('i');
                fileIcon.className = 'fas fa-file';
                fileIcon.style.fontSize = '24px';
                fileIcon.style.color = '#94a3b8';
                item.appendChild(fileIcon);
            }
            
            item.appendChild(removeBtn);
            container.appendChild(item);
        });
    }

    // Step 1: Deliverable Handlers
    function changeDpDeliverableType(type) {
        dpFormState.deliverableType = type;
        const uploadSection = document.getElementById('dpDeliverableUploadSection');
        const urlSection = document.getElementById('dpDeliverableUrlSection');
        const urlInput = document.getElementById('dpDeliverableUrl');

        if (type === 'upload') {
            uploadSection.style.display = 'block';
            urlSection.style.display = 'none';
        } else {
            uploadSection.style.display = 'none';
            urlSection.style.display = 'block';
            if (type === 'gdrive') {
                urlInput.placeholder = 'https://drive.google.com/...';
            } else {
                urlInput.placeholder = 'https://...';
            }
        }
    }

    function handleDpDeliverableFile(input) {
        if (input.files && input.files[0]) {
            dpFormState.deliverableFile = input.files[0];
            document.getElementById('dpDeliverableFileName').textContent = dpFormState.deliverableFile.name;
            document.getElementById('dpDeliverableFilePreview').style.display = 'flex';
        }
    }

    function removeDpDeliverableFile() {
        dpFormState.deliverableFile = null;
        document.getElementById('dpDeliverableFile').value = '';
        document.getElementById('dpDeliverableFilePreview').style.display = 'none';
    }

    function updateDpDeliverableUrl(val) {
        dpFormState.deliverableUrl = val;
    }

    // Step 2: Pricing Handlers
    function changeDpPriceType(type) {
        dpFormState.priceType = type;
        const fixedSection = document.getElementById('dpFixedPriceSection');
        const pwywSection = document.getElementById('dpPwywSection');
        
        if (type === 'fixed') {
            fixedSection.style.display = 'block';
            pwywSection.style.display = 'none';
        } else {
            fixedSection.style.display = 'none';
            pwywSection.style.display = 'block';
        }
    }

    function updateDpPriceField(field, value) {
        if (field === 'fixed') dpFormState.priceFixed = value;
        else if (field === 'min') dpFormState.priceMin = value;
        else if (field === 'max') dpFormState.priceMax = value;
    }

    function updateDpQtyField(field, value) {
        if (field === 'min') dpFormState.qtyMin = value;
        else if (field === 'max') dpFormState.qtyMax = value;
    }

    function changeDpQtyLimitType(type) {
        dpFormState.qtyLimitType = type;
        const qtyUnlimitedWrapper = document.getElementById('dp-qty-unlimited-wrapper');
        const qtyLimitedWrapper = document.getElementById('dp-qty-limited-wrapper');
        const maxQtySection = document.getElementById('dpMaxQtySection');
        
        if (type === 'unlimited') {
            qtyUnlimitedWrapper.classList.add('active');
            qtyLimitedWrapper.classList.remove('active');
            maxQtySection.style.display = 'none';
            dpFormState.qtyMax = ''; // Clear value
            document.getElementById('dpMaxQty').value = '';
        } else {
            qtyUnlimitedWrapper.classList.remove('active');
            qtyLimitedWrapper.classList.add('active');
            maxQtySection.style.display = 'block';
        }
    }

    // Step 3: Schedule Handlers
    function toggleDpSchedule(enabled) {
        dpFormState.isScheduled = enabled;
        document.getElementById('dpScheduleSection').style.display = enabled ? 'block' : 'none';
    }

    function updateDpScheduleField(field, value) {
        if (field === 'start') dpFormState.startTime = value;
        else if (field === 'end') dpFormState.endTime = value;
    }

    function nextDigitalProductStep() {
        // Step Validation
        if (currentDpStep === 1) {
            if (!dpFormState.title || dpFormState.title.trim() === '') {
                alert('Nama produk wajib diisi.');
                document.getElementById('dpTitle').focus();
                return;
            }
            if (!dpFormState.description || dpFormState.description.trim() === '') {
                alert('Deskripsi produk wajib diisi.');
                if (dpQuill) dpQuill.focus();
                return;
            }
            if (dpFormState.deliverableType === 'upload') {
                if (!dpFormState.deliverableFile) {
                    alert('Silakan unggah file isi produk yang akan dijual.');
                    return;
                }
            } else {
                if (!dpFormState.deliverableUrl || dpFormState.deliverableUrl.trim() === '') {
                    alert('Silakan masukkan URL tautan produk yang valid.');
                    document.getElementById('dpDeliverableUrl').focus();
                    return;
                }
            }
        } else if (currentDpStep === 2) {
            // Step 2 Validation
            if (dpFormState.priceType === 'fixed') {
                if (dpFormState.priceFixed === '' || isNaN(dpFormState.priceFixed) || parseFloat(dpFormState.priceFixed) < 0) {
                    alert('Silakan masukkan harga jual yang valid.');
                    document.getElementById('dpFixedPrice').focus();
                    return;
                }
            } else {
                if (dpFormState.priceMin === '' || isNaN(dpFormState.priceMin) || parseFloat(dpFormState.priceMin) < 0) {
                    alert('Silakan masukkan harga minimal yang valid.');
                    document.getElementById('dpMinPrice').focus();
                    return;
                }
                if (dpFormState.priceMax !== '' && parseFloat(dpFormState.priceMax) < parseFloat(dpFormState.priceMin)) {
                    alert('Harga maksimal tidak boleh lebih kecil dari harga minimal.');
                    document.getElementById('dpMaxPrice').focus();
                    return;
                }
            }
            
            if (dpFormState.qtyMin === '' || isNaN(dpFormState.qtyMin) || parseInt(dpFormState.qtyMin) < 1) {
                alert('Minimal pembelian harus minimal 1.');
                document.getElementById('dpMinQty').focus();
                return;
            }
            
            if (dpFormState.qtyMax !== '' && parseInt(dpFormState.qtyMax) < parseInt(dpFormState.qtyMin)) {
                alert('Maksimal pembelian tidak boleh lebih kecil dari minimal pembelian.');
                document.getElementById('dpMaxQty').focus();
                return;
            }
        } else if (currentDpStep === 3) {
            // Step 3 Validation
            if (dpFormState.isScheduled) {
                if (!dpFormState.startTime) {
                    alert('Silakan tentukan Waktu Mulai penayangan.');
                    document.getElementById('dpStartTime').focus();
                    return;
                }
                if (!dpFormState.endTime) {
                    alert('Silakan tentukan Waktu Berakhir penayangan.');
                    document.getElementById('dpEndTime').focus();
                    return;
                }
                
                // Validate if end time is after start time
                const start = new Date(dpFormState.startTime);
                const end = new Date(dpFormState.endTime);
                if (end <= start) {
                    alert('Waktu Berakhir harus lebih lambat dari Waktu Mulai.');
                    document.getElementById('dpEndTime').focus();
                    return;
                }
            }
        }

        if (currentDpStep < maxDpStep) {
            currentDpStep++;
            updateDigitalProductWizardUI();
        } else {
            // Reached the end, perform save/submit action
            handleSaveProduct();
        }
    }

    function handleSaveProduct() {
        // 1. Validasi Data
        if (!dpFormState.title || !dpFormState.description) {
            alert("Data produk belum lengkap. Silakan periksa kembali form Anda.");
            return;
        }

        const btnSave = document.getElementById('btn-dp-next');
        if (btnSave) {
            btnSave.disabled = true;
            btnSave.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
        }

        // 2. Siapkan FormData
        let formData = new FormData();
        if (dpFormState.element_id) {
            formData.append('element_id', dpFormState.element_id);
        }
        formData.append('title', dpFormState.title);
        formData.append('description', dpFormState.description);
        
        // Media files
        formData.append('media_count', dpFormState.files.length);
        dpFormState.files.forEach((file, index) => {
            formData.append(`media_${index}`, file);
        });

        // Pricing
        formData.append('pricing_type', dpFormState.priceType || 'fixed');
        if (dpFormState.priceType === 'fixed') {
            formData.append('price_fixed', dpFormState.priceFixed || 0);
        } else {
            formData.append('price_min', dpFormState.priceMin || 0);
            formData.append('price_max', dpFormState.priceMax || '');
        }

        // Quantity
        formData.append('quantity_min', dpFormState.qtyMin || 1);
        if (dpFormState.qtyMax) {
            formData.append('has_quantity_limit', 1);
            formData.append('quantity_max', dpFormState.qtyMax);
        } else {
            formData.append('has_quantity_limit', 0);
        }

        // Scheduling
        formData.append('is_scheduled', dpFormState.isScheduled ? 1 : 0);
        if (dpFormState.isScheduled) {
            formData.append('start_time', dpFormState.startTime || '');
            formData.append('end_time', dpFormState.endTime || '');
        }

        // Deliverable
        formData.append('deliverable_type', dpFormState.deliverableType || 'upload');
        if (dpFormState.deliverableType === 'upload' && dpFormState.deliverableFile) {
            formData.append('deliverable_file', dpFormState.deliverableFile);
        } else if (dpFormState.deliverableUrl) {
            formData.append('deliverable_url', dpFormState.deliverableUrl);
        }

        // Send via fetch
        fetch('{{ route('admin.elements.digital-product.store') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Untuk sementara, kita reload halaman untuk menampilkan produk baru.
                alert('Produk digital berhasil disimpan!');
                window.location.reload();
            } else {
                alert('Terjadi kesalahan saat menyimpan produk.');
                if (btnSave) {
                    btnSave.disabled = false;
                    btnSave.innerHTML = 'Selesai';
                }
            }
        })
        .catch(err => {
            console.error(err);
            alert('Gagal menghubungi server.');
            if (btnSave) {
                btnSave.disabled = false;
                btnSave.innerHTML = 'Selesai';
            }
        });

        // 4. Reset & Kembalikan UI
        // cancelDigitalProductWizard already handles resetting the state and closing the wizard
        cancelDigitalProductWizard();
    }

    function prevDigitalProductStep() {
        if (currentDpStep > 1) {
            currentDpStep--;
            updateDigitalProductWizardUI();
        }
    }

    function updateDigitalProductWizardUI() {
        // Hide all steps and update indicators
        for (let i = 1; i <= maxDpStep; i++) {
            const stepEl = document.getElementById(`dp-step-${i}`);
            const indicatorEl = document.getElementById(`dp-step-indicator-${i}`);
            const iconEl = document.getElementById(`dp-step-icon-${i}`);
            const numEl = document.getElementById(`dp-step-num-${i}`);
            
            if (stepEl) stepEl.style.display = 'none';
            
            if (indicatorEl) {
                // Remove legacy inline styles if any
                indicatorEl.style.color = '';
                indicatorEl.style.fontWeight = '';
                
                indicatorEl.classList.remove('active', 'completed');
                
                if (i < currentDpStep) {
                    indicatorEl.classList.add('completed');
                    if (iconEl) iconEl.style.display = 'inline-block';
                    if (numEl) numEl.style.display = 'none';
                } else if (i === currentDpStep) {
                    indicatorEl.classList.add('active');
                    if (iconEl) iconEl.style.display = 'none';
                    if (numEl) numEl.style.display = 'inline-block';
                } else {
                    if (iconEl) iconEl.style.display = 'none';
                    if (numEl) numEl.style.display = 'inline-block';
                }
            }
        }

        // Show current step
        const currentStepEl = document.getElementById(`dp-step-${currentDpStep}`);
        if (currentStepEl) currentStepEl.style.display = 'block';

        // Show/hide prev button
        const btnPrev = document.getElementById('btn-dp-prev');
        if (currentDpStep > 1) {
            btnPrev.style.display = 'block';
        } else {
            btnPrev.style.display = 'none';
        }

        // Change next button text to 'Selesai' on last step
        const btnNext = document.getElementById('btn-dp-next');
        if (currentDpStep === maxDpStep) {
            btnNext.textContent = 'Selesai';
            btnNext.style.background = '#10b981'; // Green color for complete
            btnNext.style.color = 'white';
        } else {
            btnNext.textContent = 'Next';
            btnNext.style.background = '#FF9040'; // Original brand color
            btnNext.style.color = 'white';
        }
    }
</script>
