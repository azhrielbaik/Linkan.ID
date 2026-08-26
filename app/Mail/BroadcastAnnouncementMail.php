<?php

namespace App\Mail;

use App\Models\BroadcastAnnouncement;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BroadcastAnnouncementMail extends Mailable
{
    use Queueable, SerializesModels;

    public BroadcastAnnouncement $announcement;
    public User $recipient;

    public function __construct(BroadcastAnnouncement $announcement, User $recipient)
    {
        $this->announcement = $announcement;
        $this->recipient = $recipient;
    }

    public function build()
    {
        $badgePrefix = match ($this->announcement->type) {
            'warning' => '⚠️ [PENTING]',
            'danger'  => '🚨 [MENDESAK]',
            'success' => '✨ [INFO UPDATE]',
            default   => '📢 [PENGUMUMAN]',
        };

        return $this->subject($badgePrefix . ' ' . $this->announcement->title . ' — Linkan.ID')
                    ->view('emails.broadcast-announcement')
                    ->with([
                        'announcement' => $this->announcement,
                        'recipient'    => $this->recipient,
                    ]);
    }
}
