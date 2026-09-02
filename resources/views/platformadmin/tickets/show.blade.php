<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('platform.ticket_detail') }} #{{ $ticket->ticket_code }} — Platform Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/platform/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/platform/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/platform/notifications.css') }}">
    <link rel="stylesheet" href="{{ asset('css/seller-tickets.css') }}">
    <link rel="stylesheet" href="{{ asset('css/platform/tickets.css') }}">
    <link rel="stylesheet" href="{{ asset('css/platform/users.css') }}">
</head>
<body>

    {{-- Sidebar --}}
    @include('platformadmin.sidebar.sidebarplatform')

    <div class="platform-main">
        {{-- Header --}}
        <div class="platform-header">
            <div class="platform-header-left">
                <button class="hamburger-btn" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
                <h1>{{ __('platform.ticket_detail') }} #{{ $ticket->ticket_code }}</h1>
            </div>
            <div class="header-right">
                @include('platformadmin.partials.notifications')
                @include('platformadmin.partials.header_profile')
            </div>
        </div>

        <div class="content-wrapper">
            <!-- Navigation Bar -->
            <div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                <a href="{{ route('platform-admin.tickets.index') }}" style="display: inline-flex; align-items: center; gap: 8px; color: #475569; text-decoration: none; font-size: 13px; font-weight: 700; background: #ffffff; border: 1px solid #e2e8f0; padding: 8px 16px; border-radius: 8px; transition: all 0.2s;">
                    <i class="fas fa-arrow-left"></i> {{ __('platform.back_to_tickets') }}
                </a>

                <div style="display: flex; gap: 8px; align-items: center;">
                    <span class="ticket-code-badge" style="font-size: 13px;">#{{ $ticket->ticket_code }}</span>
                    <span class="badge-status {{ $ticket->status_badge_class }}">
                        {{ $ticket->status_label }}
                    </span>
                    <span class="badge-priority {{ $ticket->priority_badge_class }}">
                        {{ $ticket->priority }}
                    </span>
                </div>
            </div>

            <!-- Thread Grid -->
            <div class="p-thread-grid">

                <!-- Main Chat Conversation -->
                <div class="p-thread-main">

                    <div style="border-bottom: 1px solid #f1f5f9; padding-bottom: 16px; margin-bottom: 20px;">
                        <h2 style="font-size: 20px; font-weight: 800; color: #0f172a; margin: 0 0 6px 0;">
                            {{ $ticket->subject }}
                        </h2>
                        <div style="font-size: 12px; color: #64748b;">
                            {{ __('platform.ticket_category') }}: <strong style="color: #ED842C;">{{ $ticket->category_label }}</strong> &bull; {{ __('platform.ticket_submitted_at') }}: <strong>{{ $ticket->created_at->format('d M Y, H:i') }} WIB</strong>
                        </div>
                    </div>

                    <!-- Initial Message from Seller -->
                    <div class="p-bubble p-bubble-seller">
                        <div class="bubble-header">
                            <div class="bubble-author">
                                <i class="fas fa-user-circle" style="color: #64748b;"></i>
                                {{ $ticket->user->name ?? 'User' }} (Seller)
                            </div>
                            <div class="bubble-time">
                                {{ $ticket->created_at->format('d M Y, H:i') }}
                            </div>
                        </div>
                        <div class="bubble-body">
                            {!! nl2br(e($ticket->message)) !!}
                        </div>
                    </div>

                    <!-- Reply Stream -->
                    @foreach($ticket->replies as $r)
                        @if($r->is_admin_reply)
                            <div class="p-bubble p-bubble-admin">
                                <div class="bubble-header">
                                    <div class="bubble-author">
                                        <i class="fas fa-shield-alt" style="color: #ED842C;"></i>
                                        {{ $r->user->name ?? 'Platform Admin' }} [Admin Platform]
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
                                        <div style="font-size: 11px; font-weight: 700; color: #64748b; margin-bottom: 6px;">{{ __('platform.attachment_label') }}</div>
                                        <a href="{{ asset('storage/' . $r->attachment) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $r->attachment) }}" alt="{{ __('platform.attachment_label') }}">
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="p-bubble p-bubble-seller">
                                <div class="bubble-header">
                                    <div class="bubble-author">
                                        <i class="fas fa-user-circle" style="color: #64748b;"></i>
                                        {{ $ticket->user->name ?? 'Seller' }}
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
                                        <div style="font-size: 11px; font-weight: 700; color: #64748b; margin-bottom: 6px;">{{ __('platform.attachment_label') }}</div>
                                        <a href="{{ asset('storage/' . $r->attachment) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $r->attachment) }}" alt="{{ __('platform.attachment_label') }}">
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endif
                    @endforeach

                    <!-- Admin Reply Box -->
                    <div class="p-reply-area">
                        <form action="{{ route('platform-admin.tickets.reply', $ticket->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                                <div style="font-size: 13px; font-weight: 700; color: #1e293b;">
                                    <i class="fas fa-reply" style="color: #ED842C;"></i> {{ __('platform.type_admin_reply') }}
                                </div>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <label for="status_select" style="font-size: 12px; font-weight: 600; color: #64748b;">{{ __('platform.change_status') }}</label>
                                    <select name="status" id="status_select" class="p-filter-select">
                                        <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>{{ __('platform.status_in_progress') }}</option>
                                        <option value="resolved" {{ $ticket->status === 'resolved' ? 'selected' : '' }}>{{ __('platform.status_resolved') }}</option>
                                        <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>{{ __('platform.status_waiting_open') }}</option>
                                        <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>{{ __('platform.status_closed') }}</option>
                                    </select>
                                </div>
                            </div>

                            <textarea name="message" class="p-reply-textarea" placeholder="{{ __('platform.reply_textarea_placeholder') }}" required></textarea>

                            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 14px; flex-wrap: wrap; gap: 12px;">
                                <div>
                                    <label for="admin_reply_attachment" style="font-size: 12px; color: #475569; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; background: #f8fafc; border: 1px solid #cbd5e1; padding: 7px 14px; border-radius: 8px;">
                                        <i class="fas fa-paperclip"></i> {{ __('platform.add_attachment') }}
                                    </label>
                                    <input type="file" name="attachment" id="admin_reply_attachment" style="display: none;" accept="image/*" onchange="previewAdminAttachmentName(this)">
                                    <span id="adminAttachmentFileName" style="font-size: 11px; color: #16a34a; margin-left: 6px; font-weight: 600;"></span>
                                </div>

                                <button type="submit" class="btn-send-admin-reply">
                                    <i class="fas fa-paper-plane"></i> {{ __('platform.send_reply_email') }}
                                </button>
                            </div>
                        </form>
                    </div>

                </div>

                <!-- Right Sidebar: Ticket Metadata & Seller Details -->
                <div class="p-thread-sidebar">

                    <!-- Seller Card -->
                    <div style="border-bottom: 1px solid #e2e8f0; padding-bottom: 16px; margin-bottom: 16px;">
                        <div style="font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 10px;">
                            {{ __('platform.ticket_creator_profile') }}
                        </div>
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                            <div class="seller-avatar-small" style="width: 40px; height: 40px; font-size: 14px;">
                                {{ strtoupper(substr($ticket->user->name ?? 'U', 0, 2)) }}
                            </div>
                            <div>
                                <div style="font-weight: 800; color: #0f172a; font-size: 14px;">
                                    {{ $ticket->user->name ?? __('platform.deleted_user') }}
                                </div>
                                <div style="font-size: 12px; color: #64748b;">
                                    {{ $ticket->user->email ?? '-' }}
                                </div>
                            </div>
                        </div>

                        @if($ticket->user)
                            <div style="margin-top: 10px; display: flex; flex-direction: column; gap: 6px;">
                                <button type="button" onclick="openSellerModal({{ $ticket->user->id }})" style="background: #fff8f2; border: 1px solid rgba(237, 132, 44, 0.3); border-radius: 8px; padding: 7px 12px; display: inline-flex; align-items: center; justify-content: center; gap: 6px; font-size: 12px; font-weight: 700; color: #ED842C; cursor: pointer; transition: all 0.2s;">
                                    <i class="fas fa-user-shield"></i> {{ __('platform.view_seller_portfolio') }}
                                </button>
                                <a href="{{ route('platform-admin.users', ['search' => $ticket->user->email ?? $ticket->user->name]) }}" target="_blank" style="display: inline-flex; align-items: center; justify-content: center; gap: 6px; font-size: 11px; font-weight: 600; color: #64748b; text-decoration: none; padding: 4px;">
                                    <i class="fas fa-external-link-alt"></i> {{ __('platform.open_in_user_management') }} &rarr;
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Quick Status & Priority Updater Form -->
                    <div style="border-bottom: 1px solid #e2e8f0; padding-bottom: 16px; margin-bottom: 16px;">
                        <div style="font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 10px;">
                            {{ __('platform.ticket_settings') }}
                        </div>

                        <form action="{{ route('platform-admin.tickets.status', $ticket->id) }}" method="POST">
                            @csrf
                            <div style="margin-bottom: 10px;">
                                <label style="font-size: 12px; font-weight: 600; color: #475569; display: block; margin-bottom: 4px;">{{ __('platform.ticket_status') }}</label>
                                <select name="status" class="p-filter-select" style="width: 100%; box-sizing: border-box;">
                                    <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>{{ __('platform.status_waiting_open') }}</option>
                                    <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>{{ __('platform.status_in_progress') }}</option>
                                    <option value="resolved" {{ $ticket->status === 'resolved' ? 'selected' : '' }}>{{ __('platform.status_resolved') }}</option>
                                    <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>{{ __('platform.status_closed') }}</option>
                                </select>
                            </div>

                            <div style="margin-bottom: 14px;">
                                <label style="font-size: 12px; font-weight: 600; color: #475569; display: block; margin-bottom: 4px;">{{ __('platform.priority_level') }}</label>
                                <select name="priority" class="p-filter-select" style="width: 100%; box-sizing: border-box;">
                                    <option value="urgent" {{ $ticket->priority === 'urgent' ? 'selected' : '' }}>{{ __('platform.priority_urgent') }}</option>
                                    <option value="high" {{ $ticket->priority === 'high' ? 'selected' : '' }}>{{ __('platform.priority_high') }}</option>
                                    <option value="medium" {{ $ticket->priority === 'medium' ? 'selected' : '' }}>{{ __('platform.priority_medium') }}</option>
                                    <option value="low" {{ $ticket->priority === 'low' ? 'selected' : '' }}>{{ __('platform.priority_low') }}</option>
                                </select>
                            </div>

                            <button type="submit" style="width: 100%; padding: 8px; background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 12px; font-weight: 700; color: #334155; cursor: pointer; transition: background 0.2s;">
                                <i class="fas fa-sync-alt"></i> {{ __('platform.update_status_priority') }}
                            </button>
                        </form>
                    </div>

                    <!-- Meta Timeline -->
                    <div style="font-size: 12px; color: #64748b; line-height: 1.8;">
                        <div><strong>{{ __('platform.ticket_created_at') }}:</strong> {{ $ticket->created_at->format('d M Y, H:i') }} WIB</div>
                        <div><strong>{{ __('platform.ticket_last_active') }}:</strong> {{ $ticket->last_replied_at ? $ticket->last_replied_at->format('d M Y, H:i') . ' WIB' : '-' }}</div>
                        <div><strong>{{ __('platform.ticket_total_replies') }}:</strong> {{ $ticket->replies->count() }} {{ __('platform.message_count') }}</div>
                    </div>

                </div>

            </div>

        </div>
    </div>

    <!-- Modal Detail / Inspeksi Lengkap Seller -->
    <div id="sellerModal" class="modal">
        <div class="modal-container modal-container-large">
            <div class="modal-header">
                <h3><i class="fas fa-user-shield" style="color: #ED842C;"></i> {{ __('platform.seller_profile_inspection') }}</h3>
                <button type="button" class="modal-close" onclick="closeSellerModal()">&times;</button>
            </div>
            <div class="modal-body" id="sellerModalBody">
                <div class="loading-spinner">
                    <i class="fas fa-spinner fa-spin" style="font-size: 28px; margin-bottom: 8px; display: block;"></i>
                    {{ __('platform.loading_data') }}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-modal-cancel" onclick="closeSellerModal()">{{ __('platform.close') }}</button>
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
    </script>
    <script src="{{ asset('js/platform/notifications.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/platform/users.js') }}?v={{ time() }}"></script>
</body>
</html>
