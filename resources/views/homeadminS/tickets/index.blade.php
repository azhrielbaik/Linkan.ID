@extends('layouts.admin')

@section('title', 'Pusat Bantuan & Tiket Support — Linkan.ID')
@section('page_title', 'Pusat Bantuan')

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

    @if(session('error'))
        <div style="background-color: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 14px 18px; border-radius: 12px; margin-bottom: 20px; font-size: 13px; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-exclamation-circle" style="font-size: 16px;"></i> {{ session('error') }}
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

    <!-- Active Ticket Notice Banner (Rate Limiter) -->
    @if(isset($activeTicket) && $activeTicket)
        <div class="ticket-active-alert-card">
            <div class="ticket-active-alert-content">
                <div class="ticket-active-alert-icon">
                    <i class="fas fa-headset"></i>
                </div>
                <div>
                    <div class="ticket-active-alert-title">
                        <i class="fas fa-info-circle"></i> Anda Memiliki Tiket Bantuan yang Masih Aktif (#{{ $activeTicket->ticket_code }})
                    </div>
                    <div class="ticket-active-alert-desc">
                        Subjek: <strong>"{{ $activeTicket->subject }}"</strong> (Status: <em>{{ $activeTicket->status_label }}</em>).<br>
                        Pengajuan tiket baru dibatasi. Anda dapat membuat tiket baru setelah tiket aktif ini berstatus <strong>Selesai</strong> atau <strong>Ditutup</strong> oleh admin.
                    </div>
                </div>
            </div>
            <a href="{{ route('admin.tickets.show', $activeTicket->id) }}" class="btn-view-active-ticket">
                <i class="fas fa-comments"></i> Buka Thread Tiket Aktif
            </a>
        </div>
    @endif

    <!-- Header Section -->
    <div class="tickets-header-section">
        <div class="tickets-header-title">
            <h2>Pusat Bantuan & Tiket Bantuan</h2>
            <p>Laporkan kendala terkait penarikan dana, produk digital, atau pertanyaan akun Anda langsung ke tim support.</p>
        </div>
        @if(isset($activeTicket) && $activeTicket)
            <button type="button" class="btn-create-ticket btn-disabled" onclick="showActiveTicketNotice()" title="Anda masih memiliki tiket yang sedang aktif">
                <i class="fas fa-lock"></i> Buat Tiket Baru
            </button>
        @else
            <button type="button" class="btn-create-ticket" onclick="openCreateTicketModal()">
                <i class="fas fa-plus"></i> Buat Tiket Baru
            </button>
        @endif
    </div>

    <!-- Stats Cards -->
    <div class="tickets-stats-grid">
        <div class="ticket-stat-card">
            <div class="ticket-stat-icon icon-all">
                <i class="fas fa-ticket-alt"></i>
            </div>
            <div class="ticket-stat-info">
                <div class="stat-value">{{ $totalTickets }}</div>
                <div class="stat-label">Total Tiket</div>
            </div>
        </div>

        <div class="ticket-stat-card">
            <div class="ticket-stat-icon icon-open">
                <i class="fas fa-clock"></i>
            </div>
            <div class="ticket-stat-info">
                <div class="stat-value">{{ $openTickets }}</div>
                <div class="stat-label">Menunggu Respon</div>
            </div>
        </div>

        <div class="ticket-stat-card">
            <div class="ticket-stat-icon icon-progress">
                <i class="fas fa-spinner"></i>
            </div>
            <div class="ticket-stat-info">
                <div class="stat-value">{{ $inProgressTickets }}</div>
                <div class="stat-label">Sedang Ditangani</div>
            </div>
        </div>

        <div class="ticket-stat-card">
            <div class="ticket-stat-icon icon-resolved">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="ticket-stat-info">
                <div class="stat-value">{{ $resolvedTickets }}</div>
                <div class="stat-label">Terselesaikan</div>
            </div>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="tickets-filter-bar">
        <div class="ticket-tabs">
            <a href="{{ route('admin.tickets.index') }}" class="ticket-tab-item {{ empty($status) ? 'active' : '' }}">
                Semua
            </a>
            <a href="{{ route('admin.tickets.index', ['status' => 'open']) }}" class="ticket-tab-item {{ $status === 'open' ? 'active' : '' }}">
                Menunggu
            </a>
            <a href="{{ route('admin.tickets.index', ['status' => 'in_progress']) }}" class="ticket-tab-item {{ $status === 'in_progress' ? 'active' : '' }}">
                Proses
            </a>
            <a href="{{ route('admin.tickets.index', ['status' => 'resolved']) }}" class="ticket-tab-item {{ $status === 'resolved' ? 'active' : '' }}">
                Selesai
            </a>
        </div>

        <form action="{{ route('admin.tickets.index') }}" method="GET" class="ticket-search-form">
            @if($status)
                <input type="hidden" name="status" value="{{ $status }}">
            @endif
            <i class="fas fa-search ticket-search-icon"></i>
            <input type="text" name="search" class="ticket-search-input" placeholder="Cari kode atau subjek..." value="{{ $search }}">
        </form>
    </div>

    <!-- Tickets List Table -->
    <div class="tickets-table-card">
        <table class="tickets-table">
            <thead>
                <tr>
                    <th>Kode Tiket</th>
                    <th>Subjek & Kategori</th>
                    <th>Status</th>
                    <th>Prioritas</th>
                    <th>Terakhir Diperbarui</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tickets as $t)
                <tr>
                    <td>
                        <span class="ticket-code-badge">#{{ $t->ticket_code }}</span>
                    </td>
                    <td>
                        <a href="{{ route('admin.tickets.show', $t->id) }}" class="ticket-subject-link">
                            {{ $t->subject }}
                        </a>
                        <div class="ticket-snippet">
                            <span style="font-weight: 600; color: #DE6C20;">[{{ $t->category_label }}]</span> 
                            {{ Str::limit($t->message, 60) }}
                        </div>
                    </td>
                    <td>
                        <span class="badge-status {{ $t->status_badge_class }}">
                            <i class="fas fa-circle" style="font-size: 6px;"></i> {{ $t->status_label }}
                        </span>
                    </td>
                    <td>
                        <span class="badge-priority {{ $t->priority_badge_class }}">
                            {{ $t->priority }}
                        </span>
                    </td>
                    <td style="color: #64748b; font-size: 12px; white-space: nowrap;">
                        {{ $t->last_replied_at ? $t->last_replied_at->diffForHumans() : $t->created_at->diffForHumans() }}
                    </td>
                    <td style="text-align: center;">
                        <a href="{{ route('admin.tickets.show', $t->id) }}" class="btn-ticket-detail">
                            <i class="fas fa-comments"></i> Buka Thread
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="tickets-empty-state">
                            <i class="fas fa-headset"></i>
                            <p style="font-weight: 600; font-size: 15px; color: #334155; margin-bottom: 6px;">Belum Ada Tiket Bantuan</p>
                            <p style="font-size: 13px; margin-bottom: 18px;">Jika Anda memiliki pertanyaan atau kendala seputar platform, silakan buat tiket bantuan.</p>

                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($tickets->hasPages())
            <div style="padding: 16px 20px; border-top: 1px solid #f1f5f9;">
                {{ $tickets->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Modal Buat Tiket Baru -->
<div id="createTicketModal" class="ticket-modal">
    <div class="ticket-modal-card">
        <div class="ticket-modal-header">
            <h3><i class="fas fa-headset" style="color: #DE6C20;"></i> Buat Tiket Bantuan Baru</h3>
            <button type="button" class="ticket-modal-close" onclick="closeCreateTicketModal()">&times;</button>
        </div>
        <form action="{{ route('admin.tickets.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="ticket-modal-body">
                <div class="ticket-form-group">
                    <label for="category">Kategori Kendala <span style="color: #ef4444;">*</span></label>
                    <select name="category" id="category" class="ticket-form-control" required>
                        <option value="">-- Pilih Kategori Kendala --</option>
                        <option value="payout">Penarikan Dana / Payout</option>
                        <option value="product">Produk Digital & Transaksi</option>
                        <option value="account">Akun & Keamanan</option>
                        <option value="general">Pertanyaan Umum / Lainnya</option>
                    </select>
                </div>

                <div class="ticket-form-group">
                    <label for="subject">Subjek Kendala <span style="color: #ef4444;">*</span></label>
                    <input type="text" name="subject" id="subject" class="ticket-form-control" placeholder="Contoh: Permintaan payout belum masuk rekening" required>
                </div>

                <div class="ticket-form-group">
                    <label for="message">Rincian Keluhan / Pertanyaan <span style="color: #ef4444;">*</span></label>
                    <textarea name="message" id="message" rows="5" class="ticket-form-control" placeholder="Jelaskan kendala Anda secara detail agar tim support dapat membantu lebih cepat..." required></textarea>
                </div>

                <div class="ticket-form-group" style="margin-bottom: 0;">
                    <label for="attachment">Lampiran Screenshot / Bukti (Opsional)</label>
                    <input type="file" name="attachment" id="attachment" class="ticket-form-control" accept="image/*">
                    <small style="color: #94a3b8; font-size: 11px; margin-top: 4px; display: block;">Format didukung: JPG, PNG, WEBP (Maksimal 2MB)</small>
                </div>
            </div>
            <div style="padding: 16px 24px; background: #f8fafc; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end; gap: 10px; border-radius: 0 0 16px 16px;">
                <button type="button" onclick="closeCreateTicketModal()" style="padding: 9px 18px; border: 1px solid #e2e8f0; background: #ffffff; color: #64748b; font-weight: 600; font-size: 13px; border-radius: 8px; cursor: pointer;">
                    Batal
                </button>
                <button type="submit" class="btn-create-ticket" style="padding: 9px 20px;">
                    <i class="fas fa-paper-plane"></i> Kirim Tiket
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function openCreateTicketModal() {
        const modal = document.getElementById('createTicketModal');
        if (modal) modal.classList.add('show');
    }

    function closeCreateTicketModal() {
        const modal = document.getElementById('createTicketModal');
        if (modal) modal.classList.remove('show');
    }

    function showActiveTicketNotice() {
        @if(isset($activeTicket) && $activeTicket)
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tiket Masih Aktif!',
                    html: `Anda saat ini masih memiliki tiket yang belum selesai:<br><strong style="color: #DE6C20;">#{{ $activeTicket->ticket_code }}</strong> — <em>{{ e($activeTicket->subject) }}</em><br><br><span style="font-size: 13px; color: #64748b;">Harap tunggu hingga tiket tersebut diselesaikan atau ditutup oleh admin sebelum mengajukan tiket baru.</span>`,
                    confirmButtonText: '<i class="fas fa-comments"></i> Buka Tiket Aktif',
                    confirmButtonColor: '#DE6C20',
                    showCancelButton: true,
                    cancelButtonText: 'Tutup',
                    cancelButtonColor: '#64748b',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('admin.tickets.show', $activeTicket->id) }}";
                    }
                });
            } else {
                alert('Anda masih memiliki tiket bantuan yang sedang aktif (#{{ $activeTicket->ticket_code }}). Harap tunggu hingga tiket tersebut selesai atau ditutup oleh admin sebelum membuat tiket baru.');
            }
        @endif
    }

    // Close when click backdrop
    window.addEventListener('click', function(e) {
        const modal = document.getElementById('createTicketModal');
        if (e.target === modal) {
            closeCreateTicketModal();
        }
    });
</script>
@endpush
@endsection
