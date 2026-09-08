<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\ActivityLog;

use Illuminate\Support\Facades\Schedule;
use App\Services\ActivityLogger;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('activity-logs:prune {days=30}', function (int $days = 30) {
    $days = max(1, $days);
    $cutoff = now()->subDays($days);
    $deleted = ActivityLog::where('created_at', '<', $cutoff)->delete();

    $message = "Auto-cleanup berhasil menghapus {$deleted} baris log aktivitas yang berusia lebih dari {$days} hari.";
    $this->info($message);

    if ($deleted > 0 && class_exists(ActivityLogger::class)) {
        ActivityLogger::log('auto_cleanup_logs', $message, [
            'deleted_count' => $deleted,
            'retention_days' => $days,
            'cutoff_date' => $cutoff->toDateTimeString(),
        ]);
    }
})->purpose('Prune old activity logs while retaining the configured retention period');

// Jadwal Pembersihan Otomatis Setiap Hari Pukul 02:00 WIB (Retensi 30 Hari)
Schedule::command('activity-logs:prune 30')->dailyAt('02:00');
