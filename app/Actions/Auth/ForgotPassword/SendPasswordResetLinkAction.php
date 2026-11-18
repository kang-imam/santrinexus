<?php

namespace App\Actions\Auth\ForgotPassword;

use App\DataTransferObjects\Auth\ForgotPasswordData;
use Illuminate\Support\Facades\Password;

final class SendPasswordResetLinkAction
{
    public function execute(ForgotPasswordData $data): string
    {
        return Password::sendResetLink([
            'email' => $data->email,
        ]);
    }
}
