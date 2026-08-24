@extends("layouts.admin")

@section("page_title", __('admin.microsite_management'))

@push("styles")
<link rel="stylesheet" href="{{ asset('css/pages/mylinkan.css') }}?v={{ filemtime(public_path('css/pages/mylinkan.css')) }}" data-turbo-track="reload">
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
            <h2 class="editor-mode-title">
                <a href="{{ route('admin.mylinkan', ['mode' => 'gallery']) }}" class="back-link">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <i class="fas fa-sliders-h text-brand-orange"></i> {{ __('admin.edit_content_blocks') }}
            </h2>
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
                                    <div class="option-card-desc">Tambahkan teks custom</div>
                                </div>

                                <!-- Element Option 4: Video -->
                                <div class="element-option-card" onclick="addVideoElement()">
                                    <div class="option-card-title">Embed Video</div>
                                    <div class="option-card-desc">Embed video dari YouTube</div>
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

                    </div> <!-- Closes elementBlocksList -->
                </div> <!-- Closes digitalProductsSection -->
            </div> <!-- Closes editor-left-panel -->

            <!-- RIGHT PANEL: STICKY PHONE PREVIEW -->
            <x-microsite.phone-preview :appearance="$appearance" :image-elements="$imageElements ?? null" :divider-elements="$dividerElements ?? null" :text-elements="$textElements ?? null" :video-elements="$videoElements ?? null" />
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
    data-route-order-update="{{ route('admin.elements.order.update') }}"
    data-route-appearance-update="{{ route('admin.appearance.update') }}"
    data-appearance-blocks-order="{{ $appearance->blocks_order ?? '' }}">
</div>

@endpush
