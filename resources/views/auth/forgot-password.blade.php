<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Linkan.ID</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/auth/forgot-password.css') }}">
</head>
<body>
    <div class="split-container">
        <!-- Left Side: Form -->
        <div class="form-side">
            <div class="form-wrapper">
                <h1 class="auth-title">Forgot Password</h1>
                <p class="auth-subtitle">Please enter You email adress to recieve a verification card</p>

                @if (isset($errors) && $errors->any())
                    <div class="error-box">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.request-otp') }}">
                    @csrf
                    <div class="form-group">
                        <input type="email" name="email" class="auth-pill-input" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    </div>

                    <button type="submit" class="auth-pill-btn">Send</button>
                </form>

                <div class="auth-bottom-wrap">
                    <a href="{{ route('login') }}" class="auth-bottom-link">Try another way</a>
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
