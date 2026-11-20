<?php

namespace App\Actions\Auth\TwoFactor;

use App\Contracts\Auth\TwoFactorAuthenticationServiceInterface;
use App\DataTransferObjects\Auth\TwoFactor\TwoFactorRecoveryCodesData;
use App\Models\User;

final readonly class ConfirmTwoFactorSetupAction
{
    public function __construct(
        private TwoFactorAuthenticationServiceInterface $twoFactorAuthenticationService,
    ) {}

    public function handle(User $user, string $code): ?TwoFactorRecoveryCodesData
    {
        return $this->twoFactorAuthenticationService->confirmSetup($user, $code);
    }
}
