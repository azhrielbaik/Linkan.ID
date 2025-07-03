<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo e($product->title); ?> - Detail</title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/favicon.png')); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Global Styles */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            background: #f9f9f9;
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        /* Product Wrapper */
        .product-wrapper {
            background:#F5F5F5 ;
            border-radius: 10px;
            padding: 20px;
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            min-height: 100vh;
            overflow: hidden; /* Prevents overflow */
        }

        /* Header */
        .header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }
        .header a {
            text-decoration: none;
            color: #ff7a00;
            font-size: 24px;
        }
        .username {
            font-weight: bold;
            color: #ff7a00;
            font-size: 18px;
        }

        /* Product Image */
        .product-image {
            width: 100%;
            height: 520px;
            background: #eee;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 15px;
        }
        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Product Title and Price */
        .title-price {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .product-title {
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }
        .product-price {
            font-size: 16px;
            font-weight: bold;
            color: #ff7a00;
        }

        /* Description */
        .description-label {
            font-size: 10px;
            font-weight: bold;
            color: #888;
            margin-bottom: 4px;
        }
        .product-description {
            font-size: 14px;
            color: #555;
            margin-bottom: 20px;
        }

        /* Buy Button */
        .buy-button {
            display: block;
            width: 100%;
            padding: 12px;
            background-color: #ff7a00;
            color: white;
            text-align: center;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            text-decoration: none;
            margin-top: 20px;
            box-sizing: border-box;
        }

        /* Modal Cart Custom Style */
        #cartModal {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
        }
        #cartModal .cart-container {
            background: #fff;
            margin: 6% auto;
            padding: 0;
            border-radius: 16px;
            width: 95%;
            max-width: 420px;
            position: relative;
            box-shadow: 0 8px 32px rgba(0,0,0,0.18);
            overflow: hidden;
            font-family: inherit;
        }
        #cartModal .cart-header {
            padding: 20px 24px 10px 24px;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        #cartModal .cart-header h3 {
            font-size: 20px;
            font-weight: bold;
            margin: 0;
        }
        #cartModal .close-btn {
            background: transparent;
            border: none;
            font-size: 26px;
            color: #888;
            cursor: pointer;
            transition: color 0.2s;
        }
        #cartModal .close-btn:hover {
            color: #ff7a00;
        }
        #cartModal .cart-product {
            display: flex;
            gap: 14px;
            align-items: center;
            padding: 18px 24px 10px 24px;
        }
        #cartModal .cart-product img {
            width: 56px;
            height: 56px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #eee;
        }
        #cartModal .cart-product-info {
            flex: 1;
        }
        #cartModal .cart-product-info div {
            margin-bottom: 2px;
        }
        #cartModal .cart-product-title {
            font-size: 15px;
            font-weight: bold;
            color: #333;
        }
        #cartModal .cart-product-qty,
        #cartModal .cart-product-price {
            font-size: 13px;
            color: #555;
        }
        #cartModal .cart-product-edit {
            background: none;
            color: blue;
            border: none;
            cursor: pointer;
            font-size: 13px;
            padding: 0;
            margin-top: 2px;
        }
        #cartModal #editSection {
            display: none;
            padding: 0 24px 10px 24px;
        }
        #cartModal #editSection label {
            font-size: 13px;
            color: #888;
        }
        #cartModal #editSection input[type=number] {
            width: 100%;
            padding: 6px;
            margin: 6px 0 10px 0;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
        }
        #cartModal #editSection .edit-btns {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }
        @media (max-width: 480px) {
            #cartModal #editSection .edit-btns {
                flex-direction: column;
            }
            #cartModal #editSection .edit-btns button {
                width: 100%;
            }
        }
        #cartModal #editSection button {
            padding: 6px 16px;
            border-radius: 6px;
            border: none;
            font-size: 14px;
        }
        #cartModal #editSection #cancelEdit {
            background: #ccc;
            color: #333;
        }
        #cartModal #editSection #updateQty {
            background: #ff7a00;
            color: white;
        }
        #cartModal .order-summary-label {
            margin: 10px 0 0 0;
            color: #888;
            font-size: 12px;
            font-weight: bold;
            padding: 0 24px;
        }
        #cartModal .order-summary-row {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            padding: 2px 24px;
        }
        #cartModal .order-summary-row.bold {
            font-weight: bold;
        }
        #cartModal .voucher-input {
            width: calc(100% - 48px);
            margin: 14px 24px 0 24px;
            padding: 8px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
        }
        #cartModal .cart-btn {
            margin: 16px 24px 0 24px;
            width: calc(100% - 48px);
            padding: 12px;
            background: #ff7a00;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s;
        }
        #cartModal .cart-btn:hover {
            background: #ff9800;
            color: #fff;
            box-shadow: 0 2px 8px rgba(255,152,0,0.15);
            transition: background 0.2s, color 0.2s, box-shadow 0.2s;
        }
        #cartModal .continue-btn {
            margin: 10px 24px 20px 24px;
            width: calc(100% - 48px);
            padding: 10px;
            background: white;
            color: #1abc9c;
            border: 1px solid #1abc9c;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s, color 0.2s;
        }
        #cartModal .continue-btn:hover {
            background: #e0f7f4;
            color: #159c87;
        }
        #cartModal #editSection #cancelEdit:hover {
            background: #b0b0b0;
            color: #222;
        }
        #cartModal #editSection #updateQty:hover {
            background: #ff9800;
            color: #fff;
        }
        #cartModal button {
            transition: background 0.2s, color 0.2s, box-shadow 0.2s;
        }
        #cartModal .cart-btn:hover,
        #cartModal button[onclick*="closeModal"]:hover {
            background: #ff9800;
            color: #fff;
            box-shadow: 0 2px 8px rgba(255,152,0,0.15);
        }
        #cartModal .continue-btn:hover {
            background: #e0f7f4;
            color: #159c87;
            box-shadow: 0 2px 8px rgba(26,188,156,0.10);
        }
        #cartModal .cart-product-edit:hover {
            text-decoration: underline;
            color: #0056b3;
        }
        #cartModal #editSection #cancelEdit:hover {
            background: #b0b0b0;
            color: #222;
        }
        #cartModal #editSection #updateQty:hover {
            background: #ff9800;
            color: #fff;
        }
    </style>
</head>
<body>
<div class="product-wrapper">
    <div class="header">
        <a href="<?php echo e(route('public.profile', ['username' => $user->username])); ?>">
            <i class="fa fa-arrow-left"></i> <!-- Ganti dengan kode ini -->
        </a>
        <div class="username"><?php echo e($user->username); ?></div>
    </div>

    <!-- Modal Popup -->
<div id="cartModal" style="display: none; position: fixed; z-index: 999; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);">
    <div style="background: white; margin: 10% auto; padding: 20px; border-radius: 10px; width: 90%; max-width: 400px; position: relative;">
        <!-- Tombol X (Close) -->
        <button onclick="closeModal()" style="position: absolute; top: 10px; right: 10px; background: transparent; border: none; font-size: 22px; cursor: pointer;">&times;</button>
        <h3 style="margin-bottom: 20px;">Cart (<span id="cartCount">1</span>)</h3>
        <div style="border: 1px solid #ddd; border-radius: 8px; padding: 10px; margin-bottom: 15px;">
            <div style="display: flex; gap: 10px; align-items: center;">
                <img id="modalImage" src="<?php echo e(asset('storage/' . $product->image)); ?>" alt="<?php echo e($product->title); ?>" style="width: 50px; height: 50px; object-fit: cover;">
                <div style="flex: 1;">
                    <div id="modalTitle" style="font-size: 14px; font-weight: bold;"><?php echo e($product->title); ?></div>
                    <div style="font-size: 13px;">Qty. <span id="modalQty">1</span></div>
                    <div style="font-size: 13px;">IDR <span id="modalPrice"><?php echo e(number_format($product->price, 0, ',', '.')); ?></span></div>
                    <button id="editButton" style="background: none; color: blue; border: none; cursor: pointer; font-size: 13px;">Edit</button>
                </div>
            </div>
        </div>
        <div id="editSection" style="display: none; margin-top: 10px;">
            <label for="qtyInput">Quantity:</label>
            <input type="number" id="qtyInput" value="1" min="1" style="width: 100%; padding: 5px; margin: 5px 0;">
            <div class="edit-btns">
                <button id="cancelEdit">Cancel</button>
                <button id="updateQty">Update</button>
            </div>
        </div>
        <div style="margin: 15px 0 10px 0; color: #888; font-size: 12px; font-weight: bold;">ORDER SUMMARY</div>
        <div style="display: flex; justify-content: space-between; font-size: 14px;">
            <div>Total (<span id="cartCount2">1</span> Items)</div>
            <div>IDR <span id="totalItem"><?php echo e(number_format($product->price, 0, ',', '.')); ?></span></div>
        </div>
        <div style="display: flex; justify-content: space-between; font-size: 14px; font-weight: bold;">
            <div>Grand total</div>
            <div>IDR <span id="grandTotal"><?php echo e(number_format($product->price, 0, ',', '.')); ?></span></div>
        </div>
        <button class="cart-btn" onclick="closeModal()">
            <a href="<?php echo e(route('checkout', ['id' => $product->id])); ?>" class="cart-btn">Checkout</a>

        </button>
         
    </div>
</div>


    <div class="product-image">
        <?php if($product->image): ?>
            <img src="<?php echo e(asset('storage/' . $product->image)); ?>" alt="<?php echo e($product->title); ?>">
        <?php else: ?>
            <img src="https://via.placeholder.com/400x200?text=No+Image" alt="No image">
        <?php endif; ?>
    </div>

    <div class="title-price">
        <div class="product-title"><?php echo e($product->title); ?></div>
        <div class="product-price">Rp <?php echo e(number_format($product->price, 0, ',', '.')); ?></div>
    </div>

    <div class="description-label">DESCRIPTION :</div>
    <div class="product-description"><?php echo e($product->description); ?></div>

    <a href="<?php echo e(route('track.click', ['link_id' => $user->username, 'target' => $product->platform_url ?? '#'])); ?>" class="buy-button" target="_blank">
        <?php echo e(str_replace('_', ' ', $product->button_text ?? 'Beli')); ?>

    </a>
</div>
<script>
     const buyButton = document.querySelector('.buy-button');
    const cartModal = document.getElementById('cartModal');
    const editButton = document.getElementById('editButton');
    const editSection = document.getElementById('editSection');
    const cancelEdit = document.getElementById('cancelEdit');
    const updateQty = document.getElementById('updateQty');
    const qtyInput = document.getElementById('qtyInput');
    const modalQty = document.getElementById('modalQty');
    const totalItem = document.getElementById('totalItem');
    const grandTotal = document.getElementById('grandTotal');

    const price = <?php echo e($product->price); ?>;

    buyButton.addEventListener('click', function(e) {
        e.preventDefault();
        cartModal.style.display = 'block';
    });

    editButton.addEventListener('click', function() {
        editSection.style.display = 'block';
    });

    cancelEdit.addEventListener('click', function() {
        editSection.style.display = 'none';
    });

  updateQty.addEventListener('click', function() {
    const qty = parseInt(qtyInput.value);
    if (qty > 0) {
        fetch('<?php echo e(route("cart.updateQty")); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            },
            body: JSON.stringify({
                product_id: <?php echo e($product->id); ?>,
                qty: qty
            })
        }).then(response => response.json())
          .then(data => console.log(data));
    }

    // Update UI seperti biasa...
    modalQty.textContent = qty;
    totalItem.textContent = formatRupiah(price * qty);
    grandTotal.textContent = formatRupiah(price * qty);
    editSection.style.display = 'none';
});


    function closeModal() {
        cartModal.style.display = 'none';
    }

    function formatRupiah(angka) {
        return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    </script>

<!-- Font Awesome -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js" crossorigin="anonymous"></script>

</body>
</html>
<?php /**PATH /Users/indobuzz/Documents/dev/LINKAN_ID/resources/views/public/product-detail.blade.php ENDPATH**/ ?>