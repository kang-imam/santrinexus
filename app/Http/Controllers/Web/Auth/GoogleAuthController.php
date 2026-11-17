<?php

namespace App\Http\Controllers\Web\Auth;

use App\DataTransferObjects\Auth\GoogleUserData;
use App\Http\Controllers\Controller;
use App\Services\Auth\GoogleAuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleAuthController extends Controller
{
    public function __construct(private GoogleAuthService $googleAuthService) {}

    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback(Request $request): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $userData = GoogleUserData::fromSocialite($googleUser);
            $this->googleAuthService->authenticate($userData, $request);

            return redirect()->intended(route('account.dashboard'));
        } catch (Throwable $exception) {
            report($exception);

            return redirect()->route('login')->withErrors([
                'google' => 'Login dengan Google gagal. Silakan coba kembali.',
            ]);
        }
    }
}
