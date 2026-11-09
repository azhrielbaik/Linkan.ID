<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @php
        $aboutJsonLd = [
            '@context' => 'https://schema.org',
            '@type' => 'AboutPage',
            'name' => __('public.seo_title_about'),
            'description' => __('public.seo_desc_about'),
            'url' => url('/' . app()->getLocale() . '/about'),
            'mainEntity' => [
                '@type' => 'Organization',
                'name' => 'Linkan.ID',
                'url' => url('/' . app()->getLocale()),
                'logo' => asset('images/Logo.png'),
            ],
        ];
    @endphp

    <x-seo.meta
        :title="__('public.seo_title_about')"
        :description="__('public.seo_desc_about')"
        :canonical="url('/' . app()->getLocale() . '/about')"
        :locales="['id' => url('/id/about'), 'en' => url('/en/about'), 'x-default' => url('/id/about')]"
        :jsonLd="$aboutJsonLd"
    />

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
                <img src="{{ asset('images/Logo.svg') }}" alt="Linkan Logo" width="125" height="35">
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
                <img src="{{ asset('images/icon/Vector.png') }}" alt="Digital Product Icon" class="feature-icon" width="48" height="48" loading="lazy">
                <h3>{{ __('public.about_feat_1_title') }}</h3>
                <p>{{ __('public.about_feat_1_desc') }}</p>
            </div>

            <!-- Feature 2 -->
            <div class="feature-item">
                <img src="{{ asset('images/icon/game-icons_graduate-cap.png') }}" alt="Online Course Icon" class="feature-icon" width="48" height="48" loading="lazy">
                <h3>{{ __('public.about_feat_2_title') }}</h3>
                <p>{{ __('public.about_feat_2_desc') }}</p>
            </div>

            <!-- Feature 3 -->
            <div class="feature-item">
                <img src="{{ asset('images/icon/mdi_donation.png') }}" alt="Donations Icon" class="feature-icon" width="48" height="48" loading="lazy">
                <h3>{{ __('public.about_feat_3_title') }}</h3>
                <p>{{ __('public.about_feat_3_desc') }}</p>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <div class="footer-wrapper">
        <div class="footer-pill">
            <a href="{{ url('/') }}" class="footer-logo">
                <img src="{{ asset('images/Logo.svg') }}" alt="Linkan Logo" width="125" height="35" loading="lazy">
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
