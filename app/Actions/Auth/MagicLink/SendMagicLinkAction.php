<?php

namespace App\Actions\Auth\MagicLink;

use App\DataTransferObjects\Auth\MagicLinkData;
use App\Models\User;
use App\Notifications\Auth\MagicLinkNotification;
use App\Services\Auth\MagicLinkService;
use Illuminate\Support\Facades\URL;

class SendMagicLinkAction
{
    public function __construct(
        private readonly MagicLinkService $magicLinkService
    ) {}

    public function execute(MagicLinkData $data): void
    {
        if (! $data->email) {
            return;
        }
        $user = User::query()
            ->where('email', $data->email)
            ->where('status', 'active')
            ->first();
        if (! $user) {
            return;
        }
        $expirationMinutes = $this->magicLinkService->expirationMinutes();
        $expiresAt = now()->addMinutes($expirationMinutes);
        $token = $this->magicLinkService->issue($user, $expiresAt);
        $url = URL::temporarySignedRoute(
            'magic-link.confirm',
            $expiresAt,
            ['token' => $token]
        );
        $user->notify(new MagicLinkNotification(
            url: $url,
            expirationMinutes: $expirationMinutes
        ));
    }
}
