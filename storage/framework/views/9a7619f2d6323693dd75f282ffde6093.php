

<?php $__env->startSection("page_title", "Digital Product"); ?>

<?php $__env->startPush("styles"); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/pages/digital-product.css')); ?>" data-turbo-track="reload">
<?php $__env->stopPush(); ?>

<?php $__env->startSection("content"); ?>
<div class="dashboard-digital-product-page">

<div class="page-header">
                <h1 class="page-title">Add Digital Product</h1>
            </div>

            <!-- ✅ Alert messages -->
            <?php if(session('success')): ?>
                <div class="alert alert-success" style="background: #e0ffe0; padding: 10px; border-radius: 5px; margin-bottom: 20px; color: #007500;">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="alert alert-danger" style="background: #ffe3e3; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                    <ul style="margin: 0; padding-left: 20px; color: #b30000;">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if(isset($product) && $product->verification_status === 'rejected'): ?>
                <div class="alert alert-warning" style="background: #fff3cd; padding: 10px; border-radius: 5px; margin-bottom: 20px; color: #856404;">
                    <i class="fas fa-exclamation-triangle"></i> Produk ini sebelumnya ditolak. Silakan perbaiki dan kirim ulang untuk verifikasi.
                </div>
            <?php endif; ?>

            
            <form id="digitalProductForm"
      action="<?php echo e(isset($product) ? route('admin.digital-products.update', $product->id) : route('admin.digital-products.store')); ?>"
      method="POST" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>
    <?php if(isset($product)): ?>
        <?php echo method_field('PUT'); ?>
    <?php endif; ?>

                <?php echo csrf_field(); ?>
                <div class="content-card">
                    <h2 class="card-title">Details</h2>

                    <div class="form-group">
                        <label>Image</label>
                        <div class="image-upload" onclick="document.getElementById('productImage').click()">
                            <?php if(isset($product) && $product->image): ?>
                                <img src="<?php echo e(asset('storage/' . $product->image)); ?>" style="max-width: 100%; max-height: 100%; border-radius: 5px;">
                            <?php else: ?>
                                <i class="fas fa-image"></i>
                                <span>Add Image</span>
                            <?php endif; ?>
                        </div>
                        <div class="format-info">Format: PNG, JPEG, JPG <strong> (Optimize size 566 x 525 px)</strong> </div>
                        <input type="file" id="productImage" name="image" accept=".png,.jpg,.jpeg" style="display: none">
                    </div>

                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control" placeholder="Title" value="<?php echo e(isset($product) ? $product->title : old('title')); ?>">
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Description"><?php echo e(isset($product) ? $product->description : old('description')); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>Platform</label>
                        <div class="platform-container">
                            <div class="platform-options">
                                <button type="button" class="platform-button <?php echo e(isset($product) && $product->platform_type == 'upload' ? 'active' : ''); ?>" data-platform="upload">Upload</button>
                                <button type="button" class="platform-button <?php echo e(isset($product) && $product->platform_type == 'dropbox' ? 'active' : ''); ?>" data-platform="dropbox">Dropbox</button>
                                <button type="button" class="platform-button <?php echo e(isset($product) && $product->platform_type == 'gdrive' ? 'active' : ''); ?>" data-platform="gdrive">G-Drive</button>
                                <button type="button" class="platform-button <?php echo e(isset($product) && $product->platform_type == 'other' ? 'active' : ''); ?>" data-platform="other">Other</button>
                            </div>
                            <input type="hidden" name="platform_type" value="<?php echo e(isset($product) ? $product->platform_type : 'upload'); ?>">
                            <input type="text" class="form-control platform-input" placeholder="Enter your URL here" name="platform_url" style="display: <?php echo e(isset($product) && $product->platform_type != 'upload' ? 'block' : 'none'); ?>;" value="<?php echo e(isset($product) ? $product->platform_url : old('platform_url')); ?>">
                            <div class="select-file-button form-control" onclick="document.getElementById('platform_file').click()" style="text-align: center; cursor: pointer; display: <?php echo e(isset($product) && $product->platform_type == 'upload' ? 'block' : 'none'); ?>;">
                                <i class="fas fa-paperclip"></i> 
                                <span id="selected-file-name">
                                    <?php if(isset($product) && $product->platform_file): ?>
                                        <?php echo e(basename($product->platform_file)); ?>

                                    <?php else: ?>
                                        Select File
                                    <?php endif; ?>
                                </span>
                            </div>
                            <input type="file" id="platform_file" name="platform_file" style="display: none;">
                        </div>
                    </div>
                </div>

                <div class="content-card">
                    <h2 class="card-title">Pricing</h2>

                    <div class="form-group">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                            <label style="margin: 0;">Allow Customer to pay what they want</label>
                            <!-- ✅ Hidden input + checkbox -->
                            <input type="hidden" name="pay_what_want" value="0">
                            <label class="toggle-switch">
                                <input type="checkbox" name="pay_what_want" value="1" <?php echo e(old('pay_what_want') ? 'checked' : ''); ?>>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="price-group">
                            <div class="price-input">
                                <label>Price</label>
                                <input type="text" name="price" id="priceInput" class="form-control" placeholder="Rp 0" value="<?php echo e(isset($product) ? 'Rp ' . number_format($product->price, 0, ',', '.') : old('price')); ?>">
                                <input type="hidden" name="price_raw" id="priceRaw" value="<?php echo e(isset($product) ? $product->price : old('price')); ?>">
                            </div>
                            <div class="currency-input">
                                <label>Currency</label>
                                <input type="text" name="currency" class="form-control" value="IDR" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Purchase Button</label>
                        <select name="button_text" class="select-dropdown">
                            <option value="buy_now" <?php echo e((isset($product) && $product->button_text == 'buy_now') || old('button_text') == 'buy_now' ? 'selected' : ''); ?>>Buy Now</option>
                            <option value="purchase" <?php echo e((isset($product) && $product->button_text == 'purchase') || old('button_text') == 'purchase' ? 'selected' : ''); ?>>Purchase</option>
                            <option value="get_now" <?php echo e((isset($product) && $product->button_text == 'get_now') || old('button_text') == 'get_now' ? 'selected' : ''); ?>>Get Now</option>
                        </select>
                    </div>
                </div>

                <div class="action-buttons">
                    <button type="button" class="cancel-button" onclick="history.back()">Cancel</button>
                    <button type="submit" class="add-product-button">Add Product</button>
                </div>
            </form>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush("scripts"); ?>
<script>
// Format currency Rupiah
        function formatRupiah(angka) {
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return 'Rp ' + rupiah;
        }

        function unformatRupiah(rupiah) {
            return rupiah.replace(/[^\d]/g, '');
        }

        // Price input formatting
        const priceInput = document.getElementById('priceInput');
        const priceRaw = document.getElementById('priceRaw');

        priceInput.addEventListener('input', function(e) {
            let value = e.target.value;
            let unformatted = unformatRupiah(value);
            
            if (unformatted !== '') {
                let formatted = formatRupiah(unformatted);
                e.target.value = formatted;
                priceRaw.value = unformatted;
            } else {
                e.target.value = '';
                priceRaw.value = '';
            }
        });

        priceInput.addEventListener('blur', function(e) {
            let value = e.target.value;
            if (value === '' || value === 'Rp ') {
                e.target.value = '';
                priceRaw.value = '';
            }
        });

        // Image preview
        document.getElementById('productImage').addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imageUpload = document.querySelector('.image-upload');
                    imageUpload.innerHTML = `<img src="${e.target.result}" style="max-width: 100%; max-height: 100%; border-radius: 5px;">`;
                }
                reader.readAsDataURL(e.target.files[0]);
            }
        });

        // Platform file selection
        document.getElementById('platform_file').addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : 'Select File';
            document.getElementById('selected-file-name').textContent = fileName;
        });

        // Platform button selection
        document.querySelectorAll('.platform-button').forEach(button => {
            button.addEventListener('click', function() {
                document.querySelectorAll('.platform-button').forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');

                const platform = this.getAttribute('data-platform');
                const urlInput = document.querySelector('.platform-input');
                const fileButton = document.querySelector('.select-file-button');

                document.querySelector('input[name="platform_type"]').value = platform;

                if (platform === 'upload') {
                    urlInput.style.display = 'none';
                    fileButton.style.display = 'block';
                } else {
                    urlInput.style.display = 'block';
                    fileButton.style.display = 'none';
                }
            });
        });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make("layouts.admin", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\TUGAS PKL\Linkan.ID\resources\views/homeadminS/digital-product.blade.php ENDPATH**/ ?>