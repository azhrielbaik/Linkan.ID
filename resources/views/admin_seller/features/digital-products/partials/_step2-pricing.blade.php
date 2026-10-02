<!-- Step 2: Pricing -->
<div id="step2-content" class="step-content" style="display: none;">
    
    <div class="section-label">Pengaturan Harga</div>

    <div class="form-row-box pay-what-want-row">
        <div class="pww-container">
            <div class="pww-info">
                <span class="row-label pww-label">{{ __('admin.allow_pay_what_want') }}</span>
                <span class="pww-desc">Izinkan pembeli menentukan harga sendiri</span>
            </div>
            <input type="hidden" name="pay_what_want" value="0">
            <label class="toggle-switch">
                <input type="checkbox" name="pay_what_want" value="1" {{ old('pay_what_want') || (isset($product) && $product->pay_what_want) ? 'checked' : '' }}>
                <span class="toggle-slider"></span>
            </label>
        </div>
    </div>

    <div class="form-row-box">
        <span class="row-label">{{ __('admin.price') }}:</span>
        <input type="text" name="price" id="priceInput" class="row-input" placeholder="Rp 0" value="{{ isset($product) ? 'Rp ' . number_format($product->price, 0, ',', '.') : old('price') }}">
        <input type="hidden" name="price_raw" id="priceRaw" value="{{ isset($product) ? $product->price : old('price') }}">
    </div>

    <div class="form-row-box">
        <span class="row-label">{{ __('admin.currency') }}:</span>
        <input type="text" name="currency" class="row-input currency-input-readonly" value="IDR" readonly>
    </div>

    <div class="section-label mt-sub">Tombol Beli</div>
    <div class="form-row-box">
        <span class="row-label">{{ __('admin.purchase_button') }}:</span>
        <select name="button_text" class="select-dropdown">
            <option value="buy_now" {{ (isset($product) && $product->button_text == 'buy_now') || old('button_text') == 'buy_now' ? 'selected' : '' }}>{{ __('admin.buy_now') }}</option>
            <option value="purchase" {{ (isset($product) && $product->button_text == 'purchase') || old('button_text') == 'purchase' ? 'selected' : '' }}>{{ __('admin.purchase') }}</option>
            <option value="get_now" {{ (isset($product) && $product->button_text == 'get_now') || old('button_text') == 'get_now' ? 'selected' : '' }}>{{ __('admin.get_now') }}</option>
        </select>
    </div>

    <div class="action-buttons space-between">
        <button type="button" class="btn-prev" onclick="prevStep()"><i class="fas fa-arrow-left btn-icon-left"></i> Kembali</button>
        <button type="submit" class="add-product-button" id="btnAddProduct" disabled>{{ isset($product) ? __('admin.save_changes') : __('admin.add_product') }} <i class="fas fa-check btn-icon-right"></i></button>
    </div>
</div>
