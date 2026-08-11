<?php $__env->startSection("page_title", "Microsite Management"); ?>

<?php $__env->startPush("styles"); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/pages/mylinkan.css')); ?>" data-turbo-track="reload">
<?php $__env->stopPush(); ?>

<?php $__env->startSection("content"); ?>
<div class="dashboard-mylinkan-page">

<div class="microsite-container">
    
    <!-- HEADER TITLE & MODE SWITCH -->
    <div class="header-title-section">
        <div>
            <h1><?php echo e(__('sidebar.microsite')); ?> Management</h1>
            <p>Kelola galeri microsite, lihat screenshot preview, dan kustomisasi konten publik Anda.</p>
        </div>
        
        <div class="mode-switch-btn">
            <a href="<?php echo e(route('admin.mylinkan', ['mode' => 'gallery'])); ?>" class="<?php echo e($viewMode == 'gallery' ? 'active' : ''); ?>">
                <i class="fas fa-th-large"></i> Galeri Microsite
            </a>
            <a href="<?php echo e(route('admin.mylinkan', ['mode' => 'edit'])); ?>" class="<?php echo e($viewMode == 'edit' ? 'active' : ''); ?>">
                <i class="fas fa-edit"></i> Edit Konten & Blok
            </a>
        </div>
    </div>

    <!-- STATS SUMMARY ROW -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: #FFF3E6; color: #FF9040;">
                <i class="fas fa-pager"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">1</div>
                <div class="stat-label">Microsite Aktif</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: #E6F6FF; color: #0088FF;">
                <i class="fas fa-eye"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value"><?php echo e(number_format($totalViews ?? 0)); ?></div>
                <div class="stat-label">Total Penayangan</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: #E6FFFA; color: #00B894;">
                <i class="fas fa-box-open"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value"><?php echo e($totalProducts ?? 0); ?></div>
                <div class="stat-label">Produk Terpasang</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: #FFF0F6; color: #E83E8C;">
                <i class="fas fa-link"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value"><?php echo e($totalShortlinks ?? 0); ?></div>
                <div class="stat-label">Shortlink Terhubung</div>
            </div>
        </div>
    </div>

    <?php if($viewMode == 'gallery'): ?>
        <!-- GALLERY LIST VIEW -->
        <div class="section-header">
            <h2><i class="fas fa-layer-group" style="color: #FF9040;"></i> Daftar Microsite Saya</h2>
        </div>

        <div class="microsite-gallery-grid">
            
            <!-- MAIN MICROSITE CARD WITH PHONE SCREENSHOT THUMBNAIL -->
            <div class="microsite-card">
                
                <!-- CARD HEADER & THUMBNAIL CONTAINER -->
                <div class="card-thumbnail-container">
                    <span class="status-badge active">
                        <span class="dot"></span> Live Profil Utama
                    </span>

                    <!-- REAL PHONE THUMBNAIL REPRESENTATION -->
                    <div class="phone-thumbnail">
                        <div class="phone-thumbnail-screen" style="
                            background-image: url('<?php echo e($appearance && $appearance->background_color ? asset('images/background/' . $appearance->background_color) : ''); ?>');
                            background-color: <?php echo e($appearance && $appearance->background_color ? 'transparent' : '#f8f9fa'); ?>;
                        ">
                            <!-- Banner -->
                            <div class="phone-thumb-banner">
                                <?php if($appearance && $appearance->banner): ?>
                                    <img src="<?php echo e(asset('storage/' . $appearance->banner)); ?>" alt="Banner">
                                <?php endif; ?>
                            </div>

                            <!-- Avatar -->
                            <div class="phone-thumb-avatar">
                                <?php if($appearance && $appearance->profile_image): ?>
                                    <img src="<?php echo e(asset('storage/' . $appearance->profile_image)); ?>" alt="Avatar">
                                <?php else: ?>
                                    <i class="fas fa-user" style="color: #888; font-size: 16px;"></i>
                                <?php endif; ?>
                            </div>

                            <!-- Name & Bio -->
                            <div class="phone-thumb-name" style="color: <?php echo e($appearance ? $appearance->theme_color : '#FF9040'); ?>">
                                <?php echo e($appearance ? $appearance->name : Auth::user()->name); ?>

                            </div>
                            <div class="phone-thumb-bio" style="color: <?php echo e($appearance ? $appearance->theme_color : '#666'); ?>">
                                <?php echo strip_tags($appearance->bio ?? 'Selamat datang di linkan saya!'); ?>

                            </div>

                            <!-- Block Preview Snippets -->
                            <?php if($digitalProducts && $digitalProducts->count() > 0): ?>
                                <?php $__currentLoopData = $digitalProducts->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prod): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="phone-thumb-block">
                                        <i class="fas fa-box"></i>
                                        <span><?php echo e($prod->title); ?></span>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>

                            <?php if($shortlinks && $shortlinks->count() > 0): ?>
                                <?php $__currentLoopData = $shortlinks->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="phone-thumb-block">
                                        <i class="fas fa-link"></i>
                                        <span><?php echo e($sl->title ?: $sl->slug); ?></span>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- CARD BODY DETAILS -->
                <div class="card-body-details">
                    <div class="card-title-row">
                        <h3 class="microsite-name"><?php echo e($appearance->name ?? Auth::user()->name); ?></h3>
                    </div>

                    <a href="<?php echo e(url('/linkan.id/' . Auth::user()->username)); ?>" target="_blank" class="url-pill">
                        <i class="fas fa-globe"></i> linkan.id/<?php echo e(Auth::user()->username); ?>

                    </a>

                    <div class="card-stats-tags">
                        <span class="stat-tag"><i class="fas fa-eye"></i> <?php echo e(number_format($totalViews)); ?> views</span>
                        <span class="stat-tag"><i class="fas fa-cube"></i> <?php echo e($digitalProducts->count()); ?> produk</span>
                        <span class="stat-tag"><i class="fas fa-link"></i> <?php echo e($shortlinks->total()); ?> link</span>
                    </div>

                    <!-- ACTION BUTTONS -->
                    <div class="card-actions-grid">
                        <button type="button" class="btn-action-secondary" onclick="openPreviewModal('<?php echo e(url('/linkan.id/' . Auth::user()->username)); ?>')">
                            <i class="fas fa-mobile-alt"></i> Preview
                        </button>
                        <a href="<?php echo e(route('admin.mylinkan', ['mode' => 'edit'])); ?>" class="btn-action-primary">
                            <i class="fas fa-edit"></i> Edit Blok
                        </a>
                        <a href="<?php echo e(route('admin.appearance')); ?>" class="btn-action-secondary">
                            <i class="fas fa-paint-brush"></i> Tampilan
                        </a>
                        <button type="button" class="btn-action-secondary" onclick="copyToClipboard('<?php echo e(url('/linkan.id/' . Auth::user()->username)); ?>')">
                            <i class="fas fa-copy"></i> Salin Link
                        </button>
                    </div>
                </div>

            </div>

            <!-- CREATE NEW MICROSITE / TEMPLATE CARD -->
            <div class="create-new-card" onclick="showAddBlockModal()">
                <div class="create-icon-wrapper">
                    <i class="fas fa-plus"></i>
                </div>
                <div class="create-title">Tambah Blok Baru</div>
                <div class="create-subtitle">Tambahkan produk digital atau tautan singkat ke microsite Anda</div>
                <button type="button" class="btn-action-primary">
                    <i class="fas fa-plus-circle"></i> Tambah Blok
                </button>
            </div>

        </div>

    <?php else: ?>
        <!-- EDITOR VIEW MODE -->
        <div class="section-header">
            <h2>
                <a href="<?php echo e(route('admin.mylinkan', ['mode' => 'gallery'])); ?>" style="color: #6b7280; text-decoration: none; margin-right: 10px;">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <i class="fas fa-sliders-h" style="color: #FF9040;"></i> Editor Konten & Blok
            </h2>
            <button class="btn-action-primary" onclick="showAddBlockModal()">
                <i class="fas fa-plus"></i> Add New Block
            </button>
        </div>

        <div class="editor-layout">
            <!-- LEFT PANEL: BLOCK MANAGEMENT -->
            <div class="editor-left-panel">
                
                <!-- URL HEADER BAR -->
                <div style="background: #f9fafb; padding: 14px 18px; border-radius: 12px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; border: 1px solid #e5e7eb;">
                    <span style="font-weight: 600; font-size: 14px; color: #374151;">
                        <i class="fas fa-link" style="color: #FF9040; margin-right: 8px;"></i> <?php echo e(url('/linkan.id/' . Auth::user()->username)); ?>

                    </span>
                    <button type="button" class="btn-action-secondary" style="padding: 6px 12px; font-size: 12px;" onclick="copyToClipboard('<?php echo e(url('/linkan.id/' . Auth::user()->username)); ?>')">
                        <i class="fas fa-copy"></i> Salin
                    </button>
                </div>

                <!-- DIGITAL PRODUCTS BLOCKS -->
                <?php if($digitalProducts->count()): ?>
                    <div style="margin-bottom: 30px;">
                        <h3 style="font-size: 16px; font-weight: 700; color: #111827; margin-bottom: 14px;">Produk Digital Saya</h3>
                        <?php $__currentLoopData = $digitalProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="block-item-card" onclick="showActionModal(<?php echo e($product->id); ?>, '<?php echo e($product->title); ?>')">
                                <i class="fas fa-grip-vertical" style="color: #9ca3af; cursor: move;"></i>
                                <div style="width: 36px; height: 36px; border-radius: 8px; background: #FFE5D3; color: #FF9040; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <div style="flex: 1; min-width: 0;">
                                    <div style="font-weight: 700; font-size: 14px; color: #111827;"><?php echo e($product->title); ?></div>
                                    <?php if($product->verification_status == 'pending'): ?>
                                        <span class="status pending" style="font-size: 11px; background: #fff3cd; color: #856404; padding: 2px 8px; border-radius: 4px;">Menunggu Verifikasi</span>
                                    <?php elseif($product->verification_status == 'rejected'): ?>
                                        <span class="status rejected" style="font-size: 11px; background: #f8d7da; color: #721c24; padding: 2px 8px; border-radius: 4px;">Ditolak</span>
                                    <?php else: ?>
                                        <span class="status approved" style="font-size: 11px; background: #d4edda; color: #155724; padding: 2px 8px; border-radius: 4px;">Terverifikasi</span>
                                    <?php endif; ?>
                                </div>
                                <i class="fas fa-ellipsis-v" style="color: #6b7280; cursor: pointer;"></i>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>

                <!-- SHORTLINKS BLOCKS -->
                <?php if($shortlinks->count()): ?>
                    <div>
                        <h3 style="font-size: 16px; font-weight: 700; color: #111827; margin-bottom: 14px;">Tautan Pendek (Shortlinks)</h3>
                        <?php $__currentLoopData = $shortlinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="block-item-card" onclick="window.location.href='<?php echo e(route('admin.shortlinks.index')); ?>'">
                                <i class="fas fa-grip-vertical" style="color: #9ca3af; cursor: move;"></i>
                                <div style="width: 36px; height: 36px; border-radius: 8px; background: #E6F6FF; color: #0088FF; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-link"></i>
                                </div>
                                <div style="flex: 1; min-width: 0;">
                                    <div style="font-weight: 700; font-size: 14px; color: #111827;"><?php echo e($link->title ?: $link->slug); ?></div>
                                    <div style="font-size: 12px; color: #FF9040;">linkan.id/<?php echo e($link->slug); ?></div>
                                </div>
                                <i class="fas fa-external-link-alt" style="color: #6b7280;"></i>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <div style="margin-top: 15px;">
                            <?php echo e($shortlinks->appends(request()->except('links_page'))->links()); ?>

                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- RIGHT PANEL: STICKY PHONE PREVIEW -->
            <div class="editor-sticky-preview">
                <h3 style="font-size: 16px; font-weight: 700; color: #1f2937; margin-bottom: 14px;">Live Phone Preview</h3>
                <div class="phone-preview" style="width: 100%; max-width: 320px; aspect-ratio: 9/19; border-radius: 36px; background: white; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border: 4px solid #111827;">
                    <div class="phone-content" style="width: 100%; height: 100%; padding: 18px 14px; overflow-y: auto; background-image: url('<?php echo e($appearance && $appearance->background_color ? asset('images/background/' . $appearance->background_color) : ''); ?>'); background-size: cover; background-position: center;">
                        <?php if($appearance && $appearance->banner): ?>
                            <div style="width: 100%; height: 100px; border-radius: 10px; overflow: hidden; margin-bottom: 14px;">
                                <img src="<?php echo e(asset('storage/' . $appearance->banner)); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        <?php endif; ?>

                        <div style="width: 70px; height: 70px; border-radius: 50%; overflow: hidden; margin: 0 auto 10px; background: #e5e7eb; display: flex; align-items: center; justify-content: center;">
                            <?php if($appearance && $appearance->profile_image): ?>
                                <img src="<?php echo e(asset('storage/' . $appearance->profile_image)); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            <?php else: ?>
                                <i class="fas fa-user" style="font-size: 24px; color: #888;"></i>
                            <?php endif; ?>
                        </div>

                        <div style="font-size: 16px; font-weight: 700; text-align: center; margin-bottom: 6px; color: <?php echo e($appearance ? $appearance->theme_color : '#FF9040'); ?>">
                            <?php echo e($appearance ? $appearance->name : Auth::user()->name); ?>

                        </div>

                        <?php if($appearance && $appearance->bio): ?>
                            <div style="font-size: 12px; text-align: center; margin-bottom: 14px; color: <?php echo e($appearance ? $appearance->theme_color : '#666'); ?>">
                                <?php echo $appearance->bio; ?>

                            </div>
                        <?php endif; ?>

                        <?php if($digitalProducts->count() > 0): ?>
                            <div style="display: flex; flex-direction: column; gap: 8px; width: 100%;">
                                <?php $__currentLoopData = $digitalProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div style="background: white; border-radius: 8px; padding: 8px 10px; display: flex; align-items: center; gap: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                        <div style="width: 32px; height: 32px; border-radius: 6px; background: #FFE5D3; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                            <i class="fas fa-file-alt" style="color: #FF9040; font-size: 12px;"></i>
                                        </div>
                                        <div style="font-size: 12px; font-weight: 600; color: #333; flex: 1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?php echo e($product->title); ?></div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

</div>

<!-- INTERACTIVE PHONE PREVIEW MODAL -->
<div class="preview-modal-overlay" id="previewModalOverlay">
    <div class="phone-modal-wrapper">
        <button type="button" class="btn-close-modal" onclick="closePreviewModal()">&times;</button>
        
        <div class="phone-modal-header">
            <i class="fas fa-globe" style="color: #FF9040;"></i>
            <span id="modalUrlText">linkan.id/<?php echo e(Auth::user()->username); ?></span>
            <button type="button" class="btn-action-secondary" style="padding: 4px 10px; font-size: 11px;" onclick="copyToClipboard('<?php echo e(url('/linkan.id/' . Auth::user()->username)); ?>')">
                <i class="fas fa-copy"></i>
            </button>
            <a href="<?php echo e(url('/linkan.id/' . Auth::user()->username)); ?>" target="_blank" class="btn-action-primary" style="padding: 4px 10px; font-size: 11px;">
                <i class="fas fa-external-link-alt"></i> Buka
            </a>
        </div>

        <div class="phone-device-frame">
            <div class="phone-notch"></div>
            <iframe src="" class="phone-iframe" id="phonePreviewIframe"></iframe>
        </div>
    </div>
</div>

<!-- MODAL ADD BLOCK -->
<div id="addBlockModal" class="modal">
    <div class="modal-content" style="border-radius: 20px; overflow: hidden;">
        <div class="modal-header" style="background: #f9fafb; padding: 18px 24px; border-bottom: 1px solid #e5e7eb;">
            <h2 style="font-size: 18px; font-weight: 700; margin: 0;">Tambah Blok Baru</h2>
            <button class="close-button" onclick="closeModal()">×</button>
        </div>
        <div class="modal-body" style="padding: 24px;">
            <div class="block-option" onclick="selectBlockType('digital')" style="border: 1px solid #e5e7eb; border-radius: 14px; padding: 16px; margin-bottom: 14px; cursor: pointer; transition: all 0.2s ease;">
                <div class="block-icon">
                    <img src="<?php echo e(asset('images/productdigital.png')); ?>" alt="Digital Product" style="width: 28px;">
                </div>
                <div class="block-info">
                    <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 4px;">Produk Digital</h3>
                    <p style="font-size: 13px; color: #6b7280; margin: 0;">Jual produk digital, e-book, lisensi, atau file</p>
                </div>
            </div>

            <div class="block-option" onclick="selectBlockType('shortlink')" style="border: 1px solid #e5e7eb; border-radius: 14px; padding: 16px; cursor: pointer; transition: all 0.2s ease;">
                <div class="block-icon">
                    <i class="fas fa-link" style="color: #FF9040; font-size: 24px;"></i>
                </div>
                <div class="block-info">
                    <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 4px;">Tautan Pendek</h3>
                    <p style="font-size: 13px; color: #6b7280; margin: 0;">Buat dan hubungkan tautan singkat kustom</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ACTION MODAL & DELETE CONFIRMATION -->
<div id="actionModal" class="modal">
    <div class="modal-content" style="border-radius: 20px; padding: 20px;">
        <div class="modal-header">
            <h2>Aksi Produk</h2>
            <button class="close-button" onclick="closeActionModal()">×</button>
        </div>
        <div class="modal-body" style="padding-top: 14px;">
            <a href="#" id="editButton" class="btn-action-primary" style="margin-bottom: 10px; width: 100%;">
                <i class="fas fa-edit"></i> Edit Produk
            </a>
            <form id="deleteForm" method="POST" action="">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="button" class="btn-action-secondary" style="color: #ef4444; border-color: #fca5a5; width: 100%;" onclick="confirmDelete()">
                    <i class="fas fa-trash"></i> Hapus Produk
                </button>
            </form>
        </div>
    </div>
</div>

<div id="confirmDeleteModal" class="modal">
    <div class="modal-content" style="border-radius: 20px; padding: 24px;">
        <div class="modal-header">
            <h2>Konfirmasi Hapus</h2>
            <button class="close-button" onclick="closeConfirmDeleteModal()">×</button>
        </div>
        <div class="modal-body">
            <p id="deleteMessage" style="margin-bottom: 20px; font-size: 14px; color: #4b5563;"></p>
            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeConfirmDeleteModal()" class="btn-action-secondary">Batal</button>
                <form id="finalDeleteForm" method="POST" action="">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn-action-primary" style="background: #ef4444;">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush("scripts"); ?>
<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert('Tautan berhasil disalin ke clipboard!');
        }).catch(err => {
            console.error('Gagal menyalin teks: ', err);
        });
    }

    function openPreviewModal(url) {
        const modal = document.getElementById('previewModalOverlay');
        const iframe = document.getElementById('phonePreviewIframe');
        iframe.src = url;
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closePreviewModal() {
        const modal = document.getElementById('previewModalOverlay');
        const iframe = document.getElementById('phonePreviewIframe');
        modal.classList.remove('active');
        iframe.src = '';
        document.body.style.overflow = 'auto';
    }

    function showAddBlockModal() {
        document.getElementById('addBlockModal').style.display = 'block';
        document.body.classList.add('modal-open');
    }

    function closeModal() {
        document.getElementById('addBlockModal').style.display = 'none';
        document.body.classList.remove('modal-open');
    }

    function selectBlockType(type) {
        if(type === 'digital') {
            window.location.href = "<?php echo e(route('admin.digital-products.create')); ?>";
        } else if(type === 'shortlink') {
            window.location.href = "<?php echo e(route('admin.shortlinks.index')); ?>";
        }
    }

    function showActionModal(productId, productTitle) {
        window.currentDeleteId = productId;
        window.currentDeleteTitle = productTitle;
        document.getElementById('deleteForm').action = `/admin/digital-products/${productId}`;
        document.getElementById('editButton').href = `/admin/digital-products/${productId}/edit`;
        document.getElementById('actionModal').style.display = 'block';
        document.body.classList.add('modal-open');
    }

    function closeActionModal() {
        document.getElementById('actionModal').style.display = 'none';
        document.body.classList.remove('modal-open');
    }

    function confirmDelete() {
        const title = window.currentDeleteTitle;
        const productId = window.currentDeleteId;
        document.getElementById('deleteMessage').innerText = `Apakah Anda yakin ingin menghapus produk "${title}"?`;
        document.getElementById('finalDeleteForm').action = `/admin/digital-products/${productId}`;
        closeActionModal();
        document.getElementById('confirmDeleteModal').style.display = 'block';
        document.body.classList.add('modal-open');
    }

    function closeConfirmDeleteModal() {
        document.getElementById('confirmDeleteModal').style.display = 'none';
        document.body.classList.remove('modal-open');
    }

    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            closeModal();
            closeActionModal();
            closeConfirmDeleteModal();
        }
        if (event.target.id === 'previewModalOverlay') {
            closePreviewModal();
        }
    }
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make("layouts.admin", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/rakanyuka/Documents/PKL/Linkan/resources/views/homeadminS/mylinkan.blade.php ENDPATH**/ ?>