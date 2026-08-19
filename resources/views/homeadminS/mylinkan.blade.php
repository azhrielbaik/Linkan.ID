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
                <h2 class="gallery-title" style="margin: 0;"><i class="fas fa-layer-group" style="color: #FF9040;"></i> {{ __('admin.my_microsite_list') }}</h2>
                
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
                <i class="fas fa-sliders-h" style="color: #FF9040; margin-right: 10px;"></i> {{ __('admin.edit_content_blocks') }}
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
                            <i class="fas fa-layer-group" style="color: #FF9040;"></i> Edit Element
                        </h3>
                        <span style="font-size: 11px; color: #6b7280; font-weight: 500;">
                            <i class="fas fa-arrows-alt-v" style="margin-right: 4px;"></i> Drag & Drop untuk mengurutkan
                        </span>
                    </div>

                    <!-- DRAGGABLE ELEMENT BLOCKS LIST CONTAINER -->
                    <div id="elementBlocksList" style="display: flex; flex-direction: column; gap: 10px;">
                        
                        <!-- 1. PROFILE BLOCK CARD (DRAGGABLE, VISIBLE BY DEFAULT IN EDIT MODE) -->
                        <div id="profileBlockCard" class="draggable-element-block" data-element-type="profile" style="display: block; transition: all 0.25s ease;">
                            
                            <!-- COLLAPSED BLOCK HEADER CARD -->
                            <div class="block-item-card" onclick="toggleProfileEditForm()">
                                <i class="fas fa-grip-vertical drag-handle" style="color: #9ca3af; cursor: grab;" onclick="event.stopPropagation()" title="Tarik ke atas/bawah untuk ubah urutan"></i>
                                <div style="width: 36px; height: 36px; border-radius: 8px; color: #FF9040; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0;">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                                <div style="flex: 1; min-width: 0;">
                                    <div style="font-weight: 700; font-size: 14px; color: #111827; display: flex; align-items: center; gap: 8px;">
                                        <span>Profil</span>
                                        <span style="font-size: 10px; font-weight: 700; background: #FF9040; color: #ffffff; padding: 2px 8px; border-radius: 4px;">Elemen</span>
                                    </div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 8px;" onclick="event.stopPropagation()">
                                    <button type="button" onclick="toggleProfileEditForm()" style="background: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 8px; padding: 6px 12px; font-size: 12px; font-weight: 700; color: #374151; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.2s ease;" onmouseover="this.style.background='#FF9040'; this.style.color='#ffffff'; this.style.borderColor='#FF9040';" onmouseout="this.style.background='#f3f4f6'; this.style.color='#374151'; this.style.borderColor='#e5e7eb';">
                                        <i class="fas fa-pen" style="font-size: 10px;"></i> <span id="profileEditBtnText">Edit</span>
                                    </button>
                                </div>
                            </div>

                        <!-- EXPANDABLE EDIT FORM BODY (SLIDES DOWN WHEN EDIT CLICKED) -->
                        <div id="profileEditFormBody" class="profile-edit-form-body">
                            <div style="padding: 20px;">
                                <form action="{{ route('admin.appearance.update') }}" method="POST" enctype="multipart/form-data" id="profileBlockForm">
                                    @csrf
                                    <input type="hidden" name="theme_color" value="{{ $appearance->theme_color ?? '#FF9040' }}">
                                    <input type="hidden" name="background_color" value="{{ $appearance->background_color ?? '#FFFFFF' }}">
                                    
                                    <div style="display: flex; flex-direction: column; gap: 16px;">
                                        <div style="font-size: 14px; font-weight: 700; color: #111827; padding-bottom: 8px; border-bottom: 1px solid #f3f4f6;">
                                            Pengaturan Data Profil
                                        </div>

                                        <!-- 1. GAMBAR SAMPUL (COVER BANNER) -->
                                        <div>
                                            <label style="display: block; font-size: 12px; font-weight: 700; color: #374151; margin-bottom: 6px;">Gambar Sampul (Banner)</label>
                                            <div class="upload-dropzone" style="padding: 20px; text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                                <input type="file" name="banner" id="inputBannerFile" accept="image/jpeg, image/png, image/gif" style="position: absolute; inset: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; z-index: 2;" onchange="previewProfileBanner(this)">
                                                
                                                <div id="bannerPreviewPlaceholder" style="display: {{ ($appearance && $appearance->banner) ? 'none' : 'flex' }}; flex-direction: column; align-items: center; justify-content: center; gap: 8px;">
                                                    <i class="fas fa-cloud-upload-alt" style="font-size: 32px; color: #FF9040; transition: all 0.3s ease;"></i>
                                                    <div style="font-size: 13px; font-weight: 600; color: #374151;">
                                                        Drop your images here or <span style="color: #FF9040;">browse</span>
                                                    </div>
                                                    <div style="font-size: 10px; color: #9ca3af;">supports JPG, JPEG, PNG & GIF</div>
                                                </div>

                                                <div id="bannerPreviewContainer" style="display: {{ ($appearance && $appearance->banner) ? 'block' : 'none' }}; width: 100%; aspect-ratio: 3 / 1; border-radius: 8px; overflow: hidden; background: #f3f4f6; position: relative; z-index: 1;">
                                                    @if($appearance && $appearance->banner)
                                                        <img src="{{ asset('storage/' . $appearance->banner) }}" id="bannerPreviewImg" style="width: 100%; height: 100%; object-fit: cover;">
                                                    @else
                                                        <img src="" id="bannerPreviewImg" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                                                    @endif
                                                </div>
                                                
                                                <div id="bannerSizeError" style="display: none; width: 100%; margin-top: 12px; background: #FEE2E2; color: #EF4444; font-size: 11px; font-weight: 700; padding: 8px; border-radius: 6px; z-index: 5; text-align: center; border: 1px solid #FCA5A5;">
                                                    <i class="fas fa-exclamation-circle" style="margin-right: 4px;"></i> Gagal: Ukuran maksimal gambar sampul adalah 2MB!
                                                </div>
                                            </div>
                                        </div>

                                        <!-- 2. FOTO PROFILE -->
                                        <div>
                                            <label style="display: block; font-size: 12px; font-weight: 700; color: #374151; margin-bottom: 6px;">Foto Profil</label>
                                            <div class="upload-dropzone" style="display: flex; flex-direction: column; padding: 12px 16px;">
                                                <input type="file" name="profile_image" id="inputAvatarFile" accept="image/jpeg, image/png, image/gif" style="position: absolute; inset: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; z-index: 2;" onchange="previewProfileAvatar(this)">
                                                
                                                <div style="display: flex; align-items: center; gap: 16px; width: 100%;">
                                                    <div id="avatarPreviewContainer" style="width: 56px; height: 56px; border-radius: 50%; background: #ffffff; overflow: hidden; border: 2px solid #e5e7eb; flex-shrink: 0; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 6px rgba(0,0,0,0.05); z-index: 1;">
                                                        @if($appearance && $appearance->profile_image)
                                                            <img src="{{ asset('storage/' . $appearance->profile_image) }}" id="avatarPreviewImg" style="width: 100%; height: 100%; object-fit: cover;">
                                                        @else
                                                            <i class="fas fa-user" id="avatarPreviewPlaceholder" style="color: #9ca3af; font-size: 20px;"></i>
                                                            <img src="" id="avatarPreviewImg" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                                                        @endif
                                                    </div>
                                                    
                                                    <div style="flex: 1; z-index: 1; text-align: left;">
                                                        <div style="font-size: 13px; font-weight: 600; color: #374151;">Upload Foto Profil</div>
                                                        <div style="font-size: 11px; color: #6b7280; margin-top: 2px;">Seret gambar ke sini atau <span style="color: #FF9040; font-weight: 600;">browse</span></div>
                                                    </div>
                                                    
                                                    <div style="width: 32px; height: 32px; border-radius: 50%; background: rgba(255,144,64,0.1); display: flex; align-items: center; justify-content: center; z-index: 1;">
                                                        <i class="fas fa-cloud-upload-alt" style="color: #FF9040; font-size: 14px; transition: all 0.3s ease;"></i>
                                                    </div>
                                                </div>

                                                <div id="avatarSizeError" style="display: none; width: 100%; margin-top: 10px; color: #EF4444; font-size: 11px; font-weight: 700; padding: 6px 8px; z-index: 5; text-align: center;">
                                                    <i class="fas fa-exclamation-circle" style="margin-right: 4px;"></i> Gagal: Ukuran maksimal foto profil adalah 2MB!
                                                </div>
                                            </div>
                                        </div>

                                        <!-- 3. BENTUK PROFILE (PROFILE SHAPE) -->
                                        <div>
                                            <label style="display: block; font-size: 12px; font-weight: 700; color: #374151; margin-bottom: 8px;">Bentuk Foto Profil</label>
                                            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px;">
                                                <label style="cursor: pointer; border: 1px solid #e5e7eb; border-radius: 10px; padding: 10px; display: flex; flex-direction: column; align-items: center; gap: 6px; background: #ffffff; text-align: center;">
                                                    <input type="radio" name="profile_shape" value="circle" {{ old('profile_shape', $appearance->profile_shape ?? 'circle') == 'circle' ? 'checked' : '' }} onchange="updateProfileShape('circle')">
                                                    <div style="width: 24px; height: 24px; border-radius: 50%; background: rgba(255, 144, 64, 0.2); border: 2px solid #FF9040;"></div>
                                                    <span style="font-size: 11px; font-weight: 600; color: #374151;">Lingkaran</span>
                                                </label>
                                                <label style="cursor: pointer; border: 1px solid #e5e7eb; border-radius: 10px; padding: 10px; display: flex; flex-direction: column; align-items: center; gap: 6px; background: #ffffff; text-align: center;">
                                                    <input type="radio" name="profile_shape" value="rounded" {{ old('profile_shape', $appearance->profile_shape ?? 'circle') == 'rounded' ? 'checked' : '' }} onchange="updateProfileShape('rounded')">
                                                    <div style="width: 24px; height: 24px; border-radius: 6px; background: rgba(255, 144, 64, 0.2); border: 2px solid #FF9040;"></div>
                                                    <span style="font-size: 11px; font-weight: 600; color: #374151;">Rounded</span>
                                                </label>
                                                <label style="cursor: pointer; border: 1px solid #e5e7eb; border-radius: 10px; padding: 10px; display: flex; flex-direction: column; align-items: center; gap: 6px; background: #ffffff; text-align: center;">
                                                    <input type="radio" name="profile_shape" value="square" {{ old('profile_shape', $appearance->profile_shape ?? 'circle') == 'square' ? 'checked' : '' }} onchange="updateProfileShape('square')">
                                                    <div style="width: 24px; height: 24px; border-radius: 0px; background: rgba(255, 144, 64, 0.2); border: 2px solid #FF9040;"></div>
                                                    <span style="font-size: 11px; font-weight: 600; color: #374151;">Persegi</span>
                                                </label>
                                            </div>
                                        </div>

                                        <!-- 4. NAMA PROFILE (TEKS EDITOR SEDERHANA) -->
                                        <div>
                                            <label style="display: block; font-size: 12px; font-weight: 700; color: #374151; margin-bottom: 6px;">Nama Profil</label>
                                            <div style="border: 1px solid #d1d5db; border-radius: 10px; background: #ffffff; overflow: hidden;">
                                                <div style="display: flex; align-items: center; gap: 4px; padding: 6px 10px; background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                                                    <button type="button" onmousedown="event.preventDefault();" onclick="formatText('bold')" style="background: none; border: none; font-size: 12px; font-weight: 700; color: #4b5563; cursor: pointer; padding: 2px 6px;" title="Tebal (Bold)"><i class="fas fa-bold"></i></button>
                                                    <button type="button" onmousedown="event.preventDefault();" onclick="formatText('italic')" style="background: none; border: none; font-size: 12px; font-style: italic; color: #4b5563; cursor: pointer; padding: 2px 6px;" title="Miring (Italic)"><i class="fas fa-italic"></i></button>
                                                    
                                                    <div style="height: 16px; width: 1px; background: #d1d5db; margin: 0 4px;"></div>
                                                    
                                                    <select onchange="formatText('fontName', this.value, 'editorProfileName')" style="border: 1px solid #e5e7eb; border-radius: 4px; padding: 2px 4px; font-size: 11px; color: #4b5563; outline: none; background: #fff; cursor: pointer;" title="Pilih Font">
                                                        <option value="Plus Jakarta Sans">Jakarta Sans</option>
                                                        <option value="Arial">Arial</option>
                                                        <option value="Times New Roman">Times New Roman</option>
                                                        <option value="Courier New">Courier New</option>
                                                        <option value="Georgia">Georgia</option>
                                                        <option value="Verdana">Verdana</option>
                                                    </select>
                                                    
                                                    <label style="cursor: pointer; display: flex; align-items: center; justify-content: center; width: 20px; height: 20px; color: #4b5563;" title="Pilih Warna">
                                                        <i class="fas fa-eye-dropper"></i>
                                                        <input type="color" id="colorPickerProfileName" oninput="formatText('foreColor', this.value, 'editorProfileName')" style="position: absolute; opacity: 0; width: 1px; height: 1px; padding: 0; border: none;">
                                                    </label>
                                                    
                                                    
                                                </div>
                                                <div id="editorProfileName" contenteditable="true" style="width: 100%; border: none; padding: 10px 12px; font-size: 13px; font-weight: 600; outline: none; min-height: 20px; word-wrap: break-word;" placeholder="Masukkan nama profil Anda..." onkeyup="syncProfileName(); updateLiveProfileName(this.innerHTML)">{!! old('name', $appearance->name ?? Auth::user()->name) !!}</div>
                                                <input type="hidden" name="name" id="inputProfileName" value="{{ old('name', $appearance->name ?? Auth::user()->name) }}">
                                            </div>
                                        </div>

                                        <!-- 5. DESKRIPSI PROFILE (TEKS EDITOR SEDERHANA) -->
                                        <div>
                                            <label style="display: block; font-size: 12px; font-weight: 700; color: #374151; margin-bottom: 6px;">Deskripsi / Bio Profil</label>
                                            <div style="border: 1px solid #d1d5db; border-radius: 10px; background: #ffffff; overflow: hidden;">
                                                <div style="display: flex; align-items: center; gap: 4px; padding: 6px 10px; background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                                                    <button type="button" onmousedown="event.preventDefault();" onclick="formatText('bold')" style="background: none; border: none; font-size: 12px; font-weight: 700; color: #4b5563; cursor: pointer; padding: 2px 6px;" title="Tebal (Bold)"><i class="fas fa-bold"></i></button>
                                                    <button type="button" onmousedown="event.preventDefault();" onclick="formatText('italic')" style="background: none; border: none; font-size: 12px; font-style: italic; color: #4b5563; cursor: pointer; padding: 2px 6px;" title="Miring (Italic)"><i class="fas fa-italic"></i></button>
                                                    <button type="button" onmousedown="event.preventDefault();" onclick="formatText('underline')" style="background: none; border: none; font-size: 12px; text-decoration: underline; color: #4b5563; cursor: pointer; padding: 2px 6px;" title="Garis Bawah (Underline)"><i class="fas fa-underline"></i></button>
                                                    
                                                    <div style="height: 16px; width: 1px; background: #d1d5db; margin: 0 4px;"></div>
                                                    
                                                    <select onchange="formatText('fontName', this.value, 'editorProfileBio')" style="border: 1px solid #e5e7eb; border-radius: 4px; padding: 2px 4px; font-size: 11px; color: #4b5563; outline: none; background: #fff; cursor: pointer;" title="Pilih Font">
                                                        <option value="Plus Jakarta Sans">Jakarta Sans</option>
                                                        <option value="Arial">Arial</option>
                                                        <option value="Times New Roman">Times New Roman</option>
                                                        <option value="Courier New">Courier New</option>
                                                        <option value="Georgia">Georgia</option>
                                                        <option value="Verdana">Verdana</option>
                                                    </select>
                                                    <label style="cursor: pointer; display: flex; align-items: center; justify-content: center; width: 20px; height: 20px; color: #4b5563;" title="Pilih Warna">
                                                        <i class="fas fa-eye-dropper"></i>
                                                        <input type="color" id="colorPickerProfileBio" oninput="formatText('foreColor', this.value, 'editorProfileBio')" style="position: absolute; opacity: 0; width: 1px; height: 1px; padding: 0; border: none;">
                                                    </label>
                                                </div>
                                                <div id="editorProfileBio" contenteditable="true" style="width: 100%; border: none; padding: 10px 12px; font-size: 12px; outline: none; min-height: 55px; word-wrap: break-word;" placeholder="Tulis deskripsi singkat profil Anda..." onkeyup="syncProfileBio(); updateLiveProfileBio(this.innerHTML)">{!! old('bio', $appearance->bio ?? '') !!}</div>
                                                <input type="hidden" name="bio" id="inputProfileBio" value="{{ old('bio', $appearance->bio ?? '') }}">
                                            </div>
                                        </div>

                                        <!-- ACTION BUTTONS -->
                                        <div style="display: flex; justify-content: flex-end; gap: 10px; padding-top: 10px; border-top: 1px solid #f3f4f6;">
                                            <button type="button" onclick="toggleProfileEditForm()" style="background: #f3f4f6; border: 1px solid #d1d5db; color: #4b5563; padding: 8px 16px; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer;">Batal</button>
                                            <button type="submit" style="background: #FF9040; border: none; color: #ffffff; padding: 8px 18px; border-radius: 8px; font-size: 12px; font-weight: 700; cursor: pointer;">Simpan Perubahan</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            </div>
                        </div>

                        @if(isset($imageElements))
                            @foreach($imageElements as $imageEl)
                                @php $elementId = 'imageBlock_' . $imageEl->id; @endphp
                                <div id="{{ $elementId }}" class="draggable-element-block existing-image-element" data-element-type="image" data-db-id="{{ $imageEl->id }}" style="display: block; transition: all 0.25s ease;">
                                    <div class="block-item-card" onclick="toggleImageEditForm('{{ $elementId }}')">
                                        <i class="fas fa-grip-vertical drag-handle" style="color: #9ca3af; cursor: grab;" onclick="event.stopPropagation()" title="Tarik ke atas/bawah untuk ubah urutan"></i>
                                        <div style="width: 36px; height: 36px; border-radius: 8px; color: #6366F1; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0;">
                                            <i class="fas fa-image"></i>
                                        </div>
                                        <div style="flex: 1; min-width: 0;">
                                            <div style="font-weight: 700; font-size: 14px; color: #111827; display: flex; align-items: center; gap: 8px;">
                                                <span>Gambar</span>
                                            </div>
                                        </div>
                                        <div style="display: flex; align-items: center; gap: 8px;" onclick="event.stopPropagation()">
                                            <button type="button" onclick="toggleImageEditForm('{{ $elementId }}')" style="background: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 8px; padding: 6px 12px; font-size: 12px; font-weight: 700; color: #374151; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.2s ease;" onmouseover="this.style.background='#6366F1'; this.style.color='#ffffff'; this.style.borderColor='#6366F1';" onmouseout="this.style.background='#f3f4f6'; this.style.color='#374151'; this.style.borderColor='#e5e7eb';">
                                                <i class="fas fa-pen" style="font-size: 10px;"></i> <span id="btnText_{{ $elementId }}">Edit</span>
                                            </button>
                                        </div>
                                    </div>

                                    <div id="formBody_{{ $elementId }}" class="edit-form-body" style="max-height: 0; opacity: 0; overflow: hidden; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); border: 1px solid #e5e7eb; border-radius: 12px; background: #f9fafb; margin-top: 0;">
                                        <div style="padding: 20px;">
                                            <div style="font-size: 14px; font-weight: 700; color: #111827; padding-bottom: 8px; border-bottom: 1px solid #f3f4f6; margin-bottom: 16px;">
                                                Pengaturan Elemen Gambar
                                            </div>
                                            
                                            <div>
                                                <label style="display: block; font-size: 12px; font-weight: 700; color: #374151; margin-bottom: 6px;">Unggah Gambar</label>
                                                <div class="upload-dropzone dynamic-dropzone" style="padding: 20px; text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center; position: relative;">
                                                    <input type="file" accept="image/jpeg, image/png, image/gif" style="position: absolute; inset: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; z-index: 2;" onchange="previewDynamicImage(this, '{{ $elementId }}')">
                                                    
                                                    <div id="placeholder_{{ $elementId }}" style="display: {{ $imageEl->image_path ? 'none' : 'flex' }}; flex-direction: column; align-items: center; justify-content: center; gap: 8px;">
                                                        <i class="fas fa-cloud-upload-alt" style="font-size: 32px; color: #6366F1;"></i>
                                                        <div style="font-size: 13px; font-weight: 600; color: #374151;">Seret gambar ke sini atau <span style="color: #6366F1;">browse</span></div>
                                                        <div style="font-size: 10px; color: #9ca3af;">supports JPG, JPEG, PNG & GIF</div>
                                                    </div>

                                                    <div id="previewCont_{{ $elementId }}" style="display: {{ $imageEl->image_path ? 'block' : 'none' }}; width: 100%; border-radius: 8px; overflow: hidden; background: #f3f4f6; position: relative; z-index: 1;">
                                                        <img src="{{ $imageEl->image_path ? asset('storage/' . $imageEl->image_path) : '' }}" id="previewImg_{{ $elementId }}" style="width: 100%; object-fit: contain;">
                                                    </div>
                                                    
                                                    <div id="error_{{ $elementId }}" style="display: none; width: 100%; margin-top: 12px; color: #EF4444; font-size: 11px; font-weight: 700; padding: 8px; border-radius: 6px; z-index: 5; text-align: center; border: 1px solid #FCA5A5; background: #FEE2E2;">
                                                        <i class="fas fa-exclamation-circle" style="margin-right: 4px;"></i> Gagal: Ukuran maksimal gambar adalah 2MB!
                                                    </div>
                                                </div>
                                            </div>

                                            <div style="margin-top: 16px;">
                                                <label style="display: block; font-size: 12px; font-weight: 700; color: #374151; margin-bottom: 6px;">URL Tautan (Opsional)</label>
                                                <input type="url" id="link_{{ $elementId }}" placeholder="https://..." value="{{ $imageEl->link_url }}" style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 13px; outline: none; transition: border-color 0.2s;" oninput="updateDynamicImageLink('{{ $elementId }}', this.value)">
                                            </div>

                                            <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 16px; border-top: 1px solid #e5e7eb; margin-top: 20px;">
                                                <button type="button" onclick="removeDynamicElement('{{ $elementId }}')" style="background: none; border: none; color: #EF4444; font-size: 12px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 6px; padding: 8px;">
                                                    <i class="fas fa-trash-alt"></i> Hapus Elemen
                                                </button>
                                                <button type="button" onclick="saveDynamicElement('{{ $elementId }}')" style="background: #FF9040; border: none; color: #ffffff; padding: 8px 18px; border-radius: 8px; font-size: 12px; font-weight: 700; cursor: pointer; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
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
            <div class="editor-sticky-preview" style="background: #ffffff; border: none; padding: 0; width: 100%; max-width: 360px;">
                <!-- SECTION TITLE -->
                <div style="width: 100%; margin-bottom: 12px;">
                    <h3 style="font-size: 15px; font-weight: 700; color: #111827; margin: 0; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-mobile-alt" style="color: #FF9040;"></i> {{ __('admin.live_phone_preview') }}
                    </h3>
                </div>

                <!-- SIDE-BY-SIDE FLEX CONTAINER: PHONE MOCKUP (LEFT) + VERTICAL URL BAR (RIGHT) -->
                <div class="phone-preview-flex-container" style="display: flex; align-items: center; justify-content: center; gap: 12px; width: 100%;">
                    
                    <!-- DAISYUI PHONE MOCKUP FRAME (LEFT) -->
                    <div class="mockup-phone border-[#ff8938]">
                        <div class="mockup-phone-camera"></div>
                        <div class="mockup-phone-display">
                            <!-- REALISTIC SMARTPHONE TOP STATUS BAR -->
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 6px 16px 2px; font-size: 10px; font-weight: 700; color: #1f2937; background: transparent; z-index: 10; position: relative; flex-shrink: 0;">
                                <span>09:41</span>
                                <div style="display: flex; gap: 4px; align-items: center; font-size: 9px; color: #374151;">
                                    <i class="fas fa-signal"></i>
                                    <i class="fas fa-wifi"></i>
                                    <i class="fas fa-battery-full"></i>
                                </div>
                            </div>

                            <!-- MAIN SCROLLABLE PHONE CONTENT -->
                            <div class="phone-content" id="phonePreviewContent" style="flex: 1; min-height: 0; padding: 12px 14px 16px 14px; overflow-y: auto; background-image: url('{{ $appearance && $appearance->background_color ? asset('images/background/' . $appearance->background_color) : '' }}'); background-size: cover; background-position: center;">
                                
                                <!-- EMPTY STATE PLACEHOLDER (SHOWN WHEN NO ELEMENTS ARE ADDED IN LEFT PANEL) -->
                                <div id="phoneEmptyState" class="phone-empty-state">
                                    <div style="width: 44px; height: 44px; border-radius: 50%; background: rgba(255, 144, 64, 0.1); color: #FF9040; display: flex; align-items: center; justify-content: center; font-size: 18px; margin-bottom: 10px;">
                                        <i class="fas fa-layer-group"></i>
                                    </div>
                                    <div style="font-size: 13px; font-weight: 700; color: #4b5563; margin-bottom: 4px;">Belum Ada Elemen</div>
                                    <p style="font-size: 11px; color: #9ca3af; margin: 0; line-height: 1.4;">Klik "Tambah Element" di sebelah kiri untuk menambahkan profil atau komponen ke microsite ini.</p>
                                </div>

                                <!-- LIVE PROFILE SECTION (HIDDEN UNTIL PROFILE ELEMENT IS ADDED IN LEFT PANEL) -->
                                <div id="liveProfileSection" style="display: none; margin-bottom: 16px;">
                                    <div id="livePhoneBannerContainer" style="width: 100%; aspect-ratio: 3 / 1; border-radius: 10px; overflow: hidden; margin-bottom: 12px; display: {{ ($appearance && $appearance->banner) ? 'block' : 'none' }};">
                                        <img src="{{ ($appearance && $appearance->banner) ? asset('storage/' . $appearance->banner) : '' }}" id="livePhoneBannerImg" style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>

                                    @php
                                        $shapeRadius = '50%';
                                        if (isset($appearance->profile_shape)) {
                                            if ($appearance->profile_shape === 'rounded') $shapeRadius = '14px';
                                            if ($appearance->profile_shape === 'square') $shapeRadius = '0px';
                                        }
                                    @endphp
                                    <div id="livePhoneAvatarContainer" style="width: 68px; height: 68px; border-radius: {{ $shapeRadius }}; overflow: hidden; margin: 0 auto 10px; background: #e5e7eb; display: flex; align-items: center; justify-content: center; border: 3px solid #ffffff; box-shadow: 0 4px 10px rgba(0,0,0,0.08); transition: border-radius 0.25s ease;">
                                        @if($appearance && $appearance->profile_image)
                                            <img src="{{ asset('storage/' . $appearance->profile_image) }}" id="livePhoneAvatarImg" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <i class="fas fa-user" id="livePhoneAvatarPlaceholder" style="font-size: 24px; color: #888;"></i>
                                        @endif
                                    </div>

                                    <div id="livePhoneName" style="font-size: 15px; font-weight: 700; text-align: center; margin-bottom: 4px; color: {{ $appearance ? $appearance->theme_color : '#FF9040' }}">
                                        {!! $appearance ? $appearance->name : Auth::user()->name !!}
                                    </div>

                                    <div id="livePhoneBio" style="font-size: 12px; text-align: center; margin-bottom: 14px; color: {{ $appearance ? $appearance->theme_color : '#666' }}">
                                        {!! $appearance ? $appearance->bio : '' !!}
                                    </div>
                                    
                                    <div class="preview-social-links" id="livePreviewSocialLinks">
                                        @if($appearance && $appearance->instagram)
                                            <a href="{{ $appearance->instagram }}" target="_blank"><i class="fab fa-instagram"></i></a>
                                        @endif
                                        @if($appearance && $appearance->tiktok)
                                            <a href="{{ $appearance->tiktok }}" target="_blank"><i class="fab fa-tiktok"></i></a>
                                        @endif
                                        @if($appearance && $appearance->whatsapp)
                                            <a href="{{ $appearance->whatsapp }}" target="_blank"><i class="fab fa-whatsapp"></i></a>
                                        @endif
                                        @if($appearance && $appearance->linkedin)
                                            <a href="{{ $appearance->linkedin }}" target="_blank"><i class="fab fa-linkedin"></i></a>
                                        @endif
                                        @if($appearance && $appearance->facebook)
                                            <a href="{{ $appearance->facebook }}" target="_blank"><i class="fab fa-facebook"></i></a>
                                        @endif
                                        @if($appearance && $appearance->website)
                                            <a href="{{ $appearance->website }}" target="_blank"><i class="fas fa-globe"></i></a>
                                        @endif
                                        @if($appearance && $appearance->twitter)
                                            <a href="{{ $appearance->twitter }}" target="_blank"><i class="fab fa-twitter"></i></a>
                                        @endif
                                        @if($appearance && $appearance->youtube)
                                            <a href="{{ $appearance->youtube }}" target="_blank"><i class="fab fa-youtube"></i></a>
                                        @endif
                                        @if($appearance && $appearance->telegram)
                                            <a href="{{ $appearance->telegram }}" target="_blank"><i class="fab fa-telegram"></i></a>
                                        @endif
                                        @if($appearance && $appearance->email)
                                            <a href="mailto:{{ $appearance->email }}"><i class="fas fa-envelope"></i></a>
                                        @endif
                                        @if($appearance && $appearance->discord)
                                            <a href="{{ $appearance->discord }}" target="_blank"><i class="fab fa-discord"></i></a>
                                        @endif
                                    </div>
                                    

                                </div>

                                @if(isset($imageElements))
                                    @foreach($imageElements as $imageEl)
                                        @php $elementId = 'imageBlock_' . $imageEl->id; @endphp
                                        <div id="live_{{ $elementId }}" class="live-image-element" style="margin-bottom: 12px; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); transition: all 0.3s ease;">
                                            <a href="{{ $imageEl->link_url ?? '#' }}" id="liveLink_{{ $elementId }}" target="_blank" style="display: block; width: 100%; text-decoration: none;">
                                                <img id="liveImg_{{ $elementId }}" src="{{ $imageEl->image_path ? asset('storage/' . $imageEl->image_path) : '' }}" style="width: 100%; display: block; object-fit: cover;">
                                            </a>
                                        </div>
                                    @endforeach
                                @endif

                            </div>

                            <!-- REALISTIC SMARTPHONE BOTTOM HOME GESTURE INDICATOR -->
                            <div style="padding: 4px 0 6px; background: transparent; display: flex; justify-content: center; flex-shrink: 0;">
                                <div style="width: 100px; height: 4px; background: #9ca3af; border-radius: 4px;"></div>
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
                            <span style="color: #9CA3AF; font-weight: 500;">linkan.id/</span><span style="color: #FF9040;">{{ Auth::user()->username }}</span>
                        </div>

                        <!-- BOTTOM: VERTICAL ACTION BUTTON STACK -->
                        <div style="display: flex; flex-direction: column; align-items: center; gap: 8px;">
                            <!-- Copy Button -->
                            <button type="button" onclick="copyToClipboard('{{ url('/linkan.id/' . Auth::user()->username) }}')" style="
                                background: #FFF7ED;
                                border: 1px solid #FFEDD5;
                                color: #FF9040;
                                width: 32px;
                                height: 32px;
                                border-radius: 10px;
                                font-size: 12px;
                                cursor: pointer;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                transition: all 0.2s ease;
                            " onmouseover="this.style.background='#FF9040'; this.style.color='#ffffff'; this.style.borderColor='#FF9040';" onmouseout="this.style.background='#FFF7ED'; this.style.color='#FF9040'; this.style.borderColor='#FFEDD5';" title="Salin Tautan Microsite">
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
        <div id="__ELEMENT_ID__" class="draggable-element-block" data-element-type="image" style="display: block; transition: all 0.25s ease;">
            <div class="block-item-card" onclick="toggleImageEditForm('__ELEMENT_ID__')">
                <i class="fas fa-grip-vertical drag-handle" style="color: #9ca3af; cursor: grab;" onclick="event.stopPropagation()" title="Tarik ke atas/bawah untuk ubah urutan"></i>
                <div style="width: 36px; height: 36px; border-radius: 8px; color: #6366F1; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0;">
                    <i class="fas fa-image"></i>
                </div>
                <div style="flex: 1; min-width: 0;">
                    <div style="font-weight: 700; font-size: 14px; color: #111827; display: flex; align-items: center; gap: 8px;">
                        <span>Gambar</span>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 8px;" onclick="event.stopPropagation()">
                    <button type="button" onclick="toggleImageEditForm('__ELEMENT_ID__')" style="background: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 8px; padding: 6px 12px; font-size: 12px; font-weight: 700; color: #374151; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.2s ease;" onmouseover="this.style.background='#6366F1'; this.style.color='#ffffff'; this.style.borderColor='#6366F1';" onmouseout="this.style.background='#f3f4f6'; this.style.color='#374151'; this.style.borderColor='#e5e7eb';">
                        <i class="fas fa-pen" style="font-size: 10px;"></i> <span id="btnText___ELEMENT_ID__">Edit</span>
                    </button>
                </div>
            </div>

            <div id="formBody___ELEMENT_ID__" class="edit-form-body" style="max-height: 0; opacity: 0; overflow: hidden; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); border: 1px solid #e5e7eb; border-radius: 12px; background: #f9fafb; margin-top: 0;">
                <div style="padding: 20px;">
                    <div style="font-size: 14px; font-weight: 700; color: #111827; padding-bottom: 8px; border-bottom: 1px solid #f3f4f6; margin-bottom: 16px;">
                        Pengaturan Elemen Gambar
                    </div>
                    
                    <div>
                        <label class="form-label-custom">Unggah Gambar</label>
                        <div class="upload-dropzone dynamic-dropzone image-block-dropzone">
                            <input type="file" accept="image/jpeg, image/png, image/gif" class="hidden-file-input" onchange="previewDynamicImage(this, '__ELEMENT_ID__')">
                            
                            <div id="placeholder___ELEMENT_ID__" style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px;">
                                <i class="fas fa-cloud-upload-alt" class="upload-icon-indigo"></i>
                                <div class="upload-text-main">Seret gambar ke sini atau <span class="upload-text-highlight-indigo">browse</span></div>
                                <div class="upload-text-sub">supports JPG, JPEG, PNG & GIF</div>
                            </div>

                            <div id="previewCont___ELEMENT_ID__" style="display: none; width: 100%; border-radius: 8px; overflow: hidden; background: #f3f4f6; position: relative; z-index: 1;">
                                <img src="" id="previewImg___ELEMENT_ID__" class="preview-img-contain">
                            </div>
                            
                            <div id="error___ELEMENT_ID__" class="upload-size-error" style="display: none;">
                                <i class="fas fa-exclamation-circle" class="wizard-icon-back"></i> Gagal: Ukuran maksimal gambar adalah 2MB!
                            </div>
                        </div>
                    </div>

                    <div class="form-field-margin">
                        <label class="form-label-custom">URL Tautan (Opsional)</label>
                        <input type="url" id="link___ELEMENT_ID__" placeholder="https://..." style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 13px; outline: none; transition: border-color 0.2s;" oninput="updateDynamicImageLink('__ELEMENT_ID__', this.value)">
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 16px; border-top: 1px solid #e5e7eb; margin-top: 20px;">
                        <button type="button" onclick="removeDynamicElement('__ELEMENT_ID__')" style="background: none; border: none; color: #EF4444; font-size: 12px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 6px; padding: 8px;">
                            <i class="fas fa-trash-alt"></i> Hapus Elemen
                        </button>
                        <button type="button" onclick="saveDynamicElement('__ELEMENT_ID__')" style="background: #FF9040; border: none; color: #ffffff; padding: 8px 18px; border-radius: 8px; font-size: 12px; font-weight: 700; cursor: pointer; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                            Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <template id="image-live-template">
        <div id="live___ELEMENT_ID__" class="microsite-live-element" style="width: 100%; margin-bottom: 16px; border-radius: 12px; overflow: hidden; display: none; transition: all 0.3s ease; text-align: center;">
            <a id="liveLink___ELEMENT_ID__" href="#" target="_blank" style="display: block; width: 100%; cursor: default; pointer-events: none;">
                <img id="liveImg___ELEMENT_ID__" src="" style="width: 100%; height: auto; border-radius: 12px; object-fit: contain; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
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
