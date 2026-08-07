<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Linkan - Powering Creators Economy</title>
    <meta name="description" content="Create your microsite, shorten links, and sell digital products all in one platform. Join the vibrant creators economy with Linkan.">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    <style>
        :root {
            --font-heading: 'Plus Jakarta Sans', sans-serif;
            --font-body: 'Plus Jakarta Sans', sans-serif;
            --dark: #121212;
            --orange: #ED842C;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-body);
            color: #fff;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            background: linear-gradient(
                180deg, 
                #DE8654 0%, 
                #D97F4E 15%, 
                #3B76CD 40%, 
                #3B76CD 55%, 
                #F94B55 80%, 
                #FFFFFF 100%
            );
            min-height: 100vh;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* NAVBAR */
        .navbar-wrapper {
            width: 100%;
            padding: 24px;
            display: flex;
            justify-content: center;
            position: fixed;
            top: 0;
            z-index: 100;
        }

        .navbar-pill {
            background: #FFFFFF;
            border-radius: 50px;
            padding: 10px 10px 10px 24px;
            width: 90%;
            max-width: 1000px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .nav-logo {
            font-family: var(--font-heading);
            font-size: 22px;
            font-weight: 800;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 6px;
            margin-left: 11px;
            letter-spacing: -0.5px;
        }

        .nav-logo-icon {
            color: var(--orange);
            display: flex;
            align-items: center;
        }

        .nav-links {
            display: flex;
            gap: 24px;
            align-items: center;
        }

        .nav-link {
            font-size: 14px;
            font-weight: 500;
            color: #333;
            transition: color 0.2s;
        }
        
        .nav-link:hover {
            color: var(--orange);
        }

        .btn-signup {
            background: var(--dark);
            color: #fff;
            padding: 12px 20px;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 700;
            margin-right: 13px;
            transition: transform 0.2s, background 0.2s;
        }

        .btn-signup:hover {
            transform: scale(1.05);
            background: #000;
        }

        /* MAIN SECTIONS */
        section {
            padding: 80px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .hero-section {
            padding-top: 180px;
            padding-bottom: 60px;
        }

        .hero-title {
            font-family: var(--font-heading);
            font-size: clamp(2.5rem, 6vw, 4.5rem);
            font-weight: 800;
            letter-spacing: -0.02em;
            margin-bottom: 1.5rem;
            color: #FFFFFF;
            text-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .hero-subtitle {
            font-size: clamp(1rem, 2vw, 1.15rem);
            color: rgba(255, 255, 255, 0.95);
            font-weight: 400;
            line-height: 1.6;
            max-width: 600px;
            margin: 0 auto 3rem;
        }

        /* CLAIM FORM */
        .claim-wrapper {
            display: flex;
            gap: 12px;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            max-width: 600px;
            width: 100%;
        }

        .claim-input-pill {
            background: #FFFFFF;
            padding: 14px 24px;
            border-radius: 50px;
            display: flex;
            align-items: center;
            gap: 8px;
            flex: 1;
            min-width: 250px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        }

        .claim-prefix {
            font-weight: 800;
            color: var(--dark);
            font-size: 16px;
        }

        .claim-input {
            border: none;
            background: transparent;
            font-size: 16px;
            font-family: var(--font-body);
            flex: 1;
            outline: none;
            color: var(--dark);
        }

        .claim-input::placeholder {
            color: #aaa;
            font-weight: 400;
        }

        .btn-create {
            background: #FFFFFF;
            color: var(--dark);
            border: none;
            padding: 14px 36px;
            border-radius: 50px;
            font-weight: 800;
            font-size: 16px;
            cursor: pointer;
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
            transition: transform 0.2s;
        }

        .btn-create:hover {
            transform: scale(1.05);
        }

        /* FEATURES SECTION */
        .features-section {
            padding-top: 100px;
            padding-bottom: 80px;
        }

        .section-title {
            font-family: var(--font-heading);
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 800;
            margin-bottom: 1rem;
            text-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .section-subtitle {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 3rem;
        }

        .feature-pills {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .feature-pill {
            background: #FFFFFF;
            color: var(--dark);
            font-weight: 700;
            padding: 14px 32px;
            border-radius: 50px;
            font-size: 14px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.05);
            transition: transform 0.2s;
        }
        
        .feature-pill:hover {
            transform: translateY(-5px);
        }

        /* TESTIMONIALS SECTION */
        .testimonials-section {
            padding-top: 120px;
            padding-bottom: 60px;
        }

        .testi-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            max-width: 1100px;
            margin: 0 auto;
            width: 100%;
        }

        .testi-card {
            background: #FFFFFF;
            border-radius: 30px;
            padding: 30px;
            text-align: left;
            box-shadow: 0 15px 35px rgba(0,0,0,0.08);
            color: var(--dark);
        }

        .testi-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .testi-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 18px;
        }

        .avatar-red { background: #FF4A50; }
        .avatar-orange { background: #ED842C; }

        .testi-meta h4 {
            font-size: 16px;
            font-weight: 800;
            color: var(--dark);
            line-height: 1.2;
        }

        .testi-meta span {
            font-size: 12px;
            color: #888;
        }

        .testi-text {
            font-size: 13px;
            line-height: 1.6;
            color: #555;
            font-weight: 500;
        }

        /* IMAGE SECTION */
        .image-section {
            padding: 0;
            margin-top: -60px; /* Overlap slightly with red gradient */
            position: relative;
            z-index: 10;
        }

        .image-section img {
            max-width: 100%;
            width: 800px;
            height: auto;
            display: block;
            margin: 0 auto;
            filter: drop-shadow(0 20px 40px rgba(0,0,0,0.15));
        }

        /* FOOTER */
        .footer-wrapper {
            background: transparent;
            padding: 40px 5%;
            color: var(--dark);
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .footer-left {
            display: flex;
            align-items: center;
            gap: 30px;
            flex-wrap: wrap;
        }

        .footer-logo {
            font-family: var(--font-heading);
            font-size: 22px;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 6px;
            letter-spacing: -0.5px;
        }

        .footer-copyright {
            font-size: 14px;
            color: #666;
            font-weight: 500;
        }

        .footer-links {
            display: flex;
            gap: 30px;
        }

        .footer-link {
            font-size: 14px;
            font-weight: 600;
            color: #333;
            transition: color 0.2s;
        }

        .footer-link:hover {
            color: var(--orange);
        }

        /* ANIMATIONS */
        .reveal {
            opacity: 0;
            transform: translateY(40px);
            transition: all 0.8s cubic-bezier(0.5, 0, 0, 1);
        }

        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        .reveal-scale {
            opacity: 0;
            transform: scale(0.9);
            transition: all 0.8s cubic-bezier(0.5, 0, 0, 1);
        }

        .reveal-scale.active {
            opacity: 1;
            transform: scale(1);
        }

        /* Responsive */
        @media (max-width: 900px) {
            .testi-grid {
                grid-template-columns: 1fr;
                max-width: 400px;
            }
        }

        @media (max-width: 768px) {
            .nav-links { display: none; }
            .hero-title { font-size: 2.2rem; }
            .claim-wrapper { flex-direction: column; }
            .claim-input-pill, .btn-create { width: 100%; justify-content: center; }
            .footer-content { flex-direction: column; text-align: center; justify-content: center; }
            .footer-left { flex-direction: column; gap: 15px; }
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar-wrapper">
        <div class="navbar-pill">
            <a href="{{ url('/') }}" class="nav-logo">
                <img src="{{ asset('images/Logo.png') }}" alt="Linkan Logo" style="height: 50px; width: auto;">
            </a>
            <div class="nav-links">
                <a href="{{ route('pricing') }}" class="nav-link">Pricing</a>
                <a href="{{ route('service') }}" class="nav-link">Service</a>
                <a href="{{ route('FAQ') }}" class="nav-link">FAQ</a>
                <a href="{{ route('login') }}" class="nav-link">Sign In</a>
            </div>
            <a href="{{ route('register') }}" class="btn-signup">Sign Up</a>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section class="hero-section reveal">
        <h1 class="hero-title">#PoweringCreatorsEconomy</h1>
        <p class="hero-subtitle">
            Create Instant Mobile Webpage to sell your knowledge, Chat, Video Calls, Events, Digital Product. Share it across social media.
        </p>
        
        <form action="{{ route('register') }}" method="GET" class="claim-wrapper">
            <div class="claim-input-pill">
                <span class="claim-prefix">Linkan.id/</span>
                <input type="text" name="username" class="claim-input" placeholder="YourNameHere" autocomplete="off">
            </div>
            <button type="submit" class="btn-create">Create</button>
        </form>
    </section>

    <!-- FEATURES SECTION -->
    <section class="features-section reveal">
        <h2 class="section-title">Not just another link-in-bio</h2>
        <p class="section-subtitle">Linkan.id take care of your entire workflow, start to finish.</p>
        
        <div class="feature-pills">
            <div class="feature-pill">Digital Product</div>
            <div class="feature-pill">Donations</div>
            <div class="feature-pill">Online Course</div>
        </div>
    </section>

    <!-- TESTIMONIALS SECTION -->
    <section class="testimonials-section reveal">
        <h2 class="section-title">See What People Are Saying</h2>
        
        <div class="testi-grid">
            <div class="testi-card reveal-scale">
                <div class="testi-header">
                    <div class="testi-avatar avatar-red"></div>
                    <div class="testi-meta">
                        <h4>RakanMY</h4>
                        <span>@rakanmy</span>
                    </div>
                </div>
                <p class="testi-text">"Sangat membantu saya untuk berjualan digital product dengan mudah dan cepat tanpa ribet. Tampilannya juga sangat premium."</p>
            </div>
            
            <div class="testi-card reveal-scale" style="transition-delay: 0.1s;">
                <div class="testi-header">
                    <div class="testi-avatar" style="background: #E8E8E8; color: #555;">F</div>
                    <div class="testi-meta">
                        <h4>Frsbrly</h4>
                        <span>@frsbrly</span>
                    </div>
                </div>
                <p class="testi-text">"Linkan.id memberikan solusi terbaik untuk mengelola semua link dan produk digital saya dalam satu tempat. Luar biasa!"</p>
            </div>
            
            <div class="testi-card reveal-scale" style="transition-delay: 0.2s;">
                <div class="testi-header">
                    <div class="testi-avatar avatar-orange"></div>
                    <div class="testi-meta">
                        <h4>mhmdazrl</h4>
                        <span>@mhmdazrl</span>
                    </div>
                </div>
                <p class="testi-text">"Platform yang sangat intuitif dan mudah digunakan. Sangat direkomendasikan untuk kreator pemula maupun profesional."</p>
            </div>
        </div>
    </section>

    <!-- IMAGE SECTION -->
    <section class="image-section reveal">
        <img src="{{ asset('images/hero_laptop.png') }}" alt="Linkan Dashboard Presentation">
    </section>

    <!-- FOOTER -->
    <footer class="footer-wrapper">
        <div class="footer-content">
            <div class="footer-left">
                <div class="footer-logo">
                    <img src="{{ asset('images/Logo.png') }}" alt="Linkan Logo" style="height: 100px; width: auto;">
                </div>
                <div class="footer-copyright">
                    © 2026 Linkan. Built for the Creator Economy.
                </div>
            </div>
            <div class="footer-links">
                <a href="{{ route('about') }}" class="footer-link">About Us</a>
                <a href="{{ route('contact.form') }}" class="footer-link">Contact Us</a>
            </div>
        </div>
    </footer>

    <!-- INTERSECTION OBSERVER FOR ANIMATIONS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const reveals = document.querySelectorAll('.reveal, .reveal-scale');
            
            const revealOptions = {
                threshold: 0.15,
                rootMargin: "0px 0px -50px 0px"
            };

            const revealOnScroll = new IntersectionObserver(function(entries, observer) {
                entries.forEach(entry => {
                    if (!entry.isIntersecting) {
                        return;
                    } else {
                        entry.target.classList.add('active');
                        observer.unobserve(entry.target);
                    }
                });
            }, revealOptions);

            reveals.forEach(reveal => {
                revealOnScroll.observe(reveal);
            });
            
            // Trigger immediately for elements already in viewport on load
            setTimeout(() => {
                reveals.forEach(reveal => {
                    const rect = reveal.getBoundingClientRect();
                    if(rect.top < window.innerHeight) {
                        reveal.classList.add('active');
                    }
                });
            }, 100);
        });
    </script>
</body>
</html>