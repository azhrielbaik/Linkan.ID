<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Linkan</title>
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
            background: #f6faff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .register-main-container {
            display: flex;
            width: 100vw;
            min-height: 100vh;
            align-items: center;
            justify-content: center;
        }
        .register-illustration {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background: none;
        }
        .register-illustration img {
            max-width: 420px;
            width: 90%;
            animation: floating 3s ease-in-out infinite;
        }
        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
        .register-form-section {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding-top: 48px;
            padding-bottom: 48px;
        }
        .register-card {
            width: 100%;
            max-width: 420px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.12);
            padding: 0;
            overflow: hidden;
        }
        .register-card-header {
            background: #e6f0fa;
            padding: 24px 0 12px 0;
            text-align: center;
            border-bottom: 1px solid #e0e0e0;
        }
        .register-card-header h1 {
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: 2px;
            color: #222;
            margin-bottom: 6px;
        }
        .register-card-header span {
            color: #FF9040;
        }
        .register-card-header p {
            color: #333;
            font-size: 1rem;
            margin-bottom: 0;
        }
        .register-card-body {
            padding: 32px 32px 24px 32px;
        }
        .form-group {
            margin-bottom: 18px;
        }
        .form-group label {
            display: block;
            margin-bottom: 6px;
            color: #222;
            font-weight: 500;
        }
        .form-group input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #bfc9d1;
            border-radius: 7px;
            font-size: 15px;
            background: #f8f9fa;
            transition: border 0.2s;
        }
        .form-group input:focus {
            outline: none;
            border-color: #FF9040;
        }
        .form-group small {
            color: #888;
            font-size: 12px;
        }
        .btn-register {
            width: 100%;
            padding: 14px;
            background: linear-gradient(90deg, #FF9040 60%, #ffb07c 100%);
            color: #fff;
            border: none;
            border-radius: 7px;
            font-size: 17px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
            margin-bottom: 10px;
            transition: background 0.2s, box-shadow 0.2s;
            box-shadow: 0 2px 8px rgba(255,144,64,0.08);
        }
        .btn-register:hover {
            background: linear-gradient(90deg, #ffb07c 0%, #FF9040 100%);
        }
        .login-link {
            text-align: center;
            font-size: 14px;
            color: #333;
            margin-bottom: 10px;
        }
        .login-link a {
            color: #FF9040;
            text-decoration: none;
            font-weight: 500;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
        .error-message {
            color: #dc3545;
            font-size: 14px;
            margin-top: 6px;
        }
        @media (max-width: 900px) {
            .register-main-container { flex-direction: column; }
            .register-illustration { display: none; }
            .register-form-section {
                padding-top: 24px;
                padding-bottom: 24px;
            }
        }
        .logo {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 10px;
            padding: 0;
            height: 48px;
        }
        .logo img {
            height: 40px;
            width: auto;
            max-width: 180px;
            transition: transform 0.2s;
            display: block;
        }
        .logo img:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>
<div class="register-main-container">
    <div class="register-illustration">
        <img src="{{ asset('images/Singing Contract.png') }}" alt="Register Illustration" />
    </div>
    <div class="register-form-section">
        <div class="register-card">
            <div class="register-card-header">
                <div class="logo">
            <a href="{{ url('/') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Linkan Logo" id="logo">
            </a>
        </div>
                <p>Please fill in all the mandatory fileds below</p>
            </div>
            <div class="register-card-body">
                @if(session('error'))
                    <div class="error-message" style="margin-bottom: 20px;">
                        {{ session('error') }}
                    </div>
                @endif
                <form method="POST" action="{{ route('register.submit') }}">
                    @csrf
                    @if(isset($googleData))
                        <input type="hidden" name="google_id" value="{{ $googleData['google_id'] }}">
                    @endif
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" placeholder="Enter your full name" value="{{ $googleData['name'] ?? old('name') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" placeholder="Choose your username" value="{{ old('username') }}" required>
                        <small style="color: #666; font-size: 12px;">This will be your link: linkan.id/username</small>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Example@gmail.com" value="{{ $googleData['email'] ?? old('email') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="••••••••" required>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required>
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
                    <button type="submit" class="btn-register">Create Account</button>
                </form>
                <div class="login-link">
                    Already have an account? <a href="{{ route('login') }}">Sign in</a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>