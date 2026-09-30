@extends("admin_seller.layouts.settings")

@section("page_title", "Pengaturan Akun")

@section("settings_content")
<!-- Main Untitled UI Style Card Container -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

    <!-- Header Section -->
    <div class="p-4 sm:p-6 lg:p-8 pb-5 sm:pb-6 border-b border-slate-200">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight">Akun & Keamanan</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1 leading-relaxed">Kelola kredensial login, alamat email, sesi perangkat, dan preferensi akun Anda.</p>
            </div>
        </div>
    </div>

    <!-- Banner Info Card -->
    <div class="p-4 sm:p-6 lg:p-8 pb-0">
        <div class="bg-slate-50/80 border border-slate-200/90 rounded-xl p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-start sm:items-center gap-3 sm:gap-3.5 min-w-0">
                <i class="fa-solid fa-user"></i>
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-2 flex-wrap">
                        <p class="text-sm font-bold text-slate-900">Akun Seller Linkan.ID</p>
                    </div>
                    <p class="text-xs text-slate-500 mt-0.5 break-words">
                        Terdaftar sejak {{ $user->created_at ? $user->created_at->translatedFormat('F Y') : '2024' }}
                    </p>
                </div>
            </div>
            <div class="flex items-center w-full sm:w-auto shrink-0 pt-2 sm:pt-0 border-t sm:border-t-0 border-slate-200/60">
                <a href="{{ route('admin.settings') }}" class="w-full sm:w-auto justify-center px-3.5 py-2 bg-white hover:bg-slate-100 border border-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition shadow-xs no-underline inline-flex items-center gap-1.5">
                    <i class="fas fa-user-pen text-slate-400 text-[11px]"></i> Edit Profil Publik
                </a>
            </div>
        </div>
    </div>

    <!-- 2-Column Horizontal Rows Container -->
    <div class="p-4 sm:p-6 lg:p-8 divide-y divide-slate-200">
        @include('admin_seller.features.account.partials._password')
        @include('admin_seller.features.account.partials._email')
        @include('admin_seller.features.account.partials._google')
        @include('admin_seller.features.account.partials._sessions')
        @include('admin_seller.features.account.partials._login_history')
        @include('admin_seller.features.account.partials._notifications')
        @include('admin_seller.features.account.partials._danger_zone')
    </div>

</div>

@include('admin_seller.features.account.partials._modals')
@endsection

@push("scripts")
<script src="{{ asset('js/pages/account.js') }}?v={{ time() }}"></script>
@endpush
