<?php

namespace App\DataTransferObjects\Auth;

final readonly class MagicLinkData
{
    public function __construct(
        public ?string $email = null,
        public ?string $token = null
    ) {}
}
