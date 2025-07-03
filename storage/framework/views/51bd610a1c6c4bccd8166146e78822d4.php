<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appearance - Linkan</title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/favicon.png')); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
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
    overflow: hidden;
}

        .main-content {
            flex: 1;
            padding: 20px;
            margin-left: 250px;
            margin-right: 440px; /* Lebar preview + jarak */
        }

        .url-section {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
            padding: 24px 20px;
            margin-bottom: 24px;
            border: none;
        }

        .url-input-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .url-input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: #f8f9fa;
            color: #666;
        }

        .share-button {
            background: none;
            border: none;
            color: #FF9040;
            cursor: pointer;
            padding: 8px;
            font-size: 16px;
        }

        .content-section {
    display: flex;
    gap: 32px;
    min-height: 100vh;
}

.left-panel {
    flex: 2;
    /* max-height: 100vh; */
    overflow-y: auto;
    padding-right: 10px;
}

.left-panel form {
    display: flex;
    flex-direction: column;
    min-height: 100%;
    gap: 20px;
    padding-bottom: 100px; /* Tambahkan ini agar tombol tidak mentok */
}


.save-button {
    align-self: center; /* Tombol di tengah */
    background: #FF9040; /* Warna fallback */
    color: white;
    border: none;
    padding: 12px 30px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    margin-top: 20px;
    transition: background-color 0.3s ease;
}


        .right-panel {
            flex: 1;
            min-width: 300px;
        }

        .card, .preview-section, .preview-phone {
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
            border-radius: 16px;
        }
        .card {
            background: #fff;
            padding: 24px 20px;
            margin-bottom: 24px;
            border: none;
        }
        .preview-section {
            background: #f7f8fa;
            padding: 24px 20px;
            border-radius: 16px;
            width: 400px;
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 100;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .preview-phone {
            width: 100%;
            max-width: 375px;
            height: 700px;
            border-radius: 32px;
            background: #fff;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
            border: 1.5px solid #e5e7eb;
        }
        .preview-screen {
            width: 100%;
            height: 100%;
            background: #f8f9fa;
            border-radius: 24px;
            padding: 24px 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            overflow-y: auto;
        }
        .preview-banner {
            width: 100%;
            height: 120px;
            background: #e5e7eb;
            border-radius: 12px;
            margin-bottom: 20px;
            overflow: hidden;
        }
        .preview-profile {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #e5e7eb;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
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
            margin-bottom: 18px;
            padding: 0 16px;
            line-height: 1.5;
        }
        .preview-social-links {
            display: flex;
            gap: 16px;
            margin-bottom: 20px;
        }
        .preview-products {
            width: 100%;
            padding: 10px 0;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .preview-product-item {
            background: #fff;
            border-radius: 10px;
            padding: 12px 14px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            border: 1px solid #ececec;
            transition: transform 0.2s;
        }
        .preview-product-item:hover {
            transform: translateY(-2px) scale(1.01);
        }
        .preview-product-image {
            width: 40px;
            height: 40px;
            background: #FFE5D3;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
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
            padding: 6px 16px;
            border-radius: 6px;
            font-size: 13px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
            flex-shrink: 0;
            min-width: 90px;
            text-align: center;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }
        .preview-product-button:hover {
            opacity: 0.92;
        }
        .card-title {
            font-size: 18px;
            color: #333;
            margin-bottom: 18px;
        }
        .card-priview {
            font-size: 16px;
            color: #333;
            margin-bottom: 15px;
            text-align: center;
        }

        .banner-section {
            border: 2px dashed #ddd;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            align-items: center; /* posisi horizontal tengah */
            justify-content: center; /* opsional: posisi vertikal tengah */
        }

        .banner-section i {
            font-size: 40px;
            color: #ddd;
            margin-bottom: 10px;
        }

        .banner-text {
            color: #666;
            margin-bottom: 15px;
        }

        .upload-button {
            background: #FF9040;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .profile-section {
            text-align: center;
            padding: 20px 0;
        }

        .profile-image {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: #f8f9fa;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            overflow: hidden;
        }

        .profile-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-image i {
            font-size: 40px;
            color: #ddd;
        }

        .profile-name {
            width: 80%;
            margin: 0 auto 10px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .bio-section textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            resize: vertical;
            margin-top: 10px;
        }

        .preview-name,
        .preview-bio,
        .preview-screen button {
            transition: all 0.3s ease;
        }

        .popup-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    z-index: 1000;
    display: flex;
    justify-content: center;
    align-items: center;
}

.popup-content {
    background-color: white;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    position: relative;
    min-width: 300px;
}

.close-btn {
    position: absolute;
    top: 8px;
    right: 12px;
    font-size: 24px;
    cursor: pointer;
}

.social-btn {
    margin: 5px;
    padding: 8px 14px;
    background-color: #f0f0f0;
    border: 1px solid #ccc;
    border-radius: 20px;
    cursor: pointer;
    font-size: 14px;
}
.social-btn i {
    margin-right: 6px;
}
.social-input {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
}
.social-input i {
    font-size: 18px;
}
.social-input input {
    flex: 1;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 6px;
}

.remove-social {
    background: none;
    border: none;
    color: #888;
    font-size: 27px;
    cursor: pointer;
    margin-left: 8px;
    line-height: 1;
    padding: 4px;
}
.remove-social:hover {
    color: red;
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
            <form method="POST" action="<?php echo e(route('appearance.update')); ?>" enctype="multipart/form-data" id="appearanceForm">
            <?php echo csrf_field(); ?>
            <div class="content-section">
                <div class="left-panel">
                    <div class="url-section card">
                        <div class="url-input-group">
                            <input type="text" class="url-input" value="My Linkan: <?php echo e(url('linkan.id/' . Auth::user()->username)); ?>" readonly>
                            <button class="share-button" onclick="copyToClipboard('http://localhost:8000/linkan.id/<?php echo e(Auth::user()->username); ?>')">
                                <i class="fas fa-share-alt"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Banner -->
                    <div class="card">
                        <h2 class="card-title">Banner</h2>
                        <div class="banner-section">
<?php if($appearance && $appearance->banner): ?>
    <img src="<?php echo e(asset('storage/' . $appearance->banner)); ?>" alt="Banner"
         style="width: 589px; height: 233px; object-fit: cover; margin-bottom: 15px;" id="previewBanner">
    <input type="hidden" name="delete_banner" id="deleteBanner" value="0">
    <button type="button" onclick="confirmDeleteBanner()" class="upload-button" style="background-color: red; color: white;">
        Hapus Banner
    </button>
<?php else: ?>
    <i class="fas fa-image"></i>
    <p class="banner-text">Optimize banner size 1056 x 638 px</p>
<?php endif; ?>
<input type="file" name="banner" id="bannerInput" style="display: none;" accept="image/*">
<button type="button" class="upload-button" onclick="document.getElementById('bannerInput').click()">Upload Image</button>
                        </div>
                    </div>

                    <!-- Profile -->
                    <div class="card">
                        <h2 class="card-title">Profile</h2>
                        <div class="profile-section">
                           <div class="profile-image" onclick="openProfilePopup()">
                                <?php if($appearance && $appearance->profile_image): ?>
                                    <img src="<?php echo e(asset('storage/' . $appearance->profile_image)); ?>" alt="Profile" id="previewProfileImage">
                                <?php else: ?>
                                    <i class="fas fa-user" id="defaultProfileIcon"></i>
                                <?php endif; ?>
                            </div>
                            <input type="file" name="profile_image" id="profileImageInput" style="display: none;" accept="image/*">
                            <input type="text" name="name" class="profile-name" placeholder="Your Name" value="<?php echo e($appearance ? $appearance->name : Auth::user()->name); ?>" id="inputName">
                            <div class="bio-section">
                                <div id="editor" style="height: 150px; margin-bottom: 10px;"><?php echo $appearance ? $appearance->bio : ''; ?></div>
                                <input type="hidden" name="bio" id="bioInput" value="<?php echo e($appearance ? $appearance->bio : ''); ?>">
                            </div>
                            <!-- 🎨 Color Picker -->
<div style="display: flex; align-items: center; gap: 10px; margin-top: 10px;">
    <label for="colorPicker">Customize Color:</label>
    <input type="color" id="colorPicker" name="themeColor" value="<?php echo e($appearance ? $appearance->theme_color : '#FF9040'); ?>">

   <input type="hidden" name="theme_color" id="themeColor" value="<?php echo e($appearance ? $appearance->theme_color : '#FF9040'); ?>">
</div>

                        </div>
                    </div>
                 <!-- Social Media Links -->
<div class="card">
    <h2 class="card-title">Social Links</h2>

    <!-- Tombol Pilih Platform -->
    <div id="social-buttons" style="margin-bottom: 10px;">
        <?php $__currentLoopData = ['instagram','tiktok','whatsapp','linkedin','facebook','website','twitter','youtube','telegram','email','discord']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $platform): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <button type="button" class="social-btn" data-platform="<?php echo e($platform); ?>">
                <i class="<?php echo e([
                        'instagram'=>'fab fa-instagram',
                        'tiktok'=>'fab fa-tiktok',
                        'whatsapp'=>'fab fa-whatsapp',
                        'linkedin'=>'fab fa-linkedin',
                        'facebook'=>'fab fa-facebook',
                        'website'=>'fas fa-globe',
                        'twitter'=>'fab fa-twitter',
                        'youtube'=>'fab fa-youtube',
                        'telegram'=>'fab fa-telegram',
                        'email'=>'fas fa-envelope',
                        'discord'=>'fab fa-discord'
                    ][$platform]); ?>"></i>
                <?php echo e(ucfirst($platform)); ?>

            </button>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <!-- Input yang akan muncul -->
    <div id="social-link-inputs">
        <?php $__currentLoopData = ['instagram','tiktok','whatsapp','linkedin','facebook','website','twitter','youtube','telegram','email','discord']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $platform): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="social-input" data-platform="<?php echo e($platform); ?>"
                 style="<?php echo e(($appearance && $appearance->$platform) ? '' : 'display:none;'); ?>">
                <i class="<?php echo e([
                        'instagram'=>'fab fa-instagram',
                        'tiktok'=>'fab fa-tiktok',
                        'whatsapp'=>'fab fa-whatsapp',
                        'linkedin'=>'fab fa-linkedin',
                        'facebook'=>'fab fa-facebook',
                        'website'=>'fas fa-globe',
                        'twitter'=>'fab fa-twitter',
                        'youtube'=>'fab fa-youtube',
                        'telegram'=>'fab fa-telegram',
                        'email'=>'fas fa-envelope',
                        'discord'=>'fab fa-discord'
                    ][$platform]); ?>"></i>
                <input
                    type="<?php echo e($platform=='email' ? 'email' : 'url'); ?>"
                    id="input<?php echo e(ucfirst($platform)); ?>"
                    name="<?php echo e($platform); ?>"
                    placeholder="<?php echo e(ucfirst($platform)); ?> <?php echo e($platform=='email' ? 'Address' : 'URL'); ?>"
                    value="<?php echo e($appearance->$platform ?? ''); ?>"
                >
                <button type="button" class="remove-social" title="Hapus">&times;</button>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>


    <!-- Theme -->
   <div class="card">
    <h2 class="card-title">Theme</h2>
    <div class="theme-options" id="themeOptions"
         style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 16px;">

        <?php
            $themes = ['blue ocean.png', 'city light.png', 'clasic.png', 'desert.png', 'green flower.png', 'pink candy.png', 'playstation abstract.png','sunset.png', 'mountain.png','library.png','news paper.png'];
        ?>

        <?php $__currentLoopData = $themes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $theme): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div style="text-align: center;">
                <img src="<?php echo e(asset('images/previewt/' . $theme)); ?>"
                     data-bg="<?php echo e(asset('images/background/' . $theme)); ?>"
                     data-name="<?php echo e($theme); ?>"
                     class="theme-preview"
                     style="width: 100px; height: 70px; object-fit: cover; cursor: pointer; border: 2px solid transparent; border-radius: 8px; transition: transform 0.2s;">
                <div style="font-size: 13px; margin-top: 6px; color: #333;">
                    <?php echo e(ucwords(str_replace(['-', '_'], ' ', pathinfo($theme, PATHINFO_FILENAME)))); ?>

                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    </div>
    <input type="hidden" name="background_color" id="backgroundColor" value="<?php echo e($appearance ? $appearance->background_color : ''); ?>">
</div>


<div style="display: flex; justify-content: center; margin-top: 20px;">
    <button type="submit" class="save-button"
        style="background-color: #FF9040">
        Save Changes
    </button>
</div>
  </form>
  <!-- Modal Profile Popup -->
<div id="profilePopup" class="popup-modal" style="display: none;">
    <div class="popup-content">
        <span class="close-btn" onclick="closeProfilePopup()">&times;</span>
        
        <?php if($appearance && $appearance->profile_image): ?>
            <button type="button" class="upload-button" onclick="document.getElementById('profileImageInput').click()">Upload Image</button>
           <input type="hidden" name="delete_profile_image" id="deleteProfileImage" value="0">
    <button type="button" onclick="confirmDeleteProfileImage()" class="upload-button" style="background-color: red; color: white; margin-top: 10px;">
        Hapus Foto Profil
    </button>
        <?php else: ?>
            <button type="button" class="upload-button" onclick="document.getElementById('profileImageInput').click()">Upload Image</button>
        <?php endif; ?>
    </div>
</div>

                </div>

                <!-- Preview -->

              
                    <div class="preview-section">
                          <div class="right-panel">
                     <div class="preview-header">
                       <h2 class="card-priview">Preview</h2>
                      </div>
                          <div class="preview-phone">
                              <div class="preview-screen" id="previewScreen" style="width: 100%; height: 100%; background: #f8f9fa; border-radius: 30px; padding: 20px; display: flex; flex-direction: column; align-items: center; overflow-y: auto; background-image: url('<?php echo e($appearance && $appearance->background_color ? asset('images/background/' . $appearance->background_color) : ''); ?>'); background-size: cover; background-position: center;">
                                  <?php if($appearance && $appearance->banner): ?>
                                      <div class="preview-banner" style="width: 100%; height: 120px; background: #ddd; border-radius: 10px; margin-bottom: 20px; overflow: hidden;">
                                          <img src="<?php echo e(asset('storage/' . $appearance->banner)); ?>" alt="Banner" style="width: 100%; height: 100%; object-fit: cover;">
                                      </div>
                                  <?php endif; ?>
                                  <div class="preview-profile" id="previewPhoneProfile" style="width: 80px; height: 80px; border-radius: 50%; background: #ddd; margin-bottom: 15px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                      <?php if($appearance && $appearance->profile_image): ?>
                                          <img src="<?php echo e(asset('storage/' . $appearance->profile_image)); ?>" alt="Profile" style="width: 100%; height: 100%; object-fit: cover;">
                                      <?php else: ?>
                                          <i class="fas fa-user"></i>
                                      <?php endif; ?>
                                  </div>
                                  <div class="preview-name" id="livePreviewName" style="font-size: 18px; font-weight: 600; margin-bottom: 10px; text-align: center; color: <?php echo e($appearance ? $appearance->theme_color : '#FF9040'); ?>"><?php echo e($appearance ? $appearance->name : Auth::user()->name); ?></div>
                                  <div class="preview-bio" id="livePreviewBio" style="font-size: 14px; color: <?php echo e($appearance ? $appearance->theme_color : '#FF9040'); ?>; text-align: center; margin-bottom: 15px; padding: 0 20px; line-height: 1.4;"><?php echo $appearance ? $appearance->bio : ''; ?></div>
                                  <div class="preview-social-links" id="livePreviewSocialLinks" style="display: flex; gap: 15px; margin-bottom: 20px;">
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
                                  <?php if($digitalProducts && $digitalProducts->count() > 0): ?>
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
                                                  <a href="<?php echo e(route('track.click', ['link_id' => Auth::user()->username, 'target' => $product->platform_url ?? '#'])); ?>" class="preview-product-button" style="background-color: <?php echo e($appearance ? $appearance->theme_color : '#FF9040'); ?>; color: white; padding: 4px 12px; border-radius: 4px; font-size: 12px; border: none; cursor: pointer; transition: background-color 0.3s ease; flex-shrink: 0; min-width: 100px; text-align: center; height: 28px; display: flex; align-items: center; justify-content: center; text-decoration: none;" target="_blank"><?php echo e(str_replace('_', ' ', $product->button_text ?? 'Beli')); ?></a>
                                              </div>
                                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                      </div>
                                  <?php endif; ?>
                              </div>
                          </div>

                      </div>
                  </div>
              </div>
        </div>
    </div>
<script>
// Semua script dalam satu blok DOMContentLoaded

document.addEventListener('DOMContentLoaded', function () {
    // --- Quill.js ---
    var quill = new Quill('#editor', {
        theme: 'snow',
        placeholder: 'Tulis bio Anda di sini...',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['link'],
                ['clean']
            ]
        },
        bounds: '#editor'
    });
    quill.on('text-change', function() {
        const content = quill.root.innerHTML;
        const previewBio = document.getElementById('livePreviewBio');
        if (previewBio) previewBio.innerHTML = content;
        const bioInput = document.getElementById('bioInput');
        if (bioInput) bioInput.value = content;
    });

    // --- Color Picker & Theme Color ---
    const colorPicker = document.getElementById('colorPicker');
    const themeColorInput = document.getElementById('themeColor');
    const previewName = document.getElementById('livePreviewName');
    const previewBio = document.getElementById('livePreviewBio');
    const previewButtons = document.querySelectorAll('.preview-product-button');
    const previewSocialLinks = document.getElementById('livePreviewSocialLinks');
    function updatePreviewColor(color) {
        if (previewName) previewName.style.color = color;
        if (previewBio) previewBio.style.color = color;
        previewButtons.forEach(btn => btn.style.backgroundColor = color);
        if (themeColorInput) themeColorInput.value = color;
        if (colorPicker) colorPicker.value = color;
        if (previewSocialLinks) {
            previewSocialLinks.querySelectorAll('a i').forEach(icon => {
                icon.style.color = color;
            });
        }
    }
    if (colorPicker) {
        colorPicker.addEventListener('input', function () {
            updatePreviewColor(this.value);
        });
    }
    if (themeColorInput) updatePreviewColor(themeColorInput.value);

    // --- Live Preview Name ---
    const inputName = document.getElementById('inputName');
    if (inputName && previewName) {
        inputName.addEventListener('input', function() {
            previewName.textContent = this.value;
        });
    }

    // --- Social Links ---
    const placeholderMap = {
        instagram: 'https://instagram.com/',
        tiktok: 'https://tiktok.com/',
        whatsapp: 'https://wa.me/08xxxxxxxxxx',
        linkedin: 'https://linkedin.com/in/username',
        facebook: 'https://facebook.com/username',
        website: 'https://yourwebsite.com',
        twitter: 'https://twitter.com/username',
        youtube: 'https://youtube.com/@channel',
        telegram: 'https://t.me/username',
        email: 'Your email',
        discord: 'https://discord.gg/invitecode'
    };
    // Toggle tampil input saat klik tombol platform
    document.querySelectorAll('.social-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const p = btn.dataset.platform;
            const inputDiv = document.querySelector(`.social-input[data-platform="${p}"]`);
            if (inputDiv) {
                inputDiv.style.display = inputDiv.style.display === 'none' ? 'flex' : 'none';
                const input = inputDiv.querySelector('input');
                if (input && placeholderMap[p]) {
                    input.placeholder = placeholderMap[p];
                }
                updateSocialPreview();
                updatePreviewColor(themeColorInput.value);
            }
        });
    });
    // Hapus satu social-input
    document.querySelectorAll('.remove-social').forEach(btn => {
        btn.addEventListener('click', () => {
            const div = btn.closest('.social-input');
            if (div) {
                const inp = div.querySelector('input');
                if (inp) inp.value = '';
                div.style.display = 'none';
                updateSocialPreview();
            }
        });
    });
    // Live preview social links
    function updateSocialPreview() {
        const platforms = [
            { id: 'inputInstagram', icon: 'fab fa-instagram' },
            { id: 'inputTiktok', icon: 'fab fa-tiktok' },
            { id: 'inputWhatsapp', icon: 'fab fa-whatsapp' },
            { id: 'inputLinkedin', icon: 'fab fa-linkedin' },
            { id: 'inputFacebook', icon: 'fab fa-facebook' },
            { id: 'inputWebsite', icon: 'fas fa-globe' },
            { id: 'inputTwitter', icon: 'fab fa-twitter' },
            { id: 'inputYoutube', icon: 'fab fa-youtube' },
            { id: 'inputTelegram', icon: 'fab fa-telegram' },
            { id: 'inputEmail', icon: 'fas fa-envelope', isEmail: true },
            { id: 'inputDiscord', icon: 'fab fa-discord' },
        ];
        if (!previewSocialLinks) return;
        previewSocialLinks.innerHTML = '';
        platforms.forEach(platform => {
            const input = document.getElementById(platform.id);
            if (input && input.value) {
                const href = platform.isEmail ? `mailto:${input.value}` : input.value;
                previewSocialLinks.innerHTML += `<a href="${href}" target="_blank"><i class="${platform.icon}"></i></a>`;
            }
        });
        // Update warna icon
        updatePreviewColor(themeColorInput.value);
    }
    [
        'inputInstagram', 'inputTiktok', 'inputWhatsapp', 'inputLinkedin',
        'inputFacebook', 'inputWebsite', 'inputTwitter', 'inputYoutube',
        'inputTelegram', 'inputEmail', 'inputDiscord'
    ].forEach(id => {
        const input = document.getElementById(id);
        if (input) {
            input.addEventListener('input', updateSocialPreview);
        }
    });
    updateSocialPreview();

    // --- Banner Preview ---
    const bannerInput = document.getElementById('bannerInput');
    if (bannerInput) {
        bannerInput.addEventListener('change', function(e) {
            const reader = new FileReader();
            reader.onload = function(event) {
                let img = document.getElementById('previewBanner');
                if (img) {
                    img.src = event.target.result;
                } else {
                    const bannerSection = document.querySelector('.banner-section');
                    img = document.createElement('img');
                    img.id = 'previewBanner';
                    img.src = event.target.result;
                    img.alt = 'Banner';
                    img.style = "width: 589px; height: 233px; object-fit: cover; margin-bottom: 15px;";
                    bannerSection.insertBefore(img, bannerSection.querySelector('button.upload-button'));
                }
                let phoneBanner = document.querySelector('.preview-banner img');
                if (phoneBanner) {
                    phoneBanner.src = event.target.result;
                } else {
                    const previewScreen = document.getElementById('previewScreen');
                    let previewBannerDiv = previewScreen.querySelector('.preview-banner');
                    if (!previewBannerDiv) {
                        previewBannerDiv = document.createElement('div');
                        previewBannerDiv.className = 'preview-banner';
                        previewBannerDiv.style = "width: 100%; height: 120px; background: #ddd; border-radius: 10px; margin-bottom: 20px; overflow: hidden;";
                        previewScreen.insertBefore(previewBannerDiv, previewScreen.firstChild);
                    }
                    const newImg = document.createElement('img');
                    newImg.src = event.target.result;
                    newImg.alt = 'Banner';
                    newImg.style = "width: 100%; height: 100%; object-fit: cover;";
                    previewBannerDiv.innerHTML = '';
                    previewBannerDiv.appendChild(newImg);
                }
            };
            reader.readAsDataURL(e.target.files[0]);
        });
    }

    // --- Profile Image Preview ---
    const profileImageInput = document.getElementById('profileImageInput');
    if (profileImageInput) {
        profileImageInput.addEventListener('change', function(e) {
            const reader = new FileReader();
            reader.onload = function(event) {
                const previewProfile = document.getElementById('previewPhoneProfile');
                if (previewProfile) previewProfile.innerHTML = `<img src="${event.target.result}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover;">`;
                const profileImage = document.querySelector('.profile-image');
                if (profileImage) profileImage.innerHTML = `<img src="${event.target.result}" alt="Profile">`;
            };
            reader.readAsDataURL(e.target.files[0]);
        });
    }

    // --- Theme Pilihan (Background Gambar) ---
    const backgroundColorInput = document.getElementById('backgroundColor');
    const previewScreen = document.getElementById('previewScreen');
    // Terapkan background dari database saat halaman dimuat ulang
    const currentBackground = backgroundColorInput ? backgroundColorInput.value : '';
    if (currentBackground) {
        const matchedTheme = document.querySelector(`.theme-preview[data-name="${currentBackground}"]`);
        if (matchedTheme && previewScreen) {
            const bgUrl = matchedTheme.getAttribute('data-bg');
            previewScreen.style.backgroundImage = `url('${bgUrl}')`;
            previewScreen.style.backgroundSize = 'cover';
            previewScreen.style.backgroundPosition = 'center';
            matchedTheme.style.border = "2px solid #FF9040";
        }
    }
    document.querySelectorAll('.theme-preview').forEach(img => {
        img.addEventListener('click', function () {
            const bgUrl = this.getAttribute('data-bg');
            const bgName = this.getAttribute('data-name');
            if (backgroundColorInput) backgroundColorInput.value = bgName;
            if (previewScreen) {
                previewScreen.style.backgroundImage = `url('${bgUrl}')`;
                previewScreen.style.backgroundSize = 'cover';
                previewScreen.style.backgroundPosition = 'center';
            }
            document.querySelectorAll('.theme-preview').forEach(tp => {
                tp.style.border = "2px solid transparent";
            });
            this.style.border = "2px solid #FF9040";
        });
    });

    // --- Copy to Clipboard ---
    window.copyToClipboard = function(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert('Link copied to clipboard!');
        }).catch(err => {
            console.error('Failed to copy text: ', err);
        });
    };

    // --- Popup Profile ---
    window.openProfilePopup = function() {
        const popup = document.getElementById('profilePopup');
        if (popup) popup.style.display = 'flex';
    };
    window.closeProfilePopup = function() {
        const popup = document.getElementById('profilePopup');
        if (popup) popup.style.display = 'none';
    };
    window.confirmDeleteProfileImage = function() {
        if (confirm('Yakin ingin menghapus foto profil?')) {
            const del = document.getElementById('deleteProfileImage');
            if (del) del.value = 1;
            const form = document.getElementById('appearanceForm');
            if (form) form.submit();
        }
    };
    window.confirmDeleteBanner = function() {
        if (confirm('Yakin ingin menghapus banner?')) {
            const form = document.getElementById('appearanceForm');
            const del = document.getElementById('deleteBanner');
            if (del) del.value = 1;
            if (form) form.submit();
        }
    };
});
</script>

</body>
</html>
<?php /**PATH /Users/indobuzz/Documents/dev/LINKAN_ID/resources/views/homeadminS/appearance.blade.php ENDPATH**/ ?>