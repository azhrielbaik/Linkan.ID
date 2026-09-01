<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PlatformAdminController extends Controller
{
    // Menampilkan halaman beranda platform admin
    public function beranda()
    {
        // 1. Stats Ringkasan
        $totalUsers = User::where('role', '!=', 'admin_platform')->count();
        $totalTransactions = DB::table('transactions')->where('status', 'success')->count();
        $totalCommission = DB::table('platform_commissions')->sum('commission') ?? 0;
        $totalProducts = DB::table('digital_products')->count();

        // 2. Chart Pendapatan - 12 Bulan Terakhir
        $monthlyLabels = [];
        $monthlyData = [];
        for ($i = 11; $i >= 0; $i--) {
            $monthDate = now()->subMonths($i);
            $monthKey = $monthDate->format('Y-m');
            $monthName = $monthDate->translatedFormat('M Y');
            $monthlyLabels[] = $monthName;

            $sum = DB::table('platform_commissions')
                ->whereYear('created_at', $monthDate->year)
                ->whereMonth('created_at', $monthDate->month)
                ->sum('commission') ?? 0;
            $monthlyData[] = (float)$sum;
        }

        // Chart Pendapatan - 7 Hari Terakhir
        $weeklyLabels = [];
        $weeklyData = [];
        for ($i = 6; $i >= 0; $i--) {
            $dayDate = now()->subDays($i);
            $dayName = $dayDate->translatedFormat('D, d M');
            $weeklyLabels[] = $dayName;

            $sum = DB::table('platform_commissions')
                ->whereDate('created_at', $dayDate->toDateString())
                ->sum('commission') ?? 0;
            $weeklyData[] = (float)$sum;
        }

        // 3. Top Seller Ranking (Berdasarkan total komisi / penjualan)
        $topSellers = DB::table('users')
            ->where('users.role', '!=', 'admin_platform')
            ->leftJoin('platform_commissions', 'users.id', '=', 'platform_commissions.seller_id')
            ->leftJoin('digital_products', 'users.id', '=', 'digital_products.user_id')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.avatar',
                DB::raw('COUNT(DISTINCT digital_products.id) as total_products'),
                DB::raw('COUNT(DISTINCT platform_commissions.id) as total_sales_count'),
                DB::raw('COALESCE(SUM(platform_commissions.commission), 0) as total_commission_earned'),
                DB::raw('COALESCE(SUM(platform_commissions.amount), 0) as total_turnover')
            )
            ->groupBy('users.id', 'users.name', 'users.email', 'users.avatar')
            ->orderByDesc('total_commission_earned')
            ->orderByDesc('total_sales_count')
            ->limit(5)
            ->get();

        // 4. Riwayat Komisi Terkini
        $commissions = DB::table('platform_commissions')
            ->join('users as sellers', 'platform_commissions.seller_id', '=', 'sellers.id')
            ->select(
                'platform_commissions.*',
                'sellers.name as seller_name',
                'sellers.email as seller_email'
            )
            ->orderBy('platform_commissions.created_at', 'desc')
            ->limit(10)
            ->get();

        return view('platformadmin.berandaplatform', compact(
            'totalUsers',
            'totalTransactions',
            'totalCommission',
            'totalProducts',
            'monthlyLabels',
            'monthlyData',
            'weeklyLabels',
            'weeklyData',
            'topSellers',
            'commissions'
        ));
    }

    // Export Laporan Komisi & Transaksi ke Excel (CSV)
    public function exportExcel(Request $request)
    {
        $fileName = 'laporan_komisi_platform_' . date('Y-m-d_H-i-s') . '.csv';

        $commissions = DB::table('platform_commissions')
            ->join('users as sellers', 'platform_commissions.seller_id', '=', 'sellers.id')
            ->select(
                'platform_commissions.id',
                'sellers.name as seller_name',
                'sellers.email as seller_email',
                'platform_commissions.amount as turnover',
                'platform_commissions.commission',
                'platform_commissions.created_at'
            )
            ->orderBy('platform_commissions.created_at', 'desc')
            ->get();

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename={$fileName}",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID', 'Nama Seller', 'Email Seller', 'Nominal Transaksi (IDR)', 'Komisi Platform (IDR)', 'Waktu'];

        $callback = function () use ($commissions, $columns) {
            $file = fopen('php://output', 'w');
            // Add BOM for UTF-8 Excel support
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, $columns);

            foreach ($commissions as $row) {
                fputcsv($file, [
                    $row->id,
                    $row->seller_name,
                    $row->seller_email,
                    $row->turnover,
                    $row->commission,
                    $row->created_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Menampilkan halaman print laporan komisi
    public function print(Request $request)
    {
        $data = [];

        if ($request->isMethod('post') && $request->has('data')) {
            $inputData = $request->input('data');
            $data = is_string($inputData) ? json_decode($inputData, true) : $inputData;
        }

        // Fallback jika dibuka via GET atau data kosong: Ambil data dari database langsung
        if (empty($data) || empty($data['commission_details'])) {
            $totalEarnings = DB::table('platform_commissions')->sum('commission') ?? 0;
            $commissions = DB::table('platform_commissions')
                ->join('users as sellers', 'platform_commissions.seller_id', '=', 'sellers.id')
                ->select(
                    'platform_commissions.commission',
                    'platform_commissions.amount',
                    'platform_commissions.created_at',
                    'sellers.name as seller_name',
                    'sellers.email as seller_email'
                )
                ->orderBy('platform_commissions.created_at', 'desc')
                ->get();

            $data = [
                'total_earnings' => 'Rp ' . number_format($totalEarnings, 0, ',', '.'),
                'total_records' => $commissions->count(),
                'commission_details' => $commissions->map(function ($c) {
                    return [
                        'name'   => $c->seller_name,
                        'email'  => $c->seller_email,
                        'date'   => \Carbon\Carbon::parse($c->created_at)->translatedFormat('d M Y, H:i'),
                        'turnover' => 'Rp ' . number_format($c->amount, 0, ',', '.'),
                        'amount' => 'Rp ' . number_format($c->commission, 0, ',', '.')
                    ];
                })->toArray()
            ];
        }

        return view('platformadmin.print', compact('data'));
    }

    // Endpoint JSON untuk realtime komisi
    public function getCommissions(Request $request)
    {
        $totalEarnings = DB::table('platform_commissions')->sum('commission') ?? 0;
        $commissions = DB::table('platform_commissions')
            ->join('users as sellers', 'platform_commissions.seller_id', '=', 'sellers.id')
            ->select(
                'platform_commissions.id',
                'platform_commissions.seller_id',
                'platform_commissions.commission',
                'platform_commissions.amount',
                'platform_commissions.created_at',
                'sellers.name as seller_name',
                'sellers.email as seller_email'
            )
            ->orderBy('platform_commissions.created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'total_earnings' => $totalEarnings,
            'commissions' => $commissions
        ]);
    }

    /**
     * Mengambil payload array notifikasi untuk Platform Admin.
     */
    private function fetchNotificationsData(): array
    {
        $notifications = [];

        // 1. Pending Digital Products (Verifikasi Produk Baru)
        $pendingProducts = DB::table('digital_products')
            ->join('users', 'digital_products.user_id', '=', 'users.id')
            ->where('digital_products.verification_status', 'pending')
            ->select(
                'digital_products.id',
                'digital_products.title as product_name',
                'digital_products.created_at',
                'users.name as seller_name',
                'users.email as seller_email'
            )
            ->orderBy('digital_products.created_at', 'desc')
            ->limit(10)
            ->get();

        foreach ($pendingProducts as $prod) {
            $timeAgo = \Carbon\Carbon::parse($prod->created_at)->diffForHumans();
            $notifications[] = [
                'id'          => 'prod_' . $prod->id,
                'type'        => 'product',
                'title'       => 'Verifikasi Produk Baru',
                'message'     => 'Seller <strong>' . e($prod->seller_name) . '</strong> mengajukan produk <em>"' . e($prod->product_name) . '"</em>.',
                'badge'       => 'Verifikasi',
                'badge_class' => 'badge-product',
                'icon'        => 'fas fa-box-open',
                'icon_bg'     => '#EEF0FE',
                'icon_color'  => '#5A5BF1',
                'url'         => route('platform-admin.verifikasi'),
                'time_ago'    => $timeAgo,
                'timestamp'   => strtotime($prod->created_at),
            ];
        }

        // 2. Pending Payout Requests
        $pendingPayouts = DB::table('payout_transactions')
            ->join('users', 'payout_transactions.user_id', '=', 'users.id')
            ->where('payout_transactions.status', 'pending')
            ->select(
                'payout_transactions.id',
                'payout_transactions.amount',
                'payout_transactions.method',
                'payout_transactions.bank_name',
                'payout_transactions.created_at',
                'users.name as seller_name'
            )
            ->orderBy('payout_transactions.created_at', 'desc')
            ->limit(10)
            ->get();

        foreach ($pendingPayouts as $payout) {
            $timeAgo = \Carbon\Carbon::parse($payout->created_at)->diffForHumans();
            $bank = $payout->bank_name ?? strtoupper($payout->method);
            $notifications[] = [
                'id'          => 'payout_' . $payout->id,
                'type'        => 'payout',
                'title'       => 'Permintaan Payout Baru',
                'message'     => 'Seller <strong>' . e($payout->seller_name) . '</strong> mengajukan withdraw <strong>Rp ' . number_format($payout->amount, 0, ',', '.') . '</strong> via ' . e($bank) . '.',
                'badge'       => 'Payout',
                'badge_class' => 'badge-payout',
                'icon'        => 'fas fa-money-bill-wave',
                'icon_bg'     => '#fef3c7',
                'icon_color'  => '#d97706',
                'url'         => route('platform-admin.payouts.index'),
                'time_ago'    => $timeAgo,
                'timestamp'   => strtotime($payout->created_at),
            ];
        }

        // 3. Pending Suspension Appeals
        $pendingAppeals = DB::table('suspension_appeals')
            ->join('users', 'suspension_appeals.user_id', '=', 'users.id')
            ->where('suspension_appeals.status', 'pending')
            ->select(
                'suspension_appeals.id',
                'suspension_appeals.appeal_reason',
                'suspension_appeals.created_at',
                'users.name as seller_name'
            )
            ->orderBy('suspension_appeals.created_at', 'desc')
            ->limit(10)
            ->get();

        foreach ($pendingAppeals as $appeal) {
            $timeAgo = \Carbon\Carbon::parse($appeal->created_at)->diffForHumans();
            $notifications[] = [
                'id'          => 'appeal_' . $appeal->id,
                'type'        => 'appeal',
                'title'       => 'Permohonan Banding Akun',
                'message'     => 'Seller <strong>' . e($appeal->seller_name) . '</strong> mengajukan banding penangguhan akun.',
                'badge'       => 'Banding',
                'badge_class' => 'badge-appeal',
                'icon'        => 'fas fa-shield-alt',
                'icon_bg'     => '#fee2e2',
                'icon_color'  => '#dc2626',
                'url'         => route('platform-admin.users', ['view' => 'appeals']),
                'time_ago'    => $timeAgo,
                'timestamp'   => strtotime($appeal->created_at),
            ];
        }

        // Urutkan notifikasi berdasarkan waktu terbaru
        usort($notifications, function ($a, $b) {
            return $b['timestamp'] - $a['timestamp'];
        });

        $unreadCount = count($pendingProducts) + count($pendingPayouts) + count($pendingAppeals);

        return [
            'status'        => 'success',
            'unread_count'  => $unreadCount,
            'counts'        => [
                'products' => count($pendingProducts),
                'payouts'  => count($pendingPayouts),
                'appeals'  => count($pendingAppeals),
            ],
            'notifications' => array_slice($notifications, 0, 20)
        ];
    }

    // Endpoint JSON notifikasi standar untuk Platform Admin
    public function getNotifications(Request $request)
    {
        return response()->json($this->fetchNotificationsData());
    }

    /**
     * Server-Sent Events (SSE) Stream endpoint untuk Platform Admin.
     */
    public function streamNotifications(Request $request)
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }

        return response()->stream(function () {
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

                $data = $this->fetchNotificationsData();
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

    // Menampilkan halaman manajemen user
    public function users(Request $request)
    {
        $viewType = $request->input('view', 'users'); // 'users', 'appeals', or 'reset_requests'

        $query = User::with(['suspensionAppeals' => function ($q) {
            $q->where('status', 'pending')->latest()->limit(1);
        }])->where('role', '!=', 'admin_platform');

        // Search by name or email
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by status
        $filter = $request->input('filter', 'all');
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

        // Filter by date range (Registration Date)
        $startDate = $request->input('start_date') ?: $request->input('date', '');
        $endDate   = $request->input('end_date', '');
        if ($startDate && $endDate) {
            $query->whereDate('created_at', '>=', $startDate)
                  ->whereDate('created_at', '<=', $endDate);
        } elseif ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        $totalUsers     = User::where('role', '!=', 'admin_platform')->count();
        $totalActive    = User::where('role', '!=', 'admin_platform')->where(function ($q) {
            $q->whereNull('suspended_at')
              ->orWhere(function ($sub) {
                  $sub->whereNotNull('suspended_until')->where('suspended_until', '<=', now());
              });
        })->count();
        $totalSuspended = User::where('role', '!=', 'admin_platform')->whereNotNull('suspended_at')->where(function ($q) {
            $q->whereNull('suspended_until')
              ->orWhere('suspended_until', '>', now());
        })->count();

        // Data Permohonan Banding
        $appealsQuery = \App\Models\SuspensionAppeal::with('user')->latest();
        if ($appealStatus = $request->input('appeal_status')) {
            $appealsQuery->where('status', $appealStatus);
        }
        $appeals = $appealsQuery->paginate(15, ['*'], 'appeals_page')->withQueryString();
        $pendingAppealsCount = \App\Models\SuspensionAppeal::where('status', 'pending')->count();

        return view('platformadmin.users', compact(
            'users',
            'totalUsers',
            'totalActive',
            'totalSuspended',
            'filter',
            'search',
            'startDate',
            'endDate',
            'pendingAppealsCount'
        ));
    }

    /**
     * Endpoint autocomplete suggestion untuk Manajemen User.
     */
    public function userSuggest(Request $request)
    {
        $q = trim($request->query('q', ''));
        if (empty($q)) {
            return response()->json([]);
        }

        $users = User::where('role', '!=', 'admin_platform')
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%");
            })
            ->limit(10)
            ->get();

        $suggestions = collect();

        foreach ($users as $user) {
            if ($user->name && stripos($user->name, $q) !== false) {
                $suggestions->push([
                    'label' => $user->name . ' (Nama)',
                    'value' => $user->name
                ]);
            }
            if ($user->email && stripos($user->email, $q) !== false) {
                $suggestions->push([
                    'label' => $user->email . ' (Email)',
                    'value' => $user->email
                ]);
            }
        }

        $uniqueSuggestions = $suggestions->unique('value')->values()->take(5);

        return response()->json($uniqueSuggestions);
    }

    // Suspend akun user dengan pilihan durasi dan alasan
    public function suspend(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        // Jangan suspend sesama platform_admin
        if ($user->role === 'admin_platform') {
            return back()->with('error', 'Tidak dapat men-suspend akun Platform Admin.');
        }

        $request->validate([
            'duration'       => ['required', 'string', 'in:1_day,3_days,7_days,30_days,permanent'],
            'suspend_reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $suspendedUntil = null;
        $durationLabel = 'Permanen';

        switch ($request->duration) {
            case '1_day':
                $suspendedUntil = now()->addDay();
                $durationLabel = '1 Hari (s.d ' . $suspendedUntil->format('d M Y, H:i') . ')';
                break;
            case '3_days':
                $suspendedUntil = now()->addDays(3);
                $durationLabel = '3 Hari (s.d ' . $suspendedUntil->format('d M Y, H:i') . ')';
                break;
            case '7_days':
                $suspendedUntil = now()->addDays(7);
                $durationLabel = '7 Hari / 1 Minggu (s.d ' . $suspendedUntil->format('d M Y, H:i') . ')';
                break;
            case '30_days':
                $suspendedUntil = now()->addDays(30);
                $durationLabel = '30 Hari / 1 Bulan (s.d ' . $suspendedUntil->format('d M Y, H:i') . ')';
                break;
            case 'permanent':
            default:
                $suspendedUntil = null;
                $durationLabel = 'Permanen';
                break;
        }

        $reason = $request->suspend_reason ? strip_tags($request->suspend_reason) : 'Pelanggaran ketentuan penggunaan platform';

        $user->update([
            'suspended_at'    => now(),
            'suspended_until' => $suspendedUntil,
            'suspend_reason'  => $reason,
        ]);

        // Catat Log Aktivitas
        \App\Services\ActivityLogger::log(
            'suspend_user',
            "Men-suspend akun user: {$user->name} ({$user->email}) - Durasi: {$durationLabel}. Alasan: {$reason}",
            [
                'target_user_id'  => $user->id,
                'user_name'       => $user->name,
                'user_email'      => $user->email,
                'duration'        => $request->duration,
                'suspended_until' => $suspendedUntil ? $suspendedUntil->toDateTimeString() : null,
                'reason'          => $reason
            ]
        );

        return back()->with('success', "Akun {$user->name} berhasil di-suspend dengan durasi: {$durationLabel}.");
    }

    // Aktifkan kembali akun user
    public function activate(int $id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'suspended_at'    => null,
            'suspended_until' => null,
            'suspend_reason'  => null,
        ]);

        // Jika ada banding pending, otomatis tandai approved
        \App\Models\SuspensionAppeal::where('user_id', $user->id)
            ->where('status', 'pending')
            ->update([
                'status'      => 'approved',
                'admin_notes' => 'Akun dipulihkan secara manual oleh Admin Platform.',
                'resolved_at' => now(),
            ]);

        // Catat Log Aktivitas
        \App\Services\ActivityLogger::log(
            'activate_user',
            "Mengaktifkan kembali akun user: {$user->name} ({$user->email})",
            ['target_user_id' => $user->id, 'user_name' => $user->name, 'user_email' => $user->email]
        );

        return back()->with('success', "Akun {$user->name} berhasil diaktifkan kembali.");
    }

    // Setujui Permohonan Banding
    public function approveAppeal(int $id)
    {
        $appeal = \App\Models\SuspensionAppeal::with('user')->findOrFail($id);

        $appeal->update([
            'status'      => 'approved',
            'admin_notes' => 'Permohonan banding disetujui. Akun telah dipulihkan.',
            'resolved_at' => now(),
        ]);

        // Buka suspensi akun user
        if ($appeal->user) {
            $appeal->user->update([
                'suspended_at'    => null,
                'suspended_until' => null,
                'suspend_reason'  => null,
            ]);
        }

        // Catat Log Aktivitas
        \App\Services\ActivityLogger::log(
            'approve_suspension_appeal',
            "Menyetujui permohonan banding akun: {$appeal->user->name} ({$appeal->user->email})",
            ['appeal_id' => $appeal->id, 'user_id' => $appeal->user_id]
        );

        return back()->with('success', "Permohonan banding dari {$appeal->user->name} berhasil disetujui dan akun telah dipulihkan.");
    }

    // Tolak Permohonan Banding
    public function rejectAppeal(Request $request, int $id)
    {
        $appeal = \App\Models\SuspensionAppeal::with('user')->findOrFail($id);

        $request->validate([
            'admin_notes' => ['required', 'string', 'max:1000'],
        ], [
            'admin_notes.required' => 'Wajib memberikan catatan alasan penolakan banding.',
        ]);

        $adminNotes = strip_tags($request->admin_notes);

        $appeal->update([
            'status'      => 'rejected',
            'admin_notes' => $adminNotes,
            'resolved_at' => now(),
        ]);

        // Catat Log Aktivitas
        \App\Services\ActivityLogger::log(
            'reject_suspension_appeal',
            "Menolak permohonan banding akun: {$appeal->user->name}. Catatan: {$adminNotes}",
            ['appeal_id' => $appeal->id, 'user_id' => $appeal->user_id, 'admin_notes' => $adminNotes]
        );

        return back()->with('success', "Permohonan banding dari {$appeal->user->name} telah ditolak.");
    }

    /**
     * Mengambil detail lengkap profil dan portofolio seller untuk modal inspeksi.
     */
    public function sellerDetail(int $id)
    {
        try {
            $user = User::with('digitalProducts')->findOrFail($id);

            // Statistik Produk
            $totalProducts    = $user->digitalProducts()->count();
            $activeProducts   = $user->digitalProducts()->where('is_active', true)->where('verification_status', 'approved')->count();
            $pendingProducts  = $user->digitalProducts()->where('verification_status', 'pending')->count();
            $rejectedProducts = $user->digitalProducts()->where('verification_status', 'rejected')->count();
            $takedownProducts = $user->digitalProducts()->where('is_active', false)->count();

            // Statistik Kunjungan Microsite
            $totalViews  = DB::table('link_views')->where('link_id', $user->username)->count();
            $totalClicks = DB::table('link_clicks')->where('link_id', $user->username)->count();

            // Finansial & Transaksi
            $totalTurnover = (float) DB::table('transactions')
                ->join('digital_products', 'transactions.product_id', '=', 'digital_products.id')
                ->where('digital_products.user_id', $user->id)
                ->where('transactions.status', 'success')
                ->sum('transactions.total_price');

            $totalOrders = (int) DB::table('transactions')
                ->join('digital_products', 'transactions.product_id', '=', 'digital_products.id')
                ->where('digital_products.user_id', $user->id)
                ->where('transactions.status', 'success')
                ->count();

            $totalWithdrawn = (float) DB::table('payout_transactions')
                ->where('user_id', $user->id)
                ->where('status', 'approved')
                ->sum('amount');

            $pendingWithdraw = (float) DB::table('payout_transactions')
                ->where('user_id', $user->id)
                ->where('status', 'pending')
                ->sum('amount');

            $currentBalance = (float) ($user->balance ?? 0);

            // Produk Terkini (5)
            $recentProducts = $user->digitalProducts()
                ->latest()
                ->take(5)
                ->get()
                ->map(function ($p) {
                    return [
                        'id'                  => $p->id,
                        'title'               => $p->title,
                        'price'               => (float) $p->price,
                        'sale_price'          => $p->sale_price ? (float) $p->sale_price : null,
                        'image'               => $p->image ? asset('storage/' . $p->image) : null,
                        'platform_type'       => $p->platform_type,
                        'verification_status' => $p->verification_status,
                        'is_active'           => (bool) $p->is_active,
                        'created_at'          => $p->created_at ? $p->created_at->format('d M Y') : '-',
                    ];
                });

            // Riwayat Payout Terkini (5)
            $recentPayouts = DB::table('payout_transactions')
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            // Riwayat Permohonan Banding (semua)
            $appealsHistory = \App\Models\SuspensionAppeal::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($a, $index) use ($user) {
                    return [
                        'id'            => $a->id,
                        'attempt'       => $index + 1,
                        'appeal_reason' => $a->appeal_reason,
                        'status'        => $a->status,
                        'admin_notes'   => $a->admin_notes,
                        'submitted_at'  => $a->created_at->format('d M Y, H:i'),
                        'resolved_at'   => $a->resolved_at ? $a->resolved_at->format('d M Y, H:i') : null,
                    ];
                })
                ->values();

            $avatarUrl = null;
            if ($user->avatar) {
                $avatarUrl = \Illuminate\Support\Str::startsWith($user->avatar, ['http://', 'https://'])
                    ? $user->avatar
                    : asset('storage/' . $user->avatar);
            }

            return response()->json([
                'status' => 'success',
                'user' => [
                    'id'           => $user->id,
                    'name'         => $user->name,
                    'email'        => \App\Models\ActivityLog::maskEmail($user->email),
                    'username'     => $user->username,
                    'role'         => $user->role,
                    'is_suspended' => $user->isSuspended(),
                    'avatar'       => $avatarUrl,
                    'joined_at'    => $user->created_at ? $user->created_at->format('d M Y, H:i') : '-',
                    'microsite_url'=> url('/linkan.id/' . ($user->username ?? $user->id)),
                ],
                'stats' => [
                    'total_products'    => $totalProducts,
                    'active_products'   => $activeProducts,
                    'pending_products'  => $pendingProducts,
                    'rejected_products' => $rejectedProducts,
                    'takedown_products' => $takedownProducts,
                    'total_views'       => $totalViews,
                    'total_clicks'      => $totalClicks,
                    'total_turnover'    => $totalTurnover,
                    'total_orders'      => $totalOrders,
                    'total_withdrawn'   => $totalWithdrawn,
                    'pending_withdraw'  => $pendingWithdraw,
                    'current_balance'   => $currentBalance,
                ],
                'recent_products'  => $recentProducts,
                'recent_payouts'   => $recentPayouts,
                'appeals_history'  => $appealsHistory,
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error loading seller detail: ' . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}

