<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Linkan - Dashboard</title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/favicon.png')); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f5f6fa;
        }


        .container {
    display: flex;
    min-height: 100vh;
            gap: 0;
            padding: 0;
}

        .main-content {
            flex: 1;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            min-width: 0; /* Untuk mencegah overflow */
            margin-left: 250px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 24px;
            color: #333;
        }

        .notification-icon {
            background-color: #fff;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
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
        }

        .my-linkan-url {
            background: #f5f5f5;
            padding: 8px 15px;
            border-radius: 5px;
            flex-grow: 1;
            color: #666;
        }

        .share-button {
            background: none;
            border: none;
            color: #FF9040;
            cursor: pointer;
            padding: 5px;
        }



        .settings-icon {
            color: #666;
            cursor: pointer;
        }





        .home-button {
            background: #FF9040;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 15px;
            border: none;
            cursor: pointer;
        }

        .add-block-button {
            background: #FF9040;
            color: white;
            padding: 12px;
            border-radius: 10px;
            border: none;
            width: 100%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
            font-size: 16px;
        }

        .block-list {
            margin-top: 20px;
        }

        .block-item {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .drag-handle {
            color: #666;
            cursor: move;
        }

        .block-icon {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #FF9040;
        }

        .block-title {
            flex-grow: 1;
            color: #333;
        }

        .block-actions {
            color: #666;
            cursor: pointer;
        }

        /* Preview Section */
        .preview-section {
            background: #eee;
            padding: 20px;
            border-radius: 10px;
            width: 100%;
            max-width: 430px;
            height: fit-content;
            position: sticky;
            top: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .preview-header {
            margin-bottom: 15px;
            color: #333;
            font-size: 18px;
            width: 100%;
            text-align: center;
        }

        .phone-preview {
            width: 100%;
            max-width: 375px;
            min-width: 250px;
            aspect-ratio: 9/19.5;
            min-height: 500px;
            max-height: 800px;
            border-radius: 40px;
            background: white;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

        .phone-content {
            width: 100%;
            height: 100%;
            background: #f8f9fa;
            border-radius: 30px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            overflow-y: auto;
        }

        .banner-preview {
            width: 100%;
            height: 120px;
            background: #ddd;
            border-radius: 10px;
            margin-bottom: 20px;
            overflow: hidden;
        }

        .banner-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #ddd;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .profile-circle img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .preview-name {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
            text-align: center;
        }

        .preview-bio {
            font-size: 14px;
            color: #666;
            text-align: center;
            margin-bottom: 15px;
            padding: 0 20px;
            line-height: 1.4;
        }

        .social-links {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }

        .social-links a {
            font-size: 20px;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .social-links a:hover {
            opacity: 0.8;
        }

        .preview-products {
            width: 100%;
            padding: 10px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .preview-product-item {
            background: white;
            border-radius: 8px;
            padding: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.2s ease;
        }

        .preview-product-item:hover {
            transform: translateY(-2px);
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

        .preview-product-info {
            flex: 1;
            min-width: 0;
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
            background: #FF9040;
            color: white;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 12px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
            flex-shrink: 0;
            min-width: 100px;
            text-align: center;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .preview-product-button:disabled {
            background-color: #e9ecef;
            color: #6c757d;
            cursor: not-allowed;
            opacity: 1;
        }

        .preview-product-button .status {
            font-size: 11px;
            padding: 2px 6px;
            border-radius: 3px;
            margin: 0;
            display: inline-block;
            background: transparent;
        }

        .preview-product-button .status.pending {
            color: #6c757d;
        }

        .preview-product-button .status.rejected {
            color: #6c757d;
        }

        .preview-product-button .rejection-reason {
            margin-top: 4px;
            padding: 4px 8px;
            background-color: #fff3f3;
            border: 1px solid #ffcdd2;
            border-radius: 4px;
            font-size: 11px;
            max-width: 100%;
            display: block;
            text-align: left;
        }

        .preview-product-button .rejection-reason strong {
            color: #d32f2f;
            display: inline;
            margin-right: 4px;
            font-size: 11px;
        }

        .preview-product-button .rejection-reason p {
            margin: 0;
            color: #666;
            line-height: 1.2;
            display: inline;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            backdrop-filter: blur(5px);
        }

        .modal-content {
            background-color: white;
            width: 400px;
            border-radius: 20px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            overflow: hidden;
        }

        .modal-header {
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #eee;
        }

        .modal-header h2 {
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }

        .close-button {
            background: none;
            border: none;
            font-size: 24px;
            color: #666;
            cursor: pointer;
            padding: 0;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-body {
            padding: 20px;
        }

        .block-option {
            display: flex;
            align-items: center;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .block-option:hover {
            background-color: #f5f5f5;
        }

        .block-option .block-icon {
            width: 48px;
            height: 48px;
            background: #FFE5D3;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }

        .block-option .block-icon img {
            width: 24px;
            height: 24px;
            object-fit: contain;
        }

        .block-option .block-info h3 {
            font-size: 16px;
            margin-bottom: 5px;
            color: #333;
        }

        .block-option .block-info p {
            font-size: 14px;
            color: #666;
            margin: 0;
        }

        /* Blur effect when modal is open */
        body.modal-open .container {
            filter: blur(5px);
            pointer-events: none;
            transition: filter 0.3s ease;
        }

        body.modal-open {
            overflow: hidden;
        }

        /* Coming Soon Block */
        .block-option.coming-soon {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .block-option.coming-soon .block-icon {
            background: #f5f5f5;
        }

        @media (max-width: 1200px) {
            .container {
                flex-direction: column;
                gap: 20px;
            }

            .main-content {
                width: 100%;
            }

            .preview-section {
                width: 100%;
                position: relative;
                top: 0;
            }

            .phone-preview {
                margin: 0 auto;
            }
        }

        /* Tambahkan style untuk tombol edit produk yang ditolak */
        .edit-rejected {
            display: none;
        }

        .rejection-reason {
            margin-top: 4px;
            padding: 4px 8px;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
            font-size: 12px;
            max-width: 90%;
            display: inline-block;
            color: #721c24;
            line-height: 1.3;
        }

        .rejection-reason strong {
            color: #721c24;
            display: inline;
            margin-right: 5px;
            font-weight: 600;
            font-size: 12px;
        }

        .rejection-reason p {
            margin: 0;
            color: #721c24;
            line-height: 1.3;
            display: inline;
            font-size: 12px;
        }

        .status {
            font-size: 12px;
            padding: 4px 8px;
            border-radius: 4px;
            margin-left: 8px;
            display: inline-block;
            line-height: 1.3;
        }

        .status.pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status.rejected {
            background-color: #f8d7da;
            color: #721c24;
        }

        .status.approved {
            background-color: #d4edda;
            color: #155724;
        }

        @media (max-width: 900px) {
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <?php echo $__env->make('homeadminS.sidebar.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="main-content">
            <div class="header">
                <h1>My Linkan</h1>
                <div class="notification-icon">
                    <i class="fas fa-bell"></i>
                </div>
            </div>

            <div class="my-linkan-header">
                <div class="my-linkan-url">
                    <a href="<?php echo e(url('/linkan.id/' . Auth::user()->username)); ?>" style="color: #FF9040;">
                        <?php echo e(url('/linkan.id/' . Auth::user()->username)); ?>

                    </a>

                </div>
                <button class="share-button" onclick="copyToClipboard('http://localhost:8000/linkan.id/<?php echo e(Auth::user()->username); ?>')">
                        <i class="fas fa-share-alt"></i>
                </button>
            </div>

            <button class="home-button">Home</button>

            <button class="add-block-button" onclick="showAddBlockModal()">
                <i class="fas fa-plus"></i>
                Add new block
            </button>
                        <?php if($digitalProducts->count()): ?>
                <div class="block-list">
                    <h3 style="margin-bottom: 10px; color: #333;">Produk Digital Terbaru</h3>
                    <?php $__currentLoopData = $digitalProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="block-item" onclick="showActionModal(<?php echo e($product->id); ?>, '<?php echo e($product->title); ?>')">
                        <i class="fas fa-grip-vertical drag-handle"></i>
                        <div class="block-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="block-title">
                            <?php echo e($product->title); ?>

                            <?php if($product->verification_status == 'pending'): ?>
                                <span class="status pending">Menunggu Verifikasi</span>
                            <?php elseif($product->verification_status == 'rejected'): ?>
                                <span class="status rejected">Ditolak</span>
                                <?php if($product->rejection_reason): ?>
                                    <div class="rejection-reason">
                                        <strong>Reason:</strong>
                                        <p><?php echo e($product->rejection_reason); ?></p>
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="status approved">Terverifikasi</span>
                            <?php endif; ?>
                        </div>
                        <div class="block-actions">
                            <i class="fas fa-ellipsis-v"></i>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
            </div>
    <!-- Preview Section -->
    <div class="preview-section">
        <div class="preview-header">
            <h2>Preview</h2>
        </div>
        <div class="phone-preview">
            <div class="phone-content" id="previewScreen" style="width: 100%; height: 100%; background: #f8f9fa; border-radius: 30px; padding: 20px; display: flex; flex-direction: column; align-items: center; overflow-y: auto; background-image: url('<?php echo e($appearance && $appearance->background_color ? asset('images/background/' . $appearance->background_color) : ''); ?>'); background-size: cover; background-position: center;">
                <?php if($appearance && $appearance->banner): ?>
                    <div class="banner-preview" style="width: 100%; height: 120px; background: #ddd; border-radius: 10px; margin-bottom: 20px; overflow: hidden;">
                        <img src="<?php echo e(asset('storage/' . $appearance->banner)); ?>" alt="Banner" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                <?php endif; ?>
                <div class="profile-circle" style="width: 80px; height: 80px; border-radius: 50%; background: #ddd; margin-bottom: 15px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                    <?php if($appearance && $appearance->profile_image): ?>
                        <img src="<?php echo e(asset('storage/' . $appearance->profile_image)); ?>" alt="Profile" style="width: 100%; height: 100%; object-fit: cover;">
                    <?php else: ?>
                        <i class="fas fa-user"></i>
                    <?php endif; ?>
                </div>
                <div class="preview-name" style="font-size: 18px; font-weight: 600; margin-bottom: 10px; text-align: center; color: <?php echo e($appearance ? $appearance->theme_color : '#FF9040'); ?>"><?php echo e($appearance ? $appearance->name : Auth::user()->name); ?></div>
                <?php if($appearance && $appearance->bio): ?>
                    <div class="preview-bio" style="font-size: 14px; color: <?php echo e($appearance ? $appearance->theme_color : '#FF9040'); ?>; text-align: center; margin-bottom: 15px; padding: 0 20px; line-height: 1.4;"><?php echo $appearance->bio; ?></div>
                <?php endif; ?>
                <div class="social-links" style="display: flex; gap: 15px; margin-bottom: 20px;">
                    <?php if($appearance && $appearance->instagram): ?>
                        <a href="<?php echo e($appearance->instagram); ?>" target="_blank"><i class="fab fa-instagram" style="color: <?php echo e($appearance ? $appearance->theme_color : '#FF9040'); ?>"></i></a>
                    <?php endif; ?>
                    <?php if($appearance && $appearance->tiktok): ?>
                        <a href="<?php echo e($appearance->tiktok); ?>" target="_blank"><i class="fab fa-tiktok" style="color: <?php echo e($appearance ? $appearance->theme_color : '#FF9040'); ?>"></i></a>
                    <?php endif; ?>
                    <?php if($appearance && $appearance->whatsapp): ?>
                        <a href="<?php echo e($appearance->whatsapp); ?>" target="_blank"><i class="fab fa-whatsapp" style="color: <?php echo e($appearance ? $appearance->theme_color : '#FF9040'); ?>"></i></a>
                    <?php endif; ?>
                </div>
                <?php if($appearance && $appearance->description): ?>
                    <div class="preview-bio" style="color: <?php echo e($appearance ? $appearance->theme_color : '#FF9040'); ?>"><?php echo e($appearance->description); ?></div>
                <?php endif; ?>
                <?php if($appearance && $appearance->link): ?>
                    <a href="<?php echo e($appearance->link); ?>" class="preview-product-button" style="background-color: <?php echo e($appearance ? $appearance->theme_color : '#FF9040'); ?>"><?php echo e($appearance->button_text ?? 'Beli'); ?></a>
                <?php endif; ?>
                <?php if($digitalProducts->count() > 0): ?>
                    <div class="preview-products" style="width: 100%; padding: 10px; display: flex; flex-direction: column; gap: 10px;">
                        <?php $__currentLoopData = $digitalProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="preview-product-item" style="background: white; border-radius: 8px; padding: 10px; display: flex; align-items: center; gap: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: transform 0.2s ease;">
                                <div class="preview-product-image" style="width: 40px; height: 40px; background: #FFE5D3; border-radius: 6px; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0;">
                                    <?php if($product->image): ?>
                                        <img src="<?php echo e(asset('storage/' . $product->image)); ?>" alt="<?php echo e($product->title); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                    <?php else: ?>
                                        <i class="fas fa-file-alt"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="preview-product-info" style="flex: 1; min-width: 0;">
                                    <div class="preview-product-title" style="font-size: 14px; color: #333; margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?php echo e($product->title); ?></div>
                                </div>
                                <?php if($product->verification_status == 'approved'): ?>
                                    <a href="<?php echo e(route('track.click', ['link_id' => Auth::user()->username, 'target' => $product->platform_url ?? '#'])); ?>" class="preview-product-button" style="background-color: <?php echo e($appearance ? $appearance->theme_color : '#FF9040'); ?>; color: white; padding: 4px 12px; border-radius: 4px; font-size: 12px; border: none; cursor: pointer; transition: background-color 0.3s ease; flex-shrink: 0; min-width: 100px; text-align: center; height: 28px; display: flex; align-items: center; justify-content: center; text-decoration: none;" target="_blank"><?php echo e(str_replace('_', ' ', $product->button_text ?? 'Beli')); ?></a>
                                <?php else: ?>
                                    <button class="preview-product-button" disabled style="background-color: #e9ecef; color: #6c757d; cursor: not-allowed; opacity: 1;">
                                        <?php if($product->verification_status == 'pending'): ?>
                                            <span class="status pending">Menunggu Verifikasi</span>
                                        <?php else: ?>
                                            <span class="status rejected">Ditolak</span>
                                        <?php endif; ?>
                                    </button>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

    <!-- Modal untuk Add New Block -->
    <div id="addBlockModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add new block</h2>
                <button class="close-button" onclick="closeModal()">×</button>
            </div>
            <div class="modal-body">
                <div class="block-option" onclick="selectBlockType('digital')">
                    <div class="block-icon">
                        <img src="<?php echo e(asset('images/productdigital.png')); ?>" alt="Digital Product">
                    </div>
                    <div class="block-info">
                        <h3>Digital Product</h3>
                        <p>Sell Digital Products</p>
                    </div>
                </div>

                <div class="block-option coming-soon">
                    <div class="block-icon">
                        <i class="fas fa-question" style="color: #FF9040; font-size: 24px;"></i>
                    </div>
                    <div class="block-info">
                        <h3>COMMING SOON</h3>
                        <p>Let's see</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="actionModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Action</h2>
            <button class="close-button" onclick="closeActionModal()">×</button>
        </div>
        <div class="modal-body">
            <!-- Tombol Edit -->
            <a href="#" id="editButton" class="add-block-button" style="background-color: #FF9040; margin-bottom: 10px; text-decoration: none; text-align: center;">Edit</a>
            <!-- Tombol Delete -->
            <form id="deleteForm" method="POST" action="">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="button" class="add-block-button" style="background-color: #e3342f;" onclick="confirmDelete()">Delete</button>
            </form>
        </div>
    </div>
</div>


<div id="confirmDeleteModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Konfirmasi Hapus</h2>
            <button class="close-button" onclick="closeConfirmDeleteModal()">×</button>
        </div>
        <div class="modal-body">
            <p id="deleteMessage" style="margin-bottom: 20px;"></p>
            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button onclick="closeConfirmDeleteModal()" class="add-block-button" style="background-color: gray;">No</button>
                <form id="finalDeleteForm" method="POST" action="">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="add-block-button" style="background-color: #e3342f;">Yes</button>
                </form>
            </div>
        </div>
    </div>
</div>

    <script>
          function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('Link copied to clipboard!');
            }).catch(err => {
                console.error('Failed to copy text: ', err);
            });
        }
        function showAddBlockModal() {
            document.getElementById('addBlockModal').style.display = 'block';
            document.body.classList.add('modal-open');
        }

        function closeModal() {
            document.getElementById('addBlockModal').style.display = 'none';
            document.body.classList.remove('modal-open');
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.className === 'modal') {
                closeModal();
            }
        }

        function selectBlockType(type) {
            switch(type) {
                case 'digital':
                    window.location.href = "<?php echo e(route('digital-product.create')); ?>";
                    break;
                case 'donation':
                    // Akan ditambahkan nanti
                    break;
                case 'course':
                    // Akan ditambahkan nanti
                    break;
            }
        }

        function showActionModal(productId, productTitle) {
            // Simpan ID dan judul produk untuk keperluan penghapusan
            window.currentDeleteId = productId;
            window.currentDeleteTitle = productTitle;

            // Set action tombol Delete untuk modal
            document.getElementById('deleteForm').action = `/digital-product/${productId}`;

            // Set URL tombol Edit
            document.getElementById('editButton').href = `/digital-product/${productId}/edit`;

            // Tampilkan modal
            document.getElementById('actionModal').style.display = 'block';
            document.body.classList.add('modal-open');
        }

        function closeActionModal() {
            // Menutup modal
            document.getElementById('actionModal').style.display = 'none';
            document.body.classList.remove('modal-open');
        }

        function confirmDelete() {
            // Menampilkan pesan konfirmasi
            const title = window.currentDeleteTitle;
            const productId = window.currentDeleteId;

            document.getElementById('deleteMessage').innerText = `Apakah kamu yakin ingin menghapus produk "${title}"?`;
            document.getElementById('finalDeleteForm').action = `/digital-product/${productId}`;

            // Menutup modal sebelumnya dan menampilkan konfirmasi hapus
            closeActionModal();
            document.getElementById('confirmDeleteModal').style.display = 'block';
            document.body.classList.add('modal-open');
        }

        function closeConfirmDeleteModal() {
            document.getElementById('confirmDeleteModal').style.display = 'none';
            document.body.classList.remove('modal-open');
        }
    </script>
</body>
</html>
<?php /**PATH C:\LINKAN_ID\resources\views/homeadminS/mylinkan.blade.php ENDPATH**/ ?>