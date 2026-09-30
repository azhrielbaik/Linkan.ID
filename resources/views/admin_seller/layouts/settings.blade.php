@extends("admin_seller.layouts.app")

@section("page_title", "Settings")

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/settings-tailwind.css') }}?v={{ file_exists(public_path('css/settings-tailwind.css')) ? filemtime(public_path('css/settings-tailwind.css')) : time() }}">
<style>
    /* Scoped base resets for the settings page since preflight is disabled */
    .dashboard-settings-wrapper * {
        box-sizing: border-box;
    }
    .dashboard-settings-wrapper input,
    .dashboard-settings-wrapper button,
    .dashboard-settings-wrapper select,
    .dashboard-settings-wrapper textarea {
        font-family: inherit;
        font-size: 100%;
        line-height: 1.15;
        margin: 0;
    }
    .dashboard-settings-wrapper h1,
    .dashboard-settings-wrapper h2,
    .dashboard-settings-wrapper h3,
    .dashboard-settings-wrapper h4,
    .dashboard-settings-wrapper p {
        margin: 0;
    }
    /* Fix missing borders due to disabled preflight */
    .dashboard-settings-wrapper *,
    .dashboard-settings-wrapper ::before,
    .dashboard-settings-wrapper ::after {
        border-width: 0;
        border-style: solid;
        border-color: #e5e7eb;
    }
    .dashboard-settings-wrapper input:focus,
    .dashboard-settings-wrapper select:focus,
    .dashboard-settings-wrapper textarea:focus {
        outline: none;
    }

    /* Navigation Tabs */
    .settings-tab-btn {
        display: inline-flex;
        align-items: center;
        text-decoration: none;
        padding: 0.625rem 1.25rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 500;
        background-color: #ffffff;
        color: #475569;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .settings-tab-btn:hover {
        background-color: #f8fafc;
        color: #ED842C;
        border-color: #cbd5e1;
    }
    .settings-tab-btn.active {
        font-weight: 600;
        background-color: #ED842C;
        color: #ffffff;
        border-color: #ED842C;
        box-shadow: 0 4px 6px -1px rgba(237, 132, 44, 0.2), 0 2px 4px -2px rgba(237, 132, 44, 0.2);
    }

    /* Alerts */
    .settings-alert-success {
        background-color: #ecfdf5;
        border: 1px solid #a7f3d0;
        color: #047857;
    }
    .settings-alert-error {
        background-color: #fef2f2;
        border: 1px solid #fecaca;
        color: #b91c1c;
    }

    /* Select Chevron */
    .settings-select {
        background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%234a5568%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E");
        background-repeat: no-repeat;
        background-position: right 12px top 50%;
        background-size: 10px auto;
    }

    /* =========================================================
       DARK MODE OPTIMIZATION FOR SETTINGS LAYOUT & PAGES
       ========================================================= */
    html.dark .dashboard-settings-wrapper {
        color: #f1f5f9;
    }

    /* Navigation Tabs Dark Mode */
    html.dark .settings-tab-btn {
        background-color: #1c212e !important;
        color: #94a3b8 !important;
        border-color: #2a3241 !important;
        box-shadow: none !important;
    }
    html.dark .settings-tab-btn:hover {
        background-color: #2a3241 !important;
        color: #ED842C !important;
        border-color: #3b465c !important;
    }
    html.dark .settings-tab-btn.active {
        background-color: #ED842C !important;
        color: #ffffff !important;
        border-color: #ED842C !important;
        box-shadow: 0 4px 12px rgba(237, 132, 44, 0.35) !important;
    }

    /* Cards & Containers */
    html.dark .dashboard-settings-wrapper .bg-white {
        background-color: #1c212e !important;
        border-color: #2a3241 !important;
        color: #ffffff !important;
    }
    html.dark .dashboard-settings-wrapper .border-slate-100,
    html.dark .dashboard-settings-wrapper .border-slate-200,
    html.dark .dashboard-settings-wrapper .border-slate-300,
    html.dark .dashboard-settings-wrapper .border-slate-200\/60,
    html.dark .dashboard-settings-wrapper .border-slate-200\/80,
    html.dark .dashboard-settings-wrapper .border-slate-200\/90 {
        border-color: #2a3241 !important;
    }
    html.dark .dashboard-settings-wrapper .divide-slate-100 > :not([hidden]) ~ :not([hidden]),
    html.dark .dashboard-settings-wrapper .divide-slate-200 > :not([hidden]) ~ :not([hidden]) {
        border-color: #2a3241 !important;
    }
    html.dark .dashboard-settings-wrapper .bg-slate-50,
    html.dark .dashboard-settings-wrapper .bg-slate-50\/50,
    html.dark .dashboard-settings-wrapper .bg-slate-50\/60,
    html.dark .dashboard-settings-wrapper .bg-slate-50\/80,
    html.dark .dashboard-settings-wrapper .bg-slate-100 {
        background-color: #0f131c !important;
        border-color: #2a3241 !important;
    }
    html.dark .dashboard-settings-wrapper .hover\:bg-slate-50:hover,
    html.dark .dashboard-settings-wrapper .hover\:bg-slate-100:hover,
    html.dark .dashboard-settings-wrapper .hover\:bg-slate-50\/50:hover {
        background-color: #222938 !important;
    }

    /* Typography */
    html.dark .dashboard-settings-wrapper .text-slate-900,
    html.dark .dashboard-settings-wrapper .text-slate-800,
    html.dark .dashboard-settings-wrapper .text-slate-700 {
        color: #f1f5f9 !important;
    }
    html.dark .dashboard-settings-wrapper .text-slate-600,
    html.dark .dashboard-settings-wrapper .text-slate-500 {
        color: #94a3b8 !important;
    }
    html.dark .dashboard-settings-wrapper .text-slate-400 {
        color: #64748b !important;
    }

    /* Inputs & Form Controls */
    html.dark .dashboard-settings-wrapper input,
    html.dark .dashboard-settings-wrapper textarea,
    html.dark .dashboard-settings-wrapper select {
        background-color: #0f131c !important;
        border-color: #2a3241 !important;
        color: #f1f5f9 !important;
    }
    html.dark .dashboard-settings-wrapper input:focus,
    html.dark .dashboard-settings-wrapper textarea:focus,
    html.dark .dashboard-settings-wrapper select:focus {
        border-color: #ED842C !important;
        box-shadow: 0 0 0 2px rgba(237, 132, 44, 0.25) !important;
    }
    html.dark .dashboard-settings-wrapper input::placeholder,
    html.dark .dashboard-settings-wrapper textarea::placeholder {
        color: #64748b !important;
    }
    html.dark .dashboard-settings-wrapper input:disabled,
    html.dark .dashboard-settings-wrapper input[readonly] {
        background-color: #141824 !important;
        border-color: #2a3241 !important;
        color: #64748b !important;
    }

    /* Select Element Dark Mode Chevron */
    html.dark .settings-select {
        background-color: #0f131c !important;
        border-color: #2a3241 !important;
        color: #f1f5f9 !important;
        background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%2394a3b8%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E") !important;
    }
    html.dark .dashboard-settings-wrapper option {
        background-color: #1c212e !important;
        color: #f1f5f9 !important;
    }

    /* Alert / Status Boxes */
    html.dark .settings-alert-success,
    html.dark .dashboard-settings-wrapper .bg-green-50,
    html.dark .dashboard-settings-wrapper .bg-emerald-50,
    html.dark .dashboard-settings-wrapper .bg-emerald-50\/80 {
        background-color: rgba(16, 185, 129, 0.12) !important;
        border-color: rgba(16, 185, 129, 0.3) !important;
        color: #34d399 !important;
    }
    html.dark .dashboard-settings-wrapper .bg-green-100,
    html.dark .dashboard-settings-wrapper .bg-emerald-100 {
        background-color: rgba(16, 185, 129, 0.2) !important;
        color: #34d399 !important;
    }
    html.dark .dashboard-settings-wrapper .text-green-700,
    html.dark .dashboard-settings-wrapper .text-emerald-700,
    html.dark .dashboard-settings-wrapper .text-emerald-600 {
        color: #34d399 !important;
    }

    html.dark .settings-alert-error,
    html.dark .dashboard-settings-wrapper .bg-red-50,
    html.dark .dashboard-settings-wrapper .bg-red-50\/40 {
        background-color: rgba(239, 68, 68, 0.12) !important;
        border-color: rgba(239, 68, 68, 0.3) !important;
    }
    html.dark .dashboard-settings-wrapper .bg-red-100 {
        background-color: rgba(239, 68, 68, 0.2) !important;
    }
    html.dark .dashboard-settings-wrapper .text-red-700,
    html.dark .dashboard-settings-wrapper .text-red-600 {
        color: #f87171 !important;
    }

    html.dark .dashboard-settings-wrapper .bg-amber-50,
    html.dark .dashboard-settings-wrapper .bg-amber-50\/70 {
        background-color: rgba(245, 158, 11, 0.12) !important;
        border-color: rgba(245, 158, 11, 0.3) !important;
    }
    html.dark .dashboard-settings-wrapper .bg-amber-100 {
        background-color: rgba(245, 158, 11, 0.2) !important;
    }
    html.dark .dashboard-settings-wrapper .text-amber-900,
    html.dark .dashboard-settings-wrapper .text-amber-800,
    html.dark .dashboard-settings-wrapper .text-amber-700,
    html.dark .dashboard-settings-wrapper .text-amber-600 {
        color: #fbbf24 !important;
    }

    html.dark .dashboard-settings-wrapper .bg-blue-50,
    html.dark .dashboard-settings-wrapper .bg-blue-50\/80 {
        background-color: rgba(59, 130, 246, 0.12) !important;
        border-color: rgba(59, 130, 246, 0.3) !important;
    }
    html.dark .dashboard-settings-wrapper .bg-blue-100 {
        background-color: rgba(59, 130, 246, 0.2) !important;
    }
    html.dark .dashboard-settings-wrapper .text-blue-900,
    html.dark .dashboard-settings-wrapper .text-blue-700 {
        color: #93c5fd !important;
    }

    html.dark .dashboard-settings-wrapper .bg-orange-50,
    html.dark .dashboard-settings-wrapper .bg-orange-100 {
        background-color: rgba(237, 132, 44, 0.15) !important;
        color: #ED842C !important;
    }

    /* Modals in Dark Mode */
    html.dark #disconnectGoogleModal .bg-white,
    html.dark #deleteConfirmationModal .bg-white {
        background-color: #1c212e !important;
        border: 1px solid #2a3241 !important;
        color: #f1f5f9 !important;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7) !important;
    }
</style>
@stack('settings_styles')
@endpush

@section("content")
<div class="dashboard-settings-wrapper font-sans" style="font-family: 'Plus Jakarta Sans', sans-serif;">

    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg flex items-center gap-3 text-sm font-medium settings-alert-success">
            <i class="fas fa-check-circle text-lg"></i> {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 p-4 rounded-lg text-sm settings-alert-error">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Navigation Tabs -->
    <div class="flex flex-wrap gap-2 mb-8">
        @php
            $isProfile = request()->routeIs('admin.settings');
            $isAccount = request()->routeIs('admin.account');
            $isPayout = request()->routeIs('admin.payout.*') || request()->routeIs('admin.payouts.*');
            $isTickets = request()->routeIs('admin.tickets.*');
        @endphp
        <a href="{{ route('admin.settings') }}" class="settings-tab-btn {{ $isProfile ? 'active' : '' }}">
            Profile
        </a>
        <a href="{{ route('admin.account') }}" class="settings-tab-btn {{ $isAccount ? 'active' : '' }}">
            Account
        </a>
        <a href="{{ route('admin.payout.index') }}" class="settings-tab-btn {{ $isPayout ? 'active' : '' }}">
            Payout
        </a>
        <a href="{{ route('admin.tickets.index') }}" class="settings-tab-btn {{ $isTickets ? 'active' : '' }}">
            Support Center
        </a>
    </div>

    @yield('settings_content')

</div>
@endsection
