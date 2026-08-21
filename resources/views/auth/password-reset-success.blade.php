<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Success Changed - Linkan.ID</title>
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

        /* Left Side: Success Content */
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
            text-align: center;
        }

        .logo-header {
            margin-bottom: 28px;
            display: flex;
            justify-content: center;
        }

        .logo-img {
            height: 48px;
            width: auto;
            object-fit: contain;
        }

        .auth-title {
            font-size: 28px;
            font-weight: 900;
            color: #000000;
            text-align: center;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .auth-subtitle {
            font-size: 15px;
            font-weight: 500;
            color: #64748b;
            text-align: center;
            margin-bottom: 32px;
            line-height: 1.4;
        }

        /* Scalloped Orange Badge */
        .success-badge-wrap {
            margin: 32px auto 40px auto;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .scallop-badge {
            width: 130px;
            height: 130px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .scallop-badge svg {
            width: 100%;
            height: 100%;
            filter: drop-shadow(0 8px 16px rgba(222, 108, 32, 0.25));
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
