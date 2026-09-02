<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('auth.login_title') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
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

                    @if(session('lockout_seconds'))
                        <div class="error-message" id="lockout-notice" style="margin-bottom:16px;">
                            Terlalu banyak percobaan login. Coba lagi dalam <span id="lockout-timer">{{ session('lockout_seconds') }}</span> detik.
                        </div>
                    @else
                        @error('email')
                            <div class="error-message" id="error-message">{{ $message }}</div>
                        @enderror
                    @endif

                    <button type="submit" class="btn-submit" id="btn-submit">{{ __('auth.login') }}</button>
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
                <img src="{{ asset('images/Login/Group 18.png') }}" alt="Login Showcase" class="mockup-image floating-animation">
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

            // Lockout countdown timer
            const timerEl = document.getElementById('lockout-timer');
            const submitBtn = document.getElementById('btn-submit');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');

            if (timerEl) {
                let seconds = parseInt(timerEl.textContent, 10);

                // Disable form inputs during lockout
                if (submitBtn) { submitBtn.disabled = true; submitBtn.style.opacity = '0.5'; submitBtn.style.cursor = 'not-allowed'; }
                if (emailInput) { emailInput.disabled = true; }
                if (passwordInput) { passwordInput.disabled = true; }

                const interval = setInterval(function() {
                    seconds--;
                    timerEl.textContent = seconds;

                    if (seconds <= 0) {
                        clearInterval(interval);
                        // Re-enable form
                        if (submitBtn) { submitBtn.disabled = false; submitBtn.style.opacity = ''; submitBtn.style.cursor = ''; }
                        if (emailInput) { emailInput.disabled = false; }
                        if (passwordInput) { passwordInput.disabled = false; }
                        // Hide lockout notice
                        const notice = document.getElementById('lockout-notice');
                        if (notice) notice.style.display = 'none';
                        const errMsg = document.getElementById('error-message');
                        if (errMsg) errMsg.style.display = 'none';
                    }
                }, 1000);
            }
        });
    </script>
</body>
</html>
