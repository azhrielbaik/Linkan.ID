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

        /* Responsive design */
        @media (max-width: 900px) {
            .features-grid {
                grid-template-columns: 1fr;
                max-width: 400px;
                margin: 0 auto;
            }
        }

        @media (max-width: 768px) {
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
            .nav-links {
                display: none; /* Hide links on navbar on very small screens */
            }
            .navbar-pill {
                justify-content: space-between;
            }
        }
    </style>
</head>
<body>
    <!-- NAVBAR -->
    <div class="navbar-wrapper">
        <div class="navbar-pill">
            <a href="{{ url('/') }}" class="nav-logo">
                <img src="{{ asset('images/Logo.svg') }}" alt="Linkan Logo">
            </a>
            <div class="nav-links">
                <a href="{{ url('/') }}#pricing" class="nav-link">{{ __('layout.pricing') }}</a>
                <a href="{{ url('/') }}#digital-marketing" class="nav-link">{{ __('layout.service') }}</a>
                <a href="{{ route('FAQ') }}" class="nav-link">{{ __('layout.faq') }}</a>
                <a href="{{ route('login') }}" class="nav-link">{{ __('layout.sign_in') }}</a>
                <a href="{{ route('register') }}" class="btn-signup">{{ __('layout.sign_up_free') }}</a>
            </div>
        </div>
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
                <a href="{{ route('about') }}" class="footer-link">{{ __('layout.about_us') }}</a>
                <a href="{{ route('contact.form') }}" class="footer-link">{{ __('layout.contact_us') }}</a>
            </div>
        </div>
    </div>
</body>
</html>
