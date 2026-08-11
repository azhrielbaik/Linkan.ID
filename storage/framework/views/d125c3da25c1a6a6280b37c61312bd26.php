

<?php $__env->startSection("page_title", "My Purchases"); ?>

<?php $__env->startPush("styles"); ?>
<style>
.header h1 {
            font-size: 18px;
            color: #333;
        }
        
        .notification-icon i {
            color: #333;
            font-size: 16px;
        }
        .my-linkan-header {
            background: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .my-linkan-url {
            background: #f5f5f5;
            padding: 8px 15px;
            border-radius: 5px;
            flex-grow: 1;
            color: #666;
            font-weight: 600;
        }
        .share-button {
            background: none;
            border: none;
            color: #FF9040;
            cursor: pointer;
            padding: 5px;
            font-size: 18px;
        }
        .filter-sort-bar {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 18px;
        }
        .filter-sort-btn {
            display: flex;
            align-items: center;
            gap: 5px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 5px 12px;
            color: #666;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .filter-sort-btn:hover {
            background: #f5f5f5;
        }
        .search-bar {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 5px;
        }
        .search-bar input {
            width: 180px;
            border-radius: 5px;
            border: 1px solid #ddd;
            padding: 5px 10px;
            font-size: 14px;
        }
        .search-bar button {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 5px 10px;
            color: #666;
            font-size: 14px;
            cursor: pointer;
        }
        @media (max-width: 900px) {
            .main-content {
                margin-left: 0;
            }
        }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection("content"); ?>

        <!-- Card My Linkan URL -->
        <div class="my-linkan-header">
            <div class="my-linkan-url">
                <a href="<?php echo e(url('/linkan.id/' . Auth::user()->username)); ?>" style="color: #FF9040; text-decoration: none;">
                    <?php echo e(url('/linkan.id/' . Auth::user()->username)); ?>

                </a>
            </div>
            <button class="share-button" onclick="copyToClipboard('<?php echo e(url('/linkan.id/' . Auth::user()->username)); ?>')" title="Share">
                <i class="fas fa-share-alt"></i>
            </button>
        </div>
        <!-- Card Filter, Sort, Search, and Content -->
        <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
            <div class="filter-sort-bar">
                <button class="filter-sort-btn"><i class="fas fa-filter"></i> Filter</button>
                <button class="filter-sort-btn"><i class="fas fa-sort"></i> Sorting</button>
                <div class="search-bar">
                    <input type="text" placeholder="Search...">
                    <button><i class="fas fa-search"></i></button>
                </div>
            </div>
            <div style="margin-bottom: 10px; font-weight: 500; color: #888;">Content Purchase Search Result</div>
            <div class="row">
                <?php $__empty_1 = true; $__currentLoopData = $purchasedProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php if($product): ?>
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div style="display: flex; align-items: center; background: #f7f8fa; border-radius: 8px; box-shadow: 0 1px 4px rgba(0,0,0,0.04); padding: 12px 15px;">
                            <img src="<?php echo e($product->image ? asset('storage/'.$product->image) : asset('images/default-product.png')); ?>" alt="Product Image" style="width: 80px; height: 50px; object-fit: cover; border-radius: 6px; margin-right: 15px;" onerror="this.onerror=null;this.src='<?php echo e(asset('images/default-product.png')); ?>';">
                            <div>
                                <div style="font-weight: 600; color: #222;"><?php echo e($product->title); ?></div>
                                <div style="font-size: 12px; color: #888;">
                                    <?php echo e(optional($purchases->firstWhere('product_id', $product->id))->created_at ? optional($purchases->firstWhere('product_id', $product->id))->created_at->format('d M Y') : '-'); ?>

                                </div>
                                <span class="badge bg-secondary" style="font-size: 11px;">Purchased</span>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-12">
                    <div style="padding: 30px; text-align: center; color: #aaa;">No purchased content found.</div>
                </div>
                <?php endif; ?>
            </div>
        </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush("scripts"); ?>
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('Link copied to clipboard!');
    }, function(err) {
        alert('Failed to copy link');
    });
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make("layouts.admin", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\TUGAS PKL\Linkan.ID\resources\views/homeadminS/mypurchase.blade.php ENDPATH**/ ?>