<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use App\Models\ShortlinkClick;

class ProcessClickLocation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $clickId;
    public $ip;

    public function __construct($clickId, $ip)
    {
        $this->clickId = $clickId;
        $this->ip = $ip;
    }

    public function handle()
    {
        if (empty($this->ip) || in_array($this->ip, ['127.0.0.1', '::1'])) {
            return;
        }

        try {
            $response = Http::timeout(5)->get("http://ip-api.com/json/{$this->ip}");
            if ($response->successful() && $response->json('status') === 'success') {
                ShortlinkClick::where('id', $this->clickId)->update([
                    'country' => $response->json('country'),
                    'city' => $response->json('city'),
                ]);
            }
        } catch (\Exception $e) {
            // Ignore
        }
    }
}
