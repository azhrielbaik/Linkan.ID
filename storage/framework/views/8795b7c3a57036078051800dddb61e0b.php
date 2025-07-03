<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Linkan.id</title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/favicon.png')); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <?php echo $__env->make('layout.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: #e3f3f6;
            overflow-x: hidden;
        }

        .background-gradient {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(240,244,255,0.8) 0%, rgba(255,255,255,0.9) 100%);
            z-index: -1;
        }

        .about-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-top: 100px;
        }

        .about-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .about-header h1 {
            font-size: 2.5em;
            color: #333;
            margin-bottom: 10px;
        }

        .subtitle {
            font-size: 1.2em;
            color: #666;
        }

        .about-section {
            margin-bottom: 50px;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .about-section h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 1.8em;
        }

        .about-section p {
            line-height: 1.6;
            color: #666;
        }

        .about-section ul {
            list-style-type: none;
            padding: 0;
        }

        .about-section ul li {
            margin-bottom: 15px;
            padding-left: 25px;
            position: relative;
            line-height: 1.6;
            color: #666;
        }

        .about-section ul li:before {
            content: "•";
            color: #FF9040;
            font-size: 1.5em;
            position: absolute;
            left: 0;
            top: -2px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-top: 30px;
        }

        .feature-item {
            text-align: center;
            padding: 30px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .feature-item:hover {
            transform: translateY(-5px);
        }

        .feature-item i {
            font-size: 2.5em;
            color: #FF9040;
            margin-bottom: 20px;
        }

        .feature-item h3 {
            color: #333;
            margin-bottom: 15px;
        }

        .feature-item p {
            color: #666;
            line-height: 1.6;
        }

        @media (max-width: 768px) {
            .about-header h1 {
                font-size: 2em;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }
            .about-container {
                margin-top: 80px;
            }
        }

        @media (max-width: 900px) {
            .about-container {
                margin-left: 0;
                padding: 24px 6px;
            }
        }
    </style>
</head>
<body>
    <div class="background-gradient"></div>

    <div class="about-container">
        <div class="about-header">
            <h1>Tentang Linkan.id</h1>
            <p class="subtitle">Platform untuk Creator Economy</p>
        </div>

        <div class="about-content">
            <section class="about-section">
                <h2>Visi Kami</h2>
                <p>Linkan.id hadir untuk memberdayakan para creator dalam mengembangkan bisnis mereka melalui platform yang aman, mudah digunakan, dan terintegrasi.</p>
            </section>

            <section class="about-section">
                <h2>Misi Kami</h2>
                <ul>
                    <li>Menyediakan platform yang memudahkan creator dalam menjual produk digital mereka</li>
                    <li>Membantu creator dalam mengelola dan memonetisasi konten mereka</li>
                    <li>Menyediakan solusi terintegrasi untuk kebutuhan creator</li>
                    <li>Membangun komunitas creator yang kuat dan saling mendukung</li>
                </ul>
            </section>

            <section class="about-section">
                <h2>Fitur Utama</h2>
                <div class="features-grid">
                    <div class="feature-item">
                        <i class="fas fa-box"></i>
                        <h3>Digital Products</h3>
                        <p>Jual produk digital Anda dengan aman dan mudah</p>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-graduation-cap"></i>
                        <h3>Online Course</h3>
                        <p>Bagikan pengetahuan Anda melalui kelas virtual</p>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-hand-holding-heart"></i>
                        <h3>Donations</h3>
                        <p>Terima dukungan dari penggemar Anda</p>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <?php echo $__env->make('layout.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>
</html>
<?php /**PATH C:\LINKAN_ID\resources\views/about.blade.php ENDPATH**/ ?>