<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pusat Resolusi & Perlindungan Pembeli — Linkan.ID</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #ea580c;
            --primary-hover: #c2410c;
            --primary-light: #fff7ed;
            --primary-border: #fed7aa;
            --slate-900: #0f172a;
            --slate-800: #1e293b;
            --slate-600: #475569;
            --slate-400: #94a3b8;
            --slate-100: #f1f5f9;
            --slate-50: #f8fafc;
            --success: #10b981;
            --success-light: #ecfdf5;
            --danger: #ef4444;
            --danger-light: #fef2f2;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', sans-serif; }

        body {
            background-color: #f8fafc;
            background-image: 
                radial-gradient(at 0% 0%, rgba(234, 88, 12, 0.07) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(37, 99, 235, 0.05) 0px, transparent 50%),
                radial-gradient(at 50% 100%, rgba(234, 88, 12, 0.04) 0px, transparent 50%);
            background-attachment: fixed;
            color: var(--slate-900);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 16px 60px 16px;
        }

        .dispute-wrapper {
            width: 100%;
            max-width: 680px;
            margin: 0 auto;
        }

        /* Top Brand & Header */
        .brand-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .brand-logo-link {
            display: inline-block;
            margin-bottom: 16px;
            transition: transform 0.2s ease;
        }

        .brand-logo-link:hover {
            transform: scale(1.03);
        }

        .brand-logo {
            height: 42px;
            width: auto;
        }

        .protection-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: #ffffff;
            border: 1px solid var(--primary-border);
            padding: 5px 14px;
            border-radius: 9999px;
            font-size: 11.5px;
            font-weight: 700;
            color: var(--primary);
            box-shadow: 0 2px 8px rgba(234, 88, 12, 0.08);
            margin-bottom: 12px;
        }

        .page-title {
            font-size: 26px;
            font-weight: 800;
            color: var(--slate-900);
            letter-spacing: -0.6px;
            margin-bottom: 6px;
        }

        .page-desc {
            font-size: 14px;
            color: var(--slate-600);
            line-height: 1.5;
            max-width: 520px;
            margin: 0 auto;
        }

        /* Stepper Navigation */
        .stepper-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            margin-bottom: 28px;
            padding: 0 10px;
        }

        .stepper-line {
            position: absolute;
            top: 18px;
            left: 36px;
            right: 36px;
            height: 2px;
            background: #e2e8f0;
            z-index: 1;
        }

        .stepper-line-active {
            position: absolute;
            top: 18px;
            left: 36px;
            width: 0%;
            height: 2px;
            background: var(--primary);
            z-index: 2;
            transition: width 0.4s ease;
        }

        .step-item {
            position: relative;
            z-index: 3;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
        }

        .step-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #ffffff;
            border: 2px solid #cbd5e1;
            color: var(--slate-400);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 800;
            transition: all 0.3s ease;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
        }

        .step-item.active .step-circle {
            border-color: var(--primary);
            background: var(--primary);
            color: #ffffff;
            box-shadow: 0 0 0 4px rgba(234, 88, 12, 0.15);
        }

        .step-item.completed .step-circle {
            border-color: var(--success);
            background: var(--success);
            color: #ffffff;
        }

        .step-label {
            font-size: 11px;
            font-weight: 700;
            color: var(--slate-400);
            transition: color 0.3s ease;
        }

        .step-item.active .step-label,
        .step-item.completed .step-label {
            color: var(--slate-800);
        }

        /* Glassmorphic Main Card */
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(16px);
            border-radius: 20px;
            border: 1px solid rgba(226, 232, 240, 0.9);
            box-shadow: 0 12px 35px -8px rgba(15, 23, 42, 0.08);
            padding: 30px;
            margin-bottom: 22px;
            transition: all 0.3s ease;
        }

        .card-header-row {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid #f1f5f9;
        }

        .card-header-icon {
            width: 38px;
            height: 38px;
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }

        .icon-step1 { background: #fff7ed; color: #ea580c; border: 1.5px solid #fed7aa; }
        .icon-step2 { background: #eff6ff; color: #2563eb; border: 1.5px solid #bfdbfe; }
        .icon-step3 { background: #ecfdf5; color: #059669; border: 1.5px solid #a7f3d0; }

        .card-header-text h3 {
            font-size: 16px;
            font-weight: 800;
            color: var(--slate-900);
            margin-bottom: 2px;
        }

        .card-header-text p {
            font-size: 12px;
            color: var(--slate-600);
            margin: 0;
        }

        /* Form Controls */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 16px;
        }

        @media (max-width: 600px) {
            .form-row { grid-template-columns: 1fr; }
            .glass-card { padding: 22px 18px; }
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: var(--slate-800);
            margin-bottom: 7px;
        }

        .form-label span.req {
            color: var(--danger);
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            color: var(--slate-400);
            font-size: 13.5px;
            pointer-events: none;
            transition: color 0.2s ease;
        }

        .form-control {
            width: 100%;
            padding: 11px 14px 11px 38px;
            border-radius: 11px;
            border: 1.5px solid #cbd5e1;
            font-size: 13px;
            color: var(--slate-900);
            background: #ffffff;
            transition: all 0.2s ease;
            outline: none;
        }

        .form-control.no-icon {
            padding-left: 14px;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(234, 88, 12, 0.12);
        }

        .form-control:focus + .input-icon {
            color: var(--primary);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 85px;
            padding: 12px 14px;
            line-height: 1.5;
        }

        /* Verify Button */
        .btn-verify-modern {
            padding: 11px 22px;
            background: var(--slate-900);
            color: #ffffff;
            border: none;
            border-radius: 11px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.15);
        }

        .btn-verify-modern:hover {
            background: var(--slate-800);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(15, 23, 42, 0.2);
        }

        /* Verified Card */
        .verified-card {
            display: none;
            background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
            border: 1.5px solid #bbf7d0;
            border-radius: 14px;
            padding: 16px 18px;
            margin-top: 16px;
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.08);
            animation: fadeIn 0.3s ease;
        }

        .verified-card.show {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .verified-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 11px;
            font-weight: 800;
            color: #047857;
            background: #d1fae5;
            padding: 3px 8px;
            border-radius: 6px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 8px;
        }

        .verified-product-title {
            font-size: 15px;
            font-weight: 800;
            color: var(--slate-900);
            margin-bottom: 3px;
        }

        .verified-meta {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            font-size: 12.5px;
            color: var(--slate-600);
        }

        .verified-meta strong {
            color: var(--slate-900);
        }

        /* Bank Quick Chips */
        .bank-chips-label {
            font-size: 11px;
            font-weight: 700;
            color: var(--slate-400);
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 6px;
        }

        .bank-chips-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-bottom: 14px;
        }

        .bank-chip {
            background: var(--slate-100);
            border: 1px solid #e2e8f0;
            color: var(--slate-600);
            font-size: 11px;
            font-weight: 700;
            padding: 5px 11px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .bank-chip:hover {
            background: #e2e8f0;
            color: var(--slate-900);
            border-color: #cbd5e1;
        }

        .bank-chip.active {
            background: var(--primary-light);
            border-color: var(--primary-border);
            color: var(--primary);
        }

        /* File Upload Box */
        .file-upload-box {
            border: 1.5px dashed #cbd5e1;
            border-radius: 12px;
            padding: 18px;
            text-align: center;
            background: var(--slate-50);
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
        }

        .file-upload-box:hover {
            border-color: var(--primary);
            background: var(--primary-light);
        }

        .file-upload-box input[type="file"] {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .upload-icon {
            font-size: 24px;
            color: var(--slate-400);
            margin-bottom: 6px;
        }

        .file-upload-box:hover .upload-icon {
            color: var(--primary);
        }

        /* Submit Button */
        .btn-submit-main {
            width: 100%;
            padding: 14px 24px;
            background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%);
            color: #ffffff;
            border: none;
            border-radius: 12px;
            font-size: 14.5px;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.25s ease;
            box-shadow: 0 4px 16px rgba(234, 88, 12, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            letter-spacing: 0.1px;
        }

        .btn-submit-main:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(234, 88, 12, 0.4);
        }

        .btn-submit-main:active {
            transform: translateY(0);
        }

        .btn-submit-main:disabled {
            background: #cbd5e1;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        /* Alert Box */
        .alert-box {
            padding: 12px 16px;
            border-radius: 11px;
            font-size: 12.5px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .alert-box.err {
            background: var(--danger-light);
            border: 1px solid #fecaca;
            color: #b91c1c;
        }

        /* Success Splash */
        .success-wrapper {
            background: #ffffff;
            border-radius: 24px;
            border: 1.5px solid #a7f3d0;
            padding: 36px 28px;
            text-align: center;
            box-shadow: 0 12px 40px rgba(5, 150, 105, 0.1);
        }

        .success-icon-badge {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            color: #059669;
            border: 2px solid #a7f3d0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            margin: 0 auto 18px auto;
            box-shadow: 0 4px 16px rgba(16, 185, 129, 0.2);
        }

        .dispute-code-box {
            background: #fff7ed;
            border: 1.5px solid #fed7aa;
            border-radius: 12px;
            padding: 12px 20px;
            display: inline-block;
            margin: 16px 0;
        }

        .dispute-code-val {
            font-family: monospace;
            font-size: 20px;
            font-weight: 800;
            color: var(--primary);
            letter-spacing: 0.5px;
        }

        .security-guarantee-note {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 12px;
            color: var(--slate-400);
            margin-top: 18px;
        }
    </style>
</head>
<body>

<div class="dispute-wrapper">
    <!-- Header Brand -->
    <div class="brand-header">
        <a href="{{ url('/') }}" class="brand-logo-link" title="Beranda Linkan.ID">
            <img src="{{ asset('images/Logo.svg') }}" alt="Logo Linkan.ID" class="brand-logo">
        </a>
        <br>
        <div class="protection-badge">
            <i class="fas fa-scale-balanced"></i>
            <span>Linkan Buyer Protection & Escrow Guarantee</span>
        </div>
        <h1 class="page-title">Pusat Resolusi & Komplain Pembeli</h1>
        <p class="page-desc">Laporkan kendala produk digital Anda secara resmi. Dana transaksi seller akan otomatis ditahan (*freeze*) selama proses investigasi.</p>
    </div>

    @if(session('dispute_submitted'))
        <!-- Success State View -->
        <div class="success-wrapper">
            <div class="success-icon-badge">
                <i class="fas fa-check"></i>
            </div>
            <h2 style="font-size: 22px; font-weight: 800; color: #0f172a; margin-bottom: 8px;">Laporan Berhasil Diajukan</h2>
            <p style="font-size: 14px; color: #475569; max-width: 480px; margin: 0 auto; line-height: 1.55;">
                Pengaduan Anda telah tercatat ke antrean investigasi Platform Admin. Dana transaksi sebesar <strong>Rp {{ number_format(session('amount', 0), 0, ',', '.') }}</strong> telah <strong>dibekukan secara instan</strong> dari saldo penarikan seller.
            </p>

            <div class="dispute-code-box">
                <div style="font-size: 11px; font-weight: 700; color: #9a3412; text-transform: uppercase; margin-bottom: 3px;">Nomor ID Sengketa Resmi:</div>
                <div class="dispute-code-val">{{ session('dispute_code') }}</div>
            </div>

            <p style="font-size: 12.5px; color: #64748b; line-height: 1.5; max-width: 440px; margin: 0 auto;">
                Simpan ID sengketa ini sebagai bukti pengaduan. Tim admin kami akan meninjau keluhan Anda dan menginformasikan hasil keputusan refund maksimal 1x24 jam kerja.
            </p>

            <div style="margin-top: 28px; display: flex; justify-content: center; gap: 12px;">
                <a href="{{ url('/') }}" style="padding: 11px 22px; border-radius: 11px; background: #0f172a; color: #fff; font-size: 13px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 7px;">
                    <i class="fas fa-house"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    @else

        <!-- Stepper Progress Bar -->
        <div class="stepper-bar">
            <div class="stepper-line"></div>
            <div class="stepper-line-active" id="stepperLineActive"></div>

            <div class="step-item active" id="stepIndicator1">
                <div class="step-circle">1</div>
                <span class="step-label">Validasi Order</span>
            </div>

            <div class="step-item" id="stepIndicator2">
                <div class="step-circle">2</div>
                <span class="step-label">Rincian Kendala</span>
            </div>

            <div class="step-item" id="stepIndicator3">
                <div class="step-circle">3</div>
                <span class="step-label">Tujuan Refund</span>
            </div>
        </div>

        @if(session('error'))
            <div class="alert-box err">
                <i class="fas fa-exclamation-circle" style="font-size: 15px;"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <form action="{{ route('public.dispute.store') }}" method="POST" enctype="multipart/form-data" id="disputeForm">
            @csrf

            <!-- STEP 1: Verifikasi Transaksi -->
            @include('public.partials.dispute_step1')

            <!-- STEP 2: Rincian Kendala & Bukti Pendukung -->
            @include('public.partials.dispute_step2')

            <!-- STEP 3: Rekening Pengembalian Dana (Refund) -->
            @include('public.partials.dispute_step3')
        </form>

        <div class="security-guarantee-note">
            <i class="fas fa-circle-check" style="color: var(--primary);"></i>
            <span>Setiap laporan diproses resmi oleh Tim Investigasi & Administrator Platform Linkan.ID</span>
        </div>
    @endif
</div>

<script>
    function selectBankChip(name) {
        document.getElementById('inp_refund_bank').value = name;
        document.querySelectorAll('.bank-chip').forEach(c => {
            c.classList.toggle('active', c.textContent.trim() === name);
        });
    }

    function handleFileSelected(input) {
        const file = input.files[0];
        const label = document.getElementById('uploadFileName');
        if (file) {
            label.innerHTML = `<span style="color: #ea580c;"><i class="fas fa-file-check"></i> ${file.name}</span> (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
        } else {
            label.textContent = 'Klik atau Seret Berkas Bukti ke Sini';
        }
    }

    async function verifyOrderAjax() {
        const orderId = document.getElementById('inp_order_id').value.trim();
        const email = document.getElementById('inp_buyer_email').value.trim();
        const errBox = document.getElementById('verifyErrorMsg');
        const resBox = document.getElementById('verifiedResultBox');
        const loadingMsg = document.getElementById('verifyLoadingMsg');
        const cardStep2 = document.getElementById('cardStep2');
        const cardStep3 = document.getElementById('cardStep3');
        const submitBtn = document.getElementById('btnSubmitDispute');
        const step2Ind = document.getElementById('stepIndicator2');
        const step3Ind = document.getElementById('stepIndicator3');
        const stepperLine = document.getElementById('stepperLineActive');

        if (!orderId || !email) {
            errBox.textContent = 'Harap isi Order ID dan Email Pembeli terlebih dahulu.';
            errBox.style.display = 'flex';
            resBox.classList.remove('show');
            return;
        }

        errBox.style.display = 'none';
        loadingMsg.style.display = 'inline-block';

        try {
            const response = await fetch("{{ route('public.dispute.check_order') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    "Accept": "application/json"
                },
                body: JSON.stringify({ order_id: orderId, buyer_email: email })
            });

            const data = await response.json();
            loadingMsg.style.display = 'none';

            if (response.ok && data.valid) {
                document.getElementById('res_product_title').textContent = data.data.product_title;
                document.getElementById('res_seller_name').textContent = data.data.seller_name;
                document.getElementById('res_total_price').textContent = data.data.formatted_price;
                if (!document.getElementById('inp_buyer_name').value) {
                    document.getElementById('inp_buyer_name').value = data.data.buyer_name;
                }

                resBox.classList.add('show');
                cardStep2.style.opacity = '1';
                cardStep2.style.pointerEvents = 'auto';
                cardStep3.style.opacity = '1';
                cardStep3.style.pointerEvents = 'auto';
                submitBtn.disabled = false;

                // Update Stepper to completed step 1 & active step 2
                document.getElementById('stepIndicator1').classList.add('completed');
                step2Ind.classList.add('active');
                step3Ind.classList.add('active');
                if (stepperLine) stepperLine.style.width = '100%';
            } else {
                resBox.classList.remove('show');
                errBox.textContent = data.message || 'Transaksi tidak valid.';
                errBox.style.display = 'flex';
                cardStep2.style.opacity = '0.55';
                cardStep2.style.pointerEvents = 'none';
                cardStep3.style.opacity = '0.55';
                cardStep3.style.pointerEvents = 'none';
                submitBtn.disabled = true;

                if (stepperLine) stepperLine.style.width = '0%';
                step2Ind.classList.remove('active');
                step3Ind.classList.remove('active');
            }
        } catch (error) {
            loadingMsg.style.display = 'none';
            errBox.textContent = 'Gagal menghubungi server. Silakan coba lagi beberapa saat lagi.';
            errBox.style.display = 'flex';
        }
    }

    // Auto verify if prefilled order id is provided
    document.addEventListener('DOMContentLoaded', function() {
        const orderId = document.getElementById('inp_order_id');
        const email = document.getElementById('inp_buyer_email');
        if (orderId && orderId.value && email && email.value) {
            verifyOrderAjax();
        }
    });
</script>

</body>
</html>
