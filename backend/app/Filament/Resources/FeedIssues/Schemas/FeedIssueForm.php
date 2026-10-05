<?php

namespace App\Filament\Resources\FeedIssues\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class FeedIssueForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('issue_number')
                    ->label('Nomor Pengeluaran')
                    ->placeholder('Contoh: PK-20261005-001')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),

                Select::make('feed_product_id')
                    ->label('Produk Pakan')
                    ->relationship('feedProduct', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                DatePicker::make('issued_at')
                    ->label('Tanggal Keluar')
                    ->default(now())
                    ->required(),

                TextInput::make('quantity')
                    ->label('Jumlah Keluar')
                    ->numeric()
                    ->rules(['integer'])
                    ->minValue(1)
                    ->maxValue(1000000)
                    ->step(1)
                    ->suffix('kemasan')
                    ->helperText('Masukkan jumlah kemasan, bukan berat kilogram.')
                    ->required(),

                Select::make('reason')
                    ->label('Alasan Pengeluaran')
                    ->options([
                        'percontohan' => 'Percontohan / Uji Coba',
                        'rusak' => 'Pakan Rusak',
                        'kedaluwarsa' => 'Pakan Kedaluwarsa',
                        'lainnya' => 'Lainnya',
                    ])
                    ->helperText('Pengeluaran untuk penjualan akan dicatat melalui fitur penjualan.')
                    ->required(),

                TextInput::make('recipient_name')
                    ->label('Nama Penerima')
                    ->placeholder('Isi jika pakan diserahkan kepada seseorang')
                    ->maxLength(255),

                Textarea::make('notes')
                    ->label('Keterangan Pengeluaran')
                    ->placeholder('Jelaskan tujuan atau kondisi pakan yang dikeluarkan')
                    ->rows(3)
                    ->maxLength(5000)
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}