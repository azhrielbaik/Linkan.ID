<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Success Changed - Linkan.ID</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/auth/password-reset-success.css') }}">
</head>
<body>
    <div class="split-container">
        <!-- Left Side: Success Content -->
        <div class="form-side">
            <div class="form-wrapper">
                <!-- Logo -->
                <div class="logo-header">
                    <img src="{{ asset('images/Logo.png') }}" alt="LiNKAN" class="logo-img">
                </div>

                <h1 class="auth-title">Password Success Changed</h1>
                <p class="auth-subtitle">Your new password Already</p>

                <!-- Scalloped Badge with Checkmark -->
                <div class="success-badge-wrap">
                    <div class="scallop-badge">
                        <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <!-- 16-point scalloped seal -->
                            <path d="M50 0C53.5 6.5 61.2 9.7 68.3 7.8C73.8 12.8 74.5 21 69.9 26.8C74.6 32.5 73.8 40.7 68.3 45.7C71.2 52.8 68.5 60.5 62 64C62.5 71.5 57.5 77.8 50 79C42.5 77.8 37.5 71.5 38 64C31.5 60.5 28.8 52.8 31.7 45.7C26.2 40.7 25.4 32.5 30.1 26.8C25.5 21 26.2 12.8 31.7 7.8C38.8 9.7 46.5 6.5 50 0Z" transform="translate(0, 5) scale(1.05)" fill="#DE6C20" />
                            <!-- Clean rosette circle / flower badge -->
                            <circle cx="50" cy="50" r="45" fill="#DE6C20" />
                            <!-- White Checkmark -->
                            <path d="M32 50L44 62L68 38" stroke="#FFFFFF" stroke-width="8" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                </div>

                <a href="{{ route('login') }}" class="auth-pill-btn">Back to Login</a>

                <div class="auth-bottom-wrap">
                    <span class="auth-bottom-link">Change Password</span>
                </div>
            </div>
        </div>

        <!-- Right Side: Orange Showcase -->
        <div class="mockup-side">
            <div class="mockup-wrapper">
                <img src="{{ asset('images/Login/Group 18.png') }}" alt="Linkan.ID Showcase" class="mockup-image floating-animation">
            </div>
        </div>
    </div>
</body>
</html>
