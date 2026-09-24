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
                        {{ __('dashboard.suspended_title') }}
                    </h2>
                    <p style="font-size: 13px; color: #64748b; line-height: 1.5;">
                        {{ __('dashboard.suspended_desc') }}
                    </p>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 14px 18px; margin-bottom: 20px; font-size: 13px;">
                <div>
                    <strong style="color: #991b1b; display: block; margin-bottom: 2px;"><i class="fas fa-info-circle"></i> {{ __('dashboard.suspend_reason') }}</strong>
                    <span style="color: #475569;">{{ Auth::user()->suspend_reason ?? __('dashboard.suspend_default_reason') }}</span>
                </div>
                <div>
                    <strong style="color: #991b1b; display: block; margin-bottom: 2px;"><i class="fas fa-clock"></i> {{ __('dashboard.suspend_until') }}</strong>
                    <span style="color: #475569; font-weight: 700;">
                        @if(Auth::user()->suspended_until)
                            {{ __('dashboard.suspend_until_prefix') }} {{ Auth::user()->suspended_until->format('d M Y, H:i') }} ({{ Auth::user()->suspended_until->diffForHumans() }})
                        @else
                            {{ __('dashboard.suspend_permanent') }}
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
                            <div style="font-weight: 800; color: #166534; font-size: 14px;">{{ __('dashboard.appeal_pending_title') }}</div>
                            <span style="font-size: 11px; font-weight: 800; background: #dcfce7; color: #166534; padding: 2px 8px; border-radius: 6px;">{{ __('dashboard.appeal_count_prefix') }}{{ $totalAppealsCount ?? 1 }}/{{ $maxAppeals ?? 3 }}</span>
                        </div>
                        <div style="font-size: 13px; color: #15803d; line-height: 1.4;">
                            {{ __('dashboard.appeal_submitted_on') }}<strong>{{ $activeAppeal->created_at->format('d M Y, H:i') }}</strong> {{ __('dashboard.appeal_in_progress') }}
                        </div>
                    </div>
                </div>
            @elseif(isset($totalAppealsCount) && $totalAppealsCount >= ($maxAppeals ?? 3) && isset($activeAppeal) && $activeAppeal->status === 'rejected')
                <div style="background: #fff1f2; border: 1.5px solid #fecdd3; border-radius: 12px; padding: 18px 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <div style="font-weight: 800; color: #be123c; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-ban"></i> {{ __('dashboard.appeal_limit_reached') }}
                        </div>
                        <span style="font-size: 11px; font-weight: 800; background: #fee2e2; color: #991b1b; padding: 2px 8px; border-radius: 6px;">{{ __('dashboard.appeal_3_of_3') }}</span>
                    </div>
                    <div style="font-size: 13px; color: #4c0519; line-height: 1.5; margin-bottom: 10px;">
                        <strong>{{ __('dashboard.admin_last_note') }}</strong> {{ $activeAppeal->admin_notes ?? __('dashboard.appeal_reject_default') }}
                    </div>
                    <div style="font-size: 12px; color: #64748b; background: #ffffff; padding: 10px 14px; border-radius: 8px; border: 1px solid #f1f5f9; line-height: 1.5;">
                        <i class="fas fa-info-circle" style="color: #64748b;"></i> {{ __('dashboard.appeal_max_reached_desc_1') }}<a href="{{ route('contact.form') }}" style="color: #5A5BF1; font-weight: 700; text-decoration: underline;">{{ __('dashboard.contact_us') }}</a>.
                    </div>
                </div>
            @elseif(isset($activeAppeal) && $activeAppeal->status === 'rejected')
                <div style="background: #fff1f2; border: 1.5px solid #fecdd3; border-radius: 12px; padding: 16px 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                        <div style="font-weight: 800; color: #be123c; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-times-circle"></i> {{ __('dashboard.appeal_previously_rejected') }}
                        </div>
                        <span style="font-size: 11px; font-weight: 800; background: #fee2e2; color: #991b1b; padding: 2px 8px; border-radius: 6px;">{{ __('dashboard.appeal_count_prefix') }}{{ $totalAppealsCount }}/{{ $maxAppeals ?? 3 }}</span>
                    </div>
                    <div style="font-size: 13px; color: #4c0519; line-height: 1.4; margin-bottom: 12px;">
                        <strong>{{ __('dashboard.admin_note') }}</strong> {{ $activeAppeal->admin_notes ?? __('dashboard.appeal_reject_default') }}
                    </div>

                    @if(isset($canSubmitAppeal) && !$canSubmitAppeal)
                        {{-- Cooldown 1 Hari Aktif --}}
                        <div style="background: #fefce8; border: 1.5px solid #fef08a; border-radius: 10px; padding: 12px 16px; margin-top: 10px;">
                            <div style="font-weight: 700; color: #854d0e; font-size: 13px; display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                                <i class="fas fa-clock"></i> {{ __('dashboard.cooldown_title') }}
                            </div>
                            <div style="font-size: 12px; color: #a16207; line-height: 1.5;">
                                {{ __('dashboard.cooldown_desc_1') }}<strong>{{ $remainingCooldownText }}</strong> {{ __('dashboard.cooldown_desc_2') }}{{ $cooldownUntil ? $cooldownUntil->format('d M Y, H:i') : '-' }} WIB).
                            </div>
                            <div style="font-size: 11px; color: #a16207; margin-top: 6px;">
                                <i class="fas fa-shield-alt"></i> {{ __('dashboard.remaining_appeals_prefix') }}<strong>{{ $remainingAttempts ?? 1 }} kali</strong> lagi.
                            </div>
                        </div>
                    @else
                        {{-- Form Banding Ulang --}}
                        <p style="font-size: 12px; color: #64748b; margin-bottom: 10px;">
                            {{ __('dashboard.you_have_remaining') }}<strong>{{ $remainingAttempts ?? 1 }}</strong> {{ __('dashboard.appeal_chances') }}
                        </p>
                        
                        <form action="{{ route('admin.appeal.store') }}" method="POST">
                            @csrf
                            <div style="margin-bottom: 12px;">
                                <textarea name="appeal_reason" rows="3" placeholder="{{ __('dashboard.appeal_placeholder_2') }}" style="width: 100%; padding: 12px 14px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 13px; font-family: inherit; outline: none; box-sizing: border-box;" required></textarea>
                            </div>
                            <button type="submit" style="background: #5A5BF1; color: #fff; border: none; border-radius: 10px; padding: 10px 20px; font-weight: 700; font-size: 13px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                                <i class="fas fa-paper-plane"></i> {{ __('dashboard.submit_appeal_retry') }}{{ ($totalAppealsCount ?? 0) + 1 }}{{ __('dashboard.of_3') }}
                            </button>
                        </form>
                    @endif
                </div>
            @else
                <!-- Initial Appeal Form -->
                <div style="border-top: 1px solid #f1f5f9; padding-top: 18px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <h3 style="font-size: 15px; font-weight: 800; color: #1e293b; margin: 0;">
                            <i class="fas fa-file-signature" style="color: #5A5BF1;"></i> {{ __('dashboard.submit_appeal_title') }}
                        </h3>
                        <span style="font-size: 11px; font-weight: 800; background: #EEF0FE; color: #5A5BF1; padding: 2px 8px; border-radius: 6px;">{{ __('dashboard.chance_1_of_3') }}</span>
                    </div>
                    <p style="font-size: 12px; color: #64748b; margin-bottom: 12px;">
                        {{ __('dashboard.appeal_initial_desc') }}
                    </p>
                    <form action="{{ route('admin.appeal.store') }}" method="POST">
                        @csrf
                        <div style="margin-bottom: 12px;">
                            <textarea name="appeal_reason" rows="3" placeholder="{{ __('dashboard.appeal_placeholder_1') }}" style="width: 100%; padding: 12px 14px; border: 1.5px solid #cbd5e1; border-radius: 10px; font-size: 13px; font-family: inherit; outline: none; box-sizing: border-box;" required></textarea>
                        </div>
                        <button type="submit" style="background: #5A5BF1; color: #fff; border: none; border-radius: 10px; padding: 11px 22px; font-weight: 700; font-size: 13px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                            <i class="fas fa-paper-plane"></i> {{ __('dashboard.submit_appeal_btn') }}
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
                <h2>{{ __('dashboard.hero_title') }}</h2>
                <a href="{{ route('admin.digital-products.create') }}" class="hero-btn">
                    {{ __('dashboard.create_product_now') }} <div class="icon-arrow"><i class="fas fa-chevron-right"></i></div>
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
                        <div class="stat-lbl">{{ __('dashboard.total_earnings') }}</div>
                    </div>
                </div>
                <div class="stat-card-item">
                    <div class="stat-icon pink">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-val">{{ number_format($lifetimeOrders) }}</div>
                        <div class="stat-lbl">{{ __('dashboard.total_sales') }}</div>
                    </div>
                </div>
                <div class="stat-card-item">
                    <div class="stat-icon blue">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-val">{{ number_format($totalProducts) }}</div>
                        <div class="stat-lbl">{{ __('dashboard.active_digital_products') }}</div>
                    </div>
                </div>
            </div>

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
                                @php
                                    $dashImg = null;
                                    // Karena dari DB::table(), media_files mungkin berupa string JSON
                                    $mediaFiles = is_string($product->media_files) ? json_decode($product->media_files, true) : $product->media_files;
                                    if (is_array($mediaFiles) && count($mediaFiles) > 0) {
                                        $dashImg = $mediaFiles[0]['url'] ?? $mediaFiles[0]['path'] ?? null;
                                    }
                                    if (!$dashImg && $product->image) {
                                        $dashImg = $product->image;
                                    }
                                @endphp

                                @if($dashImg)
                                    <img src="{{ Storage::url($dashImg) }}" alt="{{ $product->title }}">
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

            <!-- Recent Transactions (Your Lesson Alternative) -->
            <div class="recent-transactions-section">
                <div class="section-header">
                    <h3>{{ __('dashboard.recent_transactions') }}</h3>
                    <a href="{{ route('admin.orders') }}" class="see-all">{{ __('dashboard.see_all') }}</a>
                </div>
                
                <div class="transactions-list">
                    <table class="tx-table">
                        <thead>
                            <tr>
                                <th>{{ __('dashboard.th_buyer') }}</th>
                                <th>{{ __('dashboard.th_product') }}</th>
                                <th>{{ __('dashboard.th_status') }}</th>
                                <th>{{ __('dashboard.th_action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTransactions ?? [] as $tx)
                                <tr class="tx-row">
                                    <td class="td-buyer" data-label="{{ __('dashboard.th_buyer') }}">
                                        <div class="tx-buyer">
                                            <div class="tx-avatar">{{ strtoupper(substr($tx->buyer_name, 0, 1)) }}</div>
                                            <div class="tx-buyer-info">
                                                <span class="tx-name">{{ $tx->buyer_name }}</span>
                                                <span class="tx-date">{{ \Carbon\Carbon::parse($tx->created_at)->format('d/m/Y') }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="td-product" data-label="{{ __('dashboard.th_product') }}">
                                        <div class="tx-item-title">{{ $tx->product_title }}</div>
                                    </td>
                                    <td class="td-status" data-label="{{ __('dashboard.th_status') }}">
                                        @php
                                            $txStatus = $tx->status instanceof \BackedEnum ? $tx->status->value : (string) $tx->status;
                                        @endphp
                                        @if($txStatus === 'success')
                                            <span class="tx-status"><i class="fas fa-check"></i> {{ __('dashboard.status_success') }}</span>
                                        @elseif($txStatus === 'pending')
                                            <span class="tx-status pending"><i class="fas fa-clock"></i> {{ __('dashboard.status_pending') }}</span>
                                        @else
                                            <span class="tx-status failed"><i class="fas fa-times"></i> {{ ucfirst($txStatus) }}</span>
                                        @endif
                                    </td>
                                    <td class="td-action" data-label="{{ __('dashboard.th_action') }}">
                                        <a href="{{ route('admin.orders') }}" class="tx-action" aria-label="Detail Pesanan"><i class="fas fa-arrow-right"></i></a>
                                    </td>
                                </tr>
                            @empty
                                <tr class="tx-empty-row">
                                    <td colspan="4" class="td-empty" style="text-align: center; padding: 30px; color: #64748b;">{{ __('dashboard.no_recent_transactions') }}</td>
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
                        @if(Auth::check() && Auth::user()->avatar)
                            <img src="{{ Storage::url(Auth::user()->avatar) }}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <i class="fas fa-user"></i>
                        @endif
                    </div>
                </div>
                
                <h3>{{ __('dashboard.welcome') }}{{ Auth::user()->username ?? Auth::user()->name }}!</h3>
                <p>{{ __('dashboard.profile_desc') }}</p>
                
                <!-- Chart (integrated inside profile widget for compact UI like reference) -->
                <div class="chart-widget-body">
                    <div class="stats-numbers">
                        <span>{{ __('dashboard.views') }}<strong id="totalViews" style="color: #5A5BF1;">{{ $totalViews }}</strong></span>
                        <span>{{ __('dashboard.orders') }}<strong id="totalClicks" style="color: #5A5BF1;">{{ $totalClicks }}</strong></span>
                    </div>
                    <div class="chart-wrapper">
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
<script src="{{ asset('js/apexcharts.min.js') }}"></script>
<script>
(function() {
    function initBeranda() {
        const chartEl = document.querySelector('#statsChart');
        if (!chartEl) return;

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
                if (myChart) {
                    myChart.updateSeries([
                        { data: data.views },
                        { data: data.clicks }
                    ]);
                    myChart.updateOptions({
                        xaxis: { categories: data.labels }
                    });
                } else {
                    const chartContainer = document.querySelector("#statsChart");
                    chartContainer.innerHTML = ''; // Prevent duplicate charts on Turbo/PJAX reload

                    const options = {
                        series: [
                            { name: 'Views', data: data.views },
                            { name: 'Pesanan', data: data.clicks }
                        ],
                        chart: {
                            type: 'area',
                            height: 120,
                            width: '100%',
                            parentHeightOffset: 0,
                            sparkline: { enabled: true },
                            toolbar: { show: false }
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
                            categories: data.labels,
                            crosshairs: { width: 1 }
                        },
                        tooltip: {
                            fixed: { enabled: false },
                            x: { show: true },
                            marker: { show: true }
                        }
                    };
                    myChart = new ApexCharts(document.querySelector("#statsChart"), options);
                    myChart.render();
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
