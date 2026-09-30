<!-- ROW 4: SESI AKTIF (ACTIVE SESSIONS) -->
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
