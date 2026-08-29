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
                <div id="digitalProductWizardPanel" style="display: none;">
                    <div class="edit-element-header">
                        <h3 class="element-header-title">
                            <i class="fas fa-box text-brand-orange"></i> Tambah Digital Product
                        </h3>
                    </div>

                    <!-- Wizard Steps Indicator -->
                    <div style="display: flex; gap: 10px; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px;">
                        <div id="dp-step-indicator-1" style="font-weight: bold; color: #FF9040;">1. Detail Produk</div>
                        <div id="dp-step-indicator-2" style="color: #999;">2. Pricing</div>
                        <div id="dp-step-indicator-3" style="color: #999;">3. Waktu Penayangan</div>
                    </div>

                    <!-- Wizard Body -->
                    <div class="wizard-body" style="background: white; border-radius: 8px; padding: 15px; border: 1px solid #eee;">
                        <!-- Step 1: Detail Produk -->
                        <div class="wizard-step" id="dp-step-1">
                            <h4 style="margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px;">Detail Produk</h4>
                            
                            <div class="form-group" style="margin-bottom: 20px;">
                                <label for="dpTitle" class="form-label-custom" style="display: block; margin-bottom: 8px; font-weight: 600; color: #334155;">Nama Produk <span style="color: #ef4444;">*</span></label>
                                <input type="text" id="dpTitle" class="form-control-input" placeholder="Masukkan judul/nama produk digital..." style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #cbd5e1; outline: none; transition: border-color 0.2s;" oninput="updateDpTitle(this.value)" required>
                            </div>

                            <!-- Include Quill CSS -->
                            <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
                            <style>
                                .ql-toolbar.ql-snow { border-top-left-radius: 8px; border-top-right-radius: 8px; border-color: #cbd5e1; }
                                .ql-container.ql-snow { border-bottom-left-radius: 8px; border-bottom-right-radius: 8px; border-color: #cbd5e1; font-family: inherit; }
                                .ql-editor { min-height: 150px; font-size: 14px; }
                            </style>

                            <div class="form-group" style="margin-bottom: 20px;">
                                <label class="form-label-custom" style="display: block; margin-bottom: 8px; font-weight: 600; color: #334155;">Deskripsi Produk <span style="color: #ef4444;">*</span></label>
                                <div id="dpDescriptionEditor"></div>
                            </div>

                            <div class="form-group" style="margin-bottom: 20px;">
                                <label class="form-label-custom" style="display: block; margin-bottom: 8px; font-weight: 600; color: #334155;">Media Produk (Maks. 5 File)</label>
                                <div class="upload-dropzone" style="border: 2px dashed #cbd5e1; padding: 25px; text-align: center; border-radius: 8px; cursor: pointer; background: #f8fafc; transition: background 0.2s;" onclick="document.getElementById('dpFiles').click()" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#f8fafc'">
                                    <input type="file" id="dpFiles" accept="image/jpeg,image/png,image/gif,video/mp4" multiple style="display: none;" onchange="handleDpFiles(this)">
                                    <i class="fas fa-cloud-upload-alt" style="font-size: 28px; color: #64748b; margin-bottom: 10px;"></i>
                                    <div style="color: #475569; font-size: 15px;">Klik untuk memilih Gambar atau Video (MP4)</div>
                                    <div style="font-size: 13px; color: #94a3b8; margin-top: 5px;">Format yang didukung: JPG, PNG, GIF, MP4</div>
                                </div>
                                <div id="dpFilesError" style="color: #ef4444; font-size: 13px; margin-top: 8px; display: none;"></div>
                                
                                <!-- File Preview Grid -->
                                <div id="dpFilesPreview" style="display: flex; flex-wrap: wrap; gap: 12px; margin-top: 15px;">
                                    <!-- Thumbnail elements will be injected here via JS -->
                                </div>
                            </div>
                            
                            <div class="form-group" style="margin-bottom: 10px;">
                                <label class="form-label-custom" style="display: block; margin-bottom: 8px; font-weight: 600; color: #334155;">Isi Produk yang Dijual (Akses Produk) <span style="color: #ef4444;">*</span></label>
                                
                                <!-- Radio selection -->
                                <div style="display: flex; flex-wrap: wrap; gap: 15px; margin-bottom: 15px;">
                                    <label style="cursor: pointer; display: flex; align-items: center; gap: 6px; font-size: 14px; color: #475569;">
                                        <input type="radio" name="dpDeliverableType" value="upload" onchange="changeDpDeliverableType(this.value)" checked>
                                        Upload File
                                    </label>
                                    <label style="cursor: pointer; display: flex; align-items: center; gap: 6px; font-size: 14px; color: #475569;">
                                        <input type="radio" name="dpDeliverableType" value="gdrive" onchange="changeDpDeliverableType(this.value)">
                                        Link Google Drive
                                    </label>
                                    <label style="cursor: pointer; display: flex; align-items: center; gap: 6px; font-size: 14px; color: #475569;">
                                        <input type="radio" name="dpDeliverableType" value="external" onchange="changeDpDeliverableType(this.value)">
                                        Link Lainnya
                                    </label>
                                </div>

                                <!-- Upload Section -->
                                <div id="dpDeliverableUploadSection">
                                    <div class="upload-dropzone" style="border: 2px dashed #cbd5e1; padding: 20px; text-align: center; border-radius: 8px; cursor: pointer; background: #f8fafc;" onclick="document.getElementById('dpDeliverableFile').click()" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#f8fafc'">
                                        <input type="file" id="dpDeliverableFile" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" style="display: none;" onchange="handleDpDeliverableFile(this)">
                                        <i class="fas fa-file-upload" style="font-size: 24px; color: #64748b; margin-bottom: 10px;"></i>
                                        <div style="color: #475569; font-size: 14px;">Klik untuk memilih File (Gambar, PDF, Word)</div>
                                    </div>
                                    <div id="dpDeliverableFilePreview" style="margin-top: 10px; display: none; padding: 12px; border: 1px solid #e2e8f0; border-radius: 6px; background: #fff; align-items: center; justify-content: space-between; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                                        <div style="display: flex; align-items: center; gap: 10px; overflow: hidden;">
                                            <i class="fas fa-file-alt" style="font-size: 20px; color: #3b82f6;"></i>
                                            <span id="dpDeliverableFileName" style="font-size: 14px; color: #334155; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 200px;">filename.pdf</span>
                                        </div>
                                        <button type="button" onclick="removeDpDeliverableFile()" style="background: none; border: none; color: #ef4444; cursor: pointer; display: flex; align-items: center; justify-content: center; width: 24px; height: 24px; border-radius: 50%;"><i class="fas fa-trash-alt"></i></button>
                                    </div>
                                </div>

                                <!-- GDrive / External Section -->
                                <div id="dpDeliverableUrlSection" style="display: none;">
                                    <input type="url" id="dpDeliverableUrl" class="form-control-input" placeholder="https://..." style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #cbd5e1; outline: none; transition: border-color 0.2s;" oninput="updateDpDeliverableUrl(this.value)">
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Pricing -->
                        <div class="wizard-step" id="dp-step-2" style="display: none;">
                            <h4 style="margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px; color: #334155;">Pengaturan Harga</h4>
                            
                            <!-- Pricing Type -->
                            <div class="form-group" style="margin-bottom: 20px;">
                                <label class="form-label-custom" style="display: block; margin-bottom: 8px; font-weight: 600; color: #334155;">Tipe Harga <span style="color: #ef4444;">*</span></label>
                                
                                <div style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 15px;">
                                    <label style="cursor: pointer; display: flex; align-items: center; gap: 6px; font-size: 14px; color: #475569;">
                                        <input type="radio" name="dpPriceType" value="fixed" onchange="changeDpPriceType(this.value)" checked>
                                        Harga Biasa (Fixed Price)
                                    </label>
                                    <label style="cursor: pointer; display: flex; align-items: center; gap: 6px; font-size: 14px; color: #475569;">
                                        <input type="radio" name="dpPriceType" value="pwyw" onchange="changeDpPriceType(this.value)">
                                        Bebas Tentukan Harga (Pay What You Want)
                                    </label>
                                </div>

                                <!-- Fixed Price Input -->
                                <div id="dpFixedPriceSection">
                                    <label for="dpFixedPrice" class="form-label-custom" style="display: block; margin-bottom: 5px; font-size: 13px; color: #64748b;">Harga Jual (Rp) <span style="color: #ef4444;">*</span></label>
                                    <input type="number" id="dpFixedPrice" class="form-control-input" placeholder="0" min="0" style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #cbd5e1; outline: none; transition: border-color 0.2s;" oninput="updateDpPriceField('fixed', this.value)">
                                </div>

                                <!-- PWYW Input -->
                                <div id="dpPwywSection" style="display: none;">
                                    <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                                        <div style="flex: 1; min-width: 150px;">
                                            <label for="dpMinPrice" class="form-label-custom" style="display: block; margin-bottom: 5px; font-size: 13px; color: #64748b;">Harga Minimal (Rp) <span style="color: #ef4444;">*</span></label>
                                            <input type="number" id="dpMinPrice" class="form-control-input" placeholder="0" min="0" style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #cbd5e1; outline: none; transition: border-color 0.2s;" oninput="updateDpPriceField('min', this.value)">
                                        </div>
                                        <div style="flex: 1; min-width: 150px;">
                                            <label for="dpMaxPrice" class="form-label-custom" style="display: block; margin-bottom: 5px; font-size: 13px; color: #64748b;">Harga Maksimal (Rp) (Opsional)</label>
                                            <input type="number" id="dpMaxPrice" class="form-control-input" placeholder="0" min="0" style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #cbd5e1; outline: none; transition: border-color 0.2s;" oninput="updateDpPriceField('max', this.value)">
                                        </div>
                                    </div>
                                    <div style="font-size: 12px; color: #94a3b8; margin-top: 6px;">Pembeli dapat membayar berapapun di antara batas harga ini. Kosongkan harga maksimal jika tidak ada batasan.</div>
                                </div>
                            </div>

                            <h4 style="margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-top: 30px; color: #334155;">Kuantitas Pembelian</h4>
                            
                            <div class="form-group" style="margin-bottom: 20px;">
                                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                                    <div style="flex: 1; min-width: 150px;">
                                        <label for="dpMinQty" class="form-label-custom" style="display: block; margin-bottom: 5px; font-weight: 600; color: #334155;">Minimal Pembelian <span style="color: #ef4444;">*</span></label>
                                        <input type="number" id="dpMinQty" class="form-control-input" value="1" min="1" style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #cbd5e1; outline: none; transition: border-color 0.2s;" oninput="updateDpQtyField('min', this.value)">
                                    </div>
                                    <div style="flex: 1; min-width: 150px;">
                                        <label for="dpMaxQty" class="form-label-custom" style="display: block; margin-bottom: 5px; font-weight: 600; color: #334155;">Maksimal Pembelian (Opsional)</label>
                                        <input type="number" id="dpMaxQty" class="form-control-input" placeholder="Tak Terbatas" min="1" style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #cbd5e1; outline: none; transition: border-color 0.2s;" oninput="updateDpQtyField('max', this.value)">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Waktu Penayangan -->
                        <div class="wizard-step" id="dp-step-3" style="display: none;">
                            <h4 style="margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px; color: #334155;">Waktu Penayangan</h4>
                            
                            <div class="form-group" style="margin-bottom: 20px;">
                                <label style="cursor: pointer; display: flex; align-items: center; gap: 10px; font-weight: 600; color: #334155;">
                                    <input type="checkbox" id="dpEnableSchedule" onchange="toggleDpSchedule(this.checked)" style="width: 16px; height: 16px; cursor: pointer;">
                                    Aktifkan Batas Waktu Rilis
                                </label>
                                <div style="font-size: 13px; color: #64748b; margin-top: 5px; margin-left: 26px;">
                                    Jika tidak diaktifkan, produk akan tayang seterusnya di microsite.
                                </div>
                            </div>

                            <div id="dpScheduleSection" style="display: none; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 15px; margin-bottom: 15px;">
                                <div class="form-group" style="margin-bottom: 15px;">
                                    <label for="dpStartTime" class="form-label-custom" style="display: block; margin-bottom: 5px; font-weight: 600; color: #334155;">Waktu Mulai <span style="color: #ef4444;">*</span></label>
                                    <input type="datetime-local" id="dpStartTime" class="form-control-input" style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #cbd5e1; outline: none; transition: border-color 0.2s;" onchange="updateDpScheduleField('start', this.value)">
                                </div>
                                <div class="form-group">
                                    <label for="dpEndTime" class="form-label-custom" style="display: block; margin-bottom: 5px; font-weight: 600; color: #334155;">Waktu Berakhir <span style="color: #ef4444;">*</span></label>
                                    <input type="datetime-local" id="dpEndTime" class="form-control-input" style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #cbd5e1; outline: none; transition: border-color 0.2s;" onchange="updateDpScheduleField('end', this.value)">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Wizard Footer (Navigation) -->
                    <div class="wizard-footer" style="display: flex; justify-content: space-between; margin-top: 20px;">
                        <button type="button" onclick="cancelDigitalProductWizard()" style="padding: 8px 16px; border: 1px solid #ccc; border-radius: 6px; background: white; cursor: pointer;">
                            Cancel
                        </button>
                        <div style="display: flex; gap: 10px;">
                            <button type="button" id="btn-dp-prev" onclick="prevDigitalProductStep()" style="display: none; padding: 8px 16px; border: 1px solid #FF9040; color: #FF9040; border-radius: 6px; background: white; cursor: pointer;">
                                Previous
                            </button>
                            <button type="button" id="btn-dp-next" onclick="nextDigitalProductStep()" style="padding: 8px 16px; border: none; background: #FF9040; color: white; border-radius: 6px; cursor: pointer;">
                                Next
                            </button>
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
        title: '',
        description: '',
        files: [], // Media
        deliverableType: 'upload', // 'upload', 'gdrive', 'external'
        deliverableFile: null,
        deliverableUrl: '',
        priceType: 'fixed', // 'fixed', 'pwyw'
        priceFixed: '',
        priceMin: '',
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
            ['link', 'image', 'video']
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
        formData.append('title', dpFormState.title);
        formData.append('description', dpFormState.description);
        
        // Media files
        formData.append('media_count', dpFormState.files.length);
        dpFormState.files.forEach((file, index) => {
            formData.append(`media_${index}`, file);
        });

        // Pricing
        formData.append('pricing_type', dpFormState.priceType);
        if (dpFormState.priceType === 'fixed') {
            formData.append('price_fixed', dpFormState.priceFixed);
        } else {
            formData.append('price_min', dpFormState.priceMin);
            formData.append('price_max', dpFormState.priceMax);
        }

        // Quantity
        formData.append('quantity_min', dpFormState.qtyMin);
        if (dpFormState.qtyMax) {
            formData.append('has_quantity_limit', 1);
            formData.append('quantity_max', dpFormState.qtyMax);
        } else {
            formData.append('has_quantity_limit', 0);
        }

        // Scheduling
        formData.append('is_scheduled', dpFormState.isScheduled ? 1 : 0);
        if (dpFormState.isScheduled) {
            formData.append('start_time', dpFormState.startTime);
            formData.append('end_time', dpFormState.endTime);
        }

        // Deliverable
        formData.append('deliverable_type', dpFormState.deliverableType);
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
        // Hide all steps
        for (let i = 1; i <= maxDpStep; i++) {
            const stepEl = document.getElementById(`dp-step-${i}`);
            const indicatorEl = document.getElementById(`dp-step-indicator-${i}`);
            
            if (stepEl) stepEl.style.display = 'none';
            if (indicatorEl) {
                indicatorEl.style.color = '#999';
                indicatorEl.style.fontWeight = 'normal';
            }
        }

        // Show current step
        const currentStepEl = document.getElementById(`dp-step-${currentDpStep}`);
        const currentIndicatorEl = document.getElementById(`dp-step-indicator-${currentDpStep}`);
        
        if (currentStepEl) currentStepEl.style.display = 'block';
        if (currentIndicatorEl) {
            currentIndicatorEl.style.color = '#FF9040';
            currentIndicatorEl.style.fontWeight = 'bold';
        }

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
