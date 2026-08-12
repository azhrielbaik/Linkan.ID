<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Linkan - Powering Creators Economy</title>
    <meta name="description" content="Create your microsite, shorten links, and sell digital products all in one platform. Join the vibrant creators economy with Linkan.">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    <script>
        // Force the page to always load at the very top (bypassing browser scroll restoration)
        if ('scrollRestoration' in history) {
            history.scrollRestoration = 'manual';
        }
        window.scrollTo(0, 0);
    </script>
    <style>
        :root {
            --font-heading: 'Plus Jakarta Sans', sans-serif;
            --font-body: 'Plus Jakarta Sans', sans-serif;
            --dark: #121212;
            --orange: #5A5BF1;
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
                #5A5BF1 0%, 
                #91E7DA 100%
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

        .logo-img {
            height: 45px;
            width: auto;
            transition: height 0.3s ease;
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
            background: #000000;
            color: #fff;
            padding: 10px 24px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 700;
            transition: transform 0.2s, background 0.2s;
        }

        .btn-signup:hover {
            background: #222222;
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

        /* HERO SECTION REDESIGN */
        .hero-section {
            padding-top: 50px;
            padding-bottom: 60px;
            width: 100%;
            display: flex;
            justify-content: center;
        }

        .hero-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 1200px;
            width: 100%;
            gap: 40px;
            text-align: left;
        }

        .hero-content {
            flex: 1.1;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .hero-image-wrapper {
            flex: 0.8;
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        .hero-img {
            width: 100%;
            height: auto;
            max-height: 600px;
            filter: drop-shadow(0 20px 40px rgba(0,0,0,0.15));
        }

        .hero-title {
            font-family: var(--font-heading);
            font-size: clamp(2.3rem, 5.5vw, 3.8rem);
            font-weight: 800;
            letter-spacing: -0.02em;
            margin-bottom: 1.5rem;
            color: #FFFFFF;
            text-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .title-line {
            display: block;
        }

        /* Let Anime.js splitText handle Level 2 & Level 3 wrapper styles automatically.
           We only style the character inner element to enable GPU acceleration. */
        .char-inner {
            display: inline-block !important;
            will-change: transform;
        }

        .hero-subtitle {
            font-size: clamp(1.4rem, 2vw, 1.15rem);
            color: rgba(255, 255, 255, 0.95);
            font-weight: 400;
            line-height: 1.6;
            max-width: 600px;
            margin: 0 auto 4rem;
            margin-right: 70px;
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
            padding-bottom: 20px;
        }

        .section-title {
            font-family: var(--font-heading);
            font-size: clamp(2.2rem, 3.5vw, 3rem);
            font-weight: 800;
            margin-bottom: 1rem;
            text-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .section-subtitle {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 3rem;
            margin-top: 1rem;
        }

        .feature-pills {
            display: flex;
            justify-content: center;
            gap: 80px;
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
            margin-top: -30px;
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

        /* DIGITAL MARKETING SECTION */
        .digital-marketing-section {
            padding-top: 20px;
            padding-bottom: 80px;
            width: 100%;
            display: flex;
            justify-content: center;
        }

        .marketing-container {
            display: flex;
            align-items: center;
            justify-content: center;
            max-width: 980px;
            width: 100%;
            gap: 50px;
            text-align: left;
        }

        .marketing-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .marketing-title {
            font-family: var(--font-heading);
            font-size: clamp(2rem, 4vw, 3.2rem);
            font-weight: 800;
            margin-bottom: 1rem;
            color: #FFFFFF;
            line-height: 1.2;
            letter-spacing: -0.02em;
        }

        .marketing-subtitle {
            font-size: clamp(1rem, 1.5vw, 1.15rem);
            color: rgba(255, 255, 255, 0.95);
            line-height: 1.6;
            margin-bottom: 2.5rem;
            max-width: 500px;
        }

        .btn-service {
            background: #FFFFFF;
            color: var(--orange);
            border: none;
            padding: 14px 36px;
            border-radius: 50px;
            font-weight: 800;
            font-size: 16px;
            cursor: pointer;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }

        .btn-service:hover {
            transform: scale(1.05);
        }

        .marketing-image-wrapper {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .marketing-img {
            width: 100%;
            max-width: 480px;
            height: auto;
        }

        /* PRICING SECTION */
        .pricing-section {
            padding-top: 60px;
            padding-bottom: 100px;
            width: 100%;
        }

        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            max-width: 900px;
            margin: 0 auto;
            width: 100%;
            align-items: end;
        }

        .pricing-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 24px;
            color: var(--dark);
            text-align: left;
            display: flex;
            flex-direction: column;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            position: relative;
            z-index: 1;
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .pricing-card:hover {
            transform: scale(1.03) translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.12);
        }

        .pricing-card.popular {
            border: 2px solid var(--orange);
            background: #FFFFFF;
            box-shadow: 0 20px 40px rgba(90, 91, 241, 0.15);
            z-index: 2;
        }

        .pricing-card.popular:hover {
            box-shadow: 0 25px 50px rgba(90, 91, 241, 0.3);
        }

        .popular-badge {
            position: absolute;
            top: -14px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--orange);
            color: #FFF;
            font-size: 12px;
            font-weight: 800;
            padding: 6px 16px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 4px 10px rgba(90, 91, 241, 0.3);
            white-space: nowrap;
        }

        .pricing-header {
            padding: 24px 24px 8px;
            background: transparent;
            border-bottom: none;
        }

        .pricing-body {
            padding: 0 24px 24px;
        }

        .pricing-tier {
            font-family: var(--font-heading);
            font-size: 18px;
            font-weight: 800;
        }

        .pricing-price {
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 20px;
            display: flex;
            align-items: baseline;
            gap: 4px;
        }
        
        .pricing-price span {
            font-size: 12px;
            font-weight: 600;
            color: #666;
        }

        .btn-pricing {
            display: block;
            text-align: center;
            background: var(--orange);
            color: #FFFFFF;
            padding: 12px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 14px;
            margin-bottom: 25px;
            transition: transform 0.2s, background 0.2s;
        }

        .btn-pricing:hover {
            transform: scale(1.02);
            color: #FFFFFF;
            background: #4647D9;
        }

        .pricing-features-title {
            font-size: 12px;
            color: #666;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .pricing-features {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .pricing-features li {
            font-size: 12px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            color: #333;
        }

        .pricing-check {
            width: 6px;
            height: 6px;
            background: var(--dark);
            border-radius: 50%;
            display: inline-block;
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

        .testi-mobile-marquee {
            display: none;
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
            overflow-x: hidden;
        }

        .showcase-container {
            position: relative;
            width: 100%;
            max-width: 700px;
            margin: 0 auto;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .showcase-img {
            max-width: 100%;
            width: 700px;
            height: auto;
            display: block;
            filter: drop-shadow(0 20px 40px rgba(0,0,0,0.15));
            transform: translateX(-20px); /* Geser gambar ke kiri tanpa mengubah posisi tag */
        }

        .floating-tag {
            position: absolute;
            background: #FFFFFF;
            color: #1a56db;
            font-family: var(--font-heading);
            font-weight: 800;
            font-size: 14px;
            padding: 12px 24px;
            border-radius: 50px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
            white-space: nowrap;
        }

        .floating-tag.active {
            transition: box-shadow 0.3s ease, transform 0.3s ease;
        }

        .floating-tag.active:hover {
            transform: translateY(-2px) scale(1.05) !important;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.16);
        }
        
        .anime-tag {
            opacity: 0;
            pointer-events: none;
            z-index: 20;
        }
        
        .anime-tag.active {
            pointer-events: auto;
        }

        /* Position tags relative to the image container */
        .tag-1 { top: 27%; left: 80%; }
        .tag-2 { top: 37%; right: -20%; }
        .tag-3 { top: 57%; right: -25%; }
        .tag-4 { bottom: 20%; left: 84%; }

        /* Responsive styling for tags */
        @media (max-width: 768px) {
            .floating-tag {
                font-size: 11px;
                padding: 8px 16px;
            }
            .tag-1 { top: 15%; left: 55%; }
            .tag-2 { top: 35%; right: -15%; }
            .tag-3 { top: 60%; right: -10%; }
            .tag-4 { bottom: 15%; left: 60%; }
        }

        @media (max-width: 480px) {
            .floating-tag {
                font-size: 9px;
                padding: 6px 12px;
            }
            .tag-1 { top: 12%; left: 50%; }
            .tag-2 { top: 32%; right: -10%; }
            .tag-3 { top: 58%; right: -2%; }
            .tag-4 { bottom: 15%; left: 55%; }
        }

        /* FOOTER */
        .footer-wrapper {
            background: #FFFFFF;
            padding: 40px 5%;
            color: var(--dark);
            border-radius: 40px 40px 0 0;
            margin-top: 60px;
            width: 100%;
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
        @keyframes marquee-ltr {
            0% {
                transform: translateX(-100%);
            }
            100% {
                transform: translateX(0);
            }
        }

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

        /* Sticky navbar scrolled state */
        .navbar-wrapper.scrolled {
            padding: 12px 24px;
        }
        
        .navbar-wrapper.scrolled .navbar-pill {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.06);
            padding: 6px 12px 6px 20px;
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
            background-color: var(--dark);
            border-radius: 2px;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1),
                        opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1),
                        background-color 0.3s ease;
        }

        /* Toggle animation to 'X' */
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
            color: var(--dark);
            transition: color 0.2s, transform 0.2s;
            position: relative;
        }

        .mobile-nav-link:hover {
            color: var(--orange);
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

        /* Responsive */
        @media (max-width: 900px) {
            .testi-grid {
                display: none !important;
            }
            .testi-mobile-marquee {
                display: flex;
                overflow: hidden;
                width: 100vw;
                position: relative;
                padding: 25px 0;
                mask-image: linear-gradient(to right, transparent, black 5%, black 95%, transparent);
                -webkit-mask-image: linear-gradient(to right, transparent, black 5%, black 95%, transparent);
            }
            .testi-marquee-track {
                display: flex;
                width: max-content;
                gap: 16px;
                padding: 0 8px;
                animation: scroll-marquee 20s linear infinite;
                will-change: transform;
            }
            @keyframes scroll-marquee {
                0% { transform: translateX(0); }
                100% { transform: translateX(calc(-50% - 8px)); }
            }
            .testi-mobile-marquee .testi-card {
                width: 280px;
                flex-shrink: 0;
                box-shadow: 0 8px 24px rgba(0,0,0,0.06);
                padding: 24px;
                border-radius: 20px;
                background: #FFFFFF;
            }
            .pricing-grid {
                grid-template-columns: 1fr;
                max-width: 320px;
                align-items: center;
            }
        }

        @media (max-width: 768px) {
            .marketing-container {
                flex-direction: column;
                text-align: center;
            }
            .marketing-content {
                align-items: center;
                text-align: center;
            }
            .marketing-title {
                text-align: center;
            }
            .marketing-image-wrapper {
                margin-top: 30px;
            }
            .navbar-wrapper {
                padding: 12px 16px;
            }
            .navbar-wrapper.scrolled {
                padding: 8px 12px;
            }
            .navbar-pill {
                width: 100%;
                padding: 6px 12px 6px 16px;
            }
            .mobile-nav-toggle {
                display: flex;
            }
            .nav-links {
                display: none;
            }
            section {
                padding: 60px 20px;
            }
            .hero-section {
                padding-top: 130px;
                padding-bottom: 40px;
            }
            .hero-container {
                flex-direction: column;
                text-align: center;
                gap: 20px;
            }
            .hero-content {
                align-items: center;
                text-align: center;
            }
            .hero-title {
                font-size: clamp(2rem, 7vw, 2.8rem);
                text-align: center;
                margin-bottom: 1rem;
            }
            .hero-subtitle {
                text-align: center;
                margin: 0 auto 1.8rem;
                font-size: 15px;
            }
            .claim-wrapper {
                flex-direction: column;
                width: 100%;
                gap: 12px;
            }
            .claim-input-pill {
                width: 100%;
                min-width: unset;
                padding: 12px 20px;
            }
            .claim-prefix {
                font-size: 15px;
            }
            .claim-input {
                font-size: 15px;
            }
            .btn-create {
                width: 100%;
                justify-content: center;
                padding: 12px 36px;
                font-size: 15px;
            }
            .hero-image-wrapper {
                width: 100%;
                justify-content: center;
                margin-top: 10px;
            }
            .hero-img {
                max-width: 90%;
                max-height: 280px;
            }
            .features-section {
                padding-top: 60px;
                padding-bottom: 20px;
            }
            .section-title {
                font-size: 1.8rem;
                margin-bottom: 0.8rem;
            }
            .section-subtitle {
                font-size: 14px;
                margin-bottom: 2rem;
            }
            .feature-pills {
                gap: 10px;
            }
            .feature-pill {
                padding: 10px 20px;
                font-size: 13px;
            }
            .feature-mockup-wrapper {
                margin-top: -40px;
            }
            .testimonials-section {
                padding-top: 60px;
                padding-bottom: 40px;
            }
            .testi-card {
                padding: 24px;
                border-radius: 20px;
            }
            .creator-showcase-section {
                margin-top: -30px;
                padding: 0 16px;
            }
            .footer-wrapper {
                margin-top: 40px;
                padding: 30px 20px;
                border-radius: 30px 30px 0 0;
            }
            .footer-content {
                flex-direction: column;
                text-align: center;
                justify-content: center;
                gap: 20px;
            }
            .footer-left {
                flex-direction: column;
                gap: 12px;
            }
            .footer-links {
                gap: 20px;
            }
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar-wrapper" id="navbarWrapper">
        <div class="navbar-pill">
            <a href="{{ url('/') }}" class="nav-logo">
                <img src="{{ asset('images/Logo.png') }}" alt="Linkan Logo" class="logo-img">
            </a>
            <div class="nav-links">
                <a href="{{ route('pricing') }}" class="nav-link">Pricing</a>
                <a href="{{ route('service') }}" class="nav-link">Service</a>
                <a href="{{ route('FAQ') }}" class="nav-link">FAQ</a>
                <a href="{{ route('login') }}" class="nav-link">Sign In</a>
                <a href="{{ route('register') }}" class="btn-signup">Sign Up</a>
            </div>
            <button class="mobile-nav-toggle" id="mobileNavToggle" aria-label="Toggle Menu">
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
            </button>
        </div>
    </nav>

    <!-- MOBILE NAVIGATION OVERLAY -->
    <div class="mobile-nav-overlay" id="mobileNavOverlay">
        <div class="mobile-nav-menu">
            <a href="{{ route('pricing') }}" class="mobile-nav-link">Pricing</a>
            <a href="{{ route('service') }}" class="mobile-nav-link">Service</a>
            <a href="{{ route('FAQ') }}" class="mobile-nav-link">FAQ</a>
            <a href="{{ route('login') }}" class="mobile-nav-link">Sign In</a>
            <a href="{{ route('register') }}" class="mobile-btn-signup">Sign Up</a>
        </div>
    </div>

    <!-- HERO SECTION -->
    <section class="hero-section reveal">
        <div class="hero-container">
            <div class="hero-content">
                <h1 class="hero-title">
                    <span class="title-line">Powering Creators</span>
                    <span class="title-line">Economy</span>
                </h1>
                <p class="hero-subtitle">
                    Create Instant Mobile Webpage to sell your knowledge. Chat, Video Calls, Events, Digital Product. Share it across social media.
                </p>
                
                <form action="{{ route('register') }}" method="GET" class="claim-wrapper">
                    <div class="claim-input-pill">
                        <span class="claim-prefix">Linkan.id/</span>
                        <input type="text" name="username" class="claim-input" placeholder="YourNameHere" autocomplete="off">
                    </div>
                    <button type="submit" class="btn-create">Create</button>
                </form>
            </div>
            <div class="hero-image-wrapper">
                <img src="{{ asset('images/landing page/pria_laptop.svg') }}" alt="Powering Creators Economy" class="hero-img">
            </div>
        </div>
    </section>

    <!-- FEATURES SECTION -->
    <section class="features-section reveal">
        <h2 class="section-title" id="changing-title" style="min-height: 48px; overflow: hidden; position: relative;">Not just another link-in-bio</h2>
        <p class="section-subtitle">Linkan.id take care of your entire workflow, start to finish.</p>
        
        <div class="feature-pills">
            <div class="feature-pill">Digital Product</div>
            <div class="feature-pill">Donations</div>
            <div class="feature-pill">Online Course</div>
        </div>

        <div class="feature-mockup-wrapper">
            <img src="{{ asset('images/landing page/Group 15.png') }}" alt="Linkan Dashboard Mockup" class="feature-mockup-img">
        </div>
    </section>

    <!-- DIGITAL MARKETING SECTION -->
    <section class="digital-marketing-section reveal">
        <div class="marketing-container">
            <div class="marketing-content">
                <h2 class="marketing-title">Digital marketing<br>for your business</h2>
                <p class="marketing-subtitle">
                    optimize digital marketing in your business through billions of users on various effective internet marketing channels
                </p>
                <a href="{{ route('service') }}" class="btn-service">Get Service</a>
            </div>
            <div class="marketing-image-wrapper">
                <img src="{{ asset('images/landing page/handphone_besar.svg') }}" alt="Digital Marketing" class="marketing-img">
            </div>
        </div>
    </section>

    <!-- PRICING SECTION -->
    <section class="pricing-section reveal" id="pricing">
        <div class="pricing-grid">
            <!-- Basic -->
            <div class="pricing-card anime-pricing">
                <div class="pricing-header">
                    <h3 class="pricing-tier">Basic</h3>
                </div>
                <div class="pricing-body">
                    <div class="pricing-price">$ 0</div>
                    <a href="{{ route('register') }}" class="btn-pricing" style="background: #E8E8FF; color: var(--orange);">Get Started</a>
                    <div class="pricing-features-title">Everything you need:</div>
                    <ul class="pricing-features">
                        <li><span class="pricing-check"></span> Unlimited Link</li>
                        <li><span class="pricing-check"></span> Digital Product Store</li>
                        <li><span class="pricing-check"></span> Statistic / Traffic</li>
                        <li><span class="pricing-check"></span> Link Thumbnails</li>
                        <li><span class="pricing-check"></span> Templates</li>
                        <li><span class="pricing-check"></span> Custom Background</li>
                    </ul>
                </div>
            </div>
            <!-- Standard -->
            <div class="pricing-card popular anime-pricing">
                <div class="popular-badge">Most Popular</div>
                <div class="pricing-header">
                    <h3 class="pricing-tier">Standard</h3>
                </div>
                <div class="pricing-body">
                    <div class="pricing-price">$ 6 <span>/ Month</span></div>
                    <a href="{{ route('register') }}" class="btn-pricing">Get Started</a>
                    <div class="pricing-features-title">Everything you need:</div>
                    <ul class="pricing-features">
                        <li><span class="pricing-check"></span> Unlimited Link</li>
                        <li><span class="pricing-check"></span> Digital Product Store</li>
                        <li><span class="pricing-check"></span> Statistic / Traffic</li>
                        <li><span class="pricing-check"></span> Link Thumbnails</li>
                        <li><span class="pricing-check"></span> Templates</li>
                        <li><span class="pricing-check"></span> Custom Background</li>
                        <li><span class="pricing-check"></span> About Me</li>
                        <li><span class="pricing-check"></span> Email Notification</li>
                        <li><span class="pricing-check"></span> Donation Page</li>
                        <li><span class="pricing-check"></span> Transaction Fee</li>
                    </ul>
                </div>
            </div>
            <!-- Unlimited -->
            <div class="pricing-card anime-pricing">
                <div class="pricing-header">
                    <h3 class="pricing-tier">Unlimited</h3>
                </div>
                <div class="pricing-body">
                    <div class="pricing-price">$ 30 <span>/ Month</span></div>
                    <a href="{{ route('register') }}" class="btn-pricing">Get Started</a>
                    <div class="pricing-features-title">Everything you need:</div>
                    <ul class="pricing-features">
                        <li><span class="pricing-check"></span> Unlimited Link</li>
                        <li><span class="pricing-check"></span> Digital Product Store</li>
                        <li><span class="pricing-check"></span> Statistic / Traffic</li>
                        <li><span class="pricing-check"></span> Link Thumbnails</li>
                        <li><span class="pricing-check"></span> Templates</li>
                        <li><span class="pricing-check"></span> Custom Background</li>
                        <li><span class="pricing-check"></span> About Me</li>
                        <li><span class="pricing-check"></span> Email Notification</li>
                        <li><span class="pricing-check"></span> Donation Page</li>
                        <li><span class="pricing-check"></span> Transaction Fee</li>
                        <li><span class="pricing-check"></span> Priority Support</li>
                        <li><span class="pricing-check"></span> Custom Domain</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- TESTIMONIALS SECTION -->
    <section class="testimonials-section reveal">
        <h2 class="section-title">See What People Are Saying</h2>
        
        <!-- Desktop Grid View -->
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

        <!-- Mobile Scroll View -->
        <div class="testi-mobile-marquee">
            <div class="testi-marquee-track">
                <!-- SET 1 -->
                <div class="testi-card">
                    <div class="testi-header">
                        <div class="testi-avatar avatar-red"></div>
                        <div class="testi-meta">
                            <h4>RakanMY</h4>
                            <span>@rakanmy</span>
                        </div>
                    </div>
                    <p class="testi-text">"Sangat membantu saya untuk berjualan digital product dengan mudah dan cepat tanpa ribet. Tampilannya juga sangat premium."</p>
                </div>
                
                <div class="testi-card">
                    <div class="testi-header">
                        <div class="testi-avatar" style="background: #E8E8E8; color: #555;">F</div>
                        <div class="testi-meta">
                            <h4>Frsbrly</h4>
                            <span>@frsbrly</span>
                        </div>
                    </div>
                    <p class="testi-text">"Linkan.id memberikan solusi terbaik untuk mengelola semua link dan produk digital saya dalam satu tempat. Luar biasa!"</p>
                </div>
                
                <div class="testi-card">
                    <div class="testi-header">
                        <div class="testi-avatar avatar-orange"></div>
                        <div class="testi-meta">
                            <h4>mhmdazrl</h4>
                            <span>@mhmdazrl</span>
                        </div>
                    </div>
                    <p class="testi-text">"Platform yang sangat intuitif dan mudah digunakan. Sangat direkomendasikan untuk kreator pemula maupun profesional."</p>
                </div>

                <!-- SET 2 (DUPLICATE FOR SEAMLESS LOOP) -->
                <div class="testi-card" aria-hidden="true">
                    <div class="testi-header">
                        <div class="testi-avatar avatar-red"></div>
                        <div class="testi-meta">
                            <h4>RakanMY</h4>
                            <span>@rakanmy</span>
                        </div>
                    </div>
                    <p class="testi-text">"Sangat membantu saya untuk berjualan digital product dengan mudah dan cepat tanpa ribet. Tampilannya juga sangat premium."</p>
                </div>
                
                <div class="testi-card" aria-hidden="true">
                    <div class="testi-header">
                        <div class="testi-avatar" style="background: #E8E8E8; color: #555;">F</div>
                        <div class="testi-meta">
                            <h4>Frsbrly</h4>
                            <span>@frsbrly</span>
                        </div>
                    </div>
                    <p class="testi-text">"Linkan.id memberikan solusi terbaik untuk mengelola semua link dan produk digital saya dalam satu tempat. Luar biasa!"</p>
                </div>
                
                <div class="testi-card" aria-hidden="true">
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
        </div>
    </section>

    <!-- CREATOR SHOWCASE SECTION -->
    <section class="creator-showcase-section reveal">
        <div class="showcase-container">
            <img src="{{ asset('images/landing page/wanita_laptop.svg') }}" alt="Linkan Creator Showcase" class="showcase-img">
            
            <div class="floating-tag anime-tag tag-1">Membantu Creator</div>
            <div class="floating-tag anime-tag tag-2">Memudahkan Pengguna</div>
            <div class="floating-tag anime-tag tag-3">Top 1 Platform Microsite</div>
            <div class="floating-tag anime-tag tag-4">Optimalisasi digital</div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer-wrapper">
        <div class="footer-content">
            <div class="footer-left">
                <div class="footer-logo">
                    <img src="{{ asset('images/Logo.png') }}" alt="Linkan Logo" style="height: 45px; width: auto;">
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

            function handleShowcaseReveal(reveal) {
                if (reveal.classList.contains('creator-showcase-section') && !reveal.dataset.revealed) {
                    reveal.dataset.revealed = 'true';
                    const container = reveal.querySelector('.showcase-container');
                    const tags = reveal.querySelectorAll('.anime-tag');
                    if (container && tags.length > 0) {
                        const cRect = container.getBoundingClientRect();
                        const cx = cRect.width / 2;
                        const cy = cRect.height / 2;
                        
                        tags.forEach(tag => {
                            const ex = tag.offsetLeft + (tag.offsetWidth / 2);
                            const ey = tag.offsetTop + (tag.offsetHeight / 2);
                            tag.dataset.dx = cx - ex;
                            tag.dataset.dy = cy - ey;
                        });
                    }
                    window.dispatchEvent(new CustomEvent('showcase-revealed'));
                }
            }

            const revealOnScroll = new IntersectionObserver(function(entries, observer) {
                entries.forEach(entry => {
                    if (!entry.isIntersecting) {
                        return;
                    } else {
                        entry.target.classList.add('active');
                        handleShowcaseReveal(entry.target);
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
                        handleShowcaseReveal(reveal);
                    }
                });
            }, 100);

            // Typing animation for placeholder in claim input
            const claimInput = document.querySelector('.claim-input');
            if (claimInput) {
                const words = ["YourNameHere", "kreator", "bisnisanda", "portofolio", "toko-online", "content_creator"];
                let wordIndex = 0;
                let charIndex = 0;
                let isDeleting = false;
                let typingDelay = 150;
                let erasingDelay = 100;
                let newWordDelay = 2000;

                function type() {
                    const currentWord = words[wordIndex];
                    let currentDelay = typingDelay;

                    if (isDeleting) {
                        claimInput.setAttribute('placeholder', currentWord.substring(0, charIndex));
                        charIndex--;
                        currentDelay = erasingDelay;
                    } else {
                        claimInput.setAttribute('placeholder', currentWord.substring(0, charIndex));
                        charIndex++;
                        currentDelay = typingDelay;
                    }

                    if (!isDeleting && charIndex > currentWord.length) {
                        isDeleting = true;
                        currentDelay = newWordDelay;
                    } else if (isDeleting && charIndex < 0) {
                        isDeleting = false;
                        wordIndex = (wordIndex + 1) % words.length;
                        charIndex = 0;
                        currentDelay = 500;
                    }

                    setTimeout(type, currentDelay);
                }

                setTimeout(type, 1000);
            }


            // Mobile menu toggle logic
            const mobileNavToggle = document.getElementById('mobileNavToggle');
            const mobileNavOverlay = document.getElementById('mobileNavOverlay');
            if (mobileNavToggle && mobileNavOverlay) {
                const mobileNavLinks = mobileNavOverlay.querySelectorAll('.mobile-nav-link');
                
                function toggleMenu() {
                    mobileNavToggle.classList.toggle('active');
                    mobileNavOverlay.classList.toggle('active');
                    if (mobileNavOverlay.classList.contains('active')) {
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
                        document.body.style.overflow = '';
                    });
                });
            }
        });
    </script>

    <script type="module">
        import { createTimeline, stagger, splitText } from 'https://esm.sh/animejs@4.5.0';

        document.addEventListener('DOMContentLoaded', () => {
            // Navbar Hide/Show on Scroll using AnimeJS
            const navbarWrapper = document.getElementById('navbarWrapper');
            if (navbarWrapper) {
                let lastScrollY = window.scrollY;
                let isNavbarVisible = true;
                
                window.addEventListener('scroll', () => {
                    const currentScrollY = window.scrollY;
                    
                    // Compact styling check
                    if (currentScrollY > 20) {
                        navbarWrapper.classList.add('scrolled');
                    } else {
                        navbarWrapper.classList.remove('scrolled');
                    }

                    // Hide when scrolling down past 150px
                    if (currentScrollY > lastScrollY && currentScrollY > 150) {
                        if (isNavbarVisible) {
                            isNavbarVisible = false;
                            createTimeline().add(navbarWrapper, {
                                y: -150,
                                opacity: 0,
                                duration: 400,
                                ease: 'outQuad'
                            });
                        }
                    } 
                    // Show when scrolling up
                    else if (currentScrollY < lastScrollY) {
                        if (!isNavbarVisible) {
                            isNavbarVisible = true;
                            createTimeline().add(navbarWrapper, {
                                y: 0,
                                opacity: 1,
                                duration: 400,
                                ease: 'outQuad'
                            });
                        }
                    }
                    lastScrollY = currentScrollY;
                });
            }

            // Rotating Text Animation for "Not just another link-in-bio"
            const changingTitle = document.getElementById('changing-title');
            if (changingTitle) {
                const phrases = [
                    "Not just another link-in-bio",
                    "Your ultimate creator toolkit",
                    "Monetize your audience easily",
                    "All your links in one place"
                ];
                let phraseIndex = 0;

                function animateChangingText() {
                    changingTitle.style.opacity = '0';
                    changingTitle.innerHTML = phrases[phraseIndex];
                    const split = splitText(changingTitle, {
                        lines: false,
                        words: false,
                        chars: { wrap: true }
                    });

                    const charElements = changingTitle.querySelectorAll('span');
                    charElements.forEach(el => {
                        el.style.display = 'inline-block';
                        if (el.textContent === ' ' || el.innerHTML === ' ') {
                            el.innerHTML = '&nbsp;';
                        }
                    });

                    changingTitle.style.opacity = '1';

                    const tl = createTimeline({
                        onComplete: () => {
                            phraseIndex = (phraseIndex + 1) % phrases.length;
                            animateChangingText(); // Seamless transition to next word
                        }
                    });

                    tl.add(split.chars, {
                        y: ['25px', '0px'],
                        opacity: [0, 1],
                        duration: 800,
                        ease: 'outQuad',
                        delay: stagger(30)
                    })
                    .add(split.chars, {
                        y: ['0px', '-25px'],
                        opacity: [1, 0],
                        duration: 500,
                        ease: 'inQuad',
                        delay: stagger(20, { from: 'first' })
                    }, '+=2500'); // Delay reading time (2.5 seconds)
                }

                // Start the rotation
                setTimeout(animateChangingText, 1000);
            }


            document.fonts.ready.then(() => {
                const heroTitle = document.querySelector('.hero-title');
                if (heroTitle) {
                    const split = splitText(heroTitle, {
                        lines: false,
                        words: true,
                        chars: {
                            class: 'char-inner',
                            wrap: 'clip',
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
                
                // AnimeJS event listener for showcase tags
                window.addEventListener('showcase-revealed', () => {
                    const tags = document.querySelectorAll('.anime-tag');
                    if (tags.length > 0) {
                        createTimeline()
                        .add(tags, {
                            opacity: [0, 1],
                            scale: [0, 1],
                            x: (el) => [parseFloat(el.dataset.dx || 0), 0],
                            y: (el) => [parseFloat(el.dataset.dy || 0), 0],
                            duration: 1200,
                            ease: 'outElastic(1, .8)'
                        }, stagger(150));

                        // Add active class after animation so hover works
                        setTimeout(() => {
                            tags.forEach(tag => tag.classList.add('active'));
                        }, 1500);
                    }
                });
            });
        });
    </script>
</body>
</html>