<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use App\Models\FeedSale;

class SalesReport extends Page
{
    protected string $view = 'filament.pages.sales-report';

    protected static ?string $navigationLabel = 'Laporan Penjualan';

    protected static ?string $title = 'Laporan Penjualan';

    protected static ?int $navigationSort = 10;

    public ?string $startDate = null;

    public ?string $endDate = null;

    public int $totalTransactions = 0;

    public int $totalPackages = 0;

    public string $totalRevenue = '0.00';

    public bool $reportReady = false;

    public ?string $reportStartDate = null;

    public ?string $reportEndDate = null;

    public array $salesDetails = [];

    public function mount(): void
    {
        abort_unless(static::canAccess(), 403);

        $today = now('Asia/Jakarta');

        $this->startDate = $today->copy()->startOfMonth()->toDateString();
        $this->endDate = $today->toDateString();
    }

    public function applyFilters(): void
    {
        abort_unless(static::canAccess(), 403);

        $this->reportReady = false;

        $validated = $this->validate([
            'startDate' => ['required', 'date_format:Y-m-d'],
            'endDate' => [
                'required',
                'date_format:Y-m-d',
                'after_or_equal:startDate',
            ],
        ], [
            'startDate.required' => 'Tanggal mulai wajib diisi.',
            'startDate.date_format' => 'Tanggal mulai tidak valid.',
            'endDate.required' => 'Tanggal akhir wajib diisi.',
            'endDate.date_format' => 'Tanggal akhir tidak valid.',
            'endDate.after_or_equal' =>
            'Tanggal akhir tidak boleh sebelum tanggal mulai.',
        ]);

        $summary = FeedSale::query()
            ->whereNull('cancelled_at')
            ->whereBetween('sold_at', [
                $validated['startDate'],
                $validated['endDate'],
            ])
            ->selectRaw('COUNT(*) AS transaction_count')
            ->selectRaw('COALESCE(SUM(quantity), 0) AS package_count')
            ->selectRaw('COALESCE(SUM(total_price), 0) AS revenue_total')
            ->first();

        $this->totalTransactions = (int) $summary->transaction_count;
        $this->totalPackages = (int) $summary->package_count;
        $this->totalRevenue = (string) $summary->revenue_total;

        $this->reportStartDate = $validated['startDate'];
        $this->reportEndDate = $validated['endDate'];
        $this->reportReady = true;
        $this->salesDetails = FeedSale::query()
            ->with('feedProduct')
            ->whereNull('cancelled_at')
            ->whereBetween('sold_at', [
                $validated['startDate'],
                $validated['endDate'],
            ])
            ->orderByDesc('sold_at')
            ->orderByDesc('id')
            ->limit(50)
            ->get()
            ->map(fn(FeedSale $sale): array => [
                'id' => $sale->id,
                'sale_number' => $sale->sale_number,
                'sold_at' => $sale->sold_at->format('d/m/Y'),
                'customer_name' => $sale->customer_name,
                'product_name' => $sale->feedProduct?->name
                    ?? 'Produk tidak tersedia',
                'quantity' => $sale->quantity,
                'total_price' => (string) $sale->total_price,
                'payment_method' => match ($sale->payment_method) {
                    'tunai' => 'Tunai',
                    'transfer' => 'Transfer Bank',
                    default => 'Tidak diketahui',
                },
            ])
            ->all();
    }

    public static function canAccess(): bool
    {
        $user = Auth::user();

        return $user instanceof User
            && in_array($user->role, ['admin', 'petugas'], true);
    }
}
