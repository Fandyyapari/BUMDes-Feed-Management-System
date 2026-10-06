<?php

namespace App\Filament\Resources\FeedReceipts\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class FeedReceiptInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextEntry::make('receipt_number')
                ->label('Nomor Penerimaan'),

            TextEntry::make('feedProduct.name')
                ->label('Produk Pakan'),

            TextEntry::make('received_at')
                ->label('Tanggal Diterima')
                ->date('d/m/Y'),

            TextEntry::make('supplier_name')
                ->label('Pemasok / Tim Produksi'),

            TextEntry::make('batch_number')
                ->label('Kode Produksi')
                ->placeholder('Tidak dicantumkan'),

            TextEntry::make('quantity')
                ->label('Jumlah Diterima')
                ->numeric(locale: 'id')
                ->suffix(' kemasan'),

            TextEntry::make('unit_cost')
                ->label('Harga Beli per Kemasan')
                ->money('IDR', locale: 'id')
                ->placeholder('Belum diketahui'),

            TextEntry::make('expires_at')
                ->label('Batas Penggunaan')
                ->date('d/m/Y')
                ->placeholder('Belum tersedia'),

            TextEntry::make('notes')
                ->label('Catatan Penerimaan')
                ->placeholder('Tidak ada catatan')
                ->columnSpanFull(),

            TextEntry::make('cancelled_at')
                ->label('Waktu Pembatalan (WIB)')
                ->dateTime('d/m/Y H:i')
                ->timezone('Asia/Jakarta')
                ->visible(fn($record): bool => $record->isCancelled()),

            TextEntry::make('cancelledBy.name')
                ->label('Dibatalkan Oleh')
                ->placeholder('Tidak tersedia')
                ->visible(fn($record): bool => $record->isCancelled()),

            TextEntry::make('cancellation_reason')
                ->label('Alasan Pembatalan')
                ->columnSpanFull()
                ->visible(fn($record): bool => $record->isCancelled()),
        ]);
    }
}
