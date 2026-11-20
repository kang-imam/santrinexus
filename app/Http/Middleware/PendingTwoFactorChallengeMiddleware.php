<?php

namespace App\Http\Middleware;

use App\Contracts\Auth\PendingTwoFactorChallengeServiceInterface;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final readonly class PendingTwoFactorChallengeMiddleware
{
    public function __construct(
        private PendingTwoFactorChallengeServiceInterface $pendingChallengeService,
    ) {}

    public function handle(Request $request, Closure $next): Response|RedirectResponse
    {
        if ($request->user('web')) {
            $this->pendingChallengeService->cancel();

            return redirect()->route('account.dashboard');
        }
        if (! $this->pendingChallengeService->isPending()) {
            $this->pendingChallengeService->clearExpired();

            return redirect()
                ->route('login')
                ->withErrors([
                    'two_factor' => 'Sesi verifikasi tidak valid atau telah kedaluwarsa. Silakan login kembali.',
                ]);
        }

        return $next($request);
    }
}
