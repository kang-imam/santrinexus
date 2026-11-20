<?php

namespace App\Notifications\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MagicLinkNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly string $url,
        public readonly int $expirationMinutes
    ) {
        $this->afterCommit();
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Tautan Login SantriNexus')
            ->greeting('Halo, '.$notifiable->name.'!')
            ->line('Kami menerima permintaan untuk masuk ke akun SantriNexus Anda.')
            ->line('Gunakan tombol berikut untuk melanjutkan proses login.')
            ->action('Login Sekarang', $this->url)
            ->line('Tautan ini hanya dapat digunakan satu kali.')
            ->line('Tautan akan kedaluwarsa dalam '.$this->expirationMinutes.' menit.')
            ->line('Jika Anda tidak merasa meminta tautan login ini, abaikan email ini.');
    }
}
