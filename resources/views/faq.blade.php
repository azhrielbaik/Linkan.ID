<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - Frequently Asked Questions</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @include('layout.header')
    <style>
        :root {
            --brand-orange: #ED842C; /* Or #FF9040 if they prefer the peach */
            --brand-blue: #0067D5;
            --brand-dark: #121212;
            --bg-body: #F8F9FA;
            --bg-card: #FFFFFF;
            --bg-item: #F4F6F9;
            --bg-item-hover: #EDF1F6;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--bg-body);
            overflow-x: hidden;
            padding-top: 80px;
        }

        .background-gradient {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--bg-body);
            z-index: -1;
        }

        .faq-wrapper {
            max-width: 1000px;
            margin: 60px auto 80px;
            background: var(--bg-card);
            padding: 60px 40px;
            border-radius: 24px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.04);
        }

        .faq-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .faq-badge {
            color: var(--brand-blue);
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            display: inline-block;
            margin-bottom: 12px;
        }

        .faq-header h2 {
            font-size: 40px;
            font-weight: 800;
            color: var(--brand-dark);
            margin-bottom: 16px;
            font-family: 'Outfit', sans-serif;
            line-height: 1.2;
        }

        .faq-header p {
            color: #666;
            font-size: 17px;
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .faq-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        .faq-item {
            background: var(--bg-item);
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .faq-question {
            padding: 22px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            font-weight: 600;
            font-size: 15px;
            color: var(--brand-dark);
            user-select: none;
            transition: background-color 0.2s ease;
        }

        .faq-question:hover {
            background: var(--bg-item-hover);
        }

        .faq-icon {
            font-size: 24px;
            color: #999;
            font-weight: 400;
            line-height: 1;
            transition: transform 0.3s ease, color 0.3s ease;
        }

        .faq-item.open .faq-icon {
            transform: rotate(45deg);
            color: var(--brand-orange);
        }

        .faq-item.open {
            background: var(--bg-item);
        }

        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s ease, padding 0.4s ease;
            padding: 0 24px;
            background: var(--bg-item);
        }

        .faq-item.open .faq-answer {
            max-height: 500px;
            padding: 0 24px 24px 24px;
        }

        .faq-answer p {
            font-size: 14px;
            color: #555;
            line-height: 1.7;
            margin: 0;
        }

        @media screen and (max-width: 768px) {
            .faq-wrapper {
                margin: 40px 20px;
                padding: 40px 20px;
            }
            .faq-grid {
                grid-template-columns: 1fr;
            }
            .faq-header h2 {
                font-size: 32px;
            }
        }
    </style>
</head>
<body>
    <div class="background-gradient"></div>
    
    <div class="faq-wrapper">
        <div class="faq-header">
            <span class="faq-badge">FAQ</span>
            <h2>Frequently Asked Questions</h2>
            <p>We compiled a list of answers to address your most pressing questions regarding our Services.</p>
        </div>
        
        <div class="faq-grid">
            <div class="faq-item">
                <div class="faq-question">
                    <span>Apa itu Linkan.id?</span>
                    <span class="faq-icon">+</span>
                </div>
                <div class="faq-answer">
                    <p>Linkan.id adalah platform link-in-bio yang membantu kreator, pebisnis, dan influencer mengelola kehadiran digital mereka dengan lebih praktis dan profesional. Platform ini memungkinkan pengguna membuat halaman bio yang berisi tautan ke media sosial, konten digital, serta layanan donasi dalam satu tempat.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <span>Apa saja fitur utama Linkan.id?</span>
                    <span class="faq-icon">+</span>
                </div>
                <div class="faq-answer">
                    <p>Linkan.id menyediakan berbagai fitur unggulan, di antaranya: Halaman Link-in-Bio, Penjualan Produk Digital, Layanan Donasi Online, Sistem Pembayaran Aman, Analisis Performa Penjualan, dan Kustomisasi Template.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <span>Bagaimana sistem pembayaran bekerja?</span>
                    <span class="faq-icon">+</span>
                </div>
                <div class="faq-answer">
                    <p>Linkan.id menggunakan payment gateway real-time, sehingga transaksi pembelian produk digital dan donasi dilakukan secara otomatis tanpa perlu konfirmasi manual.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <span>Bisa menjual produk digital?</span>
                    <span class="faq-icon">+</span>
                </div>
                <div class="faq-answer">
                    <p>Ya. Linkan.id memungkinkan pengguna menjual berbagai produk digital seperti e-book, e-course, template, dan konten digital lainnya secara langsung.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <span>Bagaimana sistem pembagian komisi?</span>
                    <span class="faq-icon">+</span>
                </div>
                <div class="faq-answer">
                    <p>Admin Platform menerima komisi sebesar 5% dari pendapatan Admin Seller, yang secara otomatis dipotong saat penarikan pendapatan dilakukan.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <span>Bisa mengelola tampilan halaman?</span>
                    <span class="faq-icon">+</span>
                </div>
                <div class="faq-answer">
                    <p>Ya. Linkan.id menyediakan berbagai pilihan layout dan tema yang bisa dikustomisasi agar sesuai dengan identitas atau brand pengguna.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <span>Apakah ada fitur analisis performa?</span>
                    <span class="faq-icon">+</span>
                </div>
                <div class="faq-answer">
                    <p>Ya. Linkan.id dilengkapi dengan fitur laporan analitik komprehensif, sehingga pengguna bisa memantau trafik, klik, sumber trafik, hingga performa penjualan.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <span>Apakah gratis?</span>
                    <span class="faq-icon">+</span>
                </div>
                <div class="faq-answer">
                    <p>Linkan.id menyediakan akses gratis untuk fitur dasar link-in-bio. Namun untuk akses penuh terhadap fitur premium seperti penjualan digital dan analitik mendalam, terdapat sistem pembagian komisi atau langganan.</p>
                </div>
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
</html>
@include('layout.footer')