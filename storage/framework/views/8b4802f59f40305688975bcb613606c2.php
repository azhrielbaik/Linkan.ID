<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Linkan - Powering Creators Economy</title>
    <meta name="description" content="Create your microsite, shorten links, and sell digital products all in one platform. Join the vibrant creators economy with Linkan.">
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/favicon.png')); ?>">
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
                #F68529 0%, 
                #E4721C 15%, 
                #1C64D3 42%, 
                #E92E38 72%, 
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
            padding: 8px 12px 8px 24px;
            width: 90%;
            max-width: 1000px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }

        .nav-logo {
            font-family: var(--font-heading);
            font-size: 22px;
            font-weight: 800;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 6px;
            letter-spacing: -0.5px;
        }

        .nav-links {
            display: flex;
            gap: 24px;
            align-items: center;
        }

        .nav-link {
            font-size: 14px;
            font-weight: 600;
            color: #333;
            transition: color 0.2s;
        }
        
        .nav-link:hover {
            color: var(--orange);
        }

        .btn-signup {
            background: #1D5DC6;
            color: #fff;
            padding: 10px 22px;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 700;
            transition: transform 0.2s, background 0.2s;
        }

        .btn-signup:hover {
            background: #15459b;
            transform: scale(1.05);
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

        .text-dark {
            color: var(--dark);
        }

        /* 1. Target the outer clip wrapper span */
        .hero-title > span:not(.text-dark),
        .hero-title > .text-dark > span {
            clip-path: inset(0px) !important;
            position: relative !important;
            vertical-align: bottom !important;
            display: inline-block !important;
            transform: translateZ(0) !important;
        }

        /* 2. Target the inner relative wrapper span */
        .hero-title > span:not(.text-dark) > span,
        .hero-title > .text-dark > span > span {
            position: relative !important;
            display: inline-block !important;
            vertical-align: bottom !important;
        }

        /* 3. Target the character span itself */
        .char-inner {
            display: inline-block !important;
            will-change: transform;
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

        .feature-mockup-wrapper {
            margin-top: 50px;
            width: 100%;
            max-width: 800px;
            display: flex;
            justify-content: center;
        }
        
        .feature-mockup-img {
            max-width: 100%;
            height: auto;
            filter: drop-shadow(0 20px 40px rgba(0,0,0,0.12));
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

        /* CREATOR SHOWCASE SECTION */
        .creator-showcase-section {
            padding: 0;
            margin-top: -60px; /* Overlap slightly with red gradient */
            position: relative;
            z-index: 10;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .showcase-container {
            position: relative;
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .showcase-img {
            max-width: 100%;
            width: 800px;
            height: auto;
            display: block;
            filter: drop-shadow(0 20px 40px rgba(0,0,0,0.15));
        }

        .floating-tag {
            position: absolute;
            background: #FFFFFF;
            color: #121212;
            font-family: var(--font-heading);
            font-weight: 800;
            font-size: 14px;
            padding: 12px 24px;
            border-radius: 50px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
            white-space: nowrap;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .floating-tag:hover {
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.16);
        }

        /* Position tags relative to the image container */
        .tag-1 {
            top: 15%;
            right: 32%;
            transform: translate(50%, -50%);
        }

        .tag-2 {
            top: 36%;
            right: 8%;
            transform: translateY(-50%);
        }

        .tag-3 {
            top: 58%;
            right: 5%;
            transform: translateY(-50%);
        }

        .tag-4 {
            bottom: 12%;
            right: 18%;
            transform: translate(50%, 50%);
        }

        /* Responsive styling for tags */
        @media (max-width: 768px) {
            .floating-tag {
                font-size: 11px;
                padding: 8px 16px;
            }
            .tag-1 { top: 15%; right: 28%; }
            .tag-2 { top: 36%; right: 5%; }
            .tag-3 { top: 58%; right: 2%; }
            .tag-4 { bottom: 12%; right: 15%; }
        }

        @media (max-width: 480px) {
            .floating-tag {
                font-size: 9px;
                padding: 6px 12px;
            }
            .tag-1 { top: 15%; right: 24%; }
            .tag-2 { top: 36%; right: 2%; }
            .tag-3 { top: 58%; right: -2%; }
            .tag-4 { bottom: 12%; right: 12%; }
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
            transform: scale(0.85) translateY(40px);
            transition: opacity 0.8s cubic-bezier(0.34, 1.56, 0.64, 1),
                        transform 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .reveal.active {
            opacity: 1;
            transform: scale(1) translateY(0);
        }

        .reveal-scale {
            opacity: 0;
            transform: scale(0.8);
            transition: opacity 0.8s cubic-bezier(0.34, 1.56, 0.64, 1),
                        transform 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
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
            <a href="<?php echo e(url('/')); ?>" class="nav-logo">
                <img src="<?php echo e(asset('images/Logo.png')); ?>" alt="Linkan Logo" style="height: 45px; width: auto;">
            </a>
            <div class="nav-links">
                <a href="<?php echo e(route('pricing')); ?>" class="nav-link">Pricing</a>
                <a href="<?php echo e(route('service')); ?>" class="nav-link">Service</a>
                <a href="<?php echo e(route('FAQ')); ?>" class="nav-link">FAQ</a>
                <a href="<?php echo e(route('login')); ?>" class="nav-link">Sign In</a>
                <a href="<?php echo e(route('register')); ?>" class="btn-signup">Sign Up</a>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section class="hero-section reveal">
        <h1 class="hero-title">#Powering <span class="text-dark">Creators</span> Economy</h1>
        <p class="hero-subtitle">
            Create Instant Mobile Webpage to sell your knowledge, Chat, Video Calls, Events, Digital Product. Share it across social media.
        </p>
        
        <form action="<?php echo e(route('register')); ?>" method="GET" class="claim-wrapper">
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

        <div class="feature-mockup-wrapper">
            <img src="<?php echo e(asset('images/landing page/Group 15.png')); ?>" alt="Linkan Dashboard Mockup" class="feature-mockup-img">
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

    <!-- CREATOR SHOWCASE SECTION -->
    <section class="creator-showcase-section reveal">
        <div class="showcase-container">
            <img src="<?php echo e(asset('images/landing page/Group 17.png')); ?>" alt="Linkan Creator Showcase" class="showcase-img">
    </section>

    <!-- FOOTER -->
    <footer class="footer-wrapper">
        <div class="footer-content">
            <div class="footer-left">
                <div class="footer-logo">
                    <img src="<?php echo e(asset('images/Logo.png')); ?>" alt="Linkan Logo" style="height: 45px; width: auto;">
                </div>
                <div class="footer-copyright">
                    © 2026 Linkan. Built for the Creator Economy.
                </div>
            </div>
            <div class="footer-links">
                <a href="<?php echo e(route('about')); ?>" class="footer-link">About Us</a>
                <a href="<?php echo e(route('contact.form')); ?>" class="footer-link">Contact Us</a>
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

    <script type="module">
        import { createTimeline, stagger, splitText } from 'https://esm.sh/animejs@4.5.0';

        document.addEventListener('DOMContentLoaded', () => {
            document.fonts.ready.then(() => {
                const heroTitle = document.querySelector('.hero-title');
                if (heroTitle) {
                    const split = splitText(heroTitle, {
                        lines: false,
                        words: false,
                        chars: {
                            class: 'char-inner',
                            wrap: 'hidden',
                            clone: 'bottom'
                        },
                    });

                    split.addEffect(({ chars }) => {
                        return createTimeline()
                        .add(chars, {
                            y: '-100%',
                            loop: true,
                            loopDelay: 350,
                            duration: 750,
                            ease: 'inOut(2)',
                        }, stagger(150, { from: 'center' }));
                    });
                }
            });
        });
    </script>
</body>
</html><?php /**PATH /home/rakanyuka/Documents/PKL/Linkan/resources/views/welcome.blade.php ENDPATH**/ ?>