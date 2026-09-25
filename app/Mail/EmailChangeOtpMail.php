<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailChangeOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $otp;

    public string $userName;

    public string $newEmail;

    public function __construct(string $otp, string $userName, string $newEmail)
    {
        $this->otp = $otp;
        $this->userName = $userName;
        $this->newEmail = $newEmail;
    }

    public function build(): self
    {
        return $this->subject('Kode OTP Verifikasi Ganti Email')
            ->view('emails.email_change_otp');
    }
}
