<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detail Sengketa {{ $dispute->dispute_code }} — Platform Admin</title>
    @include('platformadmin.partials.head_assets')
    <link rel="stylesheet" href="{{ asset('css/platform/transactions.css') }}">
    <link rel="stylesheet" href="{{ asset('css/platform/disputes.css') }}?v={{ file_exists(public_path('css/platform/disputes.css')) ? filemtime(public_path('css/platform/disputes.css')) : time() }}">
</head>
<body>

    {{-- Sidebar --}}
    @include('platformadmin.sidebar.sidebarplatform')

    <div class="platform-main">
        {{-- Header --}}
        <div class="platform-header">
            <div class="platform-header-left">
                <button class="hamburger-btn" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <a href="{{ route('platform-admin.disputes.index') }}" style="color: #64748b; font-size: 15px; text-decoration: none;" title="Kembali ke Daftar">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1 style="margin: 0; font-size: 18px;">Kasus {{ $dispute->dispute_code }}</h1>
                    <span class="{{ $dispute->status_badge_class }}">{{ $dispute->status_label }}</span>
                </div>
            </div>
            <div class="header-right">
                @include('platformadmin.partials.notifications')
                @include('platformadmin.partials.header_profile')
            </div>
        </div>

        <div class="content-wrapper">

            @if(session('success'))
                <div class="alert alert-success" style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 14px 18px; border-radius: 12px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; font-size: 13px; font-weight: 600;">
                    <i class="fas fa-check-circle" style="color: #10b981; font-size: 16px;"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger" style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 14px 18px; border-radius: 12px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; font-size: 13px; font-weight: 600;">
                    <i class="fas fa-exclamation-circle" style="color: #ef4444; font-size: 16px;"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger" style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 14px 18px; border-radius: 12px; margin-bottom: 20px; font-size: 13px;">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="dsp-detail-grid">
                {{-- Panel Kiri: Investigasi & Bukti --}}
                <div>
                    {{-- Card 1: Keluhan Pembeli --}}
                    <div class="dsp-detail-card">
                        <div class="dsp-detail-header">
                            <h3><i class="fas fa-comment-dots" style="color: #ea580c;"></i> Laporan Kendala Pembeli</h3>
                            <span class="dsp-reason-tag" style="font-size: 12px; padding: 3px 10px;">{{ $dispute->reason_label }}</span>
                        </div>

                        <div style="background: #f8fafc; border-left: 4px solid #ea580c; border-radius: 4px 10px 10px 4px; padding: 16px; margin-bottom: 18px;">
                            <div style="font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 6px;">Uraian Masalah:</div>
                            <p style="margin: 0; font-size: 13.5px; line-height: 1.6; color: #1e293b; white-space: pre-line;">{{ $dispute->description }}</p>
                        </div>

                        {{-- Lampiran Bukti --}}
                        <div style="margin-top: 14px;">
                            <div style="font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 8px;">
                                <i class="fas fa-paperclip"></i> Bukti Pendukung yang Diunggah Pembeli:
                            </div>

                            @if($dispute->evidence_file)
                                @php
                                    $ext = pathinfo($dispute->evidence_file, PATHINFO_EXTENSION);
                                    $isImg = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'webp']);
                                @endphp

                                @if($isImg)
                                    <div class="dsp-evidence-box">
                                        <a href="{{ asset('storage/' . $dispute->evidence_file) }}" target="_blank" title="Klik untuk memperbesar gambar">
                                            <img src="{{ asset('storage/' . $dispute->evidence_file) }}" alt="Bukti Sengketa" class="dsp-evidence-img">
                                        </a>
                                        <div style="margin-top: 8px; font-size: 11px; color: #64748b;">
                                            <i class="fas fa-up-right-from-square"></i> Klik gambar untuk melihat dalam ukuran penuh
                                        </div>
                                    </div>
                                @else
                                    <div style="padding: 14px; background: #f1f5f9; border-radius: 10px; display: flex; align-items: center; justify-content: space-between;">
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <i class="fas fa-file-pdf" style="font-size: 24px; color: #dc2626;"></i>
                                            <div>
                                                <div style="font-weight: 700; color: #0f172a; font-size: 13px;">Dokumen Bukti (PDF)</div>
                                                <div style="font-size: 11px; color: #64748b;">Format berkas dokumen resmi</div>
                                            </div>
                                        </div>
                                        <a href="{{ asset('storage/' . $dispute->evidence_file) }}" target="_blank" class="btn-dsp-action btn-dsp-detail">
                                            <i class="fas fa-download"></i> Buka Berkas
                                        </a>
                                    </div>
                                @endif
                            @else
                                <div style="padding: 12px; background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 8px; color: #94a3b8; font-size: 12px; text-align: center;">
                                    Pembeli tidak melampirkan file berkas bukti pendukung.
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Card 2: Informasi Pesanan & Transaksi --}}
                    <div class="dsp-detail-card">
                        <div class="dsp-detail-header">
                            <h3><i class="fas fa-receipt" style="color: #2563eb;"></i> Data Transaksi Terkait</h3>
                            <span style="font-family: monospace; font-size: 12px; font-weight: 700; color: #2563eb;">{{ $dispute->order_id }}</span>
                        </div>

                        <div class="dsp-info-row">
                            <span class="dsp-info-label">Produk Digital</span>
                            <span class="dsp-info-val">{{ $dispute->product->title ?? '-' }}</span>
                        </div>
                        <div class="dsp-info-row">
                            <span class="dsp-info-label">Harga Total</span>
                            <span class="dsp-info-val" style="color: #ea580c; font-size: 14px;">Rp {{ number_format($dispute->amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="dsp-info-row">
                            <span class="dsp-info-label">Metode Pembayaran</span>
                            <span class="dsp-info-val">{{ strtoupper($dispute->transaction->payment_method ?? 'QRIS') }}</span>
                        </div>
                        <div class="dsp-info-row">
                            <span class="dsp-info-label">Waktu Transaksi</span>
                            <span class="dsp-info-val">{{ $dispute->transaction ? $dispute->transaction->created_at->format('d M Y, H:i') : '-' }}</span>
                        </div>
                        <div class="dsp-info-row">
                            <span class="dsp-info-label">Status Transaksi</span>
                            <span class="dsp-info-val">
                                @if($dispute->transaction)
                                    <span style="text-transform: uppercase; font-size: 11px; padding: 2px 7px; border-radius: 4px; background: #f1f5f9;">
                                        {{ $dispute->transaction->status->value }}
                                    </span>
                                @else
                                    -
                                @endif
                            </span>
                        </div>
                    </div>

                    {{-- Card 3: Informasi Pihak Terlibat --}}
                    <div class="dsp-detail-card">
                        <div class="dsp-detail-header">
                            <h3><i class="fas fa-users" style="color: #64748b;"></i> Pihak Terkait (Buyer & Seller)</h3>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px;">
                                <div style="font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 6px;">
                                    <i class="fas fa-user"></i> Pembeli (Pelapor)
                                </div>
                                <div style="font-weight: 800; color: #0f172a; font-size: 13.5px;">{{ $dispute->buyer_name }}</div>
                                <div style="font-size: 12px; color: #64748b; margin-top: 2px;">{{ $dispute->buyer_email }}</div>
                                @if($dispute->buyer_phone)
                                    <div style="font-size: 11.5px; color: #15803d; margin-top: 4px;">
                                        <i class="fab fa-whatsapp"></i> {{ $dispute->buyer_phone }}
                                    </div>
                                @endif
                            </div>

                            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px;">
                                <div style="font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 6px;">
                                    <i class="fas fa-store"></i> Seller (Pemilik Produk)
                                </div>
                                <div style="font-weight: 800; color: #0f172a; font-size: 13.5px;">{{ $dispute->seller->name ?? '-' }}</div>
                                <div style="font-size: 12px; color: #64748b; margin-top: 2px;">{{ $dispute->seller->email ?? '-' }}</div>
                                <div style="font-size: 11.5px; color: #64748b; margin-top: 4px;">
                                    Username: <span style="font-weight: 700;">{{ $dispute->seller->username ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Panel Kanan: Rekening Refund & Aksi Eksekusi --}}
                <div>
                    {{-- Card: Rekening Tujuan Refund --}}
                    <div class="dsp-detail-card">
                        <div class="dsp-detail-header">
                            <h3><i class="fas fa-building-columns" style="color: #059669;"></i> Rekening Tujuan Refund</h3>
                        </div>

                        <div class="dsp-bank-box">
                            <span class="dsp-bank-badge">{{ strtoupper($dispute->refund_bank_name) }}</span>
                            <div class="dsp-acc-number">{{ $dispute->refund_account_number }}</div>
                            <div class="dsp-acc-name">Atas Nama: <strong>{{ $dispute->refund_account_name }}</strong></div>
                        </div>

                        <div style="font-size: 12px; color: #64748b; line-height: 1.45;">
                            <i class="fas fa-info-circle" style="color: #2563eb;"></i>
                            Jika sengketa disetujui, kirimkan dana refund sebesar <strong>Rp {{ number_format($dispute->amount, 0, ',', '.') }}</strong> ke rekening di atas, lalu masukkan nomor referensi transfer.
                        </div>
                    </div>

                    {{-- Card: Status Saldo & Eksekusi Admin --}}
                    <div class="dsp-detail-card">
                        <div class="dsp-detail-header">
                            <h3><i class="fas fa-gavel" style="color: #0f172a;"></i> Keputusan Admin</h3>
                        </div>

                        @if(in_array($dispute->status, ['pending', 'under_review']))
                            <div style="background: #fff7ed; border: 1.5px solid #fed7aa; border-radius: 12px; padding: 14px; margin-bottom: 20px;">
                                <div style="display: flex; align-items: center; gap: 8px; font-weight: 800; color: #ea580c; font-size: 12.5px; margin-bottom: 4px;">
                                    <i class="fas fa-lock"></i> Saldo Seller Sedang Ditahan (Freeze)
                                </div>
                                <div style="font-size: 12px; color: #7c2d12; line-height: 1.45;">
                                    Dana sebesar <strong>Rp {{ number_format($dispute->amount, 0, ',', '.') }}</strong> saat ini dikunci dari saldo penarikan seller hingga ada keputusan resmi admin.
                                </div>
                            </div>

                            @if($dispute->status === 'pending')
                                <form action="{{ route('platform-admin.disputes.review', $dispute->id) }}" method="POST" style="margin-bottom: 14px;">
                                    @csrf
                                    <button type="submit" style="width: 100%; padding: 10px 14px; background: #eff6ff; border: 1px solid #bfdbfe; color: #1d4ed8; border-radius: 9px; font-weight: 700; font-size: 12.5px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 7px;">
                                        <i class="fas fa-magnifying-glass"></i> Tandai "Sedang Diinvestigasi"
                                    </button>
                                </form>
                            @endif

                            <div style="display: flex; flex-direction: column; gap: 10px;">
                                <button type="button" onclick="openRefundModal()" style="width: 100%; padding: 12px 16px; background: linear-gradient(135deg, #059669 0%, #047857 100%); color: #ffffff; border: none; border-radius: 10px; font-weight: 700; font-size: 13px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 2px 8px rgba(5, 150, 105, 0.25);">
                                    <i class="fas fa-rotate-left"></i> Setujui & Terbitkan Refund
                                </button>

                                <button type="button" onclick="openRejectModal()" style="width: 100%; padding: 10px 16px; background: #ffffff; color: #dc2626; border: 1.5px solid #fca5a5; border-radius: 10px; font-weight: 700; font-size: 12.5px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
                                    <i class="fas fa-times-circle"></i> Tolak Sengketa (Lepas Freeze)
                                </button>
                            </div>
                        @elseif($dispute->status === 'resolved_refunded')
                            <div style="background: #ecfdf5; border: 1.5px solid #a7f3d0; border-radius: 12px; padding: 16px;">
                                <div style="display: flex; align-items: center; gap: 8px; font-weight: 800; color: #047857; font-size: 13.5px; margin-bottom: 6px;">
                                    <i class="fas fa-circle-check"></i> Refund Berhasil Diterbitkan
                                </div>
                                <div style="font-size: 12.5px; color: #065f46; margin-bottom: 4px;">
                                    Ref Transfer: <strong style="font-family: monospace;">{{ $dispute->refund_reference ?? '-' }}</strong>
                                </div>
                                <div style="font-size: 11.5px; color: #047857;">
                                    Waktu Eksekusi: {{ $dispute->resolved_at ? $dispute->resolved_at->format('d M Y, H:i') : '-' }}
                                </div>
                                @if($dispute->admin_notes)
                                    <div style="margin-top: 10px; padding-top: 10px; border-top: 1px dashed #a7f3d0; font-size: 12px; color: #065f46;">
                                        <strong>Catatan Admin:</strong> {{ $dispute->admin_notes }}
                                    </div>
                                @endif
                            </div>
                        @elseif($dispute->status === 'resolved_rejected')
                            <div style="background: #f8fafc; border: 1.5px solid #cbd5e1; border-radius: 12px; padding: 16px;">
                                <div style="display: flex; align-items: center; gap: 8px; font-weight: 800; color: #475569; font-size: 13.5px; margin-bottom: 6px;">
                                    <i class="fas fa-circle-xmark"></i> Sengketa Ditolak Admin
                                </div>
                                <div style="font-size: 12.5px; color: #64748b; margin-bottom: 4px;">
                                    Penahanan saldo seller telah dilepaskan dan transaksi dinyatakan sah.
                                </div>
                                @if($dispute->admin_notes)
                                    <div style="margin-top: 10px; padding-top: 10px; border-top: 1px dashed #cbd5e1; font-size: 12px; color: #334155;">
                                        <strong>Alasan Penolakan:</strong> {{ $dispute->admin_notes }}
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Modal Konfirmasi Setujui Refund --}}
    <div id="modalApproveRefund" class="dsp-modal-confirm" onclick="if(event.target === this) closeRefundModal()">
        <div class="dsp-modal-box">
            <h3 style="margin: 0 0 10px 0; font-size: 16px; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-rotate-left" style="color: #059669;"></i> Setujui & Terbitkan Refund
            </h3>
            <p style="font-size: 12.5px; color: #64748b; line-height: 1.5; margin-bottom: 16px;">
                Anda akan menerbitkan pengembalian dana sebesar <strong>Rp {{ number_format($dispute->amount, 0, ',', '.') }}</strong> ke <strong>{{ $dispute->buyer_name }}</strong> ({{ $dispute->refund_bank_name }} - {{ $dispute->refund_account_number }}).
            </p>

            <form action="{{ route('platform-admin.disputes.refund', $dispute->id) }}" method="POST">
                @csrf
                <div style="margin-bottom: 14px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #1e293b; margin-bottom: 6px;">
                        Nomor Referensi Transfer / Bukti Pembayaran <span style="color: #dc2626;">*</span>
                    </label>
                    <input type="text" name="refund_reference" required placeholder="Contoh: TF-BCA-982312 atau No Resi/Mutasi"
                           style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 12.5px; outline: none;">
                </div>

                <div style="margin-bottom: 14px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #1e293b; margin-bottom: 6px;">
                        Catatan Internal Admin (Opsional)
                    </label>
                    <textarea name="admin_notes" rows="2" placeholder="Catatan hasil investigasi produk..."
                              style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 12.5px; outline: none;"></textarea>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #1e293b; margin-bottom: 6px;">
                        Kata Sandi Admin Anda <span style="color: #dc2626;">*</span>
                    </label>
                    <input type="password" name="admin_password" required placeholder="Konfirmasi kata sandi admin..."
                           style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 12.5px; outline: none;">
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="button" onclick="closeRefundModal()" style="padding: 8px 16px; border-radius: 8px; border: 1px solid #cbd5e1; background: #fff; font-size: 12.5px; font-weight: 600; cursor: pointer;">
                        Batal
                    </button>
                    <button type="submit" style="padding: 8px 18px; border-radius: 8px; border: none; background: #059669; color: #fff; font-size: 12.5px; font-weight: 700; cursor: pointer;">
                        <i class="fas fa-check"></i> Konfirmasi Refund
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Konfirmasi Tolak Sengketa --}}
    <div id="modalRejectDispute" class="dsp-modal-confirm" onclick="if(event.target === this) closeRejectModal()">
        <div class="dsp-modal-box">
            <h3 style="margin: 0 0 10px 0; font-size: 16px; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-times-circle" style="color: #dc2626;"></i> Tolak Pengajuan Sengketa
            </h3>
            <p style="font-size: 12.5px; color: #64748b; line-height: 1.5; margin-bottom: 16px;">
                Menolak sengketa akan melepaskan penahanan saldo seller sebesar <strong>Rp {{ number_format($dispute->amount, 0, ',', '.') }}</strong> dan menutup kasus ini.
            </p>

            <form action="{{ route('platform-admin.disputes.reject', $dispute->id) }}" method="POST">
                @csrf
                <div style="margin-bottom: 14px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #1e293b; margin-bottom: 6px;">
                        Alasan Penolakan Sengketa <span style="color: #dc2626;">*</span>
                    </label>
                    <textarea name="admin_notes" rows="3" required placeholder="Jelaskan alasan penolakan (misal file digital terbukti valid & aktif)..."
                              style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 12.5px; outline: none;"></textarea>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #1e293b; margin-bottom: 6px;">
                        Kata Sandi Admin Anda <span style="color: #dc2626;">*</span>
                    </label>
                    <input type="password" name="admin_password" required placeholder="Konfirmasi kata sandi admin..."
                           style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 12.5px; outline: none;">
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="button" onclick="closeRejectModal()" style="padding: 8px 16px; border-radius: 8px; border: 1px solid #cbd5e1; background: #fff; font-size: 12.5px; font-weight: 600; cursor: pointer;">
                        Batal
                    </button>
                    <button type="submit" style="padding: 8px 18px; border-radius: 8px; border: none; background: #dc2626; color: #fff; font-size: 12.5px; font-weight: 700; cursor: pointer;">
                        <i class="fas fa-times"></i> Tolak Sengketa
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openRefundModal() {
            document.getElementById('modalApproveRefund').classList.add('show');
        }
        function closeRefundModal() {
            document.getElementById('modalApproveRefund').classList.remove('show');
        }
        function openRejectModal() {
            document.getElementById('modalRejectDispute').classList.add('show');
        }
        function closeRejectModal() {
            document.getElementById('modalRejectDispute').classList.remove('show');
        }
    </script>

    @vite(['resources/js/app.js'])
    <script src="{{ asset('js/platform/notifications.js') }}"></script>
</body>
</html>
