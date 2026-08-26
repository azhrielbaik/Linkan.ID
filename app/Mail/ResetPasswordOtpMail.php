<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $otpCode;
    public string $userName;
    public int $expiresInMinutes;

    /**
     * Create a new message instance.
     */
    public function __construct(string $otpCode, string $userName = 'Pengguna Linkan', int $expiresInMinutes = 15)
    {
        $this->otpCode = $otpCode;
        $this->userName = $userName;
        $this->expiresInMinutes = $expiresInMinutes;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('[Linkan.ID] ' . $this->otpCode . ' adalah Kode Verifikasi Reset Password Anda')
                    ->view('emails.reset-password-otp')
                    ->with([
                        'otpCode'          => $this->otpCode,
                        'userName'         => $this->userName,
                        'expiresInMinutes' => $this->expiresInMinutes,
                    ]);
    }
}
