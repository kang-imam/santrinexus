<?php

namespace App\Actions\Auth\MagicLink;

use App\DataTransferObjects\Auth\MagicLinkData;
use App\Models\User;
use App\Services\Auth\MagicLinkService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class VerifyMagicLinkAction
{
    public function __construct(
        private readonly MagicLinkService $magicLinkService
    ) {}

    public function execute(MagicLinkData $data, Request $request): User
    {
        if (! $data->token || preg_match('/\A[a-f0-9]{64}\z/', $data->token) !== 1) {
            throw ValidationException::withMessages([
                'token' => 'Token autentikasi tidak valid.',
            ]);
        }
        $user = $this->magicLinkService->consume($data->token);
        if (! $user || $user->status !== 'active') {
            throw ValidationException::withMessages([
                'token' => 'Tautan login tidak valid, sudah digunakan, kedaluwarsa, atau akun tidak aktif.',
            ]);
        }
        $user->forceFill([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ])->save();
        Auth::login($user);
        $request->session()->regenerate();

        return $user;
    }
}
