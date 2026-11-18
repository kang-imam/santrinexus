<?php

namespace App\Http\Requests\Auth;

use App\Services\Auth\PhoneNumberService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $data = [];
        if ($this->has('name')) {
            $data['name'] = trim((string) $this->input('name'));
        }
        if ($this->has('username')) {
            $username = trim((string) $this->input('username'));
            $data['username'] = $username === '' ? null : $username;
        }
        if ($this->has('email')) {
            $data['email'] = mb_strtolower(trim((string) $this->input('email')));
        }
        if ($this->has('phone')) {
            $phone = $this->input('phone');
            if ($phone === null || trim((string) $phone) === '') {
                $data['phone'] = null;
            } else {
                try {
                    $data['phone'] = app(PhoneNumberService::class)->normalize((string) $phone);
                } catch (\InvalidArgumentException) {
                    $data['phone'] = trim((string) $phone);
                }
            }
        }
        $this->merge($data);
    }

    public function rules(): array
    {
        return [
            'name' => [
                'bail',
                'required',
                'string',
                'min:2',
                'max:100',
            ],
            'username' => [
                'bail',
                'nullable',
                'string',
                'min:3',
                'max:30',
                'regex:/^[a-zA-Z0-9_.-]+$/',
                Rule::unique('users', 'username'),
            ],
            'email' => [
                'bail',
                'required',
                'string',
                'email:rfc',
                'max:254',
                Rule::unique('users', 'email'),
            ],
            'phone' => [
                'bail',
                'nullable',
                'string',
                'max:40',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (! app(PhoneNumberService::class)->isValid($value)) {
                        $fail('Nomor telepon tidak valid.');
                    }
                },
                Rule::unique('users', 'phone'),
            ],
            'password' => [
                'bail',
                'required',
                'string',
                Password::min(8)->letters()->numbers(),
                'confirmed',
            ],
            'password_confirmation' => [
                'bail',
                'required',
                'string',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'name.string' => 'Nama harus berupa teks.',
            'name.min' => 'Nama minimal 2 karakter.',
            'name.max' => 'Nama maksimal 100 karakter.',
            'username.string' => 'Username harus berupa teks.',
            'username.min' => 'Username minimal 3 karakter.',
            'username.max' => 'Username maksimal 30 karakter.',
            'username.regex' => 'Username hanya boleh menggunakan huruf, angka, titik, garis bawah, dan tanda hubung.',
            'username.unique' => 'Username sudah digunakan.',
            'email.required' => 'Email wajib diisi.',
            'email.string' => 'Email harus berupa teks.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 254 karakter.',
            'email.unique' => 'Email sudah terdaftar.',
            'phone.string' => 'Nomor telepon harus berupa teks.',
            'phone.max' => 'Nomor telepon terlalu panjang.',
            'phone.unique' => 'Nomor telepon sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.string' => 'Password harus berupa teks.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password_confirmation.required' => 'Konfirmasi password wajib diisi.',
            'password_confirmation.string' => 'Konfirmasi password harus berupa teks.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nama lengkap',
            'username' => 'username',
            'email' => 'alamat email',
            'phone' => 'nomor telepon',
            'password' => 'password',
            'password_confirmation' => 'konfirmasi password',
        ];
    }
}
