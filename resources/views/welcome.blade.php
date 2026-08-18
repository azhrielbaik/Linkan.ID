<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('public.meta_title') }}</title>
    <meta name="description" content="{{ __('public.meta_desc') }}">
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

                /* LANGUAGE TOGGLE PILL */
        .lang-toggle-pill {
            display: flex;
            background: #f1f1f1;
            border-radius: 20px;
            padding: 3px;
            align-items: center;
        }
        .lang-btn {
            padding: 6px 12px;
            font-size: 13px;
            font-weight: 700;
            border-radius: 16px;
            color: #666;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        .lang-btn.active {
            background: #ffffff;
            color: var(--dark);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .mobile-lang-toggle {
            margin-top: 10px;
            transform: scale(1.2);
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
            color: #767676; /* WCAG 2.0 AA contrast */
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
            transform: translateX(-20px) translateZ(0);
            will-change: transform, opacity;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
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
            will-change: transform, opacity;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            transform: translateZ(0);
        }

        @keyframes float-tag-1 {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(4px, -12px); }
        }
        @keyframes float-tag-2 {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(-5px, -9px); }
        }
        @keyframes float-tag-3 {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(3px, -15px); }
        }
        @keyframes float-tag-4 {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(-6px, -11px); }
        }

        .floating-tag.active {
            transition: box-shadow 0.3s ease, transform 0.3s ease;
        }

        .floating-tag.active:hover {
            animation-play-state: paused;
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
        .tag-1.active { animation: float-tag-1 3.5s ease-in-out infinite; }
        
        .tag-2 { top: 37%; right: -20%; }
        .tag-2.active { animation: float-tag-2 4.2s ease-in-out infinite; }
        
        .tag-3 { top: 57%; right: -25%; }
        .tag-3.active { animation: float-tag-3 3.8s ease-in-out infinite; }
        
        .tag-4 { bottom: 20%; left: 84%; }
        .tag-4.active { animation: float-tag-4 4.6s ease-in-out infinite; }

        /* Responsive styling for tags */
        @media (max-width: 900px) {
            .showcase-container {
                padding-top: 40px;
                padding-bottom: 40px;
            }
            .showcase-img {
                width: 90%;
                max-width: 100%;
                transform: none;
                margin: 0 auto;
            }
            .floating-tag {
                font-size: 11px;
                padding: 8px 16px;
            }
            .tag-1 { top: 35px; left: 0; right: 0; margin: 0 auto; width: max-content; }
            .tag-2 { top: 35%; right: 2%; left: auto; margin: 0; }
            .tag-3 { bottom: 40%; left: 2%; right: auto; margin: 0; }
            .tag-4 { bottom: 0; left: 0; right: 0; margin: 0 auto; width: max-content; }
        }

        @media (max-width: 480px) {
            .showcase-container {
                padding-top: 50px;
                padding-bottom: 50px;
            }
            .showcase-img {
                width: 100%;
                max-width: 100%;
                transform: none;
            }
            .floating-tag {
                font-size: 9px;
                padding: 6px 12px;
            }
            .tag-1 { top: 70px; left: 0; right: 0; margin: 0 auto; width: max-content; }
            .tag-2 { top: 40%; right: 2%; left: auto; margin: 0; }
            .tag-3 { bottom: 35%; left: 2%; right: auto; margin: 0; }
            .tag-4 { bottom: 5px; left: 0; right: 0; margin: 0 auto; width: max-content; }
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
                display: flex;
                overflow-x: auto;
                padding: 20px;
                scroll-snap-type: x mandatory;
                max-width: 100vw;
                gap: 20px;
                width: 100%;
                align-items: stretch;
            }
            .pricing-grid::-webkit-scrollbar {
                display: none;
            }
            .pricing-card {
                flex: 0 0 300px;
                scroll-snap-align: center;
                height: auto;
            }
        }

        @media (max-width: 900px) {
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
                padding-top: 140px;
                padding-bottom: 40px;
            }
            .hero-container {
                flex-direction: row;
                text-align: left;
                gap: 20px;
                align-items: center;
            }
            .hero-content {
                align-items: flex-start;
                text-align: left;
                flex: 1.2;
            }
            .hero-title {
                font-size: clamp(1.8rem, 4vw, 2.5rem);
                text-align: left;
                margin-bottom: 1rem;
            }
            .hero-subtitle {
                text-align: left;
                margin: 0 0 1rem 0;
                font-size: 13px;
                line-height: 1.4;
            }
            .claim-wrapper {
                flex-direction: row;
                flex-wrap: nowrap;
                width: 100%;
                gap: 8px;
                align-items: center;
            }
            .claim-input-pill {
                width: auto;
                flex: 1;
                min-width: unset;
                padding: 10px 16px;
            }
            .claim-prefix {
                font-size: 12px;
            }
            .claim-input {
                font-size: 12px;
                width: 100%;
            }
            .btn-create {
                width: auto;
                justify-content: center;
                padding: 10px 24px;
                font-size: 13px;
            }
            .hero-image-wrapper {
                flex: 0.8;
                justify-content: flex-end;
                margin-top: 0;
            }
            .hero-img {
                max-width: 100%;
                max-height: 350px;
            }
        }

        @media (max-width: 650px) {
            .hero-container {
                flex-direction: column;
                text-align: center;
                gap: 30px;
            }
            .hero-content {
                align-items: center;
                text-align: center;
            }
            .hero-title {
                font-size: clamp(2.2rem, 8vw, 2.8rem);
                text-align: center;
            }
            .hero-subtitle {
                text-align: center;
                margin: 0 auto 1.8rem;
            }
            .claim-wrapper {
                align-items: center;
            }
            .hero-image-wrapper {
                justify-content: center;
                margin-top: 20px;
            }
            .hero-img {
                max-width: 90%;
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
    <a href="#main-content" class="skip-to-content">Skip to content</a>

    <!-- NAVBAR -->
    <header class="navbar-wrapper" id="navbarWrapper">
        <nav class="navbar-pill" aria-label="Main Navigation">
            <a href="{{ url('/') }}" class="nav-logo">
                <img src="{{ asset('images/Logo.png') }}" alt="Linkan Logo" class="logo-img">
            </a>
                        <div class="nav-links">
                <div class="lang-toggle-pill">
                    <a href="{{ route('lang.switch', 'en') }}" class="lang-btn {{ App::getLocale() == 'en' ? 'active' : '' }}">EN</a>
                    <a href="{{ route('lang.switch', 'id') }}" class="lang-btn {{ App::getLocale() == 'id' ? 'active' : '' }}">ID</a>
                </div>
                <a href="{{ route('pricing') }}" class="nav-link">{{ __('layout.pricing') }}</a>
                <a href="{{ route('service') }}" class="nav-link">{{ __('layout.service') }}</a>
                <a href="{{ route('FAQ') }}" class="nav-link">{{ __('layout.faq') }}</a>
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
            <div class="lang-toggle-pill mobile-lang-toggle">
                <a href="{{ route('lang.switch', 'en') }}" class="lang-btn {{ App::getLocale() == 'en' ? 'active' : '' }}">EN</a>
                <a href="{{ route('lang.switch', 'id') }}" class="lang-btn {{ App::getLocale() == 'id' ? 'active' : '' }}">ID</a>
            </div>
            <a href="{{ route('pricing') }}" class="mobile-nav-link">{{ __('layout.pricing') }}</a>
            <a href="{{ route('service') }}" class="mobile-nav-link">{{ __('layout.service') }}</a>
            <a href="{{ route('FAQ') }}" class="mobile-nav-link">{{ __('layout.faq') }}</a>
            <a href="{{ route('login') }}" class="mobile-nav-link">{{ __('layout.sign_in') }}</a>
            <a href="{{ route('register') }}" class="mobile-btn-signup">{{ __('layout.sign_up_free') }}</a>
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
                    <button type="submit" class="btn-create">{{ __('public.btn_create') }}</button>
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
    <section class="digital-marketing-section reveal">
        <div class="marketing-container">
            <div class="marketing-content">
                <h2 class="marketing-title">{!! __('public.marketing_title') !!}</h2>
                <p class="marketing-subtitle">
                    {{ __('public.marketing_subtitle') }}
                </p>
                <a href="{{ route('service') }}" class="btn-service">{{ __('public.btn_service') }}</a>
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
                    <div class="pricing-price">$ 0</div>
                    <a href="{{ route('register') }}" class="btn-pricing" style="background: #E8E8FF; color: var(--orange);">{{ __('public.btn_get_started') }}</a>
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
                    <div class="pricing-price">$ 6 <span>{{ __('public.pricing_month') }}</span></div>
                    <a href="{{ route('register') }}" class="btn-pricing">{{ __('public.btn_get_started') }}</a>
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
                    <div class="pricing-price">$ 30 <span>{{ __('public.pricing_month') }}</span></div>
                    <a href="{{ route('register') }}" class="btn-pricing">{{ __('public.btn_get_started') }}</a>
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
        <h2 class="section-title" style="margin-bottom: 4rem;">{{ __('public.testi_title') }}</h2>
        
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
                <p class="testi-text">"“Lorem ipsum dolor sit amet, lorem ipsum dolor si amet”"</p>
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
                    <img src="{{ asset('images/Logo.png') }}" alt="Linkan Logo" style="height: 45px; width: auto;">
                </div>
                <div class="footer-copyright">
                    {{ __('public.footer_copyright') }}
                </div>
            </div>
            <div class="footer-links">
                <a href="{{ route('about') }}" class="footer-link">{{ __('layout.about_us') }}</a>
                <a href="{{ route('contact.form') }}" class="footer-link">{{ __('layout.contact_us') }}</a>
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
                        const isMobile = window.innerWidth <= 768;
                        const duration = isMobile ? 700 : 900;
                        const ease = isMobile ? 'outCubic' : 'outBack(1.2)';
                        const stag = isMobile ? 80 : 120;

                        createTimeline()
                        .add(tags, {
                            opacity: [0, 1],
                            scale: [0.6, 1],
                            x: (el) => isMobile ? [0, 0] : [parseFloat(el.dataset.dx || 0), 0],
                            y: (el) => isMobile ? [20, 0] : [parseFloat(el.dataset.dy || 0), 0],
                            duration: duration,
                            ease: ease
                        }, stagger(stag));

                        // Add active class after animation so hover/floating works smoothly
                        setTimeout(() => {
                            tags.forEach(tag => {
                                tag.style.transform = '';
                                tag.classList.add('active');
                            });
                        }, duration + (tags.length * stag));
                    }
                });
            });
        });
    </script>
</body>
</html>
