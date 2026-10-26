<!-- STEP 1: Verifikasi Transaksi -->
<div class="glass-card" id="cardStep1">
    <div class="card-header-row">
        <div class="card-header-icon icon-step1">
            <i class="fas fa-receipt"></i>
        </div>
        <div class="card-header-text">
            <h3>Langkah 1: Verifikasi Transaksi Pembelian</h3>
            <p>Masukkan Order ID dan alamat email yang Anda gunakan saat proses checkout.</p>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Nomor Order ID <span class="req">*</span></label>
            <div class="input-wrapper">
                <input type="text" name="order_id" id="inp_order_id" class="form-control" 
                       value="{{ old('order_id', $prefilledOrderId) }}" required placeholder="Contoh: ORD-20260923-XXXX">
                <i class="fas fa-hashtag input-icon"></i>
            </div>
        </div>
        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Email Pembeli <span class="req">*</span></label>
            <div class="input-wrapper">
                <input type="email" name="buyer_email" id="inp_buyer_email" class="form-control" 
                       value="{{ old('buyer_email') }}" required placeholder="nama@email.com">
                <i class="fas fa-envelope input-icon"></i>
            </div>
        </div>
    </div>

    <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-top: 16px;">
        <button type="button" class="btn-verify-modern" id="btnVerifyOrder" onclick="verifyOrderAjax()">
            <i class="fas fa-magnifying-glass"></i>
            <span>Cek Validitas Pesanan</span>
        </button>
        <span id="verifyLoadingMsg" style="font-size: 12px; color: var(--slate-600); display: none;">
            <i class="fas fa-spinner fa-spin" style="color: var(--primary);"></i> Memeriksa data transaksi...
        </span>
    </div>

    <div id="verifyErrorMsg" class="alert-box err" style="display: none; margin-top: 14px; margin-bottom: 0;"></div>

    <!-- Hasil Verifikasi Sukses -->
    <div id="verifiedResultBox" class="verified-card">
        <div class="verified-badge">
            <i class="fas fa-circle-check"></i> Transaksi Terverifikasi Sah
        </div>
        <div class="verified-product-title" id="res_product_title"></div>
        <div class="verified-meta">
            <span>Penjual: <strong id="res_seller_name"></strong></span>
            <span>&bull;</span>
            <span>Nominal: <strong id="res_total_price" style="color: var(--primary);"></strong></span>
        </div>
    </div>
</div>
