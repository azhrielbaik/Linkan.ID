<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password via OTP - Linkan.ID</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/auth/reset-password-otp.css') }}">
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
