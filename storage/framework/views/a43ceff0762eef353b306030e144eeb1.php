

<?php $__env->startSection("page_title", "Settings"); ?>

<?php $__env->startPush("styles"); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/pages/setting.css')); ?>" data-turbo-track="reload">
<?php $__env->stopPush(); ?>

<?php $__env->startSection("content"); ?>
<div class="dashboard-setting-page">



            <div class="settings-card" onclick="window.location.href='<?php echo e(route('admin.account')); ?>'">
                <div class="settings-card-icon">
                    <i class="fas fa-user"></i>
                </div>
                <div class="settings-card-content">
                    <h3>My Account</h3>
                    <p>Account detail, shop information, etc</p>
                </div>
            </div>

            <div class="settings-card" onclick="window.location.href='<?php echo e(route('admin.payout.index')); ?>'">
                <div class="settings-card-icon">
                    <i class="fas fa-wallet"></i>
                </div>
                <div class="settings-card-content">
                    <h3>Payout Settings</h3>
                    <p>Withdraw earnings, Bank account, etc</p>
                </div>
            </div>
            </div>

</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make("layouts.admin", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\TUGAS PKL\Linkan.ID\resources\views/homeadminS/setting.blade.php ENDPATH**/ ?>