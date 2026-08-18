@extends("layouts.admin")

@section("page_title", __('admin.appearance_title'))

@push("styles")
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/pages/appearance.css') }}" data-turbo-track="reload">
@endpush



@section("content")
<div class="dashboard-appearance-page">

<form method="POST" action="{{ route('admin.appearance.update') }}" enctype="multipart/form-data" id="appearanceForm">
            @csrf
            <div class="content-section">
                <div class="left-panel">
                    <div class="url-section card">
                        <div class="url-input-group">
                            <input type="text" class="url-input" value="My Linkan: {{ url('linkan.id/' . Auth::user()->username) }}" readonly>
                            <button class="share-button" type="button" onclick="copyToClipboard('{{ route('track.view', ['username' => Auth::user()->username]) }}')">
                                <i class="fas fa-share-alt"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Banner -->
                    <div class="card">
                        <h2 class="card-title">Banner</h2>
                        <div class="banner-section">
@if($appearance && $appearance->banner)
    <img src="{{ asset('storage/' . $appearance->banner) }}" alt="Banner"
         style="width: 589px; height: 233px; object-fit: cover; margin-bottom: 15px;" id="previewBanner">
    <input type="hidden" name="delete_banner" id="deleteBanner" value="0">
    <button type="button" onclick="confirmDeleteBanner()" class="upload-button" style="background-color: red; color: white;">
    <button type="button" onclick="confirmDeleteBanner()" class="upload-button" style="background-color: red; color: white;">
        {{ __('admin.delete_banner') }}
    </button>
@else
    <i class="fas fa-image"></i>
    <p class="banner-text">{{ __('admin.optimize_banner_size') }}</p>
@endif
<input type="file" name="banner" id="bannerInput" style="display: none;" accept="image/*">
<button type="button" class="upload-button" onclick="document.getElementById('bannerInput').click()">{{ __('admin.upload_image') }}</button>
                        </div>
                    </div>

                    <!-- Profile -->
                    <div class="card">
                        <h2 class="card-title">{{ __('admin.profile') }}</h2>
                        <div class="profile-section">
                           <div class="profile-image" onclick="openProfilePopup()">
                                @if($appearance && $appearance->profile_image)
                                    <img src="{{ asset('storage/' . $appearance->profile_image) }}" alt="Profile" id="previewProfileImage">
                                @else
                                    <i class="fas fa-user" id="defaultProfileIcon"></i>
                                @endif
                            </div>
                            <input type="file" name="profile_image" id="profileImageInput" style="display: none;" accept="image/*">
                            <input type="text" name="name" class="profile-name" placeholder="{{ __('admin.your_name') }}" value="{{ $appearance ? $appearance->name : Auth::user()->name }}" id="inputName">
                            <div class="bio-section">
                                <div id="editor" style="height: 150px; margin-bottom: 10px;">{!! $appearance ? $appearance->bio : '' !!}</div>
                                <input type="hidden" name="bio" id="bioInput" value="{{ $appearance ? $appearance->bio : '' }}">
                            </div>
                            <!-- 🎨 Color Picker -->
<div style="display: flex; align-items: center; gap: 10px; margin-top: 10px;">
    <label for="colorPicker">{{ __('admin.customize_color') }}</label>
    <input type="color" id="colorPicker" name="themeColor" value="{{ $appearance ? $appearance->theme_color : '#FF9040' }}">

   <input type="hidden" name="theme_color" id="themeColor" value="{{ $appearance ? $appearance->theme_color : '#FF9040' }}">
</div>

                        </div>
                    </div>
                 <!-- Social Media Links -->
<div class="card">
    <h2 class="card-title">{{ __('admin.social_links') }}</h2>

    <!-- Tombol Pilih Platform -->
    <div id="social-buttons" style="margin-bottom: 10px;">
        @foreach(['instagram','tiktok','whatsapp','linkedin','facebook','website','twitter','youtube','telegram','email','discord'] as $platform)
            <button type="button" class="social-btn" data-platform="{{ $platform }}">
                <i class="{{
                    [
                        'instagram'=>'fab fa-instagram',
                        'tiktok'=>'fab fa-tiktok',
                        'whatsapp'=>'fab fa-whatsapp',
                        'linkedin'=>'fab fa-linkedin',
                        'facebook'=>'fab fa-facebook',
                        'website'=>'fas fa-globe',
                        'twitter'=>'fab fa-twitter',
                        'youtube'=>'fab fa-youtube',
                        'telegram'=>'fab fa-telegram',
                        'email'=>'fas fa-envelope',
                        'discord'=>'fab fa-discord'
                    ][$platform]
                }}"></i>
                {{ ucfirst($platform) }}
            </button>
        @endforeach
    </div>

    <!-- Input yang akan muncul -->
    <div id="social-link-inputs">
        @foreach(['instagram','tiktok','whatsapp','linkedin','facebook','website','twitter','youtube','telegram','email','discord'] as $platform)
            <div class="social-input" data-platform="{{ $platform }}"
                 style="{{ ($appearance && $appearance->$platform) ? '' : 'display:none;' }}">
                <i class="{{
                    [
                        'instagram'=>'fab fa-instagram',
                        'tiktok'=>'fab fa-tiktok',
                        'whatsapp'=>'fab fa-whatsapp',
                        'linkedin'=>'fab fa-linkedin',
                        'facebook'=>'fab fa-facebook',
                        'website'=>'fas fa-globe',
                        'twitter'=>'fab fa-twitter',
                        'youtube'=>'fab fa-youtube',
                        'telegram'=>'fab fa-telegram',
                        'email'=>'fas fa-envelope',
                        'discord'=>'fab fa-discord'
                    ][$platform]
                }}"></i>
                <input
                    type="{{ $platform=='email' ? 'email' : 'url' }}"
                    id="input{{ ucfirst($platform) }}"
                    name="{{ $platform }}"
                    placeholder="{{ ucfirst($platform) }} {{ $platform=='email' ? 'Address' : 'URL' }}"
                    value="{{ $appearance->$platform ?? '' }}"
                >
                <button type="button" class="remove-social" title="Hapus">&times;</button>
            </div>
        @endforeach
    </div>
</div>


    <!-- Theme -->
   <div class="card">
    <h2 class="card-title">{{ __('admin.theme') }}</h2>
    <div class="theme-options" id="themeOptions"
         style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 16px;">

        @php
            $themes = ['blue ocean.png', 'city light.png', 'clasic.png', 'desert.png', 'green flower.png', 'pink candy.png', 'playstation abstract.png','sunset.png', 'mountain.png','library.png','news paper.png'];
        @endphp

        @foreach ($themes as $theme)
            <div style="text-align: center;">
                <img src="{{ asset('images/previewt/' . $theme) }}"
                     data-bg="{{ asset('images/background/' . $theme) }}"
                     data-name="{{ $theme }}"
                     class="theme-preview"
                     style="width: 100px; height: 70px; object-fit: cover; cursor: pointer; border: 2px solid transparent; border-radius: 8px; transition: transform 0.2s;">
                <div style="font-size: 13px; margin-top: 6px; color: #333;">
                    {{ ucwords(str_replace(['-', '_'], ' ', pathinfo($theme, PATHINFO_FILENAME))) }}
                </div>
            </div>
        @endforeach

    </div>
    <input type="hidden" name="background_color" id="backgroundColor" value="{{ $appearance ? $appearance->background_color : '' }}">
</div>


<div style="display: flex; justify-content: center; margin-top: 20px;">
    <button type="submit" class="save-button"
        style="background-color: #FF9040">
        {{ __('admin.save_changes') }}
    </button>
</div>
  </form>
  <!-- Modal Profile Popup -->
<div id="profilePopup" class="popup-modal" style="display: none;">
    <div class="popup-content">
        <span class="close-btn" onclick="closeProfilePopup()">&times;</span>
        
        @if($appearance && $appearance->profile_image)
            <button type="button" class="upload-button" onclick="document.getElementById('profileImageInput').click()">{{ __('admin.upload_image') }}</button>
           <input type="hidden" name="delete_profile_image" id="deleteProfileImage" value="0">
    <button type="button" onclick="confirmDeleteProfileImage()" class="upload-button" style="background-color: red; color: white; margin-top: 10px;">
        {{ __('admin.delete_profile_photo') }}
    </button>
        @else
            <button type="button" class="upload-button" onclick="document.getElementById('profileImageInput').click()">{{ __('admin.upload_image') }}</button>
        @endif
    </div>
</div>

                </div>

                <!-- Preview -->

              
                    <div class="preview-section">
                          <div class="right-panel">
                     <div class="preview-header">
                       <h2 class="card-priview">{{ __('admin.preview') }}</h2>
                      </div>
                          <div class="preview-phone">
                              <div class="preview-screen" id="previewScreen" style="width: 100%; height: 100%; background: #f8f9fa; border-radius: 30px; padding: 20px; display: flex; flex-direction: column; align-items: center; overflow-y: auto; background-image: url('{{ $appearance && $appearance->background_color ? asset('images/background/' . $appearance->background_color) : '' }}'); background-size: cover; background-position: center;">
                                  @if($appearance && $appearance->banner)
                                      <div class="preview-banner" style="width: 100%; height: 120px; background: #ddd; border-radius: 10px; margin-bottom: 20px; overflow: hidden;">
                                          <img src="{{ asset('storage/' . $appearance->banner) }}" alt="Banner" style="width: 100%; height: 100%; object-fit: cover;">
                                      </div>
                                  @endif
                                  <div class="preview-profile" id="previewPhoneProfile" style="width: 80px; height: 80px; border-radius: 50%; background: #ddd; margin-bottom: 15px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                      @if($appearance && $appearance->profile_image)
                                          <img src="{{ asset('storage/' . $appearance->profile_image) }}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover;">
                                      @else
                                          <i class="fas fa-user"></i>
                                      @endif
                                  </div>
                                  <div class="preview-name" id="livePreviewName" style="font-size: 18px; font-weight: 600; margin-bottom: 10px; text-align: center; color: {{ $appearance ? $appearance->theme_color : '#FF9040' }}">{{ $appearance ? $appearance->name : Auth::user()->name }}</div>
                                  <div class="preview-bio" id="livePreviewBio" style="font-size: 14px; color: {{ $appearance ? $appearance->theme_color : '#FF9040' }}; text-align: center; margin-bottom: 15px; padding: 0 20px; line-height: 1.4;">{!! $appearance ? $appearance->bio : '' !!}</div>
                                  <div class="preview-social-links" id="livePreviewSocialLinks" style="display: flex; gap: 15px; margin-bottom: 20px;">
                                      @if($appearance && $appearance->instagram)
                                          <a href="{{ $appearance->instagram }}" target="_blank"><i class="fab fa-instagram" style="color: {{ $appearance ? $appearance->theme_color : '#FF9040' }}"></i></a>
                                      @endif
                                      @if($appearance && $appearance->tiktok)
                                          <a href="{{ $appearance->tiktok }}" target="_blank"><i class="fab fa-tiktok" style="color: {{ $appearance ? $appearance->theme_color : '#FF9040' }}"></i></a>
                                      @endif
                                      @if($appearance && $appearance->whatsapp)
                                          <a href="{{ $appearance->whatsapp }}" target="_blank"><i class="fab fa-whatsapp" style="color: {{ $appearance ? $appearance->theme_color : '#FF9040' }}"></i></a>
                                      @endif
                                  </div>
                                  @if($appearance && $appearance->description)
                                      <div class="preview-bio" style="color: {{ $appearance ? $appearance->theme_color : '#FF9040' }}">{{ $appearance->description }}</div>
                                  @endif
                                  @if($appearance && $appearance->link)
                                      <a href="{{ $appearance->link }}" class="preview-product-button" style="background-color: {{ $appearance ? $appearance->theme_color : '#FF9040' }}">{{ $appearance->button_text ?? __('admin.buy') }}</a>
                                  @endif
                                  @if($digitalProducts && $digitalProducts->count() > 0)
                                      <div class="preview-products" style="width: 100%; padding: 10px; display: flex; flex-direction: column; gap: 10px;">
                                          @foreach($digitalProducts as $product)
                                              <div class="preview-product-item" style="background: white; border-radius: 8px; padding: 10px; display: flex; align-items: center; gap: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: transform 0.2s ease;">
                                                  <div class="preview-product-image" style="width: 40px; height: 40px; background: #FFE5D3; border-radius: 6px; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0;">
                                                      @if($product->image)
                                                          <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                                                      @else
                                                          <i class="fas fa-file-alt"></i>
                                                      @endif
                                                  </div>
                                                  <div class="preview-product-info" style="flex: 1; min-width: 0;">
                                                      <div class="preview-product-title" style="font-size: 14px; color: #333; margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $product->title }}</div>
                                                  </div>
                                                  <a href="{{ route('track.click', ['link_id' => Auth::user()->username, 'target' => $product->platform_url ?? '#']) }}" class="preview-product-button" style="background-color: {{ $appearance ? $appearance->theme_color : '#FF9040' }}; color: white; padding: 4px 12px; border-radius: 4px; font-size: 12px; border: none; cursor: pointer; transition: background-color 0.3s ease; flex-shrink: 0; min-width: 100px; text-align: center; height: 28px; display: flex; align-items: center; justify-content: center; text-decoration: none;" target="_blank">{{ str_replace('_', ' ', $product->button_text ?? __('admin.buy')) }}</a>
                                              </div>
                                          @endforeach
                                      </div>
                                  @endif
                              </div>
                          </div>

                      </div>
                  </div>
              </div>

</div>
@endsection

@push("scripts")
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
@endpush

@push("scripts")
<script>
// Semua script dalam satu blok DOMContentLoaded

document.addEventListener('turbo:load', function () {
    const form = document.getElementById('appearanceForm');
    if (!form) return;
    // --- Quill.js ---
    var quill = new Quill('#editor', {
        theme: 'snow',
        placeholder: 'Tulis bio Anda di sini...',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['link'],
                ['clean']
            ]
        },
        bounds: '#editor'
    });
    quill.on('text-change', function() {
        const content = quill.root.innerHTML;
        const previewBio = document.getElementById('livePreviewBio');
        if (previewBio) previewBio.innerHTML = content;
        const bioInput = document.getElementById('bioInput');
        if (bioInput) bioInput.value = content;
    });

    // --- Color Picker & Theme Color ---
    const colorPicker = document.getElementById('colorPicker');
    const themeColorInput = document.getElementById('themeColor');
    const previewName = document.getElementById('livePreviewName');
    const previewBio = document.getElementById('livePreviewBio');
    const previewButtons = document.querySelectorAll('.preview-product-button');
    const previewSocialLinks = document.getElementById('livePreviewSocialLinks');
    function updatePreviewColor(color) {
        if (previewName) previewName.style.color = color;
        if (previewBio) previewBio.style.color = color;
        previewButtons.forEach(btn => btn.style.backgroundColor = color);
        if (themeColorInput) themeColorInput.value = color;
        if (colorPicker) colorPicker.value = color;
        if (previewSocialLinks) {
            previewSocialLinks.querySelectorAll('a i').forEach(icon => {
                icon.style.color = color;
            });
        }
    }
    if (colorPicker) {
        colorPicker.addEventListener('input', function () {
            updatePreviewColor(this.value);
        });
    }
    if (themeColorInput) updatePreviewColor(themeColorInput.value);

    // --- Live Preview Name ---
    const inputName = document.getElementById('inputName');
    if (inputName && previewName) {
        inputName.addEventListener('input', function() {
            previewName.textContent = this.value;
        });
    }

    // --- Social Links ---
    const placeholderMap = {
        instagram: 'https://instagram.com/',
        tiktok: 'https://tiktok.com/',
        whatsapp: 'https://wa.me/08xxxxxxxxxx',
        linkedin: 'https://linkedin.com/in/username',
        facebook: 'https://facebook.com/username',
        website: 'https://yourwebsite.com',
        twitter: 'https://twitter.com/username',
        youtube: 'https://youtube.com/@channel',
        telegram: 'https://t.me/username',
        email: 'Your email',
        discord: 'https://discord.gg/invitecode'
    };
    // Toggle tampil input saat klik tombol platform
    document.querySelectorAll('.social-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const p = btn.dataset.platform;
            const inputDiv = document.querySelector(`.social-input[data-platform="${p}"]`);
            if (inputDiv) {
                inputDiv.style.display = inputDiv.style.display === 'none' ? 'flex' : 'none';
                const input = inputDiv.querySelector('input');
                if (input && placeholderMap[p]) {
                    input.placeholder = placeholderMap[p];
                }
                updateSocialPreview();
                updatePreviewColor(themeColorInput.value);
            }
        });
    });
    // Hapus satu social-input
    document.querySelectorAll('.remove-social').forEach(btn => {
        btn.addEventListener('click', () => {
            const div = btn.closest('.social-input');
            if (div) {
                const inp = div.querySelector('input');
                if (inp) inp.value = '';
                div.style.display = 'none';
                updateSocialPreview();
            }
        });
    });
    // Live preview social links
    function updateSocialPreview() {
        const platforms = [
            { id: 'inputInstagram', icon: 'fab fa-instagram' },
            { id: 'inputTiktok', icon: 'fab fa-tiktok' },
            { id: 'inputWhatsapp', icon: 'fab fa-whatsapp' },
            { id: 'inputLinkedin', icon: 'fab fa-linkedin' },
            { id: 'inputFacebook', icon: 'fab fa-facebook' },
            { id: 'inputWebsite', icon: 'fas fa-globe' },
            { id: 'inputTwitter', icon: 'fab fa-twitter' },
            { id: 'inputYoutube', icon: 'fab fa-youtube' },
            { id: 'inputTelegram', icon: 'fab fa-telegram' },
            { id: 'inputEmail', icon: 'fas fa-envelope', isEmail: true },
            { id: 'inputDiscord', icon: 'fab fa-discord' },
        ];
        if (!previewSocialLinks) return;
        previewSocialLinks.innerHTML = '';
        platforms.forEach(platform => {
            const input = document.getElementById(platform.id);
            if (input && input.value) {
                const href = platform.isEmail ? `mailto:${input.value}` : input.value;
                previewSocialLinks.innerHTML += `<a href="${href}" target="_blank"><i class="${platform.icon}"></i></a>`;
            }
        });
        // Update warna icon
        updatePreviewColor(themeColorInput.value);
    }
    [
        'inputInstagram', 'inputTiktok', 'inputWhatsapp', 'inputLinkedin',
        'inputFacebook', 'inputWebsite', 'inputTwitter', 'inputYoutube',
        'inputTelegram', 'inputEmail', 'inputDiscord'
    ].forEach(id => {
        const input = document.getElementById(id);
        if (input) {
            input.addEventListener('input', updateSocialPreview);
        }
    });
    updateSocialPreview();

    // --- Banner Preview ---
    const bannerInput = document.getElementById('bannerInput');
    if (bannerInput) {
        bannerInput.addEventListener('change', function(e) {
            const reader = new FileReader();
            reader.onload = function(event) {
                let img = document.getElementById('previewBanner');
                if (img) {
                    img.src = event.target.result;
                } else {
                    const bannerSection = document.querySelector('.banner-section');
                    img = document.createElement('img');
                    img.id = 'previewBanner';
                    img.src = event.target.result;
                    img.alt = 'Banner';
                    img.style = "width: 589px; height: 233px; object-fit: cover; margin-bottom: 15px;";
                    bannerSection.insertBefore(img, bannerSection.querySelector('button.upload-button'));
                }
                let phoneBanner = document.querySelector('.preview-banner img');
                if (phoneBanner) {
                    phoneBanner.src = event.target.result;
                } else {
                    const previewScreen = document.getElementById('previewScreen');
                    let previewBannerDiv = previewScreen.querySelector('.preview-banner');
                    if (!previewBannerDiv) {
                        previewBannerDiv = document.createElement('div');
                        previewBannerDiv.className = 'preview-banner';
                        previewBannerDiv.style = "width: 100%; height: 120px; background: #ddd; border-radius: 10px; margin-bottom: 20px; overflow: hidden;";
                        previewScreen.insertBefore(previewBannerDiv, previewScreen.firstChild);
                    }
                    const newImg = document.createElement('img');
                    newImg.src = event.target.result;
                    newImg.alt = 'Banner';
                    newImg.style = "width: 100%; height: 100%; object-fit: cover;";
                    previewBannerDiv.innerHTML = '';
                    previewBannerDiv.appendChild(newImg);
                }
            };
            reader.readAsDataURL(e.target.files[0]);
        });
    }

    // --- Profile Image Preview ---
    const profileImageInput = document.getElementById('profileImageInput');
    if (profileImageInput) {
        profileImageInput.addEventListener('change', function(e) {
            const reader = new FileReader();
            reader.onload = function(event) {
                const previewProfile = document.getElementById('previewPhoneProfile');
                if (previewProfile) previewProfile.innerHTML = `<img src="${event.target.result}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover;">`;
                const profileImage = document.querySelector('.profile-image');
                if (profileImage) profileImage.innerHTML = `<img src="${event.target.result}" alt="Profile">`;
            };
            reader.readAsDataURL(e.target.files[0]);
        });
    }

    // --- Theme Pilihan (Background Gambar) ---
    const backgroundColorInput = document.getElementById('backgroundColor');
    const previewScreen = document.getElementById('previewScreen');
    // Terapkan background dari database saat halaman dimuat ulang
    const currentBackground = backgroundColorInput ? backgroundColorInput.value : '';
    if (currentBackground) {
        const matchedTheme = document.querySelector(`.theme-preview[data-name="${currentBackground}"]`);
        if (matchedTheme && previewScreen) {
            const bgUrl = matchedTheme.getAttribute('data-bg');
            previewScreen.style.backgroundImage = `url('${bgUrl}')`;
            previewScreen.style.backgroundSize = 'cover';
            previewScreen.style.backgroundPosition = 'center';
            matchedTheme.style.border = "2px solid #FF9040";
        }
    }
    document.querySelectorAll('.theme-preview').forEach(img => {
        img.addEventListener('click', function () {
            const bgUrl = this.getAttribute('data-bg');
            const bgName = this.getAttribute('data-name');
            if (backgroundColorInput) backgroundColorInput.value = bgName;
            if (previewScreen) {
                previewScreen.style.backgroundImage = `url('${bgUrl}')`;
                previewScreen.style.backgroundSize = 'cover';
                previewScreen.style.backgroundPosition = 'center';
            }
            document.querySelectorAll('.theme-preview').forEach(tp => {
                tp.style.border = "2px solid transparent";
            });
            this.style.border = "2px solid #FF9040";
        });
    });

    // --- Copy to Clipboard ---
    window.copyToClipboard = function(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert('Link copied to clipboard!');
        }).catch(err => {
            console.error('Failed to copy text: ', err);
        });
    };

    // --- Popup Profile ---
    window.openProfilePopup = function() {
        const popup = document.getElementById('profilePopup');
        if (popup) popup.style.display = 'flex';
    };
    window.closeProfilePopup = function() {
        const popup = document.getElementById('profilePopup');
        if (popup) popup.style.display = 'none';
    };
    window.confirmDeleteProfileImage = function() {
        if (confirm('Yakin ingin menghapus foto profil?')) {
            const del = document.getElementById('deleteProfileImage');
            if (del) del.value = 1;
            const form = document.getElementById('appearanceForm');
            if (form) form.submit();
        }
    };
    window.confirmDeleteBanner = function() {
        if (confirm('Yakin ingin menghapus banner?')) {
            const form = document.getElementById('appearanceForm');
            const del = document.getElementById('deleteBanner');
            if (del) del.value = 1;
            if (form) form.submit();
        }
    };
});
</script>
@endpush
