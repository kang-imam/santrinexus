<?php

use App\Contracts\Auth\PendingTwoFactorChallengeServiceInterface;
use App\Http\Middleware\PendingTwoFactorChallengeMiddleware;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->pendingService = Mockery::mock(PendingTwoFactorChallengeServiceInterface::class);
    $this->middleware = new PendingTwoFactorChallengeMiddleware($this->pendingService);
    Auth::guard('web')->logout();
});
afterEach(function () {
    Mockery::close();
});
it('allows request when challenge is pending', function () {
    $this->pendingService->shouldReceive('isPending')->once()->andReturn(true);
    $request = Request::create('/two-factor-challenge', 'GET');
    $response = $this->middleware->handle($request, fn (Request $request) => response('OK'));
    expect($response)->toBeInstanceOf(Response::class)
        ->and($response->getContent())->toBe('OK');
});
it('redirects to login when challenge is not pending', function () {
    $this->pendingService->shouldReceive('isPending')->once()->andReturn(false);
    $this->pendingService->shouldReceive('clearExpired')->once()->andReturn(false);
    $request = Request::create('/two-factor-challenge', 'GET');
    $response = $this->middleware->handle($request, fn (Request $request) => response('OK'));
    expect($response->isRedirect())->toBeTrue()
        ->and($response->getTargetUrl())->toContain('/login');
});
it('redirects to login when challenge has expired', function () {
    $this->pendingService->shouldReceive('isPending')->once()->andReturn(false);
    $this->pendingService->shouldReceive('clearExpired')->once()->andReturn(true);
    $request = Request::create('/two-factor-challenge', 'GET');
    $response = $this->middleware->handle($request, fn (Request $request) => response('OK'));
    expect($response->isRedirect())->toBeTrue()
        ->and($response->getTargetUrl())->toContain('/login');
});
it('redirects authenticated users to dashboard and cancels pending challenge', function () {
    $user = User::factory()->create();
    Auth::guard('web')->login($user);
    $this->pendingService->shouldReceive('cancel')->once()->andReturn(true);
    $request = Request::create('/two-factor-challenge', 'GET');
    $request->setUserResolver(fn () => Auth::guard('web')->user());
    $response = $this->middleware->handle($request, fn (Request $request) => response('OK'));
    expect($response->isRedirect())->toBeTrue()
        ->and($response->getTargetUrl())->toContain('account');
});
