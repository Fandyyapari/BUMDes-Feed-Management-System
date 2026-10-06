<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function handleRecordUpdate(
        Model $record,
        array $data
    ): Model {
        // Periksa kembali akun yang sedang login.
        $actor = Filament::auth()->user();

        abort_unless(
            $actor instanceof User
                && ($actor->fresh()?->isAdmin() ?? false),
            403,
            'Hanya admin yang boleh mengubah akun.'
        );

        // Sesuai aturan resource: akun sendiri tidak diubah di sini.
        abort_if(
            (string) $record->getKey() === (string) Filament::auth()->id(),
            403,
            'Akun sendiri tidak dapat diubah melalui Kelola Akun.'
        );

        // Kata sandi kosong berarti tetap memakai kata sandi lama.
        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        $validated = Validator::make(
            ['data' => $data],
            [
                'data.name' => ['required', 'string', 'max:255'],
                'data.email' => [
                    'required',
                    'email',
                    'max:255',
                    Rule::unique('users', 'email')
                        ->ignore($record->getKey()),
                ],
                'data.password' => [
                    'sometimes',
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
                    'required',
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

        $user = User::query()->findOrFail($record->getKey());

        $user->name = $validated['name'];

        // Email baru perlu diverifikasi ulang jika fitur itu digunakan.
        if ($user->email !== $validated['email']) {
            $user->email_verified_at = null;
        }

        $user->email = $validated['email'];
        $user->role = $validated['role'];

        // Pelanggan tidak boleh memiliki izin pembatalan stok.
        $user->can_cancel_stock =
            in_array($validated['role'], ['admin', 'petugas'], true)
            && (bool) $validated['can_cancel_stock'];

        if (isset($validated['password'])) {
            $user->password = $validated['password'];
        }

        $user->save();

        return $user;
    }

    protected function getRedirectUrl(): string
    {
        return UserResource::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Perubahan akun berhasil disimpan';
    }
}