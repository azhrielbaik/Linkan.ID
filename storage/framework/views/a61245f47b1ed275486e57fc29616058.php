<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Linkan Dashboard'); ?></title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/favicon.png')); ?>">
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
            color: #888;
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
            border: 1px solid #eaeaea;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
            width: 180px;
            display: none;
            flex-direction: column;
            z-index: 1000;
            padding: 8px;
        }

        .profile-dropdown.show {
            display: flex;
        }

        .profile-dropdown a {
            padding: 10px 14px;
            text-decoration: none;
            color: #181818;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 12px;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .profile-dropdown a i {
            color: #666;
            font-size: 16px;
            transition: color 0.2s;
        }

        .profile-dropdown a:hover {
            background: #FFF0E5;
            color: #FF9040;
        }

        .profile-dropdown a:hover i {
            color: #FF9040;
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
    <?php echo $__env->yieldPushContent('styles'); ?>
    <?php echo $__env->yieldPushContent('page-styles'); ?>
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
        <?php echo $__env->make('homeadminS.sidebar.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="main-content">
            <div class="header">
                <div class="header-left" style="display: flex; align-items: center;">
                    <i class="fas fa-bars hamburger-menu" onclick="toggleSidebar()"></i>
                    <h1><?php echo $__env->yieldContent('page_title', 'URL SHORTENER'); ?></h1>
                </div>
                
                <div class="header-right">
                    <div class="header-actions">
                        <a href="<?php echo e(route('admin.settings')); ?>" class="action-icon"><i class="fas fa-cog"></i></a>
                        <a href="#" class="action-icon"><i class="far fa-bell"></i></a>
                    </div>
                    
                    <div class="top-profile" onclick="toggleProfileDropdown()">
                        <?php
                            $name = Auth::check() ? Auth::user()->name : 'User';
                            $initials = strtoupper(substr($name, 0, 2));
                        ?>
                        <div class="top-avatar">
                            <?php echo e($initials); ?>

                        </div>
                        <div class="top-user-info">
                            <span class="top-user-name"><?php echo e($name); ?></span>
                            <i class="fas fa-caret-down top-profile-arrow"></i>
                        </div>
                        
                        <!-- Dropdown Menu -->
                        <div class="profile-dropdown" id="profileDropdown">
                            <a href="<?php echo e(route('admin.account')); ?>">
                                <i class="fas fa-user-circle"></i> Profile
                            </a>
                            <a href="<?php echo e(route('admin.settings')); ?>">
                                <i class="fas fa-cog"></i> Settings
                            </a>
                            <!-- Logout uses a form, so we create a simple button looking like a link -->
                            <form action="<?php echo e(route('logout')); ?>" method="POST" style="margin: 0; padding: 0;">
                                <?php echo csrf_field(); ?>
                                <button type="submit" style="width: 100%; text-align: left; background: none; border: none; cursor: pointer; padding: 10px 14px; font-size: 14px; font-weight: 600; color: #E53935; display: flex; align-items: center; gap: 12px; border-radius: 8px; font-family: 'Plus Jakarta Sans', sans-serif; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#ffebee'" onmouseout="this.style.backgroundColor='transparent'">
                                    <i class="fas fa-sign-out-alt" style="color: #E53935;"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-wrapper">
                <?php echo $__env->yieldContent('content'); ?>
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
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH /home/rakanyuka/Documents/PKL/Linkan/resources/views/layouts/admin.blade.php ENDPATH**/ ?>