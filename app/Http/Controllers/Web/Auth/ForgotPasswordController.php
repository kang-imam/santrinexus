<?php

namespace App\Http\Controllers\Web\Auth;

use App\Actions\Auth\ForgotPassword\SendPasswordResetLinkAction;
use App\DataTransferObjects\Auth\ForgotPasswordData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use Illuminate\Http\RedirectResponse;

final class ForgotPasswordController extends Controller
{
    public function __construct(
        private readonly SendPasswordResetLinkAction $sendPasswordResetLinkAction,
    ) {}

    public function __invoke(ForgotPasswordRequest $request): RedirectResponse
    {
        $data = new ForgotPasswordData(
            email: $request->validated('email'),
        );
        $this->sendPasswordResetLinkAction->execute($data);

        return back()->with(
            'status',
            'Jika alamat email terdaftar, kami akan mengirimkan tautan untuk mengatur ulang password.'
        );
    }
}
