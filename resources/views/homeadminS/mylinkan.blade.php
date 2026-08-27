@extends("layouts.admin")

@section("page_title", __('admin.microsite_management'))

@push("styles")
<link rel="stylesheet" href="{{ asset('css/pages/mylinkan.css') }}?v={{ filemtime(public_path('css/pages/mylinkan.css')) }}">
@endpush

@section("content")
<div class="dashboard-mylinkan-page">

<div class="microsite-container">
    
    <!-- COMBINED HEADER & MODE SWITCH -->
    <div class="section-header microsite-main-header">
        @if($viewMode == 'gallery')
            <div class="header-flex-container">
                <h2 class="gallery-title"><i class="fas fa-layer-group text-brand-orange"></i> {{ __('admin.my_microsite_list') }}</h2>
                
                <div class="microsite-actions-bar">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Cari microsite...">
                    </div>
                    <button type="button" class="btn-action-secondary btn-padded">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <button type="button" class="btn-action-primary btn-padded" onclick="openNewMicrositeModal()">
                        <i class="fas fa-plus"></i> Buat Baru
                    </button>
                </div>
            </div>
        @else
            {{-- EDITOR MODE HEADER: Judul di kiri, Tab Switcher di kanan --}}
            <div class="editor-mode-header-bar">
                <h2 class="editor-mode-title">
                    <a href="{{ route('admin.mylinkan', ['mode' => 'gallery']) }}" class="back-link" title="Kembali ke daftar microsite">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <i class="fas fa-sliders-h text-brand-orange"></i> {{ __('admin.edit_content_blocks') }}
                </h2>

                {{-- TAB SWITCHER: Elemen | Pengaturan --}}
                <nav class="editor-panel-tab-switcher" role="tablist" aria-label="Panel editor">
                    <button
                        type="button"
                        id="tab-btn-elemen"
                        role="tab"
                        class="editor-panel-tab-btn is-active"
                        aria-selected="true"
                        aria-controls="editorPanelElemen"
                        onclick="switchEditorPanel('elemen')"
                    >
                        <i class="fas fa-layer-group"></i>
                        <span>Elemen</span>
                    </button>
                    <button
                        type="button"
                        id="tab-btn-pengaturan"
                        role="tab"
                        class="editor-panel-tab-btn"
                        aria-selected="false"
                        aria-controls="editorPanelPengaturan"
                        onclick="switchEditorPanel('pengaturan')"
                    >
                        <i class="fas fa-paint-brush"></i>
                        <span>Pengaturan</span>
                    </button>
                </nav>
            </div>
        @endif
    </div>

    @if($viewMode == 'gallery')
        <!-- GALLERY LIST VIEW -->

        <div class="microsite-gallery-grid">
            
            <!-- MAIN MICROSITE CARD WITH PHONE SCREENSHOT THUMBNAIL -->
            <div class="microsite-card">
                
                <!-- CARD HEADER & THUMBNAIL CONTAINER -->
                <div class="card-thumbnail-container">

                    <!-- REAL PHONE THUMBNAIL REPRESENTATION -->
                    <div class="phone-thumbnail">
                        <div class="phone-thumbnail-screen" style="
                            background-image: url('{{ $appearance && $appearance->background_color ? asset('images/background/' . $appearance->background_color) : '' }}');
                            background-color: {{ $appearance && $appearance->background_color ? 'transparent' : '#f8f9fa' }};
                        ">
                            <!-- Banner -->
                            <div class="phone-thumb-banner">
                                @if($appearance && $appearance->banner)
                                    <img src="{{ asset('storage/' . $appearance->banner) }}" alt="Banner">
                                @endif
                            </div>

                            <!-- Avatar -->
                            <div class="phone-thumb-avatar">
                                @if($appearance && $appearance->profile_image)
                                    <img src="{{ asset('storage/' . $appearance->profile_image) }}" alt="Avatar">
                                @else
                                    <i class="fas fa-user" style="color: #888; font-size: 16px;"></i>
                                @endif
                            </div>

                            <!-- Name & Bio -->
                            <div class="phone-thumb-name" style="color: {{ $appearance ? $appearance->theme_color : '#FF9040' }}">
                                {!! $appearance ? $appearance->name : Auth::user()->name !!}
                            </div>
                            <div class="phone-thumb-bio" style="color: {{ $appearance ? $appearance->theme_color : '#666' }}">
                                {!! strip_tags($appearance->bio ?? 'Selamat datang di linkan saya!') !!}
                            </div>

                            <!-- Block Preview Snippets -->
                            @if($digitalProducts && $digitalProducts->count() > 0)
                                @foreach($digitalProducts->take(2) as $prod)
                                    <div class="phone-thumb-block">
                                        <i class="fas fa-box"></i>
                                        <span>{{ $prod->title }}</span>
                                    </div>
                                @endforeach
                            @endif

                            @if($shortlinks && $shortlinks->count() > 0)
                                @foreach($shortlinks->take(2) as $sl)
                                    <div class="phone-thumb-block">
                                        <i class="fas fa-link"></i>
                                        <span>{{ $sl->title ?: $sl->slug }}</span>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <!-- CARD BODY DETAILS -->
                <div class="card-body-details">
                    <div class="card-title-row">
                        <h3 class="microsite-name">{!! $appearance->name ?? Auth::user()->name !!}</h3>
                    </div>

                    <a href="{{ url('/linkan.id/' . Auth::user()->username) }}" target="_blank" class="url-pill">
                        <i class="fas fa-globe"></i> linkan.id/{{ Auth::user()->username }}
                    </a>

                    <div class="card-stats-tags">
                        <span class="stat-tag"><i class="fas fa-eye"></i> {{ number_format($totalViews) }} views</span>
                        <span class="stat-tag"><i class="fas fa-cube"></i> {{ $digitalProducts->count() }} {{ __('admin.product') }}</span>
                        <span class="stat-tag"><i class="fas fa-link"></i> {{ $shortlinks->total() }} {{ __('admin.link') }}</span>
                    </div>

                    <!-- ACTION BUTTONS -->
                    <div class="card-actions-grid">
                        <a href="{{ url('/linkan.id/' . Auth::user()->username) }}" target="_blank" class="btn-action-secondary">
                            <i class="fa-solid fa-arrow-up-from-ground-water"></i> Kunjungi
                        </a>
                        <a href="{{ route('admin.mylinkan', ['mode' => 'edit']) }}" class="btn-action-primary">
                            <i class="fas fa-pen"></i> {{ __('admin.edit_block') }}
                        </a>
                        <a href="{{ route('admin.appearance') }}" class="btn-action-secondary">
                            <i class="fas fa-paint-brush"></i> {{ __('admin.appearance') }}
                        </a>
                        <button type="button" class="btn-action-secondary" onclick="copyToClipboard('{{ url('/linkan.id/' . Auth::user()->username) }}')">
                            <i class="fas fa-copy"></i> {{ __('admin.copy_link') }}
                        </button>
                    </div>
                </div>

            </div>

        </div>

    @else
        <!-- EDITOR VIEW MODE -->

        <div class="editor-layout">
            <!-- LEFT PANEL: BLOCK MANAGEMENT -->
            <div class="editor-left-panel">

                {{-- PANEL ELEMEN: konten default (tambah & edit elemen) --}}
                <div id="editorPanelElemen" role="tabpanel" aria-labelledby="tab-btn-elemen">

                <!-- ACTION: TAMBAH ELEMENT BUTTON & SLIDE-DOWN PANEL -->
                <div class="add-element-wrapper">
                    <!-- BUTTON -->
                    <button type="button" id="btnToggleAddElement" class="btn-add-element" onclick="toggleAddElementPanel()">
                        <i id="btnToggleIcon" class="fas fa-plus-circle" style="font-size: 18px; transition: transform 0.3s ease;"></i>
                        <span id="btnToggleText">{{ __('admin.add_element') }}</span>
                    </button>

                    <!-- INLINE SLIDE-DOWN PANEL -->
                    <div id="addElementPanel" class="add-element-panel">
                        <div class="add-element-panel-inner">
                            <!-- PANEL CONTENT AREA (COMPONENT CARDS SELECTOR) -->
                            <div id="addElementPanelBody" class="add-element-grid">
                                
                                <!-- Profile Block is default and cannot be added/removed -->
                                
                                <!-- Element Option 1: Gambar -->
                                <div class="element-option-card" onclick="addGambarElement()">
                                    <div class="option-card-title">Gambar</div>
                                    <div class="option-card-desc">Upload gambar & link</div>
                                </div>

                                <!-- Element Option 2: Pembatas -->
                                <div class="element-option-card" onclick="addDividerElement()">
                                    <div class="option-card-title">Pembatas</div>
                                    <div class="option-card-desc">Garis atau spasi pemisah</div>
                                </div>

                                <!-- Element Option 3: Teks -->
                                <div class="element-option-card" onclick="addTextElement()">
                                    <div class="option-card-title">Teks</div>
                                    <div class="option-card-desc">Tambahkan paragraf</div>
                                </div>

                                <!-- Element Option 4: Video -->
                                <div class="element-option-card" onclick="addVideoElement()">
                                    <div class="option-card-title">Embed Video</div>
                                    <div class="option-card-desc">Embed video Youtube</div>
                                </div>

                                <!-- Element Option 5: Social Media -->
                                <div class="element-option-card" onclick="addSocialMediaElement()">
                                    <div class="option-card-title">Media Sosial</div>
                                    <div class="option-card-desc">Tautkan akun sosial media</div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- EDIT ELEMENT SECTION CONTAINER (DRAGGABLE BLOCKS) -->
                <div id="digitalProductsSection" class="digital-products-section">
                    <div id="elementSectionHeader" class="edit-element-header">
                        <h3 class="element-header-title">
                            <i class="fas fa-layer-group text-brand-orange"></i> Edit Element
                        </h3>
                        <span class="element-header-subtitle">
                            <i class="fas fa-arrows-alt-v"></i> Drag & Drop untuk mengurutkan
                        </span>
                    </div>

                    <!-- 1. PROFILE BLOCK CARD (STATIC, NOT DRAGGABLE, PINNED AT TOP) -->
                    <div id="profileBlockCard" class="draggable-element-block" data-element-type="profile">
                        <!-- COLLAPSED BLOCK HEADER CARD -->
                            <div class="block-item-card" onclick="toggleProfileEditForm()">
                                <i class="fas fa-grip-vertical drag-handle-icon" style="visibility: hidden; cursor: default;"></i>
                                <div class="profile-block-icon">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                                <div class="block-item-content">
                                    <div class="block-item-title-wrapper">
                                        <span>Profil</span>
                                    </div>
                                </div>
                                <div class="block-item-actions" onclick="event.stopPropagation()">
                                    <button type="button" onclick="toggleProfileEditForm()" class="btn-profile-edit">
                                        <i class="fas fa-pen" class="btn-edit-icon"></i> <span id="profileEditBtnText">Edit</span>
                                    </button>
                                </div>
                            </div>

                        <!-- EXPANDABLE EDIT FORM BODY (SLIDES DOWN WHEN EDIT CLICKED) -->
                        <div id="profileEditFormBody" class="profile-edit-form-body">
                            <div class="profile-form-padding">
                                <form action="{{ route('admin.appearance.update') }}" method="POST" enctype="multipart/form-data" id="profileBlockForm">
                                    @csrf
                                    <input type="hidden" name="theme_color" value="{{ $appearance->theme_color ?? '#FF9040' }}">
                                    <input type="hidden" name="background_color" value="{{ $appearance->background_color ?? '#FFFFFF' }}">
                                    
                                    <div class="profile-form-group">
                                        <div class="profile-form-header">
                                            Pengaturan Data Profil
                                        </div>

                                        <!-- 1. GAMBAR SAMPUL (COVER BANNER) -->
                                        <div>
                                            <label class="profile-form-label">Gambar Sampul (Banner)</label>
                                            <div class="upload-dropzone banner-dropzone">
                                                <input type="file" name="banner" id="inputBannerFile" accept="image/jpeg, image/png, image/gif" class="dropzone-input" onchange="previewProfileBanner(this)">
                                                
                                                <div id="bannerPreviewPlaceholder" class="banner-placeholder {{ ($appearance && $appearance->banner) ? 'd-none' : 'd-flex' }}">
                                                    <i class="fas fa-cloud-upload-alt dropzone-icon"></i>
                                                    <div class="dropzone-text-primary">
                                                        Drop your images here or <span class="dropzone-browse-text">browse</span>
                                                    </div>
                                                    <div class="dropzone-text-secondary">supports JPG, JPEG, PNG & GIF</div>
                                                </div>

                                                <div id="bannerPreviewContainer" class="banner-preview-container {{ ($appearance && $appearance->banner) ? 'd-block' : 'd-none' }}">
                                                    @if($appearance && $appearance->banner)
                                                        <img src="{{ asset('storage/' . $appearance->banner) }}" id="bannerPreviewImg" class="live-phone-banner-img">
                                                    @else
                                                        <img src="" id="bannerPreviewImg" class="banner-preview-img d-none">
                                                    @endif
                                                    <div class="edit-image-overlay">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </div>
                                                </div>
                                                
                                                <div id="bannerSizeError" class="dropzone-error-msg d-none">
                                                    <i class="fas fa-exclamation-circle error-icon"></i> Gagal: Ukuran maksimal gambar sampul adalah 2MB!
                                                </div>
                                            </div>
                                        </div>

                                        <!-- 2. FOTO PROFILE -->
                                        <div>
                                            <label class="profile-form-label">Foto Profil</label>
                                            <div class="upload-dropzone avatar-dropzone">
                                                <input type="file" name="profile_image" id="inputAvatarFile" accept="image/jpeg, image/png, image/gif" class="dropzone-input" onchange="previewProfileAvatar(this)">
                                                
                                                <div class="avatar-dropzone-inner">
                                                    <div id="avatarPreviewContainer" class="avatar-preview-container">
                                                        @if($appearance && $appearance->profile_image)
                                                            <img src="{{ asset('storage/' . $appearance->profile_image) }}" id="avatarPreviewImg" class="live-phone-banner-img">
                                                        @else
                                                            <i class="fas fa-user" id="avatarPreviewPlaceholder" class="avatar-placeholder-icon"></i>
                                                            <img src="" id="avatarPreviewImg" class="banner-preview-img d-none">
                                                        @endif
                                                    </div>
                                                    
                                                    <div class="avatar-dropzone-text">
                                                        <div class="dropzone-text-primary">Upload Foto Profil</div>
                                                        <div class="dropzone-text-secondary">Seret gambar ke sini atau <span class="dropzone-browse-text dropzone-browse-bold">browse</span></div>
                                                    </div>
                                                    
                                                    <div class="avatar-dropzone-action">
                                                        <i class="fas fa-cloud-upload-alt dropzone-icon-small"></i>
                                                    </div>
                                                </div>

                                                <div id="avatarSizeError" class="dropzone-error-msg dropzone-error-sm d-none">
                                                    <i class="fas fa-exclamation-circle error-icon"></i> Gagal: Ukuran maksimal foto profil adalah 2MB!
                                                </div>
                                            </div>
                                        </div>

                                        <!-- 3. BENTUK PROFILE (PROFILE SHAPE) -->
                                        <div>
                                            <label class="profile-form-label mb-2">Bentuk Foto Profil</label>
                                            <div class="shape-options-grid">
                                                <label class="shape-option-label">
                                                    <input type="radio" name="profile_shape" value="circle" {{ old('profile_shape', $appearance->profile_shape ?? 'circle') == 'circle' ? 'checked' : '' }} onchange="updateProfileShape('circle')">
                                                    <div class="shape-preview shape-circle"></div>
                                                    <span class="shape-option-text">Lingkaran</span>
                                                </label>
                                                <label class="shape-option-label">
                                                    <input type="radio" name="profile_shape" value="rounded" {{ old('profile_shape', $appearance->profile_shape ?? 'circle') == 'rounded' ? 'checked' : '' }} onchange="updateProfileShape('rounded')">
                                                    <div class="shape-preview shape-rounded"></div>
                                                    <span class="shape-option-text">Rounded</span>
                                                </label>
                                                <label class="shape-option-label">
                                                    <input type="radio" name="profile_shape" value="square" {{ old('profile_shape', $appearance->profile_shape ?? 'circle') == 'square' ? 'checked' : '' }} onchange="updateProfileShape('square')">
                                                    <div class="shape-preview shape-square"></div>
                                                    <span class="shape-option-text">Persegi</span>
                                                </label>
                                            </div>
                                        </div>

                                        <!-- 4. NAMA PROFILE (TEKS EDITOR SEDERHANA) -->
                                        <div>
                                            <label class="profile-form-label">Nama Profil</label>
                                            <div class="text-editor-container">
                                                <div class="text-editor-toolbar">
                                                    <button type="button" onmousedown="event.preventDefault();" onclick="formatText('bold')" class="toolbar-btn toolbar-btn-bold" title="Tebal (Bold)"><i class="fas fa-bold"></i></button>
                                                    <button type="button" onmousedown="event.preventDefault();" onclick="formatText('italic')" class="toolbar-btn toolbar-btn-italic" title="Miring (Italic)"><i class="fas fa-italic"></i></button>
                                                    
                                                    <div class="toolbar-separator"></div>
                                                    
                                                    <select onchange="formatText('fontName', this.value, 'editorProfileName')" class="toolbar-select" title="Pilih Font">
                                                        <option value="Plus Jakarta Sans">Jakarta Sans</option>
                                                        <option value="Arial">Arial</option>
                                                        <option value="Times New Roman">Times New Roman</option>
                                                        <option value="Courier New">Courier New</option>
                                                        <option value="Georgia">Georgia</option>
                                                        <option value="Verdana">Verdana</option>
                                                    </select>
                                                    
                                                    <label class="toolbar-color-picker" title="Pilih Warna">
                                                        <i class="fas fa-eye-dropper"></i>
                                                        <input type="color" id="colorPickerProfileName" oninput="formatText('foreColor', this.value, 'editorProfileName')" class="hidden-color-input">
                                                    </label>
                                                    
                                                    
                                                </div>
                                                <div id="editorProfileName" contenteditable="true" class="text-editor-body editor-name-body" placeholder="Masukkan nama profil Anda..." onkeyup="syncProfileName(); updateLiveProfileName(this.innerHTML)">{!! old('name', $appearance->name ?? Auth::user()->name) !!}</div>
                                                <input type="hidden" name="name" id="inputProfileName" value="{{ old('name', $appearance->name ?? Auth::user()->name) }}">
                                            </div>
                                        </div>

                                        <!-- 5. DESKRIPSI PROFILE (TEKS EDITOR SEDERHANA) -->
                                        <div>
                                            <label class="profile-form-label">Deskripsi / Bio Profil</label>
                                            <div class="text-editor-container">
                                                <div class="text-editor-toolbar">
                                                    <button type="button" onmousedown="event.preventDefault();" onclick="formatText('bold')" class="toolbar-btn toolbar-btn-bold" title="Tebal (Bold)"><i class="fas fa-bold"></i></button>
                                                    <button type="button" onmousedown="event.preventDefault();" onclick="formatText('italic')" class="toolbar-btn toolbar-btn-italic" title="Miring (Italic)"><i class="fas fa-italic"></i></button>
                                                    <button type="button" onmousedown="event.preventDefault();" onclick="formatText('underline')" class="toolbar-btn toolbar-btn-underline" title="Garis Bawah (Underline)"><i class="fas fa-underline"></i></button>
                                                    
                                                    <div class="toolbar-separator"></div>
                                                    
                                                    <select onchange="formatText('fontName', this.value, 'editorProfileBio')" class="toolbar-select" title="Pilih Font">
                                                        <option value="Plus Jakarta Sans">Jakarta Sans</option>
                                                        <option value="Arial">Arial</option>
                                                        <option value="Times New Roman">Times New Roman</option>
                                                        <option value="Courier New">Courier New</option>
                                                        <option value="Georgia">Georgia</option>
                                                        <option value="Verdana">Verdana</option>
                                                    </select>
                                                    <label class="toolbar-color-picker" title="Pilih Warna">
                                                        <i class="fas fa-eye-dropper"></i>
                                                        <input type="color" id="colorPickerProfileBio" oninput="formatText('foreColor', this.value, 'editorProfileBio')" class="hidden-color-input">
                                                    </label>
                                                </div>
                                                <div id="editorProfileBio" contenteditable="true" class="text-editor-body editor-bio-body" placeholder="Tulis deskripsi singkat profil Anda..." onkeyup="syncProfileBio(); updateLiveProfileBio(this.innerHTML)">{!! old('bio', $appearance->bio ?? '') !!}</div>
                                                <input type="hidden" name="bio" id="inputProfileBio" value="{{ old('bio', $appearance->bio ?? '') }}">
                                            </div>
                                        </div>

                                        <!-- ACTION BUTTONS -->
                                        <div class="form-actions-wrapper">
                                            <button type="button" onclick="toggleProfileEditForm()" class="btn-secondary">Batal</button>
                                            <button type="submit" class="btn-primary">Simpan Perubahan</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            </div>
                        </div>

                        <!-- DRAGGABLE ELEMENT BLOCKS LIST CONTAINER -->
                        <div id="elementBlocksList" style="display: flex; flex-direction: column;">

                        @if(isset($imageElements))
                            @foreach($imageElements as $imageEl)
                                @php 
                                    $elementId = 'imageBlock_' . $imageEl->id; 
                                    $isActive = $imageEl->is_active ?? true;
                                @endphp
                                <div id="{{ $elementId }}" class="draggable-element-block {{ $isActive ? '' : 'block-inactive' }}" data-element-type="image" data-db-id="{{ $imageEl->id }}">
                                    <div class="block-item-card" onclick="toggleImageEditForm('{{ $elementId }}')">
                                        <i class="fas fa-grip-vertical drag-handle drag-handle-icon" onclick="event.stopPropagation()" title="Tarik ke atas/bawah untuk ubah urutan"></i>
                                        <div class="block-item-icon-wrapper">
                                            <i class="fas fa-image"></i>
                                        </div>
                                        <div class="block-item-content">
                                            <div class="block-item-title-wrapper">
                                                <span>Gambar</span>
                                            </div>
                                        </div>
                                        <div class="block-item-actions" onclick="event.stopPropagation()">
                                            <div class="element-visibility-container">
                                                <span class="visibility-status-text {{ $isActive ? 'status-active' : 'status-inactive' }}" id="statusText_{{ $elementId }}">{{ $isActive ? 'Aktif' : 'Nonaktif' }}</span>
                                                <label class="toggle-switch">
                                                    <input type="checkbox" id="visibilitySwitch_{{ $elementId }}" onchange="toggleElementVisibility('{{ $elementId }}', this)" {{ $isActive ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                            <button type="button" class="btn-element-action btn-delete-icon" onclick="removeDynamicElement('{{ $elementId }}'); event.stopPropagation();" title="Hapus Elemen">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                            <button type="button" onclick="toggleImageEditForm('{{ $elementId }}')" class="btn-edit-block">
                                                <i class="fas fa-pen" class="btn-edit-icon"></i> <span id="btnText_{{ $elementId }}">Edit</span>
                                            </button>
                                        </div>
                                    </div>

                                    <div id="formBody_{{ $elementId }}" class="edit-form-body" style="max-height: 0; opacity: 0; margin-top: 0;">
                                        <div class="profile-form-padding">
                                            <div class="profile-form-header" style="margin-bottom: 16px;">
                                                Pengaturan Elemen Gambar
                                            </div>
                                            
                                            <div>
                                                <label class="profile-form-label">Unggah Gambar</label>
                                                <div class="upload-dropzone dynamic-dropzone" style="padding: 20px; text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center; position: relative;">
                                                    <input type="file" accept="image/jpeg, image/png, image/gif" class="dropzone-input" onchange="previewDynamicImage(this, '{{ $elementId }}')">
                                                    
                                                    <div id="placeholder_{{ $elementId }}" style="display: {{ $imageEl->image_path ? 'none' : 'flex' }}; flex-direction: column; align-items: center; justify-content: center; gap: 8px;">
                                                        <i class="fas fa-cloud-upload-alt" style="font-size: 32px; color: #6366F1;"></i>
                                                        <div class="dropzone-text-primary">Seret gambar ke sini atau <span style="color: #6366F1;">browse</span></div>
                                                        <div class="dropzone-text-secondary">supports JPG, JPEG, PNG & GIF</div>
                                                    </div>

                                                    <div id="previewCont_{{ $elementId }}" style="display: {{ $imageEl->image_path ? 'block' : 'none' }}; width: 100%; border-radius: 8px; overflow: hidden; background: #f3f4f6; position: relative; z-index: 1;">
                                                        <img src="{{ $imageEl->image_path ? asset('storage/' . $imageEl->image_path) : '' }}" id="previewImg_{{ $elementId }}" style="width: 100%; object-fit: contain;">
                                                        <div class="edit-image-overlay">
                                                            <i class="fa-solid fa-pen-to-square"></i>
                                                        </div>
                                                    </div>
                                                    
                                                    <div id="error_{{ $elementId }}" style="display: none; width: 100%; margin-top: 12px; color: #EF4444; font-size: 11px; font-weight: 700; padding: 8px; border-radius: 6px; z-index: 5; text-align: center; border: 1px solid #FCA5A5; background: #FEE2E2;">
                                                        <i class="fas fa-exclamation-circle error-icon"></i> Gagal: Ukuran maksimal gambar adalah 2MB!
                                                    </div>
                                                </div>
                                            </div>

                                            <div style="margin-top: 16px;">
                                                <label class="profile-form-label">URL Tautan (Opsional)</label>
                                                <input type="url" id="link_{{ $elementId }}" placeholder="https://..." value="{{ $imageEl->link_url }}" class="form-input-custom" oninput="updateDynamicImageLink('{{ $elementId }}', this.value)">
                                            </div>

                                            <div class="element-action-footer">
                                                <button type="button" onclick="removeDynamicElement('{{ $elementId }}')" class="btn-delete-element">
                                                    <i class="fas fa-trash-alt"></i> Hapus Elemen
                                                </button>
                                                <button type="button" onclick="saveDynamicElement('{{ $elementId }}')" class="btn-save-element">
                                                    Simpan
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        @if(isset($dividerElements))
                            @foreach($dividerElements as $dividerEl)
                                @php 
                                    $elementId = 'dividerBlock_' . $dividerEl->id; 
                                    $isActive = $dividerEl->is_active ?? true;
                                @endphp
                                <div id="{{ $elementId }}" class="draggable-element-block {{ $isActive ? '' : 'block-inactive' }}" data-element-type="divider" data-db-id="{{ $dividerEl->id }}">
                                    <div class="block-item-card" onclick="if(typeof toggleDividerEditForm === 'function') toggleDividerEditForm('{{ $elementId }}')">
                                        <i class="fas fa-grip-vertical drag-handle drag-handle-icon" onclick="event.stopPropagation()" title="Tarik ke atas/bawah untuk ubah urutan"></i>
                                        <div class="block-item-icon-wrapper">
                                            <i class="fas fa-minus"></i>
                                        </div>
                                        <div class="block-item-content">
                                            <div class="block-item-title-wrapper">
                                                <span>Pembatas</span>
                                            </div>
                                        </div>
                                        <div class="block-item-actions" onclick="event.stopPropagation()">
                                            <div class="element-visibility-container">
                                                <span class="visibility-status-text {{ $isActive ? 'status-active' : 'status-inactive' }}" id="statusText_{{ $elementId }}">{{ $isActive ? 'Aktif' : 'Nonaktif' }}</span>
                                                <label class="toggle-switch">
                                                    <input type="checkbox" id="visibilitySwitch_{{ $elementId }}" onchange="toggleElementVisibility('{{ $elementId }}', this)" {{ $isActive ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                            <button type="button" class="btn-element-action btn-delete-icon" onclick="removeDynamicDivider('{{ $elementId }}'); event.stopPropagation();" title="Hapus Elemen">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                            <button type="button" onclick="if(typeof toggleDividerEditForm === 'function') toggleDividerEditForm('{{ $elementId }}')" class="btn-edit-block">
                                                <i class="fas fa-pen" class="btn-edit-icon"></i> <span id="btnText_{{ $elementId }}">Edit</span>
                                            </button>
                                        </div>
                                    </div>

                                    <div id="formBody_{{ $elementId }}" class="edit-form-body" style="max-height: 0; opacity: 0; margin-top: 0;">
                                        <div class="edit-form-content">
                                            <div class="edit-form-group">
                                                <label class="profile-form-label">Jenis Pembatas</label>
                                                <div class="segment-control-wrapper">
                                                    <label class="segment-control-label">
                                                        <input type="radio" name="dividerTypeGroup_{{ $elementId }}" value="line" class="hidden-radio" onchange="document.getElementById('dividerType_{{ $elementId }}').value = this.value; updateDividerPreview('{{ $elementId }}'); updateSegmentedControl(this);" {{ $dividerEl->type === 'line' ? 'checked' : '' }}>
                                                        <div class="segment-btn {{ $dividerEl->type === 'line' ? 'active' : '' }}">
                                                            <i class="fas fa-minus"></i> Garis
                                                        </div>
                                                    </label>
                                                    <label class="segment-control-label">
                                                        <input type="radio" name="dividerTypeGroup_{{ $elementId }}" value="space" class="hidden-radio" onchange="document.getElementById('dividerType_{{ $elementId }}').value = this.value; updateDividerPreview('{{ $elementId }}'); updateSegmentedControl(this);" {{ $dividerEl->type === 'space' ? 'checked' : '' }}>
                                                        <div class="segment-btn {{ $dividerEl->type === 'space' ? 'active' : '' }}">
                                                            <i class="fas fa-arrows-alt-v"></i> Spasi Kosong
                                                        </div>
                                                    </label>
                                                    <input type="hidden" id="dividerType_{{ $elementId }}" value="{{ $dividerEl->type }}">
                                                </div>
                                            </div>

                                            <div class="slider-control-container">
                                                <label class="profile-form-label slider-control-header">
                                                    <span class="slider-control-title">Ukuran Jarak</span>
                                                    <div class="slider-value-badge" id="dividerSizeValue_{{ $elementId }}">{{ $dividerEl->size }}px</div>
                                                </label>
                                                <div class="slider-input-wrapper">
                                                    <button type="button" class="btn-slider-adjust" onclick="adjustDividerSize('{{ $elementId }}', -5)">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                    <input type="range" id="dividerSize_{{ $elementId }}" class="modern-range" min="10" max="100" step="5" value="{{ $dividerEl->size }}" oninput="updateDividerPreview('{{ $elementId }}')">
                                                    <button type="button" class="btn-slider-adjust" onclick="adjustDividerSize('{{ $elementId }}', 5)">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="element-action-footer">
                                                <button type="button" onclick="removeDynamicDivider('{{ $elementId }}'); event.stopPropagation();" class="btn-delete-element">
                                                    <i class="fas fa-trash-alt"></i> Hapus Elemen
                                                </button>
                                                <button type="button" onclick="saveDynamicDivider('{{ $elementId }}')" class="btn-save-element">
                                                    Simpan
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        @if(isset($textElements) && $textElements->count() > 0)
                            @foreach($textElements as $textEl)
                                @php 
                                    $elementId = 'textBlock_' . $textEl->id; 
                                    $isActive = $textEl->is_active ?? true;
                                @endphp
                                <div id="{{ $elementId }}" class="draggable-element-block {{ $isActive ? '' : 'block-inactive' }}" data-element-type="text" data-db-id="{{ $textEl->id }}">
                                    <div class="block-item-card" onclick="if(typeof toggleTextEditForm === 'function') toggleTextEditForm('{{ $elementId }}')">
                                        <i class="fas fa-grip-vertical drag-handle drag-handle-icon" onclick="event.stopPropagation()" title="Tarik ke atas/bawah untuk ubah urutan"></i>
                                        <div class="block-item-icon-wrapper">
                                            <i class="fas fa-font"></i>
                                        </div>
                                        <div class="block-item-content">
                                            <div class="block-item-title-wrapper">
                                                <span>Teks</span>
                                            </div>
                                        </div>
                                        <div class="block-item-actions" onclick="event.stopPropagation()">
                                            <div class="element-visibility-container">
                                                <span class="visibility-status-text {{ $isActive ? 'status-active' : 'status-inactive' }}" id="statusText_{{ $elementId }}">{{ $isActive ? 'Aktif' : 'Nonaktif' }}</span>
                                                <label class="toggle-switch">
                                                    <input type="checkbox" id="visibilitySwitch_{{ $elementId }}" onchange="toggleElementVisibility('{{ $elementId }}', this)" {{ $isActive ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                            <button type="button" class="btn-element-action btn-delete-icon" onclick="removeDynamicText('{{ $elementId }}'); event.stopPropagation();" title="Hapus Elemen">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                            <button type="button" onclick="if(typeof toggleTextEditForm === 'function') toggleTextEditForm('{{ $elementId }}')" class="btn-edit-block">
                                                <i class="fas fa-pen" class="btn-edit-icon"></i> <span id="btnText_{{ $elementId }}">Edit</span>
                                            </button>
                                        </div>
                                    </div>

                                    <div id="formBody_{{ $elementId }}" class="edit-form-body" style="max-height: 0; opacity: 0; margin-top: 0;">
                                        <div class="edit-form-content">
                                            <div class="profile-form-header" style="margin-bottom: 20px; font-weight: 600; color: #0f172a; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                                                <i class="fas fa-font" style="color: #3b82f6; margin-right: 8px;"></i> Pengaturan Teks
                                            </div>
                                            <div class="profile-form-group" style="margin-bottom: 24px;">
                                                <label class="profile-form-label" style="font-weight: 600; color: #334155; margin-bottom: 8px; display: block;">Isi Teks Konten</label>
                                                <div class="text-editor-container" style="box-shadow: 0 1px 3px rgba(0,0,0,0.05); transition: all 0.3s ease; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden;">
                                                    <div class="text-editor-toolbar" style="background: #f8fafc; padding: 8px; display: flex; flex-wrap: wrap; gap: 4px; border-bottom: 1px solid #e2e8f0; align-items: center;">
                                                    <button type="button" class="toolbar-btn" onclick="execCmd('{{ $elementId }}', 'bold')" title="Bold"><i class="fas fa-bold"></i></button>
                                                    <button type="button" class="toolbar-btn" onclick="execCmd('{{ $elementId }}', 'italic')" title="Italic"><i class="fas fa-italic"></i></button>
                                                    <button type="button" class="toolbar-btn" onclick="execCmd('{{ $elementId }}', 'underline')" title="Underline"><i class="fas fa-underline"></i></button>
                                                    <button type="button" class="toolbar-btn" onclick="execCmd('{{ $elementId }}', 'strikeThrough')" title="Strikethrough"><i class="fas fa-strikethrough"></i></button>
                                                    <span class="toolbar-divider"></span>
                                                    <button type="button" class="toolbar-btn" onclick="execCmd('{{ $elementId }}', 'justifyLeft')" title="Align Left"><i class="fas fa-align-left"></i></button>
                                                    <button type="button" class="toolbar-btn" onclick="execCmd('{{ $elementId }}', 'justifyCenter')" title="Align Center"><i class="fas fa-align-center"></i></button>
                                                    <button type="button" class="toolbar-btn" onclick="execCmd('{{ $elementId }}', 'justifyRight')" title="Align Right"><i class="fas fa-align-right"></i></button>
                                                    <span class="toolbar-divider"></span>
                                                    <button type="button" class="toolbar-btn" onclick="execCmd('{{ $elementId }}', 'insertUnorderedList')" title="Bullet List"><i class="fas fa-list-ul"></i></button>
                                                    <button type="button" class="toolbar-btn" onclick="execCmd('{{ $elementId }}', 'insertOrderedList')" title="Numbered List"><i class="fas fa-list-ol"></i></button>
                                                    <span class="toolbar-divider"></span>
                                                    <input type="color" class="toolbar-color-picker" onchange="execCmd('{{ $elementId }}', 'foreColor', this.value)" title="Text Color" value="#000000">
                                                    <span class="toolbar-divider"></span>
                                                    <div class="toolbar-dropdown">
                                                        <select onchange="changeTextSize('{{ $elementId }}', this.value)" class="toolbar-select" id="textSizeSelect_{{ $elementId }}">
                                                            <option value="12px">Kecil (12px)</option>
                                                            <option value="16px" selected>Normal (16px)</option>
                                                            <option value="24px">Besar (24px)</option>
                                                            <option value="custom">Custom...</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div id="customSizeWrapper_{{ $elementId }}" class="custom-size-wrapper" style="display: none;">
                                                    <input type="number" id="customSizeInput_{{ $elementId }}" class="toolbar-input" placeholder="Ukuran (px)" min="1" max="99" onchange="applyCustomSize('{{ $elementId }}')">
                                                    <button type="button" class="toolbar-btn-text" onclick="applyCustomSize('{{ $elementId }}')">Terapkan</button>
                                                </div>
                                                <div id="editorContent_{{ $elementId }}" class="text-editor-area" contenteditable="true" oninput="updateTextPreview('{{ $elementId }}')" style="padding: 16px; min-height: 120px; font-size: 16px; line-height: 1.5; outline: none;">{!! $textEl->content ?? 'Teks Anda di sini...' !!}</div>
                                            </div>
                                            </div>

                                            <div class="element-action-footer" style="margin-top: 15px;">
                                                <button type="button" onclick="removeDynamicText('{{ $elementId }}'); event.stopPropagation();" class="btn-delete-element">
                                                    <i class="fas fa-trash-alt"></i> Hapus Elemen
                                                </button>
                                                <button type="button" onclick="saveDynamicText('{{ $elementId }}')" class="btn-save-element" id="btnSaveText_{{ $elementId }}">
                                                    Simpan
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        @if(isset($videoElements) && $videoElements->count() > 0)
                            @foreach($videoElements as $videoEl)
                                @php 
                                    $elementId = 'videoBlock_' . $videoEl->id; 
                                    $isActive = $videoEl->is_active ?? true;
                                    $isAutoplay = $videoEl->is_autoplay ?? false;
                                @endphp
                                <div id="{{ $elementId }}" class="draggable-element-block {{ $isActive ? '' : 'block-inactive' }}" data-element-type="video" data-db-id="{{ $videoEl->id }}">
                                    <div class="block-item-card" onclick="if(typeof toggleVideoEditForm === 'function') toggleVideoEditForm('{{ $elementId }}')">
                                        <i class="fas fa-grip-vertical drag-handle drag-handle-icon" onclick="event.stopPropagation()" title="Tarik ke atas/bawah untuk ubah urutan"></i>
                                        <div class="block-item-icon-wrapper">
                                            <i class="fab fa-youtube"></i>
                                        </div>
                                        <div class="block-item-content">
                                            <div class="block-item-title-wrapper">
                                                <span>Video</span>
                                            </div>
                                        </div>
                                        <div class="block-item-actions" onclick="event.stopPropagation()">
                                            <div class="element-visibility-container">
                                                <span class="visibility-status-text {{ $isActive ? 'status-active' : 'status-inactive' }}" id="statusText_{{ $elementId }}">{{ $isActive ? 'Aktif' : 'Nonaktif' }}</span>
                                                <label class="toggle-switch">
                                                    <input type="checkbox" id="visibilitySwitch_{{ $elementId }}" onchange="toggleElementVisibility('{{ $elementId }}', this)" {{ $isActive ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                            <button type="button" class="btn-element-action btn-delete-icon" onclick="removeDynamicVideo('{{ $elementId }}'); event.stopPropagation();" title="Hapus Elemen">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                            <button type="button" onclick="if(typeof toggleVideoEditForm === 'function') toggleVideoEditForm('{{ $elementId }}')" class="btn-edit-block">
                                                <i class="fas fa-pen" class="btn-edit-icon"></i> <span id="btnText_{{ $elementId }}">Edit</span>
                                            </button>
                                        </div>
                                    </div>

                                    <div id="formBody_{{ $elementId }}" class="edit-form-body" style="max-height: 0; opacity: 0; margin-top: 0;">
                                        <div class="edit-form-content">
                                            <div class="profile-form-header" style="margin-bottom: 20px; font-weight: 600; color: #0f172a; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                                                <i class="fab fa-youtube" style="color: #ef4444; margin-right: 8px;"></i> Pengaturan Video
                                            </div>
                                            
                                            <div class="profile-form-group">
                                                <label class="profile-form-label" style="font-weight: 600; color: #334155; margin-bottom: 8px; display: block;">Tautan Video YouTube</label>
                                                <div style="position: relative;">
                                                    <div style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #ef4444; font-size: 18px; pointer-events: none;">
                                                        <i class="fab fa-youtube"></i>
                                                    </div>
                                                    <input type="text" id="videoUrl_{{ $elementId }}" class="form-control-input" value="{{ $videoEl->video_url }}" placeholder="Tempel URL YouTube di sini..." oninput="updateVideoPreview('{{ $elementId }}')" style="padding-left: 42px; border-radius: 12px; background-color: #f8fafc; border: 1px solid #e2e8f0; transition: all 0.3s ease;">
                                                </div>
                                            </div>
                                            
                                            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; margin-top: 20px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between;">
                                                <div style="display: flex; align-items: center; gap: 12px;">
                                                    <div style="background: #fff; padding: 10px; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); color: #3b82f6; display: flex; align-items: center; justify-content: center;">
                                                        <i class="fas fa-play" style="font-size: 14px;"></i>
                                                    </div>
                                                    <div>
                                                        <label class="profile-form-label" style="margin-bottom: 2px; font-weight: 600; color: #334155;">Putar Otomatis (Autoplay)</label>
                                                        <span style="font-size: 12px; color: #64748b; line-height: 1.4; display: block;">Video akan otomatis diputar saat diakses.</span>
                                                    </div>
                                                </div>
                                                <label class="toggle-switch" style="margin: 0; flex-shrink: 0;">
                                                    <input type="checkbox" id="videoAutoplay_{{ $elementId }}" onchange="updateVideoPreview('{{ $elementId }}')" {{ $isAutoplay ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>

                                            <div class="element-action-footer">
                                                <button type="button" onclick="removeDynamicVideo('{{ $elementId }}'); event.stopPropagation();" class="btn-delete-element">
                                                    <i class="fas fa-trash-alt"></i> Hapus Elemen
                                                </button>
                                                <button type="button" onclick="saveDynamicVideo('{{ $elementId }}')" class="btn-save-element" id="btnSaveVideo_{{ $elementId }}">
                                                    Simpan
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        @if(isset($socialMediaElements) && $socialMediaElements->count() > 0)
                            @foreach($socialMediaElements as $socialEl)
                                @php 
                                    $elementId = 'socialBlock_' . $socialEl->id; 
                                    $isActive = $socialEl->is_active ?? true;
                                    $platforms = is_string($socialEl->platforms) ? json_decode($socialEl->platforms, true) : ($socialEl->platforms ?? []);
                                @endphp
                                <div id="{{ $elementId }}" class="draggable-element-block {{ $isActive ? '' : 'block-inactive' }}" data-element-type="social" data-db-id="{{ $socialEl->id }}">
                                    <div class="block-item-card" onclick="if(typeof toggleSocialEditForm === 'function') toggleSocialEditForm('{{ $elementId }}')">
                                        <i class="fas fa-grip-vertical drag-handle drag-handle-icon" onclick="event.stopPropagation()" title="Tarik ke atas/bawah untuk ubah urutan"></i>
                                        <div class="block-item-icon-wrapper">
                                            <i class="fas fa-share-alt"></i>
                                        </div>
                                        <div class="block-item-content">
                                            <div class="block-item-title-wrapper">
                                                <span>Media Sosial</span>
                                            </div>
                                        </div>
                                        <div class="block-item-actions" onclick="event.stopPropagation()">
                                            <div class="element-visibility-container">
                                                <span class="visibility-status-text {{ $isActive ? 'status-active' : 'status-inactive' }}" id="statusText_{{ $elementId }}">{{ $isActive ? 'Aktif' : 'Nonaktif' }}</span>
                                                <label class="toggle-switch">
                                                    <input type="checkbox" id="visibilitySwitch_{{ $elementId }}" onchange="toggleElementVisibility('{{ $elementId }}', this)" {{ $isActive ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                            </div>
                                            <button type="button" class="btn-element-action btn-delete-icon" onclick="removeDynamicSocialMedia('{{ $elementId }}'); event.stopPropagation();" title="Hapus Elemen">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                            <button type="button" onclick="if(typeof toggleSocialEditForm === 'function') toggleSocialEditForm('{{ $elementId }}')" class="btn-edit-block">
                                                <i class="fas fa-pen" class="btn-edit-icon"></i> <span id="btnText_{{ $elementId }}">Edit</span>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Form Edit untuk Media Sosial -->
                                    <div id="formBody_{{ $elementId }}" class="edit-form-body" style="max-height: 0; opacity: 0; margin-top: 0;">
                                        <div class="edit-form-content">
                                            <form id="socialForm_{{ $elementId }}">
                                                <div class="social-edit-header">
                                                    <h4 class="form-section-title" style="margin-bottom: 5px;"><i class="fas fa-share-alt"></i> Pengaturan Media Sosial</h4>
                                                    <p style="font-size: 13px; color: #6b7280; margin-bottom: 20px;">Aktifkan platform yang ingin Anda tampilkan.</p>
                                                </div>

                                                <div class="social-platforms-list" id="social_platforms_list_{{ $elementId }}">
                                                    @php
                                                        $availablePlatforms = [
                                                            'instagram' => ['icon' => 'fab fa-instagram', 'color' => '#E1306C', 'label' => 'Instagram', 'placeholder' => 'contoh: https://instagram.com/username'],
                                                            'facebook' => ['icon' => 'fab fa-facebook', 'color' => '#1877F2', 'label' => 'Facebook', 'placeholder' => 'contoh: https://facebook.com/username'],
                                                            'youtube' => ['icon' => 'fab fa-youtube', 'color' => '#FF0000', 'label' => 'YouTube', 'placeholder' => 'contoh: https://youtube.com/c/username'],
                                                            'whatsapp' => ['icon' => 'fab fa-whatsapp', 'color' => '#25D366', 'label' => 'WhatsApp', 'placeholder' => 'contoh: 628123456789'],
                                                            'telegram' => ['icon' => 'fab fa-telegram', 'color' => '#0088cc', 'label' => 'Telegram', 'placeholder' => 'contoh: username_anda'],
                                                            'tiktok' => ['icon' => 'fab fa-tiktok', 'color' => '#000000', 'label' => 'TikTok', 'placeholder' => 'contoh: https://tiktok.com/@username'],
                                                            'twitter' => ['icon' => 'fab fa-x-twitter', 'color' => '#000000', 'label' => 'X (Twitter)', 'placeholder' => 'contoh: https://x.com/username'],
                                                            'email' => ['icon' => 'fas fa-envelope', 'color' => '#ea4335', 'label' => 'Email', 'placeholder' => 'contoh: email@anda.com'],
                                                        ];
                                                    @endphp

                                                    @foreach($availablePlatforms as $platKey => $platInfo)
                                                        @if(array_key_exists($platKey, $platforms) && !empty($platforms[$platKey]))
                                                            <div class="social-platform-item" id="platform_item_{{ $platKey }}_{{ $elementId }}">
                                                                <div class="platform-header">
                                                                    <div class="platform-info">
                                                                        <i class="{{ $platInfo['icon'] }}" style="color: {{ $platInfo['color'] }}; font-size: 20px; width: 24px; text-align: center;"></i>
                                                                        <span class="platform-name">{{ $platInfo['label'] }}</span>
                                                                    </div>
                                                                    <button type="button" class="btn-remove-platform" onclick="removeSocialPlatformFromForm('{{ $elementId }}', '{{ $platKey }}')" title="Hapus Platform">
                                                                        <i class="fas fa-trash-alt"></i>
                                                                    </button>
                                                                </div>
                                                                <div class="platform-input-container">
                                                                    <label class="form-label-custom">URL atau Username {{ $platInfo['label'] }}</label>
                                                                    <input type="text" id="input_{{ $platKey }}_{{ $elementId }}" class="form-input-custom platform-input-trigger" data-platform="{{ $platKey }}" data-element="{{ $elementId }}" value="{{ $platforms[$platKey] }}" placeholder="{{ $platInfo['placeholder'] }}" onkeyup="updateSocialPreview('{{ $elementId }}')" onchange="updateSocialPreview('{{ $elementId }}')">
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>

                                                <!-- ADD PLATFORM BUTTON -->
                                                <div style="margin-top: 16px; text-align: center;">
                                                    <button type="button" class="btn btn-outline btn-primary btn-sm" onclick="openSocialPlatformSelector('{{ $elementId }}')" style="border-radius: 8px; width: 100%; border: 1px dashed #cbd5e1; color: #64748b; background: white; padding: 12px; transition: all 0.2s;" onmouseover="this.style.borderColor='#3b82f6'; this.style.color='#3b82f6';" onmouseout="this.style.borderColor='#cbd5e1'; this.style.color='#64748b';">
                                                        <i class="fas fa-plus"></i> Tambah Platform Media Sosial
                                                    </button>
                                                </div>

                                                <div class="form-actions-wrapper">
                                                    <button type="button" class="btn-secondary" onclick="toggleSocialEditForm('{{ $elementId }}')">
                                                        Batal
                                                    </button>
                                                    <button type="button" class="btn-primary" onclick="saveDynamicSocialMedia('{{ $elementId }}')">
                                                    Simpan
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                    </div> <!-- Closes elementBlocksList -->
                </div> <!-- Closes digitalProductsSection -->

                </div> {{-- Closes #editorPanelElemen --}}

                {{-- ============================================================
                     PANEL PENGATURAN: Background, Layout Profil, Bentuk Blok
                     ============================================================ --}}
                <div id="editorPanelPengaturan" role="tabpanel" aria-labelledby="tab-btn-pengaturan" hidden>

                    {{-- ── SEKSI 1: BACKGROUND ── --}}
                    <section class="design-settings-section">
                        <header class="design-settings-section-header">
                            <div class="design-settings-section-icon">
                                <i class="fas fa-image"></i>
                            </div>
                            <div>
                                <h3 class="design-settings-section-title">Background Halaman</h3>
                                <p class="design-settings-section-desc">Pilih gambar atau warna untuk latar belakang</p>
                            </div>
                        </header>

                        {{-- Sub-tab: Gambar | Warna --}}
                        <div class="background-sub-tab-switcher" role="tablist">
                            <button
                                type="button"
                                id="bg-tab-gambar"
                                class="bg-sub-tab-btn {{ ($appearance && $appearance->background_type === 'image') ? 'is-active' : '' }}"
                                onclick="switchBackgroundTab('gambar')"
                            >
                                <i class="fas fa-image"></i> Gambar
                            </button>
                            <button
                                type="button"
                                id="bg-tab-warna"
                                class="bg-sub-tab-btn {{ (!$appearance || $appearance->background_type !== 'image') ? 'is-active' : '' }}"
                                onclick="switchBackgroundTab('warna')"
                            >
                                <i class="fas fa-palette"></i> Warna
                            </button>
                        </div>

                        {{-- Sub-panel: Pilih Gambar Background --}}
                        <div id="bgPanelGambar" class="background-image-grid" {{ ($appearance && $appearance->background_type === 'image') ? '' : 'hidden' }}>
                            @php
                                $backgroundImages = [
                                    'blue ocean.png'           => 'Blue Ocean',
                                    'city light.png'           => 'City Light',
                                    'clasic.png'               => 'Classic',
                                    'desert.png'               => 'Desert',
                                    'green flower.png'         => 'Green Flower',
                                    'library.png'              => 'Library',
                                    'mountain.png'             => 'Mountain',
                                    'news paper.png'           => 'News Paper',
                                    'pink candy.png'           => 'Pink Candy',
                                    'playstation abstract.png' => 'PS Abstract',
                                    'sunset.png'               => 'Sunset',
                                ];
                                $currentBgImage = ($appearance && $appearance->background_type === 'image')
                                    ? $appearance->background_color
                                    : null;
                            @endphp

                            {{-- Opsi: Tidak ada gambar (transparan / hanya warna) --}}
                            <label class="background-image-option {{ !$currentBgImage ? 'is-selected' : '' }}">
                                <input
                                    type="radio"
                                    name="design_background_image"
                                    value=""
                                    class="hidden-radio"
                                    {{ !$currentBgImage ? 'checked' : '' }}
                                    onchange="applyBackgroundImage('')"
                                >
                                <div class="bg-option-preview bg-option-none">
                                    <i class="fas fa-ban"></i>
                                </div>
                                <span class="bg-option-label">Tanpa Gambar</span>
                            </label>

                            @foreach($backgroundImages as $filename => $label)
                                <label class="background-image-option {{ $currentBgImage === $filename ? 'is-selected' : '' }}">
                                    <input
                                        type="radio"
                                        name="design_background_image"
                                        value="{{ $filename }}"
                                        class="hidden-radio"
                                        {{ $currentBgImage === $filename ? 'checked' : '' }}
                                        onchange="applyBackgroundImage('{{ $filename }}')"
                                    >
                                    <div class="bg-option-preview" style="background-image: url('{{ asset('images/background/' . $filename) }}'); background-size: cover; background-position: center;">
                                    </div>
                                    <span class="bg-option-label">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>

                        {{-- Sub-panel: Pilih Warna Background --}}
                        <div id="bgPanelWarna" class="background-color-panel" {{ ($appearance && $appearance->background_type === 'image') ? 'hidden' : '' }}>

                            <div class="bg-color-presets">
                                @php
                                    $colorPresets = [
                                        '#FFFFFF' => 'Putih',
                                        '#F8FAFC' => 'Abu Terang',
                                        '#F0FDF4' => 'Hijau Lembut',
                                        '#FFF7ED' => 'Oranye Lembut',
                                        '#EFF6FF' => 'Biru Lembut',
                                        '#FDF4FF' => 'Ungu Lembut',
                                        '#FFF1F2' => 'Merah Muda',
                                        '#FAFAF9' => 'Stone',
                                        '#1E293B' => 'Biru Gelap',
                                        '#111827' => 'Hitam',
                                    ];
                                    $currentBgColor = ($appearance && $appearance->background_type === 'color')
                                        ? ($appearance->background_color ?? '#FFFFFF')
                                        : '#FFFFFF';
                                @endphp

                                @foreach($colorPresets as $hex => $colorName)
                                    <button
                                        type="button"
                                        class="bg-color-preset-swatch {{ $currentBgColor === $hex ? 'is-selected' : '' }}"
                                        style="background-color: {{ $hex }};"
                                        title="{{ $colorName }}"
                                        onclick="applyBackgroundColor('{{ $hex }}')"
                                        data-color="{{ $hex }}"
                                    ></button>
                                @endforeach
                            </div>

                            <div class="bg-color-custom-row">
                                <label class="profile-form-label" for="bgColorCustomPicker">Warna Custom</label>
                                <div class="bg-color-picker-wrapper">
                                    <input
                                        type="color"
                                        id="bgColorCustomPicker"
                                        value="{{ $currentBgColor ?? '#FFFFFF' }}"
                                        oninput="applyBackgroundColor(this.value)"
                                        class="bg-color-custom-input"
                                    >
                                    <span id="bgColorHexDisplay" class="bg-color-hex-display">{{ $currentBgColor ?? '#FFFFFF' }}</span>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- ── SEKSI 2: LAYOUT PROFIL ── --}}
                    <section class="design-settings-section">
                        <header class="design-settings-section-header">
                            <div class="design-settings-section-icon">
                                <i class="fas fa-id-card"></i>
                            </div>
                            <div>
                                <h3 class="design-settings-section-title">Layout Profil</h3>
                                <p class="design-settings-section-desc">Atur posisi dan tampilan bagian profil</p>
                            </div>
                        </header>

                        @php
                            $currentLayout = $appearance->profile_layout ?? 'classic';
                        @endphp

                        <div class="profile-layout-options" role="radiogroup" aria-label="Pilih layout profil">

                            {{-- Layout 1: Title-Top — judul di area banner, avatar besar di tengah --}}
                            <label class="profile-layout-card {{ $currentLayout === 'title-top' ? 'is-selected' : '' }}">
                                <input type="radio" name="design_profile_layout" value="title-top" class="hidden-radio" {{ $currentLayout === 'title-top' ? 'checked' : '' }} onchange="applyProfileLayout('title-top')">
                                <div class="layout-preview layout-preview-title-top">
                                    {{-- Banner area dengan judul di dalamnya --}}
                                    <div class="lp-tt-header">
                                        <div class="lp-line lp-line-wide lp-line-dark"></div>
                                    </div>
                                    {{-- Avatar besar di tengah (di bawah banner, di area putih) --}}
                                    <div class="lp-tt-avatar"></div>
                                    {{-- Bio --}}
                                    <div class="lp-line lp-line-mid" style="margin-top: 4px;"></div>
                                    {{-- Blok konten --}}
                                    <div class="lp-block"></div>
                                </div>
                                <span class="layout-card-label">Title Top</span>
                            </label>

                            {{-- Layout 2: Classic — banner atas, avatar overlap di batas, judul & bio di bawah --}}
                            <label class="profile-layout-card {{ ($currentLayout === 'classic' || !$currentLayout) ? 'is-selected' : '' }}">
                                <input type="radio" name="design_profile_layout" value="classic" class="hidden-radio" {{ ($currentLayout === 'classic' || !$currentLayout) ? 'checked' : '' }} onchange="applyProfileLayout('classic')">
                                <div class="layout-preview layout-preview-classic">
                                    {{-- Banner atas --}}
                                    <div class="lp-cl-banner"></div>
                                    {{-- Avatar di tepi banner (overlap) --}}
                                    <div class="lp-cl-avatar"></div>
                                    {{-- Nama --}}
                                    <div class="lp-line lp-line-wide"></div>
                                    {{-- Bio --}}
                                    <div class="lp-line lp-line-mid"></div>
                                    {{-- Blok konten --}}
                                    <div class="lp-block"></div>
                                </div>
                                <span class="layout-card-label">Classic</span>
                            </label>

                            {{-- Layout 3: Side Panel — area abu portrait di kiri, avatar kiri atas, teks di kanan --}}
                            <label class="profile-layout-card {{ $currentLayout === 'side' ? 'is-selected' : '' }}">
                                <input type="radio" name="design_profile_layout" value="side" class="hidden-radio" {{ $currentLayout === 'side' ? 'checked' : '' }} onchange="applyProfileLayout('side')">
                                <div class="layout-preview layout-preview-side">
                                    {{-- Panel kiri abu --}}
                                    <div class="lp-side-panel">
                                        <div class="lp-side-avatar"></div>
                                    </div>
                                    {{-- Area kanan: nama + bio + blok --}}
                                    <div class="lp-side-content">
                                        <div class="lp-line lp-line-wide lp-line-dark"></div>
                                        <div class="lp-line lp-line-mid"></div>
                                        <div class="lp-block"></div>
                                        <div class="lp-block"></div>
                                    </div>
                                </div>
                                <span class="layout-card-label">Side Panel</span>
                            </label>

                        </div>
                    </section>

                    {{-- ── SEKSI 3: BENTUK BLOK ELEMEN ── --}}
                    <section class="design-settings-section">
                        <header class="design-settings-section-header">
                            <div class="design-settings-section-icon">
                                <i class="fas fa-vector-square"></i>
                            </div>
                            <div>
                                <h3 class="design-settings-section-title">Bentuk Blok</h3>
                                <p class="design-settings-section-desc">Atur sudut blok elemen di microsite</p>
                            </div>
                        </header>

                        @php $currentBlockShape = $appearance->block_shape ?? 'rounded'; @endphp

                        <div class="block-shape-options" role="radiogroup" aria-label="Pilih bentuk blok">

                            {{-- Shape 1: Sharp (siku-siku) --}}
                            <label class="block-shape-option {{ $currentBlockShape === 'sharp' ? 'is-selected' : '' }}">
                                <input type="radio" name="design_block_shape" value="sharp" class="hidden-radio" {{ $currentBlockShape === 'sharp' ? 'checked' : '' }} onchange="applyBlockShape('sharp')">
                                <div class="block-shape-preview block-shape-sharp"></div>
                                <span class="block-shape-label">Sharp</span>
                            </label>

                            {{-- Shape 2: Rounded (sudut bulat biasa) --}}
                            <label class="block-shape-option {{ $currentBlockShape === 'rounded' ? 'is-selected' : '' }}">
                                <input type="radio" name="design_block_shape" value="rounded" class="hidden-radio" {{ $currentBlockShape === 'rounded' ? 'checked' : '' }} onchange="applyBlockShape('rounded')">
                                <div class="block-shape-preview block-shape-rounded"></div>
                                <span class="block-shape-label">Rounded</span>
                            </label>

                            {{-- Shape 3: Pill (super rounded) --}}
                            <label class="block-shape-option {{ $currentBlockShape === 'pill' ? 'is-selected' : '' }}">
                                <input type="radio" name="design_block_shape" value="pill" class="hidden-radio" {{ $currentBlockShape === 'pill' ? 'checked' : '' }} onchange="applyBlockShape('pill')">
                                <div class="block-shape-preview block-shape-pill"></div>
                                <span class="block-shape-label">Pill</span>
                            </label>

                        </div>
                    </section>

                </div> {{-- Closes #editorPanelPengaturan --}}

            </div> <!-- Closes editor-left-panel -->


            <!-- RIGHT PANEL: STICKY PHONE PREVIEW -->
            <x-microsite.phone-preview :appearance="$appearance" :image-elements="$imageElements ?? null" :divider-elements="$dividerElements ?? null" :text-elements="$textElements ?? null" :video-elements="$videoElements ?? null" :social-media-elements="$socialMediaElements ?? null" />
        </div>
    @endif

</div>



<!-- NEW MICROSITE CREATION MULTI-STEP MODAL -->
<x-microsite.create-modal />



    <!-- TEMPLATES FOR DYNAMIC ELEMENTS -->
    <template id="image-block-template">
        <div id="__ELEMENT_ID__" class="draggable-element-block" data-element-type="image" class="draggable-element-block-inner">
            <div class="block-item-card" onclick="toggleImageEditForm('__ELEMENT_ID__')">
                <i class="fas fa-grip-vertical drag-handle drag-handle-icon" onclick="event.stopPropagation()" title="Tarik ke atas/bawah untuk ubah urutan"></i>
                <div class="block-item-icon-wrapper">
                    <i class="fas fa-image"></i>
                </div>
                <div class="block-item-content">
                    <div class="block-item-title-wrapper">
                        <span>Gambar</span>
                    </div>
                </div>
                <div class="block-item-actions" onclick="event.stopPropagation()">
                    <div class="element-visibility-container">
                        <span class="visibility-status-text status-active" id="statusText___ELEMENT_ID__">Aktif</span>
                        <label class="toggle-switch">
                            <input type="checkbox" id="visibilitySwitch___ELEMENT_ID__" onchange="toggleElementVisibility('__ELEMENT_ID__', this)" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    <button type="button" class="btn-element-action btn-delete-icon" onclick="removeDynamicElement('__ELEMENT_ID__'); event.stopPropagation();" title="Hapus Elemen">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                    <button type="button" onclick="toggleImageEditForm('__ELEMENT_ID__')" class="btn-edit-block">
                        <i class="fas fa-pen" class="btn-edit-icon"></i> <span id="btnText___ELEMENT_ID__">Edit</span>
                    </button>
                </div>
            </div>

            <div id="formBody___ELEMENT_ID__" class="edit-form-body" style="max-height: 0; opacity: 0; margin-top: 0;">
                <div class="profile-form-padding">
                    <div class="profile-form-header" style="margin-bottom: 16px;">
                        Pengaturan Elemen Gambar
                    </div>
                    
                    <div>
                        <label class="form-label-custom">Unggah Gambar</label>
                        <div class="upload-dropzone dynamic-dropzone image-block-dropzone">
                            <input type="file" accept="image/jpeg, image/png, image/gif" class="hidden-file-input" onchange="previewDynamicImage(this, '__ELEMENT_ID__')">
                            
                            <div id="placeholder___ELEMENT_ID__" class="dropzone-placeholder-flex">
                                <i class="fas fa-cloud-upload-alt upload-icon-indigo"></i>
                                <div class="upload-text-main">Seret gambar ke sini atau <span class="upload-text-highlight-indigo">browse</span></div>
                                <div class="upload-text-sub">supports JPG, JPEG, PNG & GIF</div>
                            </div>

                            <div id="previewCont___ELEMENT_ID__" class="dynamic-preview-container" style="display: none;">
                                                        <img src="" id="previewImg___ELEMENT_ID__" class="preview-img-contain">
                                                        <div class="edit-image-overlay">
                                                            <i class="fa-solid fa-pen-to-square"></i>
                                                        </div>
                                                    </div>
                            
                            <div id="error___ELEMENT_ID__" class="upload-size-error" style="display: none;">
                                <i class="fas fa-exclamation-circle" class="wizard-icon-back"></i> Gagal: Ukuran maksimal gambar adalah 2MB!
                            </div>
                        </div>
                    </div>

                    <div class="form-field-margin">
                        <label class="form-label-custom">URL Tautan (Opsional)</label>
                        <input type="url" id="link___ELEMENT_ID__" placeholder="https://..." class="form-input-custom" oninput="updateDynamicImageLink('__ELEMENT_ID__', this.value)">
                    </div>

                    <div class="element-action-footer">
                        <button type="button" onclick="removeDynamicElement('__ELEMENT_ID__')" class="btn-delete-element">
                            <i class="fas fa-trash-alt"></i> Hapus Elemen
                        </button>
                        <button type="button" onclick="saveDynamicElement('__ELEMENT_ID__')" class="btn-save-element">
                            Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <template id="image-live-template">
        <div id="live___ELEMENT_ID__" class="microsite-live-element" style="display: none; cursor: pointer;" onclick="if(typeof toggleImageEditForm === 'function') toggleImageEditForm('__ELEMENT_ID__', true);">
            <a id="liveLink___ELEMENT_ID__" class="live-element-link" style="pointer-events: none;">
                <img id="liveImg___ELEMENT_ID__" src="" class="live-element-img">
            </a>
        </div>
    </template>

    <template id="divider-block-template">
        <div id="__ELEMENT_ID__" class="draggable-element-block" data-element-type="divider">
            <div class="block-item-card" onclick="if(typeof toggleDividerEditForm === 'function') toggleDividerEditForm('__ELEMENT_ID__')">
                <i class="fas fa-grip-vertical drag-handle drag-handle-icon" onclick="event.stopPropagation()" title="Tarik ke atas/bawah untuk ubah urutan"></i>
                <div class="block-item-icon-wrapper">
                    <i class="fas fa-minus"></i>
                </div>
                <div class="block-item-content">
                    <div class="block-item-title-wrapper">
                        <span>Pembatas</span>
                    </div>
                </div>
                <div class="block-item-actions" onclick="event.stopPropagation()">
                    <div class="element-visibility-container">
                        <span class="visibility-status-text status-active" id="statusText___ELEMENT_ID__">Aktif</span>
                        <label class="toggle-switch">
                            <input type="checkbox" id="visibilitySwitch___ELEMENT_ID__" onchange="toggleElementVisibility('__ELEMENT_ID__', this)" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    <button type="button" class="btn-element-action btn-delete-icon" onclick="removeDynamicDivider('__ELEMENT_ID__'); event.stopPropagation();" title="Hapus Elemen">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                    <button type="button" onclick="if(typeof toggleDividerEditForm === 'function') toggleDividerEditForm('__ELEMENT_ID__')" class="btn-edit-block">
                        <i class="fas fa-pen" class="btn-edit-icon"></i> <span id="btnText___ELEMENT_ID__">Edit</span>
                    </button>
                </div>
            </div>

            <div id="formBody___ELEMENT_ID__" class="edit-form-body" style="max-height: 0; opacity: 0; margin-top: 0;">
                <div class="edit-form-content">
                    <div class="edit-form-group">
                        <label class="profile-form-label">Jenis Pembatas</label>
                        <div class="segment-control-wrapper">
                            <label class="segment-control-label">
                                <input type="radio" name="dividerTypeGroup___ELEMENT_ID__" value="line" class="hidden-radio" onchange="document.getElementById('dividerType___ELEMENT_ID__').value = this.value; updateDividerPreview('__ELEMENT_ID__'); updateSegmentedControl(this);" checked>
                                <div class="segment-btn active">
                                    <i class="fas fa-minus"></i> Garis
                                </div>
                            </label>
                            <label class="segment-control-label">
                                <input type="radio" name="dividerTypeGroup___ELEMENT_ID__" value="space" class="hidden-radio" onchange="document.getElementById('dividerType___ELEMENT_ID__').value = this.value; updateDividerPreview('__ELEMENT_ID__'); updateSegmentedControl(this);">
                                <div class="segment-btn">
                                    <i class="fas fa-arrows-alt-v"></i> Spasi Kosong
                                </div>
                            </label>
                            <input type="hidden" id="dividerType___ELEMENT_ID__" value="line">
                        </div>
                    </div>

                    <div class="slider-control-container">
                        <label class="profile-form-label slider-control-header">
                            <span class="slider-control-title">Ukuran Jarak</span>
                            <div class="slider-value-badge" id="dividerSizeValue___ELEMENT_ID__">20px</div>
                        </label>
                        <div class="slider-input-wrapper">
                            <button type="button" class="btn-slider-adjust" onclick="adjustDividerSize('__ELEMENT_ID__', -5)">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="range" id="dividerSize___ELEMENT_ID__" class="modern-range" min="10" max="100" step="5" value="20" oninput="updateDividerPreview('__ELEMENT_ID__')">
                            <button type="button" class="btn-slider-adjust" onclick="adjustDividerSize('__ELEMENT_ID__', 5)">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>

                    <div class="element-action-footer">
                        <button type="button" onclick="removeDynamicDivider('__ELEMENT_ID__'); event.stopPropagation();" class="btn-delete-element">
                            <i class="fas fa-trash-alt"></i> Hapus Elemen
                        </button>
                        <button type="button" onclick="saveDynamicDivider('__ELEMENT_ID__')" class="btn-save-element">
                            Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <template id="divider-live-template">
        <div id="live___ELEMENT_ID__" class="microsite-live-element live-divider-wrapper" style="padding: 10px 0;" onclick="if(typeof toggleDividerEditForm === 'function') toggleDividerEditForm('__ELEMENT_ID__', true);">
            <div id="liveDivider___ELEMENT_ID__" class="live-divider-inner" style="border-top: 2px solid #cbd5e1;"></div>
        </div>
    </template>

    <template id="text-block-template">
        <div id="__ELEMENT_ID__" class="draggable-element-block" data-element-type="text">
            <div class="block-item-card" onclick="if(typeof toggleTextEditForm === 'function') toggleTextEditForm('__ELEMENT_ID__')">
                <i class="fas fa-grip-vertical drag-handle drag-handle-icon" onclick="event.stopPropagation()" title="Tarik ke atas/bawah untuk ubah urutan"></i>
                <div class="block-item-icon-wrapper">
                    <i class="fas fa-font"></i>
                </div>
                <div class="block-item-content">
                    <div class="block-item-title-wrapper">
                        <span>Teks</span>
                    </div>
                </div>
                <div class="block-item-actions" onclick="event.stopPropagation()">
                    <div class="element-visibility-container">
                        <span class="visibility-status-text status-active" id="statusText___ELEMENT_ID__">Aktif</span>
                        <label class="toggle-switch">
                            <input type="checkbox" id="visibilitySwitch___ELEMENT_ID__" onchange="toggleElementVisibility('__ELEMENT_ID__', this)" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    <button type="button" class="btn-element-action btn-delete-icon" onclick="removeDynamicText('__ELEMENT_ID__'); event.stopPropagation();" title="Hapus Elemen">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                    <button type="button" onclick="if(typeof toggleTextEditForm === 'function') toggleTextEditForm('__ELEMENT_ID__')" class="btn-edit-block">
                        <i class="fas fa-pen" class="btn-edit-icon"></i> <span id="btnText___ELEMENT_ID__">Edit</span>
                    </button>
                </div>
            </div>

            <div id="formBody___ELEMENT_ID__" class="edit-form-body" style="max-height: 0; opacity: 0; margin-top: 0;">
                <div class="edit-form-content">
                    <div class="profile-form-header" style="margin-bottom: 20px; font-weight: 600; color: #0f172a; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                        <i class="fas fa-font" style="color: #3b82f6; margin-right: 8px;"></i> Pengaturan Teks
                    </div>
                    <div class="profile-form-group" style="margin-bottom: 24px;">
                        <label class="profile-form-label" style="font-weight: 600; color: #334155; margin-bottom: 8px; display: block;">Isi Teks Konten</label>
                        <div class="text-editor-container" style="box-shadow: 0 1px 3px rgba(0,0,0,0.05); transition: all 0.3s ease; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden;">
                            <div class="text-editor-toolbar" style="background: #f8fafc; padding: 8px; display: flex; flex-wrap: wrap; gap: 4px; border-bottom: 1px solid #e2e8f0; align-items: center;">
                            <button type="button" class="toolbar-btn" onclick="execCmd('__ELEMENT_ID__', 'bold')" title="Bold"><i class="fas fa-bold"></i></button>
                            <button type="button" class="toolbar-btn" onclick="execCmd('__ELEMENT_ID__', 'italic')" title="Italic"><i class="fas fa-italic"></i></button>
                            <button type="button" class="toolbar-btn" onclick="execCmd('__ELEMENT_ID__', 'underline')" title="Underline"><i class="fas fa-underline"></i></button>
                            <button type="button" class="toolbar-btn" onclick="execCmd('__ELEMENT_ID__', 'strikeThrough')" title="Strikethrough"><i class="fas fa-strikethrough"></i></button>
                            <span class="toolbar-divider"></span>
                            <button type="button" class="toolbar-btn" onclick="execCmd('__ELEMENT_ID__', 'justifyLeft')" title="Align Left"><i class="fas fa-align-left"></i></button>
                            <button type="button" class="toolbar-btn" onclick="execCmd('__ELEMENT_ID__', 'justifyCenter')" title="Align Center"><i class="fas fa-align-center"></i></button>
                            <button type="button" class="toolbar-btn" onclick="execCmd('__ELEMENT_ID__', 'justifyRight')" title="Align Right"><i class="fas fa-align-right"></i></button>
                            <span class="toolbar-divider"></span>
                            <button type="button" class="toolbar-btn" onclick="execCmd('__ELEMENT_ID__', 'insertUnorderedList')" title="Bullet List"><i class="fas fa-list-ul"></i></button>
                            <button type="button" class="toolbar-btn" onclick="execCmd('__ELEMENT_ID__', 'insertOrderedList')" title="Numbered List"><i class="fas fa-list-ol"></i></button>
                            <span class="toolbar-divider"></span>
                            <input type="color" class="toolbar-color-picker" onchange="execCmd('__ELEMENT_ID__', 'foreColor', this.value)" title="Text Color" value="#000000">
                            <span class="toolbar-divider"></span>
                            <div class="toolbar-dropdown">
                                <select onchange="changeTextSize('__ELEMENT_ID__', this.value)" class="toolbar-select" id="textSizeSelect___ELEMENT_ID__">
                                    <option value="12px">Kecil (12px)</option>
                                    <option value="16px" selected>Normal (16px)</option>
                                    <option value="24px">Besar (24px)</option>
                                    <option value="custom">Custom...</option>
                                </select>
                            </div>
                        </div>
                        <div id="customSizeWrapper___ELEMENT_ID__" class="custom-size-wrapper" style="display: none;">
                            <input type="number" id="customSizeInput___ELEMENT_ID__" class="toolbar-input" placeholder="Ukuran (px)" min="1" max="99" onchange="applyCustomSize('__ELEMENT_ID__')">
                            <button type="button" class="toolbar-btn-text" onclick="applyCustomSize('__ELEMENT_ID__')">Terapkan</button>
                        </div>
                        <div id="editorContent___ELEMENT_ID__" class="text-editor-area" contenteditable="true" oninput="updateTextPreview('__ELEMENT_ID__')" style="padding: 16px; min-height: 120px; font-size: 16px; line-height: 1.5; outline: none;">Teks Anda di sini...</div>
                    </div>
                    </div>

                    <div class="element-action-footer" style="margin-top: 15px;">
                        <button type="button" onclick="removeDynamicText('__ELEMENT_ID__'); event.stopPropagation();" class="btn-delete-element">
                            <i class="fas fa-trash-alt"></i> Hapus Elemen
                        </button>
                        <button type="button" onclick="saveDynamicText('__ELEMENT_ID__')" class="btn-save-element" id="btnSaveText___ELEMENT_ID__">
                            Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <template id="video-live-template">
        <div class="video-container" id="liveVideoContainer___ELEMENT_ID__">
            <!-- iframe will be generated here -->
            <div style="background: #f3f4f6; padding: 40px 20px; text-align: center; border-radius: 8px; color: #6b7280; font-size: 14px;">
                <i class="fab fa-youtube" style="font-size: 24px; margin-bottom: 8px; display: block;"></i>
                Masukkan URL YouTube
            </div>
        </div>
    </template>

    <template id="video-block-template">
        <div id="__ELEMENT_ID__" class="draggable-element-block" data-element-type="video">
            <div class="block-item-card" onclick="if(typeof toggleVideoEditForm === 'function') toggleVideoEditForm('__ELEMENT_ID__')">
                <i class="fas fa-grip-vertical drag-handle drag-handle-icon" onclick="event.stopPropagation()" title="Tarik ke atas/bawah untuk ubah urutan"></i>
                <div class="block-item-icon-wrapper">
                    <i class="fab fa-youtube"></i>
                </div>
                <div class="block-item-content">
                    <div class="block-item-title-wrapper">
                        <span>Video</span>
                    </div>
                </div>
                <div class="block-item-actions" onclick="event.stopPropagation()">
                    <div class="element-visibility-container">
                        <span class="visibility-status-text status-active" id="statusText___ELEMENT_ID__">Aktif</span>
                        <label class="toggle-switch">
                            <input type="checkbox" id="visibilitySwitch___ELEMENT_ID__" onchange="toggleElementVisibility('__ELEMENT_ID__', this)" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    <button type="button" class="btn-element-action btn-delete-icon" onclick="removeDynamicVideo('__ELEMENT_ID__'); event.stopPropagation();" title="Hapus Elemen">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                    <button type="button" onclick="if(typeof toggleVideoEditForm === 'function') toggleVideoEditForm('__ELEMENT_ID__')" class="btn-edit-block">
                        <i class="fas fa-pen" class="btn-edit-icon"></i> <span id="btnText___ELEMENT_ID__">Edit</span>
                    </button>
                </div>
            </div>

            <div id="formBody___ELEMENT_ID__" class="edit-form-body" style="max-height: 0; opacity: 0; margin-top: 0;">
                <div class="edit-form-content">
                    <div class="profile-form-header" style="margin-bottom: 20px; font-weight: 600; color: #0f172a; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                        <i class="fab fa-youtube" style="color: #ef4444; margin-right: 8px;"></i> Pengaturan Video
                    </div>
                    
                    <div class="profile-form-group">
                        <label class="profile-form-label" style="font-weight: 600; color: #334155; margin-bottom: 8px; display: block;">Tautan Video YouTube</label>
                        <div style="position: relative;">
                            <div style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #ef4444; font-size: 18px; pointer-events: none;">
                                <i class="fab fa-youtube"></i>
                            </div>
                            <input type="text" id="videoUrl___ELEMENT_ID__" class="form-control-input" value="" placeholder="Tempel URL YouTube di sini..." oninput="updateVideoPreview('__ELEMENT_ID__')" style="padding-left: 42px; border-radius: 12px; background-color: #f8fafc; border: 1px solid #e2e8f0; transition: all 0.3s ease;">
                        </div>
                    </div>
                    
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; margin-top: 20px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="background: #fff; padding: 10px; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); color: #3b82f6; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-play" style="font-size: 14px;"></i>
                            </div>
                            <div>
                                <label class="profile-form-label" style="margin-bottom: 2px; font-weight: 600; color: #334155;">Putar Otomatis (Autoplay)</label>
                                <span style="font-size: 12px; color: #64748b; line-height: 1.4; display: block;">Video akan otomatis diputar saat diakses.</span>
                            </div>
                        </div>
                        <label class="toggle-switch" style="margin: 0; flex-shrink: 0;">
                            <input type="checkbox" id="videoAutoplay___ELEMENT_ID__" onchange="updateVideoPreview('__ELEMENT_ID__')">
                            <span class="toggle-slider"></span>
                        </label>
                    </div>

                    <div class="element-action-footer">
                        <button type="button" onclick="removeDynamicVideo('__ELEMENT_ID__'); event.stopPropagation();" class="btn-delete-element">
                            <i class="fas fa-trash-alt"></i> Hapus Elemen
                        </button>
                        <button type="button" onclick="saveDynamicVideo('__ELEMENT_ID__')" class="btn-save-element" id="btnSaveVideo___ELEMENT_ID__">
                            Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <!-- CUSTOM DELETE CONFIRMATION MODAL -->

    <template id="social-live-template">
        <div id="live___ELEMENT_ID__" class="microsite-live-element live-social-wrapper" style="display: none;" onclick="if(typeof toggleSocialEditForm === 'function') toggleSocialEditForm('__ELEMENT_ID__', true);">
            <div id="liveSocialContainer___ELEMENT_ID__" class="live-social-container" style="display: flex; justify-content: center; gap: 12px; padding: 10px 0;">
                <!-- Social media icons will be rendered here dynamically -->
            </div>
        </div>
    </template>

    <template id="social-platform-item-template">
        <div class="social-platform-item" id="platform_item___PLATFORM_____ELEMENT_ID__">
            <div class="platform-header">
                <div class="platform-info">
                    <i class="__ICON_CLASS__" style="color: __COLOR__; font-size: 20px; width: 24px; text-align: center;"></i>
                    <span class="platform-name">__PLATFORM_NAME__</span>
                </div>
                        <button type="button" class="btn-remove-platform" onclick="removeSocialPlatformFromForm('__ELEMENT_ID__', '__PLATFORM__')" title="Hapus Platform">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
            <div class="platform-input-container">
                <label class="form-label-custom">URL atau Username __PLATFORM_NAME__</label>
                <input type="text" id="input___PLATFORM_____ELEMENT_ID__" class="form-input-custom platform-input-trigger" data-platform="__PLATFORM__" data-element="__ELEMENT_ID__" placeholder="__PLACEHOLDER__" onkeyup="updateSocialPreview('__ELEMENT_ID__')" onchange="updateSocialPreview('__ELEMENT_ID__')">
            </div>
        </div>
    </template>
    <template id="social-block-template">
        <div id="__ELEMENT_ID__" class="draggable-element-block" data-element-type="social">
            <div class="block-item-card" onclick="if(typeof toggleSocialEditForm === 'function') toggleSocialEditForm('__ELEMENT_ID__')">
                <i class="fas fa-grip-vertical drag-handle drag-handle-icon" onclick="event.stopPropagation()" title="Tarik ke atas/bawah untuk ubah urutan"></i>
                <div class="block-item-icon-wrapper">
                    <i class="fas fa-share-alt"></i>
                </div>
                <div class="block-item-content">
                    <div class="block-item-title-wrapper">
                        <span>Media Sosial</span>
                    </div>
                </div>
                <div class="block-item-actions" onclick="event.stopPropagation()">
                    <div class="element-visibility-container">
                        <span class="visibility-status-text status-active" id="statusText___ELEMENT_ID__">Aktif</span>
                        <label class="toggle-switch">
                            <input type="checkbox" id="visibilitySwitch___ELEMENT_ID__" onchange="toggleElementVisibility('__ELEMENT_ID__', this)" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    <button type="button" class="btn-element-action btn-delete-icon" onclick="removeDynamicElement('__ELEMENT_ID__'); event.stopPropagation();" title="Hapus Elemen">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                    <button type="button" onclick="if(typeof toggleSocialEditForm === 'function') toggleSocialEditForm('__ELEMENT_ID__')" class="btn-edit-block">
                        <i class="fas fa-pen" class="btn-edit-icon"></i> <span id="btnText___ELEMENT_ID__">Edit</span>
                    </button>
                </div>
            </div>

            <!-- Form Edit untuk Media Sosial -->
            <div id="editForm___ELEMENT_ID__" class="edit-form-body">
                <div class="edit-form-content">
                    <form id="socialForm___ELEMENT_ID__">
                        <div class="social-edit-header">
                            <h4 class="form-section-title" style="margin-bottom: 5px;"><i class="fas fa-share-alt"></i> Pengaturan Media Sosial</h4>
                            <p style="font-size: 13px; color: #6b7280; margin-bottom: 20px;">Aktifkan platform yang ingin Anda tampilkan.</p>
                        </div>

                        <!-- PLATFORM LIST CONTAINER -->
                        <div class="social-platforms-list" id="social_platforms_list___ELEMENT_ID__">
                            <!-- Selected platforms will be appended here via JS -->
                        </div>

                        <!-- ADD PLATFORM BUTTON -->
                        <div style="margin-top: 16px; text-align: center;">
                            <button type="button" class="btn btn-outline btn-primary btn-sm" onclick="openSocialPlatformSelector('__ELEMENT_ID__')" style="border-radius: 8px; width: 100%; border: 1px dashed #cbd5e1; color: #64748b; background: white; padding: 12px; transition: all 0.2s;" onmouseover="this.style.borderColor='#3b82f6'; this.style.color='#3b82f6';" onmouseout="this.style.borderColor='#cbd5e1'; this.style.color='#64748b';">
                                <i class="fas fa-plus"></i> Tambah Platform Media Sosial
                            </button>
                        </div>

                        <div class="form-actions-wrapper">
                            <button type="button" class="btn-secondary" onclick="toggleSocialEditForm('__ELEMENT_ID__')">
                                Batal
                            </button>
                            <button type="button" class="btn-primary" onclick="saveDynamicSocialMedia('__ELEMENT_ID__')">
                                <i class="fas fa-save" style="margin-right: 6px;"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>
    <!-- Social Platform Selection Modal -->
    <div id="socialPlatformModal" class="custom-confirm-modal-overlay">
        <div class="custom-confirm-modal-box platform-modal">
            <h3 class="platform-modal-title">Choose your platforms</h3>
            
            <div class="social-platform-grid">
                <button type="button" class="btn-select-platform" data-platform="linkedin" onclick="toggleSocialPlatformSelection(this)">
                    <i class="fab fa-linkedin platform-icon" style="color: #0077b5;"></i>
                    <div class="platform-info">
                        <div class="platform-name">Linkedin</div>
                        <div class="platform-desc">Good for B2B businesses</div>
                    </div>
                    <div class="platform-checkbox"></div>
                </button>
                <button type="button" class="btn-select-platform" data-platform="twitter" onclick="toggleSocialPlatformSelection(this)">
                    <i class="fab fa-x-twitter platform-icon" style="color: #000000;"></i>
                    <div class="platform-info">
                        <div class="platform-name">X (Twitter)</div>
                        <div class="platform-desc">Good for B2B businesses</div>
                    </div>
                    <div class="platform-checkbox"></div>
                </button>
                <button type="button" class="btn-select-platform" data-platform="facebook" onclick="toggleSocialPlatformSelection(this)">
                    <i class="fab fa-facebook platform-icon" style="color: #1877F2;"></i>
                    <div class="platform-info">
                        <div class="platform-name">Facebook</div>
                        <div class="platform-desc">Good for B2B businesses</div>
                    </div>
                    <div class="platform-checkbox"></div>
                </button>
                <button type="button" class="btn-select-platform" data-platform="instagram" onclick="toggleSocialPlatformSelection(this)">
                    <i class="fab fa-instagram platform-icon" style="color: #E1306C;"></i>
                    <div class="platform-info">
                        <div class="platform-name">Instagram</div>
                        <div class="platform-desc">Good for B2B businesses</div>
                    </div>
                    <div class="platform-checkbox"></div>
                </button>
                <button type="button" class="btn-select-platform" data-platform="reddit" onclick="toggleSocialPlatformSelection(this)">
                    <i class="fab fa-reddit platform-icon" style="color: #FF4500;"></i>
                    <div class="platform-info">
                        <div class="platform-name">Reddit</div>
                        <div class="platform-desc">Good for B2B businesses</div>
                    </div>
                    <div class="platform-checkbox"></div>
                </button>
                <button type="button" class="btn-select-platform" data-platform="youtube" onclick="toggleSocialPlatformSelection(this)">
                    <i class="fab fa-youtube platform-icon" style="color: #FF0000;"></i>
                    <div class="platform-info">
                        <div class="platform-name">YouTube</div>
                        <div class="platform-desc">Good for B2B businesses</div>
                    </div>
                    <div class="platform-checkbox"></div>
                </button>
                <button type="button" class="btn-select-platform" data-platform="whatsapp" onclick="toggleSocialPlatformSelection(this)">
                    <i class="fab fa-whatsapp platform-icon" style="color: #25D366;"></i>
                    <div class="platform-info">
                        <div class="platform-name">WhatsApp</div>
                        <div class="platform-desc">Good for B2B businesses</div>
                    </div>
                    <div class="platform-checkbox"></div>
                </button>
                <button type="button" class="btn-select-platform" data-platform="tiktok" onclick="toggleSocialPlatformSelection(this)">
                    <i class="fab fa-tiktok platform-icon" style="color: #000000;"></i>
                    <div class="platform-info">
                        <div class="platform-name">TikTok</div>
                        <div class="platform-desc">Good for B2B businesses</div>
                    </div>
                    <div class="platform-checkbox"></div>
                </button>
            </div>
            
            <div class="platform-modal-actions">
                <button type="button" class="btn-platform-back" onclick="closeSocialPlatformSelector()">Back to Previous</button>
                <button type="button" class="btn-platform-finish" onclick="finishSocialPlatformSelection()">Finish Steps</button>
            </div>
        </div>
    </div>
    <div id="customDeleteConfirmModal" class="custom-confirm-modal-overlay">
        <div class="custom-confirm-modal-box">
            <button class="custom-confirm-close-btn" onclick="closeDeleteConfirmModal()">
                <i class="fas fa-times"></i>
            </button>
            <div class="custom-confirm-icon-wrapper">
                <i class="fas fa-trash-alt"></i>
            </div>
            <h3 class="custom-confirm-title" id="customDeleteConfirmTitle">Are you sure you want to delete this product?</h3>
            <div class="custom-confirm-actions">
                <button class="btn-custom-confirm-cancel" onclick="closeDeleteConfirmModal()">Batal</button>
                <button class="btn-custom-confirm-submit" id="btnConfirmDelete" onclick="if(typeof window.confirmDeleteCallback === 'function') { window.confirmDeleteCallback(); } closeDeleteConfirmModal();">Ya, Hapus</button>
            </div>
        </div>
    </div>

</div>
@endsection

@push("scripts")
<!-- Include SortableJS for robust drag and drop -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<script>
    window.MicrositeConfig = {
        authUserName: '{{ Auth::user()->name }}',
        translations: {
            addElement: '{{ __("admin.add_element") }}'
        }
    };
</script>
<script src="{{ asset('js/microsite-editor.js') }}"></script>



<div id="micrositeEditorUrls" style="display: none;"
    data-route-image-delete="{{ url('/admin/elements/image') }}"
    data-route-image-store="{{ route('admin.elements.image.store') }}"
    data-route-divider-delete="{{ url('/admin/elements/divider') }}"
    data-route-divider-store="{{ route('admin.elements.divider.store') }}"
    data-route-text-delete="{{ url('/admin/elements/text') }}"
    data-route-text-store="{{ route('admin.elements.text.store') }}"
    data-route-video-delete="{{ url('/admin/elements/video') }}"
    data-route-video-store="{{ route('admin.elements.video.store') }}"
    data-route-social-delete="{{ url('/admin/elements/social') }}"
    data-route-social-store="{{ route('admin.elements.social.store') }}"
    data-route-order-update="{{ route('admin.elements.order.update') }}"
    data-route-appearance-update="{{ route('admin.appearance.update') }}"
    data-route-design-settings-update="{{ route('admin.appearance.design-settings.update') }}"
    data-appearance-blocks-order="{{ $appearance->blocks_order ?? '' }}">
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
    if (panelGambar) panelGambar.hidden = (subTab === 'warna');
    if (panelWarna)  panelWarna.hidden  = (subTab === 'gambar');
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
