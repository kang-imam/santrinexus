<?php

namespace App\Actions\Auth\TwoFactor;

use App\Contracts\Auth\TwoFactorAuthenticationServiceInterface;
use App\DataTransferObjects\Auth\TwoFactor\TwoFactorVerificationData;
use App\Models\User;

final readonly class DisableTwoFactorAction
{
    public function __construct(
        private TwoFactorAuthenticationServiceInterface $twoFactorAuthenticationService,
    ) {}

    public function handle(User $user, TwoFactorVerificationData $data): bool
    {
        $useRecoveryCode = is_string($data->recoveryCode) && trim($data->recoveryCode) !== '';
        $code = $useRecoveryCode ? $data->recoveryCode : $data->code;

        return $this->twoFactorAuthenticationService->disable(
            $user,
            $code,
            $useRecoveryCode
        );
    }
}
