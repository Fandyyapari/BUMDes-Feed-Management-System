<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\FeedSales\FeedSaleResource;
use App\Models\FeedSale;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestFeedSales extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Penjualan Terbaru')
            ->query(
                FeedSale::query()
                    ->with('feedProduct')
            )
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('sale_number')
                    ->label('Nomor Penjualan')
                    ->wrap(),

                TextColumn::make('sold_at')
                    ->label('Tanggal')
                    ->date('d/m/Y'),

                TextColumn::make('customer_name')
                    ->label('Pembeli')
                    ->wrap(),

                TextColumn::make('feedProduct.name')
                    ->label('Produk Pakan')
                    ->wrap(),

                TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->numeric(decimalPlaces: 0, locale: 'id')
                    ->suffix(' kemasan'),

                TextColumn::make('total_price')
                    ->label('Total Harga')
                    ->money('IDR', locale: 'id'),

                TextColumn::make('status')
                    ->label('Status')
                    ->state(
                        fn (FeedSale $record): string =>
                            $record->isCancelled()
                                ? 'Dibatalkan'
                                : 'Aktif'
                    )
                    ->badge()
                    ->color(
                        fn (string $state): string =>
                            $state === 'Dibatalkan'
                                ? 'danger'
                                : 'success'
                    ),
            ])
            ->recordActions([
                Action::make('view')
                    ->label('Lihat')
                    ->url(
                        fn (FeedSale $record): string =>
                            FeedSaleResource::getUrl(
                                'view',
                                ['record' => $record]
                            )
                    ),
            ])
            ->paginated([5])
            ->defaultPaginationPageOption(5)
            ->emptyStateHeading('Belum ada penjualan')
            ->emptyStateDescription(
                'Penjualan yang sudah dicatat akan tampil di sini.'
            );
    }
}