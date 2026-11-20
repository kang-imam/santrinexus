<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Gate::before(function (User $user): ?bool {
            if ($user->status !== 'active') {
                return false;
            }

            return $user->hasRole('Super Admin', 'web') ? true : null;
        });
    }
}
