<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - Frequently Asked Questions</title>
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
            background-color: #CC0010; /* Solid Red */
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            padding: 24px 20px;
            overflow-x: hidden;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* NAVBAR */
        .navbar-wrapper {
            width: 100%;
            display: flex;
            justify-content: center;
            margin-bottom: 40px;
        }

        .navbar-pill {
            background: #FFFFFF;
            border-radius: 50px;
            padding: 8px 12px 8px 24px;
            width: 100%;
            max-width: 1000px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.06);
        }

        .nav-logo img {
            height: 45px;
            width: auto;
            display: block;
        }

        .nav-links {
            display: flex;
            gap: 24px;
            align-items: center;
        }

        .nav-link {
            font-size: 14px;
            font-weight: 600;
            color: #333333;
            transition: color 0.2s;
        }

        .nav-link:hover {
            color: #EE8025;
        }

        .nav-link.active {
            color: #EE8025;
        }

        .btn-signup {
            background: #000000; /* Solid Black */
            color: #FFFFFF;
            padding: 10px 22px;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 700;
            transition: transform 0.2s, background 0.2s;
        }

        .btn-signup:hover {
            background: #222222;
            transform: scale(1.05);
        }

        /* KNOWLEDGE BASE CARD */
        .kb-card {
            background: #FFFFFF;
            border-radius: 40px;
            width: 100%;
            max-width: 800px;
            padding: 40px 32px;
            text-align: center;
            box-shadow: 0 20px 50px rgba(0,0,0,0.1);
            margin-bottom: 40px;
        }

        .kb-title {
            font-size: 28px;
            font-weight: 800;
            color: #0062E6; /* Blue text */
            margin-bottom: 24px;
            letter-spacing: -0.5px;
        }

        .kb-search-container {
            display: flex;
            justify-content: center;
            width: 100%;
        }

        .kb-search-bar {
            background: #0062E6;
            color: #FFFFFF;
            border: none;
            border-radius: 50px;
            padding: 14px 28px;
            width: 100%;
            max-width: 400px;
            font-size: 15px;
            font-weight: 600;
            outline: none;
            box-shadow: 0 4px 15px rgba(0, 98, 230, 0.15);
            transition: box-shadow 0.2s;
            text-align: left;
        }

        .kb-search-bar::placeholder {
            color: rgba(255, 255, 255, 0.85);
            font-weight: 500;
        }

        /* SECTION TITLE */
        .section-title {
            font-size: 28px;
            font-weight: 800;
            color: #FFFFFF;
            text-align: center;
            margin-bottom: 40px;
            letter-spacing: -0.5px;
        }

        /* FAQ GRID */
        .faq-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            width: 100%;
            max-width: 1000px;
            margin-bottom: 60px;
        }

        .faq-item {
            background: #FFFFFF;
            border-radius: 50px; /* Fully rounded capsule style */
            box-shadow: 0 10px 25px rgba(0,0,0,0.06);
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            height: fit-content;
        }

        .faq-question {
            padding: 18px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            font-weight: 800;
            font-size: 16px;
            color: #0062E6; /* Blue text */
            user-select: none;
        }

        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease, padding 0.3s ease;
            padding: 0 32px;
            background: #FFFFFF;
        }

        .faq-item.open {
            border-radius: 24px; /* Soften rounded corners when expanded */
        }

        .faq-item.open .faq-answer {
            max-height: 300px;
            padding: 0 32px 24px 32px;
        }

        .faq-answer p {
            font-size: 14px;
            color: #344054;
            line-height: 1.6;
            font-weight: 500;
        }

        /* FOOTER */
        .footer-wrapper {
            width: 100%;
            display: flex;
            justify-content: center;
            margin-top: auto;
        }

        .footer-pill {
            background: #FFFFFF;
            border-radius: 50px;
            padding: 12px 32px;
            width: 100%;
            max-width: 1000px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.06);
        }

        .footer-logo img {
            height: 45px;
            width: auto;
            display: block;
        }

        .footer-links {
            display: flex;
            gap: 32px;
        }

        .footer-link {
            font-size: 15px;
            font-weight: 700;
            color: #121212;
            transition: color 0.2s;
        }

        .footer-link:hover {
            color: #EE8025;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .navbar-pill {
                padding: 8px 16px;
            }
            .nav-links {
                gap: 16px;
            }
            .btn-signup {
                padding: 8px 16px;
                font-size: 13px;
            }
            .faq-grid {
                grid-template-columns: 1fr;
            }
            .kb-card {
                padding: 30px 20px;
            }
            .kb-title {
                font-size: 24px;
            }
            .faq-item {
                border-radius: 40px;
            }
            .faq-question {
                padding: 16px 24px;
                font-size: 14px;
            }
            .faq-item.open .faq-answer {
                padding: 0 24px 20px 24px;
            }
            .footer-pill {
                padding: 10px 24px;
            }
            .footer-links {
                gap: 20px;
            }
        }

        @media (max-width: 480px) {
            .nav-links {
                display: none; /* Hide links on navbar on very small screens */
            }
            .navbar-pill {
                justify-content: space-between;
            }
        }
    </style>
</head>
<body>
    <!-- NAVBAR -->
    <div class="navbar-wrapper">
        <div class="navbar-pill">
            <a href="<?php echo e(url('/')); ?>" class="nav-logo">
                <img src="<?php echo e(asset('images/Logo.png')); ?>" alt="Linkan Logo">
            </a>
            <div class="nav-links">
                <a href="<?php echo e(route('pricing')); ?>" class="nav-link">Pricing</a>
                <a href="<?php echo e(route('service')); ?>" class="nav-link">Service</a>
                <a href="<?php echo e(route('FAQ')); ?>" class="nav-link active">FAQ</a>
                <a href="<?php echo e(route('login')); ?>" class="nav-link">Log In</a>
                <a href="<?php echo e(route('register')); ?>" class="btn-signup">Sign Up</a>
            </div>
        </div>
    </div>

    <!-- KNOWLEDGE BASE CARD -->
    <div class="kb-card">
        <h2 class="kb-title">Linkan.id Knowledge Base</h2>
        <div class="kb-search-container">
            <input type="text" class="kb-search-bar" placeholder="e.g. costum domain">
        </div>
    </div>

    <!-- SECTION TITLE -->
    <h2 class="section-title">Frequently Asked Questions</h2>

    <!-- FAQ GRID -->
    <div class="faq-grid">
        <!-- Item 1 -->
        <div class="faq-item">
            <div class="faq-question">
                <span>Apa itu linkan.id ?</span>
            </div>
            <div class="faq-answer">
                <p>Linkan.id adalah platform link-in-bio yang membantu kreator, pebisnis, dan influencer mengelola kehadiran digital mereka dengan lebih praktis dan profesional. Platform ini memungkinkan pengguna membuat halaman bio yang berisi tautan ke media sosial, konten digital, serta layanan donasi dalam satu tempat.</p>
            </div>
        </div>

        <!-- Item 2 -->
        <div class="faq-item">
            <div class="faq-question">
                <span>Apa Saja Fitur Linkan.id?</span>
            </div>
            <div class="faq-answer">
                <p>Linkan.id menyediakan berbagai fitur unggulan, di antaranya: Halaman Link-in-Bio, Penjualan Produk Digital, Layanan Donasi Online, Sistem Pembayaran Aman, Analisis Performa Penjualan, dan Kustomisasi Template.</p>
            </div>
        </div>

        <!-- Item 3 -->
        <div class="faq-item">
            <div class="faq-question">
                <span>Bagaimana sistem pembayaran pekerja?</span>
            </div>
            <div class="faq-answer">
                <p>Linkan.id menggunakan payment gateway real-time, sehingga transaksi pembelian produk digital dan donasi dilakukan secara otomatis tanpa perlu konfirmasi manual.</p>
            </div>
        </div>

        <!-- Item 4 -->
        <div class="faq-item">
            <div class="faq-question">
                <span>Bisa Menjual Produk Digital?</span>
            </div>
            <div class="faq-answer">
                <p>Ya. Linkan.id memungkinkan pengguna menjual berbagai produk digital seperti e-book, e-course, template, dan konten digital lainnya secara langsung.</p>
            </div>
        </div>

        <!-- Item 5 -->
        <div class="faq-item">
            <div class="faq-question">
                <span>Bagaimana Sistem Pembagian Komisi ?</span>
            </div>
            <div class="faq-answer">
                <p>Admin Platform menerima komisi sebesar 5% dari pendapatan Admin Seller, yang secara otomatis dipotong saat penarikan pendapatan dilakukan.</p>
            </div>
        </div>

        <!-- Item 6 -->
        <div class="faq-item">
            <div class="faq-question">
                <span>Apakah Gratis?</span>
            </div>
            <div class="faq-answer">
                <p>Linkan.id menyediakan akses gratis untuk fitur dasar link-in-bio. Namun untuk akses penuh terhadap fitur premium seperti penjualan digital dan analitik mendalam, terdapat sistem pembagian komisi atau langganan.</p>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <div class="footer-wrapper">
        <div class="footer-pill">
            <a href="<?php echo e(url('/')); ?>" class="footer-logo">
                <img src="<?php echo e(asset('images/Logo.png')); ?>" alt="Linkan Logo">
            </a>
            <div class="footer-links">
                <a href="<?php echo e(route('about')); ?>" class="footer-link">About Us</a>
                <a href="<?php echo e(route('contact.form')); ?>" class="footer-link">Contact Us</a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const faqItems = document.querySelectorAll('.faq-item');

            faqItems.forEach(item => {
                const question = item.querySelector('.faq-question');
                
                question.addEventListener('click', () => {
                    const isOpen = item.classList.contains('open');

                    // Tutup semua yang terbuka
                    faqItems.forEach(faq => faq.classList.remove('open'));

                    // Buka yang di-klik jika sebelumnya tidak terbuka
                    if (!isOpen) {
                        item.classList.add('open');
                    }
                });
            });
        });
    </script>
</body>
</html><?php /**PATH /home/rakanyuka/Documents/PKL/Linkan/resources/views/faq.blade.php ENDPATH**/ ?>