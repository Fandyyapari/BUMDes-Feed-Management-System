<?php

namespace App\Filament\Resources\FeedProducts\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class FeedProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Pakan')
                    ->required()
                    ->maxLength(255),

                TextInput::make('sku')
                    ->label('Kode Produk')
                    ->placeholder('Contoh: PF-10KG')
                    ->required()
                    ->maxLength(50)
                    ->unique(ignoreRecord: true),

                FileUpload::make('image')
                    ->label('Foto Pakan')
                    ->image()
                    ->acceptedFileTypes([
                        'image/jpeg',
                        'image/png',
                        'image/webp',
                    ])
                    ->disk('public')
                    ->directory('feed-products')
                    ->visibility('public')
                    ->maxSize(2048)
                    ->helperText('JPG, PNG, atau WebP. Maksimal 2 MB.')
                    ->columnSpanFull(),

                TextInput::make('weight_kg')
                    ->label('Berat per Kemasan')
                    ->numeric()
                    ->suffix('kg')
                    ->minValue(0.01)
                    ->maxValue(999999.99)
                    ->step(0.01)
                    ->required(),

                TextInput::make('price')
                    ->label('Harga per Kemasan')
                    ->numeric()
                    ->prefix('Rp')
                    ->minValue(0)
                    ->maxValue(9999999999.99)
                    ->step(0.01)
                    ->required(),

                Textarea::make('description')
                    ->label('Deskripsi Pakan')
                    ->rows(4)
                    ->maxLength(5000)
                    ->helperText('Isi informasi produk sesuai panduan tim produksi.')
                    ->columnSpanFull(),

                Toggle::make('is_active')
                    ->label('Produk Aktif')
                    ->default(false)
                    ->helperText('Aktifkan setelah informasi produk siap ditampilkan.')
                    ->required(),
            ]);
    }
}