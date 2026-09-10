<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Linkan Dashboard')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/seller-notifications.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    @stack('styles')
    @stack('page-styles')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', Arial, sans-serif;
        }

        html, body {
            max-width: 100%;
            overflow-x: clip;
        }

        body {
            background-color: #ffffff;
            color: #333;
        }

        .container {
            display: flex;
            min-height: 100vh;
            background-color: #ffffff;
        }

        .main-content {
            flex: 1;
            margin-left: 250px; /* Sidebar width */
            background-color: #ffffff;
            min-width: 0;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.3s ease;
        }

        body.mini-sidebar .main-content {
            margin-left: 80px;
        }

        .header {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            background: #ffffff !important;
            padding: 16px 40px !important;
            border-bottom: 1px solid #f0f2f5 !important;
            min-height: 56px !important;
        }

        .content-wrapper {
            padding: 24px 40px !important;
            flex: 1;
            background-color: #ffffff;
        }

        .header h1 {
            font-size: 26px !important;
            font-weight: 800 !important;
            color: #1a1a1a !important;
            letter-spacing: -0.5px !important;
            text-transform: capitalize !important;
            margin: 0 !important;
            line-height: 1.2 !important;
        }

        .stat-icon {
            width: 48px !important;
            height: 48px !important;
            border-radius: 50% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 20px !important;
            flex-shrink: 0 !important;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .action-icon {
            color: #1a1a1a;
            font-size: 20px;
            cursor: pointer;
            transition: color 0.2s;
            text-decoration: none;
        }

        .action-icon:hover {
            color: #1E50D8;
        }

        .top-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            padding-left: 24px;
            border-left: 1px solid #eaeaea;
            position: relative;
        }

        .top-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background-color: #0067D5;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            border: 2px solid #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .top-user-info {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .top-user-name {
            font-size: 15px;
            font-weight: 600;
            color: #181818;
        }

        .top-profile-arrow {
            font-size: 12px;
            color: #1a1a1a;
        }

        .hamburger-menu {
            display: none;
            font-size: 24px;
            color: #181818;
            cursor: pointer;
            margin-right: 15px;
        }

        /* Profile Dropdown */
        .profile-dropdown {
            position: absolute;
            top: calc(100% + 15px);
            right: 0;
            background: #fff;
            border: 1px solid #f0f0f0;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            width: 240px;
            display: none;
            flex-direction: column;
            z-index: 1000;
            padding: 12px 0;
        }

        .profile-dropdown.show {
            display: flex;
        }

        .pd-header {
            padding: 8px 24px 16px 24px;
            font-size: 15px;
            font-weight: 600;
            color: #4a5568;
            border-bottom: 1px solid #f0f0f0;
            margin-bottom: 8px;
        }

        .profile-dropdown a.pd-item, .profile-dropdown button.pd-item {
            padding: 12px 24px;
            text-decoration: none;
            color: #4a5568;
            font-size: 16px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 16px;
            transition: all 0.2s;
            background: none;
            border: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .profile-dropdown .pd-item i {
            color: #4a5568;
            font-size: 18px;
            width: 20px;
            text-align: center;
            transition: color 0.2s;
        }

        .profile-dropdown .pd-item:hover {
            background: #f8f9fa;
            color: #1a1a1a;
        }

        .profile-dropdown .pd-item:hover i {
            color: #1a1a1a;
        }

        .profile-dropdown .pd-divider {
            height: 1px;
            background-color: #f0f0f0;
            margin: 8px 24px;
            border: none;
        }

        .profile-dropdown .pd-logout {
            color: #ff4d4f !important;
            margin-top: 4px;
        }
        
        .profile-dropdown .pd-logout i {
            color: #ff4d4f !important;
        }
        
        .profile-dropdown .pd-logout:hover {
            background: #fff1f0;
        }

        @media (max-width: 1200px) {
            .content-wrapper {
                padding: 20px;
            }
            .header {
                padding: 16px 20px;
            }
        }

        @media (max-width: 900px) {
            .main-content { margin-left: 0 !important; }
            .hamburger-menu { display: block; }
            .header-left { display: flex; align-items: center; }
        }

        @media (max-width: 600px) {
            .header {
                padding: 12px 16px !important;
            }
            .header-left {
                gap: 12px;
            }
            .hamburger-menu {
                margin-right: 0 !important;
                font-size: 22px;
            }
            .header h1 {
                font-size: 15px !important;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                max-width: 110px;
            }
            .header-right {
                gap: 12px !important;
            }
            .header-actions {
                gap: 12px !important;
            }
            .action-icon {
                font-size: 18px !important;
            }
            .top-profile {
                padding-left: 12px !important;
                gap: 0 !important;
                border-left: 1px solid #eaeaea !important;
            }
            .top-user-name, .top-profile-arrow { display: none !important; }
            .top-avatar {
                width: 32px !important;
                height: 32px !important;
                font-size: 12px !important;
            }
            .content-wrapper { padding: 16px; }
        }

        /* Global Pagination Fix: Hide Next & Previous buttons, show numbers only */
        nav[role="navigation"] .flex.justify-between:not(.hidden) {
            display: none !important;
        }
        span.relative.z-0.inline-flex > :first-child,
        span.relative.z-0.inline-flex > :last-child {
            display: none !important;
        }
    </style>
    @stack('styles')
    @stack('page-styles')
    <meta name="view-transition" content="same-origin" />
    <script src="https://cdn.jsdelivr.net/npm/@hotwired/turbo@8.0.4/dist/turbo.es2017-umd.js"></script>
    <script>
        document.addEventListener("turbo:before-render", function(event) {
            if (document.body.classList.contains('mini-sidebar')) {
                event.detail.newBody.classList.add('mini-sidebar');
            } else {
                event.detail.newBody.classList.remove('mini-sidebar');
            }
        });
    </script>
    <style>
        /* Optional fade transition during turbo drive navigations */
        ::view-transition-old(root),
        ::view-transition-new(root) {
            animation-duration: 0.3s;
        }
    </style>
</head>
<body>
    <script>
        if(localStorage.getItem('sidebar-mini') === 'true') {
            document.body.classList.add('mini-sidebar');
        }
    </script>
    <div class="container">
        @include('admin_seller.layouts.sidebar')

        <div class="main-content">
            <div class="header">
                <div class="header-left" style="display: flex; align-items: center;">
                    <i class="fas fa-bars hamburger-menu" onclick="toggleSidebar()"></i>
                    <h1>@yield('page_title', 'URL SHORTENER')</h1>
                </div>

                <div class="header-right">
                    <div class="header-actions">
                        <a href="{{ route('admin.settings') }}" class="action-icon" title="Pengaturan"><i class="fas fa-cog"></i></a>

                        {{-- Seller Notification Bell & Dropdown --}}
                        @inject('dashboardService', 'App\Services\AdminSeller\DashboardService')
                        @php
                            $notifData = $dashboardService->fetchSellerNotificationsData(Auth::user());
                            $notifications = collect($notifData['notifications'] ?? [])->take(5);
                            $unreadCount = $notifData['unread_count'] ?? 0;
                            $hasUnread = $unreadCount > 0;
                            $displayCount = $unreadCount > 99 ? '99+' : $unreadCount;
                            $headerCount = str_pad($unreadCount, 2, '0', STR_PAD_LEFT);
                        @endphp
                        <div class="seller-notif-wrapper">
                            <button type="button" class="action-icon seller-notif-btn" id="sellerNotifBtn" onclick="toggleSellerNotif(event)" title="Notifikasi" aria-label="Notifikasi">
                                <i class="far fa-bell"></i>
                                <span class="seller-notif-badge" id="sellerNotifBadge" style="display: {{ $hasUnread ? 'flex' : 'none' }};">{{ $displayCount }}</span>
                            </button>

                            <!-- Notification Dropdown Panel -->
                            <div class="seller-notif-dropdown" id="sellerNotifDropdown">
                                <div class="seller-notif-header">
                                    <div class="seller-notif-title">Notifications</div>
                                    <div class="seller-notif-badge-header">{{ $headerCount }} Notifications</div>
                                </div>

                                <div class="seller-notif-list" id="sellerNotifList">
                                    @forelse ($notifications as $item)
                                    <a href="{{ $item['url'] ?? '#' }}" class="notif-item no-loader" onclick="if(typeof markSellerNotifRead === 'function') markSellerNotifRead(event, '{{ $item['id'] }}', this)">
                                        <div class="notif-avatar-box">
                                            @if(!empty($item['avatar_url']))
                                                <img src="{{ $item['avatar_url'] }}" alt="Avatar" class="notif-avatar">
                                            @else
                                                <div class="notif-sys-icon">
                                                    <i class="{{ $item['icon'] }}"></i>
                                                </div>
                                            @endif
                                            <div class="notif-state-badge {{ $item['state_class'] }}">
                                                <i class="{{ $item['icon'] }}"></i>
                                            </div>
                                        </div>
                                        <div class="notif-content">
                                            <p class="notif-message">{!! $item['message'] !!}</p>
                                            <span class="notif-time">{{ $item['time_ago'] }}</span>
                                        </div>
                                    </a>
                                    @empty
                                    <div style="padding: 20px; text-align: center; color: #64748b; font-size: 14px;">
                                        Tidak ada notifikasi
                                    </div>
                                    @endforelse
                                </div>

                                <div class="seller-notif-footer">
                                    <a href="#" class="no-loader" onclick="if(typeof markAllSellerNotifsRead === 'function') markAllSellerNotifsRead(event, false, this)">Read All Messages</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="top-profile" onclick="toggleProfileDropdown()">
                        @php
                            $name = Auth::check() ? Auth::user()->name : 'User';
                            $initials = strtoupper(substr($name, 0, 2));
                            $balance = Auth::check() ? \Illuminate\Support\Facades\DB::table('transactions')
                                ->join('digital_products', 'transactions.product_id', '=', 'digital_products.id')
                                ->where('digital_products.user_id', Auth::id())
                                ->where('transactions.status', 'success')
                                ->sum('transactions.total_price') : 0;
                        @endphp
                        <div class="top-avatar">
                            {{ $initials }}
                        </div>
                        <div class="top-user-info">
                            <span class="top-user-name">{{ $name }}</span>
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
                                <i class="far fa-credit-card"></i> Balance: Rp {{ number_format($balance, 0, ',', '.') }}
                            </a>
                            
                            <a href="{{ route('admin.settings') }}" class="pd-item">
                                <i class="far fa-sun"></i> Account Settings
                            </a>
                            
                            <a href="{{ route('admin.tickets.index') }}" class="pd-item">
                                <i class="fas fa-headphones-alt"></i> Support Center
                            </a>

                            <hr class="pd-divider">

                            <form action="{{ route('logout') }}" method="POST" style="margin: 0; padding: 0;" onsubmit="if(window.sellerEventSource) window.sellerEventSource.close();">
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

    @vite(['resources/js/app.js'])
    <script>
        window.SellerNotifEndpoint = "{{ route('admin.notifications') }}";
        window.SellerNotifReadEndpoint = "{{ route('admin.notifications.read') }}";
        window.SellerNotifReadAllEndpoint = "{{ route('admin.notifications.read-all') }}";
        window.SellerNotifSSEEndpoint = "{{ route('admin.notifications.stream') }}";
    </script>
    <script src="{{ asset('js/seller-notifications.js') }}?v={{ time() }}"></script>
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



        function toggleProfileDropdown() {
            const dropdown = document.getElementById('profileDropdown');
            if (dropdown) {
                dropdown.classList.toggle('show');
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const profile = document.querySelector('.top-profile');
            const dropdown = document.getElementById('profileDropdown');
            if (profile && dropdown && !profile.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });
    </script>
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
    <div id="global-loading-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.7); z-index: 99999; justify-content: center; align-items: center; flex-direction: column; backdrop-filter: blur(2px);">
        <div class="loader loader--style3" title="2" style="margin: 0 auto; text-align: center; height: 100px; padding: 1em; display: inline-block; vertical-align: top;">
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
        <div style="font-weight: 600; color: #1a1a1a; font-size: 15px;">Memproses...</div>
    </div>
    <script>
        function showGlobalLoader() {
            const overlay = document.getElementById('global-loading-overlay');
            if (overlay) overlay.style.display = 'flex';
        }
        function hideGlobalLoader() {
            const overlay = document.getElementById('global-loading-overlay');
            if (overlay) overlay.style.display = 'none';
        }

        // 1. Standar Form Submit
        document.addEventListener('submit', function(e) {
            // Check if submission is prevented by another script
            if (!e.defaultPrevented) {
                showGlobalLoader();
            }
        });

        // 2. Turbo events (karena menggunakan Hotwired Turbo)
        document.addEventListener('turbo:submit-start', showGlobalLoader);
        document.addEventListener('turbo:submit-end', hideGlobalLoader);
        document.addEventListener('turbo:load', hideGlobalLoader);
        document.addEventListener('turbo:render', hideGlobalLoader);

        // 3. Intercept Fetch API (Untuk AJAX manual seperti update posisi, simpan produk digital)
        if (!window._networkIntercepted) {
            window._networkIntercepted = true;
            
            const originalFetch = window.fetch;
            window.fetch = async function(...args) {
                let isMutating = false;
                if (args[1] && args[1].method && !args[1].silent) {
                    const method = args[1].method.toUpperCase();
                    if (['POST', 'PUT', 'PATCH', 'DELETE'].includes(method)) {
                        isMutating = true;
                        showGlobalLoader();
                    }
                }
                try {
                    return await originalFetch.apply(this, args);
                } finally {
                    if (isMutating) {
                        hideGlobalLoader();
                    }
                }
            };

            // 4. Intercept XMLHttpRequest (Untuk jQuery AJAX, Axios, dll)
            const originalXhrOpen = XMLHttpRequest.prototype.open;
            XMLHttpRequest.prototype.open = function(method, url) {
                this._requestMethod = method ? method.toUpperCase() : 'GET';
                return originalXhrOpen.apply(this, arguments);
            };
            const originalXhrSend = XMLHttpRequest.prototype.send;
            XMLHttpRequest.prototype.send = function() {
                let isMutating = ['POST', 'PUT', 'PATCH', 'DELETE'].includes(this._requestMethod);
                if (isMutating) {
                    showGlobalLoader();
                    this.addEventListener('loadend', function() {
                        hideGlobalLoader();
                    });
                }
                return originalXhrSend.apply(this, arguments);
            };
        }

        // Jika halaman dipulihkan dari bfcache (tombol back)
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                hideGlobalLoader();
            }
        });
    </script>
</body>
</html>
