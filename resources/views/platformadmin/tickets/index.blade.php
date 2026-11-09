<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('platform.support_tickets') }} — Platform Admin</title>
    @include('platformadmin.partials.head_assets')
    <link rel="stylesheet" href="{{ asset('css/platform/tickets.css') }}">
</head>
<body>

    {{-- Sidebar --}}
    @include('platformadmin.sidebar.sidebarplatform')

    <div class="platform-main">
        {{-- Header --}}
        <div class="platform-header">
            <div class="platform-header-left">
                <button type="button" class="hamburger-btn" onclick="toggleSidebar()" aria-label="Toggle Sidebar" title="Buka/Tutup Menu"><i class="fas fa-bars"></i></button>
                <h1>{{ __('platform.support_tickets') }}</h1>
            </div>
            <div class="header-right">
                @include('platformadmin.partials.notifications')
                @include('platformadmin.partials.header_profile')
            </div>
        </div>

        <div class="content-wrapper">
            {{-- Alert Notifikasi Session --}}
            @if(session('success'))
                <div class="alert alert-success" style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 14px 18px; border-radius: 12px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; font-size: 13px; font-weight: 600;">
                    <i class="fas fa-check-circle" style="color: #10b981; font-size: 16px;"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger" style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 14px 18px; border-radius: 12px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; font-size: 13px; font-weight: 600;">
                    <i class="fas fa-exclamation-circle" style="color: #ef4444; font-size: 16px;"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger" style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 14px 18px; border-radius: 12px; margin-bottom: 20px; font-size: 13px;">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Header Section -->
            <div class="platform-tickets-header">
                <div>
                    <h2>{{ __('platform.support_tickets_subtitle') }}</h2>
                    <p>{{ __('platform.support_tickets_desc') }}</p>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="platform-tickets-stats">
                <div class="p-ticket-stat">
                    <div class="p-ticket-stat-icon p-icon-total">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <div>
                        <div class="p-ticket-stat-num">{{ $totalCount }}</div>
                        <div class="p-ticket-stat-lbl">{{ __('platform.total_tickets_in') }}</div>
                    </div>
                </div>

                <div class="p-ticket-stat">
                    <div class="p-ticket-stat-icon p-icon-open">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div>
                        <div class="p-ticket-stat-num">{{ $openCount }}</div>
                        <div class="p-ticket-stat-lbl">{{ __('platform.waiting_response') }}</div>
                    </div>
                </div>

                <div class="p-ticket-stat">
                    <div class="p-ticket-stat-icon p-icon-progress">
                        <i class="fas fa-spinner"></i>
                    </div>
                    <div>
                        <div class="p-ticket-stat-num">{{ $inProgressCount }}</div>
                        <div class="p-ticket-stat-lbl">{{ __('platform.being_handled') }}</div>
                    </div>
                </div>

                <div class="p-ticket-stat">
                    <div class="p-ticket-stat-icon p-icon-resolved">
                        <i class="fas fa-check-double"></i>
                    </div>
                    <div>
                        <div class="p-ticket-stat-num">{{ $resolvedCount }}</div>
                        <div class="p-ticket-stat-lbl">{{ __('platform.resolved_tickets') }}</div>
                    </div>
                </div>
            </div>

            <!-- Status Tabs Navigation (Terpisah Mandiri) -->
            <div class="tabs-container">
                <a href="{{ route('platform-admin.tickets.index', array_merge(request()->except('status', 'page'), [])) }}"
                   class="tab-link {{ empty($status) ? 'active' : '' }}">
                    <i class="fas fa-inbox"></i> <span class="tab-label">{{ __('platform.all_tickets') }} ({{ $totalCount }})</span>
                </a>
                <a href="{{ route('platform-admin.tickets.index', array_merge(request()->except('status', 'page'), ['status' => 'open'])) }}"
                   class="tab-link {{ $status === 'open' ? 'active' : '' }}">
                    <i class="fas fa-envelope-open-text"></i> <span class="tab-label">{{ __('platform.waiting_tickets') }} ({{ $openCount }})</span>
                </a>
                <a href="{{ route('platform-admin.tickets.index', array_merge(request()->except('status', 'page'), ['status' => 'in_progress'])) }}"
                   class="tab-link {{ $status === 'in_progress' ? 'active' : '' }}">
                    <i class="fas fa-spinner"></i> <span class="tab-label">{{ __('platform.in_process_tickets') }} ({{ $inProgressCount }})</span>
                </a>
                <a href="{{ route('platform-admin.tickets.index', array_merge(request()->except('status', 'page'), ['status' => 'resolved'])) }}"
                   class="tab-link {{ $status === 'resolved' ? 'active' : '' }}">
                    <i class="fas fa-check-double"></i> <span class="tab-label">{{ __('platform.status_resolved') }} ({{ $resolvedCount }})</span>
                </a>
                <a href="{{ route('platform-admin.tickets.index', array_merge(request()->except('status', 'page'), ['status' => 'closed'])) }}"
                   class="tab-link {{ $status === 'closed' ? 'active' : '' }}">
                    <i class="fas fa-archive"></i> <span class="tab-label">{{ __('platform.closed_tickets') }} ({{ $closedCount }})</span>
                </a>
            </div>

            <!-- Filters Bar (Hanya Form Filter & Pencarian) -->
            <div class="p-tickets-filters">
                <form action="{{ route('platform-admin.tickets.index') }}" method="GET" class="p-filter-inputs" style="width: 100%;">
                    @if($status)
                        <input type="hidden" name="status" value="{{ $status }}">
                    @endif

                    <select name="priority" class="p-filter-select" onchange="this.form.submit()">
                        <option value="">{{ __('platform.all_priorities') }}</option>
                        <option value="urgent" {{ $priority === 'urgent' ? 'selected' : '' }}>Urgent</option>
                        <option value="high" {{ $priority === 'high' ? 'selected' : '' }}>High</option>
                        <option value="medium" {{ $priority === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="low" {{ $priority === 'low' ? 'selected' : '' }}>Low</option>
                    </select>

                    <select name="category" class="p-filter-select" onchange="this.form.submit()">
                        <option value="">{{ __('platform.all_categories') }}</option>
                        <option value="payout" {{ $category === 'payout' ? 'selected' : '' }}>{{ __('platform.cat_payout') }}</option>
                        <option value="product" {{ $category === 'product' ? 'selected' : '' }}>{{ __('platform.cat_product') }}</option>
                        <option value="account" {{ $category === 'account' ? 'selected' : '' }}>{{ __('platform.cat_account') }}</option>
                        <option value="general" {{ $category === 'general' ? 'selected' : '' }}>{{ __('platform.cat_general') }}</option>
                    </select>

                    <div class="date-picker-box" data-start-name="start_date" data-end-name="end_date" data-start-value="{{ $startDate ?? '' }}" data-end-value="{{ $endDate ?? '' }}" data-placeholder="Tanggal Tiket">
                        <i class="fas fa-calendar-alt date-picker-icon"></i>
                        <span class="date-range-display">Tanggal Tiket</span>
                        <button type="button" class="date-range-clear-btn" title="Reset Tanggal" style="display: none;"><i class="fas fa-times"></i></button>
                        <input type="hidden" name="start_date" value="{{ $startDate ?? '' }}" class="date-range-hidden-input">
                        <input type="hidden" name="end_date" value="{{ $endDate ?? '' }}" class="date-range-hidden-input">
                    </div>

                    <div class="search-wrap">
                        <i class="fas fa-search"></i>
                        <input type="text" name="search" placeholder="{{ __('platform.search_ticket_placeholder') }}" value="{{ $search }}">
                    </div>

                    <button type="submit" class="btn-filter"><i class="fas fa-filter"></i> {{ __('platform.filter') }}</button>
                    @if($search || $priority || $category || $startDate || $endDate)
                        <a href="{{ route('platform-admin.tickets.index', ['status' => $status]) }}" class="btn-reset"><i class="fas fa-rotate-left"></i> {{ __('platform.reset') }}</a>
                    @endif
                </form>
            </div>

            <!-- Tickets Table -->
            <div class="p-tickets-table-card">
                <div class="table-responsive">
                <table class="p-tickets-table">
                    <thead>
                        <tr>
                            <th><i class="fas fa-ticket-alt"></i> {{ __('platform.ticket_code_and_time') }}</th>
                            <th><i class="fas fa-user"></i> {{ __('platform.seller_requester') }}</th>
                            <th><i class="fas fa-folder-open"></i> {{ __('platform.subject_and_category') }}</th>
                            <th><i class="fas fa-chart-line"></i> {{ __('platform.status') }}</th>
                            <th><i class="fas fa-flag"></i> {{ __('platform.priority') }}</th>
                            <th style="text-align: center;"><i class="fas fa-cog"></i> {{ __('platform.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $t)
                        <tr>
                            <td>
                                <span class="ticket-code-badge">#{{ $t->ticket_code }}</span>
                                <div style="font-size: 11px; color: #94a3b8; margin-top: 4px;">
                                    {{ $t->created_at->format('d M Y, H:i') }}
                                </div>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div class="seller-avatar-small">
                                        {{ strtoupper(substr($t->user->name ?? 'U', 0, 2)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight: 700; color: #0f172a;">{{ $t->user->name ?? __('platform.deleted_user') }}</div>
                                        <div style="font-size: 11px; color: #64748b;">{{ $t->user->email ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a href="javascript:void(0)" onclick="openTicketModal({{ $t->id }})" style="font-weight: 700; color: #0f172a; text-decoration: none; display: block; margin-bottom: 2px;">
                                    {{ $t->subject }}
                                </a>
                                <div style="font-size: 12px; color: #64748b;">
                                    <strong style="color: #ED842C;">[{{ $t->category_label }}]</strong>
                                    {{ Str::limit($t->message, 50) }}
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
                            <td style="text-align: center;">
                                <button type="button" class="btn-open-ticket" onclick="openTicketModal({{ $t->id }})" title="{{ __('platform.respond_ticket') }}">
                                    <i class="fas fa-reply"></i> {{ __('platform.respond_ticket') }}
                                </button>
                                <script type="application/json" id="ticket-data-{{ $t->id }}">
                                    {!! json_encode([
                                        'id' => $t->id,
                                        'ticket_code' => $t->ticket_code,
                                        'subject' => $t->subject,
                                        'category' => $t->category,
                                        'category_label' => $t->category_label,
                                        'status' => $t->status,
                                        'status_label' => $t->status_label,
                                        'status_badge_class' => $t->status_badge_class,
                                        'priority' => $t->priority,
                                        'priority_badge_class' => $t->priority_badge_class,
                                        'message' => $t->message,
                                        'created_at_formatted' => $t->created_at->format('d M Y, H:i') . ' WIB',
                                        'last_replied_formatted' => $t->last_replied_at ? $t->last_replied_at->format('d M Y, H:i') . ' WIB' : '-',
                                        'total_replies_count' => $t->replies->count(),
                                        'user' => [
                                            'id' => $t->user->id ?? null,
                                            'name' => $t->user->name ?? __('platform.deleted_user'),
                                            'email' => $t->user->email ?? '-',
                                            'initials' => strtoupper(substr($t->user->name ?? 'U', 0, 2)),
                                        ],
                                        'replies' => $t->replies->map(function ($r) {
                                            return [
                                                'id' => $r->id,
                                                'is_admin_reply' => (bool) $r->is_admin_reply,
                                                'sender_name' => $r->user->name ?? ($r->is_admin_reply ? 'Admin Platform' : 'Seller'),
                                                'sender_initials' => strtoupper(substr($r->user->name ?? ($r->is_admin_reply ? 'A' : 'S'), 0, 2)),
                                                'created_at_formatted' => $r->created_at->format('d M Y, H:i'),
                                                'message' => $r->message,
                                                'attachment_url' => $r->attachment ? asset('storage/' . $r->attachment) : null,
                                            ];
                                        }),
                                        'reply_url' => route('platform-admin.tickets.reply', $t->id),
                                        'status_url' => route('platform-admin.tickets.status', $t->id),
                                    ]) !!}
                                </script>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 40px; color: #94a3b8;">
                                <i class="fas fa-inbox" style="font-size: 36px; color: #cbd5e1; margin-bottom: 10px; display: block;"></i>
                                {{ __('platform.no_tickets_found') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>

                @if($tickets->hasPages())
                    <div style="padding: 16px 20px; border-top: 1px solid #f1f5f9;">
                        {{ $tickets->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    {{-- Modal Pop-up Respon Tiket Bantuan --}}
    <div id="ticketDetailModal" class="p-ticket-modal" onclick="if(event.target === this) closeTicketModal()">
        <div class="p-ticket-modal-card">
            <div class="p-ticket-modal-header">
                <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                    <h2 id="ticketModalCode" style="margin: 0; font-size: 17px; font-weight: 800; color: #0f172a;">Tiket Bantuan</h2>
                    <div id="ticketModalBadges" style="display: flex; align-items: center; gap: 8px;"></div>
                </div>
                <button type="button" class="p-ticket-modal-close" onclick="closeTicketModal()" aria-label="Tutup" title="Tutup">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="ticketModalBodyContent" class="p-ticket-modal-body">
                <!-- Konten dinamis dimuat via tickets.js -->
            </div>
            <div class="p-ticket-modal-footer">
                <button type="button" class="btn-ticket-back" onclick="closeTicketModal()" style="cursor: pointer;">
                    <i class="fas fa-xmark"></i> Tutup
                </button>
            </div>
        </div>
    </div>

    {{-- Modal Preview Lampiran Gambar (Pop-up) --}}
    <div id="imageAttachmentModal" class="modal attachment-modal" onclick="if(event.target === this) closeAttachmentModal()">
        <div class="attachment-modal-container">
            <div class="modal-header">
                <h3><i class="fas fa-image" style="color: #ED842C;"></i> <span id="attachmentModalTitle">Pratinjau Lampiran Gambar</span></h3>
                <button type="button" class="modal-close" onclick="closeAttachmentModal()">&times;</button>
            </div>
            <div class="attachment-modal-body">
                <div class="attachment-image-wrap">
                    <img id="attachmentModalImg" src="" alt="Pratinjau Lampiran">
                </div>
            </div>
            <div class="modal-footer">
                <a id="attachmentModalDownload" href="" target="_blank" class="btn-attachment-open">
                    <i class="fas fa-external-link-alt"></i> Buka Ukuran Asli
                </a>
                <button type="button" class="btn-modal-cancel" onclick="closeAttachmentModal()">Tutup</button>
            </div>
        </div>
    </div>

    <script>
        window.PlatformTicketsConfig = {
            baseUrl: '{{ route('platform-admin.tickets.index') }}',
            csrfToken: '{{ csrf_token() }}'
        };
    </script>
    @vite(['resources/js/app.js'])
    <script src="{{ asset('js/platform/notifications.js') }}"></script>
    <script src="{{ asset('js/platform/tickets.js') }}?v={{ file_exists(public_path('js/platform/tickets.js')) ? filemtime(public_path('js/platform/tickets.js')) : time() }}"></script>
</body>
</html>
