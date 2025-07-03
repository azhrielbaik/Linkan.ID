<style>
    /* Sidebar Styles */
    .sidebar {
        width: 250px;
        background-color: #e0e7ff;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        padding: 20px;
        overflow-y: auto;
        z-index: 100;
    }
    .sidebar .logo {
        width: 120px;
        margin-bottom: 30px;
    }
    .sidebar a {
        display: flex;
        align-items: center;
        text-decoration: none;
        color: #1a1a1a;
        padding: 10px;
        margin: 5px 0;
        border-radius: 5px;
        transition: 0.3s;
    }
    .sidebar a:hover {
        background-color: #d1d9ff;
    }
    .sidebar a i {
        margin-right: 10px;
        width: 20px;
    }
    .sidebar hr {
        border: none;
        border-top: 3px solid #000;
        margin: 15px 0;
    }
    /* Active menu */
    .sidebar a.active {
        background-color: #FF9040;
        color: white;
    }
    /* Submenu container */
    .submenu {
        padding-left: 20px;
        display: none; /* hide submenu by default */
        flex-direction: column;
    }
    /* Show submenu if parent active */
    .submenu.show {
        display: flex;
    }
    /* Submenu link style */
    .submenu a {
        padding: 8px 10px;
        font-size: 14px;
    }
</style>

<div class="sidebar">
    <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Linkan Logo" class="logo">

    <a href="<?php echo e(route('beranda.admins')); ?>" class="<?php echo e(request()->routeIs('beranda.admins') ? 'active' : ''); ?>">
        <i class="fas fa-home"></i>Home
    </a>
    <a href="<?php echo e(route('mylinkan')); ?>" class="<?php echo e(request()->routeIs('mylinkan') || request()->routeIs('digital-product.*') ? 'active' : ''); ?>">
        <i class="fas fa-user"></i>My Linkan
    </a>
    <a href="<?php echo e(route('appearance')); ?>" class="<?php echo e(request()->routeIs('appearance') ? 'active' : ''); ?>">
        <i class="fas fa-paint-brush"></i>Appearance
    </a>
    <a href="<?php echo e(route('shortlink.index')); ?>" class="<?php echo e(request()->routeIs('shortlink.index') ? 'active' : ''); ?>">
        <i class="fas fa-link"></i>Shortlink
    </a>
    <a href="<?php echo e(route('statistic')); ?>" class="<?php echo e(request()->routeIs('statistic') ? 'active' : ''); ?>">
        <i class="fas fa-chart-bar"></i>Statistic
    </a>
    <a href="<?php echo e(route('orders')); ?>" class="<?php echo e(request()->routeIs('orders') ? 'active' : ''); ?>">
        <i class="fas fa-shopping-cart"></i>Orders
    </a>
    <a href="<?php echo e(route('mypurchase')); ?>" class="<?php echo e(request()->routeIs('mypurchase') ? 'active' : ''); ?>">
        <i class="fas fa-box"></i>My Purchase
    </a>

    <?php
        // Cek jika route aktif untuk settings atau submenu terkait
        $isSettingsActive = request()->routeIs('settings') ||
                            request()->routeIs('account.settings') ||
                            request()->routeIs('payout.index');
    ?>

    <a href="<?php echo e(route('settings')); ?>" class="<?php echo e($isSettingsActive ? 'active' : ''); ?>">
        <i class="fas fa-cog"></i>Setting
    </a>


    <hr>

  <div class="marketing-tools">
    <form action="<?php echo e(route('logout')); ?>" method="POST" style="display: flex; align-items: center;">
        <?php echo csrf_field(); ?>
        <button type="submit" style="background: none; border: none; padding: 10px; margin: 0; display: flex; align-items: center; color: #1a1a1a; cursor: pointer;">
            <img src="<?php echo e(asset('images/logout.png')); ?>" alt="Logout" style="width: 20px; height: 20px; margin-right: 10px;">
            Logout
        </button>
    </form>
</div>

</div>
<?php /**PATH C:\LINKAN_ID\resources\views/homeadminS/sidebar/sidebar.blade.php ENDPATH**/ ?>