<?php

namespace App\Filament\Resources\FeedIssues\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class FeedIssueInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('issue_number')
                    ->label('Nomor Pengeluaran'),

                TextEntry::make('feedProduct.name')
                    ->label('Produk Pakan'),

                TextEntry::make('issued_at')
                    ->label('Tanggal Keluar')
                    ->date('d/m/Y'),

                TextEntry::make('quantity')
                    ->label('Jumlah Keluar')
                    ->numeric(decimalPlaces: 0, locale: 'id')
                    ->suffix(' kemasan'),

                TextEntry::make('reason')
                    ->label('Alasan Pengeluaran')
                    ->formatStateUsing(
                        fn (string $state): string => match ($state) {
                            'percontohan' => 'Percontohan / Uji Coba',
                            'rusak' => 'Pakan Rusak',
                            'kedaluwarsa' => 'Pakan Kedaluwarsa',
                            'lainnya' => 'Lainnya',
                            default => $state,
                        }
                    ),

                TextEntry::make('recipient_name')
                    ->label('Nama Penerima')
                    ->placeholder('Tidak ada penerima'),

                TextEntry::make('notes')
                    ->label('Keterangan Pengeluaran')
                    ->columnSpanFull(),

                TextEntry::make('created_at')
                    ->label('Waktu Pencatatan')
                    ->dateTime('d/m/Y H:i'),
            ]);
    }
}