<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('public.about_title') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: #EE8025; /* Solid Orange */
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            padding: 24px 20px;
            overflow-x: hidden;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        .lang-toggle-pill {
            display: flex;
            align-items: center;
            gap: 2px;
            padding: 3px;
            background: #f1f1f1;
            border-radius: 20px;
        }

        .lang-btn {
            padding: 6px 10px;
            border-radius: 16px;
            color: #666;
            font-size: 13px;
            font-weight: 700;
            transition: all 0.3s ease;
        }

        .lang-btn.active {
            background: #fff;
            color: #121212;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .mobile-lang-toggle {
            margin-bottom: 4px;
            transform: scale(1.1);
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

        /* ABOUT CONTAINER */
        .about-container {
            width: 100%;
            max-width: 840px;
            margin: 0 auto 40px auto;
            display: flex;
            flex-direction: column;
            gap: 32px;
        }

        .about-header {
            text-align: center;
            color: #FFFFFF;
            margin-bottom: 8px;
        }

        .about-header h1 {
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .about-header .subtitle {
            font-size: 18px;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.95);
        }

        /* CARDS */
        .about-card {
            background: #FFFFFF;
            border-radius: 40px; /* Fully rounded capsule style */
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            width: 100%;
        }

        .about-card h2 {
            font-size: 28px;
            font-weight: 800;
            color: #0062E6; /* Royal Blue */
            margin-bottom: 20px;
            letter-spacing: -0.5px;
        }

        .about-card p {
            font-size: 16px;
            font-weight: 600;
            color: #0062E6; /* Royal Blue */
            line-height: 1.6;
        }

        .about-card ul {
            list-style-position: inside;
            display: flex;
            flex-direction: column;
            gap: 12px;
            color: #0062E6; /* Royal Blue */
        }

        .about-card ul li {
            font-size: 15px;
            font-weight: 600;
            line-height: 1.5;
            padding-left: 4px;
        }

        /* FITUR UTAMA SECTION */
        .features-section {
            width: 100%;
            max-width: 1000px;
            margin: 20px auto 60px auto;
            text-align: center;
        }

        .features-title {
            font-size: 28px;
            font-weight: 800;
            color: #FFFFFF;
            margin-bottom: 32px;
            letter-spacing: -0.5px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            width: 100%;
        }

        .feature-item {
            background: #FFFFFF;
            border-radius: 30px;
            padding: 36px 24px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.08);
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: transform 0.2s ease;
        }

        .feature-item:hover {
            transform: translateY(-2px);
        }

        .feature-icon {
            width: 48px;
            height: 48px;
            fill: #0062E6;
            margin-bottom: 20px;
        }

        .feature-item h3 {
            font-size: 18px;
            font-weight: 800;
            color: #0062E6;
            margin-bottom: 12px;
            letter-spacing: -0.2px;
        }

        .feature-item p {
            font-size: 14px;
            font-weight: 600;
            color: #0062E6;
            line-height: 1.5;
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

        .mobile-nav-toggle {
            display: none;
            flex-direction: column;
            justify-content: space-between;
            width: 24px;
            height: 18px;
            padding: 0;
            background: transparent;
            border: none;
            cursor: pointer;
            z-index: 110;
        }

        .hamburger-line {
            width: 100%;
            height: 2px;
            background-color: #333;
            border-radius: 2px;
            transition: transform 0.3s ease, opacity 0.3s ease;
        }

        .mobile-nav-toggle.active .hamburger-line:nth-child(1) { transform: translateY(8px) rotate(45deg); }
        .mobile-nav-toggle.active .hamburger-line:nth-child(2) { opacity: 0; }
        .mobile-nav-toggle.active .hamburger-line:nth-child(3) { transform: translateY(-8px) rotate(-45deg); }

        .mobile-nav-overlay {
            position: fixed;
            inset: 0;
            z-index: 99;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.98);
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
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
        }

        .mobile-nav-link {
            color: #333;
            font-size: 20px;
            font-weight: 700;
        }

        .mobile-btn-signup {
            width: 100%;
            padding: 14px 40px;
            border-radius: 50px;
            background: #000;
            color: #fff;
            font-size: 18px;
            font-weight: 700;
            text-align: center;
        }

        /* Responsive design */
        @media (max-width: 900px) {
            .features-grid {
                grid-template-columns: 1fr;
                max-width: 400px;
                margin: 0 auto;
            }
        }

        @media (max-width: 768px) {
            .mobile-nav-toggle {
                display: flex;
            }
            .nav-links {
                display: none;
            }
            .navbar-pill {
                padding: 8px 16px;
            }
            .nav-links {
                gap: 16px;
            }
            .btn-signup {
                padding: 8px 16px;
                font-size: 13px;
            }
            .about-card {
                padding: 30px 24px;
            }
            .footer-pill {
                padding: 10px 24px;
            }
            .footer-links {
                gap: 20px;
            }
        }

        @media (max-width: 480px) {
            .navbar-pill {
                justify-content: space-between;
            }
        }
    </style>
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

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const mobileNavToggle = document.getElementById('mobileNavToggle');
            const mobileNavOverlay = document.getElementById('mobileNavOverlay');

            if (mobileNavToggle && mobileNavOverlay) {
                const closeMenu = () => {
                    mobileNavToggle.classList.remove('active');
                    mobileNavOverlay.classList.remove('active');
                    mobileNavToggle.setAttribute('aria-expanded', 'false');
                    mobileNavOverlay.setAttribute('aria-hidden', 'true');
                    document.body.style.overflow = '';
                };

                mobileNavToggle.addEventListener('click', () => {
                    const isActive = mobileNavToggle.classList.toggle('active');
                    mobileNavOverlay.classList.toggle('active', isActive);
                    mobileNavToggle.setAttribute('aria-expanded', isActive);
                    mobileNavOverlay.setAttribute('aria-hidden', !isActive);
                    document.body.style.overflow = isActive ? 'hidden' : '';
                });

                mobileNavOverlay.querySelectorAll('a').forEach(link => link.addEventListener('click', closeMenu));
            }

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
