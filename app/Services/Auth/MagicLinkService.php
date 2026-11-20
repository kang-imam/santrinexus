<?php

namespace App\Services\Auth;

use App\Models\MagicLinkToken;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class MagicLinkService
{
    public function issue(User $user, ?CarbonInterface $expiresAt = null): string
    {
        $plainToken = bin2hex(random_bytes(32));
        $expiresAt ??= now()->addMinutes($this->expirationMinutes());
        DB::transaction(function () use ($user, $plainToken, $expiresAt) {
            $lockedUser = User::query()
                ->whereKey($user->getKey())
                ->lockForUpdate()
                ->first();
            if (! $lockedUser || $lockedUser->status !== 'active') {
                throw new InvalidArgumentException('User account is not active.');
            }
            MagicLinkToken::query()
                ->where('user_id', $lockedUser->id)
                ->delete();
            MagicLinkToken::create([
                'user_id' => $lockedUser->id,
                'token' => hash('sha256', $plainToken),
                'expires_at' => $expiresAt,
            ]);
        });

        return $plainToken;
    }

    public function consume(string $plainToken): ?User
    {
        if (preg_match('/\A[a-f0-9]{64}\z/', $plainToken) !== 1) {
            return null;
        }
        $tokenHash = hash('sha256', $plainToken);

        return DB::transaction(function () use ($tokenHash) {
            $tokenReference = MagicLinkToken::query()
                ->where('token', $tokenHash)
                ->first();
            if (! $tokenReference) {
                return null;
            }
            $user = User::query()
                ->whereKey($tokenReference->user_id)
                ->lockForUpdate()
                ->first();
            $magicLink = MagicLinkToken::query()
                ->whereKey($tokenReference->id)
                ->where('token', $tokenHash)
                ->lockForUpdate()
                ->first();
            if (! $magicLink || $magicLink->used_at || $magicLink->expires_at->isPast()) {
                return null;
            }
            $magicLink->update([
                'used_at' => now(),
            ]);
            if (! $user || $user->status !== 'active') {
                return null;
            }

            return $user;
        });
    }

    public function revoke(User $user): int
    {
        return DB::transaction(function () use ($user) {
            $lockedUser = User::query()
                ->whereKey($user->getKey())
                ->lockForUpdate()
                ->first();
            if (! $lockedUser) {
                return 0;
            }

            return MagicLinkToken::query()
                ->where('user_id', $lockedUser->id)
                ->delete();
        });
    }

    public function cleanupExpired(): int
    {
        return MagicLinkToken::query()
            ->where('expires_at', '<=', now())
            ->delete();
    }

    public function expirationMinutes(): int
    {
        return max(1, (int) config('auth.magic_link_expiration', 15));
    }
}
