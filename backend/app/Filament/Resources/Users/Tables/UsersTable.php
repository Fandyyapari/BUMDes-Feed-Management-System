<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('email')
                    ->label('Alamat Email')
                    ->searchable()
                    ->wrap(),

                TextColumn::make('role')
                    ->label('Peran Akun')
                    ->badge()
                    ->formatStateUsing(
                        fn (string $state): string => match ($state) {
                            'admin' => 'Admin',
                            'petugas' => 'Petugas BUMDes',
                            'pelanggan' => 'Pelanggan',
                            default => 'Belum Ditentukan',
                        }
                    )
                    ->color(
                        fn (string $state): string => match ($state) {
                            'admin' => 'danger',
                            'petugas' => 'success',
                            default => 'gray',
                        }
                    )
                    ->sortable(),

                IconColumn::make('can_cancel_stock')
                    ->label('Izin Pembatalan Stok')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->timezone('Asia/Jakarta')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->label('Peran Akun')
                    ->options([
                        'admin' => 'Admin',
                        'petugas' => 'Petugas BUMDes',
                        'pelanggan' => 'Pelanggan',
                    ]),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Ubah'),
            ])
            ->emptyStateHeading('Belum ada akun')
            ->emptyStateDescription(
                'Tambahkan akun untuk petugas BUMDes atau pelanggan.'
            )
            ->searchPlaceholder('Cari nama atau alamat email')
            ->defaultSort('created_at', 'desc');
    }
}