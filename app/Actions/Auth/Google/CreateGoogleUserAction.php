<?php

namespace App\Actions\Auth\Google;

use App\DataTransferObjects\Auth\GoogleUserData;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CreateGoogleUserAction
{
    public function execute(GoogleUserData $data): User
    {
        if (! $data->email || ! $data->emailVerified || ! $data->googleId) {
            throw ValidationException::withMessages([
                'google' => 'Data akun Google tidak valid.',
            ]);
        }

        return User::create([
            'name' => $data->name ?: 'Pengguna Google',
            'username' => $this->generateUsername($data->name),
            'email' => $data->email,
            'google_id' => $data->googleId,
            'avatar' => $data->avatar,
            'email_verified_at' => now(),
            'password' => Str::random(64),
            'status' => 'active',
        ]);
    }

    private function generateUsername(?string $name): string
    {
        $base = Str::slug($name ?? 'pengguna', '');
        $base = mb_substr($base ?: 'pengguna', 0, 40);
        do {
            $username = $base.Str::lower(Str::random(6));
        } while (User::withTrashed()->where('username', $username)->exists());

        return $username;
    }
}
