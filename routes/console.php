<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\ActivityLog;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('activity-logs:prune {days=365}', function (int $days) {
    $days = max(30, $days);
    $cutoff = now()->subDays($days);
    $deleted = ActivityLog::where('created_at', '<', $cutoff)->delete();

    $this->info("Deleted {$deleted} activity logs older than {$days} days.");
})->purpose('Prune old activity logs while retaining the configured retention period');
