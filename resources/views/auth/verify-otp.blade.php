<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email - Linkan.ID</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
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

                <div class="error-box" id="errorBox" style="{{ (isset($errors) && $errors->any()) ? '' : 'display: none;' }}">
                    <i class="fas fa-exclamation-circle" style="margin-right: 4px;"></i>
                    <span id="errorMessage">{{ (isset($errors) && $errors->any()) ? $errors->first() : '' }}</span>
                </div>

                <form method="POST" action="{{ route('password.verify-otp.submit') }}" id="otpForm">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="otp_code" id="fullOtpInput" value="">

                    <div class="otp-container" id="otpContainer">
                        <input type="text" maxlength="1" class="otp-box" id="digit-1" data-index="0" autofocus autocomplete="off" inputmode="numeric" pattern="[0-9]*">
                        <input type="text" maxlength="1" class="otp-box" id="digit-2" data-index="1" autocomplete="off" inputmode="numeric" pattern="[0-9]*">
                        <input type="text" maxlength="1" class="otp-box" id="digit-3" data-index="2" autocomplete="off" inputmode="numeric" pattern="[0-9]*">
                        <input type="text" maxlength="1" class="otp-box" id="digit-4" data-index="3" autocomplete="off" inputmode="numeric" pattern="[0-9]*">
                    </div>

                    <button type="submit" class="auth-pill-btn" id="verifyBtn">Verify</button>
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
        const otpContainer = document.getElementById('otpContainer');
        const otpForm = document.getElementById('otpForm');
        const verifyBtn = document.getElementById('verifyBtn');
        const fullOtpInput = document.getElementById('fullOtpInput');
        const errorBox = document.getElementById('errorBox');
        const errorMessage = document.getElementById('errorMessage');
        const resendButton = document.getElementById('resendButton');
        const resendCountdown = document.getElementById('resendCountdown');
        const otpCountdown = document.getElementById('otpCountdown');
        const otpCreatedAt = {{ $latestRequest->created_at->getTimestamp() * 1000 }};
        const otpExpiresAt = {{ $latestRequest->expires_at->getTimestamp() * 1000 }};
        const resendAvailableAt = otpCreatedAt + 60000;

        let isVerifying = false;

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

        function getOtpValue() {
            return digits.map(d => d.value.trim()).join('');
        }

        function updateFullOtp() {
            const val = getOtpValue();
            fullOtpInput.value = val;
            return val;
        }

        function triggerShake() {
            otpContainer.classList.remove('shake');
            void otpContainer.offsetWidth; // Force reflow
            otpContainer.classList.add('shake');

            digits.forEach(d => {
                d.classList.remove('success');
                d.classList.add('error');
            });

            setTimeout(() => {
                otpContainer.classList.remove('shake');
            }, 600);
        }

        function clearErrorStyles() {
            digits.forEach(d => d.classList.remove('error'));
        }

        function showOtpError(msg) {
            if (errorMessage) {
                errorMessage.textContent = msg;
            }
            if (errorBox) {
                errorBox.style.display = 'block';
            }

            triggerShake();

            // Clear values and re-focus on digit 1 for quick retyping
            digits.forEach(d => {
                d.value = '';
                d.disabled = false;
            });
            updateFullOtp();

            if (verifyBtn) {
                verifyBtn.disabled = false;
                verifyBtn.textContent = 'Verify';
            }

            setTimeout(() => {
                digits[0].focus();
            }, 50);
        }

        async function verifyOtpCode(code) {
            if (isVerifying || code.length !== 4) return;
            isVerifying = true;

            // Update UI to verifying state
            digits.forEach(d => d.disabled = true);
            if (verifyBtn) {
                verifyBtn.disabled = true;
                verifyBtn.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right: 6px;"></i> Verifying...';
            }

            try {
                const response = await fetch("{{ route('password.verify-otp.submit') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        token: '{{ $token }}',
                        otp_code: code
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    // OTP is valid!
                    digits.forEach(d => {
                        d.classList.remove('error');
                        d.classList.add('success');
                    });
                    if (verifyBtn) {
                        verifyBtn.innerHTML = '<i class="fas fa-check" style="margin-right: 6px;"></i> Verified!';
                        verifyBtn.style.backgroundColor = '#ecfdf5';
                        verifyBtn.style.borderColor = '#10b981';
                        verifyBtn.style.color = '#059669';
                    }
                    if (errorBox) {
                        errorBox.style.display = 'none';
                    }

                    setTimeout(() => {
                        window.location.href = data.redirect || "{{ route('password.create-new') }}";
                    }, 350);
                } else {
                    // OTP is invalid / wrong
                    const message = data.message || (data.errors && data.errors.otp_code ? data.errors.otp_code[0] : 'Kode OTP salah. Silakan periksa kembali.');
                    showOtpError(message);
                }
            } catch (err) {
                // Fallback to standard form submit
                otpForm.submit();
            } finally {
                isVerifying = false;
            }
        }

        // Server-side initial error shake check
        @if (isset($errors) && $errors->any())
            triggerShake();
        @endif

        digits.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                clearErrorStyles();

                // Only keep numbers
                const val = e.target.value.replace(/\D/g, '');
                e.target.value = val;

                if (val.length >= 1) {
                    e.target.value = val[0]; // strictly 1 digit
                    if (index < digits.length - 1) {
                        digits[index + 1].focus();
                    }
                }

                const fullVal = updateFullOtp();

                // Auto-verify as soon as 4 digits are completed!
                if (fullVal.length === 4) {
                    verifyOtpCode(fullVal);
                }
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace') {
                    clearErrorStyles();
                    if (!input.value && index > 0) {
                        digits[index - 1].focus();
                        digits[index - 1].value = '';
                        updateFullOtp();
                    }
                }
            });

            // Handle paste 4-digit code
            input.addEventListener('paste', (e) => {
                e.preventDefault();
                clearErrorStyles();
                const pasteData = (e.clipboardData || window.clipboardData).getData('text').trim();
                const digitsOnly = pasteData.replace(/\D/g, '').slice(0, 4);

                if (digitsOnly.length > 0) {
                    digitsOnly.split('').forEach((char, i) => {
                        if (digits[i]) digits[i].value = char;
                    });
                    const fullVal = updateFullOtp();
                    const focusIndex = Math.min(digitsOnly.length, 3);
                    digits[focusIndex].focus();

                    // Auto-verify if 4 digits were pasted
                    if (fullVal.length === 4) {
                        verifyOtpCode(fullVal);
                    }
                }
            });
        });

        otpForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const val = updateFullOtp();
            if (val.length === 4) {
                verifyOtpCode(val);
            } else {
                showOtpError('Please enter all 4 digits.');
            }
        });
    </script>
</body>
</html>
