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
                                    <input type="text" id="dpTitle" class="dp-row-input" placeholder="Misal: Template Undangan..." oninput="updateDpTitle(this.value)" required maxlength="200" pattern="[^<>]*" title="Karakter &lt; dan &gt; tidak diperbolehkan untuk mencegah injeksi">
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