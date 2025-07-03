<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - Frequently Asked Questions</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    @include('layout.header')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color:#e3f3f6;
            overflow-x: hidden;
            padding-top: 80px;
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

        .faq-container {
            max-width: 800px;
            margin: auto;
            margin-top: 30px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .faq-item {
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
        }

        .faq-question {
            font-weight: bold;
            cursor: pointer;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background-color 0.3s ease;
            position: relative;
        }

        .faq-question:hover {
            background-color: #f5f5f5;
        }

        .faq-answer {
            display: none;
            margin-top: 5px;
            padding: 0 10px;
            line-height: 1.6;
            color: #666;
            transition: all 0.3s ease;
            opacity: 0;
            transform: translateY(-10px);
        }

        .faq-answer.show {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }

        @media screen and (max-width: 768px) {
            .faq-container {
                margin: 20px;
                padding: 15px;
            }
        }

        @media screen and (max-width: 480px) {
            .faq-question {
                font-size: 14px;
            }

            .faq-answer {
                font-size: 13px;
            }
        }
   .faq-arrow {
    display: inline-block;
    margin-left: 12px;
    width: 18px;
    height: 18px;
    transition: transform 0.3s cubic-bezier(.4,0,.2,1);
    vertical-align: middle;
    position: relative;
}
.faq-arrow::before {
    content: '';
    display: block;
    width: 0.7em;
    height: 0.7em;
    border-right: 2.5px solid #FF7A00;
    border-bottom: 2.5px solid #FF7A00;
    /* Panah ke kanan (rotate 315deg) */
    transform: rotate(315deg);
    position: absolute;
    left: 3px;
    top: 2px;
    transition: transform 0.3s cubic-bezier(.4,0,.2,1);
}
.faq-question.open .faq-arrow::before {
    /* Panah ke bawah (rotate 45deg) */
    transform: rotate(45deg);
}
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const questions = document.querySelectorAll('.faq-question');

            questions.forEach(question => {
                const answer = question.nextElementSibling;

                question.addEventListener('click', function() {
                    // Cek apakah jawaban sudah terbuka
                    const isVisible = answer.classList.contains('show');

                    // Tutup semua jawaban
                    document.querySelectorAll('.faq-answer').forEach(a => {
                        a.classList.remove('show');
                    });

                    // Jika jawabannya belum terbuka, tampilkan jawaban yang diklik
                    if (!isVisible) {
                        answer.classList.add('show');
                    }
                });
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
    const questions = document.querySelectorAll('.faq-question');

    questions.forEach(question => {
        const answer = question.nextElementSibling;

        question.addEventListener('click', function() {
            const isVisible = answer.classList.contains('show');

            // Tutup semua jawaban & reset panah
            document.querySelectorAll('.faq-answer').forEach(a => a.classList.remove('show'));
            document.querySelectorAll('.faq-question').forEach(q => q.classList.remove('open'));

            // Jika belum terbuka, buka jawaban & putar panah
            if (!isVisible) {
                answer.classList.add('show');
                question.classList.add('open');
            }
        });
    });
});
    </script>
</head>
<body>
<div class="background-gradient"></div>
    <div class="faq-container">
        <h2 style="text-align:center;color:#FF7A00">Frequently Asked Questions</h2>
        <div class="faq-item">
            <p class="faq-question">1. Apa itu Linkan.id?
                 <span class="faq-arrow"></span>
            </p>
            <p class="faq-answer">Linkan.id adalah platform link-in-bio yang membantu kreator, pebisnis, dan influencer mengelola kehadiran digital mereka dengan lebih praktis dan profesional. Platform ini memungkinkan pengguna membuat halaman bio yang berisi tautan ke media sosial, konten digital, serta layanan donasi dalam satu tempat.</p>
        </div>
        <div class="faq-item">
            <p class="faq-question">2. Apa saja fitur utama yang ditawarkan oleh Linkan.id?
                <span class="faq-arrow"></span>
            </p>
            <p class="faq-answer">Linkan.id menyediakan berbagai fitur unggulan, di antaranya: Halaman Link-in-Bio, Penjualan Produk Digital, Layanan Donasi Online, Sistem Pembayaran Aman, Analisis Performa Penjualan, dan Kustomisasi Template.</p>
        </div>
        <div class="faq-item">
            <p class="faq-question">3. Bagaimana sistem pembayaran di Linkan.id bekerja?
                <span class="faq-arrow"></span>
            </p>
            <p class="faq-answer">Linkan.id menggunakan payment gateway real-time, sehingga transaksi pembelian produk digital dan donasi dilakukan secara otomatis tanpa perlu konfirmasi manual.</p>
        </div>
        <div class="faq-item">
            <p class="faq-question">4. Apakah saya bisa menjual produk digital melalui Linkan.id?
                <span class="faq-arrow"></span>
            </p>
            <p class="faq-answer">Ya. Linkan.id memungkinkan pengguna menjual berbagai produk digital seperti e-book, e-course, template, dan konten digital lainnya.</p>
        </div>
        <div class="faq-item">
            <p class="faq-question">5. Bagaimana sistem pembagian komisi di Linkan.id?
                <span class="faq-arrow"></span>
            </p>
            <p class="faq-answer">Admin Platform menerima komisi sebesar 5% dari pendapatan Admin Seller, yang secara otomatis dipotong saat penarikan pendapatan dilakukan.</p>
        </div>
        <div class="faq-item">
            <p class="faq-question">6. Apakah saya bisa mengelola tampilan halaman saya di Linkan.id?
                <span class="faq-arrow"></span>
            </p>
            <p class="faq-answer">Ya. Linkan.id menyediakan berbagai template yang bisa dikustomisasi agar sesuai dengan identitas pengguna.</p>
        </div>
        <div class="faq-item">
            <p class="faq-question">7. Apakah ada fitur analisis performa di Linkan.id?
                <span class="faq-arrow"></span>
            </p>
            <p class="faq-answer">Ya. Linkan.id dilengkapi dengan fitur laporan analisis performa penjualan, sehingga pengguna bisa memantau perkembangan bisnis mereka dan mengoptimalkan strategi pemasaran.</p>
        </div>
        <div class="faq-item">
            <p class="faq-question">8. Apakah saya bisa menggunakan Linkan.id secara gratis?
                <span class="faq-arrow"></span>
            </p>
            <p class="faq-answer">Linkan.id mungkin menyediakan versi gratis dengan fitur terbatas, namun untuk akses penuh terhadap fitur premium seperti penjualan digital dan laporan analitik, kemungkinan ada biaya atau sistem pembagian komisi.</p>
        </div>
    </div>
</body>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const questions = document.querySelectorAll('.faq-question');

    questions.forEach(question => {
        const answer = question.nextElementSibling;

        question.addEventListener('click', function() {
            const isVisible = answer.classList.contains('show');

            // Tutup semua jawaban & reset panah
            document.querySelectorAll('.faq-answer').forEach(a => a.classList.remove('show'));
            document.querySelectorAll('.faq-question').forEach(q => q.classList.remove('open'));

            // Jika belum terbuka, buka jawaban & putar panah
            if (!isVisible) {
                answer.classList.add('show');
                question.classList.add('open');
            }
        });
    });
});
</script>
</html>
@include('layout.footer')