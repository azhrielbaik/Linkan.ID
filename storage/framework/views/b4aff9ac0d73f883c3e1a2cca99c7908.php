<style>

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background-color: #e0e7ff;
            min-height: 100vh;
            padding: 20px;
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
/* Tambahkan ini ke dalam tag <style> di bagian atas */
    .sidebar a.active {
    background-color: #FF9040;
    color: white;
}

</style>
<div class="sidebar">
    <img src="<?php echo e(asset('images/Logo.png')); ?>" alt="Linkan Logo" class="logo">
    <a href="<?php echo e(route('platform-admin.dashboard')); ?>" class="<?php echo e(request()->routeIs('platform-admin.dashboard') ? 'active' : ''); ?>">
        <i class="fas fa-home"></i>Home
    </a>
    <a href="<?php echo e(route('platform-admin.verifikasi')); ?>" class="<?php echo e(request()->routeIs('platform-admin.verifikasi*') ? 'active' : ''); ?>">
        <i class="fas fa-check-circle"></i> Verification
    </a>
    <hr>

    <div class="marketing-tools">
        <form action="<?php echo e(route('logout')); ?>" method="POST" style="display: flex; align-items: center;">
            <?php echo csrf_field(); ?>
            <button type="submit" style="background: none; border: none; padding: 10px; margin: 0; display: flex; align-items: center; color: #1a1a1a; cursor: pointer; width: 100%; transition: 0.3s; border-radius: 5px;" onmouseover="this.style.backgroundColor='#d1d9ff'" onmouseout="this.style.backgroundColor='transparent'">
                <img src="<?php echo e(asset('images/logout.png')); ?>" alt="Logout" style="width: 20px; height: 20px; margin-right: 10px;">
                LogOut
            </button>
        </form>
    </div>
</div>

<?php /**PATH C:\Users\user\Documents\TUGAS PKL\Linkan.ID\resources\views/platformadmin/sidebar/sidebarplatform.blade.php ENDPATH**/ ?>