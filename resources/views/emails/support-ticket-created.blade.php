<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Bantuan Diterima - Linkan.ID</title>
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
                            <div style="display: inline-block; background-color: #fff7ed; color: #ea580c; border: 1px solid #ffedd5; font-size: 12px; font-weight: 700; padding: 4px 12px; border-radius: 20px; margin-bottom: 16px;">
                                🎫 {{ $ticket->ticket_code }}
                            </div>

                            <h2 style="margin: 0 0 12px 0; color: #0f172a; font-size: 20px; font-weight: 700;">Tiket Bantuan Anda Telah Diterima</h2>
                            <p style="margin: 0 0 20px 0; color: #475569; font-size: 14px; line-height: 1.6;">
                                Halo <strong>{{ $ticket->user->name ?? 'Seller' }}</strong>,<br>
                                Terima kasih telah menghubungi Pusat Bantuan <strong>Linkan.ID</strong>. Tiket bantuan Anda telah berhasil didaftarkan ke sistem kami dengan detail sebagai berikut:
                            </p>

                            <!-- Detail Box -->
                            <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px 20px; margin-bottom: 24px;">
                                <table width="100%" style="font-size: 13px; color: #334155; line-height: 1.8;">
                                    <tr>
                                        <td width="35%" style="color: #64748b; font-weight: 600;">Nomor Tiket</td>
                                        <td style="font-weight: 700; color: #DE6C20;">{{ $ticket->ticket_code }}</td>
                                    </tr>
                                    <tr>
                                        <td style="color: #64748b; font-weight: 600;">Kategori</td>
                                        <td style="font-weight: 600;">{{ $ticket->category_label }}</td>
                                    </tr>
                                    <tr>
                                        <td style="color: #64748b; font-weight: 600;">Subjek Kendala</td>
                                        <td style="font-weight: 700; color: #0f172a;">{{ $ticket->subject }}</td>
                                    </tr>
                                    <tr>
                                        <td style="color: #64748b; font-weight: 600;">Status</td>
                                        <td>
                                            <span style="display: inline-block; background-color: #fef3c7; color: #b45309; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 6px;">
                                                Menunggu Respon
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <div style="margin-bottom: 24px;">
                                <div style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 6px;">Pesan Anda:</div>
                                <div style="background-color: #ffffff; border-left: 3px solid #DE6C20; padding: 12px 16px; font-size: 13px; color: #475569; line-height: 1.6; background-color: #fffaf5;">
                                    {!! nl2br(e($ticket->message)) !!}
                                </div>
                            </div>

                            <p style="margin: 0 0 24px 0; color: #475569; font-size: 13px; line-height: 1.6;">
                                Tim support Platform Admin kami sedang meninjau keluhan Anda dan akan segera memberikan balasan melalui dashboard serta email ini.
                            </p>

                            <!-- CTA Button -->
                            <div style="text-align: center; margin: 28px 0 12px 0;">
                                <a href="{{ route('admin.tickets.show', $ticket->id) }}" style="display: inline-block; background: linear-gradient(135deg, #DE6C20 0%, #c45712 100%); color: #ffffff; text-decoration: none; font-size: 14px; font-weight: 700; padding: 12px 28px; border-radius: 10px; box-shadow: 0 4px 12px rgba(222, 108, 32, 0.25);">
                                    Lihat Status Tiket di Dashboard
                                </a>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8fafc; padding: 20px 32px; border-top: 1px solid #e2e8f0; text-align: center;">
                            <p style="margin: 0; color: #94a3b8; font-size: 12px; line-height: 1.5;">
                                &copy; {{ date('Y') }} <strong>Linkan.ID</strong>. Seluruh hak cipta dilindungi.<br>
                                Anda menerima email ini karena membuat tiket bantuan di Linkan.ID.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
