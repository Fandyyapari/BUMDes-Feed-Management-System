<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CustomerAuthController extends Controller
{
    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        // Rapikan email dan nomor HP sebelum diperiksa.
        $request->merge([
            'email' => strtolower(trim((string) $request->input('email'))),
            'phone' => $this->normalizePhone(
                (string) $request->input('phone')
            ),
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => [
                'required',
                'regex:/^628[0-9]{8,11}$/',
                'unique:users,phone',
            ],
            'dusun' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:1000'],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:72',
                'confirmed',
                function ($attribute, $value, $fail) {
                    if (strlen($value) > 72) {
                        $fail('Kata sandi terlalu panjang.');
                    }
                },
            ],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email belum benar.',
            'email.unique' => 'Email sudah digunakan.',
            'phone.required' => 'Nomor HP wajib diisi.',
            'phone.regex' => 'Masukkan nomor HP yang benar, misalnya 081234567890.',
            'phone.unique' => 'Nomor HP sudah digunakan.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.max' => 'Kata sandi maksimal 72 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi belum cocok.',
        ]);

        $user = new User();
        $user->fill($validated);

        // Pendaftar selalu menjadi pelanggan.
        $user->role = 'pelanggan';
        $user->can_cancel_stock = false;
        $user->save();

        // Password dienkripsi oleh cast "hashed" pada model User.
        Auth::guard('web')->login($user);
        $request->session()->regenerate();

        return redirect()->route('home')
            ->with('success', 'Akun berhasil dibuat. Selamat datang!');
    }

    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'login' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'max:72'],
        ], [
            'login.required' => 'Email atau nomor HP wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $login = trim($validated['login']);
        $isEmail = filter_var($login, FILTER_VALIDATE_EMAIL) !== false;

        $field = $isEmail ? 'email' : 'phone';
        $value = $isEmail
            ? strtolower($login)
            : $this->normalizePhone($login);

        $credentials = [
            $field => $value,
            'password' => $validated['password'],
            'role' => 'pelanggan',
        ];

        if (! Auth::guard('web')->attempt($credentials)) {
            throw ValidationException::withMessages([
                'login' => 'Email, nomor HP, atau kata sandi belum cocok.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->route('home')
            ->with('success', 'Berhasil masuk.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'Berhasil keluar.');
    }

    private function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/[\s()+-]/', '', trim($phone));

        if (str_starts_with($phone, '08')) {
            return '62' . substr($phone, 1);
        }

        return $phone;
    }
}