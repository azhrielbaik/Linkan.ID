<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('auth.register_title') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
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
                
                <h2 class="title">{{ __('auth.create_account') }}</h2>
                <p class="subtitle">{{ __('auth.register_agreement') }} <strong>{{ __('auth.terms_conditions') }}</strong> {{ __('auth.our') }}</p>

                @if(session('warning'))
                    <div class="auth-alert auth-alert-warning" role="alert">
                        <div class="auth-alert-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                        </div>
                        <div class="auth-alert-body">
                            <div class="auth-alert-title">{{ __('Perhatian') }}</div>
                            <p class="auth-alert-text">{{ session('warning') }}</p>
                            @if(isset($googleData))
                                <div style="margin-top: 10px;">
                                    <a href="{{ route('google.register') }}" style="display: inline-flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 600; color: #1e293b; background: #ffffff; padding: 7px 14px; border-radius: 8px; border: 1px solid #cbd5e1; text-decoration: none; box-shadow: 0 1px 2px rgba(0,0,0,0.06); transition: all 0.2s;">
                                        <img src="{{ asset('images/google.png') }}" alt="Google" style="width: 15px; height: 15px;">
                                        Daftar langsung dengan Google
                                    </a>
                                </div>
                            @endif
                        </div>
                        <button type="button" class="auth-alert-close" onclick="this.closest('.auth-alert').remove()" aria-label="Tutup">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>
                @endif

                @if(session('info'))
                    <div class="auth-alert auth-alert-info" role="alert">
                        <div class="auth-alert-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="16" x2="12" y2="12"></line>
                                <line x1="12" y1="8" x2="12.01" y2="8"></line>
                            </svg>
                        </div>
                        <div class="auth-alert-body">
                            <div class="auth-alert-title">{{ __('Informasi') }}</div>
                            <p class="auth-alert-text">{{ session('info') }}</p>
                        </div>
                        <button type="button" class="auth-alert-close" onclick="this.closest('.auth-alert').remove()" aria-label="Tutup">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="auth-alert auth-alert-error" role="alert">
                        <div class="auth-alert-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg>
                        </div>
                        <div class="auth-alert-body">
                            <div class="auth-alert-title">{{ __('Gagal') }}</div>
                            <p class="auth-alert-text">{{ session('error') }}</p>
                        </div>
                        <button type="button" class="auth-alert-close" onclick="this.closest('.auth-alert').remove()" aria-label="Tutup">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>
                @endif

                <form method="POST" action="{{ route('register.submit') }}" class="login-form">
                    @csrf
                    @if(isset($googleData))
                        <input type="hidden" name="google_id" value="{{ $googleData['google_id'] }}">
                    @elseif(old('google_id'))
                        <input type="hidden" name="google_id" value="{{ old('google_id') }}">
                    @endif

                    <div class="form-input-group">
                        <label for="name">{{ __('auth.full_name') }}</label>
                        <input type="text" id="name" name="name" placeholder="{{ __('auth.full_name_placeholder') }}" value="{{ $googleData['name'] ?? old('name') }}" required>
                    </div>

                    <div class="form-input-group">
                        <label for="username">{{ __('auth.username') }}</label>
                        <input type="text" id="username" name="username" placeholder="{{ __('auth.username_placeholder') }}" value="{{ old('username') }}" required>
                    </div>

                    <div class="form-input-group">
                        <label for="email">{{ __('auth.email') }}</label>
                        <input type="email" id="email" name="email" placeholder="{{ __('auth.email_example') }}" value="{{ $googleData['email'] ?? old('email') }}" required autocomplete="email">
                    </div>

                    <div class="form-input-group">
                        <label for="password">{{ __('auth.password') }}</label>
                        <div class="password-wrapper">
                            <input type="password" id="password" name="password" placeholder="{{ __('auth.password_min') }}" required>
                            <button type="button" class="toggle-password" aria-label="{{ __('auth.toggle_password') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 7c2.76 0 5 2.24 5 5 0 .65-.13 1.26-.36 1.83l2.92 2.92c1.51-1.26 2.7-2.89 3.43-4.75-1.73-4.39-6-7.5-11-7.5-1.4 0-2.74.25-3.98.7l2.16 2.16C10.74 7.13 11.35 7 12 7zM2 4.27l2.28 2.28.46.46C3.08 8.3 1.78 10.02 1 12c1.73 4.39 6 7.5 11 7.5 1.55 0 3.03-.3 4.38-.84l.42.42L19.73 22 21 20.73 3.27 3 2 4.27zM7.53 9.8l1.55 1.55c-.05.21-.08.43-.08.65 0 1.66 1.34 3 3 3 .22 0 .44-.03.65-.08l1.55 1.55c-.67.33-1.41.53-2.2.53-2.76 0-5-2.24-5-5 0-.79.2-1.53.53-2.2zm4.31-.78l3.15 3.15.02-.16c0-1.66-1.34-3-3-3l-.17.01z"/></svg>
                            </button>
                        </div>
                    </div>

                    <div class="form-input-group">
                        <label for="password_confirmation">{{ __('auth.password_confirmation') }}</label>
                        <div class="password-wrapper">
                            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="{{ __('auth.password_confirmation_placeholder') }}" required>
                            <button type="button" class="toggle-password" aria-label="{{ __('auth.toggle_password') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 7c2.76 0 5 2.24 5 5 0 .65-.13 1.26-.36 1.83l2.92 2.92c1.51-1.26 2.7-2.89 3.43-4.75-1.73-4.39-6-7.5-11-7.5-1.4 0-2.74.25-3.98.7l2.16 2.16C10.74 7.13 11.35 7 12 7zM2 4.27l2.28 2.28.46.46C3.08 8.3 1.78 10.02 1 12c1.73 4.39 6 7.5 11 7.5 1.55 0 3.03-.3 4.38-.84l.42.42L19.73 22 21 20.73 3.27 3 2 4.27zM7.53 9.8l1.55 1.55c-.05.21-.08.43-.08.65 0 1.66 1.34 3 3 3 .22 0 .44-.03.65-.08l1.55 1.55c-.67.33-1.41.53-2.2.53-2.76 0-5-2.24-5-5 0-.79.2-1.53.53-2.2zm4.31-.78l3.15 3.15.02-.16c0-1.66-1.34-3-3-3l-.17.01z"/></svg>
                            </button>
                        </div>
                    </div>

                    @error('name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                    @error('username')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror

                    <button type="submit" class="btn-submit">{{ __('auth.register_btn') }}</button>
                </form>

                <div class="auth-divider">{{ __('auth.or') }}</div>

                <a href="{{ route('google.register') }}" class="btn-google">
                    <img src="{{ asset('images/google.png') }}" alt="Google Logo">
                    Daftar dengan Google
                </a>

                <div class="footer-text">
                    {{ __('auth.already_have_account') }} <a href="{{ route('login') }}">{{ __('auth.login_link') }}</a>
                </div>
            </div>
        </div>

        <!-- Right side: Welcome Info -->
        <div class="info-side">
            <h2 class="welcome-text">{{ __('auth.welcome_to') }}</h2>
            <img src="{{ asset('images/favicon.png') }}" alt="Linkan Icon" class="logo-icon">
            <h3 class="brand-name">{{ __('auth.brand_name') }}</h3>
            <p class="brand-desc">{{ __('auth.brand_desc') }}</p>
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
        });
    </script>
</body>
</html>