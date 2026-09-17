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
    .dashboard-settings-wrapper button {
        font-family: inherit;
        font-size: 100%;
        line-height: 1.15;
        margin: 0;
    }
    .dashboard-settings-wrapper h1,
    .dashboard-settings-wrapper h2,
    .dashboard-settings-wrapper h3 {
        margin: 0;
    }
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
    .dashboard-settings-wrapper input:focus {
        outline: none;
    }
</style>
@stack('settings_styles')
@endpush

@section("page_title", "Settings")

@section("content")
<div class="dashboard-settings-wrapper font-sans" style="font-family: 'Plus Jakarta Sans', sans-serif;">

    @if(session('success'))
        <div class="mb-6 bg-green-50 text-green-700 p-4 rounded-lg flex items-center gap-3 text-sm font-medium border border-green-200">
            <i class="fas fa-check-circle text-lg"></i> {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 bg-red-50 text-red-700 p-4 rounded-lg text-sm border border-red-200">
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
        <a href="{{ route('admin.settings') }}" class="no-underline px-5 py-2.5 rounded-full text-sm transition-all duration-200 {{ $isProfile ? 'font-semibold bg-[#ED842C] text-white shadow-md shadow-[#ED842C]/20' : 'font-medium bg-white text-slate-600 hover:bg-slate-50 hover:text-[#ED842C] border border-slate-200 shadow-sm' }}">
            Profile
        </a>
        <a href="{{ route('admin.account') }}" class="no-underline px-5 py-2.5 rounded-full text-sm transition-all duration-200 {{ $isAccount ? 'font-semibold bg-[#ED842C] text-white shadow-md shadow-[#ED842C]/20' : 'font-medium bg-white text-slate-600 hover:bg-slate-50 hover:text-[#ED842C] border border-slate-200 shadow-sm' }}">
            Account
        </a>
        <a href="{{ route('admin.payout.index') }}" class="no-underline px-5 py-2.5 rounded-full text-sm transition-all duration-200 {{ $isPayout ? 'font-semibold bg-[#ED842C] text-white shadow-md shadow-[#ED842C]/20' : 'font-medium bg-white text-slate-600 hover:bg-slate-50 hover:text-[#ED842C] border border-slate-200 shadow-sm' }}">
            Payout
        </a>
        <a href="{{ route('admin.tickets.index') }}" class="no-underline px-5 py-2.5 rounded-full text-sm transition-all duration-200 {{ $isTickets ? 'font-semibold bg-[#ED842C] text-white shadow-md shadow-[#ED842C]/20' : 'font-medium bg-white text-slate-600 hover:bg-slate-50 hover:text-[#ED842C] border border-slate-200 shadow-sm' }}">
            Support Center
        </a>
    </div>

    @yield('settings_content')

</div>
@endsection
