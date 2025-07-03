<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
class ContactMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        return $this->from('dhefajar0410@gmail.com', $this->data['name']) // Nama pengirim = dari form
                    ->replyTo($this->data['email'], $this->data['name']) // Agar penerima bisa balas ke email user
                    ->subject('New Contact Message')
                    ->view('emails.contact')
                    ->with('data', $this->data);
    }
}