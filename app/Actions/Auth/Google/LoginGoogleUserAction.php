<?php

namespace App\Actions\Auth\Google;

use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginGoogleUserAction
{
    public function execute(User $user, Request $request): User
    {
        $account = DB::transaction(function () use ($user, $request) {
            $account = User::query()->lockForUpdate()->findOrFail($user->id);
            if ($account->status !== 'active') {
                throw new AuthorizationException('Akun tidak dapat digunakan untuk login.');
            }
            $account->forceFill([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
            ])->save();

            return $account;
        });
        Auth::guard('web')->login($account);
        $request->session()->regenerate();

        return $account;
    }
}
