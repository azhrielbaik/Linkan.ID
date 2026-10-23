**DOKUMEN USE CASE SCENARIO — LINKAN.ID**  
Dokumen ini memuat skenario penggunaan (*use case scenarios*) mendalam dan menyeluruh untuk sistem **Linkan.ID**. Setiap skenario disusun berdasarkan implementasi kode, logika bisnis, aturan validasi, serta arsitektur basis data yang ada di dalam proyek.  
  
---  
**DAFTAR ISI USE CASE**  
**A. Aktor: Pengunjung & Pembeli (Public / Guest)**  
1. **UC-01**: Mengakses Profil Microsite Kreator (*Public Microsite View*)  
2. **UC-02**: Mengakses & Membuka Tautan Singkat (*Shortlink Redirection*)  
3. **UC-03**: Pembelian Produk Digital & Pembayaran (*Digital Product Checkout*)  
4. **UC-04**: Mengajukan Pengaduan Sengketa Transaksi (*Buyer Dispute Submission*)  
5. **UC-05**: Mengirim Ulasan Produk Digital via Token (*HMAC-Signed Product Review*)  
**B. Aktor: Kreator / Penjual** (*Seller*)  
1. **UC-06**: Registrasi & Autentikasi Kreator (*Registration, Login, Google OAuth*)  
2. **UC-07**: Mengatur Tampilan & Urutan Blok Microsite (*Microsite Builder & Reordering*)  
3. **UC-08**: Membuat & Mengelola Produk Digital (*Product Management & PWYW Pricing*)  
4. **UC-09**: Membuat Shortlink & Memantau Statistik Kunjungan (*Shortlink & Analytics*)  
5. **UC-10**: Mengajukan Penarikan Dana (*Payout / Withdrawal Request*)  
6. **UC-11**: Memperbarui Email Akun dengan Verifikasi 2-Langkah (*2-Step Email Change via OTP*)  
7. **UC-12**: Mengajukan Banding Penangguhan Akun (*Suspension Appeal Submission*)  
8. **UC-13**: Mengajukan & Merespons Tiket Bantuan (*Support Ticket Interaction*)  
**C. Aktor: Administrator Platform** (*Platform Admin*)  
1. **UC-14**: Kurasi & Verifikasi Produk Digital Baru (*Product Verification*)  
2. **UC-15**: Manajemen Penangguhan Pengguna & Peninjauan Banding (*User Suspension & Appeal Review*)  
3. **UC-16**: Peninjauan & Persetujuan Penarikan Dana Seller (*Payout Approval Management*)  
4. **UC-17**: Investigasi & Resolusi Sengketa Pembeli (*Dispute & Refund Resolution*)  
5. **UC-18**: Pengaturan Darurat Platform & Siaran Pengumuman (*Emergency Switches & Broadcast*)  
6. **UC-19**: Audit Log Aktivitas & Jejak Forensik Sistem (*System Activity & Transaction Auditing*)  
**D. Aktor: Sistem & Layanan Eksternal** (*System / Gateway*)  
1. **UC-20**: Pemrosesan Otomatis Webhook Midtrans (*Asynchronous Payment Callback & Fulfillment*)  
  
---  
**A. MODUL PENGUNJUNG & PEMBELI (PUBLIC / GUEST)**  
  
---  
**UC-01: Mengakses Profil Microsite Kreator (Public Microsite View)**  
- **Aktor**: Pengunjung / Publik  
- **Deskripsi Singkat**: Pengunjung mengakses URL profil unik kreator (`/{alias}` atau `/linkan.id/{alias}`) untuk melihat tautan, gambar, video, dan etalase produk digital yang disematkan oleh kreator.  
- **Precondition**:  
  - Kreator telah membuat akun dan memiliki konfigurasi microsite (`Appearance`) aktif.  
  - Akun kreator tidak sedang ditangguhkan (*suspended*).  
- **Postcondition**:  
  - Halaman profil kreator berhasil dirender lengkap dengan elemen aktif.  
  - Statistik kunjungan harian (*link views*) tercatat di basis data.  
***Alur Normal***  
  
|  |  |  |  
| - | - | - |  
| **No** |  **Aksi Aktor** |  **Reaksi Sistem** |  
| 1 |  Pengunjung membuka URL `https://linkan.id/{alias}` pada peramban web. |  Sistem menangkap request pada rute catch-all dan mencocokkan `{alias}` dengan data di tabel `appearances`. |  
| 2 |  - |  Sistem memeriksa status penangguhan akun pemilik microsite (`User::isSuspended()`). |  
| 3 |  - |  Sistem memeriksa apakah IP Address dan User-Agent pengunjung sudah tercatat pada tabel `link_views` untuk tanggal hari ini. |  
| 4 |  - |  Jika belum pernah tercatat hari ini, sistem menyimpan rekaman baru ke tabel `link_views`. |  
| 5 |  - |  Sistem melalui `AppearanceService::getSortedBlocksForPublic()` membaca konfigurasi `blocks_order` dan mengambil data elemen aktif (Profil, Gambar, Video, Teks, Pembatas, Sosmed, dan Produk Digital aktif). |  
| 6 |  - |  Sistem merender tampilan Blade `public.profile` dengan metadata OpenGraph, Schema.org JSON-LD, serta tata letak CSS yang disesuaikan. |  
  
   
***Alur Alternatif & Pengecualian***  
- **A1: Akun Pemilik Microsite Ditangguhkan (*Suspended*)**  
  - **Reaksi Sistem**: Pada langkah 2, sistem mendeteksi `suspended_at` terisi dan waktu sekarang belum melewati batas penangguhan. Sistem langsung menghentikan proses dan menampilkan halaman error HTTP 403 Forbidden dengan pesan: *"Profil atau tautan ini sedang ditangguhkan."*  
- **A2: Alias Microsite Tidak Ditemukan**  
  - **Reaksi Sistem**: Pada langkah 1, sistem tida k menemukan kecocokan alias di tabel `appearances` maupun slug di tabel `shortlinks`. Sistem mengembalikan response HTTP 404 Not Found (*"Tautan tidak ditemukan"*).  
- **A3: Elemen Tertentu Dinonaktifkan oleh Kreator**  
  - **Reaksi Sistem**: Pada langkah 5, sistem hanya memuat elemen yang memiliki nilai `is_active = true`. Elemen yang disembunyikan (*hidden*) otomatis dilewati dari struktur `sortedBlocks`.  
  
---  
**UC-02: Mengakses & Membuka Tautan Singkat (Shortlink Redirection)**  
- **Aktor**: Pengunjung / Publik  
- **Deskripsi Singkat**: Pengunjung mengakses URL singkat (`/{slug}`) untuk dialihkan ke URL tujuan eksternal, dengan pencatatan analitik klik, geolokasi, dan deteksi tipe perangkat.  
- **Precondition**: Shortlink telah didaftarkan oleh kreator di dalam sistem.  
- **Postcondition**: Pengunjung berhasil dialihkan ke URL target (HTTP 302), dan data klik tersimpan secara analitis.  
***Alur Normal***  
  
|  |  |  |  
| - | - | - |  
| **No** |  **Aksi Aktor** |  **Reaksi Sistem** |  
| 1 |  Pengunjung membuka URL `https://linkan.id/{slug}`. |  Sistem mencari rekaman slug di tabel `shortlinks`. |  
| 2 |  - |  Sistem memastikan shortlink belum kedaluwarsa (`expires_at == null` atau `now() <= expires_at`). |  
| 3 |  - |  Sistem memeriksa apakah shortlink dilindungi kata sandi (`password`). Jika tidak ada proteksi, sistem melanjutkan proses. |  
| 4 |  - |  Sistem membaca cookie `linkan_visitor`. Jika belum ada, sistem mengenerate UUID unik baru untuk pengunjung. |  
| 5 |  - |  Sistem mendeteksi `source` (berdasarkan parameter `utm_source` atau HTTP referer host) dan tipe perangkat (`DeviceDetector`: Mobile, Tablet, Desktop). |  
| 6 |  - |  Sistem menyimpan rekaman klik baru ke tabel `shortlink_clicks`. |  
| 7 |  - |  Sistem mendispatch asynchronous queue job `ProcessClickLocation` untuk melacak negara dan kota pengunjung via API geolokasi di latar belakang. |  
| 8 |  - |  Sistem mengembalikan response HTTP 302 Redirect ke alamat URL `destination`. |  
  
   
***Alur Alternatif & Pengecualian***  
- **A1: Shortlink Telah Kedaluwarsa (*Expired*)**  
  - **Reaksi Sistem**: Pada langkah 2, waktu saat ini melewati nilai `expires_at`. Sistem menghentikan alur dan mengembalikan response HTTP 410 Gone dengan pesan: *"Tautan ini telah kedaluwarsa."*  
- **A2: Shortlink Dilindungi Kata Sandi (*Password Protected*)**  
  1. Pada langkah 3, sistem menemukan kolom `password` memiliki nilai dan sesi `unlocked_shortlink_{id}` belum ada.  
  2. Sistem mengalihkan pengunjung ke halaman input kata sandi `/p/{slug}` (`shortlink.password.form`).  
  3. **Aksi Aktor**: Pengunjung memasukkan kata sandi dan menekan tombol submit.  
  4. **Reaksi Sistem**: Sistem memvalidasi input. Jika cocok, sistem menetapkan kunci sesi `unlocked_shortlink_{id}` dan mengalihkan kembali ke `/{slug}` untuk memproses alur normal langkah 4-8. Jika salah, sistem menampilkan pesan error: *"Kata sandi salah."*  
  
---  
**UC-03: Pembelian Produk Digital & Pembayaran (Digital Product Checkout)**  
- **Aktor**: Pembeli (Guest/Buyer)  
- **Deskripsi Singkat**: Pembeli memilih produk digital dari kreator, memilih jumlah/menentukan nominal donasi/harga (PWYW), mengisi identitas pemesan, melakukan pembayaran via Midtrans Snap, dan menerima produk digital secara instan.  
- **Precondition**:  
  - Produk berstatus aktif (`is_active = 1`), tidak sedang di-takedown oleh Admin, dan kreator tidak disuspend.  
  - Gerbang pembayaran checkout tidak sedang dinonaktifkan oleh platform.  
- **Postcondition**:  
  - Data transaksi tercatat dengan status `pending` (atau `success` jika produk gratis).  
  - Pembeli menerima email pengiriman file produk digital beserta token ulasan.  
***Alur Normal***  
  
|  |  |  |  
| - | - | - |  
| **No** |  **Aksi Aktor** |  **Reaksi Sistem** |  
| 1 |  Pembeli membuka halaman detail produk `/id/product/{id}`. |  Sistem menampilkan detail produk, jenis harga (*fixed* atau *pay-what-you-want*), deskripsi, dan tombol beli. |  
| 2 |  Pembeli mengklik tombol "Beli Sekarang". |  Sistem membuka halaman checkout `/id/checkout/{id}`. |  
| 3 |  Pembeli mengisi nama lengkap, alamat email, jumlah kuantitas (*qty*), serta harga kustom (jika tipe produk PWYW). |  Sistem memvalidasi input formulir melalui `CheckoutDigitalProductRequest`. |  
| 4 |  Pembeli menekan tombol "Lanjut Pembayaran". |  Sistem memanggil `CheckoutService::generateSnapToken()`, menghitung total tagihan, dan meminta Snap Token ke API server Midtrans. |  
| 5 |  - |  Sistem mengembalikan Snap Token ke halaman frontend dan memicu popup antarmuka pembayaran Midtrans Snap. |  
| 6 |  - |  Sistem menyimpan draf transaksi awal ke tabel `transactions` dengan status `pending` via `CheckoutService::storeTransaction()`. |  
| 7 |  Pembeli menyelesaikan pembayaran di antarmuka Midtrans (misal: scan QRIS atau transfer VA). |  Midtrans memproses transaksi dan mengirimkan webhook notifikasi ke backend Linkan.ID (lihat alur UC-20). |  
| 8 |  Pembeli dialihkan ke halaman `/id/checkout/{id}/success?order_id=...`. |  Sistem menampilkan konfirmasi pembayaran sukses dan instruksi pengunduhan file digital. |  
  
   
***Alur Alternatif & Pengecualian***  
- **A1: Pembelian Produk Gratis (Rp 0)**  
  - **Aksi Aktor**: Pembeli checkout produk yang berharga Rp 0.  
  - **Reaksi Sistem**: Pada langkah 4, sistem mendeteksi `totalPrice == 0`. Sistem tidak memanggil API Midtrans, langsung menetapkan status transaksi menjadi `success`, mengirimkan file produk melalui email secara langsung, dan mengalihkan pembeli ke halaman sukses.  
- **A2: Fitur Checkout Dimatikan oleh Admin Platform (*Emergency Kill-Switch*)**  
  - **Reaksi Sistem**: Pada langkah 4, sistem mengecek pengaturan `PlatformSetting::get('disable_checkout')`. Jika aktif (nilai 1), sistem menggagalkan proses dan mengembalikan flash error: *"Fitur checkout sedang dinonaktifkan sementara oleh Admin."*  
- **A3: Nominal PWYW Di Bawah Batas Minimum**  
  - **Aksi Aktor**: Pembeli memasukkan nominal harga kustom di bawah `price_min`.  
  - **Reaksi Sistem**: Validasi FormRequest menolak input dan memunculkan pesan error: *"Nominal harga kustom minimal adalah Rp {price_min}."*  
- **A4: Pembeli Menutup Popup Midtrans Tanpa Membayar**  
  - **Aksi Aktor**: Pembeli menutup modal Snap.  
  - **Reaksi Sistem**: Frontend menangkap callback `onClose` Midtrans, transaksi tetap berstatus `pending` dan menunggu pembayaran hingga batas waktu kedaluwarsa Midtrans tercapai.  
  
---  
**UC-04: Mengajukan Pengaduan Sengketa Transaksi (Buyer Dispute Submission)**  
- **Aktor**: Pembeli (Buyer)  
- **Deskripsi Singkat**: Pembeli yang mengalami kendala (file rusak, link kedaluwarsa, atau produk tidak sesuai deskripsi) dapat mengajukan sengketa resmi melalui form publik bantuan untuk ditengahi oleh Platform Admin.  
- **Precondition**: Pembeli memiliki ID Pesanan (*Order ID*) yang valid dan berstatus `success`.  
- **Postcondition**: Laporan sengketa tersimpan di tabel `disputes`, dan dana transaksi terkait di-hold (dibekukan) dari saldo penarikan seller.  
***Alur Normal***  
  
|  |  |  |  
| - | - | - |  
| **No** |  **Aksi Aktor** |  **Reaksi Sistem** |  
| 1 |  Pembeli membuka halaman formulir bantuan sengketa `/id/bantuan/sengketa`. |  Sistem menampilkan form verifikasi pesanan dan input pengaduan sengketa. |  
| 2 |  Pembeli memasukkan Order ID dan mengklik tombol "Cek Pesanan". |  Sistem melakukan pengecekan via AJAX `/bantuan/sengketa/check-order` (dengan rate limit 30 request/menit). |  
| 3 |  - |  Sistem memastikan pesanan ada di tabel `transactions`, statusnya `success`, dan belum pernah memiliki tiket sengketa aktif. |  
| 4 |  - |  Sistem mengembalikan detail produk dan nama penjual ke antarmuka form. |  
| 5 |  Pembeli melengkapi data: email pembeli, alasan sengketa, kronologi detail keluhan, nomor rekening/e-wallet untuk pengembalian dana, serta bukti tangkapan layar/dokumen pendukung. |  Sistem menerima input formulir. |  
| 6 |  Pembeli menekan tombol "Kirim Pengaduan Sengketa". |  Sistem memvalidasi data via `PublicDisputeController@store` (rate limit 10 request/menit). |  
| 7 |  - |  Sistem mengunggah file bukti ke direktori penyimpanan privat `disputes/evidence`. |  
| 8 |  - |  Sistem menyimpan rekaman baru di tabel `disputes` dengan status `pending`. |  
| 9 |  - |  Logika kalkulasi saldo seller secara otomatis membekukan nominal transaksi tersebut (*frozen dispute amount*) sehingga tidak dapat di-withdraw seller selama proses investigasi. |  
| 10 |  - |  Sistem mencatat aktivitas log sistem dan mengalihkan pembeli dengan pesan konfirmasi keberhasilan. |  
  
   
***Alur Alternatif & Pengecualian***  
- **A1: Order ID Tidak Ditemukan atau Belum Sukses**  
  - **Reaksi Sistem**: Pada langkah 3, jika `status != 'success'`, sistem mengembalikan response JSON error: *"Nomor pesanan tidak valid atau transaksi belum selesai."*  
- **A2: Pesanan Sudah Memiliki Sengketa yang Sedang Berjalan**  
  - **Reaksi Sistem**: Sistem mendeteksi relasi sengketa dengan status `pending` atau `under_review`. Sistem menolak pengajuan ganda dengan pesan: *"Pesanan ini sudah memiliki tiket pengaduan aktif yang sedang diproses."*  
  
---  
**UC-05: Mengirim Ulasan Produk Digital via Token (HMAC-Signed Product Review)**  
- **Aktor**: Pembeli (Buyer)  
- **Deskripsi Singkat**: Pembeli memberikan rating bintang (1-5) dan testimoni tertulis untuk produk yang telah dibeli tanpa perlu login, menggunakan tautan review bertanda tangan digital yang dikirimkan ke email mereka.  
- **Precondition**: Pembeli memiliki URL ulasan berisi token HMAC yang valid.  
- **Postcondition**: Ulasan tersimpan di tabel `product_reviews` dan token tidak dapat digunakan ulang.  
***Alur Normal***  
  
|  |  |  |  
| - | - | - |  
| **No** |  **Aksi Aktor** |  **Reaksi Sistem** |  
| 1 |  Pembeli mengklik tautan review dari email transaksi: `/id/review/{token}`. |  Sistem membaca `{token}` dan memanggil `ReviewTokenHelper::verify($token)`. |  
| 2 |  - |  Sistem mendekode token payload dan mencocokkan signature dengan `hash_hmac('sha256', order_id, config('app.key'))`. |  
| 3 |  - |  Setelah signature valid, sistem mengekstrak `order_id` dan mengambil data transaksi serta produk terkait. |  
| 4 |  - |  Sistem memastikan transaksi ini belum pernah memiliki ulasan sebelumnya di tabel `product_reviews`. |  
| 5 |  - |  Sistem merender tampilan formulir ulasan `public.review`. |  
| 6 |  Pembeli memilih jumlah bintang (1 sampai 5) dan menuliskan komentar pengalaman produk. |  Sistem menerima data input. |  
| 7 |  Pembeli mengklik tombol "Kirim Ulasan". |  Sistem memvalidasi input (rating wajib integer 1-5, komentar string sanitasi). |  
| 8 |  - |  Sistem menyimpan ulasan ke tabel `product_reviews` dengan mengikat `product_id`, `order_id`, `rating`, `comment`, dan `buyer_name`. |  
| 9 |  - |  Sistem menampilkan halaman terima kasih sukses. |  
  
   
***Alur Alternatif & Pengecualian***  
- **A1: Token Telah Dimanipulasi / Tidak Valid**  
  - **Reaksi Sistem**: Pada langkah 2, perbandingan signature gagal (`hash_equals == false`). Sistem menghentikan eksekusi dan memunculkan error HTTP 403 / 404: *"Tautan ulasan tidak valid atau telah dimodifikasi."*  
- **A2: Ulasan Sudah Pernah Dikirim Sebelumnya**  
  - **Reaksi Sistem**: Pada langkah 4, ditemukan data review untuk `order_id` tersebut. Sistem mengarahkan pembeli ke tampilan pemberitahuan: *"Anda sudah pernah memberikan ulasan untuk pesanan ini."*  
  
---  
**B. MODUL KREATOR / PENJUAL (SELLER)**  
  
---  
**UC-06: Registrasi & Autentikasi Kreator (Registration, Login, Google OAuth)**  
- **Aktor**: Kreator (Seller)  
- **Deskripsi Singkat**: Kreator mendaftar akun baru menggunakan email dan kata sandi atau akun Google OAuth, serta masuk ke dashboard Linkan.ID.  
- **Precondition**: Kreator memiliki email yang valid dan belum terdaftar.  
- **Postcondition**: Kreator terautentikasi dan sesi dashboard admin seller aktif.  
***Alur Normal (Registrasi Tradisional)***  
  
|  |  |  |  
| - | - | - |  
| **No** |  **Aksi Aktor** |  **Reaksi Sistem** |  
| 1 |  Kreator membuka halaman registrasi `/id/register`. |  Sistem menampilkan form registrasi lengkap dengan layout split-screen. |  
| 2 |  Kreator mengisi Nama Lengkap, Username unik, Email, Kata Sandi, dan Konfirmasi Kata Sandi. |  Sistem menerima input formulir. |  
| 3 |  Kreator menekan tombol "Create Account". |  Sistem memvalidasi keunikan username dan email, serta format kata sandi. |  
| 4 |  - |  Sistem membuat data user baru di tabel `users` dengan `role = 'user'`, melakukan *hashing* kata sandi menggunakan Bcrypt, dan membuat data default tampilan di tabel `appearances`. |  
| 5 |  - |  Sistem mencatat aktivitas di `ActivityLogger` dan mengautentikasi kreator ke sesi aplikasi. |  
| 6 |  - |  Sistem mengalihkan kreator ke dashboard seller `/id/admin/dashboard`. |  
  
   
***Alur Alternatif: Login via Google OAuth***  
1. **Aksi Aktor**: Kreator mengklik tombol "Sign in with Google" di halaman login.  
2. **Reaksi Sistem**: Sistem mengalihkan pengguna ke consent screen Google API via Laravel Socialite (`LoginController::redirectToGoogle`).  
3. **Aksi Aktor**: Kreator memilih akun Google dan memberikan persetujuan izin profil/email.  
4. **Reaksi Sistem**:  
  - Google mengembalikan callback ke `/id/login/google/callback`.  
  - Sistem mencocokkan `google_id` atau `email`. Jika sudah terdaftar, sistem langsung meloginkan akun.  
  - Jika pengguna baru mendaftar via Google, sistem membuatkan username unik secara otomatis dan menetapkan atribut `google_id`.  
  - Jika akun berstatus suspended, sistem langsung melempar middleware `CheckSuspended`.  
  - Sistem mengarahkan pengguna ke dashboard.  
  
---  
**UC-07: Mengatur Tampilan & Urutan Blok Microsite (Microsite Builder & Reordering)**  
- **Aktor**: Kreator (Seller)  
- **Deskripsi Singkat**: Kreator mengkustomisasi tampilan halaman biolink mereka (tema, warna, font, gambar latar belakang, avatar) serta menambah, menyusun urutan (drag-and-drop), dan menyembunyikan blok elemen secara realtime.  
- **Precondition**: Kreator telah login dan berada di halaman editor microsite `/id/admin/microsites`.  
- **Postcondition**: Konfigurasi tema dan urutan blok `blocks_order` tersimpan di database dan langsung tercermin pada preview.  
***Alur Normal***  
  
|  |  |  |  
| - | - | - |  
| **No** |  **Aksi Aktor** |  **Reaksi Sistem** |  
| 1 |  Kreator membuka tab builder di `/id/admin/microsites`. |  Sistem memuat antarmuka editor, daftar elemen yang telah dibuat, dan iframe live preview. |  
| 2 |  Kreator mengubah tema warna latar, gaya font, atau bentuk avatar (*circle, rounded, square*). |  Sistem mengirim request AJAX ke `/id/admin/appearance` atau `/id/admin/appearance/design-settings`. |  
| 3 |  - |  Sistem memperbarui tabel `appearances` dan memicu `preview-sync.js` untuk meng-update styling preview tanpa me-reload seluruh halaman. |  
| 4 |  Kreator menambahkan elemen baru (misal: Blok Teks/Tombol Tautan) melalui modal pop-up. |  Sistem menyimpan entitas baru ke tabel `text_elements` dan menambahkan ID elemen (misal: `text_5`) ke string `blocks_order`. |  
| 5 |  Kreator menyeret (*drag-and-drop*) kartu elemen ke posisi urutan baru. |  Modul `drag-order.js` mengkalkulasi urutan array baru dari DOM. |  
| 6 |  - |  Frontend mengirim request POST ke `/id/admin/elements/order` dengan payload `order: "profile,text_5,image_1,digitalproduct_2"`. |  
| 7 |  - |  Sistem memperbarui kolom `blocks_order` pada tabel `appearances` milik kreator. |  
| 8 |  Kreator mengklik tombol switch visibilitas pada salah satu elemen. |  Sistem mengirim request ke `/id/admin/elements/toggle-visibility` dan mengubah kolom boolean `is_active` elemen bersangkutan. |  
  
   
***Alur Alternatif & Pengecualian***  
- **A1: Kegagalan Upload Banner / Avatar karena Melebihi Batas Ukuran**  
  - **Aksi Aktor**: Kreator mengunggah gambar latar belakang dengan ukuran lebih dari batas validasi (misal > 5MB).  
  - **Reaksi Sistem**: Form request menolak file, memunculkan toast error: *"Ukuran gambar tidak boleh melebihi batas maksimal"*, dan menjaga gambar lama tetap utuh.  
  
---  
**UC-08: Membuat & Mengelola Produk Digital (Product Management & PWYW Pricing)**  
- **Aktor**: Kreator (Seller)  
- **Deskripsi Singkat**: Kreator membuat produk digital baru (ebook, kursus, preset, file arsip), menentukan model penetapan harga (*Fixed Price* atau *Pay What You Want*), mengunggah cover & file produk, serta menyematkannya ke microsite.  
- **Precondition**: Kreator login ke dashboard seller dan memiliki kuota penyimpanan yang diizinkan.  
- **Postcondition**: Produk digital tersimpan di tabel `digital_products`, file terarsip di storage, dan otomatis masuk ke antrean verifikasi/microsite.  
***Alur Normal***  
  
|  |  |  |  
| - | - | - |  
| **No** |  **Aksi Aktor** |  **Reaksi Sistem** |  
| 1 |  Kreator menavigasi ke menu "Digital Products" dan menekan tombol "Tambah Produk". |  Sistem menampilkan form penambahan produk digital `/id/admin/digital-products/create`. |  
| 2 |  Kreator mengisi judul produk, deskripsi, kategori, serta mengunggah gambar thumbnail/cover. |  Sistem menerima input. |  
| 3 |  Kreator memilih model harga: Fixed Price (memasukkan nominal pasti, misal Rp 50.000) atau Pay What You Want (memasukkan harga minimal, misal Rp 15.000). |  Sistem menyesuaikan form input harga berdasarkan pilihan model. |  
| 4 |  Kreator mengunggah berkas aset digital (PDF, ZIP, EPUB, dll) atau memasukkan URL akses tautan Google Drive / Notion. |  Sistem menerima berkas/tautan. |  
| 5 |  Kreator mencentang opsi "Sematkan ke Microsite" dan menekan tombol "Simpan Produk". |  Sistem memvalidasi payload melalui `StoreDigitalProductRequest`. |  
| 6 |  - |  Sistem menyimpan file cover dan file aset digital ke direktori storage privat `digital_products/files`. |  
| 7 |  - |  Sistem membuat entitas baru di tabel `digital_products` dengan kepemilikan `user_id`. |  
| 8 |  - |  Sistem secara otomatis menyisipkan penanda `digitalproduct_{id}` ke kolom `blocks_order` pada tabel `appearances`. |  
| 9 |  - |  Sistem mencatat log di `ActivityLogger` dan mengalihkan kreator ke daftar produk dengan pesan sukses. |  
  
   
***Alur Alternatif & Pengecualian***  
- **A1: Menghapus Produk yang Telah Disematkan di Microsite**  
  - **Aksi Aktor**: Kreator memilih aksi "Hapus dari Microsite" pada produk.  
  - **Reaksi Sistem**: Sistem tidak menghapus data produk secara permanen dari tabel (untuk menjaga riwayat transaksi lama), melainkan hanya mencabut penanda `digitalproduct_{id}` dari string `blocks_order` di tabel `appearances` (*unpin action*).  
  
---  
**UC-09: Membuat Shortlink & Memantau Statistik Kunjungan (Shortlink & Analytics)**  
- **Aktor**: Kreator (Seller)  
- **Deskripsi Singkat**: Kreator membuat tautan pendek kustom dengan proteksi opsional (kata sandi dan masa kedaluwarsa), serta melihat metrik analitik klik (grafik harian, sebaran perangkat, geolokasi negara, dan sumber rujukan referer).  
- **Precondition**: Kreator login ke dashboard seller.  
- **Postcondition**: Tautan baru aktif dan statistik klik dapat dipantau via grafik ApexCharts.  
***Alur Normal***  
  
|  |  |  |  
| - | - | - |  
| **No** |  **Aksi Aktor** |  **Reaksi Sistem** |  
| 1 |  Kreator membuka menu "Shortlinks" (`/id/admin/shortlinks`). |  Sistem merender daftar shortlink yang dimiliki kreator dengan pagination 6 kartu per halaman. |  
| 2 |  Kreator mengisi form cepat: Judul, Slug kustom (misal: `promo-spesial`), URL tujuan (`destination`), password (opsional), dan tanggal kedaluwarsa (opsional). |  Sistem menerima input. |  
| 3 |  Kreator menekan tombol "Buat Tautan". |  Sistem memvalidasi keunikan slug di tabel `shortlinks`. |  
| 4 |  - |  Sistem menyimpan shortlink baru yang terikat pada `user_id` kreator. |  
| 5 |  - |  Sistem mencatat aktivitas log pembuatan shortlink dan menampilkan link lengkap `https://linkan.id/promo-spesial`. |  
| 6 |  Kreator mengklik tombol "Lihat Statistik / Analitik" pada kartu shortlink. |  Sistem membuka halaman analitik `/id/admin/shortlinks/{id}/analytics`. |  
| 7 |  - |  Sistem mengeksekusi agregasi kueri klik: total klik, visitor unik (`distinct visitor_id`), time behavior (hari teramai), grafik device breakdown (Mobile/Tablet/Desktop), dan geografi negara. |  
| 8 |  - |  Sistem menyajikan data visual interaktif melalui library ApexCharts. |  
  
   
***Alur Alternatif & Pengecualian***  
- **A1: Slug Kustom Sudah Digunakan Pengguna Lain**  
  - **Reaksi Sistem**: Sistem mendeteksi duplikasi nilai `slug` pada tabel `shortlinks`. Validasi melempar pesan error: *"Slug ini sudah digunakan, silakan pilih slug lain."*  
- **A2: Kreator Mencoba Mengakses Analitik Shortlink Milik Orang Lain**  
  - **Reaksi Sistem**: Metode `ownedShortlink()` memeriksa `shortlink->user_id === auth()->id()`. Jika tidak cocok, sistem langsung melempar HTTP 404 Not Found untuk menjaga kerahasiaan data.  
  
---  
**UC-10: Mengajukan Penarikan Dana (Payout / Withdrawal Request)**  
- **Aktor**: Kreator (Seller)  
- **Deskripsi Singkat**: Kreator mengajukan penarikan akumulasi hasil penjualan produk digital ke rekening bank atau e-wallet mereka yang telah terdaftar.  
- **Precondition**:  
  - Saldo bersih (*current withdrawable balance*) mencukupi batas minimal penarikan (default Rp 10.000).  
  - Kreator telah mengisi dan menyimpan data rekening pencairan (*payout method*).  
  - Akun kreator tidak sedang ditangguhkan (*suspended*).  
- **Postcondition**:  
  - Saldo yang diminta masuk ke status tertahan (*pending hold*).  
  - Transaksi penarikan berstatus `pending` tersimpan di `payout_transactions` menunggu approval admin.  
***Alur Normal***  
  
|  |  |  |  
| - | - | - |  
| **No** |  **Aksi Aktor** |  **Reaksi Sistem** |  
| 1 |  Kreator menavigasi ke menu "Dompet & Payout" (`/id/admin/payout`). |  Sistem melalui `PayoutService::getPayoutOverview()` menghitung total penghasilan kotor, total penarikan selesai, penarikan pending, dan dana sengketa yang dibekukan. |  
| 2 |  - |  Sistem menampilkan saldo yang tersedia untuk dicairkan beserta potongan estimasi fee komisi platform (misal: 5%). |  
| 3 |  Kreator mengklik tombol "Tarik Dana" (`/id/admin/payout/withdraw`). |  Sistem memeriksa apakah kreator sudah memiliki `UserPayoutDetail`. Jika sudah, sistem membuka formulir penarikan. |  
| 4 |  Kreator memasukkan nominal penarikan yang diinginkan (dalam rentang saldo tersedia dan di atas minimal withdraw). |  Sistem memvalidasi nominal. |  
| 5 |  Kreator menekan tombol "Ajukan Penarikan". |  Request diproses dengan rate limiting (maksimal 6 request/menit). |  
| 6 |  - |  Sistem membuat rekaman baru di tabel `payout_transactions` dengan rincian rekening tujuan, nominal kotor (*gross_amount*), potongan fee platform, nominal bersih (*net_amount*), dan status `pending`. |  
| 7 |  - |  Sistem mencatat aktivitas di `ActivityLogger` dan memicu `PlatformNotificationEvent` agar admin platform menerima notifikasi instan. |  
| 8 |  - |  Sistem mengembalikan tampilan riwayat payout dengan badge kuning "Menunggu Persetujuan". |  
  
   
***Alur Alternatif & Pengecualian***  
- **A1: Kreator Belum Mengatur Metode Pencairan Dana**  
  - **Reaksi Sistem**: Pada langkah 3, data `UserPayoutDetail` bernilai null. Sistem mengalihkan kreator ke formulir `/id/admin/payout/method` dengan peringatan: *"Silakan lengkapi data rekening atau e-wallet Anda terlebih dahulu."*  
- **A2: Saldo Terkunci Akibat Sengketa Aktif Pembeli**  
  - **Reaksi Sistem**: Jika terdapat pengaduan sengketa yang belum terselesaikan, nilai `frozenDisputeAmount` memotong nominal `currentBalance`. Jika saldo bersih menjadi di bawah batas minimal withdraw, tombol penarikan dinonaktifkan dengan informasi detail dana yang sedang dibekukan.  
  
---  
**UC-11: Memperbarui Email Akun dengan Verifikasi 2-Langkah (2-Step Email Change via OTP)**  
- **Aktor**: Kreator (Seller)  
- **Deskripsi Singkat**: Prosedur keamanan tinggi untuk memperbarui alamat email login kreator. Memerlukan verifikasi kode OTP 6 digit yang dikirimkan ke email lama, diikuti konfirmasi link aktivasi yang dikirimkan ke email baru.  
- **Precondition**: Kreator telah login dan masa cooldown 24 jam sejak permintaan terakhir telah terlewati.  
- **Postcondition**: Alamat email akun diperbarui, sesi diperbarui, dan tautan Google OAuth otomatis dilepas demi keamanan.  
***Alur Normal***  
  
|  |  |  |  
| - | - | - |  
| **No** |  **Aksi Aktor** |  **Reaksi Sistem** |  
| 1 |  Kreator membuka menu "Pengaturan Akun" tab "Ganti Email" (`/id/admin/account`). |  Sistem menampilkan form penggantian email dengan pemberitahuan keamanan. |  
| 2 |  Kreator memasukkan alamat email baru yang diinginkan dan menekan tombol "Kirim Kode OTP". |  Request diproses via `/admin/account/email/request-otp`. |  
| 3 |  - |  Sistem memverifikasi keunikan email baru, memeriksa batas cooldown 24 jam, dan mengenerate kode angka OTP 6 digit acak. |  
| 4 |  - |  Sistem menyimpan hash OTP ke tabel `email_change_otps` (kedaluwarsa 15 menit, batas percobaan 5 kali). |  
| 5 |  - |  Sistem mengirimkan email berisi kode OTP ke **alamat email lama** kreator melalui `EmailChangeOtpMail`. |  
| 6 |  - |  Sistem mengubah tampilan antarmuka ke Langkah 2 (Form Input OTP). |  
| 7 |  Kreator memeriksa inbox email lama, menyalin kode 6 digit, dan memasukkannya ke form web. |  Sistem menerima input via `/admin/account/email/verify-otp`. |  
| 8 |  - |  Sistem mencocokkan hash OTP dan memastikan kode belum kedaluwarsa. |  
| 9 |  - |  Setelah validasi OTP berhasil, sistem menyimpan draf di tabel `pending_email_changes` dengan token kriptografi acak 64 karakter. |  
| 10 |  - |  Sistem mengirim email verifikasi akhir ke **alamat email baru** kreator melalui `EmailChangeVerificationMail`. |  
| 11 |  Kreator membuka email baru dan mengklik tautan aktivasi `/id/admin/account/email/verify/{token}`. |  Sistem memvalidasi token dari tabel `pending_email_changes`. |  
| 12 |  - |  Sistem memperbarui kolom `email` di tabel `users` menjadi email baru. |  
| 13 |  - |  Jika akun sebelumnya terhubung dengan Google (`google_id != null`), sistem otomatis mencabut tautan Google untuk mencegah pengambilalihan akun tak sah. |  
| 14 |  - |  Sistem menghapus draf pending email dan menampilkan notifikasi sukses pembaruan email. |  
  
   
***Alur Alternatif & Pengecualian***  
- **A1: Kode OTP Salah Lebih dari 5 Kali**  
  - **Aksi Aktor**: Kreator salah memasukkan kode OTP berturut-turut.  
  - **Reaksi Sistem**: Pada kesalahan ke-5, sistem menghapus rekaman OTP dari tabel `email_change_otps`, membatalkan sesi penggantian email, dan memunculkan error: *"Anda telah melebihi batas percobaan kode OTP. Silakan ajukan ulang."*  
- **A2: Pengajuan Masih Dalam Masa Cooldown 24 Jam**  
  - **Reaksi Sistem**: Sistem mendeteksi `last_email_change_requested_at` kurang dari 24 jam lalu. Sistem menolak permohonan dengan pesan: *"Pergantian email hanya dapat diajukan satu kali dalam 24 jam demi keamanan akun."*  
  
---  
**UC-12: Mengajukan Banding Penangguhan Akun (Suspension Appeal Submission)**  
- **Aktor**: Kreator yang Ditangguhkan (Suspended Seller)  
- **Deskripsi Singkat**: Kreator yang akunnya disuspend oleh platform admin dapat mengirimkan klarifikasi/permohonan pembukaan blokir akun melalui halaman khusus banding.  
- **Precondition**: Akun pengguna memiliki status penangguhan aktif (`suspended_at != null`).  
- **Postcondition**: Formulir banding tersimpan di tabel `suspension_appeals` berstatus `pending` menunggu tinjauan admin platform.  
***Alur Normal***  
  
|  |  |  |  
| - | - | - |  
| **No** |  **Aksi Aktor** |  **Reaksi Sistem** |  
| 1 |  Kreator yang ditangguhkan mencoba mengakses dashboard seller. |  Middleware `CheckSuspended` mencegat request dan mengalihkan kreator ke tampilan khusus penangguhan akun. |  
| 2 |  - |  Sistem menampilkan alasan penangguhan (*suspend reason*), batas waktu (jika temporer), dan formulir pengajuan banding. |  
| 3 |  Kreator menuliskan argumen klarifikasi/alasan banding pada textarea formulir. |  Sistem menerima input teks. |  
| 4 |  Kreator menekan tombol "Kirim Permohonan Banding". |  Request dikirim via POST `/id/admin/appeal`. |  
| 5 |  - |  Sistem memastikan kreator belum memiliki permohonan banding berstatus `pending`. |  
| 6 |  - |  Sistem menyimpan data banding ke tabel `suspension_appeals` dengan status `pending`. |  
| 7 |  - |  Sistem mencatat log aktivitas dan menampilkan notifikasi bahwa banding telah diteruskan ke tim Admin Platform. |  
  
   
***Alur Alternatif & Pengecualian***  
- **A1: Kreator Telah Mengirim Banding Sebelumnya yang Belum Direspons**  
  - **Reaksi Sistem**: Sistem menemukan data di `suspension_appeals` untuk `user_id` tersebut yang masih berstatus `pending`. Sistem menyembunyikan formulir dan menampilkan pesan: *"Banding Anda sebelumnya sedang dalam proses peninjauan oleh tim Admin."*  
  
---  
**UC-13: Mengajukan & Merespons Tiket Bantuan (Support Ticket Interaction)**  
- **Aktor**: Kreator (Seller)  
- **Deskripsi Singkat**: Kreator membuat tiket bantuan untuk menanyakan kendala teknis atau transaksi kepada Admin Platform, serta melakukan percakapan dua arah di dalam tiket.  
- **Precondition**: Kreator login ke dashboard seller.  
- **Postcondition**: Tiket atau balasan baru tersimpan di tabel `support_tickets` dan `support_ticket_replies`.  
***Alur Normal***  
  
|  |  |  |  
| - | - | - |  
| **No** |  **Aksi Aktor** |  **Reaksi Sistem** |  
| 1 |  Kreator membuka menu "Pusat Bantuan / Tiket" (`/id/admin/tickets`). |  Sistem menampilkan daftar tiket riwayat bantuan milik kreator beserta statusnya (*open, answered, resolved, closed*). |  
| 2 |  Kreator mengklik "Buat Tiket Baru", mengisi subjek, kategori kendala, prioritas, pesan keluhan, serta mengunggah lampiran gambar. |  Sistem menerima input. |  
| 3 |  Kreator menekan tombol "Kirim Tiket". |  Sistem memvalidasi input via `SupportTicketController@store`. |  
| 4 |  - |  Sistem menyimpan tiket baru ke tabel `support_tickets` dengan kode tiket unik, status default `open`. |  
| 5 |  - |  Sistem mengirim email notifikasi ke tim platform via `SupportTicketCreatedMail`. |  
| 6 |  Kreator membuka halaman detail tiket `/id/admin/tickets/{id}`. |  Sistem menampilkan kronologi pesan percakapan tiket. |  
| 7 |  Kreator mengetik balasan tambahan dan menekan tombol "Kirim Balasan". |  Sistem menyimpan pesan ke `support_ticket_replies` dan memperbarui timestamp tiket. |  
  
   
  
---  
**C. MODUL ADMINISTRATOR PLATFORM (PLATFORM ADMIN)**  
  
---  
**UC-14: Kurasi & Verifikasi Produk Digital Baru (Product Verification)**  
- **Aktor**: Admin Platform (`role: admin_platform`)  
- **Deskripsi Singkat**: Admin meninjau kelayakan produk digital yang diunggah oleh para kreator untuk memastikan tidak melanggar ketentuan layanan (bebas konten ilegal, plagiarisme, atau malware) sebelum dapat dijual secara publik.  
- **Precondition**: Admin Platform terautentikasi dan berada di menu `/id/platform-admin/verifikasi`.  
- **Postcondition**: Status produk digital diperbarui (`approved` atau `rejected`).  
***Alur Normal***  
  
|  |  |  |  
| - | - | - |  
| **No** |  **Aksi Aktor** |  **Reaksi Sistem** |  
| 1 |  Admin Platform membuka menu "Verifikasi Produk". |  Sistem menampilkan daftar produk digital berstatus `pending` lengkap dengan filter pencarian dan pagination. |  
| 2 |  Admin memeriksa judul, kategori, deskripsi, harga, thumbnail, serta mengunduh sampel file produk digital. |  Sistem menyediakan link pratinjau file yang tersimpan di storage. |  
| 3 |  Admin menyatakan produk valid dan mengklik tombol "Setujui Produk" (*Approve*). |  Request dikirim via POST `/id/platform-admin/verifikasi/{id}` dengan rate limit 30 request/menit. |  
| 4 |  - |  Sistem memperbarui kolom status produk menjadi `approved` dan mengaktifkan visibilitas produk (`is_active = 1`). |  
| 5 |  - |  Sistem mencatat tindakan kurasi di tabel `activity_logs`. |  
| 6 |  - |  Sistem memicu event notifikasi realtime ke seller pemilik produk bahwa produknya telah lolos verifikasi. |  
  
   
***Alur Alternatif: Penolakan Produk*** (*Reject*)  
1. **Aksi Aktor**: Admin menganggap produk tidak memenuhi standar dan menekan tombol "Tolak Produk".  
2. **Reaksi Sistem**: Sistem menampilkan modal input alasan penolakan (*rejection reason*).  
3. **Aksi Aktor**: Admin mengetik alasan (misal: "File rusak atau melanggar hak cipta") dan menekan konfirmasi tolak.  
4. **Reaksi Sistem**: Sistem memperbarui status produk menjadi `rejected`, mencatat alasan di kolom penolakan, menonaktifkan produk dari microsite, serta mengirim notifikasi penjelasan ke seller bersangkutan.  
  
---  
**UC-15: Manajemen Penangguhan Pengguna & Peninjauan Banding (User Suspension & Appeal Review)**  
- **Aktor**: Admin Platform (`role: admin_platform`)  
- **Deskripsi Singkat**: Tindakan administratif darurat untuk menangguhkan akun seller yang terindikasi fraud/spam, serta meninjau surat permohonan banding yang diajukan oleh seller terkait.  
- **Precondition**: Admin login dengan hak akses `admin_platform`.  
- **Postcondition**: Pengguna disuspend atau diaktifkan kembali.  
***Alur Normal***  
  
|  |  |  |  
| - | - | - |  
| **No** |  **Aksi Aktor** |  **Reaksi Sistem** |  
| 1 |  Admin Platform membuka menu "Manajemen User" (`/id/platform-admin/users`). |  Sistem merender daftar seluruh pengguna platform dengan status akun mereka. |  
| 2 |  Admin memilih pengguna bermasalah dan mengklik tombol "Suspend User". |  Sistem memunculkan modal penangguhan akun. |  
| 3 |  Admin memilih durasi penangguhan (Permanen atau Sementara dengan batas tanggal) serta menuliskan alasan penangguhan wajib. |  Sistem menerima parameter input via `SuspendUserRequest`. |  
| 4 |  Admin menekan konfirmasi "Terapkan Penangguhan". |  Request diproses dengan rate limiting (maksimal 15 request/menit). |  
| 5 |  - |  Sistem mengisi kolom `suspended_at`, `suspended_until`, dan `suspend_reason` pada tabel `users`. |  
| 6 |  - |  Sistem secara otomatis menonaktifkan seluruh produk digital aktif milik seller tersebut. |  
| 7 |  - |  Sistem mencatat aktivitas kritis di tabel `activity_logs`. |  
| 8 |  - |  Jika pengguna sedang aktif berselancar, pada request berikutnya middleware `CheckSuspended` langsung memutus akses mereka. |  
  
   
***Alur Alternatif: Menyetujui Permohonan Banding Akun*** (*Approve Appeal*)  
1. **Aksi Aktor**: Admin membuka tab "Permohonan Banding" (`/id/platform-admin/users/appeals`).  
2. **Reaksi Sistem**: Sistem menyajikan daftar surat banding dari seller berstatus `pending`.  
3. **Aksi Aktor**: Admin membaca argumen klarifikasi seller dan menekan tombol "Terima Banding & Buka Suspend".  
4. **Reaksi Sistem**:  
  - Sistem memvalidasi via `ApproveAppealRequest`.  
  - Sistem mengubah status di tabel `suspension_appeals` menjadi `approved`.  
  - Sistem mereset kolom `suspended_at`, `suspended_until`, dan `suspend_reason` pada tabel `users` menjadi null.  
  - Sistem memulihkan akses seller dan mencatat aksi pembukaan blokir ke log audit.  
  
---  
**UC-16: Peninjauan & Persetujuan Penarikan Dana Seller (Payout Approval Management)**  
- **Aktor**: Admin Platform (`role: admin_platform`)  
- **Deskripsi Singkat**: Admin memvalidasi permohonan penarikan saldo seller, melakukan transfer manual atau via mutasi bank, dan mengonfirmasi persetujuan di sistem.  
- **Precondition**: Terdapat permohonan penarikan berstatus `pending` di tabel `payout_transactions`.  
- **Postcondition**: Status payout berubah menjadi `approved`/`completed` atau `rejected`, dan saldo seller diselaraskan secara akurat.  
***Alur Normal***  
  
|  |  |  |  
| - | - | - |  
| **No** |  **Aksi Aktor** |  **Reaksi Sistem** |  
| 1 |  Admin Platform membuka menu "Manajemen Payout" (`/id/platform-admin/payouts`). |  Sistem menampilkan antrean permohonan withdraw dengan detail nama seller, bank tujuan, nomor rekening, nominal kotor, komisi platform, dan nominal bersih yang harus ditransfer. |  
| 2 |  Admin memeriksa histori penjualan seller untuk memastikan tidak ada indikasi manipulasi transaksi. |  Sistem menyajikan riwayat pesanan seller terkait. |  
| 3 |  Admin melakukan transfer dana melalui sistem perbankan ke rekening seller. |  Admin menyelesaikan proses transfer eksternal. |  
| 4 |  Admin mengklik tombol "Setujui Payout" (*Approve*) dan memasukkan nomor referensi transfer bank. |  Request dikirim via POST `/id/platform-admin/payouts/{id}/approve` (rate limit 15/menit). |  
| 5 |  - |  Sistem membungkus eksekusi dalam `DB::transaction()` dengan `lockForUpdate()` pada baris payout. |  
| 6 |  - |  Sistem mengubah status payout menjadi `approved` / `completed` dan mencatat `platform_admin_id` yang memproses. |  
| 7 |  - |  Sistem mencatat komisi platform ke tabel agregasi pendapatan platform. |  
| 8 |  - |  Sistem mencatat log audit di `ActivityLogger` dan memicu notifikasi real-time ke seller bersangkutan. |  
  
   
***Alur Alternatif: Penolakan Payout*** (*Reject Payout*)  
1. **Aksi Aktor**: Admin menemukan rekening tidak valid atau ada dugaan pelanggaran, lalu mengklik "Tolak Payout".  
2. **Reaksi Sistem**: Sistem meminta alasan penolakan wajib.  
3. **Aksi Aktor**: Admin memasukkan alasan (misal: "Nomor rekening tidak terdaftar / nama tidak cocok") dan mengonfirmasi penolakan.  
4. **Reaksi Sistem**:  
  - Sistem mengubah status transaksi penarikan menjadi `rejected`.  
  - Dana yang sebelumnya berstatus `pendingGross` secara otomatis **dilepaskan kembali ke saldo aktif seller** pada kalkulasi `PayoutService::getPayoutOverview`.  
  - Sistem mengirimkan pemberitahuan alasan penolakan ke seller.  
  
---  
**UC-17: Investigasi & Resolusi Sengketa Pembeli (Dispute & Refund Resolution)**  
- **Aktor**: Admin Platform (`role: admin_platform`)  
- **Deskripsi Singkat**: Menangani perselisihan antara pembeli dan penjual terkait produk digital yang dilaporkan bermasalah.  
- **Precondition**: Terdapat tiket sengketa baru berstatus `pending` di menu sengketa platform.  
- **Postcondition**: Sengketa diputuskan dengan solusi *Refund* (pengembalian dana ke pembeli) atau *Reject* (dana diteruskan ke seller).  
***Alur Normal (Keputusan Refund Pembeli)***  
  
|  |  |  |  
| - | - | - |  
| **No** |  **Aksi Aktor** |  **Reaksi Sistem** |  
| 1 |  Admin membuka menu "Manajemen Sengketa" (`/id/platform-admin/disputes`). |  Sistem menyajikan daftar sengketa aktif. |  
| 2 |  Admin memilih sengketa dan mengklik "Mulai Investigasi" (*Start Review*). |  Sistem mengubah status sengketa menjadi `under_review`. |  
| 3 |  Admin memeriksa bukti screenshot, mendengarkan argumen seller dan pembeli, serta menguji file digital produk terkait. |  Sistem menyajikan seluruh berkas bukti yang diunggah. |  
| 4 |  Admin memutuskan klaim pembeli terbukti sah (produk rusak/tidak dapat diakses) dan menekan tombol "Proses Refund". |  Request dikirim via POST `/id/platform-admin/disputes/{id}/refund`. |  
| 5 |  - |  Sistem mengunci baris data transaksi dan sengketa via `lockForUpdate()`. |  
| 6 |  - |  Sistem mengubah status sengketa menjadi `refunded` dan status transaksi asal menjadi `refunded`. |  
| 7 |  - |  Sistem memotong saldo seller sesuai nilai transaksi yang di-refund sehingga dana sengketa yang tertahan resmi ditarik kembali dari seller. |  
| 8 |  - |  Sistem mencatat log aktivitas keputusan sengketa dan mengirim email resolusi ke pembeli dan seller. |  
  
   
***Alur Alternatif: Menolak Tuntutan Sengketa*** (*Reject Dispute*)  
1. **Aksi Aktor**: Admin menilai produk berfungsi dengan baik sesuai deskripsi dan klaim pembeli tidak beralasan, lalu mengklik "Tolak Sengketa".  
2. **Reaksi Sistem**: Sistem meminta input catatan keputusan (*resolution notes*).  
3. **Aksi Aktor**: Admin memasukkan catatan dan mengonfirmasi penolakan.  
4. **Reaksi Sistem**:  
  - Status sengketa diubah menjadi `rejected`.  
  - Dana transaksi yang sebelumnya dibekukan (*frozenDisputeAmount*) otomatis dicairkan kembali ke saldo penarikan seller.  
  
---  
**UC-18: Pengaturan Darurat Platform & Siaran Pengumuman (Emergency Switches & Broadcast)**  
- **Aktor**: Admin Platform (`role: admin_platform`)  
- **Deskripsi Singkat**: Mengontrol saklar darurat platform (mematikan checkout global untuk maintenance payment gateway, mengaktifkan mode pemeliharaan) serta menerbitkan banner siaran pengumuman (*broadcast announcement*) ke seluruh dashboard seller.  
- **Precondition**: Admin Platform terautentikasi dan berada di menu `/id/platform-admin/settings`.  
- **Postcondition**: Variabel sistem di tabel `platform_settings` diperbarui dan pesan siaran terkirim.  
***Alur Normal***  
  
|  |  |  |  
| - | - | - |  
| **No** |  **Aksi Aktor** |  **Reaksi Sistem** |  
| 1 |  Admin Platform membuka menu "Pengaturan Platform". |  Sistem menampilkan parameter komisi platform, batas minimum withdraw, saklar darurat checkout, dan daftar riwayat broadcast. |  
| 2 |  Admin mengaktifkan toggle switch "Disable Checkout Sementara" dan mengisi pesan darurat (misal: "Sedang ada pemeliharaan sistem pembayaran Midtrans"). |  Sistem menerima input via POST `/id/platform-admin/settings/emergency-switches`. |  
| 3 |  - |  Sistem memperbarui nilai kunci `disable_checkout` menjadi `1` pada tabel `platform_settings`. |  
| 4 |  - |  Seluruh permintaan checkout di sisi publik langsung diblokir dan menampilkan pesan pemeliharaan darurat tersebut. |  
| 5 |  Admin mengisi form pengumuman: Judul, Isi Pengumuman, Tipe Banner (*info, warning, danger, success*), dan mencentang opsi "Kirim juga via Email Massal". |  Sistem menerima form broadcast via `PlatformAdmin\SettingController@storeBroadcast`. |  
| 6 |  - |  Sistem membuat entitas baru di tabel `broadcast_announcements` berstatus `is_active = true`. |  
| 7 |  - |  Sistem mendispatch background queue job `DispatchBroadcastEmailsJob` untuk mengirimkan email secara chunking bertahap ke seluruh seller tanpa membebani server web. |  
| 8 |  - |  Banner pengumuman langsung terpampang di bagian atas dashboard seluruh seller yang sedang aktif. |  
  
   
  
---  
**UC-19: Audit Log Aktivitas & Jejak Forensik Sistem (System Activity & Transaction Auditing)**  
- **Aktor**: Admin Platform (`role: admin_platform`)  
- **Deskripsi Singkat**: Menelusuri seluruh rekaman jejak audit aktivitas sensitif pengguna, mutasi transaksi keuangan, dan log error sistem dengan filter canggih untuk kebutuhan akuntabilitas dan investigasi keamanan.  
- **Precondition**: Admin Platform login ke dashboard back-office.  
- **Postcondition**: Laporan aktivitas dapat ditinjau, difilter per tanggal/IP, dan diekspor ke berkas arsip.  
***Alur Normal***  
  
|  |  |  |  
| - | - | - |  
| **No** |  **Aksi Aktor** |  **Reaksi Sistem** |  
| 1 |  Admin Platform membuka menu "Audit Log" (`/id/platform-admin/logs/activity`). |  Sistem menyajikan tabel kronologi log dari tabel `activity_logs` dengan pagination efisien berindeks komposit. |  
| 2 |  Admin memanfaatkan kotak pencarian interaktif dengan fitur live autocomplete `/platform-admin/logs/activity/suggest`. |  Sistem merespons cepat daftar sugesti nama aksi atau email pengguna. |  
| 3 |  Admin memfilter berdasarkan rentang tanggal, jenis aksi (misal: `suspend_user`, `payout_approve`, `create_shortlink`), atau IP address tertentu. |  Sistem menyaring data secara presisi. |  
| 4 |  Admin mengklik tombol "Export Log Archive". |  Sistem mengekspor rekaman log terpilih ke format file arsip CSV/Excel untuk kebutuhan kepatuhan hukum (*compliance*). |  
  
   
  
---  
**D. MODUL SISTEM & GATEWAY EKSTERNAL**  
  
---  
**UC-20: Pemrosesan Otomatis Webhook Midtrans (Asynchronous Payment Callback & Fulfillment)**  
- **Aktor**: Midtrans Payment Gateway (Sistem Eksternal) & Linkan Backend Engine  
- **Deskripsi Singkat**: Menangani notifikasi HTTP POST webhook dari Midtrans saat status pembayaran berubah (misal dari pending menjadi settlement, expire, atau cancel). Memastikan mutasi saldo seller dan pengiriman produk terjadi secara otomatis, idempoten, dan bebas dari *race condition*.  
- **Precondition**: Transaksi sebelumnya telah diinisiasi dengan `order_id` yang valid.  
- **Postcondition**: Status transaksi di database menjadi `success` atau `failed`, saldo seller bertambah secara tepat, dan file produk terkirim ke email pembeli.  
***Alur Normal***  
  
|  |  |  |  
| - | - | - |  
| **No** |  **Aksi Aktor (Midtrans)** |  **Reaksi Sistem (Linkan Engine)** |  
| 1 |  Midtrans mengirimkan HTTP POST request ke endpoint `https://linkan.id/midtrans/callback`. |  Middleware Laravel melewati pengecekan token CSRF (khusus rute ini) dan menerapkan throttling 30 request/menit. |  
| 2 |  - |  `CheckoutService::handleCallback` mengambil nilai `order_id` dari payload JSON. |  
| 3 |  - |  Sistem melakukan verifikasi status independen dengan memanggil API server Midtrans `\Midtrans\Transaction::status($orderId)` menggunakan Server Key resmi. |  
| 4 |  - |  Sistem memulai transaksi database `DB::transaction()` dan mengunci baris data pesanan menggunakan `Transaction::where('order_id', $orderId)->lockForUpdate()->first()`. |  
| 5 |  - |  Sistem memeriksa apakah status transaksi saat ini sudah `success`. Jika sudah tercatat sukses sebelumnya, sistem segera menghentikan proses (*idempotency check*) untuk mencegah penambahan saldo berulang. |  
| 6 |  - |  Jika respon status dari Midtrans adalah `settlement` atau `capture`, sistem memperbarui kolom `status` menjadi `TransactionStatus::SUCCESS` dan menyimpan tipe metode pembayaran (`payment_method`). |  
| 7 |  - |  Sistem mengunci baris data seller di tabel `users` (`lockForUpdate()`) dan menambahkan saldo secara atomik: `increment('balance', total_price)`. |  
| 8 |  - |  Sistem mendispatch pengiriman email produk digital `SendDigitalProductMail` ke alamat email pembeli. |  
| 9 |  - |  Sistem mencatat seluruh payload dan tahapan proses ke dalam channel log khusus `payment.log`. |  
| 10 |  - |  Sistem mengembalikan response HTTP 200 OK dengan format JSON `{"status": 200, "message": "Callback processed"}` ke server Midtrans. |  
  
   
***Alur Alternatif & Pengecualian***  
- **A1: Pembayaran Dibatalkan / Kedaluwarsa (*Cancel / Expire / Deny*)**  
  - **Kondisi**: Status transaksi dari Midtrans bernilai `cancel`, `deny`, atau `expire`.  
  - **Reaksi Sistem**: Pada langkah 6, sistem mengubah status transaksi di database menjadi `TransactionStatus::FAILED`, tidak melakukan penambahan saldo seller, dan mencatat event kegagalan tersebut ke dalam `payment.log`.  
- **A2: Transaksi Tidak Ditemukan di Database**  
  - **Kondisi**: Midtrans mengirimkan notifikasi untuk `order_id` yang tidak tercatat di tabel `transactions`.  
  - **Reaksi Sistem**: Sistem mencatat error ke `payment.log` dan mengembalikan response HTTP 404: `{"status": 404, "message": "Transaction not found"}`.  
- **A3: Kegagalan Koneksi Verifikasi ke Server Midtrans**  
  - **Kondisi**: Terjadi kegagalan jaringan saat backend menghubungi API status Midtrans.  
  - **Reaksi Sistem**: Sistem melempar exception, mencatat pesan kesalahan ke `payment.log`, dan mengembalikan HTTP 400 sehingga Midtrans akan mencoba mengirimkan ulang notifikasi (*retry webhook delivery*) beberapa saat kemudian.  
  
---  
*Dokumen use case scenario ini dibuat secara otomatis dan komprehensif berdasarkan basis kode Linkan.ID.*  
