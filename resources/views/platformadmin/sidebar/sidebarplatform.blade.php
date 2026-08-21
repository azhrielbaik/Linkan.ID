<style>
    /* =============================================
       PLATFORM ADMIN SIDEBAR — Identik Admin Seller
       #5A5BF1 | Plus Jakarta Sans
    ============================================= */
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    .sidebar {
        width: 250px;
        background-color: #5A5BF1;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        padding: 24px 20px;
        overflow: visible;
        z-index: 1000;
        transition: width 0.3s ease, transform 0.3s ease;
        box-shadow: 2px 0 16px rgba(90, 91, 241, 0.15);
        display: flex;
        flex-direction: column;
        border-right: none;
        font-family: 'Plus Jakarta Sans', sans-serif;
        box-sizing: border-box;
    }
    
    .sidebar-inner-scroll {
        overflow-y: auto;
        overflow-x: hidden;
        flex: 1;
        display: flex;
        flex-direction: column;
        scrollbar-width: none; /* Firefox */
        -ms-overflow-style: none; /* IE and Edge */
    }

    .sidebar-inner-scroll::-webkit-scrollbar {
        display: none;
        width: 0;
        height: 0;
    }
    
    /* Minimize Button */
    .sidebar-minimize-btn {
        position: absolute;
        top: 30px;
        right: -15px;
        width: 30px;
        height: 30px;
        background: #ffffff;
        border: none;
        border-radius: 50%;
        display: none;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 1001;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        color: #5A5BF1;
    }
    
    @media (min-width: 901px) {
        .sidebar-minimize-btn {
            display: flex;
        }
    }

    .sidebar-minimize-btn i {
        transform: rotate(90deg);
        transition: transform 0.3s ease;
    }

    /* Mini Sidebar Overrides - Desktop */
    @media (min-width: 901px) {
        body.mini-sidebar .sidebar {
            width: 80px;
            padding: 24px 10px;
        }
        
        body.mini-sidebar .sidebar-minimize-btn i {
            transform: rotate(270deg);
        }

        body.mini-sidebar .nav-text, 
        body.mini-sidebar .badge,
        body.mini-sidebar .menu-label {
            display: none;
        }

        body.mini-sidebar .logo {
            display: none;
        }
        
        body.mini-sidebar .lang-toggle,
        body.mini-sidebar .sidebar-close {
            display: none !important;
        }
        
        body.mini-sidebar .sidebar a {
            justify-content: center;
            padding: 12px 0;
        }
        
        body.mini-sidebar .sidebar a i {
            margin-right: 0;
            font-size: 20px;
        }
        
        body.mini-sidebar .marketing-tools form button {
            justify-content: center;
            padding: 12px 0;
        }
        
        body.mini-sidebar .marketing-tools form button i {
            margin-right: 0;
            font-size: 20px;
        }

        body.mini-sidebar .platform-main {
            margin-left: 80px !important;
        }
    }
    
    /* Responsive behavior */
    @media (max-width: 900px) {
        .sidebar {
            transform: translateX(-100%);
        }
        .sidebar.show {
            transform: translateX(0);
        }
    }

    .sidebar .logo-container {
        display: flex;
        align-items: center;
        margin-bottom: 32px;
        justify-content: space-between;
        width: 100%;
    }

    .sidebar .logo {
        width: 105px;
        height: auto;
        filter: brightness(0) invert(1);
    }

    /* Language Switcher Button (ID / EN) */
    .lang-toggle {
        display: flex;
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 20px;
        padding: 2px;
        align-items: center;
        gap: 2px;
    }
    
    .lang-toggle a {
        padding: 4px 10px !important;
        border-radius: 16px !important;
        font-size: 10px !important;
        font-weight: 800 !important;
        text-decoration: none !important;
        color: #ffffff !important;
        background: transparent;
        transition: all 0.2s ease !important;
        margin: 0 !important;
        display: inline-block !important;
        line-height: normal;
    }
    
    .lang-toggle a:hover {
        background: rgba(255, 255, 255, 0.3) !important;
    }
    
    .lang-toggle a.active {
        background: #ffffff !important;
        color: #5A5BF1 !important;
        box-shadow: 0 2px 6px rgba(0,0,0,0.12) !important;
    }

    .menu-label {
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: rgba(255, 255, 255, 0.6);
        padding: 0 18px 8px;
    }

    .sidebar-nav {
        flex: 1;
    }

    .sidebar a {
        display: flex;
        align-items: center;
        text-decoration: none;
        color: #ffffff;
        padding: 12px 18px;
        margin: 6px 0;
        border-radius: 12px;
        transition: all 0.2s ease;
        font-weight: 700;
        font-size: 15px;
        letter-spacing: -0.2px;
    }

    .sidebar a:hover {
        background-color: rgba(255, 255, 255, 0.16);
        color: #ffffff;
    }

    .sidebar a i {
        margin-right: 14px;
        width: 24px;
        font-size: 18px;
        text-align: center;
        color: #ffffff;
    }

    /* Active menu matching seller admin */
    .sidebar a.active {
        background-color: #ffffff !important;
        color: #5A5BF1 !important;
        font-weight: 800;
        box-shadow: 0 4px 14px rgba(0,0,0,0.06);
    }
    
    .sidebar a.active i {
        color: #5A5BF1 !important;
    }

    .sidebar hr {
        border: none;
        border-top: 1px solid rgba(255, 255, 255, 0.2);
        margin: 20px 0;
    }

    /* Logout Button Styling */
    .sidebar .marketing-tools form button {
        background: none;
        border: none;
        padding: 12px 18px;
        width: 100%;
        margin: 0;
        display: flex;
        align-items: center;
        color: #ffffff;
        cursor: pointer;
        border-radius: 12px;
        transition: all 0.2s ease;
        font-weight: 700;
        font-size: 15px;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .sidebar .marketing-tools form button:hover {
        background-color: rgba(255, 255, 255, 0.16);
        color: #ffffff;
    }
    
    .sidebar .marketing-tools form button i {
        width: 24px;
        font-size: 18px;
        text-align: center;
        margin-right: 14px;
        color: #ffffff;
        transition: color 0.2s ease;
    }
    
    /* Close button for mobile */
    .sidebar-close {
        display: none;
        color: #ffffff;
        font-size: 22px;
        cursor: pointer;
        padding: 4px;
    }
    
    @media (max-width: 900px) {
        .sidebar-close {
            display: block;
        }
    }
</style>

<script>
    if(localStorage.getItem('sidebar-mini') === 'true') {
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

{{-- SweetAlert2 CDN & Linkan Theme Integration --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .linkan-swal-popup {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        border-radius: 20px !important;
        padding: 26px !important;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15) !important;
    }
    .linkan-swal-title {
        font-size: 18px !important;
        font-weight: 800 !important;
        color: #1e293b !important;
        margin-bottom: 8px !important;
    }
    .linkan-swal-html {
        font-size: 13px !important;
        color: #64748b !important;
        line-height: 1.5 !important;
    }
    .linkan-swal-confirm-btn {
        background: #5A5BF1 !important;
        color: #ffffff !important;
        padding: 10px 22px !important;
        border-radius: 10px !important;
        font-weight: 700 !important;
        font-size: 13px !important;
        border: none !important;
        cursor: pointer !important;
        margin-left: 10px !important;
        transition: background 0.2s !important;
        font-family: 'Plus Jakarta Sans', sans-serif !important;
    }
    .linkan-swal-confirm-btn:hover {
        background: #4748d0 !important;
    }
    .linkan-swal-confirm-danger-btn {
        background: #dc2626 !important;
        color: #ffffff !important;
        padding: 10px 22px !important;
        border-radius: 10px !important;
        font-weight: 700 !important;
        font-size: 13px !important;
        border: none !important;
        cursor: pointer !important;
        margin-left: 10px !important;
        transition: background 0.2s !important;
        font-family: 'Plus Jakarta Sans', sans-serif !important;
    }
    .linkan-swal-confirm-danger-btn:hover {
        background: #b91c1c !important;
    }
    .linkan-swal-cancel-btn {
        background: #f1f5f9 !important;
        color: #475569 !important;
        padding: 10px 18px !important;
        border-radius: 10px !important;
        font-weight: 700 !important;
        font-size: 13px !important;
        border: none !important;
        cursor: pointer !important;
        transition: background 0.2s !important;
        font-family: 'Plus Jakarta Sans', sans-serif !important;
    }
    .linkan-swal-cancel-btn:hover {
        background: #e2e8f0 !important;
    }
</style>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        if (sidebar) {
            sidebar.classList.toggle('show');
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
            iconColor: options.iconColor || (isDanger ? '#dc2626' : '#5A5BF1'),
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
