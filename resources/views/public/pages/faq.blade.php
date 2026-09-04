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
    <script src="{{ asset('js/pages/faq-config.js') }}"></script>
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="{{ asset('css/pages/faq.css') }}">
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
    <script src="{{ asset('js/pages/faq.js') }}"></script>
</body>
</html>
