<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\DigitalProduct;
use App\Models\User;

class DashboardController extends Controller
{
    public function beranda()
    {
        $user = Auth::user();

        // Debug: Log user ID
        \Log::info('Dashboard - Calculating earnings for user ID: ' . $user->id);

        // Ambil produk digital milik user
        $digitalProducts = DigitalProduct::where('user_id', $user->id)->get();
        $totalProducts = $digitalProducts->count();

        // Ambil semua transaksi untuk debugging
        $allTransactions = DB::table('transactions')
            ->join('digital_products', 'transactions.product_id', '=', 'digital_products.id')
            ->where('digital_products.user_id', $user->id)
            ->select('transactions.*', 'digital_products.title')
            ->get();

        // Log semua transaksi (termasuk yang tidak success)
        \Log::info('Dashboard - All transactions for user:');
        foreach ($allTransactions as $transaction) {
            \Log::info("Transaction ID: {$transaction->id}, Status: {$transaction->status}, Amount: {$transaction->total_price}, Product: {$transaction->title}, Created: {$transaction->created_at}");
        }

        // Ambil total views dan clicks berdasarkan link_id (username)
        $totalViews = DB::table('link_views')
            ->where('link_id', $user->username)
            ->count();

        $totalClicks = DB::table('link_clicks')
            ->where('link_id', $user->username)
            ->count();

        // Debug untuk memastikan data
        \Log::info('Dashboard - Total Views: ' . $totalViews);
        \Log::info('Dashboard - Username: ' . $user->username);

        // Ambil data lifetime orders (hanya transaksi yang berhasil)
        $lifetimeOrders = DB::table('transactions')
            ->join('digital_products', 'transactions.product_id', '=', 'digital_products.id')
            ->where('digital_products.user_id', $user->id)
            ->where('transactions.status', 'success')
            ->sum('transactions.qty');

        \Log::info('Dashboard - Lifetime Orders Calculation: ' . $lifetimeOrders);

        // Ambil semua transaksi yang berhasil
        $successTransactions = DB::table('transactions')
            ->join('digital_products', 'transactions.product_id', '=', 'digital_products.id')
            ->where('digital_products.user_id', $user->id)
            ->where('transactions.status', 'success')
            ->select('transactions.*', 'digital_products.title')
            ->get();

        // Log transaksi yang berhasil
        \Log::info('Dashboard - Successful transactions:');
        foreach ($successTransactions as $transaction) {
            \Log::info("Success Transaction ID: {$transaction->id}, Amount: {$transaction->total_price}, Product: {$transaction->title}, Created: {$transaction->created_at}");
        }

        // Hitung total pendapatan dari transaksi yang berhasil
        $totalEarnings = (float)DB::table('transactions')
            ->join('digital_products', 'transactions.product_id', '=', 'digital_products.id')
            ->where('digital_products.user_id', $user->id)
            ->where('transactions.status', 'success')
            ->sum('transactions.total_price');

        // Kurangi total penarikan yang sudah diajukan
        $totalWithdrawn = (float)DB::table('payout_transactions')
            ->where('user_id', $user->id)
            ->sum('amount');
        $totalEarnings = $totalEarnings - $totalWithdrawn;

        // Debug: Log hasil perhitungan
        \Log::info('Dashboard - Total Earnings Calculation: ' . $totalEarnings);
        \Log::info('Dashboard - Raw SQL Query: ' . DB::table('transactions')
            ->join('digital_products', 'transactions.product_id', '=', 'digital_products.id')
            ->where('digital_products.user_id', $user->id)
            ->where('transactions.status', 'success')
            ->toSql());
        \Log::info('Dashboard - Query Bindings: ' . json_encode([
            'user_id' => $user->id,
            'status' => 'success'
        ]));

        // Update balance di tabel users jika berbeda dengan total pendapatan
        $currentBalance = (float)(DB::table('users')->where('id', $user->id)->value('balance') ?? 0);
        if ($currentBalance != $totalEarnings) {
            \Log::info("Dashboard - Updating balance from {$currentBalance} to {$totalEarnings}");
            DB::table('users')
                ->where('id', $user->id)
                ->update(['balance' => $totalEarnings]);
        }

        // Debug: Log final values
        \Log::info('Dashboard - Final Values:');
        \Log::info('totalEarnings: ' . $totalEarnings);
        \Log::info('currentBalance: ' . $currentBalance);
        \Log::info('lifetimeOrders: ' . $lifetimeOrders);

        // Ambil data appearance untuk profile
        $appearance = \App\Models\Appearance::where('user_id', $user->id)->first();

        // Total shortlinks
        $totalShortlinks = \App\Models\Shortlink::where('user_id', $user->id)->count();
        $activeMicrosite = ($appearance && $appearance->is_active) ? 1 : 1;

        // Ambil pengumuman broadcast aktif dari platform admin
        $announcements = \App\Models\BroadcastAnnouncement::where('is_active', true)->latest()->get();

        // Data Banding Akun Ditangguhkan
        $appeals = \App\Models\SuspensionAppeal::where('user_id', $user->id)->orderBy('created_at', 'asc')->get();
        $activeAppeal = $appeals->last();
        $totalAppealsCount = $appeals->count();
        $maxAppeals = 3;
        $remainingAttempts = max(0, $maxAppeals - $totalAppealsCount);
        $canSubmitAppeal = true;
        $cooldownUntil = null;
        $remainingCooldownText = null;

        if ($totalAppealsCount >= $maxAppeals) {
            $canSubmitAppeal = false;
        } elseif ($activeAppeal && $activeAppeal->status === 'rejected') {
            $rejectedAt = \Carbon\Carbon::parse($activeAppeal->resolved_at ?? $activeAppeal->updated_at);
            $cooldownUntil = $rejectedAt->copy()->addDay();
            if (now()->lt($cooldownUntil)) {
                $canSubmitAppeal = false;
                $diff = now()->diff($cooldownUntil);
                $remainingParts = [];
                if ($diff->h > 0) $remainingParts[] = $diff->h . ' jam';
                if ($diff->i > 0) $remainingParts[] = $diff->i . ' menit';
                if (empty($remainingParts)) $remainingParts[] = $diff->s . ' detik';
                $remainingCooldownText = implode(' ', $remainingParts);
            }
        }

        return view('homeadminS.beranda', compact(
            'totalProducts',
            'totalViews',
            'totalClicks',
            'totalShortlinks',
            'activeMicrosite',
            'lifetimeOrders',
            'totalEarnings',
            'appearance',
            'announcements',
            'activeAppeal',
            'totalAppealsCount',
            'maxAppeals',
            'remainingAttempts',
            'canSubmitAppeal',
            'cooldownUntil',
            'remainingCooldownText'
        ));
    }

    /**
     * Mengajukan surat banding suspensi dari seller ke Admin Platform.
     * Aturan:
     * 1. Maksimal 3 kali pengajuan banding.
     * 2. Jika ditolak, baru dapat mengajukan kembali setelah 1 hari (24 jam).
     */
    public function submitAppeal(Request $request)
    {
        $user = Auth::user();

        if (!$user->isSuspended()) {
            return back()->with('info', 'Akun Anda saat ini dalam status aktif dan tidak ditangguhkan.');
        }

        // 1. Cek batas maksimal pengajuan banding (Maksimal 3 kali)
        $totalAppeals = \App\Models\SuspensionAppeal::where('user_id', $user->id)->count();
        if ($totalAppeals >= 3) {
            return back()->with('error', 'Anda telah mencapai batas maksimum pengajuan banding (3 kali). Pengajuan banding baru tidak dapat dilakukan lagi.');
        }

        // 2. Cek apakah ada permohonan banding yang masih pending
        $pendingAppeal = \App\Models\SuspensionAppeal::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if ($pendingAppeal) {
            return back()->with('error', 'Anda sudah memiliki permohonan banding yang sedang dalam proses peninjauan oleh Admin Platform.');
        }

        // 3. Cek jeda waktu 1 hari (24 jam) setelah penolakan terakhir
        $latestRejected = \App\Models\SuspensionAppeal::where('user_id', $user->id)
            ->where('status', 'rejected')
            ->latest()
            ->first();

        if ($latestRejected) {
            $rejectedAt = \Carbon\Carbon::parse($latestRejected->resolved_at ?? $latestRejected->updated_at);
            $cooldownUntil = $rejectedAt->copy()->addDay();
            if (now()->lt($cooldownUntil)) {
                $diff = now()->diff($cooldownUntil);
                $remainingParts = [];
                if ($diff->h > 0) $remainingParts[] = $diff->h . ' jam';
                if ($diff->i > 0) $remainingParts[] = $diff->i . ' menit';
                if (empty($remainingParts)) $remainingParts[] = $diff->s . ' detik';
                $remainingText = implode(' ', $remainingParts);
                return back()->with('error', "Permohonan banding Anda sebelumnya telah ditolak. Anda baru dapat mengajukan banding kembali setelah 1 hari (tersisa {$remainingText}).");
            }
        }

        $request->validate([
            'appeal_reason' => ['required', 'string', 'min:10', 'max:1500'],
        ], [
            'appeal_reason.required' => 'Mohon jelaskan alasan atau klarifikasi banding Anda.',
            'appeal_reason.min'      => 'Penjelasan banding minimal 10 karakter.',
            'appeal_reason.max'      => 'Penjelasan banding maksimal 1500 karakter.',
        ]);

        \App\Models\SuspensionAppeal::create([
            'user_id'       => $user->id,
            'appeal_reason' => strip_tags($request->appeal_reason),
            'status'        => 'pending',
        ]);

        $attemptNumber = $totalAppeals + 1;
        return back()->with('success', "Permohonan banding ke-{$attemptNumber} dari 3 berhasil dikirimkan. Tim Platform Admin akan segera meninjau permohonan Anda.");
    }

    public function getChartData(Request $request)
    {
        $user = Auth::user();

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Jika tanggal tidak diberikan, ambil 7 hari terakhir
        try {
            $startDate = $startDate ? Carbon::parse($startDate) : Carbon::now()->subDays(6);
            $endDate = $endDate ? Carbon::parse($endDate) : Carbon::now();
        } catch (\Exception $e) {
            // Jika parsing gagal, set default
            $startDate = Carbon::now()->subDays(6);
            $endDate = Carbon::now();
        }

        // Batasi range maksimal 30 hari
        if ($startDate->diffInDays($endDate) > 30) {
            $endDate = $startDate->copy()->addDays(30);
        }

        $dates = [];
        $views = [];
        $clicks = [];

        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            $dates[] = $currentDate->format('d M');

            $viewCount = DB::table('link_views')
                ->where('link_id', $user->username)
                ->whereDate('created_at', $currentDate)
                ->count();

            $clickCount = DB::table('link_clicks')
                ->where('link_id', $user->username)
                ->whereDate('created_at', $currentDate)
                ->count();

            $views[] = $viewCount;
            $clicks[] = $clickCount;

            $currentDate->addDay();
        }

        return response()->json([
            'labels' => $dates,
            'views' => $views,
            'clicks' => $clicks,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d')
        ]);
    }

    public function getDigitalProducts()
    {
        $user = Auth::user();

        $digitalProducts = DigitalProduct::where('user_id', $user->id)
            ->select('id', 'title', 'price', 'created_at')
            ->latest()
            ->get();

        return response()->json([
            'total' => $digitalProducts->count(),
            'products' => $digitalProducts
        ]);
    }

    // Fungsi untuk mencatat CLICK
    public function trackClick(Request $request)
    {
        $linkId = $request->query('link_id');
        $target = $request->query('target');

        // Validasi URL target
        if (!filter_var($target, FILTER_VALIDATE_URL)) {
            abort(400, 'Invalid target URL');
        }

        // Dapatkan user berdasarkan username
        $user = User::where('username', $linkId)->first();
        if (!$user) {
            abort(404, 'User not found');
        }

        // Debug untuk memastikan data
        \Log::info('Tracking Click:');
        \Log::info('Link ID: ' . $linkId);
        \Log::info('Target URL: ' . $target);
        \Log::info('IP Address: ' . $request->ip());
        \Log::info('User Agent: ' . $request->header('User-Agent'));

        // Catat click ke database
        DB::table('link_clicks')->insert([
            'user_id' => $user->id,
            'link_id' => $linkId,
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->to($target);
    }

    /**
     * Helper untuk mengambil payload notifikasi Seller Admin.
     */
    private function fetchSellerNotificationsData($user): array
    {
        $notifications = [];

        // 1. Transaksi / Pesanan Produk Digital Baru Milik Seller
        $recentOrders = DB::table('transactions')
            ->join('digital_products', 'transactions.product_id', '=', 'digital_products.id')
            ->where('digital_products.user_id', $user->id)
            ->where('transactions.status', 'success')
            ->select(
                'transactions.id',
                'transactions.order_id',
                'transactions.buyer_name',
                'transactions.total_price',
                'transactions.created_at',
                'digital_products.title as product_title'
            )
            ->orderBy('transactions.created_at', 'desc')
            ->limit(10)
            ->get();

        foreach ($recentOrders as $order) {
            $timeAgo = \Carbon\Carbon::parse($order->created_at)->diffForHumans();
            $notifications[] = [
                'id'          => 'order_' . $order->id,
                'type'        => 'order',
                'title'       => 'Penjualan Baru Berhasil!',
                'message'     => 'Produk <strong>' . e($order->product_title) . '</strong> terjual senilai <strong>Rp ' . number_format($order->total_price, 0, ',', '.') . '</strong> kepada ' . e($order->buyer_name) . '.',
                'badge'       => 'Pesanan',
                'badge_class' => 'badge-order',
                'icon'        => 'fas fa-shopping-cart',
                'icon_bg'     => '#dcfce7',
                'icon_color'  => '#16a34a',
                'url'         => route('admin.orders'),
                'time_ago'    => $timeAgo,
                'timestamp'   => strtotime($order->created_at),
            ];
        }

        // 2. Status Verifikasi Produk Seller (approved, rejected, takedown)
        $verifiedProducts = DB::table('digital_products')
            ->where('user_id', $user->id)
            ->whereIn('verification_status', ['approved', 'rejected'])
            ->select('id', 'title', 'verification_status', 'rejection_reason', 'takedown_reason', 'takedown_at', 'updated_at', 'created_at')
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        foreach ($verifiedProducts as $prod) {
            $timeAgo = \Carbon\Carbon::parse($prod->updated_at ?? $prod->created_at)->diffForHumans();

            if (!empty($prod->takedown_at)) {
                $notifications[] = [
                    'id'          => 'prod_td_' . $prod->id,
                    'type'        => 'product',
                    'title'       => 'Produk Dinonaktifkan (Takedown)',
                    'message'     => 'Produk <strong>' . e($prod->title) . '</strong> di-takedown oleh Admin. Alasan: <em>' . e($prod->takedown_reason ?? 'Pelanggaran ketentuan') . '</em>',
                    'badge'       => 'Takedown',
                    'badge_class' => 'badge-product-rejected',
                    'icon'        => 'fas fa-ban',
                    'icon_bg'     => '#fee2e2',
                    'icon_color'  => '#dc2626',
                    'url'         => route('admin.digital-products.index'),
                    'time_ago'    => $timeAgo,
                    'timestamp'   => strtotime($prod->takedown_at),
                ];
            } elseif ($prod->verification_status === 'approved') {
                $notifications[] = [
                    'id'          => 'prod_app_' . $prod->id,
                    'type'        => 'product',
                    'title'       => 'Produk Telah Disetujui!',
                    'message'     => 'Produk <strong>' . e($prod->title) . '</strong> telah diverifikasi dan kini aktif untuk dijual.',
                    'badge'       => 'Disetujui',
                    'badge_class' => 'badge-product-approved',
                    'icon'        => 'fas fa-check-circle',
                    'icon_bg'     => '#EEF0FE',
                    'icon_color'  => '#5A5BF1',
                    'url'         => route('admin.digital-products.index'),
                    'time_ago'    => $timeAgo,
                    'timestamp'   => strtotime($prod->updated_at),
                ];
            } elseif ($prod->verification_status === 'rejected') {
                $notifications[] = [
                    'id'          => 'prod_rej_' . $prod->id,
                    'type'        => 'product',
                    'title'       => 'Verifikasi Produk Ditolak',
                    'message'     => 'Produk <strong>' . e($prod->title) . '</strong> ditolak. Alasan: <em>' . e($prod->rejection_reason ?? 'Tidak memenuhi syarat') . '</em>',
                    'badge'       => 'Ditolak',
                    'badge_class' => 'badge-product-rejected',
                    'icon'        => 'fas fa-times-circle',
                    'icon_bg'     => '#fee2e2',
                    'icon_color'  => '#dc2626',
                    'url'         => route('admin.digital-products.index'),
                    'time_ago'    => $timeAgo,
                    'timestamp'   => strtotime($prod->updated_at),
                ];
            }
        }

        // 3. Status Payout Seller (approved, rejected)
        $payoutUpdates = DB::table('payout_transactions')
            ->where('user_id', $user->id)
            ->whereIn('status', ['approved', 'rejected'])
            ->select('id', 'amount', 'method', 'bank_name', 'status', 'rejection_reason', 'processed_at', 'created_at', 'updated_at')
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        foreach ($payoutUpdates as $payout) {
            $timeAgo = \Carbon\Carbon::parse($payout->processed_at ?? $payout->updated_at)->diffForHumans();
            $bank = $payout->bank_name ?? strtoupper($payout->method);

            if ($payout->status === 'approved') {
                $notifications[] = [
                    'id'          => 'payout_app_' . $payout->id,
                    'type'        => 'payout',
                    'title'       => 'Penarikan Dana Berhasil!',
                    'message'     => 'Payout sebesar <strong>Rp ' . number_format($payout->amount, 0, ',', '.') . '</strong> via ' . e($bank) . ' telah berhasil ditransfer.',
                    'badge'       => 'Payout Cair',
                    'badge_class' => 'badge-payout-approved',
                    'icon'        => 'fas fa-money-bill-wave',
                    'icon_bg'     => '#dcfce7',
                    'icon_color'  => '#16a34a',
                    'url'         => route('admin.payout.history'),
                    'time_ago'    => $timeAgo,
                    'timestamp'   => strtotime($payout->processed_at ?? $payout->updated_at),
                ];
            } else {
                $notifications[] = [
                    'id'          => 'payout_rej_' . $payout->id,
                    'type'        => 'payout',
                    'title'       => 'Permintaan Payout Ditolak',
                    'message'     => 'Payout sebesar <strong>Rp ' . number_format($payout->amount, 0, ',', '.') . '</strong> ditolak. Catatan: <em>' . e($payout->rejection_reason ?? '-') . '</em>',
                    'badge'       => 'Ditolak',
                    'badge_class' => 'badge-payout-rejected',
                    'icon'        => 'fas fa-exclamation-circle',
                    'icon_bg'     => '#fee2e2',
                    'icon_color'  => '#dc2626',
                    'url'         => route('admin.payout.history'),
                    'time_ago'    => $timeAgo,
                    'timestamp'   => strtotime($payout->processed_at ?? $payout->updated_at),
                ];
            }
        }

        // 4. Status Banding Akun Seller (approved, rejected)
        $appealUpdates = DB::table('suspension_appeals')
            ->where('user_id', $user->id)
            ->whereIn('status', ['approved', 'rejected'])
            ->select('id', 'status', 'admin_notes', 'resolved_at', 'updated_at', 'created_at')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        foreach ($appealUpdates as $appeal) {
            $timeAgo = \Carbon\Carbon::parse($appeal->resolved_at ?? $appeal->updated_at)->diffForHumans();
            if ($appeal->status === 'approved') {
                $notifications[] = [
                    'id'          => 'appeal_app_' . $appeal->id,
                    'type'        => 'appeal',
                    'title'       => 'Banding Akun Disetujui!',
                    'message'     => 'Permohonan banding Anda telah diterima dan akun Anda telah dipulihkan kembali.',
                    'badge'       => 'Dipulihkan',
                    'badge_class' => 'badge-appeal-approved',
                    'icon'        => 'fas fa-shield-alt',
                    'icon_bg'     => '#dcfce7',
                    'icon_color'  => '#16a34a',
                    'url'         => route('admin.dashboard'),
                    'time_ago'    => $timeAgo,
                    'timestamp'   => strtotime($appeal->resolved_at ?? $appeal->updated_at),
                ];
            } else {
                $notifications[] = [
                    'id'          => 'appeal_rej_' . $appeal->id,
                    'type'        => 'appeal',
                    'title'       => 'Banding Akun Ditolak',
                    'message'     => 'Permohonan banding akun Anda ditolak oleh Admin Platform. Catatan: <em>' . e($appeal->admin_notes ?? '-') . '</em>',
                    'badge'       => 'Ditolak',
                    'badge_class' => 'badge-appeal-rejected',
                    'icon'        => 'fas fa-shield-alt',
                    'icon_bg'     => '#fee2e2',
                    'icon_color'  => '#dc2626',
                    'url'         => route('admin.dashboard'),
                    'time_ago'    => $timeAgo,
                    'timestamp'   => strtotime($appeal->resolved_at ?? $appeal->updated_at),
                ];
            }
        }

        // 5. Broadcast Announcements Aktif
        $broadcasts = DB::table('broadcast_announcements')
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        foreach ($broadcasts as $b) {
            $timeAgo = \Carbon\Carbon::parse($b->created_at)->diffForHumans();
            $notifications[] = [
                'id'          => 'broadcast_' . $b->id,
                'type'        => 'broadcast',
                'title'       => $b->title,
                'message'     => e($b->message),
                'badge'       => 'Pengumuman',
                'badge_class' => 'badge-broadcast',
                'icon'        => 'fas fa-bullhorn',
                'icon_bg'     => '#EEF0FE',
                'icon_color'  => '#5A5BF1',
                'url'         => route('admin.dashboard'),
                'time_ago'    => $timeAgo,
                'timestamp'   => strtotime($b->created_at),
            ];
        }

        // Urutkan berdasarkan timestamp terbaru
        usort($notifications, function ($a, $b) {
            return $b['timestamp'] - $a['timestamp'];
        });

        return [
            'status'        => 'success',
            'unread_count'  => count($notifications),
            'notifications' => array_slice($notifications, 0, 20)
        ];
    }

    /**
     * Endpoint JSON notifikasi standar untuk Seller (Admin Seller).
     */
    public function getNotifications(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['status' => 'error', 'notifications' => []], 401);
        }

        return response()->json($this->fetchSellerNotificationsData($user));
    }

    /**
     * Server-Sent Events (SSE) Stream endpoint untuk Seller (Admin Seller).
     */
    public function streamNotifications(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['status' => 'error'], 401);
        }

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }

        return response()->stream(function () use ($user) {
            @set_time_limit(0);
            @ini_set('implicit_flush', 1);
            if (ob_get_level()) {
                @ob_end_flush();
            }
            flush();

            $maxCycles = 10;
            $lastHash = null;

            for ($i = 0; $i < $maxCycles; $i++) {
                if (connection_aborted()) {
                    break;
                }

                $data = $this->fetchSellerNotificationsData($user);
                $currentHash = md5(json_encode($data));

                if ($lastHash !== $currentHash || $i === 0) {
                    echo "event: notifications\n";
                    echo "data: " . json_encode($data) . "\n\n";
                    $lastHash = $currentHash;
                } else {
                    echo ": ping\n\n";
                }

                if (ob_get_level()) {
                    @ob_flush();
                }
                flush();

                sleep(3);
            }
        }, 200, [
            'Content-Type'      => 'text/event-stream',
            'Cache-Control'     => 'no-cache, no-store, must-revalidate',
            'Connection'        => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
    }
}
