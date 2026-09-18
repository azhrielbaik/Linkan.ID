<!-- SEARCH & FILTER -->
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
