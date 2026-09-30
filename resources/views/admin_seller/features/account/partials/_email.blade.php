<!-- ROW 2: ALAMAT EMAIL & VERIFIKASI -->
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
                <div class="w-10 h-10 flex items-center justify-center text-base shrink-0">
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
