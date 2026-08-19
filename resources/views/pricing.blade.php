<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('public.pricing_title') }}</title>
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

        /* Accessibility: High Visibility Focus */
        *:focus-visible {
            outline: 3px solid #FFD700 !important;
            outline-offset: 2px !important;
        }

        body {
            background-color: #EE8025; /* Solid Orange */
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            padding: 24px 20px;
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
            padding: 8px 12px 8px 24px;
            width: 100%;
            max-width: 1000px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.06);
        }

        .nav-logo img {
            height: 45px;
            width: auto;
            display: block;
        }

        .nav-links {
            display: flex;
            gap: 24px;
            align-items: center;
        }

        .nav-link {
            font-size: 14px;
            font-weight: 600;
            color: #333333;
            transition: color 0.2s;
        }

        .nav-link:hover {
            color: #EE8025;
        }

        .nav-link.active {
            color: #EE8025;
        }

        .btn-signup {
            background: #000000; /* Solid Black */
            color: #FFFFFF;
            padding: 10px 22px;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 700;
            transition: transform 0.2s, background 0.2s;
        }

        .btn-signup:hover {
            background: #222222;
            transform: scale(1.05);
        }

        /* PRICING CARD */
        .pricing-card {
            background: #FFFFFF;
            border-radius: 40px;
            border: 5px solid #0062E6; /* Royal Blue Border */
            width: 100%;
            max-width: 520px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0,0,0,0.12);
            margin-bottom: 40px;
        }

        .card-header {
            background: #F2F4F7;
            padding: 24px 32px;
            border-bottom: 1px solid #EAECF0;
        }

        .card-header h2 {
            font-size: 32px;
            font-weight: 800;
            color: #121212;
            letter-spacing: -0.5px;
        }

        .card-body {
            padding: 40px 32px;
        }

        .price-section {
            display: flex;
            align-items: baseline;
            margin-bottom: 28px;
        }

        .price-val {
            font-size: 36px;
            font-weight: 800;
            color: #121212;
            letter-spacing: -0.5px;
        }

        .price-period {
            font-size: 16px;
            font-weight: 600;
            color: #667085;
            margin-left: 8px;
        }

        .btn-get-started {
            display: block;
            width: 100%;
            background: #0062E6; /* Blue background */
            color: #FFFFFF;
            text-align: center;
            padding: 14px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 700;
            box-shadow: 0 4px 15px rgba(0, 98, 230, 0.2);
            transition: transform 0.2s, background 0.2s;
            margin-bottom: 36px;
        }

        .btn-get-started:hover {
            background: #0052c2;
            transform: translateY(-1px);
        }

        .features-title {
            font-size: 16px;
            font-weight: 700;
            color: #344054;
            margin-bottom: 24px;
            letter-spacing: -0.2px;
        }

        .feature-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .check-circle {
            width: 20px;
            height: 20px;
            background: #000000; /* Black circle */
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .check-circle svg {
            width: 12px;
            height: 12px;
            fill: #FFFFFF;
        }

        .feature-name {
            font-size: 15px;
            font-weight: 700;
            color: #121212;
        }

        /* FOOTER */
        .footer-wrapper {
            width: 100%;
            display: flex;
            justify-content: center;
            margin-top: auto;
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
            height: 45px;
            width: auto;
            display: block;
        }

        .footer-links {
            display: flex;
            gap: 32px;
        }

        .footer-link {
            font-size: 15px;
            font-weight: 700;
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
            .pricing-card {
                max-width: 100%;
            }
            .footer-pill {
                padding: 10px 24px;
            }
            .footer-links {
                gap: 20px;
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
                <a href="{{ url('/') }}#pricing" class="nav-link active">{{ __('layout.pricing') }}</a>
                <a href="{{ url('/') }}#digital-marketing" class="nav-link">{{ __('layout.service') }}</a>
                <a href="{{ route('FAQ') }}" class="nav-link">{{ __('layout.faq') }}</a>
                <a href="{{ route('login') }}" class="nav-link">{{ __('layout.sign_in') }}</a>
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
            <a href="{{ url('/') }}#pricing" class="mobile-nav-link">{{ __('layout.pricing') }}</a>
            <a href="{{ url('/') }}#digital-marketing" class="mobile-nav-link">{{ __('layout.service') }}</a>
            <a href="{{ route('FAQ') }}" class="mobile-nav-link">{{ __('layout.faq') }}</a>
            <a href="{{ route('login') }}" class="mobile-nav-link">{{ __('layout.sign_in') }}</a>
            <a href="{{ route('register') }}" class="mobile-btn-signup">{{ __('layout.sign_up_free') }}</a>
        </nav>
    </div>

    <!-- PRICING CONTENT -->
    <main id="main-content" style="display: flex; justify-content: center; width: 100%;">
    <section class="pricing-card">
        <div class="card-header">
            <h2>{{ __('public.pricing_free_title') }}</h2>
        </div>
        <div class="card-body">
            <div class="price-section">
                <span class="price-val">Gratis</span>
            </div>

            <a href="{{ route('register') }}" class="btn-get-started">{{ __('public.btn_get_started') }}</a>

            <h3 class="features-title">{{ __('public.pricing_free_everything') }}</h3>

            <ul class="feature-list">
                <li class="feature-item">
                    <span class="check-circle">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                    </span>
                    <span class="feature-name">{{ __('public.feat_unlimited_link') }}</span>
                </li>
                <li class="feature-item">
                    <span class="check-circle">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                    </span>
                    <span class="feature-name">{{ __('public.feat_digital_store') }}</span>
                </li>
                <li class="feature-item">
                    <span class="check-circle">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                    </span>
                    <span class="feature-name">{{ __('public.feat_statistic') }}</span>
                </li>
                <li class="feature-item">
                    <span class="check-circle">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                    </span>
                    <span class="feature-name">{{ __('public.feat_link_thumbnails') }}</span>
                </li>
                <li class="feature-item">
                    <span class="check-circle">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                    </span>
                    <span class="feature-name">{{ __('public.feat_templates') }}</span>
                </li>
                <li class="feature-item">
                    <span class="check-circle">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                    </span>
                    <span class="feature-name">{{ __('public.feat_custom_bg') }}</span>
                </li>
                <li class="feature-item">
                    <span class="check-circle">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                    </span>
                    <span class="feature-name">{{ __('public.feat_about_me') }}</span>
                </li>
                <li class="feature-item">
                    <span class="check-circle">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                    </span>
                    <span class="feature-name">{{ __('public.feat_email_notif') }}</span>
                </li>
                <li class="feature-item">
                    <span class="check-circle">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                    </span>
                    <span class="feature-name">{{ __('public.feat_donation_page') }}</span>
                </li>
                <li class="feature-item">
                    <span class="check-circle">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                    </span>
                    <span class="feature-name">{{ __('public.feat_transaction_fee') }}</span>
                </li>
            </ul>
        </div>
    </section>
    </main>

    <!-- FOOTER -->
    <footer class="footer-wrapper">
        <div class="footer-pill">
            <a href="{{ url('/') }}" class="footer-logo">
                <img src="{{ asset('images/Logo.svg') }}" alt="Linkan Logo">
            </a>
            <div class="footer-links">
                <a href="{{ route('about') }}" class="footer-link">{{ __('layout.about_us') }}</a>
                <a href="{{ route('contact.form') }}" class="footer-link">{{ __('layout.contact_us') }}</a>
            </div>
        </div>
    </footer>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
        });
    </script>
</body>
</html>