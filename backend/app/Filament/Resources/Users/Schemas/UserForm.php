<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->label('Nama Lengkap')
                ->required()
                ->trim()
                ->maxLength(255),

            TextInput::make('email')
                ->label('Alamat Email')
                ->email()
                ->required()
                ->trim()
                ->maxLength(255)
                ->unique(ignoreRecord: true),

            TextInput::make('password')
                ->label('Kata Sandi')
                ->password()
                ->revealable()
                ->autocomplete('new-password')
                ->minLength(8)
                ->maxLength(72)
                ->required(
                    fn (string $operation): bool =>
                        $operation === 'create'
                )
                ->afterStateHydrated(
                    fn (TextInput $component) =>
                        $component->state('')
                )
                ->saved(
                    fn (?string $state): bool => filled($state)
                )
                ->helperText(
                    'Minimal 8 karakter. Saat mengubah akun, '
                    . 'kosongkan untuk mempertahankan kata sandi lama.'
                ),

            Select::make('role')
                ->label('Peran Akun')
                ->options([
                    'admin' => 'Admin',
                    'petugas' => 'Petugas BUMDes',
                    'pelanggan' => 'Pelanggan',
                ])
                ->default('pelanggan')
                ->required()
                ->rules([
                    'in:admin,petugas,pelanggan',
                ])
                ->helperText(
                    'Admin mengelola akun. Petugas mengelola '
                    . 'transaksi pakan. Pelanggan tidak masuk panel admin.'
                ),

            Toggle::make('can_cancel_stock')
                ->label('Izinkan Pembatalan Transaksi Stok')
                ->default(false)
                ->helperText(
                    'Izin khusus untuk membatalkan pakan masuk, '
                    . 'pakan keluar, dan penjualan. '
                    . 'Hanya diberikan kepada admin atau petugas yang dipercaya.'
                ),
        ]);
    }
}