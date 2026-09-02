@php
    $micrositeUrl = $product->user ? route('public.profile', ['username' => $product->user->username]) : url('/');
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil - {{ $product->title }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center justify-center p-4 sm:p-6">

    <div class="w-full max-w-[420px] bg-white rounded-[32px] shadow-xl overflow-hidden flex flex-col relative pb-8">
        
        <!-- Header Green Background -->
        <div class="bg-green-500 h-32 w-full absolute top-0 left-0 z-0 rounded-b-[40px]"></div>

        <div class="relative z-10 flex flex-col items-center pt-8 px-6 text-center">
            <!-- Icon Check -->
            <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-lg border-4 border-green-500 text-green-500 text-3xl mb-4">
                <i class="fa-solid fa-check"></i>
            </div>

            <h2 class="text-[20px] font-bold text-gray-900 mb-1">Terima Kasih Atas Pesanan Anda!</h2>
            <p class="text-[13px] text-gray-500 mb-2">{{ \Carbon\Carbon::now()->translatedFormat('l, d M Y, H:i') }}</p>
            <p class="text-[14px] text-gray-600 mb-6">Tautan produk digital telah dikirimkan ke email Anda. Silakan cek inbox atau folder spam Anda.</p>
        </div>

        <!-- Product Info Card -->
        <div class="mx-6 bg-gray-50 rounded-2xl p-4 border border-gray-100 mb-6 z-10 relative">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-white flex items-center justify-center overflow-hidden shrink-0 border border-gray-100 shadow-sm">
                    @php
                        $imagePath = $product->image;
                        if ($imagePath && !file_exists(storage_path('app/public/' . $imagePath))) {
                            if (!empty($product->media_files) && is_array($product->media_files) && isset($product->media_files[0]['path'])) {
                                $imagePath = $product->media_files[0]['path'];
                            } else {
                                $imagePath = str_replace('product_images/', 'digital_products/media/', $imagePath);
                            }
                        }
                    @endphp
                    @if($imagePath)
                        <img src="{{ asset('storage/' . $imagePath) }}" class="w-full h-full object-cover">
                    @else
                        <i class="fa-solid fa-box text-gray-300 text-lg"></i>
                    @endif
                </div>
                <div class="text-left flex-1">
                    <h4 class="font-semibold text-gray-900 text-[14px] leading-tight line-clamp-1">{{ $product->title }}</h4>
                    <p class="text-[12px] text-gray-500 mt-0.5">Penjual: {{ $product->user->name ?? 'Seller' }}</p>
                </div>
            </div>
        </div>

        <!-- Transaction Details -->
        <div class="px-6 mb-8 relative z-10">
            <h3 class="text-[14px] font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Detail Transaksi</h3>
            
            <div class="space-y-3">
                @if($transaction)
                <div class="flex justify-between items-center text-[13px]">
                    <span class="text-gray-500">ID Pesanan</span>
                    <span class="font-medium text-gray-800">{{ $transaction->order_id }}</span>
                </div>
                <div class="flex justify-between items-center text-[13px]">
                    <span class="text-gray-500">Email Pembeli</span>
                    <span class="font-medium text-gray-800">{{ $transaction->buyer_email }}</span>
                </div>
                <div class="flex justify-between items-center text-[13px]">
                    <span class="text-gray-500">Jumlah</span>
                    <span class="font-medium text-gray-800">{{ $transaction->qty }}x</span>
                </div>
                <div class="flex justify-between items-center text-[13px] mt-4 pt-3 border-t border-gray-100 border-dashed">
                    <span class="text-gray-500 font-medium">Total Pembayaran</span>
                    <span class="font-bold text-green-600 text-[16px]">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</span>
                </div>
                @else
                <div class="flex justify-between items-center text-[13px]">
                    <span class="text-gray-500">ID Pesanan</span>
                    <span class="font-medium text-gray-800">{{ request('order_id', '-') }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Buttons -->
        <div class="px-6 flex flex-col gap-3 relative z-10">
            <a href="{{ $micrositeUrl }}" class="w-full bg-[#ED842C] hover:bg-orange-600 text-white text-[15px] font-semibold py-3.5 rounded-xl transition-colors shadow-lg shadow-orange-900/20 active:scale-[0.98] text-center flex items-center justify-center gap-2">
                <i class="fa-solid fa-house text-sm"></i> Kembali ke Beranda
            </a>
            <a href="{{ route('product.show', ['id' => $product->id]) }}" class="w-full bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-[15px] font-semibold py-3.5 rounded-xl transition-colors active:scale-[0.98] text-center">
                Lihat Detail Produk
            </a>
        </div>
    </div>

</body>
</html>
