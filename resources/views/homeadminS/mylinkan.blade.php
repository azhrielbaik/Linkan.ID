@extends("layouts.admin")

@section("page_title", __('admin.microsite_management'))

@push("styles")
<link rel="stylesheet" href="{{ asset('css/pages/mylinkan.css') }}?v={{ filemtime(public_path('css/pages/mylinkan.css')) }}" data-turbo-track="reload">
@endpush

@section("content")
<div class="dashboard-mylinkan-page">

<div class="microsite-container">
    
    <!-- COMBINED HEADER & MODE SWITCH -->
    <div class="section-header" style="margin-top: 10px;">
        @if($viewMode == 'gallery')
            <div style="display: flex; justify-content: space-between; align-items: center; width: 100%; flex-wrap: wrap; gap: 15px;">
                <h2 class="gallery-title" style="margin: 0;"><i class="fas fa-layer-group" style="color: #5A5BF1;"></i> {{ __('admin.my_microsite_list') }}</h2>
                
                <!-- ACTION BAR: SEARCH, FILTER, BUAT BARU -->
                <div class="microsite-actions-bar">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Cari microsite...">
                    </div>
                    <button type="button" class="btn-action-secondary" style="padding: 10px 16px;">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <button type="button" class="btn-action-primary" style="padding: 10px 16px;" onclick="openNewMicrositeModal()">
                        <i class="fas fa-plus"></i> Buat Baru
                    </button>
                </div>
            </div>
        @else
            <h2 style="display: flex; align-items: center; margin: 0;">
                <a href="{{ route('admin.mylinkan', ['mode' => 'gallery']) }}" style="color: #6b7280; text-decoration: none; margin-right: 10px;">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <i class="fas fa-sliders-h" style="color: #5A5BF1; margin-right: 10px;"></i> {{ __('admin.edit_content_blocks') }}
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
                            <i class="fas fa-edit"></i> {{ __('admin.edit_block') }}
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
                                <div class="element-option-card card-image" onclick="addGambarElement()">
                                    <div style="font-weight: 600; font-size: 14px; color: #111827; margin-bottom: 4px;">Gambar</div>
                                    <div style="font-size: 13px; color: #6b7280;">Upload gambar & link</div>
                                </div>

                                <!-- Element Option 2: Digital Product Block -->
                                <div class="element-option-card card-product">
                                    <div style="font-weight: 600; font-size: 14px; color: #111827; margin-bottom: 4px;">Produk Digital</div>
                                    <div style="font-size: 13px; color: #6b7280;">Jual file/kursus</div>
                                </div>

                                <!-- Element Option 3: Shortlink / Custom Link -->
                                <div class="element-option-card card-link">
                                    <div style="font-weight: 600; font-size: 14px; color: #111827; margin-bottom: 4px;">Tautan Kustom</div>
                                    <div style="font-size: 13px; color: #6b7280;">Link eksternal</div>
                                </div>

                                <!-- Element Option 4: Social Icons -->
                                <div class="element-option-card card-social">
                                    <div style="font-weight: 600; font-size: 14px; color: #111827; margin-bottom: 4px;">Sosial Media</div>
                                    <div style="font-size: 13px; color: #6b7280;">Hubungkan akun</div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- EDIT ELEMENT SECTION CONTAINER (DRAGGABLE BLOCKS) -->
                <div id="digitalProductsSection" style="margin-bottom: 30px; transition: opacity 0.25s ease, transform 0.25s ease;">
                    
                    <!-- EDIT ELEMENT HEADER -->
                    <div id="elementSectionHeader" class="edit-element-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <h3 style="font-size: 16px; font-weight: 700; color: #111827; margin: 0; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-layer-group" style="color: #5A5BF1;"></i> Edit Element
                        </h3>
                        <span style="font-size: 11px; color: #6b7280; font-weight: 500;">
                            <i class="fas fa-arrows-alt-v" style="margin-right: 4px;"></i> Drag & Drop untuk mengurutkan
                        </span>
                    </div>

                    <!-- DRAGGABLE ELEMENT BLOCKS LIST CONTAINER -->
                    <div id="elementBlocksList" style="display: flex; flex-direction: column; gap: 10px;">
                        
                        <!-- 1. PROFILE BLOCK CARD (DRAGGABLE, VISIBLE BY DEFAULT IN EDIT MODE) -->
                        <div id="profileBlockCard" class="draggable-element-block" data-element-type="profile" class="draggable-element-block-inner">
                            
                            <!-- COLLAPSED BLOCK HEADER CARD -->
                            <div class="block-item-card" onclick="toggleProfileEditForm()">
                                <i class="fas fa-grip-vertical drag-handle" class="drag-handle-icon" onclick="event.stopPropagation()" title="Tarik ke atas/bawah untuk ubah urutan"></i>
                                <div style="width: 36px; height: 36px; border-radius: 8px; color: #FF9040; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0;">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                                <div class="block-item-content">
                                    <div class="block-item-title-wrapper">
                                        <span>Profil</span>
                                        <span style="font-size: 10px; font-weight: 700; background: #5A5BF1; color: #ffffff; padding: 2px 8px; border-radius: 4px;">Elemen</span>
                                    </div>
                                </div>
                                <div class="block-item-actions" onclick="event.stopPropagation()">
                                    <button type="button" onclick="toggleProfileEditForm()" style="background: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 8px; padding: 6px 12px; font-size: 12px; font-weight: 700; color: #374151; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.2s ease;" onmouseover="this.style.background='#FF9040'; this.style.color='#ffffff'; this.style.borderColor='#FF9040';" onmouseout="this.style.background='#f3f4f6'; this.style.color='#374151'; this.style.borderColor='#e5e7eb';">
                                        <i class="fas fa-pen" class="btn-edit-icon"></i> <span id="profileEditBtnText">Edit</span>
                                    </button>
                                </div>
                            </div>

                        <!-- EXPANDABLE EDIT FORM BODY (SLIDES DOWN WHEN EDIT CLICKED) -->
                        <div id="profileEditFormBody" class="profile-edit-form-body">
                            <div class="profile-form-padding">
                                <form action="{{ route('admin.appearance.update') }}" method="POST" enctype="multipart/form-data" id="profileBlockForm">
                                    @csrf
                                    <input type="hidden" name="theme_color" value="{{ $appearance->theme_color ?? '#5A5BF1' }}">
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
                                                
                                                <div id="bannerPreviewPlaceholder" class="banner-placeholder" style="display: {{ ($appearance && $appearance->banner) ? 'none' : 'flex' }};">
                                                    <i class="fas fa-cloud-upload-alt dropzone-icon"></i>
                                                    <div class="dropzone-text-primary">
                                                        Drop your images here or <span class="dropzone-browse-text">browse</span>
                                                    </div>
                                                    <div class="dropzone-text-secondary">supports JPG, JPEG, PNG & GIF</div>
                                                </div>

                                                <div id="bannerPreviewContainer" class="banner-preview-container" style="display: {{ ($appearance && $appearance->banner) ? 'block' : 'none' }};">
                                                    @if($appearance && $appearance->banner)
                                                        <img src="{{ asset('storage/' . $appearance->banner) }}" id="bannerPreviewImg" class="live-phone-banner-img">
                                                    @else
                                                        <img src="" id="bannerPreviewImg" class="banner-preview-img" style="display: none;">
                                                    @endif
                                                </div>
                                                
                                                <div id="bannerSizeError" class="dropzone-error-msg" style="display: none;">
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
                                                            <img src="" id="avatarPreviewImg" class="banner-preview-img" style="display: none;">
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

                                                <div id="avatarSizeError" class="dropzone-error-msg dropzone-error-sm" style="display: none;">
                                                    <i class="fas fa-exclamation-circle error-icon"></i> Gagal: Ukuran maksimal foto profil adalah 2MB!
                                                </div>
                                            </div>
                                        </div>

                                        <!-- 3. BENTUK PROFILE (PROFILE SHAPE) -->
                                        <div>
                                            <label class="profile-form-label" style="margin-bottom: 8px;">Bentuk Foto Profil</label>
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

                        @if(isset($imageElements))
                            @foreach($imageElements as $imageEl)
                                @php $elementId = 'imageBlock_' . $imageEl->id; @endphp
                                <div id="{{ $elementId }}" class="draggable-element-block existing-image-element" data-element-type="image" data-db-id="{{ $imageEl->id }}" class="draggable-element-block-inner">
                                    <div class="block-item-card" onclick="toggleImageEditForm('{{ $elementId }}')">
                                        <i class="fas fa-grip-vertical drag-handle" class="drag-handle-icon" onclick="event.stopPropagation()" title="Tarik ke atas/bawah untuk ubah urutan"></i>
                                        <div class="block-item-icon-wrapper">
                                            <i class="fas fa-image"></i>
                                        </div>
                                        <div class="block-item-content">
                                            <div class="block-item-title-wrapper">
                                                <span>Gambar</span>
                                            </div>
                                        </div>
                                        <div class="block-item-actions" onclick="event.stopPropagation()">
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

                    </div> <!-- Closes elementBlocksList -->
                </div> <!-- Closes digitalProductsSection -->
            </div> <!-- Closes editor-left-panel -->

            <!-- RIGHT PANEL: STICKY PHONE PREVIEW -->
            <div class="editor-sticky-preview clean-sticky-preview">
                <!-- SECTION TITLE -->
                <div class="phone-preview-header">
                    <h3 class="phone-preview-title">
                        <i class="fas fa-mobile-alt phone-preview-icon"></i> {{ __('admin.live_phone_preview') }}
                    </h3>
                </div>

                <!-- SIDE-BY-SIDE FLEX CONTAINER: PHONE MOCKUP (LEFT) + VERTICAL URL BAR (RIGHT) -->
                <div class="phone-preview-flex-container">
                    
                    <!-- DAISYUI PHONE MOCKUP FRAME (LEFT) -->
                    <div class="mockup-phone border-[#5A5BF1]">
                        <div class="mockup-phone-camera"></div>
                        <div class="mockup-phone-display">
                            <!-- REALISTIC SMARTPHONE TOP STATUS BAR -->
                            <div class="mockup-phone-status-bar">
                                <span>09:41</span>
                                <div class="mockup-phone-status-icons">
                                    <i class="fas fa-signal"></i>
                                    <i class="fas fa-wifi"></i>
                                    <i class="fas fa-battery-full"></i>
                                </div>
                            </div>

                            <!-- MAIN SCROLLABLE PHONE CONTENT -->
                            <div class="phone-content" id="phonePreviewContent" style="background-image: url('{{ $appearance && $appearance->background_color ? asset('images/background/' . $appearance->background_color) : '' }}');">
                                
                                <!-- EMPTY STATE PLACEHOLDER (SHOWN WHEN NO ELEMENTS ARE ADDED IN LEFT PANEL) -->
                                <div id="phoneEmptyState" class="phone-empty-state">
                                    <div class="empty-state-icon-wrapper">
                                        <i class="fas fa-layer-group"></i>
                                    </div>
                                    <div class="empty-state-title">Belum Ada Elemen</div>
                                    <p class="empty-state-desc">Klik "Tambah Element" di sebelah kiri untuk menambahkan profil atau komponen ke microsite ini.</p>
                                </div>

                                <!-- LIVE PROFILE SECTION (HIDDEN UNTIL PROFILE ELEMENT IS ADDED IN LEFT PANEL) -->
                                <div id="liveProfileSection" class="live-profile-section" style="display: none;">
                                    <div id="livePhoneBannerContainer" class="live-phone-banner-container" style="display: {{ ($appearance && $appearance->banner) ? 'block' : 'none' }};">
                                        <img src="{{ ($appearance && $appearance->banner) ? asset('storage/' . $appearance->banner) : '' }}" id="livePhoneBannerImg" class="live-phone-banner-img">
                                    </div>

                                    @php
                                        $shapeRadius = '50%';
                                        if (isset($appearance->profile_shape)) {
                                            if ($appearance->profile_shape === 'rounded') $shapeRadius = '14px';
                                            if ($appearance->profile_shape === 'square') $shapeRadius = '0px';
                                        }
                                    @endphp
                                    <div id="livePhoneAvatarContainer" class="live-phone-avatar-container" style="border-radius: {{ $shapeRadius }};">
                                        @if($appearance && $appearance->profile_image)
                                            <img src="{{ asset('storage/' . $appearance->profile_image) }}" id="livePhoneAvatarImg" class="live-phone-banner-img">
                                        @else
                                            <i class="fas fa-user" id="livePhoneAvatarPlaceholder" class="live-phone-avatar-placeholder"></i>
                                        @endif
                                    </div>

                                    <div id="livePhoneName" class="live-phone-name" style="color: {{ $appearance ? $appearance->theme_color : '#FF9040' }};">
                                        {!! $appearance ? $appearance->name : Auth::user()->name !!}
                                    </div>

                                    <div id="livePhoneBio" class="live-phone-bio" style="color: {{ $appearance ? $appearance->theme_color : '#666' }};">
                                        {!! $appearance ? $appearance->bio : '' !!}
                                    </div>
                                    
                                    <div class="preview-social-links" id="livePreviewSocialLinks">
                                        @if($appearance && $appearance->instagram)
                                            <a href="{{ $appearance->instagram }}" target="_blank" class="social-link-item"><i class="fab fa-instagram"></i></a>
                                        @endif
                                        @if($appearance && $appearance->tiktok)
                                            <a href="{{ $appearance->tiktok }}" target="_blank" class="social-link-item"><i class="fab fa-tiktok"></i></a>
                                        @endif
                                        @if($appearance && $appearance->whatsapp)
                                            <a href="{{ $appearance->whatsapp }}" target="_blank" class="social-link-item"><i class="fab fa-whatsapp"></i></a>
                                        @endif
                                        @if($appearance && $appearance->linkedin)
                                            <a href="{{ $appearance->linkedin }}" target="_blank" class="social-link-item"><i class="fab fa-linkedin"></i></a>
                                        @endif
                                        @if($appearance && $appearance->facebook)
                                            <a href="{{ $appearance->facebook }}" target="_blank" class="social-link-item"><i class="fab fa-facebook"></i></a>
                                        @endif
                                        @if($appearance && $appearance->website)
                                            <a href="{{ $appearance->website }}" target="_blank" class="social-link-item"><i class="fas fa-globe"></i></a>
                                        @endif
                                        @if($appearance && $appearance->twitter)
                                            <a href="{{ $appearance->twitter }}" target="_blank" class="social-link-item"><i class="fab fa-twitter"></i></a>
                                        @endif
                                        @if($appearance && $appearance->youtube)
                                            <a href="{{ $appearance->youtube }}" target="_blank" class="social-link-item"><i class="fab fa-youtube"></i></a>
                                        @endif
                                        @if($appearance && $appearance->telegram)
                                            <a href="{{ $appearance->telegram }}" target="_blank" class="social-link-item"><i class="fab fa-telegram"></i></a>
                                        @endif
                                        @if($appearance && $appearance->email)
                                            <a href="mailto:{{ $appearance->email }}" class="social-link-item"><i class="fas fa-envelope"></i></a>
                                        @endif
                                        @if($appearance && $appearance->discord)
                                            <a href="{{ $appearance->discord }}" target="_blank" class="social-link-item"><i class="fab fa-discord"></i></a>
                                        @endif
                                    </div>
                                    

                                </div>

                                @if(isset($imageElements))
                                    @foreach($imageElements as $imageEl)
                                        @php $elementId = 'imageBlock_' . $imageEl->id; @endphp
                                        <div id="live_{{ $elementId }}" class="live-image-element">
                                            <a href="{{ $imageEl->link_url ?? '#' }}" id="liveLink_{{ $elementId }}" target="_blank" class="live-image-link">
                                                <img id="liveImg_{{ $elementId }}" src="{{ $imageEl->image_path ? asset('storage/' . $imageEl->image_path) : '' }}" class="live-image-img">
                                            </a>
                                        </div>
                                    @endforeach
                                @endif

                            </div>

                            <!-- REALISTIC SMARTPHONE BOTTOM HOME GESTURE INDICATOR -->
                            <div class="mockup-home-gesture-wrapper">
                                <div class="mockup-home-gesture-bar"></div>
                            </div>
                        </div>
                    </div>

                    <!-- SLEEK REDESIGNED VERTICAL BROWSER URL PILL (RIGHT SIDE OF PHONE) -->
                    <div class="preview-url-browser-bar">
                        <!-- TOP: HTTPS GREEN LOCK BADGE -->
                        <div style="
                            width: 32px;
                            height: 32px;
                            border-radius: 50%;
                            background: #ECFDF5;
                            color: #10B981;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            font-size: 12px;
                            box-shadow: 0 2px 5px rgba(16, 185, 129, 0.15);
                        " title="Akses Aman HTTPS (SSL Active)">
                            <i class="fas fa-lock"></i>
                        </div>

                        <!-- CENTER: ELEGANT VERTICAL DOMAIN PATH -->
                        <div style="
                            writing-mode: vertical-rl;
                            transform: rotate(180deg);
                            font-size: 12px;
                            font-weight: 700;
                            color: #374151;
                            letter-spacing: 0.6px;
                            white-space: nowrap;
                            user-select: none;
                            display: flex;
                            align-items: center;
                            gap: 4px;
                        ">
                            <span style="color: #9CA3AF; font-weight: 500;">linkan.id/</span><span style="color: #5A5BF1;">{{ Auth::user()->username }}</span>
                        </div>

                        <!-- BOTTOM: VERTICAL ACTION BUTTON STACK -->
                        <div style="display: flex; flex-direction: column; align-items: center; gap: 8px;">
                            <!-- Copy Button -->
                            <button type="button" onclick="copyToClipboard('{{ url('/linkan.id/' . Auth::user()->username) }}')" style="
                                background: #FFF7ED;
                                border: 1px solid #FFEDD5;
                                color: #5A5BF1;
                                width: 32px;
                                height: 32px;
                                border-radius: 10px;
                                font-size: 12px;
                                cursor: pointer;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                transition: all 0.2s ease;
                            " onmouseover="this.style.background='#5A5BF1'; this.style.color='#ffffff'; this.style.borderColor='#5A5BF1';" onmouseout="this.style.background='#FFF7ED'; this.style.color='#5A5BF1'; this.style.borderColor='#FFEDD5';" title="Salin Tautan Microsite">
                                <i class="fas fa-copy"></i>
                            </button>
                            
                            <!-- Open External Link Button -->
                            <a href="{{ url('/linkan.id/' . Auth::user()->username) }}" target="_blank" style="
                                background: #F3F4F6;
                                border: 1px solid #E5E7EB;
                                color: #4B5563;
                                width: 32px;
                                height: 32px;
                                border-radius: 10px;
                                font-size: 12px;
                                cursor: pointer;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                text-decoration: none;
                                transition: all 0.2s ease;
                            " onmouseover="this.style.background='#374151'; this.style.color='#ffffff'; this.style.borderColor='#374151';" onmouseout="this.style.background='#F3F4F6'; this.style.color='#4B5563'; this.style.borderColor='#E5E7EB';" title="Buka Microsite di Tab Baru">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
            </div>
        </div>
    @endif

</div>



<!-- NEW MICROSITE CREATION MULTI-STEP MODAL -->
<x-microsite.create-modal />



    <!-- TEMPLATES FOR DYNAMIC ELEMENTS -->
    <template id="image-block-template">
        <div id="__ELEMENT_ID__" class="draggable-element-block" data-element-type="image" class="draggable-element-block-inner">
            <div class="block-item-card" onclick="toggleImageEditForm('__ELEMENT_ID__')">
                <i class="fas fa-grip-vertical drag-handle" class="drag-handle-icon" onclick="event.stopPropagation()" title="Tarik ke atas/bawah untuk ubah urutan"></i>
                <div class="block-item-icon-wrapper">
                    <i class="fas fa-image"></i>
                </div>
                <div class="block-item-content">
                    <div class="block-item-title-wrapper">
                        <span>Gambar</span>
                    </div>
                </div>
                <div class="block-item-actions" onclick="event.stopPropagation()">
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
        <div id="live___ELEMENT_ID__" class="microsite-live-element" style="display: none;">
            <a id="liveLink___ELEMENT_ID__" href="#" target="_blank" class="live-element-link">
                <img id="liveImg___ELEMENT_ID__" src="" class="live-element-img">
            </a>
        </div>
    </template>

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
    data-route-order-update="{{ route('admin.elements.order.update') }}"
    data-route-appearance-update="{{ route('admin.appearance.update') }}"
    data-appearance-blocks-order="{{ $appearance->blocks_order ?? '' }}">
</div>

@endpush
