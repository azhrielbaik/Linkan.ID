<?php

namespace App\Services;

use App\Models\User;
use App\Services\PlatformAdmin\CommissionExportService;
use App\Services\PlatformAdmin\PlatformDashboardService;
use App\Services\PlatformAdmin\PlatformNotificationService;
use App\Services\PlatformAdmin\UserSuspensionService;

class PlatformAdminService
{
    const ROLE_PLATFORM_ADMIN = 'admin_platform';

    public function __construct(
        protected ?PlatformDashboardService $dashboardService = null,
        protected ?PlatformNotificationService $notificationService = null,
        protected ?UserSuspensionService $suspensionService = null,
        protected ?CommissionExportService $commissionService = null
    ) {
        $this->dashboardService = $dashboardService ?? new PlatformDashboardService;
        $this->notificationService = $notificationService ?? new PlatformNotificationService;
        $this->suspensionService = $suspensionService ?? new UserSuspensionService($this->notificationService);
        $this->commissionService = $commissionService ?? new CommissionExportService;
    }

    /**
     * Get statistics and charts for dashboard
     */
    public function getDashboardStats(): array
    {
        return $this->dashboardService->getDashboardStats();
    }

    /**
     * Hapus cache data beranda platform.
     */
    public static function clearDashboardCache(): void
    {
        (new PlatformDashboardService)->clearDashboardCache();
    }

    /**
     * Ambil jumlah tiket yang open dengan caching (TTL: 60 detik).
     */
    public static function getPendingTicketsCount(): int
    {
        return (new PlatformNotificationService)->getPendingTicketsCount();
    }

    /**
     * Hapus cache data tiket platform saat status tiket berubah.
     */
    public static function clearPendingTicketsCache(): void
    {
        (new PlatformNotificationService)->clearPendingTicketsCache();
    }

    /**
     * Hapus cache data notifikasi platform saat ada pembaruan status data.
     */
    public static function clearNotificationsCache(): void
    {
        (new PlatformNotificationService)->clearNotificationsCache();
    }

    /**
     * Ambil data notifikasi mentah dari database (atau dari cache berdurasi 10 detik).
     */
    public function getCachedRawNotifications(): array
    {
        return $this->notificationService->getCachedRawNotifications();
    }

    /**
     * Fetch formatted notifications data with user read state
     */
    public function getNotificationsData(array $readKeys = []): array
    {
        return $this->notificationService->getNotificationsData($readKeys);
    }

    /**
     * Get commissions data for export or API
     */
    public function getCommissionsData()
    {
        return $this->commissionService->getCommissionsData();
    }

    /**
     * Get total platform earnings
     */
    public function getTotalEarnings(): float
    {
        return $this->commissionService->getTotalEarnings();
    }

    /**
     * Generate CSV content stream for Export
     */
    public function streamCommissionsCsv(callable $callback)
    {
        return $this->commissionService->streamCommissionsCsv($callback);
    }

    /**
     * Get users list with filters
     */
    public function getUsersList($search, $filter, $startDate, $endDate, $appealStatus)
    {
        return $this->suspensionService->getUsersList($search, $filter, $startDate, $endDate, $appealStatus);
    }

    /**
     * Get suggestions for user search
     */
    public function getSuggestions(string $q): array
    {
        return $this->suspensionService->getSuggestions($q);
    }

    /**
     * Suspend user logic
     */
    public function suspendUser(User $user, string $duration, ?string $reason): string
    {
        return $this->suspensionService->suspendUser($user, $duration, $reason);
    }

    /**
     * Activate user logic
     */
    public function activateUser(User $user, ?string $reason = null): void
    {
        $this->suspensionService->activateUser($user, $reason);
    }
}
