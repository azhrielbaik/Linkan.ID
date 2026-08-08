<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Linkan</title>
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

        .title {
            font-size: 28px;
            font-weight: 800;
            color: #121212;
            text-align: center;
            margin-bottom: 32px;
            letter-spacing: -0.5px;
        }

        .login-form {
            width: 100%;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #121212;
            font-size: 15px;
            font-weight: 600;
        }

        .form-group input {
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

        .form-group input:focus {
            border-color: #0A60D4;
            box-shadow: 0 0 0 4px rgba(10, 96, 212, 0.1);
        }

        .form-group input::placeholder {
            color: #98A2B3;
            font-weight: 400;
        }

        .error-message {
            color: #d93025;
            font-size: 14px;
            margin-top: 6px;
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

        .btn-register {
            width: 100%;
            padding: 14px;
            background: #000000;
            color: #ffffff;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 24px;
            margin-bottom: 20px;
        }

        .btn-register:hover {
            background: #222222;
            transform: translateY(-1px);
        }

        .login-link {
            text-align: center;
            font-size: 14px;
            color: #667085;
            margin-top: 10px;
        }

        .login-link a {
            color: #0A60D4;
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        /* Right Side: Welcome Banner Container */
        .info-side {
            flex: 1;
            background: #0A60D4;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px 40px;
            color: #ffffff;
            text-align: center;
            position: relative;
        }

        .welcome-text {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 40px;
            letter-spacing: -0.5px;
        }

        .logo-icon {
            width: 140px;
            height: 140px;
            object-fit: contain;
            border-radius: 28px;
            margin-bottom: 40px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
            animation: floating 4s ease-in-out infinite;
        }

        @keyframes floating {
            0% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-10px);
            }
            100% {
                transform: translateY(0px);
            }
        }

        .brand-name {
            font-size: 24px;
            font-weight: 800;
            margin-bottom: 24px;
        }

        .brand-desc {
            font-size: 16px;
            font-weight: 500;
            line-height: 1.6;
            max-width: 420px;
            color: rgba(255, 255, 255, 0.95);
        }

        /* Responsive Layout styling */
        @media (max-width: 900px) {
            .form-side {
                padding: 30px;
            }
            .info-side {
                padding: 30px;
            }
        }

        @media (max-width: 768px) {
            .info-side {
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
                <h2 class="title">Buat Akun Anda</h2>

                <?php if(session('error')): ?>
                    <div class="error-message" style="margin-bottom: 20px;">
                        <?php echo e(session('error')); ?>

                    </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo e(route('register.submit')); ?>" class="login-form">
                    <?php echo csrf_field(); ?>
                    <?php if(isset($googleData)): ?>
                        <input type="hidden" name="google_id" value="<?php echo e($googleData['google_id']); ?>">
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" placeholder="Enter your full name" value="<?php echo e($googleData['name'] ?? old('name')); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="username">User Name</label>
                        <input type="text" id="username" name="username" placeholder="Choose your username" value="<?php echo e(old('username')); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Example@gmail.com" value="<?php echo e($googleData['email'] ?? old('email')); ?>" required autocomplete="email">
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="••••••••" required>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required>
                    </div>

                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="error-message"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="error-message"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="error-message"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    <button type="submit" class="btn-register">Create Account</button>
                </form>

                <div class="login-link">
                    Already have an account? <a href="<?php echo e(route('login')); ?>">Sign in</a>
                </div>
            </div>
        </div>

        <!-- Right side: Welcome Info -->
        <div class="info-side">
            <h2 class="welcome-text">Selamat datang di</h2>
            <img src="<?php echo e(asset('images/favicon.png')); ?>" alt="Linkan Icon" class="logo-icon">
            <h3 class="brand-name">Linkan</h3>
            <p class="brand-desc">Join the vibrant creators economy. set up your fluid microsited, sell digital products dan receive donatios instantly</p>
        </div>
    </div>
</body>
</html><?php /**PATH C:\Users\user\Documents\TUGAS PKL\linkan.id\Linkan.ID\resources\views/register.blade.php ENDPATH**/ ?>