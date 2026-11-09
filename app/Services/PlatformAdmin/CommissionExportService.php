<?php

namespace App\Services\PlatformAdmin;

use Illuminate\Support\Facades\DB;

class CommissionExportService
{
    /**
     * Get commissions data for export or API
     */
    public function getCommissionsData()
    {
        return DB::table('platform_commissions')
            ->join('users as sellers', 'platform_commissions.seller_id', '=', 'sellers.id')
            ->select(
                'platform_commissions.id',
                'platform_commissions.seller_id',
                'platform_commissions.amount',
                'platform_commissions.commission',
                'platform_commissions.created_at',
                'sellers.name as seller_name',
                'sellers.email as seller_email'
            )
            ->orderBy('platform_commissions.created_at', 'desc')
            ->get();
    }

    /**
     * Get total platform earnings
     */
    public function getTotalEarnings(): float
    {
        return (float) (DB::table('platform_commissions')->sum('commission') ?? 0);
    }

    /**
     * Generate CSV content stream for Export
     */
    public function streamCommissionsCsv(callable $callback)
    {
        $commissions = $this->getCommissionsData();
        $columns = ['ID', 'Nama Seller', 'Email Seller', 'Nominal Transaksi (IDR)', 'Komisi Platform (IDR)', 'Waktu'];

        return function () use ($callback, $commissions, $columns) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF"); // Add BOM for UTF-8 Excel support
            fputcsv($file, $columns);

            foreach ($commissions as $row) {
                fputcsv($file, [
                    $row->id,
                    $row->seller_name,
                    $row->seller_email,
                    $row->amount,
                    $row->commission,
                    $row->created_at,
                ]);
            }
            fclose($file);
            if (is_callable($callback)) {
                $callback();
            }
        };
    }
}
