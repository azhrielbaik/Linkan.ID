<?php

namespace App\Mail;

use App\Models\SupportTicket;
use App\Models\SupportTicketReply;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SupportTicketReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public SupportTicket $ticket;
    public SupportTicketReply $reply;

    public function __construct(SupportTicket $ticket, SupportTicketReply $reply)
    {
        $this->ticket = $ticket;
        $this->reply = $reply;
    }

    public function build()
    {
        return $this->subject('[Linkan.ID] Balasan Baru untuk Tiket ' . $this->ticket->ticket_code . ': ' . $this->ticket->subject)
                    ->view('emails.support-ticket-reply')
                    ->with([
                        'ticket' => $this->ticket,
                        'reply'  => $this->reply,
                    ]);
    }
}
