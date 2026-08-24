<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode Verifikasi Reset Password - Linkan.ID</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f6f8; font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased; color: #334155;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f4f6f8; padding: 40px 15px;">
        <tr>
            <td align="center">
                <!-- Main Container -->
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width: 520px; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06); border: 1px solid #e2e8f0;">
                    
                    <!-- Header with Logo / Brand -->
                    <tr>
                        <td align="center" style="background: linear-gradient(135deg, #DE6C20 0%, #c45712 100%); padding: 32px 20px;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 26px; font-weight: 800; letter-spacing: -0.5px;">Linkan<span style="color: #ffd8b3;">.ID</span></h1>
                            <p style="margin: 6px 0 0 0; color: rgba(255, 255, 255, 0.9); font-size: 13px; font-weight: 500;">Solusi Toko & Produk Digital Terpercaya</p>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 36px 32px 24px 32px;">
                            <h2 style="margin: 0 0 12px 0; color: #0f172a; font-size: 20px; font-weight: 700;">Permintaan Reset Password</h2>
                            <p style="margin: 0 0 20px 0; color: #475569; font-size: 14px; line-height: 1.6;">
                                Halo <strong>{{ $userName }}</strong>,<br>
                                Kami menerima permintaan untuk mengatur ulang kata sandi akun <strong>Linkan.ID</strong> Anda. Gunakan kode verifikasi (OTP) berikut untuk melanjutkan:
                            </p>

                            <!-- OTP Box Display -->
                            <div style="text-align: center; margin: 28px 0; background-color: #fffaf5; border: 2px dashed #DE6C20; border-radius: 12px; padding: 24px 16px;">
                                <div style="color: #64748b; font-size: 12px; text-transform: uppercase; font-weight: 700; letter-spacing: 1px; margin-bottom: 8px;">
                                    KODE VERIFIKASI (OTP)
                                </div>
                                <div style="font-size: 36px; font-weight: 900; letter-spacing: 10px; color: #DE6C20; font-family: 'Courier New', Courier, monospace;">
                                    {{ $otpCode }}
                                </div>
                                <div style="color: #94a3b8; font-size: 12px; margin-top: 8px; font-weight: 500;">
                                    ⏱️ Berlaku selama <strong>{{ $expiresInMinutes }} menit</strong>
                                </div>
                            </div>

                            <!-- Security Warning -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #fef2f2; border-left: 4px solid #ef4444; border-radius: 6px; padding: 12px 14px; margin-bottom: 24px;">
                                <tr>
                                    <td style="color: #991b1b; font-size: 12px; line-height: 1.5;">
                                        <strong>PENTING:</strong> Jangan berikan kode OTP ini kepada siapa pun, termasuk pihak yang mengatasnamakan <strong>Linkan.ID</strong>. Tim kami tidak pernah meminta kode rahasia ini.
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 0; color: #64748b; font-size: 13px; line-height: 1.5;">
                                Jika Anda tidak merasa melakukan permintaan ini, silakan abaikan email ini. Akun Anda akan tetap aman.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8fafc; padding: 20px 32px; border-top: 1px solid #e2e8f0; text-align: center;">
                            <p style="margin: 0; color: #94a3b8; font-size: 12px; line-height: 1.5;">
                                &copy; {{ date('Y') }} <strong>Linkan.ID</strong>. All rights reserved.<br>
                                Email ini dikirim secara otomatis oleh sistem keamanan Linkan.ID.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
