<!-- Modal Popup (Cart Summary) -->
<div id="cartModal">
    <div class="cart-container">
        <div class="cart-header">
            <h3>Cart (<span id="cartCount">1</span>)</h3>
            <button class="close-btn" onclick="closeModal()">&times;</button>
        </div>
        
        <div class="cart-product">
            <img src="{{ $mainImage }}" alt="{{ $product->title }}">
            <div class="cart-product-info">
                <div class="cart-product-title">{{ $product->title }}</div>
                <div class="cart-product-qty">Qty. <span id="modalQty">1</span></div>
                <div class="cart-product-price">IDR <span id="modalPrice">{{ number_format($currentPrice, 0, ',', '.') }}</span></div>
                <button id="editButton" class="cart-product-edit">Edit</button>
            </div>
        </div>
        
        <div id="editSection">
            <label for="qtyInput">Quantity:</label>
            <input type="number" id="qtyInput" value="1" min="1">
            <div class="edit-btns">
                <button id="cancelEdit">Cancel</button>
                <button id="updateQty">Update</button>
            </div>
        </div>
        
        <div class="order-summary-label">ORDER SUMMARY</div>
        <div class="order-summary-row" style="margin-top: 10px;">
            <div>Total (<span id="cartCount2">1</span> Items)</div>
            <div>IDR <span id="totalItem">{{ number_format($currentPrice, 0, ',', '.') }}</span></div>
        </div>
        <div class="order-summary-row bold" style="margin-top: 10px; margin-bottom: 20px;">
            <div>Grand total</div>
            <div>IDR <span id="grandTotal">{{ number_format($currentPrice, 0, ',', '.') }}</span></div>
        </div>
        
        <a href="{{ route('checkout', ['id' => $product->id]) }}" class="cart-btn" onclick="closeModal()">Checkout</a>
    </div>
</div>
