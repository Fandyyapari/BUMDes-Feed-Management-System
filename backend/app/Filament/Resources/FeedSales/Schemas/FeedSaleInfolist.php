<?php

namespace App\Filament\Resources\FeedSales\Schemas;

use App\Models\FeedSale;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class FeedSaleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextEntry::make('sale_number')
                ->label('Nomor Penjualan'),

            TextEntry::make('sold_at')
                ->label('Tanggal Penjualan')
                ->date('d/m/Y'),

            TextEntry::make('customer_name')
                ->label('Nama Pembeli'),

            TextEntry::make('customer_phone')
                ->label('Nomor HP Pembeli')
                ->placeholder('Tidak dicantumkan'),

            TextEntry::make('feedProduct.name')
                ->label('Produk Pakan'),

            TextEntry::make('quantity')
                ->label('Jumlah Terjual')
                ->numeric(decimalPlaces: 0, locale: 'id')
                ->suffix(' kemasan'),

            TextEntry::make('unit_price')
                ->label('Harga per Kemasan')
                ->money('IDR', locale: 'id'),

            TextEntry::make('total_price')
                ->label('Total Penjualan')
                ->money('IDR', locale: 'id'),

            TextEntry::make('payment_method')
                ->label('Metode Pembayaran')
                ->formatStateUsing(
                    fn (string $state): string => match ($state) {
                        'tunai' => 'Tunai',
                        'transfer' => 'Transfer Bank',
                        default => $state,
                    }
                ),

            TextEntry::make('createdBy.name')
                ->label('Dicatat Oleh'),

            TextEntry::make('status')
                ->label('Status Transaksi')
                ->state(
                    fn (FeedSale $record): string =>
                    $record->isCancelled() ? 'Dibatalkan' : 'Aktif'
                )
                ->badge()
                ->color(
                    fn (string $state): string =>
                    $state === 'Dibatalkan' ? 'danger' : 'success'
                ),

            TextEntry::make('created_at')
                ->label('Waktu Pencatatan (WIB)')
                ->dateTime('d/m/Y H:i')
                ->timezone('Asia/Jakarta'),

            TextEntry::make('notes')
                ->label('Catatan Penjualan')
                ->placeholder('Tidak ada catatan')
                ->columnSpanFull(),

            TextEntry::make('cancelled_at')
                ->label('Waktu Pembatalan (WIB)')
                ->dateTime('d/m/Y H:i')
                ->timezone('Asia/Jakarta')
                ->visible(
                    fn (FeedSale $record): bool => $record->isCancelled()
                ),

            TextEntry::make('cancelledBy.name')
                ->label('Dibatalkan Oleh')
                ->visible(
                    fn (FeedSale $record): bool => $record->isCancelled()
                ),

            TextEntry::make('cancellation_reason')
                ->label('Alasan Pembatalan')
                ->columnSpanFull()
                ->visible(
                    fn (FeedSale $record): bool => $record->isCancelled()
                ),
        ]);
    }
}