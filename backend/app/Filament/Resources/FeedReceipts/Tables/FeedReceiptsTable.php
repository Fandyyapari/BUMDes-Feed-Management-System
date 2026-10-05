<?php

namespace App\Filament\Resources\FeedReceipts\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class FeedReceiptsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('receipt_number')
                    ->label('Nomor Penerimaan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->state(fn($record): string => $record->isCancelled()
                        ? 'Dibatalkan'
                        : 'Aktif')
                    ->badge()
                    ->color(fn(string $state): string => $state === 'Dibatalkan'
                        ? 'danger'
                        : 'success'),

                TextColumn::make('received_at')
                    ->label('Tanggal Diterima')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('feedProduct.name')
                    ->label('Produk Pakan')
                    ->searchable()
                    ->wrap(),

                TextColumn::make('supplier_name')
                    ->label('Pemasok / Tim Produksi')
                    ->searchable()
                    ->wrap(),

                TextColumn::make('quantity')
                    ->label('Jumlah Diterima')
                    ->numeric(locale: 'id')
                    ->suffix(' kemasan')
                    ->sortable(),

                TextColumn::make('unit_cost')
                    ->label('Harga Beli per Kemasan')
                    ->money('IDR', locale: 'id')
                    ->placeholder('Belum diketahui')
                    ->sortable(),

                TextColumn::make('batch_number')
                    ->label('Kode Produksi')
                    ->placeholder('Tidak dicantumkan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('expires_at')
                    ->label('Batas Penggunaan')
                    ->date('d/m/Y')
                    ->placeholder('Belum tersedia')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('feed_product_id')
                    ->label('Produk Pakan')
                    ->relationship('feedProduct', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Lihat'),
            ])
            ->emptyStateHeading('Belum ada pakan masuk')
            ->emptyStateDescription(
                'Tambahkan penerimaan pakan yang sudah diterima BUMDes.'
            )
            ->searchPlaceholder('Cari nomor penerimaan, pakan, atau pemasok')
            ->defaultSort('received_at', 'desc');
    }
}
