<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Linkan.ID</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pages/contact.css') }}">
</head>
<body>
    <!-- TOP NAVBAR -->
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

    <!-- HERO TITLE -->
    <h1 class="page-title">Letâ€™s talk with us</h1>

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
                <a href="{{ route('about') }}" class="footer-link scramble-link" data-value="{{ __('layout.about_us') }}">{{ __('layout.about_us') }}</a>
                <a href="{{ route('contact.form') }}" class="footer-link scramble-link active" data-value="{{ __('layout.contact_us') }}">{{ __('layout.contact_us') }}</a>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/pages/contact.js') }}"></script>
</body>
</html>
