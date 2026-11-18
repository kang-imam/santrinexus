<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

final class ForgotPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return [
            'email' => 'alamat email',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => ':attribute wajib diisi.',
            'email.string' => ':attribute harus berupa teks.',
            'email.email' => 'Format :attribute tidak valid.',
            'email.max' => ':attribute maksimal 255 karakter.',
        ];
    }
}
