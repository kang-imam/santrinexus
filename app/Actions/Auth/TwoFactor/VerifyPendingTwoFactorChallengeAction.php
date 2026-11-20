<?php

namespace App\Actions\Auth\TwoFactor;

use App\Actions\Auth\Login\CompleteWebLoginAction;
use App\Contracts\Auth\PendingTwoFactorChallengeServiceInterface;
use App\DataTransferObjects\Auth\Authentication\PendingAuthenticationData;
use App\DataTransferObjects\Auth\TwoFactor\TwoFactorVerificationData;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

final readonly class VerifyPendingTwoFactorChallengeAction
{
    public function __construct(
        private PendingTwoFactorChallengeServiceInterface $pendingChallengeService,
        private VerifyTwoFactorCodeAction $verifyTwoFactorCodeAction,
        private VerifyTwoFactorRecoveryCodeAction $verifyTwoFactorRecoveryCodeAction,
        private CompleteWebLoginAction $completeWebLoginAction,
    ) {}

    public function execute(TwoFactorVerificationData $data, Request $request): User
    {
        $pending = $this->pendingChallengeService->getPending();
        if (! $pending instanceof PendingAuthenticationData) {
            throw ValidationException::withMessages([
                'code' => 'Sesi verifikasi tidak valid atau telah kedaluwarsa.',
            ]);
        }
        $user = $this->pendingChallengeService->getPendingUser();
        if (! $user instanceof User) {
            throw ValidationException::withMessages([
                'code' => 'Sesi verifikasi tidak valid atau telah kedaluwarsa.',
            ]);
        }
        $useRecoveryCode = is_string($data->recoveryCode)
            && trim($data->recoveryCode) !== '';
        $verified = $useRecoveryCode
            ? $this->verifyTwoFactorRecoveryCodeAction->handle($user, $data)
            : $this->verifyTwoFactorCodeAction->handle($user, $data);
        if (! $verified) {
            throw ValidationException::withMessages([
                $useRecoveryCode ? 'recoveryCode' : 'code' => 'Kode autentikasi tidak valid.',
            ]);
        }
        if (! $this->pendingChallengeService->complete($pending->challengeId)) {
            throw ValidationException::withMessages([
                'code' => 'Sesi verifikasi tidak valid atau telah kedaluwarsa.',
            ]);
        }

        return $this->completeWebLoginAction->execute(
            $user,
            $request,
            $pending->remember,
        );
    }
}
