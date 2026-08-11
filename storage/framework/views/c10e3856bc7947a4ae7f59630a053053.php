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
        overflow-y: auto;
        z-index: 1000;
        transition: transform 0.3s ease;
        box-shadow: 2px 0 10px rgba(0,0,0,0.03);
        display: flex;
        flex-direction: column;
        border-right: 1px solid #eaeaea;
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
        color: #1a1a1a;
        cursor: pointer;
        border-radius: 8px;
        transition: all 0.3s ease;
        font-weight: 500;
        font-size: 15px;
    }

    .sidebar .marketing-tools form button:hover {
        background-color: #f0f0f0;
    }
    
    .sidebar .marketing-tools form button i {
        width: 22px;
        font-size: 18px;
        text-align: center;
        margin-right: 14px;
        color: #1a1a1a;
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
    <div class="logo-container" style="justify-content: space-between; width: 100%; align-items: center;">
        <img src="<?php echo e(asset('images/Logo.png')); ?>" alt="Logo" class="logo" style="width: 100px; height: auto;">
        
        <div style="display: flex; align-items: center; gap: 8px;">
            <div class="lang-toggle" style="padding: 2px;">
                <a href="<?php echo e(route('lang.switch', 'id')); ?>" data-turbo="false" class="<?php echo e(App::getLocale() == 'id' ? 'active' : ''); ?>" style="padding: 4px 8px !important; font-size: 10px !important;">ID</a>
                <a href="<?php echo e(route('lang.switch', 'en')); ?>" data-turbo="false" class="<?php echo e(App::getLocale() == 'en' ? 'active' : ''); ?>" style="padding: 4px 8px !important; font-size: 10px !important;">EN</a>
            </div>
            <i class="fas fa-times sidebar-close" onclick="toggleSidebar()"></i>
        </div>
    </div>

    <div class="sidebar-nav">
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="<?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">
            <i class="fas fa-th-large"></i><?php echo e(__('sidebar.dashboard')); ?>

        </a>
        
        <a href="<?php echo e(route('admin.statistics')); ?>" class="<?php echo e(request()->routeIs('admin.statistics*') ? 'active' : ''); ?>">
            <i class="fas fa-chart-pie"></i><?php echo e(__('sidebar.analytics')); ?>

        </a>

        <a href="<?php echo e(route('admin.shortlinks.index')); ?>" class="<?php echo e(request()->routeIs('admin.shortlinks.*') ? 'active' : ''); ?>">
            <i class="fas fa-link"></i><?php echo e(__('sidebar.shortlink')); ?>

        </a>

        <a href="<?php echo e(route('admin.mylinkan')); ?>" class="<?php echo e(request()->routeIs('admin.mylinkan') || request()->routeIs('admin.digital-products.*') ? 'active' : ''); ?>">
            <i class="fas fa-pager"></i><?php echo e(__('sidebar.microsite')); ?>

        </a>

        <a href="<?php echo e(route('admin.orders')); ?>" class="<?php echo e(request()->routeIs('admin.orders*') ? 'active' : ''); ?>">
            <i class="fas fa-store"></i><?php echo e(__('sidebar.shop')); ?>

        </a>

        <a href="<?php echo e(route('admin.purchases')); ?>" class="<?php echo e(request()->routeIs('admin.purchases') ? 'active' : ''); ?>">
            <i class="fas fa-shopping-bag"></i><?php echo e(__('sidebar.mypurchases')); ?>

        </a>

        <a href="<?php echo e(route('admin.appearance')); ?>" class="<?php echo e(request()->routeIs('admin.appearance*') ? 'active' : ''); ?>">
            <i class="fas fa-paint-brush"></i><?php echo e(__('sidebar.appearance')); ?>

        </a>

        <?php
            $isSettingsActive = request()->routeIs('admin.settings')
                || request()->routeIs('admin.account*')
                || request()->routeIs('admin.payout.*');
        ?>

        <a href="<?php echo e(route('admin.settings')); ?>" class="<?php echo e($isSettingsActive ? 'active' : ''); ?>">
            <i class="fas fa-cog"></i><?php echo e(__('sidebar.settings')); ?>

        </a>
    </div>

    <div class="marketing-tools">
        <form action="<?php echo e(route('logout')); ?>" method="POST" style="display: flex; align-items: center;">
            <?php echo csrf_field(); ?>
            <button type="submit">
                <i class="fas fa-sign-out-alt"></i>
                <?php echo e(__('sidebar.logout')); ?>

            </button>
        </form>
    </div>

</div>
<?php /**PATH C:\Users\user\Documents\TUGAS PKL\Linkan.ID\resources\views/homeadminS/sidebar/sidebar.blade.php ENDPATH**/ ?>