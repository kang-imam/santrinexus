<?php

namespace App\Actions\Auth\Login;

use App\Contracts\Auth\PendingTwoFactorChallengeServiceInterface;
use App\DataTransferObjects\Auth\Authentication\AuthenticationResultData;
use App\DataTransferObjects\Auth\LoginData;
use App\Enums\Auth\AuthenticationMethod;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final readonly class LoginUserAction
{
    public function __construct(
        private PendingTwoFactorChallengeServiceInterface $pendingChallengeService,
        private CompleteWebLoginAction $completeWebLoginAction,
    ) {}

    public function handle(LoginData $data, Request $request): AuthenticationResultData
    {
        $column = match ($data->identifierType) {
            'email' => 'email',
            'username' => 'username',
            'phone' => 'phone',
            default => throw new AuthenticationException,
        };
        $authenticated = Auth::guard('web')->attempt([
            $column => $data->normalizedIdentifier,
            'password' => $data->password,
            'status' => 'active',
        ], false);
        if (! $authenticated) {
            throw new AuthenticationException;
        }
        $user = Auth::guard('web')->user();
        if (! $user instanceof User) {
            Auth::guard('web')->logout();
            throw new AuthenticationException;
        }
        Auth::guard('web')->logout();
        if ($user->two_factor_enabled) {
            if ($user->two_factor_confirmed_at === null) {
                throw new AuthenticationException;
            }
            $this->pendingChallengeService->begin(
                $user,
                AuthenticationMethod::PASSWORD,
                $data->remember,
            );

            return AuthenticationResultData::requiresTwoFactor();
        }
        $account = $this->completeWebLoginAction->execute(
            $user,
            $request,
            $data->remember,
        );

        return AuthenticationResultData::authenticated($account);
    }
}
