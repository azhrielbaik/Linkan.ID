@php
    $uniqueId = uniqid('dp_editor_');
    $elementId = 'digitalproduct_' . $digitalProduct->id;
    $isActive = $digitalProduct->is_active ?? true;
    $priceDisplay = $digitalProduct->pricing_type === 'fixed' 
                    ? 'Rp ' . number_format($digitalProduct->price, 0, ',', '.') 
                    : 'Mulai dari Rp ' . number_format($digitalProduct->price_min, 0, ',', '.');
    $deliverableText = $digitalProduct->deliverable_type === 'upload' ? 'File Upload' : 'Tautan Akses';
@endphp
<div id="{{ $elementId }}" class="draggable-element-block {{ $isActive ? '' : 'block-inactive' }}" data-element-type="digital_product" data-db-id="{{ $digitalProduct->id }}">
    <div class="block-item-card js-toggle-edit-form" data-type="DigitalProduct" data-target-id="{{ $elementId }}">
        <i class="fas fa-grip-vertical drag-handle drag-handle-icon js-stop-propagation" title="Tarik ke atas/bawah untuk ubah urutan"></i>
        <div class="block-item-icon-wrapper">
            <i class="fas fa-box" style="color: #F97316;"></i>
        </div>
        <div class="block-item-content">
            <div class="block-item-title-wrapper">
                <span>Produk Digital: {{ $digitalProduct->title }}</span>
            </div>
        </div>
        <div class="block-item-actions js-stop-propagation" >
            <div class="element-visibility-container">
                <span class="visibility-status-text {{ $isActive ? 'status-active' : 'status-inactive' }}" id="statusText_{{ $elementId }}">{{ $isActive ? 'Aktif' : 'Nonaktif' }}</span>
                <label class="toggle-switch">
                    <input class="js-toggle-visibility" type="checkbox" id="visibilitySwitch_{{ $elementId }}" data-target-id="{{ $elementId }}" {{ $isActive ? 'checked' : '' }}>
                    <span class="toggle-slider"></span>
                </label>
            </div>
            <button type="button" class="btn-element-action btn-delete-icon" onclick="deleteDynamicDigitalProduct({{ $digitalProduct->id }})" title="Hapus Produk">
                <i class="fas fa-trash-alt"></i>
            </button>
            <button type="button" data-type="DigitalProduct" data-target-id="{{ $elementId }}" class="btn-edit-block js-toggle-edit-form">
                <i class="fas fa-pen" class="btn-edit-icon"></i> <span id="btnText_{{ $elementId }}">Detail</span>
            </button>
        </div>
    </div>

    <div id="formBody_{{ $elementId }}" class="edit-form-body" style="max-height: 0; opacity: 0; margin-top: 0;">
        <div class="profile-form-padding">
            <div class="profile-form-header" style="margin-bottom: 16px;">
                <i class="fas fa-box dynamic-setting-header-icon-blue"></i> Detail Produk Digital
            </div>
            
            <div class="element-body p-3" style="background: #f8fafc; font-size: 13px; color: #64748b; border-radius: 8px; border: 1px solid #e2e8f0;">
                <div class="d-flex justify-content-between mb-2">
                    <div><strong>Harga:</strong> {{ $priceDisplay }}</div>
                    <div><strong>Isi Produk:</strong> {{ $deliverableText }}</div>
                </div>
                <div class="d-flex justify-content-between">
                    <div><strong>Kuantitas:</strong> {{ $digitalProduct->has_quantity_limit ? 'Maks ' . $digitalProduct->quantity : 'Tanpa Batas' }}</div>
                    <div><strong>Status Jadwal:</strong> {!! $digitalProduct->is_scheduled ? '<span class="text-success">Aktif</span>' : '<span class="text-muted">Tidak Aktif</span>' !!}</div>
                </div>
                <div class="mt-3 text-center">
                    <em>(Fitur edit langsung untuk produk digital sedang dikembangkan. Hapus dan buat baru jika ingin mengubah data).</em>
                </div>
            </div>
        </div>
    </div>
</div>
