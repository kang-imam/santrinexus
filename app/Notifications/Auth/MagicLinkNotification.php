<?php

namespace App\Notifications\Auth;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class MagicLinkNotification extends Notification
{
    public function __construct(
        public readonly string $url,
        public readonly int $expirationMinutes
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $data = [
            'user' => $notifiable,
            'url' => $this->url,
            'expirationMinutes' => $this->expirationMinutes,
            'company' => config('company'),
        ];

        return (new MailMessage)
            ->subject('Tautan Login SantriNexus')
            ->view('emails.auth.magic-link', $data)
            ->text('emails.auth.magic-link-text', $data);
    }
}
