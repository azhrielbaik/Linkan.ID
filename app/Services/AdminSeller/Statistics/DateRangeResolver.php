<?php

namespace App\Services\AdminSeller\Statistics;

use Carbon\Carbon;

class DateRangeResolver
{
    /**
     * Resolve date range and previous comparison period based on preset or custom input.
     *
     * @return array [Carbon $currentStart, Carbon $currentEnd, Carbon $prevStart, Carbon $prevEnd, string $preset]
     */
    public function resolveDateRange(?string $preset = '30days', ?string $startDate = null, ?string $endDate = null): array
    {
        $preset = $preset ?: '30days';

        switch ($preset) {
            case 'today':
                $currentStart = Carbon::today()->startOfDay();
                $currentEnd = Carbon::today()->endOfDay();
                $prevStart = Carbon::yesterday()->startOfDay();
                $prevEnd = Carbon::yesterday()->endOfDay();
                break;

            case 'yesterday':
                $currentStart = Carbon::yesterday()->startOfDay();
                $currentEnd = Carbon::yesterday()->endOfDay();
                $prevStart = Carbon::yesterday()->subDay()->startOfDay();
                $prevEnd = Carbon::yesterday()->subDay()->endOfDay();
                break;

            case 'this_week':
                $currentStart = Carbon::now()->startOfWeek();
                $currentEnd = Carbon::now()->endOfDay();
                $prevStart = Carbon::now()->subWeek()->startOfWeek();
                $prevEnd = Carbon::now()->subWeek()->endOfWeek();
                break;

            case 'last_week':
                $currentStart = Carbon::now()->subWeek()->startOfWeek();
                $currentEnd = Carbon::now()->subWeek()->endOfWeek();
                $prevStart = Carbon::now()->subWeeks(2)->startOfWeek();
                $prevEnd = Carbon::now()->subWeeks(2)->endOfWeek();
                break;

            case '7days':
                $currentStart = Carbon::now()->subDays(6)->startOfDay();
                $currentEnd = Carbon::now()->endOfDay();
                $prevStart = Carbon::now()->subDays(13)->startOfDay();
                $prevEnd = Carbon::now()->subDays(7)->endOfDay();
                break;

            case 'month':
                $currentStart = Carbon::now()->startOfMonth();
                $currentEnd = Carbon::now()->endOfDay();
                $prevStart = Carbon::now()->subMonth()->startOfMonth();
                $prevEnd = Carbon::now()->subMonth()->endOfMonth();
                break;

            case 'last_month':
                $currentStart = Carbon::now()->subMonth()->startOfMonth();
                $currentEnd = Carbon::now()->subMonth()->endOfMonth();
                $prevStart = Carbon::now()->subMonths(2)->startOfMonth();
                $prevEnd = Carbon::now()->subMonths(2)->endOfMonth();
                break;

            case '90days':
                $currentStart = Carbon::now()->subDays(89)->startOfDay();
                $currentEnd = Carbon::now()->endOfDay();
                $prevStart = Carbon::now()->subDays(179)->startOfDay();
                $prevEnd = Carbon::now()->subDays(90)->endOfDay();
                break;

            case 'this_year':
                $currentStart = Carbon::now()->startOfYear();
                $currentEnd = Carbon::now()->endOfDay();
                $prevStart = Carbon::now()->subYear()->startOfYear();
                $prevEnd = Carbon::now()->subYear()->endOfYear();
                break;

            case 'last_year':
                $currentStart = Carbon::now()->subYear()->startOfYear();
                $currentEnd = Carbon::now()->subYear()->endOfYear();
                $prevStart = Carbon::now()->subYears(2)->startOfYear();
                $prevEnd = Carbon::now()->subYears(2)->endOfYear();
                break;

            case 'all_time':
                $currentStart = Carbon::now()->subYears(3)->startOfDay();
                $currentEnd = Carbon::now()->endOfDay();
                $prevStart = Carbon::now()->subYears(6)->startOfDay();
                $prevEnd = Carbon::now()->subYears(3)->subSecond();
                break;

            case 'custom':
                if ($startDate && $endDate) {
                    $currentStart = Carbon::parse($startDate)->startOfDay();
                    $currentEnd = Carbon::parse($endDate)->endOfDay();
                } else {
                    $currentStart = Carbon::now()->subDays(29)->startOfDay();
                    $currentEnd = Carbon::now()->endOfDay();
                }
                $diffDays = max(1, (int) $currentStart->diffInDays($currentEnd) + 1);
                $prevStart = $currentStart->copy()->subDays($diffDays);
                $prevEnd = $currentStart->copy()->subSecond();
                break;

            case '30days':
            default:
                $preset = '30days';
                $currentStart = Carbon::now()->subDays(29)->startOfDay();
                $currentEnd = Carbon::now()->endOfDay();
                $prevStart = Carbon::now()->subDays(59)->startOfDay();
                $prevEnd = Carbon::now()->subDays(30)->endOfDay();
                break;
        }

        return [$currentStart, $currentEnd, $prevStart, $prevEnd, $preset];
    }
}
