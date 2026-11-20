<?php

namespace App\Http\Controllers\Web\Auth;

use App\Actions\Auth\Login\LoginUserAction;
use App\DataTransferObjects\Auth\LoginData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\RedirectResponse;

final class LoginController extends Controller
{
    public function __construct(
        private readonly LoginUserAction $loginUserAction,
    ) {}

    public function store(LoginRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $data = new LoginData(
            identifier: $validated['identifier'],
            password: $validated['password'],
            remember: $validated['remember'] ?? false,
        );
        $result = $this->loginUserAction->handle($data, $request);
        if ($result->requiresTwoFactorVerification()) {
            return redirect()->route('two-factor.challenge');
        }
        $user = $result->user;
        if (! $user instanceof User) {
            throw new AuthenticationException;
        }
        if (! $user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice')
                ->with('warning', 'Silakan verifikasi alamat email sebelum melanjutkan.');
        }

        return redirect()->intended(route('account.dashboard'));
    }
}
