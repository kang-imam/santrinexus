<?php

namespace App\DataTransferObjects\Auth\TwoFactor;

use InvalidArgumentException;

final readonly class TwoFactorVerificationData
{
    public function __construct(
        public ?string $code = null,
        public ?string $recoveryCode = null,
    ) {
        $hasCode = is_string($this->code) && trim($this->code) !== '';
        $hasRecoveryCode = is_string($this->recoveryCode) && trim($this->recoveryCode) !== '';
        if ($hasCode === $hasRecoveryCode) {
            throw new InvalidArgumentException('Masukkan tepat satu kode verifikasi.');
        }
    }
}
