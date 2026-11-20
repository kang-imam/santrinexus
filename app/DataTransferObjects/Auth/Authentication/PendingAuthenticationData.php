<?php

namespace App\DataTransferObjects\Auth\Authentication;

use App\Enums\Auth\AuthenticationMethod;
use InvalidArgumentException;

final readonly class PendingAuthenticationData
{
    public function __construct(
        public int|string $userId,
        public AuthenticationMethod $method,
        public bool $remember,
        public string $challengeId,
        public int $expiresAt,
    ) {
        if ($this->challengeId === '' || trim($this->challengeId) !== $this->challengeId) {
            throw new InvalidArgumentException('ID challenge tidak valid.');
        }
        if ($this->expiresAt <= 0) {
            throw new InvalidArgumentException('Waktu kedaluwarsa challenge tidak valid.');
        }
    }

    public function isExpired(?int $timestamp = null): bool
    {
        return ($timestamp ?? time()) >= $this->expiresAt;
    }

    public function toArray(): array
    {
        return [
            'user_id' => $this->userId,
            'method' => $this->method->value,
            'remember' => $this->remember,
            'challenge_id' => $this->challengeId,
            'expires_at' => $this->expiresAt,
        ];
    }

    public static function fromArray(array $data): ?self
    {
        if (
            ! isset($data['user_id'], $data['method'], $data['challenge_id'], $data['expires_at']) ||
            ! is_int($data['user_id']) && ! is_string($data['user_id']) ||
            ! is_string($data['method']) ||
            ! is_bool($data['remember'] ?? null) ||
            ! is_string($data['challenge_id']) ||
            ! is_int($data['expires_at'])
        ) {
            return null;
        }
        $method = AuthenticationMethod::tryFrom($data['method']);
        if ($method === null) {
            return null;
        }
        try {
            return new self(
                userId: $data['user_id'],
                method: $method,
                remember: $data['remember'],
                challengeId: $data['challenge_id'],
                expiresAt: $data['expires_at'],
            );
        } catch (InvalidArgumentException) {
            return null;
        }
    }
}
