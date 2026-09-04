<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('public.faq_title', ['default' => 'FAQ - Frequently Asked Questions | Linkan.id']) }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#5A5BF1',
                            50: '#F5F5FE',
                            100: '#ECECFD',
                            200: '#D4D5FB',
                            300: '#B0B1F8',
                            400: '#8485F4',
                            500: '#5A5BF1',
                            600: '#4C4DF0',
                            700: '#3D3EDB',
                            800: '#3233B2',
                            900: '#2C2C8D',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: #5A5BF1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 24px 20px;
            overflow-x: hidden;
            color: #FFFFFF;
            position: relative;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* Skip to Content */
        .skip-to-content {
            position: absolute;
            top: -40px;
            left: 0;
            background: #000;
            color: white;
            padding: 8px 16px;
            z-index: 9999;
            transition: top 0.2s;
            border-radius: 0 0 8px 0;
            font-size: 14px;
        }
        .skip-to-content:focus {
            top: 0;
        }

        /* Language Toggle Pill */
        .lang-toggle-pill {
            display: flex;
            align-items: center;
            gap: 2px;
            padding: 3px;
            background: #F1F5F9;
            border-radius: 20px;
        }

        .lang-btn {
            padding: 6px 10px;
            border-radius: 16px;
            color: #64748B;
            font-size: 13px;
            font-weight: 700;
            transition: all 0.3s ease;
        }

        .lang-btn.active {
            background: #FFFFFF;
            color: #0F172A;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .mobile-lang-toggle {
            margin-bottom: 8px;
            transform: scale(1.1);
        }

        /* NAVBAR */
        .navbar-wrapper {
            width: 100%;
            display: flex;
            justify-content: center;
            margin-bottom: 32px;
            z-index: 40;
        }

        .navbar-pill {
            background: #FFFFFF;
            border-radius: 50px;
            padding: 8px 16px 8px 24px;
            width: 100%;
            max-width: 1040px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            white-space: nowrap;
        }

        .nav-logo {
            flex-shrink: 0;
        }

        .nav-logo img {
            height: 30px;
            width: auto;
            display: block;
        }

        .nav-links {
            display: flex;
            gap: 24px;
            align-items: center;
            flex-wrap: nowrap;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .nav-link {
            font-size: 14px;
            font-weight: 600;
            color: #475569;
            transition: color 0.2s ease;
            text-decoration: none;
            display: inline-block;
            white-space: nowrap !important;
            flex-shrink: 0;
        }

        .nav-link:hover,
        .nav-link.active,
        .scramble-link:hover,
        .scramble-link.active {
            color: #0F172A !important;
        }

        .scramble-link {
            display: inline-block;
            position: relative;
            overflow: hidden;
            line-height: 1.3em;
            vertical-align: middle;
            text-decoration: none;
            cursor: pointer;
            transition: color 0.2s ease;
            white-space: nowrap !important;
            flex-shrink: 0;
        }

        .scramble-char-box {
            display: inline-block;
            overflow: hidden;
            height: 1.3em;
            line-height: 1.3em;
            vertical-align: top;
            position: relative;
            white-space: nowrap !important;
            flex-shrink: 0;
        }

        .scramble-char-col {
            display: flex;
            flex-direction: column;
            will-change: transform;
            white-space: nowrap !important;
        }

        .scramble-char-item {
            display: block;
            height: 1.3em;
            line-height: 1.3em;
            text-align: center;
            white-space: nowrap !important;
        }

        .btn-signup {
            background: #5A5BF1;
            color: #FFFFFF;
            padding: 10px 22px;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 700;
            transition: transform 0.2s, background 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 14px rgba(90, 91, 241, 0.25);
        }

        .btn-signup:hover {
            background: #4C4DF0;
            transform: scale(1.03);
            box-shadow: 0 6px 20px rgba(90, 91, 241, 0.35);
        }

        /* MOBILE NAV TOGGLE BUTTON */
        .mobile-nav-toggle {
            display: none;
            flex-direction: column;
            justify-content: space-between;
            width: 24px;
            height: 18px;
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 0;
            z-index: 110;
        }

        .hamburger-line {
            width: 100%;
            height: 2px;
            background-color: #334155;
            border-radius: 2px;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1),
                        opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1),
                        background-color 0.3s ease;
        }

        .mobile-nav-toggle.active .hamburger-line:nth-child(1) {
            transform: translateY(8px) rotate(45deg);
        }

        .mobile-nav-toggle.active .hamburger-line:nth-child(2) {
            opacity: 0;
        }

        .mobile-nav-toggle.active .hamburger-line:nth-child(3) {
            transform: translateY(-8px) rotate(-45deg);
        }

        /* MOBILE NAVIGATION OVERLAY */
        .mobile-nav-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            z-index: 99;
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .mobile-nav-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        .mobile-nav-menu {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 28px;
            width: 80%;
            max-width: 320px;
            transform: translateY(30px);
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .mobile-nav-overlay.active .mobile-nav-menu {
            transform: translateY(0);
        }

        .mobile-nav-link {
            font-size: 20px;
            font-weight: 700;
            color: #334155;
            transition: color 0.2s, transform 0.2s;
            position: relative;
        }

        .mobile-nav-link:hover {
            color: #5A5BF1;
            transform: scale(1.05);
        }

        .mobile-btn-signup {
            background: #5A5BF1;
            color: #FFFFFF;
            padding: 14px 40px;
            border-radius: 50px;
            font-size: 18px;
            font-weight: 700;
            width: 100%;
            text-align: center;
            box-shadow: 0 10px 25px rgba(90, 91, 241, 0.3);
            transition: transform 0.2s, background 0.2s;
            margin-top: 10px;
        }

        /* =========================================================
           KNOWLEDGE BASE CARD & GOOEY ANIMATED SEARCH BAR
           ========================================================= */
        .kb-card {
            background: #FFFFFF;
            border-radius: 30px;
            width: 100%;
            max-width: 660px;
            padding: 38px 36px 36px 36px;
            text-align: center;
            box-shadow: 0 20px 50px rgba(0,0,0,0.12);
            margin-bottom: 36px;
            position: relative;
        }

        .kb-title {
            font-size: 26px;
            font-weight: 800;
            color: #0F172A;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
            line-height: 1.25;
        }

        .kb-subtitle {
            font-size: 14.5px;
            color: #475569;
            font-weight: 500;
            margin-bottom: 22px;
            line-height: 1.5;
        }

        .kb-search-outer {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            margin-top: 8px;
            position: relative;
        }

        .goo-search-container {
            filter: url(#goo-effect);
            -webkit-filter: url(#goo-effect);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        }

        /* Center the entire assembly (bar + detached blob) horizontally */
        .goo-search-container.is-expanded {
            transform: translateX(-32px);
        }

        /* State 1 (Button): Initially small rounded capsule */
        .goo-search-bar {
            position: relative;
            width: 135px;
            height: 52px;
            background: #5A5BF1;
            border-radius: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            overflow: hidden;
            transition: width 0.5s cubic-bezier(0.16, 1, 0.3, 1),
                        background-color 0.3s ease,
                        box-shadow 0.3s ease,
                        transform 0.2s ease;
            box-shadow: 0 6px 22px rgba(90, 91, 241, 0.32);
            user-select: none;
            z-index: 2;
        }

        .goo-search-bar:hover:not(.expanded) {
            background: #4E4FF0;
            transform: scale(1.04);
        }

        /* State 2 (Input): Expanded width */
        .goo-search-bar.expanded {
            width: 440px;
            max-width: 78vw;
            background: #5152EA;
            cursor: default;
            justify-content: flex-start;
            padding: 0 16px 0 22px;
            box-shadow: 0 10px 32px rgba(90, 91, 241, 0.42);
            transform: none;
        }

        /* State 1: Button label */
        .goo-btn-content {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #FFFFFF;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 0.3px;
            transition: opacity 0.25s ease, transform 0.35s ease;
            pointer-events: none;
        }

        .goo-search-bar.expanded .goo-btn-content {
            opacity: 0;
            transform: scale(0.6) translateX(-25px);
            pointer-events: none;
        }

        /* State 2: Input wrap */
        .goo-input-wrap {
            display: flex;
            align-items: center;
            width: 100%;
            height: 100%;
            opacity: 0;
            pointer-events: none;
            transform: translateX(-15px);
            transition: opacity 0.35s ease 0.15s, transform 0.45s cubic-bezier(0.16, 1, 0.3, 1) 0.15s;
        }

        .goo-search-bar.expanded .goo-input-wrap {
            opacity: 1;
            pointer-events: auto;
            transform: translateX(0);
        }

        .goo-input {
            flex: 1;
            background: transparent;
            border: none;
            outline: none !important;
            box-shadow: none !important;
            color: #FFFFFF;
            font-size: 15px;
            font-weight: 500;
            font-family: inherit;
            min-width: 0;
            height: 100%;
            padding: 0;
            margin: 0;
        }

        .goo-input:focus,
        .goo-input:focus-visible,
        .goo-search-bar:focus,
        .goo-search-bar:focus-visible,
        .goo-clear-btn:focus,
        .goo-clear-btn:focus-visible,
        .goo-search-icon-blob:focus,
        .goo-search-icon-blob:focus-visible {
            outline: none !important;
            box-shadow: none !important;
        }

        .goo-input::placeholder {
            color: rgba(255, 255, 255, 0.72);
            font-weight: 500;
        }

        .goo-clear-btn {
            background: rgba(255, 255, 255, 0.22);
            border: none;
            color: #FFFFFF;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            display: none;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            margin-right: 4px;
            flex-shrink: 0;
            transition: background 0.2s, transform 0.2s;
        }

        .goo-clear-btn:hover {
            background: rgba(255, 255, 255, 0.4);
            transform: scale(1.1);
        }

        .goo-clear-btn.visible {
            display: flex;
        }

        /* Splitting Magnifying Glass Icon (Gooey Blob on the Right) */
        .goo-search-icon-blob {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: #4648EA;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #FFFFFF;
            border: none;
            outline: none;
            cursor: pointer;
            margin-left: -52px;
            transform: translateX(0) scale(0);
            opacity: 0;
            pointer-events: none;
            transition: transform 0.5s cubic-bezier(0.34, 1.56, 0.64, 1),
                        opacity 0.3s ease,
                        background-color 0.2s ease;
            z-index: 3;
        }

        /* When expanded: Split / pop out to the right with liquid gooey detachment */
        .goo-search-container.is-expanded .goo-search-icon-blob {
            transform: translateX(64px) scale(1);
            opacity: 1;
            pointer-events: auto;
        }

        .goo-search-icon-blob:hover {
            background: #393ADB;
            transform: translateX(64px) scale(1.08);
        }

        .goo-search-icon-blob:active {
            transform: translateX(64px) scale(0.94);
        }

        @media (max-width: 600px) {
            .goo-search-container.is-expanded {
                transform: translateX(-27px);
            }
            .goo-search-bar.expanded {
                width: 310px;
                max-width: 62vw;
                height: 48px;
                padding: 0 12px 0 16px;
            }
            .goo-search-bar {
                height: 48px;
            }
            .goo-search-icon-blob {
                width: 48px;
                height: 48px;
                margin-left: -48px;
            }
            .goo-search-container.is-expanded .goo-search-icon-blob {
                transform: translateX(54px) scale(1);
            }
            .goo-search-icon-blob:hover {
                transform: translateX(54px) scale(1.06);
            }
            .goo-input {
                font-size: 13.5px;
            }
        }

        /* =========================================================
           CSS GRID ACCORDION ANIMATION (Pure CSS Grid Trick)
           ========================================================= */
        .faq-grid-content {
            display: grid;
            grid-template-rows: 0fr;
            transition: all 300ms ease-in-out;
        }

        .faq-item.is-open .faq-grid-content {
            grid-template-rows: 1fr;
        }

        /* Rotates icon Plus by 45 degrees forming an 'X' */
        .faq-icon-rotator {
            transition: transform 300ms ease-in-out;
            transform-origin: center;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .faq-item.is-open .faq-icon-rotator {
            transform: rotate(45deg);
        }

        /* Accordion item card highlight state */
        .faq-item {
            transition: border-color 300ms ease, box-shadow 300ms ease, transform 200ms ease;
        }

        .faq-item.is-open {
            border-color: rgba(90, 91, 241, 0.5);
            box-shadow: 0 15px 35px -5px rgba(0, 0, 0, 0.15);
        }

        .faq-item.is-open .faq-icon-badge {
            background-color: #5A5BF1;
            color: #FFFFFF;
        }

        /* Category tab animation */
        .faq-category-group {
            transition: opacity 300ms ease, transform 300ms ease;
        }

        /* FOOTER */
        .footer-wrapper {
            width: 100%;
            display: flex;
            justify-content: center;
            margin-top: auto;
            padding-top: 48px;
            padding-bottom: 24px;
        }

        .footer-pill {
            background: #FFFFFF;
            border-radius: 50px;
            padding: 14px 32px;
            width: 100%;
            max-width: 1000px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }

        .footer-logo img {
            height: 28px;
            width: auto;
            display: block;
        }

        .footer-links {
            display: flex;
            gap: 32px;
        }

        .footer-link {
            font-size: 14px;
            font-weight: 600;
            color: #475569;
            transition: color 0.2s;
        }

        .footer-link:hover {
            color: #5A5BF1;
        }

        @media (max-width: 900px) {
            .mobile-nav-toggle {
                display: flex;
            }
            .nav-links {
                display: none;
            }
            .navbar-pill {
                justify-content: space-between;
                padding: 10px 20px;
            }
        }

        @media (max-width: 640px) {
            .footer-pill {
                flex-direction: column;
                gap: 16px;
                padding: 16px 20px;
                border-radius: 28px;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <a href="#main-content" class="skip-to-content">Skip to content</a>

    <!-- =========================================================
         BACKGROUND GLOWING ORBS (blur-3xl with low opacity)
         ========================================================= -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden -z-10" aria-hidden="true">
        <!-- Top Central Glowing Aura -->
        <div class="absolute -top-32 left-1/2 -translate-x-1/2 w-[720px] h-[480px] bg-gradient-to-br from-white/15 via-indigo-300/20 to-purple-300/20 rounded-full blur-3xl opacity-60"></div>

        <!-- Center-Left Ambient Glow -->
        <div class="absolute top-1/3 -left-36 w-[480px] h-[480px] bg-gradient-to-tr from-cyan-300/15 via-white/10 to-indigo-300/15 rounded-full blur-3xl opacity-50"></div>

        <!-- Center-Right Ambient Glow -->
        <div class="absolute top-2/3 -right-36 w-[520px] h-[520px] bg-gradient-to-bl from-purple-300/20 via-pink-300/15 to-white/10 rounded-full blur-3xl opacity-50"></div>
    </div>

    <!-- SVG GOOEY FILTER (ID: goo-effect) -->
    <svg style="position: absolute; width: 0; height: 0; pointer-events: none;" aria-hidden="true">
        <defs>
            <filter id="goo-effect">
                <feGaussianBlur in="SourceGraphic" stdDeviation="6" result="blur" />
                <feColorMatrix in="blur" mode="matrix" values="
                    1 0 0 0 0
                    0 1 0 0 0
                    0 0 1 0 0
                    0 0 0 19 -9" result="goo" />
                <feComposite in="SourceGraphic" in2="goo" operator="atop" />
            </filter>
        </defs>
    </svg>

    <!-- NAVBAR -->
    <header class="navbar-wrapper">
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
                <a href="{{ route('FAQ') }}" class="nav-link scramble-link active" data-value="{{ __('layout.faq') }}">{{ __('layout.faq') }}</a>
                <a href="{{ route('login') }}" class="nav-link scramble-link" data-value="{{ __('layout.sign_in') }}">{{ __('layout.sign_in') }}</a>
                <a href="{{ route('register') }}" class="btn-signup">{{ __('layout.sign_up_free') }}</a>
            </div>
            <button class="mobile-nav-toggle" id="mobileNavToggle" aria-label="Toggle Menu" aria-expanded="false" aria-controls="mobileNavOverlay">
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
            </button>
        </nav>
    </header>

    <!-- MOBILE NAVIGATION OVERLAY -->
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

    <!-- MAIN CONTENT -->
    <main id="main-content" class="w-full max-w-4xl mx-auto flex flex-col items-center">

        <!-- =========================================================
             1. HEADER SECTION (KNOWLEDGE BASE CARD + GOOEY SEARCH BAR)
             - Teks subtitle bergradasi (bg-clip-text text-transparent bg-gradient-to-r)
             - Teks judul utama besar (bold)
             - Efek cahaya / glowing background elemen absolute blur-3xl
             - Gooey Animated Search Bar (2-State: Button -> Input + Detaching Blob)
             ========================================================= -->
        <div class="kb-card">
            <!-- Glow effect behind header card -->
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-80 h-36 bg-primary/20 rounded-full blur-3xl opacity-50 -z-10 pointer-events-none" aria-hidden="true"></div>

            <!-- Main Title Large & Bold -->
            <h1 class="kb-title">
                Frequently Asked Questions
            </h1>

            <!-- Descriptive Subtitle -->
            <p class="kb-subtitle">
                Temukan jawaban lengkap seputar fitur, pembayaran, pengelolaan produk digital, dan akun Linkan.id Anda
            </p>

            <!-- 2-STATE GOOEY ANIMATED SEARCH BAR -->
            <div class="kb-search-outer">
                <div class="goo-search-container" id="gooSearchContainer">
                    <!-- State 1 & State 2 Main Bar -->
                    <div class="goo-search-bar" id="gooSearchBar" role="button" tabindex="0" aria-expanded="false" aria-label="Search Knowledge Base">
                        <!-- State 1: Button Content -->
                        <div class="goo-btn-content" id="gooBtnContent">
                            <span class="goo-btn-text">Search</span>
                        </div>

                        <!-- State 2: Input Content -->
                        <div class="goo-input-wrap" id="gooInputWrap">
                            <input type="text" class="goo-input" id="kbSearchInput" placeholder="e.g. custom domain, produk digital..." aria-label="Search Knowledge Base" autocomplete="off" spellcheck="false">
                            <button type="button" class="goo-clear-btn" id="gooClearBtn" aria-label="Clear search" title="Hapus pencarian">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Splitting Magnifying Glass Icon (Gooey Blob on the Right) -->
                    <button type="button" class="goo-search-icon-blob" id="gooSearchIconBtn" aria-label="Execute search" title="Cari">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- =========================================================
             2. TABS (KATEGORI)
             - Deretan tombol kategori
             - Tab aktif: background warna primary & teks putih
             - Tab inaktif: ber-outline dengan teks abu-abu
             - Transisi Tailwind: transition-colors duration-300
             ========================================================= -->
        <section class="w-full px-4 mb-8" aria-label="Kategori Pertanyaan">
            <div class="flex flex-wrap items-center justify-center gap-2 md:gap-3" id="faqCategoryTabs" role="tablist">
                <button
                    type="button"
                    role="tab"
                    aria-selected="true"
                    data-category="all"
                    class="faq-tab-btn active px-5 py-2.5 rounded-full text-sm font-semibold transition-colors duration-300 bg-primary text-white border-2 border-white ring-2 ring-primary shadow-lg shadow-black/10 cursor-pointer">
                    Semua Kategori
                </button>
                <button
                    type="button"
                    role="tab"
                    aria-selected="false"
                    data-category="general"
                    class="faq-tab-btn px-5 py-2.5 rounded-full text-sm font-semibold transition-colors duration-300 border border-slate-200 text-slate-500 hover:text-slate-900 hover:border-slate-300 bg-white shadow-sm cursor-pointer">
                    Umum
                </button>
                <button
                    type="button"
                    role="tab"
                    aria-selected="false"
                    data-category="payment"
                    class="faq-tab-btn px-5 py-2.5 rounded-full text-sm font-semibold transition-colors duration-300 border border-slate-200 text-slate-500 hover:text-slate-900 hover:border-slate-300 bg-white shadow-sm cursor-pointer">
                    Pembayaran & Komisi
                </button>
                <button
                    type="button"
                    role="tab"
                    aria-selected="false"
                    data-category="product"
                    class="faq-tab-btn px-5 py-2.5 rounded-full text-sm font-semibold transition-colors duration-300 border border-slate-200 text-slate-500 hover:text-slate-900 hover:border-slate-300 bg-white shadow-sm cursor-pointer">
                    Produk Digital
                </button>
                <button
                    type="button"
                    role="tab"
                    aria-selected="false"
                    data-category="account"
                    class="faq-tab-btn px-5 py-2.5 rounded-full text-sm font-semibold transition-colors duration-300 border border-slate-200 text-slate-500 hover:text-slate-900 hover:border-slate-300 bg-white shadow-sm cursor-pointer">
                    Akun & Keamanan
                </button>
                <button
                    type="button"
                    role="tab"
                    aria-selected="false"
                    data-category="integration"
                    class="faq-tab-btn px-5 py-2.5 rounded-full text-sm font-semibold transition-colors duration-300 border border-slate-200 text-slate-500 hover:text-slate-900 hover:border-slate-300 bg-white shadow-sm cursor-pointer">
                    Integrasi & Fitur
                </button>
            </div>
        </section>

        <!-- =========================================================
             3. ACCORDION (FAQ ITEM)
             - Item berupa card ber-border
             - Header item berisi teks pertanyaan dan icon Plus
             - Animasi buka/tutup menggunakan trik CSS Grid (0fr -> 1fr)
             - Icon Plus berputar 45 derajat (menjadi silang 'X')
             ========================================================= -->
        <section class="w-full px-4 mb-16" aria-label="Daftar FAQ">
            <div id="faqListContainer" class="space-y-6">

                <!-- KATEGORI: UMUM -->
                <div class="faq-category-group space-y-3" data-category="general">
                    <div class="flex items-center gap-2 pb-1 border-b border-white/20">
                        <span class="text-xs font-bold uppercase tracking-wider text-indigo-200">Kategori</span>
                        <h2 class="text-sm font-bold text-white uppercase tracking-wider">Umum</h2>
                    </div>

                    <!-- Item 1.1 -->
                    <div class="faq-item border border-white/80 rounded-2xl bg-white shadow-md hover:shadow-xl transition-all" data-category="general">
                        <button type="button" class="faq-accordion-header w-full flex items-center justify-between p-5 md:p-6 text-left focus:outline-none cursor-pointer group" aria-expanded="false" onclick="event.stopPropagation(); const item = this.closest('.faq-item'); const isOpen = item.classList.contains('is-open'); item.classList.toggle('is-open', !isOpen); this.setAttribute('aria-expanded', String(!isOpen));">
                            <span class="font-bold text-slate-800 text-base md:text-lg group-hover:text-primary transition-colors duration-200 pr-4">
                                Apa itu Linkan.id ?
                            </span>
                            <span class="faq-icon-badge flex-shrink-0 w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 group-hover:bg-primary/10 group-hover:text-primary transition-all duration-300">
                                <span class="faq-icon-rotator">
                                    <x-lucide-plus class="w-4 h-4" />
                                </span>
                            </span>
                        </button>
                        <div class="faq-grid-content overflow-hidden transition-all duration-300 ease-in-out">
                            <div class="overflow-hidden">
                                <div class="px-5 pb-5 md:px-6 md:pb-6 text-slate-600 leading-relaxed text-sm md:text-base border-t border-slate-100 pt-4">
                                    Linkan.id adalah platform bio-link all-in-one yang dirancang untuk kreator konten, pebisnis online, dan profesional. Dengan Linkan.id, Anda dapat menampilkan semua tautan penting, menjual produk digital, menerima donasi, dan mengarahkan audiens dengan satu tautan simpel yang berkelas.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Item 1.2 -->
                    <div class="faq-item border border-white/80 rounded-2xl bg-white shadow-md hover:shadow-xl transition-all" data-category="general">
                        <button type="button" class="faq-accordion-header w-full flex items-center justify-between p-5 md:p-6 text-left focus:outline-none cursor-pointer group" aria-expanded="false" onclick="event.stopPropagation(); const item = this.closest('.faq-item'); const isOpen = item.classList.contains('is-open'); item.classList.toggle('is-open', !isOpen); this.setAttribute('aria-expanded', String(!isOpen));">
                                     <span class="font-bold text-slate-800 text-base md:text-lg group-hover:text-primary transition-colors duration-200 pr-4">
                                Apa saja fitur unggulan Linkan.id ?
                            </span>
                            <span class="faq-icon-badge flex-shrink-0 w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 group-hover:bg-primary/10 group-hover:text-primary transition-all duration-300">
                                <span class="faq-icon-rotator">
                                    <x-lucide-plus class="w-4 h-4" />
                                </span>
                            </span>
                        </button>
                        <div class="faq-grid-content overflow-hidden transition-all duration-300 ease-in-out">
                            <div class="overflow-hidden">
                                <div class="px-5 pb-5 md:px-6 md:pb-6 text-slate-600 leading-relaxed text-sm md:text-base border-t border-slate-100 pt-4">
                                    Fitur unggulan Linkan.id meliputi:
                                    <ul class="list-disc list-inside mt-2 space-y-1.5 text-slate-600">
                                        <li>Kustomisasi Microsite / Bio-Link yang responsif dan estetis</li>
                                        <li>Penjualan Produk Digital (Ebook, Template, Video, Kursus, Lisensi)</li>
                                        <li>Integrasi Payment Gateway otomatis (QRIS, VA, E-Wallet)</li>
                                        <li>Analisis Pengunjung & Pelacakan Konversi Real-Time</li>
                                        <li>Dukungan Custom Domain dan Pixel Pelacakan (Meta, TikTok, Google Analytics)</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Item 1.3 -->
                    <div class="faq-item border border-white/80 rounded-2xl bg-white shadow-md hover:shadow-xl transition-all" data-category="general">
                        <button type="button" class="faq-accordion-header w-full flex items-center justify-between p-5 md:p-6 text-left focus:outline-none cursor-pointer group" aria-expanded="false" onclick="event.stopPropagation(); const item = this.closest('.faq-item'); const isOpen = item.classList.contains('is-open'); item.classList.toggle('is-open', !isOpen); this.setAttribute('aria-expanded', String(!isOpen));">
                            <span class="font-bold text-slate-800 text-base md:text-lg group-hover:text-primary transition-colors duration-200 pr-4">
                                Tips memilih username yang baik di Linkan.id
                            </span>
                            <span class="faq-icon-badge flex-shrink-0 w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 group-hover:bg-primary/10 group-hover:text-primary transition-all duration-300">
                                <span class="faq-icon-rotator">
                                    <x-lucide-plus class="w-4 h-4" />
                                </span>
                            </span>
                        </button>
                        <div class="faq-grid-content overflow-hidden transition-all duration-300 ease-in-out">
                            <div class="overflow-hidden">
                                <div class="px-5 pb-5 md:px-6 md:pb-6 text-slate-600 leading-relaxed text-sm md:text-base border-t border-slate-100 pt-4">
                                    Pilihlah username yang singkat, mudah diingat, dan konsisten dengan akun media sosial utama Anda (misalnya Instagram, TikTok, atau Twitter). Hindari penggunaan karakter tanda hubung atau angka berlebihan agar audiens mudah mengetik tautan Anda.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Item 1.4 -->
                    <div class="faq-item border border-white/80 rounded-2xl bg-white shadow-md hover:shadow-xl transition-all" data-category="general">
                        <button type="button" class="faq-accordion-header w-full flex items-center justify-between p-5 md:p-6 text-left focus:outline-none cursor-pointer group" aria-expanded="false" onclick="event.stopPropagation(); const item = this.closest('.faq-item'); const isOpen = item.classList.contains('is-open'); item.classList.toggle('is-open', !isOpen); this.setAttribute('aria-expanded', String(!isOpen));">
                            <span class="font-bold text-slate-800 text-base md:text-lg group-hover:text-primary transition-colors duration-200 pr-4">
                                Apakah saya bisa menggunakan Custom Domain sendiri?
                            </span>
                            <span class="faq-icon-badge flex-shrink-0 w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 group-hover:bg-primary/10 group-hover:text-primary transition-all duration-300">
                                <span class="faq-icon-rotator">
                                    <x-lucide-plus class="w-4 h-4" />
                                </span>
                            </span>
                        </button>
                        <div class="faq-grid-content overflow-hidden transition-all duration-300 ease-in-out">
                            <div class="overflow-hidden">
                                <div class="px-5 pb-5 md:px-6 md:pb-6 text-slate-600 leading-relaxed text-sm md:text-base border-t border-slate-100 pt-4">
                                    Ya! Pengguna paket Pro/Unlimited dapat menghubungkan nama domain pribadi (misal: <code>links.namabrand.com</code>). Cukup arahkan CNAME record DNS domain Anda ke server Linkan.id sesuai panduan yang tersedia di dashboard.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KATEGORI: PEMBAYARAN & KOMISI -->
                <div class="faq-category-group space-y-3" data-category="payment">
                    <div class="flex items-center gap-2 pb-1 border-b border-white/20">
                        <span class="text-xs font-bold uppercase tracking-wider text-indigo-200">Kategori</span>
                        <h2 class="text-sm font-bold text-white uppercase tracking-wider">Pembayaran & Komisi</h2>
                    </div>

                    <!-- Item 2.1 -->
                    <div class="faq-item border border-white/80 rounded-2xl bg-white shadow-md hover:shadow-xl transition-all" data-category="payment">
                        <button type="button" class="faq-accordion-header w-full flex items-center justify-between p-5 md:p-6 text-left focus:outline-none cursor-pointer group" aria-expanded="false" onclick="event.stopPropagation(); const item = this.closest('.faq-item'); const isOpen = item.classList.contains('is-open'); item.classList.toggle('is-open', !isOpen); this.setAttribute('aria-expanded', String(!isOpen));">
                            <span class="font-bold text-slate-800 text-base md:text-lg group-hover:text-primary transition-colors duration-200 pr-4">
                                Bagaimana sistem pembayaran untuk pembeli?
                            </span>
                            <span class="faq-icon-badge flex-shrink-0 w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 group-hover:bg-primary/10 group-hover:text-primary transition-all duration-300">
                                <span class="faq-icon-rotator">
                                    <x-lucide-plus class="w-4 h-4" />
                                </span>
                            </span>
                        </button>
                        <div class="faq-grid-content overflow-hidden transition-all duration-300 ease-in-out">
                            <div class="overflow-hidden">
                                <div class="px-5 pb-5 md:px-6 md:pb-6 text-slate-600 leading-relaxed text-sm md:text-base border-t border-slate-100 pt-4">
                                    Linkan.id terhubung secara otomatis dengan payment gateway berizin resmi. Pembeli dapat membayar menggunakan QRIS (semua e-wallet dan m-banking), Virtual Account (BCA, Mandiri, BNI, BRI), serta dompet digital (GoPay, OVO, ShopeePay, DANA) dengan konfirmasi pembayaran instan 24/7.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Item 2.2 -->
                    <div class="faq-item border border-white/80 rounded-2xl bg-white shadow-md hover:shadow-xl transition-all" data-category="payment">
                        <button type="button" class="faq-accordion-header w-full flex items-center justify-between p-5 md:p-6 text-left focus:outline-none cursor-pointer group" aria-expanded="false" onclick="event.stopPropagation(); const item = this.closest('.faq-item'); const isOpen = item.classList.contains('is-open'); item.classList.toggle('is-open', !isOpen); this.setAttribute('aria-expanded', String(!isOpen));">
                            <span class="font-bold text-slate-800 text-base md:text-lg group-hover:text-primary transition-colors duration-200 pr-4">
                                Bagaimana sistem pembagian komisi di Linkan.id?
                            </span>
                            <span class="faq-icon-badge flex-shrink-0 w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 group-hover:bg-primary/10 group-hover:text-primary transition-all duration-300">
                                <span class="faq-icon-rotator">
                                    <x-lucide-plus class="w-4 h-4" />
                                </span>
                            </span>
                        </button>
                        <div class="faq-grid-content overflow-hidden transition-all duration-300 ease-in-out">
                            <div class="overflow-hidden">
                                <div class="px-5 pb-5 md:px-6 md:pb-6 text-slate-600 leading-relaxed text-sm md:text-base border-t border-slate-100 pt-4">
                                    Admin Platform hanya mengenakan komisi platform transparan sebesar 5% dari setiap transaksi produk digital yang sukses. Biaya ini dipotong secara otomatis saat penarikan saldo, tanpa ada biaya langganan tersembunyi.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Item 2.3 -->
                    <div class="faq-item border border-white/80 rounded-2xl bg-white shadow-md hover:shadow-xl transition-all" data-category="payment">
                        <button type="button" class="faq-accordion-header w-full flex items-center justify-between p-5 md:p-6 text-left focus:outline-none cursor-pointer group" aria-expanded="false" onclick="event.stopPropagation(); const item = this.closest('.faq-item'); const isOpen = item.classList.contains('is-open'); item.classList.toggle('is-open', !isOpen); this.setAttribute('aria-expanded', String(!isOpen));">
                            <span class="font-bold text-slate-800 text-base md:text-lg group-hover:text-primary transition-colors duration-200 pr-4">
                                Bagaimana cara menarik dana (payout) ke rekening saya?
                            </span>
                            <span class="faq-icon-badge flex-shrink-0 w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 group-hover:bg-primary/10 group-hover:text-primary transition-all duration-300">
                                <span class="faq-icon-rotator">
                                    <x-lucide-plus class="w-4 h-4" />
                                </span>
                            </span>
                        </button>
                        <div class="faq-grid-content overflow-hidden transition-all duration-300 ease-in-out">
                            <div class="overflow-hidden">
                                <div class="px-5 pb-5 md:px-6 md:pb-6 text-slate-600 leading-relaxed text-sm md:text-base border-t border-slate-100 pt-4">
                                    Buka menu <strong>Finansial > Penarikan Saldo</strong> di dashboard Anda. Daftarkan nomor rekening bank atau e-wallet Anda yang valid. Setelah diverifikasi, Anda dapat mengajukan permintaan payout yang diproses dalam 1x24 jam kerja.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Item 2.4 -->
                    <div class="faq-item border border-white/80 rounded-2xl bg-white shadow-md hover:shadow-xl transition-all" data-category="payment">
                        <button type="button" class="faq-accordion-header w-full flex items-center justify-between p-5 md:p-6 text-left focus:outline-none cursor-pointer group" aria-expanded="false" onclick="event.stopPropagation(); const item = this.closest('.faq-item'); const isOpen = item.classList.contains('is-open'); item.classList.toggle('is-open', !isOpen); this.setAttribute('aria-expanded', String(!isOpen));">
                            <span class="font-bold text-slate-800 text-base md:text-lg group-hover:text-primary transition-colors duration-200 pr-4">
                                Pembayaran QRIS terpotong namun produk belum diterima?
                            </span>
                            <span class="faq-icon-badge flex-shrink-0 w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 group-hover:bg-primary/10 group-hover:text-primary transition-all duration-300">
                                <span class="faq-icon-rotator">
                                    <x-lucide-plus class="w-4 h-4" />
                                </span>
                            </span>
                        </button>
                        <div class="faq-grid-content overflow-hidden transition-all duration-300 ease-in-out">
                            <div class="overflow-hidden">
                                <div class="px-5 pb-5 md:px-6 md:pb-6 text-slate-600 leading-relaxed text-sm md:text-base border-t border-slate-100 pt-4">
                                    Sistem payment gateway umumnya memperbarui status dalam 1-5 menit. Jika setelah 15 menit status transaksi belum berubah, silakan hubungi tim dukungan kami melalui halaman Kontak dengan menyertakan bukti pembayaran dan kode transaksi untuk verifikasi manual cepat.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KATEGORI: PRODUK DIGITAL -->
                <div class="faq-category-group space-y-3" data-category="product">
                    <div class="flex items-center gap-2 pb-1 border-b border-white/20">
                        <span class="text-xs font-bold uppercase tracking-wider text-indigo-200">Kategori</span>
                        <h2 class="text-sm font-bold text-white uppercase tracking-wider">Produk Digital</h2>
                    </div>

                    <!-- Item 3.1 -->
                    <div class="faq-item border border-white/80 rounded-2xl bg-white shadow-md hover:shadow-xl transition-all" data-category="product">
                        <button type="button" class="faq-accordion-header w-full flex items-center justify-between p-5 md:p-6 text-left focus:outline-none cursor-pointer group" aria-expanded="false" onclick="event.stopPropagation(); const item = this.closest('.faq-item'); const isOpen = item.classList.contains('is-open'); item.classList.toggle('is-open', !isOpen); this.setAttribute('aria-expanded', String(!isOpen));">
                            <span class="font-bold text-slate-800 text-base md:text-lg group-hover:text-primary transition-colors duration-200 pr-4">
                                Produk digital apa saja yang dapat saya jual?
                            </span>
                            <span class="faq-icon-badge flex-shrink-0 w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 group-hover:bg-primary/10 group-hover:text-primary transition-all duration-300">
                                <span class="faq-icon-rotator">
                                    <x-lucide-plus class="w-4 h-4" />
                                </span>
                            </span>
                        </button>
                        <div class="faq-grid-content overflow-hidden transition-all duration-300 ease-in-out">
                            <div class="overflow-hidden">
                                <div class="px-5 pb-5 md:px-6 md:pb-6 text-slate-600 leading-relaxed text-sm md:text-base border-t border-slate-100 pt-4">
                                    Anda dapat menjual hampir semua tipe produk digital seperti:
                                    <ul class="list-disc list-inside mt-2 space-y-1.5 text-slate-600">
                                        <li>E-Book, dokumen panduan, dan file PDF</li>
                                        <li>Template Notion, Canva, Figma, Spreadsheet</li>
                                        <li>Preset Lightroom, audio file, dan aset desain</li>
                                        <li>Tiket webinar atau akses rekaman video pelatihan</li>
                                        <li>Kode lisensi software, kupon, atau tautan private grup Telegram/Discord</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Item 3.2 -->
                    <div class="faq-item border border-white/80 rounded-2xl bg-white shadow-md hover:shadow-xl transition-all" data-category="product">
                        <button type="button" class="faq-accordion-header w-full flex items-center justify-between p-5 md:p-6 text-left focus:outline-none cursor-pointer group" aria-expanded="false" onclick="event.stopPropagation(); const item = this.closest('.faq-item'); const isOpen = item.classList.contains('is-open'); item.classList.toggle('is-open', !isOpen); this.setAttribute('aria-expanded', String(!isOpen));">
                            <span class="font-bold text-slate-800 text-base md:text-lg group-hover:text-primary transition-colors duration-200 pr-4">
                                Bagaimana pembeli mengakses file produk setelah membayar?
                            </span>
                            <span class="faq-icon-badge flex-shrink-0 w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 group-hover:bg-primary/10 group-hover:text-primary transition-all duration-300">
                                <span class="faq-icon-rotator">
                                    <x-lucide-plus class="w-4 h-4" />
                                </span>
                            </span>
                        </button>
                        <div class="faq-grid-content overflow-hidden transition-all duration-300 ease-in-out">
                            <div class="overflow-hidden">
                                <div class="px-5 pb-5 md:px-6 md:pb-6 text-slate-600 leading-relaxed text-sm md:text-base border-t border-slate-100 pt-4">
                                    Setelah pembayaran berhasil, pembeli langsung diarahkan ke halaman invoice sukses dengan tombol unduh instan. Selain itu, sistem Linkan.id juga otomatis mengirimkan email konfirmasi berisi link akses unduhan ke alamat email pembeli.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Item 3.3 -->
                    <div class="faq-item border border-white/80 rounded-2xl bg-white shadow-md hover:shadow-xl transition-all" data-category="product">
                        <button type="button" class="faq-accordion-header w-full flex items-center justify-between p-5 md:p-6 text-left focus:outline-none cursor-pointer group" aria-expanded="false" onclick="event.stopPropagation(); const item = this.closest('.faq-item'); const isOpen = item.classList.contains('is-open'); item.classList.toggle('is-open', !isOpen); this.setAttribute('aria-expanded', String(!isOpen));">
                            <span class="font-bold text-slate-800 text-base md:text-lg group-hover:text-primary transition-colors duration-200 pr-4">
                                Apakah bisa mengirim custom pesan setelah pembelian?
                            </span>
                            <span class="faq-icon-badge flex-shrink-0 w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 group-hover:bg-primary/10 group-hover:text-primary transition-all duration-300">
                                <span class="faq-icon-rotator">
                                    <x-lucide-plus class="w-4 h-4" />
                                </span>
                            </span>
                        </button>
                        <div class="faq-grid-content overflow-hidden transition-all duration-300 ease-in-out">
                            <div class="overflow-hidden">
                                <div class="px-5 pb-5 md:px-6 md:pb-6 text-slate-600 leading-relaxed text-sm md:text-base border-t border-slate-100 pt-4">
                                    Tentu saja! Pada saat membuat atau mengedit produk, Anda bisa mengisi formulir "Pesan Kustom Pasca Pembelian" yang akan ditampilkan langsung kepada pembeli, seperti instruksi cara menggunakan produk atau link grup privat.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KATEGORI: AKUN & KEAMANAN -->
                <div class="faq-category-group space-y-3" data-category="account">
                    <div class="flex items-center gap-2 pb-1 border-b border-white/20">
                        <span class="text-xs font-bold uppercase tracking-wider text-indigo-200">Kategori</span>
                        <h2 class="text-sm font-bold text-white uppercase tracking-wider">Akun & Keamanan</h2>
                    </div>

                    <!-- Item 4.1 -->
                    <div class="faq-item border border-white/80 rounded-2xl bg-white shadow-md hover:shadow-xl transition-all" data-category="account">
                        <button type="button" class="faq-accordion-header w-full flex items-center justify-between p-5 md:p-6 text-left focus:outline-none cursor-pointer group" aria-expanded="false" onclick="event.stopPropagation(); const item = this.closest('.faq-item'); const isOpen = item.classList.contains('is-open'); item.classList.toggle('is-open', !isOpen); this.setAttribute('aria-expanded', String(!isOpen));">
                            <span class="font-bold text-slate-800 text-base md:text-lg group-hover:text-primary transition-colors duration-200 pr-4">
                                Cara ganti atau mereset password akun Linkan.id
                            </span>
                            <span class="faq-icon-badge flex-shrink-0 w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 group-hover:bg-primary/10 group-hover:text-primary transition-all duration-300">
                                <span class="faq-icon-rotator">
                                    <x-lucide-plus class="w-4 h-4" />
                                </span>
                            </span>
                        </button>
                        <div class="faq-grid-content overflow-hidden transition-all duration-300 ease-in-out">
                            <div class="overflow-hidden">
                                <div class="px-5 pb-5 md:px-6 md:pb-6 text-slate-600 leading-relaxed text-sm md:text-base border-t border-slate-100 pt-4">
                                    Jika lupa password, klik tautan <em>"Lupa Password?"</em> pada halaman login, lalu masukkan alamat email akun Anda. Kami akan mengirimkan tautan pemulihan aman ke email Anda untuk membuat password baru.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Item 4.2 -->
                    <div class="faq-item border border-white/80 rounded-2xl bg-white shadow-md hover:shadow-xl transition-all" data-category="account">
                        <button type="button" class="faq-accordion-header w-full flex items-center justify-between p-5 md:p-6 text-left focus:outline-none cursor-pointer group" aria-expanded="false" onclick="event.stopPropagation(); const item = this.closest('.faq-item'); const isOpen = item.classList.contains('is-open'); item.classList.toggle('is-open', !isOpen); this.setAttribute('aria-expanded', String(!isOpen));">
                            <span class="font-bold text-slate-800 text-base md:text-lg group-hover:text-primary transition-colors duration-200 pr-4">
                                Bagaimana cara membatalkan langganan paket Unlimited?
                            </span>
                            <span class="faq-icon-badge flex-shrink-0 w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 group-hover:bg-primary/10 group-hover:text-primary transition-all duration-300">
                                <span class="faq-icon-rotator">
                                    <x-lucide-plus class="w-4 h-4" />
                                </span>
                            </span>
                        </button>
                        <div class="faq-grid-content overflow-hidden transition-all duration-300 ease-in-out">
                            <div class="overflow-hidden">
                                <div class="px-5 pb-5 md:px-6 md:pb-6 text-slate-600 leading-relaxed text-sm md:text-base border-t border-slate-100 pt-4">
                                    Masuk ke <strong>Dashboard > Pengaturan Akun > Langganan</strong>. Klik tombol <em>"Batalkan Langganan"</em>. Akun Anda akan tetap aktif menikmati seluruh fitur paket hingga akhir periode tagihan berjalan.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Item 4.3 -->
                    <div class="faq-item border border-white/80 rounded-2xl bg-white shadow-md hover:shadow-xl transition-all" data-category="account">
                        <button type="button" class="faq-accordion-header w-full flex items-center justify-between p-5 md:p-6 text-left focus:outline-none cursor-pointer group" aria-expanded="false" onclick="event.stopPropagation(); const item = this.closest('.faq-item'); const isOpen = item.classList.contains('is-open'); item.classList.toggle('is-open', !isOpen); this.setAttribute('aria-expanded', String(!isOpen));">
                            <span class="font-bold text-slate-800 text-base md:text-lg group-hover:text-primary transition-colors duration-200 pr-4">
                                Bagaimana cara mengumpulkan kontak database email pembeli?
                            </span>
                            <span class="faq-icon-badge flex-shrink-0 w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 group-hover:bg-primary/10 group-hover:text-primary transition-all duration-300">
                                <span class="faq-icon-rotator">
                                    <x-lucide-plus class="w-4 h-4" />
                                </span>
                            </span>
                        </button>
                        <div class="faq-grid-content overflow-hidden transition-all duration-300 ease-in-out">
                            <div class="overflow-hidden">
                                <div class="px-5 pb-5 md:px-6 md:pb-6 text-slate-600 leading-relaxed text-sm md:text-base border-t border-slate-100 pt-4">
                                    Setiap kali ada transaksi atau unduhan freebie (produk gratis), data nama, nomor WhatsApp, dan email pembeli otomatis tersimpan di menu <strong>Pelanggan / Leads</strong>. Anda dapat mengunduh database tersebut dalam format CSV kapan saja.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KATEGORI: INTEGRASI & FITUR -->
                <div class="faq-category-group space-y-3" data-category="integration">
                    <div class="flex items-center gap-2 pb-1 border-b border-white/20">
                        <span class="text-xs font-bold uppercase tracking-wider text-indigo-200">Kategori</span>
                        <h2 class="text-sm font-bold text-white uppercase tracking-wider">Integrasi & Fitur</h2>
                    </div>

                    <!-- Item 5.1 -->
                    <div class="faq-item border border-white/80 rounded-2xl bg-white shadow-md hover:shadow-xl transition-all" data-category="integration">
                        <button type="button" class="faq-accordion-header w-full flex items-center justify-between p-5 md:p-6 text-left focus:outline-none cursor-pointer group" aria-expanded="false" onclick="event.stopPropagation(); const item = this.closest('.faq-item'); const isOpen = item.classList.contains('is-open'); item.classList.toggle('is-open', !isOpen); this.setAttribute('aria-expanded', String(!isOpen));">
                            <span class="font-bold text-slate-800 text-base md:text-lg group-hover:text-primary transition-colors duration-200 pr-4">
                                Cara memasang Google Analytics 4 (GA4) di Linkan.id
                            </span>
                            <span class="faq-icon-badge flex-shrink-0 w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 group-hover:bg-primary/10 group-hover:text-primary transition-all duration-300">
                                <span class="faq-icon-rotator">
                                    <x-lucide-plus class="w-4 h-4" />
                                </span>
                            </span>
                        </button>
                        <div class="faq-grid-content overflow-hidden transition-all duration-300 ease-in-out">
                            <div class="overflow-hidden">
                                <div class="px-5 pb-5 md:px-6 md:pb-6 text-slate-600 leading-relaxed text-sm md:text-base border-t border-slate-100 pt-4">
                                    Buka menu <strong>Pengaturan > Integrasi Pelacakan</strong>. Salin kode Measurement ID Google Analytics Anda (berawalan <code>G-XXXXXXXXXX</code>) lalu tempelkan pada kolom Google Analytics ID. Data pengunjung akan mulai terekam secara otomatis.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Item 5.2 -->
                    <div class="faq-item border border-white/80 rounded-2xl bg-white shadow-md hover:shadow-xl transition-all" data-category="integration">
                        <button type="button" class="faq-accordion-header w-full flex items-center justify-between p-5 md:p-6 text-left focus:outline-none cursor-pointer group" aria-expanded="false" onclick="event.stopPropagation(); const item = this.closest('.faq-item'); const isOpen = item.classList.contains('is-open'); item.classList.toggle('is-open', !isOpen); this.setAttribute('aria-expanded', String(!isOpen));">
                            <span class="font-bold text-slate-800 text-base md:text-lg group-hover:text-primary transition-colors duration-200 pr-4">
                                Cara memasang Meta Pixel (Facebook) & TikTok Pixel
                            </span>
                            <span class="faq-icon-badge flex-shrink-0 w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 group-hover:bg-primary/10 group-hover:text-primary transition-all duration-300">
                                <span class="faq-icon-rotator">
                                    <x-lucide-plus class="w-4 h-4" />
                                </span>
                            </span>
                        </button>
                        <div class="faq-grid-content overflow-hidden transition-all duration-300 ease-in-out">
                            <div class="overflow-hidden">
                                <div class="px-5 pb-5 md:px-6 md:pb-6 text-slate-600 leading-relaxed text-sm md:text-base border-t border-slate-100 pt-4">
                                    Di menu Integrasi Pelacakan, masukkan ID Meta Pixel atau TikTok Pixel Anda. Linkan.id otomatis memicu standard events seperti <em>PageView</em>, <em>ViewContent</em>, <em>InitiateCheckout</em>, dan <em>Purchase</em> untuk optimasi iklan Anda.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Item 5.3 -->
                    <div class="faq-item border border-white/80 rounded-2xl bg-white shadow-md hover:shadow-xl transition-all" data-category="integration">
                        <button type="button" class="faq-accordion-header w-full flex items-center justify-between p-5 md:p-6 text-left focus:outline-none cursor-pointer group" aria-expanded="false" onclick="event.stopPropagation(); const item = this.closest('.faq-item'); const isOpen = item.classList.contains('is-open'); item.classList.toggle('is-open', !isOpen); this.setAttribute('aria-expanded', String(!isOpen));">
                            <span class="font-bold text-slate-800 text-base md:text-lg group-hover:text-primary transition-colors duration-200 pr-4">
                                Apakah tersedia Webhook untuk otomatisasi ke sistem lain?
                            </span>
                            <span class="faq-icon-badge flex-shrink-0 w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 group-hover:bg-primary/10 group-hover:text-primary transition-all duration-300">
                                <span class="faq-icon-rotator">
                                    <x-lucide-plus class="w-4 h-4" />
                                </span>
                            </span>
                        </button>
                        <div class="faq-grid-content overflow-hidden transition-all duration-300 ease-in-out">
                            <div class="overflow-hidden">
                                <div class="px-5 pb-5 md:px-6 md:pb-6 text-slate-600 leading-relaxed text-sm md:text-base border-t border-slate-100 pt-4">
                                    Ya, Linkan.id mendukung Webhook URL. Anda bisa menghubungkannya dengan platform seperti Zapier, Make, atau backend aplikasi kustom Anda untuk menerima payload JSON seketika saat order berstatus sukses.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- EMPTY SEARCH STATE -->
                <div id="faqNoResults" class="hidden text-center py-12 px-4 bg-white rounded-2xl border border-white/80 shadow-lg">
                    <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 mx-auto flex items-center justify-center mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="8" stroke-width="2"/>
                            <path d="m21 21-4.35-4.35" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-800 mb-1">Pertanyaan tidak ditemukan</h3>
                    <p class="text-sm text-slate-500 max-w-sm mx-auto mb-4">Coba cari dengan kata kunci lain atau pilih salah satu kategori di atas.</p>
                    <button type="button" id="faqResetSearchBtn" class="text-xs font-semibold text-primary hover:underline">
                        Reset pencarian
                    </button>
                </div>

            </div>
        </section>

        <!-- =========================================================
             HELP CALL TO ACTION CARD
             ========================================================= -->
        <section class="w-full px-4 mb-8">
            <div class="bg-white rounded-3xl p-8 md:p-10 text-center relative overflow-hidden shadow-xl border border-white/80">
                <div class="absolute -right-12 -bottom-12 w-48 h-48 bg-primary/10 rounded-full blur-2xl pointer-events-none"></div>
                <h3 class="text-xl md:text-2xl font-bold text-slate-900 mb-2">Masih punya pertanyaan yang belum terjawab?</h3>
                <p class="text-slate-600 text-sm md:text-base max-w-lg mx-auto mb-6">
                    Tim dukungan Linkan.id siap membantu Anda memaksimalkan pertumbuhan bisnis digital Anda kapan saja.
                </p>
                <div class="flex flex-wrap items-center justify-center gap-3">
                    <a href="{{ route('contact.form') }}" class="px-6 py-3 rounded-full bg-primary text-white font-semibold text-sm shadow-md shadow-primary/30 hover:bg-primary/90 transition-all hover:scale-105">
                        Hubungi Tim Support
                    </a>
                    <a href="{{ route('about') }}" class="px-6 py-3 rounded-full bg-slate-100 text-slate-700 font-semibold text-sm border border-slate-200 hover:border-slate-300 hover:bg-slate-200/70 transition-all">
                        Pelajari Tentang Kami
                    </a>
                </div>
            </div>
        </section>

    </main>

    <!-- FOOTER -->
    <footer class="footer-wrapper">
        <div class="footer-pill">
            <a href="{{ url('/') }}" class="footer-logo">
                <img src="{{ asset('images/Logo.svg') }}" alt="Linkan Logo">
            </a>
        </div>
    </footer>

    <!-- =========================================================
         4. VANILLA JAVASCRIPT LOGIC
         - Tanpa Alpine.js
         - 2-State Gooey Search Bar Interactions & Live FAQ Filtering
         - Tab Switcher: Menangkap event klik tab, menyembunyikan grup FAQ
           yang tidak sesuai data-category, menampilkan grup terpilih
           dengan animasi opacity
         - Accordion Toggle: classList.toggle('is-open') pada card FAQ
           menutup akordion lain di grup yang sama saat dibuka
         - Navbar scramble text effect & Mobile menu toggle
         ========================================================= -->
    <script>
        (function () {
            // -------------------------------------------------------------
            // A. ACCORDION TOGGLE (Vanilla JS)
            // -------------------------------------------------------------
            // -------------------------------------------------------------
            // B. TAB SWITCHER (Vanilla JS)
            // -------------------------------------------------------------
            const tabButtons = document.querySelectorAll('.faq-tab-btn');
            const categoryGroups = document.querySelectorAll('.faq-category-group');
            const searchInput = document.getElementById('kbSearchInput');
            const noResults = document.getElementById('faqNoResults');
            let currentActiveCategory = 'all';

            // Classes for Active and Inactive Tabs
            const activeTabClasses = ['active', 'bg-primary', 'text-white', 'border-2', 'border-white', 'ring-2', 'ring-primary', 'shadow-lg'];
            const inactiveTabClasses = ['border', 'border-slate-200', 'text-slate-500', 'hover:text-slate-900', 'hover:border-slate-300', 'bg-white', 'shadow-sm'];

            function applyTabCategory(targetCategory) {
                currentActiveCategory = targetCategory;

                // 1. Update tombol tab visual state
                tabButtons.forEach(btn => {
                    const btnCategory = btn.getAttribute('data-category');
                    if (btnCategory === targetCategory) {
                        btn.setAttribute('aria-selected', 'true');
                        inactiveTabClasses.forEach(cls => btn.classList.remove(cls));
                        activeTabClasses.forEach(cls => btn.classList.add(cls));
                    } else {
                        btn.setAttribute('aria-selected', 'false');
                        activeTabClasses.forEach(cls => btn.classList.remove(cls));
                        inactiveTabClasses.forEach(cls => btn.classList.add(cls));
                    }
                });

                // 2. Tampilkan atau sembunyikan grup kategori dengan animasi opacity
                categoryGroups.forEach(group => {
                    const groupCategory = group.getAttribute('data-category');
                    const shouldShow = (targetCategory === 'all' || groupCategory === targetCategory);

                    if (shouldShow) {
                        group.style.display = 'block';
                        group.style.opacity = '0';
                        group.style.transform = 'translateY(6px)';

                        // Tutup akordion yang terbuka saat berpindah tab
                        group.querySelectorAll('.faq-item.is-open').forEach(item => {
                            item.classList.remove('is-open');
                            const h = item.querySelector('.faq-accordion-header');
                            if (h) h.setAttribute('aria-expanded', 'false');
                        });

                        requestAnimationFrame(() => {
                            requestAnimationFrame(() => {
                                group.style.opacity = '1';
                                group.style.transform = 'translateY(0)';
                            });
                        });
                    } else {
                        group.style.opacity = '0';
                        group.style.display = 'none';
                    }
                });

                // Sembunyikan notifikasi no-results jika ada
                if (noResults) noResults.classList.add('hidden');
            }

            tabButtons.forEach(btn => {
                btn.addEventListener('click', function () {
                    const targetCategory = btn.getAttribute('data-category');
                    // Bersihkan input search saat tab ditekan
                    if (searchInput && searchInput.value.trim() !== '') {
                        searchInput.value = '';
                        const clearBtn = document.getElementById('gooClearBtn');
                        if (clearBtn) clearBtn.classList.remove('visible');
                    }
                    applyTabCategory(targetCategory);
                });
            });

            // -------------------------------------------------------------
            // C. 2-STATE GOOEY SEARCH BAR & LIVE FILTERING
            // -------------------------------------------------------------
            const gooContainer = document.getElementById('gooSearchContainer');
            const gooSearchBar = document.getElementById('gooSearchBar');
            const clearBtn = document.getElementById('gooClearBtn');
            const searchIconBtn = document.getElementById('gooSearchIconBtn');
            const resetSearchBtn = document.getElementById('faqResetSearchBtn');
            const allFaqItems = document.querySelectorAll('.faq-item');

            if (gooSearchBar && gooContainer && searchInput) {
                // Expand from State 1 (Button) to State 2 (Input)
                const expandSearch = () => {
                    if (!gooSearchBar.classList.contains('expanded')) {
                        gooSearchBar.classList.add('expanded');
                        gooContainer.classList.add('is-expanded');
                        gooSearchBar.setAttribute('aria-expanded', 'true');
                        setTimeout(() => {
                            searchInput.focus();
                        }, 150);
                    }
                };

                // Collapse back to State 1 (Button) if empty
                const collapseSearch = () => {
                    if (searchInput.value.trim() === '') {
                        gooSearchBar.classList.remove('expanded');
                        gooContainer.classList.remove('is-expanded');
                        gooSearchBar.setAttribute('aria-expanded', 'false');
                        if (clearBtn) clearBtn.classList.remove('visible');
                        handleSearch();
                    }
                };

                // Click button to expand
                gooSearchBar.addEventListener('click', (e) => {
                    if (!gooSearchBar.classList.contains('expanded')) {
                        expandSearch();
                    }
                });

                gooSearchBar.addEventListener('keydown', (e) => {
                    if ((e.key === 'Enter' || e.key === ' ') && !gooSearchBar.classList.contains('expanded')) {
                        e.preventDefault();
                        expandSearch();
                    }
                });

                // Click outside to collapse if empty
                document.addEventListener('click', (e) => {
                    if (!gooContainer.contains(e.target)) {
                        collapseSearch();
                    }
                });

                // Escape key collapses
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && gooSearchBar.classList.contains('expanded')) {
                        searchInput.value = '';
                        collapseSearch();
                    }
                });

                // Real-time live search filter
                const handleSearch = () => {
                    const query = searchInput.value.toLowerCase().trim();

                    if (query.length > 0) {
                        if (clearBtn) clearBtn.classList.add('visible');
                    } else {
                        if (clearBtn) clearBtn.classList.remove('visible');
                        // Kembalikan ke tab kategori aktif
                        applyTabCategory(currentActiveCategory);
                        allFaqItems.forEach(item => {
                            item.style.display = '';
                        });
                        return;
                    }

                    let totalMatches = 0;

                    categoryGroups.forEach(group => {
                        const items = group.querySelectorAll('.faq-item');
                        let groupMatches = 0;

                        items.forEach(item => {
                            const questionText = item.querySelector('.faq-accordion-header span')?.textContent.toLowerCase() || '';
                            const answerText = item.querySelector('.faq-grid-content')?.textContent.toLowerCase() || '';

                            if (questionText.includes(query) || answerText.includes(query)) {
                                item.style.display = '';
                                groupMatches++;
                                totalMatches++;

                                if (query.length >= 2) {
                                    item.classList.add('is-open');
                                    item.querySelector('.faq-accordion-header')?.setAttribute('aria-expanded', 'true');
                                }
                            } else {
                                item.style.display = 'none';
                                item.classList.remove('is-open');
                                item.querySelector('.faq-accordion-header')?.setAttribute('aria-expanded', 'false');
                            }
                        });

                        if (groupMatches > 0) {
                            group.style.display = 'block';
                            group.style.opacity = '1';
                            group.style.transform = 'none';
                        } else {
                            group.style.display = 'none';
                        }
                    });

                    if (noResults) {
                        noResults.classList.toggle('hidden', totalMatches > 0);
                    }
                };

                searchInput.addEventListener('input', handleSearch);

                if (clearBtn) {
                    clearBtn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        searchInput.value = '';
                        handleSearch();
                        searchInput.focus();
                    });
                }

                if (searchIconBtn) {
                    searchIconBtn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        handleSearch();
                        const firstMatch = document.querySelector('.faq-item:not([style*="display: none"])');
                        if (firstMatch) {
                            firstMatch.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    });
                }

                if (resetSearchBtn) {
                    resetSearchBtn.addEventListener('click', function () {
                        searchInput.value = '';
                        collapseSearch();
                        applyTabCategory('all');
                    });
                }
            }

            // -------------------------------------------------------------
            // D. MOBILE NAVBAR MENU TOGGLE
            // -------------------------------------------------------------
            const mobileNavToggle = document.getElementById('mobileNavToggle');
            const mobileNavOverlay = document.getElementById('mobileNavOverlay');
            if (mobileNavToggle && mobileNavOverlay) {
                const mobileNavLinks = mobileNavOverlay.querySelectorAll('.mobile-nav-link, .mobile-btn-signup');

                function toggleMenu() {
                    const isActive = mobileNavToggle.classList.toggle('active');
                    mobileNavOverlay.classList.toggle('active');
                    mobileNavToggle.setAttribute('aria-expanded', isActive);
                    mobileNavOverlay.setAttribute('aria-hidden', !isActive);
                    document.body.style.overflow = isActive ? 'hidden' : '';
                }

                mobileNavToggle.addEventListener('click', toggleMenu);

                mobileNavLinks.forEach(link => {
                    link.addEventListener('click', () => {
                        mobileNavToggle.classList.remove('active');
                        mobileNavOverlay.classList.remove('active');
                        mobileNavToggle.setAttribute('aria-expanded', 'false');
                        mobileNavOverlay.setAttribute('aria-hidden', 'true');
                        document.body.style.overflow = '';
                    });
                });
            }

            // -------------------------------------------------------------
            // E. SCRAMBLE TEXT EFFECT FOR NAVBAR LINKS
            // -------------------------------------------------------------
            const letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            const scrambleLinks = document.querySelectorAll('.scramble-link');

            scrambleLinks.forEach(link => {
                if (!link.dataset.value) {
                    link.dataset.value = link.innerText.trim();
                }

                let timeoutId = null;

                link.addEventListener('mouseenter', () => {
                    const originalText = link.dataset.value;
                    if (!originalText) return;

                    clearTimeout(timeoutId);

                    link.style.whiteSpace = 'nowrap';
                    link.style.display = 'inline-block';

                    link.innerHTML = '';
                    const tempSpans = [];
                    originalText.split('').forEach(char => {
                        const s = document.createElement('span');
                        s.style.display = 'inline-block';
                        s.style.whiteSpace = 'nowrap';
                        s.style.visibility = 'hidden';
                        s.textContent = char === ' ' ? '\u00A0' : char;
                        link.appendChild(s);
                        tempSpans.push({ span: s, char });
                    });

                    const charWidths = tempSpans.map(({ span, char }) => {
                        return { char, width: Math.ceil(span.getBoundingClientRect().width * 10) / 10 };
                    });

                    link.innerHTML = '';
                    const charBoxes = [];

                    charWidths.forEach(({ char, width }, i) => {
                        if (char === ' ') {
                            const space = document.createElement('span');
                            space.style.display = 'inline-block';
                            space.style.whiteSpace = 'nowrap';
                            space.style.width = `${width}px`;
                            space.innerHTML = '&nbsp;';
                            link.appendChild(space);
                            return;
                        }

                        const box = document.createElement('span');
                        box.className = 'scramble-char-box';
                        box.style.display = 'inline-block';
                        box.style.overflow = 'hidden';
                        box.style.whiteSpace = 'nowrap';
                        box.style.width = `${width}px`;
                        box.style.height = '1.3em';
                        box.style.lineHeight = '1.3em';
                        box.style.verticalAlign = 'top';
                        box.style.textAlign = 'center';

                        const col = document.createElement('span');
                        col.className = 'scramble-char-col';
                        col.style.display = 'flex';
                        col.style.flexDirection = 'column';
                        col.style.width = '100%';
                        col.style.whiteSpace = 'nowrap';

                        const r1 = letters[Math.floor(Math.random() * letters.length)];
                        const r2 = letters[Math.floor(Math.random() * letters.length)];
                        const r3 = letters[Math.floor(Math.random() * letters.length)];

                        const charList = [char, r1, r2, r3, char];

                        charList.forEach(c => {
                            const item = document.createElement('span');
                            item.className = 'scramble-char-item';
                            item.style.display = 'block';
                            item.style.height = '1.3em';
                            item.style.lineHeight = '1.3em';
                            item.style.width = '100%';
                            item.style.textAlign = 'center';
                            item.style.whiteSpace = 'nowrap';
                            item.textContent = c;
                            col.appendChild(item);
                        });

                        col.style.transform = 'translateY(-80%)';
                        box.appendChild(col);
                        link.appendChild(box);
                        charBoxes.push({ col, index: i });
                    });

                    void link.offsetWidth;

                    charBoxes.forEach(({ col, index }) => {
                        col.style.transition = `transform 0.45s cubic-bezier(0.16, 1, 0.3, 1) ${index * 35}ms`;
                        col.style.transform = 'translateY(0%)';
                    });

                    const totalDuration = 450 + (originalText.length * 35) + 50;
                    timeoutId = setTimeout(() => {
                        link.innerText = originalText;
                        link.style.whiteSpace = '';
                    }, totalDuration);
                });
            });
        })();
    </script>
</body>
</html>
