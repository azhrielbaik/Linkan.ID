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
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fff;
            padding: 18px 40px;
            border-bottom: 1px solid #eaeaea;
        }
        
        .content-wrapper {
            padding: 30px 40px;
            flex: 1;
        }

        .header h1 {
            font-size: 18px;
            font-weight: 800;
            color: #181818;
            letter-spacing: 0.5px;
            text-transform: uppercase;
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
    <?php echo $__env->yieldPushContent('styles'); ?>
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
                        <a href="<?php echo e(route('settings')); ?>" class="action-icon"><i class="fas fa-cog"></i></a>
                        <a href="#" class="action-icon"><i class="far fa-bell"></i></a>
                    </div>
                    
                    <div class="top-profile">
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
    </script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\user\Documents\TUGAS PKL\linkan.id\Linkan.ID\resources\views/layouts/admin.blade.php ENDPATH**/ ?>