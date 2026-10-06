<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        // Periksa kembali peran akun yang sedang login.
        $actor = Filament::auth()->user();

        abort_unless(
            $actor instanceof User
                && ($actor->fresh()?->isAdmin() ?? false),
            403,
            'Hanya admin yang boleh membuat akun.'
        );

        // Validasi ulang data sebelum disimpan.
        $validated = Validator::make(
            ['data' => $data],
            [
                'data.name' => ['required', 'string', 'max:255'],
                'data.email' => [
                    'required',
                    'email',
                    'max:255',
                    'unique:users,email',
                ],
                'data.password' => [
                    'required',
                    'string',
                    'min:8',
                    'max:72',
                ],
                'data.role' => [
                    'required',
                    'in:admin,petugas,pelanggan',
                ],
                'data.can_cancel_stock' => [
                    'sometimes',
                    'boolean',
                ],
            ],
            [],
            [
                'data.name' => 'nama lengkap',
                'data.email' => 'alamat email',
                'data.password' => 'kata sandi',
                'data.role' => 'peran akun',
                'data.can_cancel_stock' => 'izin pembatalan stok',
            ]
        )->validate()['data'];

        // Isi hanya kolom yang memang boleh disimpan.
        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = $validated['password'];
        $user->role = $validated['role'];

        // Pelanggan selalu tidak memiliki izin pembatalan stok.
        $user->can_cancel_stock =
            in_array($validated['role'], ['admin', 'petugas'], true)
            && (bool) ($validated['can_cancel_stock'] ?? false);

        $user->save();

        return $user;
    }

    protected function getRedirectUrl(): string
    {
        return UserResource::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Akun berhasil ditambahkan';
    }
}