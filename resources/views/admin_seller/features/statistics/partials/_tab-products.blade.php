{{-- Tab: Produk Digital --}}
<div id="tab-products" class="sa-tab-content">
    
    <!-- Charts Row: Top by Revenue & Top by Qty -->
    <div class="analytics-two-cols">
        <x-analytics.chart-card title="Top 5 Produk Berdasarkan Omset (Revenue)">
            <div class="sa-chart-wrap">
                <div id="chartProductsRevenue" class="chart-inner-fill"></div>
            </div>
        </x-analytics.chart-card>

        <x-analytics.chart-card title="Top 5 Produk Berdasarkan Kuantitas Terjual">
            <div class="sa-chart-wrap">
                <div id="chartProductsQty" class="chart-inner-fill"></div>
            </div>
        </x-analytics.chart-card>
    </div>

    <!-- Full Digital Products Performance Table -->
    <div class="analytics-card mt-section">
        <x-analytics.table-toolbar 
            title="Tabel Performa Produk Digital"
            description="Metrik penjualan, rata-rata order (AOV), dan ulasan pembeli per produk"
            searchId="productsSearch"
            searchPlaceholder="Cari nama produk..."
            searchHandler="AnalyticsPage.tables.filterProducts()"
            statusId="productsStatusFilter"
            statusHandler="AnalyticsPage.tables.filterProducts()"
            :statusOptions="['all' => 'Semua Status', 'active' => 'Aktif', 'inactive' => 'Nonaktif']"
            sortId="productsSort"
            sortHandler="AnalyticsPage.tables.sortProducts()"
            :sortOptions="['revenue_desc' => 'Pendapatan Tertinggi', 'qty_desc' => 'Paling Banyak Terjual', 'rating_desc' => 'Rating Tertinggi']"
            exportFilename="products_performance.csv"
            tableId="tableDigitalProducts"
        />

        <div class="sa-table-responsive">
            <table class="sa-table-clean" id="tableDigitalProducts">
                <thead>
                    <tr>
                        <th>Foto & Nama Produk</th>
                        <th>Harga Satuan</th>
                        <th>Total Terjual</th>
                        <th>Total Revenue</th>
                        <th>Rata-rata Order (AOV)</th>
                        <th>Rating & Ulasan</th>
                        <th>Status</th>
                        <th>Stok</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody id="productsTableBody">
                    @forelse($analytics['products']['list'] as $prod)
                        <tr data-title="{{ strtolower($prod['title']) }}" data-status="{{ $prod['is_active'] ? 'active' : 'inactive' }}" data-revenue="{{ $prod['revenue'] }}" data-qty="{{ $prod['qty_sold'] }}" data-rating="{{ $prod['avg_rating'] }}">
                            <td>
                                <div class="sa-prod-cell">
                                    @if($prod['image_url'])
                                        <img src="{{ $prod['image_url'] }}" alt="{{ $prod['title'] }}" class="sa-prod-thumb">
                                    @else
                                        <div class="sa-prod-thumb"><i class="fas fa-box"></i></div>
                                    @endif
                                    <div>
                                        <div class="font-bold text-gray-900 dark:text-white text-sm max-w-[200px] truncate" title="{{ $prod['title'] }}">
                                            {{ $prod['title'] }}
                                        </div>
                                        <div class="text-[11px] text-gray-400">ID: #{{ $prod['id'] }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="font-semibold text-gray-700 dark:text-gray-300">
                                {{ $prod['price_formatted'] }}
                            </td>
                            <td>
                                <span class="font-bold text-gray-900 dark:text-white">{{ number_format($prod['qty_sold'], 0, ',', '.') }}</span> pcs
                            </td>
                            <td class="font-bold text-orange-600 dark:text-orange-400">
                                {{ $prod['revenue_formatted'] }}
                            </td>
                            <td class="text-gray-700 dark:text-gray-300 font-medium">
                                {{ $prod['aov_formatted'] }}
                            </td>
                            <td>
                                <div class="sa-rating-stars">
                                    <i class="fas fa-star"></i>
                                    <span>{{ number_format($prod['avg_rating'], 1) }}</span>
                                    <span class="text-gray-400 text-xs font-normal">({{ $prod['reviews_count'] }})</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge-status {{ $prod['is_active'] ? 'status-active' : 'status-expired' }}">
                                    {{ $prod['is_active'] ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge-tag">{{ $prod['quantity'] }}</span>
                            </td>
                            <td class="text-right">
                                <a href="{{ route('admin.digital-products.edit', ['digital_product' => $prod['id']]) }}" class="sa-btn-outline btn-sm-action">
                                    Edit <i class="fas fa-pen text-[10px]"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-8 text-gray-400 text-xs">
                                Belum ada produk digital yang dibuat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
