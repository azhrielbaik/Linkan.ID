<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $announcement->title }} - Linkan.ID</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f6f8; font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased; color: #334155;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f4f6f8; padding: 40px 15px;">
        <tr>
            <td align="center">
                <!-- Main Container -->
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width: 540px; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06); border: 1px solid #e2e8f0;">
                    
                    <!-- Header with Logo / Brand -->
                    <tr>
                        <td align="center" style="background: linear-gradient(135deg, #DE6C20 0%, #c45712 100%); padding: 32px 20px;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 26px; font-weight: 800; letter-spacing: -0.5px;">Linkan<span style="color: #ffd8b3;">.ID</span></h1>
                            <p style="margin: 6px 0 0 0; color: rgba(255, 255, 255, 0.9); font-size: 13px; font-weight: 500;">Pusat Informasi & Pengumuman Resmi</p>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 36px 32px 24px 32px;">
                            @php
                                $badgeStyle = match($announcement->type) {
                                    'warning' => 'background-color: #fef3c7; color: #b45309; border: 1px solid #fde68a;',
                                    'danger'  => 'background-color: #fef2f2; color: #b91c1c; border: 1px solid #fecaca;',
                                    'success' => 'background-color: #ecfdf5; color: #047857; border: 1px solid #a7f3d0;',
                                    default   => 'background-color: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe;',
                                };
                                $typeLabel = match($announcement->type) {
                                    'warning' => '⚠️ PENTING',
                                    'danger'  => '🚨 MENDESAK',
                                    'success' => '✨ UPDATE SISTEM',
                                    default   => '📢 PENGUMUMAN',
                                };
                            @endphp

                            <div style="display: inline-block; {{ $badgeStyle }} font-size: 12px; font-weight: 700; padding: 4px 14px; border-radius: 20px; margin-bottom: 16px;">
                                {{ $typeLabel }}
                            </div>

                            <h2 style="margin: 0 0 12px 0; color: #0f172a; font-size: 22px; font-weight: 800; line-height: 1.3;">
                                {{ $announcement->title }}
                            </h2>

                            <p style="margin: 0 0 20px 0; color: #475569; font-size: 14px; line-height: 1.6;">
                                Halo <strong>{{ $recipient->name }}</strong>,<br>
                                Kami ingin menyampaikan informasi penting terkait operasional dan pembaruan pada platform <strong>Linkan.ID</strong>:
                            </p>

                            <!-- Message Content Box -->
                            <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 22px; margin-bottom: 24px; font-size: 14px; color: #1e293b; line-height: 1.7;">
                                {!! nl2br(e($announcement->message)) !!}
                            </div>

                            <div style="font-size: 12px; color: #94a3b8; margin-bottom: 24px;">
                                Disiarkan pada: <strong>{{ date('d M Y, H:i') }} WIB</strong> oleh Tim Manajemen Linkan.ID.
                            </div>

                            <!-- CTA Button -->
                            <div style="text-align: center; margin: 28px 0 12px 0;">
                                <a href="{{ route('admin.dashboard') }}" style="display: inline-block; background: linear-gradient(135deg, #DE6C20 0%, #c45712 100%); color: #ffffff; text-decoration: none; font-size: 14px; font-weight: 700; padding: 12px 28px; border-radius: 10px; box-shadow: 0 4px 12px rgba(222, 108, 32, 0.25);">
                                    Masuk ke Dashboard Seller
                                </a>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8fafc; padding: 20px 32px; border-top: 1px solid #e2e8f0; text-align: center;">
                            <p style="margin: 0; color: #94a3b8; font-size: 12px; line-height: 1.5;">
                                &copy; {{ date('Y') }} <strong>Linkan.ID</strong>. Seluruh hak cipta dilindungi.<br>
                                Anda menerima email ini karena terdaftar sebagai seller aktif di Linkan.ID.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
