<?php

namespace App\Actions\Auth\TwoFactor;

use App\Contracts\Auth\TwoFactorAuthenticationServiceInterface;
use App\Models\User;

final readonly class CancelTwoFactorSetupAction
{
    public function __construct(
        private TwoFactorAuthenticationServiceInterface $twoFactorAuthenticationService,
    ) {}

    public function handle(User $user): bool
    {
        return $this->twoFactorAuthenticationService->cancelSetup($user);
    }
}
