<?php

namespace App\DataTransferObjects\Auth\Authentication;

use App\Enums\Auth\AuthenticationStatus;
use App\Models\User;
use InvalidArgumentException;

final readonly class AuthenticationResultData
{
    public function __construct(
        public AuthenticationStatus $status,
        public ?User $user = null,
    ) {
        if ($this->status === AuthenticationStatus::AUTHENTICATED && $this->user === null) {
            throw new InvalidArgumentException('Pengguna wajib tersedia untuk autentikasi yang berhasil.');
        }
        if ($this->status === AuthenticationStatus::TWO_FACTOR_REQUIRED && $this->user !== null) {
            throw new InvalidArgumentException('Pengguna tidak boleh dianggap terautentikasi sebelum verifikasi 2FA.');
        }
    }

    public static function authenticated(User $user): self
    {
        return new self(AuthenticationStatus::AUTHENTICATED, $user);
    }

    public static function requiresTwoFactor(): self
    {
        return new self(AuthenticationStatus::TWO_FACTOR_REQUIRED);
    }

    public function isAuthenticated(): bool
    {
        return $this->status === AuthenticationStatus::AUTHENTICATED;
    }

    public function requiresTwoFactorVerification(): bool
    {
        return $this->status === AuthenticationStatus::TWO_FACTOR_REQUIRED;
    }
}
