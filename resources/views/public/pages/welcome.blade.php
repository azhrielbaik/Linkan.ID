<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('public.meta_title') }}</title>
    <meta name="description" content="{{ __('public.meta_desc') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    <script src="{{ asset('js/pages/welcome-scroll.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/pages/welcome.css') }}">
</head>
<body>
    <a href="#main-content" class="skip-to-content">Skip to content</a>

    <!-- NAVBAR -->
    <header class="navbar-wrapper" id="navbarWrapper">
        <nav class="navbar-pill" aria-label="Main Navigation">
            <a href="{{ url('/') }}" class="nav-logo" aria-label="Linkan.ID Home">
                <img src="{{ asset('images/Logo.svg') }}" alt="Linkan Logo" class="logo-img">
            </a>
            <div class="nav-links">
                <div class="lang-toggle-pill" style="margin-right: 4px;">
                    <a href="{{ route('lang.switch', 'en') }}" class="lang-btn {{ App::getLocale() == 'en' ? 'active' : '' }}">EN</a>
                    <a href="{{ route('lang.switch', 'id') }}" class="lang-btn {{ App::getLocale() == 'id' ? 'active' : '' }}">ID</a>
                </div>
                <a href="#pricing" class="nav-link scramble-link" data-value="{{ __('layout.pricing') }}">{{ __('layout.pricing') }}</a>
                <a href="#digital-marketing" class="nav-link scramble-link" data-value="{{ __('layout.service') }}">{{ __('layout.service') }}</a>
                <a href="{{ route('FAQ') }}" class="nav-link scramble-link" data-value="{{ __('layout.faq') }}">{{ __('layout.faq') }}</a>
                <a href="{{ route('login') }}" class="nav-link scramble-link" data-value="{{ __('layout.sign_in') }}">{{ __('layout.sign_in') }}</a>
                <a href="{{ route('register') }}" class="btn-signup origin-btn relative overflow-hidden rounded-full border">
                    <span class="btn-bg"></span>
                    <span class="btn-text relative z-10">{{ __('layout.sign_up_free') }}</span>
                </a>
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
            <div class="lang-toggle-pill mobile-lang-toggle">
                <a href="{{ route('lang.switch', 'en') }}" class="lang-btn {{ App::getLocale() == 'en' ? 'active' : '' }}">EN</a>
                <a href="{{ route('lang.switch', 'id') }}" class="lang-btn {{ App::getLocale() == 'id' ? 'active' : '' }}">ID</a>
            </div>
            <a href="#pricing" class="mobile-nav-link scramble-link" data-value="{{ __('layout.pricing') }}">{{ __('layout.pricing') }}</a>
            <a href="#digital-marketing" class="mobile-nav-link scramble-link" data-value="{{ __('layout.service') }}">{{ __('layout.service') }}</a>
            <a href="{{ route('FAQ') }}" class="mobile-nav-link scramble-link" data-value="{{ __('layout.faq') }}">{{ __('layout.faq') }}</a>
            <a href="{{ route('login') }}" class="mobile-nav-link scramble-link" data-value="{{ __('layout.sign_in') }}">{{ __('layout.sign_in') }}</a>
            <a href="{{ route('register') }}" class="mobile-btn-signup origin-btn relative overflow-hidden rounded-full border">
                <span class="btn-bg"></span>
                <span class="btn-text relative z-10">{{ __('layout.sign_up_free') }}</span>
            </a>
        </nav>
    </div>

    <!-- MAIN CONTENT -->
    <main id="main-content">
        <!-- HERO SECTION -->
        <section class="hero-section reveal">
        <div class="hero-container">
            <div class="hero-content">
                <h1 class="hero-title">
                    <span class="title-line">{{ __('public.hero_title_1') }}</span>
                    <span class="title-line">{{ __('public.hero_title_2') }}</span>
                </h1>
                <p class="hero-subtitle">
                    {{ __('public.hero_subtitle') }}
                </p>

                <form action="{{ route('register') }}" method="GET" class="claim-wrapper">
                    <div class="claim-input-pill">
                        <span class="claim-prefix">Linkan.id/</span>
                        <input type="text" name="username" class="claim-input" placeholder="{{ __('public.claim_placeholder') }}" autocomplete="off" aria-label="Claim your username">
                    </div>
                    <button type="submit" class="btn-create origin-btn relative overflow-hidden rounded-full border">
                        <span class="btn-bg"></span>
                        <span class="btn-text relative z-10">{{ __('public.btn_create') }}</span>
                    </button>
                </form>
            </div>
            <div class="hero-image-wrapper">
                <img src="{{ asset('images/landing page/pria_laptop.webp') }}" alt="Powering Creators Economy" class="hero-img">
            </div>
        </div>
    </section>

    <!-- FEATURES SECTION -->
    <section class="features-section reveal">
        <h2 class="section-title" id="changing-title" style="min-height: 48px; overflow: hidden; position: relative;">{{ __('public.features_title') }}</h2>
        <p class="section-subtitle">{{ __('public.features_subtitle') }}</p>

        <div class="feature-pills">
            <div class="feature-pill">{{ __('public.feat_digital_product') }}</div>
            <div class="feature-pill">{{ __('public.feat_donations') }}</div>
            <div class="feature-pill">{{ __('public.feat_online_course') }}</div>
        </div>

        <div class="feature-mockup-wrapper">
            <img src="{{ asset('images/landing page/Group 15.png') }}" alt="Linkan Dashboard Mockup" class="feature-mockup-img">
        </div>
    </section>

    <!-- DIGITAL MARKETING SECTION -->
    <section class="digital-marketing-section reveal" id="digital-marketing">
        <div class="marketing-container">
            <div class="marketing-content">
                <h2 class="marketing-title">{!! __('public.marketing_title') !!}</h2>
                <p class="marketing-subtitle">
                    {{ __('public.marketing_subtitle') }}
                </p>
                <a href="{{ route('register') }}" class="btn-service origin-btn relative overflow-hidden rounded-full border">
                    <span class="btn-bg"></span>
                    <span class="btn-text relative z-10">{{ __('public.btn_service') }}</span>
                </a>
            </div>
            <div class="marketing-image-wrapper">
                <img src="{{ asset('images/landing page/handphone_besar.webp') }}" alt="Digital Marketing" class="marketing-img">
            </div>
        </div>
    </section>

    <!-- PRICING SECTION -->
    <section class="pricing-section reveal" id="pricing">
        <div class="pricing-grid">
            <!-- Basic -->
            <div class="pricing-card anime-pricing">
                <div class="pricing-header">
                    <h3 class="pricing-tier">{{ __('public.pricing_basic_title') }}</h3>
                </div>
                <div class="pricing-body">
                    <div class="pricing-price">Gratis</div>
                    <a href="{{ route('register') }}" class="btn-pricing btn-pricing-basic origin-btn relative overflow-hidden rounded-xl border">
                        <span class="btn-bg"></span>
                        <span class="btn-text relative z-10">{{ __('public.btn_get_started') }}</span>
                    </a>
                    <div class="pricing-features-title">{{ __('public.pricing_everything') }}</div>
                    <ul class="pricing-features">
                        <li><span class="pricing-check"></span> {{ __('public.feat_unlimited_link') }}</li>
                        <li><span class="pricing-check"></span> {{ __('public.feat_digital_store') }}</li>
                        <li><span class="pricing-check"></span> {{ __('public.feat_statistic') }}</li>
                        <li><span class="pricing-check"></span> {{ __('public.feat_link_thumbnails') }}</li>
                        <li><span class="pricing-check"></span> {{ __('public.feat_templates') }}</li>
                        <li><span class="pricing-check"></span> {{ __('public.feat_custom_bg') }}</li>
                    </ul>
                </div>
            </div>
            <!-- Standard -->
            <div class="pricing-card popular anime-pricing">
                <div class="popular-badge">{{ __('public.pricing_popular_badge') }}</div>
                <div class="pricing-header">
                    <h3 class="pricing-tier">{{ __('public.pricing_standard_title') }}</h3>
                </div>
                <div class="pricing-body">
                    <div class="pricing-price">Rp 99.000 <span>{{ __('public.pricing_month') }}</span></div>
                    <a href="{{ route('register') }}" class="btn-pricing btn-pricing-primary origin-btn relative overflow-hidden rounded-xl border">
                        <span class="btn-bg"></span>
                        <span class="btn-text relative z-10">{{ __('public.btn_get_started') }}</span>
                    </a>
                    <div class="pricing-features-title">{{ __('public.pricing_everything') }}</div>
                    <ul class="pricing-features">
                        <li><span class="pricing-check"></span> {{ __('public.feat_unlimited_link') }}</li>
                        <li><span class="pricing-check"></span> {{ __('public.feat_digital_store') }}</li>
                        <li><span class="pricing-check"></span> {{ __('public.feat_statistic') }}</li>
                        <li><span class="pricing-check"></span> {{ __('public.feat_link_thumbnails') }}</li>
                        <li><span class="pricing-check"></span> {{ __('public.feat_templates') }}</li>
                        <li><span class="pricing-check"></span> {{ __('public.feat_custom_bg') }}</li>
                        <li><span class="pricing-check"></span> {{ __('public.feat_about_me') }}</li>
                        <li><span class="pricing-check"></span> {{ __('public.feat_email_notif') }}</li>
                        <li><span class="pricing-check"></span> {{ __('public.feat_donation_page') }}</li>
                        <li><span class="pricing-check"></span> {{ __('public.feat_transaction_fee') }}</li>
                    </ul>
                </div>
            </div>
            <!-- Unlimited -->
            <div class="pricing-card anime-pricing">
                <div class="pricing-header">
                    <h3 class="pricing-tier">{{ __('public.pricing_unlimited_title') }}</h3>
                </div>
                <div class="pricing-body">
                    <div class="pricing-price">Rp 449.000 <span>{{ __('public.pricing_month') }}</span></div>
                    <a href="{{ route('register') }}" class="btn-pricing btn-pricing-primary origin-btn relative overflow-hidden rounded-xl border">
                        <span class="btn-bg"></span>
                        <span class="btn-text relative z-10">{{ __('public.btn_get_started') }}</span>
                    </a>
                    <div class="pricing-features-title">{{ __('public.pricing_everything') }}</div>
                    <ul class="pricing-features">
                        <li><span class="pricing-check"></span> {{ __('public.feat_unlimited_link') }}</li>
                        <li><span class="pricing-check"></span> {{ __('public.feat_digital_store') }}</li>
                        <li><span class="pricing-check"></span> {{ __('public.feat_statistic') }}</li>
                        <li><span class="pricing-check"></span> {{ __('public.feat_link_thumbnails') }}</li>
                        <li><span class="pricing-check"></span> {{ __('public.feat_templates') }}</li>
                        <li><span class="pricing-check"></span> {{ __('public.feat_custom_bg') }}</li>
                        <li><span class="pricing-check"></span> {{ __('public.feat_about_me') }}</li>
                        <li><span class="pricing-check"></span> {{ __('public.feat_email_notif') }}</li>
                        <li><span class="pricing-check"></span> {{ __('public.feat_donation_page') }}</li>
                        <li><span class="pricing-check"></span> {{ __('public.feat_transaction_fee') }}</li>
                        <li><span class="pricing-check"></span> {{ __('public.feat_priority_support') }}</li>
                        <li><span class="pricing-check"></span> {{ __('public.feat_custom_domain') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- TESTIMONIALS SECTION -->
    <section class="testimonials-section reveal">
        <div class="testi-header-container">
            <div class="testi-pill-badge">
                <span class="pulse-dot"></span>
                <span>Testimonial & Review</span>
            </div>
            <h2 class="section-title">{{ __('public.testi_title') }}</h2>
            <p class="section-subtitle" style="margin-top: 0.5rem; margin-bottom: 0;">
                {{ app()->getLocale() == 'id' ? 'Dipercaya oleh ribuan kreator, pebisnis online, dan profesional di seluruh Indonesia' : 'Trusted by thousands of creators, online sellers, and professionals' }}
            </p>
        </div>

        <div class="testimonials-marquee-wrapper">
            <!-- ROW 1: SLIDE LEFT -->
            <div class="testi-marquee-row">
                <div class="testi-track testi-track-left">
                    <!-- SET 1 (Unique Items 1 - 6) -->
                    <!-- Card 1 -->
                    <div class="testi-card">
                        <div class="testi-header">
                            <div class="testi-profile">
                                <div class="testi-avatar avatar-grad-1">R</div>
                                <div class="testi-meta">
                                    <div class="testi-name-wrap">
                                        <h4>RakanMY</h4>
                                        <span class="testi-verified" title="Verified Creator">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                        </span>
                                    </div>
                                    <span>@rakanmy</span>
                                </div>
                            </div>
                            <span class="testi-role-tag">Ã°Å¸Å¡â‚¬ Digital Creator</span>
                        </div>
                        <div class="testi-stars">
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        </div>
                        <p class="testi-text">"Linkan.id beneran ngebantu banget buat jualan e-book dan preset foto secara otomatis. Setup cuma 5 menit, langsung siap jualan!"</p>
                    </div>

                    <!-- Card 2 -->
                    <div class="testi-card">
                        <div class="testi-header">
                            <div class="testi-profile">
                                <div class="testi-avatar avatar-grad-2">F</div>
                                <div class="testi-meta">
                                    <div class="testi-name-wrap">
                                        <h4>Faris Berly</h4>
                                        <span class="testi-verified" title="Verified Creator">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                        </span>
                                    </div>
                                    <span>@frsbrly</span>
                                </div>
                            </div>
                            <span class="testi-role-tag">Ã°Å¸â€ºÂÃ¯Â¸Â Brand Founder</span>
                        </div>
                        <div class="testi-stars">
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        </div>
                        <p class="testi-text">"Solusi all-in-one terbaik untuk kelola link promosi dan katalog produk. Konversi checkout naik lebih dari 40% sejak pakai Linkan!"</p>
                    </div>

                    <!-- Card 3 -->
                    <div class="testi-card">
                        <div class="testi-header">
                            <div class="testi-profile">
                                <div class="testi-avatar avatar-grad-5">N</div>
                                <div class="testi-meta">
                                    <div class="testi-name-wrap">
                                        <h4>Nadia Safitri</h4>
                                        <span class="testi-verified" title="Verified Creator">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                        </span>
                                    </div>
                                    <span>@nadiasaf</span>
                                </div>
                            </div>
                            <span class="testi-role-tag">Ã¢Å“Â¨ Beauty & Lifestyle</span>
                        </div>
                        <div class="testi-stars">
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        </div>
                        <p class="testi-text">"Bio link Instagram aku sekarang jadi jauh lebih aesthetic dan elegan. Followers gampang banget nemu link barang rekomendasi aku."</p>
                    </div>

                    <!-- Card 4 -->
                    <div class="testi-card">
                        <div class="testi-header">
                            <div class="testi-profile">
                                <div class="testi-avatar avatar-grad-3">D</div>
                                <div class="testi-meta">
                                    <div class="testi-name-wrap">
                                        <h4>Dimas Pratama</h4>
                                        <span class="testi-verified" title="Verified Creator">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                        </span>
                                    </div>
                                    <span>@dimas.tech</span>
                                </div>
                            </div>
                            <span class="testi-role-tag">Ã°Å¸â€œÅ¡ Course Instructor</span>
                        </div>
                        <div class="testi-stars">
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        </div>
                        <p class="testi-text">"Jualan modul pembelajaran & video course jadi serba otomatis. Pembeli bayar via QRIS langsung dapat akses instan tanpa konfirmasi manual."</p>
                    </div>

                    <!-- Card 5 -->
                    <div class="testi-card">
                        <div class="testi-header">
                            <div class="testi-profile">
                                <div class="testi-avatar avatar-grad-7">C</div>
                                <div class="testi-meta">
                                    <div class="testi-name-wrap">
                                        <h4>Clara Veronica</h4>
                                        <span class="testi-verified" title="Verified Creator">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                        </span>
                                    </div>
                                    <span>@claraveron</span>
                                </div>
                            </div>
                            <span class="testi-role-tag">Ã°Å¸Å½Â¨ UI/UX Designer</span>
                        </div>
                        <div class="testi-stars">
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        </div>
                        <p class="testi-text">"Kustomisasi tampilannya fleksibel banget! Bisa sesuaikan warna, font, dan layout portofolio agar matching dengan visual identity saya."</p>
                    </div>

                    <!-- Card 6 -->
                    <div class="testi-card">
                        <div class="testi-header">
                            <div class="testi-profile">
                                <div class="testi-avatar avatar-grad-4">R</div>
                                <div class="testi-meta">
                                    <div class="testi-name-wrap">
                                        <h4>Rian Hidayat</h4>
                                        <span class="testi-verified" title="Verified Creator">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                        </span>
                                    </div>
                                    <span>@rianhidayat</span>
                                </div>
                            </div>
                            <span class="testi-role-tag">Ã°Å¸â€œÂ¦ E-Commerce Seller</span>
                        </div>
                        <div class="testi-stars">
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        </div>
                        <p class="testi-text">"Integrasi katalog tokonya simpel dan enteng. Gak perlu pusing bikin website mahal, cukup satu link Linkan sudah bisa jualan puluhan produk."</p>
                    </div>

                    <!-- SET 2 (DUPLICATE CLONE 1 - 6 FOR SEAMLESS LOOP) -->
                    <!-- Card 1 Duplicate -->
                    <div class="testi-card" aria-hidden="true">
                        <div class="testi-header">
                            <div class="testi-profile">
                                <div class="testi-avatar avatar-grad-1">R</div>
                                <div class="testi-meta">
                                    <div class="testi-name-wrap">
                                        <h4>RakanMY</h4>
                                        <span class="testi-verified">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                        </span>
                                    </div>
                                    <span>@rakanmy</span>
                                </div>
                            </div>
                            <span class="testi-role-tag">Ã°Å¸Å¡â‚¬ Digital Creator</span>
                        </div>
                        <div class="testi-stars">
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        </div>
                        <p class="testi-text">"Linkan.id beneran ngebantu banget buat jualan e-book dan preset foto secara otomatis. Setup cuma 5 menit, langsung siap jualan!"</p>
                    </div>

                    <!-- Card 2 Duplicate -->
                    <div class="testi-card" aria-hidden="true">
                        <div class="testi-header">
                            <div class="testi-profile">
                                <div class="testi-avatar avatar-grad-2">F</div>
                                <div class="testi-meta">
                                    <div class="testi-name-wrap">
                                        <h4>Faris Berly</h4>
                                        <span class="testi-verified">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                        </span>
                                    </div>
                                    <span>@frsbrly</span>
                                </div>
                            </div>
                            <span class="testi-role-tag">Ã°Å¸â€ºÂÃ¯Â¸Â Brand Founder</span>
                        </div>
                        <div class="testi-stars">
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        </div>
                        <p class="testi-text">"Solusi all-in-one terbaik untuk kelola link promosi dan katalog produk. Konversi checkout naik lebih dari 40% sejak pakai Linkan!"</p>
                    </div>

                    <!-- Card 3 Duplicate -->
                    <div class="testi-card" aria-hidden="true">
                        <div class="testi-header">
                            <div class="testi-profile">
                                <div class="testi-avatar avatar-grad-5">N</div>
                                <div class="testi-meta">
                                    <div class="testi-name-wrap">
                                        <h4>Nadia Safitri</h4>
                                        <span class="testi-verified">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                        </span>
                                    </div>
                                    <span>@nadiasaf</span>
                                </div>
                            </div>
                            <span class="testi-role-tag">Ã¢Å“Â¨ Beauty & Lifestyle</span>
                        </div>
                        <div class="testi-stars">
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        </div>
                        <p class="testi-text">"Bio link Instagram aku sekarang jadi jauh lebih aesthetic dan elegan. Followers gampang banget nemu link barang rekomendasi aku."</p>
                    </div>

                    <!-- Card 4 Duplicate -->
                    <div class="testi-card" aria-hidden="true">
                        <div class="testi-header">
                            <div class="testi-profile">
                                <div class="testi-avatar avatar-grad-3">D</div>
                                <div class="testi-meta">
                                    <div class="testi-name-wrap">
                                        <h4>Dimas Pratama</h4>
                                        <span class="testi-verified">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                        </span>
                                    </div>
                                    <span>@dimas.tech</span>
                                </div>
                            </div>
                            <span class="testi-role-tag">Ã°Å¸â€œÅ¡ Course Instructor</span>
                        </div>
                        <div class="testi-stars">
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        </div>
                        <p class="testi-text">"Jualan modul pembelajaran & video course jadi serba otomatis. Pembeli bayar via QRIS langsung dapat akses instan tanpa konfirmasi manual."</p>
                    </div>

                    <!-- Card 5 Duplicate -->
                    <div class="testi-card" aria-hidden="true">
                        <div class="testi-header">
                            <div class="testi-profile">
                                <div class="testi-avatar avatar-grad-7">C</div>
                                <div class="testi-meta">
                                    <div class="testi-name-wrap">
                                        <h4>Clara Veronica</h4>
                                        <span class="testi-verified">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                        </span>
                                    </div>
                                    <span>@claraveron</span>
                                </div>
                            </div>
                            <span class="testi-role-tag">Ã°Å¸Å½Â¨ UI/UX Designer</span>
                        </div>
                        <div class="testi-stars">
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        </div>
                        <p class="testi-text">"Kustomisasi tampilannya fleksibel banget! Bisa sesuaikan warna, font, dan layout portofolio agar matching dengan visual identity saya."</p>
                    </div>

                    <!-- Card 6 Duplicate -->
                    <div class="testi-card" aria-hidden="true">
                        <div class="testi-header">
                            <div class="testi-profile">
                                <div class="testi-avatar avatar-grad-4">R</div>
                                <div class="testi-meta">
                                    <div class="testi-name-wrap">
                                        <h4>Rian Hidayat</h4>
                                        <span class="testi-verified">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                        </span>
                                    </div>
                                    <span>@rianhidayat</span>
                                </div>
                            </div>
                            <span class="testi-role-tag">Ã°Å¸â€œÂ¦ E-Commerce Seller</span>
                        </div>
                        <div class="testi-stars">
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        </div>
                        <p class="testi-text">"Integrasi katalog tokonya simpel dan enteng. Gak perlu pusing bikin website mahal, cukup satu link Linkan sudah bisa jualan puluhan produk."</p>
                    </div>
                </div>
            </div>

            <!-- ROW 2: SLIDE RIGHT -->
            <div class="testi-marquee-row">
                <div class="testi-track testi-track-right">
                    <!-- SET 1 (Unique Items 7 - 12) -->
                    <!-- Card 7 -->
                    <div class="testi-card">
                        <div class="testi-header">
                            <div class="testi-profile">
                                <div class="testi-avatar avatar-grad-6">M</div>
                                <div class="testi-meta">
                                    <div class="testi-name-wrap">
                                        <h4>Muhammad Azriel</h4>
                                        <span class="testi-verified" title="Verified Creator">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                        </span>
                                    </div>
                                    <span>@mhmdazrl</span>
                                </div>
                            </div>
                            <span class="testi-role-tag">Ã°Å¸â€™Â¡ Tech Creator</span>
                        </div>
                        <div class="testi-stars">
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        </div>
                        <p class="testi-text">"Platform yang sangat intuitif dan cepat. Fitur analitik kliknya akurat banget untuk optimasi performa campaign di media sosial."</p>
                    </div>

                    <!-- Card 8 -->
                    <div class="testi-card">
                        <div class="testi-header">
                            <div class="testi-profile">
                                <div class="testi-avatar avatar-grad-9">B</div>
                                <div class="testi-meta">
                                    <div class="testi-name-wrap">
                                        <h4>Bayu Aditya</h4>
                                        <span class="testi-verified" title="Verified Creator">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                        </span>
                                    </div>
                                    <span>@bayuaditya</span>
                                </div>
                            </div>
                            <span class="testi-role-tag">Ã°Å¸â€œË† Affiliate Marketer</span>
                        </div>
                        <div class="testi-stars">
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        </div>
                        <p class="testi-text">"Komisi affiliate naik signifikan sejak pakai Linkan. Halamannya terbuka super kilat di smartphone dan minim bounce rate."</p>
                    </div>

                    <!-- Card 9 -->
                    <div class="testi-card">
                        <div class="testi-header">
                            <div class="testi-profile">
                                <div class="testi-avatar avatar-grad-8">S</div>
                                <div class="testi-meta">
                                    <div class="testi-name-wrap">
                                        <h4>Siti Rahmawati</h4>
                                        <span class="testi-verified" title="Verified Creator">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                        </span>
                                    </div>
                                    <span>@siti_craft</span>
                                </div>
                            </div>
                            <span class="testi-role-tag">Ã°Å¸Â§Â¶ Artisan & Handmade</span>
                        </div>
                        <div class="testi-stars">
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        </div>
                        <p class="testi-text">"Suka banget sama desainnya yang bersih dan rapi. Pelanggan gak kebingungan lagi saat mau order custom merchandise via WhatsApp."</p>
                    </div>

                    <!-- Card 10 -->
                    <div class="testi-card">
                        <div class="testi-header">
                            <div class="testi-profile">
                                <div class="testi-avatar avatar-grad-10">K</div>
                                <div class="testi-meta">
                                    <div class="testi-name-wrap">
                                        <h4>Kevin Santoso</h4>
                                        <span class="testi-verified" title="Verified Creator">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                        </span>
                                    </div>
                                    <span>@kevinsan</span>
                                </div>
                            </div>
                            <span class="testi-role-tag">Ã°Å¸Å½â„¢Ã¯Â¸Â Podcaster & Host</span>
                        </div>
                        <div class="testi-stars">
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        </div>
                        <p class="testi-text">"Linkan jadi pusat navigasi untuk episode podcast, form sponsorship, dan link donasi komunitas kami. Sangat powerful dan recommended!"</p>
                    </div>

                    <!-- Card 11 -->
                    <div class="testi-card">
                        <div class="testi-header">
                            <div class="testi-profile">
                                <div class="testi-avatar avatar-grad-11">M</div>
                                <div class="testi-meta">
                                    <div class="testi-name-wrap">
                                        <h4>Maya Anggraini</h4>
                                        <span class="testi-verified" title="Verified Creator">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                        </span>
                                    </div>
                                    <span>@mayangrn</span>
                                </div>
                            </div>
                            <span class="testi-role-tag">Ã¢Å“Ë†Ã¯Â¸Â Travel Blogger</span>
                        </div>
                        <div class="testi-stars">
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        </div>
                        <p class="testi-text">"Desain responsifnya juara! Semua link rekomendasi hotel, kuliner, dan itinerary tersusun cantik tanpa bikin audiens pusing."</p>
                    </div>

                    <!-- Card 12 -->
                    <div class="testi-card">
                        <div class="testi-header">
                            <div class="testi-profile">
                                <div class="testi-avatar avatar-grad-12">B</div>
                                <div class="testi-meta">
                                    <div class="testi-name-wrap">
                                        <h4>Bagas Triputra</h4>
                                        <span class="testi-verified" title="Verified Creator">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                        </span>
                                    </div>
                                    <span>@bagastri</span>
                                </div>
                            </div>
                            <span class="testi-role-tag">Ã°Å¸â€œÂ¸ Commercial Photo</span>
                        </div>
                        <div class="testi-stars">
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        </div>
                        <p class="testi-text">"Portofolio photoshoot dan jadwal booking foto jadi jauh lebih profesional. Klien baru langsung yakin dari first impression!"</p>
                    </div>

                    <!-- SET 2 (DUPLICATE CLONE 7 - 12 FOR SEAMLESS LOOP) -->
                    <!-- Card 7 Duplicate -->
                    <div class="testi-card" aria-hidden="true">
                        <div class="testi-header">
                            <div class="testi-profile">
                                <div class="testi-avatar avatar-grad-6">M</div>
                                <div class="testi-meta">
                                    <div class="testi-name-wrap">
                                        <h4>Muhammad Azriel</h4>
                                        <span class="testi-verified">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                        </span>
                                    </div>
                                    <span>@mhmdazrl</span>
                                </div>
                            </div>
                            <span class="testi-role-tag">Ã°Å¸â€™Â¡ Tech Creator</span>
                        </div>
                        <div class="testi-stars">
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        </div>
                        <p class="testi-text">"Platform yang sangat intuitif dan cepat. Fitur analitik kliknya akurat banget untuk optimasi performa campaign di media sosial."</p>
                    </div>

                    <!-- Card 8 Duplicate -->
                    <div class="testi-card" aria-hidden="true">
                        <div class="testi-header">
                            <div class="testi-profile">
                                <div class="testi-avatar avatar-grad-9">B</div>
                                <div class="testi-meta">
                                    <div class="testi-name-wrap">
                                        <h4>Bayu Aditya</h4>
                                        <span class="testi-verified">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                        </span>
                                    </div>
                                    <span>@bayuaditya</span>
                                </div>
                            </div>
                            <span class="testi-role-tag">Ã°Å¸â€œË† Affiliate Marketer</span>
                        </div>
                        <div class="testi-stars">
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        </div>
                        <p class="testi-text">"Komisi affiliate naik signifikan sejak pakai Linkan. Halamannya terbuka super kilat di smartphone dan minim bounce rate."</p>
                    </div>

                    <!-- Card 9 Duplicate -->
                    <div class="testi-card" aria-hidden="true">
                        <div class="testi-header">
                            <div class="testi-profile">
                                <div class="testi-avatar avatar-grad-8">S</div>
                                <div class="testi-meta">
                                    <div class="testi-name-wrap">
                                        <h4>Siti Rahmawati</h4>
                                        <span class="testi-verified">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                        </span>
                                    </div>
                                    <span>@siti_craft</span>
                                </div>
                            </div>
                            <span class="testi-role-tag">Ã°Å¸Â§Â¶ Artisan & Handmade</span>
                        </div>
                        <div class="testi-stars">
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        </div>
                        <p class="testi-text">"Suka banget sama desainnya yang bersih dan rapi. Pelanggan gak kebingungan lagi saat mau order custom merchandise via WhatsApp."</p>
                    </div>

                    <!-- Card 10 Duplicate -->
                    <div class="testi-card" aria-hidden="true">
                        <div class="testi-header">
                            <div class="testi-profile">
                                <div class="testi-avatar avatar-grad-10">K</div>
                                <div class="testi-meta">
                                    <div class="testi-name-wrap">
                                        <h4>Kevin Santoso</h4>
                                        <span class="testi-verified">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                        </span>
                                    </div>
                                    <span>@kevinsan</span>
                                </div>
                            </div>
                            <span class="testi-role-tag">Ã°Å¸Å½â„¢Ã¯Â¸Â Podcaster & Host</span>
                        </div>
                        <div class="testi-stars">
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        </div>
                        <p class="testi-text">"Linkan jadi pusat navigasi untuk episode podcast, form sponsorship, dan link donasi komunitas kami. Sangat powerful dan recommended!"</p>
                    </div>

                    <!-- Card 11 Duplicate -->
                    <div class="testi-card" aria-hidden="true">
                        <div class="testi-header">
                            <div class="testi-profile">
                                <div class="testi-avatar avatar-grad-11">M</div>
                                <div class="testi-meta">
                                    <div class="testi-name-wrap">
                                        <h4>Maya Anggraini</h4>
                                        <span class="testi-verified">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                        </span>
                                    </div>
                                    <span>@mayangrn</span>
                                </div>
                            </div>
                            <span class="testi-role-tag">Ã¢Å“Ë†Ã¯Â¸Â Travel Blogger</span>
                        </div>
                        <div class="testi-stars">
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        </div>
                        <p class="testi-text">"Desain responsifnya juara! Semua link rekomendasi hotel, kuliner, dan itinerary tersusun cantik tanpa bikin audiens pusing."</p>
                    </div>

                    <!-- Card 12 Duplicate -->
                    <div class="testi-card" aria-hidden="true">
                        <div class="testi-header">
                            <div class="testi-profile">
                                <div class="testi-avatar avatar-grad-12">B</div>
                                <div class="testi-meta">
                                    <div class="testi-name-wrap">
                                        <h4>Bagas Triputra</h4>
                                        <span class="testi-verified">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                        </span>
                                    </div>
                                    <span>@bagastri</span>
                                </div>
                            </div>
                            <span class="testi-role-tag">Ã°Å¸â€œÂ¸ Commercial Photo</span>
                        </div>
                        <div class="testi-stars">
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        </div>
                        <p class="testi-text">"Portofolio photoshoot dan jadwal booking foto jadi jauh lebih profesional. Klien baru langsung yakin dari first impression!"</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CREATOR SHOWCASE SECTION -->
    <section class="creator-showcase-section reveal">
        <div class="showcase-container">
            <img src="{{ asset('images/landing page/wanita_laptop.webp') }}" alt="Linkan Creator Showcase" class="showcase-img" loading="lazy">

            <div class="floating-tag anime-tag tag-1">{{ __('public.creator_tag_1') }}</div>
            <div class="floating-tag anime-tag tag-2">{{ __('public.creator_tag_2') }}</div>
            <div class="floating-tag anime-tag tag-3">{{ __('public.creator_tag_3') }}</div>
            <div class="floating-tag anime-tag tag-4">{{ __('public.creator_tag_4') }}</div>
        </div>
    </section>
    </main>

    <!-- FOOTER -->
    <footer class="footer-wrapper">
        <div class="footer-content">
            <div class="footer-left">
                <div class="footer-logo">
                    <img src="{{ asset('images/Logo.svg') }}" alt="Linkan Logo" style="height: 45px; width: auto;">
                </div>
                <div class="footer-copyright">
                    {{ __('public.footer_copyright') }}
                </div>
            </div>
            <div class="footer-links">
                <a href="{{ route('about') }}" class="footer-link scramble-link" data-value="{{ __('layout.about_us') }}">{{ __('layout.about_us') }}</a>
                <a href="{{ route('contact.form') }}" class="footer-link scramble-link" data-value="{{ __('layout.contact_us') }}">{{ __('layout.contact_us') }}</a>
            </div>
        </div>
    </footer>

    <!-- INTERSECTION OBSERVER FOR ANIMATIONS -->
    <script src="{{ asset('js/pages/welcome.js') }}"></script>

    <script type="module" src="{{ asset('js/pages/welcome-animation.js') }}"></script>
</body>
</html>
