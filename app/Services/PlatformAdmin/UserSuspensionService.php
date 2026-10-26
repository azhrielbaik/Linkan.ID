<?php

namespace App\Services\PlatformAdmin;

use App\Models\SuspensionAppeal;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Support\Facades\DB;

class UserSuspensionService
{
    const ROLE_PLATFORM_ADMIN = 'admin_platform';

    public function __construct(
        protected ?PlatformNotificationService $notificationService = null
    ) {
        $this->notificationService = $notificationService ?? new PlatformNotificationService;
    }

    /**
     * Get users list with filters
     */
    public function getUsersList($search, $filter, $startDate, $endDate, $appealStatus)
    {
        $query = User::with(['suspensionAppeals' => function ($q) {
            $q->where('status', 'pending')->latest()->limit(1);
        }])->where('role', '!=', self::ROLE_PLATFORM_ADMIN);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($filter === 'active') {
            $query->where(function ($q) {
                $q->whereNull('suspended_at')
                    ->orWhere(function ($sub) {
                        $sub->whereNotNull('suspended_until')->where('suspended_until', '<=', now());
                    });
            });
        } elseif ($filter === 'suspended') {
            $query->whereNotNull('suspended_at')->where(function ($q) {
                $q->whereNull('suspended_until')
                    ->orWhere('suspended_until', '>', now());
            });
        }

        if ($startDate && $endDate) {
            $query->whereDate('created_at', '>=', $startDate)
                ->whereDate('created_at', '<=', $endDate);
        } elseif ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        $totalUsers = User::where('role', '!=', self::ROLE_PLATFORM_ADMIN)->count();
        $totalActive = User::where('role', '!=', self::ROLE_PLATFORM_ADMIN)->where(function ($q) {
            $q->whereNull('suspended_at')
                ->orWhere(function ($sub) {
                    $sub->whereNotNull('suspended_until')->where('suspended_until', '<=', now());
                });
        })->count();
        $totalSuspended = User::where('role', '!=', self::ROLE_PLATFORM_ADMIN)->whereNotNull('suspended_at')->where(function ($q) {
            $q->whereNull('suspended_until')
                ->orWhere('suspended_until', '>', now());
        })->count();

        // Appeals
        $appealsQuery = SuspensionAppeal::with('user')->latest();
        if ($appealStatus) {
            $appealsQuery->where('status', $appealStatus);
        }
        $appeals = $appealsQuery->paginate(15, ['*'], 'appeals_page')->withQueryString();
        $pendingAppealsCount = SuspensionAppeal::where('status', 'pending')->count();

        return compact('users', 'totalUsers', 'totalActive', 'totalSuspended', 'appeals', 'pendingAppealsCount');
    }

    /**
     * Get suggestions for user search
     */
    public function getSuggestions(string $q): array
    {
        $users = User::where('role', '!=', self::ROLE_PLATFORM_ADMIN)
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            })
            ->limit(10)
            ->get();

        $suggestions = collect();
        foreach ($users as $user) {
            if ($user->name && stripos($user->name, $q) !== false) {
                $suggestions->push(['label' => $user->name.' (Nama)', 'value' => $user->name]);
            }
            if ($user->email && stripos($user->email, $q) !== false) {
                $suggestions->push(['label' => $user->email.' (Email)', 'value' => $user->email]);
            }
        }

        return $suggestions->unique('value')->values()->take(5)->toArray();
    }

    /**
     * Suspend user logic
     */
    public function suspendUser(User $user, string $duration, ?string $reason): string
    {
        $suspendedUntil = null;
        $durationLabel = 'Permanen';

        switch ($duration) {
            case '1_day':
                $suspendedUntil = now()->addDay();
                $durationLabel = '1 Hari (s.d '.$suspendedUntil->format('d M Y, H:i').')';
                break;
            case '3_days':
                $suspendedUntil = now()->addDays(3);
                $durationLabel = '3 Hari (s.d '.$suspendedUntil->format('d M Y, H:i').')';
                break;
            case '7_days':
                $suspendedUntil = now()->addDays(7);
                $durationLabel = '7 Hari / 1 Minggu (s.d '.$suspendedUntil->format('d M Y, H:i').')';
                break;
            case '30_days':
                $suspendedUntil = now()->addDays(30);
                $durationLabel = '30 Hari / 1 Bulan (s.d '.$suspendedUntil->format('d M Y, H:i').')';
                break;
            case 'permanent':
            default:
                break;
        }

        DB::transaction(function () use ($user, $duration, $reason, $durationLabel, $suspendedUntil) {
            $user->update([
                'suspended_at' => now(),
                'suspended_until' => $suspendedUntil,
                'suspend_reason' => $reason,
            ]);

            ActivityLogger::log(
                'suspend_user',
                "Men-suspend akun user: {$user->name} ({$user->email}) - Durasi: {$durationLabel}. Alasan: {$reason}",
                [
                    'target_user_id' => $user->id,
                    'user_name' => $user->name,
                    'user_email' => $user->email,
                    'duration' => $duration,
                    'suspended_until' => $suspendedUntil ? $suspendedUntil->toDateTimeString() : null,
                    'reason' => $reason,
                ]
            );
        });

        $this->notificationService->clearNotificationsCache();

        return $durationLabel;
    }

    /**
     * Activate user logic
     */
    public function activateUser(User $user, ?string $reason = null): void
    {
        DB::transaction(function () use ($user, $reason) {
            $user->update([
                'suspended_at' => null,
                'suspended_until' => null,
                'suspend_reason' => null,
            ]);

            SuspensionAppeal::where('user_id', $user->id)
                ->where('status', 'pending')
                ->update([
                    'status' => 'approved',
                    'admin_notes' => $reason ?: 'Akun dipulihkan secara manual oleh Admin Platform.',
                    'resolved_at' => now(),
                ]);

            $logDesc = "Mengaktifkan kembali akun user: {$user->name} ({$user->email})";
            if ($reason) {
                $logDesc .= ". Catatan: {$reason}";
            }

            ActivityLogger::log(
                'activate_user',
                $logDesc,
                [
                    'target_user_id' => $user->id,
                    'user_name' => $user->name,
                    'user_email' => $user->email,
                    'reason' => $reason,
                ]
            );
        });

        $this->notificationService->clearNotificationsCache();
    }
}
