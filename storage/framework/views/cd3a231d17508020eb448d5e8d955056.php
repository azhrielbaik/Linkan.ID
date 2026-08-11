<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pricing - Linkan</title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/favicon.png')); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: #EE8025; /* Solid Orange */
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            padding: 24px 20px;
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
            height: 45px;
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
            background: #000000; /* Solid Black */
            color: #FFFFFF;
            padding: 10px 22px;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 700;
            transition: transform 0.2s, background 0.2s;
        }

        .btn-signup:hover {
            background: #222222;
            transform: scale(1.05);
        }

        /* PRICING CARD */
        .pricing-card {
            background: #FFFFFF;
            border-radius: 40px;
            border: 5px solid #0062E6; /* Royal Blue Border */
            width: 100%;
            max-width: 520px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0,0,0,0.12);
            margin-bottom: 40px;
        }

        .card-header {
            background: #F2F4F7;
            padding: 24px 32px;
            border-bottom: 1px solid #EAECF0;
        }

        .card-header h2 {
            font-size: 32px;
            font-weight: 800;
            color: #121212;
            letter-spacing: -0.5px;
        }

        .card-body {
            padding: 40px 32px;
        }

        .price-section {
            display: flex;
            align-items: baseline;
            margin-bottom: 28px;
        }

        .price-val {
            font-size: 36px;
            font-weight: 800;
            color: #121212;
            letter-spacing: -0.5px;
        }

        .price-period {
            font-size: 16px;
            font-weight: 600;
            color: #667085;
            margin-left: 8px;
        }

        .btn-get-started {
            display: block;
            width: 100%;
            background: #0062E6; /* Blue background */
            color: #FFFFFF;
            text-align: center;
            padding: 14px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 700;
            box-shadow: 0 4px 15px rgba(0, 98, 230, 0.2);
            transition: transform 0.2s, background 0.2s;
            margin-bottom: 36px;
        }

        .btn-get-started:hover {
            background: #0052c2;
            transform: translateY(-1px);
        }

        .features-title {
            font-size: 16px;
            font-weight: 700;
            color: #344054;
            margin-bottom: 24px;
            letter-spacing: -0.2px;
        }

        .feature-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .check-circle {
            width: 20px;
            height: 20px;
            background: #000000; /* Black circle */
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .check-circle svg {
            width: 12px;
            height: 12px;
            fill: #FFFFFF;
        }

        .feature-name {
            font-size: 15px;
            font-weight: 700;
            color: #121212;
        }

        /* FOOTER */
        .footer-wrapper {
            width: 100%;
            display: flex;
            justify-content: center;
            margin-top: auto;
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
            height: 45px;
            width: auto;
            display: block;
        }

        .footer-links {
            display: flex;
            gap: 32px;
        }

        .footer-link {
            font-size: 15px;
            font-weight: 700;
            color: #121212;
            transition: color 0.2s;
        }

        .footer-link:hover {
            color: #EE8025;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .navbar-pill {
                padding: 8px 16px;
            }
            .nav-links {
                gap: 16px;
            }
            .btn-signup {
                padding: 8px 16px;
                font-size: 13px;
            }
            .pricing-card {
                max-width: 100%;
            }
            .footer-pill {
                padding: 10px 24px;
            }
            .footer-links {
                gap: 20px;
            }
        }

        @media (max-width: 480px) {
            .nav-links {
                display: none; /* Hide link lists in navbar on very small screens */
            }
            .navbar-pill {
                justify-content: space-between;
            }
        }
    </style>
</head>
<body>
    <!-- NAVBAR -->
    <div class="navbar-wrapper">
        <div class="navbar-pill">
            <a href="<?php echo e(url('/')); ?>" class="nav-logo">
                <img src="<?php echo e(asset('images/Logo.png')); ?>" alt="Linkan Logo">
            </a>
            <div class="nav-links">
                <a href="<?php echo e(route('pricing')); ?>" class="nav-link active">Pricing</a>
                <a href="<?php echo e(route('service')); ?>" class="nav-link">Service</a>
                <a href="<?php echo e(route('FAQ')); ?>" class="nav-link">FAQ</a>
                <a href="<?php echo e(route('login')); ?>" class="nav-link">Log In</a>
                <a href="<?php echo e(route('register')); ?>" class="btn-signup">Sign Up</a>
            </div>
        </div>
    </div>

    <!-- PRICING CARD -->
    <div class="pricing-card">
        <div class="card-header">
            <h2>Free</h2>
        </div>
        <div class="card-body">
            <div class="price-section">
                <span class="price-val">Rp 0</span>
                <span class="price-period">Forever</span>
            </div>

            <a href="<?php echo e(route('register')); ?>" class="btn-get-started">Get Started</a>

            <h3 class="features-title">Everything in Free :</h3>

            <ul class="feature-list">
                <li class="feature-item">
                    <span class="check-circle">
                        <svg viewBox="0 0 24 24">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                    </span>
                    <span class="feature-name">Unlimited Link</span>
                </li>
                <li class="feature-item">
                    <span class="check-circle">
                        <svg viewBox="0 0 24 24">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                    </span>
                    <span class="feature-name">Digital Product Store</span>
                </li>
                <li class="feature-item">
                    <span class="check-circle">
                        <svg viewBox="0 0 24 24">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                    </span>
                    <span class="feature-name">Statistic / Traffic</span>
                </li>
                <li class="feature-item">
                    <span class="check-circle">
                        <svg viewBox="0 0 24 24">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                    </span>
                    <span class="feature-name">Link Thumbnails</span>
                </li>
                <li class="feature-item">
                    <span class="check-circle">
                        <svg viewBox="0 0 24 24">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                    </span>
                    <span class="feature-name">Templates</span>
                </li>
                <li class="feature-item">
                    <span class="check-circle">
                        <svg viewBox="0 0 24 24">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                    </span>
                    <span class="feature-name">Custom Background</span>
                </li>
                <li class="feature-item">
                    <span class="check-circle">
                        <svg viewBox="0 0 24 24">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                    </span>
                    <span class="feature-name">About Me</span>
                </li>
                <li class="feature-item">
                    <span class="check-circle">
                        <svg viewBox="0 0 24 24">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                    </span>
                    <span class="feature-name">Email Notification</span>
                </li>
                <li class="feature-item">
                    <span class="check-circle">
                        <svg viewBox="0 0 24 24">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                    </span>
                    <span class="feature-name">Donation Place</span>
                </li>
                <li class="feature-item">
                    <span class="check-circle">
                        <svg viewBox="0 0 24 24">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                    </span>
                    <span class="feature-name">Transaction Fee</span>
                </li>
            </ul>
        </div>
    </div>

    <!-- FOOTER -->
    <div class="footer-wrapper">
        <div class="footer-pill">
            <a href="<?php echo e(url('/')); ?>" class="footer-logo">
                <img src="<?php echo e(asset('images/Logo.png')); ?>" alt="Linkan Logo">
            </a>
            <div class="footer-links">
                <a href="<?php echo e(route('about')); ?>" class="footer-link">About Us</a>
                <a href="<?php echo e(route('contact.form')); ?>" class="footer-link">Contact Us</a>
            </div>
        </div>
    </div>
</body>
</html><?php /**PATH C:\Users\user\Documents\TUGAS PKL\Linkan.ID\resources\views/pricing.blade.php ENDPATH**/ ?>