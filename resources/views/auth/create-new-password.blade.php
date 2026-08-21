<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Password - Linkan.ID</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
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

        /* Left Side: Form */
        .form-side {
            flex: 1;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .form-wrapper {
            width: 100%;
            max-width: 380px;
        }

        .auth-title {
            font-size: 30px;
            font-weight: 900;
            color: #000000;
            text-align: center;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .auth-subtitle {
            font-size: 14px;
            font-weight: 500;
            color: #64748b;
            text-align: center;
            margin-bottom: 36px;
            line-height: 1.4;
            max-width: 300px;
            margin-left: auto;
            margin-right: auto;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .auth-pill-input {
            width: 100%;
            height: 52px;
            border: 1.5px solid #4a5568;
            border-radius: 50px;
            padding: 0 24px;
            font-size: 15px;
            color: #1e293b;
            background: #ffffff;
            outline: none;
            transition: all 0.2s ease;
        }

        .auth-pill-input:focus {
            border-color: #DE6C20;
            box-shadow: 0 0 0 3px rgba(222, 108, 32, 0.15);
        }

        .auth-pill-btn {
            width: 100%;
            height: 52px;
            border: 1.5px solid #4a5568;
            border-radius: 50px;
            background: #ffffff;
            color: #4a5568;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            text-decoration: none;
            margin-top: 10px;
        }

        .auth-pill-btn:hover {
            border-color: #DE6C20;
            color: #DE6C20;
            background: #fffaf5;
            transform: translateY(-1px);
        }

        .auth-bottom-wrap {
            text-align: center;
            margin-top: 48px;
        }

        .auth-bottom-link {
            font-size: 13px;
            color: #94a3b8;
            text-decoration: none;
            transition: color 0.2s;
        }

        .auth-bottom-link:hover {
            color: #DE6C20;
        }

        .error-box {
            background: #fee2e2;
            color: #dc2626;
            padding: 10px 16px;
            border-radius: 12px;
            font-size: 13px;
            margin-bottom: 20px;
            text-align: center;
        }

        /* Right Side: Orange Mockup */
        .mockup-side {
            flex: 1;
            background: #DE6C20;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            overflow: hidden;
            position: relative;
        }

        .mockup-wrapper {
            width: 100%;
            max-width: 520px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .mockup-image {
            max-width: 100%;
            height: auto;
            max-height: 80vh;
            object-fit: contain;
            filter: drop-shadow(0 20px 40px rgba(0, 0, 0, 0.2));
        }

        .floating-animation {
            animation: floating 4s ease-in-out infinite;
        }

        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-12px); }
            100% { transform: translateY(0px); }
        }

        @media (max-width: 900px) {
            .mockup-side { display: none; }
            .form-side { padding: 30px 20px; }
        }
    </style>
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
