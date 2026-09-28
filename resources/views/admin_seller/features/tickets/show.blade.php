@extends('admin_seller.layouts.settings')

@section('title', 'Thread Tiket #' . $ticket->ticket_code . ' — Linkan.ID')
@section('page_title', 'Settings')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/seller-tickets.css') }}?v={{ time() }}">
@endpush

@section('settings_content')
<div class="tickets-container">

    {{-- Alerts --}}
    @if(session('success'))
        <div class="ticket-alert ticket-alert-success">
            <i class="fas fa-check-circle" style="font-size: 16px;"></i> {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="ticket-alert ticket-alert-error">
            <ul style="margin: 0; padding-left: 18px;">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Back Button & Header -->
    <div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
        <a href="{{ route('admin.tickets.index') }}" class="ticket-back-btn">
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
            
            <div class="ticket-thread-header">
                <h2 class="ticket-thread-title">
                    {{ $ticket->subject }}
                </h2>
                <div class="ticket-thread-meta">
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
                        <div class="ticket-reply-header">
                            <i class="fas fa-reply" style="color: #DE6C20;"></i> Kirim Balasan / Informasi Tambahan
                        </div>
                        <textarea name="message" class="ticket-reply-textarea" placeholder="Tulis balasan atau penjelasan tambahan untuk tim admin..." required></textarea>
                        
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 12px; flex-wrap: wrap; gap: 10px;">
                            <div>
                                <label for="reply_attachment" class="ticket-attachment-label">
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
                <div class="ticket-closed-notice">
                    <i class="fas fa-lock"></i> Tiket ini telah ditutup oleh Admin. Anda dapat membuat tiket baru jika memiliki kendala lain.
                </div>
            @endif

        </div>

        <!-- Right Column: Ticket Info & Summary -->
        <div class="ticket-sidebar-card">
            <h3 class="ticket-sidebar-title">
                Informasi Tiket
            </h3>

            <div class="ticket-sidebar-info">
                <div style="margin-bottom: 12px;">
                    <div class="ticket-info-label">Kode Tiket</div>
                    <div style="font-weight: 800; color: #DE6C20;">#{{ $ticket->ticket_code }}</div>
                </div>

                <div style="margin-bottom: 12px;">
                    <div class="ticket-info-label">Kategori</div>
                    <div class="ticket-info-value">{{ $ticket->category_label }}</div>
                </div>

                <div style="margin-bottom: 12px;">
                    <div class="ticket-info-label">Status Tiket</div>
                    <div style="margin-top: 2px;">
                        <span class="badge-status {{ $ticket->status_badge_class }}">
                            {{ $ticket->status_label }}
                        </span>
                    </div>
                </div>

                <div style="margin-bottom: 12px;">
                    <div class="ticket-info-label">Prioritas</div>
                    <div style="margin-top: 2px;">
                        <span class="badge-priority {{ $ticket->priority_badge_class }}">
                            {{ $ticket->priority }}
                        </span>
                    </div>
                </div>

                <div style="margin-bottom: 12px;">
                    <div class="ticket-info-label">Waktu Dibuat</div>
                    <div class="ticket-info-value">{{ $ticket->created_at->format('d M Y, H:i') }} WIB</div>
                </div>

                <div>
                    <div class="ticket-info-label">Terakhir Diperbarui</div>
                    <div class="ticket-info-value">{{ $ticket->last_replied_at ? $ticket->last_replied_at->format('d M Y, H:i') . ' WIB' : '-' }}</div>
                </div>
            </div>

            <div class="ticket-sidebar-footer">
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
