<?php

namespace App\Filament\Resources\FeedSales\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use App\Models\FeedProduct;
use Filament\Schemas\Components\Utilities\Set;

class FeedSaleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            DatePicker::make('sold_at')
                ->label('Tanggal Penjualan')
                ->default(now('Asia/Jakarta')->toDateString())
                ->required(),

            Select::make('feed_product_id')
                ->label('Produk Pakan')
                ->relationship(
                    name: 'feedProduct',
                    titleAttribute: 'name',
                    modifyQueryUsing: fn(Builder $query) =>
                    $query->where('is_active', true),
                )
                ->searchable()
                ->preload()
                ->placeholder('Pilih produk pakan')
                ->live()
                ->afterStateUpdated(function ($state, Set $set): void {
                    $product = filled($state)
                        ? FeedProduct::find($state)
                        : null;

                    $set(
                        'unit_price',
                        $product ? (int) $product->price : null
                    );
                })
                ->required(),



            TextInput::make('customer_name')
                ->label('Nama Pembeli')
                ->maxLength(255)
                ->required(),

            TextInput::make('customer_phone')
                ->label('Nomor HP Pembeli')
                ->tel()
                ->maxLength(30),

            TextInput::make('quantity')
                ->label('Jumlah Kemasan')
                ->numeric()
                ->rules(['integer'])
                ->minValue(1)
                ->maxValue(1000000)
                ->default(1)
                ->suffix('kemasan')
                ->required(),

            TextInput::make('unit_price')
                ->label('Harga Jual per Kemasan')
                ->prefix('Rp')
                ->numeric()
                ->rules(['integer'])
                ->minValue(1)
                ->maxValue(1000000000)
                ->helperText('Isi harga yang disepakati, tanpa titik atau koma.')
                ->required(),

            Select::make('payment_method')
                ->label('Metode Pembayaran')
                ->options([
                    'tunai' => 'Tunai',
                    'transfer' => 'Transfer Bank',
                ])
                ->default('tunai')
                ->required(),

            Textarea::make('notes')
                ->label('Catatan Penjualan')
                ->rows(3)
                ->maxLength(2000)
                ->columnSpanFull(),
        ]);
    }
}
