<!-- PERFORMANCE CARD -->
<div class="card performance-card">
    <div class="card-header">
        <div class="card-title">{{ __('shortlink.performance') }}</div>
        <div class="card-subtitle">{{ __('shortlink.data_preview') }}</div>
    </div>

    <div class="stats-row">
        <div class="stat-box">
            <div class="stat-icon stat-icon-orange"><i class="fas fa-chart-line"></i></div>
            <div class="stat-info">
                <span>{{ __('shortlink.total_clicks') }}</span>
                <strong>2,280</strong>
            </div>
        </div>
        <div class="stat-box">
            <div class="stat-icon stat-icon-blue"><i class="fas fa-link"></i></div>
            <div class="stat-info">
                <span>{{ __('shortlink.total_links') }}</span>
                <strong>{{ $shortlinks->total() }}</strong>
            </div>
        </div>
    </div>

    <div class="chart-container">
        @foreach([300, 280, 260, 310, 250, 400, 220, 350, 450, 240, 410, 380] as $index => $val)
        <div class="chart-bar-group">
            <div class="chart-bar" style="height: {{ ($val / 450) * 160 }}px;"></div>
            <div class="chart-label">{{ ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'][$index] }}</div>
        </div>
        @endforeach
    </div>
    <button class="hide-chart-btn"><i class="fas fa-chevron-up"></i> {{ __('shortlink.hide_chart') }}</button>
</div>
