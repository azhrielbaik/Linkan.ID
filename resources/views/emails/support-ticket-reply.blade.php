<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Balasan Tiket Bantuan - Linkan.ID</title>
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
                            <p style="margin: 6px 0 0 0; color: rgba(255, 255, 255, 0.9); font-size: 13px; font-weight: 500;">Pusat Bantuan & Layanan Dukungan Seller</p>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 36px 32px 24px 32px;">
                            <div style="display: inline-block; background-color: #ecfdf5; color: #059669; border: 1px solid #d1fae5; font-size: 12px; font-weight: 700; padding: 4px 12px; border-radius: 20px; margin-bottom: 16px;">
                                💬 Balasan Baru • {{ $ticket->ticket_code }}
                            </div>

                            <h2 style="margin: 0 0 12px 0; color: #0f172a; font-size: 20px; font-weight: 700;">Admin Membalas Tiket Bantuan Anda</h2>
                            <p style="margin: 0 0 20px 0; color: #475569; font-size: 14px; line-height: 1.6;">
                                Halo <strong>{{ $ticket->user->name ?? 'Seller' }}</strong>,<br>
                                Tim Support Platform Admin <strong>Linkan.ID</strong> telah memberikan balasan terkait tiket bantuan Anda (<strong>{{ $ticket->subject }}</strong>).
                            </p>

                            <!-- Reply Box Display -->
                            <div style="background-color: #f0fdf4; border: 1.5px solid #86efac; border-radius: 12px; padding: 20px; margin-bottom: 24px;">
                                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 10px;">
                                    <strong style="color: #166534; font-size: 13px;">🛡️ Balasan Resmi Tim Admin:</strong>
                                </div>
                                <div style="font-size: 14px; color: #1e293b; line-height: 1.7;">
                                    {!! nl2br(e($reply->message)) !!}
                                </div>
                            </div>

                            <!-- Ticket Status Box -->
                            <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px 18px; margin-bottom: 24px; font-size: 13px;">
                                <table width="100%">
                                    <tr>
                                        <td width="35%" style="color: #64748b; font-weight: 600;">Status Tiket Saat Ini</td>
                                        <td style="font-weight: 700; color: #0f172a;">{{ $ticket->status_label }}</td>
                                    </tr>
                                </table>
                            </div>

                            <p style="margin: 0; color: #64748b; font-size: 13px; line-height: 1.5;">
                                Jika masih ada hal yang ingin ditanyakan, Anda dapat membalas langsung melalui halaman Pusat Bantuan di dashboard seller Anda.
                            </p>

                            <!-- CTA Button -->
                            <div style="text-align: center; margin: 28px 0 12px 0;">
                                <a href="{{ route('admin.tickets.show', $ticket->id) }}" style="display: inline-block; background: linear-gradient(135deg, #DE6C20 0%, #c45712 100%); color: #ffffff; text-decoration: none; font-size: 14px; font-weight: 700; padding: 12px 28px; border-radius: 10px; box-shadow: 0 4px 12px rgba(222, 108, 32, 0.25);">
                                    Buka Tiket & Balas di Dashboard
                                </a>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8fafc; padding: 20px 32px; border-top: 1px solid #e2e8f0; text-align: center;">
                            <p style="margin: 0; color: #94a3b8; font-size: 12px; line-height: 1.5;">
                                &copy; {{ date('Y') }} <strong>Linkan.ID</strong>. Seluruh hak cipta dilindungi.<br>
                                Notifikasi resmi Pusat Bantuan Linkan.ID.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
