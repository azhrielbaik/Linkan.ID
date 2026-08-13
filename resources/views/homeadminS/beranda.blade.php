@extends("layouts.admin")

@section("page_title", __('admin.overview_dashboard'))

@push("styles")
<link rel="stylesheet" href="{{ asset('css/pages/beranda.css') }}" data-turbo-track="reload">
@endpush

@section("content")
<div class="dashboard-beranda-page">



            <div class="account-section">
                <div class="profile">
                    <div class="profile-image">
                        @if($appearance && $appearance->profile_image)
                            <img src="{{ asset('storage/' . $appearance->profile_image) }}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
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

            <div class="earnings-section">
                <div class="earnings-header">
                    <span>{{ __('admin.earnings') }}</span>
                    <i class="fas fa-cog"></i>
                </div>
                <div class="earnings-amount">IDR {{ number_format($totalEarnings, 0, ',', '.') }}</div>
            </div>

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
                    <span>{{ __('admin.views') }} <span id="totalViews">{{ $totalViews }}</span></span>
                    <span>{{ __('admin.clicks') }} <span id="totalClicks">{{ $totalClicks }}</span></span>
                </div>
                <div class="stats-chart">
                    <canvas id="statsChart"></canvas>
                </div>
            </div>

            <div class="summary-section">
                <div class="summary-card">
                    <i class="fas fa-shopping-cart"></i>
                    <div class="label">{{ __('admin.lifetime_orders') }}</div>
                    <div class="number">{{ $lifetimeOrders }}</div>
                </div>
                <div class="summary-card">
                    <i class="fas fa-chart-line"></i>
                    <div class="label">{{ __('admin.lifetime_sales') }}</div>
                    <div class="number">{{ number_format($totalEarnings, 0, ',', '.') }}</div>
                </div>
                <div class="summary-card">
                    <i class="fas fa-box"></i>
                    <div class="label">{{ __('admin.my_blocks') }}</div>
                    <div class="number">{{ $totalProducts }}</div>
                </div>
            </div>

</div>
@endsection

@push("scripts")
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@push("scripts")
<script>
document.addEventListener('turbo:load', function() {
    const chartEl = document.getElementById('statsChart');
    if (!chartEl) return;

    const ctx = chartEl.getContext('2d');
    let myChart;
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
        navigator.clipboard.writeText(text).then(() => {
            alert('Link copied to clipboard!');
        }).catch((err) => {
            console.error('Failed to copy text: ', err);
        });
    };

    function updateChart() {
        const params = new URLSearchParams();
        if (startDate) params.append('start_date', startDate);
        if (endDate) params.append('end_date', endDate);

        fetch(`/get-chart-data?${params.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then((response) => response.json())
        .then((data) => {
            const totalViewsEl = document.getElementById('totalViews');
            const totalClicksEl = document.getElementById('totalClicks');
            const startDateEl = document.getElementById('startDate');
            const endDateEl = document.getElementById('endDate');

            if (totalViewsEl && data.views) {
                totalViewsEl.textContent = data.views.reduce((a, b) => a + b, 0);
            }
            if (totalClicksEl && data.clicks) {
                totalClicksEl.textContent = data.clicks.reduce((a, b) => a + b, 0);
            }

            if (startDateEl) startDateEl.value = data.start_date;
            if (endDateEl) endDateEl.value = data.end_date;

            startDate = data.start_date;
            endDate = data.end_date;

            if (myChart) {
                myChart.destroy();
            }

            myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [
                        {
                            label: 'Views',
                            data: data.views,
                            backgroundColor: '#ff4500',
                            borderRadius: 4,
                            maxBarThickness: 12,
                        },
                        {
                            label: 'Clicks',
                            data: data.clicks,
                            backgroundColor: '#4a90e2',
                            borderRadius: 4,
                            maxBarThickness: 12,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f0f0f0',
                            },
                        },
                        x: {
                            grid: {
                                display: false,
                            },
                        },
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            align: 'start',
                            labels: {
                                boxWidth: 12,
                                usePointStyle: true,
                                pointStyle: 'circle',
                            },
                        },
                    },
                },
            });
        })
        .catch((err) => {
            console.error('Error fetching chart data:', err);
        });
    }

    const today = new Date();
    const sevenDaysAgo = new Date();
    sevenDaysAgo.setDate(today.getDate() - 6);

    const startDateEl = document.getElementById('startDate');
    const endDateEl = document.getElementById('endDate');

    if (startDateEl && endDateEl) {
        startDateEl.value = sevenDaysAgo.toISOString().split('T')[0];
        endDateEl.value = today.toISOString().split('T')[0];

        startDate = startDateEl.value;
        endDate = endDateEl.value;
    }

    updateChart();
});
</script>
@endpush
