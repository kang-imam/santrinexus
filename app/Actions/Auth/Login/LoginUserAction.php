<?php

namespace App\Actions\Auth\Login;

use App\DataTransferObjects\Auth\LoginData;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginUserAction
{
    public function handle(LoginData $data, Request $request): User
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
        ], $data->remember);
        if (! $authenticated) {
            throw new AuthenticationException;
        }
        $request->session()->regenerate();
        $user = Auth::guard('web')->user();
        if (! $user instanceof User) {
            Auth::guard('web')->logout();
            throw new AuthenticationException;
        }
        $user->forceFill([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ])->save();

        return $user;
    }
}
