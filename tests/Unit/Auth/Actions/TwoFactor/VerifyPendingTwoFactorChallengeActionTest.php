<?php

use App\Actions\Auth\TwoFactor\VerifyPendingTwoFactorChallengeAction;
use App\Contracts\Auth\PendingTwoFactorChallengeServiceInterface;
use App\Contracts\Auth\TwoFactorAuthenticationServiceInterface;
use App\DataTransferObjects\Auth\Authentication\PendingAuthenticationData;
use App\DataTransferObjects\Auth\TwoFactor\TwoFactorVerificationData;
use App\Enums\Auth\AuthenticationMethod;
use App\Models\User;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

beforeEach(function () {
    Auth::guard('web')->logout();
    $this->user = User::factory()->create([
        'status' => 'active',
        'password' => Hash::make('Password123!'),
        'two_factor_enabled' => true,
        'two_factor_secret' => encrypt('TESTSECRET'),
        'two_factor_confirmed_at' => now(),
        'last_login_at' => null,
        'last_login_ip' => null,
    ]);
    $this->pending = new PendingAuthenticationData(
        userId: $this->user->getKey(),
        method: AuthenticationMethod::PASSWORD,
        remember: false,
        challengeId: bin2hex(random_bytes(32)),
        expiresAt: now()->addMinutes(5)->timestamp,
    );
    $this->pendingService = Mockery::mock(PendingTwoFactorChallengeServiceInterface::class);
    $this->twoFactorService = Mockery::mock(TwoFactorAuthenticationServiceInterface::class);
    app()->instance(PendingTwoFactorChallengeServiceInterface::class, $this->pendingService);
    app()->instance(TwoFactorAuthenticationServiceInterface::class, $this->twoFactorService);
    $this->request = Request::create('/two-factor-challenge', 'POST');
    $this->request->server->set('REMOTE_ADDR', '127.0.0.1');
    $this->request->setLaravelSession(app(Session::class));
    $this->action = app(VerifyPendingTwoFactorChallengeAction::class);
});
afterEach(function () {
    Auth::guard('web')->logout();
});
function expectPendingChallenge(VerifyPendingTwoFactorChallengeActionTestProxy $proxy): void {}
it('authenticates the user after valid otp verification', function () {
    $this->pendingService->shouldReceive('getPending')->once()->andReturn($this->pending);
    $this->pendingService->shouldReceive('getPendingUser')->once()->andReturn($this->user);
    $this->pendingService->shouldReceive('complete')->once()->with($this->pending->challengeId)->andReturn(true);
    $this->twoFactorService->shouldReceive('verifyCode')->once()->with($this->user, '123456')->andReturn(true);
    $result = $this->action->execute(new TwoFactorVerificationData(code: '123456'), $this->request);
    expect($result->is($this->user))->toBeTrue()
        ->and(Auth::guard('web')->check())->toBeTrue()
        ->and(Auth::guard('web')->id())->toBe($this->user->getKey())
        ->and($this->user->fresh()->last_login_at)->not->toBeNull();
});
it('authenticates the user after valid recovery code verification', function () {
    $this->pendingService->shouldReceive('getPending')->once()->andReturn($this->pending);
    $this->pendingService->shouldReceive('getPendingUser')->once()->andReturn($this->user);
    $this->pendingService->shouldReceive('complete')->once()->with($this->pending->challengeId)->andReturn(true);
    $this->twoFactorService->shouldReceive('verifyRecoveryCode')->once()->with($this->user, 'AB12345678')->andReturn(true);
    $result = $this->action->execute(new TwoFactorVerificationData(code: null, recoveryCode: 'AB12345678'), $this->request);
    expect($result->is($this->user))->toBeTrue()
        ->and(Auth::guard('web')->check())->toBeTrue()
        ->and(Auth::guard('web')->id())->toBe($this->user->getKey());
});
it('preserves the remember preference when completing login', function () {
    $pending = new PendingAuthenticationData(
        userId: $this->user->getKey(),
        method: AuthenticationMethod::PASSWORD,
        remember: true,
        challengeId: $this->pending->challengeId,
        expiresAt: now()->addMinutes(5)->timestamp,
    );
    $this->pendingService->shouldReceive('getPending')->once()->andReturn($pending);
    $this->pendingService->shouldReceive('getPendingUser')->once()->andReturn($this->user);
    $this->pendingService->shouldReceive('complete')->once()->with($pending->challengeId)->andReturn(true);
    $this->twoFactorService->shouldReceive('verifyCode')->once()->andReturn(true);
    $this->action->execute(new TwoFactorVerificationData(code: '123456'), $this->request);
    expect(Auth::guard('web')->check())->toBeTrue();
});
it('rejects verification when no pending challenge exists', function () {
    $this->pendingService->shouldReceive('getPending')->once()->andReturn(null);
    $this->pendingService->shouldNotReceive('getPendingUser');
    $this->twoFactorService->shouldNotReceive('verifyCode');
    $this->twoFactorService->shouldNotReceive('verifyRecoveryCode');
    expect(fn () => $this->action->execute(new TwoFactorVerificationData(code: '123456'), $this->request))
        ->toThrow(ValidationException::class);
    expect(Auth::guard('web')->check())->toBeFalse();
});
it('rejects verification when the pending user is unavailable', function () {
    $this->pendingService->shouldReceive('getPending')->once()->andReturn($this->pending);
    $this->pendingService->shouldReceive('getPendingUser')->once()->andReturn(null);
    $this->twoFactorService->shouldNotReceive('verifyCode');
    $this->twoFactorService->shouldNotReceive('verifyRecoveryCode');
    $this->pendingService->shouldNotReceive('complete');
    expect(fn () => $this->action->execute(new TwoFactorVerificationData(code: '123456'), $this->request))
        ->toThrow(ValidationException::class);
    expect(Auth::guard('web')->check())->toBeFalse();
});
it('rejects an invalid otp without completing the challenge', function () {
    $this->pendingService->shouldReceive('getPending')->once()->andReturn($this->pending);
    $this->pendingService->shouldReceive('getPendingUser')->once()->andReturn($this->user);
    $this->twoFactorService->shouldReceive('verifyCode')->once()->andReturn(false);
    $this->pendingService->shouldNotReceive('complete');
    expect(fn () => $this->action->execute(new TwoFactorVerificationData(code: '000000'), $this->request))
        ->toThrow(ValidationException::class);
    expect(Auth::guard('web')->check())->toBeFalse();
});
it('rejects an invalid recovery code without completing the challenge', function () {
    $this->pendingService->shouldReceive('getPending')->once()->andReturn($this->pending);
    $this->pendingService->shouldReceive('getPendingUser')->once()->andReturn($this->user);
    $this->twoFactorService->shouldReceive('verifyRecoveryCode')->once()->andReturn(false);
    $this->pendingService->shouldNotReceive('complete');
    expect(fn () => $this->action->execute(new TwoFactorVerificationData(code: null, recoveryCode: 'INVALID1234'), $this->request))
        ->toThrow(ValidationException::class);
    expect(Auth::guard('web')->check())->toBeFalse();
});
it('rejects verification when challenge completion fails', function () {
    $this->pendingService->shouldReceive('getPending')->once()->andReturn($this->pending);
    $this->pendingService->shouldReceive('getPendingUser')->once()->andReturn($this->user);
    $this->twoFactorService->shouldReceive('verifyCode')->once()->andReturn(true);
    $this->pendingService->shouldReceive('complete')->once()->with($this->pending->challengeId)->andReturn(false);
    expect(fn () => $this->action->execute(new TwoFactorVerificationData(code: '123456'), $this->request))
        ->toThrow(ValidationException::class);
    expect(Auth::guard('web')->check())->toBeFalse();
});
it('does not authenticate when verification fails', function () {
    $this->pendingService->shouldReceive('getPending')->once()->andReturn($this->pending);
    $this->pendingService->shouldReceive('getPendingUser')->once()->andReturn($this->user);
    $this->twoFactorService->shouldReceive('verifyCode')->once()->andReturn(false);
    try {
        $this->action->execute(new TwoFactorVerificationData(code: '000000'), $this->request);
    } catch (ValidationException) {
    }
    expect(Auth::guard('web')->check())->toBeFalse()
        ->and($this->user->fresh()->last_login_at)->toBeNull()
        ->and($this->user->fresh()->last_login_ip)->toBeNull();
});
it('does not authenticate when challenge completion fails', function () {
    $this->pendingService->shouldReceive('getPending')->once()->andReturn($this->pending);
    $this->pendingService->shouldReceive('getPendingUser')->once()->andReturn($this->user);
    $this->twoFactorService->shouldReceive('verifyCode')->once()->andReturn(true);
    $this->pendingService->shouldReceive('complete')->once()->andReturn(false);
    try {
        $this->action->execute(new TwoFactorVerificationData(code: '123456'), $this->request);
    } catch (ValidationException) {
    }
    expect(Auth::guard('web')->check())->toBeFalse()
        ->and($this->user->fresh()->last_login_at)->toBeNull();
});
it('does not update login metadata when the pending user is unavailable', function () {
    $this->pendingService->shouldReceive('getPending')->once()->andReturn($this->pending);
    $this->pendingService->shouldReceive('getPendingUser')->once()->andReturn(null);
    try {
        $this->action->execute(new TwoFactorVerificationData(code: '123456'), $this->request);
    } catch (ValidationException) {
    }
    expect($this->user->fresh()->last_login_at)->toBeNull()
        ->and($this->user->fresh()->last_login_ip)->toBeNull();
});
it('uses the challenge id stored in the pending session', function () {
    $this->pendingService->shouldReceive('getPending')->once()->andReturn($this->pending);
    $this->pendingService->shouldReceive('getPendingUser')->once()->andReturn($this->user);
    $this->twoFactorService->shouldReceive('verifyCode')->once()->andReturn(true);
    $this->pendingService->shouldReceive('complete')->once()->with($this->pending->challengeId)->andReturn(true);
    $this->action->execute(new TwoFactorVerificationData(code: '123456'), $this->request);
    expect(Auth::guard('web')->id())->toBe($this->user->getKey());
});
