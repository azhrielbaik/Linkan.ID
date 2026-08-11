<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Linkan</title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/favicon.png')); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: #ffffff;
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
        }

        .split-container {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        /* Left Side: Form Container */
        .form-side {
            flex: 1;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .form-wrapper {
            width: 100%;
            max-width: 420px;
        }

        .logo-container {
            display: flex;
            justify-content: center;
            margin-bottom: 32px;
        }

        .logo-container img {
            height: 90px;
            width: auto;
            transition: transform 0.3s ease;
        }

        .logo-container img:hover {
            transform: scale(1.05);
        }

        .title {
            font-size: 28px;
            font-weight: 800;
            color: #121212;
            text-align: center;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .subtitle {
            font-size: 15px;
            font-weight: 500;
            color: #667085;
            text-align: center;
            margin-bottom: 40px;
        }

        .login-form {
            width: 100%;
        }

        .form-input-group {
            margin-bottom: 20px;
        }

        .form-input-group input {
            width: 100%;
            padding: 14px 24px;
            border: 1px solid #D0D5DD;
            border-radius: 50px;
            font-size: 15px;
            font-weight: 500;
            background-color: #ffffff;
            color: #121212;
            outline: none;
            transition: all 0.3s ease;
        }

        .form-input-group input:focus {
            border-color: #EE8025;
            box-shadow: 0 0 0 4px rgba(238, 128, 37, 0.1);
        }

        .form-input-group input::placeholder {
            color: #98A2B3;
            font-weight: 400;
        }

        .error-message {
            color: #d93025;
            font-size: 14px;
            margin-top: -10px;
            margin-bottom: 20px;
            padding-left: 12px;
        }

        .success-message {
            background: #e6f4ea;
            color: #137333;
            padding: 12px 20px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 24px;
            text-align: center;
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: #ffffff;
            color: #344054;
            border: 1px solid #D0D5DD;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn-submit:hover {
            background: #f9fafb;
            border-color: #98A2B3;
            transform: translateY(-1px);
        }

        .auth-divider {
            text-align: center;
            margin: 28px 0;
            color: #98A2B3;
            font-size: 14px;
            font-weight: 600;
            position: relative;
            letter-spacing: 0.5px;
        }

        .btn-google {
            width: 100%;
            padding: 14px;
            background: #ffffff;
            border: 1px solid #D0D5DD;
            border-radius: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 600;
            color: #344054;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-google:hover {
            background: #f9fafb;
            border-color: #98A2B3;
            transform: translateY(-1px);
        }

        .btn-google img {
            width: 20px;
            height: 20px;
        }

        .links-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
            margin-top: 28px;
        }

        .link-item {
            color: #667085;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .link-item:hover {
            color: #EE8025;
            text-decoration: underline;
        }

        /* Right Side: Mockup Showcase Container */
        .mockup-side {
            flex: 1;
            background: #EE8025;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            overflow: hidden;
            position: relative;
        }

        .mockup-wrapper {
            width: 100%;
            max-width: 600px;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .mockup-image {
            max-width: 100%;
            height: auto;
            max-height: 80vh;
            object-fit: contain;
            filter: drop-shadow(0 20px 40px rgba(0, 0, 0, 0.15));
        }

        .floating-animation {
            animation: floating 4s ease-in-out infinite;
            transform-origin: center center;
        }

        @keyframes floating {
            0% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-15px);
            }
            100% {
                transform: translateY(0px);
            }
        }

        /* Responsive Layout styling */
        @media (max-width: 900px) {
            .form-side {
                padding: 30px;
            }
            .mockup-side {
                padding: 30px;
            }
        }

        @media (max-width: 768px) {
            .mockup-side {
                display: none;
            }
            .form-side {
                flex: 1;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="split-container">
        <!-- Left side: Form -->
        <div class="form-side">
            <div class="form-wrapper">
                <div class="logo-container">
                    <a href="<?php echo e(url('/')); ?>">
                        <img src="<?php echo e(asset('images/Logo.png')); ?>" alt="Linkan Logo">
                    </a>
                </div>

                <h2 class="title">Selamat Datang Kembali</h2>
                <p class="subtitle">Masuk ke Linkan anda</p>

                <?php if(session('success')): ?>
                    <div class="success-message">
                        <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo e(route('login.submit')); ?>" class="login-form">
                    <?php echo csrf_field(); ?>
                    <div class="form-input-group">
                        <input type="email" id="email" name="email" placeholder="Email atau Username" value="<?php echo e(old('email')); ?>" required autocomplete="email">
                    </div>

                    <div class="form-input-group">
                        <input type="password" id="password" name="password" placeholder="Password" required autocomplete="current-password">
                    </div>

                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="error-message"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    <button type="submit" class="btn-submit">Masuk</button>
                </form>

                <div class="auth-divider">ATAU</div>

                <a href="<?php echo e(url('/login/google')); ?>" class="btn-google">
                    <img src="<?php echo e(asset('images/google.png')); ?>" alt="Google Logo">
                    Login dengan google
                </a>

                <div class="links-container">
                    <a href="<?php echo e(route('password.request')); ?>" class="link-item">Lupa kata sandi?</a>
                    <a href="<?php echo e(route('register')); ?>" class="link-item">belum memiliki akun? daftar</a>
                </div>
            </div>
        </div>

        <!-- Right side: Image Showcase -->
        <div class="mockup-side">
            <div class="mockup-wrapper">
                <img src="<?php echo e(asset('images/login/Group 18.png')); ?>" alt="Login Showcase" class="mockup-image floating-animation">
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const floatingImage = document.querySelector('.floating-animation');
            if (floatingImage) {
                document.addEventListener('mousemove', function(e) {
                    const moveX = (e.clientX - window.innerWidth/2) * 0.004;
                    const moveY = (e.clientY - window.innerHeight/2) * 0.004;
                    
                    // Combine mouse move effect with CSS keyframe animation offset
                    floatingImage.style.transform = `translate(${moveX}px, ${moveY}px)`;
                });
            }
        });
    </script>
</body>
</html>
<?php /**PATH C:\Users\user\Documents\TUGAS PKL\Linkan.ID\resources\views/login.blade.php ENDPATH**/ ?>