<?php

namespace App\Services\AdminSeller;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatisticService
{
    /**
     * Get statistics overview data.
     *
     * @param User $user
     * @return array
     */
    public function getStatisticOverview(User $user): array
    {
        $totalViews = DB::table('link_views')
            ->where('link_id', $user->username)
            ->count();
            
        $totalClicks = DB::table('link_clicks')
            ->where('link_id', $user->username)
            ->count();
            
        $totalSales = DB::table('transactions')
            ->join('digital_products', 'transactions.product_id', '=', 'digital_products.id')
            ->where('digital_products.user_id', $user->id)
            ->where('transactions.status', 'success')
            ->sum('transactions.total_price');

        return [
            'totalViews' => $totalViews,
            'totalClicks' => $totalClicks,
            'totalSales' => $totalSales,
        ];
    }

    /**
     * Get chart data for the specified date range.
     *
     * @param User $user
     * @param string|null $startDate
     * @param string|null $endDate
     * @return array
     */
    public function getChartData(User $user, ?string $startDate, ?string $endDate): array
    {
        if (!$startDate || !$endDate) {
            $endDateObj = Carbon::now();
            $startDateObj = Carbon::now()->subDays(6);
        } else {
            $startDateObj = Carbon::parse($startDate);
            $endDateObj = Carbon::parse($endDate);
        }

        $daysDiff = $startDateObj->diffInDays($endDateObj);
        
        if ($daysDiff > 30) {
            $endDateObj = $startDateObj->copy()->addDays(30);
        }

        $dates = [];
        $views = [];
        $clicks = [];
        $sales = [];

        $currentDate = $startDateObj->copy();
        while ($currentDate <= $endDateObj) {
            $dates[] = $currentDate->format('d M');
            
            $viewCount = DB::table('link_views')
                ->where('link_id', $user->username)
                ->whereDate('created_at', $currentDate)
                ->count();
            
            $clickCount = DB::table('link_clicks')
                ->where('link_id', $user->username)
                ->whereDate('created_at', $currentDate)
                ->count();
            
            $saleAmount = DB::table('transactions')
                ->join('digital_products', 'transactions.product_id', '=', 'digital_products.id')
                ->where('digital_products.user_id', $user->id)
                ->where('transactions.status', 'success')
                ->whereDate('transactions.created_at', $currentDate)
                ->sum('transactions.total_price');
            
            $views[] = $viewCount;
            $clicks[] = $clickCount;
            $sales[] = $saleAmount;
            
            $currentDate->addDay();
        }

        return [
            'labels' => $dates,
            'views' => $views,
            'clicks' => $clicks,
            'sales' => $sales,
            'start_date' => $startDateObj->format('Y-m-d'),
            'end_date' => $endDateObj->format('Y-m-d')
        ];
    }
}
