<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class CustomerPasswordResetController extends Controller
{
    public function showForgotPassword(): View
    {
        return view('auth.forgot-password');
    }

    public function showResetPassword(
        Request $request,
        string $token
    ): View {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ], [
            'email.required' => 'Email pada tautan reset tidak tersedia.',
            'email.email' => 'Email pada tautan reset tidak valid.',
            'email.max' => 'Email terlalu panjang.',
        ]);

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $validated['email'],
        ]);
    }

    public function sendResetLink(Request $request): RedirectResponse
    {
        // Samakan format email dengan proses pendaftaran.
        $request->merge([
            'email' => strtolower(
                trim((string) $request->input('email'))
            ),
        ]);

        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Masukkan alamat email yang benar.',
            'email.max' => 'Email maksimal 255 karakter.',
        ]);

        try {
            // Fitur ini khusus untuk akun pelanggan.
            Password::broker('users')->sendResetLink([
                'email' => $validated['email'],
                'role' => 'pelanggan',
            ]);
        } catch (TransportExceptionInterface $exception) {
            // Catat kegagalan tanpa menyimpan sandi atau tautan reset.
            Log::warning('Pengiriman email reset pelanggan gagal.', [
                'exception_class' => get_class($exception),
                'exception_code' => $exception->getCode(),
            ]);
        }

        // Pesan sama untuk email terdaftar maupun tidak terdaftar.
        return back()->with(
            'status',
            'Jika email terdaftar, kami akan mencoba mengirim tautan reset. '
                . 'Periksa kotak masuk dan spam. Jika belum diterima, '
                . 'coba lagi beberapa menit kemudian atau hubungi pengurus.'
        );
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        $request->merge([
            'email' => strtolower(
                trim((string) $request->input('email'))
            ),
        ]);

        $validated = $request->validate([
            'token' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'password' => [
                'bail',
                'required',
                'string',
                'min:8',
                'max:72',
                'confirmed',
                function ($attribute, $value, $fail) {
                    if (strlen($value) > 72) {
                        $fail('Kata sandi terlalu panjang. Maksimal 72 byte.');
                    }
                },
            ],
        ], [
            'token.required' => 'Tautan reset tidak lengkap. Minta tautan baru.',
            'token.string' => 'Tautan reset tidak valid.',
            'token.max' => 'Tautan reset tidak valid.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email belum benar.',
            'email.max' => 'Email maksimal 255 karakter.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.max' => 'Kata sandi maksimal 72 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi belum cocok.',
        ]);

        $status = Password::broker('users')->reset(
            [
                'email' => $validated['email'],
                'token' => $validated['token'],
                'password' => $validated['password'],
                'password_confirmation' => $request->input(
                    'password_confirmation'
                ),
                'role' => 'pelanggan',
            ],
            function (User $user, string $password): void {
                // Password otomatis di-hash oleh cast pada model User.
                $user->password = $password;
                $user->setRememberToken(Str::random(60));
                $user->save();

                // Hapus sesi login milik akun yang baru mereset kata sandi.
                DB::connection(config('session.connection'))
                    ->table(config('session.table', 'sessions'))
                    ->where('user_id', $user->getAuthIdentifier())
                    ->delete();

                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => 'Tautan reset tidak valid atau sudah kedaluwarsa. '
                    . 'Silakan minta tautan baru.',
            ]);
        }

        // Pelanggan masuk kembali menggunakan kata sandi baru.
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with(
                'success',
                'Kata sandi berhasil direset. Silakan masuk '
                    . 'menggunakan kata sandi baru.'
            );
    }
}
