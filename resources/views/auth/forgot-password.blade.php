<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Linkan</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                font-family: 'Inter', sans-serif;
            }

            body {
                background-color: #ffffff;
                background-image: url('{{ asset('images/background.png') }}');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                display: flex;
                min-height: 100vh;
                position: relative;
            }

            body::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-image: url('{{ asset('images/background.png') }}');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                opacity: 0.1;
                z-index: 0;
                pointer-events: none;
            }

            .container {
                display: flex;
                width: 100%;
                max-width: 1440px;
                margin: auto;
                align-items: center;
                justify-content: space-between;
                padding: 0 80px;
                position: relative;
                z-index: 2;
            }

            .left-side {
                flex: 0 0 45%;
                max-width: 600px;
                margin-right: 40px;
            }

            .left-side img {
                width: 100%;
                height: auto;
                object-fit: contain;
            }

            .login-container {
                flex: 0 0 400px;
                margin-left: auto;
                position: relative;
                z-index: 3;
            }

            .logo {
                display: flex;
                align-items: center;
                margin-bottom: 0;
            }

            .logo img {
                height: 40px;
                width: auto;
                transition: transform 0.3s ease;
                filter: none;
            }

            .logo img:hover {
                transform: scale(1.05);
            }

            .login-box {
                background: white;
                padding: 40px;
                border-radius: 24px;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
                width: 100%;
                position: relative;
            }

            .login-box h2 {
                font-size: 24px;
                margin-bottom: 32px;
                color: #333;
                font-weight: 600;
            }

            .form-group {
                position: relative;
                margin-bottom: 24px;
            }

            .form-group label {
                display: block;
                margin-bottom: 10px;
                color: #333;
                font-size: 15px;
                font-weight: 500;
            }

            .form-group input {
                width: 100%;
                padding: 14px 18px;
                border: 1px solid #e1e1e1;
                border-radius: 14px;
                font-size: 15px;
                background-color: #fff;
                transition: all 0.3s ease;
                position: relative;
                z-index: 2;
            }

            .form-group input:focus {
                outline: none;
                border-color: #FF7F50;
                box-shadow: 0 0 0 4px rgba(255, 127, 80, 0.1);
            }

            .form-group input::placeholder {
                color: #999;
            }

            .forgot-password {
                text-align: right;
                margin: -12px 0 24px;
            }

            .forgot-password a {
                color: #FF7F50;
                text-decoration: none;
                font-size: 14px;
                font-weight: 500;
            }

            .btn-login {
                width: 100%;
                padding: 16px;
                background: linear-gradient(135deg, #FF7F50, #ff6b3b);
                color: white;
                border: none;
                border-radius: 14px;
                font-size: 16px;
                font-weight: 600;
                cursor: pointer;
                margin-bottom: 28px;
                transition: all 0.3s ease;
                position: relative;
                z-index: 2;
            }

            .btn-login:hover {
                background: linear-gradient(135deg, #ff6b3b, #FF7F50);
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(255, 127, 80, 0.3);
            }

            .divider {
                text-align: center;
                margin: 24px 0;
                color: #666;
                font-size: 14px;
                position: relative;
            }

            .divider::before,
            .divider::after {
                content: "";
                position: absolute;
                top: 50%;
                width: 45%;
                height: 1px;
                background-color: #e1e1e1;
            }

            .divider::before {
                left: 0;
            }

            .divider::after {
                right: 0;
            }

            .google-login {
                width: 100%;
                padding: 14px;
                background: #fff;
                border: 1px solid #e1e1e1;
                border-radius: 14px;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 12px;
                cursor: pointer;
                font-size: 15px;
                font-weight: 500;
                color: #333;
                transition: all 0.3s ease;
                position: relative;
                z-index: 2;
            }

            .google-login:hover {
                background: #f8f8f8;
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            }

            .google-login img {
                width: 24px;
            }

            .error-message {
                color: #dc3545;
                font-size: 14px;
                margin-top: 6px;
            }

            @media (max-width: 1200px) {
                .container {
                    padding: 0 40px;
                }
                
                .left-side {
                    flex: 0 0 40%;
                }
            }

            .navbar {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 1.5rem 7%;
                background-color: transparent;
                position: fixed;
                width: 100%;
                top: 0;
                z-index: 1000;
                transition: all 0.3s ease;
            }

            .navbar.scrolled {
                padding: 1rem 7%;
                background-color: rgba(255, 255, 255, 0.8);
                backdrop-filter: blur(10px);
            }

            .logo {
                display: flex;
                align-items: center;
                margin-bottom: 0;
            }

            .logo img {
                height: 40px;
                width: auto;
                transition: transform 0.3s ease;
                filter: none;
            }

            .logo img:hover {
                transform: scale(1.05);
            }

            @media (max-width: 992px) {
                .container {
                    padding: 20px;
                    flex-direction: column;
                    gap: 40px;
                }

                .left-side {
                    flex: 0 0 auto;
                    margin-right: 0;
                    max-width: 400px;
                }

                .login-container {
                    margin-left: 0;
                }
            }

            @media (max-width: 480px) {
                .left-side {
                    display: none;
                }

                .login-box {
                    padding: 30px 20px;
                }
            }

            .floating-animation {
                animation: floating 3s ease-in-out infinite;
                transform-origin: center center;
            }

            @keyframes floating {
                0% {
                    transform: translateY(0px);
                }
                50% {
                    transform: translateY(-20px);
                }
                100% {
                    transform: translateY(0px);
                }
            }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <a href="{{ url('/') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Linkan Logo" id="logo">
            </a>
        </div>
    </nav>
    <div class="container">
        <div class="left-side">
            <img src="{{ asset('images/logohp.png') }}" alt="Forgot Password Illustration" class="floating-animation">
        </div>

        <div class="login-container">
            <div class="login-box">
                <h2>Forgot Password</h2>

                @if (session('status'))
                    <div class="success-message" style="background: #e0ffe0; padding: 10px; border-radius: 5px; margin-bottom: 20px; color: #007500;">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" placeholder="Example@gmail.com" required>
                    </div>

                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror

                    <button type="submit" class="btn-login">Send Reset Link</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Salin bagian script dari login.blade.php
        document.addEventListener('DOMContentLoaded', function () {
            const floatingImage = document.querySelector('.floating-animation');
            document.addEventListener('mousemove', function (e) {
                const moveX = (e.clientX - window.innerWidth / 2) * 0.005;
                const moveY = (e.clientY - window.innerHeight / 2) * 0.005;
                floatingImage.style.transform = `translate(${moveX}px, ${moveY}px) translateY(${getFloatingY()}px)`;
            });

            function getFloatingY() {
                const time = Date.now() * 0.001;
                return Math.sin(time) * 20;
            }

            function updateFloating() {
                if (!document.hidden) {
                    const currentY = getFloatingY();
                    floatingImage.style.transform = `translateY(${currentY}px)`;
                }
                requestAnimationFrame(updateFloating);
            }

            updateFloating();

            window.addEventListener('scroll', function () {
                const navbar = document.querySelector('.navbar');
                if (window.scrollY > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            });
        });
    </script>
</body>
</html>
