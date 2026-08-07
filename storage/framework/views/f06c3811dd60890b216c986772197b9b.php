<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo e($appearance->name ?? $user->name); ?> | Linkan.id</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/favicon.png')); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            background: #f8f9fa;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            padding: 30px;
            min-height: 100vh;
            margin: 0;
        }

      .content-wrapper {
    width: 100%;
    max-width: 400px;
    background: white;
    border-radius: 20px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    background-image: url('<?php echo e($appearance && $appearance->background_color ? asset('images/background/' . $appearance->background_color) : ''); ?>');
    background-size: cover;
    background-position: center;
    display: flex;
    flex-direction: column; /* Membuat semua elemen tampil vertikal */
    align-items: center; /* Memastikan elemen di tengah */
}

        .preview-banner {
            width: 100%;
            height: 160px;
            background: #ddd;
            border-radius: 10px;
            margin-bottom: 20px;
            overflow: hidden;
        }

        .preview-banner img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .preview-profile {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #ddd;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .preview-profile img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .preview-name {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
            text-align: center;
            color: <?php echo e($appearance->theme_color ?? '#FF9040'); ?>;
        }

        .preview-bio {
            font-size: 14px;
            color: <?php echo e($appearance->theme_color ?? '#FF9040'); ?>;
            text-align: center;
            margin-bottom: 15px;
            padding: 0 20px;
            line-height: 1.4;
        }

        .preview-social-links {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .preview-social-links a {
            color: <?php echo e($appearance->theme_color ?? '#FF9040'); ?>;
            font-size: 20px;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .preview-social-links a:hover {
            opacity: 0.8;
        }

       .preview-products {
    width: 100%;
    padding: 10px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    align-items: center; /* Memastikan produk berada di tengah */
}
.product-info {
    flex: 1;
    overflow: hidden; /* jika teks panjang */
}
      .preview-product-item {
    background: white;
    border-radius: 8px;
    padding: 10px;
    display: flex;
    align-items: center;
    gap: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    justify-content: space-between;
    width: 100%; /* Pastikan item mengambil ruang penuh */
}


        .preview-product-image {
            width: 40px;
            height: 40px;
            background: #FFE5D3;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
        }

        .preview-product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .preview-product-title {
            font-size: 14px;
            color: #333;
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .preview-product-button {
            background: <?php echo e($appearance->theme_color ?? '#FF9040'); ?>;
            color: white;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 12px;
            border: none;
            cursor: pointer;
            flex-shrink: 0;
            text-decoration: none;
            transition: background 0.3s ease;
            margin-left: auto; /* penting agar tombol terdorong ke kanan */
        }
    </style>
</head>
<body>
    <div class="content-wrapper">
        <?php if($appearance && $appearance->banner): ?>
            <div class="preview-banner">
                <img src="<?php echo e(asset('storage/' . $appearance->banner)); ?>" alt="Banner">
            </div>
        <?php endif; ?>

        <div class="preview-profile">
            <?php if($appearance && $appearance->profile_image): ?>
                <img src="<?php echo e(asset('storage/' . $appearance->profile_image)); ?>" alt="Profile Image">
            <?php else: ?>
                <i class="fas fa-user"></i>
            <?php endif; ?>
        </div>

        <div class="preview-name"><?php echo e($appearance->name ?? $user->name); ?></div>
        <div class="preview-bio"><?php echo $appearance->bio ?? ''; ?></div>

             <div class="preview-social-links" id="livePreviewSocialLinks">
                                     <?php if($appearance && $appearance->instagram): ?>
    <a href="<?php echo e($appearance->instagram); ?>" target="_blank"><i class="fab fa-instagram"></i></a>
<?php endif; ?>
<?php if($appearance && $appearance->tiktok): ?>
    <a href="<?php echo e($appearance->tiktok); ?>" target="_blank"><i class="fab fa-tiktok"></i></a>
<?php endif; ?>
<?php if($appearance && $appearance->whatsapp): ?>
    <a href="<?php echo e($appearance->whatsapp); ?>" target="_blank"><i class="fab fa-whatsapp"></i></a>
<?php endif; ?>
<?php if($appearance && $appearance->linkedin): ?>
    <a href="<?php echo e($appearance->linkedin); ?>" target="_blank"><i class="fab fa-linkedin"></i></a>
<?php endif; ?>
<?php if($appearance && $appearance->facebook): ?>
    <a href="<?php echo e($appearance->facebook); ?>" target="_blank"><i class="fab fa-facebook"></i></a>
<?php endif; ?>
<?php if($appearance && $appearance->website): ?>
    <a href="<?php echo e($appearance->website); ?>" target="_blank"><i class="fas fa-globe"></i></a>
<?php endif; ?>
<?php if($appearance && $appearance->twitter): ?>
    <a href="<?php echo e($appearance->twitter); ?>" target="_blank"><i class="fab fa-twitter"></i></a>
<?php endif; ?>
<?php if($appearance && $appearance->youtube): ?>
    <a href="<?php echo e($appearance->youtube); ?>" target="_blank"><i class="fab fa-youtube"></i></a>
<?php endif; ?>
<?php if($appearance && $appearance->telegram): ?>
    <a href="<?php echo e($appearance->telegram); ?>" target="_blank"><i class="fab fa-telegram"></i></a>
<?php endif; ?>
<?php if($appearance && $appearance->email): ?>
    <a href="mailto:<?php echo e($appearance->email); ?>"><i class="fas fa-envelope"></i></a>
<?php endif; ?>
<?php if($appearance && $appearance->discord): ?>
    <a href="<?php echo e($appearance->discord); ?>" target="_blank"><i class="fab fa-discord"></i></a>
<?php endif; ?>

                                    </div>
        <?php if($products && $products->count() > 0): ?>
            <div class="preview-products">
                <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($product->verification_status == 'approved'): ?>
                        <div class="preview-product-item">
                            <div class="preview-product-image">
                                <?php if($product->image): ?>
                                    <img src="<?php echo e(asset('storage/' . $product->image)); ?>" alt="<?php echo e($product->title); ?>">
                                <?php else: ?>
                                    <i class="fas fa-file-alt"></i>
                                <?php endif; ?>
                            </div>
                            <div class="product-info">
                                <div class="preview-product-title"><?php echo e($product->title); ?></div>
                            </div>
                            <a href="<?php echo e(route('track.click', ['link_id' => $user->username, 'target' => route('product.show', $product->id)])); ?>" class="preview-product-button">
                                <?php echo e(str_replace('_', ' ', $product->button_text ?? 'Beli')); ?>

                            </a>
                        </div>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
<?php /**PATH /home/rakanyuka/Documents/PKL/Linkan/resources/views/public/profile.blade.php ENDPATH**/ ?>