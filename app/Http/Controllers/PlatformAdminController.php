<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlatformAdminController extends Controller
{
    // Menampilkan halaman beranda platform admin
    public function beranda()
    {
        $commissions = DB::table('platform_commissions')
            ->join('users as sellers', 'platform_commissions.seller_id', '=', 'sellers.id')
            ->select(
                'platform_commissions.*',
                'sellers.name as seller_name',
                'sellers.email as seller_email'
            )
            ->orderBy('platform_commissions.created_at', 'desc')
            ->get();

        return view('platformadmin.berandaplatform', compact('commissions'));
    }

    // Function untuk mencetak data
    public function print(Request $request)
    {
        $data = $request->input('data');
        if ($data) {
            $data = json_decode($data, true);
        } else {
            $data = [
                'total_earnings' => 'IDR 0',
                'commission_details' => []
            ];
        }
        return view('platformadmin.print', compact('data'));
    }

    public function getCommissions()
    {
        $commissions = \DB::table('platform_commissions')
            ->join('users as sellers', 'platform_commissions.seller_id', '=', 'sellers.id')
            ->select(
                'platform_commissions.*',
                'sellers.name as seller_name',
                'sellers.email as seller_email'
            )
            ->orderBy('platform_commissions.created_at', 'desc')
            ->get();
        $total_earnings = \DB::table('platform_commissions')->sum('commission');
        return response()->json([
            'commissions' => $commissions,
            'total_earnings' => $total_earnings
        ]);
    }
}
