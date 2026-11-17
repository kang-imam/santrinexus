<?php

namespace App\Actions\Auth\Google;

use App\DataTransferObjects\Auth\GoogleUserData;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;

class LinkGoogleAccountAction
{
    public function execute(User $user, GoogleUserData $data): User
    {
        return DB::transaction(function () use ($user, $data) {
            $account = User::withTrashed()->lockForUpdate()->findOrFail($user->id);
            if ($account->trashed() || $account->status !== 'active' || ! $account->email_verified_at) {
                throw new AuthorizationException('Akun tidak memenuhi persyaratan untuk dihubungkan.');
            }
            if (! $data->emailVerified || ! $data->email || ! hash_equals(mb_strtolower($account->email), mb_strtolower($data->email))) {
                throw new AuthorizationException('Email Google tidak dapat diverifikasi untuk akun ini.');
            }
            if ($account->google_id && ! hash_equals($account->google_id, $data->googleId)) {
                throw new AuthorizationException('Akun sudah terhubung dengan Google lain.');
            }
            $existingGoogleAccount = User::withTrashed()
                ->where('google_id', $data->googleId)
                ->where('id', '!=', $account->id)
                ->exists();
            if ($existingGoogleAccount) {
                throw new AuthorizationException('Google sudah terhubung dengan akun lain.');
            }
            $account->forceFill([
                'google_id' => $data->googleId,
                'avatar' => $account->avatar ?: $data->avatar,
            ])->save();

            return $account;
        });
    }
}
