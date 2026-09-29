@extends("admin_seller.layouts.app")

@section("page_title", isset($product) ? __('admin.edit_digital_product') : __('admin.add_digital_product'))

@push("styles")
<link rel="stylesheet" href="{{ asset('css/pages/digital-product.css') }}?v={{ time() }}" data-turbo-track="reload">
<link rel="stylesheet" href="https://cdn.quilljs.com/1.3.6/quill.snow.css">
@endpush

@section("content")
<div class="dashboard-digital-product-page">

    <div class="page-header">
        <div class="header-title-group">
            <a href="{{ route('admin.digital-products.index') }}" class="btn-back-circle" title="Kembali ke Toko">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="page-title">{{ isset($product) ? __('admin.edit_digital_product') : __('admin.add_digital_product') }}</h1>
        </div>
        <a href="{{ route('admin.digital-products.index') }}" class="btn-back-link">
            <i class="fas fa-arrow-left"></i> Kembali ke Toko
        </a>
    </div>

    <!-- Alerts -->
    @if (session('success'))
        <div class="alert alert-success" style="background: #e0ffe0; padding: 10px; border-radius: 5px; margin-bottom: 20px; color: #007500;">
            {{ session('success') }}
        </div>
    @endif

    @if (isset($errors) && $errors->any())
        <div class="alert alert-danger" style="background: #ffe3e3; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
            <ul style="margin: 0; padding-left: 20px; color: #b30000;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(isset($product) && $product->verification_status === 'rejected')
        <div class="alert alert-warning" style="background: #fff3cd; padding: 10px; border-radius: 5px; margin-bottom: 20px; color: #856404;">
            <i class="fas fa-exclamation-triangle"></i> {{ __('admin.product_rejected') }}
        </div>
    @endif
    
    <form id="digitalProductForm"
          action="{{ isset($product) ? route('admin.digital-products.update', $product->id) : route('admin.digital-products.store') }}"
          method="POST" enctype="multipart/form-data">
        @csrf
        @if (isset($product))
            @method('PUT')
        @endif

        <div class="main-form-card">
            <!-- Stepper UI -->
            <div class="stepper-wrapper">
                <div class="stepper-item active" id="step1-indicator">
                    <div class="stepper-circle">
                        <i class="fas fa-check" id="step1-icon" style="display:none;"></i>
                        <span id="step1-num">1</span>
                    </div>
                    <div class="stepper-label">Detail Produk</div>
                </div>
                <div class="stepper-line"></div>
                <div class="stepper-item" id="step2-indicator">
                    <div class="stepper-circle">
                        <i class="fas fa-check" id="step2-icon" style="display:none;"></i>
                        <span id="step2-num">2</span>
                    </div>
                    <div class="stepper-label">Harga</div>
                </div>
            </div>

            <!-- Step 1: Details -->
            <div id="step1-content" class="step-content active">
                
                <div class="section-label">Informasi Dasar</div>
                
                <div class="form-row-box">
                    <span class="row-label">{{ __('admin.title') }}:</span>
                    <input type="text" name="title" class="row-input" placeholder="Masukkan nama produk..." value="{{ isset($product) ? $product->title : old('title') }}">
                </div>

                <div class="form-row-box textarea-box" style="display: block;">
                    <span class="row-label" style="display: block; margin-bottom: 8px;">{{ __('admin.description') }}:</span>
                    @php
                        // Bersihkan tag kotor: hapus style inline, span berlebih, font, dsb.
                        $rawDescription = isset($product) ? ($product->description ?? '') : old('description', '');
                        $cleanDescription = $rawDescription;
                        if (!empty($cleanDescription)) {
                            // Hapus atribut style inline
                            $cleanDescription = preg_replace('/\s*style\s*=\s*(["\']).*?\1/i', '', $cleanDescription);
                            // Hapus tag span namun pertahankan teks di dalamnya
                            $cleanDescription = preg_replace('/<\/?span[^>]*>/i', '', $cleanDescription);
                            // Hapus tag font jika ada
                            $cleanDescription = preg_replace('/<\/?font[^>]*>/i', '', $cleanDescription);
                        }
                    @endphp
                    <div style="width: 100%;">
                        <div id="descriptionEditor"></div>
                    </div>
                    <input type="hidden" name="description" id="descriptionInput" value="{{ $cleanDescription }}">
                </div>

                {{-- Foto / Gambar Produk --}}
                @php
                    $existingPhotos = [];
                    if (isset($product)) {
                        $mf = is_string($product->media_files) ? json_decode($product->media_files, true) : $product->media_files;
                        if (is_array($mf) && count($mf) > 0) {
                            foreach ($mf as $item) {
                                $path = $item['path'] ?? $item['url'] ?? null;
                                if ($path) {
                                    $url = Str::startsWith($path, ['http://', 'https://', 'data:image/', '/storage/']) ? $path : asset('storage/' . $path);
                                    $existingPhotos[] = [
                                        'path' => $path,
                                        'url'  => $url,
                                    ];
                                }
                            }
                        }
                        if (empty($existingPhotos) && !empty($product->image)) {
                            $url = Str::startsWith($product->image, ['http://', 'https://', 'data:image/', '/storage/']) ? $product->image : asset('storage/' . $product->image);
                            $existingPhotos[] = [
                                'path' => $product->image,
                                'url'  => $url,
                            ];
                        }
                    }
                @endphp

                <div class="product-photos-container" style="margin-top: 28px;">
                    <div class="section-label">Foto / Gambar Produk</div>
                    <p class="photo-helper-text">
                        Unggah foto produk Anda (maksimal 5 foto). Format JPG, PNG, WEBP hingga 5MB per foto. Foto urutan pertama akan otomatis dijadikan foto sampul utama.
                    </p>

                    {{-- Empty Dropzone (tampil ketika belum ada foto) --}}
                    <div id="photoEmptyDropzone" class="upload-box photo-empty-dropzone" onclick="document.getElementById('productPhotosInput').click()" style="display: {{ count($existingPhotos) === 0 ? 'block' : 'none' }}; cursor: pointer;">
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

                <div class="section-label" style="margin-top: 28px;">Pilih Platform</div>
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
                    <div class="form-row-box" onclick="document.getElementById('platform_file').click()" style="cursor: pointer; justify-content: space-between;">
                        <span class="row-label" style="min-width: auto; margin-right: 0;">Pilih File Produk</span>
                        <div style="flex: 1; text-align: right; color: #9ca3af; font-size: 14px;">
                            <i class="fas fa-paperclip" style="margin-right: 5px;"></i>
                            <span id="selected-file-name">
                                @if(isset($product) && $product->platform_file)
                                    {{ basename($product->platform_file) }}
                                @else
                                    {{ __('admin.select_file') }}
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
                <input type="file" id="platform_file" name="platform_file" style="display: none;">

                <div class="action-buttons space-between">
                    <a href="{{ route('admin.digital-products.index') }}" class="btn-prev" style="text-decoration: none; display: inline-flex; align-items: center;">
                        <i class="fas fa-arrow-left" style="margin-right: 8px;"></i> Kembali ke Toko
                    </a>
                    <button type="button" class="btn-next" onclick="nextStep()">Lanjut <i class="fas fa-arrow-right" style="margin-left: 8px;"></i></button>
                </div>
            </div>

            <!-- Step 2: Pricing -->
            <div id="step2-content" class="step-content" style="display: none;">
                
                <div class="section-label">Pengaturan Harga</div>

                <div class="form-row-box">
                    <div style="flex: 1; display: flex; align-items: center; justify-content: space-between; width: 100%;">
                        <div>
                            <span class="row-label" style="display: inline-block; min-width: auto;">{{ __('admin.allow_pay_what_want') }}</span>
                            <span style="font-size: 13px; color: #6b7280; margin-left: 10px;">Izinkan pembeli menentukan harga sendiri</span>
                        </div>
                        <input type="hidden" name="pay_what_want" value="0">
                        <label class="toggle-switch">
                            <input type="checkbox" name="pay_what_want" value="1" {{ old('pay_what_want') || (isset($product) && $product->pay_what_want) ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>

                <div class="form-row-box">
                    <span class="row-label">{{ __('admin.price') }}:</span>
                    <input type="text" name="price" id="priceInput" class="row-input" placeholder="Rp 0" value="{{ isset($product) ? 'Rp ' . number_format($product->price, 0, ',', '.') : old('price') }}">
                    <input type="hidden" name="price_raw" id="priceRaw" value="{{ isset($product) ? $product->price : old('price') }}">
                </div>

                <div class="form-row-box">
                    <span class="row-label">{{ __('admin.currency') }}:</span>
                    <input type="text" name="currency" class="row-input" value="IDR" readonly style="color: #6b7280;">
                </div>

                <div class="section-label" style="margin-top: 24px;">Tombol Beli</div>
                <div class="form-row-box">
                    <span class="row-label">{{ __('admin.purchase_button') }}:</span>
                    <select name="button_text" class="select-dropdown">
                        <option value="buy_now" {{ (isset($product) && $product->button_text == 'buy_now') || old('button_text') == 'buy_now' ? 'selected' : '' }}>{{ __('admin.buy_now') }}</option>
                        <option value="purchase" {{ (isset($product) && $product->button_text == 'purchase') || old('button_text') == 'purchase' ? 'selected' : '' }}>{{ __('admin.purchase') }}</option>
                        <option value="get_now" {{ (isset($product) && $product->button_text == 'get_now') || old('button_text') == 'get_now' ? 'selected' : '' }}>{{ __('admin.get_now') }}</option>
                    </select>
                </div>

                <div class="action-buttons space-between">
                    <button type="button" class="btn-prev" onclick="prevStep()"><i class="fas fa-arrow-left" style="margin-right: 8px;"></i> Kembali</button>
                    <button type="submit" class="add-product-button">{{ isset($product) ? __('admin.save_changes') : __('admin.add_product') }} <i class="fas fa-check" style="margin-left: 8px;"></i></button>
                </div>
            </div>

        </div>
    </form>

</div>
@endsection

@push("scripts")
<script>
// Format currency Rupiah
function formatRupiah(angka) {
    var number_string = angka.replace(/[^,\d]/g, '').toString(),
        split = number_string.split(','),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return 'Rp ' + rupiah;
}

function unformatRupiah(rupiah) {
    return rupiah.replace(/[^\d]/g, '');
}

// Price input formatting
const priceInput = document.getElementById('priceInput');
const priceRaw = document.getElementById('priceRaw');

if (priceInput) {
    priceInput.addEventListener('input', function(e) {
        let value = e.target.value;
        let unformatted = unformatRupiah(value);
        
        if (unformatted !== '') {
            let formatted = formatRupiah(unformatted);
            e.target.value = formatted;
            priceRaw.value = unformatted;
        } else {
            e.target.value = '';
            priceRaw.value = '';
        }
    });

    priceInput.addEventListener('blur', function(e) {
        let value = e.target.value;
        if (value === '' || value === 'Rp ') {
            e.target.value = '';
            priceRaw.value = '';
        }
    });
}

// ============================================================
// Product Photos Management (Multi-photo upload & existing photos)
// ============================================================
(function() {
    var existingPhotos = @json($existingPhotos) || [];
    var stagedFiles = [];
    var maxPhotos = 5;

    var fileInput = document.getElementById('productPhotosInput');
    var existingInput = document.getElementById('existingMediaInput');
    var gridEl = document.getElementById('productPhotosGrid');
    var dropzoneEl = document.getElementById('photoEmptyDropzone');
    var errorEl = document.getElementById('photoErrorMessage');

    function showError(msg) {
        if (!errorEl) return;
        errorEl.textContent = msg;
        errorEl.style.display = 'block';
        setTimeout(function() {
            errorEl.style.display = 'none';
        }, 5000);
    }

    function syncFileInput() {
        if (!fileInput) return;
        try {
            var dt = new DataTransfer();
            stagedFiles.forEach(function(file) {
                dt.items.add(file);
            });
            fileInput.files = dt.files;
        } catch (e) {
            console.error('DataTransfer error:', e);
        }
    }

    function syncExistingInput() {
        if (!existingInput) return;
        existingInput.value = JSON.stringify(existingPhotos);
    }

    window.removeExistingPhoto = function(index) {
        if (index >= 0 && index < existingPhotos.length) {
            existingPhotos.splice(index, 1);
            syncExistingInput();
            renderGallery();
        }
    };

    window.removeStagedPhoto = function(index) {
        if (index >= 0 && index < stagedFiles.length) {
            stagedFiles.splice(index, 1);
            syncFileInput();
            renderGallery();
        }
    };

    function renderGallery() {
        if (!gridEl || !dropzoneEl) return;

        var totalPhotos = existingPhotos.length + stagedFiles.length;

        if (totalPhotos === 0) {
            gridEl.innerHTML = '';
            gridEl.style.display = 'none';
            dropzoneEl.style.display = 'block';
            return;
        }

        dropzoneEl.style.display = 'none';
        gridEl.style.display = 'grid';
        gridEl.innerHTML = '';

        var globalIndex = 0;

        // Render existing photos
        existingPhotos.forEach(function(item, idx) {
            var isCover = (globalIndex === 0);
            var card = document.createElement('div');
            card.className = 'photo-item-card' + (isCover ? ' is-cover' : '');

            var img = document.createElement('img');
            img.src = item.url;
            img.alt = 'Foto Produk';
            card.appendChild(img);

            if (isCover) {
                var badge = document.createElement('span');
                badge.className = 'photo-cover-badge';
                badge.innerHTML = '<i class="fas fa-star"></i> Sampul';
                card.appendChild(badge);
            }

            var delBtn = document.createElement('button');
            delBtn.type = 'button';
            delBtn.className = 'photo-btn-delete';
            delBtn.title = 'Hapus foto ini';
            delBtn.innerHTML = '<i class="fas fa-trash-alt"></i>';
            delBtn.onclick = function(e) {
                e.stopPropagation();
                window.removeExistingPhoto(idx);
            };
            card.appendChild(delBtn);

            gridEl.appendChild(card);
            globalIndex++;
        });

        // Render staged (newly selected) photos
        stagedFiles.forEach(function(file, idx) {
            var isCover = (globalIndex === 0);
            var card = document.createElement('div');
            card.className = 'photo-item-card' + (isCover ? ' is-cover' : '');

            var img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.alt = file.name;
            img.onload = function() {
                URL.revokeObjectURL(this.src);
            };
            card.appendChild(img);

            if (isCover) {
                var badge = document.createElement('span');
                badge.className = 'photo-cover-badge';
                badge.innerHTML = '<i class="fas fa-star"></i> Sampul';
                card.appendChild(badge);
            } else {
                var newBadge = document.createElement('span');
                newBadge.className = 'photo-new-badge';
                newBadge.textContent = 'Baru';
                card.appendChild(newBadge);
            }

            var delBtn = document.createElement('button');
            delBtn.type = 'button';
            delBtn.className = 'photo-btn-delete';
            delBtn.title = 'Hapus foto ini';
            delBtn.innerHTML = '<i class="fas fa-trash-alt"></i>';
            delBtn.onclick = function(e) {
                e.stopPropagation();
                window.removeStagedPhoto(idx);
            };
            card.appendChild(delBtn);

            gridEl.appendChild(card);
            globalIndex++;
        });

        // If fewer than maxPhotos, show "+ Tambah Foto" card
        if (totalPhotos < maxPhotos) {
            var addCard = document.createElement('div');
            addCard.className = 'photo-add-card';
            addCard.innerHTML = '<i class="fas fa-plus"></i><span>Tambah Foto<br><small style="color:#94a3b8;">(' + totalPhotos + '/' + maxPhotos + ')</small></span>';
            addCard.onclick = function() {
                if (fileInput) fileInput.click();
            };
            gridEl.appendChild(addCard);
        }
    }

    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            var selectedFiles = Array.from(e.target.files || []);
            if (selectedFiles.length === 0) return;

            var currentTotal = existingPhotos.length + stagedFiles.length;
            var availableSlots = maxPhotos - currentTotal;

            if (availableSlots <= 0) {
                showError('Maksimal ' + maxPhotos + ' foto produk sudah tercapai.');
                fileInput.value = '';
                return;
            }

            if (selectedFiles.length > availableSlots) {
                showError('Hanya dapat menambahkan ' + availableSlots + ' foto lagi. ' + (selectedFiles.length - availableSlots) + ' foto dilewati.');
                selectedFiles = selectedFiles.slice(0, availableSlots);
            }

            var allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];

            selectedFiles.forEach(function(file) {
                if (!allowedTypes.includes(file.type.toLowerCase())) {
                    showError('Format file ' + file.name + ' tidak didukung. Gunakan JPG, PNG, atau WEBP.');
                    return;
                }
                if (file.size > 5 * 1024 * 1024) {
                    showError('Ukuran file ' + file.name + ' melebihi batas 5MB.');
                    return;
                }
                stagedFiles.push(file);
            });

            syncFileInput();
            renderGallery();
        });
    }

    // Initial render
    renderGallery();
})();

// Platform file selection
document.getElementById('platform_file').addEventListener('change', function(e) {
    const fileName = e.target.files[0] ? e.target.files[0].name : 'Select File';
    document.getElementById('selected-file-name').textContent = fileName;
});

// Platform button selection (Radio Cards)
document.querySelectorAll('input[name="platform_type"]').forEach(radio => {
    radio.addEventListener('change', function() {
        // Remove active class from all wrappers
        document.querySelectorAll('.platform-card-wrapper').forEach(wrapper => {
            wrapper.classList.remove('active');
        });
        
        // Add active class to checked radio wrapper
        if (this.checked) {
            this.closest('.platform-card-wrapper').classList.add('active');
        }

        const platform = this.value;
        const urlInput = document.getElementById('url-input-container');
        const fileButton = document.getElementById('file-input-container');

        if (platform === 'upload') {
            urlInput.style.display = 'none';
            fileButton.style.display = 'block';
        } else {
            urlInput.style.display = 'flex';
            fileButton.style.display = 'none';
        }
    });
});

// Stepper Logic
function nextStep() {
    // Basic validation for step 1
    const titleInput = document.querySelector('input[name="title"]').value;
    if(!titleInput) {
        alert("Judul produk tidak boleh kosong.");
        return;
    }
    
    document.getElementById('step1-content').style.display = 'none';
    document.getElementById('step2-content').style.display = 'block';
    
    // Update Stepper UI
    const step1Indicator = document.getElementById('step1-indicator');
    step1Indicator.classList.remove('active');
    step1Indicator.classList.add('completed');
    document.getElementById('step1-num').style.display = "none";
    document.getElementById('step1-icon').style.display = "inline-block";

    const step2Indicator = document.getElementById('step2-indicator');
    step2Indicator.classList.add('active');
}

function prevStep() {
    document.getElementById('step2-content').style.display = 'none';
    document.getElementById('step1-content').style.display = 'block';
    
    // Update Stepper UI
    const step1Indicator = document.getElementById('step1-indicator');
    step1Indicator.classList.remove('completed');
    step1Indicator.classList.add('active');
    document.getElementById('step1-icon').style.display = "none";
    document.getElementById('step1-num').style.display = "inline-block";

    const step2Indicator = document.getElementById('step2-indicator');
    step2Indicator.classList.remove('active');
}
</script>

<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
// ============================================================
// Quill Rich Text Editor — Description Field (Matching Microsite)
// ============================================================
(function() {
    var editorElement = document.getElementById('descriptionEditor');
    if (!editorElement) return;

    // Cegah duplikasi toolbar jika terjadi reload Turbo/PJAX
    if (editorElement.previousSibling && editorElement.previousSibling.classList && editorElement.previousSibling.classList.contains('ql-toolbar')) {
        return;
    }

    var toolbarOptions = [
        [{ 'font': [] }, { 'size': ['small', false, 'large', 'huge'] }],
        ['bold', 'italic', 'underline'],
        [{ 'background': [] }],
        [{ 'list': 'bullet' }, { 'list': 'ordered' }],
        [{ 'align': [] }],
        ['link']
    ];

    var quill = new Quill('#descriptionEditor', {
        theme: 'snow',
        modules: {
            toolbar: toolbarOptions
        },
        placeholder: 'Tuliskan deskripsi lengkap produk digital Anda...'
    });

    // Inisialisasi konten awal bersih
    var initialContent = @json($cleanDescription);
    if (initialContent && initialContent.trim() !== '') {
        if (/<[a-z][\s\S]*>/i.test(initialContent)) {
            quill.clipboard.dangerouslyPasteHTML(initialContent);
        } else {
            quill.setText(initialContent);
        }
    }

    var descInput = document.getElementById('descriptionInput');

    function syncQuillContent() {
        if (!descInput) return;
        var text = quill.getText().trim();
        if (text.length === 0) {
            descInput.value = '';
        } else {
            descInput.value = quill.root.innerHTML;
        }
    }

    quill.on('text-change', syncQuillContent);

    var form = document.getElementById('digitalProductForm');
    if (form) {
        form.addEventListener('submit', function() {
            syncQuillContent();
        });
    }
})();
</script>
@endpush
