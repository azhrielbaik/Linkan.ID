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

    <!-- Mobile Container -->
    <div class="w-full max-w-[400px] bg-white rounded-[32px] shadow-2xl overflow-hidden flex flex-col relative px-6 py-10">
        
        <!-- Icon & Confetti -->
        <div class="relative flex justify-center items-center mt-2 mb-6 w-full h-32">
            <!-- Confetti Particles -->
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <!-- Dots -->
                <div class="absolute top-4 left-12 w-2.5 h-2.5 rounded-full bg-pink-400"></div>
                <div class="absolute top-20 left-4 w-2 h-2 rounded-full bg-blue-600"></div>
                <div class="absolute top-12 right-12 w-1.5 h-1.5 rounded-full bg-gray-300"></div>
                <!-- Lines / Squiggles -->
                <div class="absolute top-16 left-16 w-4 h-1 bg-gray-300 rounded-full rotate-45"></div>
                <div class="absolute top-8 right-16 w-5 h-1.5 bg-gray-200 rounded-full -rotate-12"></div>
                <div class="absolute bottom-6 right-8 w-3 h-1 bg-pink-500 rounded-full -rotate-45"></div>
                <div class="absolute bottom-4 left-10 w-2 h-2 rounded-full bg-purple-400"></div>
            </div>

            <!-- Green Check Circle -->
            <div class="relative z-10 w-24 h-24 bg-[#34D399] rounded-full flex items-center justify-center shadow-[0_10px_20px_rgba(52,211,153,0.3)]">
                <i class="fa-solid fa-check text-white text-[40px]"></i>
            </div>
        </div>

        <!-- Typography -->
        <div class="text-center mb-8">
            <h1 class="text-[22px] font-bold text-gray-900 mb-2 tracking-tight">Payment Successful</h1>
            <p class="text-[14px] text-gray-500 font-medium leading-snug">
                Your order is confirmed!<br>
                Thanks for your patronage
            </p>
        </div>

        <!-- Payment Details Card -->
        <div class="w-full bg-[#F8F9FA] rounded-[24px] p-5 mb-8">
            <h3 class="text-[15px] font-bold text-gray-800 mb-4">Payment Details</h3>
            
            <div class="space-y-3.5">
                @if($transaction)
                <div class="flex justify-between items-center text-[13.5px]">
                    <span class="text-gray-400 font-medium">Transaction ID</span>
                    <span class="font-semibold text-gray-800">{{ $transaction->order_id }}</span>
                </div>
                <div class="flex justify-between items-center text-[13.5px]">
                    <span class="text-gray-400 font-medium">Date</span>
                    <span class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($transaction->created_at)->format('d M, Y') }}</span>
                </div>
                <div class="flex justify-between items-center text-[13.5px]">
                    <span class="text-gray-400 font-medium">Type of Transaction</span>
                    <span class="font-semibold text-gray-800">Online</span>
                </div>
                <div class="flex justify-between items-center text-[13.5px]">
                    <span class="text-gray-400 font-medium">Total</span>
                    <span class="font-bold text-gray-900">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center text-[13.5px]">
                    <span class="text-gray-400 font-medium">Status</span>
                    <span class="font-bold text-[#34D399] flex items-center gap-1.5 bg-green-50 px-2 py-0.5 rounded-md">
                        <i class="fa-solid fa-circle-check text-sm"></i> Success
                    </span>
                </div>
                @else
                <div class="flex justify-between items-center text-[13.5px]">
                    <span class="text-gray-400 font-medium">Transaction ID</span>
                    <span class="font-semibold text-gray-800">{{ request('order_id', '-') }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Buttons -->
        <div class="flex flex-col gap-3.5 mt-auto">
            <a href="{{ route('product.show', ['id' => $product->id]) }}" class="w-full bg-[#FFDCA8] hover:bg-[#FFC980] text-[#D97706] text-[16px] font-bold py-4 rounded-[20px] transition-colors text-center shadow-sm">
                Track Your Order
            </a>
            <a href="{{ $micrositeUrl }}" class="w-full bg-[#E58C17] hover:bg-[#C97914] text-white text-[16px] font-bold py-4 rounded-[20px] transition-colors text-center shadow-[0_8px_15px_rgba(229,140,23,0.3)]">
                Back to Home
            </a>
        </div>
    </div>

</body>
</html>
