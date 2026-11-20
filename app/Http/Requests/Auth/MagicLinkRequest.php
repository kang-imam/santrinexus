<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class MagicLinkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return match ($this->route()?->getName()) {
            'magic-link.send' => [
                'email' => ['required', 'string', 'email:rfc', 'max:255'],
            ],
            'magic-link.verify' => [
                'token' => ['required', 'string', 'size:64', 'regex:/\A[a-f0-9]{64}\z/'],
            ],
            default => []
        };
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 255 karakter.',
            'token.required' => 'Token autentikasi wajib diisi.',
            'token.size' => 'Token autentikasi tidak valid.',
            'token.regex' => 'Format token autentikasi tidak valid.',
        ];
    }

    public function attributes(): array
    {
        return [
            'email' => 'email',
            'token' => 'token autentikasi',
        ];
    }
}
