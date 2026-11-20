<?php

namespace App\Actions\Auth\Login;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

final readonly class CompleteWebLoginAction
{
    public function execute(User $user, Request $request, bool $remember = false): User
    {
        $account = DB::transaction(function () use ($user, $request): User {
            $account = User::query()
                ->lockForUpdate()
                ->find($user->getKey());
            if (! $account || $account->status !== 'active') {
                throw new AuthenticationException('Akun tidak dapat digunakan untuk login.');
            }
            $account->forceFill([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
            ])->save();

            return $account;
        });
        Auth::guard('web')->login($account, $remember);
        $request->session()->regenerate();

        return $account;
    }
}
