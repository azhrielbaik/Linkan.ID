@props(['appearance', 'imageElements'])

<div class="editor-sticky-preview editor-sticky-preview-custom">
    <!-- SECTION TITLE -->
    <div class="preview-section-title-wrapper">
        <h3 class="preview-section-title">
            <i class="fas fa-mobile-alt preview-section-icon"></i> {{ __('admin.live_phone_preview') }}
        </h3>
    </div>

    <!-- SIDE-BY-SIDE FLEX CONTAINER: PHONE MOCKUP (LEFT) + VERTICAL URL BAR (RIGHT) -->
    <div class="phone-preview-flex-container">
        
        <!-- DAISYUI PHONE MOCKUP FRAME (LEFT) -->
        <div class="mockup-phone border-[#ff8938]">
            <div class="mockup-phone-camera"></div>
            <div class="mockup-phone-display">
                <!-- REALISTIC SMARTPHONE TOP STATUS BAR -->
                <div class="mockup-status-bar">
                    <span>09:41</span>
                    <div class="mockup-status-icons">
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
                        <div class="empty-state-title">Belum ada elemen</div>
                        <div class="empty-state-desc">Tambahkan elemen dari panel kiri untuk mulai membangun microsite Anda</div>
                    </div>

                    <!-- LIVE PROFILE SECTION (HIDDEN UNTIL PROFILE ELEMENT IS ADDED IN LEFT PANEL) -->
                    <div id="liveProfileSection" style="display: none; margin-bottom: 16px;">
                        <div id="livePhoneBannerContainer" style="width: 100%; aspect-ratio: 3 / 1; border-radius: 10px; overflow: hidden; margin-bottom: 12px; display: {{ ($appearance && $appearance->banner) ? 'block' : 'none' }};">
                            <img src="{{ ($appearance && $appearance->banner) ? asset('storage/' . $appearance->banner) : '' }}" id="livePhoneBannerImg" class="preview-img-full">
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
                                <img src="{{ asset('storage/' . $appearance->profile_image) }}" id="livePhoneAvatarImg" class="preview-img-full">
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
                <span style="color: #9CA3AF; font-weight: 500;">linkan.id/</span><span class="upload-text-highlight">{{ Auth::user()->username }}</span>
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
