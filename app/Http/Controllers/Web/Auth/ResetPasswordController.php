<?php

namespace App\Http\Controllers\Web\Auth;

use App\Actions\Auth\ResetPassword\ResetPasswordAction;
use App\DataTransferObjects\Auth\ResetPasswordData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetPasswordRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;

final class ResetPasswordController extends Controller
{
    public function __construct(
        private readonly ResetPasswordAction $resetPasswordAction,
    ) {}

    public function __invoke(ResetPasswordRequest $request): RedirectResponse
    {
        $data = new ResetPasswordData(
            email: $request->validated('email'),
            token: $request->validated('token'),
            password: $request->validated('password'),
        );
        $status = $this->resetPasswordAction->execute($data);
        if ($status === Password::PASSWORD_RESET) {
            return redirect()
                ->route('login')
                ->with('status', 'Password berhasil diperbarui. Silakan login menggunakan password baru.');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => match ($status) {
                    Password::INVALID_TOKEN => 'Token reset password tidak valid atau sudah kedaluwarsa.',
                    Password::INVALID_USER => 'Permintaan reset password tidak dapat diproses. Periksa kembali data Anda.',
                    default => 'Reset password gagal. Silakan coba kembali.',
                },
            ]);
    }
}
