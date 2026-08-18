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
        overflow-y: visible; /* changed from auto so button can overlap */
        z-index: 1000;
        transition: width 0.3s ease, transform 0.3s ease;
        box-shadow: 2px 0 10px rgba(0,0,0,0.03);
        display: flex;
        flex-direction: column;
        border-right: 1px solid #eaeaea;
    }
    .sidebar-inner-scroll {
        overflow-y: auto;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    
    /* Minimize Button */
    .sidebar-minimize-btn {
        position: absolute;
        top: 30px;
        right: -15px;
        width: 30px;
        height: 30px;
        background: #fff;
        border: 1px solid #eaeaea;
        border-radius: 50%;
        display: none;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 1001;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        color: #666;
    }
    
    @media (min-width: 901px) {
        .sidebar-minimize-btn {
            display: flex;
        }
    }

    .sidebar-minimize-btn i {
        transform: rotate(90deg); /* Points left initially */
        transition: transform 0.3s ease;
    }

    /* Mini Sidebar Overrides - Only on Desktop */
    @media (min-width: 901px) {
        body.mini-sidebar .sidebar {
            width: 80px;
            padding: 24px 10px;
        }
        
        body.mini-sidebar .sidebar-minimize-btn i {
            transform: rotate(270deg); /* Points right when minimized */
        }

        body.mini-sidebar .nav-text, 
        body.mini-sidebar .badge {
            display: none;
        }

        body.mini-sidebar .logo {
            display: none; /* Hide main logo, optionally show small icon */
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
        }
        
        body.mini-sidebar .marketing-tools form button i {
            margin-right: 0;
            font-size: 20px;
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
        color: #e53935;
        cursor: pointer;
        border-radius: 8px;
        transition: all 0.3s ease;
        font-weight: 600;
        font-size: 15px;
    }

    .sidebar .marketing-tools form button:hover {
        background-color: #ffebee;
        color: #d32f2f;
    }
    
    .sidebar .marketing-tools form button i {
        width: 22px;
        font-size: 18px;
        text-align: center;
        margin-right: 14px;
        color: #e53935;
        transition: color 0.3s ease;
    }

    .sidebar .marketing-tools form button:hover i {
        color: #d32f2f;
    }
    
    /* Close button for mobile */
    .sidebar-close {
        display: none;
        color: #1a1a1a;
        font-size: 22px; /* Sedikit lebih kecil agar rapi */
        cursor: pointer;
        padding: 4px;
    }
    
    @media (max-width: 900px) {
        .sidebar-close {
            display: block;
        }
    }
</style>

<div class="sidebar" id="sidebar">
    <div class="sidebar-minimize-btn" onclick="toggleMinimize()">
        <i class="fas fa-caret-down"></i>
    </div>
    
    <div class="sidebar-inner-scroll">
    <div class="logo-container" style="justify-content: space-between; width: 100%; align-items: center;">
        <img src="{{ asset('images/Logo.svg') }}" alt="Logo" class="logo" style="width: 90px; height: auto;">
        
        <div style="display: flex; align-items: center; gap: 8px;">
            <div class="lang-toggle" style="padding: 2px;">
                <a href="{{ route('lang.switch', 'id') }}" data-turbo="false" class="{{ App::getLocale() == 'id' ? 'active' : '' }}" style="padding: 4px 8px !important; font-size: 10px !important;">ID</a>
                <a href="{{ route('lang.switch', 'en') }}" data-turbo="false" class="{{ App::getLocale() == 'en' ? 'active' : '' }}" style="padding: 4px 8px !important; font-size: 10px !important;">EN</a>
            </div>
            <i class="fas fa-times sidebar-close" onclick="toggleSidebar()"></i>
        </div>
    </div>

    <div class="sidebar-nav">
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-th-large"></i><span class="nav-text">{{ __('sidebar.dashboard') }}</span>
        </a>
        
        <a href="{{ route('admin.statistics') }}" class="{{ request()->routeIs('admin.statistics*') ? 'active' : '' }}">
            <i class="fas fa-chart-pie"></i><span class="nav-text">{{ __('sidebar.analytics') }}</span>
        </a>

        <a href="{{ route('admin.shortlinks.index') }}" class="{{ request()->routeIs('admin.shortlinks.*') ? 'active' : '' }}">
            <i class="fas fa-link"></i><span class="nav-text">{{ __('sidebar.shortlink') }}</span>
        </a>

        <a href="{{ route('admin.mylinkan') }}" class="{{ request()->routeIs('admin.mylinkan') || request()->routeIs('admin.digital-products.*') ? 'active' : '' }}">
            <i class="fas fa-pager"></i><span class="nav-text">{{ __('sidebar.microsite') }}</span>
        </a>

        <a href="{{ route('admin.orders') }}" class="{{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
            <i class="fas fa-store"></i><span class="nav-text">{{ __('sidebar.shop') }}</span>
        </a>

        <a href="{{ route('admin.purchases') }}" class="{{ request()->routeIs('admin.purchases') ? 'active' : '' }}">
            <i class="fas fa-shopping-bag"></i><span class="nav-text">{{ __('sidebar.mypurchases') }}</span>
        </a>

        <a href="{{ route('admin.appearance') }}" class="{{ request()->routeIs('admin.appearance*') ? 'active' : '' }}">
            <i class="fas fa-paint-brush"></i><span class="nav-text">{{ __('sidebar.appearance') }}</span>
        </a>

        @php
            $isSettingsActive = request()->routeIs('admin.settings')
                || request()->routeIs('admin.account*')
                || request()->routeIs('admin.payout.*');
        @endphp

        <a href="{{ route('admin.settings') }}" class="{{ $isSettingsActive ? 'active' : '' }}">
            <i class="fas fa-cog"></i><span class="nav-text">{{ __('sidebar.settings') }}</span>
        </a>
    </div>

    <div class="marketing-tools">
        <form action="{{ route('logout') }}" method="POST" style="display: flex; align-items: center;">
            @csrf
            <button type="submit">
                <i class="fas fa-sign-out-alt"></i>
                <span class="nav-text">{{ __('sidebar.logout') }}</span>
            </button>
        </form>
    </div>
    </div>

</div>
