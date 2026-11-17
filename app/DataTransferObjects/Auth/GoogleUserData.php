<?php

namespace App\DataTransferObjects\Auth;

use Laravel\Socialite\Contracts\User as SocialiteUser;

readonly class GoogleUserData
{
    public function __construct(
        public string $googleId,
        public ?string $name,
        public ?string $email,
        public ?string $avatar,
        public bool $emailVerified
    ) {}

    public static function fromSocialite(SocialiteUser $user): self
    {
        $googleId = trim((string) $user->getId());
        if ($googleId === '') {
            throw new \InvalidArgumentException('Google ID tidak valid.');
        }
        $email = trim((string) $user->getEmail());
        $email = $email !== '' ? mb_strtolower($email) : null;
        $emailVerified = false;
        if (property_exists($user, 'user')) {
            $emailVerified = ($user->user['verified_email'] ?? false) === true;
        }

        return new self(
            googleId: $googleId,
            name: $user->getName(),
            email: $email,
            avatar: $user->getAvatar(),
            emailVerified: $emailVerified
        );
    }
}
