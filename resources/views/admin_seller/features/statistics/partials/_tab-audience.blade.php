{{-- Tab: Audiens --}}
<div id="tab-audience" class="sa-tab-content">
    
    <!-- Row 1: Device Breakdown & Traffic Source -->
    <div class="analytics-two-cols">
        <x-analytics.chart-card title="Distribusi Perangkat (Device Breakdown)">
            <div class="sa-chart-wrap">
                <div id="chartAudienceDevice" class="chart-inner-fill"></div>
            </div>
        </x-analytics.chart-card>

        <x-analytics.chart-card title="Distribusi Sumber Trafik (Traffic Source)">
            <div class="sa-chart-wrap">
                <div id="chartAudienceSource" class="chart-inner-fill"></div>
            </div>
        </x-analytics.chart-card>
    </div>

    <!-- Row 2: Top 10 Cities & Top 10 Countries -->
    <div class="analytics-two-cols mt-section">
        <x-analytics.chart-card title="Top 10 Kota Pengunjung">
            <div class="sa-chart-wrap">
                <div id="chartAudienceCity" class="chart-inner-fill"></div>
            </div>
        </x-analytics.chart-card>

        <x-analytics.chart-card title="Top 10 Negara Pengunjung">
            <div class="sa-chart-wrap">
                <div id="chartAudienceCountry" class="chart-inner-fill"></div>
            </div>
        </x-analytics.chart-card>
    </div>

    <!-- Row 3: Peak Hours & Peak Days -->
    <div class="analytics-two-cols mt-section">
        <x-analytics.chart-card title="Tren Jam Kunjungan (00:00 - 23:00 WIB)">
            <div class="sa-chart-wrap">
                <div id="chartAudienceHours" class="chart-inner-fill"></div>
            </div>
        </x-analytics.chart-card>

        <x-analytics.chart-card title="Hari Paling Ramai (Senin - Minggu)">
            <div class="sa-chart-wrap">
                <div id="chartAudienceDays" class="chart-inner-fill"></div>
            </div>
        </x-analytics.chart-card>
    </div>

</div>
