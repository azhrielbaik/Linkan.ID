<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email - Linkan.ID</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/auth/verify-otp.css') }}">
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

                <div class="otp-expiry-text">
                    Kode berlaku selama <strong id="otpCountdown">--:--</strong>
                </div>


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
                    <form method="POST" action="{{ route('password.verify-otp.resend') }}" class="resend-form">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <button type="submit" id="resendButton" class="resend-btn" disabled>
                            Resend code in <span id="resendCountdown">60</span>s
                        </button>
                    </form>
                    <div class="resend-help">You can generate a new code after it expires.</div>
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
        const resendButton = document.getElementById('resendButton');
        const resendCountdown = document.getElementById('resendCountdown');
        const otpCountdown = document.getElementById('otpCountdown');
        const otpCreatedAt = {{ $latestRequest->created_at->getTimestamp() * 1000 }};
        const otpExpiresAt = {{ $latestRequest->expires_at->getTimestamp() * 1000 }};
        const resendAvailableAt = otpCreatedAt + 60000;

        function updateTimers() {
            const now = Date.now();
            const resendSeconds = Math.max(0, Math.ceil((resendAvailableAt - now) / 1000));
            const otpSeconds = Math.max(0, Math.ceil((otpExpiresAt - now) / 1000));
            const minutes = String(Math.floor(otpSeconds / 60)).padStart(2, '0');
            const seconds = String(otpSeconds % 60).padStart(2, '0');

            if (resendCountdown) resendCountdown.textContent = resendSeconds;
            if (otpCountdown) otpCountdown.textContent = `${minutes}:${seconds}`;
            if (resendButton && resendSeconds === 0 && otpSeconds > 0) {
                resendButton.disabled = false;
                resendButton.textContent = 'Resend code';
            }
            if (resendButton && otpSeconds === 0) {
                resendButton.disabled = resendSeconds > 0;
                resendButton.textContent = resendSeconds > 0
                    ? `Generate new code in ${resendSeconds}s`
                    : 'Generate new code';
            }
        }

        updateTimers();
        setInterval(updateTimers, 1000);

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
