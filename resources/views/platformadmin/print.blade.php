<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Komisi Platform — Linkan.ID</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('css/platform/print.css') }}">
</head>
<body>

    <div class="print-container">
        {{-- Header Dokumen --}}
        <div class="print-header">
            <div class="brand-info">
                <h1>Linkan.ID</h1>
                <p>Platform Digital Monetization & Bio Link</p>
                <p style="margin-top: 4px; font-weight: 600; color: #1e293b;">Laporan Komisi & Pendapatan Platform</p>
            </div>
            <div class="report-meta">
                <div class="report-badge">Dokumen Resmi</div>
                <div class="report-date">
                    <i class="far fa-calendar-alt"></i> Dicetak: {{ now()->translatedFormat('d F Y, H:i') }}
                </div>
            </div>
        </div>

        {{-- KPI Ringkasan --}}
        <div class="kpi-banner">
            <div>
                <div class="kpi-title">{{ __('platform.total_commission') }}</div>
                <div class="kpi-value">{{ $data['total_earnings'] ?? 'Rp 0' }}</div>
                <div class="kpi-sub">Total akumulasi bagi hasil komisi platform dari transaksi seller</div>
            </div>
            <div class="kpi-icon">
                <i class="fas fa-coins"></i>
            </div>
        </div>

        {{-- Rincian Transaksi Komisi --}}
        <div class="section-title">
            <i class="fas fa-list-check" style="color: #5A5BF1;"></i> Rincian Transaksi Komisi
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th style="width: 50px; text-align: center;">#</th>
                        <th>Seller</th>
                        <th>Waktu Transaksi</th>
                        <th>Nominal Transaksi</th>
                        <th style="text-align: right;">Komisi Platform</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($data['commission_details']) && !empty($data['commission_details']))
                        @foreach($data['commission_details'] as $idx => $commission)
                            <tr>
                                <td style="text-align: center; color: #94a3b8; font-weight: 700;">{{ $idx + 1 }}</td>
                                <td>
                                    <div class="seller-name">{{ $commission['name'] }}</div>
                                    <div class="seller-email">{{ $commission['email'] }}</div>
                                </td>
                                <td style="color: #64748b; font-size: 12px; white-space: nowrap;">
                                    {{ $commission['date'] }}
                                </td>
                                <td class="amount-turnover">
                                    {{ $commission['turnover'] ?? '-' }}
                                </td>
                                <td class="amount-commission" style="text-align: right;">
                                    {{ $commission['amount'] }}
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <i class="fas fa-inbox" style="font-size: 32px; margin-bottom: 8px; display: block;"></i>
                                    {{ __('platform.no_commission_data') }}
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        {{-- Footer --}}
        <div class="print-footer">
            <span>© {{ date('Y') }} Linkan.ID — Platform Administration System</span>
            <span>Halaman Laporan Sah & Terverifikasi</span>
        </div>

        {{-- Toolbar Aksi (Hanya muncul di layar browser, tersembunyi saat dicetak) --}}
        <div class="no-print">
            <button class="btn-print-action btn-print-primary" onclick="window.print()">
                <i class="fas fa-print"></i> Cetak / Simpan ke PDF
            </button>
            <button class="btn-print-action btn-print-secondary" onclick="window.close()">
                <i class="fas fa-times"></i> Tutup Jendela
            </button>
        </div>
    </div>

</body>
</html>
