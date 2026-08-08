<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service - Linkan</title>
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
            background-color: #0A60D4; /* Solid Blue */
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            padding: 24px 20px;
            overflow-x: hidden;
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

        /* HERO MAIN CONTENT */
        .hero {
            display: flex;
            width: 100%;
            max-width: 1100px;
            margin: auto;
            align-items: center;
            justify-content: space-between;
            gap: 40px;
            padding: 40px 0;
        }

        .hero-content {
            flex: 1;
            text-align: left;
            max-width: 580px;
            color: #FFFFFF;
        }

        .hero-title {
            font-size: clamp(2.5rem, 5vw, 3.8rem);
            font-weight: 800;
            line-height: 1.15;
            margin-bottom: 24px;
            letter-spacing: -1px;
        }

        .hero-description {
            font-size: 18px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.6;
            margin-bottom: 36px;
        }

        .create-button {
            display: inline-block;
            background: #FFFFFF;
            color: #0A60D4;
            font-weight: 700;
            font-size: 16px;
            padding: 14px 32px;
            border-radius: 50px;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(255, 255, 255, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .create-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(255, 255, 255, 0.2);
        }

        /* HERO IMAGE (MOCKUP) */
        .hero-image {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            max-width: 880px;
            position: relative;
        }

        .phone-mockup {
            width: 100%;
            display: flex;
            justify-content: center;
        }

        .phone-mockup img {
            max-width: 100%;
            height: auto;
            max-height: 90vh;
            object-fit: contain;
            filter: drop-shadow(0 20px 40px rgba(0, 0, 0, 0.15));
        }

        .floating-animation {
            animation: floating 4s ease-in-out infinite;
            transform-origin: center center;
        }

        @keyframes floating {
            0% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-12px);
            }
            100% {
                transform: translateY(0px);
            }
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
            .hero {
                flex-direction: column;
                text-align: center;
                gap: 40px;
                padding: 20px 0;
            }
            .hero-content {
                text-align: center;
                max-width: 100%;
            }
            .hero-image {
                max-width: 100%;
            }
            .phone-mockup img {
                max-height: 50vh;
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
                display: none; /* Hide links on navbar on very small screens */
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
                <a href="<?php echo e(route('pricing')); ?>" class="nav-link">Pricing</a>
                <a href="<?php echo e(route('service')); ?>" class="nav-link active">Service</a>
                <a href="<?php echo e(route('FAQ')); ?>" class="nav-link">FAQ</a>
                <a href="<?php echo e(route('login')); ?>" class="nav-link">Log In</a>
                <a href="<?php echo e(route('register')); ?>" class="btn-signup">Sign Up</a>
            </div>
        </div>
    </div>

    <!-- MAIN HERO CONTENT -->
    <main class="hero">
        <div class="hero-content">
            <h1 class="hero-title">Digital marketing for your business</h1>
            <p class="hero-description">optimize digital marketing in your business through billions of users on various effective internet marketing channels</p>
            <a href="<?php echo e(route('register')); ?>" class="create-button">Get Service</a>
        </div>
        <div class="hero-image">
            <div class="phone-mockup">
                <img src="<?php echo e(asset('images/landing page/Service.png')); ?>" alt="Mobile App Preview" class="floating-animation">
            </div>
        </div>
    </main>

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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const floatingImage = document.querySelector('.floating-animation');
            if (floatingImage) {
                document.addEventListener('mousemove', function(e) {
                    const moveX = (e.clientX - window.innerWidth/2) * 0.004;
                    const moveY = (e.clientY - window.innerHeight/2) * 0.004;
                    
                    floatingImage.style.transform = `translate(${moveX}px, ${moveY}px)`;
                });
            }
        });
    </script>
</body>
</html><?php /**PATH C:\Users\user\Documents\TUGAS PKL\linkan.id\Linkan.ID\resources\views/service.blade.php ENDPATH**/ ?>