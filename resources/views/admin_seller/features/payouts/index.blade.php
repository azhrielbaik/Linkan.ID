@extends("admin_seller.layouts.settings")

@section("page_title", __('admin.payout_title'))

@push("styles")
<link rel="stylesheet" href="{{ asset('css/settings-tailwind.css') }}?v={{ time() }}">
<style>
    /* Payout Specific Scoped Styles */
    .payout-card-border {
        border: 1px solid #E2E8F0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    }
    .payout-credit-card {
        background: linear-gradient(135deg, #F97316 0%, #EA580C 100%);
        box-shadow: 0 10px 15px -3px rgba(234, 88, 12, 0.4), 0 4px 6px -2px rgba(234, 88, 12, 0.2);
    }
    .glass-effect {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }
</style>
@endpush

@section("settings_content")
<div class="font-sans text-slate-800 pb-10">

    {{-- Header Action --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Payouts</h1>
            <p class="text-sm text-slate-500 mt-1">Manage your earnings, balance, and withdrawal methods.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-emerald-50 text-emerald-700 px-4 py-3 rounded-lg flex items-center gap-3 text-sm font-medium border border-emerald-200">
            <i class="fas fa-check-circle text-emerald-500 text-lg"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 text-red-700 px-4 py-3 rounded-lg flex items-center gap-3 text-sm font-medium border border-red-200">
            <i class="fas fa-exclamation-circle text-red-500 text-lg"></i> {{ session('error') }}
        </div>
    @endif

    @if(!empty($isPayoutFrozen))
        <div class="mb-6 bg-amber-50 text-amber-900 px-4 py-3.5 rounded-xl flex items-start gap-3 text-sm font-medium border border-amber-300 shadow-sm">
            <i class="fas fa-exclamation-triangle text-amber-600 text-lg mt-0.5 shrink-0"></i>
            <div>
                <strong class="font-bold text-amber-950 block mb-0.5">Pemberitahuan Sistem (Layanan Payout Sedang Ditangguhkan):</strong>
                <span>{{ $freezeMessage ?? 'Layanan penarikan dana sedang ditangguhkan sementara oleh sistem untuk audit berkala atau pemeliharaan jaringan perbankan. Anda tetap dapat mengumpulkan saldo dari penjualan produk.' }}</span>
            </div>
        </div>
    @endif

    @if(!empty($frozenDisputeAmount) && $frozenDisputeAmount > 0)
        <div class="mb-6 bg-red-50 text-red-900 px-4 py-3.5 rounded-xl flex items-start gap-3 text-sm font-medium border border-red-200 shadow-sm">
            <i class="fas fa-shield-halved text-red-600 text-lg mt-0.5 shrink-0"></i>
            <div>
                <strong class="font-bold text-red-950 block mb-0.5">Penahanan Dana Sementara (Sengketa Aktif):</strong>
                <span>Terdapat dana sebesar <strong>Rp {{ number_format($frozenDisputeAmount, 0, ',', '.') }}</strong> yang sedang dibekukan sementara oleh admin platform karena adanya pengaduan sengketa transaksi dari pembeli yang sedang dalam proses investigasi.</span>
            </div>
        </div>
    @endif

    {{-- Top Metric Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
        {{-- Total Earnings --}}
        <div class="bg-white rounded-xl p-5 payout-card-border flex flex-col justify-between">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-600">
                    <i class="fas fa-chart-line text-sm"></i>
                </div>
                <span class="text-sm font-semibold text-slate-600">Total Earnings</span>
            </div>
            <div>
                <div class="text-3xl font-bold text-slate-900">Rp {{ number_format($totalEarnings, 0, ',', '.') }}</div>
                <p class="text-xs text-slate-500 mt-1">All-time gross income</p>
            </div>
        </div>

        {{-- Available Balance --}}
        <div class="bg-white rounded-xl p-5 payout-card-border flex flex-col justify-between">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-600">
                        <i class="fas fa-wallet text-sm"></i>
                    </div>
                    <span class="text-sm font-semibold text-slate-600">Available balance</span>
                </div>
                <span class="text-xs font-medium bg-green-100 text-green-700 px-2 py-0.5 rounded-md">Ready</span>
            </div>
            <div>
                <div class="text-3xl font-bold text-slate-900">Rp {{ number_format($currentBalance, 0, ',', '.') }}</div>
                <p class="text-xs text-slate-500 mt-1">Updates daily · Withdrawable</p>
            </div>
        </div>

        {{-- Total Withdrawn --}}
        <div class="bg-white rounded-xl p-5 payout-card-border flex flex-col justify-between">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-600">
                    <i class="fas fa-arrow-up-right-from-square text-sm"></i>
                </div>
                <span class="text-sm font-semibold text-slate-600">Total Withdrawn</span>
            </div>
            <div>
                <div class="text-3xl font-bold text-slate-900">Rp {{ number_format($totalWithdrawn, 0, ',', '.') }}</div>
                <p class="text-xs text-slate-500 mt-1">Successfully paid out to you</p>
            </div>
        </div>
    </div>

    {{-- Main Grid Layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- Left Column: Payout History Table --}}
        <div class="lg:col-span-2 bg-white rounded-xl payout-card-border overflow-hidden">
            <div class="px-6 py-5 flex items-center justify-between" style="border-bottom: 1px solid #cbd5e1;">
                <h2 class="text-lg font-bold text-slate-900">Recent Payouts</h2>
                <a href="{{ route('admin.payout.history') }}" class="text-sm font-semibold text-[#ED842C] hover:text-[#d67322]">View All</a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-xs uppercase text-slate-500 font-semibold tracking-wider">
                            <th class="px-6 py-3">Date</th>
                            <th class="px-6 py-3">Payout ID</th>
                            <th class="px-6 py-3">Net Amount</th>
                            <th class="px-6 py-3">Method</th>
                            <th class="px-6 py-3 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($history as $item)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-slate-900">
                                {{ \Carbon\Carbon::parse($item->created_at)->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-500">
                                #{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-6 py-4 text-sm font-bold text-slate-900">
                                Rp {{ number_format($item->amount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-500">
                                {{ $item->method }}<br>
                                <span class="text-xs opacity-80">...{{ substr($item->account_number, -4) }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($item->status === 'completed' || $item->status === 'approved')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                        Paid
                                    </span>
                                @elseif($item->status === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                        Pending
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-sm text-slate-500">
                                No payouts found. Start selling to earn!
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 text-xs text-slate-500 bg-slate-50" style="border-top: 1px solid #cbd5e1;">
                Showing {{ $history->count() }} most recent payout(s)
            </div>
        </div>

        {{-- Right Column: Withdrawal & Account Details --}}
        <div class="flex flex-col gap-6">
            
            {{-- Withdraw Action Card --}}
            <div class="bg-white rounded-xl payout-card-border flex flex-col overflow-hidden">
                <div class="px-6 py-4 flex items-center justify-between" style="border-bottom: 2px solid #cbd5e1;">
                    <h3 class="text-base font-bold text-slate-900">Withdraw Funds</h3>
                    <i class="fas fa-ellipsis-v text-slate-600 cursor-pointer"></i>
                </div>
                
                <div class="p-6">
                    <div class="text-[36px] font-extrabold text-slate-900 tracking-tight leading-none mb-2">
                        Rp {{ number_format($currentBalance, 0, ',', '.') }}
                    </div>
                    <div class="text-[14px] text-slate-500 mb-6">
                        Available balance for withdrawal
                    </div>

                    <div class="pt-5 space-y-4 mb-5 text-[15px]" style="border-top: 1px solid #cbd5e1;">
                        <div class="flex justify-between items-center">
                            <span class="text-slate-500">Gross balance</span>
                            <span class="font-semibold text-slate-900">Rp {{ number_format($currentBalance, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-500">Platform fee (5%)</span>
                            <span class="font-semibold text-red-500">-Rp {{ number_format($currentBalance * 0.05, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="pt-5 mb-6 flex justify-between items-center" style="border-top: 1px solid #cbd5e1;">
                        <span class="text-[15px] font-bold text-slate-900">Net payout</span>
                        <span class="text-[15px] font-bold text-slate-900">Rp {{ number_format($currentBalance * 0.95, 0, ',', '.') }}</span>
                    </div>

                    @if(!empty($isPayoutFrozen))
                        <button type="button" disabled class="block w-full py-3.5 px-4 bg-slate-200 text-slate-400 font-bold text-center rounded-full cursor-not-allowed shadow-none select-none flex items-center justify-center gap-2" title="Layanan penarikan dana sedang ditangguhkan sementara">
                            <i class="fas fa-lock text-sm"></i> Penarikan Ditangguhkan
                        </button>
                    @else
                        <a href="{{ route('admin.payout.withdraw') }}" class="block w-full py-3.5 px-4 bg-[#ED842C] hover:bg-[#d67322] text-white font-bold text-center rounded-full transition-colors no-underline">
                            Withdraw Now
                        </a>
                    @endif
                </div>
            </div>

            {{-- Payout Account Card --}}
            <div class="bg-white rounded-xl p-6 payout-card-border">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-bold text-slate-900">Payout account</h3>
                    <a href="{{ route('admin.payout.method') }}" class="text-sm font-semibold text-[#ED842C] hover:text-[#d67322]">Change</a>
                </div>

                @if($payoutDetail)
                    <div class="payout-credit-card rounded-xl p-5 text-white relative overflow-hidden">
                        <!-- Decorative circles -->
                        <div class="absolute -top-10 -right-10 w-32 h-32 rounded-full bg-white opacity-10"></div>
                        <div class="absolute -bottom-10 -left-10 w-24 h-24 rounded-full bg-white opacity-10"></div>
                        
                        <div class="flex justify-between items-start mb-8 relative z-10">
                            {{-- Chip --}}
                            <div class="w-10 h-8 rounded bg-yellow-200/90 border border-yellow-300 opacity-80"></div>
                            
                            <span class="text-[10px] font-bold tracking-wider uppercase glass-effect px-2 py-1 rounded-md">
                                {{ $payoutDetail->method_type }}
                            </span>
                        </div>
                        
                        <div class="text-xl font-mono tracking-widest mb-6 relative z-10 drop-shadow-md">
                            **** **** **** {{ substr($payoutDetail->account_number, -4) }}
                        </div>
                        
                        <div class="flex justify-between items-end relative z-10">
                            <div>
                                <div class="text-[9px] uppercase tracking-wider opacity-80 mb-1">Account Holder</div>
                                <div class="text-sm font-bold truncate max-w-[140px]">{{ $payoutDetail->account_name }}</div>
                            </div>
                            <div class="text-right">
                                <div class="text-[9px] uppercase tracking-wider opacity-80 mb-1">
                                    @if($payoutDetail->method_type === 'Bank')
                                        Bank
                                    @else
                                        Provider
                                    @endif
                                </div>
                                <div class="text-sm font-bold">{{ $payoutDetail->bank_name ?? $payoutDetail->method_type }}</div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-slate-50 border border-dashed border-slate-300 rounded-xl p-6 text-center">
                        <div class="w-12 h-12 mx-auto bg-slate-200 rounded-full flex items-center justify-center text-slate-400 mb-3">
                            <i class="fas fa-plus"></i>
                        </div>
                        <h4 class="text-sm font-semibold text-slate-700 mb-1">No Account Added</h4>
                        <p class="text-xs text-slate-500 mb-4">Add a bank or e-wallet to withdraw funds.</p>
                        <a href="{{ route('admin.payout.method') }}" class="inline-flex px-4 py-2 bg-white border border-slate-300 rounded-lg text-xs font-semibold text-slate-700 hover:bg-slate-50">
                            Add Account
                        </a>
                    </div>
                @endif
            </div>
            
        </div>
    </div>
</div>
@endsection
