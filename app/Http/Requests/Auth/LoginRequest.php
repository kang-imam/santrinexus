<?php

namespace App\Http\Requests\Auth;

use App\Services\Auth\PhoneNumberService;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $identifier = trim((string) $this->input('identifier', ''));
        if (str_contains($identifier, '@')) {
            $identifier = mb_strtolower($identifier);
        } elseif ($this->isPhoneIdentifier($identifier)) {
            try {
                $identifier = app(PhoneNumberService::class)->normalize($identifier);
            } catch (\InvalidArgumentException) {
            }
        }
        $this->merge([
            'identifier' => $identifier,
        ]);
    }

    public function rules(): array
    {
        return [
            'identifier' => [
                'bail',
                'required',
                'string',
                'max:255',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (str_contains($value, '@')) {
                        if (! filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $fail('Format email tidak valid.');
                        }

                        return;
                    }
                    if ($this->isPhoneIdentifier($value)) {
                        if (! app(PhoneNumberService::class)->isValid($value)) {
                            $fail('Nomor telepon tidak valid.');
                        }

                        return;
                    }
                    if (! preg_match('/^[\pL\pN_.-]+$/u', $value)) {
                        $fail('Format username tidak valid.');
                    }
                },
            ],
            'password' => [
                'bail',
                'required',
                'string',
            ],
            'remember' => [
                'sometimes',
                'boolean',
            ],
        ];
    }

    protected function isPhoneIdentifier(string $value): bool
    {
        return preg_match('/^\+?[0-9\s().-]+$/', $value) === 1;
    }

    public function messages(): array
    {
        return [
            'identifier.required' => 'Email, username, atau nomor telepon wajib diisi.',
            'identifier.string' => 'Identifier harus berupa teks.',
            'identifier.max' => 'Identifier maksimal 255 karakter.',
            'password.required' => 'Password wajib diisi.',
            'password.string' => 'Password harus berupa teks.',
            'remember.boolean' => 'Pilihan remember me tidak valid.',
        ];
    }
}
