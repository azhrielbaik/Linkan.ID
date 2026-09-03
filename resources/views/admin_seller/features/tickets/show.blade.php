@extends('admin_seller.layouts.app')

@section('title', 'Thread Tiket #' . $ticket->ticket_code . ' — Linkan.ID')
@section('page_title', 'Detail Tiket Bantuan')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/seller-tickets.css') }}?v={{ time() }}">
@endpush

@section('content')
<div class="tickets-container">

    {{-- Alerts --}}
    @if(session('success'))
        <div style="background-color: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 14px 18px; border-radius: 12px; margin-bottom: 20px; font-size: 13px; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-check-circle" style="font-size: 16px;"></i> {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div style="background-color: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 14px 18px; border-radius: 12px; margin-bottom: 20px; font-size: 13px;">
            <ul style="margin: 0; padding-left: 18px;">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Back Button & Header -->
    <div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
        <a href="{{ route('admin.tickets.index') }}" style="display: inline-flex; align-items: center; gap: 8px; color: #64748b; text-decoration: none; font-size: 13px; font-weight: 600; background: #ffffff; border: 1px solid #e2e8f0; padding: 8px 14px; border-radius: 8px; transition: all 0.2s;">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Tiket
        </a>
        <div style="display: flex; gap: 8px; align-items: center;">
            <span class="ticket-code-badge" style="font-size: 13px;">#{{ $ticket->ticket_code }}</span>
            <span class="badge-status {{ $ticket->status_badge_class }}">
                {{ $ticket->status_label }}
            </span>
        </div>
    </div>

    <div class="ticket-thread-wrapper">
        
        <!-- Left Column: Chat Conversation Thread -->
        <div class="ticket-chat-card">
            
            <div style="border-bottom: 1px solid #f1f5f9; padding-bottom: 16px; margin-bottom: 20px;">
                <h2 style="font-size: 18px; font-weight: 800; color: #0f172a; margin: 0 0 6px 0;">
                    {{ $ticket->subject }}
                </h2>
                <div style="font-size: 12px; color: #64748b;">
                    Dibuat pada: <strong>{{ $ticket->created_at->format('d M Y, H:i') }} WIB</strong> • Kategori: <strong style="color: #DE6C20;">{{ $ticket->category_label }}</strong>
                </div>
            </div>

            <!-- Original Message Bubble -->
            <div class="ticket-message-bubble bubble-user">
                <div class="bubble-header">
                    <div class="bubble-author">
                        <i class="fas fa-user-circle" style="color: #64748b;"></i> Anda (Pembuat Tiket)
                    </div>
                    <div class="bubble-time">
                        {{ $ticket->created_at->format('d M Y, H:i') }}
                    </div>
                </div>
                <div class="bubble-body">
                    {!! nl2br(e($ticket->message)) !!}
                </div>
            </div>

            <!-- Replies Stream -->
            @foreach($ticket->replies as $r)
                @if($r->is_admin_reply)
                    <div class="ticket-message-bubble bubble-admin">
                        <div class="bubble-header">
                            <div class="bubble-author">
                                <i class="fas fa-shield-alt" style="color: #DE6C20;"></i> Tim Support Platform Admin
                            </div>
                            <div class="bubble-time">
                                {{ $r->created_at->format('d M Y, H:i') }}
                            </div>
                        </div>
                        <div class="bubble-body">
                            {!! nl2br(e($r->message)) !!}
                        </div>
                        @if($r->attachment)
                            <div class="bubble-attachment">
                                <div style="font-size: 11px; font-weight: 700; color: #64748b; margin-bottom: 6px;">Lampiran:</div>
                                <a href="{{ asset('storage/' . $r->attachment) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $r->attachment) }}" alt="Lampiran">
                                </a>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="ticket-message-bubble bubble-user">
                        <div class="bubble-header">
                            <div class="bubble-author">
                                <i class="fas fa-user-circle" style="color: #64748b;"></i> Anda
                            </div>
                            <div class="bubble-time">
                                {{ $r->created_at->format('d M Y, H:i') }}
                            </div>
                        </div>
                        <div class="bubble-body">
                            {!! nl2br(e($r->message)) !!}
                        </div>
                        @if($r->attachment)
                            <div class="bubble-attachment">
                                <div style="font-size: 11px; font-weight: 700; color: #64748b; margin-bottom: 6px;">Lampiran:</div>
                                <a href="{{ asset('storage/' . $r->attachment) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $r->attachment) }}" alt="Lampiran">
                                </a>
                            </div>
                        @endif
                    </div>
                @endif
            @endforeach

            <!-- Reply Box -->
            @if($ticket->status !== 'closed')
                <div class="ticket-reply-box">
                    <form action="{{ route('admin.tickets.reply', $ticket->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div style="font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 8px;">
                            <i class="fas fa-reply" style="color: #DE6C20;"></i> Kirim Balasan / Informasi Tambahan
                        </div>
                        <textarea name="message" class="ticket-reply-textarea" placeholder="Tulis balasan atau penjelasan tambahan untuk tim admin..." required></textarea>
                        
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 12px; flex-wrap: wrap; gap: 10px;">
                            <div>
                                <label for="reply_attachment" style="font-size: 12px; color: #64748b; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; background: #f8fafc; border: 1px solid #e2e8f0; padding: 6px 12px; border-radius: 6px;">
                                    <i class="fas fa-paperclip"></i> Tambah Gambar / Bukti
                                </label>
                                <input type="file" name="attachment" id="reply_attachment" style="display: none;" accept="image/*" onchange="previewAttachmentName(this)">
                                <span id="attachmentFileName" style="font-size: 11px; color: #16a34a; margin-left: 6px; font-weight: 600;"></span>
                            </div>
                            <button type="submit" class="btn-create-ticket" style="padding: 8px 18px; font-size: 13px;">
                                <i class="fas fa-paper-plane"></i> Kirim Pesan
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div style="margin-top: 20px; background: #f1f5f9; border-radius: 10px; padding: 14px 18px; text-align: center; color: #64748b; font-size: 13px;">
                    <i class="fas fa-lock"></i> Tiket ini telah ditutup oleh Admin. Anda dapat membuat tiket baru jika memiliki kendala lain.
                </div>
            @endif

        </div>

        <!-- Right Column: Ticket Info & Summary -->
        <div class="ticket-sidebar-card">
            <h3 style="font-size: 14px; font-weight: 800; color: #0f172a; margin: 0 0 16px 0; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px;">
                Informasi Tiket
            </h3>

            <div style="font-size: 13px; color: #475569; line-height: 1.8;">
                <div style="margin-bottom: 12px;">
                    <div style="color: #94a3b8; font-size: 11px; font-weight: 700; text-transform: uppercase;">Kode Tiket</div>
                    <div style="font-weight: 800; color: #DE6C20;">#{{ $ticket->ticket_code }}</div>
                </div>

                <div style="margin-bottom: 12px;">
                    <div style="color: #94a3b8; font-size: 11px; font-weight: 700; text-transform: uppercase;">Kategori</div>
                    <div style="font-weight: 600; color: #0f172a;">{{ $ticket->category_label }}</div>
                </div>

                <div style="margin-bottom: 12px;">
                    <div style="color: #94a3b8; font-size: 11px; font-weight: 700; text-transform: uppercase;">Status Tiket</div>
                    <div style="margin-top: 2px;">
                        <span class="badge-status {{ $ticket->status_badge_class }}">
                            {{ $ticket->status_label }}
                        </span>
                    </div>
                </div>

                <div style="margin-bottom: 12px;">
                    <div style="color: #94a3b8; font-size: 11px; font-weight: 700; text-transform: uppercase;">Prioritas</div>
                    <div style="margin-top: 2px;">
                        <span class="badge-priority {{ $ticket->priority_badge_class }}">
                            {{ $ticket->priority }}
                        </span>
                    </div>
                </div>

                <div style="margin-bottom: 12px;">
                    <div style="color: #94a3b8; font-size: 11px; font-weight: 700; text-transform: uppercase;">Waktu Dibuat</div>
                    <div>{{ $ticket->created_at->format('d M Y, H:i') }} WIB</div>
                </div>

                <div>
                    <div style="color: #94a3b8; font-size: 11px; font-weight: 700; text-transform: uppercase;">Terakhir Diperbarui</div>
                    <div>{{ $ticket->last_replied_at ? $ticket->last_replied_at->format('d M Y, H:i') . ' WIB' : '-' }}</div>
                </div>
            </div>

            <div style="margin-top: 20px; padding-top: 14px; border-top: 1px solid #f1f5f9; font-size: 12px; color: #64748b; line-height: 1.5;">
                <i class="fas fa-info-circle" style="color: #DE6C20;"></i> Setiap kali admin membalas, Anda juga akan menerima notifikasi melalui email.
            </div>
        </div>

    </div>

</div>

<script>
    function previewAttachmentName(input) {
        const span = document.getElementById('attachmentFileName');
        if (input.files && input.files[0]) {
            span.textContent = 'File terpilih: ' + input.files[0].name;
        } else {
            span.textContent = '';
        }
    }
</script>
@endsection
