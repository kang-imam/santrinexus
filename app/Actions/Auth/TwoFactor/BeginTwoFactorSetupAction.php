<?php

namespace App\Actions\Auth\TwoFactor;

use App\Contracts\Auth\TwoFactorAuthenticationServiceInterface;
use App\DataTransferObjects\Auth\TwoFactor\TwoFactorSetupData;
use App\Models\User;

final readonly class BeginTwoFactorSetupAction
{
    public function __construct(
        private TwoFactorAuthenticationServiceInterface $twoFactorAuthenticationService,
    ) {}

    public function handle(User $user): TwoFactorSetupData
    {
        return $this->twoFactorAuthenticationService->beginSetup($user);
    }
}
