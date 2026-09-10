@extends("admin_seller.layouts.app")

@section("page_title", __('admin.dashboard_title'))

@push("styles")
<link rel="stylesheet" href="{{ asset('css/pages/beranda.css') }}" data-turbo-track="reload">
@endpush

@section("content")
<div class="dashboard-beranda-page">

    {{-- Banner Notifikasi Suspensi & Formulir Banding --}}
    @if(Auth::user()->isSuspended())
        <div class="suspension-alert-card" style="background: #ffffff; border: 2px solid #fecaca; border-radius: 16px; padding: 24px; margin-bottom: 24px; box-shadow: 0 4px 20px rgba(220, 38, 38, 0.08);">
            <div style="display: flex; align-items: flex-start; gap: 16px; margin-bottom: 18px;">
                <div style="width: 48px; height: 48px; border-radius: 14px; background: #fee2e2; color: #dc2626; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0;">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div>
                    <h2 style="font-size: 18px; font-weight: 800; color: #991b1b; margin-bottom: 4px;">
                        Akun Anda Sedang Ditangguhkan (Suspended)
                    </h2>
                    <p style="font-size: 13px; color: #64748b; line-height: 1.5;">
                        Akses fitur transaksi, pengelolaan produk, dan penarikan dana dinonaktifkan sementara oleh Admin Platform.
                    </p>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 14px 18px; margin-bottom: 20px; font-size: 13px;">
                <div>
                    <strong style="color: #991b1b; display: block; margin-bottom: 2px;"><i class="fas fa-info-circle"></i> Alasan Penangguhan:</strong>
                    <span style="color: #475569;">{{ Auth::user()->suspend_reason ?? 'Pelanggaran ketentuan platform' }}</span>
                </div>
                <div>
                    <strong style="color: #991b1b; display: block; margin-bottom: 2px;"><i class="fas fa-clock"></i> Batas Waktu Suspend:</strong>
                    <span style="color: #475569; font-weight: 700;">
                        @if(Auth::user()->suspended_until)
                            Sampai {{ Auth::user()->suspended_until->format('d M Y, H:i') }} ({{ Auth::user()->suspended_until->diffForHumans() }})
                        @else
                            Permanen (Memerlukan peninjauan banding)
                        @endif
                    </span>
                </div>
            </div>

            <!-- Appeal Section -->
            @if(isset($activeAppeal) && $activeAppeal->status === 'pending')
                <div style="background: #f0fdf4; border: 1.5px solid #bbf7d0; border-radius: 12px; padding: 16px 20px; display: flex; align-items: center; gap: 14px;">
                    <i class="fas fa-hourglass-half" style="font-size: 24px; color: #16a34a;"></i>
                    <div style="flex: 1;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2px;">
                            <div style="font-weight: 800; color: #166534; font-size: 14px;">Permohonan Banding Sedang Ditinjau</div>
                            <span style="font-size: 11px; font-weight: 800; background: #dcfce7; color: #166534; padding: 2px 8px; border-radius: 6px;">Banding {{ $totalAppealsCount ?? 1 }}/{{ $maxAppeals ?? 3 }}</span>
                        </div>
                        <div style="font-size: 13px; color: #15803d; line-height: 1.4;">
                            Surat banding Anda yang diajukan pada <strong>{{ $activeAppeal->created_at->format('d M Y, H:i') }}</strong> sedang dalam proses evaluasi oleh tim Platform Admin.
                        </div>
                    </div>
                </div>
            @elseif(isset($totalAppealsCount) && $totalAppealsCount >= ($maxAppeals ?? 3) && isset($activeAppeal) && $activeAppeal->status === 'rejected')
                <div style="background: #fff1f2; border: 1.5px solid #fecdd3; border-radius: 12px; padding: 18px 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <div style="font-weight: 800; color: #be123c; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-ban"></i> Batas Pengajuan Banding Telah Habis
                        </div>
                        <span style="font-size: 11px; font-weight: 800; background: #fee2e2; color: #991b1b; padding: 2px 8px; border-radius: 6px;">3/3 Percobaan Habis</span>
                    </div>
                    <div style="font-size: 13px; color: #4c0519; line-height: 1.5; margin-bottom: 10px;">
                        <strong>Catatan Admin Terakhir:</strong> {{ $activeAppeal->admin_notes ?? 'Tidak memenuhi syarat pemulihan akun.' }}
                    </div>
                    <div style="font-size: 12px; color: #64748b; background: #ffffff; padding: 10px 14px; border-radius: 8px; border: 1px solid #f1f5f9; line-height: 1.5;">
                        <i class="fas fa-info-circle" style="color: #64748b;"></i> Anda telah mencapai kuota maksimal pengajuan banding (3 kali). Akun Anda tetap dalam penangguhan. Jika Anda membutuhkan bantuan lebih lanjut, silakan hubungi tim dukungan kami melalui menu <a href="{{ route('contact.form') }}" style="color: #5A5BF1; font-weight: 700; text-decoration: underline;">Hubungi Kami</a>.
                    </div>
                </div>
            @elseif(isset($activeAppeal) && $activeAppeal->status === 'rejected')
                <div style="background: #fff1f2; border: 1.5px solid #fecdd3; border-radius: 12px; padding: 16px 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                        <div style="font-weight: 800; color: #be123c; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-times-circle"></i> Permohonan Banding Sebelumnya Ditolak
                        </div>
                        <span style="font-size: 11px; font-weight: 800; background: #fee2e2; color: #991b1b; padding: 2px 8px; border-radius: 6px;">Banding {{ $totalAppealsCount }}/{{ $maxAppeals ?? 3 }}</span>
                    </div>
                    <div style="font-size: 13px; color: #4c0519; line-height: 1.4; margin-bottom: 12px;">
                        <strong>Catatan Admin:</strong> {{ $activeAppeal->admin_notes ?? 'Tidak memenuhi syarat pemulihan akun.' }}
                    </div>

                    @if(isset($canSubmitAppeal) && !$canSubmitAppeal)
                        {{-- Cooldown 1 Hari Aktif --}}
                        <div style="background: #fefce8; border: 1.5px solid #fef08a; border-radius: 10px; padding: 12px 16px; margin-top: 10px;">
                            <div style="font-weight: 700; color: #854d0e; font-size: 13px; display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                                <i class="fas fa-clock"></i> Jeda Waktu Pengajuan Banding (Cooldown 1 Hari)
                            </div>
                            <div style="font-size: 12px; color: #a16207; line-height: 1.5;">
                                Anda baru dapat mengajukan permohonan banding berikutnya dalam <strong>{{ $remainingCooldownText }}</strong> (tersedia pada {{ $cooldownUntil ? $cooldownUntil->format('d M Y, H:i') : '-' }} WIB).
                            </div>
                            <div style="font-size: 11px; color: #a16207; margin-top: 6px;">
                                <i class="fas fa-shield-alt"></i> Sisa kesempatan pengajuan banding: <strong>{{ $remainingAttempts ?? 1 }} kali</strong> lagi.
                            </div>
                        </div>
                    @else
                        {{-- Form Banding Ulang --}}
                        <p style="font-size: 12px; color: #64748b; margin-bottom: 10px;">
                            Anda memiliki sisa <strong>{{ $remainingAttempts ?? 1 }} kali kesempatan</strong> pengajuan banding. Silakan kirimkan klarifikasi atau penjelasan baru:
                        </p>
                        
                        <form action="{{ route('admin.appeal.store') }}" method="POST">
                            @csrf
                            <div style="margin-bottom: 12px;">
                                <textarea name="appeal_reason" rows="3" placeholder="Jelaskan alasan atau klarifikasi tambahan Anda secara jelas dan lengkap..." style="width: 100%; padding: 12px 14px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 13px; font-family: inherit; outline: none; box-sizing: border-box;" required></textarea>
                            </div>
                            <button type="submit" style="background: #5A5BF1; color: #fff; border: none; border-radius: 10px; padding: 10px 20px; font-weight: 700; font-size: 13px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                                <i class="fas fa-paper-plane"></i> Ajukan Banding Ulang (Percobaan ke-{{ ($totalAppealsCount ?? 0) + 1 }} dari 3)
                            </button>
                        </form>
                    @endif
                </div>
            @else
                <!-- Initial Appeal Form -->
                <div style="border-top: 1px solid #f1f5f9; padding-top: 18px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <h3 style="font-size: 15px; font-weight: 800; color: #1e293b; margin: 0;">
                            <i class="fas fa-file-signature" style="color: #5A5BF1;"></i> Ajukan Banding Penangguhan Akun
                        </h3>
                        <span style="font-size: 11px; font-weight: 800; background: #EEF0FE; color: #5A5BF1; padding: 2px 8px; border-radius: 6px;">Kesempatan 1/3</span>
                    </div>
                    <p style="font-size: 12px; color: #64748b; margin-bottom: 12px;">
                        Jika Anda merasa penangguhan ini adalah kekeliruan atau telah menyelesaikan masalah terkait, silakan kirimkan surat permohonan banding ke Admin Platform (Maksimal 3 kali pengajuan):
                    </p>
                    <form action="{{ route('admin.appeal.store') }}" method="POST">
                        @csrf
                        <div style="margin-bottom: 12px;">
                            <textarea name="appeal_reason" rows="3" placeholder="Tuliskan penjelasan, kronologi, atau alasan mengapa akun Anda layak dipulihkan..." style="width: 100%; padding: 12px 14px; border: 1.5px solid #cbd5e1; border-radius: 10px; font-size: 13px; font-family: inherit; outline: none; box-sizing: border-box;" required></textarea>
                        </div>
                        <button type="submit" style="background: #5A5BF1; color: #fff; border: none; border-radius: 10px; padding: 11px 22px; font-weight: 700; font-size: 13px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                            <i class="fas fa-paper-plane"></i> Kirim Surat Banding ke Admin
                        </button>
                    </form>
                </div>
            @endif
        </div>
    @endif

    {{-- Broadcast Announcements from Platform Admin --}}
    @if(isset($announcements) && $announcements->count() > 0)
        <div class="announcement-banner-wrapper" style="margin-bottom: 24px; display: flex; flex-direction: column; gap: 12px;">
            @foreach($announcements as $ann)
                @php
                    $bgColor = '#eff6ff';
                    $borderColor = '#bfdbfe';
                    $textColor = '#1e40af';
                    $iconClass = 'fas fa-info-circle';

                    if ($ann->type === 'warning') {
                        $bgColor = '#fefce8';
                        $borderColor = '#fef08a';
                        $textColor = '#854d0e';
                        $iconClass = 'fas fa-exclamation-triangle';
                    } elseif ($ann->type === 'danger') {
                        $bgColor = '#fef2f2';
                        $borderColor = '#fecaca';
                        $textColor = '#991b1b';
                        $iconClass = 'fas fa-bullhorn';
                    } elseif ($ann->type === 'success') {
                        $bgColor = '#f0fdf4';
                        $borderColor = '#bbf7d0';
                        $textColor = '#166534';
                        $iconClass = 'fas fa-check-circle';
                    }
                @endphp
                <div class="announcement-item" id="ann-{{ $ann->id }}" style="background: {{ $bgColor }}; border: 1.5px solid {{ $borderColor }}; border-radius: 14px; padding: 14px 18px; display: flex; align-items: flex-start; justify-content: space-between; gap: 14px; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
                    <div style="display: flex; align-items: flex-start; gap: 12px;">
                        <i class="{{ $iconClass }}" style="font-size: 18px; color: {{ $textColor }}; margin-top: 2px;"></i>
                        <div>
                            <div style="font-weight: 800; font-size: 14px; color: {{ $textColor }}; margin-bottom: 2px;">{{ $ann->title }}</div>
                            <div style="font-size: 13px; color: {{ $textColor }}; opacity: 0.9; line-height: 1.5;">{{ $ann->message }}</div>
                            <div style="font-size: 11px; color: {{ $textColor }}; opacity: 0.7; margin-top: 4px;">
                                <i class="fas fa-clock"></i> {{ $ann->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                    <button type="button" onclick="document.getElementById('ann-{{ $ann->id }}').remove()" style="background: none; border: none; color: {{ $textColor }}; opacity: 0.6; cursor: pointer; font-size: 16px; padding: 2px 6px;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endforeach
        </div>
    @endif

    <!-- MAIN DASHBOARD GRID -->
    <div class="dashboard-main-layout">
        
        <!-- === LEFT COLUMN === -->
        <div class="dashboard-left">
            
            <!-- Hero Banner -->
            <div class="hero-banner">
                <h2>Tingkatkan Penjualan Anda dengan Memaksimalkan Produk Digital!</h2>
                <a href="{{ route('admin.digital-products.create') }}" class="hero-btn">
                    Buat Produk Sekarang <div class="icon-arrow"><i class="fas fa-chevron-right"></i></div>
                </a>
            </div>

            <!-- Analytics Cards -->
            <div class="stat-cards-grid">
                <div class="stat-card-item">
                    <div class="stat-icon purple">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-val">Rp {{ number_format($totalEarnings, 0, ',', '.') }}</div>
                        <div class="stat-lbl">Total Pendapatan</div>
                    </div>
                </div>
                <div class="stat-card-item">
                    <div class="stat-icon pink">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-val">{{ number_format($lifetimeOrders) }}</div>
                        <div class="stat-lbl">Total Penjualan</div>
                    </div>
                </div>
                <div class="stat-card-item">
                    <div class="stat-icon blue">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-val">{{ number_format($totalProducts) }}</div>
                        <div class="stat-lbl">Produk Digital Aktif</div>
                    </div>
                </div>
            </div>

            <!-- Recent Products (Continue Watching Alternative) -->
            <div class="recent-products-section">
                <div class="section-header">
                    <h3>Produk Digital Terbaru Anda</h3>
                    <div class="nav-arrows">
                        <div class="nav-arrow"><i class="fas fa-chevron-left"></i></div>
                        <div class="nav-arrow active"><i class="fas fa-chevron-right"></i></div>
                    </div>
                </div>
                
                <div class="recent-products-row">
                    @forelse($recentProducts ?? [] as $product)
                        <div class="product-course-card">
                            <button class="like-btn"><i class="far fa-heart"></i></button>
                            <div class="product-img-box">
                                @if($product->image_url)
                                    <img src="{{ Storage::url($product->image_url) }}" alt="{{ $product->title }}">
                                @else
                                    <i class="fas fa-image"></i>
                                @endif
                            </div>
                            <span class="product-tag purple">PRODUK DIGITAL</span>
                            <h4 class="product-title">{{ $product->title }}</h4>
                            <div class="product-footer">
                                <div class="price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                <div class="date">{{ $product->created_at->format('d M') }}</div>
                            </div>
                        </div>
                    @empty
                        <div style="grid-column: span 3; text-align: center; padding: 40px; background: #fff; border-radius: 16px; border: 1px solid #f1f5f9; color: #64748b; font-size: 14px;">
                            <i class="fas fa-box-open" style="font-size: 24px; color: #cbd5e1; margin-bottom: 12px; display: block;"></i>
                            Anda belum memiliki produk digital.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Transactions (Your Lesson Alternative) -->
            <div class="recent-transactions-section">
                <div class="section-header">
                    <h3>Transaksi Terakhir</h3>
                    <a href="{{ route('admin.orders') }}" class="see-all">Lihat Semua</a>
                </div>
                
                <div class="transactions-list">
                    <table class="tx-table">
                        <thead>
                            <tr>
                                <th>PEMBELI</th>
                                <th>PRODUK</th>
                                <th>STATUS</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTransactions ?? [] as $tx)
                                <tr>
                                    <td data-label="PEMBELI">
                                        <div class="tx-buyer">
                                            <div class="tx-avatar">{{ strtoupper(substr($tx->buyer_name, 0, 1)) }}</div>
                                            <div>
                                                <span class="tx-name">{{ $tx->buyer_name }}</span>
                                                <span class="tx-date">{{ \Carbon\Carbon::parse($tx->created_at)->format('d/m/Y') }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td data-label="PRODUK">
                                        <div class="tx-item-title">{{ $tx->product_title }}</div>
                                    </td>
                                    <td data-label="STATUS">
                                        @if($tx->status === 'success')
                                            <span class="tx-status"><i class="fas fa-check"></i> Sukses</span>
                                        @elseif($tx->status === 'pending')
                                            <span class="tx-status pending"><i class="fas fa-clock"></i> Pending</span>
                                        @else
                                            <span class="tx-status failed"><i class="fas fa-times"></i> {{ ucfirst($tx->status) }}</span>
                                        @endif
                                    </td>
                                    <td data-label="AKSI">
                                        <a href="{{ route('admin.orders') }}" class="tx-action"><i class="fas fa-arrow-right"></i></a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" style="text-align: center; padding: 30px; color: #64748b;">Belum ada transaksi terakhir.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div> <!-- END LEFT COLUMN -->

        <!-- === RIGHT COLUMN === -->
        <div class="dashboard-right">
            
            <!-- User Profile Widget -->
            <div class="widget-card profile-widget">
                <i class="fas fa-ellipsis-v profile-dots" onclick="toggleProfileDropdown()"></i>
                
                <div class="profile-avatar-circle">
                    <div class="avatar-inner">
                        @if($appearance && $appearance->profile_image)
                            <img src="{{ asset('storage/' . $appearance->profile_image) }}" alt="Profile">
                        @else
                            <i class="fas fa-user"></i>
                        @endif
                    </div>
                    <div class="completion-badge">100%</div>
                </div>
                
                <h3>Selamat Datang, {{ Auth::user()->name }}!</h3>
                <p>Kelola penjualan dan raih target Anda.</p>
                
                <!-- Chart (integrated inside profile widget for compact UI like reference) -->
                <div class="chart-widget-body">
                    <div class="stats-numbers">
                        <span>Views: <strong id="totalViews" style="color: #5A5BF1;">{{ $totalViews }}</strong></span>
                        <span>Clicks: <strong id="totalClicks" style="color: #5A5BF1;">{{ $totalClicks }}</strong></span>
                    </div>
                    <div class="chart-wrapper">
                        <canvas id="statsChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Recent Activities Widget -->
            <div class="widget-card activities-widget">
                <h3>
                    Aktivitas Terbaru 
                    <div class="add-btn"><i class="fas fa-plus"></i></div>
                </h3>
                
                <div class="activities-list">
                    @forelse($recentActivities ?? [] as $activity)
                        <div class="activity-item">
                            <div class="activity-left">
                                <div class="activity-icon" style="background: {{ $activity['icon_bg'] }}; color: {{ $activity['icon_color'] }};">
                                    <i class="{{ $activity['icon'] }}"></i>
                                </div>
                                <div class="activity-info">
                                    <span class="title">{{ $activity['title'] }}</span>
                                    <span class="desc">{!! strip_tags($activity['message']) !!}</span>
                                </div>
                            </div>
                            <a href="{{ $activity['url'] }}" class="activity-action">Lihat</a>
                        </div>
                    @empty
                        <div style="text-align: center; color: #64748b; font-size: 12px; padding: 20px 0;">
                            Belum ada aktivitas.
                        </div>
                    @endforelse
                </div>
                
                <a href="{{ route('admin.orders') }}" class="btn-see-all">Lihat Semua Pesanan</a>
            </div>

        </div> <!-- END RIGHT COLUMN -->
        
    </div>
</div>
@endsection

@push("scripts")
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function() {
    function initBeranda() {
        const chartEl = document.getElementById('statsChart');
        if (!chartEl) return;

        const ctx = chartEl.getContext('2d');
        let myChart = null;

        function updateChart() {
            const params = new URLSearchParams();
            // Fetch default last 7 days from API

            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

            fetch(`{{ route('admin.chart-data') }}?${params.toString()}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then((response) => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then((data) => {
                const totalViewsEl = document.getElementById('totalViews');
                const totalClicksEl = document.getElementById('totalClicks');

                if (totalViewsEl && data.views) {
                    const sumViews = data.views.reduce((a, b) => a + b, 0);
                    totalViewsEl.textContent = sumViews;
                }
                if (totalClicksEl && data.clicks) {
                    totalClicksEl.textContent = data.clicks.reduce((a, b) => a + b, 0);
                }

                if (myChart) {
                    myChart.data.labels = data.labels;
                    myChart.data.datasets[0].data = data.views;
                    myChart.data.datasets[1].data = data.clicks;
                    myChart.update();
                } else {
                    myChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.labels,
                            datasets: [
                                {
                                    label: 'Views',
                                    data: data.views,
                                    borderColor: '#5A5BF1',
                                    backgroundColor: 'rgba(90, 91, 241, 0.08)',
                                    fill: true,
                                    tension: 0.4,
                                    borderWidth: 2,
                                    pointRadius: 0,
                                    pointHoverRadius: 4
                                },
                                {
                                    label: 'Clicks',
                                    data: data.clicks,
                                    borderColor: '#3B82F6',
                                    backgroundColor: 'rgba(59, 130, 246, 0.08)',
                                    fill: true,
                                    tension: 0.4,
                                    borderWidth: 2,
                                    pointRadius: 0,
                                    pointHoverRadius: 4
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false } // Hide legend for compact UI
                            },
                            scales: {
                                y: {
                                    display: false // Hide Y axis for compact UI
                                },
                                x: {
                                    display: false // Hide X axis for compact UI
                                }
                            },
                            layout: {
                                padding: 0
                            }
                        }
                    });
                }
            })
            .catch((error) => console.error('Error fetching chart data:', error));
        }

        updateChart();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initBeranda);
    } else {
        initBeranda();
    }
    document.addEventListener('turbo:load', initBeranda);
})();
</script>
@endpush
