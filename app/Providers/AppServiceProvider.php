<?php

namespace App\Providers;

use App\Contracts\Auth\PendingTwoFactorChallengeServiceInterface;
use App\Contracts\Auth\TwoFactorAuthenticationServiceInterface;
use App\Models\User;
use App\Services\Auth\PendingTwoFactorChallengeService;
use App\Services\Auth\TwoFactorAuthenticationService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            TwoFactorAuthenticationServiceInterface::class,
            TwoFactorAuthenticationService::class
        );
        $this->app->bind(
            PendingTwoFactorChallengeServiceInterface::class,
            PendingTwoFactorChallengeService::class
        );
    }

    public function boot(): void
    {
        RateLimiter::for('two-factor-challenge', function (Request $request) {
            return [
                Limit::perMinute(5)->by($request->ip().'|'.$request->session()->getId()),
                Limit::perMinute(20)->by($request->ip()),
            ];
        });
        Gate::before(function (User $user): ?bool {
            if ($user->status !== 'active') {
                return false;
            }

            return $user->hasRole('Super Admin', 'web') ? true : null;
        });
    }
}
