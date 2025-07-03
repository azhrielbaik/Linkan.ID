<style>
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 7%;
            background-color: #E6F0FF;
            box-shadow: 0 2px 15px rgba(0,0,0,0.03);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            padding: 1rem 7%;
            background-color:#E6F0FF;
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo img {
            height: 40px;
            width: auto;
            transition: transform 0.3s ease;
        }

        .logo img:hover {
            transform: scale(1.05);
        }

        .nav-links {
            display: flex;
            gap: 2.5rem;
            align-items: center;
        }

        .nav-links a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -4px;
            left: 0;
            background-color: #FF7733;
            transition: width 0.3s ease;
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .nav-links a:hover {
            color: #FF7733;
        }

        .login {
            color: #FF7733 !important;
            font-weight: 600;
            padding: 0.8rem 1.8rem;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .login:hover {
            background-color: rgba(255,119,51,0.1);
            transform: translateY(-2px);
        }

        .sign-up {
            background-color: #FF7733;
            color: white !important;
            padding: 0.8rem 1.8rem;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255,119,51,0.2);
        }

        .sign-up:hover {
            background-color: #ff6619;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255,119,51,0.3);
        }

        .hero {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8rem 7% 4rem;
            gap: 4rem;
            min-height: 100vh;
            position: relative;
        }

        .hero-content {
            flex: 1;
            max-width: 600px;
            animation: fadeInLeft 1s ease;
            transition: all 0.5s ease;
        }

        .hero-image {
            flex: 1;
            position: relative;
            display: flex;
            justify-content: center;
            animation: fadeInRight 1s ease;
            transition: all 0.5s ease;
        }

        .hero-content.transition {
            opacity: 0;
            transform: translateX(-50px);
        }

        .hero-image.transition {
            opacity: 0;
            transform: translateX(50px);
        }

        .hero-title {
            font-size: 54px;
            color: #333;
            margin-bottom: 1.5rem;
            line-height: 1.2;
            font-weight: 700;
        }

        .hero-title span {
            background: linear-gradient(120deg, #FF7733, #ff9966);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-description {
            font-size: 18px;
            color: #555;
            margin-bottom: 2.5rem;
            line-height: 1.8;
        }

        .create-section {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            position: relative;
        }

        .url-input {
            flex: 1;
            padding: 1.2rem;
            border: 2px solid #ddd;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        .url-input:focus {
            outline: none;
            border-color: #FF7733;
            box-shadow: 0 4px 20px rgba(255,119,51,0.1);
        }

        .create-button {
            background: linear-gradient(135deg, #FF7733, #ff9966);
            color: white;
            border: none;
            padding: 1.2rem 2.5rem;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255,119,51,0.2);
        }

        .create-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255,119,51,0.3);
            background: linear-gradient(135deg, #ff6619, #ff8c66);
        }

        .phone-mockup {
            position: relative;
            max-width: 400px;
            animation: float 6s ease-in-out infinite;
        }

        .phone-mockup img {
            width: 100%;
            height: auto;
            filter: drop-shadow(0 20px 30px rgba(0,0,0,0.1));
        }

        .floating-elements {
            position: fixed;
            width: 100%;
            height: 100vh;
            top: 0;
            left: 0;
            pointer-events: none;
        }
        .sign-up-container {
            display: flex;
            justify-content: center;
            margin-top: 20px; /* Sesuaikan jarak ke atas */
        }

        .floating-icon {
            position: absolute;
            background-color: rgba(255, 255, 255, 0.95);
            padding: 25px;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            display: flex;
            align-items: center;
            gap: 15px;
            transition: all 0.3s ease;
            animation: float 5s ease-in-out infinite;
            pointer-events: auto;
            cursor: pointer;
            backdrop-filter: blur(10px);
        }

        .floating-icon:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 12px 30px rgba(0,0,0,0.12);
            background-color: white;
        }

        .floating-icon img {
            width: 40px;
            height: 40px;
        }

        .floating-icon span {
            color: #FF7733;
            font-weight: 600;
            font-size: 16px;
            white-space: nowrap;
        }

        @keyframes float {
            0% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-15px);
            }
            100% {
                transform: translateY(0px);
            }
        }

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @media (max-width: 1024px) {
            .hero {
                padding: 6rem 5% 3rem;
            }
            
            .hero-title {
                font-size: 44px;
            }
        }

        @media (max-width: 1200px) {
            .floating-icon.calendar {
                left: 1%;
            }
            .floating-icon.book {
                right: 1%;
            }
        }
        
        @media (max-width: 768px) {
            .navbar {
                padding: 1rem 5%;
            }

            .nav-links {
                display: none;
            }

            .hero {
                flex-direction: column;
                text-align: center;
                padding-top: 5rem;
                gap: 3rem;
            }

            .hero-content {
                order: 1;
            }

            .hero-image {
                order: 2;
                margin-top: 2rem;
            }

            .hero-title {
                font-size: 36px;
            }

            .create-section {
                flex-direction: column;
            }

            .floating-icon {
                display: none;
            }
        }

</style>
<nav class="navbar">
        <div class="logo">
            <a href="<?php echo e(url('/')); ?>">
                <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Linkan Logo" id="logo">
            </a>
        </div>
        <div class="nav-links">
            <a href="<?php echo e(route('pricing')); ?>">Pricing</a>
            <a href="<?php echo e(route('service')); ?>">Service</a>
                <a href="<?php echo e(route('FAQ')); ?>">FAQ</a>
            <a href="<?php echo e(route('login')); ?>">Sign In</a>
            <a href="<?php echo e(route('register')); ?>" class="sign-up">SIGN UP FREE</a>
        </div>
    </nav><?php /**PATH C:\Ardy\2025\Semester 4\Project2\LINKAN_ID-ardy\resources\views/layout/header.blade.php ENDPATH**/ ?>