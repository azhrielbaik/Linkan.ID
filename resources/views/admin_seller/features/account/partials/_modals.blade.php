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
