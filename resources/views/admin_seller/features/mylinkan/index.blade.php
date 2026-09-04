@extends("admin_seller.layouts.app")

@section("page_title", __('admin.microsite_management'))

@push("styles")
<link rel="stylesheet" href="{{ asset('css/pages/mylinkan.css') }}?v={{ filemtime(public_path('css/pages/mylinkan.css')) }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
@endpush
@section("content")
<div class="dashboard-mylinkan-page">

<div class="microsite-container">
    
    @if($viewMode == 'gallery')
        @include('admin_seller.features.mylinkan.partials._gallery')
    @else
        @include('admin_seller.features.mylinkan.partials._editor')
    @endif
</div>



<!-- NEW MICROSITE CREATION MULTI-STEP MODAL -->
<x-microsite.create-modal />



    <!-- JS TEMPLATES & HIDDEN MODALS -->
    @include('admin_seller.features.mylinkan.partials._templates')

</div>
@endsection

@push("scripts")
<!-- Include SortableJS for robust drag and drop -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<!-- Include Cropper.js for image cropping -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<script>
    window.MicrositeConfig = {
        authUserName: '{{ Auth::user()->name }}',
        translations: {
            addElement: '{{ __("admin.add_element") }}'
        }
    };
</script>
<script src="{{ asset('js/microsite-editor.js') }}?v={{ time() }}"></script>



<div id="micrositeEditorUrls" style="display: none;"
    data-route-image-delete="{{ url('/admin/elements/image') }}"
    data-route-image-store="{{ route('admin.elements.image.store', ['appearance_id' => isset($appearance) ? $appearance->id : '']) }}"
    data-route-divider-delete="{{ url('/admin/elements/divider') }}"
    data-route-divider-store="{{ route('admin.elements.divider.store', ['appearance_id' => isset($appearance) ? $appearance->id : '']) }}"
    data-route-text-delete="{{ url('/admin/elements/text') }}"
    data-route-text-store="{{ route('admin.elements.text.store', ['appearance_id' => isset($appearance) ? $appearance->id : '']) }}"
    data-route-video-delete="{{ url('/admin/elements/video') }}"
    data-route-video-store="{{ route('admin.elements.video.store', ['appearance_id' => isset($appearance) ? $appearance->id : '']) }}"
    data-route-social-delete="{{ url('/admin/elements/social') }}"
    data-route-social-store="{{ route('admin.elements.social.store', ['appearance_id' => isset($appearance) ? $appearance->id : '']) }}"
    data-route-order-update="{{ route('admin.elements.order.update', ['appearance_id' => isset($appearance) ? $appearance->id : '']) }}"
    data-route-appearance-update="{{ route('admin.appearance.update', ['appearance_id' => isset($appearance) ? $appearance->id : '']) }}"
    data-route-design-settings-update="{{ route('admin.appearance.design-settings.update', ['appearance_id' => isset($appearance) ? $appearance->id : '']) }}"
    data-appearance-blocks-order="{{ $appearance->blocks_order ?? '' }}"
    data-appearance-id="{{ isset($appearance) ? $appearance->id : '' }}">
</div>

<script>
// ================================================================
// EDITOR PANEL TAB SWITCHER
// Mengontrol visibilitas panel "Elemen" vs "Pengaturan"
// ================================================================

/**
 * Switch antara panel editor "elemen" dan "pengaturan".
 * @param {'elemen'|'pengaturan'} activePanel - nama panel yang akan ditampilkan
 */
function switchEditorPanel(activePanel) {
    const panelElemen      = document.getElementById('editorPanelElemen');
    const panelPengaturan  = document.getElementById('editorPanelPengaturan');
    const tabBtnElemen     = document.getElementById('tab-btn-elemen');
    const tabBtnPengaturan = document.getElementById('tab-btn-pengaturan');

    if (!panelElemen || !panelPengaturan) return;

    const isElemen = (activePanel === 'elemen');

    // Toggle panel visibility menggunakan atribut `hidden` (accessible)
    panelElemen.hidden     = !isElemen;
    panelPengaturan.hidden = isElemen;

    // Update ARIA dan visual state tombol tab
    tabBtnElemen.classList.toggle('is-active', isElemen);
    tabBtnElemen.setAttribute('aria-selected', isElemen ? 'true' : 'false');

    tabBtnPengaturan.classList.toggle('is-active', !isElemen);
    tabBtnPengaturan.setAttribute('aria-selected', isElemen ? 'false' : 'true');

    // Inisialisasi sub-tab background saat pertama kali buka panel Pengaturan
    if (!isElemen) {
        const currentType = document.getElementById('bgColorCustomPicker') ? 'ready' : null;
        if (currentType) {
            const activeBgTabId = document.getElementById('bg-tab-warna')?.classList.contains('is-active')
                ? 'warna' : 'gambar';
            _applyBgPanelVisibility(activeBgTabId);
        }
    }
}

// ================================================================
// BACKGROUND SETTINGS: Sub-tab Gambar | Warna
// ================================================================

/**
 * Switch antara panel background "gambar" dan "warna".
 * @param {'gambar'|'warna'} subTab
 */
function switchBackgroundTab(subTab) {
    document.getElementById('bg-tab-gambar')?.classList.toggle('is-active', subTab === 'gambar');
    document.getElementById('bg-tab-warna')?.classList.toggle('is-active', subTab === 'warna');
    _applyBgPanelVisibility(subTab);
}

/** Helper internal untuk show/hide panel background sesuai sub-tab aktif */
function _applyBgPanelVisibility(subTab) {
    const panelGambar = document.getElementById('bgPanelGambar');
    const panelWarna  = document.getElementById('bgPanelWarna');
    if (panelGambar) panelGambar.style.display = (subTab === 'gambar') ? '' : 'none';
    if (panelWarna)  panelWarna.style.display  = (subTab === 'warna') ? 'flex' : 'none';
}

/**
 * Terapkan gambar background ke phone preview dan auto-save ke DB.
 * @param {string} filename - nama file dari folder public/images/background/
 */
function applyBackgroundImage(filename) {
    const phoneContent = document.getElementById('phonePreviewContent');
    if (phoneContent) {
        if (filename) {
            phoneContent.style.backgroundImage = `url('{{ asset('images/background/') }}/${filename}')`;
            phoneContent.style.backgroundColor = 'transparent';
        } else {
            phoneContent.style.backgroundImage = '';
            phoneContent.style.backgroundColor = '#ffffff';
        }
    }

    // Highlight opsi yang dipilih
    document.querySelectorAll('.background-image-option').forEach(opt => {
        const radio = opt.querySelector('input[type="radio"]');
        opt.classList.toggle('is-selected', radio && radio.value === filename);
    });

    // Auto-save via AJAX
    _saveDesignSetting({
        background_type:  filename ? 'image' : 'color',
        background_color: filename || (document.getElementById('bgColorCustomPicker')?.value ?? '#FFFFFF'),
    });
}

/**
 * Terapkan warna background ke phone preview dan auto-save ke DB.
 * @param {string} hexColor - nilai hex warna, misal '#F8FAFC'
 */
function applyBackgroundColor(hexColor) {
    const phoneContent = document.getElementById('phonePreviewContent');
    if (phoneContent) {
        phoneContent.style.backgroundImage = '';
        phoneContent.style.backgroundColor = hexColor;
    }

    // Update color picker & hex display
    const picker     = document.getElementById('bgColorCustomPicker');
    const hexDisplay = document.getElementById('bgColorHexDisplay');
    if (picker)     picker.value       = hexColor;
    if (hexDisplay) hexDisplay.textContent = hexColor;

    // Highlight preset swatch yang aktif
    document.querySelectorAll('.bg-color-preset-swatch').forEach(swatch => {
        swatch.classList.toggle('is-selected', swatch.dataset.color === hexColor);
    });

    // Auto-save via AJAX
    _saveDesignSetting({
        background_type:  'color',
        background_color: hexColor,
    });
}

// ================================================================
// PROFILE LAYOUT
// ================================================================

/**
 * Terapkan layout profil dan update phone preview secara real-time.
 * Semua perubahan visual dikontrol via CSS dengan data-profile-layout attribute.
 * @param {'title-top'|'classic'|'side'} layout
 */
function applyProfileLayout(layout) {
    // Highlight kartu yang aktif di panel Pengaturan
    document.querySelectorAll('.profile-layout-card').forEach(card => {
        const radio = card.querySelector('input[type="radio"]');
        card.classList.toggle('is-selected', radio && radio.value === layout);
    });

    // Set data-attribute pada live profile section — CSS menangani semua layout
    const liveProfile = document.getElementById('liveProfileSection');
    if (!liveProfile) return;

    liveProfile.setAttribute('data-profile-layout', layout);

    // Pastikan banner container terlihat untuk semua layout (CSS yang atur posisinya)
    const bannerContainer = document.getElementById('livePhoneBannerContainer');
    if (bannerContainer) {
        const bannerImg = document.getElementById('livePhoneBannerImg');
        const hasBanner = bannerImg && bannerImg.src && !bannerImg.src.endsWith('/');
        // Tampilkan banner untuk semua layout jika ada banner, CSS yang atur tampilannya
        if (hasBanner) {
            bannerContainer.style.display = '';
        }
    }

    // Auto-save via AJAX
    _saveDesignSetting({ profile_layout: layout });
}


// ================================================================
// BLOCK SHAPE
// ================================================================

/**
 * Terapkan bentuk sudut blok elemen dan update phone preview secara real-time.
 * @param {'sharp'|'rounded'|'pill'} shape
 */
function applyBlockShape(shape) {
    const radiusMap = { sharp: '0px', rounded: '14px', pill: '9999px' };
    const borderRadius = radiusMap[shape] ?? '14px';

    // Terapkan ke semua blok di phone preview
    document.querySelectorAll(
        '.live-image-element, .live-text-element, .live-video-wrapper, .live-social-wrapper, .microsite-live-element'
    ).forEach(el => {
        el.style.borderRadius = borderRadius;
    });

    // Highlight pilihan yang aktif
    document.querySelectorAll('.block-shape-option').forEach(opt => {
        const radio = opt.querySelector('input[type="radio"]');
        opt.classList.toggle('is-selected', radio && radio.value === shape);
    });

    // Auto-save via AJAX
    _saveDesignSetting({ block_shape: shape });
}

// ================================================================
// AJAX AUTO-SAVE (shared utility)
// ================================================================

/**
 * Kirim perubahan design setting ke server via AJAX.
 * @param {Object} settingPayload - key-value pair dari field yang ingin disimpan
 */
function _saveDesignSetting(settingPayload) {
    const urlContainer = document.getElementById('micrositeEditorUrls');
    if (!urlContainer) return;

    const endpoint = urlContainer.dataset.routeDesignSettingsUpdate;
    if (!endpoint) return;

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    fetch(endpoint, {
        method:  'POST',
        headers: {
            'Content-Type':     'application/json',
            'Accept':           'application/json',
            'X-CSRF-TOKEN':     csrfToken ?? '',
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify(settingPayload),
    })
    .then(response => response.ok ? response.json() : Promise.reject(response))
    .catch(err => console.error('[Design Settings] Gagal menyimpan:', err));
}

// ================================================================
// INISIALISASI: terapkan block_shape yang tersimpan ke phone preview
// ================================================================
(function initDesignSettings() {
    const savedBlockShape = '{{ $appearance->block_shape ?? "rounded" }}';
    if (savedBlockShape !== 'rounded') {
        // Terapkan tanpa trigger auto-save ulang saat init
        const radiusMap = { sharp: '0px', rounded: '14px', pill: '9999px' };
        const radius = radiusMap[savedBlockShape] ?? '14px';
        document.querySelectorAll(
            '.live-image-element, .live-text-element, .live-video-wrapper, .live-social-wrapper'
        ).forEach(el => { el.style.borderRadius = radius; });
    }

    // Inisialisasi sub-tab background berdasarkan data tersimpan
    const savedBgType = '{{ $appearance->background_type ?? "color" }}';
    if (savedBgType === 'image') {
        switchBackgroundTab('gambar');
    } else {
        switchBackgroundTab('warna');
    }

    // Inisialisasi profile layout di phone preview saat halaman load
    const savedProfileLayout = '{{ $appearance->profile_layout ?? "classic" }}';
    const liveProfile = document.getElementById('liveProfileSection');
    if (liveProfile && savedProfileLayout && savedProfileLayout !== 'classic') {
        liveProfile.setAttribute('data-profile-layout', savedProfileLayout);
    }
})();
</script>

@endpush
