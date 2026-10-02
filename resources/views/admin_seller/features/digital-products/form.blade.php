@extends("admin_seller.layouts.app")

@section("page_title", isset($product) ? __('admin.edit_digital_product') : __('admin.add_digital_product'))

@push("styles")
<link rel="stylesheet" href="{{ asset('css/pages/digital-product.css') }}?v={{ file_exists(public_path('css/pages/digital-product.css')) ? filemtime(public_path('css/pages/digital-product.css')) : '1.0' }}">
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
        <div class="product-form-alert alert-success">
            <i class="fas fa-check-circle alert-icon"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (isset($errors) && $errors->any())
        <div class="product-form-alert alert-danger">
            <i class="fas fa-exclamation-circle alert-icon"></i>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(isset($product) && $product->verification_status === 'rejected')
        <div class="product-form-alert alert-warning">
            <i class="fas fa-exclamation-triangle alert-icon"></i>
            <span>{{ __('admin.product_rejected') }}</span>
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

            @include('admin_seller.features.digital-products.partials._step1-details')
            @include('admin_seller.features.digital-products.partials._step2-pricing')
        </div>
    </form>

    @include('admin_seller.features.digital-products.partials._upload-modal')

</div>

<!-- Data Bridge for External JavaScript -->
<script id="product-form-data" type="application/json">
{
    "existingPhotos": @json($existingPhotos ?? []),
    "cleanDescription": @json($cleanDescription ?? ''),
    "hasExistingPlatformFile": {{ !empty($hasInitialPlatformFile) ? 'true' : 'false' }},
    "indexUrl": "{{ route('admin.digital-products.index') }}",
    "selectFileText": "{{ __('admin.select_file') }}"
}
</script>
@endsection

@push("scripts")
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script src="{{ asset('js/pages/digital-product-form.js') }}?v={{ file_exists(public_path('js/pages/digital-product-form.js')) ? filemtime(public_path('js/pages/digital-product-form.js')) : '1.0' }}"></script>
@endpush
