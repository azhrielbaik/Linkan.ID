<!-- ROW 3: LAYANAN TERHUBUNG (CONNECTED OAUTH) -->
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
