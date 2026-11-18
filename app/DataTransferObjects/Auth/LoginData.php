<?php

namespace App\DataTransferObjects\Auth;

use App\Services\Auth\PhoneNumberService;
use InvalidArgumentException;

readonly class LoginData
{
    public string $identifierType;

    public string $normalizedIdentifier;

    public function __construct(
        public string $identifier,
        public string $password,
        public bool $remember = false,
    ) {
        $identifier = trim($identifier);
        if ($identifier === '') {
            throw new InvalidArgumentException('Identifier tidak boleh kosong.');
        }
        if ($password === '') {
            throw new InvalidArgumentException('Password tidak boleh kosong.');
        }
        $this->identifierType = $this->resolveIdentifierType($identifier);
        $this->normalizedIdentifier = $this->normalizeIdentifier($identifier, $this->identifierType);
    }

    private function resolveIdentifierType(string $identifier): string
    {
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            return 'email';
        }
        if (preg_match('/^\+?[0-9\s().-]+$/', $identifier)) {
            return 'phone';
        }

        return 'username';
    }

    private function normalizeIdentifier(string $identifier, string $type): string
    {
        return match ($type) {
            'email' => mb_strtolower($identifier),
            'phone' => app(PhoneNumberService::class)->normalize($identifier),
            default => $identifier,
        };
    }
}
