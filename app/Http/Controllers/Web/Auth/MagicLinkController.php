<?php

namespace App\Http\Controllers\Web\Auth;

use App\Actions\Auth\MagicLink\SendMagicLinkAction;
use App\Actions\Auth\MagicLink\VerifyMagicLinkAction;
use App\DataTransferObjects\Auth\MagicLinkData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\MagicLinkRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MagicLinkController extends Controller
{
    public function create(): View
    {
        return view('auth.magic-link');
    }

    public function send(MagicLinkRequest $request, SendMagicLinkAction $action): RedirectResponse
    {
        $action->execute(
            new MagicLinkData(
                email: $request->validated('email')
            )
        );

        return back()->with(
            'status',
            'Jika email terdaftar, tautan login akan dikirim ke email tersebut.'
        );
    }

    public function confirm(Request $request): View
    {
        $token = $request->query('token');
        abort_unless(
            is_string($token) && preg_match('/\A[a-f0-9]{64}\z/', $token) === 1,
            404
        );

        return view('auth.magic-link-confirm', [
            'token' => $token,
        ]);
    }

    public function verify(MagicLinkRequest $request, VerifyMagicLinkAction $action): RedirectResponse
    {
        $action->execute(
            new MagicLinkData(
                token: $request->validated('token')
            ),
            $request
        );

        return redirect()->intended('/dashboard');
    }
}
