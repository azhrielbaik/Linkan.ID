<div id="digitalProductWizardPanel" style="display: none;">
                    <div class="dp-wizard-container">
                        {{-- MODE SELECTOR: Pilih dari Toko vs Buat Baru --}}
                        <script>
                            window.switchDpMode = window.switchDpMode || function(mode) {
                                if (window.MicrositeBuilder && typeof window.MicrositeBuilder.switchDpMode === 'function') {
                                    return window.MicrositeBuilder.switchDpMode(mode);
                                }
                            };
                        </script>
                        <div id="dpModeSelector" style="margin-bottom: 20px;">
                            <p style="font-size: 13px; color: #6b7280; margin-bottom: 12px; font-weight: 500;">
                                Bagaimana cara Anda menambahkan produk?
                            </p>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                                {{-- Opsi A: Pilih dari Toko --}}
                                <div id="dpModePickBtn" onclick="if(window.switchDpMode) window.switchDpMode('pick');"
                                     style="padding: 14px 12px; border: 2px solid #e5e7eb; border-radius: 10px; cursor: pointer; text-align: center; transition: all 0.2s;">
                                    <i class="fas fa-store" style="font-size: 20px; color: #ED842C; margin-bottom: 8px; display: block;"></i>
                                    <div style="font-weight: 700; font-size: 13px; color: #0f172a;">Pilih dari Toko</div>
                                    <div style="font-size: 11px; color: #64748b; margin-top: 3px;">Gunakan produk yang sudah ada</div>
                                </div>
                                {{-- Opsi B: Buat Baru --}}
                                <div id="dpModeCreateBtn" onclick="if(window.switchDpMode) window.switchDpMode('create');"
                                     style="padding: 14px 12px; border: 2px solid #e5e7eb; border-radius: 10px; cursor: pointer; text-align: center; transition: all 0.2s;">
                                    <i class="fas fa-plus-circle" style="font-size: 20px; color: #6366f1; margin-bottom: 8px; display: block;"></i>
                                    <div style="font-weight: 700; font-size: 13px; color: #0f172a;">Buat Produk Baru</div>
                                    <div style="font-size: 11px; color: #64748b; margin-top: 3px;">Isi detail produk dari awal</div>
                                </div>
                            </div>
                        </div>

                        {{-- PANEL: Picker "Pilih dari Toko" --}}
                        <div id="dpPickFromTokoPanel" style="display: none;">
                            <div style="font-size: 12px; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 12px;">
                                Produk di Toko Anda
                            </div>

                            {{-- Search box --}}
                            <div style="position: relative; margin-bottom: 14px;">
                                <i class="fas fa-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 13px;"></i>
                                <input type="text" id="dpPickSearch" placeholder="Cari produk..."
                                       oninput="filterTokoProducts(this.value)"
                                       style="width: 100%; padding: 9px 12px 9px 34px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 13px; outline: none; background: #f8fafc;">
                            </div>

                            {{-- List produk --}}
                            <div id="dpPickProductList"
                                 style="max-height: 320px; overflow-y: auto; display: flex; flex-direction: column; gap: 8px;">
                                {{-- Diisi oleh JavaScript --}}
                            </div>

                            {{-- Pesan jika kosong --}}
                            <div id="dpPickEmptyMsg" style="display: none; text-align: center; padding: 30px 0; color: #94a3b8;">
                                <i class="fas fa-box-open" style="font-size: 28px; display: block; margin-bottom: 8px;"></i>
                                <p style="font-size: 13px;">Belum ada produk di Toko Anda.</p>
                                <a href="{{ route('admin.digital-products.create') }}" target="_blank"
                                   style="font-size: 12px; color: #ED842C; font-weight: 600; text-decoration: none;">
                                    + Tambah produk ke Toko
                                </a>
                            </div>
                        </div>

                        {{-- Konten wizard buat produk baru --}}
                        <div id="dpCreatePanel" style="display: none;">
                            <!-- Stepper UI -->
                            <div class="dp-stepper-wrapper">
                            <div class="dp-stepper-item active" id="dp-step-indicator-1">
                                <div class="dp-stepper-circle">
                                    <i class="fas fa-check" id="dp-step-icon-1" style="display:none;"></i>
                                    <span id="dp-step-num-1">1</span>
                                </div>
                                <div class="dp-stepper-label">{{ __('microsite.product_details') }}</div>
                            </div>
                            <div class="dp-stepper-line"></div>
                            <div class="dp-stepper-item" id="dp-step-indicator-2">
                                <div class="dp-stepper-circle">
                                    <i class="fas fa-check" id="dp-step-icon-2" style="display:none;"></i>
                                    <span id="dp-step-num-2">2</span>
                                </div>
                                <div class="dp-stepper-label">{{ __('microsite.pricing') }}</div>
                            </div>
                            <div class="dp-stepper-line"></div>
                            <div class="dp-stepper-item" id="dp-step-indicator-3">
                                <div class="dp-stepper-circle">
                                    <i class="fas fa-check" id="dp-step-icon-3" style="display:none;"></i>
                                    <span id="dp-step-num-3">3</span>
                                </div>
                                <div class="dp-stepper-label">{{ __('microsite.display') }}</div>
                            </div>
                        </div>

                        <!-- Wizard Body -->
                        <div class="wizard-body">
                            
                            <!-- Step 1: {{ __('microsite.product_details') }} -->
                            <div class="wizard-step" id="dp-step-1">
                                
                                <div class="dp-form-row-box">
                                    <span class="dp-row-label">{{ __('microsite.product_name') }}</span>
                                    <input type="text" id="dpTitle" class="dp-row-input" placeholder="{{ __('microsite.product_name_placeholder') }}" oninput="updateDpTitle(this.value)" required maxlength="200" pattern="[^<>]*" title="Karakter &lt; dan &gt; tidak diperbolehkan untuk mencegah injeksi">
                                </div>

                                <div class="dp-form-row-box" style="display: block;">
                                    <span class="dp-row-label" style="display: block; margin-bottom: 10px;">{{ __('microsite.description') }}</span>
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
                                <div style="font-size: 11px; font-weight: 700; color: #9ca3af; text-transform: uppercase; margin-bottom: 12px; margin-top: 24px; letter-spacing: 0.05em;">{{ __('microsite.product_media') }}</div>
                                <div class="dp-upload-box" onclick="document.getElementById('dpFiles').click()">
                                    <input type="file" id="dpFiles" accept="image/jpeg,image/png,image/gif,video/mp4" multiple style="display: none;" onchange="handleDpFiles(this)">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <h4>{{ __('microsite.click_to_upload') }}</h4>
                                    <p>{{ __('microsite.format_media') }}</p>
                                </div>
                                <div id="dpFilesError" style="color: #ef4444; font-size: 13px; margin-top: -15px; margin-bottom: 15px; display: none;"></div>
                                <div id="dpFilesPreview" style="display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 24px;"></div>

                                <!-- Deliverable Selection -->
                                <div style="font-size: 11px; font-weight: 700; color: #9ca3af; text-transform: uppercase; margin-bottom: 12px; letter-spacing: 0.05em;">{{ __('microsite.product_access') }}</div>
                                <div class="dp-platform-cards-grid">
                                    <label class="dp-platform-card-wrapper active" id="dp-deliv-upload-wrapper">
                                        <input type="radio" name="dpDeliverableType" value="upload" onchange="changeDpDeliverableType(this.value)" checked>
                                        <div class="dp-platform-card">
                                            <div class="dp-platform-card-content">
                                                <div class="dp-platform-card-header">
                                                    <span class="dp-platform-card-title">{{ __('microsite.upload_file') }}</span>
                                                    <span class="dp-platform-radio-circle"></span>
                                                </div>
                                                <p class="dp-platform-card-desc">{{ __('microsite.upload_file_desc') }}</p>
                                            </div>
                                        </div>
                                    </label>

                                    <label class="dp-platform-card-wrapper" id="dp-deliv-gdrive-wrapper">
                                        <input type="radio" name="dpDeliverableType" value="gdrive" onchange="changeDpDeliverableType(this.value)">
                                        <div class="dp-platform-card">
                                            <div class="dp-platform-card-content">
                                                <div class="dp-platform-card-header">
                                                    <span class="dp-platform-card-title">{{ __('microsite.google_drive') }}</span>
                                                    <span class="dp-platform-radio-circle"></span>
                                                </div>
                                                <p class="dp-platform-card-desc">Berikan akses lewat tautan {{ __('microsite.google_drive') }}.</p>
                                            </div>
                                        </div>
                                    </label>

                                    <label class="dp-platform-card-wrapper" id="dp-deliv-external-wrapper">
                                        <input type="radio" name="dpDeliverableType" value="external" onchange="changeDpDeliverableType(this.value)">
                                        <div class="dp-platform-card">
                                            <div class="dp-platform-card-content">
                                                <div class="dp-platform-card-header">
                                                    <span class="dp-platform-card-title">{{ __('microsite.external_link') }}</span>
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
                                    <div id="dpDeliverableFilePreview" class="file-upload-progress-card" style="display: none;">
                                        <div class="file-progress-header">
                                            <div class="file-info-group">
                                                <div class="file-icon-wrapper" id="dpFileIconWrapper">
                                                    <i class="fas fa-file-alt" id="dpFileIcon"></i>
                                                </div>
                                                <div class="file-text-details">
                                                    <div class="file-name" id="dpDeliverableFileName">filename.pdf</div>
                                                    <div class="file-meta" id="dpDeliverableFileSize">0 KB</div>
                                                </div>
                                            </div>
                                            <div class="file-status-badge" id="dpFileStatusBadge">
                                                <span class="badge-dot" id="dpFileBadgeDot"></span>
                                                <span id="dpFileStatusText">Mengunggah...</span>
                                            </div>
                                            <button type="button" class="btn-remove-file" onclick="removeDpDeliverableFile()" aria-label="Hapus Berkas" title="Hapus Berkas">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                        <div class="file-progress-track">
                                            <div class="file-progress-fill" id="dpFileProgressFill" style="width: 0%;"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- URL Section -->
                                <div id="dpDeliverableUrlSection" style="display: none;">
                                    <div class="dp-form-row-box">
                                        <span class="dp-row-label">URL Akses:</span>
                                        <input type="url" id="dpDeliverableUrl" class="dp-row-input" placeholder="https://..." oninput="updateDpDeliverableUrl(this.value)" maxlength="255" pattern="https?://.*" title="Harus berupa URL yang valid (http:// atau https://)">
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
                                        <input type="text" id="dpFixedPrice" class="dp-row-input" value="0" oninput="formatRupiahInput(this, 'fixed')">
                                    </div>
                                </div>

                                <!-- PWYW Input -->
                                <div id="dpPwywSection" style="display: none;">
                                    <div class="dp-form-row-box">
                                        <span class="dp-row-label">Min. Harga (Rp):</span>
                                        <input type="text" id="dpMinPrice" class="dp-row-input" value="0" oninput="formatRupiahInput(this, 'min')">
                                    </div>
                                    <div class="dp-form-row-box">
                                        <span class="dp-row-label">Maks. Harga (Rp):</span>
                                        <input type="text" id="dpMaxPrice" class="dp-row-input" placeholder="Tak Terbatas" oninput="formatRupiahInput(this, 'max')">
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
                        </div> {{-- Closes #dpCreatePanel --}}

                        <!-- Wizard Footer (Navigation) -->
                        <div class="dp-wizard-footer">
                            <button type="button" class="dp-btn-prev" onclick="cancelDigitalProductWizard()">Batal</button>
                            <div style="display: flex; gap: 10px;">
                                <button type="button" class="dp-btn-prev" id="btn-dp-prev" onclick="prevDigitalProductStep()" style="display: none;">Kembali</button>
                                <button type="button" class="dp-btn-next" id="btn-dp-next" onclick="nextDigitalProductStep()">Lanjut <i class="fas fa-arrow-right" style="margin-left: 8px;"></i></button>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Upload Progress Modal Overlay for Microsite Wizard -->
                    <div id="dpSubmitUploadModal" class="submit-upload-modal-overlay" style="display: none;">
                        <div class="submit-upload-modal-card">
                            <div class="modal-spinner-icon">
                                <i class="fas fa-cloud-upload-alt fa-bounce"></i>
                            </div>
                            <h3 class="modal-title">Mengunggah Produk Digital...</h3>
                            <p class="modal-desc" id="dpSubmitModalDesc">Sedang mengirim berkas produk dan data ke server. Mohon jangan menutup halaman ini.</p>
                            <div class="submit-progress-track">
                                <div class="submit-progress-fill" id="dpSubmitModalProgressFill" style="width: 0%;"></div>
                            </div>
                            <div class="submit-progress-meta">
                                <span id="dpSubmitModalBytesText">0 MB / 0 MB</span>
                                <span id="dpSubmitModalPercentText">0%</span>
                            </div>
                        </div>
                    </div>
                </div> {{-- Closes #digitalProductWizardPanel --}}