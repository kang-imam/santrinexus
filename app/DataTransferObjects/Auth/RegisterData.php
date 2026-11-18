<?php

namespace App\DataTransferObjects\Auth;

use App\Services\Auth\PhoneNumberService;
use InvalidArgumentException;

readonly class RegisterData
{
    public string $name;

    public ?string $username;

    public string $email;

    public ?string $phone;

    public string $password;

    public string $passwordConfirmation;

    public function __construct(string $name, ?string $username, string $email, ?string $phone, string $password, string $passwordConfirmation)
    {
        $this->name = trim($name);
        $this->username = $username !== null && trim($username) !== '' ? trim($username) : null;
        $this->email = mb_strtolower(trim($email));
        $this->phone = $phone !== null && trim($phone) !== '' ? app(PhoneNumberService::class)->normalize($phone) : null;
        $this->password = $password;
        $this->passwordConfirmation = $passwordConfirmation;
        if ($this->name === '') {
            throw new InvalidArgumentException('Nama tidak boleh kosong.');
        }
        if (! filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Format email tidak valid.');
        }
        if ($this->password === '') {
            throw new InvalidArgumentException('Password tidak boleh kosong.');
        }
        if ($this->password !== $this->passwordConfirmation) {
            throw new InvalidArgumentException('Konfirmasi password tidak cocok.');
        }
    }
}
