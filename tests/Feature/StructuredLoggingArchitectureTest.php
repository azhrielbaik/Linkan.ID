<?php

namespace Tests\Feature;

use App\Http\Middleware\AssignRequestId;
use App\Jobs\ProcessAppearanceBlocks;
use App\Logging\SanitizeSensitiveDataProcessor;
use App\Logging\StructuredJsonFormatter;
use App\Models\User;
use DateTimeImmutable;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Monolog\Level;
use Monolog\LogRecord;
use Tests\TestCase;

class StructuredLoggingArchitectureTest extends TestCase
{
    /**
     * Test 1: Header X-Request-ID is automatically generated and attached to response.
     */
    public function test_assign_request_id_middleware_attaches_header(): void
    {
        $middleware = new AssignRequestId();
        $request = Request::create('/test-endpoint', 'GET');

        $response = $middleware->handle($request, function ($req) {
            $this->assertTrue($req->attributes->has('request_id'));
            $this->assertNotEmpty($req->attributes->get('request_id'));

            return new Response('content', 200);
        });

        $this->assertTrue($response->headers->has('X-Request-ID'));
        $requestId = $response->headers->get('X-Request-ID');
        $this->assertMatchesRegularExpression('/^[a-f0-9\-]{36}$/i', $requestId);
    }

    /**
     * Test 2: Inbound valid X-Request-ID header is preserved.
     */
    public function test_inbound_request_id_header_is_preserved(): void
    {
        $middleware = new AssignRequestId();
        $customId = 'REQ-TRACE-12345678-ABC';
        $request = Request::create('/test-endpoint', 'GET', [], [], [], [
            'HTTP_X_REQUEST_ID' => $customId,
        ]);

        $response = $middleware->handle($request, function ($req) use ($customId) {
            $this->assertEquals($customId, $req->attributes->get('request_id'));

            return new Response('ok', 200);
        });

        $this->assertEquals($customId, $response->headers->get('X-Request-ID'));
    }

    /**
     * Test 3: Inbound invalid or overly long X-Request-ID header is sanitized to UUID.
     */
    public function test_malformed_inbound_request_id_is_replaced_with_uuid(): void
    {
        $middleware = new AssignRequestId();
        $maliciousId = '<script>alert(1)</script>' . str_repeat('X', 100);
        $request = Request::create('/test-endpoint', 'GET', [], [], [], [
            'HTTP_X_REQUEST_ID' => $maliciousId,
        ]);

        $response = $middleware->handle($request, function ($req) {
            return new Response('ok', 200);
        });

        $assignedId = $response->headers->get('X-Request-ID');
        $this->assertNotEquals($maliciousId, $assignedId);
        $this->assertMatchesRegularExpression('/^[a-f0-9\-]{36}$/i', $assignedId);
    }

    /**
     * Test 4: SanitizeSensitiveDataProcessor masks sensitive parameters recursively.
     */
    public function test_sensitive_data_processor_masks_sensitive_keys(): void
    {
        $processor = new SanitizeSensitiveDataProcessor();

        $record = new LogRecord(
            new DateTimeImmutable(),
            'application',
            Level::Info,
            'user_profile_updated',
            [
                'username' => 'john_doe',
                'password' => 'secret_password_123',
                'password_confirmation' => 'secret_password_123',
                'token' => 'jwt.token.here',
                'server_key' => 'midtrans-server-key-xyz',
                'card_number' => '4111111111111234',
                'cvv' => '123',
                'authorization' => 'Bearer eyJhbGciOi...',
                'cookie' => 'laravel_session=abc123xyz',
                'nested' => [
                    'client_secret' => 'super_secret',
                    'safe_field' => 'visible_value',
                ],
            ]
        );

        $processed = $processor($record);
        $context = $processed->context;

        $this->assertEquals('john_doe', $context['username']);
        $this->assertEquals('[REDACTED]', $context['password']);
        $this->assertEquals('[REDACTED]', $context['password_confirmation']);
        $this->assertEquals('[REDACTED]', $context['token']);
        $this->assertEquals('[REDACTED]', $context['server_key']);
        $this->assertEquals('[REDACTED]', $context['card_number']);
        $this->assertEquals('[REDACTED]', $context['cvv']);
        $this->assertEquals('[REDACTED]', $context['authorization']);
        $this->assertEquals('[REDACTED]', $context['cookie']);
        $this->assertEquals('[REDACTED]', $context['nested']['client_secret']);
        $this->assertEquals('visible_value', $context['nested']['safe_field']);
    }

    /**
     * Test 5: StructuredJsonFormatter produces standardized single-line JSON format.
     */
    public function test_structured_json_formatter_produces_required_fields(): void
    {
        $formatter = new StructuredJsonFormatter();

        $record = new LogRecord(
            new DateTimeImmutable(),
            'payment',
            Level::Info,
            'payment_charge_initiated',
            [
                'event' => 'payment_charge_initiated',
                'request_id' => 'REQ-TEST-UUID',
                'environment' => 'testing',
                'user_id' => 42,
                'ip_address' => '192.168.1.10',
                'duration_ms' => 150.25,
                'order_id' => 'ORDER-1001',
                'amount' => 50000,
                'password' => 'leak_attempt',
            ]
        );

        $formatted = $formatter->format($record);
        $this->assertStringEndsWith("\n", $formatted);

        $parsed = json_decode($formatted, true);
        $this->assertIsArray($parsed);

        // Required standard fields
        $this->assertArrayHasKey('timestamp', $parsed);
        $this->assertArrayHasKey('level', $parsed);
        $this->assertArrayHasKey('event', $parsed);
        $this->assertArrayHasKey('request_id', $parsed);
        $this->assertArrayHasKey('environment', $parsed);
        $this->assertArrayHasKey('user_id', $parsed);
        $this->assertArrayHasKey('ip_address', $parsed);
        $this->assertArrayHasKey('duration_ms', $parsed);
        $this->assertArrayHasKey('context', $parsed);

        // Verify values
        $this->assertEquals('info', $parsed['level']);
        $this->assertEquals('payment_charge_initiated', $parsed['event']);
        $this->assertEquals('REQ-TEST-UUID', $parsed['request_id']);
        $this->assertEquals('testing', $parsed['environment']);
        $this->assertEquals(42, $parsed['user_id']);
        $this->assertEquals('192.168.1.10', $parsed['ip_address']);
        $this->assertEquals(150.25, $parsed['duration_ms']);
        $this->assertEquals('ORDER-1001', $parsed['context']['order_id']);
        $this->assertEquals(50000, $parsed['context']['amount']);
        $this->assertEquals('[REDACTED]', $parsed['context']['password']);
    }

    /**
     * Test 6: Auth events write structured audit records to the audit channel.
     */
    public function test_auth_events_emit_to_audit_channel(): void
    {
        $mockUser = new User();
        $mockUser->id = 77;
        $mockUser->email = 'audit_tester@example.com';
        $mockUser->role = 'admin_seller';

        // Dispatch login event
        event(new Login('web', $mockUser, false));

        // Dispatch failed login event
        event(new Failed('web', null, ['email' => 'hacker@example.com', 'password' => 'secret']));

        // Dispatch logout event
        event(new Logout('web', $mockUser));

        $date = date('Y-m-d');
        $auditLogPath = storage_path("logs/audit-{$date}.log");
        $this->assertFileExists($auditLogPath);

        $content = file_get_contents($auditLogPath);
        $this->assertStringContainsString('auth_login_successful', $content);
        $this->assertStringContainsString('audit_tester@example.com', $content);
        $this->assertStringContainsString('auth_login_failed', $content);
        $this->assertStringContainsString('hacker@example.com', $content);
        $this->assertStringNotContainsString('"password":"secret"', $content);
        $this->assertStringContainsString('auth_logout', $content);
    }

    /**
     * Test 7: Payment channel writes valid structured JSON to disk.
     */
    public function test_payment_channel_writes_structured_log(): void
    {
        Log::channel('payment')->info('midtrans_webhook_received', [
            'event' => 'midtrans_webhook_received',
            'order_id' => 'ORDER-VERIFY-99',
            'transaction_status' => 'settlement',
            'gross_amount' => 75000,
            'card_number' => '4111222233334444',
        ]);

        $date = date('Y-m-d');
        $paymentLogPath = storage_path("logs/payment-{$date}.log");
        $this->assertFileExists($paymentLogPath);

        $lines = file($paymentLogPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $lastLine = end($lines);

        $json = json_decode($lastLine, true);
        $this->assertIsArray($json);
        $this->assertEquals('info', $json['level']);
        $this->assertEquals('midtrans_webhook_received', $json['event']);
        $this->assertEquals('ORDER-VERIFY-99', $json['context']['order_id']);
        $this->assertEquals('settlement', $json['context']['transaction_status']);
        $this->assertEquals('[REDACTED]', $json['context']['card_number']);
    }

    /**
     * Test 8: Jobs channel logs structured events and correlation ID.
     */
    public function test_jobs_channel_writes_structured_log(): void
    {
        Log::channel('jobs')->info('job_processing_completed', [
            'event' => 'job_processing_completed',
            'job' => 'App\\Jobs\\SendOrderNotification',
            'job_id' => 'queue-job-1234',
            'queue' => 'default',
            'attempts' => 1,
        ]);

        $date = date('Y-m-d');
        $jobsLogPath = storage_path("logs/jobs-{$date}.log");
        $this->assertFileExists($jobsLogPath);

        $lines = file($jobsLogPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $lastLine = end($lines);

        $json = json_decode($lastLine, true);
        $this->assertIsArray($json);
        $this->assertEquals('info', $json['level']);
        $this->assertEquals('job_processing_completed', $json['event']);
        $this->assertEquals('queue-job-1234', $json['context']['job_id']);
    }

    /**
     * Test 9: End-to-end HTTP request includes X-Request-ID and logs to daily channel with correlation ID.
     */
    public function test_http_request_end_to_end_has_x_request_id_and_structured_log(): void
    {
        $response = $this->get('/id/login');
        $response->assertStatus(200);
        $this->assertTrue($response->headers->has('X-Request-ID'));
        $requestId = $response->headers->get('X-Request-ID');
        $this->assertNotEmpty($requestId);

        $date = date('Y-m-d');
        $dailyLogPath = storage_path("logs/laravel-{$date}.log");
        $this->assertFileExists($dailyLogPath);

        $lines = file($dailyLogPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $lastLine = end($lines);

        $json = json_decode($lastLine, true);
        $this->assertIsArray($json);
        $this->assertArrayHasKey('request_id', $json);
    }
}

