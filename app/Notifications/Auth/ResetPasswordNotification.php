<?php

namespace App\Notifications\Auth;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class ResetPasswordNotification extends Notification
{
    public function __construct(private readonly string $token) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $data = [
            'user' => $notifiable,
            'url' => route('password.reset', [
                'token' => $this->token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ]),
            'expirationMinutes' => (int) config('auth.passwords.'.config('auth.defaults.passwords').'.expire'),
            'company' => config('company'),
        ];

        return (new MailMessage)
            ->subject('Atur Ulang Password Akun SantriNexus')
            ->view('emails.auth.reset-password', $data)
            ->text('emails.auth.reset-password-text', $data);
    }
}
