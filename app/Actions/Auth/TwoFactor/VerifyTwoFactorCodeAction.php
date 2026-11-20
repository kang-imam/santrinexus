<?php

namespace App\Actions\Auth\TwoFactor;

use App\Contracts\Auth\TwoFactorAuthenticationServiceInterface;
use App\DataTransferObjects\Auth\TwoFactor\TwoFactorVerificationData;
use App\Models\User;

final readonly class VerifyTwoFactorCodeAction
{
    public function __construct(
        private TwoFactorAuthenticationServiceInterface $twoFactorAuthenticationService,
    ) {}

    public function handle(User $user, TwoFactorVerificationData $data): bool
    {
        if (trim($data->recoveryCode) !== '') {
            return false;
        }

        return $this->twoFactorAuthenticationService->verifyCode($user, $data->code);
    }
}
