<!-- ROW 1: UBAH KATA SANDI (PASSWORD) -->
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
