<?php

use App\Actions\Auth\TwoFactor\VerifyTwoFactorCodeAction;
use App\Contracts\Auth\TwoFactorAuthenticationServiceInterface;
use App\DataTransferObjects\Auth\TwoFactor\TwoFactorVerificationData;
use App\Models\User;
use Mockery;
use RuntimeException;

beforeEach(function () {
    $this->user = new User([
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]);
    $this->service = Mockery::mock(TwoFactorAuthenticationServiceInterface::class);
    $this->action = new VerifyTwoFactorCodeAction($this->service);
});
it('verifies a valid authenticator code', function () {
    $this->service
        ->shouldReceive('verifyCode')
        ->once()
        ->with($this->user, '123456')
        ->andReturn(true);
    $result = $this->action->handle(
        $this->user,
        new TwoFactorVerificationData('123456')
    );
    expect($result)->toBeTrue();
});
it('rejects an invalid authenticator code', function () {
    $this->service
        ->shouldReceive('verifyCode')
        ->once()
        ->with($this->user, '000000')
        ->andReturn(false);
    $result = $this->action->handle(
        $this->user,
        new TwoFactorVerificationData('000000')
    );
    expect($result)->toBeFalse();
});
it('does not call service when recovery code verification is requested', function () {
    $this->service->shouldNotReceive('verifyCode');
    $result = $this->action->handle(
        $this->user,
        new TwoFactorVerificationData(
            code: '',
            recoveryCode: 'AB12345678',
        )
    );
    expect($result)->toBeFalse();
});
it('passes the exact otp to the service', function () {
    $this->service
        ->shouldReceive('verifyCode')
        ->once()
        ->with($this->user, '654321')
        ->andReturn(true);
    expect($this->action->handle(
        $this->user,
        new TwoFactorVerificationData('654321')
    ))->toBeTrue();
});
it('propagates exception when verification fails unexpectedly', function () {
    $this->service
        ->shouldReceive('verifyCode')
        ->once()
        ->with($this->user, '123456')
        ->andThrow(new RuntimeException('Two factor verification failed.'));
    expect(fn () => $this->action->handle(
        $this->user,
        new TwoFactorVerificationData('123456')
    ))->toThrow(RuntimeException::class, 'Two factor verification failed.');
});
