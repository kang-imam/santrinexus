<?php

use App\Contracts\Auth\PendingTwoFactorChallengeServiceInterface;
use App\Contracts\Auth\TwoFactorAuthenticationServiceInterface;
use App\Enums\Auth\AuthenticationMethod;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->twoFactorService = Mockery::mock(TwoFactorAuthenticationServiceInterface::class);
    $this->app->instance(TwoFactorAuthenticationServiceInterface::class, $this->twoFactorService);
    RateLimiter::for('two-factor-challenge', fn (Request $request) => Limit::perMinute(5)->by($request->ip()));
    Auth::guard('web')->logout();
    $this->csrfToken = Str::random(40);
    $this->withSession(['_token' => $this->csrfToken]);
});
afterEach(function () {
    Auth::guard('web')->logout();
    Mockery::close();
});
function createTwoFactorChallengeTestUser(array $attributes = []): User
{
    return User::factory()->create(array_merge([
        'status' => 'active',
        'two_factor_enabled' => true,
        'two_factor_secret' => 'test-secret',
        'two_factor_confirmed_at' => now(),
        'two_factor_recovery_codes' => [],
        'email_verified_at' => now(),
    ], $attributes));
}
function beginTwoFactorChallengeForTest(User $user, bool $remember = false): void
{
    app(PendingTwoFactorChallengeServiceInterface::class)->begin(
        $user,
        AuthenticationMethod::PASSWORD,
        $remember
    );
    test()->csrfToken = session()->token();
}
function challengePost(array $data = []): TestResponse
{
    return test()->post(route('two-factor.challenge.store'), array_merge([
        '_token' => test()->csrfToken,
    ], $data));
}
it('displays the challenge page when a valid pending challenge exists', function () {
    $user = createTwoFactorChallengeTestUser();
    beginTwoFactorChallengeForTest($user);
    $this->get(route('two-factor.challenge.create'))
        ->assertOk()
        ->assertViewIs('auth.two-factor-challenge');
});
it('redirects to login when no pending challenge exists', function () {
    $this->get(route('two-factor.challenge.create'))
        ->assertRedirect(route('login'))
        ->assertSessionHasErrors('two_factor');
});
it('completes authentication with a valid otp', function () {
    $user = createTwoFactorChallengeTestUser();
    beginTwoFactorChallengeForTest($user);
    $this->twoFactorService->shouldReceive('verifyCode')->once()->withArgs(fn ($account, $code) => $account->is($user) && $code === '123456')->andReturn(true);
    challengePost(['code' => '123456'])
        ->assertRedirect(route('account.dashboard'));
    expect(Auth::guard('web')->check())->toBeTrue()
        ->and((int) Auth::guard('web')->id())->toBe((int) $user->getKey())
        ->and($user->fresh()->last_login_at)->not->toBeNull();
});
it('completes authentication with a valid recovery code', function () {
    $user = createTwoFactorChallengeTestUser();
    beginTwoFactorChallengeForTest($user);
    $this->twoFactorService->shouldReceive('verifyRecoveryCode')->once()->withArgs(fn ($account, $code) => $account->is($user) && $code === 'AB12345678')->andReturn(true);
    challengePost(['recovery_code' => 'AB12345678'])
        ->assertRedirect(route('account.dashboard'));
    expect(Auth::guard('web')->check())->toBeTrue()
        ->and((int) Auth::guard('web')->id())->toBe((int) $user->getKey());
});
it('preserves remember preference until authentication is completed', function () {
    $user = createTwoFactorChallengeTestUser();
    beginTwoFactorChallengeForTest($user, true);
    $this->twoFactorService->shouldReceive('verifyCode')->once()->andReturn(true);
    $response = challengePost(['code' => '123456']);
    $response->assertRedirect(route('account.dashboard'));
    expect(Auth::guard('web')->check())->toBeTrue();
    expect(collect($response->headers->getCookies())->contains(fn ($cookie) => str_starts_with($cookie->getName(), 'remember_web_')))->toBeTrue();
});
it('rejects an invalid otp without completing authentication', function () {
    $user = createTwoFactorChallengeTestUser();
    beginTwoFactorChallengeForTest($user);
    $this->twoFactorService->shouldReceive('verifyCode')->once()->andReturn(false);
    challengePost(['code' => '123456'])
        ->assertRedirect()
        ->assertSessionHasErrors('code');
    expect(Auth::guard('web')->check())->toBeFalse()
        ->and(app(PendingTwoFactorChallengeServiceInterface::class)->isPending())->toBeTrue();
});
it('rejects an invalid recovery code without completing authentication', function () {
    $user = createTwoFactorChallengeTestUser();
    beginTwoFactorChallengeForTest($user);
    $this->twoFactorService->shouldReceive('verifyRecoveryCode')->once()->andReturn(false);
    challengePost(['recovery_code' => 'AB12345678'])
        ->assertRedirect()
        ->assertSessionHasErrors('recoveryCode');
    expect(Auth::guard('web')->check())->toBeFalse()
        ->and(app(PendingTwoFactorChallengeServiceInterface::class)->isPending())->toBeTrue();
});
it('rejects an empty verification request', function () {
    $user = createTwoFactorChallengeTestUser();
    beginTwoFactorChallengeForTest($user);
    $this->twoFactorService->shouldNotReceive('verifyCode');
    $this->twoFactorService->shouldNotReceive('verifyRecoveryCode');
    challengePost()
        ->assertSessionHasErrors();
    expect(Auth::guard('web')->check())->toBeFalse();
});
it('rejects an otp with an invalid length', function () {
    $user = createTwoFactorChallengeTestUser();
    beginTwoFactorChallengeForTest($user);
    $this->twoFactorService->shouldNotReceive('verifyCode');
    challengePost(['code' => '12345'])
        ->assertSessionHasErrors('code');
    expect(Auth::guard('web')->check())->toBeFalse();
});
it('rejects requests containing both otp and recovery code', function () {
    $user = createTwoFactorChallengeTestUser();
    beginTwoFactorChallengeForTest($user);
    $this->twoFactorService->shouldNotReceive('verifyCode');
    $this->twoFactorService->shouldNotReceive('verifyRecoveryCode');
    challengePost([
        'code' => '123456',
        'recovery_code' => 'AB12345678',
    ])->assertSessionHasErrors();
    expect(Auth::guard('web')->check())->toBeFalse();
});
it('redirects to email verification after successful two factor authentication when email is unverified', function () {
    $user = createTwoFactorChallengeTestUser(['email_verified_at' => null]);
    beginTwoFactorChallengeForTest($user);
    $this->twoFactorService->shouldReceive('verifyCode')->once()->andReturn(true);
    challengePost(['code' => '123456'])
        ->assertRedirect(route('verification.notice'));
    expect(Auth::guard('web')->check())->toBeTrue()
        ->and((int) Auth::guard('web')->id())->toBe((int) $user->getKey());
});
it('cancels pending authentication and redirects to login', function () {
    $user = createTwoFactorChallengeTestUser();
    beginTwoFactorChallengeForTest($user);
    $this->delete(route('two-factor.challenge.destroy'), [
        '_token' => $this->csrfToken,
    ])->assertRedirect(route('login'))
        ->assertSessionHas('status');
    expect(app(PendingTwoFactorChallengeServiceInterface::class)->isPending())->toBeFalse()
        ->and(Auth::guard('web')->check())->toBeFalse();
});
it('does not authenticate when the pending challenge has expired', function () {
    $user = createTwoFactorChallengeTestUser();
    beginTwoFactorChallengeForTest($user);
    $pending = app(PendingTwoFactorChallengeServiceInterface::class)->getPending();
    $this->withSession([
        'auth.two_factor.pending' => array_merge($pending->toArray(), [
            'expires_at' => now()->subMinute()->timestamp,
        ]),
    ]);
    challengePost(['code' => '123456'])
        ->assertRedirect(route('login'));
    expect(Auth::guard('web')->check())->toBeFalse();
});
