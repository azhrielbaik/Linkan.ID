{{-- Linkan.ID Platform Admin Sidebar --}}
<link rel="stylesheet" href="{{ asset('css/platform/sidebar.css') }}?v={{ time() }}">

<script>
    if (localStorage.getItem('sidebar-mini') === 'true') {
        document.body.classList.add('mini-sidebar');
    }
</script>

<div class="sidebar" id="sidebar">
    <div class="sidebar-minimize-btn" onclick="toggleMinimize()">
        <i class="fas fa-caret-down"></i>
    </div>
    
    <div class="sidebar-inner-scroll">
        <div class="logo-container">
            <img src="{{ asset('images/Logo.svg') }}" alt="Logo" class="logo">
            
            <div style="display: flex; align-items: center; gap: 8px;">
                <div class="lang-toggle">
                    <a href="{{ route('lang.switch', 'id') }}" data-turbo="false" class="{{ App::getLocale() == 'id' ? 'active' : '' }}">ID</a>
                    <a href="{{ route('lang.switch', 'en') }}" data-turbo="false" class="{{ App::getLocale() == 'en' ? 'active' : '' }}">EN</a>
                </div>
                <i class="fas fa-times sidebar-close" onclick="toggleSidebar()"></i>
            </div>
        </div>

        <div class="menu-label">{{ __('sidebar.main_menu') }}</div>

        <div class="sidebar-nav">
            <a href="{{ route('platform-admin.dashboard') }}" class="{{ request()->routeIs('platform-admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i><span class="nav-text">{{ __('sidebar.dashboard') }}</span>
            </a>
            
            <a href="{{ route('platform-admin.users') }}" class="{{ request()->routeIs('platform-admin.users*') ? 'active' : '' }}">
                <i class="fas fa-users"></i><span class="nav-text">{{ __('sidebar.user_management') }}</span>
            </a>

            <a href="{{ route('platform-admin.products.index') }}" class="{{ request()->routeIs('platform-admin.products*') ? 'active' : '' }}">
                <i class="fas fa-boxes"></i><span class="nav-text">{{ __('sidebar.product_management') }}</span>
            </a>

            <a href="{{ route('platform-admin.verifikasi') }}" class="{{ request()->routeIs('platform-admin.verifikasi*') ? 'active' : '' }}">
                <i class="fas fa-check-circle"></i><span class="nav-text">{{ __('sidebar.verification') }}</span>
            </a>

            <a href="{{ route('platform-admin.payouts.index') }}" class="{{ request()->routeIs('platform-admin.payouts*') ? 'active' : '' }}">
                <i class="fas fa-money-bill-wave"></i><span class="nav-text">{{ __('sidebar.payout_management') }}</span>
            </a>

            <a href="{{ route('platform-admin.logs.activity') }}" class="{{ request()->routeIs('platform-admin.logs.activity*') ? 'active' : '' }}">
                <i class="fas fa-clipboard-list"></i><span class="nav-text">{{ __('sidebar.activity_logs') }}</span>
            </a>

            <a href="{{ route('platform-admin.logs.transactions') }}" class="{{ request()->routeIs('platform-admin.logs.transactions*') ? 'active' : '' }}">
                <i class="fas fa-receipt"></i><span class="nav-text">{{ __('sidebar.transaction_logs') }}</span>
            </a>

            <a href="{{ route('platform-admin.tickets.index') }}" class="{{ request()->routeIs('platform-admin.tickets*') ? 'active' : '' }}">
                <i class="fas fa-headset"></i><span class="nav-text">{{ __('platform.pusat_bantuan') }}</span>
                @php
                    $pendingTicketsCount = \App\Models\SupportTicket::where('status', 'open')->count();
                @endphp
                @if($pendingTicketsCount > 0)
                    <span style="background: #ef4444; color: #fff; font-size: 11px; font-weight: 800; padding: 2px 7px; border-radius: 10px; margin-left: auto;">
                        {{ $pendingTicketsCount }}
                    </span>
                @endif
            </a>

            <a href="{{ route('platform-admin.settings.index') }}" class="{{ request()->routeIs('platform-admin.settings*') ? 'active' : '' }}">
                <i class="fas fa-cog"></i><span class="nav-text">{{ __('sidebar.platform_settings') }}</span>
            </a>
        </div>

        <hr>

        <div class="marketing-tools">
            <form action="{{ route('logout') }}" method="POST" style="display: flex; align-items: center;" id="platformLogoutForm">
                @csrf
                <button type="button" onclick="confirmPlatformLogout()">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="nav-text">{{ __('sidebar.logout') }}</span>
                </button>
            </form>
        </div>
    </div>
</div>
<div class="sidebar-backdrop" id="sidebarBackdrop" onclick="toggleSidebar()"></div>

{{-- SweetAlert2 CDN & Linkan Integration --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.getElementById('sidebarBackdrop');
        if (sidebar) {
            sidebar.classList.toggle('show');
            if (backdrop) {
                backdrop.classList.toggle('show');
            }
        }
    }
    
    function toggleMinimize() {
        document.body.classList.toggle('mini-sidebar');
        const isMini = document.body.classList.contains('mini-sidebar');
        localStorage.setItem('sidebar-mini', isMini ? 'true' : 'false');
    }

    // Global Modal Konfirmasi Linkan.ID
    window.showConfirmModal = function(options) {
        const isDanger = options.confirmDanger || false;
        const confirmBtnClass = isDanger ? 'linkan-swal-confirm-danger-btn' : 'linkan-swal-confirm-btn';

        Swal.fire({
            title: options.title || 'Konfirmasi Aksi',
            text: options.text || 'Apakah Anda yakin ingin melanjutkan tindakan ini?',
            icon: options.icon || (isDanger ? 'warning' : 'question'),
            iconColor: options.iconColor || (isDanger ? '#dc2626' : '#ED842C'),
            showCancelButton: true,
            confirmButtonText: options.confirmText || (isDanger ? 'Ya, Lanjutkan' : 'Ya, Setujui'),
            cancelButtonText: options.cancelText || 'Batal',
            customClass: {
                popup: 'linkan-swal-popup',
                title: 'linkan-swal-title',
                htmlContainer: 'linkan-swal-html',
                confirmButton: confirmBtnClass,
                cancelButton: 'linkan-swal-cancel-btn'
            },
            buttonsStyling: false,
            reverseButtons: true,
            backdrop: 'rgba(15, 23, 42, 0.45)'
        }).then((result) => {
            if (result.isConfirmed && typeof options.onConfirm === 'function') {
                options.onConfirm();
            }
        });
    };

    function confirmPlatformLogout() {
        showConfirmModal({
            title: 'Keluar dari Sesi Admin?',
            text: 'Anda akan keluar dari panel kontrol Platform Admin Linkan.ID.',
            icon: 'question',
            confirmText: '<i class="fas fa-sign-out-alt"></i> Ya, Logout',
            confirmDanger: true,
            onConfirm: () => {
                document.getElementById('platformLogoutForm').submit();
            }
        });
    }
</script>

{{-- Floating Toast Notifications for Platform Admin --}}
@include('platformadmin.partials.toast')

