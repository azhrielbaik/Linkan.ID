<!-- ROW 7: ZONA BAHAYA (DANGER ZONE) -->
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
                <div class="w-9 h-9 text-red-600 flex items-center justify-center text-sm shrink-0 mt-0.5">
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
