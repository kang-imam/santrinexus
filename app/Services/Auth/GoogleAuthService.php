<?php

namespace App\Services\Auth;

use App\Actions\Auth\Google\CreateGoogleUserAction;
use App\Actions\Auth\Google\FindGoogleUserAction;
use App\Actions\Auth\Google\LinkGoogleAccountAction;
use App\Actions\Auth\Google\LoginGoogleUserAction;
use App\DataTransferObjects\Auth\GoogleUserData;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class GoogleAuthService
{
    public function __construct(
        private FindGoogleUserAction $findGoogleUser,
        private CreateGoogleUserAction $createGoogleUser,
        private LinkGoogleAccountAction $linkGoogleAccount,
        private LoginGoogleUserAction $loginGoogleUser
    ) {}

    public function authenticate(GoogleUserData $data, Request $request): User
    {
        if (! $data->googleId || ! $data->email || ! $data->emailVerified) {
            throw new AuthorizationException('Data akun Google tidak valid atau email belum terverifikasi.');
        }
        $user = $this->findGoogleUser->execute($data);
        if ($user) {
            if ($user->trashed()) {
                throw new AuthorizationException('Akun tidak dapat digunakan untuk login.');
            }
            if ($user->status !== 'active') {
                throw new AuthorizationException('Akun tidak dapat digunakan untuk login.');
            }
        } else {
            $existingUser = $this->findGoogleUser->findByEmail($data->email);
            if ($existingUser) {
                if ($existingUser->trashed() || $existingUser->status !== 'active') {
                    throw new AuthorizationException('Akun tidak dapat digunakan untuk login.');
                }
                if (! $existingUser->email_verified_at) {
                    throw new AuthorizationException('Email akun lama belum terverifikasi. Silakan masuk menggunakan metode sebelumnya.');
                }
                $user = $this->linkGoogleAccount->execute($existingUser, $data);
            } else {
                $user = $this->createGoogleUser->execute($data);
            }
        }

        return $this->loginGoogleUser->execute($user, $request);
    }
}
