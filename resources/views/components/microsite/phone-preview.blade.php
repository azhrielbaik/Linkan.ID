@props(['appearance', 'imageElements', 'dividerElements', 'textElements', 'videoElements', 'socialMediaElements', 'digitalProducts'])

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
                    <div class="mockup-phone border-[#ff8938]">
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
                            <div class="phone-content" id="phonePreviewContent" style="
                                @if($appearance && $appearance->background_type === 'image')
                                    background-image: url('{{ asset('images/background/' . $appearance->background_color) }}');
                                    background-color: transparent;
                                @elseif($appearance && $appearance->background_type === 'color')
                                    background-image: none;
                                    background-color: {{ $appearance->background_color }};
                                @else
                                    background-image: url('{{ $appearance && $appearance->background_color ? asset('images/background/' . $appearance->background_color) : '' }}');
                                    background-color: #ffffff;
                                @endif
                            ">
                                <style>
                                    #phonePreviewContent {
                                        display: flex;
                                        flex-wrap: wrap;
                                        align-content: flex-start;
                                    }
                                    #phonePreviewContent > div {
                                        width: 100%;
                                        flex: 0 0 100%;
                                    }
                                    #phonePreviewContent > div[data-type="DigitalProduct"] {
                                        width: 50%;
                                        flex: 0 0 50%;
                                        padding: 0 5px;
                                        box-sizing: border-box;
                                    }
                                </style>
                                
                                <!-- EMPTY STATE PLACEHOLDER (SHOWN WHEN NO ELEMENTS ARE ADDED IN LEFT PANEL) -->
                                <div id="phoneEmptyState" class="phone-empty-state">
                                    <div class="empty-state-icon-wrapper">
                                        <i class="fas fa-layer-group"></i>
                                    </div>
                                    <div class="empty-state-title">Belum Ada Elemen</div>
                                    <p class="empty-state-desc">Klik "Tambah Element" di sebelah kiri untuk menambahkan profil atau komponen ke microsite ini.</p>
                                </div>

                                <!-- LIVE PROFILE SECTION (HIDDEN UNTIL PROFILE ELEMENT IS ADDED IN LEFT PANEL) -->
                                <div id="liveProfileSection" class="live-profile-section live-element-pointer js-toggle-edit-form" style="display: none;" data-type="Profile" data-force-open="true">
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

                                    <div id="livePhoneName" class="live-phone-name" style="color: {{ $appearance ? $appearance->theme_color : '#2268d2ff' }};">
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

                                @if(isset($digitalProducts))
                                    @foreach($digitalProducts as $digitalProduct)
                                        @php
                                            $elementId = 'digitalproduct_' . $digitalProduct->id;
                                            $isActive = $digitalProduct->is_active ?? true;
                                            
                                            // Format the product array for the component
                                            $mediaFiles = is_string($digitalProduct->media_files) ? json_decode($digitalProduct->media_files, true) : ($digitalProduct->media_files ?? []);
                                            $media = [];
                                            foreach($mediaFiles as $file) {
                                                if (is_array($file)) {
                                                    $media[] = [
                                                        'type' => $file['type'] ?? 'image/jpeg',
                                                        'url' => isset($file['path']) ? asset('storage/' . $file['path']) : ($file['url'] ?? '')
                                                    ];
                                                }
                                            }

                                            $productData = [
                                                'title' => $digitalProduct->title,
                                                'description' => $digitalProduct->description,
                                                'pricing' => [
                                                    'type' => $digitalProduct->pricing_type,
                                                    'fixed' => $digitalProduct->price,
                                                    'min' => $digitalProduct->price_min,
                                                    'max' => $digitalProduct->price_max,
                                                ],
                                                'quantity' => [
                                                    'min' => $digitalProduct->quantity_min ?? 1,
                                                    'max' => $digitalProduct->has_quantity_limit ? $digitalProduct->quantity : null,
                                                ],
                                                'schedule' => [
                                                    'enabled' => $digitalProduct->is_scheduled,
                                                    'start' => $digitalProduct->start_time,
                                                    'end' => $digitalProduct->end_time,
                                                ],
                                                'deliverable' => [
                                                    'type' => $digitalProduct->deliverable_type ?? 'external',
                                                    'url' => $digitalProduct->deliverable_type !== 'upload' ? $digitalProduct->deliverable_url : '',
                                                    'file' => $digitalProduct->deliverable_type === 'upload' ? $digitalProduct->deliverable_url : ''
                                                ]
                                            ];
                                        @endphp
                                        <div id="live_{{ $elementId }}" class="live-element-pointer" style="display: {{ $isActive ? 'block' : 'none' }};" data-type="DigitalProduct" data-target-id="{{ $elementId }}" data-force-open="true">
                                            <x-microsite.digital-product-view :product="$productData" :media="$media" />
                                        </div>
                                    @endforeach
                                @endif

                                @if(isset($imageElements))
                                    @foreach($imageElements as $imageEl)
                                        @php 
                                            $elementId = 'imageBlock_' . $imageEl->id; 
                                            $isActive = $imageEl->is_active ?? true;
                                        @endphp
                                        <div id="live_{{ $elementId }}" class="live-image-element live-element-pointer js-toggle-edit-form" style="display: {{ $isActive ? 'block' : 'none' }};" data-type="Image" data-target-id="{{ $elementId }}" data-force-open="true">
                                            <a id="liveLink_{{ $elementId }}" class="live-image-link pointer-events-none">
                                                <img id="liveImg_{{ $elementId }}" src="{{ $imageEl->image_path ? asset('storage/' . $imageEl->image_path) : '' }}" class="live-image-img">
                                            </a>
                                        </div>
                                    @endforeach
                                @endif

                                @if(isset($dividerElements))
                                    @foreach($dividerElements as $dividerEl)
                                        @php 
                                            $elementId = 'dividerBlock_' . $dividerEl->id; 
                                            $padding = $dividerEl->type === 'line' ? ($dividerEl->size / 2) . 'px 0' : '0';
                                            $height = $dividerEl->type === 'line' ? '0' : $dividerEl->size . 'px';
                                            $border = $dividerEl->type === 'line' ? '2px solid #cbd5e1' : 'none';
                                            $isActive = $dividerEl->is_active ?? true;
                                        @endphp
                                        <div id="live_{{ $elementId }}" class="microsite-live-element live-divider-wrapper js-toggle-edit-form" style="padding: {{ $padding }}; display: {{ $isActive ? 'block' : 'none' }};" data-type="Divider" data-target-id="{{ $elementId }}" data-force-open="true">
                                            <div id="liveDivider_{{ $elementId }}" class="live-divider-inner" style="border-top: {{ $border }}; height: {{ $height }};"></div>
                                        </div>
                                    @endforeach
                                @endif

                                @if(isset($textElements))
                                    @foreach($textElements as $textEl)
                                        @php 
                                            $elementId = 'textBlock_' . $textEl->id; 
                                            $isActive = $textEl->is_active ?? true;
                                        @endphp
                                        <div id="live_{{ $elementId }}" class="live-text-element js-toggle-edit-form" style="display: {{ $isActive ? 'block' : 'none' }};" data-type="Text" data-target-id="{{ $elementId }}" data-force-open="true">
                                            {!! $textEl->content !!}
                                        </div>
                                    @endforeach
                                @endif

                                @if(isset($videoElements))
                                    @foreach($videoElements as $videoEl)
                                        @php 
                                            $elementId = 'videoBlock_' . $videoEl->id; 
                                            $isActive = $videoEl->is_active ?? true;
                                        @endphp
                                        <div id="live_{{ $elementId }}" class="live-video-wrapper live-element-pointer js-toggle-edit-form" style="display: {{ $isActive ? 'block' : 'none' }};" data-type="Video" data-target-id="{{ $elementId }}" data-force-open="true">
                                            <div class="video-container" id="liveVideoContainer_{{ $elementId }}">
                                                @if($videoEl->video_url)
                                                    @php
                                                        // Extract YouTube Video ID
                                                        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?|shorts)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $videoEl->video_url, $match);
                                                        $videoId = $match[1] ?? '';
                                                        $autoplay = $videoEl->is_autoplay ? '&autoplay=1&mute=1' : '';
                                                        $embedUrl = $videoId ? "https://www.youtube.com/embed/{$videoId}?rel=0{$autoplay}" : '';
                                                    @endphp
                                                    @if($embedUrl)
                                                        <!-- pointer-events: none to allow clicking through to edit form -->
                                                        <div class="live-video-embed-wrapper">
                                                            <iframe src="{{ $embedUrl }}" class="live-video-iframe" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                                        </div>
                                                    @else
                                                        <div class="live-video-placeholder">
                                                            <i class="fab fa-youtube live-video-placeholder-icon"></i>
                                                            URL YouTube Tidak Valid
                                                        </div>
                                                    @endif
                                                @else
                                                    <div class="live-video-placeholder">
                                                        <i class="fab fa-youtube live-video-placeholder-icon"></i>
                                                        Masukkan URL YouTube
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                                @if(isset($socialMediaElements))
                                    @foreach($socialMediaElements as $socialEl)
                                        @php 
                                            $elementId = 'socialBlock_' . $socialEl->id; 
                                            $isActive = $socialEl->is_active ?? true;
                                            $platforms = is_string($socialEl->platforms) ? json_decode($socialEl->platforms, true) : ($socialEl->platforms ?? []);
                                            
                                            $availableIcons = [
                                                'linkedin' => ['icon' => 'fab fa-linkedin', 'color' => '#0077b5'],
                                                'reddit' => ['icon' => 'fab fa-reddit', 'color' => '#FF4500'],
                                                'instagram' => ['icon' => 'fab fa-instagram', 'color' => '#E1306C'],
                                                'facebook' => ['icon' => 'fab fa-facebook', 'color' => '#1877F2'],
                                                'youtube' => ['icon' => 'fab fa-youtube', 'color' => '#FF0000'],
                                                'whatsapp' => ['icon' => 'fab fa-whatsapp', 'color' => '#25D366'],
                                                'telegram' => ['icon' => 'fab fa-telegram', 'color' => '#0088cc'],
                                                'tiktok' => ['icon' => 'fab fa-tiktok', 'color' => '#000000'],
                                                'twitter' => ['icon' => 'fab fa-x-twitter', 'color' => '#000000'],
                                                'email' => ['icon' => 'fas fa-envelope', 'color' => '#ea4335'],
                                            ];
                                        @endphp
                                        <div id="live_{{ $elementId }}" class="microsite-live-element live-social-wrapper js-toggle-edit-form" style="display: {{ $isActive ? 'block' : 'none' }};" data-type="Social" data-target-id="{{ $elementId }}" data-force-open="true">
                                            <div id="liveSocialContainer_{{ $elementId }}" class="live-social-container live-social-container-styled">
                                                @foreach($platforms as $plat => $url)
                                                    @if(!empty($url) && isset($availableIcons[$plat]))
                                                        <a href="{{ $url }}" target="_blank" class="live-social-icon-btn">
                                                            <i class="{{ $availableIcons[$plat]['icon'] }}" class="live-social-icon-inner"></i>
                                                        </a>
                                                    @endif
                                                @endforeach
                                            </div>
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
                        <div class="ssl-secure-badge" title="Akses Aman HTTPS (SSL Active)">
                            <i class="fas fa-lock"></i>
                        </div>

                        <!-- CENTER: ELEGANT VERTICAL DOMAIN PATH -->
                        <div class="vertical-domain-path">
                            <span class="domain-prefix">linkan.id/</span><span class="domain-username">{{ Auth::user()->username }}</span>
                        </div>

                        <!-- BOTTOM: VERTICAL ACTION BUTTON STACK -->
                        <div class="vertical-action-stack">
                            <!-- Copy Button -->
                            <button class="js-copy-url btn-vertical-action btn-copy" type="button" data-url="{{ url('/linkan.id/' . Auth::user()->username) }}" title="Salin Tautan Microsite">
                                <i class="fas fa-copy"></i>
                            </button>
                            
                            <!-- Open External Link Button -->
                            <a href="{{ url('/linkan.id/' . Auth::user()->username) }}" target="_blank" class="btn-vertical-action btn-external" title="Buka Microsite di Tab Baru">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            </div>
