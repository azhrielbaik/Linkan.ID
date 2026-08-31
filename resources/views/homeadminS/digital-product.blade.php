@extends("layouts.admin")

@section("page_title", __('admin.digital_product'))

@push("styles")
<link rel="stylesheet" href="{{ asset('css/pages/digital-product.css') }}?v={{ time() }}" data-turbo-track="reload">
@endpush

@section("content")
<div class="dashboard-digital-product-page">

    <div class="page-header">
        <h1 class="page-title">{{ isset($product) ? __('admin.edit_digital_product') : __('admin.add_digital_product') }}</h1>
    </div>

    <!-- Alerts -->
    @if (session('success'))
        <div class="alert alert-success" style="background: #e0ffe0; padding: 10px; border-radius: 5px; margin-bottom: 20px; color: #007500;">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
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

                <div class="form-row-box textarea-box">
                    <span class="row-label">{{ __('admin.description') }}:</span>
                    <textarea name="description" class="row-textarea" placeholder="Deskripsi produk...">{{ isset($product) ? $product->description : old('description') }}</textarea>
                </div>

                <div class="section-label" style="margin-top: 24px;">Pilih Platform</div>
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
                
                <div class="section-label" style="margin-top: 24px;">Sertifikat / File Tambahan (Opsional)</div>
                <div class="upload-box" onclick="document.getElementById('productImage').click()">
                    <div id="image-upload-placeholder" style="display: {{ (isset($product) && $product->image) ? 'none' : 'block' }}">
                        <i class="fas fa-file-image"></i>
                        <h4>Click to upload or drag and drop</h4>
                        <p>Format JPG, PNG. {{ __('admin.optimize_image_size') }}</p>
                    </div>
                    <img id="image-upload-preview" class="image-upload-preview" src="{{ isset($product) && $product->image ? asset('storage/' . $product->image) : '' }}" style="display: {{ (isset($product) && $product->image) ? 'block' : 'none' }}">
                </div>
                <input type="file" id="productImage" name="image" accept=".png,.jpg,.jpeg" style="display: none">
                
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

                <div class="action-buttons">
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

// Image preview
document.getElementById('productImage').addEventListener('change', function(e) {
    if (e.target.files && e.target.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('image-upload-placeholder').style.display = 'none';
            const imgPreview = document.getElementById('image-upload-preview');
            imgPreview.src = e.target.result;
            imgPreview.style.display = 'block';
        }
        reader.readAsDataURL(e.target.files[0]);
    }
});

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
@endpush
