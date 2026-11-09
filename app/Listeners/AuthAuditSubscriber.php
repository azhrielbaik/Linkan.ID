<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Log;

class AuthAuditSubscriber
{
    /**
     * Handle successful login event.
     */
    public function handleLogin(Login $event): void
    {
        try {
            $user = $event->user;

            Log::channel('audit')->info('auth_login_successful', [
                'event' => 'auth_login_successful',
                'user_id' => $user->id,
                'email' => $user->email,
                'role' => $user->role ?? null,
                'guard' => $event->guard,
                'remember' => $event->remember,
                'ip_address' => request()?->ip(),
                'user_agent' => request()?->userAgent(),
            ]);
        } catch (\Throwable) {
            // Fail-safe
        }
    }

    /**
     * Handle failed login attempt event.
     */
    public function handleFailed(Failed $event): void
    {
        try {
            Log::channel('audit')->warning('auth_login_failed', [
                'event' => 'auth_login_failed',
                'attempted_email' => $event->credentials['email'] ?? $event->credentials['username'] ?? null,
                'guard' => $event->guard,
                'user_id' => $event->user?->id,
                'ip_address' => request()?->ip(),
                'user_agent' => request()?->userAgent(),
            ]);
        } catch (\Throwable) {
            // Fail-safe
        }
    }

    /**
     * Handle logout event.
     */
    public function handleLogout(Logout $event): void
    {
        try {
            Log::channel('audit')->info('auth_logout', [
                'event' => 'auth_logout',
                'user_id' => $event->user?->id,
                'guard' => $event->guard,
                'ip_address' => request()?->ip(),
            ]);
        } catch (\Throwable) {
            // Fail-safe
        }
    }

    /**
     * Handle authentication rate-limit lockout event.
     */
    public function handleLockout(Lockout $event): void
    {
        try {
            Log::channel('audit')->warning('auth_login_locked_out', [
                'event' => 'auth_login_locked_out',
                'ip_address' => request()?->ip(),
                'user_agent' => request()?->userAgent(),
            ]);
        } catch (\Throwable) {
            // Fail-safe
        }
    }

    /**
     * Handle password reset event.
     */
    public function handlePasswordReset(PasswordReset $event): void
    {
        try {
            Log::channel('audit')->info('auth_password_reset_completed', [
                'event' => 'auth_password_reset_completed',
                'user_id' => $event->user->id,
                'email' => $event->user->email,
                'ip_address' => request()?->ip(),
            ]);
        } catch (\Throwable) {
            // Fail-safe
        }
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @return array<string, string>
     */
    public function subscribe(Dispatcher $events): array
    {
        return [
            Login::class => 'handleLogin',
            Failed::class => 'handleFailed',
            Logout::class => 'handleLogout',
            Lockout::class => 'handleLockout',
            PasswordReset::class => 'handlePasswordReset',
        ];
    }
}

