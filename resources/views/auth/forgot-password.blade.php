<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('auth.forgot_password_title') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: #ffffff;
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
        }

        .split-container {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        /* Left Side: Form Container */
        .form-side {
            flex: 1;
            background: #f4f4f4;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .form-wrapper {
            width: 100%;
            max-width: 420px;
            margin-top:-108px;
        }

        .btn-back-home {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 46px;
            height: 46px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            color: #121212;
            text-decoration: none;
            transition: all 0.2s ease;
            margin-bottom: 36px;
        }

        .btn-back-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .btn-back-home svg {
            width: 20px;
            height: 20px;
            fill: currentColor;
        }

        .title {
            font-size: 32px;
            font-weight: 800;
            color: #121212;
            text-align: center;
            margin-bottom: 12px;
            letter-spacing: -0.5px;
        }

        .subtitle {
            font-size: 15px;
            font-weight: 500;
            color: #667085;
            text-align: center;
            margin-bottom: 40px;
            line-height: 1.5;
        }

        .login-form {
            width: 100%;
        }

        .form-input-group {
            margin-bottom: 24px;
        }

        .form-input-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #667085;
            margin-bottom: 10px;
            text-align: left;
        }

        .form-input-group input {
            width: 100%;
            padding: 16px 20px;
            border: 1px solid #E4E7EC;
            border-radius: 16px;
            font-size: 15px;
            font-weight: 500;
            background-color: #ffffff;
            color: #121212;
            outline: none;
            transition: all 0.3s ease;
        }

        .form-input-group input:focus {
            border-color: #EE8025;
            box-shadow: 0 0 0 4px rgba(238, 128, 37, 0.1);
        }

        .form-input-group input::placeholder {
            color: #98A2B3;
            font-weight: 400;
        }

        .error-message {
            color: #d93025;
            font-size: 14px;
            margin-top: -10px;
            margin-bottom: 20px;
        }

        .success-message {
            background: #e6f4ea;
            color: #137333;
            padding: 12px 20px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 24px;
            text-align: center;
        }

        .btn-submit {
            width: 100%;
            padding: 16px;
            background: #ED842C;
            color: #ffffff;
            border: none;
            border-radius: 16px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 8px;
        }

        .btn-submit:hover {
            background: #ED842C;
            transform: translateY(-1px);
            opacity: 0.9;
        }

        .footer-text {
            font-size: 14px;
            color: #667085;
            text-align: center;
            margin-top: 32px;
            font-weight: 500;
        }

        .footer-text a {
            color: #121212;
            text-decoration: none;
            font-weight: 600;
        }

        .footer-text a:hover {
            text-decoration: underline;
        }

        /* Right Side: Mockup Showcase Container */
        .mockup-side {
            flex: 1;
            background: #ED842C;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            overflow: hidden;
            position: relative;
        }

        .mockup-wrapper {
            width: 100%;
            max-width: 600px;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .mockup-image {
            max-width: 100%;
            height: auto;
            max-height: 80vh;
            object-fit: contain;
            filter: drop-shadow(0 20px 40px rgba(0, 0, 0, 0.15));
        }

        .floating-animation {
            animation: floating 4s ease-in-out infinite;
            transform-origin: center center;
        }

        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }

        /* Responsive Layout styling */
        @media (max-width: 900px) {
            .form-side { padding: 30px; }
            .mockup-side { padding: 30px; }
        }

        @media (max-width: 768px) {
            .mockup-side { display: none; }
            .form-side { flex: 1; width: 100%; }
        }
    </style>
</head>
<body>
    <div class="split-container">
        <!-- Left side: Form -->
        <div class="form-side">
            <div class="form-wrapper">
                
                <!-- Back Button -->
                <a href="{{ route('login') }}" class="btn-back-home" aria-label="{{ __('auth.back_to_login') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>

                <h2 class="title">{{ __('auth.forgot_password_heading') }}</h2>
                <p class="subtitle">{{ __('auth.forgot_password_desc') }}</p>

                @if (session('status'))
                    <div class="success-message">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="login-form">
                    @csrf
                    
                    <div class="form-input-group">
                        <label for="email">{{ __('auth.email_address') }}</label>
                        <input type="email" id="email" name="email" placeholder="{{ __('auth.email_example') }}" value="{{ old('email') }}" required autocomplete="email">
                    </div>

                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror

                    <button type="submit" class="btn-submit">{{ __('auth.send_link') }}</button>
                </form>
                
                <p class="footer-text">
                    {{ __('auth.back_to') }} <a href="{{ route('login') }}">{{ __('auth.login_page') }}</a>
                </p>

            </div>
        </div>

        <!-- Right side: Image Showcase -->
        <div class="mockup-side">
            <div class="mockup-wrapper">
                <img src="{{ asset('images/logohp.png') }}" alt="Forgot Password Showcase" class="mockup-image floating-animation">
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
</html>
