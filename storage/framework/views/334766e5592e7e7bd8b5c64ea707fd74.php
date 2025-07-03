
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Linkan - Powering Creators Economy</title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/favicon.png')); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
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



        /* Best Creators Section Styles */
        .best-creators {
            padding: 4rem 7%;
            text-align: center;
            background-color: white;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }

        .link-in-bio {
            padding: 4rem 7%;
            text-align: center;
            background-color: rgb(245, 245, 245);
        }

        .section-title {
            font-size: 36px;
            color: #333;
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .section-subtitle {
            font-size: 16px;
            color: #666;
            margin-bottom: 3rem;
        }

        .creators-showcase {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-bottom: 4rem;
            overflow-x: auto;
            padding: 1rem 0;
        }

        .creator-profile {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
        }

        .creator-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 0.5rem;
        }

        .creator-name {
            font-size: 14px;
            font-weight: 600;
            color: #333;
        }

        .creator-stats {
            display: flex;
            gap: 1rem;
            font-size: 12px;
            color: #666;
        }


        
        @media (max-width: 1024px) {
            .phones-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 768px) {
            .phones-grid {
                grid-template-columns: repeat(2, 1fr);
                padding: 0 1rem;
            }

            .creators-showcase {
                gap: 1rem;
            }
        }

        .creators-showcase {
        overflow-x: auto; /* Mengaktifkan scroll horizontal */
        white-space: nowrap; /* Mencegah gambar turun ke baris baru */
    }

    .scroll-container {
        display: inline-block;
    }

    .scroll-container img {
        height: auto;
        width: 220%; /* Perbesar gambar 1.5x dari ukuran aslinya */
        max-width: none; /* Mencegah batasan lebar */
    }

    .bold {
        font-weight: 500;
        color: black; /* Hitam pekat */
    }

    .light {
        color: rgba(0, 0, 0, 0.5); /* Hitam pudar */
    }

    .category-button {
            display: flex;
            align-items: center;
            background: #c3c3c3; /* Warna abu-abu */
            padding: 12px 20px;
            border-radius: 30px;
            font-size: 11px;
            color: #000; /* Warna hitam */
            text-decoration: none;
            transition: all 0.3s ease-in-out;
            width: 163px; /* Lebar minimum */
            height: 40px; /* Tinggi minimum */
            margin-top: 2rem;
            font-weight: 550;
            margin: 0 18px; /* Memberikan jarak kiri dan kanan masing-masing 5px */

        }

        .category-button img {
            width: 32px;
            height: 32px;
            margin-right: 10px;
            transition: filter 0.3s ease-in-out;
        }

        /* Efek Hover */
        .category-button:hover {
            background: #ff8800; /* Warna orange */
            color: #fff; /* Warna putih */
        }

        .button-container {
    display: flex;
    justify-content: center; /* Menjadikan tombol berada di tengah */
    margin-top: 2rem; /* Jarak dari atas */
}

.button-container img[alt="Donations"] {
    width: 25px; /* Ukuran gambar donations */
    height: 22px;
}

.slideshow-container {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}

.slideshow-container img {
    width: 500px; /* Sesuaikan ukuran gambar */
    height: 200px;
    object-fit: cover;
    border-radius: 10px;
    transition: opacity 0.5s ease-in-out;
}

 /* Efek transisi untuk gambar */
 #slideImage {
        width: 100%;
        max-width: 500px;
        display: block;
        margin: auto;
        transition: opacity 0.4s ease-in-out;
    }
        .testimoni {
            padding: 50px 20px;
        }
        .section-title {
            font-size: 28px;
            font-weight: bold;
            text-align: center;
        }
        .section-subtitle {
            font-size: 16px;
            color: #6c757d;
            margin-bottom: 30px;
            text-align: center;
        }
        .testimoni-showcase {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 20px;
            max-width: 1200px;
            margin: auto;
        }
        .testimonial-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            flex: 1 1 45%;
            text-align: left;
            min-width: 300px;
        }
        .testimonial-card b {
            font-size: 14px;
        }
        .testimonial-card p {
            font-size: 14px;
            color: #333;
        }

        .Line {
    display: block;
    width: 60%;
    max-width: 450px; /* Maksimal panjang garis */
    height: 10px;
    background-color: #CFDEE1;
    margin: 10px auto;
    border-radius: 3px;
}
.Line-hero {
    display: block;
    width: 60%;
    max-width: 450px;
    height: 10px;
    background-color: #ced6d6;
    margin: 10px auto;
    border-radius: 3px;
    position: relative;
    left: -100px; /* Geser ke kiri sejauh 30px */
}

.Use-Linkan {
    background-color: rgb(245, 245, 245); /* Mengubah latar belakang menjadi putih */
    padding: 20px; /* Menambah ruang agar lebih rapi */
}

.features-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            padding: 40px 20px;
        }
        .feature-card {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 300px;
            text-align: left;
            flex: 1;
        }
        .feature-card h3 {
            color: #2f8d71;
            margin-bottom: 10px;
        }
        .feature-card p {
            color: #333;
            font-size: 14px;
        }
    
        .sign-up-container {
    display: flex;
    justify-content: center;
    margin-top: 20px; /* Sesuaikan jarak ke atas */
}

.sign-up-now {
    background-color: #FF7733;
    color: white !important;
    padding: 0.8rem 1.8rem;
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(255, 119, 51, 0.2);
    text-decoration: none; /* Menghilangkan garis bawah */
    line-height: normal; /* Menghindari space ekstra */
    display: inline-block; /* Mencegah margin bawah ekstra */
    text-align: center; /* Memastikan teksnya juga rata tengah */
}

.sign-up-now:hover {
    background-color: #ff6619;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(255, 119, 51, 0.3);
}

    </style>
</head>
<body>
    <div class="background-gradient"></div>

    <main class="hero">
        <div class="hero-content" id="heroContent">
            <h1 class="hero-title">Powering <span>Creators</span> Economy</h1>
            <hr class="Line-hero">
            <p class="hero-description">Create Instant Mobile Webpage to sell your knowledge. Chat, Video Calls, Events, Digital Product. Share it across social media.</p>
            <div class="create-section">
                <div class="url-input">
                    <span class="bold">Linkan.id/</span><span class="light" id="animated-text"></span>
                </div>
                <button class="create-button">Create</button>
            </div>
        </div>
        <div class="hero-image" id="heroImage">
            <div class="phone-mockup">
                <img src="<?php echo e(asset('images/logohp.png')); ?>" alt="Mobile App Preview">
            </div>
        </div>
    </main>

    <section class="best-creators">
        <h2 class="section-title">Best Creators Use Linkan.id</h2>
        <p class="section-subtitle">See how our creators use Linkan to set the new standard for their business</p>
        <div class="creators-showcase">
            <div class="scroll-container">
                <img src="<?php echo e(asset('images/BestCreator.png')); ?>" alt="Best Creator">
            </div>
        </div>
    </section>

    <section class="link-in-bio">
        <h2 class="section-title">Not just another link-in-bio</h2>
        <p class="section-subtitle">Linkan.id take care of your entire workflow, start to finish.</p>
<!-- Tombol Kategori -->
<div class="button-container">
    <a href="#" class="category-button" onclick="changeImage(event, 'digital')">
        <img src="<?php echo e(asset('images/iconfile.png')); ?>" alt="Digital Product">
        Digital Product
    </a>
    <a href="#" class="category-button" onclick="changeImage(event, 'donation')">
        <img src="<?php echo e(asset('images/icondonation.png')); ?>" alt="Donations">
        Donations
    </a>
    <a href="#" class="category-button" onclick="changeImage(event, 'course')">
        <img src="<?php echo e(asset('images/onlinecourse.png')); ?>" alt="Online Course">
        Online Course
    </a>
</div>

<!-- Slideshow Container -->
<div class="slideshow-container">
    <img id="slideImage" src="<?php echo e(asset('images/onlinecoursegambar.png')); ?>" alt="Slideshow">
</div>
    </section>

    <section class="testimoni">
        <h2 class="section-title">See What People Are Saying</h2>
        <p class="section-subtitle">No more paying for 5+ different apps! Linkan.id brings it all home</p>
        <div class="testimoni-showcase">
            <div class="testimonial-card">
                <b>Fajar Ramadhan Ms</b> <br>
                <p>@fajarms</p>
                <p>Fitur Fitur nya membantu banget!!</p>
            </div>
            <div class="testimonial-card">
                <b>Ardy Damar</b> <br>
                <p>@Ardyd</p>
                <p>Linkan.id sangat membantu saya untuk berjualan digital product dan membuka jasa Course Online saya</p>
            </div>
            <div class="testimonial-card">
                <b>Anik Aida</b> <br>
                <p>@anik3</p>
                <p>Terimakasih Linkan.id, Aku bisa mendapatkan Uang Tambahan Dari berjualan Digital Product</p>
            </div>
            <div class="testimonial-card">
                <b>Rifqi Pratama</b> <br>
                <p>@Rifqi</p>
                <p>Selama saya menggunakan Linkan.id saya sangat puas dengan penyediaan fitur nya seperti Digital Product, Online Course, dan Donation. Semoga kedepannya bisa berkembang lebih baik lagi</p>
            </div>
            <div class="testimonial-card">
                <b>Hasbillah Maulana</b> <br>
                <p>@hasbimaul</p>
                <p>Linkan.id membantu saya dengan adanya fitur donasi dapat membantu untuk membuat sebuah tempat berdonasi yang akan di donasikan untuk orang yang membutuhkan</p>
            </div>
        </div>
    </section>

    <section class="Use-Linkan">
        <h2 class="section-title">How Creators Using Linkan.id</h2>
        <hr class="Line">

        <div class="features-container">
            <div class="feature-card">
                <h3>Digital Products</h3>
                <p>Sell your e-book/presets/itinerary to your audience, Through lynk.id secured system</p>
            </div>
            <div class="feature-card">
                <h3>Online Course</h3>
                <p>Share your knowledge in a virtual class. Setting the duration & price for this offering is completely flexible</p>
            </div>
            <div class="feature-card">
                <h3>Donations</h3>
                <p>Receive one-off support from fans who may not be able to make a monthly commitment.</p>
            </div>
        </div>
    
        <div class="sign-up-container">
            <a href="<?php echo e(route('register')); ?>" class="sign-up-now">SIGN UP NOW!</a>
        </div>
    
    </section>
    

    <script>
         function refreshPage() {
        window.scrollTo(0, 0); // Pindah ke bagian atas halaman
        location.reload(); // Refresh halaman
    }

        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Menambahkan animasi transisi untuk konten
        const logo = document.getElementById('logo');
        const heroContent = document.getElementById('heroContent');
        const heroImage = document.getElementById('heroImage');

        logo.addEventListener('click', function() {
            // Menambahkan class transisi
            heroContent.classList.add('transition');
            heroImage.classList.add('transition');

            // Menghapus class transisi setelah animasi selesai
            setTimeout(() => {
                heroContent.classList.remove('transition');
                heroImage.classList.remove('transition');
            }, 500);
        });
        const animatedText = document.getElementById("animated-text");
    const texts = ["YourNameHere", "YourUsernameHere"];
    let textIndex = 0;
    let charIndex = 0;

    function typeEffect() {
        if (charIndex < texts[textIndex].length) {
            animatedText.textContent += texts[textIndex].charAt(charIndex);
            charIndex++;
            setTimeout(typeEffect, 100); // Kecepatan mengetik
        } else {
            setTimeout(eraseEffect, 1500); // Tunggu sebelum menghapus
        }
    }

    function eraseEffect() {
        if (charIndex > 0) {
            animatedText.textContent = texts[textIndex].substring(0, charIndex - 1);
            charIndex--;
            setTimeout(eraseEffect, 50); // Kecepatan menghapus
        } else {
            textIndex = (textIndex + 1) % texts.length;
            setTimeout(typeEffect, 500); // Tunggu sebelum mengetik lagi
        }
    }

    typeEffect(); // Jalankan animasi pertama kali

    let currentIndex = 0;
    let autoSlideInterval;
    let timeoutReset;
    const categories = ["digital", "course", "donation"];

    const images = {
        "digital": "<?php echo e(asset('images/Product Digital.png')); ?>",
        "course": "<?php echo e(asset('images/onlinecoursegambar.png')); ?>",
        "donation": "<?php echo e(asset('images/Donation.png')); ?>"
    };

    function changeImage(event, category) {
        if (event) event.preventDefault();

        let imageElement = document.getElementById('slideImage');
        if (!images[category]) return;

        // Efek fade-out sebelum mengganti gambar
        imageElement.style.opacity = "0";
        setTimeout(() => {
            imageElement.src = images[category];
            imageElement.style.opacity = "1";
        }, 500);

        // **Hentikan auto-slide sementara (3 detik)**
        clearInterval(autoSlideInterval); // Hentikan auto-slide sementara
        clearTimeout(timeoutReset); // Hapus timeout sebelumnya agar tidak tumpang tindih
        timeoutReset = setTimeout(() => {
            startAutoSlide(); // **Restart auto-slide setelah 3 detik**
        }, 4000);
    }

    function autoSlide() {
        if (!document.hidden) { // **Cegah auto-slide jika tab browser tidak aktif**
            let category = categories[currentIndex];
            changeImage(null, category);
            currentIndex = (currentIndex + 1) % categories.length;
        }
    }

    function startAutoSlide() {
        clearInterval(autoSlideInterval);
        autoSlideInterval = setInterval(autoSlide, 1000); // **Auto-slide setiap 1 detik secara konsisten**
    }

    document.addEventListener("DOMContentLoaded", function () {
        startAutoSlide();
    });

    </script>
</body>
</html> 
<?php echo $__env->make('layout.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Ardy\2025\Semester 4\Project2\LINKAN_ID-ardy\resources\views/welcome.blade.php ENDPATH**/ ?>