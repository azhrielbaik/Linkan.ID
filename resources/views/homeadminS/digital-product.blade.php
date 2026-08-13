@extends("layouts.admin")

@section("page_title", __('admin.digital_product'))

@push("styles")
<link rel="stylesheet" href="{{ asset('css/pages/digital-product.css') }}" data-turbo-track="reload">
@endpush

@section("content")
<div class="dashboard-digital-product-page">

<div class="page-header">
                <h1 class="page-title">{{ __('admin.add_digital_product') }}</h1>
            </div>

            <!-- ✅ Alert messages -->
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

                @csrf
                <div class="content-card">
                    <h2 class="card-title">{{ __('admin.details') }}</h2>

                    <div class="form-group">
                        <label>{{ __('admin.image') }}</label>
                        <div class="image-upload" onclick="document.getElementById('productImage').click()">
                            @if(isset($product) && $product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" style="max-width: 100%; max-height: 100%; border-radius: 5px;">
                            @else
                                <i class="fas fa-image"></i>
                                <span>{{ __('admin.add_image') }}</span>
                            @endif
                        </div>
                        <div class="format-info">{{ __('admin.format_image') }} <strong> {{ __('admin.optimize_image_size') }}</strong> </div>
                        <input type="file" id="productImage" name="image" accept=".png,.jpg,.jpeg" style="display: none">
                    </div>

                    <div class="form-group">
                        <label>{{ __('admin.title') }}</label>
                        <input type="text" name="title" class="form-control" placeholder="{{ __('admin.title') }}" value="{{ isset($product) ? $product->title : old('title') }}">
                    </div>

                    <div class="form-group">
                        <label>{{ __('admin.description') }}</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="{{ __('admin.description') }}">{{ isset($product) ? $product->description : old('description') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>{{ __('admin.platform') }}</label>
                        <div class="platform-container">
                            <div class="platform-options">
                                <button type="button" class="platform-button {{ isset($product) && $product->platform_type == 'upload' ? 'active' : '' }}" data-platform="upload">{{ __('admin.upload') }}</button>
                                <button type="button" class="platform-button {{ isset($product) && $product->platform_type == 'dropbox' ? 'active' : '' }}" data-platform="dropbox">{{ __('admin.dropbox') }}</button>
                                <button type="button" class="platform-button {{ isset($product) && $product->platform_type == 'gdrive' ? 'active' : '' }}" data-platform="gdrive">{{ __('admin.gdrive') }}</button>
                                <button type="button" class="platform-button {{ isset($product) && $product->platform_type == 'other' ? 'active' : '' }}" data-platform="other">{{ __('admin.other') }}</button>
                            </div>
                            <input type="hidden" name="platform_type" value="{{ isset($product) ? $product->platform_type : 'upload' }}">
                            <input type="text" class="form-control platform-input" placeholder="{{ __('admin.enter_url') }}" name="platform_url" style="display: {{ isset($product) && $product->platform_type != 'upload' ? 'block' : 'none' }};" value="{{ isset($product) ? $product->platform_url : old('platform_url') }}">
                            <div class="select-file-button form-control" onclick="document.getElementById('platform_file').click()" style="text-align: center; cursor: pointer; display: {{ isset($product) && $product->platform_type == 'upload' ? 'block' : 'none' }};">
                                <i class="fas fa-paperclip"></i> 
                                <span id="selected-file-name">
                                    @if(isset($product) && $product->platform_file)
                                        {{ basename($product->platform_file) }}
                                    @else
                                        {{ __('admin.select_file') }}
                                    @endif
                                </span>
                            </div>
                            <input type="file" id="platform_file" name="platform_file" style="display: none;">
                        </div>
                    </div>
                </div>

                <div class="content-card">
                    <h2 class="card-title">{{ __('admin.pricing') }}</h2>

                    <div class="form-group">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                            <label style="margin: 0;">{{ __('admin.allow_pay_what_want') }}</label>
                            <!-- ✅ Hidden input + checkbox -->
                            <input type="hidden" name="pay_what_want" value="0">
                            <label class="toggle-switch">
                                <input type="checkbox" name="pay_what_want" value="1" {{ old('pay_what_want') ? 'checked' : '' }}>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="price-group">
                            <div class="price-input">
                                <label>{{ __('admin.price') }}</label>
                                <input type="text" name="price" id="priceInput" class="form-control" placeholder="Rp 0" value="{{ isset($product) ? 'Rp ' . number_format($product->price, 0, ',', '.') : old('price') }}">
                                <input type="hidden" name="price_raw" id="priceRaw" value="{{ isset($product) ? $product->price : old('price') }}">
                            </div>
                            <div class="currency-input">
                                <label>{{ __('admin.currency') }}</label>
                                <input type="text" name="currency" class="form-control" value="IDR" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>{{ __('admin.purchase_button') }}</label>
                        <select name="button_text" class="select-dropdown">
                            <option value="buy_now" {{ (isset($product) && $product->button_text == 'buy_now') || old('button_text') == 'buy_now' ? 'selected' : '' }}>{{ __('admin.buy_now') }}</option>
                            <option value="purchase" {{ (isset($product) && $product->button_text == 'purchase') || old('button_text') == 'purchase' ? 'selected' : '' }}>{{ __('admin.purchase') }}</option>
                            <option value="get_now" {{ (isset($product) && $product->button_text == 'get_now') || old('button_text') == 'get_now' ? 'selected' : '' }}>{{ __('admin.get_now') }}</option>
                        </select>
                    </div>
                </div>

                <div class="action-buttons">
                    <button type="button" class="cancel-button" onclick="history.back()">{{ __('admin.cancel') }}</button>
                    <button type="submit" class="add-product-button">{{ __('admin.add_product') }}</button>
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

        // Image preview
        document.getElementById('productImage').addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imageUpload = document.querySelector('.image-upload');
                    imageUpload.innerHTML = `<img src="${e.target.result}" style="max-width: 100%; max-height: 100%; border-radius: 5px;">`;
                }
                reader.readAsDataURL(e.target.files[0]);
            }
        });

        // Platform file selection
        document.getElementById('platform_file').addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : 'Select File';
            document.getElementById('selected-file-name').textContent = fileName;
        });

        // Platform button selection
        document.querySelectorAll('.platform-button').forEach(button => {
            button.addEventListener('click', function() {
                document.querySelectorAll('.platform-button').forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');

                const platform = this.getAttribute('data-platform');
                const urlInput = document.querySelector('.platform-input');
                const fileButton = document.querySelector('.select-file-button');

                document.querySelector('input[name="platform_type"]').value = platform;

                if (platform === 'upload') {
                    urlInput.style.display = 'none';
                    fileButton.style.display = 'block';
                } else {
                    urlInput.style.display = 'block';
                    fileButton.style.display = 'none';
                }
            });
        });
</script>
@endpush
