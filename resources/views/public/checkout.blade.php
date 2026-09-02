<!DOCTYPE html>
@php
    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Str;
    $savedQty = session("cart.qty.{$product->id}", 1); // default 1 jika tidak ada di session
    $totalAmount = ($itemPrice ?? $product->price) * $savedQty;
@endphp
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
    <title>Checkout - {{ $product->title }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-LDeBzOGR_X-yS9q1"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center sm:p-6">

<div class="w-full max-w-[420px] bg-white min-h-screen sm:min-h-[auto] sm:rounded-[32px] sm:shadow-2xl overflow-hidden flex flex-col relative">
    
    <!-- Header -->
    <div class="flex items-center justify-between px-6 py-5">
        <a href="{{ url()->previous() }}" class="text-gray-400 hover:text-gray-700 transition">
            <i class="fa-solid fa-chevron-left text-lg"></i>
        </a>
        <h1 class="text-gray-800 font-semibold text-[15px]">Checkout</h1>
        <div class="w-5"></div> <!-- Spacer for center alignment -->
    </div>

    <!-- Content -->
    <div class="px-6 pb-8 flex-1 flex flex-col">
        
        <!-- Amount -->
        <div class="flex justify-between items-start mb-8">
            <div>
                <p class="text-[13px] text-gray-500 font-medium mb-1">Amount</p>
                <h2 class="text-[32px] font-bold text-gray-900 tracking-tight">Rp {{ number_format($totalAmount, 0, ',', '.') }}</h2>
            </div>
            <div class="flex flex-col items-end">
                <p class="text-[12px] text-gray-400 font-medium mb-2">Bill ID #{{ strtoupper(Str::random(8)) }}</p>
                <span class="inline-block px-3 py-1 text-red-500 text-[10px] font-bold rounded-md uppercase tracking-wider">UNPAID</span>
            </div>
        </div>

        <!-- Item Card -->
        <div class="mb-8">
            <h3 class="text-[14px] font-semibold text-gray-800 mb-3">Item</h3>
            <div class="border border-gray-100 rounded-[20px] p-5 shadow-sm">
                <!-- Header Card -->
                <div class="flex items-center gap-4 pb-4 border-b border-gray-100 border-dashed">
                    <div class="w-20 h-20 rounded-2xl bg-gray-50 flex items-center justify-center overflow-hidden shrink-0 border border-gray-100 shadow-sm">
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
                            <i class="fa-solid fa-box text-gray-300 text-2xl"></i>
                        @endif
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-900 text-[16px] leading-tight mb-1">{{ $product->title }}</h4>
                        <p class="text-[13px] text-gray-500 flex items-center gap-1">
                            <i class="fa-regular fa-clock text-gray-400"></i> {{ now()->format('d M, H:i') }}
                        </p>
                        <p class="text-[13px] text-gray-500 mt-0.5">
                            By {{ $product->user->name ?? 'Seller' }}
                        </p>
                    </div>
                </div>
                
                <!-- Body Card -->
                <div class="pt-4">
                    <div class="flex justify-between items-center text-[14px] mb-2">
                        <span class="text-gray-600 flex items-center gap-2"><i class="fa-solid fa-cart-shopping w-4 text-gray-400"></i> Quantity</span>
                        <span class="font-semibold text-gray-800">{{ $savedQty }}</span>
                    </div>
                    <div class="flex justify-between items-center text-[14px]">
                        <span class="text-gray-600 flex items-center gap-2"><i class="fa-solid fa-tag w-4 text-gray-400"></i> Price</span>
                        <span class="font-semibold text-gray-800">Rp {{ number_format($itemPrice ?? $product->price, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <form id="checkout-form" class="flex-1 flex flex-col">
            <!-- Buyer Info -->
            <div class="mb-8">
                <h3 class="text-[14px] font-semibold text-gray-800 mb-3">Buyer Info</h3>
                <div class="space-y-4">
                    <div>
                        <input id="buyer_email" type="email" name="email" required placeholder="Email Address" value="{{ old('email') }}" 
                            class="w-full bg-white border border-gray-200 text-gray-900 text-[14px] rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3.5 transition shadow-sm outline-none" />
                    </div>
                    <div>
                        <input id="buyer_name" type="text" name="name" required placeholder="Full Name" value="{{ old('name') }}" 
                            class="w-full bg-white border border-gray-200 text-gray-900 text-[14px] rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3.5 transition shadow-sm outline-none" />
                    </div>
                    <input type="hidden" name="qty" value="{{ $savedQty }}">
                </div>
            </div>

            <!-- Payment Detail -->
            <div class="mt-auto">
                <h3 class="text-[14px] font-semibold text-gray-800 mb-3">Payment detail</h3>
                <div class="flex justify-between text-[14px] mb-2">
                    <span class="text-gray-500 font-medium">Subtotal</span>
                    <span class="font-semibold text-gray-800">Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-[14px] mb-6">
                    <span class="text-gray-500 font-medium">Tax</span>
                    <span class="font-semibold text-gray-800">Rp 0</span>
                </div>
                
                <button type="button" id="select-method" class="w-full bg-[#ED842C] hover:bg-blue-800 text-white text-[16px] font-semibold py-4 rounded-[16px] transition-colors shadow-lg shadow-blue-900/20 active:scale-[0.98]">
                    {{ $totalAmount > 0 ? 'Pay' : 'Dapatkan Gratis' }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let paymentSelected = false;
    let transactionResult = null;

    document.getElementById('select-method').addEventListener('click', function () {
        const email = document.getElementById('buyer_email').value.trim();
        const name = document.getElementById('buyer_name').value.trim();

        if (email === '') {
            Swal.fire('Oops!', 'Email tidak boleh kosong.', 'warning');
            document.getElementById('buyer_email').focus();
            return;
        }

        if (name === '') {
            Swal.fire('Oops!', 'Nama tidak boleh kosong.', 'warning');
            document.getElementById('buyer_name').focus();
            return;
        }

        const totalPrice = {{ $totalAmount }};
        if (totalPrice === 0) {
            Swal.fire('Diproses', 'Memproses pesanan gratis...', 'info');
            const transactionData = {
                order_id: 'FREE-' + Date.now(),
                transaction_status: 'success',
                product_id: {{ $product->id }},
                buyer_email: email,
                buyer_name: name,
                qty: {{ $savedQty }},
                total_price: 0
            };
            fetch("{{ route('transaction.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(transactionData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.redirect) {
                        Swal.fire({
                            title: 'Produk Terkirim!',
                            text: 'Produk digital telah dikirim ke email Anda. Silahkan cek inbox atau spam folder.',
                            icon: 'success',
                            confirmButtonText: 'Lanjut'
                        }).then(() => {
                            window.location.href = '{{ route("checkout.success", ["id" => $product->id]) }}?order_id=' + transactionData.order_id;
                        });
                    } else {
                        Swal.fire({
                            title: 'Produk Terkirim!',
                            text: 'Produk digital telah dikirim ke email Anda. Silahkan cek inbox atau spam folder.',
                            icon: 'success',
                            confirmButtonText: 'Lanjut'
                        }).then(() => {
                            window.location.href = '{{ route("checkout.success", ["id" => $product->id]) }}?order_id=' + transactionData.order_id;
                        });
                    }
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Terjadi kesalahan saat memproses pesanan.', 'error');
            });
            return;
        }

        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result) {
                Swal.fire('Sukses', 'Pembayaran berhasil!', 'success');
                paymentSelected = true;
                transactionResult = result;

                const transactionData = {
                    order_id: result.order_id,
                    transaction_status: result.transaction_status,
                    product_id: {{ $product->id }},
                    buyer_email: email,
                    buyer_name: name,
                    qty: {{ $savedQty }},
                    total_price: totalPrice
                };

                fetch("{{ route('transaction.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(transactionData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                            Swal.fire({
                                title: 'Memproses...',
                                text: 'Mengalihkan ke halaman sukses.',
                                icon: 'info',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                window.location.href = '{{ route("checkout.success", ["id" => $product->id]) }}?order_id=' + transactionData.order_id;
                            });
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Terjadi kesalahan saat menyimpan transaksi', 'error');
                });
            },
            onPending: function(result) {
                Swal.fire('Menunggu', 'Pembayaran sedang diproses...', 'info');
                paymentSelected = true;
                transactionResult = result;
            },
            onError: function(result) {
                Swal.fire('Gagal', 'Terjadi kesalahan dalam pembayaran.', 'error');
            },
            onClose: function() {
                Swal.fire('Perhatian', 'Kamu belum menyelesaikan pembayaran.', 'warning');
            }
        });
    });
</script>
</body>
</html>
