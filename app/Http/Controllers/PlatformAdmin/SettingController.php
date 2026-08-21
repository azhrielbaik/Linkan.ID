<?php

namespace App\Http\Controllers\PlatformAdmin;

use App\Http\Controllers\Controller;
use App\Models\BroadcastAnnouncement;
use App\Models\PlatformSetting;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
{
    /**
     * Menampilkan halaman Pengaturan Platform.
     */
    public function index()
    {
        $commissionPercent = (float) PlatformSetting::get('platform_commission_percent', 5);
        $minWithdrawAmount = (float) PlatformSetting::get('min_withdraw_amount', 10000);

        $announcements = BroadcastAnnouncement::with('admin')->latest()->get();

        return view('platformadmin.settings.index', compact(
            'commissionPercent',
            'minWithdrawAmount',
            'announcements'
        ));
    }

    /**
     * Menyimpan perubahan pengaturan komisi dan batas withdraw dengan verifikasi password admin.
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'commission_percent' => 'required|numeric|min:0|max:100',
            'min_withdraw'       => 'required|numeric|min:0',
            'admin_password'     => 'required|string',
        ], [
            'commission_percent.required' => 'Persentase komisi wajib diisi.',
            'commission_percent.numeric'  => 'Persentase komisi harus berupa angka.',
            'commission_percent.min'      => 'Persentase komisi minimal 0%.',
            'commission_percent.max'      => 'Persentase komisi maksimal 100%.',
            'min_withdraw.required'       => 'Batas minimum penarikan wajib diisi.',
            'min_withdraw.numeric'        => 'Batas minimum penarikan harus berupa angka.',
            'min_withdraw.min'            => 'Batas minimum penarikan minimal Rp 0.',
            'admin_password.required'     => 'Password admin wajib dimasukkan untuk konfirmasi keamanan.',
        ]);

        // Verifikasi password admin yang sedang aktif
        if (!Hash::check($request->input('admin_password'), Auth::user()->password)) {
            return back()->withInput()->with('error', __('platform.invalid_admin_password'));
        }

        $oldCommission = PlatformSetting::get('platform_commission_percent', 5);
        $oldMinWithdraw = PlatformSetting::get('min_withdraw_amount', 10000);

        PlatformSetting::set('platform_commission_percent', $request->input('commission_percent'));
        PlatformSetting::set('min_withdraw_amount', $request->input('min_withdraw'));

        // Catat ke Log Aktivitas
        ActivityLogger::log(
            'update_settings',
            "Mengubah pengaturan platform: Komisi ({$oldCommission}% -> {$request->commission_percent}%), Min Withdraw (Rp " . number_format($oldMinWithdraw, 0, ',', '.') . " -> Rp " . number_format($request->min_withdraw, 0, ',', '.') . ")",
            [
                'old_commission' => $oldCommission,
                'new_commission' => $request->commission_percent,
                'old_min_withdraw' => $oldMinWithdraw,
                'new_min_withdraw' => $request->min_withdraw,
            ]
        );

        return back()->with('success', __('platform.financial_settings_updated'));
    }

    /**
     * Membuat dan menyiarkan pengumuman baru ke semua seller.
     */
    public function storeBroadcast(Request $request)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'message' => 'required|string|max:2000',
            'type'    => 'required|in:info,warning,success,danger',
        ], [
            'title.required'   => 'Judul pengumuman wajib diisi.',
            'message.required' => 'Isi pesan pengumuman wajib diisi.',
            'type.required'    => 'Tipe pengumuman wajib dipilih.',
        ]);

        $announcement = BroadcastAnnouncement::create([
            'admin_id'    => Auth::id(),
            'title'       => $request->input('title'),
            'message'     => $request->input('message'),
            'type'        => $request->input('type'),
            'target_role' => 'all_sellers',
            'is_active'   => true,
        ]);

        // Catat ke Log Aktivitas
        ActivityLogger::log(
            'create_broadcast',
            "Membuat broadcast pengumuman: {$announcement->title} (Tipe: {$announcement->type})",
            [
                'announcement_id' => $announcement->id,
                'title' => $announcement->title,
                'type' => $announcement->type,
            ]
        );

        return back()->with('success', 'Broadcast pengumuman berhasil dikirim ke semua seller.');
    }

    /**
     * Toggle status aktif/nonaktif pengumuman.
     */
    public function toggleBroadcast($id)
    {
        $announcement = BroadcastAnnouncement::findOrFail($id);
        $announcement->is_active = !$announcement->is_active;
        $announcement->save();

        $statusText = $announcement->is_active ? 'diaktifkan' : 'dinonaktifkan';

        // Catat ke Log Aktivitas
        ActivityLogger::log(
            'toggle_broadcast',
            "Mengubah status broadcast pengumuman: {$announcement->title} ({$statusText})",
            [
                'announcement_id' => $announcement->id,
                'is_active' => $announcement->is_active,
            ]
        );

        return back()->with('success', "Pengumuman berhasil {$statusText}.");
    }

    /**
     * Menghapus pengumuman.
     */
    public function deleteBroadcast($id)
    {
        $announcement = BroadcastAnnouncement::findOrFail($id);
        $title = $announcement->title;
        $announcement->delete();

        // Catat ke Log Aktivitas
        ActivityLogger::log(
            'delete_broadcast',
            "Menghapus broadcast pengumuman: {$title}",
            ['title' => $title]
        );

        return back()->with('success', 'Pengumuman berhasil dihapus.');
    }
}
