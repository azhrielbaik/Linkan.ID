<!-- Step 1: Details -->
<div id="step1-content" class="step-content active">
    
    <div class="section-label">Informasi Dasar</div>
    
    <div class="form-row-box">
        <span class="row-label">{{ __('admin.title') }}:</span>
        <input type="text" name="title" class="row-input" placeholder="Masukkan nama produk..." value="{{ isset($product) ? $product->title : old('title') }}">
    </div>

    <div class="form-row-box textarea-box form-row-block">
        <span class="row-label textarea-label">{{ __('admin.description') }}:</span>
        <div class="editor-container">
            <div id="descriptionEditor"></div>
        </div>
        <input type="hidden" name="description" id="descriptionInput" value="{{ $cleanDescription }}">
    </div>

    {{-- Foto / Gambar Produk --}}
    <div class="product-photos-container mt-section">
        <div class="section-label">Foto / Gambar Produk</div>
        <p class="photo-helper-text">
            Unggah foto produk Anda (maksimal 5 foto). Format JPG, PNG, WEBP hingga 5MB per foto. Foto urutan pertama akan otomatis dijadikan foto sampul utama.
        </p>

        {{-- Empty Dropzone (tampil ketika belum ada foto) --}}
        <div id="photoEmptyDropzone" class="upload-box photo-empty-dropzone cursor-pointer" onclick="document.getElementById('productPhotosInput').click()" style="display: {{ count($existingPhotos) === 0 ? 'block' : 'none' }};">
            <i class="fas fa-images"></i>
            <h4>Pilih atau seret foto produk ke sini</h4>
            <p>Format JPG, PNG, WEBP. Maksimal 5 foto (maks 5MB/foto).</p>
        </div>

        {{-- Grid Foto Produk --}}
        <div id="productPhotosGrid" class="product-photos-grid" style="display: {{ count($existingPhotos) > 0 ? 'grid' : 'none' }};">
            <!-- Diisi otomatis oleh JavaScript -->
        </div>

        <div id="photoErrorMessage" class="photo-error-message"></div>

        {{-- Input file tersembunyi untuk upload foto baru --}}
        <input type="file" id="productPhotosInput" name="photos[]" accept="image/png,image/jpeg,image/jpg,image/webp" multiple style="display: none;">
        {{-- Input hidden untuk tracking foto lama yang dipertahankan --}}
        <input type="hidden" name="existing_media" id="existingMediaInput" value="{{ json_encode($existingPhotos) }}">
    </div>

    <div class="section-label mt-section">Pilih Platform</div>
    <div class="platform-cards-grid">
        
        <label class="platform-card-wrapper {{ (isset($product) && $product->platform_type == 'upload') || !isset($product) ? 'active' : '' }}">
            <input type="radio" name="platform_type" value="upload" {{ (isset($product) && $product->platform_type == 'upload') || !isset($product) ? 'checked' : '' }}>
            <div class="platform-card">
                <div class="platform-card-content">
                    <div class="platform-card-header">
                        <span class="platform-card-title">{{ __('admin.upload') }}</span>
                        <span class="platform-radio-circle"></span>
                    </div>
                    <p class="platform-card-desc">Unggah file langsung dari perangkat Anda secara aman.</p>
                    <div class="platform-card-footer"><i class="fas fa-file-upload"></i> Maks 50MB</div>
                </div>
            </div>
        </label>

        <label class="platform-card-wrapper {{ isset($product) && $product->platform_type == 'dropbox' ? 'active' : '' }}">
            <input type="radio" name="platform_type" value="dropbox" {{ isset($product) && $product->platform_type == 'dropbox' ? 'checked' : '' }}>
            <div class="platform-card">
                <div class="platform-card-content">
                    <div class="platform-card-header">
                        <span class="platform-card-title">{{ __('admin.dropbox') }}</span>
                        <span class="platform-radio-circle"></span>
                    </div>
                    <p class="platform-card-desc">Tautkan file produk digital dari akun Dropbox Anda.</p>
                    <div class="platform-card-footer"><i class="fab fa-dropbox"></i> Tautan Eksternal</div>
                </div>
            </div>
        </label>

        <label class="platform-card-wrapper {{ isset($product) && $product->platform_type == 'gdrive' ? 'active' : '' }}">
            <input type="radio" name="platform_type" value="gdrive" {{ isset($product) && $product->platform_type == 'gdrive' ? 'checked' : '' }}>
            <div class="platform-card">
                <div class="platform-card-content">
                    <div class="platform-card-header">
                        <span class="platform-card-title">{{ __('admin.gdrive') }}</span>
                        <span class="platform-radio-circle"></span>
                    </div>
                    <p class="platform-card-desc">Gunakan tautan Google Drive untuk membagikan file besar.</p>
                    <div class="platform-card-footer"><i class="fab fa-google-drive"></i> Tautan Eksternal</div>
                </div>
            </div>
        </label>

        <label class="platform-card-wrapper {{ isset($product) && $product->platform_type == 'other' ? 'active' : '' }}">
            <input type="radio" name="platform_type" value="other" {{ isset($product) && $product->platform_type == 'other' ? 'checked' : '' }}>
            <div class="platform-card">
                <div class="platform-card-content">
                    <div class="platform-card-header">
                        <span class="platform-card-title">{{ __('admin.other') }}</span>
                        <span class="platform-radio-circle"></span>
                    </div>
                    <p class="platform-card-desc">Gunakan tautan khusus dari platform lain yang Anda miliki.</p>
                    <div class="platform-card-footer"><i class="fas fa-link"></i> URL Kustom</div>
                </div>
            </div>
        </label>
    </div>

    <div id="url-input-container" class="form-row-box" style="display: {{ isset($product) && $product->platform_type != 'upload' ? 'flex' : 'none' }};">
        <span class="row-label">Tautan URL:</span>
        <input type="text" class="row-input" placeholder="{{ __('admin.enter_url') }}" name="platform_url" value="{{ isset($product) ? $product->platform_url : old('platform_url') }}">
    </div>
    
    <div id="file-input-container" style="display: {{ (isset($product) && $product->platform_type == 'upload') || !isset($product) ? 'block' : 'none' }};">
        <div class="form-row-box file-picker-row cursor-pointer" onclick="document.getElementById('platform_file').click()">
            <span class="row-label file-picker-label">Pilih File Produk:</span>
            <div class="file-picker-selected">
                <i class="fas fa-paperclip"></i>
                <span id="selected-file-name">
                    @if(isset($product) && ($product->platform_file || $product->deliverable_url))
                        {{ basename($product->platform_file ?: $product->deliverable_url) }}
                    @else
                        {{ __('admin.select_file') }}
                    @endif
                </span>
            </div>
        </div>

        <!-- Progress Bar Kartu Berkas Produk -->
        <div id="platformFileProgressCard" class="file-upload-progress-card" style="display: {{ $hasInitialPlatformFile ? 'block' : 'none' }};">
            <div class="file-progress-header">
                <div class="file-info-group">
                    <div class="file-icon-wrapper {{ $hasInitialPlatformFile ? 'success' : '' }}" id="platformFileIconWrapper">
                        <i class="fas fa-file-alt" id="platformFileIcon"></i>
                    </div>
                    <div class="file-text-details">
                        <div class="file-name" id="platformFileNameDisplay">
                            {{ $hasInitialPlatformFile && isset($product) ? basename($product->platform_file ?: $product->deliverable_url) : 'Nama Berkas' }}
                        </div>
                        <div class="file-meta" id="platformFileSizeDisplay">
                            {{ $hasInitialPlatformFile ? 'File Tersimpan di Server' : '0 KB' }}
                        </div>
                    </div>
                </div>
                <div class="file-status-badge {{ $hasInitialPlatformFile ? 'success' : '' }}" id="platformFileStatusBadge">
                    <span class="badge-dot {{ $hasInitialPlatformFile ? 'success' : '' }}" id="platformFileBadgeDot"></span>
                    <span id="platformFileStatusText">{{ $hasInitialPlatformFile ? 'Terupload (100%)' : 'Siap Diunggah' }}</span>
                </div>
                <button type="button" class="btn-remove-file" onclick="removePlatformFile()" aria-label="Hapus Berkas" title="Hapus Berkas">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="file-progress-track">
                <div class="file-progress-fill {{ $hasInitialPlatformFile ? 'success' : '' }}" id="platformFileProgressFill" style="width: {{ $hasInitialPlatformFile ? '100%' : '0%' }};"></div>
            </div>
        </div>
    </div>
    <input type="file" id="platform_file" name="platform_file" style="display: none;">
    <input type="hidden" name="remove_platform_file" id="removePlatformFileInput" value="0">

    <div class="action-buttons space-between">
        <a href="{{ route('admin.digital-products.index') }}" class="btn-prev btn-prev-link">
            <i class="fas fa-arrow-left btn-icon-left"></i> Kembali ke Toko
        </a>
        <button type="button" class="btn-next" id="btnNextStep1" onclick="nextStep()" disabled>
            Lanjut <i class="fas fa-arrow-right btn-icon-right"></i>
        </button>
    </div>
</div>
