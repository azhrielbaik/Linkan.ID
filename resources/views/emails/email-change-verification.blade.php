<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Perubahan Alamat Email - Linkan.ID</title>
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
                            <h2 style="margin: 0 0 12px 0; color: #0f172a; font-size: 20px; font-weight: 700;">Konfirmasi Alamat Email Baru</h2>
                            <p style="margin: 0 0 20px 0; color: #475569; font-size: 14px; line-height: 1.6;">
                                Halo <strong>{{ $userName }}</strong>,<br>
                                Kami menerima permintaan untuk mengubah alamat email akun <strong>Linkan.ID</strong> Anda ke alamat email ini. Klik tombol di bawah untuk mengonfirmasi perubahan:
                            </p>

                            <!-- Action Button -->
                            <div style="text-align: center; margin: 32px 0;">
                                <a href="{{ $verificationUrl }}" style="display: inline-block; background-color: #ED842C; color: #ffffff; text-decoration: none; padding: 14px 32px; border-radius: 10px; font-size: 15px; font-weight: 700; box-shadow: 0 4px 12px rgba(237, 132, 44, 0.25);">
                                    Konfirmasi Email Baru
                                </a>
                                <p style="color: #94a3b8; font-size: 12px; margin-top: 14px; font-weight: 500;">
                                    ⏱️ Tautan ini berlaku selama <strong>24 jam</strong>.
                                </p>
                            </div>

                            <!-- Alternate link fallback -->
                            <p style="color: #64748b; font-size: 12px; line-height: 1.5; margin-bottom: 24px; word-break: break-all;">
                                Jika tombol di atas tidak berfungsi, salin dan tempel URL berikut ke browser Anda:<br>
                                <a href="{{ $verificationUrl }}" style="color: #ED842C; text-decoration: underline;">{{ $verificationUrl }}</a>
                            </p>

                            <!-- Security Warning -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #fef2f2; border-left: 4px solid #ef4444; border-radius: 6px; padding: 12px 14px; margin-bottom: 24px;">
                                <tr>
                                    <td style="color: #991b1b; font-size: 12px; line-height: 1.5;">
                                        <strong>PENTING:</strong> Jika Anda tidak merasa melakukan permintaan perubahan ini, abaikan email ini. Alamat email lama akun Anda akan tetap aktif dan tidak berubah.
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" style="background-color: #f8fafc; padding: 20px 32px; border-top: 1px solid #f1f5f9;">
                            <p style="margin: 0; color: #94a3b8; font-size: 12px;">
                                &copy; {{ date('Y') }} Linkan.ID. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
