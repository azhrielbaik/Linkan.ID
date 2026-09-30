<!-- ROW 6: PREFERENSI NOTIFIKASI EMAIL -->
@php
    $notificationOptions = [
        [
            'name' => 'new_sales',
            'title' => 'Penjualan Baru',
            'description' => 'Dapatkan notifikasi email langsung setiap kali produk Anda dibeli pembeli.',
        ],
        [
            'name' => 'payout',
            'title' => 'Pembayaran & Withdrawal',
            'description' => 'Notifikasi ketika saldo penjualan masuk atau permintaan penarikan dana berhasil diproses.',
        ],
        [
            'name' => 'security_alerts',
            'title' => 'Pengingat Keamanan',
            'description' => 'Peringatan saat ada aktivitas login dari perangkat baru atau perubahan kata sandi.',
        ],
        [
            'name' => 'newsletter',
            'title' => 'Newsletter & Update Platform',
            'description' => 'Berita pembaruan fitur terbaru, panduan berjualan, dan promosi berkala.',
        ],
    ];
@endphp

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
                @foreach($notificationOptions as $option)
                    <label class="flex items-start gap-3 sm:gap-3.5 p-3 rounded-xl border border-slate-200/90 hover:border-slate-300 hover:bg-slate-50/50 transition cursor-pointer select-none">
                        <input 
                            type="checkbox" 
                            name="{{ $option['name'] }}" 
                            value="1" 
                            class="mt-0.5 w-4 h-4 text-[#ED842C] rounded border-slate-300 focus:ring-[#ED842C] focus:ring-offset-0 cursor-pointer shrink-0"
                            {{ !empty($notifPrefs[$option['name']]) ? 'checked' : '' }}
                        >
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-bold text-slate-900">{{ $option['title'] }}</p>
                            <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">{{ $option['description'] }}</p>
                        </div>
                    </label>
                @endforeach
            </div>

            <div class="mt-5">
                <button type="submit" class="w-full sm:w-auto justify-center px-5 py-2.5 bg-[#ED842C] hover:bg-[#d07323] text-white text-xs font-bold rounded-lg transition shadow-xs inline-flex items-center gap-2 cursor-pointer">
                    <i class="fas fa-save text-[11px]"></i> Simpan Preferensi
                </button>
            </div>
        </form>
    </div>
</div>
