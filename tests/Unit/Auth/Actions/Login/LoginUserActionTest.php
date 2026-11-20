<?php

use App\Actions\Auth\Login\CompleteWebLoginAction;
use App\Actions\Auth\Login\LoginUserAction;
use App\Contracts\Auth\PendingTwoFactorChallengeServiceInterface;
use App\DataTransferObjects\Auth\Authentication\PendingAuthenticationData;
use App\DataTransferObjects\Auth\LoginData;
use App\Enums\Auth\AuthenticationMethod;
use App\Enums\Auth\AuthenticationStatus;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->pendingService = Mockery::mock(PendingTwoFactorChallengeServiceInterface::class);
    $this->action = new LoginUserAction(
        $this->pendingService,
        app(CompleteWebLoginAction::class),
    );
    Auth::guard('web')->logout();
    $session = app('session')->driver();
    $session->start();
    request()->setLaravelSession($session);
});
afterEach(function () {
    Auth::guard('web')->logout();
    Mockery::close();
});
function makeLoginActionTestUser(array $attributes = []): User
{
    return User::factory()->create(array_merge([
        'username' => 'santri_'.bin2hex(random_bytes(4)),
        'phone' => '+6281234567890',
        'status' => 'active',
        'password' => Hash::make('Password123!'),
        'two_factor_enabled' => false,
        'two_factor_confirmed_at' => null,
    ], $attributes));
}
function makeLoginActionPendingData(User $user, bool $remember = false): PendingAuthenticationData
{
    return new PendingAuthenticationData(
        userId: $user->getKey(),
        method: AuthenticationMethod::PASSWORD,
        remember: $remember,
        challengeId: bin2hex(random_bytes(32)),
        expiresAt: now()->addMinutes(5)->timestamp,
    );
}
it('authenticates a user using email', function () {
    $user = makeLoginActionTestUser([
        'email' => 'santri@example.com',
    ]);
    $this->pendingService->shouldNotReceive('begin');
    $result = $this->action->handle(
        new LoginData('SANTRI@EXAMPLE.COM', 'Password123!'),
        request(),
    );
    expect($result->status)->toBe(AuthenticationStatus::AUTHENTICATED)
        ->and($result->isAuthenticated())->toBeTrue()
        ->and($result->user)->toBeInstanceOf(User::class)
        ->and($result->user->is($user))->toBeTrue()
        ->and(Auth::guard('web')->check())->toBeTrue()
        ->and((int) Auth::guard('web')->id())->toBe((int) $user->getKey())
        ->and($user->fresh()->last_login_at)->not->toBeNull();
});
it('authenticates a user using username', function () {
    $user = makeLoginActionTestUser([
        'email' => 'username@example.com',
        'username' => 'santri_username',
    ]);
    $this->pendingService->shouldNotReceive('begin');
    $result = $this->action->handle(
        new LoginData('santri_username', 'Password123!'),
        request(),
    );
    expect($result->isAuthenticated())->toBeTrue()
        ->and($result->user->is($user))->toBeTrue()
        ->and(Auth::guard('web')->check())->toBeTrue();
});
it('authenticates a user using normalized phone number', function () {
    $user = makeLoginActionTestUser([
        'email' => 'phone@example.com',
        'phone' => '+6281234567890',
    ]);
    $this->pendingService->shouldNotReceive('begin');
    $result = $this->action->handle(
        new LoginData('081234567890', 'Password123!'),
        request(),
    );
    expect($result->isAuthenticated())->toBeTrue()
        ->and($result->user->is($user))->toBeTrue()
        ->and(Auth::guard('web')->check())->toBeTrue();
});
it('rejects an incorrect password', function () {
    makeLoginActionTestUser([
        'email' => 'wrong-password@example.com',
    ]);
    $this->pendingService->shouldNotReceive('begin');
    expect(fn () => $this->action->handle(
        new LoginData('wrong-password@example.com', 'WrongPassword123!'),
        request(),
    ))->toThrow(AuthenticationException::class);
    expect(Auth::guard('web')->check())->toBeFalse();
});
it('rejects an inactive account', function () {
    makeLoginActionTestUser([
        'email' => 'inactive@example.com',
        'status' => 'inactive',
    ]);
    $this->pendingService->shouldNotReceive('begin');
    expect(fn () => $this->action->handle(
        new LoginData('inactive@example.com', 'Password123!'),
        request(),
    ))->toThrow(AuthenticationException::class);
    expect(Auth::guard('web')->check())->toBeFalse();
});
it('creates a pending challenge instead of authenticating a user with confirmed two factor authentication', function () {
    $user = makeLoginActionTestUser([
        'email' => 'two-factor@example.com',
        'two_factor_enabled' => true,
        'two_factor_confirmed_at' => now(),
    ]);
    $this->pendingService->shouldReceive('begin')
        ->once()
        ->withArgs(fn (User $account, AuthenticationMethod $method, bool $remember) => $account->is($user)
            && $method === AuthenticationMethod::PASSWORD
            && $remember === false
        )
        ->andReturn(makeLoginActionPendingData($user));
    $result = $this->action->handle(
        new LoginData('two-factor@example.com', 'Password123!'),
        request(),
    );
    expect($result->status)->toBe(AuthenticationStatus::TWO_FACTOR_REQUIRED)
        ->and($result->requiresTwoFactorVerification())->toBeTrue()
        ->and($result->user)->toBeNull()
        ->and(Auth::guard('web')->check())->toBeFalse()
        ->and($user->fresh()->last_login_at)->toBeNull();
});
it('preserves remember preference in the pending challenge', function () {
    $user = makeLoginActionTestUser([
        'email' => 'remember-two-factor@example.com',
        'two_factor_enabled' => true,
        'two_factor_confirmed_at' => now(),
    ]);
    $this->pendingService->shouldReceive('begin')
        ->once()
        ->withArgs(fn (User $account, AuthenticationMethod $method, bool $remember) => $account->is($user)
            && $method === AuthenticationMethod::PASSWORD
            && $remember === true
        )
        ->andReturn(makeLoginActionPendingData($user, true));
    $result = $this->action->handle(
        new LoginData('remember-two-factor@example.com', 'Password123!', true),
        request(),
    );
    expect($result->requiresTwoFactorVerification())->toBeTrue()
        ->and(Auth::guard('web')->check())->toBeFalse();
});
it('rejects a two factor account that has not confirmed its setup', function () {
    makeLoginActionTestUser([
        'email' => 'unconfirmed-two-factor@example.com',
        'two_factor_enabled' => true,
        'two_factor_confirmed_at' => null,
    ]);
    $this->pendingService->shouldNotReceive('begin');
    expect(fn () => $this->action->handle(
        new LoginData('unconfirmed-two-factor@example.com', 'Password123!'),
        request(),
    ))->toThrow(AuthenticationException::class);
    expect(Auth::guard('web')->check())->toBeFalse();
});
it('updates login metadata only after successful authentication without two factor', function () {
    $user = makeLoginActionTestUser([
        'email' => 'metadata@example.com',
        'last_login_at' => null,
        'last_login_ip' => null,
    ]);
    $this->pendingService->shouldNotReceive('begin');
    $request = request();
    $result = $this->action->handle(
        new LoginData('metadata@example.com', 'Password123!'),
        $request,
    );
    $account = $user->fresh();
    expect($result->isAuthenticated())->toBeTrue()
        ->and($account->last_login_at)->not->toBeNull()
        ->and($account->last_login_ip)->toBe($request->ip());
});
it('does not authenticate a user when the identifier does not match', function () {
    makeLoginActionTestUser([
        'email' => 'existing@example.com',
    ]);
    $this->pendingService->shouldNotReceive('begin');
    expect(fn () => $this->action->handle(
        new LoginData('missing@example.com', 'Password123!'),
        request(),
    ))->toThrow(AuthenticationException::class);
    expect(Auth::guard('web')->check())->toBeFalse();
});
