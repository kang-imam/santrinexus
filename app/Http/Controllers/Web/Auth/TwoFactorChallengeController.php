<?php

namespace App\Http\Controllers\Web\Auth;

use App\Actions\Auth\TwoFactor\VerifyPendingTwoFactorChallengeAction;
use App\Contracts\Auth\PendingTwoFactorChallengeServiceInterface;
use App\DataTransferObjects\Auth\TwoFactor\TwoFactorVerificationData;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

final class TwoFactorChallengeController extends Controller
{
    public function __construct(
        private readonly VerifyPendingTwoFactorChallengeAction $verifyPendingTwoFactorChallengeAction,
        private readonly PendingTwoFactorChallengeServiceInterface $pendingChallengeService,
    ) {}

    public function create(): View
    {
        return view('auth.two-factor-challenge');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['nullable', 'string', 'digits:6'],
            'recovery_code' => ['nullable', 'string', 'max:64'],
        ], [
            'code.digits' => 'Kode autentikasi harus terdiri dari 6 digit.',
            'recovery_code.max' => 'Recovery code tidak valid.',
        ]);
        $hasCode = isset($validated['code']) && $validated['code'] !== '';
        $hasRecoveryCode = isset($validated['recovery_code']) && $validated['recovery_code'] !== '';
        if ($hasCode === $hasRecoveryCode) {
            throw ValidationException::withMessages([
                'code' => 'Masukkan tepat satu kode verifikasi.',
            ]);
        }
        $data = new TwoFactorVerificationData(
            code: $hasCode ? $validated['code'] : null,
            recoveryCode: $hasRecoveryCode ? $validated['recovery_code'] : null,
        );
        $user = $this->verifyPendingTwoFactorChallengeAction->execute($data, $request);
        if (! $user instanceof User) {
            throw ValidationException::withMessages([
                'code' => 'Autentikasi dua faktor gagal diselesaikan.',
            ]);
        }
        if (! $user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice')
                ->with('warning', 'Silakan verifikasi alamat email sebelum melanjutkan.');
        }

        return redirect()->intended(route('account.dashboard'));
    }

    public function destroy(): RedirectResponse
    {
        $this->pendingChallengeService->cancel();

        return redirect()->route('login')
            ->with('status', 'Proses autentikasi dibatalkan. Silakan login kembali.');
    }
}
