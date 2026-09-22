<?php

namespace App\Http\Controllers\PlatformAdmin;

use App\Http\Controllers\Controller;
use App\Models\BroadcastAnnouncement;
use App\Models\PlatformSetting;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PlatformAdmin\UpdateSettingsRequest;

use Illuminate\Support\Facades\Hash;

use App\Models\User;

class SettingController extends Controller
{
    /**
     * Menampilkan halaman Pengaturan Platform.
     */
    public function index()
    {
        $commissionPercent = (float) PlatformSetting::get('platform_commission_percent', 5);
        $minWithdrawAmount = (float) PlatformSetting::get('min_withdraw_amount', 10000);

        $freezePayouts = (bool) PlatformSetting::get('freeze_payouts', 0);
        $freezePayoutsMessage = PlatformSetting::get('freeze_payouts_message', '');
        $disableCheckout = (bool) PlatformSetting::get('disable_checkout', 0);
        $disableCheckoutMessage = PlatformSetting::get('disable_checkout_message', '');

        $announcements = BroadcastAnnouncement::with('admin')->latest()->get();

        return view('platformadmin.settings.index', compact(
            'commissionPercent',
            'minWithdrawAmount',
            'freezePayouts',
            'freezePayoutsMessage',
            'disableCheckout',
            'disableCheckoutMessage',
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

        DB::transaction(function () use ($request, $oldCommission, $oldMinWithdraw) {
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
        });

        return back()->with('success', __('messages.financial_settings_updated'));
    }

    /**
     * Menyimpan perubahan sakelar darurat (Emergency Switches / Maintenance Mode Parsial)
     * dengan verifikasi kata sandi admin.
     */
    public function updateEmergencySwitches(Request $request)
    {
        $request->validate([
            'admin_password'           => 'required|string',
            'freeze_payouts'           => 'nullable',
            'freeze_payouts_message'   => 'nullable|string|max:255',
            'disable_checkout'         => 'nullable',
            'disable_checkout_message' => 'nullable|string|max:255',
        ], [
            'admin_password.required' => 'Password admin wajib dimasukkan untuk mengonfirmasi perubahan sakelar darurat.',
        ]);

        if (!Hash::check($request->input('admin_password'), Auth::user()->password)) {
            return back()->withInput()->with('error', __('platform.invalid_admin_password'));
        }

        $newFreezePayouts = $request->boolean('freeze_payouts') ? '1' : '0';
        $newFreezeMsg = trim($request->input('freeze_payouts_message', ''));
        $newDisableCheckout = $request->boolean('disable_checkout') ? '1' : '0';
        $newCheckoutMsg = trim($request->input('disable_checkout_message', ''));

        $oldFreeze = (string) PlatformSetting::get('freeze_payouts', '0');
        $oldCheckout = (string) PlatformSetting::get('disable_checkout', '0');

        DB::transaction(function () use ($newFreezePayouts, $newFreezeMsg, $newDisableCheckout, $newCheckoutMsg, $oldFreeze, $oldCheckout) {
            PlatformSetting::set('freeze_payouts', $newFreezePayouts, 'Status pembekuan penarikan dana');
            PlatformSetting::set('freeze_payouts_message', $newFreezeMsg, 'Pesan kustom pembekuan penarikan dana');
            PlatformSetting::set('disable_checkout', $newDisableCheckout, 'Status penonaktifan checkout produk');
            PlatformSetting::set('disable_checkout_message', $newCheckoutMsg, 'Pesan kustom penonaktifan checkout');

            $logDetails = [];
            $logActions = [];

            if ($oldFreeze !== $newFreezePayouts) {
                $statusStr = $newFreezePayouts === '1' ? 'DIAKTIFKAN (Dibekukan)' : 'DINONAKTIFKAN (Normal)';
                $logActions[] = "Freeze Payouts {$statusStr}";
                $logDetails['freeze_payouts'] = [
                    'from' => $oldFreeze,
                    'to'   => $newFreezePayouts,
                    'msg'  => $newFreezeMsg
                ];
            }

            if ($oldCheckout !== $newDisableCheckout) {
                $statusStr = $newDisableCheckout === '1' ? 'DIAKTIFKAN (Ditutup)' : 'DINONAKTIFKAN (Normal)';
                $logActions[] = "Disable Checkout {$statusStr}";
                $logDetails['disable_checkout'] = [
                    'from' => $oldCheckout,
                    'to'   => $newDisableCheckout,
                    'msg'  => $newCheckoutMsg
                ];
            }

            $actionSummary = !empty($logActions) ? implode(', ', $logActions) : 'Pembaruan pesan/konfigurasi sakelar darurat';

            ActivityLogger::log(
                'update_emergency_switches',
                "Mengubah status sakelar darurat platform: {$actionSummary}",
                $logDetails
            );
        });

        return back()->with('success', 'Pengaturan sakelar darurat (Maintenance Mode Parsial) berhasil diperbarui.');
    }

    /**
     * Membuat dan menyiarkan pengumuman baru ke semua seller (opsional via email massal).
     * Email dikirim secara asinkron via queued job agar tidak timeout.
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

        $recipientCount = 0;

        // Jika opsi email dicentang, delegasikan proses chunking dan pengiriman ke master background job
        if ($shouldSendEmail) {
            $recipientCount = User::where('role', '!=', 'admin_platform')
                ->whereNotNull('email')
                ->count();

            \App\Jobs\DispatchBroadcastEmailsJob::dispatch($announcement);
        }

        // Catat ke Log Aktivitas
        ActivityLogger::log(
            'create_broadcast',
            "Membuat broadcast pengumuman: {$announcement->title} (Tipe: {$announcement->type})" . ($shouldSendEmail ? " [Email dijadwalkan: {$recipientCount} penerima]" : ""),
            [
                'announcement_id'   => $announcement->id,
                'title'             => $announcement->title,
                'type'              => $announcement->type,
                'send_email'        => $shouldSendEmail,
                'queued_recipients' => $recipientCount,
            ]
        );

        $msg = __('messages.broadcast_success');
        if ($shouldSendEmail) {
            $msg .= " " . __('messages.broadcast_email_sent', ['count' => $recipientCount]);
        }

        return back()->with('success', $msg);
    }

    /**
     * Toggle status aktif/nonaktif pengumuman.
     */
    public function toggleBroadcast($id)
    {
        $announcement = BroadcastAnnouncement::findOrFail($id);
        $statusText = $announcement->is_active ? 'dinonaktifkan' : 'diaktifkan';

        DB::transaction(function () use ($announcement, $statusText) {
            $announcement->is_active = !$announcement->is_active;
            $announcement->save();

            // Catat ke Log Aktivitas
            ActivityLogger::log(
                'toggle_broadcast',
                "Mengubah status broadcast pengumuman: {$announcement->title} ({$statusText})",
                [
                    'announcement_id' => $announcement->id,
                    'is_active' => $announcement->is_active,
                ]
            );
        });

        return back()->with('success', "Status broadcast pengumuman berhasil {$statusText}.");
    }

    /**
     * Menghapus pengumuman.
     */
    public function deleteBroadcast($id)
    {
        $announcement = BroadcastAnnouncement::findOrFail($id);
        $title = $announcement->title;
        DB::transaction(function () use ($announcement, $title) {
            $announcement->delete();

            // Catat ke Log Aktivitas
            ActivityLogger::log(
                'delete_broadcast',
                "Menghapus broadcast pengumuman: {$title}",
                ['title' => $title]
            );
        });

        return back()->with('success', "Broadcast pengumuman \"{$title}\" berhasil dihapus.");

    }
}
