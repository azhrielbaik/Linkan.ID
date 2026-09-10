@extends('admin_seller.layouts.app')

@section('title', 'Issue List — Linkan.ID')
@section('page_title', 'Issue List')

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

    <!-- Issue List Header & Toolbar -->
    <div class="tickets-header">
        <h2 class="tickets-title">Issue List</h2>
        <!-- Optional Breadcrumb could go here -->
    </div>

    <div class="issue-list-box">
        <div class="issue-list-toolbar">
            <form action="{{ route('admin.tickets.index') }}" method="GET" class="issue-search">
                <i class="fas fa-search"></i>
                <input type="text" name="search" placeholder="Search issues..." value="{{ request('search') }}">
            </form>
            <button class="btn-add-issue" onclick="openCreateTicketModal()">Add New Issues</button>
        </div>

        <div class="issue-list-content">
            @forelse($tickets as $t)
                @php
                    // Map statuses to UI colors
                    $statusClass = 'status-pending';
                    $statusText = 'Pending';
                    if ($t->status === 'open') {
                        $statusClass = 'status-open';
                        $statusText = 'Open';
                    } elseif ($t->status === 'in_progress') {
                        $statusClass = 'status-in-progress';
                        $statusText = 'In Progress';
                    } elseif ($t->status === 'resolved') {
                        $statusClass = 'status-resolved';
                        $statusText = 'Resolved';
                    } elseif ($t->status === 'closed') {
                        $statusClass = 'status-closed';
                        $statusText = 'Closed';
                    }
                @endphp

                <div class="issue-row">
                    <!-- Column 1: Status -->
                    <div>
                        <span class="issue-status {{ $statusClass }}">{{ $statusText }}</span>
                    </div>

                    <!-- Column 2: ID & Title -->
                    <div class="issue-info">
                        <span class="issue-id">{{ str_replace('TKT-', 'ISSUE-', explode('-', $t->ticket_code)[0] . '-' . (isset(explode('-', $t->ticket_code)[2]) ? explode('-', $t->ticket_code)[2] : $t->ticket_code)) }}</span>
                        <span class="issue-title">{{ Str::limit($t->subject, 50) }}</span>
                    </div>

                    <!-- Column 3: User Avatar & Name -->
                    <div class="issue-user">
                        @if(Auth::user()->avatar)
                            <img src="{{ Storage::url(Auth::user()->avatar) }}" alt="Avatar" class="issue-avatar">
                        @else
                            <div class="issue-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                        @endif
                        <span class="issue-username">{{ Auth::user()->name }}</span>
                    </div>

                    <!-- Column 4: Dates -->
                    <div class="issue-dates">
                        <span><i class="far fa-calendar-alt"></i> <strong>Created:</strong> {{ $t->created_at->format('d.m.Y') }}</span>
                        <span><i class="far fa-clock"></i> <strong>Replied:</strong> {{ $t->last_replied_at ? $t->last_replied_at->format('d.m.Y') : $t->created_at->format('d.m.Y') }}</span>
                    </div>

                    <!-- Column 5: Tags -->
                    <div class="issue-tags">
                        <span class="issue-tag">{{ ucfirst($t->category) }}</span>
                        <span class="issue-tag">{{ ucfirst($t->priority) }} Priority</span>
                    </div>

                    <!-- Column 6: Meta (Comments & Files) -->
                    <div class="issue-meta">
                        <div class="meta-item" title="Comments">
                            <i class="far fa-comment-dots"></i> {{ $t->replies_count ?? 0 }}
                        </div>
                        <div class="meta-item" title="Files">
                            <i class="fas fa-paperclip"></i> {{ $t->replies()->whereNotNull('attachment')->count() > 0 ? 1 : 0 }}
                        </div>
                    </div>

                    <!-- Column 7: Actions -->
                    <div class="issue-actions">
                        <a href="{{ route('admin.tickets.show', $t->id) }}" class="btn-action" title="View Issue">
                            <i class="far fa-eye"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div class="tickets-empty-state">
                    <i class="fas fa-clipboard-list"></i>
                    <p style="font-weight: 600; font-size: 16px; color: #334155; margin-bottom: 6px;">No Issues Found</p>
                    <p style="font-size: 13px;">You have no active support tickets or issues at the moment.</p>
                </div>
            @endforelse
        </div>

        @if($tickets->hasPages())
            <div class="issue-pagination">
                {{ $tickets->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Modal Buat Tiket Baru -->
<div id="createTicketModal" class="ticket-modal">
    <div class="ticket-modal-card">
        <div class="ticket-modal-header">
            <h3><i class="fas fa-plus-circle" style="color: #4A568D;"></i> Add New Issue</h3>
            <button type="button" class="ticket-modal-close" onclick="closeCreateTicketModal()">&times;</button>
        </div>
        <form action="{{ route('admin.tickets.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="ticket-modal-body">
                <div class="ticket-form-group">
                    <label for="category">Category <span style="color: #ef4444;">*</span></label>
                    <select name="category" id="category" class="ticket-form-control" required>
                        <option value="">-- Select Category --</option>
                        <option value="payout">Payments & Payout</option>
                        <option value="product">Digital Products</option>
                        <option value="account">Account & Security</option>
                        <option value="general">General Issue</option>
                    </select>
                </div>

                <div class="ticket-form-group">
                    <label for="subject">Issue Title <span style="color: #ef4444;">*</span></label>
                    <input type="text" name="subject" id="subject" class="ticket-form-control" placeholder="E.g. Payment gateway fails..." required>
                </div>

                <div class="ticket-form-group">
                    <label for="message">Detailed Description <span style="color: #ef4444;">*</span></label>
                    <textarea name="message" id="message" rows="5" class="ticket-form-control" placeholder="Describe the issue in detail..." required></textarea>
                </div>

                <div class="ticket-form-group" style="margin-bottom: 0;">
                    <label for="attachment">Attachment (Optional)</label>
                    <input type="file" name="attachment" id="attachment" class="ticket-form-control" accept="image/*">
                    <small style="color: #94a3b8; font-size: 11px; margin-top: 4px; display: block;">Supported formats: JPG, PNG, WEBP (Max 2MB)</small>
                </div>
            </div>
            <div style="padding: 16px 24px; background: #f8fafc; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end; gap: 10px; border-radius: 0 0 16px 16px;">
                <button type="button" onclick="closeCreateTicketModal()" style="padding: 9px 18px; border: 1px solid #e2e8f0; background: #ffffff; color: #64748b; font-weight: 600; font-size: 13px; border-radius: 8px; cursor: pointer;">
                    Cancel
                </button>
                <button type="submit" class="btn-add-issue" style="padding: 9px 20px;">
                    Submit Issue
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
                    title: 'Active Issue Exists!',
                    html: `You still have an unresolved issue:<br><strong style="color: #4A568D;">#{{ $activeTicket->ticket_code }}</strong> — <em>{{ e($activeTicket->subject) }}</em><br><br><span style="font-size: 13px; color: #64748b;">Please wait until it is resolved before submitting a new one.</span>`,
                    confirmButtonText: 'View Issue',
                    confirmButtonColor: '#4A568D',
                    showCancelButton: true,
                    cancelButtonText: 'Close',
                    cancelButtonColor: '#64748b',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('admin.tickets.show', $activeTicket->id) }}";
                    }
                });
            } else {
                alert('You still have an active issue (#{{ $activeTicket->ticket_code }}). Please wait until it is resolved.');
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
