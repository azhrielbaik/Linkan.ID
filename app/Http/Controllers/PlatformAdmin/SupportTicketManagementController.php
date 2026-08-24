<?php

namespace App\Http\Controllers\PlatformAdmin;

use App\Http\Controllers\Controller;
use App\Mail\SupportTicketReplyMail;
use App\Models\SupportTicket;
use App\Models\SupportTicketReply;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class SupportTicketManagementController extends Controller
{
    /**
     * Menampilkan daftar semua tiket bantuan dari seluruh seller.
     */
    public function index(Request $request)
    {
        $status = $request->query('status');
        $priority = $request->query('priority');
        $category = $request->query('category');
        $search = $request->query('search');

        $query = SupportTicket::with(['user', 'replies']);

        if ($status && in_array($status, ['open', 'in_progress', 'resolved', 'closed'])) {
            $query->where('status', $status);
        }

        if ($priority && in_array($priority, ['low', 'medium', 'high', 'urgent'])) {
            $query->where('priority', $priority);
        }

        if ($category && in_array($category, ['payout', 'product', 'account', 'general'])) {
            $query->where('category', $category);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('ticket_code', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $tickets = $query->latest('last_replied_at')->paginate(15)->withQueryString();

        // Hitung statistik tiket global
        $totalCount = SupportTicket::count();
        $openCount = SupportTicket::where('status', 'open')->count();
        $inProgressCount = SupportTicket::where('status', 'in_progress')->count();
        $resolvedCount = SupportTicket::where('status', 'resolved')->count();
        $closedCount = SupportTicket::where('status', 'closed')->count();

        return view('platformadmin.tickets.index', compact(
            'tickets',
            'totalCount',
            'openCount',
            'inProgressCount',
            'resolvedCount',
            'closedCount',
            'status',
            'priority',
            'category',
            'search'
        ));
    }

    /**
     * Menampilkan detail tiket dan thread percakapan.
     */
    public function show($id)
    {
        $ticket = SupportTicket::with(['user', 'replies.user'])->findOrFail($id);

        return view('platformadmin.tickets.show', compact('ticket'));
    }

    /**
     * Admin mengirim balasan pada tiket seller dan mengirimkan notifikasi email via SMTP.
     */
    public function reply(Request $request, $id)
    {
        $ticket = SupportTicket::with('user')->findOrFail($id);

        $request->validate([
            'message'    => 'required|string|max:4000',
            'status'     => 'nullable|in:open,in_progress,resolved,closed',
            'attachment' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'message.required' => 'Pesan balasan admin wajib diisi.',
            'message.max'      => 'Pesan balasan maksimal 4000 karakter.',
            'attachment.image' => 'Lampiran harus berupa file gambar.',
            'attachment.max'   => 'Ukuran lampiran maksimal 2MB.',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('support_attachments', 'public');
        }

        $reply = SupportTicketReply::create([
            'support_ticket_id' => $ticket->id,
            'user_id'           => Auth::id(),
            'is_admin_reply'    => true,
            'message'           => $request->message,
            'attachment'        => $attachmentPath,
        ]);

        // Update status tiket
        if ($request->filled('status')) {
            $ticket->status = $request->status;
        } elseif ($ticket->status === 'open') {
            $ticket->status = 'in_progress';
        }

        $ticket->last_replied_at = now();
        $ticket->save();

        // Catat ke Log Aktivitas Platform
        ActivityLogger::log(
            'admin_reply_ticket',
            "Membalas tiket bantuan #{$ticket->ticket_code} (Seller: {$ticket->user->name}, Status: {$ticket->status_label})",
            [
                'ticket_id'    => $ticket->id,
                'ticket_code'  => $ticket->ticket_code,
                'seller_email' => $ticket->user->email ?? null,
                'new_status'   => $ticket->status,
            ]
        );

        // Kirim email notifikasi balasan ke seller via SMTP
        try {
            if ($ticket->user && $ticket->user->email) {
                Mail::to($ticket->user->email)->send(new SupportTicketReplyMail($ticket, $reply));
            }
        } catch (\Exception $e) {
            \Log::error('Support Ticket Reply Mail error: ' . $e->getMessage());
        }

        return back()->with('success', 'Balasan resmi berhasil dikirim ke seller dan notifikasi email telah diteruskan.');
    }

    /**
     * Memperbarui status dan prioritas tiket bantuan.
     */
    public function updateStatus(Request $request, $id)
    {
        $ticket = SupportTicket::with('user')->findOrFail($id);

        $request->validate([
            'status'   => 'required|in:open,in_progress,resolved,closed',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        $oldStatus = $ticket->status;
        $oldPriority = $ticket->priority;

        $ticket->status = $request->status;
        $ticket->priority = $request->priority;
        $ticket->save();

        // Catat ke Log Aktivitas
        ActivityLogger::log(
            'update_ticket_status',
            "Mengubah status tiket #{$ticket->ticket_code}: [{$oldStatus} -> {$ticket->status}], Prioritas: [{$oldPriority} -> {$ticket->priority}]",
            [
                'ticket_id'   => $ticket->id,
                'ticket_code' => $ticket->ticket_code,
                'status'      => $ticket->status,
                'priority'    => $ticket->priority,
            ]
        );

        return back()->with('success', "Status dan prioritas tiket #{$ticket->ticket_code} berhasil diperbarui.");
    }
}
