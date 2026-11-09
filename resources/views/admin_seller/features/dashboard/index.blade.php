@extends("admin_seller.layouts.app")

@section("page_title", __('admin.dashboard_title'))

@push("styles")
<link rel="stylesheet" href="{{ asset('css/pages/beranda.css') }}">
@endpush

@section("content")
<div class="dashboard-beranda-page">

    @include('admin_seller.features.dashboard.partials.suspension_banner')

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
                <h2>{{ __('dashboard.hero_title') }}</h2>
                <a href="{{ route('admin.digital-products.create') }}" class="hero-btn">
                    {{ __('dashboard.create_product_now') }} <div class="icon-arrow"><i class="fas fa-chevron-right"></i></div>
                </a>
            </div>

            @include('admin_seller.features.dashboard.partials.scorecards')

            <!-- Recent Products (Continue Watching Alternative) -->
            <div class="recent-products-section">
                <div class="section-header">
                    <h3>{{ __('dashboard.recent_digital_products') }}</h3>
                    <div class="nav-arrows">
                        <div class="nav-arrow"><i class="fas fa-chevron-left"></i></div>
                        <div class="nav-arrow active"><i class="fas fa-chevron-right"></i></div>
                    </div>
                </div>
                
                <div class="recent-products-row">
                    @forelse($recentProducts ?? [] as $product)
                        <div class="product-course-card">
                            <div class="product-img-box">
                                @if($product->image_url)
                                    <img src="{{ $product->image_url }}" alt="{{ $product->title }}">
                                @else
                                    <i class="fas fa-image"></i>
                                @endif
                            </div>
                            <span class="product-tag purple">{{ __('dashboard.tag_digital_product') }}</span>
                            <h4 class="product-title">{{ $product->title }}</h4>
                            <div class="product-footer">
                                <div class="price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                <div class="date">{{ $product->created_at->format('d M') }}</div>
                            </div>
                        </div>
                    @empty
                        <div style="grid-column: span 3; text-align: center; padding: 40px; background: #fff; border-radius: 16px; border: 1px solid #f1f5f9; color: #64748b; font-size: 14px;">
                            <i class="fas fa-box-open" style="font-size: 24px; color: #cbd5e1; margin-bottom: 12px; display: block;"></i>
                            {{ __('dashboard.no_digital_products') }}
                        </div>
                    @endforelse
                </div>
            </div>

            @include('admin_seller.features.dashboard.partials.recent_transactions')

        </div> <!-- END LEFT COLUMN -->

        <!-- === RIGHT COLUMN === -->
        <div class="dashboard-right">
            
            <!-- User Profile Widget -->
            <div class="widget-card profile-widget">
                <i class="fas fa-ellipsis-v profile-dots" onclick="toggleProfileDropdown()"></i>
                
                <div class="profile-avatar-circle">
                    <div class="avatar-inner">
                        @if(Auth::check() && Auth::user()->avatar)
                            <img src="{{ Storage::url(Auth::user()->avatar) }}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <i class="fas fa-user"></i>
                        @endif
                    </div>
                </div>
                
                <h3>{{ __('dashboard.welcome') }}{{ Auth::user()->name ?? Auth::user()->username }}!</h3>
                <p>{{ __('dashboard.profile_desc') }}</p>
                
                <!-- Chart (integrated inside profile widget for compact UI like reference) -->
                <div class="chart-widget-body">
                    <div class="stats-numbers">
                        <span>{{ __('dashboard.views') }}<strong id="totalViews" style="color: #5A5BF1;">{{ $totalViews }}</strong></span>
                        <span>{{ __('dashboard.orders') }}<strong id="totalClicks" style="color: #5A5BF1;">{{ $totalClicks }}</strong></span>
                    </div>
                    <div class="chart-wrapper" id="dashboardChartWrapper" tabindex="0" role="region" aria-label="Grafik Statistik Pengunjung dan Pesanan">
                        <div id="statsChart" style="min-height: 120px;"></div>
                    </div>
                </div>
            </div>

            <!-- Recent Activities Widget -->
            <div class="widget-card activities-widget">
                <h3>
                    {{ __('dashboard.recent_activities') }} 
                    <div class="add-btn"><i class="fas fa-plus"></i></div>
                </h3>
                
                <div class="activities-list">
                    @php
                        $baseStyleMap = [
                            'transaction'  => ['icon' => 'fas fa-shopping-cart', 'bg' => '#EEF0FE', 'color' => '#5A5BF1'],
                            'system_alert' => ['icon' => 'fas fa-exclamation-triangle', 'bg' => '#FEF2F2', 'color' => '#EF4444'],
                            'appeal'       => ['icon' => 'fas fa-balance-scale', 'bg' => '#EFF6FF', 'color' => '#3B82F6'],
                            'broadcast'    => ['icon' => 'fas fa-bullhorn', 'bg' => '#FFFBEB', 'color' => '#F59E0B'],
                        ];
                    @endphp
                    @forelse($recentActivities ?? [] as $activity)
                        @php
                            $type = $activity['type'] ?? 'default';
                            $status = $activity['status'] ?? 'default';
                            if ($type === 'payout') {
                                $isFailed = in_array($status, ['rejected', 'failed']);
                                $actStyle = [
                                    'icon'  => 'fas fa-wallet',
                                    'bg'    => $isFailed ? '#FEF2F2' : '#ECFDF5',
                                    'color' => $isFailed ? '#EF4444' : '#10B981',
                                ];
                            } else {
                                $actStyle = $baseStyleMap[$type] ?? [
                                    'icon'  => 'fas fa-bell',
                                    'bg'    => '#F1F5F9',
                                    'color' => '#64748B',
                                ];
                            }
                        @endphp
                        <div class="activity-item">
                            <div class="activity-left">
                                <div class="activity-icon" style="background: {{ $actStyle['bg'] }}; color: {{ $actStyle['color'] }};">
                                    <i class="{{ $actStyle['icon'] }}"></i>
                                </div>
                                <div class="activity-info">
                                    <span class="title">{{ $activity['title'] }}</span>
                                    <span class="desc">{{ $activity['message'] }}</span>
                                </div>
                            </div>
                            <a href="{{ $activity['url'] }}" class="activity-action">{{ __('dashboard.view') }}</a>
                        </div>
                    @empty
                        <div style="text-align: center; color: #64748b; font-size: 12px; padding: 20px 0;">
                            {{ __('dashboard.no_activities') }}
                        </div>
                    @endforelse
                </div>
                
                <a href="{{ route('admin.orders') }}" class="btn-see-all">{{ __('dashboard.see_all') }} Pesanan</a>
            </div>

        </div> <!-- END RIGHT COLUMN -->
        
    </div>
</div>
@endsection

@push("scripts")
<script>
(function() {
    function ensureApexCharts(callback, maxWaitMs = 5000) {
        if (typeof window.ApexCharts !== 'undefined') {
            callback();
            return;
        }
        const startTime = Date.now();
        const poll = setInterval(function() {
            if (typeof window.ApexCharts !== 'undefined') {
                clearInterval(poll);
                callback();
            } else if (Date.now() - startTime > maxWaitMs) {
                clearInterval(poll);
                console.error('Dashboard: ApexCharts failed to load within timeout');
            }
        }, 40);
    }

    let myChart = null;
    let fullChartData = null;
    let currentZoomIndex = 2; // Default: 7 Hari
    let panOffset = 0; // 0 = berlabuh di data terbaru (paling kanan)

    // Tingkat zoom yang terstruktur & intuitif untuk metrik bisnis
    const ZOOM_LEVELS = [
        { days: 3,  label: '3 Hari' },
        { days: 5,  label: '5 Hari' },
        { days: 7,  label: '7 Hari (Default)' },
        { days: 10, label: '10 Hari' },
        { days: 14, label: '14 Hari' },
        { days: 21, label: '21 Hari' },
        { days: 30, label: '30 Hari' },
        { days: 45, label: '45 Hari' },
        { days: 60, label: 'Keseluruhan' }
    ];
    const DEFAULT_ZOOM_INDEX = 2; // 7 Hari (Default)

    function getVisibleSlice() {
        if (!fullChartData || !fullChartData.labels || fullChartData.labels.length === 0) {
            return { labels: [], views: [], clicks: [], targetDays: 0, totalPoints: 0 };
        }

        const totalPoints = fullChartData.labels.length;
        const targetDays = Math.min(totalPoints, ZOOM_LEVELS[currentZoomIndex].days);
        const maxOffset = Math.max(0, totalPoints - targetDays);

        // Clamp panOffset
        panOffset = Math.max(0, Math.min(maxOffset, panOffset));

        const startIndex = totalPoints - targetDays - panOffset;
        const endIndex = startIndex + targetDays;

        return {
            labels: fullChartData.labels.slice(startIndex, endIndex),
            views: fullChartData.views.slice(startIndex, endIndex),
            clicks: fullChartData.clicks.slice(startIndex, endIndex),
            targetDays: targetDays,
            totalPoints: totalPoints
        };
    }

    function renderZoomView(animate = true) {
        if (!fullChartData || !myChart) return;
        const slice = getVisibleSlice();

        myChart.updateOptions({
            xaxis: {
                categories: slice.labels
            },
            series: [
                { name: 'Views', data: slice.views },
                { name: 'Pesanan', data: slice.clicks }
            ]
        }, false, animate);
    }

    function zoomIn() {
        if (currentZoomIndex > 0) {
            currentZoomIndex--;
            renderZoomView(true);
        }
    }

    function zoomOut() {
        if (!fullChartData) return;
        const totalPoints = fullChartData.labels.length;
        if (currentZoomIndex < ZOOM_LEVELS.length - 1 && ZOOM_LEVELS[currentZoomIndex].days < totalPoints) {
            currentZoomIndex++;
            renderZoomView(true);
        }
    }

    function resetZoom() {
        currentZoomIndex = DEFAULT_ZOOM_INDEX;
        panOffset = 0;
        renderZoomView(true);
    }

    function initBeranda() {
        const chartContainer = document.querySelector('#statsChart');
        const wrapper = document.querySelector('#dashboardChartWrapper') || document.querySelector('.chart-wrapper');
        if (!chartContainer || !wrapper) return;

        // Reset state
        currentZoomIndex = DEFAULT_ZOOM_INDEX;
        panOffset = 0;

        function updateChart() {
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

            fetch(`{{ route('admin.chart-data') }}?days=60`, {
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
                fullChartData = data;

                ensureApexCharts(function() {
                    const container = document.querySelector('#statsChart');
                    if (!container) return;

                    const slice = getVisibleSlice();

                    if (myChart) {
                        try {
                            renderZoomView(false);
                            return;
                        } catch (e) {
                            myChart.destroy();
                            myChart = null;
                        }
                    }

                    container.innerHTML = '';
                    const options = {
                        series: [
                            { name: 'Views', data: slice.views },
                            { name: 'Pesanan', data: slice.clicks }
                        ],
                        chart: {
                            type: 'area',
                            height: 120,
                            width: '100%',
                            parentHeightOffset: 0,
                            sparkline: { enabled: true },
                            toolbar: { show: false },
                            zoom: { enabled: false },
                            selection: { enabled: false },
                            animations: {
                                enabled: true,
                                easing: 'easeinout',
                                speed: 250,
                                dynamicAnimation: {
                                    enabled: true,
                                    speed: 200
                                }
                            }
                        },
                        colors: ['#FF9040', '#3B82F6'],
                        fill: {
                            type: 'gradient',
                            gradient: {
                                shadeIntensity: 1,
                                opacityFrom: 0.4,
                                opacityTo: 0.05,
                                stops: [0, 90, 100]
                            }
                        },
                        stroke: {
                            curve: 'smooth',
                            width: 2
                        },
                        xaxis: {
                            categories: slice.labels,
                            crosshairs: { width: 1 }
                        },
                        tooltip: {
                            fixed: { enabled: false },
                            x: { show: true },
                            marker: { show: true }
                        }
                    };

                    myChart = new window.ApexCharts(container, options);
                    myChart.render();
                });
            })
            .catch((error) => console.error('Error fetching chart data:', error));
        }

        updateChart();

        // Bind Pure Interaction (Scroll Zoom & Drag Geser)
        bindZoomEvents(wrapper);
    }

    function bindZoomEvents(wrapper) {
        if (!wrapper || wrapper._zoomBound) return;
        wrapper._zoomBound = true;

        // 1. Desktop Wheel Scroll Zoom (Scroll Bawah = Zoom Out, Scroll Atas = Zoom In)
        let wheelAccumulator = 0;
        const WHEEL_THRESHOLD = 30;
        let lastZoomTime = 0;
        const ZOOM_COOLDOWN = 80;
        let wheelResetTimer = null;

        wrapper.addEventListener('wheel', function(e) {
            // Cegah zoom hanya jika perangkat layar sentuh murni tanpa pointer mouse
            const isPureTouch = window.matchMedia && window.matchMedia('(pointer: coarse)').matches && !window.matchMedia('(pointer: fine)').matches;
            if (isPureTouch) return;

            // Mencegah halaman utama bergulir (scroll) saat kursor berada di atas chart-wrapper
            e.preventDefault();

            const now = Date.now();
            wheelAccumulator += e.deltaY;

            clearTimeout(wheelResetTimer);
            wheelResetTimer = setTimeout(() => {
                wheelAccumulator = 0;
            }, 120);

            if (Math.abs(wheelAccumulator) >= WHEEL_THRESHOLD && (now - lastZoomTime) > ZOOM_COOLDOWN) {
                if (wheelAccumulator > 0) {
                    // Scroll ke bawah -> ZOOM OUT (melihat keseluruhan data lebih luas)
                    zoomOut();
                } else {
                    // Scroll ke atas -> ZOOM IN (memperbesar dan melihat detail lebih fokus)
                    zoomIn();
                }
                wheelAccumulator = 0;
                lastZoomTime = now;
            }
        }, { passive: false });

        // 2. Drag-to-pan / Geser horizontal saat berada pada rentang tertentu
        let isDragging = false;
        let startX = 0;
        let initialOffset = 0;

        wrapper.addEventListener('mousedown', function(e) {
            if (e.button !== 0 || !fullChartData) return;

            const slice = getVisibleSlice();
            if (slice.targetDays >= slice.totalPoints) return; // Tidak perlu pan jika semua data terlihat

            isDragging = true;
            startX = e.clientX;
            initialOffset = panOffset;
            wrapper.classList.add('is-panning');
        });

        window.addEventListener('mousemove', function(e) {
            if (!isDragging || !fullChartData) return;
            const dx = e.clientX - startX;
            // 20px pergeseran mouse = 1 hari bergeser
            const dayShift = Math.round(dx / 20);
            const totalPoints = fullChartData.labels.length;
            const targetDays = Math.min(totalPoints, ZOOM_LEVELS[currentZoomIndex].days);
            const maxOffset = Math.max(0, totalPoints - targetDays);

            panOffset = Math.max(0, Math.min(maxOffset, initialOffset + dayShift));
            renderZoomView(false);
        });

        window.addEventListener('mouseup', function() {
            if (isDragging) {
                isDragging = false;
                wrapper.classList.remove('is-panning');
            }
        });

        // 3. Double click untuk reset kembali ke 7 hari
        wrapper.ondblclick = function() {
            resetZoom();
        };

        // 4. Keyboard accessibility
        wrapper.addEventListener('keydown', function(e) {
            if (e.key === '+' || e.key === '=' || e.key === 'ArrowUp') {
                e.preventDefault();
                zoomIn();
            } else if (e.key === '-' || e.key === '_' || e.key === 'ArrowDown') {
                e.preventDefault();
                zoomOut();
            } else if (e.key === 'ArrowLeft') {
                e.preventDefault();
                panOffset = Math.min(fullChartData ? fullChartData.labels.length : 0, panOffset + 1);
                renderZoomView(false);
            } else if (e.key === 'ArrowRight') {
                e.preventDefault();
                panOffset = Math.max(0, panOffset - 1);
                renderZoomView(false);
            } else if (e.key.toLowerCase() === 'r' || e.key === 'Escape' || e.key === 'Home') {
                e.preventDefault();
                resetZoom();
            }
        });
    }

    if (!window._dashboardListenersBound) {
        window._dashboardListenersBound = true;
        document.addEventListener('turbo:load', initBeranda);
        document.addEventListener('DOMContentLoaded', initBeranda);
        document.addEventListener('turbo:before-cache', function() {
            if (myChart) {
                try { myChart.destroy(); } catch (e) {}
                myChart = null;
            }
            const chartEl = document.querySelector('#statsChart');
            if (chartEl) chartEl.innerHTML = '';
            const wrapper = document.querySelector('#dashboardChartWrapper') || document.querySelector('.chart-wrapper');
            if (wrapper) wrapper._zoomBound = false;
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initBeranda);
    } else {
        initBeranda();
    }
})();
</script>
@endpush
