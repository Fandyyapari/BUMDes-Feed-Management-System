<?php

namespace App\Filament\Resources\FeedReceipts\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class FeedReceiptForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('receipt_number')
                ->label('Nomor Penerimaan')
                ->placeholder('Contoh: PM-20261005-001')
                ->required()
                ->maxLength(255)
                ->unique(ignoreRecord: true),

            Select::make('feed_product_id')
                ->label('Produk Pakan')
                ->relationship('feedProduct', 'name')
                ->searchable()
                ->preload()
                ->placeholder('Pilih produk pakan')
                ->required(),

            DatePicker::make('received_at')
                ->label('Tanggal Diterima')
                ->default(now())
                ->required(),

            TextInput::make('supplier_name')
                ->label('Nama Pemasok / Tim Produksi')
                ->placeholder('Contoh: Tim Produksi Desa Jarak')
                ->required()
                ->maxLength(255),

            TextInput::make('batch_number')
                ->label('Kode Produksi')
                ->placeholder('Isi jika tersedia')
                ->maxLength(255),

            TextInput::make('quantity')
                ->label('Jumlah Kemasan Diterima')
                ->numeric()
                ->rules(['integer'])
                ->minValue(1)
                ->maxValue(1000000)
                ->step(1)
                ->suffix('kemasan')
                ->helperText('Contoh: 20 kantong pakan diisi 20.')
                ->required(),

            TextInput::make('unit_cost')
                ->label('Harga Beli per Kemasan')
                ->numeric()
                ->prefix('Rp')
                ->minValue(0)
                ->maxValue(9999999999.99)
                ->step(0.01)
                ->helperText('Boleh kosong jika harga belum diketahui. Isi 0 jika gratis.'),

            DatePicker::make('expires_at')
                ->label('Batas Penggunaan')
                ->afterOrEqual('received_at')
                ->helperText('Isi sesuai informasi tim produksi. Kosongkan jika belum tersedia.'),

            Textarea::make('notes')
                ->label('Catatan Penerimaan')
                ->placeholder('Contoh: Kemasan diterima dalam keadaan utuh.')
                ->rows(3)
                ->maxLength(5000)
                ->columnSpanFull(),
        ]);
    }
}