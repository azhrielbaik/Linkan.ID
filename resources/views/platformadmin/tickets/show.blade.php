<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('platform.ticket_detail') }} #{{ $ticket->ticket_code }} — Platform Admin</title>
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
                <h1>{{ __('platform.ticket_detail') }} #{{ $ticket->ticket_code }}</h1>
            </div>
            <div class="header-right">
                @include('platformadmin.partials.notifications')
                @include('platformadmin.partials.header_profile')
            </div>
        </div>

        <div class="content-wrapper">
            <!-- Top Navigation Bar -->
            <div class="ticket-nav-bar">
                <a href="{{ route('platform-admin.tickets.index') }}" class="btn-ticket-back">
                    <i class="fas fa-arrow-left"></i>
                    <span>{{ __('platform.back_to_tickets') }}</span>
                </a>

                <div class="ticket-nav-badges">
                    <span class="ticket-code-badge">#{{ $ticket->ticket_code }}</span>
                    <span class="badge-status {{ $ticket->status_badge_class }}">
                        <i class="fas fa-circle" style="font-size: 6px;"></i> {{ $ticket->status_label }}
                    </span>
                    <span class="badge-priority {{ $ticket->priority_badge_class }}">
                        <i class="fas fa-flag" style="font-size: 9px;"></i> {{ $ticket->priority }}
                    </span>
                </div>
            </div>

            <!-- Ticket Subject Header Card -->
            <div class="ticket-header-card">
                <h2 class="ticket-subject-title">{{ $ticket->subject }}</h2>
                <div class="ticket-meta-pills">
                    <span class="ticket-meta-pill">
                        <i class="fas fa-folder-open" style="color: #ed842c;"></i>
                        <span>{{ __('platform.ticket_category') }}: <strong>{{ $ticket->category_label }}</strong></span>
                    </span>
                    <span class="ticket-meta-pill">
                        <i class="far fa-clock"></i>
                        <span>Diajukan: <strong>{{ $ticket->created_at->format('d M Y, H:i') }} WIB</strong></span>
                    </span>
                    <span class="ticket-meta-pill">
                        <i class="far fa-user"></i>
                        <span>Pengaju: <strong>{{ $ticket->user->name ?? 'User' }}</strong></span>
                    </span>
                </div>
            </div>

            <!-- Thread Grid -->
            <div class="p-thread-grid">

                <!-- Main Chat Conversation -->
                <div class="p-thread-main">

                    <!-- Chat Conversation Stream -->
                    <div class="p-chat-stream">

                        <!-- Initial Message from Seller -->
                        <div class="p-chat-item">
                            <div class="p-chat-avatar p-chat-avatar-seller">
                                {{ strtoupper(substr($ticket->user->name ?? 'U', 0, 2)) }}
                            </div>
                            <div class="p-chat-bubble p-chat-bubble-seller">
                                <div class="p-chat-header">
                                    <div class="p-chat-sender">
                                        <span class="p-chat-name">{{ $ticket->user->name ?? 'User' }}</span>
                                        <span class="p-chat-role p-chat-role-seller">Seller</span>
                                    </div>
                                    <span class="p-chat-time">
                                        <i class="far fa-clock"></i> {{ $ticket->created_at->format('d M Y, H:i') }}
                                    </span>
                                </div>
                                <div class="p-chat-text">
                                    {!! nl2br(e($ticket->message)) !!}
                                </div>
                            </div>
                        </div>

                        <!-- Replies Stream -->
                        @foreach($ticket->replies as $r)
                            @if($r->is_admin_reply)
                                <div class="p-chat-item">
                                    <div class="p-chat-avatar p-chat-avatar-admin">
                                        <i class="fas fa-shield-alt"></i>
                                    </div>
                                    <div class="p-chat-bubble p-chat-bubble-admin">
                                        <div class="p-chat-header">
                                            <div class="p-chat-sender">
                                                <span class="p-chat-name">{{ $r->user->name ?? 'Platform Admin' }}</span>
                                                <span class="p-chat-role p-chat-role-admin">Admin Platform</span>
                                            </div>
                                            <span class="p-chat-time">
                                                <i class="far fa-clock"></i> {{ $r->created_at->format('d M Y, H:i') }}
                                            </span>
                                        </div>
                                        <div class="p-chat-text">
                                            {!! nl2br(e($r->message)) !!}
                                        </div>
                                        @if($r->attachment)
                                            <div class="bubble-attachment">
                                                <button type="button" class="btn-attachment-preview" onclick="openAttachmentModal('{{ asset('storage/' . $r->attachment) }}', '{{ __('platform.attachment_label') }}')">
                                                    <div class="btn-attachment-icon">
                                                        <i class="fas fa-image"></i>
                                                    </div>
                                                    <div class="btn-attachment-info">
                                                        <span class="btn-attachment-title">{{ __('platform.attachment_label') }}</span>
                                                        <span class="btn-attachment-sub">Klik untuk melihat foto di pop-up</span>
                                                    </div>
                                                    <i class="fas fa-expand-alt btn-attachment-action"></i>
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="p-chat-item">
                                    <div class="p-chat-avatar p-chat-avatar-seller">
                                        {{ strtoupper(substr($ticket->user->name ?? 'S', 0, 2)) }}
                                    </div>
                                    <div class="p-chat-bubble p-chat-bubble-seller">
                                        <div class="p-chat-header">
                                            <div class="p-chat-sender">
                                                <span class="p-chat-name">{{ $ticket->user->name ?? 'Seller' }}</span>
                                                <span class="p-chat-role p-chat-role-seller">Seller</span>
                                            </div>
                                            <span class="p-chat-time">
                                                <i class="far fa-clock"></i> {{ $r->created_at->format('d M Y, H:i') }}
                                            </span>
                                        </div>
                                        <div class="p-chat-text">
                                            {!! nl2br(e($r->message)) !!}
                                        </div>
                                        @if($r->attachment)
                                            <div class="bubble-attachment">
                                                <button type="button" class="btn-attachment-preview" onclick="openAttachmentModal('{{ asset('storage/' . $r->attachment) }}', '{{ __('platform.attachment_label') }}')">
                                                    <div class="btn-attachment-icon">
                                                        <i class="fas fa-image"></i>
                                                    </div>
                                                    <div class="btn-attachment-info">
                                                        <span class="btn-attachment-title">{{ __('platform.attachment_label') }}</span>
                                                        <span class="btn-attachment-sub">Klik untuk melihat foto di pop-up</span>
                                                    </div>
                                                    <i class="fas fa-expand-alt btn-attachment-action"></i>
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endforeach

                    </div>

                    <!-- Admin Reply Box -->
                    <div class="p-reply-card">
                        <form action="{{ route('platform-admin.tickets.reply', $ticket->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="p-reply-header">
                                <div class="p-reply-title">
                                    <i class="fas fa-reply" style="color: #ed842c;"></i>
                                    <span>{{ __('platform.type_admin_reply') }}</span>
                                </div>
                                <div class="p-reply-status-wrap">
                                    <label for="status_select">{{ __('platform.change_status') }}:</label>
                                    <select name="status" id="status_select" class="p-filter-select" style="min-width: 160px;">
                                        <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>{{ __('platform.status_in_progress') }}</option>
                                        <option value="resolved" {{ $ticket->status === 'resolved' ? 'selected' : '' }}>{{ __('platform.status_resolved') }}</option>
                                        <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>{{ __('platform.status_waiting_open') }}</option>
                                        <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>{{ __('platform.status_closed') }}</option>
                                    </select>
                                </div>
                            </div>

                            <textarea name="message" class="p-reply-textarea" placeholder="{{ __('platform.reply_textarea_placeholder') }}" required></textarea>

                            <div class="p-reply-actions">
                                <div>
                                    <label for="admin_reply_attachment" class="btn-upload-clean">
                                        <i class="fas fa-paperclip"></i>
                                        <span>{{ __('platform.add_attachment') }}</span>
                                    </label>
                                    <input type="file" name="attachment" id="admin_reply_attachment" style="display: none;" accept="image/*" onchange="previewAdminAttachmentName(this)">
                                    <span id="adminAttachmentFileName" style="font-size: 11.5px; color: #16a34a; margin-left: 8px; font-weight: 600;"></span>
                                </div>

                                <button type="submit" class="btn-send-admin-reply">
                                    <i class="fas fa-paper-plane"></i>
                                    <span>{{ __('platform.send_reply_email') }}</span>
                                </button>
                            </div>
                        </form>
                    </div>

                </div>

                <!-- Right Sidebar: Ticket Metadata & Seller Details -->
                <div class="p-thread-sidebar">

                    <!-- Seller Card -->
                    <div class="p-sidebar-section">
                        <div class="p-sidebar-label">
                            <i class="fas fa-user-circle"></i>
                            <span>{{ __('platform.ticket_creator_profile') }}</span>
                        </div>
                        <div class="p-seller-profile-row">
                            <div class="p-seller-avatar">
                                {{ strtoupper(substr($ticket->user->name ?? 'U', 0, 2)) }}
                            </div>
                            <div class="p-seller-info-col">
                                <div class="p-seller-name">
                                    {{ $ticket->user->name ?? __('platform.deleted_user') }}
                                </div>
                                <div class="p-seller-email">
                                    {{ $ticket->user->email ?? '-' }}
                                </div>
                            </div>
                        </div>

                        @if($ticket->user)
                            <div style="display: flex; flex-direction: column; gap: 6px;">
                                <button type="button" class="btn-seller-portfolio" onclick="openSellerModal({{ $ticket->user->id }})">
                                    <i class="fas fa-user-shield"></i>
                                    <span>{{ __('platform.view_seller_portfolio') }}</span>
                                </button>
                                <a href="{{ route('platform-admin.users', ['search' => $ticket->user->email ?? $ticket->user->name]) }}" target="_blank" class="link-seller-mgmt">
                                    <i class="fas fa-external-link-alt"></i>
                                    <span>{{ __('platform.open_in_user_management') }} &rarr;</span>
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Quick Status & Priority Updater Form -->
                    <div class="p-sidebar-section">
                        <div class="p-sidebar-label">
                            <i class="fas fa-sliders-h"></i>
                            <span>{{ __('platform.ticket_settings') }}</span>
                        </div>

                        <form action="{{ route('platform-admin.tickets.status', $ticket->id) }}" method="POST">
                            @csrf
                            <div class="p-sidebar-field">
                                <label>{{ __('platform.ticket_status') }}</label>
                                <select name="status" class="p-filter-select" style="width: 100%; box-sizing: border-box;">
                                    <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>{{ __('platform.status_waiting_open') }}</option>
                                    <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>{{ __('platform.status_in_progress') }}</option>
                                    <option value="resolved" {{ $ticket->status === 'resolved' ? 'selected' : '' }}>{{ __('platform.status_resolved') }}</option>
                                    <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>{{ __('platform.status_closed') }}</option>
                                </select>
                            </div>

                            <div class="p-sidebar-field" style="margin-bottom: 14px;">
                                <label>{{ __('platform.priority_level') }}</label>
                                <select name="priority" class="p-filter-select" style="width: 100%; box-sizing: border-box;">
                                    <option value="urgent" {{ $ticket->priority === 'urgent' ? 'selected' : '' }}>{{ __('platform.priority_urgent') }}</option>
                                    <option value="high" {{ $ticket->priority === 'high' ? 'selected' : '' }}>{{ __('platform.priority_high') }}</option>
                                    <option value="medium" {{ $ticket->priority === 'medium' ? 'selected' : '' }}>{{ __('platform.priority_medium') }}</option>
                                    <option value="low" {{ $ticket->priority === 'low' ? 'selected' : '' }}>{{ __('platform.priority_low') }}</option>
                                </select>
                            </div>

                            <button type="submit" class="btn-update-sidebar">
                                <i class="fas fa-sync-alt"></i>
                                <span>{{ __('platform.update_status_priority') }}</span>
                            </button>
                        </form>
                    </div>

                    <!-- Meta Timeline -->
                    <div class="p-sidebar-section">
                        <div class="p-sidebar-label">
                            <i class="fas fa-history"></i>
                            <span>Riwayat Aktivitas</span>
                        </div>
                        <div class="p-timeline-list">
                            <div class="p-timeline-item">
                                <span>{{ __('platform.ticket_created_at') }}</span>
                                <strong>{{ $ticket->created_at->format('d M Y, H:i') }}</strong>
                            </div>
                            <div class="p-timeline-item">
                                <span>{{ __('platform.ticket_last_active') }}</span>
                                <strong>{{ $ticket->last_replied_at ? $ticket->last_replied_at->format('d M Y, H:i') : '-' }}</strong>
                            </div>
                            <div class="p-timeline-item">
                                <span>{{ __('platform.ticket_total_replies') }}</span>
                                <strong>{{ $ticket->replies->count() }} pesan</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Notice Banner -->
                    <div class="p-sidebar-notice">
                        <i class="fas fa-info-circle"></i>
                        <div>Seller akan menerima notifikasi email resmi setiap kali admin mengirim balasan.</div>
                    </div>

                </div>

            </div>

        </div>
    </div>

    <!-- Modal Detail / Inspeksi Lengkap Seller -->
    <div id="sellerModal" class="modal">
        <div class="modal-container modal-container-large">
            <div class="modal-header">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 36px; height: 36px; border-radius: 10px; background: #fff7ed; color: #ed842c; display: flex; align-items: center; justify-content: center; font-size: 15px;">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <h3 style="margin: 0; font-size: 16px; font-weight: 800; color: #0f172a;">{{ __('platform.seller_profile_inspection') }}</h3>
                </div>
                <button type="button" class="modal-close" onclick="closeSellerModal()" aria-label="Tutup">&times;</button>
            </div>
            <div class="modal-body" id="sellerModalBody">
                <div class="loading-spinner">
                    <i class="fas fa-spinner fa-spin" style="font-size: 28px; margin-bottom: 8px; display: block; color: #ed842c;"></i>
                    {{ __('platform.loading_data') }}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-modal-cancel" onclick="closeSellerModal()">{{ __('platform.close') }}</button>
            </div>
        </div>
    </div>

    <!-- Modal Preview Lampiran Gambar (Pop-up) -->
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
        window.PlatformUsersConfig = {
            userBaseUrl: '{{ url('/platform-admin/users') }}',
            appealsBaseUrl: '{{ url('/platform-admin/users/appeals') }}',
            csrfToken: '{{ csrf_token() }}',
            lang: {
                loading: '{{ __('platform.loading_data') }}',
                failed: '{{ __('platform.failed_to_load') }}',
                suspended: '{{ __('platform.suspended') }}',
                active: '{{ __('platform.active') }}',
                total_turnover: '{{ __('platform.total_turnover') }}',
                current_balance: '{{ __('platform.current_balance') }}',
                total_withdrawn: '{{ __('platform.total_withdrawn') }}',
                total_orders: '{{ __('platform.total_orders') }}',
                products_tab: '{{ __('platform.products_tab') }}',
                payouts_tab: '{{ __('platform.payouts_tab') }}',
                no_products: '{{ __('platform.no_products_seller') }}',
                no_payouts: '{{ __('platform.no_payouts_seller') }}'
            }
        };

        function previewAdminAttachmentName(input) {
            const span = document.getElementById('adminAttachmentFileName');
            if (input.files && input.files[0]) {
                span.textContent = 'File: ' + input.files[0].name;
            } else {
                span.textContent = '';
            }
        }

        function openAttachmentModal(imageUrl, title) {
            const modal = document.getElementById('imageAttachmentModal');
            const img = document.getElementById('attachmentModalImg');
            const openBtn = document.getElementById('attachmentModalDownload');
            const titleEl = document.getElementById('attachmentModalTitle');

            if (img) img.src = imageUrl;
            if (openBtn) openBtn.href = imageUrl;
            if (titleEl && title) titleEl.textContent = title;

            if (modal) {
                modal.classList.add('show');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeAttachmentModal() {
            const modal = document.getElementById('imageAttachmentModal');
            if (modal) {
                modal.classList.remove('show');
                document.body.style.overflow = '';
            }
            const img = document.getElementById('attachmentModalImg');
            if (img) img.src = '';
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeAttachmentModal();
            }
        });
    </script>
    @vite(['resources/js/app.js'])
    <script src="{{ asset('js/platform/notifications.js') }}"></script>
</body>
</html>
