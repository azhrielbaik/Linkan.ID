<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('public.faq_title', ['default' => 'FAQ - Frequently Asked Questions']) }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* Accessibility: Skip to Content */
        .skip-to-content {
            position: absolute;
            top: -40px;
            left: 0;
            background: #000;
            color: white;
            padding: 8px;
            z-index: 9999;
            transition: top 0.2s;
        }
        .skip-to-content:focus {
            top: 0;
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
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* NAVBAR */
        .navbar-wrapper {
            width: 100%;
            display: flex;
            justify-content: center;
            margin-bottom: 40px;
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
            box-shadow: 0 10px 30px rgba(0,0,0,0.06);
            white-space: nowrap;
        }

        .nav-logo {
            flex-shrink: 0;
        }

        .nav-logo img {
            height: 35px;
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
            color: #4B5563;
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
            color: #000000 !important;
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
            transition: transform 0.2s, background 0.2s;
        }

        .btn-signup:hover {
            background: #4C4DF0;
            transform: scale(1.05);
        }

        /* KNOWLEDGE BASE CARD */
        main {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .kb-card {
            background: #FFFFFF;
            border-radius: 30px;
            width: 100%;
            max-width: 620px;
            padding: 40px 40px;
            text-align: center;
            box-shadow: 0 20px 50px rgba(0,0,0,0.1);
            margin-bottom: 40px;
        }

        .kb-title {
            font-size: 24px;
            font-weight: 800;
            color: #0062E6; /* Blue text */
            margin-bottom: 12px;
            letter-spacing: -0.5px;
        }

        .kb-subtitle {
            font-size: 15px;
            color: #007BFF;
            font-weight: 600;
            margin-bottom: 24px;
        }

        /* =========================================================
           GOOEY ANIMATED SEARCH BAR (2-STATE: BUTTON -> INPUT)
           ========================================================= */
        .kb-search-outer {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            margin-top: 14px;
            position: relative;
        }

        .goo-search-container {
            filter: url(#goo-effect);
            -webkit-filter: url(#goo-effect);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        /* State 1 (Button): Initially small rounded capsule */
        .goo-search-bar {
            position: relative;
            width: 135px;
            height: 52px;
            background: #5a5bf1;
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
            background: #4e4ff0;
            transform: scale(1.04);
        }

        /* State 2 (Input): Expanded width */
        .goo-search-bar.expanded {
            width: 440px;
            max-width: 78vw;
            background: #5152ea;
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
            background: #4648ea;
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
            background: #393adb;
            transform: translateX(64px) scale(1.08);
        }

        .goo-search-icon-blob:active {
            transform: translateX(64px) scale(0.94);
        }

        @media (max-width: 600px) {
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

        /* SECTION TITLE */
        .section-title {
            font-size: 26px;
            font-weight: 800;
            color: #FFFFFF;
            text-align: center;
            margin-bottom: 30px;
            letter-spacing: -0.5px;
        }

        /* FAQ GRID */
        .faq-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            width: 100%;
            max-width: 900px;
            margin-bottom: 40px;
            margin-top: 40px;
        }

        .faq-item {
            background: #FFFFFF;
            border-radius: 16px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            height: fit-content;
            width: 100%;
            margin-bottom:50px;
        }

        .faq-question {
            padding: 16px 32px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            font-weight: 800;
            font-size: 14px;
            color: #0062E6; 
            user-select: none;
            text-align: center;
            border: none;
            background: transparent;
            width: 100%;
        }

        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease, padding 0.3s ease;
            padding: 0 32px;
            background: #FFFFFF;
        }

        .faq-item.open {
            border-radius: 16px; 
        }

        .faq-item.open .faq-answer {
            max-height: 300px;
            padding: 0 32px 24px 32px;
        }

        .faq-answer p {
            font-size: 13px;
            color: #344054;
            line-height: 1.6;
            font-weight: 500;
            text-align: center;
        }
        
        .faq-centered {
            display: flex;
            justify-content: center;
            width: 100%;
            margin-bottom: 60px;
        }
        
        .faq-centered .faq-item {
            max-width: 440px;
        }

        /* LINKS SECTION */
        .links-section {
            width: 100%;
            max-width: 1000px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 40px 20px;
            margin-bottom: 60px;
        }

        .link-column {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .link-title {
            font-size: 18px;
            font-weight: 700;
            color: #FFFFFF;
        }
        
        .link-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .link-item {
            font-size: 12px;
            color: #FFFFFF;
            font-weight: 500;
            line-height: 1.4;
            transition: opacity 0.2s;
            cursor: pointer;
        }

        .link-item:hover {
            opacity: 0.8;
            text-decoration: underline;
        }

        /* FOOTER */
        .footer-wrapper {
            width: 100%;
            display: flex;
            justify-content: center;
            margin-top: auto;
            padding-bottom: 24px;
        }

        .footer-pill {
            background: #FFFFFF;
            border-radius: 50px;
            padding: 12px 32px;
            width: 100%;
            max-width: 1000px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.06);
        }

        .footer-logo img {
            height: 30px;
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
            color: #121212;
            transition: color 0.2s;
        }

        .footer-link:hover {
            color: #EE8025;
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
            background-color: #333333;
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
            color: #333333;
            transition: color 0.2s, transform 0.2s;
            position: relative;
        }

        .mobile-nav-link:hover {
            color: #5A5BF1;
            transform: scale(1.05);
        }

        .mobile-btn-signup {
            background: #000000;
            color: #fff;
            padding: 14px 40px;
            border-radius: 50px;
            font-size: 18px;
            font-weight: 700;
            width: 100%;
            text-align: center;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            transition: transform 0.2s, background 0.2s;
            margin-top: 10px;
        }

        .mobile-btn-signup:hover {
            background: #222222;
            transform: scale(1.05);
        }

        /* Responsive design */
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

        @media (max-width: 768px) {
            .btn-signup {
                padding: 8px 16px;
                font-size: 13px;
            }
            .faq-grid {
                grid-template-columns: 1fr;
            }
            .kb-card {
                padding: 30px 20px;
            }
            .kb-title {
                font-size: 20px;
            }
            .kb-subtitle {
                font-size: 13px;
            }
            .faq-item {
                border-radius: 16px;
            }
            .faq-question {
                padding: 16px 24px;
                font-size: 13px;
            }
            .faq-item.open .faq-answer {
                padding: 0 24px 20px 24px;
            }
            .links-section {
                grid-template-columns: repeat(2, 1fr);
            }
            .footer-pill {
                padding: 10px 24px;
            }
            .footer-links {
                gap: 20px;
            }
        }

        @media (max-width: 480px) {
            .links-section {
                grid-template-columns: 1fr;
            }
            .gooey-search-wrapper {
                height: 52px;
                max-width: 100%;
            }
            .gooey-search-content {
                padding: 4px 4px 4px 14px;
            }
            .gooey-input {
                font-size: 13.5px;
            }
            .gooey-search-btn {
                padding: 9px 16px;
                font-size: 13px;
                gap: 4px;
            }
            .gooey-blob.blob-btn {
                width: 85px;
                height: 44px;
            }
            .footer-pill {
                flex-direction: column;
                gap: 16px;
            }
        }
    </style>
</head>
<body>
    <a href="#main-content" class="skip-to-content">Skip to content</a>

    <!-- NAVBAR -->
    <header class="navbar-wrapper">
        <nav class="navbar-pill" aria-label="Main Navigation">
            <a href="{{ url('/') }}" class="nav-logo">
                <img src="{{ asset('images/Logo.svg') }}" alt="Linkan Logo">
            </a>
            <div class="nav-links">
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
            <a href="{{ url('/') }}#pricing" class="mobile-nav-link scramble-link" data-value="{{ __('layout.pricing') }}">{{ __('layout.pricing') }}</a>
            <a href="{{ url('/') }}#digital-marketing" class="mobile-nav-link scramble-link" data-value="{{ __('layout.service') }}">{{ __('layout.service') }}</a>
            <a href="{{ route('FAQ') }}" class="mobile-nav-link scramble-link" data-value="{{ __('layout.faq') }}">{{ __('layout.faq') }}</a>
            <a href="{{ route('login') }}" class="mobile-nav-link scramble-link" data-value="{{ __('layout.sign_in') }}">{{ __('layout.sign_in') }}</a>
            <a href="{{ route('register') }}" class="mobile-btn-signup">{{ __('layout.sign_up_free') }}</a>
        </nav>
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

    <main id="main-content">
    <!-- KNOWLEDGE BASE CARD -->
    <div class="kb-card">
        <h1 class="kb-title">Linkan.id Knowledge Base</h1>
        <p class="kb-subtitle">Discover what Linkan.id can do to help you achieve your goals</p>
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

    <!-- SECTION TITLE -->
    <h2 class="section-title">Frequently Asked Questions</h2>

    <!-- FAQ GRID -->
    <div class="faq-grid">
        <!-- Item 1 -->
        <div class="faq-item">
            <button class="faq-question" aria-expanded="false" aria-controls="faq-answer-1">
                <span>Apa itu linkan.id ?</span>
            </button>
            <div id="faq-answer-1" class="faq-answer">
                <p>Linkan.id adalah platform link-in-bio yang membantu kreator, pebisnis, dan influencer mengelola kehadiran digital mereka dengan lebih praktis dan profesional.</p>
            </div>
        </div>

        <!-- Item 2 -->
        <div class="faq-item">
            <button class="faq-question" aria-expanded="false" aria-controls="faq-answer-2">
                <span>Apa Saja Fitur Linkan.id?</span>
            </button>
            <div id="faq-answer-2" class="faq-answer">
                <p>Fitur unggulan: Halaman Link-in-Bio, Penjualan Produk Digital, Donasi Online, Pembayaran Aman, Analisis, dan Kustomisasi Template.</p>
            </div>
        </div>

        <!-- Item 3 -->
        <div class="faq-item">
            <button class="faq-question" aria-expanded="false" aria-controls="faq-answer-3">
                <span>Bagaimana sistem pembayaran pekerja?</span>
            </button>
            <div id="faq-answer-3" class="faq-answer">
                <p>Menggunakan payment gateway real-time untuk otomatisasi transaksi tanpa konfirmasi manual.</p>
            </div>
        </div>

        <!-- Item 4 -->
        <div class="faq-item">
            <button class="faq-question" aria-expanded="false" aria-controls="faq-answer-4">
                <span>Bisa Menjual Produk Digital?</span>
            </button>
            <div id="faq-answer-4" class="faq-answer">
                <p>Ya, Anda bisa menjual e-book, e-course, template, dan konten digital lainnya langsung.</p>
            </div>
        </div>
    </div>
    
    <!-- Item 5 Centered -->
    <div class="faq-centered">
        <div class="faq-item">
            <button class="faq-question" aria-expanded="false" aria-controls="faq-answer-5">
                <span>Bagaimana Sistem Pembagian Komisi ?</span>
            </button>
            <div id="faq-answer-5" class="faq-answer">
                <p>Admin Platform menerima komisi sebesar 5% dari pendapatan Admin Seller, yang otomatis dipotong saat penarikan.</p>
            </div>
        </div>
    </div>

    <!-- LINKS SECTION -->
    <div class="links-section">
        <div class="link-column">
            <div class="link-title">General</div>
            <div class="link-list">
                <a href="#" class="link-item">Tutorial Ganti/Lupa Pasword Lynk.id</a>
                <a href="#" class="link-item">Bagaimana Memberhentikan Langganan Linkan.id Unlimited</a>
                <a href="#" class="link-item">Tips Memilih Username Linkan.id Yang Baik</a>
                <a href="#" class="link-item">Domain alternatif Linkan.id</a>
            </div>
        </div>
        <div class="link-column">
            <div class="link-title">Integration</div>
            <div class="link-list">
                <a href="#" class="link-item">Data pembelian tidak muncul di laporan</a>
                <a href="#" class="link-item">Cara Menghubungkan Google Analytics 4 (GA4) ke Linkan.id</a>
                <a href="#" class="link-item">Tutorial Instalasi TikTok Pixel di Linkan.id melalui Google Tag Manager</a>
                <a href="#" class="link-item">Webhook transaksi sukses di Linkan.id</a>
            </div>
        </div>
        <div class="link-column">
            <div class="link-title">Payment</div>
            <div class="link-list">
                <a href="#" class="link-item">Cara Input No Rekening dan ganti no rekening di Linkan.id</a>
                <a href="#" class="link-item">Tutorial Penarikan Dana di Lynk.id</a>
                <a href="#" class="link-item">Pembayaran dengan QRIS - Saldo sudah terpotong tetapi tidak mendapatkan produk</a>
                <a href="#" class="link-item">Bagaimanakah Afiliator Menerima Pembayaran</a>
            </div>
        </div>
        <div class="link-column">
            <div class="link-title">Product</div>
            <div class="link-list">
                <a href="#" class="link-item">Custom Message untuk customer setelah pembelian</a>
                <a href="#" class="link-item">Tutorial Membuat Event Online di Linkan.id</a>
                <a href="#" class="link-item">Tutorial Hapus produk Affiliate</a>
                <a href="#" class="link-item">Tutorial custom domain di Linkan.id</a>
            </div>
        </div>
        <div class="link-column">
            <div class="link-title">Promotion</div>
            <div class="link-list">
                <a href="#" class="link-item">Tutorial Menjadi Afiliator di lynk.id Sebagai Penjual</a>
                <a href="#" class="link-item">FAQ Creator on Clip Campaign</a>
                <a href="#" class="link-item">Tutorial WhatsApp broadcast menggunakan Lynk.id</a>
                <a href="#" class="link-item">FAQ Clipper on Clip Campaign</a>
            </div>
        </div>
        <div class="link-column">
            <div class="link-title">Tips & Trick</div>
            <div class="link-list">
                <a href="#" class="link-item">Tutorial Mengumpulkan Database Email / Telepon</a>
                <a href="#" class="link-item">Email receipt - akses produk tidak diterima Customer</a>
                <a href="#" class="link-item">Tutorial Verifikasi Custom Domain di Meta</a>
                <a href="#" class="link-item">Link Lynk.id di blok atau dihapus oleh Facebook</a>
            </div>
        </div>
    </div>
    </main>

    <!-- FOOTER -->
    <footer class="footer-wrapper">
        <div class="footer-pill">
            <a href="{{ url('/') }}" class="footer-logo">
                <img src="{{ asset('images/Logo.svg') }}" alt="Linkan Logo">
            </a>
            <div class="footer-links">
                <a href="{{ route('about') }}" class="footer-link scramble-link" data-value="{{ __('layout.about_us') }}">{{ __('layout.about_us') }}</a>
                <a href="{{ route('contact.form') }}" class="footer-link scramble-link" data-value="{{ __('layout.contact_us') }}">{{ __('layout.contact_us') }}</a>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const faqItems = document.querySelectorAll('.faq-item');

            faqItems.forEach(item => {
                const question = item.querySelector('.faq-question');
                
                question.addEventListener('click', () => {
                    const isOpen = item.classList.contains('open');

                    // Tutup semua yang terbuka
                    faqItems.forEach(faq => {
                        faq.classList.remove('open');
                        faq.querySelector('.faq-question').setAttribute('aria-expanded', 'false');
                    });

                    // Buka yang di-klik jika sebelumnya tidak terbuka
                    if (!isOpen) {
                        item.classList.add('open');
                        question.setAttribute('aria-expanded', 'true');
                    }
                });
            });

            // Mobile menu toggle logic
            const mobileNavToggle = document.getElementById('mobileNavToggle');
            const mobileNavOverlay = document.getElementById('mobileNavOverlay');
            if (mobileNavToggle && mobileNavOverlay) {
                const mobileNavLinks = mobileNavOverlay.querySelectorAll('.mobile-nav-link, .mobile-btn-signup');
                
                function toggleMenu() {
                    const isActive = mobileNavToggle.classList.toggle('active');
                    mobileNavOverlay.classList.toggle('active');
                    mobileNavToggle.setAttribute('aria-expanded', isActive);
                    mobileNavOverlay.setAttribute('aria-hidden', !isActive);
                    if (isActive) {
                        document.body.style.overflow = 'hidden';
                    } else {
                        document.body.style.overflow = '';
                    }
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
        // 2-State Gooey Search Bar Interactions & Live FAQ Filtering
        const gooContainer = document.getElementById('gooSearchContainer');
        const gooSearchBar = document.getElementById('gooSearchBar');
        const searchInput = document.getElementById('kbSearchInput');
        const clearBtn = document.getElementById('gooClearBtn');
        const searchIconBtn = document.getElementById('gooSearchIconBtn');
        const faqGridItems = document.querySelectorAll('.faq-item');
        const linkItems = document.querySelectorAll('.link-item');

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
                }

                // Filter FAQ Questions
                faqGridItems.forEach(item => {
                    const questionText = item.querySelector('.faq-question')?.innerText.toLowerCase() || '';
                    const answerText = item.querySelector('.faq-answer')?.innerText.toLowerCase() || '';
                    
                    if (!query || questionText.includes(query) || answerText.includes(query)) {
                        item.style.display = '';
                        if (query.length >= 2 && (questionText.includes(query) || answerText.includes(query))) {
                            item.classList.add('open');
                            item.querySelector('.faq-question')?.setAttribute('aria-expanded', 'true');
                        }
                    } else {
                        item.style.display = 'none';
                        item.classList.remove('open');
                        item.querySelector('.faq-question')?.setAttribute('aria-expanded', 'false');
                    }
                });

                // Filter Knowledge Base Links
                linkItems.forEach(link => {
                    const linkText = link.innerText.toLowerCase();
                    if (!query || linkText.includes(query)) {
                        link.style.display = '';
                    } else {
                        link.style.display = 'none';
                    }
                });
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
        }

        // Vertical Top-to-Bottom Scramble Text Effect on Navigation Links (Fixed Widths, No Layout Shift, No Drop)
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

                // Ensure link never drops or wraps
                link.style.whiteSpace = 'nowrap';
                link.style.display = 'inline-block';

                // Measure original letter widths
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

                // Build character boxes with locked widths
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
        });
    </script>
</body>
</html>
