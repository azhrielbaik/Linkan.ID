<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StatisticController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Ambil total views dan clicks
        $totalViews = DB::table('link_views')
            ->where('link_id', $user->username)
            ->count();
            
        $totalClicks = DB::table('link_clicks')
            ->where('link_id', $user->username)
            ->count();
            
        // Ambil total sales dari transactions
        $totalSales = DB::table('transactions')
            ->join('digital_products', 'transactions.product_id', '=', 'digital_products.id')
            ->where('digital_products.user_id', $user->id)
            ->where('transactions.status', 'success')
            ->sum('transactions.total_price');

        return view('homeadminS.statistic', compact(
            'totalViews',
            'totalClicks',
            'totalSales',
        ));
    }

    public function getChartData(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $user = Auth::user();
        
        // Jika tidak ada tanggal yang dipilih, gunakan 7 hari terakhir
        if (!$startDate || !$endDate) {
            $endDate = Carbon::now();
            $startDate = Carbon::now()->subDays(6);
        } else {
            $startDate = Carbon::parse($startDate);
            $endDate = Carbon::parse($endDate);
        }

        // Hitung selisih hari
        $daysDiff = $startDate->diffInDays($endDate);
        
        // Batasi maksimal 30 hari
        if ($daysDiff > 30) {
            $endDate = $startDate->copy()->addDays(30);
        }

        $dates = [];
        $views = [];
        $clicks = [];
        $sales = [];

        // Generate data untuk setiap hari dalam rentang
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $dates[] = $currentDate->format('d M');
            
            // Ambil data views untuk tanggal tersebut
            $viewCount = DB::table('link_views')
                ->where('link_id', $user->username)
                ->whereDate('created_at', $currentDate)
                ->count();
            
            // Ambil data clicks untuk tanggal tersebut
            $clickCount = DB::table('link_clicks')
                ->where('link_id', $user->username)
                ->whereDate('created_at', $currentDate)
                ->count();
            
            // Ambil data sales untuk tanggal tersebut dari transactions
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

        return response()->json([
            'labels' => $dates,
            'views' => $views,
            'clicks' => $clicks,
            'sales' => $sales,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d')
        ]);
    }
} 