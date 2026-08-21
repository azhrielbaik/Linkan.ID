@extends("layouts.admin")

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
                    <div>
                        <div style="font-weight: 800; color: #166534; font-size: 14px; margin-bottom: 2px;">Permohonan Banding Sedang Ditinjau</div>
                        <div style="font-size: 13px; color: #15803d; line-height: 1.4;">
                            Surat banding Anda yang diajukan pada <strong>{{ $activeAppeal->created_at->format('d M Y, H:i') }}</strong> sedang dalam proses evaluasi oleh tim Platform Admin.
                        </div>
                    </div>
                </div>
            @elseif(isset($activeAppeal) && $activeAppeal->status === 'rejected')
                <div style="background: #fff1f2; border: 1.5px solid #fecdd3; border-radius: 12px; padding: 16px 20px; margin-bottom: 18px;">
                    <div style="font-weight: 800; color: #be123c; font-size: 14px; margin-bottom: 4px; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-times-circle"></i> Permohonan Banding Sebelumnya Ditolak
                    </div>
                    <div style="font-size: 13px; color: #4c0519; line-height: 1.4; margin-bottom: 12px;">
                        <strong>Catatan Admin:</strong> {{ $activeAppeal->admin_notes ?? 'Tidak memenuhi syarat pemulihan akun.' }}
                    </div>
                    <p style="font-size: 12px; color: #64748b; margin-bottom: 10px;">Anda dapat mengirimkan klarifikasi atau penjelasan baru di bawah ini:</p>
                    
                    <form action="{{ route('admin.appeal.store') }}" method="POST">
                        @csrf
                        <div style="margin-bottom: 12px;">
                            <textarea name="appeal_reason" rows="3" placeholder="Jelaskan alasan atau klarifikasi tambahan Anda secara jelas dan lengkap..." style="width: 100%; padding: 12px 14px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 13px; font-family: inherit; outline: none; box-sizing: border-box;" required></textarea>
                        </div>
                        <button type="submit" style="background: #5A5BF1; color: #fff; border: none; border-radius: 10px; padding: 10px 20px; font-weight: 700; font-size: 13px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                            <i class="fas fa-paper-plane"></i> Ajukan Banding Ulang
                        </button>
                    </form>
                </div>
            @else
                <!-- Initial Appeal Form -->
                <div style="border-top: 1px solid #f1f5f9; padding-top: 18px;">
                    <h3 style="font-size: 15px; font-weight: 800; color: #1e293b; margin-bottom: 6px;">
                        <i class="fas fa-file-signature" style="color: #5A5BF1;"></i> Ajukan Banding Penangguhan Akun
                    </h3>
                    <p style="font-size: 12px; color: #64748b; margin-bottom: 12px;">
                        Jika Anda merasa penangguhan ini adalah kekeliruan atau telah menyelesaikan masalah terkait, silakan kirimkan surat permohonan banding ke Admin Platform:
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
        <div class="announcement-banner-wrapper" style="margin-bottom: 20px; display: flex; flex-direction: column; gap: 12px;">
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

    <div class="account-section">
        <div class="profile">
            <div class="profile-image" style="width: 54px; height: 54px; min-width: 54px; min-height: 54px; max-width: 54px; max-height: 54px; border-radius: 50%; overflow: hidden; flex-shrink: 0; display: flex; align-items: center; justify-content: center;">
                @if($appearance && $appearance->profile_image)
                    <img src="{{ asset('storage/' . $appearance->profile_image) }}" alt="Profile" style="width: 54px; height: 54px; object-fit: cover; border-radius: 50%; display: block;">
                @else
                    <i class="fas fa-user"></i>
                @endif
            </div>
                    <div class="profile-info">
                        <h3>{{ $appearance && $appearance->name ? $appearance->name : Auth::user()->name }}</h3>
                        <a href="{{ route('track.view', ['username' => Auth::user()->username]) }}" style="color: #FF9040;">
                            {{ url('/linkan.id/' . Auth::user()->username) }}
                        </a>
                    </div>
                    <button
                      class="share-button"
                      onclick="copyToClipboard('{{ route('track.view', ['username' => Auth::user()->username]) }}')"
                    >
                        <i class="fas fa-share-alt"></i>
                    </button>
                </div>
                <div class="start-creating">{{ __('admin.start_creating_now') }}</div>
                <div class="action-buttons">
                    <a href="{{ route('admin.mylinkan') }}" class="action-button">
                        <i class="fas fa-qrcode"></i> {{ __('admin.add_linkan') }}
                    </a>
                    <a href="{{ route('admin.digital-products.create') }}" class="action-button">
                        <i class="fas fa-box"></i> {{ __('admin.digital_product') }}
                    </a>
                    <a href="{{ route('about') }}" class="action-button">
                        <i class="fas fa-headset"></i> {{ __('admin.about_us') }}
                    </a>
                </div>
            </div>

    <!-- EARNINGS SECTION -->
    <div class="earnings-section">
        <div class="earnings-header">
            <span>{{ __('admin.earnings') }}</span>
            <a href="{{ route('admin.payout.index') }}" style="color: #ffffff;"><i class="fas fa-cog"></i></a>
        </div>
        <div class="earnings-amount">IDR {{ number_format($totalEarnings, 0, ',', '.') }}</div>
    </div>

    <!-- STATS CHART SECTION -->
    <div class="stats-section">
        <div class="stats-header">
            <h3>{{ __('admin.total_click_views') }}</h3>
            <div class="date-range-selector">
                <input type="date" id="startDate" class="date-input" />
                <span>{{ __('admin.to') }}</span>
                <input type="date" id="endDate" class="date-input" />
                <button class="apply-date" onclick="applyDateFilter()">{{ __('admin.apply') }}</button>
            </div>
        </div>
        <div class="stats-numbers">
            <span>{{ __('admin.views') }} <strong id="totalViews" style="color: #5A5BF1;">{{ $totalViews }}</strong></span>
            <span>{{ __('admin.clicks') }} <strong id="totalClicks" style="color: #5A5BF1;">{{ $totalClicks }}</strong></span>
        </div>
        <div class="stats-chart">
            <canvas id="statsChart"></canvas>
        </div>
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
        let startDate = null;
        let endDate = null;

        window.applyDateFilter = function() {
            const startDateEl = document.getElementById('startDate');
            const endDateEl = document.getElementById('endDate');
            if (startDateEl && endDateEl) {
                startDate = startDateEl.value;
                endDate = endDateEl.value;
                updateChart();
            }
        };

        window.copyToClipboard = function(text) {
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).then(() => {
                    alert(@json(__('admin.link_copied')));
                }).catch(() => {
                    fallbackCopyText(text);
                });
            } else {
                fallbackCopyText(text);
            }
        };

        function fallbackCopyText(text) {
            const textArea = document.createElement("textarea");
            textArea.value = text;
            textArea.style.position = "fixed";
            textArea.style.left = "-999999px";
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            try {
                document.execCommand('copy');
                alert(@json(__('admin.link_copied')));
            } catch (err) {
                console.error('Fallback copy failed', err);
            }
            document.body.removeChild(textArea);
        }

        function updateChart() {
            const params = new URLSearchParams();
            if (startDate) params.append('start_date', startDate);
            if (endDate) params.append('end_date', endDate);

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
                                    borderWidth: 3,
                                    pointBackgroundColor: '#5A5BF1',
                                    pointBorderColor: '#fff',
                                    pointBorderWidth: 2,
                                    pointRadius: 4
                                },
                                {
                                    label: 'Clicks',
                                    data: data.clicks,
                                    borderColor: '#3B82F6',
                                    backgroundColor: 'rgba(59, 130, 246, 0.08)',
                                    fill: true,
                                    tension: 0.4,
                                    borderWidth: 3,
                                    pointBackgroundColor: '#3B82F6',
                                    pointBorderColor: '#fff',
                                    pointBorderWidth: 2,
                                    pointRadius: 4
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top',
                                    labels: {
                                        font: { family: 'Plus Jakarta Sans', weight: '600' }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: { color: 'rgba(0, 0, 0, 0.04)' }
                                },
                                x: {
                                    grid: { display: false }
                                }
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
