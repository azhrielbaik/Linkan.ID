<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Linkan Dashboard')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', Arial, sans-serif;
        }

        body {
            background-color: #f4f6fa;
            color: #333;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        .main-content {
            flex: 1;
            margin-left: 250px; /* Sidebar width */
            background-color: #f4f6fa;
            min-width: 0;
            display: flex;
            flex-direction: column;
        }

        .header {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            background: #fff !important;
            padding: 10px 40px !important;
            border-bottom: 1px solid #eaeaea !important;
            min-height: 48px !important;
        }
        
        .content-wrapper {
            padding: 20px 40px !important;
            flex: 1;
        }

        .header h1 {
            font-size: 18px !important;
            font-weight: 800 !important;
            color: #181818 !important;
            letter-spacing: 0.5px !important;
            text-transform: uppercase !important;
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
            color: #666;
            font-size: 20px;
            cursor: pointer;
            transition: color 0.2s;
            text-decoration: none;
        }

        .action-icon:hover {
            color: #FF9040;
        }

        .top-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            padding-left: 24px;
            border-left: 1px solid #eaeaea;
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
            color: #888;
        }

        .hamburger-menu {
            display: none;
            font-size: 24px;
            color: #181818;
            cursor: pointer;
            margin-right: 15px;
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
            .main-content { margin-left: 0; }
            .hamburger-menu { display: block; }
            .header-left { display: flex; align-items: center; }
        }

        @media (max-width: 600px) {
            .header { padding: 16px; }
            .top-user-name, .top-profile-arrow { display: none; }
            .top-profile { padding-left: 12px; gap: 8px; }
            .header h1 { font-size: 16px; }
            .content-wrapper { padding: 16px; }
            .header-right { gap: 16px; }
        }
    </style>
    @stack('styles')
    @stack('page-styles')
    <meta name="view-transition" content="same-origin" />
    <script src="https://cdn.jsdelivr.net/npm/@hotwired/turbo@8.0.4/dist/turbo.es2017-umd.js"></script>
    <style>
        /* Optional fade transition during turbo drive navigations */
        ::view-transition-old(root),
        ::view-transition-new(root) {
            animation-duration: 0.3s;
        }
    </style>
</head>
<body>
    <div class="container">
        @include('homeadminS.sidebar.sidebar')

        <div class="main-content">
            <div class="header">
                <div class="header-left" style="display: flex; align-items: center;">
                    <i class="fas fa-bars hamburger-menu" onclick="toggleSidebar()"></i>
                    <h1>@yield('page_title', 'URL SHORTENER')</h1>
                </div>
                
                <div class="header-right">
                    <div class="header-actions">
                        <a href="{{ route('admin.settings') }}" class="action-icon"><i class="fas fa-cog"></i></a>
                        <a href="#" class="action-icon"><i class="far fa-bell"></i></a>
                    </div>
                    
                    <div class="top-profile">
                        @php
                            $name = Auth::check() ? Auth::user()->name : 'User';
                            $initials = strtoupper(substr($name, 0, 2));
                        @endphp
                        <div class="top-avatar">
                            {{ $initials }}
                        </div>
                        <div class="top-user-info">
                            <span class="top-user-name">{{ $name }}</span>
                            <i class="fas fa-caret-down top-profile-arrow"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-wrapper">
                @yield('content')
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            if (sidebar) {
                sidebar.classList.toggle('show');
            }
        }
    </script>
    @stack('scripts')
</body>
</html>
