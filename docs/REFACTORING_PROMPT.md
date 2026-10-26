# Master Prompt: Refactoring & Clean Architecture Linkan.ID

Dokumen ini berisi panduan dan master prompt terstruktur untuk melakukan refactoring menyeluruh pada proyek **Linkan.ID** (Laravel 11, PHP 8.2). Prompt ini dirancang agar dapat langsung diberikan kepada AI coding assistant atau digunakan oleh tim pengembang untuk mengeksekusi refactoring bertahap tanpa merusak fungsionalitas yang ada (*zero regression*).

---

## 📋 Ringkasan Audit & Masalah Arsitektur Saat Ini

Sebelum memulai refactoring, berikut adalah hasil temuan teknis pada basis kode:

1. **Safety Net**: Terdapat **125 automated tests** di direktori `tests/` yang seluruhnya berstatus **PASSING (552 assertions)**. Setiap fase refactoring wajib mempertahankan status hijau ini.
2. **Inkonsistensi Namespace & God Controllers**:
   - `PlatformAdminController.php` (553 baris) berada di root `App\Http\Controllers` padahal ada subfolder `PlatformAdmin/`. Merangkap dashboard, CSV stream, laporan print, manajemen suspend, SSE stream, ganti password, avatar, dan sesi.
   - `AppearanceController.php` dan 7 controller elemen microsite berada di root, padahal seluruhnya adalah fitur seller (`AdminSeller`).
   - Kerancuan Penamaan: `DigitalProductController.php` di root sebenarnya adalah **Checkout & Payment Callback Controller**, sedangkan CRUD produk seller berada di `AdminSeller\DigitalProductController.php`.
   - `LoginController.php` (553 baris) menangani custom throttling, login multi-role, dan 3 alur Google OAuth (Login, Register, Connect) dengan transaksi DB inline.
   - `ShortlinkController.php` (466 baris) menggabungkan redirect publik, unlock password, CRUD seller, dan kalkulasi agregasi analitik tanpa Form Request maupun Service.
   - `ForgotPasswordController.php` (384 baris) menangani 4 langkah flow OTP, token hashing, cooldown, dan email langsung di controller.
3. **God Services vs Missing Services**:
   - `StatisticService.php` (**928 baris**) menggabungkan kalkulasi scorecard, chart timeline, status transaksi, audience breakdown (device/lokasi/referrer), dan insight engine.
   - `PlatformAdminService.php` (586 baris) mencampuradukkan metrik dashboard, generator data notifikasi SSE, streaming CSV, dan logika suspend user.
   - Tidak ada Service untuk Shortlink, Auth/OAuth, OTP/Password Reset, dan Microsite Elements.
4. **Duplikasi Rute & Fallback i18n (`routes/web.php`)**:
   - Berisi 441 baris dengan lebih dari 15 rute fallback manual (seperti `/login`, `/register`, `/checkout/{id}`) di luar grup `{locale}` yang hanya melakukan redirect.
   - Rute AJAX admin element didaftarkan ganda di luar dan di dalam grup locale.
5. **Inkonsistensi Data Microsite Elements & blocks_order**:
   - `VideoElementController` memperlakukan `Appearance::$blocks_order` sebagai **JSON Array** (`json_decode`), sedangkan `DigitalProductElementController` dan `Appearance.php` memperlakukannya sebagai **Comma Separated String** (`explode(',', ...)`).
   - `DigitalProductElementController` menduplikasi 200 baris logika pembuatan produk digital dan upload media yang sudah ada di `DigitalProductService`.
6. **Redundansi Kolom Model `DigitalProduct`**:
   - Terdapat kolom ganda untuk konsep yang sama: `platform_type` vs `deliverable_type`, serta `platform_file`/`platform_url` vs `deliverable_url`.
7. **Spaghetti JavaScript di View Blade**:
   - View besar (700–950 baris) seperti `header_profile.blade.php`, `dashboard/index.blade.php`, `product-detail.blade.php` memuat 300–500 baris kode JavaScript vanilla langsung di dalam tag `<script>`.
8. **Code Style & Linting**:
   - Laravel Pint mendeteksi 133 style issues di 243 file.

---

```markdown
# MASTER PROMPT: REFACTORING & CLEAN CODE LINKAN.ID (LARAVEL 11)

## Role & Objectives
Anda adalah Senior Software Architect dan Core Laravel Engineer. Tugas Anda adalah melakukan refactoring bertahap dan menyeluruh pada codebase Linkan.ID (Laravel 11, PHP 8.2) untuk mengeliminasi seluruh "spaghetti code", merapikan penulisan kode, serta menerapkan prinsip Clean Architecture, SOLID, dan PSR-12 tanpa mengubah fungsionalitas yang ada (zero regression).

### Critical Safety Guardrail
Proyek memiliki 125 automated tests di folder `tests/` yang saat ini 100% PASSING.
Pada setiap akhir fase pengerjaan, Anda WAJIB menjalankan:
```bash
php artisan test
```
Pastikan seluruh 125 test (552 assertions) tetap hijau sebelum melanjutkan ke fase berikutnya.

---

## FASE 1: Modularisasi Rute & Restrukturisasi Namespace Controller

### 1.1. Pemecahan File Rute (`routes/web.php`)
1. Buat direktori `routes/web/` dan pecah file `routes/web.php` (441 baris) menjadi 4 file rute modular:
   - `routes/web/auth.php`: Login, register, Google OAuth, password reset via OTP.
   - `routes/web/admin_seller.php`: Dashboard seller, appearance/microsite builder, digital products, orders, payouts, stats, shortlinks, dan support tickets.
   - `routes/web/platform_admin.php`: Dashboard platform admin, verifikasi produk, takedown/restore produk, payouts, users & appeals, logs/audit, settings, broadcast, dispute & refund, print, dan streaming CSV.
   - `routes/web/public.php`: Landing page, profil microsite publik (`/{username}`), checkout publik & payment callback, review token publik, dan form bantuan sengketa.
2. Satukan file-file tersebut di dalam `routes/web.php` menggunakan `require __DIR__ . '/web/...';`.
3. Bersihkan 15+ deklarasi fallback rute tanpa prefix locale (misal `Route::get('/login', ...)` yang hanya melakukan redirect ke `/$locale/login`). Gantikan dengan fallback route handler atau middleware lokal terpusat yang otomatis menambahkan prefix bahasa default jika tidak ada segmen `id` atau `en`.
4. Hapus rute AJAX admin element yang terduplikasi di luar grup `{locale}` setelah frontend menggunakan URL yang konsisten.

### 1.2. Re-organisasi Namespace Controller
1. **Platform Admin**:
   - Pindahkan `PlatformAdminController.php` dari `App\Http\Controllers\` ke subfolder `App\Http\Controllers\PlatformAdmin\`.
   - Pecah controller monolitik ini menjadi controller terfokus:
     * `DashboardController.php`: Halaman beranda, metrik ringkasan, dan endpoint SSE streaming notifikasi.
     * `UserManagementController.php`: Daftar user, suspend user, aktivasi user, dan manajemen permohonan banding (appeals).
     * `ReportExportController.php`: Export laporan CSV dan halaman cetak (print view).
     * `AdminProfileController.php`: Update profil, avatar, ganti kata sandi, dan manajemen sesi aktif.
2. **Admin Seller & Microsite**:
   - Pindahkan `AppearanceController.php` ke `App\Http\Controllers\AdminSeller\Microsite\AppearanceController.php`.
   - Pindahkan 7 controller element (`ImageElementController`, `TextElementController`, `VideoElementController`, `DividerElementController`, `SocialMediaElementController`, `DigitalProductElementController`, `ElementVisibilityController`) ke dalam namespace `App\Http\Controllers\AdminSeller\Microsite\`.
   - Pindahkan `SupportTicketController.php` ke `App\Http\Controllers\AdminSeller\SupportTicketController.php`.
3. **Renaming Controller Publik**:
   - Ubah nama `App\Http\Controllers\DigitalProductController.php` (yang menangani Snap Midtrans, keranjang, checkout, dan webhook) menjadi `App\Http\Controllers\CheckoutController.php` untuk mengakhiri kerancuan dengan `AdminSeller\DigitalProductController.php`.
   - Update nama controller di seluruh route dan test files.

*Verifikasi Fase 1*: Jalankan `php artisan test`. Pastikan seluruh 125 test berhasil.

---

## FASE 2: Dekomposisi God Controller & Ekstraksi Form Request

### 2.1. Ekstraksi Form Request Dedikasi
Gantikan seluruh validasi inline `$request->validate([...])` di controller dengan class Form Request terpisah di bawah `App\Http\Requests\`:
1. `Shortlink/StoreShortlinkRequest.php` & `Shortlink/UpdateShortlinkRequest.php` (memindahkan validasi panjang di `ShortlinkController`).
2. `Auth/LoginRequest.php` (memindahkan validasi di `LoginController`).
3. `Auth/PasswordResetOtpRequest.php`, `Auth/VerifyOtpRequest.php`, dan `Auth/ResetPasswordRequest.php` (memindahkan validasi 4 langkah di `ForgotPasswordController`).
4. `AdminSeller/PayoutWithdrawRequest.php` & `AdminSeller/SavePayoutMethodRequest.php`.
5. `PlatformAdmin/SuspendUserRequest.php` & `PlatformAdmin/RejectAppealRequest.php`.
6. `PublicDispute/StoreDisputeRequest.php`.

### 2.2. Dekomposisi Controller Otentikasi & Shortlink
1. **Google OAuth**:
   - Buat `App\Services\Auth\GoogleAuthService.php`.
   - Pindahkan logika penanganan Google Login, Register, dan Connect (termasuk pembuatan user, linking google_id, dan default provisioning appearance) dari `LoginController.php` ke `GoogleAuthService`.
   - Buat `App\Http\Controllers\Auth\GoogleAuthController.php` khusus untuk menangani endpoint callback OAuth.
2. **Password Reset OTP**:
   - Buat `App\Services\Auth\PasswordResetOtpService.php`.
   - Pindahkan logika pembuatan token, hashing OTP, cooldown timer, pengecekan kedaluwarsa, dan update password dari `ForgotPasswordController.php` ke service ini. Controller hanya bertugas menerima HTTP request dan mengembalikan view/redirect.
3. **Pemisahan Shortlink Controller**:
   - Pisahkan `ShortlinkController.php`:
     * `App\Http\Controllers\PublicShortlinkController.php`: Menangani redirect publik `/{slug}`, form proteksi password, dan verifikasi password.
     * `App\Http\Controllers\AdminSeller\ShortlinkController.php`: Menangani CRUD shortlink dan halaman analitik seller.
   - Buat `App\Services\ShortlinkService.php` untuk menangani agregasi analitik, pencatatan klik, deteksi referer/device, dan pembatasan akses.

*Verifikasi Fase 2*: Jalankan `php artisan test`. Pastikan seluruh 125 test berhasil.

---

## FASE 3: Dekomposisi God Service & Penerapan Single Responsibility (SRP)

### 3.1. Pemecahan `StatisticService.php` (928 Baris)
Pecah class monolitik ini menjadi sub-services di bawah namespace `App\Services\AdminSeller\Statistics\`:
1. `DateRangeResolver.php`: Menangani parsing range waktu preset (`today`, `yesterday`, `this_week`, `7days`, `month`, `last_month`, `90days`, `this_year`, `all_time`) beserta rentang pembandingnya.
2. `ScorecardMetricService.php`: Menghitung agregasi omset, laba, pesanan, dan klik shortlink beserta delta perbandingan periode sebelumnya.
3. `TimelineChartService.php`: Membangun payload data grafik garis waktu (harian/mingguan) dan penanganan perbedaan SQL dialect (MySQL vs SQLite).
4. `AudienceAnalyticsService.php`: Agregasi data breakdown perangkat, browser, lokasi geografis, dan sumber referensi.
5. `PerformanceRankingService.php`: Agregasi produk terlaris dan shortlink terpopuler.
6. `SmartInsightEngine.php`: Evaluasi logika rekomendasi performa berbasis data metrik yang dihasilkan.
7. Refactor `StatisticService.php` utama menjadi Facade/Orchestrator ramping yang memanggil sub-services di atas sehingga kompatibilitas terhadap controller/view yang memanggilnya tidak terganggu.

### 3.2. Pemecahan `PlatformAdminService.php` (586 Baris)
Pecah menjadi sub-services di bawah `App\Services\PlatformAdmin\`:
1. `PlatformDashboardService.php`: Metrik total user, omset platform, chart pendapatan, dan ranking seller.
2. `PlatformNotificationService.php`: Agregasi notifikasi seller/produk/payout dan SSE event streaming.
3. `UserSuspensionService.php`: Logika eksekusi penangguhan akun, aktivasi, dan pemrosesan banding.
4. `CommissionExportService.php`: Generator data dan stream download CSV komisi platform.

*Verifikasi Fase 3*: Jalankan `php artisan test`. Pastikan seluruh 125 test berhasil.

---

## FASE 4: Standardisasi Microsite Element & Unifikasi `blocks_order`

### 4.1. Unifikasi Struktur Data `blocks_order`
1. Pastikan model `Appearance` memiliki cast:
   ```php
   protected $casts = [
       'blocks_order' => 'array',
   ];
   ```
2. Buat accessor dan mutator yang toleran terhadap format string lama (JSON string atau comma-separated string) agar data historis tetap aman:
   ```php
   public function getBlocksOrderAttribute($value): array {
       if (empty($value)) return [];
       if (is_array($value)) return $value;
       $decoded = json_decode($value, true);
       if (is_array($decoded)) return $decoded;
       return array_filter(array_map('trim', explode(',', $value)));
   }
   ```
3. Tambahkan method helper pada model `Appearance` atau `AppearanceService`:
   - `addBlock(string $blockIdentifier): void`
   - `removeBlock(string $blockIdentifier): void`
   - `reorderBlocks(array $newOrder): void`

### 4.2. Konsolidasi Element Controllers & Eliminasi Duplikasi Kode
1. Buat `App\Services\AdminSeller\MicrositeElementService.php` untuk menangani logika bersama (lookup by ID & user, reordering, update status aktif, dan pembersihan block saat dihapus).
2. Refactor `DigitalProductElementController`:
   - Hapus duplikasi 200 baris logika pembuatan produk digital dan upload file.
   - Panggil langsung `DigitalProductService::storeProduct()` dan `DigitalProductService::updateProduct()`.
   - Gunakan `Appearance::addBlock("digitalproduct_{$id}")` untuk menyematkan ke microsite.

*Verifikasi Fase 4*: Jalankan `php artisan test`. Pastikan seluruh 125 test berhasil.

---

## FASE 5: Konsolidasi Skema Model & Relasi Eloquent

### 5.1. Konsolidasi Model `DigitalProduct`
1. Tangani redundansi kolom:
   - `platform_type` vs `deliverable_type`
   - `platform_file` vs `deliverable_url`
2. Buat mutator/accessor yang menyelaraskan kedua field ini sehingga penulisan di controller/service manapun akan memperbarui field kanonikal secara konsisten.
3. Hapus redundansi pemanggilan `resolveImageUrl` dengan memanfaatkan accessor Eloquent yang bersih.

### 5.2. Lengkapi Relasi Eloquent pada Model `User`
Tambahkan relasi resmi pada `App\Models\User.php` yang sebelumnya hilang:
```php
public function payoutTransactions(): HasMany {
    return $this->hasMany(PayoutTransaction::class);
}

public function payoutDetail(): HasOne {
    return $this->hasOne(UserPayoutDetail::class);
}

public function supportTickets(): HasMany {
    return $this->hasMany(SupportTicket::class);
}

public function disputes(): HasMany {
    return $this->hasMany(Dispute::class, 'seller_id');
}

public function activityLogs(): HasMany {
    return $this->hasMany(ActivityLog::class);
}
```
Gantikan query `DB::table(...)` manual yang tersebar di service/controller dengan pemanggilan relasi Eloquent ini.

*Verifikasi Fase 5*: Jalankan `php artisan test`. Pastikan seluruh 125 test berhasil.

---

## FASE 6: Pembersihan View & Ekstraksi Inline JavaScript

### 6.1. Dekomposisi Blade View Monolitik
Pecah file view yang melebihi 500 baris menjadi sub-komponen terpisah di bawah direktori `partials/` atau `components/`:
1. `platformadmin/partials/header_profile.blade.php` (780 baris) -> pecah dropdown notifikasi, modal ganti password, dan modal profil ke file komponen terpisah.
2. `admin_seller/features/dashboard/index.blade.php` (758 baris) -> pisahkan scorecard, tabel transaksi terbaru, dan modal ajukan banding.
3. `public/product-detail.blade.php` (903 baris) -> pisahkan modal checkout Snap, slider gambar produk, dan tab ulasan pembeli.
4. `public/dispute_form.blade.php` (942 baris) -> pisahkan step verifikasi order ID dan form upload bukti dispute.

### 6.2. Ekstraksi Script JavaScript
1. Pindahkan ratusan baris kode JavaScript vanilla dan inisialisasi Chart.js dari tag `<script>` Blade ke file JS terpisah di bawah `resources/js/modules/`:
   - `resources/js/modules/notifications-sse.js` (logika koneksi SSE dan update badge realtime)
   - `resources/js/modules/seller-analytics.js` (inisialisasi dan render Chart.js statistik)
   - `resources/js/modules/checkout-snap.js` (integrasi Snap Midtrans popup dan penanganan response)
2. Masukkan script tersebut ke dalam pipeline build Vite atau muat secara modular.
3. Gunakan data-attribute HTML5 (`data-url="..."`, `data-csrf="..."`) pada elemen DOM untuk mengirim variabel dinamis dari server ke JavaScript; hindari tag Blade di tengah-tengah file script.

*Verifikasi Fase 6*: Jalankan `php artisan test`. Pastikan seluruh 125 test berhasil.

---

## FASE 7: Format & Linting Standar (Laravel Pint)

1. Jalankan Laravel Pint untuk memperbaiki seluruh 133 style issues:
   ```bash
   ./vendor/bin/pint
   ```
2. Pastikan file kode mematuhi standar PSR-12, strict typing (parameter types & return types pada semua method baru), dan penamaan yang konsisten.
3. Jalankan kembali test suite:
   ```bash
   php artisan test
   ```

---

## Kriteria Selesai (Definition of Done)
1. **Tidak ada Spaghetti Code**: Tidak ada controller berukuran > 250 baris dan tidak ada service berukuran > 350 baris.
2. **Namespace Bersih**: Tidak ada controller bisnis yang diletakkan sembarangan di root `App\Http\Controllers`.
3. **Validasi Terisolasi**: Semua aksi mutasi data divalidasi melalui Form Request.
4. **Data Konsisten**: Kolom `blocks_order` memiliki penanganan terstandardisasi dan model `DigitalProduct` konsisten.
5. **Zero Regression**: Seluruh 125 test suite tetap **PASSING 100%**.
```
