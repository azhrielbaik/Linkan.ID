<?php

namespace App\Http\Controllers\AdminSeller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\DigitalProduct;
use App\Models\User;
use App\Services\AdminSeller\DashboardService;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Tampilkan halaman utama dashboard (Beranda).
     */
    public function index()
    {
        $user = Auth::user();

        // Dapatkan semua statistik dari Service
        $data = $this->dashboardService->getDashboardStats($user);

        // Render view baru (nanti akan dibuat di resources/views/admin_seller/features/dashboard/index.blade.php)
        return view('admin_seller.features.dashboard.index', $data);
    }

    /**
     * Mengajukan surat banding suspensi dari seller ke Admin Platform.
     */
    public function submitAppeal(Request $request)
    {
        $user = Auth::user();

        if (!$user->isSuspended()) {
            return back()->with('info', 'Akun Anda saat ini dalam status aktif dan tidak ditangguhkan.');
        }

        $totalAppeals = \App\Models\SuspensionAppeal::where('user_id', $user->id)->count();
        if ($totalAppeals >= 3) {
            return back()->with('error', 'Anda telah mencapai batas maksimum pengajuan banding (3 kali). Pengajuan banding baru tidak dapat dilakukan lagi.');
        }

        $pendingAppeal = \App\Models\SuspensionAppeal::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if ($pendingAppeal) {
            return back()->with('error', 'Anda sudah memiliki permohonan banding yang sedang dalam proses peninjauan oleh Admin Platform.');
        }

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

    /**
     * Get Chart Data for Dashboard API
     */
    public function getChartData(Request $request)
    {
        $user = Auth::user();

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        try {
            $startDate = $startDate ? Carbon::parse($startDate) : Carbon::now()->subDays(6);
            $endDate = $endDate ? Carbon::parse($endDate) : Carbon::now();
        } catch (\Exception $e) {
            $startDate = Carbon::now()->subDays(6);
            $endDate = Carbon::now();
        }

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

    /**
     * Get Digital Products API
     */
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
    // Catatan: Idealnya fungsi ini dipindah ke PublicController, namun dipertahankan di sini untuk kompatibilitas rute sementara.
    public function trackClick(Request $request)
    {
        $linkId = $request->query('link_id');
        $target = $request->query('target');

        if (!filter_var($target, FILTER_VALIDATE_URL)) {
            abort(400, 'Invalid target URL');
        }

        $user = User::where('username', $linkId)->first();
        if (!$user) {
            abort(404, 'User not found');
        }

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



    public function markNotificationRead(Request $request)
    {
        $data = $request->validate(['notification_key' => 'required|string|max:100']);
        $this->dashboardService->markNotificationRead($request->user(), $data['notification_key']);

        return response()->json(['status' => 'success']);
    }

    public function markAllNotificationsRead(Request $request)
    {
        $this->dashboardService->markAllNotificationsRead($request->user());

        return response()->json(['status' => 'success']);
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
        if ($request->hasSession()) {
            $request->session()->save();
        }

        return response()->stream(function () use ($user) {
            @set_time_limit(0);
            @ini_set('implicit_flush', 1);
            if (ob_get_level()) {
                @ob_end_flush();
            }
            flush();

            $data = $this->dashboardService->fetchSellerNotificationsData($user);
            
            // Mengirimkan retry interval ke browser (misal: 3000ms)
            echo "retry: 3000\n";
            echo "event: notifications\n";
            echo "data: " . json_encode($data) . "\n\n";
            flush();
        }, 200, [
            'Content-Type'      => 'text/event-stream',
            'Cache-Control'     => 'no-cache, no-store, must-revalidate',
            'Connection'        => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
    }
}
