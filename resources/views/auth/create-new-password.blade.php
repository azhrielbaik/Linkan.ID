<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Password - Linkan.ID</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/auth/create-new-password.css') }}">
</head>
<body>
    <div class="split-container">
        <!-- Left Side: Form -->
        <div class="form-side">
            <div class="form-wrapper">
                <h1 class="auth-title">Create New Password</h1>
                <p class="auth-subtitle">Your new password must be different from previoslu used password</p>

                @if (isset($errors) && $errors->any())
                    <div class="error-box">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.create-new.submit') }}">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">

                    <div class="form-group">
                        <input type="password" name="password" class="auth-pill-input" placeholder="New Password" required autofocus>
                    </div>

                    <div class="form-group">
                        <input type="password" name="password_confirmation" class="auth-pill-input" placeholder="New Password" required>
                    </div>

                    <button type="submit" class="auth-pill-btn">Confirm</button>
                </form>

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
