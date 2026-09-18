
<div class="sidebar" id="sidebar">
    <div class="sidebar-minimize-btn" onclick="toggleMinimize()">
        <i class="fas fa-caret-down"></i>
    </div>
    
    <div class="sidebar-inner-scroll">
    <div class="logo-container" style="justify-content: space-between; width: 100%; align-items: center;">
        <img src="{{ asset('images/Logo.svg') }}" alt="Logo" class="logo logo-light">
        <img src="{{ asset('images/Logo-white.svg') }}" alt="Logo" class="logo logo-dark" style="display: none;">
        
        <img src="{{ asset('images/Logo-mini.png') }}" alt="Logo Mini" class="logo-mini logo-mini-light" style="display: none;">
        <img src="{{ asset('images/Logo-mini-white.png') }}" alt="Logo Mini" class="logo-mini logo-mini-dark" style="display: none;">
        
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

        <a href="{{ $isSuspended ? route('admin.dashboard') : route('admin.microsites.index') }}" 
           class="{{ request()->routeIs('admin.microsites.index') ? 'active' : '' }}"
           style="{{ $lockStyle }}"
           @if($isSuspended) title="Terkunci selama masa penangguhan" @endif>
            <i class="fa-solid {{ $isSuspended ? 'fa-lock' : 'fa-pager' }}"></i><span class="nav-text">{{ __('sidebar.microsite') }}</span>
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

        <a href="{{ $isSuspended ? route('admin.dashboard') : route('admin.digital-products.index') }}" 
           class="{{ request()->routeIs('admin.digital-products.*') ? 'active' : '' }}"
           style="{{ $lockStyle }}"
           @if($isSuspended) title="Terkunci selama masa penangguhan" @endif>
            <i class="fas {{ $isSuspended ? 'fa-lock' : 'fa-store' }}"></i><span class="nav-text">{{ __('sidebar.shop') }}</span>
        </a>

        <a href="{{ $isSuspended ? route('admin.dashboard') : route('admin.orders') }}" 
           class="{{ request()->routeIs('admin.orders*') ? 'active' : '' }}"
           style="{{ $lockStyle }}"
           onclick="if(window.location.href.split('?')[0] === this.href.split('?')[0]) return false;"
           @if($isSuspended) title="Terkunci selama masa penangguhan" @endif>
            <i class="fas {{ $isSuspended ? 'fa-lock' : 'fa-clipboard-list' }}"></i><span class="nav-text">{{ __('sidebar.orders') }}</span>
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
