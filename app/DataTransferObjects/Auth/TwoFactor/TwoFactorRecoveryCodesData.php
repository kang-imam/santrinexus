<?php

namespace App\DataTransferObjects\Auth\TwoFactor;

use InvalidArgumentException;

final readonly class TwoFactorRecoveryCodesData
{
    public function __construct(
        public array $codes,
    ) {
        if (count($this->codes) !== 10) {
            throw new InvalidArgumentException('Jumlah recovery code harus sepuluh.');
        }
        foreach ($this->codes as $code) {
            if (! is_string($code) || $code === '') {
                throw new InvalidArgumentException('Recovery code tidak valid.');
            }
        }
    }
}
