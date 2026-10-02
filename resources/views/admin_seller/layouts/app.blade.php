<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ (request()->cookie('theme') === 'dark') ? 'dark' : '' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta_description', 'Linkan.ID Dashboard')">
    <meta name="view-transition" content="same-origin">
    <title>@yield('title', 'Linkan Dashboard')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    {{-- Critical Inline Theme Detection (Prevents FOUC) --}}
    <script>
        (function() {
            try {
                var savedTheme = localStorage.getItem('theme');
                var prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                var isDark = savedTheme === 'dark' || (!savedTheme && prefersDark);
                if (isDark) {
                    document.documentElement.classList.add('dark');
                    if (!document.cookie.includes('theme=dark')) {
                        document.cookie = "theme=dark; path=/; max-age=31536000; SameSite=Lax";
                    }
                } else if (savedTheme === 'light') {
                    document.documentElement.classList.remove('dark');
                    if (!document.cookie.includes('theme=light')) {
                        document.cookie = "theme=light; path=/; max-age=31536000; SameSite=Lax";
                    }
                }
            } catch (e) {}
        })();
    </script>

    {{-- Typography & Font Icons --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">

    {{-- Global Layout & Feature Stylesheets --}}
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/seller-notifications.css') }}">
    @stack('styles')
    @stack('page-styles')
</head>
<body>
    <div class="container">
        @include('admin_seller.layouts.sidebar')

        <div class="main-content">
            <div class="header">
                <div class="header-left">
                    <i class="fas fa-bars hamburger-menu" onclick="toggleSidebar()"></i>
                    <h1>@yield('page_title', 'URL SHORTENER')</h1>
                </div>

                <div class="header-right">
                    <div class="header-actions">
                        <a href="{{ route('admin.settings') }}" class="action-icon" title="Pengaturan"><i class="fas fa-cog"></i></a>

                        <div class="theme-switch-wrapper">
                            <div class="theme-switch" onclick="toggleDarkMode()">
                                <div class="theme-switch-slider"></div>
                                <div class="theme-switch-icon icon-moon">
                                    <i class="far fa-moon"></i>
                                </div>
                                <div class="theme-switch-icon icon-sun">
                                    <i class="far fa-sun"></i>
                                </div>
                            </div>
                        </div>

                        {{-- Seller Notification Bell & Dropdown --}}
                        @include('admin_seller.layouts.partials._notification-dropdown')
                    </div>

                    <div class="top-profile" onclick="toggleProfileDropdown()">
                        <div class="top-avatar">
                            @if(!empty($sellerAvatar))
                                <img src="{{ Storage::url($sellerAvatar) }}" alt="Avatar" class="avatar-img">
                            @else
                                {{ $sellerInitials ?? 'US' }}
                            @endif
                        </div>
                        <div class="top-user-info">
                            <span class="top-user-name">{{ $sellerName ?? 'User' }}</span>
                            <i class="fas fa-caret-down top-profile-arrow"></i>
                        </div>

                        <!-- Dropdown Menu -->
                        <div class="profile-dropdown" id="profileDropdown">
                            <div class="pd-header">
                                Welcome back!
                            </div>
                            
                            <a href="{{ route('admin.account') }}" class="pd-item">
                                <i class="far fa-user"></i> Profile
                            </a>
                            
                            <a href="#" class="pd-item" onclick="toggleSellerNotif(event); event.preventDefault();">
                                <i class="far fa-bell"></i> Notifications
                            </a>
                            
                            <a href="#" class="pd-item">
                                <i class="far fa-credit-card"></i> Balance: Rp {{ number_format($sellerBalance ?? 0, 0, ',', '.') }}
                            </a>
                            
                            <a href="{{ route('admin.settings') }}" class="pd-item">
                                <i class="far fa-sun"></i> Account Settings
                            </a>
                            
                            <a href="{{ route('admin.tickets.index') }}" class="pd-item">
                                <i class="fas fa-headphones-alt"></i> Support Center
                            </a>

                            <hr class="pd-divider">

                            <form action="{{ route('logout') }}" method="POST" data-turbo="false" class="pd-logout-form" onsubmit="if(window.sellerEventSource) window.sellerEventSource.close();">
                                @csrf
                                <button type="submit" class="pd-item pd-logout">
                                    <i class="fas fa-sign-out-alt"></i> Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-wrapper">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Application Configuration Data Bridge -->
    <script id="app-config" type="application/json">
    {
        "notifEndpoint": "{{ route('admin.notifications') }}",
        "notifReadEndpoint": "{{ route('admin.notifications.read') }}",
        "notifReadAllEndpoint": "{{ route('admin.notifications.read-all') }}",
        "notifSSEEndpoint": "{{ route('admin.notifications.stream') }}"
    }
    </script>

    <!-- Global Scripts -->
    @vite(['resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/@hotwired/turbo@8.0.4/dist/turbo.es2017-umd.js"></script>
    <script src="{{ asset('js/layout.js') }}"></script>
    <script src="{{ asset('js/seller-notifications.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/toast-notification.js') }}?v={{ time() }}"></script>

    @if(session('success'))
    <script>
        (function() {
            function triggerToast() {
                if (typeof window.showSuccessToast === 'function') {
                    window.showSuccessToast("{!! addslashes(session('success')) !!}");
                }
            }
            triggerToast();
            document.addEventListener('turbo:load', triggerToast, { once: true });
        })();
    </script>
    @endif

    @stack('scripts')

    <!-- Global Loading Overlay -->
    <div id="global-loading-overlay" class="global-loading-overlay">
        <div class="global-loading-spinner" title="2">
            <svg version="1.1" id="loader-1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                 width="40px" height="40px" viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve">
                <path fill="#FF6700" d="M43.935,25.145c0-10.318-8.364-18.683-18.683-18.683c-10.318,0-18.683,8.365-18.683,18.683h4.068c0-8.071,6.543-14.615,14.615-14.615c8.072,0,14.615,6.543,14.615,14.615H43.935z">
                    <animateTransform attributeType="xml"
                                      attributeName="transform"
                                      type="rotate"
                                      from="0 25 25"
                                      to="360 25 25"
                                      dur="0.6s"
                                      repeatCount="indefinite"/>
                </path>
            </svg>
        </div>
        <div class="global-loading-text">Memproses...</div>
    </div>

    <!-- Global Image Cropper Modal Partial -->
    @include('admin_seller.layouts.partials._global-cropper')
</body>
</html>
