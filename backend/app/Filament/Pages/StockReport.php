<?php

namespace App\Filament\Pages;

use App\Models\User;
use App\Models\FeedProduct;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class StockReport extends Page
{
    protected string $view = 'filament.pages.stock-report';

    protected static ?string $navigationLabel = 'Laporan Stok';

    protected static ?string $title = 'Laporan Stok';

    protected static ?int $navigationSort = 11;

    public array $stockDetails = [];

    public ?string $updatedAt = null;

    public function mount(): void
    {
        $this->refreshReport();
    }

    public function refreshReport(): void
    {
        abort_unless(static::canAccess(), 403);

        $this->stockDetails = FeedProduct::query()
            ->withSum('activeReceipts', 'quantity')
            ->withSum('activeIssues', 'quantity')
            ->withSum('activeSales', 'quantity')
            ->orderBy('name')
            ->orderBy('id')
            ->get()
            ->map(function (FeedProduct $product): array {
                $received = (int) (
                    $product->active_receipts_sum_quantity ?? 0
                );

                $issued = (int) (
                    $product->active_issues_sum_quantity ?? 0
                );

                $sold = (int) (
                    $product->active_sales_sum_quantity ?? 0
                );

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'is_active' => (bool) $product->is_active,
                    'received' => $received,
                    'issued' => $issued,
                    'sold' => $sold,
                    'stock' => $received - $issued - $sold,
                ];
            })
            ->all();

        $this->updatedAt = now('Asia/Jakarta')
            ->format('d/m/Y H:i:s');
    }

    public static function canAccess(): bool
    {
        $user = Auth::user();

        return $user instanceof User
            && in_array($user->role, ['admin', 'petugas'], true);
    }
}
