<?php

namespace App\Filament\Resources\FeedProducts\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class FeedProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Foto')
                    ->disk('public'),

                TextColumn::make('name')
                    ->label('Nama Pakan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('sku')
                    ->label('Kode Produk')
                    ->searchable(),

                TextColumn::make('weight_kg')
                    ->label('Berat Kemasan')
                    ->numeric(decimalPlaces: 2, locale: 'id')
                    ->suffix(' kg')
                    ->sortable(),

                TextColumn::make('price')
                    ->label('Harga per Kemasan')
                    ->money('IDR', locale: 'id')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label('Tanggal Ditambahkan')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Status Produk')
                    ->placeholder('Semua Produk')
                    ->trueLabel('Aktif')
                    ->falseLabel('Tidak Aktif'),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Ubah'),
            ])
            ->emptyStateHeading('Belum ada produk pakan')
            ->emptyStateDescription(
                'Klik tombol tambah untuk memasukkan produk pakan pertama.'
            )
            ->searchPlaceholder('Cari nama atau kode produk')
            ->defaultSort('created_at', 'desc');
    }
}