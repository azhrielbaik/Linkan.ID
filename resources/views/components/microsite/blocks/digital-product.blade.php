@props(['elementId' => null, 'data' => null, 'digitalProduct' => null, 'isActive' => null])
@php
    $digitalProduct = $data ?? $digitalProduct;
    $elementId = $elementId ?? ('digitalproduct_' . $digitalProduct->id);
    $isActive = $isActive ?? ($digitalProduct->is_active ?? true);
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
            <div class="dp-detail-card" style="background: #ffffff; font-size: 14px; color: #334155; border-radius: 8px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                <div style="padding: 16px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <h5 style="margin: 0; font-size: 15px; font-weight: 600; color: #0f172a;">Informasi Produk</h5>
                        <p style="margin: 4px 0 0 0; font-size: 12px; color: #64748b;">Detail dari produk digital Anda</p>
                    </div>
                    <button type="button" class="btn btn-sm" style="border: 1px solid #e2e8f0; background: #f8fafc; color: #0f172a; border-radius: 6px; font-weight: 600;" onclick='openEditDigitalProductWizard(@json($digitalProduct))'>
                        <i class="fas fa-edit" style="color: #6366f1;"></i> Edit
                    </button>
                </div>
                <div style="padding: 16px;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div>
                            <span style="display: block; font-size: 11px; color: #94a3b8; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Harga</span>
                            <span style="font-weight: 600; color: #0f172a;">{{ $priceDisplay }}</span>
                        </div>
                        <div>
                            <span style="display: block; font-size: 11px; color: #94a3b8; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Tipe Pengiriman</span>
                            <span style="font-weight: 600; color: #0f172a;">{{ $deliverableText }}</span>
                        </div>
                    </div>
                    <div style="margin-top: 16px; padding-top: 16px; border-top: 1px dashed #e2e8f0;">
                        <span style="display: block; font-size: 11px; color: #94a3b8; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Deskripsi</span>
                        <div style="color: #475569; font-size: 13px; line-height: 1.5;">
                            {{ $digitalProduct->description ? \Illuminate\Support\Str::limit($digitalProduct->description, 100) : 'Tidak ada deskripsi.' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
