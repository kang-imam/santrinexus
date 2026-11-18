<?php

namespace App\Http\Controllers\Web\Auth;

use App\Actions\Auth\Register\RegisterUserAction;
use App\DataTransferObjects\Auth\RegisterData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\RedirectResponse;

class RegisterController extends Controller
{
    public function __construct(private RegisterUserAction $registerUserAction) {}

    public function store(RegisterRequest $request): RedirectResponse
    {
        $data = new RegisterData(
            name: $request->validated('name'),
            username: $request->validated('username'),
            email: $request->validated('email'),
            phone: $request->validated('phone'),
            password: $request->validated('password'),
            passwordConfirmation: $request->validated('password_confirmation')
        );
        $user = $this->registerUserAction->handle($data);

        return redirect()->route('login')->with('success', 'Registrasi berhasil. Silakan login.');
    }
}
