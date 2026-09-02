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
            max-width: 1020px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            white-space: nowrap;
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
            flex-shrink: 0;
        }

        .logo-img {
            height: 30px;
            width: auto;
            transition: height 0.3s ease;
        }

        .nav-links {
            display: flex;
            gap: 24px;
            align-items: center;
            flex-wrap: nowrap;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .nav-link {
            font-size: 14px;
            font-weight: 600;
            color: #4B5563;
            transition: color 0.2s ease;
            text-decoration: none;
            display: inline-block;
            white-space: nowrap !important;
            flex-shrink: 0;
        }

        .nav-link:hover,
        .nav-link.active,
        .scramble-link:hover,
        .scramble-link.active {
            color: #000000 !important;
        }

        .scramble-link {
            display: inline-block;
            position: relative;
            overflow: hidden;
            line-height: 1.3em;
            vertical-align: middle;
            text-decoration: none;
            cursor: pointer;
            transition: color 0.2s ease;
            white-space: nowrap !important;
            flex-shrink: 0;
        }

        .scramble-char-box {
            display: inline-block;
            overflow: hidden;
            height: 1.3em;
            line-height: 1.3em;
            vertical-align: top;
            position: relative;
            white-space: nowrap !important;
            flex-shrink: 0;
        }

        .scramble-char-col {
            display: flex;
            flex-direction: column;
            will-change: transform;
            white-space: nowrap !important;
        }

        .scramble-char-item {
            display: block;
            height: 1.3em;
            line-height: 1.3em;
            text-align: center;
            white-space: nowrap !important;
        }

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
            z-index: 101;
            flex-shrink: 0;
        }

        /* =========================================================
           ORIGIN-AWARE INTERACTIVE BUTTONS (CIRCLE FILL HOVER EFFECT)
           ========================================================= */
        .relative { position: relative; }
        .overflow-hidden { overflow: hidden; }
        .rounded-full { border-radius: 9999px; }
        .rounded-xl { border-radius: 14px; }
        .border { border: 1px solid transparent; }
        .z-10 { z-index: 10; }

        .origin-btn {
            position: relative;
            overflow: hidden;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            text-decoration: none;
            isolation: isolate;
            --x: 50%;
            --y: 50%;
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.3s ease;
        }

        .origin-btn .btn-bg {
            position: absolute;
            top: var(--y, 50%);
            left: var(--x, 50%);
            width: 250%;
            aspect-ratio: 1 / 1;
            border-radius: 9999px;
            pointer-events: none;
            z-index: 1;
            transform: translate(-50%, -50%) scale(0);
            transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .origin-btn:hover .btn-bg,
        .origin-btn.group-hover .btn-bg {
            transform: translate(-50%, -50%) scale(1);
        }

        .origin-btn .btn-text {
            position: relative;
            z-index: 10;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            pointer-events: none;
            transition: color 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .origin-btn:active {
            transform: scale(0.97);
        }

        .btn-signup {
            background: #000000;
            padding: 10px 24px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 700;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15);
        }
        .btn-signup .btn-bg {
            background: #FFFFFF;
        }
        .btn-signup .btn-text {
            color: #FFFFFF;
        }
        .btn-signup:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.18);
            border-color: #000000;
            transform: translateY(-2px);
        }
        .btn-signup:hover .btn-text {
            color: #000000;
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
            padding: 14px 36px;
            border-radius: 50px;
            font-weight: 800;
            font-size: 16px;
            border: 1px solid rgba(0, 0, 0, 0.08);
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        }
        .btn-create .btn-bg {
            background: var(--dark);
        }
        .btn-create .btn-text {
            color: var(--dark);
            font-weight: 800;
        }
        .btn-create:hover {
            border-color: var(--dark);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.22);
            transform: translateY(-2px);
        }
        .btn-create:hover .btn-text {
            color: #FFFFFF;
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
            padding: 14px 36px;
            border-radius: 50px;
            font-weight: 800;
            font-size: 16px;
            border: 1px solid rgba(90, 91, 241, 0.2);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .btn-service .btn-bg {
            background: var(--orange);
        }
        .btn-service .btn-text {
            color: var(--orange);
            font-weight: 800;
        }
        .btn-service:hover {
            border-color: var(--orange);
            box-shadow: 0 12px 30px rgba(90, 91, 241, 0.35);
            transform: translateY(-2px);
        }
        .btn-service:hover .btn-text {
            color: #FFFFFF;
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
            gap: 5px;
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
            min-height: 500px;
        }

        .pricing-card:hover {
            transform: scale(1.03) translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.12);
        }

        .pricing-card:not(.popular) {
            min-height: 620px;
        }

        .pricing-card.popular {
            border: 5px solid var(--orange);
            background: #FFFFFF;
            box-shadow: 0 20px 40px rgba(90, 91, 241, 0.15);
            z-index: 2;
            min-height: 650px;
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
            display: flex;
            width: 100%;
            text-align: center;
            padding: 13px 20px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 14px;
            margin-bottom: 25px;
        }
        .btn-pricing-basic {
            background: #E8E8FF;
            border: 1px solid rgba(90, 91, 241, 0.25);
        }
        .btn-pricing-basic .btn-bg {
            background: var(--dark);
        }
        .btn-pricing-basic .btn-text {
            color: var(--orange);
        }
        .btn-pricing-basic:hover {
            border-color: var(--dark);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.18);
            transform: translateY(-2px);
        }
        .btn-pricing-basic:hover .btn-text {
            color: #FFFFFF;
        }

        .btn-pricing-primary {
            background: var(--orange);
            border: 1px solid transparent;
            box-shadow: 0 6px 20px rgba(90, 91, 241, 0.25);
        }
        .btn-pricing-primary .btn-bg {
            background: var(--dark);
        }
        .btn-pricing-primary .btn-text {
            color: #FFFFFF;
        }
        .btn-pricing-primary:hover {
            box-shadow: 0 10px 30px rgba(18, 18, 18, 0.3);
            border-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }
        .btn-pricing-primary:hover .btn-text {
            color: #FFFFFF;
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
            padding-top: 100px;
            padding-bottom: 70px;
            position: relative;
            overflow: hidden;
            width: 100%;
        }

        .testi-header-container {
            text-align: center;
            max-width: 760px;
            margin: 0 auto 45px;
            padding: 0 20px;
        }

        .testi-pill-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 18px;
            background: rgba(255, 255, 255, 0.22);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 50px;
            font-size: 13px;
            font-weight: 700;
            color: #FFFFFF;
            margin-bottom: 16px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .testi-pill-badge span.pulse-dot {
            width: 8px;
            height: 8px;
            background: #22C55E;
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 10px #22C55E;
        }

        .testimonials-marquee-wrapper {
            display: flex;
            flex-direction: column;
            gap: 20px;
            width: 100%;
            position: relative;
            padding: 10px 0;
            mask-image: linear-gradient(to right, transparent 0%, black 5%, black 95%, transparent 100%);
            -webkit-mask-image: linear-gradient(to right, transparent 0%, black 5%, black 95%, transparent 100%);
        }

        .testi-marquee-row {
            display: flex;
            overflow: hidden;
            width: 100%;
            user-select: none;
        }

        .testi-track {
            display: flex;
            width: max-content;
            gap: 20px;
            will-change: transform;
        }

        .testi-track-left {
            animation: scroll-left 45s linear infinite;
        }

        .testi-track-right {
            animation: scroll-right 45s linear infinite;
        }

        .testimonials-marquee-wrapper:hover .testi-track,
        .testi-marquee-row:hover .testi-track,
        .testi-track:hover,
        .testi-card:hover {
            animation-play-state: paused !important;
            -webkit-animation-play-state: paused !important;
        }

        @keyframes scroll-left {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(calc(-50% - 10px));
            }
        }

        @keyframes scroll-right {
            0% {
                transform: translateX(calc(-50% - 10px));
            }
            100% {
                transform: translateX(0);
            }
        }

        .testi-card {
            width: 380px;
            max-width: 85vw;
            flex-shrink: 0;
            background: #FFFFFF;
            border-radius: 24px;
            padding: 26px 28px;
            text-align: left;
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.9);
            color: var(--dark);
            transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.35s ease, border-color 0.35s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            cursor: pointer;
            z-index: 1;
        }

        .testi-card:hover {
            transform: translateY(-6px) scale(1.03);
            box-shadow: 0 22px 45px rgba(0, 0, 0, 0.14);
            border-color: rgba(90, 91, 241, 0.35);
            z-index: 10;
        }

        .testi-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
        }

        .testi-profile {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .testi-avatar {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 800;
            font-size: 17px;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }

        .avatar-grad-1 { background: linear-gradient(135deg, #FF4A50 0%, #FF8533 100%); }
        .avatar-grad-2 { background: linear-gradient(135deg, #5A5BF1 0%, #8A4AF3 100%); }
        .avatar-grad-3 { background: linear-gradient(135deg, #10B981 0%, #06B6D4 100%); }
        .avatar-grad-4 { background: linear-gradient(135deg, #ED842C 0%, #F59E0B 100%); }
        .avatar-grad-5 { background: linear-gradient(135deg, #EC4899 0%, #F43F5E 100%); }
        .avatar-grad-6 { background: linear-gradient(135deg, #06B6D4 0%, #3B82F6 100%); }
        .avatar-grad-7 { background: linear-gradient(135deg, #8B5CF6 0%, #6366F1 100%); }
        .avatar-grad-8 { background: linear-gradient(135deg, #FA709A 0%, #FEE140 100%); }
        .avatar-grad-9 { background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%); }
        .avatar-grad-10 { background: linear-gradient(135deg, #14B8A6 0%, #059669 100%); }
        .avatar-grad-11 { background: linear-gradient(135deg, #F97316 0%, #DC2626 100%); }
        .avatar-grad-12 { background: linear-gradient(135deg, #6366F1 0%, #4338CA 100%); }

        .testi-meta {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .testi-name-wrap {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .testi-meta h4 {
            font-size: 15px;
            font-weight: 800;
            color: var(--dark);
            line-height: 1.2;
            margin: 0;
        }

        .testi-verified {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #3B82F6;
            flex-shrink: 0;
        }

        .testi-verified svg {
            width: 15px;
            height: 15px;
            fill: #3B82F6;
        }

        .testi-meta span {
            font-size: 12px;
            color: #71717A;
            font-weight: 500;
        }

        .testi-role-tag {
            font-size: 11px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 20px;
            background: #F4F4F5;
            color: #52525B;
            letter-spacing: 0.2px;
            white-space: nowrap;
        }

        .testi-stars {
            display: flex;
            gap: 3px;
            margin-bottom: 12px;
        }

        .testi-stars svg {
            width: 15px;
            height: 15px;
            fill: #F59E0B;
        }

        .testi-text {
            font-size: 13.5px;
            line-height: 1.65;
            color: #3F3F46;
            font-weight: 500;
            margin: 0;
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
            padding: 14px 40px;
            border-radius: 50px;
            font-size: 18px;
            font-weight: 700;
            width: 100%;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            margin-top: 10px;
        }
        .mobile-btn-signup .btn-bg {
            background: #FFFFFF;
        }
        .mobile-btn-signup .btn-text {
            color: #FFFFFF;
        }
        .mobile-btn-signup:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.18);
            border-color: #000000;
            transform: translateY(-2px);
        }
        .mobile-btn-signup:hover .btn-text {
            color: #000000;
        }

        /* Responsive */
        @media (max-width: 900px) {
            .testimonials-section {
                padding-top: 70px;
                padding-bottom: 50px;
            }
            .testi-header-container {
                margin-bottom: 30px;
            }
            .testimonials-marquee-wrapper {
                gap: 14px;
            }
            .testi-track {
                gap: 14px;
            }
            .testi-track-left {
                animation-duration: 32s;
            }
            .testi-track-right {
                animation-duration: 34s;
            }
            .testi-card {
                width: 310px;
                padding: 20px 22px;
                border-radius: 20px;
            }
            .testi-avatar {
                width: 40px;
                height: 40px;
                font-size: 15px;
            }
            .testi-meta h4 {
                font-size: 14px;
            }
            .testi-text {
                font-size: 12.5px;
                line-height: 1.55;
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
                            <span class="testi-role-tag">🚀 Digital Creator</span>
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
                            <span class="testi-role-tag">🛍️ Brand Founder</span>
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
                            <span class="testi-role-tag">✨ Beauty & Lifestyle</span>
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
                            <span class="testi-role-tag">📚 Course Instructor</span>
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
                            <span class="testi-role-tag">🎨 UI/UX Designer</span>
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
                            <span class="testi-role-tag">📦 E-Commerce Seller</span>
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
                            <span class="testi-role-tag">🚀 Digital Creator</span>
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
                            <span class="testi-role-tag">🛍️ Brand Founder</span>
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
                            <span class="testi-role-tag">✨ Beauty & Lifestyle</span>
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
                            <span class="testi-role-tag">📚 Course Instructor</span>
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
                            <span class="testi-role-tag">🎨 UI/UX Designer</span>
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
                            <span class="testi-role-tag">📦 E-Commerce Seller</span>
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
                            <span class="testi-role-tag">💡 Tech Creator</span>
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
                            <span class="testi-role-tag">📈 Affiliate Marketer</span>
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
                            <span class="testi-role-tag">🧶 Artisan & Handmade</span>
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
                            <span class="testi-role-tag">🎙️ Podcaster & Host</span>
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
                            <span class="testi-role-tag">✈️ Travel Blogger</span>
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
                            <span class="testi-role-tag">📸 Commercial Photo</span>
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
                            <span class="testi-role-tag">💡 Tech Creator</span>
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
                            <span class="testi-role-tag">📈 Affiliate Marketer</span>
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
                            <span class="testi-role-tag">🧶 Artisan & Handmade</span>
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
                            <span class="testi-role-tag">🎙️ Podcaster & Host</span>
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
                            <span class="testi-role-tag">✈️ Travel Blogger</span>
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
                            <span class="testi-role-tag">📸 Commercial Photo</span>
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

            // Origin-aware interactive magnetic circle buttons
            const originBtns = document.querySelectorAll('.origin-btn');
            originBtns.forEach(btn => {
                const updateCoords = (e) => {
                    const rect = btn.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;
                    btn.style.setProperty('--x', `${x}px`);
                    btn.style.setProperty('--y', `${y}px`);
                };

                btn.addEventListener('mouseenter', updateCoords);
                btn.addEventListener('mousemove', updateCoords);
            });

            // Vertical Top-to-Bottom Scramble Text Effect on Navigation Links (Fixed Widths, No Layout Shift, No Drop)
            const letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            const scrambleLinks = document.querySelectorAll('.scramble-link');

            scrambleLinks.forEach(link => {
                if (!link.dataset.value) {
                    link.dataset.value = link.innerText.trim();
                }

                let timeoutId = null;

                link.addEventListener('mouseenter', () => {
                    const originalText = link.dataset.value;
                    if (!originalText) return;

                    clearTimeout(timeoutId);

                    // Ensure link never drops or wraps
                    link.style.whiteSpace = 'nowrap';
                    link.style.display = 'inline-block';

                    // Measure original letter widths
                    link.innerHTML = '';
                    const tempSpans = [];
                    originalText.split('').forEach(char => {
                        const s = document.createElement('span');
                        s.style.display = 'inline-block';
                        s.style.whiteSpace = 'nowrap';
                        s.style.visibility = 'hidden';
                        s.textContent = char === ' ' ? '\u00A0' : char;
                        link.appendChild(s);
                        tempSpans.push({ span: s, char });
                    });

                    const charWidths = tempSpans.map(({ span, char }) => {
                        return { char, width: Math.ceil(span.getBoundingClientRect().width * 10) / 10 };
                    });

                    // Build character boxes with locked widths
                    link.innerHTML = '';
                    const charBoxes = [];

                    charWidths.forEach(({ char, width }, i) => {
                        if (char === ' ') {
                            const space = document.createElement('span');
                            space.style.display = 'inline-block';
                            space.style.whiteSpace = 'nowrap';
                            space.style.width = `${width}px`;
                            space.innerHTML = '&nbsp;';
                            link.appendChild(space);
                            return;
                        }

                        const box = document.createElement('span');
                        box.className = 'scramble-char-box';
                        box.style.display = 'inline-block';
                        box.style.overflow = 'hidden';
                        box.style.whiteSpace = 'nowrap';
                        box.style.width = `${width}px`;
                        box.style.height = '1.3em';
                        box.style.lineHeight = '1.3em';
                        box.style.verticalAlign = 'top';
                        box.style.textAlign = 'center';

                        const col = document.createElement('span');
                        col.className = 'scramble-char-col';
                        col.style.display = 'flex';
                        col.style.flexDirection = 'column';
                        col.style.width = '100%';
                        col.style.whiteSpace = 'nowrap';

                        const r1 = letters[Math.floor(Math.random() * letters.length)];
                        const r2 = letters[Math.floor(Math.random() * letters.length)];
                        const r3 = letters[Math.floor(Math.random() * letters.length)];

                        // Items from top to bottom: [Target char, Random 1, Random 2, Random 3, Start char]
                        const charList = [char, r1, r2, r3, char];

                        charList.forEach(c => {
                            const item = document.createElement('span');
                            item.className = 'scramble-char-item';
                            item.style.display = 'block';
                            item.style.height = '1.3em';
                            item.style.lineHeight = '1.3em';
                            item.style.width = '100%';
                            item.style.textAlign = 'center';
                            item.style.whiteSpace = 'nowrap';
                            item.textContent = c;
                            col.appendChild(item);
                        });

                        col.style.transform = 'translateY(-80%)';
                        box.appendChild(col);
                        link.appendChild(box);
                        charBoxes.push({ col, index: i });
                    });

                    // Force browser reflow
                    void link.offsetWidth;

                    // Animate downward (atas ke bawah) with staggered easing
                    charBoxes.forEach(({ col, index }) => {
                        col.style.transition = `transform 0.45s cubic-bezier(0.16, 1, 0.3, 1) ${index * 35}ms`;
                        col.style.transform = 'translateY(0%)';
                    });

                    const totalDuration = 450 + (originalText.length * 35) + 50;
                    timeoutId = setTimeout(() => {
                        link.innerText = originalText;
                        link.style.whiteSpace = '';
                    }, totalDuration);
                });
            });
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
