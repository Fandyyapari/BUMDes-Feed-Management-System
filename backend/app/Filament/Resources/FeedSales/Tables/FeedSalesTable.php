<?php

namespace App\Filament\Resources\FeedSales\Tables;

use App\Models\FeedSale;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class FeedSalesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sale_number')
                    ->label('Nomor Penjualan')
                    ->searchable()
                    ->wrap(),

                TextColumn::make('sold_at')
                    ->label('Tanggal Penjualan')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('customer_name')
                    ->label('Nama Pembeli')
                    ->searchable()
                    ->wrap(),

                TextColumn::make('feedProduct.name')
                    ->label('Produk Pakan')
                    ->searchable()
                    ->wrap(),

                TextColumn::make('quantity')
                    ->label('Jumlah Terjual')
                    ->numeric(decimalPlaces: 0, locale: 'id')
                    ->suffix(' kemasan')
                    ->sortable(),

                TextColumn::make('unit_price')
                    ->label('Harga per Kemasan')
                    ->money('IDR', locale: 'id')
                    ->sortable(),

                TextColumn::make('total_price')
                    ->label('Total Penjualan')
                    ->money('IDR', locale: 'id')
                    ->sortable(),

                TextColumn::make('payment_method')
                    ->label('Metode Pembayaran')
                    ->formatStateUsing(
                        fn (string $state): string => match ($state) {
                            'tunai' => 'Tunai',
                            'transfer' => 'Transfer Bank',
                            default => $state,
                        }
                    ),

                TextColumn::make('status')
                    ->label('Status Transaksi')
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

                TextColumn::make('createdBy.name')
                    ->label('Dicatat Oleh')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('feed_product_id')
                    ->label('Produk Pakan')
                    ->relationship('feedProduct', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('payment_method')
                    ->label('Metode Pembayaran')
                    ->options([
                        'tunai' => 'Tunai',
                        'transfer' => 'Transfer Bank',
                    ]),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Lihat'),
            ])
            ->emptyStateHeading('Belum ada penjualan pakan')
            ->emptyStateDescription(
                'Tambahkan penjualan pakan yang sudah dilakukan BUMDes.'
            )
            ->searchPlaceholder(
                'Cari nomor penjualan, pembeli, atau produk'
            )
            ->defaultSort('sold_at', 'desc');
    }
}