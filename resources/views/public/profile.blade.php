<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ strip_tags($appearance->name ?? $user->name) }} | Linkan.id</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @php
        $shapeRadius = '50%';
        if (isset($appearance->profile_shape)) {
            if ($appearance->profile_shape === 'rounded') $shapeRadius = '14px';
            if ($appearance->profile_shape === 'square') $shapeRadius = '0px';
        }

        $blockRadius = '14px';
        if (isset($appearance->block_shape)) {
            if ($appearance->block_shape === 'sharp') $blockRadius = '0px';
            if ($appearance->block_shape === 'pill') $blockRadius = '9999px';
        }
    @endphp
    <style>
        /* Force font face to override parent elements */
        font[face="Plus Jakarta Sans"] { font-family: 'Plus Jakarta Sans', sans-serif !important; }
        font[face="Arial"] { font-family: 'Arial', sans-serif !important; }
        font[face="Times New Roman"] { font-family: 'Times New Roman', serif !important; }
        font[face="Courier New"] { font-family: 'Courier New', monospace !important; }
        font[face="Georgia"] { font-family: 'Georgia', serif !important; }
        font[face="Verdana"] { font-family: 'Verdana', sans-serif !important; }
        body {
            @if($appearance && $appearance->background_type === 'image')
                background-image: url('{{ asset('images/background/' . $appearance->background_color) }}');
                background-color: #f8f9fa;
            @elseif($appearance && $appearance->background_type === 'color')
                background-image: none;
                background-color: {{ $appearance->background_color }};
            @else
                background-color: #f8f9fa;
                background-image: url('{{ $appearance && $appearance->background_color ? asset('images/background/' . $appearance->background_color) : '' }}');
            @endif
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }

        .content-wrapper {
            width: 100%;
            max-width: 480px;
            background: transparent;
            padding: 0 5px 40px 5px !important;
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            align-content: flex-start;
            min-height: 100vh;
        }
        .content-wrapper > div {
            width: 100%;
            flex: 0 0 100%;
        }
        .content-wrapper > .dp-wrapper {
            width: 50%;
            flex: 0 0 50%;
        }

        @media (max-width: 768px) {
            .content-wrapper {
                max-width: 100%;
            }
        }

        .preview-banner {
            width: 100%;
            aspect-ratio: 3 / 1;
            background: #ddd;
            overflow: hidden;
            border-bottom: 2px solid rgba(0,0,0,0.05);
        }

        .preview-banner img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .preview-profile {
            width: 96px;
            height: 96px;
            border-radius: {{ $shapeRadius }};
            background: #ddd;
            margin: -48px auto 15px; /* Negative top margin to overlap banner */
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border: 4px solid #ffffff;
            position: relative;
            z-index: 2;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .preview-profile img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* ─── LIVE PROFILE LAYOUTS ─── */
        .live-profile-section {
            width: 100%;
            display: flex;
            flex-direction: column;
            margin-bottom: 16px;
        }

        /* LAYOUT: TITLE TOP (Standard) */
        /* Menggunakan fallback default style (banner lurus & avatar overlap) */

        /* LAYOUT: CLASSIC */
        .live-profile-section[data-profile-layout="classic"] .preview-banner,
        .live-profile-section:not([data-profile-layout]) .preview-banner {
            aspect-ratio: 4 / 3; /* Banner lebih tinggi sesuai screenshot */
            -webkit-mask-image: radial-gradient(ellipse 85% 55px at 50% 100%, transparent 99%, black 100%);
            mask-image:radial-gradient(ellipse 90% 90px at 49% 100%, transparent 99%, black 100%);
            border-bottom: none;
            border-radius: 0; /* Let edge bleed or rounded based on content-wrapper */
        }
        .live-profile-section[data-profile-layout="classic"] .preview-profile,
        .live-profile-section:not([data-profile-layout]) .preview-profile {
            width: 100px;
            height: 100px;
            margin: -150px auto 16px; /* Tarik ke atas agar pas di lengkungan */
            border-width: 5px;
        }

        /* LAYOUT: SIDE PANEL (Left Aligned Standard) */
        .live-profile-section[data-profile-layout="side"] {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }
        .live-profile-section[data-profile-layout="side"] .preview-banner {
            width: 100%;
            aspect-ratio: 3 / 1;
            border-bottom: none;
            border-radius: 0;
            -webkit-mask-image: none;
            mask-image: none;
        }
        .live-profile-section[data-profile-layout="side"] .preview-profile {
            width: 100px;
            height: 100px;
            margin: -45px 0 10px 20px;
            border-width: 3px;
        }
        .live-profile-section[data-profile-layout="side"] .preview-name {
            text-align: left;
            padding: 0 20px;
            align-self: flex-start;
        }
        .live-profile-section[data-profile-layout="side"] .preview-bio {
            text-align: left;
            padding: 0 20px;
            align-self: flex-start;
        }
        .live-profile-section[data-profile-layout="side"] .preview-social-links {
            justify-content: flex-start;
            padding: 0 20px;
            align-self: flex-start;
        }


        .preview-name {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
            text-align: center;
            color: {{ $appearance->theme_color ?? '#FF9040' }};
        }

        .preview-bio {
            font-size: 14px;
            color: {{ $appearance->theme_color ?? '#FF9040' }};
            text-align: center;
            margin-bottom: 15px;
            padding: 0 20px;
            line-height: 1.4;
        }

        .preview-social-links {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .preview-social-links a {
            color: {{ $appearance->theme_color ?? '#FF9040' }};
            font-size: 20px;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .preview-social-links a:hover {
            opacity: 0.8;
        }

       .preview-products {
            width: 100%;
            padding: 10px 20px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            gap: 10px;
            align-items: center;
        }
.product-info {
    flex: 1;
    overflow: hidden; /* jika teks panjang */
}
      .preview-product-item {
    background: white;
    border-radius: 8px;
    padding: 10px;
    display: flex;
    align-items: center;
    gap: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    justify-content: space-between;
    width: 100%; /* Pastikan item mengambil ruang penuh */
}


        .preview-product-image {
            width: 40px;
            height: 40px;
            background: #FFE5D3;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
        }

        .preview-product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .preview-product-title {
            font-size: 14px;
            color: #333;
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .preview-product-button {
            background: {{ $appearance->theme_color ?? '#FF9040' }};
            color: white;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 12px;
            border: none;
            cursor: pointer;
            flex-shrink: 0;
            text-decoration: none;
            transition: background 0.3s ease;
            margin-left: auto; /* penting agar tombol terdorong ke kanan */
        }
    </style>
</head>
<body>
    <div class="content-wrapper">
        @php
            $blocksOrder = [];
            if ($appearance && $appearance->blocks_order) {
                $blocksOrder = explode(',', $appearance->blocks_order);
            } else {
                $blocksOrder = ['profile'];
            }
            
            // Append missing elements to ensure they always render even if blocks_order is out of sync
            if (isset($imageElements)) {
                foreach($imageElements as $el) {
                    $id = 'image_' . $el->id;
                    if (!in_array($id, $blocksOrder)) $blocksOrder[] = $id;
                }
            }
            if (isset($dividerElements)) {
                foreach($dividerElements as $el) {
                    $id = 'divider_' . $el->id;
                    if (!in_array($id, $blocksOrder)) $blocksOrder[] = $id;
                }
            }
            if (isset($textElements)) {
                foreach($textElements as $el) {
                    $id = 'text_' . $el->id;
                    if (!in_array($id, $blocksOrder)) $blocksOrder[] = $id;
                }
            }
            if (isset($videoElements)) {
                foreach($videoElements as $el) {
                    $id = 'video_' . $el->id;
                    if (!in_array($id, $blocksOrder)) $blocksOrder[] = $id;
                }
            }
            if (isset($socialMediaElements)) {
                foreach($socialMediaElements as $el) {
                    $id = 'social_' . $el->id;
                    if (!in_array($id, $blocksOrder)) $blocksOrder[] = $id;
                }
            }
            if (isset($products)) {
                foreach($products as $el) {
                    $id = 'digitalproduct_' . $el->id;
                    if (!in_array($id, $blocksOrder)) $blocksOrder[] = $id;
                }
            }
        @endphp

        @foreach($blocksOrder as $blockId)
            @if($blockId === 'profile')
                <div class="live-profile-section" data-profile-layout="{{ $appearance->profile_layout ?? 'classic' }}">
                    @if($appearance && $appearance->banner)
                        <div class="preview-banner">
                            <img src="{{ asset('storage/' . $appearance->banner) }}" alt="Banner">
                        </div>
                    @else
                        <div class="preview-banner"></div>
                    @endif

                    <div class="preview-profile">
                        @if($appearance && $appearance->profile_image)
                            <img src="{{ asset('storage/' . $appearance->profile_image) }}" alt="Profile Image">
                        @else
                            <i class="fas fa-user"></i>
                        @endif
                    </div>

                    <div class="preview-name">{!! $appearance->name ?? $user->name !!}</div>
                    <div class="preview-bio">{!! $appearance->bio ?? '' !!}</div>

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

            @elseif(str_starts_with($blockId, 'image_'))
                @php
                    $elId = str_replace('image_', '', $blockId);
                    $imageEl = isset($imageElements) ? $imageElements->firstWhere('id', $elId) : null;
                @endphp
                @if($imageEl && $imageEl->image_path)
                    <div style="width: 100%; padding: 0 20px; box-sizing: border-box;">
                    <div style="margin-bottom: 12px; border-radius: {{ $blockRadius }}; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); width: 100%;">
                        @if(!empty($imageEl->link_url))
                            <a href="{{ $imageEl->link_url }}" target="_blank" style="display: block; width: 100%; text-decoration: none;">
                        @else
                            <a style="display: block; width: 100%; text-decoration: none; pointer-events: none; cursor: default;">
                        @endif
                            <img src="{{ asset('storage/' . $imageEl->image_path) }}" style="width: 100%; display: block; object-fit: cover;">
                        </a>
                    </div>
                    </div>
                @endif
            @elseif(str_starts_with($blockId, 'divider_'))
                @php
                    $elId = str_replace('divider_', '', $blockId);
                    $dividerEl = isset($dividerElements) ? $dividerElements->firstWhere('id', $elId) : null;
                @endphp
                @if($dividerEl)
                    @php 
                        $padding = $dividerEl->type === 'line' ? ($dividerEl->size / 2) . 'px 0' : '0';
                        $height = $dividerEl->type === 'line' ? '0' : $dividerEl->size . 'px';
                        $border = $dividerEl->type === 'line' ? '2px solid #cbd5e1' : 'none';
                    @endphp
                    <div style="width: 100%; padding: 0 20px; box-sizing: border-box;">
                    <div style="width: 100%; padding: {{ $padding }};">
                        <div style="width: 100%; border-top: {{ $border }}; height: {{ $height }};"></div>
                    </div>
                    </div>
                @endif
            @elseif(str_starts_with($blockId, 'text_'))
                @php
                    $elId = str_replace('text_', '', $blockId);
                    $textEl = isset($textElements) ? $textElements->firstWhere('id', $elId) : null;
                @endphp
                @if($textEl)
                    <div style="width: 100%; padding: 0 20px; box-sizing: border-box;">
                    <div style="width: 100%; word-break: break-word; color: #1e293b; font-size: 16px; margin: 15px 0; border-radius: {{ $blockRadius }};">
                        {!! $textEl->content !!}
                    </div>
                    </div>
                @endif
            @elseif(str_starts_with($blockId, 'video_'))
                @php
                    $elId = str_replace('video_', '', $blockId);
                    $videoEl = isset($videoElements) ? $videoElements->firstWhere('id', $elId) : null;
                @endphp
                @if($videoEl && $videoEl->video_url)
                    @php
                        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?|shorts)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $videoEl->video_url, $match);
                        $videoId = $match[1] ?? '';
                        $autoplay = $videoEl->is_autoplay ? '&autoplay=1&mute=1' : '';
                        $embedUrl = $videoId ? "https://www.youtube.com/embed/{$videoId}?rel=0{$autoplay}" : '';
                    @endphp
                    @if($embedUrl)
                        <div style="width: 100%; padding: 0 20px; box-sizing: border-box;">
                            <div style="margin-bottom: 12px; width: 100%; position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: {{ $blockRadius }}; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                                <iframe src="{{ $embedUrl }}" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        </div>
                    @endif
                @endif
            @elseif(str_starts_with($blockId, 'social_'))
                @php
                    $elId = str_replace('social_', '', $blockId);
                    $socialEl = isset($socialMediaElements) ? $socialMediaElements->firstWhere('id', $elId) : null;
                @endphp
                @if($socialEl)
                    @php
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
                    <div style="display: flex; justify-content: center; gap: 12px; padding: 10px 0; margin-bottom: 12px; width: 100%; box-sizing: border-box; border-radius: {{ $blockRadius }};">
                        @foreach($platforms as $plat => $url)
                            @if(!empty($url) && isset($availableIcons[$plat]))
                                <a href="{{ $url }}" target="_blank" style="display: inline-flex; justify-content: center; align-items: center; background-color: #111827; color: white; width: 45px; height: 45px; border-radius: 50%; text-decoration: none; transition: all 0.2s; margin: 0 6px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);" onmouseover="this.style.transform='translateY(-3px) scale(1.1)';" onmouseout="this.style.transform='translateY(0) scale(1)';">
                                    <i class="{{ $availableIcons[$plat]['icon'] }}" style="font-size: 24px;"></i>
                                </a>
                            @endif
                        @endforeach
                    </div>
                @endif
            @elseif(str_starts_with($blockId, 'digitalproduct_'))
                @php
                    $elId = str_replace('digitalproduct_', '', $blockId);
                    $digitalProduct = isset($products) ? $products->firstWhere('id', $elId) : null;
                @endphp
                @if($digitalProduct && ($digitalProduct->is_active ?? true))
                    @php
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
                    <div class="dp-wrapper" style="width: 50%; padding: 0 5px; box-sizing: border-box;">
                        <x-microsite.digital-product-view :product="$productData" :media="$media" />
                    </div>
                @endif
            @endif
        @endforeach
    </div>
</body>
</html>
