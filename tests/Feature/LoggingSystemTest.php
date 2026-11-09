<?php

namespace Tests\Feature;

use App\Http\Middleware\LogHttpRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class LoggingSystemTest extends TestCase
{
    /**
     * Test all logging channels are properly configured.
     */
    public function test_logging_channels_configuration(): void
    {
        $channels = config('logging.channels');

        // Verify stack channel
        $this->assertArrayHasKey('stack', $channels);
        $this->assertEquals(['daily', 'errors'], $channels['stack']['channels']);

        // Verify auth channel
        $this->assertArrayHasKey('auth', $channels);
        $this->assertEquals('daily', $channels['auth']['driver']);
        $this->assertStringContainsString('auth.log', $channels['auth']['path']);
        $this->assertEquals(30, $channels['auth']['days']);

        // Verify payment channel
        $this->assertArrayHasKey('payment', $channels);
        $this->assertEquals('daily', $channels['payment']['driver']);
        $this->assertStringContainsString('payment.log', $channels['payment']['path']);
        $this->assertEquals(90, $channels['payment']['days']);

        // Verify jobs channel
        $this->assertArrayHasKey('jobs', $channels);
        $this->assertEquals('daily', $channels['jobs']['driver']);
        $this->assertStringContainsString('jobs.log', $channels['jobs']['path']);
        $this->assertEquals(14, $channels['jobs']['days']);

        // Verify errors channel
        $this->assertArrayHasKey('errors', $channels);
        $this->assertEquals('daily', $channels['errors']['driver']);
        $this->assertStringContainsString('errors.log', $channels['errors']['path']);
        $this->assertEquals('error', $channels['errors']['level']);
        $this->assertEquals(60, $channels['errors']['days']);

        // Verify daily channel
        $this->assertArrayHasKey('daily', $channels);
        $this->assertStringContainsString('laravel.log', $channels['daily']['path']);
        $this->assertEquals(14, $channels['daily']['days']);
    }

    /**
     * Test LogHttpRequests middleware skips specified polling routes.
     */
    public function test_log_http_requests_skips_excluded_routes(): void
    {
        $middleware = new LogHttpRequests;

        foreach (['admin.notifications.stream', 'admin.notifications.index', 'admin.chart-data'] as $routeName) {
            $request = Request::create('/test-url', 'GET');
            $request->setRouteResolver(function () use ($routeName) {
                $route = new \stdClass;

                return new class($routeName)
                {
                    private $name;

                    public function __construct($name)
                    {
                        $this->name = $name;
                    }

                    public function getName()
                    {
                        return $this->name;
                    }
                };
            });

            $logged = false;
            Log::shouldReceive('channel')->never();

            $response = $middleware->handle($request, function () {
                return new Response('ok', 200);
            });

            $this->assertEquals(200, $response->getStatusCode());
        }
    }

    /**
     * Test LogHttpRequests middleware skips fast AJAX requests under 500ms.
     */
    public function test_log_http_requests_skips_fast_ajax(): void
    {
        $middleware = new LogHttpRequests;
        $request = Request::create('/ajax-url', 'GET', [], [], [], [
            'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest',
        ]);

        Log::shouldReceive('channel')->never();

        $response = $middleware->handle($request, function () {
            return new Response('ajax ok', 200);
        });

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * Test LogHttpRequests logs standard request to daily channel.
     */
    public function test_log_http_requests_logs_to_daily_channel(): void
    {
        $middleware = new LogHttpRequests;
        $request = Request::create('/public-page', 'GET');

        $channelMock = \Mockery::mock();
        $channelMock->shouldReceive('log')
            ->once()
            ->with('info', 'HTTP Request Processed', \Mockery::on(function ($context) {
                return $context['method'] === 'GET'
                    && str_contains($context['url'], '/public-page')
                    && $context['status'] === 200
                    && array_key_exists('duration_ms', $context);
            }));

        Log::shouldReceive('channel')
            ->with('daily')
            ->once()
            ->andReturn($channelMock);

        $response = $middleware->handle($request, function () {
            return new Response('page content', 200);
        });

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * Test LogHttpRequests assigns warning for 4xx and error for 5xx.
     */
    public function test_log_http_requests_status_levels(): void
    {
        $middleware = new LogHttpRequests;

        // 404 -> warning
        $request404 = Request::create('/not-found', 'GET');
        $channelMock404 = \Mockery::mock();
        $channelMock404->shouldReceive('log')
            ->once()
            ->with('warning', 'HTTP Request Processed', \Mockery::any());

        Log::shouldReceive('channel')->with('daily')->once()->andReturn($channelMock404);

        $middleware->handle($request404, function () {
            return new Response('not found', 404);
        });

        // 500 -> error
        $request500 = Request::create('/server-error', 'GET');
        $channelMock500 = \Mockery::mock();
        $channelMock500->shouldReceive('log')
            ->once()
            ->with('error', 'HTTP Request Processed', \Mockery::any());

        Log::shouldReceive('channel')->with('daily')->once()->andReturn($channelMock500);

        $middleware->handle($request500, function () {
            return new Response('server error', 500);
        });
    }

    /**
     * Test writing logs to all custom channels creates rotating daily log files in storage/logs.
     */
    public function test_custom_channels_write_to_storage_logs(): void
    {
        Log::channel('auth')->info('Auth test log entry', ['user_id' => 123]);
        Log::channel('payment')->info('Payment test log entry', ['order_id' => 'ORDER-TEST']);
        Log::channel('jobs')->info('Jobs test log entry', ['job' => 'TestJob']);
        Log::channel('errors')->error('Errors test log entry', ['error' => 'CriticalException']);
        Log::channel('daily')->info('Daily test log entry', ['status' => 'ok']);

        $date = date('Y-m-d');
        $this->assertFileExists(storage_path("logs/auth-{$date}.log"));
        $this->assertFileExists(storage_path("logs/payment-{$date}.log"));
        $this->assertFileExists(storage_path("logs/jobs-{$date}.log"));
        $this->assertFileExists(storage_path("logs/errors-{$date}.log"));
        $this->assertFileExists(storage_path("logs/laravel-{$date}.log"));

        // Verify contents contain structured context
        $authLog = file_get_contents(storage_path("logs/auth-{$date}.log"));
        $this->assertStringContainsString('Auth test log entry', $authLog);
        $this->assertStringContainsString('"user_id":123', $authLog);

        $paymentLog = file_get_contents(storage_path("logs/payment-{$date}.log"));
        $this->assertStringContainsString('Payment test log entry', $paymentLog);
        $this->assertStringContainsString('"order_id":"ORDER-TEST"', $paymentLog);
    }
}
