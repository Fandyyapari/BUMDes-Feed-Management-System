<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomerResetPasswordNotification extends Notification
{
    public function __construct(
        private readonly string $token
    ) {
        $this->locale('id');
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        // Alamat utama berasal dari APP_URL di konfigurasi aplikasi.
        $baseUrl = rtrim((string) config('app.url'), '/');

        $path = route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false);

        $resetUrl = $baseUrl . '/' . ltrim($path, '/');

        // Sesuai broker "users" yang dipakai controller reset.
        $minutes = (int) config('auth.passwords.users.expire', 60);

        return (new MailMessage())
            ->subject('Reset Kata Sandi Akun JarakFeed')
            ->greeting('Halo, Pelanggan JarakFeed!')
            ->line(
                'Kami menerima permintaan untuk membuat '
                    . 'kata sandi baru pada akun Anda.'
            )
            ->action('Buat Kata Sandi Baru', $resetUrl)
            ->line(
                "Tautan ini berlaku selama {$minutes} menit "
                    . 'dan hanya dapat digunakan satu kali.'
            )
            ->line(
                'Jika Anda tidak meminta reset kata sandi, '
                    . 'abaikan email ini. Kata sandi Anda tidak berubah.'
            )
            ->salutation('Salam, Pengurus JarakFeed — BUMDes Desa Jarak');
    }
}
