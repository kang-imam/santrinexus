<?php

use App\Actions\Auth\TwoFactor\DisableTwoFactorAction;
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
    $this->action = new DisableTwoFactorAction($this->service);
});
it('disables two factor authentication using a valid otp', function () {
    $this->service
        ->shouldReceive('disable')
        ->once()
        ->with($this->user, '123456', false)
        ->andReturn(true);
    $result = $this->action->handle(
        $this->user,
        new TwoFactorVerificationData('123456')
    );
    expect($result)->toBeTrue();
});
it('disables two factor authentication using a valid recovery code', function () {
    $this->service
        ->shouldReceive('disable')
        ->once()
        ->with($this->user, 'AB12345678', true)
        ->andReturn(true);
    $result = $this->action->handle(
        $this->user,
        new TwoFactorVerificationData(
            code: '',
            recoveryCode: 'AB12345678',
        )
    );
    expect($result)->toBeTrue();
});
it('returns false when otp verification fails', function () {
    $this->service
        ->shouldReceive('disable')
        ->once()
        ->with($this->user, '000000', false)
        ->andReturn(false);
    $result = $this->action->handle(
        $this->user,
        new TwoFactorVerificationData('000000')
    );
    expect($result)->toBeFalse();
});
it('returns false when recovery code verification fails', function () {
    $this->service
        ->shouldReceive('disable')
        ->once()
        ->with($this->user, 'ZZ12345678', true)
        ->andReturn(false);
    $result = $this->action->handle(
        $this->user,
        new TwoFactorVerificationData(
            code: '',
            recoveryCode: 'ZZ12345678',
        )
    );
    expect($result)->toBeFalse();
});
it('passes the correct verification code and type to the service', function () {
    $this->service
        ->shouldReceive('disable')
        ->once()
        ->with($this->user, 'CD98765432', true)
        ->andReturn(true);
    expect($this->action->handle(
        $this->user,
        new TwoFactorVerificationData(
            code: '',
            recoveryCode: 'CD98765432',
        )
    ))->toBeTrue();
});
it('propagates exception when disabling two factor authentication fails unexpectedly', function () {
    $this->service
        ->shouldReceive('disable')
        ->once()
        ->with($this->user, '123456', false)
        ->andThrow(new RuntimeException('Two factor deactivation failed.'));
    expect(fn () => $this->action->handle(
        $this->user,
        new TwoFactorVerificationData('123456')
    ))->toThrow(RuntimeException::class, 'Two factor deactivation failed.');
});
