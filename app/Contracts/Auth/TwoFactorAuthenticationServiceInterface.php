<?php

namespace App\Contracts\Auth;

use App\DataTransferObjects\Auth\TwoFactor\TwoFactorRecoveryCodesData;
use App\DataTransferObjects\Auth\TwoFactor\TwoFactorSetupData;
use App\Models\User;

interface TwoFactorAuthenticationServiceInterface
{
    public function beginSetup(User $user): TwoFactorSetupData;

    public function getProvisioningUri(User $user): string;

    public function generateQrCode(User $user): string;

    public function confirmSetup(User $user, string $code): ?TwoFactorRecoveryCodesData;

    public function verifyCode(User $user, string $code): bool;

    public function verifyRecoveryCode(User $user, string $code): bool;

    public function regenerateRecoveryCodes(User $user, string $code): ?TwoFactorRecoveryCodesData;

    public function disable(User $user, string $code, bool $useRecoveryCode = false): bool;

    public function cancelSetup(User $user): bool;

    public function isEnabled(User $user): bool;

    public function isConfirmed(User $user): bool;
}
