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
<div class="new-microsite-modal-overlay" id="newMicrositeModalOverlay">
    <div class="new-microsite-modal-wrapper">
        
        <!-- FLOATING DETACHED CLOSE BUTTON -->
        <button type="button" class="floating-close-btn" onclick="closeNewMicrositeModal()" title="Tutup Modal">&times;</button>

        <div class="new-microsite-modal-card">
            
            <!-- MODAL HEADER & STEP DOTS -->
            <div class="new-microsite-modal-header">
                <div>
                    <h3 class="new-microsite-modal-title" id="wizardTitle">
                        <i class="fas fa-brush" style="color: #FF9040;"></i> {{ __('admin.create_new_microsite') }}
                    </h3>
                    <p class="new-microsite-modal-subtitle" id="wizardSubtitle">
                        Langkah 1 dari 2: Pilih Tujuan Pembuatan Microsite
                    </p>
                </div>
                
                <div class="wizard-header-right">
                    <!-- STEP DOTS INDICATOR -->
                    <div class="wizard-step-dots">
                        <span class="step-dot active" id="dotStep1" onclick="goToStep(1)" title="Langkah 1: Tujuan Pembuatan"></span>
                        <span class="step-dot" id="dotStep2" onclick="goToStep(2)" title="Langkah 2: Detail Microsite"></span>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.microsite.store') }}" method="POST" id="newMicrositeForm">
                @csrf
                <input type="hidden" name="purpose" id="selectedPurpose" value="">

                <!-- STEP 1: PURPOSE SELECTION (SINGLE PAGE VIEW) -->
                <div class="wizard-step-content active" id="wizardStep1">
                    <div class="purpose-vertical-list">
                        
                        <!-- Card 1: Portofolio -->
                        <div class="image-style-option-card" onclick="selectPurposeCard('portofolio', this)">
                            <div class="option-thumb-box">
                                <div class="option-mobile-icon-badge" style="background: #EFF6FF;">
                                    <i class="fas fa-user-tie" style="color: #2563eb; font-size: 20px;"></i>
                                </div>
                                <div class="thumb-ui-mockup portfolio-mockup">
                                    <div class="mock-avatar-circle">
                                        <i class="fas fa-user-tie" style="color: #2563eb; font-size: 15px;"></i>
                                    </div>
                                    <div class="mock-lines">
                                        <div class="mock-line-title"></div>
                                        <div class="mock-line-sub"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="option-text-info">
                                <span class="option-main-title">{{ __('admin.purpose_portfolio') }}</span>
                                <span class="option-sub-desc">{{ __('admin.purpose_portfolio_desc') }}</span>
                            </div>
                            <i class="fas fa-chevron-right option-chevron-arrow"></i>
                        </div>

                        <!-- Card 2: Jualan Produk / Marketing -->
                        <div class="image-style-option-card" onclick="selectPurposeCard('marketing', this)">
                            <div class="option-thumb-box">
                                <div class="option-mobile-icon-badge" style="background: #FFF3E6;">
                                    <i class="fas fa-store" style="color: #ea580c; font-size: 20px;"></i>
                                </div>
                                <div class="thumb-ui-mockup store-mockup">
                                    <div class="mock-store-card">
                                        <div class="mock-store-icon">
                                            <i class="fas fa-shopping-bag" style="color: #ea580c; font-size: 16px;"></i>
                                        </div>
                                        <div class="mock-store-badge">STORE</div>
                                    </div>
                                </div>
                            </div>
                            <div class="option-text-info">
                                <span class="option-main-title">{{ __('admin.purpose_marketing') }}</span>
                                <span class="option-sub-desc">{{ __('admin.purpose_marketing_desc') }}</span>
                            </div>
                            <i class="fas fa-chevron-right option-chevron-arrow"></i>
                        </div>

                        <!-- Card 3: Affiliate -->
                        <div class="image-style-option-card" onclick="selectPurposeCard('affiliate', this)">
                            <div class="option-thumb-box">
                                <div class="option-mobile-icon-badge" style="background: #ECFDF5;">
                                    <i class="fas fa-link" style="color: #059669; font-size: 20px;"></i>
                                </div>
                                <div class="thumb-ui-mockup affiliate-mockup">
                                    <div class="mock-link-pill">
                                        <i class="fas fa-link" style="color: #059669; font-size: 11px;"></i>
                                        <span>affiliate.link</span>
                                    </div>
                                    <div class="mock-link-pill sub">
                                        <i class="fas fa-share-alt" style="color: #2563eb; font-size: 11px;"></i>
                                        <span>ref=linkan</span>
                                    </div>
                                </div>
                            </div>
                            <div class="option-text-info">
                                <span class="option-main-title">{{ __('admin.purpose_affiliate') }}</span>
                                <span class="option-sub-desc">{{ __('admin.purpose_affiliate_desc') }}</span>
                            </div>
                            <i class="fas fa-chevron-right option-chevron-arrow"></i>
                        </div>

                        <!-- Card 4: Lainnya -->
                        <div class="image-style-option-card" onclick="selectPurposeCard('lainnya', this)">
                            <div class="option-thumb-box">
                                <div class="option-mobile-icon-badge" style="background: #F3E8FF;">
                                    <i class="fas fa-layer-group" style="color: #7c3aed; font-size: 20px;"></i>
                                </div>
                                <div class="thumb-ui-mockup custom-mockup">
                                    <div class="mock-layer-box top"></div>
                                    <div class="mock-layer-box mid"></div>
                                    <div class="mock-layer-box bot"></div>
                                </div>
                            </div>
                            <div class="option-text-info">
                                <span class="option-main-title">{{ __('admin.purpose_other') }}</span>
                                <span class="option-sub-desc">{{ __('admin.purpose_other_desc') }}</span>
                            </div>
                            <i class="fas fa-chevron-right option-chevron-arrow"></i>
                        </div>

                    </div>

                    <div class="wizard-modal-footer">
                        <button type="button" class="btn-action-secondary" onclick="closeNewMicrositeModal()">
                            {{ __('admin.cancel') }}
                        </button>
                        <button type="button" class="btn-action-primary" id="btnNextStep1" onclick="goToStep(2)" disabled>
                            Lanjut <i class="fas fa-arrow-right" style="margin-left: 6px;"></i>
                        </button>
                    </div>
                </div>

                <!-- STEP 2: MICROSITE DETAILS (SINGLE PAGE VIEW) -->
                <div class="wizard-step-content" id="wizardStep2" style="display: none;">
                    <div class="step-fields-container">
                        
                        <div class="form-field-item">
                            <label class="field-label">{{ __('admin.microsite_title_label') }} <span style="color: #ef4444;">*</span></label>
                            <input type="text" name="name" id="micrositeNameInput" class="form-control-input" placeholder="{{ __('admin.microsite_title_placeholder') }}" value="{{ old('name', Auth::user()->name) }}" required>
                        </div>

                        <div class="form-field-item" style="margin-top: 16px;">
                            <label class="field-label">{{ __('admin.microsite_bio_label') }}</label>
                            <textarea name="bio" class="form-control-input" rows="3" placeholder="{{ __('admin.microsite_bio_placeholder') }}">{{ old('bio') }}</textarea>
                        </div>

                    </div>

                    <div class="wizard-modal-footer">
                        <button type="button" class="btn-action-secondary" onclick="goToStep(1)">
                            <i class="fas fa-arrow-left" style="margin-right: 6px;"></i> Kembali
                        </button>
                        <button type="submit" class="btn-action-primary">
                            <i class="fas fa-brush" style="margin-right: 6px;"></i> {{ __('admin.create_microsite_btn') }}
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>



</div>
@endsection

@push("scripts")
<!-- Include SortableJS for robust drag and drop -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<script>
    let currentStep = 1;

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert('Tautan berhasil disalin ke clipboard!');
        }).catch(err => {
            console.error('Gagal menyalin teks: ', err);
        });
    }

    function openNewMicrositeModal() {
        const modal = document.getElementById('newMicrositeModalOverlay');
        if (modal) {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
            
            // Reset selection & disable next button
            const hiddenInput = document.getElementById('selectedPurpose');
            if (hiddenInput) hiddenInput.value = '';
            const allCards = document.querySelectorAll('.image-style-option-card');
            allCards.forEach(card => card.classList.remove('active'));

            const btnNext = document.getElementById('btnNextStep1');
            if (btnNext) btnNext.disabled = true;

            goToStep(1);
        }
    }

    function closeNewMicrositeModal() {
        const modal = document.getElementById('newMicrositeModalOverlay');
        if (modal) {
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }
    }

    function selectPurposeCard(purpose, cardElement) {
        const hiddenInput = document.getElementById('selectedPurpose');
        if (hiddenInput) {
            hiddenInput.value = purpose;
        }
        const allCards = document.querySelectorAll('.image-style-option-card');
        allCards.forEach(card => card.classList.remove('active'));
        if (cardElement) {
            cardElement.classList.add('active');
        }

        // Enable next button
        const btnNext = document.getElementById('btnNextStep1');
        if (btnNext) btnNext.disabled = false;
    }

    function goToStep(step) {
        if (step === 2) {
            const purpose = document.getElementById('selectedPurpose').value;
            if (!purpose) {
                alert('Silakan pilih salah satu tujuan pembuatan microsite terlebih dahulu!');
                return;
            }
        }

        currentStep = step;
        const step1 = document.getElementById('wizardStep1');
        const step2 = document.getElementById('wizardStep2');
        const dot1 = document.getElementById('dotStep1');
        const dot2 = document.getElementById('dotStep2');
        const subtitleText = document.getElementById('wizardSubtitle');

        if (step === 1) {
            if (step1) step1.style.display = 'block';
            if (step2) step2.style.display = 'none';
            if (dot1) dot1.classList.add('active');
            if (dot2) dot2.classList.remove('active');
            if (subtitleText) subtitleText.innerText = 'Langkah 1 dari 2: Pilih Tujuan Pembuatan Microsite';
        } else if (step === 2) {
            if (step1) step1.style.display = 'none';
            if (step2) step2.style.display = 'block';
            if (dot1) dot1.classList.remove('active');
            if (dot2) dot2.classList.add('active');
            if (subtitleText) subtitleText.innerText = 'Langkah 2 dari 2: Isi Nama & Bio Microsite Baru';
            const nameInput = document.getElementById('micrositeNameInput');
            if (nameInput) nameInput.focus();
        }
    }

    function toggleAddElementPanel() {
        const panel = document.getElementById('addElementPanel');
        const btn = document.getElementById('btnToggleAddElement');
        const icon = document.getElementById('btnToggleIcon');
        const text = document.getElementById('btnToggleText');
        const digitalProductsSection = document.getElementById('digitalProductsSection');

        if (!panel || !btn) return;

        const isOpen = panel.classList.contains('open');

        if (isOpen) {
            panel.style.maxHeight = '0px';
            panel.style.opacity = '0';
            panel.style.marginTop = '0px';
            panel.classList.remove('open');
            btn.classList.remove('active');
            btn.style.backgroundColor = '#FF9040';

            if (digitalProductsSection) {
                digitalProductsSection.style.display = 'block';
                setTimeout(() => {
                    digitalProductsSection.style.opacity = '1';
                    digitalProductsSection.style.transform = 'translateY(0)';
                }, 20);
            }

            if (icon) {
                icon.className = 'fas fa-plus-circle';
            }
            if (text) text.innerText = '{{ __("admin.add_element") }}';
        } else {
            panel.classList.add('open');
            panel.style.marginTop = '12px';
            panel.style.maxHeight = (panel.scrollHeight + 100) + 'px';
            panel.style.opacity = '1';
            btn.classList.add('active');
            btn.style.backgroundColor = '#374151';

            if (digitalProductsSection) {
                digitalProductsSection.style.opacity = '0';
                digitalProductsSection.style.transform = 'translateY(10px)';
                setTimeout(() => {
                    if (panel.classList.contains('open')) {
                        digitalProductsSection.style.display = 'none';
                    }
                }, 250);
            }

            if (icon) {
                icon.className = 'fas fa-chevron-up';
            }
            if (text) text.innerText = 'Tutup Panel Element';
        }
    }

    function updatePhonePreviewVisibility() {
        const card = document.getElementById('profileBlockCard');
        const liveProfile = document.getElementById('liveProfileSection');
        const emptyState = document.getElementById('phoneEmptyState');

        let isProfileActive = false;
        if (card) {
            const computedDisplay = window.getComputedStyle(card).display;
            isProfileActive = (card.style.display !== 'none') && (computedDisplay !== 'none');
        }

        if (liveProfile) {
            liveProfile.style.display = isProfileActive ? 'block' : 'none';
        }

        if (emptyState) {
            emptyState.style.display = isProfileActive ? 'none' : 'flex';
        }
    }

    function toggleProfileEditForm(forceOpen = false) {
        const formBody = document.getElementById('profileEditFormBody');
        const btnText = document.getElementById('profileEditBtnText');

        if (!formBody) return;

        const isOpen = formBody.classList.contains('open');

        if (isOpen && !forceOpen) {
            formBody.style.maxHeight = '0px';
            formBody.style.opacity = '0';
            formBody.style.marginTop = '0px';
            formBody.classList.remove('open');
            if (btnText) btnText.innerText = 'Edit';
        } else {
            formBody.classList.add('open');
            formBody.style.marginTop = '8px';
            formBody.style.maxHeight = (formBody.scrollHeight + 600) + 'px';
            formBody.style.opacity = '1';
            if (btnText) btnText.innerText = 'Tutup';
        }
    }

    function previewProfileBanner(input) {
        const errorDiv = document.getElementById('bannerSizeError');
        if (errorDiv) errorDiv.style.display = 'none';

        if (input.files && input.files[0]) {
            if (input.files[0].size > 2 * 1024 * 1024) {
                if (errorDiv) errorDiv.style.display = 'block';
                input.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e) {
                const bannerContainer = document.getElementById('bannerPreviewContainer');
                let img = document.getElementById('bannerPreviewImg');
                const placeholder = document.getElementById('bannerPreviewPlaceholder');

                if (placeholder) placeholder.style.display = 'none';
                if (bannerContainer) bannerContainer.style.display = 'block';
                
                if (!img) {
                    img = document.createElement('img');
                    img.id = 'bannerPreviewImg';
                    img.style.width = '100%';
                    img.style.height = '100%';
                    img.style.objectFit = 'cover';
                    bannerContainer.appendChild(img);
                }
                img.style.display = 'block';
                img.src = e.target.result;

                const liveBannerContainer = document.getElementById('livePhoneBannerContainer');
                const liveBannerImg = document.getElementById('livePhoneBannerImg');
                if (liveBannerContainer) liveBannerContainer.style.display = 'block';
                if (liveBannerImg) liveBannerImg.src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewProfileAvatar(input) {
        const errorDiv = document.getElementById('avatarSizeError');
        if (errorDiv) errorDiv.style.display = 'none';

        if (input.files && input.files[0]) {
            if (input.files[0].size > 2 * 1024 * 1024) {
                if (errorDiv) errorDiv.style.display = 'block';
                input.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e) {
                const avatarContainer = document.getElementById('avatarPreviewContainer');
                let img = document.getElementById('avatarPreviewImg');
                const placeholder = document.getElementById('avatarPreviewPlaceholder');

                if (placeholder) placeholder.style.display = 'none';
                if (!img) {
                    img = document.createElement('img');
                    img.id = 'avatarPreviewImg';
                    img.className = 'w-full h-full object-cover';
                    avatarContainer.appendChild(img);
                }
                img.src = e.target.result;

                const liveAvatarImg = document.getElementById('livePhoneAvatarImg');
                const liveAvatarPlaceholder = document.getElementById('livePhoneAvatarPlaceholder');
                const liveAvatarContainer = document.getElementById('livePhoneAvatarContainer');
                
                if (liveAvatarPlaceholder) liveAvatarPlaceholder.style.display = 'none';
                if (liveAvatarImg) {
                    liveAvatarImg.src = e.target.result;
                } else if (liveAvatarContainer) {
                    const newImg = document.createElement('img');
                    newImg.id = 'livePhoneAvatarImg';
                    newImg.style.width = '100%';
                    newImg.style.height = '100%';
                    newImg.style.objectFit = 'cover';
                    newImg.src = e.target.result;
                    liveAvatarContainer.appendChild(newImg);
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function updateProfileShape(shape) {
        const liveAvatarContainer = document.getElementById('livePhoneAvatarContainer');
        const avatarPreviewContainer = document.getElementById('avatarPreviewContainer');
        
        let radius = '50%';
        if (shape === 'rounded') radius = '14px';
        if (shape === 'square') radius = '0px';

        if (liveAvatarContainer) liveAvatarContainer.style.borderRadius = radius;
        if (avatarPreviewContainer) avatarPreviewContainer.style.borderRadius = radius;
    }

    function updateLiveProfileName(val) {
        const liveName = document.getElementById('livePhoneName');
        if (liveName) liveName.innerHTML = val || '{{ Auth::user()->name }}';
    }

    function updateLiveProfileBio(val) {
        const liveBio = document.getElementById('livePhoneBio');
        if (liveBio) liveBio.innerHTML = val;
    }

    function formatText(command, value = null, editorId = null) {
        if (editorId) {
            const editor = document.getElementById(editorId);
            if (editor) {
                editor.focus();
                const range = document.createRange();
                range.selectNodeContents(editor);
                const selection = window.getSelection();
                selection.removeAllRanges();
                selection.addRange(range);
            }
        }
        
        document.execCommand(command, false, value);
        
        if (editorId) {
            const selection = window.getSelection();
            if (selection) {
                selection.removeAllRanges();
            }
        }
        
        syncProfileName();
        syncProfileBio();
        
        const nameEditor = document.getElementById('editorProfileName');
        if(nameEditor) updateLiveProfileName(nameEditor.innerHTML);
        
        const bioEditor = document.getElementById('editorProfileBio');
        if(bioEditor) updateLiveProfileBio(bioEditor.innerHTML);
    }

    function syncProfileName() {
        const editor = document.getElementById('editorProfileName');
        const input = document.getElementById('inputProfileName');
        if (editor && input) input.value = editor.innerHTML;
    }

    function syncProfileBio() {
        const editor = document.getElementById('editorProfileBio');
        const input = document.getElementById('inputProfileBio');
        if (editor && input) input.value = editor.innerHTML;
    }

    // DRAG AND DROP REORDERING SYSTEM FOR MICROSITE ELEMENTS
    let elementSortable = null;

    function initElementDragAndDrop() {
        const list = document.getElementById('elementBlocksList');
        if (!list) return;

        if (elementSortable) {
            elementSortable.destroy();
        }

        elementSortable = new Sortable(list, {
            animation: 150,
            handle: '.drag-handle', // Dragging is only allowed when clicking the handle icon
            ghostClass: 'sortable-ghost', // Styling for drop placeholder
            onEnd: function (evt) {
                // Trigger visual sync and database save on drop
                syncPhonePreviewOrder();
                if (typeof saveElementsOrder === 'function') saveElementsOrder();
            }
        });
    }

    // SYNC DOM ORDER OF PHONE MOCKUP PREVIEW ACCORDING TO LEFT PANEL BLOCKS
    function syncPhonePreviewOrder() {
        const list = document.getElementById('elementBlocksList');
        const phoneContent = document.getElementById('phonePreviewContent');
        if (!list || !phoneContent) return;

        const blocks = list.querySelectorAll('.draggable-element-block');
        blocks.forEach(block => {
            const type = block.getAttribute('data-element-type');
            if (type === 'profile') {
                const liveProfile = document.getElementById('liveProfileSection');
                if (liveProfile) {
                    phoneContent.appendChild(liveProfile);
                }
            } else if (type === 'image') {
                const liveEl = document.getElementById('live_' + block.id);
                if (liveEl) {
                    phoneContent.appendChild(liveEl);
                }
            }
        });
    }

    // DYNAMIC IMAGE ELEMENT LOGIC
    let imageElementCounter = 0;

    function addGambarElement() {
        toggleAddElementPanel(); // Hide side menu
        
        imageElementCounter++;
        const elementId = 'imageBlock_' + new Date().getTime();
        const list = document.getElementById('elementBlocksList');
        
        const html = `
            <div id="${elementId}" class="draggable-element-block" data-element-type="image" style="display: block; transition: all 0.25s ease;">
                <div class="block-item-card" onclick="toggleImageEditForm('${elementId}')">
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
                        <button type="button" onclick="toggleImageEditForm('${elementId}')" style="background: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 8px; padding: 6px 12px; font-size: 12px; font-weight: 700; color: #374151; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.2s ease;" onmouseover="this.style.background='#6366F1'; this.style.color='#ffffff'; this.style.borderColor='#6366F1';" onmouseout="this.style.background='#f3f4f6'; this.style.color='#374151'; this.style.borderColor='#e5e7eb';">
                            <i class="fas fa-pen" style="font-size: 10px;"></i> <span id="btnText_${elementId}">Edit</span>
                        </button>
                    </div>
                </div>

                <div id="formBody_${elementId}" class="edit-form-body" style="max-height: 0; opacity: 0; overflow: hidden; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); border: 1px solid #e5e7eb; border-radius: 12px; background: #f9fafb; margin-top: 0;">
                    <div style="padding: 20px;">
                        <div style="font-size: 14px; font-weight: 700; color: #111827; padding-bottom: 8px; border-bottom: 1px solid #f3f4f6; margin-bottom: 16px;">
                            Pengaturan Elemen Gambar
                        </div>
                        
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 700; color: #374151; margin-bottom: 6px;">Unggah Gambar</label>
                            <div class="upload-dropzone dynamic-dropzone" style="padding: 20px; text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center; position: relative;">
                                <input type="file" accept="image/jpeg, image/png, image/gif" style="position: absolute; inset: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; z-index: 2;" onchange="previewDynamicImage(this, '${elementId}')">
                                
                                <div id="placeholder_${elementId}" style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px;">
                                    <i class="fas fa-cloud-upload-alt" style="font-size: 32px; color: #6366F1;"></i>
                                    <div style="font-size: 13px; font-weight: 600; color: #374151;">Seret gambar ke sini atau <span style="color: #6366F1;">browse</span></div>
                                    <div style="font-size: 10px; color: #9ca3af;">supports JPG, JPEG, PNG & GIF</div>
                                </div>

                                <div id="previewCont_${elementId}" style="display: none; width: 100%; border-radius: 8px; overflow: hidden; background: #f3f4f6; position: relative; z-index: 1;">
                                    <img src="" id="previewImg_${elementId}" style="width: 100%; object-fit: contain;">
                                </div>
                                
                                <div id="error_${elementId}" style="display: none; width: 100%; margin-top: 12px; color: #EF4444; font-size: 11px; font-weight: 700; padding: 8px; border-radius: 6px; z-index: 5; text-align: center; border: 1px solid #FCA5A5; background: #FEE2E2;">
                                    <i class="fas fa-exclamation-circle" style="margin-right: 4px;"></i> Gagal: Ukuran maksimal gambar adalah 2MB!
                                </div>
                            </div>
                        </div>

                        <div style="margin-top: 16px;">
                            <label style="display: block; font-size: 12px; font-weight: 700; color: #374151; margin-bottom: 6px;">URL Tautan (Opsional)</label>
                            <input type="url" id="link_${elementId}" placeholder="https://..." style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 13px; outline: none; transition: border-color 0.2s;" oninput="updateDynamicImageLink('${elementId}', this.value)">
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 16px; border-top: 1px solid #e5e7eb; margin-top: 20px;">
                            <button type="button" onclick="removeDynamicElement('${elementId}')" style="background: none; border: none; color: #EF4444; font-size: 12px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 6px; padding: 8px;">
                                <i class="fas fa-trash-alt"></i> Hapus Elemen
                            </button>
                            <button type="button" onclick="saveDynamicElement('${elementId}')" style="background: #FF9040; border: none; color: #ffffff; padding: 8px 18px; border-radius: 8px; font-size: 12px; font-weight: 700; cursor: pointer; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                                Simpan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        list.insertAdjacentHTML('beforeend', html);
        
        // Add to Phone Preview
        const phoneContent = document.getElementById('phonePreviewContent');
        if (phoneContent) {
            const liveHtml = `
                <div id="live_${elementId}" class="microsite-live-element" style="width: 100%; margin-bottom: 16px; border-radius: 12px; overflow: hidden; display: none; transition: all 0.3s ease; text-align: center;">
                    <a id="liveLink_${elementId}" href="#" target="_blank" style="display: block; width: 100%; cursor: default; pointer-events: none;">
                        <img id="liveImg_${elementId}" src="" style="width: 100%; height: auto; border-radius: 12px; object-fit: contain; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
                    </a>
                </div>
            `;
            phoneContent.insertAdjacentHTML('beforeend', liveHtml);
        }
        
        initElementDragAndDrop();
        bindDynamicDropzone(elementId);
        syncPhonePreviewOrder();
        
        setTimeout(() => toggleImageEditForm(elementId), 50);
    }

    function toggleImageEditForm(elementId) {
        const formBody = document.getElementById('formBody_' + elementId);
        const btnText = document.getElementById('btnText_' + elementId);
        if (!formBody) return;

        if (formBody.classList.contains('open')) {
            formBody.style.maxHeight = '0px';
            formBody.style.opacity = '0';
            formBody.style.marginTop = '0px';
            formBody.classList.remove('open');
            if (btnText) btnText.innerText = 'Edit';
        } else {
            formBody.classList.add('open');
            formBody.style.marginTop = '8px';
            formBody.style.maxHeight = (formBody.scrollHeight + 500) + 'px';
            formBody.style.opacity = '1';
            if (btnText) btnText.innerText = 'Tutup';
        }
    }

    function previewDynamicImage(input, elementId) {
        const errorDiv = document.getElementById('error_' + elementId);
        if (errorDiv) errorDiv.style.display = 'none';

        if (input.files && input.files[0]) {
            if (input.files[0].size > 2 * 1024 * 1024) {
                if (errorDiv) errorDiv.style.display = 'block';
                input.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e) {
                const placeholder = document.getElementById('placeholder_' + elementId);
                const previewCont = document.getElementById('previewCont_' + elementId);
                const previewImg = document.getElementById('previewImg_' + elementId);
                
                if (placeholder) placeholder.style.display = 'none';
                if (previewCont) previewCont.style.display = 'block';
                if (previewImg) previewImg.src = e.target.result;

                const liveEl = document.getElementById('live_' + elementId);
                const liveImg = document.getElementById('liveImg_' + elementId);
                if (liveEl && liveImg) {
                    liveEl.style.display = 'block';
                    liveImg.src = e.target.result;
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function updateDynamicImageLink(elementId, url) {
        const liveLink = document.getElementById('liveLink_' + elementId);
        if (liveLink) {
            liveLink.href = url || '#';
            if (url && url.length > 0) {
                liveLink.style.pointerEvents = 'auto';
                liveLink.style.cursor = 'pointer';
            } else {
                liveLink.style.pointerEvents = 'none';
                liveLink.style.cursor = 'default';
                liveLink.removeAttribute('href');
            }
        }
    }

    function removeDynamicElement(elementId) {
        if(confirm('Hapus elemen gambar ini?')) {
            const block = document.getElementById(elementId);
            const dbId = block ? block.getAttribute('data-db-id') : null;
            
            if (dbId) {
                fetch(`{{ url('/admin/elements/image') }}/${dbId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).then(res => res.json())
                .then(data => {
                    if (data.success) {
                        block.remove();
                        const liveEl = document.getElementById('live_' + elementId);
                        if (liveEl) liveEl.remove();
                        syncPhonePreviewOrder();
                        saveElementsOrder();
                    } else {
                        alert('Gagal menghapus dari database.');
                    }
                }).catch(err => {
                    console.error(err);
                    alert('Terjadi kesalahan saat menghapus.');
                });
            } else {
                if (block) block.remove();
                const liveEl = document.getElementById('live_' + elementId);
                if (liveEl) liveEl.remove();
                syncPhonePreviewOrder();
                saveElementsOrder();
            }
        }
    }

    function saveDynamicElement(elementId) {
        const block = document.getElementById(elementId);
        const fileInput = block.querySelector('input[type="file"]');
        const linkInput = document.getElementById('link_' + elementId);
        const originalBtnText = document.getElementById('btnText_' + elementId);
        
        let formData = new FormData();
        if (fileInput && fileInput.files && fileInput.files[0]) {
            formData.append('image', fileInput.files[0]);
        }
        if (linkInput && linkInput.value) {
            formData.append('link_url', linkInput.value);
        }
        
        const dbId = block.getAttribute('data-db-id');
        if (dbId) {
            formData.append('element_id', dbId);
        }

        const btn = block.querySelector('button[onclick^="saveDynamicElement"]');
        if(btn) {
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
            btn.disabled = true;
        }

        fetch('{{ route('admin.elements.image.store') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (btn) {
                btn.innerHTML = 'Simpan';
                btn.disabled = false;
            }
            if (data.success) {
                block.setAttribute('data-db-id', data.id);
                // Also update the live preview image if needed (already handled by onchange locally, but good to have)
                syncPhonePreviewOrder();
                saveElementsOrder();
                toggleImageEditForm(elementId);
                showSuccessToast('Elemen gambar berhasil disimpan!');
            }
        })
        .catch(err => {
            if (btn) {
                btn.innerHTML = 'Simpan';
                btn.disabled = false;
            }
            console.error(err);
            alert('Terjadi kesalahan saat menyimpan. Pastikan file max 2MB.');
        });
    }

    function saveElementsOrder() {
        const list = document.getElementById('elementBlocksList');
        if(!list) return;
        const blocks = list.querySelectorAll('.draggable-element-block');
        let order = [];
        blocks.forEach(block => {
            const type = block.getAttribute('data-element-type');
            if (type === 'profile') {
                order.push('profile');
            } else if (type === 'image') {
                const dbId = block.getAttribute('data-db-id');
                if (dbId) order.push('image_' + dbId);
            }
        });

        const blocksOrderStr = order.join(',');

        fetch('{{ route('admin.elements.order.update') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ blocks_order: blocksOrderStr })
        });
    }

    function bindDynamicDropzone(elementId) {
        const block = document.getElementById(elementId);
        if(!block) return;
        const zone = block.querySelector('.dynamic-dropzone');
        const input = zone.querySelector('input[type="file"]');
        if(input && zone) {
            input.addEventListener('dragenter', () => zone.classList.add('drag-over-active'));
            input.addEventListener('dragleave', () => zone.classList.remove('drag-over-active'));
            input.addEventListener('drop', () => zone.classList.remove('drag-over-active'));
        }
    }

    function initPageEvents() {
        // Sort blocks based on DB order
        const list = document.getElementById('elementBlocksList');
        const dbOrderStr = '{!! $appearance->blocks_order ?? "" !!}';
        if (list && dbOrderStr) {
            const dbOrder = dbOrderStr.split(',');
            dbOrder.forEach(blockId => {
                let el = null;
                if (blockId === 'profile') {
                    el = document.getElementById('profileBlockCard');
                } else if (blockId.startsWith('image_')) {
                    const dbId = blockId.split('_')[1];
                    el = document.querySelector(`.draggable-element-block[data-db-id="${dbId}"]`);
                }
                if (el) {
                    list.appendChild(el);
                }
            });
        }

        initElementDragAndDrop();
        syncPhonePreviewOrder();
        updatePhonePreviewVisibility();

        // Initialize drag and drop visual states for upload zones
        const dropzones = document.querySelectorAll('.upload-dropzone');
        dropzones.forEach(zone => {
            const input = zone.querySelector('input[type="file"]');
            if(input && !input.dataset.dragbound) {
                input.dataset.dragbound = 'true';
                input.addEventListener('dragenter', () => {
                    zone.classList.add('drag-over-active');
                });
                input.addEventListener('dragleave', () => {
                    zone.classList.remove('drag-over-active');
                });
                input.addEventListener('drop', () => {
                    zone.classList.remove('drag-over-active');
                });
            }
        });

        // AJAX PROFILE FORM SUBMISSION (NO RELOAD NEEDED)
        const profileForm = document.getElementById('profileBlockForm');
        if (profileForm && !profileForm.dataset.initialized) {
            profileForm.dataset.initialized = 'true';
            profileForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const submitBtn = this.querySelector('button[type="submit"]');
                const origText = submitBtn ? submitBtn.innerText : 'Simpan Perubahan';

                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerText = 'Menyimpan...';
                }

                if (typeof syncProfileName === 'function') syncProfileName();
                if (typeof syncProfileBio === 'function') syncProfileBio();
                const formData = new FormData(this);

                fetch("{{ route('admin.appearance.update') }}", {
                    method: "POST",
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                })
                .then(async res => {
                    if (!res.ok) {
                        const errData = await res.json().catch(() => ({}));
                        throw errData;
                    }
                    return res.json();
                })
                .then(data => {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerText = origText;
                    }

                    if (data && data.success) {
                        const card = document.getElementById('profileBlockCard');
                        if (card) card.style.display = 'block';

                        updatePhonePreviewVisibility();
                        toggleProfileEditForm();

                        showSuccessToast('Profil berhasil disimpan!');
                    }
                })
                .catch(err => {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerText = origText;
                    }
                    
                    if (err && err.errors) {
                        // Extract first validation error
                        const firstError = Object.values(err.errors)[0][0];
                        alert('Gagal menyimpan: ' + firstError);
                    } else {
                        // If it's a server error or non-JSON response, fallback to normal submission
                        HTMLFormElement.prototype.submit.call(this);
                    }
                });
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPageEvents);
    } else {
        initPageEvents();
    }
    document.addEventListener('turbo:load', initPageEvents);
    document.addEventListener('turbolinks:load', initPageEvents);

    function showSuccessToast(message) {
        let toast = document.getElementById('profileSuccessToast');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'profileSuccessToast';
            toast.style.cssText = 'position: fixed; bottom: 24px; right: 24px; background: #10B981; color: #ffffff; padding: 12px 20px; border-radius: 10px; font-weight: 700; font-size: 13px; box-shadow: 0 4px 14px rgba(16,185,129,0.3); z-index: 9999; display: flex; align-items: center; gap: 8px; transition: all 0.3s ease; opacity: 0; transform: translateY(10px);';
            toast.innerHTML = '<i class="fas fa-check-circle"></i> <span>' + message + '</span>';
            document.body.appendChild(toast);
        }
        setTimeout(() => {
            toast.style.opacity = '1';
            toast.style.transform = 'translateY(0)';
        }, 10);
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(10px)';
        }, 3000);
    }

    window.onclick = function(event) {
        if (event.target.id === 'newMicrositeModalOverlay') {
            closeNewMicrositeModal();
        }
    }
</script>
@endpush
