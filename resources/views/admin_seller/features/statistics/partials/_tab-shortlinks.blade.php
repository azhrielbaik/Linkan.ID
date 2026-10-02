{{-- Tab: Shortlinks --}}
<div id="tab-shortlinks" class="sa-tab-content">
    
    <!-- Charts Row 1: Top 5 Shortlinks & Peak Hours -->
    <div class="analytics-two-cols">
        <x-analytics.chart-card title="Top 5 Shortlink Paling Banyak Diklik">
            <div class="sa-chart-wrap">
                <div id="chartShortlinksTop" class="chart-inner-fill"></div>
            </div>
        </x-analytics.chart-card>

        <x-analytics.chart-card title="Tren Jam Kunjungan (00:00 - 23:00 WIB)">
            <div class="sa-chart-wrap">
                <div id="chartShortlinksPeakHours" class="chart-inner-fill"></div>
            </div>
        </x-analytics.chart-card>
    </div>

    <!-- Charts Row 2: Traffic Source & Device Breakdown -->
    <div class="analytics-two-cols mt-section">
        <x-analytics.chart-card title="Distribusi Sumber Trafik">
            <div class="sa-chart-wrap">
                <div id="chartShortlinksSource" class="chart-inner-fill"></div>
            </div>
        </x-analytics.chart-card>

        <x-analytics.chart-card title="Distribusi Perangkat (Device)">
            <div class="sa-chart-wrap">
                <div id="chartShortlinksDevice" class="chart-inner-fill"></div>
            </div>
        </x-analytics.chart-card>
    </div>

    <!-- Full Shortlink Performance Table -->
    <div class="analytics-card mt-section">
        <x-analytics.table-toolbar 
            title="Tabel Performa Shortlink"
            description="Daftar lengkap shortlink beserta statistik klik dan pengunjung unik"
            searchId="shortlinksSearch"
            searchPlaceholder="Cari slug atau URL tujuan..."
            searchHandler="AnalyticsPage.tables.filterShortlinks()"
            statusId="shortlinksStatusFilter"
            statusHandler="AnalyticsPage.tables.filterShortlinks()"
            :statusOptions="['all' => 'Semua Status', 'active' => 'Aktif', 'expired' => 'Expired']"
            sortId="shortlinksSort"
            sortHandler="AnalyticsPage.tables.sortShortlinks()"
            :sortOptions="['clicks_desc' => 'Paling Banyak Klik', 'clicks_asc' => 'Paling Sedikit Klik', 'slug_asc' => 'Slug (A-Z)']"
            exportFilename="shortlinks_analytics.csv"
            tableId="tableShortlinks"
        />

        <div class="sa-table-responsive">
            <table class="sa-table-clean" id="tableShortlinks">
                <thead>
                    <tr>
                        <th>Nama / Slug</th>
                        <th>Destination URL</th>
                        <th>Total Klik</th>
                        <th>Unique Visitors</th>
                        <th>Top Source</th>
                        <th>Top Device</th>
                        <th>Dibuat</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody id="shortlinksTableBody">
                    @forelse($analytics['shortlinks']['list'] as $sl)
                        <tr data-slug="{{ strtolower($sl['slug']) }}" data-dest="{{ strtolower($sl['destination']) }}" data-status="{{ strtolower($sl['status']) }}" data-clicks="{{ $sl['total_clicks'] }}">
                            <td>
                                <div class="font-bold text-gray-900 dark:text-white flex items-center gap-1.5">
                                    <span class="text-orange-500">/</span>{{ $sl['slug'] }}
                                    @if($sl['has_password'])
                                        <i class="fas fa-lock text-[11px] text-amber-500" title="Dilindungi Password"></i>
                                    @endif
                                </div>
                                <div class="text-[11px] text-gray-400 truncate max-w-[150px]">{{ $sl['title'] }}</div>
                            </td>
                            <td>
                                <a href="{{ $sl['destination'] }}" target="_blank" rel="noopener noreferrer" class="sa-dest-url hover:text-orange-500" title="{{ $sl['destination'] }}">
                                    {{ $sl['destination'] }}
                                </a>
                            </td>
                            <td>
                                <span class="font-bold text-gray-900 dark:text-white">{{ number_format($sl['total_clicks'], 0, ',', '.') }}</span>
                            </td>
                            <td>
                                <span class="text-gray-600 dark:text-gray-300">{{ number_format($sl['unique_clicks'], 0, ',', '.') }}</span>
                            </td>
                            <td>
                                <span class="badge-tag">{{ $sl['top_source'] }}</span>
                            </td>
                            <td>
                                <span class="badge-tag">{{ $sl['top_device'] }}</span>
                            </td>
                            <td class="text-gray-500 dark:text-gray-400 text-xs">
                                {{ $sl['created_at'] }}
                            </td>
                            <td>
                                <span class="badge-status status-{{ strtolower($sl['status']) }}">
                                    {{ $sl['status'] }}
                                </span>
                            </td>
                            <td class="text-right">
                                <a href="{{ $sl['analytics_url'] }}" class="sa-btn-outline btn-sm-action">
                                    Analytics <i class="fas fa-arrow-right text-[10px]"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-8 text-gray-400 text-xs">
                                Belum ada data shortlink.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
