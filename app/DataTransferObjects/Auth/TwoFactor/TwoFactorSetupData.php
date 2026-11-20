<?php

namespace App\DataTransferObjects\Auth\TwoFactor;

use InvalidArgumentException;

final readonly class TwoFactorSetupData
{
    public function __construct(
        public string $secret,
        public string $provisioningUri,
        public string $qrCode,
    ) {
        if ($this->secret === '' || $this->provisioningUri === '' || $this->qrCode === '') {
            throw new InvalidArgumentException('Data konfigurasi autentikasi dua faktor tidak lengkap.');
        }
    }
}
