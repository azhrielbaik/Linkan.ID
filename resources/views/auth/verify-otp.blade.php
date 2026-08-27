<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email - Linkan.ID</title>
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
            margin-bottom: 32px;
            line-height: 1.4;
            max-width: 300px;
            margin-left: auto;
            margin-right: auto;
        }

        .auth-subtitle .user-email {
            color: #0f172a;
            font-weight: 600;
            display: block;
            margin-top: 2px;
        }

        /* 4-Digit OTP Boxes */
        .otp-container {
            display: flex;
            justify-content: center;
            gap: 16px;
            margin-bottom: 28px;
        }

        .otp-box {
            width: 58px;
            height: 58px;
            border: 1.5px solid #4a5568;
            border-radius: 12px;
            font-size: 24px;
            font-weight: 800;
            text-align: center;
            color: #0f172a;
            background: #ffffff;
            outline: none;
            transition: all 0.2s ease;
        }

        .otp-box:focus {
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

        .status-box {
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 13px;
            margin-bottom: 20px;
            text-align: center;
            line-height: 1.4;
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
            .otp-box { width: 50px; height: 50px; font-size: 20px; }
        }
    </style>
</head>
<body>
    <div class="split-container">
        <!-- Left Side: Form -->
        <div class="form-side">
            <div class="form-wrapper">
                <h1 class="auth-title">Verify Your Email</h1>
                <p class="auth-subtitle">
                    Please enter the 4 digit code sent to<br>
                    <span class="user-email">{{ $email }}</span>
                </p>

                @if (session('status'))
                    <div class="status-box">
                        <i class="fas fa-check-circle" style="margin-right: 4px;"></i> {{ session('status') }}
                    </div>
                @endif

                @if (isset($errors) && $errors->any())
                    <div class="error-box">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.verify-otp.submit') }}" id="otpForm">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="otp_code" id="fullOtpInput" value="">

                    <div class="otp-container">
                        <input type="text" maxlength="1" class="otp-box" id="digit-1" data-index="0" autofocus autocomplete="off" inputmode="numeric">
                        <input type="text" maxlength="1" class="otp-box" id="digit-2" data-index="1" autocomplete="off" inputmode="numeric">
                        <input type="text" maxlength="1" class="otp-box" id="digit-3" data-index="2" autocomplete="off" inputmode="numeric">
                        <input type="text" maxlength="1" class="otp-box" id="digit-4" data-index="3" autocomplete="off" inputmode="numeric">
                    </div>

                    <button type="submit" class="auth-pill-btn">Verify</button>
                </form>

                <div class="auth-bottom-wrap">
                    <a href="{{ route('password.request') }}" class="auth-bottom-link">Resend code</a>
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

    <script>
        const digits = [
            document.getElementById('digit-1'),
            document.getElementById('digit-2'),
            document.getElementById('digit-3'),
            document.getElementById('digit-4')
        ];
        const fullOtpInput = document.getElementById('fullOtpInput');

        function updateFullOtp() {
            const val = digits.map(d => d.value).join('');
            fullOtpInput.value = val;
        }

        digits.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                const val = e.target.value;
                if (val.length === 1) {
                    if (index < digits.length - 1) {
                        digits[index + 1].focus();
                    }
                }
                updateFullOtp();
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !input.value && index > 0) {
                    digits[index - 1].focus();
                }
            });

            // Handle paste 4-digit code
            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const pasteData = (e.clipboardData || window.clipboardData).getData('text').trim();
                if (/^\d{4}$/.test(pasteData)) {
                    pasteData.split('').forEach((char, i) => {
                        if (digits[i]) digits[i].value = char;
                    });
                    updateFullOtp();
                    digits[3].focus();
                }
            });
        });

        document.getElementById('otpForm').addEventListener('submit', (e) => {
            updateFullOtp();
        });
    </script>
</body>
</html>
