@extends("admin_seller.layouts.settings")

@section("page_title", "Pengaturan Akun")

@section("settings_content")
<!-- Main Untitled UI Style Card Container -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

    <!-- Header Section -->
    <div class="p-4 sm:p-6 lg:p-8 pb-5 sm:pb-6 border-b border-slate-200">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight">Akun & Keamanan</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1 leading-relaxed">Kelola kredensial login, alamat email, sesi perangkat, dan preferensi akun Anda.</p>
            </div>
        </div>
    </div>

    <!-- Banner Info Card (Untitled UI Banner Style) -->
    <div class="p-4 sm:p-6 lg:p-8 pb-0">
        <div class="bg-slate-50/80 border border-slate-200/90 rounded-xl p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-start sm:items-center gap-3 sm:gap-3.5 min-w-0">
                <div class="w-10 h-10 rounded-xl bg-orange-100 text-[#ED842C] flex items-center justify-center text-lg shrink-0">
                    <i class="fas fa-shield-halved"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-2 flex-wrap">
                        <p class="text-sm font-bold text-slate-900">Akun Seller Linkan.ID</p>
                    </div>
                    <p class="text-xs text-slate-500 mt-0.5 break-words">
                        Terdaftar sejak {{ $user->created_at ? $user->created_at->translatedFormat('F Y') : '2024' }}
                    </p>
                </div>
            </div>
            <div class="flex items-center w-full sm:w-auto shrink-0 pt-2 sm:pt-0 border-t sm:border-t-0 border-slate-200/60">
                <a href="{{ route('admin.settings') }}" class="w-full sm:w-auto justify-center px-3.5 py-2 bg-white hover:bg-slate-100 border border-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition shadow-xs no-underline inline-flex items-center gap-1.5">
                    <i class="fas fa-user-pen text-slate-400 text-[11px]"></i> Edit Profil Publik
                </a>
            </div>
        </div>
    </div>

    <!-- Untitled UI 2-Column Horizontal Rows Container -->
    <div class="p-4 sm:p-6 lg:p-8 divide-y divide-slate-200">

        <!-- ========================================== -->
        <!-- ROW 1: UBAH KATA SANDI (PASSWORD)          -->
        <!-- ========================================== -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 sm:gap-6 lg:gap-8 py-6 sm:py-8 first:pt-4 sm:first:pt-6" id="password-section">
            <div class="lg:col-span-4">
                <h3 class="text-sm font-bold text-slate-900">Ubah Kata Sandi</h3>
                <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                    Perbarui kata sandi Anda secara berkala. Gunakan kombinasi yang kuat dan unik demi menjaga keamanan toko Anda.
                </p>
            </div>

            <div class="lg:col-span-8 max-w-2xl">
                <form action="{{ route('admin.account.password') }}" method="POST">
                    @csrf

                    <div class="space-y-4">
                        <!-- Current Password -->
                        <div>
                            <label for="current_password" class="block text-xs font-bold text-slate-700 mb-1.5">Password Saat Ini</label>
                            <div class="relative">
                                <input 
                                    type="password" 
                                    id="current_password" 
                                    name="current_password" 
                                    class="w-full px-3.5 py-2.5 pr-10 rounded-lg border @error('current_password') border-red-300 ring-1 ring-red-300 @else border-slate-200 @enderror focus:ring-2 focus:ring-[#ED842C] focus:border-[#ED842C] outline-none transition text-sm text-slate-800" 
                                    placeholder="Masukkan password saat ini" 
                                    required
                                >
                                <button 
                                    type="button" 
                                    onclick="togglePasswordVisibility('current_password', this)" 
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1 focus:outline-none transition cursor-pointer"
                                    aria-label="Toggle password visibility"
                                >
                                    <i class="fas fa-eye text-sm"></i>
                                </button>
                            </div>
                            @error('current_password')
                                <p class="text-xs text-red-500 mt-1.5 font-medium flex items-center gap-1">
                                    <i class="fas fa-circle-exclamation text-[11px]"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div>
                            <label for="password" class="block text-xs font-bold text-slate-700 mb-1.5">Password Baru</label>
                            <div class="relative">
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password" 
                                    class="w-full px-3.5 py-2.5 pr-10 rounded-lg border @error('password') border-red-300 ring-1 ring-red-300 @else border-slate-200 @enderror focus:ring-2 focus:ring-[#ED842C] focus:border-[#ED842C] outline-none transition text-sm text-slate-800" 
                                    placeholder="Minimal 8 karakter" 
                                    required
                                >
                                <button 
                                    type="button" 
                                    onclick="togglePasswordVisibility('password', this)" 
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1 focus:outline-none transition cursor-pointer"
                                    aria-label="Toggle password visibility"
                                >
                                    <i class="fas fa-eye text-sm"></i>
                                </button>
                            </div>
                            <p class="text-[11px] text-slate-400 mt-1">Gunakan kombinasi minimal 8 karakter dengan huruf, angka, dan simbol.</p>
                            @error('password')
                                <p class="text-xs text-red-500 mt-1.5 font-medium flex items-center gap-1">
                                    <i class="fas fa-circle-exclamation text-[11px]"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1.5">Konfirmasi Password Baru</label>
                            <div class="relative">
                                <input 
                                    type="password" 
                                    id="password_confirmation" 
                                    name="password_confirmation" 
                                    class="w-full px-3.5 py-2.5 pr-10 rounded-lg border border-slate-200 focus:ring-2 focus:ring-[#ED842C] focus:border-[#ED842C] outline-none transition text-sm text-slate-800" 
                                    placeholder="Ulangi password baru" 
                                    required
                                >
                                <button 
                                    type="button" 
                                    onclick="togglePasswordVisibility('password_confirmation', this)" 
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1 focus:outline-none transition cursor-pointer"
                                    aria-label="Toggle password visibility"
                                >
                                    <i class="fas fa-eye text-sm"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5">
                        <button type="submit" class="w-full sm:w-auto justify-center px-5 py-2.5 bg-[#ED842C] hover:bg-[#d07323] text-white text-xs font-bold rounded-lg transition shadow-xs inline-flex items-center gap-2 cursor-pointer">
                            <i class="fas fa-key text-[11px]"></i> Simpan Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- ROW 2: ALAMAT EMAIL & VERIFIKASI           -->
        <!-- ========================================== -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 sm:gap-6 lg:gap-8 py-6 sm:py-8">
            <div class="lg:col-span-4">
                <h3 class="text-sm font-bold text-slate-900">Alamat Email</h3>
                <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                    Alamat email utama digunakan untuk login, penerimaan konfirmasi pesanan pembeli, dan laporan penarikan saldo.
                </p>
            </div>

            <div class="lg:col-span-8 max-w-2xl">
                <!-- Current Email Display Box -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4 p-3.5 sm:p-4 rounded-xl border border-slate-200 bg-slate-50/60 mb-4">
                    <div class="flex items-center gap-3 sm:gap-3.5 min-w-0">
                        <div class="w-10 h-10 rounded-lg bg-orange-50 text-[#ED842C] flex items-center justify-center text-base shrink-0">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Email Utama</p>
                            <p class="text-sm font-bold text-slate-900 break-all sm:truncate mt-0.5">{{ $user->email }}</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between sm:justify-end gap-2 sm:gap-2.5 shrink-0 pt-2 sm:pt-0 border-t sm:border-t-0 border-slate-200/60">
                        @if($user->email_verified_at)
                            <span class="inline-flex items-center gap-1.5 px-2.5 sm:px-3 py-1 rounded-full text-[11px] sm:text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <i class="fas fa-check-circle text-emerald-600"></i> Terverifikasi
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 sm:px-3 py-1 rounded-full text-[11px] sm:text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                <i class="fas fa-exclamation-triangle text-amber-600"></i> Belum Verifikasi
                            </span>
                        @endif

                        <button 
                            type="button" 
                            onclick="toggleEmailChangeForm()" 
                            id="btn-toggle-email"
                            class="px-3 sm:px-3.5 py-1.5 border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold rounded-lg transition inline-flex items-center gap-1.5 cursor-pointer shadow-xs"
                        >
                            <i class="fas fa-pen text-slate-400 text-[11px]"></i> Ganti Email
                        </button>
                    </div>
                </div>

                <!-- Collapsible Request Email Change Form -->
                <div id="email-change-collapse" class="{{ (session('otp_sent') || session('otp_target_email') || $errors->has('otp') || $errors->has('new_email')) ? '' : 'hidden' }} transition-all duration-200 border border-slate-200 rounded-xl p-4 sm:p-5 bg-white shadow-xs">
                    @if($user->google_id)
                        <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 sm:p-3.5 mb-4 text-xs text-amber-900 flex items-start gap-2.5">
                            <i class="fas fa-exclamation-triangle text-amber-600 text-sm mt-0.5 shrink-0"></i>
                            <p class="leading-relaxed">
                                <span class="font-bold">Perhatian:</span> Akun Anda saat ini terhubung dengan Google. Jika Anda mengganti email, koneksi Google akan otomatis diputuskan dan Anda perlu menghubungkannya kembali di menu Layanan Terhubung.
                            </p>
                        </div>
                    @endif

                    @if(!empty($emailChangeCooldown))
                        <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 sm:p-3.5 mb-4 text-xs text-amber-900 flex items-start gap-2.5">
                            <i class="fas fa-exclamation-triangle text-amber-600 text-sm mt-0.5 shrink-0"></i>
                            <p class="leading-relaxed">
                                <span class="font-bold">Tidak dapat mengganti email saat ini.</span> Anda telah melakukan permintaan pergantian email dalam 24 jam terakhir. Silakan coba lagi nanti.
                            </p>
                        </div>
                    @endif

                    @if(!session('otp_sent') && !session('otp_target_email') && !$errors->has('otp'))
                        {{-- STEP 1: FORM INPUT EMAIL BARU --}}
                        <form action="{{ route('admin.account.email.request-otp') }}" method="POST" class="space-y-4">
                            @csrf

                            {{-- Info box --}}
                            <div class="rounded-xl border border-blue-200 bg-blue-50/80 p-3.5 sm:p-4">
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-blue-100 border border-blue-200 flex items-center justify-center shrink-0 mt-0.5 text-blue-600">
                                        <i class="fas fa-shield-alt text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-blue-900">Cara Kerja Verifikasi</p>
                                        <p class="text-xs text-blue-700 mt-0.5 leading-relaxed">
                                            Masukkan alamat email baru, lalu sistem akan mengirimkan <strong>kode OTP 6 digit</strong> ke email aktif Anda saat ini <strong>({{ Auth::user()->email }})</strong> sebagai verifikasi identitas.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="new_email" class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Email Baru</label>
                                <input 
                                    type="email" 
                                    id="new_email" 
                                    name="new_email" 
                                    value="{{ old('new_email') }}"
                                    @if(!empty($emailChangeCooldown)) disabled @endif
                                    class="w-full px-3.5 py-2.5 rounded-lg border @error('new_email') border-red-300 ring-1 ring-red-300 @else border-slate-200 @enderror focus:ring-2 focus:ring-[#ED842C] focus:border-[#ED842C] outline-none transition text-sm text-slate-800 disabled:bg-slate-100 disabled:text-slate-400 disabled:cursor-not-allowed" 
                                    placeholder="nama@emailbaru.com" 
                                    required
                                >
                                @error('new_email')
                                    <p class="text-xs text-red-500 mt-1.5 font-medium flex items-center gap-1">
                                        <i class="fas fa-circle-exclamation text-[11px]"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="pt-2 flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-2.5">
                                <button 
                                    type="submit" 
                                    @if(!empty($emailChangeCooldown)) disabled @endif
                                    class="w-full sm:w-auto justify-center px-5 py-2.5 text-white text-xs font-bold rounded-lg transition shadow-xs inline-flex items-center gap-2 {{ !empty($emailChangeCooldown) ? 'bg-slate-300 cursor-not-allowed opacity-75' : 'bg-[#ED842C] hover:bg-[#d07323] cursor-pointer' }}"
                                >
                                    <i class="fas fa-paper-plane text-[11px]"></i> Kirim Kode OTP ke Email Saya
                                </button>
                                <button 
                                    type="button" 
                                    onclick="toggleEmailChangeForm()" 
                                    class="w-full sm:w-auto justify-center px-4 py-2.5 border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold rounded-lg transition cursor-pointer text-center"
                                >
                                    Batal
                                </button>
                            </div>
                        </form>
                    @else
                        {{-- STEP 2: FORM INPUT OTP --}}
                        <form action="{{ route('admin.account.email.verify-otp') }}" method="POST" class="space-y-4">
                            @csrf

                            {{-- Info box konfirmasi OTP --}}
                            <div class="rounded-xl border border-emerald-200 bg-emerald-50/80 p-3.5 sm:p-4">
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-100 border border-emerald-200 flex items-center justify-center shrink-0 mt-0.5 text-emerald-600">
                                        <i class="fas fa-check-circle text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-emerald-900">Kode OTP Telah Dikirim!</p>
                                        <p class="text-xs text-emerald-700 mt-0.5 leading-relaxed">
                                            Periksa kotak masuk email aktif Anda <strong>({{ Auth::user()->email }})</strong>. Kode berlaku selama <strong>10 menit</strong>. Setelah OTP diverifikasi, email konfirmasi akan dikirim ke <strong>{{ session('otp_target_email') }}</strong>.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="otp" class="block text-xs font-bold text-slate-700 mb-1.5">Kode OTP (6 Digit)</label>
                                <input 
                                    type="text" 
                                    id="otp" 
                                    name="otp" 
                                    maxlength="6"
                                    inputmode="numeric"
                                    pattern="[0-9]{6}"
                                    autocomplete="one-time-code"
                                    class="w-full px-3.5 py-2.5 rounded-lg border text-center text-xl font-bold tracking-[0.5em] @error('otp') border-red-300 ring-1 ring-red-300 @else border-slate-200 @enderror focus:ring-2 focus:ring-[#ED842C] focus:border-[#ED842C] outline-none transition text-slate-800" 
                                    placeholder="000000"
                                    required
                                >
                                @error('otp')
                                    <p class="text-xs text-red-500 mt-1.5 font-medium flex items-center gap-1">
                                        <i class="fas fa-circle-exclamation text-[11px]"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="pt-2 flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-2.5">
                                <button 
                                    type="submit" 
                                    class="w-full sm:w-auto justify-center px-5 py-2.5 text-white text-xs font-bold rounded-lg transition shadow-xs inline-flex items-center gap-2 bg-[#ED842C] hover:bg-[#d07323] cursor-pointer"
                                >
                                    <i class="fas fa-check text-[11px]"></i> Verifikasi OTP & Kirim Konfirmasi Email
                                </button>
                                <button 
                                    type="button" 
                                    onclick="toggleEmailChangeForm()" 
                                    class="w-full sm:w-auto justify-center px-4 py-2.5 border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold rounded-lg transition cursor-pointer text-center"
                                >
                                    Batal
                                </button>
                            </div>
                        </form>

                        {{-- Link untuk minta OTP baru / kembali ke step 1 --}}
                        <div class="text-center pt-2">
                            <a href="{{ route('admin.account') }}" class="text-xs text-slate-500 hover:text-slate-700 underline font-medium">
                                Tidak menerima kode? Kembali dan minta ulang
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- ROW 3: LAYANAN TERHUBUNG (CONNECTED OAUTH) -->
        <!-- ========================================== -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 sm:gap-6 lg:gap-8 py-6 sm:py-8">
            <div class="lg:col-span-4">
                <h3 class="text-sm font-bold text-slate-900">Layanan Terhubung</h3>
                <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                    Tautkan akun pihak ketiga seperti Google untuk proses masuk yang cepat dan aman tanpa mengetikkan password.
                </p>
            </div>

            <div class="lg:col-span-8 max-w-2xl">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4 p-3.5 sm:p-4 rounded-xl border border-slate-200 bg-white shadow-xs">
                    <div class="flex items-center gap-3 sm:gap-3.5 min-w-0">
                        <div class="w-10 h-10 rounded-lg bg-slate-50 border border-slate-200/80 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.8-2.4 3.65v3.05h3.88c2.27-2.09 3.66-5.17 3.66-9.14z"/>
                                <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.1-6.72-4.93H1.26v3.15C3.27 21.36 7.35 24 12 24z"/>
                                <path fill="#FBBC05" d="M5.28 14.27c-.25-.72-.38-1.49-.38-2.27s.14-1.55.38-2.27V6.58H1.26C.46 8.16 0 9.94 0 12s.46 3.84 1.26 5.42l4.02-3.15z"/>
                                <path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.35 0 3.27 2.64 1.26 6.58l4.02 3.15c.95-2.83 3.6-4.98 6.72-4.98z"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <h4 class="text-sm font-bold text-slate-900">Google</h4>
                            <p class="text-xs text-slate-500 mt-0.5">Login akun Google</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between sm:justify-end gap-2.5 pt-2 sm:pt-0 border-t sm:border-t-0 border-slate-100">
                        @if($user->google_id)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <i class="fas fa-check-circle text-emerald-600"></i> Terhubung
                            </span>
                            <button 
                                type="button" 
                                onclick="showDisconnectGoogleModal()" 
                                class="px-3.5 py-1.5 border border-red-200 text-red-600 hover:bg-red-50 text-xs font-bold rounded-lg transition cursor-pointer"
                            >
                                Putuskan
                            </button>
                        @else
                            <div class="flex flex-col sm:items-end gap-1.5">
                                <div class="flex items-center gap-2.5">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                        Tidak Terhubung
                                    </span>
                                    <a 
                                        href="{{ route('google.connect') }}" 
                                        class="px-3.5 py-1.5 border border-[#ED842C] text-[#ED842C] hover:bg-[#ED842C] hover:text-white text-xs font-bold rounded-lg transition inline-flex items-center gap-1.5 cursor-pointer no-underline"
                                    >
                                        <i class="fab fa-google text-xs"></i> Hubungkan
                                    </a>
                                </div>
                                <span class="text-[11px] text-slate-500 font-normal">
                                    Hanya akun Google dengan email yang sama yang dapat dihubungkan.
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- ROW 4: SESI AKTIF (ACTIVE SESSIONS)        -->
        <!-- ========================================== -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 sm:gap-6 lg:gap-8 py-6 sm:py-8">
            <div class="lg:col-span-4">
                <h3 class="text-sm font-bold text-slate-900">Sesi Perangkat</h3>
                <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                    Daftar perangkat yang saat ini memiliki sesi login aktif ke akun toko Anda.
                </p>
            </div>

            <div class="lg:col-span-8 max-w-2xl">
                <div class="border border-slate-200 rounded-xl divide-y divide-slate-100 bg-white overflow-hidden shadow-xs">
                    @forelse($sessions as $session)
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-3.5 sm:p-4">
                            <div class="flex items-start sm:items-center gap-3 sm:gap-3.5 min-w-0">
                                <div class="w-10 h-10 rounded-lg bg-slate-50 border border-slate-200/80 flex items-center justify-center text-slate-600 shrink-0 text-sm mt-0.5 sm:mt-0">
                                    @if($session->device === 'mobile')
                                        <i class="fas fa-mobile-screen-button"></i>
                                    @elseif($session->device === 'tablet')
                                        <i class="fas fa-tablet-screen-button"></i>
                                    @else
                                        <i class="fas fa-laptop"></i>
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <h4 class="text-xs font-bold text-slate-900">{{ $session->browser }}</h4>
                                        @if($session->is_current)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Sesi Ini
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-[11px] text-slate-500 mt-0.5 break-words">
                                        IP: <span class="font-mono text-slate-700">{{ $session->ip_address }}</span> &bull; Aktif {{ $session->last_active_human }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center justify-end sm:shrink-0 pt-2 sm:pt-0 border-t sm:border-t-0 border-slate-100">
                                @if(!$session->is_current)
                                    <form action="{{ route('admin.account.session.revoke', $session->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengakhiri sesi di perangkat ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button 
                                            type="submit" 
                                            class="px-3 py-1 border border-red-200 text-red-600 hover:bg-red-50 text-xs font-semibold rounded-md transition cursor-pointer"
                                        >
                                            Akhiri Sesi
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="py-6 text-center text-slate-400 text-xs">
                            Tidak ada data sesi aktif yang tercatat.
                        </div>
                    @endforelse
                </div>

                @if(count($sessions) > 1)
                    <div class="mt-3.5">
                        <form action="{{ route('admin.account.sessions.revoke-all') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengeluarkan akun dari semua perangkat lain?')">
                            @csrf
                            @method('DELETE')
                            <button 
                                type="submit" 
                                class="w-full py-2.5 px-3.5 border border-red-200 text-red-600 hover:bg-red-50 text-xs font-semibold rounded-lg transition shadow-xs inline-flex items-center justify-center gap-1.5 cursor-pointer"
                            >
                                <i class="fas fa-arrow-right-from-bracket text-[11px]"></i> Akhiri Semua Sesi Lain
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        <!-- ========================================== -->
        <!-- ROW 5: RIWAYAT LOGIN (LOGIN ACTIVITY)      -->
        <!-- ========================================== -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 sm:gap-6 lg:gap-8 py-6 sm:py-8">
            <div class="lg:col-span-4">
                <h3 class="text-sm font-bold text-slate-900">Riwayat Login</h3>
                <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                    Catatan 5 aktivitas login terakhir pada akun Anda untuk memantau keamanan akses.
                </p>
            </div>

            <div class="lg:col-span-8 max-w-2xl">
                @if($loginHistory->isNotEmpty())
                    <div class="border border-slate-200 rounded-xl divide-y divide-slate-100 bg-white overflow-hidden shadow-xs">
                        @foreach($loginHistory as $log)
                            @php
                                $props = is_string($log->properties) ? json_decode($log->properties, true) : (array) $log->properties;
                                $loginType = $props['login_type'] ?? null;
                            @endphp
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 p-3 sm:p-3.5">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-8 h-8 rounded-lg bg-slate-50 text-slate-500 flex items-center justify-center text-xs shrink-0">
                                        <i class="fas fa-arrow-right-to-bracket"></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-bold text-slate-900">Login Berhasil</p>
                                        <p class="text-[11px] text-slate-500 mt-0.5 break-words">
                                            {{ \Carbon\Carbon::parse($log->created_at)->translatedFormat('d M Y, H:i') }} WIB
                                            @if($log->ip_address)
                                                &bull; IP: <span class="font-mono text-slate-600">{{ $log->ip_address }}</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center sm:shrink-0 pl-11 sm:pl-0">
                                    @if($loginType === 'google_oauth')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                            <i class="fab fa-google text-[10px]"></i> via Google
                                        </span>
                                    @elseif($loginType === 'email_password')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                            <i class="fas fa-envelope text-[10px]"></i> via Email
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                            Web Login
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-6 text-center text-slate-400 border border-slate-200 rounded-xl bg-slate-50/50">
                        <i class="fas fa-history text-xl mb-2 text-slate-400"></i>
                        <p class="text-xs font-medium">Belum ada riwayat login tercatat.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- ========================================== -->
        <!-- ROW 6: PREFERENSI NOTIFIKASI EMAIL         -->
        <!-- ========================================== -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 sm:gap-6 lg:gap-8 py-6 sm:py-8">
            <div class="lg:col-span-4">
                <h3 class="text-sm font-bold text-slate-900">Notifikasi Email</h3>
                <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                    Pilih jenis pemberitahuan otomatis yang ingin Anda terima di kotak masuk email Anda.
                </p>
            </div>

            <div class="lg:col-span-8 max-w-2xl">
                <form action="{{ route('admin.account.notifications') }}" method="POST">
                    @csrf

                    <!-- Untitled UI Checkbox List Pattern -->
                    <div class="space-y-3 sm:space-y-4">
                        <!-- Option 1: New Sales -->
                        <label class="flex items-start gap-3 sm:gap-3.5 p-3 rounded-xl border border-slate-200/90 hover:border-slate-300 hover:bg-slate-50/50 transition cursor-pointer select-none">
                            <input 
                                type="checkbox" 
                                name="new_sales" 
                                value="1" 
                                class="mt-0.5 w-4 h-4 text-[#ED842C] rounded border-slate-300 focus:ring-[#ED842C] focus:ring-offset-0 cursor-pointer shrink-0"
                                {{ !empty($notifPrefs['new_sales']) ? 'checked' : '' }}
                            >
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-bold text-slate-900">Penjualan Baru</p>
                                <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">Dapatkan notifikasi email langsung setiap kali produk Anda dibeli pembeli.</p>
                            </div>
                        </label>

                        <!-- Option 2: Payout -->
                        <label class="flex items-start gap-3 sm:gap-3.5 p-3 rounded-xl border border-slate-200/90 hover:border-slate-300 hover:bg-slate-50/50 transition cursor-pointer select-none">
                            <input 
                                type="checkbox" 
                                name="payout" 
                                value="1" 
                                class="mt-0.5 w-4 h-4 text-[#ED842C] rounded border-slate-300 focus:ring-[#ED842C] focus:ring-offset-0 cursor-pointer shrink-0"
                                {{ !empty($notifPrefs['payout']) ? 'checked' : '' }}
                            >
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-bold text-slate-900">Pembayaran & Withdrawal</p>
                                <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">Notifikasi ketika saldo penjualan masuk atau permintaan penarikan dana berhasil diproses.</p>
                            </div>
                        </label>

                        <!-- Option 3: Security Alerts -->
                        <label class="flex items-start gap-3 sm:gap-3.5 p-3 rounded-xl border border-slate-200/90 hover:border-slate-300 hover:bg-slate-50/50 transition cursor-pointer select-none">
                            <input 
                                type="checkbox" 
                                name="security_alerts" 
                                value="1" 
                                class="mt-0.5 w-4 h-4 text-[#ED842C] rounded border-slate-300 focus:ring-[#ED842C] focus:ring-offset-0 cursor-pointer shrink-0"
                                {{ !empty($notifPrefs['security_alerts']) ? 'checked' : '' }}
                            >
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-bold text-slate-900">Pengingat Keamanan</p>
                                <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">Peringatan saat ada aktivitas login dari perangkat baru atau perubahan kata sandi.</p>
                            </div>
                        </label>

                        <!-- Option 4: Newsletter -->
                        <label class="flex items-start gap-3 sm:gap-3.5 p-3 rounded-xl border border-slate-200/90 hover:border-slate-300 hover:bg-slate-50/50 transition cursor-pointer select-none">
                            <input 
                                type="checkbox" 
                                name="newsletter" 
                                value="1" 
                                class="mt-0.5 w-4 h-4 text-[#ED842C] rounded border-slate-300 focus:ring-[#ED842C] focus:ring-offset-0 cursor-pointer shrink-0"
                                {{ !empty($notifPrefs['newsletter']) ? 'checked' : '' }}
                            >
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-bold text-slate-900">Newsletter & Update Platform</p>
                                <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">Berita pembaruan fitur terbaru, panduan berjualan, dan promosi berkala.</p>
                            </div>
                        </label>
                    </div>

                    <div class="mt-5">
                        <button type="submit" class="w-full sm:w-auto justify-center px-5 py-2.5 bg-[#ED842C] hover:bg-[#d07323] text-white text-xs font-bold rounded-lg transition shadow-xs inline-flex items-center gap-2 cursor-pointer">
                            <i class="fas fa-save text-[11px]"></i> Simpan Preferensi
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- ROW 7: ZONA BAHAYA (DANGER ZONE)           -->
        <!-- ========================================== -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 sm:gap-6 lg:gap-8 py-6 sm:py-8">
            <div class="lg:col-span-4">
                <h3 class="text-sm font-bold text-red-600 flex items-center gap-1.5">
                    <i class="fas fa-triangle-exclamation text-xs"></i> Hapus Akun
                </h3>
                <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                    Tindakan ini tidak dapat dibatalkan dan akan menghapus akses akun seller Anda.
                </p>
            </div>

            <div class="lg:col-span-8 max-w-2xl">
                <div class="border border-red-200 bg-red-50/40 rounded-xl p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-start gap-3 min-w-0">
                        <div class="w-9 h-9 rounded-lg bg-red-100 text-red-600 flex items-center justify-center text-sm shrink-0 mt-0.5">
                            <i class="fas fa-user-slash"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h4 class="text-xs font-bold text-slate-900">Hapus Akun Anda</h4>
                            <p class="text-[11px] text-slate-500 mt-0.5 leading-relaxed">
                                Setelah akun dihapus, seluruh data biolink, produk digital, dan riwayat pesanan toko Anda akan dinonaktifkan secara permanen.
                            </p>
                        </div>
                    </div>
                    <div class="w-full sm:w-auto shrink-0 pt-2 sm:pt-0 border-t sm:border-t-0 border-red-200/50">
                        <button 
                            type="button" 
                            onclick="showDeletePopup()" 
                            class="w-full sm:w-auto justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg transition shadow-xs inline-flex items-center gap-1.5 cursor-pointer text-center"
                        >
                            <i class="fas fa-trash-alt text-[11px]"></i> Hapus Akun
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

<!-- ======================================================== -->
<!-- MODAL KONFIRMASI DISCONNECT GOOGLE                       -->
<!-- ======================================================== -->
<div id="disconnectGoogleModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-3 sm:p-4 backdrop-blur-sm transition-opacity duration-200" style="display: none;">
    <div class="bg-white rounded-2xl p-5 sm:p-8 max-w-md w-full shadow-2xl relative mx-3">
        <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto mb-4 text-xl">
            <i class="fab fa-google"></i>
        </div>
        
        <h3 class="text-base font-bold text-slate-900 text-center mb-2">Putuskan Akun Google?</h3>

        @if(empty($user->password))
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-3.5 mb-5 text-xs text-amber-900 leading-relaxed">
                <p class="font-bold mb-1">⚠️ Perhatian: Anda belum mengatur password akun.</p>
                <p>Karena Anda mendaftar melalui Google OAuth, Anda harus membuat kata sandi manual terlebih dahulu di menu <strong>Ubah Kata Sandi</strong> agar tidak kehilangan akses login ke akun Anda.</p>
            </div>
            <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2.5 pt-2">
                <button 
                    type="button" 
                    onclick="closeDisconnectGoogleModal()" 
                    class="w-full sm:w-auto px-4 py-2.5 border border-slate-200 text-slate-700 hover:bg-slate-50 text-xs font-semibold rounded-lg transition cursor-pointer text-center"
                >
                    Tutup
                </button>
                <a 
                    href="#password-section" 
                    onclick="closeDisconnectGoogleModal()" 
                    class="w-full sm:w-auto px-4 py-2.5 bg-[#ED842C] hover:bg-[#d07323] text-white text-xs font-bold rounded-lg transition shadow-xs inline-block no-underline text-center"
                >
                    Atur Password Dulu
                </a>
            </div>
        @else
            <p class="text-xs text-slate-500 text-center mb-5 leading-relaxed">
                Setelah koneksi Google diputuskan, Anda tidak bisa login menggunakan Google lagi. Anda dapat login menggunakan alamat email dan kata sandi yang telah Anda atur.
            </p>
            <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2.5 pt-2">
                <button 
                    type="button" 
                    onclick="closeDisconnectGoogleModal()" 
                    class="w-full sm:w-auto px-4 py-2.5 border border-slate-200 text-slate-700 hover:bg-slate-50 text-xs font-semibold rounded-lg transition cursor-pointer text-center"
                >
                    Batal
                </button>
                <form action="{{ route('admin.account.google.disconnect') }}" method="POST" class="inline w-full sm:w-auto">
                    @csrf
                    @method('DELETE')
                    <button 
                        type="submit" 
                        class="w-full px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg transition shadow-xs cursor-pointer text-center"
                    >
                        Ya, Putuskan
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL KONFIRMASI HAPUS AKUN (TAILWIND STYLED MODAL)      -->
<!-- ======================================================== -->
<div id="deleteConfirmationModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-3 sm:p-4 backdrop-blur-sm transition-opacity duration-200" style="display: none;">
    <div class="bg-white rounded-2xl p-5 sm:p-8 max-w-md w-full shadow-2xl relative mx-3">
        <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto mb-4 text-xl">
            <i class="fas fa-triangle-exclamation"></i>
        </div>
        
        <h3 class="text-base font-bold text-slate-900 text-center mb-2">Hapus Akun Anda?</h3>
        <p class="text-xs text-slate-500 text-center mb-5 leading-relaxed">
            Apakah Anda yakin ingin menghapus akun? Semua produk digital, tautan biolink, dan riwayat toko Anda akan dinonaktifkan. Tindakan ini tidak dapat dibatalkan.
        </p>
        
        <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2.5 pt-2">
            <button 
                type="button" 
                onclick="closeDeletePopup()" 
                class="w-full sm:w-auto px-4 py-2.5 border border-slate-200 text-slate-700 hover:bg-slate-50 text-xs font-semibold rounded-lg transition cursor-pointer text-center"
            >
                Batal
            </button>
            <form action="{{ route('admin.account.delete') }}" method="POST" class="inline w-full sm:w-auto">
                @csrf
                @method('DELETE')
                <button 
                    type="submit" 
                    class="w-full px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg transition shadow-xs cursor-pointer text-center"
                >
                    Ya, Hapus Akun
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push("scripts")
<script>
    function togglePasswordVisibility(fieldId, buttonElement) {
        const input = document.getElementById(fieldId);
        if (!input) return;

        const icon = buttonElement.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    function toggleEmailChangeForm() {
        const collapse = document.getElementById('email-change-collapse');
        const btn = document.getElementById('btn-toggle-email');
        if (!collapse) return;

        if (collapse.classList.contains('hidden')) {
            collapse.classList.remove('hidden');
            if (btn) {
                btn.innerHTML = '<i class="fas fa-times text-slate-400 text-[11px]"></i> Tutup';
            }
        } else {
            collapse.classList.add('hidden');
            if (btn) {
                btn.innerHTML = '<i class="fas fa-pen text-slate-400 text-[11px]"></i> Ganti Email';
            }
        }
    }

    function showDisconnectGoogleModal() {
        const modal = document.getElementById('disconnectGoogleModal');
        if (modal) {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
    }

    function closeDisconnectGoogleModal() {
        const modal = document.getElementById('disconnectGoogleModal');
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }
    }

    function showDeletePopup() {
        const modal = document.getElementById('deleteConfirmationModal');
        if (modal) {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
    }

    function closeDeletePopup() {
        const modal = document.getElementById('deleteConfirmationModal');
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }
    }

    // Close on backdrop overlay click
    window.addEventListener('click', function(event) {
        const deleteModal = document.getElementById('deleteConfirmationModal');
        const googleModal = document.getElementById('disconnectGoogleModal');
        if (event.target === deleteModal) {
            closeDeletePopup();
        }
        if (event.target === googleModal) {
            closeDisconnectGoogleModal();
        }
    });

    // Close on ESC key press
    window.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeDeletePopup();
            closeDisconnectGoogleModal();
        }
    });
</script>
@endpush
