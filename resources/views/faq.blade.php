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

        /* Accessibility: High Visibility Focus */
        *:focus-visible {
            outline: 3px solid #FFD700 !important;
            outline-offset: 2px !important;
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
            padding: 8px 12px 8px 24px;
            width: 100%;
            max-width: 1000px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.06);
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
        .kb-card {
            background: #FFFFFF;
            border-radius: 30px;
            width: 100%;
            max-width: 800px;
            padding: 40px 32px;
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

        .kb-search-container {
            display: flex;
            justify-content: center;
            gap: 16px;
            width: 100%;
            flex-wrap: wrap;
        }

        .kb-search-input {
            background: #6B6DFF;
            color: #FFFFFF;
            border: none;
            border-radius: 50px;
            padding: 14px 28px;
            width: 100%;
            max-width: 400px;
            font-size: 15px;
            font-weight: 500;
            outline: none;
        }

        .kb-search-input::placeholder {
            color: rgba(255, 255, 255, 0.85);
            font-weight: 500;
        }

        .kb-search-btn {
            background: #5455E2;
            color: #FFFFFF;
            border: none;
            border-radius: 50px;
            padding: 14px 32px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s, transform 0.2s;
        }

        .kb-search-btn:hover {
            background: #4142D6;
            transform: scale(1.05);
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
            .kb-search-container {
                flex-direction: column;
            }
            .kb-search-input {
                max-width: 100%;
            }
            .kb-search-btn {
                width: 100%;
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
                <img src="{{ asset('images/Logo.png') }}" alt="Linkan Logo">
            </a>
            <div class="nav-links">
                <a href="{{ url('/') }}#pricing" class="nav-link">{{ __('layout.pricing') }}</a>
                <a href="{{ url('/') }}#digital-marketing" class="nav-link">{{ __('layout.service') }}</a>
                <a href="{{ route('FAQ') }}" class="nav-link active">{{ __('layout.faq') }}</a>
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

    <main id="main-content">
    <!-- KNOWLEDGE BASE CARD -->
    <div class="kb-card">
        <h1 class="kb-title">Linkan.id Knowledge Base</h1>
        <p class="kb-subtitle">Discover what Linkan.id can do to help you achieve your goals</p>
        <div class="kb-search-container">
            <input type="text" class="kb-search-input" placeholder="e.g. custom domain" aria-label="Search Knowledge Base">
            <button class="kb-search-btn">Search</button>
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
                <img src="{{ asset('images/Logo.png') }}" alt="Linkan Logo">
            </a>
            <div class="footer-links">
                <a href="{{ route('about') }}" class="footer-link">{{ __('layout.about_us') }}</a>
                <a href="{{ route('contact.form') }}" class="footer-link">{{ __('layout.contact_us') }}</a>
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
        });
    </script>
</body>
</html>
