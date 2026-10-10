<?php

namespace App\Filament\Resources\FeedOrders\Schemas;

use App\Models\FeedOrder;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class FeedOrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextEntry::make('order_number')
                    ->label('Nomor Pesanan')
                    ->columnSpanFull(),

                TextEntry::make('status')
                    ->label('Status Pesanan')
                    ->badge()
                    ->formatStateUsing(
                        fn (string $state): string =>
                            FeedOrder::statusOptions()[$state]
                            ?? 'Status Tidak Dikenali'
                    )
                    ->color(fn (string $state): string => match ($state) {
                        FeedOrder::STATUS_WAITING => 'warning',
                        FeedOrder::STATUS_CONFIRMED => 'info',
                        FeedOrder::STATUS_COMPLETED => 'success',
                        FeedOrder::STATUS_REJECTED => 'danger',
                        default => 'gray',
                    }),

                TextEntry::make('created_at')
                    ->label('Tanggal Pesan')
                    ->dateTime('d/m/Y H:i')
                    ->timezone('Asia/Jakarta'),

                TextEntry::make('customer_name')
                    ->label('Nama Pelanggan'),

                TextEntry::make('customer_phone')
                    ->label('Nomor HP'),

                TextEntry::make('customer_dusun')
                    ->label('Dusun')
                    ->placeholder('Tidak dicantumkan'),

                TextEntry::make('customer_address')
                    ->label('Alamat')
                    ->placeholder('Tidak dicantumkan'),

                TextEntry::make('product_name')
                    ->label('Produk Pakan'),

                TextEntry::make('weight_kg')
                    ->label('Berat per Kemasan')
                    ->numeric(decimalPlaces: 2, locale: 'id')
                    ->suffix(' kg'),

                TextEntry::make('quantity')
                    ->label('Jumlah Pesanan')
                    ->numeric(decimalPlaces: 0, locale: 'id')
                    ->suffix(' kemasan'),

                TextEntry::make('unit_price')
                    ->label('Harga per Kemasan')
                    ->money('IDR', locale: 'id'),

                TextEntry::make('total_price')
                    ->label('Total Harga')
                    ->money('IDR', locale: 'id')
                    ->columnSpanFull(),

                TextEntry::make('customer_notes')
                    ->label('Catatan Pelanggan')
                    ->placeholder('Tidak ada catatan')
                    ->columnSpanFull(),

                TextEntry::make('admin_notes')
                    ->label('Informasi dari Pengurus')
                    ->placeholder('Belum ada informasi')
                    ->columnSpanFull(),

                TextEntry::make('processedBy.name')
                    ->label('Diproses oleh')
                    ->placeholder('Belum diproses'),

                TextEntry::make('processed_at')
                    ->label('Tanggal Diproses')
                    ->dateTime('d/m/Y H:i')
                    ->timezone('Asia/Jakarta')
                    ->placeholder('Belum diproses'),

                TextEntry::make('completed_at')
                    ->label('Tanggal Selesai')
                    ->dateTime('d/m/Y H:i')
                    ->timezone('Asia/Jakarta')
                    ->placeholder('Belum selesai'),

                TextEntry::make('cancelled_at')
                    ->label('Tanggal Pembatalan')
                    ->dateTime('d/m/Y H:i')
                    ->timezone('Asia/Jakarta')
                    ->placeholder('Tidak dibatalkan'),

                TextEntry::make('cancellation_reason')
                    ->label('Alasan Pembatalan')
                    ->placeholder('Tidak ada')
                    ->columnSpanFull(),
            ]);
    }
}