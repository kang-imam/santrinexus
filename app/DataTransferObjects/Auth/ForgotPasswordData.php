<?php

namespace App\DataTransferObjects\Auth;

final readonly class ForgotPasswordData
{
    public function __construct(
        public string $email,
    ) {}
}
