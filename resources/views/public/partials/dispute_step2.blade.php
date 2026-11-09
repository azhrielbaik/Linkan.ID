<!-- STEP 2: Rincian Kendala & Bukti Pendukung -->
<div class="glass-card" id="cardStep2" style="opacity: 0.55; pointer-events: none;">
    <div class="card-header-row">
        <div class="card-header-icon icon-step2">
            <i class="fas fa-triangle-exclamation"></i>
        </div>
        <div class="card-header-text">
            <h3>Langkah 2: Rincian Kendala & Bukti Pendukung</h3>
            <p>Pilih kategori masalah dan jelaskan secara objektif kendala yang dialami.</p>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Nama Lengkap Pembeli <span class="req">*</span></label>
            <div class="input-wrapper">
                <input type="text" name="buyer_name" id="inp_buyer_name" class="form-control" 
                       value="{{ old('buyer_name') }}" required placeholder="Nama Anda">
                <i class="fas fa-user input-icon"></i>
            </div>
        </div>
        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">No. WhatsApp Aktif (Opsional)</label>
            <div class="input-wrapper">
                <input type="text" name="buyer_phone" class="form-control" 
                       value="{{ old('buyer_phone') }}" placeholder="08xxxxxxxxxx">
                <i class="fab fa-whatsapp input-icon"></i>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="form-label">Kategori Masalah Produk <span class="req">*</span></label>
        <select name="reason" class="form-control no-icon" required>
            <option value="broken_link">🔗 Link Unduhan Rusak / Google Drive Expired</option>
            <option value="corrupted_file">📁 File Corrupt / Rusak / Tidak Bisa Diekstrak</option>
            <option value="misleading_description">⚠️ Isi Produk Tidak Sesuai Deskripsi Penjual</option>
            <option value="fraud_scam">🚨 Dugaan Penipuan / Pelanggaran Hak Cipta</option>
            <option value="other">💬 Kendala Teknis Lainnya</option>
        </select>
    </div>

    <div class="form-group">
        <label class="form-label">Jelaskan Kendala Secara Detail <span class="req">*</span></label>
        <textarea name="description" rows="3" class="form-control no-icon" required 
                  placeholder="Ceritakan kendala apa yang Anda temukan saat membuka file/link produk digital tersebut...">{{ old('description') }}</textarea>
    </div>

    <div class="form-group" style="margin-bottom: 0;">
        <label class="form-label">Lampirkan Screenshot Bukti (Gambar / PDF, Maks. 5MB)</label>
        <div class="file-upload-box" id="dropArea">
            <i class="fas fa-cloud-arrow-up upload-icon"></i>
            <div style="font-size: 13px; font-weight: 700; color: var(--slate-800);" id="uploadFileName">
                Klik atau Seret Berkas Bukti ke Sini
            </div>
            <div style="font-size: 11px; color: var(--slate-400); margin-top: 2px;">
                Mendukung JPG, PNG, WEBP, atau PDF (Maksimal 5MB)
            </div>
            <input type="file" name="evidence_file" id="evidenceFileInput" accept=".jpg,.jpeg,.png,.webp,.pdf" onchange="handleFileSelected(this)">
        </div>
    </div>
</div>
