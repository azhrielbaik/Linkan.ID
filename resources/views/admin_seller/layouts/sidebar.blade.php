
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
        @php
            $isSuspended = Auth::check() && Auth::user()->isSuspended();
            $lockStyle = $isSuspended ? 'opacity: 0.45; cursor: not-allowed;' : '';

            $navItems = [
                [
                    'route' => 'admin.dashboard',
                    'is_active' => request()->routeIs('admin.dashboard'),
                    'icon' => 'fas fa-home',
                    'label' => __('sidebar.dashboard'),
                    'can_lock' => false,
                ],
                [
                    'route' => 'admin.microsites.index',
                    'is_active' => request()->routeIs('admin.microsites.index'),
                    'icon' => 'fa-solid fa-pager',
                    'label' => __('sidebar.microsite'),
                    'can_lock' => true,
                ],
                [
                    'route' => 'admin.shortlinks.index',
                    'is_active' => request()->routeIs('admin.shortlinks.*'),
                    'icon' => 'fas fa-link',
                    'label' => __('sidebar.shortlink'),
                    'can_lock' => true,
                ],
                [
                    'route' => 'admin.statistics',
                    'is_active' => request()->routeIs('admin.statistics*'),
                    'icon' => 'fas fa-chart-bar',
                    'label' => __('sidebar.analytics'),
                    'can_lock' => true,
                ],
                [
                    'route' => 'admin.digital-products.index',
                    'is_active' => request()->routeIs('admin.digital-products.*'),
                    'icon' => 'fas fa-store',
                    'label' => __('sidebar.shop'),
                    'can_lock' => true,
                ],
                [
                    'route' => 'admin.orders',
                    'is_active' => request()->routeIs('admin.orders*'),
                    'icon' => 'fas fa-clipboard-list',
                    'label' => __('sidebar.orders'),
                    'can_lock' => true,
                ],
                [
                    'route' => 'admin.purchases',
                    'is_active' => request()->routeIs('admin.purchases'),
                    'icon' => 'fas fa-box-open',
                    'label' => __('sidebar.mypurchases'),
                    'can_lock' => true,
                ],
                [
                    'route' => 'admin.settings',
                    'is_active' => request()->routeIs('admin.settings') || request()->routeIs('admin.account*') || request()->routeIs('admin.payout.*'),
                    'icon' => 'fas fa-cog',
                    'label' => __('sidebar.settings'),
                    'can_lock' => true,
                ],
            ];
        @endphp

        @foreach($navItems as $item)
            @php
                $isItemLocked = $item['can_lock'] && $isSuspended;
                $itemHref = $isItemLocked ? route('admin.dashboard') : route($item['route']);
                $itemIcon = $isItemLocked ? 'fas fa-lock' : $item['icon'];
            @endphp
            <a href="{{ $itemHref }}" 
               class="{{ $item['is_active'] ? 'active' : '' }}"
               @if($isItemLocked) style="{{ $lockStyle }}" title="Terkunci selama masa penangguhan" @endif>
                <i class="{{ $itemIcon }}"></i><span class="nav-text">{{ $item['label'] }}</span>
            </a>
        @endforeach
    </div>

    <div class="marketing-tools">
        <form action="{{ route('logout') }}" method="POST" data-turbo="false" onsubmit="if(window.sellerEventSource) window.sellerEventSource.close();" style="display: flex; align-items: center; width: 100%;">
            @csrf
            <button type="submit">
                <i class="fas fa-sign-out-alt"></i>
                <span class="nav-text">{{ __('sidebar.logout') }}</span>
            </button>
        </form>
    </div>
    </div>

</div>
