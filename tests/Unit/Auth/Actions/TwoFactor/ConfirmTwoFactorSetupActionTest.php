<?php

use App\Actions\Auth\TwoFactor\ConfirmTwoFactorSetupAction;
use App\Contracts\Auth\TwoFactorAuthenticationServiceInterface;
use App\DataTransferObjects\Auth\TwoFactor\TwoFactorRecoveryCodesData;
use App\Models\User;
use RuntimeException;

beforeEach(function () {
    $this->user = new User([
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]);
    $this->service = Mockery::mock(TwoFactorAuthenticationServiceInterface::class);
    $this->action = new ConfirmTwoFactorSetupAction($this->service);
});
it('confirms two factor setup and returns recovery codes', function () {
    $recoveryCodes = new TwoFactorRecoveryCodesData([
        'AB12345678',
        'CD12345678',
        'EF12345678',
        'GH12345678',
        'IJ12345678',
        'KL12345678',
        'MN12345678',
        'OP12345678',
        'QR12345678',
        'ST12345678',
    ]);
    $this->service
        ->shouldReceive('confirmSetup')
        ->once()
        ->with($this->user, '123456')
        ->andReturn($recoveryCodes);
    $result = $this->action->handle($this->user, '123456');
    expect($result)->toBeInstanceOf(TwoFactorRecoveryCodesData::class)
        ->and($result)->toBe($recoveryCodes)
        ->and($result->codes)->toHaveCount(10);
});
it('returns null when otp is invalid', function () {
    $this->service
        ->shouldReceive('confirmSetup')
        ->once()
        ->with($this->user, '000000')
        ->andReturn(null);
    expect($this->action->handle($this->user, '000000'))->toBeNull();
});
it('passes the exact otp to the service', function () {
    $this->service
        ->shouldReceive('confirmSetup')
        ->once()
        ->with($this->user, '654321')
        ->andReturn(null);
    $this->action->handle($this->user, '654321');
});
it('propagates exception when setup confirmation fails', function () {
    $this->service
        ->shouldReceive('confirmSetup')
        ->once()
        ->with($this->user, '123456')
        ->andThrow(new RuntimeException('Setup confirmation failed.'));
    expect(fn () => $this->action->handle($this->user, '123456'))
        ->toThrow(RuntimeException::class, 'Setup confirmation failed.');
});
