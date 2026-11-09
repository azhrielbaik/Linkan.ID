@props([
    'title' => null,
    'description' => null,
    'canonical' => null,
    'noindex' => false,
    'ogType' => 'website',
    'ogImage' => null,
    'locales' => [], // ['id' => url, 'en' => url, 'x-default' => url]
    'jsonLd' => null, // Array or string
])

@php
    $currentLocale = app()->getLocale();
    $rawTitle = strip_tags($title ?: __('public.meta_title'));
    $cleanTitle = trim(preg_replace('/\s+/', ' ', $rawTitle));
    if (mb_strlen($cleanTitle) > 60) {
        $cleanTitle = mb_substr($cleanTitle, 0, 57) . '...';
    }

    $rawDesc = strip_tags($description ?: __('public.meta_desc'));
    $cleanDesc = trim(preg_replace('/\s+/', ' ', $rawDesc));
    if (mb_strlen($cleanDesc) < 140) {
        $extra = $currentLocale === 'en'
            ? ' Explore official links, exclusive digital content, and verified creator products safely on Linkan.id platform.'
            : ' Temukan kemudahan akses tautan resmi, produk digital berkualitas, dan interaksi langsung dengan kreator di platform Linkan.id.';
        $cleanDesc = trim($cleanDesc . $extra);
        if (mb_strlen($cleanDesc) < 140) {
            $secondExtra = $currentLocale === 'en'
                ? ' Connect with verified creators across Indonesia today.'
                : ' Solusi praktis ekonomi kreator Indonesia terlengkap.';
            $cleanDesc = trim($cleanDesc . $secondExtra);
        }
    }
    if (mb_strlen($cleanDesc) > 160) {
        $cleanDesc = mb_substr($cleanDesc, 0, 157) . '...';
    }

    $canonicalUrl = $canonical ?: url()->current();
    // Ensure absolute URL with scheme and host
    if (!Str::startsWith($canonicalUrl, ['http://', 'https://'])) {
        $canonicalUrl = url($canonicalUrl);
    }

    $ogImageUrl = $ogImage ?: asset('images/og-banner.png');
    if (!Str::startsWith($ogImageUrl, ['http://', 'https://'])) {
        $ogImageUrl = url($ogImageUrl);
    }

    $ogLocale = $currentLocale === 'en' ? 'en_US' : 'id_ID';
    $ogAltLocale = $currentLocale === 'en' ? 'id_ID' : 'en_US';

    // Auto-detect alternate locale URLs if not explicitly provided
    if (empty($locales) && !$noindex) {
        $currentPath = request()->path(); // e.g. "id/about" or "en"
        $segments = explode('/', ltrim($currentPath, '/'));
        if (isset($segments[0]) && in_array($segments[0], ['id', 'en'])) {
            $subpath = implode('/', array_slice($segments, 1));
            $idPath = $subpath ? "id/{$subpath}" : 'id';
            $enPath = $subpath ? "en/{$subpath}" : 'en';
            $locales = [
                'id' => url($idPath),
                'en' => url($enPath),
                'x-default' => url($idPath),
            ];
        }
    }
@endphp

    <title>{{ $cleanTitle }}</title>
    <meta name="description" content="{{ $cleanDesc }}">

@if($noindex)
    <meta name="robots" content="noindex, nofollow">
@else
    <meta name="robots" content="index, follow, max-image-preview:large">
    <link rel="canonical" href="{{ $canonicalUrl }}">

    @if(!empty($locales))
        @foreach($locales as $lang => $href)
    <link rel="alternate" hreflang="{{ $lang }}" href="{{ $href }}">
        @endforeach
    @endif
@endif

    <!-- Open Graph / Social Media -->
    <meta property="og:type" content="{{ $ogType }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:site_name" content="Linkan.ID">
    <meta property="og:title" content="{{ $cleanTitle }}">
    <meta property="og:description" content="{{ $cleanDesc }}">
    <meta property="og:image" content="{{ $ogImageUrl }}">
    <meta property="og:locale" content="{{ $ogLocale }}">
    <meta property="og:locale:alternate" content="{{ $ogAltLocale }}">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@LinkanID">
    <meta name="twitter:title" content="{{ $cleanTitle }}">
    <meta name="twitter:description" content="{{ $cleanDesc }}">
    <meta name="twitter:image" content="{{ $ogImageUrl }}">

@if(!empty($jsonLd))
    @if(is_array($jsonLd))
        @if(isset($jsonLd[0]) && is_array($jsonLd[0]))
            @foreach($jsonLd as $schemaItem)
    <script type="application/ld+json">
    {!! json_encode($schemaItem, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>
            @endforeach
        @else
    <script type="application/ld+json">
    {!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>
        @endif
    @elseif(is_string($jsonLd))
    <script type="application/ld+json">
    {!! $jsonLd !!}
    </script>
    @endif
@endif
