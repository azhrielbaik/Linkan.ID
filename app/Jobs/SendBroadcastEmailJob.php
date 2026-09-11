<?php

namespace App\Jobs;

use App\Mail\BroadcastAnnouncementMail;
use App\Models\BroadcastAnnouncement;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendBroadcastEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Jumlah maksimal percobaan ulang jika gagal.
     */
    public int $tries = 3;

    /**
     * Waktu tunggu antar percobaan ulang (detik).
     */
    public array $backoff = [30, 120, 300];

    /**
     * Batas waktu eksekusi per job (detik).
     */
    public int $timeout = 30;

    protected BroadcastAnnouncement $announcement;
    protected User $recipient;

    /**
     * Buat job baru untuk mengirim email broadcast ke satu recipient.
     */
    public function __construct(BroadcastAnnouncement $announcement, User $recipient)
    {
        $this->announcement = $announcement;
        $this->recipient = $recipient;
        $this->onQueue('broadcasts');
    }

    /**
     * Kirim email broadcast ke recipient.
     */
    public function handle(): void
    {
        Mail::to($this->recipient->email)
            ->send(new BroadcastAnnouncementMail($this->announcement, $this->recipient));

        // Increment counter email terkirim secara atomik
        BroadcastAnnouncement::where('id', $this->announcement->id)
            ->increment('emails_sent_count');
    }

    /**
     * Catat kegagalan setelah semua percobaan habis.
     */
    public function failed(\Throwable $exception): void
    {
        \Log::error("Broadcast email gagal dikirim ke {$this->recipient->email}: " . $exception->getMessage(), [
            'announcement_id' => $this->announcement->id,
            'recipient_id'    => $this->recipient->id,
            'recipient_email' => $this->recipient->email,
        ]);
    }
}
