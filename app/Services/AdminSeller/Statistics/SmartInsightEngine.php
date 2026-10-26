<?php

namespace App\Services\AdminSeller\Statistics;

use App\Models\User;

class SmartInsightEngine
{
    /**
     * Generate dynamic Smart Highlights (Insight Cerdas) based on server-side rules.
     */
    public function generateSmartInsights(User $user, array $scorecards, array $audience, array $products, array $shortlinks): array
    {
        $insights = [];

        // 1. Mobile visitor dominance
        $deviceLabels = array_map('strtolower', $audience['device_labels'] ?? []);
        $deviceSeries = $audience['device_series'] ?? [];
        $totalDevClicks = array_sum($deviceSeries);
        if ($totalDevClicks > 0) {
            $mobileIndex = array_search('mobile', $deviceLabels);
            if ($mobileIndex !== false) {
                $mobilePct = round(($deviceSeries[$mobileIndex] / $totalDevClicks) * 100);
                if ($mobilePct >= 50) {
                    $insights[] = [
                        'type' => 'info',
                        'icon' => 'fa-solid fa-mobile-screen',
                        'text' => "{$mobilePct}% audiens Anda mengakses via perangkat Mobile. Pastikan tautan dan etalase digital Anda ringkas dan responsif di layar ponsel.",
                    ];
                }
            }
        }

        // 2. Top Performing Shortlink
        if (! empty($shortlinks['list'])) {
            $topLink = $shortlinks['list'][0];
            if ($topLink['total_clicks'] > 5) {
                $insights[] = [
                    'type' => 'success',
                    'icon' => 'fa-solid fa-arrow-trend-up',
                    'text' => "Shortlink /{$topLink['slug']} menjadi tautan terpopuler dengan {$topLink['total_clicks']} klik ({$topLink['unique_clicks']} unik) pada periode ini.",
                ];
            }
        }

        // 3. Top Product Revenue Contribution
        $totalSales = (float) ($scorecards['sales']['value'] ?? 0.0);
        if (! empty($products['list']) && $totalSales > 0) {
            $topProduct = $products['list'][0];
            if ($topProduct['revenue'] > 0) {
                $sharePct = round(($topProduct['revenue'] / $totalSales) * 100);
                $insights[] = [
                    'type' => 'success',
                    'icon' => 'fa-solid fa-bag-shopping',
                    'text' => "Produk '{$topProduct['title']}' menjadi produk terlaris dengan kontribusi {$sharePct}% dari total omset Anda.",
                ];
            }
        }

        // 4. Stagnant active product warning
        if (! empty($products['list'])) {
            foreach ($products['list'] as $prod) {
                if ($prod['is_active'] && $prod['qty_sold'] === 0) {
                    $insights[] = [
                        'type' => 'warning',
                        'icon' => 'fa-solid fa-lightbulb',
                        'text' => "Produk '{$prod['title']}' belum mencatatkan transaksi pada periode ini. Pertimbangkan membuat penawaran khusus atau menyematkannya di bio profil.",
                    ];
                    break;
                }
            }
        }

        // 5. Peak Audience Activity
        $peakHours = $audience['peak_hours_series'] ?? [];
        if (! empty($peakHours) && max($peakHours) > 0) {
            $maxHourIndex = array_keys($peakHours, max($peakHours))[0];
            $peakHourStr = sprintf('%02d:00', $maxHourIndex);

            $peakDays = $audience['peak_days_series'] ?? [];
            $dayLabels = $audience['peak_days_labels'] ?? [];
            $peakDayStr = 'Rabu';
            if (! empty($peakDays) && max($peakDays) > 0) {
                $maxDayIndex = array_keys($peakDays, max($peakDays))[0];
                $peakDayStr = $dayLabels[$maxDayIndex] ?? 'Rabu';
            }

            $insights[] = [
                'type' => 'timing',
                'icon' => 'fa-solid fa-clock',
                'text' => "Waktu paling ramai pengunjung tercatat pada hari {$peakDayStr} sekitar pukul {$peakHourStr} WIB. Ini waktu terbaik untuk membagikan tautan konten baru.",
            ];
        }

        // Fallback default insight if dataset is fresh
        if (empty($insights)) {
            $insights[] = [
                'type' => 'info',
                'icon' => 'fa-solid fa-chart-line',
                'text' => 'Pantau pertumbuhan konversi profil Anda secara berkala untuk mengetahui kampanye mana yang mendatangkan penjualan terbanyak.',
            ];
        }

        return $insights;
    }
}
