<style>
    /* Sidebar Styles */
    .sidebar {
        width: 250px;
        background-color: #5A5BF1;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        padding: 24px 20px;
        overflow-y: visible;
        z-index: 1000;
        transition: width 0.3s ease, transform 0.3s ease;
        box-shadow: 2px 0 16px rgba(90, 91, 241, 0.15);
        display: flex;
        flex-direction: column;
        border-right: none;
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

    /* Mini Sidebar Overrides - Only on Desktop */
    @media (min-width: 901px) {
        body.mini-sidebar .sidebar {
            width: 80px;
            padding: 24px 10px;
        }
        
        body.mini-sidebar .sidebar-minimize-btn i {
            transform: rotate(270deg);
        }

        body.mini-sidebar .nav-text, 
        body.mini-sidebar .badge {
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
        margin-bottom: 32px;
        gap: 10px;
        font-weight: 800;
        font-size: 24px;
        color: #ffffff;
    }

    .sidebar .logo {
        width: 105px;
        height: auto;
        filter: brightness(0) invert(1);
    }

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
    }
    
    .lang-toggle a:hover {
        background: rgba(255, 255, 255, 0.3);
    }
    
    .lang-toggle a.active {
        background: #ffffff !important;
        color: #5A5BF1 !important;
        box-shadow: 0 2px 6px rgba(0,0,0,0.12) !important;
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
        font-size: 16px;
        letter-spacing: -0.2px;
    }

    .sidebar a:hover {
        background-color: rgba(255, 255, 255, 0.16);
        color: #ffffff;
    }

    .sidebar a i {
        margin-right: 14px;
        width: 24px;
        font-size: 19px;
        text-align: center;
        color: #ffffff;
    }

    /* Active menu matching reference */
    .sidebar a.active {
        background-color: #ffffff !important;
        color: #5A5BF1 !important;
        font-weight: 800;
        box-shadow: 0 4px 14px rgba(0,0,0,0.06);
    }
    
    .sidebar a.active i {
        color: #5A5BF1 !important;
    }

    /* Badge */
    .badge {
        background-color: #ffffff;
        color: #5A5BF1;
        font-size: 11px;
        font-weight: 800;
        padding: 2px 8px;
        border-radius: 12px;
        margin-left: auto;
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
        font-size: 16px;
    }

    .sidebar .marketing-tools form button:hover {
        background-color: rgba(255, 255, 255, 0.16);
        color: #ffffff;
    }
    
    .sidebar .marketing-tools form button i {
        width: 24px;
        font-size: 19px;
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

<div class="sidebar" id="sidebar">
    <div class="sidebar-minimize-btn" onclick="toggleMinimize()">
        <i class="fas fa-caret-down"></i>
    </div>
    
    <div class="sidebar-inner-scroll">
    <div class="logo-container" style="justify-content: space-between; width: 100%; align-items: center;">
        <img src="{{ asset('images/Logo.svg') }}" alt="Logo" class="logo">
        
        <div style="display: flex; align-items: center; gap: 8px;">
            <div class="lang-toggle">
                <a href="{{ route('lang.switch', 'id') }}" data-turbo="false" class="{{ App::getLocale() == 'id' ? 'active' : '' }}">ID</a>
                <a href="{{ route('lang.switch', 'en') }}" data-turbo="false" class="{{ App::getLocale() == 'en' ? 'active' : '' }}">EN</a>
            </div>
            <i class="fas fa-times sidebar-close" onclick="toggleSidebar()"></i>
        </div>
    </div>

    <div class="sidebar-nav">
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-home"></i><span class="nav-text">{{ __('sidebar.dashboard') }}</span>
        </a>
        
        @php
            $isSuspended = Auth::check() && Auth::user()->isSuspended();
            $lockStyle = $isSuspended ? 'opacity: 0.45; cursor: not-allowed;' : '';
        @endphp

        <a href="{{ $isSuspended ? route('admin.dashboard') : route('admin.mylinkan') }}" 
           class="{{ (request()->routeIs('admin.mylinkan') || request()->routeIs('admin.digital-products.*')) ? 'active' : '' }}"
           style="{{ $lockStyle }}"
           @if($isSuspended) title="Terkunci selama masa penangguhan" @endif>
            <i class="fas {{ $isSuspended ? 'fa-lock' : 'fa-user' }}"></i><span class="nav-text">{{ __('sidebar.microsite') }}</span>
        </a>

        <a href="{{ $isSuspended ? route('admin.dashboard') : route('admin.appearance') }}" 
           class="{{ request()->routeIs('admin.appearance*') ? 'active' : '' }}"
           style="{{ $lockStyle }}"
           @if($isSuspended) title="Terkunci selama masa penangguhan" @endif>
            <i class="fas {{ $isSuspended ? 'fa-lock' : 'fa-paint-brush' }}"></i><span class="nav-text">{{ __('sidebar.appearance') }}</span>
        </a>

        <a href="{{ $isSuspended ? route('admin.dashboard') : route('admin.shortlinks.index') }}" 
           class="{{ request()->routeIs('admin.shortlinks.*') ? 'active' : '' }}"
           style="{{ $lockStyle }}"
           @if($isSuspended) title="Terkunci selama masa penangguhan" @endif>
            <i class="fas {{ $isSuspended ? 'fa-lock' : 'fa-link' }}"></i><span class="nav-text">{{ __('sidebar.shortlink') }}</span>
        </a>

        <a href="{{ $isSuspended ? route('admin.dashboard') : route('admin.statistics') }}" 
           class="{{ request()->routeIs('admin.statistics*') ? 'active' : '' }}"
           style="{{ $lockStyle }}"
           @if($isSuspended) title="Terkunci selama masa penangguhan" @endif>
            <i class="fas {{ $isSuspended ? 'fa-lock' : 'fa-chart-bar' }}"></i><span class="nav-text">{{ __('sidebar.analytics') }}</span>
        </a>

        <a href="{{ $isSuspended ? route('admin.dashboard') : route('admin.orders') }}" 
           class="{{ request()->routeIs('admin.orders*') ? 'active' : '' }}"
           style="{{ $lockStyle }}"
           @if($isSuspended) title="Terkunci selama masa penangguhan" @endif>
            <i class="fas {{ $isSuspended ? 'fa-lock' : 'fa-clipboard-check' }}"></i><span class="nav-text">{{ __('sidebar.shop') }}</span>
        </a>

        <a href="{{ $isSuspended ? route('admin.dashboard') : route('admin.purchases') }}" 
           class="{{ request()->routeIs('admin.purchases') ? 'active' : '' }}"
           style="{{ $lockStyle }}"
           @if($isSuspended) title="Terkunci selama masa penangguhan" @endif>
            <i class="fas {{ $isSuspended ? 'fa-lock' : 'fa-box-open' }}"></i><span class="nav-text">{{ __('sidebar.mypurchases') }}</span>
        </a>

        @php
            $isSettingsActive = request()->routeIs('admin.settings')
                || request()->routeIs('admin.account*')
                || request()->routeIs('admin.payout.*');
        @endphp

        <a href="{{ $isSuspended ? route('admin.dashboard') : route('admin.settings') }}" 
           class="{{ $isSettingsActive ? 'active' : '' }}"
           style="{{ $lockStyle }}"
           @if($isSuspended) title="Terkunci selama masa penangguhan" @endif>
            <i class="fas {{ $isSuspended ? 'fa-lock' : 'fa-cog' }}"></i><span class="nav-text">{{ __('sidebar.settings') }}</span>
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
