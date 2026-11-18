<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

final class ResetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'token' => [
                'required',
                'string',
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
            ],
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(8)->uncompromised(),
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'token' => 'token reset password',
            'email' => 'alamat email',
            'password' => 'password baru',
        ];
    }

    public function messages(): array
    {
        return [
            'token.required' => ':attribute wajib diisi.',
            'token.string' => ':attribute tidak valid.',
            'email.required' => ':attribute wajib diisi.',
            'email.string' => ':attribute harus berupa teks.',
            'email.email' => 'Format :attribute tidak valid.',
            'email.max' => ':attribute maksimal 255 karakter.',
            'password.required' => ':attribute wajib diisi.',
            'password.string' => ':attribute harus berupa teks.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ];
    }
}
