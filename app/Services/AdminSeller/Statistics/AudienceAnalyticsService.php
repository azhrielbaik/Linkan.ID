<?php

namespace App\Services\AdminSeller\Statistics;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AudienceAnalyticsService
{
    /**
     * Audience breakdown (Device, Geo Cities & Countries, Traffic Sources, Peak Hours & Days).
     */
    public function getAudienceBreakdown(User $user, Carbon $start, Carbon $end): array
    {
        $clicksQuery = DB::table('shortlink_clicks')
            ->where('user_id', $user->id)
            ->whereBetween('created_at', [$start, $end]);

        // 1. Device Type
        $devices = (clone $clicksQuery)
            ->select('device_type', DB::raw('count(*) as count'))
            ->whereNotNull('device_type')
            ->groupBy('device_type')
            ->orderByDesc('count')
            ->get();

        $deviceLabels = [];
        $deviceSeries = [];
        foreach ($devices as $d) {
            $deviceLabels[] = ucfirst($d->device_type);
            $deviceSeries[] = (int) $d->count;
        }

        // 2. Traffic Source
        $sources = (clone $clicksQuery)
            ->select('source', DB::raw('count(*) as count'))
            ->groupBy('source')
            ->orderByDesc('count')
            ->limit(8)
            ->get();

        $sourceLabels = [];
        $sourceSeries = [];
        foreach ($sources as $s) {
            $sourceLabels[] = ucfirst($s->source ?: 'Direct');
            $sourceSeries[] = (int) $s->count;
        }

        // 3. Top 10 Cities
        $cities = (clone $clicksQuery)
            ->select('city', DB::raw('count(*) as count'))
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->where('city', '!=', '-')
            ->groupBy('city')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        // 4. Top 10 Countries
        $countries = (clone $clicksQuery)
            ->select('country', DB::raw('count(*) as count'))
            ->whereNotNull('country')
            ->where('country', '!=', '')
            ->where('country', '!=', '-')
            ->groupBy('country')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        // 5. Peak Hours (0 - 23)
        $hourExpr = $this->getHourExpression();
        $hours = (clone $clicksQuery)
            ->selectRaw("{$hourExpr} as hour, count(*) as count")
            ->groupBy('hour')
            ->pluck('count', 'hour');

        $peakHoursLabels = [];
        $peakHoursSeries = [];
        for ($h = 0; $h < 24; $h++) {
            $peakHoursLabels[] = sprintf('%02d:00', $h);
            $peakHoursSeries[] = (int) ($hours->get($h, 0));
        }

        // 6. Peak Days (Mon - Sun)
        $dayExpr = $this->getDayOfWeekExpression();
        $days = (clone $clicksQuery)
            ->selectRaw("{$dayExpr} as day_name, count(*) as count")
            ->groupBy('day_name')
            ->pluck('count', 'day_name');

        $dayOrder = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $dayLabelsId = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        $peakDaysSeries = [];

        foreach ($dayOrder as $engDay) {
            $peakDaysSeries[] = (int) ($days->get($engDay, 0));
        }

        return [
            'device_labels' => $deviceLabels ?: ['Mobile', 'Desktop', 'Tablet'],
            'device_series' => $deviceSeries ?: [0, 0, 0],
            'source_labels' => $sourceLabels ?: ['Direct'],
            'source_series' => $sourceSeries ?: [0],
            'cities_labels' => $cities->pluck('city')->all(),
            'cities_series' => $cities->pluck('count')->map(fn ($v) => (int) $v)->all(),
            'countries_labels' => $countries->pluck('country')->all(),
            'countries_series' => $countries->pluck('count')->map(fn ($v) => (int) $v)->all(),
            'peak_hours_labels' => $peakHoursLabels,
            'peak_hours_series' => $peakHoursSeries,
            'peak_days_labels' => $dayLabelsId,
            'peak_days_series' => $peakDaysSeries,
        ];
    }

    /**
     * Database driver independent hour expression.
     */
    public function getHourExpression(): string
    {
        return DB::getDriverName() === 'sqlite'
            ? "cast(strftime('%H', created_at) as integer)"
            : 'HOUR(created_at)';
    }

    /**
     * Database driver independent day of week expression.
     */
    public function getDayOfWeekExpression(): string
    {
        return DB::getDriverName() === 'sqlite'
            ? "case strftime('%w', created_at) when '0' then 'Sunday' when '1' then 'Monday' when '2' then 'Tuesday' when '3' then 'Wednesday' when '4' then 'Thursday' when '5' then 'Friday' when '6' then 'Saturday' end"
            : 'DAYNAME(created_at)';
    }
}
