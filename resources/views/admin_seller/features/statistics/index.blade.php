@extends("admin_seller.layouts.app")

@section("title", "Analitik Performa & Penjualan — Linkan.ID")
@section("page_title", __('sidebar.analytics') ?? 'Analitik')

@push("styles")
<link rel="stylesheet" href="{{ asset('css/pages/analytics-stats.css') }}?v={{ file_exists(public_path('css/pages/analytics-stats.css')) ? filemtime(public_path('css/pages/analytics-stats.css')) : '2.0' }}">
@endpush

@section("content")
<div class="analytics-container" id="analytics-app">
    
    <!-- Config & Data payload for clean JS hydration without inline Blade tags -->
    <div id="analytics-config" class="hidden" data-chart-url="{{ route('admin.statistics.chart-data') }}"></div>
    <script id="analytics-data" type="application/json">@json($analytics)</script>

    <!-- 1. TOP APP-BAR (ANALITIK PERFORMA) -->
    <div class="analytics-topbar">
        <div class="analytics-title-wrap">
            <h1 class="analytics-title">
                <i class="fas fa-chart-line text-orange-500"></i> Analitik Performa
            </h1>
        </div>

        <!-- Center Search Bar with ⌘K -->
        <div class="analytics-search">
            <i class="fas fa-search analytics-search-icon"></i>
            <input type="text" id="globalAnalyticsSearch" placeholder="Cari data analitik..." oninput="AnalyticsPage.ui.handleGlobalSearch(this.value)">
            <span class="analytics-search-shortcut">⌘K</span>
        </div>
    </div>

    <!-- 2. TAB PILLS NAVIGATION (OVERVIEW, SHORTLINKS, PRODUK, AUDIENS) -->
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div class="analytics-nav-pills">
            <button type="button" class="analytics-nav-pill-btn active" onclick="AnalyticsPage.ui.switchTab('overview', this)">
                <i class="fas fa-chart-pie"></i> Overview
            </button>
            <button type="button" class="analytics-nav-pill-btn" onclick="AnalyticsPage.ui.switchTab('shortlinks', this)">
                <i class="fas fa-link"></i> Shortlinks
            </button>
            <button type="button" class="analytics-nav-pill-btn" onclick="AnalyticsPage.ui.switchTab('products', this)">
                <i class="fas fa-box-open"></i> Produk Digital
            </button>
            <button type="button" class="analytics-nav-pill-btn" onclick="AnalyticsPage.ui.switchTab('audience', this)">
                <i class="fas fa-globe"></i> Audiens
            </button>
        </div>

        <!-- Period info label -->
        <div class="text-xs font-medium text-gray-500 dark:text-gray-400 flex items-center gap-2">
            <i class="far fa-calendar-check text-orange-500"></i>
            <span id="labelPeriodSummary">
                {{ \Carbon\Carbon::parse($analytics['start_date'])->format('d M Y') }} - {{ \Carbon\Carbon::parse($analytics['end_date'])->format('d M Y') }}
            </span>
        </div>
    </div>

    <!-- 3. TAB CONTENTS (MODULAR PARTIALS) -->
    @include('admin_seller.features.statistics.partials._tab-overview')
    @include('admin_seller.features.statistics.partials._tab-shortlinks')
    @include('admin_seller.features.statistics.partials._tab-products')
    @include('admin_seller.features.statistics.partials._tab-audience')

</div>
@endsection

@push("scripts")
<script src="{{ asset('js/apexcharts.min.js') }}"></script>
<script src="{{ asset('js/pages/analytics.js') }}?v={{ file_exists(public_path('js/pages/analytics.js')) ? filemtime(public_path('js/pages/analytics.js')) : '1.0' }}"></script>
@endpush
