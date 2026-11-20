<?php

namespace App\Http\Controllers\Web\Auth;

use App\Actions\Auth\Register\RegisterUserAction;
use App\DataTransferObjects\Auth\RegisterData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

final class RegisterController extends Controller
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
        Auth::guard('web')->login($user);
        $request->session()->regenerate();
        $user->sendEmailVerificationNotification();

        return redirect()->route('verification.notice')->with('success', 'Registrasi berhasil. Silakan periksa email untuk memverifikasi akun.');
    }
}
