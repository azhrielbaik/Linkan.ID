<!-- STEP 3: Rekening Pengembalian Dana (Refund) -->
<div class="glass-card" id="cardStep3" style="opacity: 0.55; pointer-events: none;">
    <div class="card-header-row">
        <div class="card-header-icon icon-step3">
            <i class="fas fa-building-columns"></i>
        </div>
        <div class="card-header-text">
            <h3>Langkah 3: Tujuan Pengembalian Dana (Refund)</h3>
            <p>Pastikan rekening atau e-wallet yang dimasukkan aktif untuk pengiriman dana refund.</p>
        </div>
    </div>

    <div class="form-group">
        <div class="bank-chips-label">Pilih Cepat Bank / E-Wallet:</div>
        <div class="bank-chips-grid">
            <button type="button" class="bank-chip" onclick="selectBankChip('BCA')">BCA</button>
            <button type="button" class="bank-chip" onclick="selectBankChip('Mandiri')">Mandiri</button>
            <button type="button" class="bank-chip" onclick="selectBankChip('BRI')">BRI</button>
            <button type="button" class="bank-chip" onclick="selectBankChip('BNI')">BNI</button>
            <button type="button" class="bank-chip" onclick="selectBankChip('GoPay')">GoPay</button>
            <button type="button" class="bank-chip" onclick="selectBankChip('DANA')">DANA</button>
            <button type="button" class="bank-chip" onclick="selectBankChip('OVO')">OVO</button>
            <button type="button" class="bank-chip" onclick="selectBankChip('ShopeePay')">ShopeePay</button>
        </div>

        <label class="form-label">Nama Bank / Penyedia E-Wallet <span class="req">*</span></label>
        <div class="input-wrapper">
            <input type="text" name="refund_bank_name" id="inp_refund_bank" class="form-control" required 
                   placeholder="Contoh: BCA / DANA / Mandiri" value="{{ old('refund_bank_name') }}">
            <i class="fas fa-building-columns input-icon"></i>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Nomor Rekening / No. HP E-Wallet <span class="req">*</span></label>
            <div class="input-wrapper">
                <input type="text" name="refund_account_number" class="form-control" required 
                       placeholder="Contoh: 8230192831" value="{{ old('refund_account_number') }}">
                <i class="fas fa-credit-card input-icon"></i>
            </div>
        </div>
        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Nama Pemilik Rekening <span class="req">*</span></label>
            <div class="input-wrapper">
                <input type="text" name="refund_account_name" class="form-control" required 
                       placeholder="Nama sesuai buku tabungan/akun" value="{{ old('refund_account_name') }}">
                <i class="fas fa-id-card input-icon"></i>
            </div>
        </div>
    </div>

    <div style="margin-top: 24px;">
        <button type="submit" class="btn-submit-main" id="btnSubmitDispute" disabled>
            <i class="fas fa-paper-plane"></i>
            <span>Kirim Laporan & Bekukan Saldo Transaksi</span>
        </button>
    </div>
</div>
