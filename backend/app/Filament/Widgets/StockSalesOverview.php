<?php

namespace App\Filament\Widgets;

use App\Models\FeedIssue;
use App\Models\FeedReceipt;
use App\Models\FeedSale;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StockSalesOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    protected function getStats(): array
    {
        // Tentukan tanggal berdasarkan waktu Indonesia bagian barat.
        $today = now('Asia/Jakarta')->toDateString();
        $monthStart = now('Asia/Jakarta')
            ->startOfMonth()
            ->toDateString();

        // Transaksi yang dibatalkan tidak ikut dihitung.
        $totalReceived = (int) FeedReceipt::query()
            ->whereNull('cancelled_at')
            ->sum('quantity');

        $totalIssued = (int) FeedIssue::query()
            ->whereNull('cancelled_at')
            ->sum('quantity');

        $totalSold = (int) FeedSale::query()
            ->whereNull('cancelled_at')
            ->sum('quantity');

        $availableStock = $totalReceived - $totalIssued - $totalSold;

        // Nilai penjualan berdasarkan tanggal transaksi.
        $salesToday = FeedSale::query()
            ->whereNull('cancelled_at')
            ->whereDate('sold_at', $today)
            ->sum('total_price');

        $salesThisMonth = FeedSale::query()
            ->whereNull('cancelled_at')
            ->whereBetween('sold_at', [$monthStart, $today])
            ->sum('total_price');

        return [
            Stat::make(
                'Sisa Stok Pakan',
                number_format($availableStock, 0, ',', '.') . ' kemasan'
            )
                ->description('Total stok seluruh produk')
                ->color('primary'),

            Stat::make(
                'Penjualan Hari Ini',
                'Rp ' . number_format((float) $salesToday, 0, ',', '.')
            )
                ->description('Nilai penjualan pada tanggal hari ini')
                ->color('success'),

            Stat::make(
                'Penjualan Bulan Ini',
                'Rp ' . number_format((float) $salesThisMonth, 0, ',', '.')
            )
                ->description('Nilai penjualan dari awal bulan sampai hari ini')
                ->color('success'),
        ];
    }
}
