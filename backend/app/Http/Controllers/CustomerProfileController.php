<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class CustomerProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user();

        abort_unless($user && $user->role === 'pelanggan', 403);

        return view('profile.edit', [
            'user' => $user,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        abort_unless($user && $user->role === 'pelanggan', 403);

        $request->merge([
            'name' => trim((string) $request->input('name')),
            'dusun' => trim((string) $request->input('dusun')),
            'address' => trim((string) $request->input('address')),
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'dusun' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:1000'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.max' => 'Nama maksimal 255 karakter.',
            'dusun.max' => 'Nama dusun maksimal 100 karakter.',
            'address.max' => 'Alamat maksimal 1.000 karakter.',
        ]);

        $user->name = $validated['name'];
        $user->dusun = $validated['dusun'] ?: null;
        $user->address = $validated['address'] ?: null;
        $user->save();

        return redirect()
            ->route('customer.profile.edit')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $user = $request->user();

        abort_unless($user && $user->role === 'pelanggan', 403);

        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => [
                'bail',
                'required',
                'string',
                'current_password:web',
            ],
            'password' => [
                'bail',
                'required',
                'string',
                'min:8',
                'max:72',
                'confirmed',
                'different:current_password',
                function ($attribute, $value, $fail) {
                    if (strlen($value) > 72) {
                        $fail('Kata sandi terlalu panjang. Maksimal 72 byte.');
                    }
                },
            ],
        ], [
            'current_password.required' => 'Kata sandi lama wajib diisi.',
            'current_password.current_password' => 'Kata sandi lama belum cocok.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi baru minimal 8 karakter.',
            'password.max' => 'Kata sandi baru maksimal 72 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi belum cocok.',
            'password.different' => 'Kata sandi baru harus berbeda dari yang lama.',
        ]);

        // Model User sudah menggunakan cast password: hashed.
        $user->password = $validated['password'];
        $user->setRememberToken(Str::random(60));
        $user->save();

        // Hapus sesi perangkat lain milik pengguna ini.
        DB::connection(config('session.connection'))
            ->table(config('session.table', 'sessions'))
            ->where('user_id', $user->getAuthIdentifier())
            ->where('id', '!=', $request->session()->getId())
            ->delete();

        // Ganti ID sesi perangkat sekarang dan hapus ID lamanya.
        $request->session()->regenerate(true);

        return redirect()
            ->route('customer.profile.edit')
            ->with('success', 'Kata sandi berhasil diperbarui.');
    }
}
