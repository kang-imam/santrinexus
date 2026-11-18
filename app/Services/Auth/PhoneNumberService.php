<?php

namespace App\Services\Auth;

use InvalidArgumentException;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

class PhoneNumberService
{
    public const DEFAULT_REGION = 'ID';

    private PhoneNumberUtil $phoneUtil;

    public function __construct()
    {
        $this->phoneUtil = PhoneNumberUtil::getInstance();
    }

    public function parse(string $phone, ?string $region = null): PhoneNumber
    {
        $phone = trim($phone);
        if ($phone === '') {
            throw new InvalidArgumentException('Nomor telepon tidak boleh kosong.');
        }
        if (! preg_match('/^\+?[0-9\s().-]+$/', $phone)) {
            throw new InvalidArgumentException('Nomor telepon mengandung karakter yang tidak valid.');
        }
        $phone = preg_replace('/[\s().-]+/', '', $phone);
        if (str_starts_with($phone, '00')) {
            $phone = '+'.substr($phone, 2);
        }
        try {
            $number = $this->phoneUtil->parse($phone, strtoupper($region ?? self::DEFAULT_REGION));
        } catch (NumberParseException $exception) {
            throw new InvalidArgumentException('Nomor telepon tidak dapat dikenali.', 0, $exception);
        }
        if (! $this->phoneUtil->isValidNumber($number)) {
            throw new InvalidArgumentException('Nomor telepon tidak valid.');
        }

        return $number;
    }

    public function normalize(?string $phone, ?string $region = null): ?string
    {
        if ($phone === null || trim($phone) === '') {
            return null;
        }

        return $this->phoneUtil->format(
            $this->parse($phone, $region),
            PhoneNumberFormat::E164
        );
    }

    public function isValid(?string $phone, ?string $region = null): bool
    {
        if ($phone === null || trim($phone) === '') {
            return false;
        }
        try {
            $this->parse($phone, $region);

            return true;
        } catch (InvalidArgumentException) {
            return false;
        }
    }

    public function formatInternational(string $phone, ?string $region = null): string
    {
        return $this->phoneUtil->format(
            $this->parse($phone, $region),
            PhoneNumberFormat::INTERNATIONAL
        );
    }

    public function formatNational(string $phone, ?string $region = null): string
    {
        return $this->phoneUtil->format(
            $this->parse($phone, $region),
            PhoneNumberFormat::NATIONAL
        );
    }

    public function getCountryCallingCode(string $phone, ?string $region = null): int
    {
        return $this->parse($phone, $region)->getCountryCode();
    }

    public function getRegionCode(string $phone, ?string $region = null): ?string
    {
        return $this->phoneUtil->getRegionCodeForNumber(
            $this->parse($phone, $region)
        );
    }

    public function compare(?string $first, ?string $second, ?string $region = null): bool
    {
        if ($first === null || $second === null) {
            return false;
        }
        $normalizedFirst = $this->normalize($first, $region);
        $normalizedSecond = $this->normalize($second, $region);

        return $normalizedFirst === $normalizedSecond;
    }
}
