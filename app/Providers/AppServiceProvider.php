<?php

namespace App\Providers;

use App\Listeners\AuthAuditSubscriber;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    // use Midtrans\Config;
    public function boot(): void
    {
        if ($this->app->environment('production') || request()->header('X-Forwarded-Proto') === 'https' || request()->server('HTTP_X_FORWARDED_PROTO') === 'https') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        if ($this->app->runningInConsole()) {
            \Illuminate\Support\Facades\URL::defaults(['locale' => 'id']);
        }

        \Midtrans\Config::$serverKey = env('SB-Mid-server-qbA7U8pOrHFCGy-0LlFclqIG');
        \Midtrans\Config::$isProduction = false; // true untuk production
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        \Illuminate\Pagination\Paginator::defaultView('platformadmin.partials.pagination');
        \Illuminate\Pagination\Paginator::defaultSimpleView('platformadmin.partials.pagination');

        \Illuminate\Pagination\CursorPaginator::currentCursorResolver(function ($cursorName = 'cursor', $default = null) {
            $raw = request()->input($cursorName, $default);

            return \App\Support\Pagination\EncryptedCursorPaginator::decryptCursor($raw);
        });

        $this->app->bind(
            \Illuminate\Pagination\CursorPaginator::class,
            \App\Support\Pagination\EncryptedCursorPaginator::class
        );

        \Illuminate\Support\Facades\View::composer(
            'admin_seller.layouts.app',
            \App\View\Composers\SellerLayoutComposer::class
        );

        // Register Authentication & Security Audit Subscriber
        Event::subscribe(AuthAuditSubscriber::class);

        // Propagate Request ID correlation into Queue payloads
        Queue::createPayloadUsing(function () {
            $shared = method_exists(Log::class, 'sharedContext')
                ? Log::sharedContext()
                : [];

            $requestId = $shared['request_id']
                ?? (app()->bound('request') && request() ? (request()->header('X-Request-ID') ?? request()->attributes->get('request_id')) : null);

            return $requestId ? ['request_id' => $requestId] : [];
        });

        // Queue Job Lifecycle Structured Telemetry
        Queue::before(function (\Illuminate\Queue\Events\JobProcessing $event) {
            try {
                $payload = $event->job->payload();
                $requestId = $payload['request_id'] ?? (string) Str::uuid();

                if (method_exists(Log::class, 'shareContext')) {
                    Log::shareContext([
                        'request_id' => $requestId,
                        'entry_point' => 'queue_worker',
                        'job_name' => $event->job->resolveName(),
                        'queue' => $event->job->getQueue(),
                        'connection' => $event->connectionName,
                    ]);
                }

                Log::channel('jobs')->info('job_processing_started', [
                    'event' => 'job_processing_started',
                    'job' => $event->job->resolveName(),
                    'job_id' => $event->job->getJobId(),
                    'queue' => $event->job->getQueue(),
                    'connection' => $event->connectionName,
                    'attempts' => $event->job->attempts(),
                ]);
            } catch (\Throwable) {
                // Fail-safe
            }
        });

        Queue::after(function (\Illuminate\Queue\Events\JobProcessed $event) {
            try {
                Log::channel('jobs')->info('job_processing_completed', [
                    'event' => 'job_processing_completed',
                    'job' => $event->job->resolveName(),
                    'job_id' => $event->job->getJobId(),
                    'queue' => $event->job->getQueue(),
                    'connection' => $event->connectionName,
                    'attempts' => $event->job->attempts(),
                ]);
            } catch (\Throwable) {
                // Fail-safe
            }
        });

        Queue::failing(function (\Illuminate\Queue\Events\JobFailed $event) {
            try {
                Log::channel('jobs')->error('job_processing_failed', [
                    'event' => 'job_processing_failed',
                    'job' => $event->job->resolveName(),
                    'job_id' => $event->job->getJobId(),
                    'queue' => $event->job->getQueue(),
                    'connection' => $event->connectionName,
                    'exception_class' => get_class($event->exception),
                    'exception_message' => $event->exception->getMessage(),
                ]);
            } catch (\Throwable) {
                // Fail-safe
            }
        });

        // Slow query listener for local, staging, and production (>500ms)
        \Illuminate\Support\Facades\DB::listen(function ($query) {
            $threshold = (float) config('logging.slow_query_threshold_ms', 500);
            if ($query->time > $threshold) {
                Log::channel('daily')->warning('Slow query detected', [
                    'event' => 'database_slow_query',
                    'query' => $query->sql,
                    'bindings' => $query->bindings,
                    'duration_ms' => $query->time,
                    'connection' => $query->connectionName,
                    'url' => app()->runningInConsole() ? 'console' : request()->fullUrl(),
                ]);
            }
        });
    }
}
