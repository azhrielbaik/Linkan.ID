import sys

css_code = """
/* Performance Card Styles */
.card-subtitle {
    font-size: 13px;
    color: #888;
}

.stat-icon-orange {
    background: #FFF3E6;
    color: #FF9040;
}

.stat-icon-blue {
    background: #E6F6FF;
    color: #0088FF;
}

/* Search & Filter Card Styles */
.search-filter-card {
    margin-bottom: 0 !important;
}

.search-filter-row {
    display: flex;
    gap: 12px;
    align-items: center;
    flex-wrap: wrap;
}

.search-input-wrapper {
    flex: 1;
    position: relative;
    min-width: 200px;
}

.search-icon-left {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #999;
}

.search-input-field {
    width: 100%;
    padding: 12px 40px;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    font-family: inherit;
    font-size: 14px;
    box-sizing: border-box;
}

.search-clear-icon {
    position: absolute;
    right: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #999;
    text-decoration: none;
}

.desktop-sort-wrapper {
    width: 180px;
    position: relative;
    flex-shrink: 0;
}

.sort-select-field {
    width: 100%;
    padding: 12px 32px 12px 16px;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    font-family: inherit;
    font-size: 14px;
    background: #fff;
    cursor: pointer;
    appearance: none;
    color: #181818;
    box-sizing: border-box;
}

.sort-dropdown-icon {
    position: absolute;
    right: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #999;
    pointer-events: none;
    font-size: 12px;
}
"""

with open("public/css/pages/shortlink-create.css", "a") as f:
    f.write("\n" + css_code)

stat_html = """<!-- PERFORMANCE CARD -->
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
"""

search_html = """<!-- SEARCH & FILTER -->
<div class="card search-filter-card">
    <form method="GET" action="{{ route('admin.shortlinks.index') }}" id="search-filter-form">
        <div class="search-filter-row">
            <div class="search-input-wrapper">
                <i class="fas fa-search search-icon-left"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('shortlink.search_placeholder') ?? 'Search your links...' }}" class="search-input-field">
                @if(request('search'))
                    <a href="{{ route('admin.shortlinks.index', request()->except(['search', 'page'])) }}" class="search-clear-icon"><i class="fas fa-times-circle"></i></a>
                @endif
            </div>
            <div class="desktop-sort-wrapper">
                <select name="sort" onchange="this.form.submit()" class="sort-select-field">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>{{ __('shortlink.filter_newest') ?? 'Newest' }}</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>{{ __('shortlink.filter_oldest') ?? 'Oldest' }}</option>
                    <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>{{ __('shortlink.filter_popular') ?? 'Most Popular' }}</option>
                </select>
                <i class="fas fa-chevron-down sort-dropdown-icon"></i>
            </div>
        </div>

        <!-- Mobile Segmented Sorting Tabs -->
        <div class="mobile-segment-control">
            <button type="button" class="segment-tab {{ request('sort') != 'popular' ? 'active' : '' }}" data-sort="newest">
                <i class="fas fa-bolt"></i> {{ __('shortlink.filter_newest') ?? 'Newest' }}
            </button>
            <button type="button" class="segment-tab {{ request('sort') == 'popular' ? 'active' : '' }}" data-sort="popular">
                <i class="fas fa-fire"></i> {{ __('shortlink.filter_popular') ?? 'Most Popular' }}
            </button>
        </div>
    </form>
</div>
"""

with open("resources/views/admin/shortlinks/partials/stat-chart.blade.php", "w") as f:
    f.write(stat_html)

with open("resources/views/admin/shortlinks/partials/search-filter.blade.php", "w") as f:
    f.write(search_html)

with open("resources/views/homeadminS/shortlink/create.blade.php", "r") as f:
    lines = f.readlines()

new_lines = lines[:31] + ["            @include('admin.shortlinks.partials.stat-chart')\n", "            @include('admin.shortlinks.partials.search-filter')\n"] + lines[98:]

with open("resources/views/homeadminS/shortlink/create.blade.php", "w") as f:
    f.writelines(new_lines)
