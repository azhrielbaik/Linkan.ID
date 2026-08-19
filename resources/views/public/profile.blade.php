<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ strip_tags($appearance->name ?? $user->name) }} | Linkan.id</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @php
        $shapeRadius = '50%';
        if (isset($appearance->profile_shape)) {
            if ($appearance->profile_shape === 'rounded') $shapeRadius = '14px';
            if ($appearance->profile_shape === 'square') $shapeRadius = '0px';
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
            background: #f8f9fa;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            padding: 30px;
            min-height: 100vh;
            margin: 0;
        }

        .content-wrapper {
            width: 100%;
            max-width: 400px;
            background: white;
            border-radius: 20px;
            padding: 20px 20px !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            background-image: url('{{ $appearance && $appearance->background_color ? asset('images/background/' . $appearance->background_color) : '' }}');
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column; /* Membuat semua elemen tampil vertikal */
            align-items: center; /* Memastikan elemen di tengah */
        }

        @media (max-width: 768px) {
            .content-wrapper {
                padding: 20px 40px !important;
            }
        }

        .preview-banner {
            width: 100%;
            aspect-ratio: 3 / 1;
            background: #ddd;
            border-radius: 10px;
            margin-bottom: 20px;
            overflow: hidden;
        }

        .preview-banner img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .preview-profile {
            width: 80px;
            height: 80px;
            border-radius: {{ $shapeRadius }};
            background: #ddd;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .preview-profile img {
            width: 100%;
            height: 100%;
            object-fit: cover;
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
    padding: 10px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    align-items: center; /* Memastikan produk berada di tengah */
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
                if (isset($imageElements)) {
                    foreach($imageElements as $el) {
                        $blocksOrder[] = 'image_' . $el->id;
                    }
                }
            }
        @endphp

        @foreach($blocksOrder as $blockId)
            @if($blockId === 'profile')
                @if($appearance && $appearance->banner)
                    <div class="preview-banner">
                        <img src="{{ asset('storage/' . $appearance->banner) }}" alt="Banner">
                    </div>
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
            @elseif(str_starts_with($blockId, 'image_'))
                @php
                    $elId = str_replace('image_', '', $blockId);
                    $imageEl = isset($imageElements) ? $imageElements->firstWhere('id', $elId) : null;
                @endphp
                @if($imageEl && $imageEl->image_path)
                    <div style="margin-bottom: 12px; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); width: 100%;">
                        <a href="{{ $imageEl->link_url ?? '#' }}" target="_blank" style="display: block; width: 100%; text-decoration: none;">
                            <img src="{{ asset('storage/' . $imageEl->image_path) }}" style="width: 100%; display: block; object-fit: cover;">
                        </a>
                    </div>
                @endif
            @endif
        @endforeach
    </div>
</body>
</html>
