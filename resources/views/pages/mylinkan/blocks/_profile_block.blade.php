                    <div id="profileBlockCard" class="draggable-element-block" data-element-type="profile">
                        <!-- COLLAPSED BLOCK HEADER CARD -->
                            <div class="block-item-card js-toggle-edit-form" data-type="Profile">
                                <i class="fas fa-grip-vertical drag-handle-icon drag-handle-hidden"></i>
                                <div class="profile-block-icon">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                                <div class="block-item-content">
                                    <div class="block-item-title-wrapper">
                                        <span>Profil</span>
                                    </div>
                                </div>
                                <div class="block-item-actions js-stop-propagation" >
                                    <button type="button" data-type="Profile" class="btn-profile-edit js-toggle-edit-form">
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
                                                <input type="file" name="banner" id="inputBannerFile" accept="image/jpeg, image/png, image/gif" class="dropzone-input js-preview-profile-banner" >
                                                
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
                                                <input type="file" name="profile_image" id="inputAvatarFile" accept="image/jpeg, image/png, image/gif" class="dropzone-input js-preview-profile-avatar" >
                                                
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
                                                    <input type="radio" name="profile_shape" value="circle" {{ old('profile_shape', $appearance->profile_shape ?? 'circle') == 'circle' ? 'checked' : '' }}  class="js-update-profile-shape" data-shape="circle">
                                                    <div class="shape-preview shape-circle"></div>
                                                    <span class="shape-option-text">Lingkaran</span>
                                                </label>
                                                <label class="shape-option-label">
                                                    <input type="radio" name="profile_shape" value="rounded" {{ old('profile_shape', $appearance->profile_shape ?? 'circle') == 'rounded' ? 'checked' : '' }}  class="js-update-profile-shape" data-shape="rounded">
                                                    <div class="shape-preview shape-rounded"></div>
                                                    <span class="shape-option-text">Rounded</span>
                                                </label>
                                                <label class="shape-option-label">
                                                    <input type="radio" name="profile_shape" value="square" {{ old('profile_shape', $appearance->profile_shape ?? 'circle') == 'square' ? 'checked' : '' }}  class="js-update-profile-shape" data-shape="square">
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
                                                    <button type="button" class="js-prevent-default" data-cmd="bold" class="toolbar-btn toolbar-btn-bold js-format-profile-text" title="Tebal (Bold)"><i class="fas fa-bold"></i></button>
                                                    <button type="button" class="js-prevent-default" data-cmd="italic" class="toolbar-btn toolbar-btn-italic js-format-profile-text" title="Miring (Italic)"><i class="fas fa-italic"></i></button>
                                                    
                                                    <div class="toolbar-separator"></div>
                                                    
                                                    <select data-cmd="fontName" data-target="editorProfileName" class="toolbar-select js-format-profile-text-val" title="Pilih Font">
                                                        <option value="Plus Jakarta Sans">Jakarta Sans</option>
                                                        <option value="Arial">Arial</option>
                                                        <option value="Times New Roman">Times New Roman</option>
                                                        <option value="Courier New">Courier New</option>
                                                        <option value="Georgia">Georgia</option>
                                                        <option value="Verdana">Verdana</option>
                                                    </select>
                                                    
                                                    <label class="toolbar-color-picker" title="Pilih Warna">
                                                        <i class="fas fa-eye-dropper"></i>
                                                        <input type="color" id="colorPickerProfileName" data-cmd="foreColor" data-target="editorProfileName" class="hidden-color-input js-format-profile-text-val">
                                                    </label>
                                                    
                                                    
                                                </div>
                                                <div id="editorProfileName" contenteditable="true" class="text-editor-body editor-name-body js-sync-profile-name" placeholder="Masukkan nama profil Anda..." >{!! old('name', $appearance->name ?? Auth::user()->name) !!}</div>
                                                <input type="hidden" name="name" id="inputProfileName" value="{{ old('name', $appearance->name ?? Auth::user()->name) }}">
                                            </div>
                                        </div>

                                        <!-- 5. DESKRIPSI PROFILE (TEKS EDITOR SEDERHANA) -->
                                        <div>
                                            <label class="profile-form-label">Deskripsi / Bio Profil</label>
                                            <div class="text-editor-container">
                                                <div class="text-editor-toolbar">
                                                    <button type="button" class="js-prevent-default" data-cmd="bold" class="toolbar-btn toolbar-btn-bold js-format-profile-text" title="Tebal (Bold)"><i class="fas fa-bold"></i></button>
                                                    <button type="button" class="js-prevent-default" data-cmd="italic" class="toolbar-btn toolbar-btn-italic js-format-profile-text" title="Miring (Italic)"><i class="fas fa-italic"></i></button>
                                                    <button type="button" class="js-prevent-default" data-cmd="underline" class="toolbar-btn toolbar-btn-underline js-format-profile-text" title="Garis Bawah (Underline)"><i class="fas fa-underline"></i></button>
                                                    
                                                    <div class="toolbar-separator"></div>
                                                    
                                                    <select data-cmd="fontName" data-target="editorProfileBio" class="toolbar-select js-format-profile-text-val" title="Pilih Font">
                                                        <option value="Plus Jakarta Sans">Jakarta Sans</option>
                                                        <option value="Arial">Arial</option>
                                                        <option value="Times New Roman">Times New Roman</option>
                                                        <option value="Courier New">Courier New</option>
                                                        <option value="Georgia">Georgia</option>
                                                        <option value="Verdana">Verdana</option>
                                                    </select>
                                                    <label class="toolbar-color-picker" title="Pilih Warna">
                                                        <i class="fas fa-eye-dropper"></i>
                                                        <input type="color" id="colorPickerProfileBio" data-cmd="foreColor" data-target="editorProfileBio" class="hidden-color-input js-format-profile-text-val">
                                                    </label>
                                                </div>
                                                <div id="editorProfileBio" contenteditable="true" class="text-editor-body editor-bio-body js-sync-profile-bio" placeholder="Tulis deskripsi singkat profil Anda..." >{!! old('bio', $appearance->bio ?? '') !!}</div>
                                                <input type="hidden" name="bio" id="inputProfileBio" value="{{ old('bio', $appearance->bio ?? '') }}">
                                            </div>
                                        </div>

                                        <!-- ACTION BUTTONS -->
                                        <div class="form-actions-wrapper">
                                            <button type="button" data-type="Profile" class="btn-secondary js-toggle-edit-form">Batal</button>
                                            <button type="submit" class="btn-primary">Simpan Perubahan</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            </div>
                        </div>
