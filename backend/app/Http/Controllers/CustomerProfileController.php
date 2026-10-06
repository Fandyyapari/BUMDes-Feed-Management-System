<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
}