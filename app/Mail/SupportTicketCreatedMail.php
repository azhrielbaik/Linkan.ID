<?php

namespace App\Mail;

use App\Models\SupportTicket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SupportTicketCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public SupportTicket $ticket;

    public function __construct(SupportTicket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function build()
    {
        return $this->subject('[Linkan.ID] Tiket Bantuan Diterima: ' . $this->ticket->ticket_code . ' - ' . $this->ticket->subject)
                    ->view('emails.support-ticket-created')
                    ->with([
                        'ticket' => $this->ticket,
                    ]);
    }
}
