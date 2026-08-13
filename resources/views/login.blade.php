<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('auth.login_title') }}</title>
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
            background: #f4f4f4; /* Light gray background matching design */
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .form-wrapper {
            width: 100%;
            max-width: 420px;
            margin-top:-10px;
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
            margin-bottom: 32px;
        }

        .subtitle strong {
            color: #121212;
            font-weight: 700;
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

        .input-hint {
            font-size: 13px;
            color: #667085;
            margin-top: 8px;
            text-align: left;
            display: block;
        }

        .password-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .password-wrapper input {
            padding-right: 48px;
        }

        .toggle-password {
            position: absolute;
            right: 16px;
            background: none;
            border: none;
            cursor: pointer;
            color: #98A2B3;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 4px;
        }

        .toggle-password:hover {
            color: #667085;
        }

        .toggle-password svg {
            width: 20px;
            height: 20px;
            fill: currentColor;
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
        }

        .btn-submit {
            width: 100%;
            padding: 16px;
            background: #ED842C; /* Bright orange matching the design */
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
        }

        .auth-divider {
            text-align: center;
            margin: 32px 0;
            color: #667085;
            font-size: 14px;
            font-weight: 500;
            position: relative;
        }
        
        .auth-divider::before, .auth-divider::after {
            content: "";
            position: absolute;
            top: 50%;
            width: 42%;
            height: 1px;
            background-color: #D0D5DD;
        }
        
        .auth-divider::before { left: 0; }
        .auth-divider::after { right: 0; }

        .btn-google {
            width: 100%;
            padding: 14px;
            background: #ffffff;
            border: 1px solid #D0D5DD;
            border-radius: 16px; 
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 600;
            color: #121212;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-google:hover {
            background: #f9fafb;
            border-color: #98A2B3;
        }

        .btn-google img {
            width: 20px;
            height: 20px;
        }

        .footer-text {
            font-size: 13px;
            color: #667085;
            text-align: center;
            margin-top: 32px;
            line-height: 1.5;
        }

        .footer-text strong {
            color: #121212;
        }

        .footer-text a {
            color: #121212;
            text-decoration: none;
            font-weight: 600;
        }

        .footer-text a:hover {
            text-decoration: underline;
        }
        
        .extra-links {
            margin-top: 16px;
            font-size: 14px;
            font-weight: 500;
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
                
                <!-- Back Button as per design -->
                <a href="{{ url('/') }}" class="btn-back-home" aria-label="{{ __('auth.back_home') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>

                <h2 class="title">{{ __('auth.login') }}</h2>
                <p class="subtitle">{{ __('auth.login_agreement') }} <strong>{{ __('auth.terms_conditions') }}</strong> {{ __('auth.our') }}</p>

                @if(session('success'))
                    <div class="success-message">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.submit') }}" class="login-form">
                    @csrf
                    
                    <div class="form-input-group">
                        <label for="email">{{ __('auth.email') }}</label>
                        <input type="text" id="email" name="email" placeholder="{{ __('auth.email_placeholder') }}" value="{{ old('email') }}" required autocomplete="email">
                        <!-- Removed input-hint to match the exact spacing of the design unless needed -->
                    </div>

                    <div class="form-input-group">
                        <label for="password">{{ __('auth.password') }}</label>
                        <div class="password-wrapper">
                            <input type="password" id="password" name="password" placeholder="{{ __('auth.password_placeholder') }}" required autocomplete="current-password">
                            <button type="button" class="toggle-password" aria-label="{{ __('auth.toggle_password') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 7c2.76 0 5 2.24 5 5 0 .65-.13 1.26-.36 1.83l2.92 2.92c1.51-1.26 2.7-2.89 3.43-4.75-1.73-4.39-6-7.5-11-7.5-1.4 0-2.74.25-3.98.7l2.16 2.16C10.74 7.13 11.35 7 12 7zM2 4.27l2.28 2.28.46.46C3.08 8.3 1.78 10.02 1 12c1.73 4.39 6 7.5 11 7.5 1.55 0 3.03-.3 4.38-.84l.42.42L19.73 22 21 20.73 3.27 3 2 4.27zM7.53 9.8l1.55 1.55c-.05.21-.08.43-.08.65 0 1.66 1.34 3 3 3 .22 0 .44-.03.65-.08l1.55 1.55c-.67.33-1.41.53-2.2.53-2.76 0-5-2.24-5-5 0-.79.2-1.53.53-2.2zm4.31-.78l3.15 3.15.02-.16c0-1.66-1.34-3-3-3l-.17.01z"/></svg>
                            </button>
                        </div>
                    </div>

                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror

                    <button type="submit" class="btn-submit">{{ __('auth.login') }}</button>
                </form>

                <div class="auth-divider">{{ __('auth.or') }}</div>

                <a href="{{ url('/login/google') }}" class="btn-google">
                    <img src="{{ asset('images/google.png') }}" alt="Google Logo">
                    {{ __('auth.login_google') }}
                </a>

                <p class="footer-text">
                    {{ __('auth.more_info') }} <strong>{{ __('auth.privacy_policy') }}</strong>{{ __('auth.our') }}
                </p>
                
                <p class="footer-text extra-links">
                    <a href="{{ route('password.request') }}">{{ __('auth.forgot_password_link') }}</a> &nbsp;|&nbsp; <a href="{{ route('register') }}">{{ __('auth.no_account') }}</a>
                </p>

            </div>
        </div>

        <!-- Right side: Image Showcase -->
        <div class="mockup-side">
            <div class="mockup-wrapper">
                <img src="{{ asset('images/login/Group 18.png') }}" alt="Login Showcase" class="mockup-image floating-animation">
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password toggle logic
            const togglePasswordBtns = document.querySelectorAll('.toggle-password');
            togglePasswordBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const input = this.previousElementSibling;
                    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                    input.setAttribute('type', type);
                    
                    if (type === 'text') {
                        this.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>`;
                    } else {
                        this.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 7c2.76 0 5 2.24 5 5 0 .65-.13 1.26-.36 1.83l2.92 2.92c1.51-1.26 2.7-2.89 3.43-4.75-1.73-4.39-6-7.5-11-7.5-1.4 0-2.74.25-3.98.7l2.16 2.16C10.74 7.13 11.35 7 12 7zM2 4.27l2.28 2.28.46.46C3.08 8.3 1.78 10.02 1 12c1.73 4.39 6 7.5 11 7.5 1.55 0 3.03-.3 4.38-.84l.42.42L19.73 22 21 20.73 3.27 3 2 4.27zM7.53 9.8l1.55 1.55c-.05.21-.08.43-.08.65 0 1.66 1.34 3 3 3 .22 0 .44-.03.65-.08l1.55 1.55c-.67.33-1.41.53-2.2.53-2.76 0-5-2.24-5-5 0-.79.2-1.53.53-2.2zm4.31-.78l3.15 3.15.02-.16c0-1.66-1.34-3-3-3l-.17.01z"/></svg>`;
                    }
                });
            });

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
