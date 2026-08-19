<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Linkan.ID</title>
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
            background-color: #515cf6;
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

        /* TOP NAVBAR */
        .navbar-wrapper {
            width: 100%;
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .navbar-pill {
            background: #FFFFFF;
            border-radius: 50px;
            padding: 10px 14px 10px 28px;
            width: 100%;
            max-width: 960px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.06);
        }

        .nav-logo img {
            height: 42px;
            width: auto;
            display: block;
        }

        .nav-links {
            display: flex;
            gap: 28px;
            align-items: center;
        }

        .nav-link {
            font-size: 14.5px;
            font-weight: 600;
            color: #1E293B;
            transition: color 0.2s;
        }

        .nav-link:hover {
            color: #515cf6;
        }

        .btn-signup {
            background: #000000;
            color: #FFFFFF;
            padding: 10px 22px;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 700;
            transition: transform 0.2s, background 0.2s;
        }

        .btn-signup:hover {
            background: #222222;
            transform: scale(1.03);
        }

        /* PAGE TITLE */
        .page-title {
            color: #FFFFFF;
            font-size: 54px;
            font-weight: 800;
            text-align: center;
            margin: 20px 0 40px 0;
            letter-spacing: -0.5px;
        }

        /* CONTENT CONTAINER (2 CARDS) */
        .contact-container {
            width: 100%;
            max-width: 960px;
            display: flex;
            justify-content: center;
            align-items: stretch;
            gap: 28px;
            margin-bottom: 40px;
        }

        /* LEFT CARD: INFO */
        .info-card {
            background: #FFFFFF;
            border-radius: 24px;
            padding: 38px 32px;
            width: 350px;
            flex-shrink: 0;
            box-shadow: 0 15px 35px rgba(0,0,0,0.07);
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

        .info-card-title {
            color: #515cf6;
            font-size: 15px;
            font-weight: 600;
            line-height: 1.4;
            margin-bottom: 44px;
        }

        .info-list {
            display: flex;
            flex-direction: column;
            gap: 32px;
        }

        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            color: #515cf6;
            font-size: 14.5px;
            font-weight: 500;
            line-height: 1.45;
        }

        .info-icon {
            flex-shrink: 0;
            width: 22px;
            height: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #515cf6;
            margin-top: 1px;
        }

        .info-icon svg {
            width: 20px;
            height: 20px;
            fill: currentColor;
        }

        /* RIGHT CARD: FORM */
        .form-card {
            background: #FFFFFF;
            border-radius: 24px;
            padding: 38px 36px;
            flex: 1;
            box-shadow: 0 15px 35px rgba(0,0,0,0.07);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-row-2 {
            display: flex;
            gap: 16px;
            margin-bottom: 16px;
        }

        .form-group {
            margin-bottom: 16px;
            width: 100%;
        }

        .form-input, .form-textarea {
            width: 100%;
            border: 1.5px solid #8e99fa;
            border-radius: 26px;
            padding: 13px 22px;
            font-size: 13.5px;
            font-weight: 500;
            color: #1E293B;
            outline: none;
            background-color: #FFFFFF;
            transition: all 0.2s ease;
        }

        .form-input::placeholder, .form-textarea::placeholder {
            color: #8e99fa;
            font-weight: 400;
            opacity: 0.95;
        }

        .form-input:focus, .form-textarea:focus {
            border-color: #515cf6;
            box-shadow: 0 0 0 3px rgba(81, 92, 246, 0.15);
        }

        .form-textarea {
            border-radius: 20px;
            min-height: 110px;
            resize: vertical;
        }

        .btn-send {
            width: 100%;
            background: #515cf6;
            color: #FFFFFF;
            border: none;
            border-radius: 26px;
            padding: 14px 20px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 4px;
            box-shadow: 0 4px 14px rgba(81, 92, 246, 0.25);
        }

        .btn-send:hover {
            background: #414ce5;
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(81, 92, 246, 0.35);
        }

        .btn-send:active {
            transform: translateY(0);
        }

        .alert-success {
            background: #e6f4ea;
            color: #137333;
            padding: 12px 18px;
            border-radius: 14px;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 18px;
            text-align: center;
        }

        /* BOTTOM FOOTER */
        .footer-wrapper {
            width: 100%;
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .footer-pill {
            background: #FFFFFF;
            border-radius: 50px;
            padding: 16px 40px;
            width: 100%;
            max-width: 960px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.06);
        }

        .footer-logo img {
            height: 40px;
            width: auto;
            display: block;
        }

        .footer-links {
            display: flex;
            gap: 36px;
            align-items: center;
        }

        .footer-link {
            font-size: 16px;
            font-weight: 600;
            color: #121212;
            transition: color 0.2s;
        }

        .footer-link:hover {
            color: #515cf6;
        }

        .footer-link.active {
            color: #515cf6;
            font-weight: 700;
        }

        /* RESPONSIVE */
        @media (max-width: 860px) {
            .contact-container {
                flex-direction: column;
                align-items: center;
            }

            .info-card, .form-card {
                width: 100%;
                max-width: 540px;
            }

            .page-title {
                font-size: 40px;
                margin: 16px 0 28px 0;
            }

            .nav-links {
                gap: 16px;
            }
        }

        @media (max-width: 600px) {
            .form-row-2 {
                flex-direction: column;
                gap: 14px;
                margin-bottom: 0;
            }

            .navbar-pill {
                padding: 8px 16px;
            }

            .nav-links .nav-link {
                display: none;
            }

            .page-title {
                font-size: 32px;
            }

            .footer-pill {
                padding: 12px 24px;
            }
        }
    </style>
</head>
<body>
    <!-- TOP NAVBAR -->
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

    <!-- HERO TITLE -->
    <h1 class="page-title">Let’s talk with us</h1>

    <!-- CONTACT CONTAINER (2 CARDS) -->
    <div class="contact-container">
        <!-- LEFT CARD: INFO -->
        <div class="info-card">
            <p class="info-card-title">Comment, Suggestion?Simply Fill In the Form</p>
            <div class="info-list">
                <!-- Location -->
                <div class="info-item">
                    <div class="info-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                        </svg>
                    </div>
                    <div>
                        Jalan Jawa, no 18, Blok C,<br>Jawa Barat
                    </div>
                </div>

                <!-- Phone -->
                <div class="info-item">
                    <div class="info-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M6.62 10.79a15.053 15.053 0 006.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                        </svg>
                    </div>
                    <div>
                        09874526281786836
                    </div>
                </div>

                <!-- Email -->
                <div class="info-item">
                    <div class="info-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                        </svg>
                    </div>
                    <div>
                        linkanid@gmail.com
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT CARD: FORM -->
        <div class="form-card">
            @if(session('success'))
                <div class="alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('contact.send') }}" method="POST">
                @csrf

                <!-- Row 1: First Name & Last Name -->
                <div class="form-row-2">
                    <div style="flex: 1;">
                        <input type="text" name="first_name" class="form-input" placeholder="First Name*" required>
                    </div>
                    <div style="flex: 1;">
                        <input type="text" name="last_name" class="form-input" placeholder="Last Name*">
                    </div>
                </div>

                <!-- Row 2: Email -->
                <div class="form-group">
                    <input type="email" name="email" class="form-input" placeholder="Email*" required>
                </div>

                <!-- Row 3: Phone Number -->
                <div class="form-group">
                    <input type="text" name="phone" class="form-input" placeholder="Phone Number*">
                </div>

                <!-- Row 4: Message -->
                <div class="form-group" style="margin-bottom: 16px;">
                    <textarea name="message" class="form-textarea" placeholder="Message for............." required></textarea>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-send">Send Message</button>
            </form>
        </div>
    </div>

    <!-- BOTTOM FOOTER -->
    <div class="footer-wrapper">
        <div class="footer-pill">
            <a href="{{ url('/') }}" class="footer-logo">
                <img src="{{ asset('images/Logo.svg') }}" alt="Linkan Logo">
            </a>
            <div class="footer-links">
                <a href="{{ route('about') }}" class="footer-link">{{ __('layout.about_us') }}</a>
                <a href="{{ route('contact.form') }}" class="footer-link active">{{ __('layout.contact_us') }}</a>
            </div>
        </div>
    </div>
</body>
</html>
