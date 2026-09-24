<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta_description', 'Linkan.ID Dashboard')">
    <title>@yield('title', 'Linkan Dashboard')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/seller-notifications.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
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
            padding: 24px 40px;
            flex: 1;
            background-color: #ffffff;
            min-width: 0;
            width: 100%;
            box-sizing: border-box;
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
            .header-left {
                display: flex !important;
                align-items: center !important;
                gap: 12px !important;
                min-width: 0 !important;
                flex: 1 !important;
            }
            .hamburger-menu {
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                width: 36px !important;
                height: 36px !important;
                min-width: 36px !important;
                border-radius: 50% !important;
                background-color: #f8fafc !important;
                border: 1px solid #e2e8f0 !important;
                color: #1e293b !important;
                font-size: 15px !important;
                cursor: pointer !important;
                margin: 0 !important;
                padding: 0 !important;
                transition: all 0.2s ease !important;
                -webkit-tap-highlight-color: transparent !important;
            }
            .header h1 {
                font-size: 18px !important;
                font-weight: 700 !important;
                color: #0f172a !important;
                margin: 0 !important;
                line-height: 1.25 !important;
                white-space: nowrap !important;
                overflow: hidden !important;
                text-overflow: ellipsis !important;
                min-width: 0 !important;
                flex: 1 !important;
                max-width: none !important;
            }
        }

        @media (max-width: 680px) {
            .header {
                padding: 10px 16px !important;
                min-height: 56px !important;
                position: sticky !important;
                top: 0 !important;
                z-index: 999 !important;
                gap: 10px !important;
                background-color: #ffffff !important;
                border-bottom: 1px solid #f1f5f9 !important;
            }
            .header-left {
                gap: 10px !important;
            }
            .header h1 {
                font-size: 16px !important;
                letter-spacing: -0.2px !important;
            }
            .header-right {
                gap: 8px !important;
            }
            .header-actions {
                gap: 6px !important;
            }
            .action-icon {
                width: 36px !important;
                height: 36px !important;
                min-width: 36px !important;
                border-radius: 50% !important;
                background-color: #f8fafc !important;
                border: 1px solid #e2e8f0 !important;
                color: #64748b !important;
                font-size: 15px !important;
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                text-decoration: none !important;
                transition: all 0.2s ease !important;
            }
            .theme-switch-wrapper {
                margin: 0 !important;
                display: inline-flex !important;
            }
            .theme-switch {
                width: 36px !important;
                height: 36px !important;
                min-width: 36px !important;
                border-radius: 50% !important;
                background-color: #f8fafc !important;
                border: 1px solid #e2e8f0 !important;
                box-shadow: none !important;
                padding: 0 !important;
                margin: 0 !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                overflow: hidden !important;
                transition: all 0.2s ease !important;
            }
            .theme-switch-slider {
                display: none !important;
            }
            .theme-switch-icon {
                position: static !important;
                width: 100% !important;
                height: 100% !important;
                font-size: 15px !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
            }
            html:not(.dark) .theme-switch-icon.icon-moon {
                display: flex !important;
                color: #64748b !important;
            }
            html:not(.dark) .theme-switch-icon.icon-sun {
                display: none !important;
            }
            html.dark .theme-switch {
                background-color: #242b3b !important;
                border-color: #333d52 !important;
            }
            html.dark .theme-switch-icon.icon-sun {
                display: flex !important;
                color: #fbbf24 !important;
            }
            html.dark .theme-switch-icon.icon-moon {
                display: none !important;
            }
            .seller-notif-btn {
                width: 36px !important;
                height: 36px !important;
                min-width: 36px !important;
                border-radius: 50% !important;
                background-color: #f8fafc !important;
                border: 1px solid #e2e8f0 !important;
                color: #64748b !important;
                font-size: 15px !important;
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                padding: 0 !important;
                transition: all 0.2s ease !important;
            }
            .seller-notif-badge {
                top: -3px !important;
                right: -3px !important;
                min-width: 16px !important;
                height: 16px !important;
                font-size: 9px !important;
                line-height: 1 !important;
                padding: 0 4px !important;
                border: 2px solid #ffffff !important;
            }
            .top-profile {
                padding-left: 8px !important;
                gap: 0 !important;
                border-left: 1px solid #e2e8f0 !important;
                position: relative !important;
                display: flex !important;
                align-items: center !important;
            }
            .top-user-name, .top-profile-arrow {
                display: none !important;
            }
            .top-avatar {
                width: 36px !important;
                height: 36px !important;
                border-radius: 50% !important;
                font-size: 13px !important;
                font-weight: 700 !important;
                background: linear-gradient(135deg, #ED842C 0%, #d97706 100%) !important;
                color: #ffffff !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                border: 2px solid #ffffff !important;
                box-shadow: 0 2px 6px rgba(237, 132, 44, 0.25) !important;
            }
            .profile-dropdown {
                position: absolute !important;
                top: calc(100% + 10px) !important;
                right: 0 !important;
                width: 230px !important;
                max-width: calc(100vw - 32px) !important;
                border-radius: 14px !important;
                box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12) !important;
                z-index: 1002 !important;
            }
            .content-wrapper { 
                padding: 16px 16px !important; 
                width: 100% !important;
                max-width: 100% !important;
                box-sizing: border-box !important;
            }
        }

        /* Small screens (<400px): hide setting cog to guarantee spacious title */
        @media (max-width: 400px) {
            .header-actions .action-icon {
                display: none !important;
            }
        }

        /* Dark mode header overrides */
        html.dark .header {
            background-color: #1c212e !important;
            border-bottom-color: #2a3241 !important;
        }
        html.dark .hamburger-menu,
        html.dark .header-actions .action-icon,
        html.dark .seller-notif-btn {
            background-color: #242b3b !important;
            border-color: #333d52 !important;
            color: #94a3b8 !important;
        }
        html.dark .hamburger-menu {
            color: #f1f5f9 !important;
        }
        html.dark .hamburger-menu:active,
        html.dark .header-actions .action-icon:active,
        html.dark .seller-notif-btn:active {
            background-color: #2d3748 !important;
        }
        html.dark .top-profile {
            border-left-color: #2a3241 !important;
        }
        html.dark .top-avatar {
            border-color: #1c212e !important;
        }
        html.dark .seller-notif-badge {
            border-color: #1c212e !important;
        }

        /* Global Pagination Fix: Hide Next & Previous buttons, show numbers only */
        nav[role="navigation"] .flex.justify-between:not(.hidden) {
            display: none !important;
        }
        span.relative.z-0.inline-flex > :first-child,
        span.relative.z-0.inline-flex > :last-child {
            display: none !important;
        }
        /* --- GLOBAL DARK MODE OVERRIDES (FLEETY THEME) --- */
        html.dark body, 
        html.dark .container, 
        html.dark .main-content, 
        html.dark .content-wrapper,
        html.dark .dashboard-mylinkan-page {
            background-color: #141824 !important;
            color: #ffffff !important;
        }
        
        html.dark .header, 
        html.dark .sidebar,
        html.dark .sidebar .logo-area,
        html.dark .section-header,
        html.dark .microsite-main-header {
            background-color: #1c212e !important;
            border-color: #2a3241 !important;
        }
        
        html.dark .header h1, 
        html.dark .nav-item {
            color: #ffffff !important;
        }
        html.dark .nav-item:hover, 
        html.dark .nav-item.active {
            background-color: #2a3241 !important;
        }
        
        /* Dashboard Cards & Containers */
        html.dark .bg-white,
        html.dark .microsite-card,
        html.dark .card-body-details {
            background-color: #1c212e !important;
            color: #ffffff !important;
            border-color: #2a3241 !important;
        }
        
        html.dark .bg-slate-50, 
        html.dark .bg-gray-50,
        html.dark .bg-slate-100,
        html.dark .card-thumbnail-container {
            background-color: #0f131c !important; /* Slightly darker inner areas */
            border-color: #2a3241 !important;
        }
        
        html.dark .url-pill {
            background-color: #2a3241 !important;
            color: #cbd5e1 !important;
        }
        
        /* Text Colors */
        html.dark .text-slate-900, 
        html.dark .text-gray-900,
        html.dark .text-slate-800,
        html.dark .microsite-name,
        html.dark .sidebar a,
        html.dark .sidebar a i,
        html.dark .sidebar .nav-text,
        html.dark h3 {
            color: #ffffff !important;
        }
        
        html.dark .sidebar a:hover,
        html.dark .sidebar a:hover .nav-text,
        html.dark .sidebar a.active,
        html.dark .sidebar a.active .nav-text {
            color: #ffffff !important;
            background-color: #2a3241 !important;
        }

        html.dark .text-slate-500, 
        html.dark .text-gray-500,
        html.dark .text-slate-600 {
            color: #94a3b8 !important;
        }
        html.dark .text-slate-700 {
            color: #cbd5e1 !important;
        }
        
        /* Borders */
        html.dark .border-slate-300, 
        html.dark .border-gray-300, 
        html.dark .border-slate-200,
        html.dark .border-slate-100,
        html.dark .border-b,
        html.dark .border-t,
        html.dark .border,
        html.dark hr,
        html.dark [style*="border"] {
            border-color: #2a3241 !important;
        }
        
        /* Action Icons */
        html.dark .action-icon {
            color: #94a3b8 !important;
        }
        html.dark .action-icon:hover {
            color: #ffffff !important;
        }

        /* Tables */
        html.dark table th {
            background-color: #141824 !important;
            color: #94a3b8 !important;
            border-bottom-color: #2a3241 !important;
        }
        html.dark table td {
            border-bottom-color: #2a3241 !important;
        }
        
        /* Notifications Dropdown */
        html.dark .seller-notif-dropdown {
            background-color: #1c212e !important;
            border-color: #2a3241 !important;
            color: #ffffff !important;
        }
        html.dark .seller-notif-dropdown .seller-notif-title,
        html.dark .seller-notif-dropdown .notif-title {
            color: #ffffff !important;
        }
        html.dark .seller-notif-dropdown .notif-desc {
            color: #94a3b8 !important;
        }
        html.dark .seller-notif-dropdown .notif-item:hover {
            background-color: #2a3241 !important;
        }
        html.dark .seller-notif-header,
        html.dark .seller-notif-footer {
            background-color: #141824 !important;
            border-color: #2a3241 !important;
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

                        {{-- Dark Mode Toggle Switch --}}
                        <style>
                            .theme-switch-wrapper {
                                display: inline-flex;
                                align-items: center;
                                justify-content: center;
                                margin: 0 15px;
                            }
                            
                            .theme-switch {
                                position: relative;
                                display: flex;
                                align-items: center;
                                background-color: #f1f5f9;
                                border-radius: 50px;
                                padding: 0;
                                cursor: pointer;
                                box-shadow: inset 3px 3px 6px rgba(0,0,0,0.08), inset -3px -3px 6px rgba(255,255,255,0.9);
                                width: 80px;
                                height: 40px;
                                transition: background-color 0.3s ease, box-shadow 0.3s ease;
                                overflow: hidden;
                            }
                            
                            .theme-switch-slider {
                                position: absolute;
                                top: 0;
                                left: 0;
                                width: 50%;
                                height: 100%;
                                background-color: #f1f5f9;
                                box-shadow: -4px 0 8px rgba(0,0,0,0.1);
                                transition: transform 0.3s cubic-bezier(0.4, 0.0, 0.2, 1), background-color 0.3s ease, box-shadow 0.3s ease;
                                z-index: 1;
                                transform: translateX(100%); /* default (light mode): slider on the right (sun) */
                            }
                            
                            .theme-switch-icon {
                                position: relative;
                                z-index: 2;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                width: 50%;
                                height: 100%;
                                font-size: 16px;
                                color: #94a3b8;
                                transition: color 0.3s ease;
                            }
                            
                            /* Dark mode states */
                            html.dark .theme-switch {
                                background-color: #1e293b;
                                box-shadow: inset 3px 3px 6px rgba(0,0,0,0.4), inset -2px -2px 5px rgba(255,255,255,0.05);
                            }
                            
                            html.dark .theme-switch-slider {
                                background-color: #1e293b;
                                transform: translateX(0); /* dark mode: slider on the left (moon) */
                                box-shadow: 4px 0 8px rgba(0,0,0,0.4);
                            }
                            
                            /* Active icon colors */
                            html:not(.dark) .icon-sun {
                                color: #FF9040; /* Yellow/Green tone for light mode active */
                            }
                            html.dark .icon-moon {
                                color: #fef08a; /* Bright yellow for dark mode active */
                            }
                            html:not(.dark) .icon-moon {
                                color: #cbd5e1;
                            }
                            html.dark .icon-sun {
                                color: #4a5568;
                            }
                            
                        </style>

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
                            $name = Auth::check() ? (Auth::user()->username ?? Auth::user()->name) : 'User';
                            $initials = strtoupper(substr($name, 0, 2));
                            $balance = Auth::check() ? \Illuminate\Support\Facades\DB::table('transactions')
                                ->join('digital_products', 'transactions.product_id', '=', 'digital_products.id')
                                ->where('digital_products.user_id', Auth::id())
                                ->where('transactions.status', 'success')
                                ->sum('transactions.total_price') : 0;
                        @endphp
                        <div class="top-avatar">
                            @if(Auth::check() && Auth::user()->avatar)
                                <img src="{{ Storage::url(Auth::user()->avatar) }}" alt="Avatar" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                            @else
                                {{ $initials }}
                            @endif
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
    <div id="global-loading-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.7); z-index: 99999; justify-content: center; align-items: center; flex-direction: column;">
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
    <script>
        function toggleDarkMode() {
            const html = document.documentElement;
            html.classList.toggle('dark');
            
            if (html.classList.contains('dark')) {
                localStorage.setItem('theme', 'dark');
            } else {
                localStorage.setItem('theme', 'light');
            }
        }

        // Initialize dark mode on load
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>

    <!-- Global Image Cropper Modal -->
    <style>
        /* Circular Crop Mask */
        #global-cropper-modal.cropper-circle-mode .cropper-view-box,
        #global-cropper-modal.cropper-circle-mode .cropper-face {
            border-radius: 50%;
        }
    </style>
    <div id="global-cropper-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 100000; justify-content: center; align-items: center;">
        <div style="background: #fff; padding: 20px; border-radius: 12px; max-width: 90%; max-height: 90%; display: flex; flex-direction: column; width: 600px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h3 style="font-size: 18px; font-weight: 700; color: #1a1a1a; margin: 0;">Sesuaikan Gambar</h3>
                <button type="button" onclick="closeGlobalCropper()" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #666;"><i class="fas fa-times"></i></button>
            </div>
            <div style="flex: 1; overflow: hidden; max-height: 60vh; background: #f0f0f0; border-radius: 8px;">
                <img id="global-cropper-image" src="" style="max-width: 100%; display: block;" alt="Crop Preview">
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 15px;">
                <button type="button" onclick="closeGlobalCropper()" style="padding: 10px 20px; border-radius: 8px; border: 1px solid #ddd; background: #fff; color: #333; font-weight: 600; cursor: pointer;">Batal</button>
                <button type="button" id="global-cropper-save" style="padding: 10px 20px; border-radius: 8px; border: none; background: #ED842C; color: #fff; font-weight: 600; cursor: pointer;">Gunakan Gambar</button>
            </div>
        </div>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script>
        let globalCropperInstance = null;
        let globalCropperInput = null;

        function closeGlobalCropper() {
            document.getElementById('global-cropper-modal').style.display = 'none';
            if (globalCropperInstance) {
                globalCropperInstance.destroy();
                globalCropperInstance = null;
            }
            if (globalCropperInput) {
                globalCropperInput.value = ''; // Reset input if cancelled so user can re-select
                globalCropperInput = null;
            }
        }

        document.addEventListener('change', function(e) {
            if (e.target && e.target.matches('.image-cropper') && e.target.files && e.target.files.length > 0) {
                const file = e.target.files[0];
                
                // If it's already cropped (our flag), let the normal process continue
                if (file.isCropped) return;
                
                // Stop the event from propagating to other listeners (like previewImage) yet
                e.preventDefault();
                e.stopImmediatePropagation();

                // Check if it's an image
                if (!file.type.match(/^image\//)) {
                    alert('Silakan pilih file gambar yang valid.');
                    e.target.value = '';
                    return;
                }

                globalCropperInput = e.target;
                
                let ratio = parseFloat(globalCropperInput.getAttribute('data-crop-ratio'));
                if (isNaN(ratio)) ratio = NaN; // Free ratio

                let shape = globalCropperInput.getAttribute('data-crop-shape');
                const modalEl = document.getElementById('global-cropper-modal');
                if (shape === 'circle') {
                    modalEl.classList.add('cropper-circle-mode');
                } else {
                    modalEl.classList.remove('cropper-circle-mode');
                }

                const reader = new FileReader();
                reader.onload = function(evt) {
                    const imageElement = document.getElementById('global-cropper-image');
                    imageElement.src = evt.target.result;
                    modalEl.style.display = 'flex';

                    if (globalCropperInstance) {
                        globalCropperInstance.destroy();
                    }

                    globalCropperInstance = new Cropper(imageElement, {
                        aspectRatio: ratio,
                        viewMode: 1, // Restrict the crop box not to exceed the size of the canvas
                        dragMode: 'move', // Allow moving the image instead of creating a new crop box
                        autoCropArea: 0.9, // 90% of the container
                        cropBoxMovable: false, // Fix the crop box position
                        cropBoxResizable: false, // Fix the crop box size
                        toggleDragModeOnDblclick: false,
                        background: true,
                        responsive: true,
                        restore: false,
                    });
                };
                reader.readAsDataURL(file);
            }
        }, true); // use capture phase so we intercept before bubble listeners

        document.getElementById('global-cropper-save').addEventListener('click', function() {
            if (!globalCropperInstance || !globalCropperInput) return;

            const saveBtn = this;
            const originalText = saveBtn.innerText;
            saveBtn.innerText = 'Memproses...';
            saveBtn.disabled = true;

            globalCropperInstance.getCroppedCanvas({
                imageSmoothingQuality: 'high',
            }).toBlob(function(blob) {
                if (!blob) {
                    alert('Gagal memproses gambar. Silakan coba lagi.');
                    saveBtn.innerText = originalText;
                    saveBtn.disabled = false;
                    return;
                }

                const originalName = globalCropperInput.files[0].name;
                const extension = originalName.substring(originalName.lastIndexOf('.')) || '.jpg';
                const newName = originalName.replace(extension, '_cropped.jpg');

                const file = new File([blob], newName, {
                    type: 'image/jpeg',
                    lastModified: new Date().getTime()
                });
                file.isCropped = true;

                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                
                const inputElement = globalCropperInput; // store ref before closing
                inputElement.files = dataTransfer.files;

                // Close modal
                document.getElementById('global-cropper-modal').style.display = 'none';
                globalCropperInstance.destroy();
                globalCropperInstance = null;
                globalCropperInput = null;
                
                saveBtn.innerText = originalText;
                saveBtn.disabled = false;

                // Trigger change event manually so original preview scripts can run
                const newEvent = new Event('change', { bubbles: true });
                inputElement.dispatchEvent(newEvent);

            }, 'image/jpeg', 0.9);
        });
    </script>
</body>
</html>
