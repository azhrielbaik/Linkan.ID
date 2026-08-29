<?php

namespace App\Http\Controllers\PlatformAdmin;

use App\Http\Controllers\Controller;
use App\Models\BroadcastAnnouncement;
use App\Models\PlatformSetting;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PlatformAdmin\UpdateSettingsRequest;

use Illuminate\Support\Facades\Hash;

use App\Mail\BroadcastAnnouncementMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

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

        return back()->with('success', __('messages.financial_settings_updated'));
    }

    /**
     * Membuat dan menyiarkan pengumuman baru ke semua seller (opsional via email massal).
     */
    public function storeBroadcast(Request $request)
    {
        $request->validate([
            'title'      => 'required|string|max:255',
            'message'    => 'required|string|max:2000',
            'type'       => 'required|in:info,warning,success,danger',
            'send_email' => 'nullable|boolean',
        ], [
            'title.required'   => 'Judul pengumuman wajib diisi.',
            'message.required' => 'Isi pesan pengumuman wajib diisi.',
            'type.required'    => 'Tipe pengumuman wajib dipilih.',
        ]);

        $shouldSendEmail = $request->boolean('send_email');

        $announcement = BroadcastAnnouncement::create([
            'admin_id'           => Auth::id(),
            'title'              => $request->input('title'),
            'message'            => $request->input('message'),
            'type'               => $request->input('type'),
            'target_role'        => 'all_sellers',
            'is_active'          => true,
            'send_email'         => $shouldSendEmail,
            'emails_sent_count'  => 0,
            'email_sent_at'      => $shouldSendEmail ? now() : null,
        ]);

        $sentCount = 0;

        // Jika opsi email dicentang, kirim email ke seluruh seller aktif
        if ($shouldSendEmail) {
            $sellers = User::where('role', '!=', 'admin_platform')
                ->whereNotNull('email')
                ->get();

            foreach ($sellers as $seller) {
                try {
                    Mail::to($seller->email)->send(new BroadcastAnnouncementMail($announcement, $seller));
                    $sentCount++;
                } catch (\Exception $e) {
                    \Log::error("Failed to send broadcast email to {$seller->email}: " . $e->getMessage());
                }
            }

            $announcement->update([
                'emails_sent_count' => $sentCount,
            ]);
        }

        // Catat ke Log Aktivitas
        ActivityLogger::log(
            'create_broadcast',
            "Membuat broadcast pengumuman: {$announcement->title} (Tipe: {$announcement->type})" . ($shouldSendEmail ? " [Email Terkirim: {$sentCount}]" : ""),
            [
                'announcement_id'   => $announcement->id,
                'title'             => $announcement->title,
                'type'              => $announcement->type,
                'send_email'        => $shouldSendEmail,
                'emails_sent_count' => $sentCount,
            ]
        );

        $msg = __('messages.broadcast_success');
        if ($shouldSendEmail) {
            $msg .= " " . __('messages.broadcast_email_sent', ['count' => $sentCount]);
        }

        return back()->with('success', $msg);
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

        return back()->with('success', __('messages.ticket_reply_sent'));
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

        return back()->with('success', __('messages.product_takedown', ['title' => $title]));

    }
}
