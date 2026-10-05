<?php

namespace App\Filament\Resources\FeedIssues\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class FeedIssuesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('issue_number')
                    ->label('Nomor Pengeluaran')
                    ->searchable(),

                TextColumn::make('issued_at')
                    ->label('Tanggal Keluar')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('feedProduct.name')
                    ->label('Produk Pakan')
                    ->searchable()
                    ->wrap(),

                TextColumn::make('quantity')
                    ->label('Jumlah Keluar')
                    ->numeric(decimalPlaces: 0, locale: 'id')
                    ->suffix(' kemasan')
                    ->sortable(),

                TextColumn::make('reason')
                    ->label('Alasan')
                    ->formatStateUsing(
                        fn (string $state): string => match ($state) {
                            'percontohan' => 'Percontohan / Uji Coba',
                            'rusak' => 'Pakan Rusak',
                            'kedaluwarsa' => 'Pakan Kedaluwarsa',
                            'lainnya' => 'Lainnya',
                            default => $state,
                        }
                    )
                    ->wrap(),

                TextColumn::make('recipient_name')
                    ->label('Nama Penerima')
                    ->searchable()
                    ->placeholder('Tidak ada penerima')
                    ->wrap(),

                TextColumn::make('notes')
                    ->label('Keterangan')
                    ->limit(50)
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('feed_product_id')
                    ->label('Produk Pakan')
                    ->relationship('feedProduct', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('reason')
                    ->label('Alasan Pengeluaran')
                    ->options([
                        'percontohan' => 'Percontohan / Uji Coba',
                        'rusak' => 'Pakan Rusak',
                        'kedaluwarsa' => 'Pakan Kedaluwarsa',
                        'lainnya' => 'Lainnya',
                    ]),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Lihat'),
            ])
            ->toolbarActions([])
            ->defaultSort('issued_at', 'desc');
    }
}