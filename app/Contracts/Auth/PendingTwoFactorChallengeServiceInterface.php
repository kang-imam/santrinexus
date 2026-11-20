<?php

namespace App\Contracts\Auth;

use App\DataTransferObjects\Auth\Authentication\PendingAuthenticationData;
use App\Enums\Auth\AuthenticationMethod;
use App\Models\User;

interface PendingTwoFactorChallengeServiceInterface
{
    public function begin(User $user, AuthenticationMethod $method, bool $remember = false): PendingAuthenticationData;

    public function getPending(): ?PendingAuthenticationData;

    public function getPendingUser(): ?User;

    public function isPending(): bool;

    public function isExpired(): bool;

    public function complete(string $challengeId): bool;

    public function cancel(): bool;

    public function clearExpired(): bool;
}
