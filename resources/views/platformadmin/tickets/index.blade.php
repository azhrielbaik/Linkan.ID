<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('platform.support_tickets') }} — Platform Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/platform/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/platform/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/platform/notifications.css') }}">
    <link rel="stylesheet" href="{{ asset('css/seller-tickets.css') }}">
    <link rel="stylesheet" href="{{ asset('css/platform/tickets.css') }}">
</head>
<body>

    {{-- Sidebar --}}
    @include('platformadmin.sidebar.sidebarplatform')

    <div class="platform-main">
        {{-- Header --}}
        <div class="platform-header">
            <div class="platform-header-left">
                <button class="hamburger-btn" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
                <h1>{{ __('platform.support_tickets') }}</h1>
            </div>
            <div class="header-right">
                @include('platformadmin.partials.notifications')
                <div class="header-user">
                    <div class="header-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                    <span>{{ Auth::user()->name }}</span>
                </div>
            </div>
        </div>

        <div class="content-wrapper">
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

            <!-- Filters Bar -->
            <div class="p-tickets-filters">
                <div class="p-ticket-tabs">
                    <a href="{{ route('platform-admin.tickets.index') }}" class="p-ticket-tab {{ empty($status) ? 'active' : '' }}">
                        {{ __('platform.all_tickets') }} ({{ $totalCount }})
                    </a>
                    <a href="{{ route('platform-admin.tickets.index', ['status' => 'open']) }}" class="p-ticket-tab {{ $status === 'open' ? 'active' : '' }}">
                        {{ __('platform.waiting_tickets') }} ({{ $openCount }})
                    </a>
                    <a href="{{ route('platform-admin.tickets.index', ['status' => 'in_progress']) }}" class="p-ticket-tab {{ $status === 'in_progress' ? 'active' : '' }}">
                        {{ __('platform.in_process_tickets') }} ({{ $inProgressCount }})
                    </a>
                    <a href="{{ route('platform-admin.tickets.index', ['status' => 'resolved']) }}" class="p-ticket-tab {{ $status === 'resolved' ? 'active' : '' }}">
                        {{ __('platform.status_resolved') }} ({{ $resolvedCount }})
                    </a>
                    <a href="{{ route('platform-admin.tickets.index', ['status' => 'closed']) }}" class="p-ticket-tab {{ $status === 'closed' ? 'active' : '' }}">
                        {{ __('platform.closed_tickets') }} ({{ $closedCount }})
                    </a>
                </div>

                <form action="{{ route('platform-admin.tickets.index') }}" method="GET" class="p-filter-inputs">
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

                    <input type="text" name="search" class="p-filter-search" placeholder="{{ __('platform.search_ticket_placeholder') }}" value="{{ $search }}">
                    <button type="submit" style="display: none;"></button>
                </form>
            </div>

            <!-- Tickets Table -->
            <div class="p-tickets-table-card">
                <table class="p-tickets-table">
                    <thead>
                        <tr>
                            <th>{{ __('platform.ticket_code_and_time') }}</th>
                            <th>{{ __('platform.seller_requester') }}</th>
                            <th>{{ __('platform.subject_and_category') }}</th>
                            <th>{{ __('platform.status') }}</th>
                            <th>{{ __('platform.priority') }}</th>
                            <th style="text-align: center;">{{ __('platform.action') }}</th>
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
                                <a href="{{ route('platform-admin.tickets.show', $t->id) }}" style="font-weight: 700; color: #0f172a; text-decoration: none; display: block; margin-bottom: 2px;">
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
                                <a href="{{ route('platform-admin.tickets.show', $t->id) }}" class="btn-open-ticket">
                                    <i class="fas fa-reply"></i> {{ __('platform.respond_ticket') }}
                                </a>
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

                @if($tickets->hasPages())
                    <div style="padding: 16px 20px; border-top: 1px solid #f1f5f9;">
                        {{ $tickets->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    <script src="{{ asset('js/platform/notifications.js') }}?v={{ time() }}"></script>
</body>
</html>
