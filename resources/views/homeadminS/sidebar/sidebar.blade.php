<style>
    /* Sidebar Styles */
    .sidebar {
        width: 250px;
        background-color: #fafafa;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        padding: 24px 20px;
        overflow-y: auto;
        z-index: 1000;
        transition: transform 0.3s ease;
        box-shadow: 2px 0 10px rgba(0,0,0,0.03);
        display: flex;
        flex-direction: column;
        border-right: 1px solid #eaeaea;
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
        margin-bottom: 35px;
        gap: 10px;
        font-weight: 800;
        font-size: 24px;
        color: #333;
    }

    .sidebar .logo {
        width: 32px;
        height: auto;
    }

    .lang-toggle {
        display: flex;
        background: #f0f0f0;
        border-radius: 20px;
        padding: 4px;
        align-items: center;
        gap: 2px;
    }
    
    .lang-toggle a {
        padding: 4px 12px !important;
        border-radius: 16px !important;
        font-size: 11px !important;
        font-weight: 800 !important;
        text-decoration: none !important;
        color: #888 !important;
        background: transparent;
        transition: all 0.3s ease !important;
        margin: 0 !important;
    }
    
    .lang-toggle a:hover {
        background: #e4e4e4;
    }
    
    .lang-toggle a.active {
        background: #FF9040 !important;
        color: #fff !important;
        box-shadow: 0 2px 6px rgba(255,144,64,0.3) !important;
    }

    .sidebar-nav {
        flex: 1;
    }

    .sidebar a {
        display: flex;
        align-items: center;
        text-decoration: none;
        color: #1a1a1a;
        padding: 12px 16px;
        margin: 4px 0;
        border-radius: 8px;
        transition: all 0.2s ease;
        font-weight: 500;
        font-size: 15px;
    }

    .sidebar a:hover {
        background-color: #f0f0f0;
    }

    .sidebar a i {
        margin-right: 14px;
        width: 22px;
        font-size: 18px;
        text-align: center;
        color: #1a1a1a;
    }

    /* Active menu matching reference */
    .sidebar a.active {
        background-color: #FFF0E5; /* Light peach orange */
        color: #FF9040; /* Peach Orange */
    }
    .sidebar a.active i {
        color: #FF9040;
    }

    /* Badge */
    .badge {
        background-color: #FF9040;
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 12px;
        margin-left: auto;
    }

    .sidebar hr {
        border: none;
        border-top: 1px solid #eaeaea;
        margin: 20px 0;
    }

    /* Logout Button Styling */
    .sidebar .marketing-tools form button {
        background: none;
        border: none;
        padding: 12px 16px;
        width: 100%;
        margin: 0;
        display: flex;
        align-items: center;
        color: #1a1a1a;
        cursor: pointer;
        border-radius: 8px;
        transition: all 0.3s ease;
        font-weight: 500;
        font-size: 15px;
    }

    .sidebar .marketing-tools form button:hover {
        background-color: #f0f0f0;
    }
    
    .sidebar .marketing-tools form button i {
        width: 22px;
        font-size: 18px;
        text-align: center;
        margin-right: 14px;
        color: #1a1a1a;
    }
    
    /* Close button for mobile */
    .sidebar-close {
        display: none;
        color: #1a1a1a;
        font-size: 24px;
        position: absolute;
        top: 20px;
        right: 20px;
        cursor: pointer;
    }
    
    @media (max-width: 900px) {
        .sidebar-close {
            display: block;
        }
    }
</style>

<div class="sidebar" id="sidebar">
    <i class="fas fa-times sidebar-close" onclick="toggleSidebar()"></i>
    
    <div class="logo-container" style="justify-content: space-between; width: 100%;">
        <img src="{{ asset('images/Logo.png') }}" alt="Logo" class="logo" style="width: 100px; height: auto;">
        
        <div class="lang-toggle">
            <a href="{{ route('lang.switch', 'id') }}" class="{{ App::getLocale() == 'id' ? 'active' : '' }}">ID</a>
            <a href="{{ route('lang.switch', 'en') }}" class="{{ App::getLocale() == 'en' ? 'active' : '' }}">EN</a>
        </div>
    </div>

    <div class="sidebar-nav">
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-th-large"></i>{{ __('sidebar.dashboard') }}
        </a>
        
        <a href="{{ route('admin.statistics') }}" class="{{ request()->routeIs('admin.statistics*') ? 'active' : '' }}">
            <i class="fas fa-chart-pie"></i>{{ __('sidebar.analytics') }}
        </a>

        <a href="{{ route('admin.shortlinks.index') }}" class="{{ request()->routeIs('admin.shortlinks.*') ? 'active' : '' }}">
            <i class="fas fa-link"></i>{{ __('sidebar.shortlink') }}
        </a>

        <a href="{{ route('admin.mylinkan') }}" class="{{ request()->routeIs('admin.mylinkan') || request()->routeIs('admin.digital-products.*') ? 'active' : '' }}">
            <i class="fas fa-pager"></i>{{ __('sidebar.microsite') }}
        </a>

        <a href="{{ route('admin.orders') }}" class="{{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
            <i class="fas fa-store"></i>{{ __('sidebar.shop') }}
        </a>

        <a href="{{ route('admin.purchases') }}" class="{{ request()->routeIs('admin.purchases') ? 'active' : '' }}">
            <i class="fas fa-shopping-bag"></i>{{ __('sidebar.mypurchases') }}
        </a>

        <a href="{{ route('admin.appearance') }}" class="{{ request()->routeIs('admin.appearance*') ? 'active' : '' }}">
            <i class="fas fa-paint-brush"></i>{{ __('sidebar.appearance') }}
        </a>

        @php
            $isSettingsActive = request()->routeIs('admin.settings')
                || request()->routeIs('admin.account*')
                || request()->routeIs('admin.payout.*');
        @endphp

        <a href="{{ route('admin.settings') }}" class="{{ $isSettingsActive ? 'active' : '' }}">
            <i class="fas fa-cog"></i>{{ __('sidebar.settings') }}
        </a>
    </div>

    <div class="marketing-tools">
        <form action="{{ route('logout') }}" method="POST" style="display: flex; align-items: center;">
            @csrf
            <button type="submit">
                <i class="fas fa-sign-out-alt"></i>
                {{ __('sidebar.logout') }}
            </button>
        </form>
    </div>

</div>
