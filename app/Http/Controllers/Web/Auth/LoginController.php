<?php

namespace App\Http\Controllers\Web\Auth;

use App\Actions\Auth\Login\LoginUserAction;
use App\DataTransferObjects\Auth\LoginData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller
{
    public function __construct(private LoginUserAction $loginUserAction) {}

    public function store(LoginRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $data = new LoginData(
            identifier: $validated['identifier'],
            password: $validated['password'],
            remember: $validated['remember'] ?? false
        );
        $this->loginUserAction->handle($data, $request);

        return redirect()->intended(route('account.dashboard'));
    }
}
