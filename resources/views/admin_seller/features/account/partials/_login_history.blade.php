<!-- ROW 5: RIWAYAT LOGIN (LOGIN ACTIVITY) -->
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
                        $props = is_array($log->properties) ? $log->properties : (is_string($log->properties) ? json_decode($log->properties, true) : []);
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
