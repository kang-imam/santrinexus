<?php

namespace App\Services\Auth;

use App\Contracts\Auth\PendingTwoFactorChallengeServiceInterface;
use App\DataTransferObjects\Auth\Authentication\PendingAuthenticationData;
use App\Enums\Auth\AuthenticationMethod;
use App\Models\User;
use Illuminate\Contracts\Session\Session;
use InvalidArgumentException;

final class PendingTwoFactorChallengeService implements PendingTwoFactorChallengeServiceInterface
{
    private const SESSION_KEY = 'auth.two_factor.pending';

    private const CHALLENGE_TTL = 300;

    public function __construct(
        private readonly Session $session,
    ) {}

    public function begin(User $user, AuthenticationMethod $method, bool $remember = false): PendingAuthenticationData
    {
        $this->ensureEligibleUser($user);
        $this->session->regenerate();
        $pending = new PendingAuthenticationData(
            userId: $user->getKey(),
            method: $method,
            remember: $remember,
            challengeId: bin2hex(random_bytes(32)),
            expiresAt: now()->addSeconds(self::CHALLENGE_TTL)->timestamp,
        );
        $this->session->put(self::SESSION_KEY, $pending->toArray());

        return $pending;
    }

    public function getPending(): ?PendingAuthenticationData
    {
        $data = $this->session->get(self::SESSION_KEY);
        if (! is_array($data)) {
            return null;
        }
        $pending = PendingAuthenticationData::fromArray($data);
        if ($pending === null || $pending->isExpired()) {
            $this->cancel();

            return null;
        }

        return $pending;
    }

    public function getPendingUser(): ?User
    {
        $pending = $this->getPending();
        if ($pending === null) {
            return null;
        }
        $user = User::query()->find($pending->userId);
        if (! $user || ! $this->isEligibleUser($user)) {
            $this->cancel();

            return null;
        }

        return $user;
    }

    public function isPending(): bool
    {
        return $this->getPendingUser() !== null;
    }

    public function isExpired(): bool
    {
        $data = $this->session->get(self::SESSION_KEY);
        if (! is_array($data)) {
            return false;
        }
        $pending = PendingAuthenticationData::fromArray($data);

        return $pending !== null && $pending->isExpired();
    }

    public function complete(string $challengeId): bool
    {
        if ($challengeId === '') {
            return false;
        }
        $pending = $this->getPending();
        if ($pending === null || ! hash_equals($pending->challengeId, $challengeId)) {
            return false;
        }
        if ($this->getPendingUser() === null) {
            return false;
        }
        $this->session->forget(self::SESSION_KEY);

        return true;
    }

    public function cancel(): bool
    {
        if (! $this->session->has(self::SESSION_KEY)) {
            return false;
        }
        $this->session->forget(self::SESSION_KEY);

        return true;
    }

    public function clearExpired(): bool
    {
        if (! $this->isExpired()) {
            return false;
        }
        $this->session->forget(self::SESSION_KEY);

        return true;
    }

    private function ensureEligibleUser(User $user): void
    {
        if (! $this->isEligibleUser($user)) {
            throw new InvalidArgumentException('Pengguna tidak memenuhi persyaratan autentikasi dua faktor.');
        }
    }

    private function isEligibleUser(User $user): bool
    {
        return $user->status === 'active'
            && $user->two_factor_enabled === true
            && $user->two_factor_confirmed_at !== null;
    }
}
