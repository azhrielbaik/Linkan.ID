<!DOCTYPE html>
<?php
    use Illuminate\Support\Facades\Route;
    $savedQty = session("cart.qty.{$product->id}", 1); // default 1 jika tidak ada di session
?>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Checkout - <?php echo e($product->title); ?></title>

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f0f4f8, #d9e2ec);
            margin: 0; padding: 20px;
            color: #34495e;
        }
        .checkout-wrapper {
            max-width: 720px;
            margin: 40px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgb(0 0 0 / 0.1);
            padding: 30px 40px;
            position: relative;
        }
        .section-title {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 18px;
            color: #2c3e50;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .section-title i {
            color: #ff7a00;
            font-size: 26px;
        }
        .product {
            display: flex;
            align-items: center;
            gap: 20px;
            border: 1px solid #e1e8ed;
            border-radius: 12px;
            padding: 15px 20px;
            background: #f9fafa;
            box-shadow: inset 0 0 10px #ffe6cc;
            position: relative;
        }
        .product img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(255, 122, 0, 0.3);
            transition: transform 0.3s ease;
        }
        .product img:hover {
            transform: scale(1.05);
        }
        .product-info {
            flex-grow: 1;
        }
        .product-info h2 {
            margin: 0 0 8px 0;
            font-size: 20px;
            font-weight: 700;
            color: #222;
        }
        .product-info .qty,
        .product-info .price {
            font-size: 16px;
            margin-bottom: 6px;
            color: #555;
        }
        .btn-detail {
            position: absolute;
            top: 50%;
            right: 20px;
            transform: translateY(-50%);
            background: #ff7a00;
            color: white;
            border: none;
            padding: 12px 18px;
            border-radius: 50px;
            font-size: 18px;
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(255, 122, 0, 0.6);
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background-color 0.3s ease;
            text-decoration: none;
        }
        .btn-detail:hover {
            background-color: #e06600;
        }
        form {
            margin-top: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #34495e;
        }
        input[type="email"],
        input[type="text"] {
            width: 100%;
            padding: 12px 14px;
            font-size: 16px;
            border: 1.8px solid #cfd8dc;
            border-radius: 8px;
            transition: border-color 0.3s ease;
            outline-offset: 2px;
        }
        input[type="email"]:focus,
        input[type="text"]:focus {
            border-color: #ff7a00;
            outline: none;
            box-shadow: 0 0 8px #ff7a00aa;
        }
        .payment {
            border-top: 2px solid #e1e8ed;
            padding-top: 25px;
            margin-top: 30px;
        }
        .payment-row {
            display: flex;
            justify-content: space-between;
            font-weight: 600;
            font-size: 18px;
            margin-bottom: 12px;
            color: #2c3e50;
        }
        .payment-row.total {
            font-size: 20px;
            color: #ff7a00;
        }
        #select-method {
            margin-top: 20px;
            padding: 14px 0;
            background: #fff1e0;
            border-radius: 10px;
            text-align: center;
            font-weight: 600;
            color: #cc6600;
            cursor: pointer;
            box-shadow: inset 0 2px 5px rgba(255, 122, 0, 0.2);
            transition: background-color 0.3s ease;
            user-select: none;
        }
        #select-method:hover {
            background-color: #ffe3c2;
        }
        .btn-buy {
            margin-top: 20px;
            width: 100%;
            padding: 16px 0;
            background: #ff7a00;
            border: none;
            color: white;
            font-size: 20px;
            font-weight: 700;
            border-radius: 12px;
            cursor: pointer;
            box-shadow: 0 8px 18px rgba(255, 122, 0, 0.6);
            transition: background-color 0.3s ease;
        }
        .btn-buy:hover {
            background-color: #e06600;
        }

        /* Responsive */
        @media (max-width: 600px) {
            .checkout-wrapper {
                padding: 20px;
            }
            .product {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            .btn-detail {
                position: static;
                transform: none;
                margin-top: 10px;
                width: 100%;
                justify-content: center;
            }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?php echo e(config('SB-Mid-client-LDeBzOGR_X-yS9q1')); ?>"></script>
</head>
<body>

<div class="checkout-wrapper">
    <!-- Product -->
    <div class="section">
        <div class="section-title">
            <i class="fa-solid fa-box-open"></i> Product
        </div>
        <div class="product">
            <img src="<?php echo e(asset('storage/' . $product->image)); ?>" alt="Product Image" />
            <div class="product-info">
                <h2><?php echo e($product->title); ?></h2>
                <div class="qty"><i class="fa-solid fa-cart-shopping"></i> Qty: <strong><?php echo e($savedQty); ?></strong></div>
                <div class="price"><i class="fa-solid fa-tag"></i> Rp <?php echo e(number_format($product->price, 0, ',', '.')); ?></div>
            </div>
            <a href="<?php echo e(route('product.show', ['id' => $product->id])); ?>" class="btn-detail" title="Lihat Detail Produk">
                Detail <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
    </div>

    <!-- Buyer Info + Payment Section -->
    <form id="checkout-form">
        <div class="section">
            <div class="section-title">
                <i class="fa-solid fa-user"></i> Buyer Info
            </div>
            <div class="form-group">
                <label for="email">Email *</label>
                <input id="email" type="email" name="email" required placeholder="Your Email" value="<?php echo e(old('email')); ?>" />
            </div>
            <div class="form-group">
                <label for="name">Name *</label>
                <input id="name" type="text" name="name" required placeholder="Your Name" value="<?php echo e(old('name')); ?>" />
            </div>
            <input type="hidden" name="qty" value="<?php echo e($savedQty); ?>">
        </div>

        <div class="section payment">
            <div class="section-title">
                <i class="fa-solid fa-credit-card"></i> Payment Detail
            </div>
            <div class="payment-row">
                <span>Subtotal</span>
                <span>Rp <?php echo e(number_format($product->price * $savedQty, 0, ',', '.')); ?></span>
            </div>
            <div class="payment-row total">
                <span>Total</span>
                <span>Rp <?php echo e(number_format($product->price * $savedQty, 0, ',', '.')); ?></span>
            </div>

            <div id="select-method"><i class="fa-solid fa-money-bill-wave"></i> Select payment method</div>
            <button id="pay-button" class="btn-buy" type="button">
                <i class="fa-solid fa-credit-card"></i> BUY NOW - Rp <?php echo e(number_format($product->price * $savedQty, 0, ',', '.')); ?>

            </button>
        </div>
    </form>
</div>

<script>
    let paymentSelected = false;
    let transactionResult = null;

    const payButton = document.getElementById('pay-button');
    const emailInput = document.querySelector('input[name="email"]');
    const nameInput = document.querySelector('input[name="name"]');

    document.getElementById('select-method').addEventListener('click', function () {
        snap.pay('<?php echo e($snapToken); ?>', {
        onSuccess: function(result) {
    Swal.fire('Sukses', 'Pembayaran berhasil! Silahkan cek Email Anda', 'success');
    paymentSelected = true;
    transactionResult = result;

    // Kirim ke server
    fetch("<?php echo e(route('transaction.store')); ?>", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        },
        body: JSON.stringify({
            order_id: result.order_id,
            transaction_status: result.transaction_status,
            product_id: <?php echo e($product->id); ?>,
            buyer_email: emailInput.value,
            buyer_name: nameInput.value,
            qty: <?php echo e($savedQty); ?>,
            total_price: <?php echo e($savedQty * $product->price); ?>

        })
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
        // Tambahkan alert atau redirect jika perlu
    })
    .catch(error => {
        console.error('Error:', error);
    });
},
            onPending: function(result) {
                Swal.fire('Menunggu', 'Pembayaran sedang diproses...', 'info');
                paymentSelected = true;
                transactionResult = result;
            },
            onError: function(result) {
                Swal.fire('Gagal', 'Terjadi kesalahan dalam pembayaran.', 'error');
                console.log(result);
            },
            onClose: function() {
                Swal.fire('Perhatian', 'Kamu belum memilih metode pembayaran.', 'warning');
            }
        });
    });

    payButton.addEventListener('click', function (e) {
        e.preventDefault();

        const email = emailInput.value.trim();
        const name = nameInput.value.trim();

        if (email === '') {
            Swal.fire('Oops!', 'Email tidak boleh kosong.', 'warning');
            emailInput.focus();
            return;
        }

        if (name === '') {
            Swal.fire('Oops!', 'Nama tidak boleh kosong.', 'warning');
            nameInput.focus();
            return;
        }

        if (!paymentSelected) {
            Swal.fire('Oops!', 'Silakan pilih metode pembayaran terlebih dahulu.', 'warning');
            return;
        }

        Swal.fire('Diproses', 'Pembayaran sedang diproses...', 'success');
        console.log(transactionResult);
    });
</script>

</body>
</html>
<?php /**PATH C:\Ardy\2025\Semester 4\Project2\LINKAN_ID-ardy-branch\resources\views/public/checkout.blade.php ENDPATH**/ ?>