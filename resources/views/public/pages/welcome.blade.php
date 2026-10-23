<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#5A5BF1">
    <meta name="format-detection" content="telephone=no">
    <title>{{ __('public.meta_title') }}</title>
    <meta name="description" content="{{ __('public.meta_desc') }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url('/') }}">

    <!-- Open Graph / Facebook / WhatsApp -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:site_name" content="Linkan.ID">
    <meta property="og:title" content="{{ __('public.meta_title') }}">
    <meta property="og:description" content="{{ __('public.meta_desc') }}">
    <meta property="og:image" content="{{ asset('images/og-banner.png') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:locale" content="{{ app()->getLocale() == 'id' ? 'id_ID' : 'en_US' }}">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@LinkanID">
    <meta name="twitter:title" content="{{ __('public.meta_title') }}">
    <meta name="twitter:description" content="{{ __('public.meta_desc') }}">
    <meta name="twitter:image" content="{{ asset('images/og-banner.png') }}">

    <!-- Schema.org WebSite (untuk Sitelink Search Box di Google) -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebSite",
      "name": "Linkan.ID",
      "url": "{{ url('/') }}",
      "description": "{{ __('public.meta_desc') }}",
      "inLanguage": "{{ app()->getLocale() == 'id' ? 'id-ID' : 'en-US' }}",
      "potentialAction": {
        "@type": "SearchAction",
        "target": {
          "@type": "EntryPoint",
          "urlTemplate": "{{ url('/') }}/{username}"
        },
        "query-input": "required name=username"
      }
    }
    </script>

    <!-- Schema.org Organization -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "Linkan.ID",
      "url": "{{ url('/') }}",
      "logo": "{{ asset('images/Logo.png') }}",
      "sameAs": []
    }
    </script>

    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    <!-- Preconnect & optimized font loading -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Preload Hero LCP Image -->
    <link rel="preload" as="image" href="{{ asset('images/landing page/pria_laptop.webp') }}" fetchpriority="high">

    <!-- Mark JavaScript availability early for progressive enhancement -->
    <script>document.documentElement.classList.add('js');</script>

    <!-- Script dengan defer agar tidak render-blocking -->
    <script src="{{ asset('js/pages/welcome-scroll.js') }}" defer></script>
    <link rel="stylesheet" href="{{ asset('css/pages/welcome.css') }}">
</head>
<body>
    <a href="#main-content" class="skip-to-content">Skip to content</a>

    <!-- NAVBAR -->
    <header class="navbar-wrapper" id="navbarWrapper">
        <nav class="navbar-pill" aria-label="Main Navigation">
            <a href="{{ url('/') }}" class="nav-logo" aria-label="Linkan.ID Home">
                <img src="{{ asset('images/Logo.svg') }}" alt="Linkan Logo" class="logo-img" width="107" height="30">
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
            <button class="mobile-nav-toggle" id="mobileNavToggle" aria-label="{{ __('public.aria_menu_open') }}" aria-expanded="false" aria-controls="mobileNavOverlay">
                <span class="hamburger-box">
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                </span>
            </button>
        </nav>
    </header>

    <!-- MOBILE NAVIGATION OVERLAY -->
    <div class="mobile-nav-overlay" id="mobileNavOverlay" aria-hidden="true" role="dialog" aria-modal="true" aria-label="Menu Navigasi Mobile">
        <div class="mobile-nav-container">
            <nav class="mobile-nav-menu" aria-label="Mobile Navigation Links">
                <a href="#pricing" class="mobile-nav-link" data-value="{{ __('layout.pricing') }}">
                    <span>{{ __('layout.pricing') }}</span>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </a>
                <a href="#digital-marketing" class="mobile-nav-link" data-value="{{ __('layout.service') }}">
                    <span>{{ __('layout.service') }}</span>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </a>
                <a href="{{ route('FAQ') }}" class="mobile-nav-link" data-value="{{ __('layout.faq') }}">
                    <span>{{ __('layout.faq') }}</span>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </a>
            </nav>

            <div class="mobile-nav-divider"></div>

            <div class="mobile-lang-wrap">
                <div class="lang-toggle-pill mobile-lang-pill">
                    <a href="{{ route('lang.switch', 'id') }}" class="lang-btn {{ App::getLocale() == 'id' ? 'active' : '' }}">Bahasa Indonesia</a>
                    <a href="{{ route('lang.switch', 'en') }}" class="lang-btn {{ App::getLocale() == 'en' ? 'active' : '' }}">English</a>
                </div>
            </div>

            <div class="mobile-nav-actions">
                <a href="{{ route('login') }}" class="mobile-btn-login">
                    <span>{{ __('layout.sign_in') }}</span>
                </a>
                <a href="{{ route('register') }}" class="mobile-btn-signup">
                    <span>{{ __('layout.sign_up_free') }}</span>
                </a>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <main id="main-content">
        <!-- HERO SECTION (Rendered without .reveal for instant LCP) -->
        <section class="hero-section">
            <div class="hero-container">
                <div class="hero-content">
                    <h1 class="hero-title spotlight-text" id="spotlightText">
                        <span class="title-line">{{ __('public.hero_title_1') }}</span>
                        <span class="title-line">{{ __('public.hero_title_2') }}</span>
                    </h1>
                    <p class="hero-subtitle">
                        {{ __('public.hero_subtitle') }}
                    </p>

                    <form action="{{ route('register') }}" method="GET" class="claim-wrapper">
                        <div class="claim-input-pill">
                            <label for="heroClaimInput" class="sr-only">Claim username</label>
                            <span class="claim-prefix">Linkan.id/</span>
                            <input type="text" id="heroClaimInput" name="username" class="claim-input" placeholder="{{ __('public.claim_placeholder') }}" autocomplete="off" autocapitalize="none" autocorrect="off" spellcheck="false" inputmode="text" enterkeyhint="go" maxlength="30" pattern="[A-Za-z0-9._-]+" aria-label="Claim your username">
                        </div>
                        <button type="submit" class="btn-create origin-btn relative overflow-hidden rounded-full border">
                            <span class="btn-bg"></span>
                            <span class="btn-text relative z-10">{{ __('public.btn_create') }}</span>
                        </button>
                    </form>
                    <div class="hero-trust-microcopy">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        <span>{{ __('public.trust_microcopy') }}</span>
                    </div>
                </div>
                <div class="hero-image-wrapper">
                    <img src="{{ asset('images/landing page/pria_laptop.webp') }}" alt="Powering Creators Economy" class="hero-img" width="1866" height="2669" fetchpriority="high" decoding="async">
                </div>
            </div>
        </section>

    <!-- FEATURES SECTION -->
    <section class="features-section reveal" id="features">
        <div class="features-motion-header" id="featuresMotionHeader">
            <!-- KINETIC VERTICAL FADE IN/OUT LOOPING TEXT CONTAINER -->
            <div class="vertical-looper-container" id="verticalLooperContainer">
                <div class="kinetic-ambient-glow" aria-hidden="true"></div>

                <!-- Floating Kinetic Star / Sparkle Accents -->
                <div class="kinetic-sparkle kinetic-sparkle-1" aria-hidden="true">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 0L14.59 9.41L24 12L14.59 14.59L12 24L9.41 14.59L0 12L9.41 9.41L12 0Z"/>
                    </svg>
                </div>
                <div class="kinetic-sparkle kinetic-sparkle-2" aria-hidden="true">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 0L14.59 9.41L24 12L14.59 14.59L12 24L9.41 14.59L0 12L9.41 9.41L12 0Z"/>
                    </svg>
                </div>

                <div class="vertical-looper-stage" id="verticalLooperStage" aria-live="polite">
                    @foreach(__('public.features_rotating') as $index => $item)
                        @php
                            $isArr = is_array($item);
                            $prefix = $isArr ? ($item['prefix'] ?? '') : $item;
                            $highlight = $isArr ? ($item['highlight'] ?? '') : '';
                            $suffix = $isArr ? ($item['suffix'] ?? '') : '';
                        @endphp
                        <h2 class="section-title vertical-looper-item {{ $index === 0 ? 'is-active' : '' }}" data-index="{{ $index }}" aria-hidden="{{ $index === 0 ? 'false' : 'true' }}">
                            @if($isArr && !empty($highlight))
                                <span class="kinetic-word">{{ $prefix }}</span>
                                <span class="kinetic-highlight-wrapper">
                                    <span class="kinetic-highlight-text">{{ $highlight }}</span>
                                    <span class="kinetic-shimmer-sweep" aria-hidden="true"></span>
                                </span>
                                @if(!empty($suffix))
                                    <span class="kinetic-word">{{ $suffix }}</span>
                                @endif
                            @else
                                <span>{{ $prefix }}</span>
                            @endif
                        </h2>
                    @endforeach
                </div>
            </div>

            <p class="section-subtitle">{{ __('public.features_subtitle') }}</p>
        </div>

        <div class="feature-pills">
            <div class="feature-pill">{{ __('public.feat_digital_product') }}</div>
            <div class="feature-pill">{{ __('public.feat_donations') }}</div>
            <div class="feature-pill">{{ __('public.feat_online_course') }}</div>
        </div>

        <div class="feature-mockup-wrapper">
            <img src="{{ asset('images/landing page/Group 15.png') }}" alt="Linkan Dashboard Mockup" class="feature-mockup-img" width="636" height="651" loading="lazy" decoding="async">
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
                <img src="{{ asset('images/landing page/handphone_besar.webp') }}" alt="Digital Marketing" class="marketing-img" width="2044" height="3213" loading="lazy" decoding="async">
            </div>
        </div>
    </section>

    <!-- PRICING SECTION -->
    <section class="pricing-section reveal" id="pricing">
        <div class="pricing-swipe-hint" id="pricingSwipeHint" aria-hidden="true">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            <span>{{ __('public.pricing_swipe_hint') }}</span>
        </div>

        <div class="pricing-grid" id="pricingGrid" role="region" aria-roledescription="carousel" aria-label="Pilihan Paket Layanan Linkan" tabindex="0">
            <!-- Basic -->
            <div class="pricing-card anime-pricing" id="pricingCard-0">
                <div class="pricing-header">
                    <h3 class="pricing-tier">{{ __('public.pricing_basic_title') }}</h3>
                </div>
                <div class="pricing-body">
                    <div class="pricing-price">{{ __('public.pricing_free') }}</div>
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
            <div class="pricing-card popular anime-pricing" id="pricingCard-1">
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
            <div class="pricing-card anime-pricing" id="pricingCard-2">
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
        <div class="pricing-dots" id="pricingDots" role="tablist" aria-label="Indikator Paket">
            <button type="button" class="pricing-dot" role="tab" aria-label="Paket Dasar" aria-selected="false" data-index="0"></button>
            <button type="button" class="pricing-dot active" role="tab" aria-label="Paket Standar (Paling Populer)" aria-selected="true" data-index="1"></button>
            <button type="button" class="pricing-dot" role="tab" aria-label="Paket Tanpa Batas" aria-selected="false" data-index="2"></button>
        </div>
    </section>

    <!-- TESTIMONIALS SECTION -->
    @php
    $testimonials = [
        [
            'avatar' => 'R',
            'grad' => 'avatar-grad-1',
            'name' => 'RakanMY',
            'handle' => '@rakanmy',
            'role' => '🌟 Digital Creator',
            'text' => '"Linkan.id beneran ngebantu banget buat jualan e-book dan preset foto secara otomatis. Setup cuma 5 menit, langsung siap jualan!"'
        ],
        [
            'avatar' => 'F',
            'grad' => 'avatar-grad-2',
            'name' => 'Faris Berly',
            'handle' => '@frsbrly',
            'role' => '🛍️ Brand Founder',
            'text' => '"Solusi all-in-one terbaik untuk kelola link promosi dan katalog produk. Konversi checkout naik lebih dari 40% sejak pakai Linkan!"'
        ],
        [
            'avatar' => 'N',
            'grad' => 'avatar-grad-5',
            'name' => 'Nadia Safitri',
            'handle' => '@nadiasftr',
            'role' => '✨ Beauty & Lifestyle',
            'text' => '"Semua kolaborasi brand, link affiliate Shopee & TikTok tersusun rapi. Tampilannya clean, aesthetic, dan super gampang di-update."'
        ],
        [
            'avatar' => 'D',
            'grad' => 'avatar-grad-3',
            'name' => 'Dimas Pratama',
            'handle' => '@dimaspratama',
            'role' => '📚 Course Instructor',
            'text' => '"Fitur monetisasinya luar biasa. Murid-murid saya gampang banget beli modul video dan join webinar tanpa kendala pembayaran."'
        ],
        [
            'avatar' => 'C',
            'grad' => 'avatar-grad-4',
            'name' => 'Clara Veronica',
            'handle' => '@claradesign',
            'role' => '🎨 UI/UX Designer',
            'text' => '"Sebagai desainer, estetika itu nomor satu. Linkan kasih kebebasan kustomisasi warna dan layout yang pas dengan personal branding saya."'
        ],
        [
            'avatar' => 'R',
            'grad' => 'avatar-grad-7',
            'name' => 'Rian Hidayat',
            'handle' => '@rian_store',
            'role' => '📦 E-Commerce Seller',
            'text' => '"Shortlink dan analitiknya detail banget! Saya bisa tracking campaign Instagram Ads mana yang paling banyak menghasilkan penjualan."'
        ],
        [
            'avatar' => 'M',
            'grad' => 'avatar-grad-6',
            'name' => 'Muhammad Azriel',
            'handle' => '@mhmdazrl',
            'role' => '💡 Tech Creator',
            'text' => '"Platform yang sangat intuitif dan cepat. Fitur analitik kliknya akurat banget untuk optimasi performa campaign di media sosial."'
        ],
        [
            'avatar' => 'B',
            'grad' => 'avatar-grad-9',
            'name' => 'Bayu Aditya',
            'handle' => '@bayuaditya',
            'role' => '📈 Affiliate Marketer',
            'text' => '"Komisi affiliate naik signifikan sejak pakai Linkan. Halamannya terbuka super kilat di smartphone dan minim bounce rate."'
        ],
        [
            'avatar' => 'S',
            'grad' => 'avatar-grad-8',
            'name' => 'Siti Rahmawati',
            'handle' => '@siti_craft',
            'role' => '🧶 Artisan & Handmade',
            'text' => '"Suka banget sama desainnya yang bersih dan rapi. Pelanggan gak kebingungan lagi saat mau order custom merchandise via WhatsApp."'
        ],
        [
            'avatar' => 'K',
            'grad' => 'avatar-grad-10',
            'name' => 'Kevin Santoso',
            'handle' => '@kevinsan',
            'role' => '🎙️ Podcaster & Host',
            'text' => '"Linkan jadi pusat navigasi untuk episode podcast, form sponsorship, dan link donasi komunitas kami. Sangat powerful dan recommended!"'
        ],
        [
            'avatar' => 'M',
            'grad' => 'avatar-grad-11',
            'name' => 'Maya Anggraini',
            'handle' => '@mayangrn',
            'role' => '✈️ Travel Blogger',
            'text' => '"Desain responsifnya juara! Semua link rekomendasi hotel, kuliner, dan itinerary tersusun cantik tanpa bikin audiens pusing."'
        ],
        [
            'avatar' => 'B',
            'grad' => 'avatar-grad-12',
            'name' => 'Bagas Triputra',
            'handle' => '@bagastri',
            'role' => '📸 Commercial Photo',
            'text' => '"Portofolio photoshoot dan jadwal booking foto jadi jauh lebih profesional. Klien baru langsung yakin dari first impression!"'
        ],
    ];
    $row1 = array_slice($testimonials, 0, 6);
    $row2 = array_slice($testimonials, 6, 6);
    $mobileTestimonials = array_slice($testimonials, 0, 4);
    @endphp

    <section class="testimonials-section reveal">
        <div class="testi-header-container">
            <div class="testi-pill-badge">
                <span class="pulse-dot"></span>
                <span>Testimonial & Review</span>
            </div>
            <h2 class="section-title">{{ __('public.testi_title') }}</h2>
            <p class="section-subtitle" style="margin-top: 0.5rem; margin-bottom: 0;">
                {{ __('public.testi_subtitle') }}
            </p>
        </div>

        <!-- MOBILE TESTIMONIALS CAROUSEL (<= 1023px, Compact Swiper Carousel) -->
        <div class="testi-mobile-wrapper">
            <div class="testi-mobile-carousel" id="testiMobileCarousel" role="region" aria-roledescription="carousel" aria-label="Ulasan Pengguna Linkan" tabindex="0">
                @foreach($mobileTestimonials as $idx => $item)
                <div class="testi-card testi-card-compact" id="testiMobileCard-{{ $idx }}">
                    <div class="testi-header">
                        <div class="testi-profile">
                            <div class="testi-avatar {{ $item['grad'] }}">{{ $item['avatar'] }}</div>
                            <div class="testi-meta">
                                <div class="testi-name-wrap">
                                    <h4>{{ $item['name'] }}</h4>
                                    <span class="testi-verified" title="Verified Creator">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                    </span>
                                </div>
                                <span>{{ $item['handle'] }}</span>
                            </div>
                        </div>
                        <span class="testi-role-tag">{{ $item['role'] }}</span>
                    </div>
                    <div class="testi-stars">
                        @for($s = 0; $s < 5; $s++)
                        <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        @endfor
                    </div>
                    <p class="testi-text">{{ $item['text'] }}</p>
                </div>
                @endforeach
            </div>
            <div class="testi-dots testi-mobile-dots" id="testiDots" role="tablist" aria-label="Navigasi Ulasan">
                @foreach($mobileTestimonials as $idx => $item)
                <button type="button" class="testi-dot {{ $idx === 0 ? 'active' : '' }}" role="tab" aria-label="Ulasan {{ $idx + 1 }} dari {{ count($mobileTestimonials) }}" aria-selected="{{ $idx === 0 ? 'true' : 'false' }}" data-index="{{ $idx }}"></button>
                @endforeach
            </div>
        </div>

        <!-- DESKTOP TESTIMONIALS MARQUEE (>= 1024px, Identical Infinite Loop) -->
        <div class="testimonials-marquee-wrapper testi-desktop-marquee">
            <!-- ROW 1: SLIDE LEFT -->
            <div class="testi-marquee-row">
                <div class="testi-track testi-track-left">
                    @foreach([$row1, $row1] as $loopIdx => $set)
                        @foreach($set as $item)
                        <div class="testi-card" @if($loopIdx === 1) aria-hidden="true" @endif>
                            <div class="testi-header">
                                <div class="testi-profile">
                                    <div class="testi-avatar {{ $item['grad'] }}">{{ $item['avatar'] }}</div>
                                    <div class="testi-meta">
                                        <div class="testi-name-wrap">
                                            <h4>{{ $item['name'] }}</h4>
                                            <span class="testi-verified" title="Verified Creator">
                                                <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                            </span>
                                        </div>
                                        <span>{{ $item['handle'] }}</span>
                                    </div>
                                </div>
                                <span class="testi-role-tag">{{ $item['role'] }}</span>
                            </div>
                            <div class="testi-stars">
                                @for($s = 0; $s < 5; $s++)
                                <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                @endfor
                            </div>
                            <p class="testi-text">{{ $item['text'] }}</p>
                        </div>
                        @endforeach
                    @endforeach
                </div>
            </div>

            <!-- ROW 2: SLIDE RIGHT -->
            <div class="testi-marquee-row">
                <div class="testi-track testi-track-right">
                    @foreach([$row2, $row2] as $loopIdx => $set)
                        @foreach($set as $item)
                        <div class="testi-card" @if($loopIdx === 1) aria-hidden="true" @endif>
                            <div class="testi-header">
                                <div class="testi-profile">
                                    <div class="testi-avatar {{ $item['grad'] }}">{{ $item['avatar'] }}</div>
                                    <div class="testi-meta">
                                        <div class="testi-name-wrap">
                                            <h4>{{ $item['name'] }}</h4>
                                            <span class="testi-verified" title="Verified Creator">
                                                <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                            </span>
                                        </div>
                                        <span>{{ $item['handle'] }}</span>
                                    </div>
                                </div>
                                <span class="testi-role-tag">{{ $item['role'] }}</span>
                            </div>
                            <div class="testi-stars">
                                @for($s = 0; $s < 5; $s++)
                                <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                @endfor
                            </div>
                            <p class="testi-text">{{ $item['text'] }}</p>
                        </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- CREATOR SHOWCASE SECTION -->
    <section class="creator-showcase-section reveal">
        <div class="showcase-container">
            <img src="{{ asset('images/landing page/wanita_laptop.webp') }}" 
                 alt="Linkan Creator Showcase" 
                 class="showcase-img" 
                 width="794" 
                 height="720" 
                 loading="lazy" 
                 decoding="async">

            <div class="showcase-tags-wrapper">
                <div class="floating-tag anime-tag tag-1">{{ __('public.creator_tag_1') }}</div>
                <div class="floating-tag anime-tag tag-2">{{ __('public.creator_tag_2') }}</div>
                <div class="floating-tag anime-tag tag-3">{{ __('public.creator_tag_3') }}</div>
                <div class="floating-tag anime-tag tag-4">{{ __('public.creator_tag_4') }}</div>
            </div>
        </div>
    </section>
    </main>

    <!-- STICKY MOBILE CTA (<= 639px) -->
    <aside class="mobile-sticky-cta" id="mobileStickyCta" aria-label="Aksi Cepat">
        <div class="mobile-sticky-cta-inner">
            <div class="mobile-sticky-cta-info">
                <span class="mobile-sticky-brand">Linkan.ID</span>
                <span class="mobile-sticky-sub">{{ __('public.trust_microcopy') }}</span>
            </div>
            <a href="{{ route('register') }}" class="mobile-sticky-btn">
                <span>{{ __('public.cta_sticky_button') }}</span>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
    </aside>

    <!-- FOOTER -->
    <footer class="footer-wrapper">
        <div class="footer-content">
            <div class="footer-left">
                <div class="footer-logo">
                    <img src="{{ asset('images/Logo.svg') }}" alt="Linkan Logo" width="125" height="35" loading="lazy">
                </div>
                <div class="footer-copyright">
                    {{ __('public.footer_copyright') }}
                </div>
            </div>
            <div class="footer-links">
                <a href="{{ route('about') }}" class="footer-link scramble-link" data-value="{{ __('layout.about_us') }}">{{ __('layout.about_us') }}</a>
                <a href="{{ route('contact.form') }}" class="footer-link scramble-link" data-value="{{ __('layout.contact_us') }}">{{ __('layout.contact_us') }}</a>
                <a href="{{ route('public.dispute.create') }}" class="footer-link scramble-link" data-value="{{ __('layout.dispute_center') }}">{{ __('layout.dispute_center') }}</a>
            </div>
        </div>
    </footer>

    <!-- INTERSECTION OBSERVER FOR ANIMATIONS -->
    <script src="{{ asset('js/pages/welcome.js') }}"></script>

    <script type="module" src="{{ asset('js/pages/welcome-animation.js') }}"></script>


</body>
</html>
