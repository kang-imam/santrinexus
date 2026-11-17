<?php

namespace App\Actions\Auth\Google;

use App\DataTransferObjects\Auth\GoogleUserData;
use App\Models\User;

class FindGoogleUserAction
{
    public function execute(GoogleUserData $data): ?User
    {
        return User::withTrashed()
            ->where('google_id', $data->googleId)
            ->first();
    }

    public function findByEmail(string $email): ?User
    {
        return User::withTrashed()
            ->where('email', mb_strtolower(trim($email)))
            ->first();
    }
}
