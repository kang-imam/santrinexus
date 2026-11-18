<?php

namespace App\Http\Controllers\Web\Auth;

use App\Actions\Auth\Login\LogoutUserAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function __construct(private LogoutUserAction $logoutUserAction) {}

    public function destroy(Request $request): RedirectResponse
    {
        $this->logoutUserAction->handle($request);

        return redirect()->route('login')->with('success', 'Berhasil logout.');
    }
}
