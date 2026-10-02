<?php

namespace App\Jobs;

use App\Models\BroadcastAnnouncement;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DispatchBroadcastEmailsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 300;

    protected BroadcastAnnouncement $announcement;

    /**
     * Buat master job untuk mendistribusikan email broadcast secara asinkron dalam batch chunk.
     */
    public function __construct(BroadcastAnnouncement $announcement)
    {
        $this->announcement = $announcement;
        $this->onQueue('broadcasts');
    }

    /**
     * Proses chunking user seller dan dispatch job email individu ke queue.
     */
    public function handle(): void
    {
        Log::channel('jobs')->info('Dispatch broadcast emails job started', [
            'announcement_id' => $this->announcement->id,
        ]);

        try {
            $totalDispatched = 0;

            User::where('role', '!=', 'admin_platform')
                ->whereNotNull('email')
                ->select(['id', 'name', 'email'])
                ->chunkById(250, function ($sellers) use (&$totalDispatched) {
                    foreach ($sellers as $seller) {
                        SendBroadcastEmailJob::dispatch($this->announcement, $seller);
                        $totalDispatched++;
                    }
                });

            Log::channel('jobs')->info('Dispatch broadcast emails job completed', [
                'announcement_id' => $this->announcement->id,
                'total_dispatched' => $totalDispatched,
            ]);
        } catch (\Throwable $e) {
            Log::channel('jobs')->error('Dispatch broadcast emails job failed', [
                'announcement_id' => $this->announcement->id,
                'error_message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Penanganan jika master job gagal.
     */
    public function failed(\Throwable $exception): void
    {
        Log::channel('jobs')->error('Dispatch broadcast emails job failed permanently', [
            'announcement_id' => $this->announcement->id,
            'error_message' => $exception->getMessage(),
        ]);
    }
}
