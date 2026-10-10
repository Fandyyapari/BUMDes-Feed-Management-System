<?php

namespace App\Filament\Resources\FeedOrders\Tables;

use App\Models\FeedOrder;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class FeedOrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->label('Nomor Pesanan')
                    ->searchable()
                    ->wrap(),

                TextColumn::make('created_at')
                    ->label('Tanggal Pesan')
                    ->dateTime('d/m/Y H:i')
                    ->timezone('Asia/Jakarta')
                    ->sortable(),

                TextColumn::make('customer_name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->wrap(),

                TextColumn::make('customer_phone')
                    ->label('Nomor HP')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('product_name')
                    ->label('Produk Pakan')
                    ->searchable()
                    ->wrap(),

                TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->numeric(decimalPlaces: 0, locale: 'id')
                    ->suffix(' kemasan')
                    ->sortable(),

                TextColumn::make('total_price')
                    ->label('Total Harga')
                    ->money('IDR', locale: 'id')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
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
                        FeedOrder::STATUS_CANCELLED => 'gray',
                        default => 'gray',
                    })
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status Pesanan')
                    ->options(FeedOrder::statusOptions()),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Lihat'),
            ])
            ->emptyStateHeading('Belum ada pesanan')
            ->emptyStateDescription(
                'Pesanan yang dikirim pelanggan akan muncul di sini.'
            )
            ->searchPlaceholder('Cari nomor pesanan, pelanggan, atau pakan')
            ->defaultSort('created_at', 'desc');
    }
}