<?php

namespace App\Http\Controllers;

use App\Mail\SupportTicketCreatedMail;
use App\Models\SupportTicket;
use App\Models\SupportTicketReply;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SupportTicketController extends Controller
{
    /**
     * Menampilkan daftar tiket bantuan milik seller yang sedang login.
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $status = $request->query('status');
        $search = $request->query('search');

        $query = SupportTicket::where('user_id', $userId);

        if ($status && in_array($status, ['open', 'in_progress', 'resolved', 'closed'])) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('ticket_code', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $tickets = $query->latest()->paginate(10)->withQueryString();

        // Hitung statistik tiket seller
        $totalTickets = SupportTicket::where('user_id', $userId)->count();
        $openTickets = SupportTicket::where('user_id', $userId)->where('status', 'open')->count();
        $inProgressTickets = SupportTicket::where('user_id', $userId)->where('status', 'in_progress')->count();
        $resolvedTickets = SupportTicket::where('user_id', $userId)->where('status', 'resolved')->count();

        // Cek apakah ada tiket aktif (open atau in_progress)
        $activeTicket = SupportTicket::where('user_id', $userId)
            ->whereIn('status', ['open', 'in_progress'])
            ->latest()
            ->first();

        return view('admin_seller.features.tickets.index', compact(
            'tickets',
            'totalTickets',
            'openTickets',
            'inProgressTickets',
            'resolvedTickets',
            'activeTicket',
            'status',
            'search'
        ));
    }

    /**
     * Menyimpan tiket bantuan baru yang dibuat seller.
     */
    public function store(Request $request)
    {
        // Rate Limiter: Pengguna yang masih memiliki tiket aktif tidak dapat membuat tiket baru
        $activeTicket = SupportTicket::where('user_id', Auth::id())
            ->whereIn('status', ['open', 'in_progress'])
            ->latest()
            ->first();

        if ($activeTicket) {
            return redirect()->route('admin.tickets.index')
                ->with('error', "Anda masih memiliki tiket bantuan yang sedang aktif (#{$activeTicket->ticket_code} - {$activeTicket->subject}). Harap tunggu hingga tiket tersebut diselesaikan atau ditutup oleh admin sebelum mengajukan tiket baru.")
                ->withInput();
        }

        $request->validate([
            'category'   => 'required|in:payout,product,account,general',
            'subject'    => 'required|string|max:200',
            'message'    => 'required|string|max:3000',
            'attachment' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'category.required'   => 'Pilih kategori kendala.',
            'subject.required'    => 'Subjek kendala wajib diisi.',
            'subject.max'         => 'Subjek kendala maksimal 200 karakter.',
            'message.required'    => 'Rincian pesan kendala wajib diisi.',
            'message.max'         => 'Rincian pesan kendala maksimal 3000 karakter.',
            'attachment.image'    => 'Lampiran harus berupa gambar.',
            'attachment.max'      => 'Ukuran gambar lampiran maksimal 2MB.',
        ]);

        // Generate unique ticket code: TKT-YYYYMM-XXXX
        $datePrefix = date('Ym');
        $randomSuffix = strtoupper(Str::random(4));
        $ticketCode = "TKT-{$datePrefix}-{$randomSuffix}";

        // Simpan attachment jika ada
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('support_attachments', 'public');
        }

        $ticket = SupportTicket::create([
            'ticket_code'     => $ticketCode,
            'user_id'         => Auth::id(),
            'category'        => $request->category,
            'subject'         => $request->subject,
            'message'         => $request->message,
            'status'          => 'open',
            'priority'        => 'medium',
            'last_replied_at' => now(),
        ]);

        // Jika ada lampiran di pesan awal, buat reply pertama sebagai catatan lampiran
        if ($attachmentPath) {
            SupportTicketReply::create([
                'support_ticket_id' => $ticket->id,
                'user_id'           => Auth::id(),
                'is_admin_reply'    => false,
                'message'           => 'Lampiran Bukti Kendala Terlampir.',
                'attachment'        => $attachmentPath,
            ]);
        }

        // Catat ke log aktivitas
        ActivityLogger::log(
            'create_support_ticket',
            "Membuat tiket bantuan baru: #{$ticketCode} ({$ticket->subject})",
            [
                'ticket_id'   => $ticket->id,
                'ticket_code' => $ticketCode,
                'category'    => $ticket->category,
            ]
        );

        // Kirim email konfirmasi ke seller via SMTP
        try {
            if (Auth::user()->email) {
                Mail::to(Auth::user()->email)->send(new SupportTicketCreatedMail($ticket));
            }
        } catch (\Exception $e) {
            \Log::error('Support Ticket Mail error: ' . $e->getMessage());
        }

        return redirect()->route('admin.tickets.show', $ticket->id)
            ->with('success', "Tiket bantuan #{$ticketCode} berhasil diajukan! Tim admin kami akan segera meninjaunya.");
    }

    /**
     * Menampilkan halaman detail thread tiket bantuan.
     */
    public function show($id)
    {
        $ticket = SupportTicket::with(['user', 'replies.user'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('admin_seller.features.tickets.show', compact('ticket'));
    }

    /**
     * Mengirimkan balasan lanjutan dari seller pada thread tiket.
     */
    public function reply(Request $request, $id)
    {
        $ticket = SupportTicket::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'message'    => 'required|string|max:3000',
            'attachment' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'message.required' => 'Pesan balasan tidak boleh kosong.',
            'message.max'      => 'Pesan balasan maksimal 3000 karakter.',
            'attachment.image' => 'Lampiran harus berupa file gambar.',
            'attachment.max'   => 'Ukuran lampiran maksimal 2MB.',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('support_attachments', 'public');
        }

        SupportTicketReply::create([
            'support_ticket_id' => $ticket->id,
            'user_id'           => Auth::id(),
            'is_admin_reply'    => false,
            'message'           => $request->message,
            'attachment'        => $attachmentPath,
        ]);

        // Update status & waktu balasan tiket
        if (in_array($ticket->status, ['resolved', 'closed'])) {
            $ticket->status = 'open'; // Re-open jika seller membalas kembali
        }
        $ticket->last_replied_at = now();
        $ticket->save();

        // Catat ke log aktivitas
        ActivityLogger::log(
            'reply_support_ticket',
            "Mengirim balasan pada tiket bantuan: #{$ticket->ticket_code}",
            [
                'ticket_id'   => $ticket->id,
                'ticket_code' => $ticket->ticket_code,
            ]
        );

        return back()->with('success', 'Balasan Anda berhasil dikirim ke tim support.');
    }
}
