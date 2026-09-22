<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @php
        $aboutTitle = app()->getLocale() == 'id'
            ? 'Tentang Kami - Linkan.id | Platform Ekonomi Kreator Indonesia'
            : 'About Us - Linkan.id | Creator Economy Platform';
        $aboutDesc = app()->getLocale() == 'id'
            ? 'Linkan.id hadir untuk memberdayakan kreator dan pebisnis online Indonesia. Kenali visi, misi, dan fitur unggulan platform bio-link & toko digital kami.'
            : 'Linkan.id empowers creators and online businesses. Discover our vision, mission, and key features of our bio-link & digital store platform.';
        $aboutUrl = url('/about');
    @endphp

    <title>{{ $aboutTitle }}</title>
    <meta name="description" content="{{ $aboutDesc }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ $aboutUrl }}">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $aboutUrl }}">
    <meta property="og:site_name" content="Linkan.ID">
    <meta property="og:title" content="{{ $aboutTitle }}">
    <meta property="og:description" content="{{ $aboutDesc }}">
    <meta property="og:image" content="{{ asset('images/og-banner.png') }}">
    <meta property="og:locale" content="{{ app()->getLocale() == 'id' ? 'id_ID' : 'en_US' }}">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $aboutTitle }}">
    <meta name="twitter:description" content="{{ $aboutDesc }}">
    <meta name="twitter:image" content="{{ asset('images/og-banner.png') }}">

    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pages/about.css') }}">
</head>
<body>
    <!-- NAVBAR -->
    <div class="navbar-wrapper">
        <nav class="navbar-pill" aria-label="Main Navigation">
            <a href="{{ url('/') }}" class="nav-logo">
                <img src="{{ asset('images/Logo.svg') }}" alt="Linkan Logo">
            </a>
            <div class="nav-links">
                <div class="lang-toggle-pill" aria-label="Language selector">
                    <a href="{{ route('lang.switch', 'en') }}" class="lang-btn {{ App::getLocale() == 'en' ? 'active' : '' }}">EN</a>
                    <a href="{{ route('lang.switch', 'id') }}" class="lang-btn {{ App::getLocale() == 'id' ? 'active' : '' }}">ID</a>
                </div>
                <a href="{{ url('/') }}#pricing" class="nav-link scramble-link" data-value="{{ __('layout.pricing') }}">{{ __('layout.pricing') }}</a>
                <a href="{{ url('/') }}#digital-marketing" class="nav-link scramble-link" data-value="{{ __('layout.service') }}">{{ __('layout.service') }}</a>
                <a href="{{ route('FAQ') }}" class="nav-link scramble-link" data-value="{{ __('layout.faq') }}">{{ __('layout.faq') }}</a>
                <a href="{{ route('login') }}" class="nav-link scramble-link" data-value="{{ __('layout.sign_in') }}">{{ __('layout.sign_in') }}</a>
                <a href="{{ route('register') }}" class="btn-signup">{{ __('layout.sign_up_free') }}</a>
            </div>
            <button class="mobile-nav-toggle" id="mobileNavToggle" aria-label="Toggle Menu" aria-expanded="false" aria-controls="mobileNavOverlay">
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
            </button>
        </nav>
    </div>

    <div class="mobile-nav-overlay" id="mobileNavOverlay" aria-hidden="true">
        <nav class="mobile-nav-menu" aria-label="Mobile Navigation">
            <div class="lang-toggle-pill mobile-lang-toggle" aria-label="Language selector">
                <a href="{{ route('lang.switch', 'en') }}" class="lang-btn {{ App::getLocale() == 'en' ? 'active' : '' }}">EN</a>
                <a href="{{ route('lang.switch', 'id') }}" class="lang-btn {{ App::getLocale() == 'id' ? 'active' : '' }}">ID</a>
            </div>
            <a href="{{ url('/') }}#pricing" class="mobile-nav-link scramble-link" data-value="{{ __('layout.pricing') }}">{{ __('layout.pricing') }}</a>
            <a href="{{ url('/') }}#digital-marketing" class="mobile-nav-link scramble-link" data-value="{{ __('layout.service') }}">{{ __('layout.service') }}</a>
            <a href="{{ route('FAQ') }}" class="mobile-nav-link scramble-link" data-value="{{ __('layout.faq') }}">{{ __('layout.faq') }}</a>
            <a href="{{ route('login') }}" class="mobile-nav-link scramble-link" data-value="{{ __('layout.sign_in') }}">{{ __('layout.sign_in') }}</a>
            <a href="{{ route('register') }}" class="mobile-btn-signup">{{ __('layout.sign_up_free') }}</a>
        </nav>
    </div>

    <!-- ABOUT CONTENT CONTAINER -->
    <div class="about-container">
        <div class="about-header">
            <h1>{{ __('public.about_header_title') }}</h1>
            <p class="subtitle">{{ __('public.about_header_subtitle') }}</p>
        </div>

        <div class="about-card">
            <h2>{{ __('public.about_vision_title') }}</h2>
            <p>{{ __('public.about_vision_desc') }}</p>
        </div>

        <div class="about-card">
            <h2>{{ __('public.about_mission_title') }}</h2>
            <ul>
                <li>{{ __('public.about_mission_1') }}</li>
                <li>{{ __('public.about_mission_2') }}</li>
                <li>{{ __('public.about_mission_3') }}</li>
                <li>{{ __('public.about_mission_4') }}</li>
            </ul>
        </div>
    </div>

    <!-- FITUR UTAMA SECTION -->
    <div class="features-section">
        <h2 class="features-title">{{ __('public.about_features_title') }}</h2>
        <div class="features-grid">
            <!-- Feature 1 -->
            <div class="feature-item">
                <img src="{{ asset('images/icon/Vector.png') }}" alt="Digital Product Icon" class="feature-icon">
                <h3>{{ __('public.about_feat_1_title') }}</h3>
                <p>{{ __('public.about_feat_1_desc') }}</p>
            </div>

            <!-- Feature 2 -->
            <div class="feature-item">
                <img src="{{ asset('images/icon/game-icons_graduate-cap.png') }}" alt="Online Course Icon" class="feature-icon">
                <h3>{{ __('public.about_feat_2_title') }}</h3>
                <p>{{ __('public.about_feat_2_desc') }}</p>
            </div>

            <!-- Feature 3 -->
            <div class="feature-item">
                <img src="{{ asset('images/icon/mdi_donation.png') }}" alt="Donations Icon" class="feature-icon">
                <h3>{{ __('public.about_feat_3_title') }}</h3>
                <p>{{ __('public.about_feat_3_desc') }}</p>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <div class="footer-wrapper">
        <div class="footer-pill">
            <a href="{{ url('/') }}" class="footer-logo">
                <img src="{{ asset('images/Logo.svg') }}" alt="Linkan Logo">
            </a>
            <div class="footer-links">
                <a href="{{ route('about') }}" class="footer-link scramble-link" data-value="{{ __('layout.about_us') }}">{{ __('layout.about_us') }}</a>
                <a href="{{ route('contact.form') }}" class="footer-link scramble-link" data-value="{{ __('layout.contact_us') }}">{{ __('layout.contact_us') }}</a>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/pages/about.js') }}"></script>
</body>
</html>
