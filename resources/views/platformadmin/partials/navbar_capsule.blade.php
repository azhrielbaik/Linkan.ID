{{-- Modern Expanding Capsule Navbar for Platform Admin --}}
<div class="platform-navbar-capsule" role="navigation" aria-label="Platform Quick Navigation">
    {{-- 1. Dashboard --}}
    <a href="{{ route('platform-admin.dashboard') }}"
       class="nav-capsule-tab {{ request()->routeIs('platform-admin.dashboard') ? 'is-current-page' : '' }}"
       title="{{ __('sidebar.dashboard') }}"
       aria-label="{{ __('sidebar.dashboard') }}">
        <i class="fas fa-home nav-capsule-icon"></i>
        <span class="nav-capsule-label">{{ __('sidebar.dashboard') }}</span>
    </a>

    {{-- 2. User Management --}}
    <a href="{{ route('platform-admin.users') }}"
       class="nav-capsule-tab {{ request()->routeIs('platform-admin.users*') ? 'is-current-page' : '' }}"
       title="{{ __('sidebar.user_management') }}"
       aria-label="{{ __('sidebar.user_management') }}">
        <i class="fas fa-users nav-capsule-icon"></i>
        <span class="nav-capsule-label">{{ __('sidebar.user_management') }}</span>
    </a>

    {{-- 3. Product Management --}}
    <a href="{{ route('platform-admin.products.index') }}"
       class="nav-capsule-tab {{ request()->routeIs('platform-admin.products*') ? 'is-current-page' : '' }}"
       title="{{ __('sidebar.product_management') }}"
       aria-label="{{ __('sidebar.product_management') }}">
        <i class="fas fa-boxes nav-capsule-icon"></i>
        <span class="nav-capsule-label">{{ __('sidebar.product_management') }}</span>
    </a>

    {{-- 4. Verification --}}
    <a href="{{ route('platform-admin.verifikasi') }}"
       class="nav-capsule-tab {{ request()->routeIs('platform-admin.verifikasi*') ? 'is-current-page' : '' }}"
       title="{{ __('sidebar.verification') }}"
       aria-label="{{ __('sidebar.verification') }}">
        <i class="fas fa-check-circle nav-capsule-icon"></i>
        <span class="nav-capsule-label">{{ __('sidebar.verification') }}</span>
    </a>

    {{-- Separator 1 --}}
    <div class="nav-capsule-separator"></div>

    {{-- 5. Payouts --}}
    <a href="{{ route('platform-admin.payouts.index') }}"
       class="nav-capsule-tab {{ request()->routeIs('platform-admin.payouts*') ? 'is-current-page' : '' }}"
       title="{{ __('sidebar.payout_management') }}"
       aria-label="{{ __('sidebar.payout_management') }}">
        <i class="fas fa-money-bill-wave nav-capsule-icon"></i>
        <span class="nav-capsule-label">{{ __('sidebar.payout_management') }}</span>
    </a>

    {{-- 6. Support Tickets --}}
    <a href="{{ route('platform-admin.tickets.index') }}"
       class="nav-capsule-tab {{ request()->routeIs('platform-admin.tickets*') ? 'is-current-page' : '' }}"
       title="{{ __('sidebar.help_center') }}"
       aria-label="{{ __('sidebar.help_center') }}">
        <i class="fas fa-headset nav-capsule-icon"></i>
        <span class="nav-capsule-label">{{ __('sidebar.help_center') }}</span>
    </a>

    {{-- 7. Activity Logs --}}
    <a href="{{ route('platform-admin.logs.activity') }}"
       class="nav-capsule-tab {{ request()->routeIs('platform-admin.logs*') ? 'is-current-page' : '' }}"
       title="{{ __('sidebar.activity_logs') }}"
       aria-label="{{ __('sidebar.activity_logs') }}">
        <i class="fas fa-history nav-capsule-icon"></i>
        <span class="nav-capsule-label">{{ __('sidebar.activity_logs') }}</span>
    </a>

    {{-- Separator 2 --}}
    <div class="nav-capsule-separator"></div>

    {{-- 8. Platform Settings --}}
    <a href="{{ route('platform-admin.settings.index') }}"
       class="nav-capsule-tab {{ request()->routeIs('platform-admin.settings*') ? 'is-current-page' : '' }}"
       title="{{ __('sidebar.platform_settings') }}"
       aria-label="{{ __('sidebar.platform_settings') }}">
        <i class="fas fa-cog nav-capsule-icon"></i>
        <span class="nav-capsule-label">{{ __('sidebar.platform_settings') }}</span>
    </a>
</div>
