@extends("layouts.admin")

@section("page_title", __('admin.dashboard_title'))

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
            alert(@json(__('admin.link_copied')));
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
            const topStatViewsEl = document.getElementById('topStatViews');
            const totalClicksEl = document.getElementById('totalClicks');

            if (totalViewsEl && data.views) {
                const sumViews = data.views.reduce((a, b) => a + b, 0);
                totalViewsEl.textContent = sumViews;
                if (topStatViewsEl) topStatViewsEl.textContent = sumViews;
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
});
</script>
@endpush
