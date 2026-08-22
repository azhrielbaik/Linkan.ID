<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password via OTP - Linkan.ID</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

        /* Left Side: Form Container */
        .form-side {
            flex: 1;
            background: #f4f4f4;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .form-wrapper {
            width: 100%;
            max-width: 440px;
        }

        .btn-back-home {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            color: #121212;
            text-decoration: none;
            transition: all 0.2s ease;
            margin-bottom: 24px;
        }

        .btn-back-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .title {
            font-size: 26px;
            font-weight: 800;
            color: #121212;
            margin-bottom: 8px;
        }

        .subtitle {
            font-size: 14px;
            color: #666;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        /* Status Banners */
        .status-box {
            border-radius: 12px;
            padding: 14px 16px;
            font-size: 13px;
            line-height: 1.5;
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .status-box.pending {
            background: #fef3c7;
            border: 1px solid #fde68a;
            color: #92400e;
        }

        .status-box.approved {
            background: #dcfce7;
            border: 1px solid #bbf7d0;
            color: #166534;
        }

        .status-box.rejected {
            background: #fee2e2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        .status-icon {
            font-size: 18px;
            margin-top: 2px;
            flex-shrink: 0;
        }

        .form-input-group {
            margin-bottom: 16px;
        }

        .form-input-group label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            color: #333;
            margin-bottom: 6px;
        }

        .form-input-group input {
            width: 100%;
            height: 48px;
            padding: 0 16px;
            border: 1.5px solid #e0e0e0;
            border-radius: 10px;
            font-size: 14px;
            font-family: inherit;
            background: #ffffff;
            outline: none;
            transition: all 0.2s;
        }

        .form-input-group input:focus {
            border-color: #5A5BF1;
            box-shadow: 0 0 0 3px rgba(90, 91, 241, 0.12);
        }

        .form-input-group input[readonly] {
            background: #f1f5f9;
            color: #64748b;
            cursor: not-allowed;
        }

        .otp-input-wrap {
            position: relative;
        }

        .otp-input-wrap input {
            letter-spacing: 6px;
            font-size: 20px;
            font-weight: 800;
            text-align: center;
            font-family: 'Courier New', monospace;
            color: #5A5BF1;
        }

        .password-wrapper {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            font-size: 16px;
        }

        .btn-submit {
            width: 100%;
            height: 48px;
            background: #5A5BF1;
            color: #ffffff;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-submit:hover {
            background: #4849d9;
            transform: translateY(-1px);
        }

        .btn-submit:disabled {
            background: #a5b4fc;
            cursor: not-allowed;
            transform: none;
        }

        .error-message {
            background: #fee2e2;
            color: #dc2626;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 16px;
        }

        /* Right side: Mockup */
        .mockup-side {
            flex: 1;
            background: linear-gradient(135deg, #EEF0FE 0%, #E0E3FD 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            overflow: hidden;
            position: relative;
        }

        .mockup-wrapper {
            width: 100%;
            max-width: 500px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .mockup-image {
            max-width: 100%;
            height: auto;
            max-height: 75vh;
            object-fit: contain;
            filter: drop-shadow(0 20px 40px rgba(0, 0, 0, 0.15));
        }

        .floating-animation {
            animation: floating 4s ease-in-out infinite;
        }

        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-12px); }
            100% { transform: translateY(0px); }
        }

        @media (max-width: 768px) {
            .mockup-side { display: none; }
            .form-side { padding: 24px; }
        }
    </style>
</head>
<body>
    <div class="split-container">
        <!-- Left side: Form -->
        <div class="form-side">
            <div class="form-wrapper">
                
                <!-- Back Button -->
                <a href="{{ route('login') }}" class="btn-back-home" title="Kembali ke Login">
                    <i class="fas fa-arrow-left"></i>
                </a>

                <h2 class="title">Reset Password</h2>
                <p class="subtitle">Buat password baru akun seller Anda dengan memasukkan Kode OTP dari Admin Platform.</p>

                @if ($errors->any())
                    <div class="error-message">
                        <ul style="padding-left: 18px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('status'))
                    <div class="status-box pending" id="flashStatusBox">
                        <i class="fas fa-clock status-icon"></i>
                        <div>
                            <strong>Permintaan Terkirim!</strong>
                            <div>{{ session('status') }}</div>
                        </div>
                    </div>
                @endif

                <!-- Dynamic Status Indicator -->
                <div id="dynamicStatusBox" class="status-box pending" style="{{ session('status') ? 'display:none;' : '' }}">
                    <i class="fas fa-spinner fa-spin status-icon" id="dynamicStatusIcon"></i>
                    <div>
                        <strong id="dynamicStatusTitle">Menunggu Persetujuan Admin</strong>
                        <div id="dynamicStatusDesc">Permintaan Anda sedang ditinjau oleh Admin Platform. Kode OTP akan terisi otomatis saat disetujui.</div>
                    </div>
                </div>

                <form method="POST" action="{{ route('password.otp.submit') }}" class="login-form">
                    @csrf
                    
                    <div class="form-input-group">
                        <label for="email">Alamat Email Terdaftar</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $email) }}" readonly>
                    </div>

                    <div class="form-input-group">
                        <label for="otp_code">Kode OTP (6 Digit dari Admin)</label>
                        <div class="otp-input-wrap">
                            <input type="text" id="otp_code" name="otp_code" placeholder="000000" maxlength="6" 
                                   value="{{ old('otp_code', ($latestRequest && $latestRequest->isApproved()) ? $latestRequest->otp_code : '') }}" 
                                   required autocomplete="off">
                        </div>
                    </div>

                    <div class="form-input-group">
                        <label for="password">Password Baru (Minimal 8 Karakter)</label>
                        <div class="password-wrapper">
                            <input type="password" id="password" name="password" placeholder="••••••••" required>
                            <button type="button" class="toggle-password" onclick="toggleVisibility('password', this)">
                                <i class="far fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-input-group">
                        <label for="password_confirmation">Konfirmasi Password Baru</label>
                        <div class="password-wrapper">
                            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required>
                            <button type="button" class="toggle-password" onclick="toggleVisibility('password_confirmation', this)">
                                <i class="far fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit" id="btnSubmitNewPassword">
                        <i class="fas fa-check-circle"></i> Simpan Password Baru
                    </button>
                </form>

                <div style="text-align: center; margin-top: 20px; font-size: 13px; color: #666;">
                    Ingat password Anda? <a href="{{ route('login') }}" style="color: #5A5BF1; font-weight: 700; text-decoration: none;">Masuk ke Akun</a>
                </div>

            </div>
        </div>

        <!-- Right side: Mockup -->
        <div class="mockup-side">
            <div class="mockup-wrapper">
                <img src="{{ asset('images/logohp.png') }}" alt="Reset Password Linkan.ID" class="mockup-image floating-animation">
            </div>
        </div>
    </div>

    <script>
        const checkStatusUrl = "{{ route('password.otp.status') }}";
        const userEmail = "{{ $email }}";
        let isApproved = false;

        function toggleVisibility(inputId, btn) {
            const input = document.getElementById(inputId);
            if (!input) return;
            if (input.type === 'password') {
                input.type = 'text';
                btn.innerHTML = '<i class="far fa-eye-slash"></i>';
            } else {
                input.type = 'password';
                btn.innerHTML = '<i class="far fa-eye"></i>';
            }
        }

        // Smart polling status persetujuan OTP admin
        function checkAdminApproval() {
            if (isApproved) return;

            fetch(`${checkStatusUrl}?email=${encodeURIComponent(userEmail)}`)
                .then(res => res.json())
                .then(data => {
                    const statusBox = document.getElementById('dynamicStatusBox');
                    const statusTitle = document.getElementById('dynamicStatusTitle');
                    const statusDesc = document.getElementById('dynamicStatusDesc');
                    const statusIcon = document.getElementById('dynamicStatusIcon');
                    const otpInput = document.getElementById('otp_code');
                    const flashBox = document.getElementById('flashStatusBox');

                    if (flashBox && data.status === 'approved') {
                        flashBox.style.display = 'none';
                    }

                    if (data.status === 'approved' && data.otp_code) {
                        isApproved = true;
                        if (otpInput && !otpInput.value) {
                            otpInput.value = data.otp_code;
                        }
                        if (statusBox) {
                            statusBox.style.display = 'flex';
                            statusBox.className = 'status-box approved';
                            statusTitle.innerText = 'Permintaan Disetujui Admin!';
                            statusDesc.innerText = `Kode OTP (${data.otp_code}) telah diverifikasi & terisi otomatis. Silakan masukkan password baru Anda.`;
                            statusIcon.className = 'fas fa-check-circle status-icon';
                        }
                    } else if (data.status === 'rejected') {
                        if (statusBox) {
                            statusBox.style.display = 'flex';
                            statusBox.className = 'status-box rejected';
                            statusTitle.innerText = 'Permintaan Ditolak';
                            statusDesc.innerText = data.admin_notes ? `Catatan Admin: ${data.admin_notes}` : 'Permintaan reset password Anda ditolak oleh Admin Platform.';
                            statusIcon.className = 'fas fa-times-circle status-icon';
                        }
                    } else if (data.status === 'expired') {
                        if (statusBox) {
                            statusBox.style.display = 'flex';
                            statusBox.className = 'status-box rejected';
                            statusTitle.innerText = 'Kode OTP Kedaluwarsa';
                            statusDesc.innerText = 'Batas waktu OTP telah habis. Silakan ajukan permohonan baru dari halaman Lupa Password.';
                            statusIcon.className = 'fas fa-exclamation-triangle status-icon';
                        }
                    }
                })
                .catch(err => console.warn('Polling error:', err));
        }

        // Cek langsung saat halaman dibuka dan ulangi setiap 3 detik
        document.addEventListener('DOMContentLoaded', function() {
            checkAdminApproval();
            setInterval(checkAdminApproval, 3000);
        });
    </script>
</body>
</html>
