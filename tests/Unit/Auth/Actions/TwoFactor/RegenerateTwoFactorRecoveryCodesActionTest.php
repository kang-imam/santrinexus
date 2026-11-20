<?php

use App\Actions\Auth\TwoFactor\RegenerateTwoFactorRecoveryCodesAction;
use App\Contracts\Auth\TwoFactorAuthenticationServiceInterface;
use App\DataTransferObjects\Auth\TwoFactor\TwoFactorRecoveryCodesData;
use App\Models\User;
use Mockery;
use RuntimeException;

beforeEach(function () {
    $this->user = new User([
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]);
    $this->service = Mockery::mock(TwoFactorAuthenticationServiceInterface::class);
    $this->action = new RegenerateTwoFactorRecoveryCodesAction($this->service);
});
it('regenerates recovery codes and returns ten new codes', function () {
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
        ->shouldReceive('regenerateRecoveryCodes')
        ->once()
        ->with($this->user, '123456')
        ->andReturn($recoveryCodes);
    $result = $this->action->handle($this->user, '123456');
    expect($result)->toBeInstanceOf(TwoFactorRecoveryCodesData::class)
        ->and($result)->toBe($recoveryCodes)
        ->and($result->codes)->toHaveCount(10);
});
it('returns null when authenticator code is invalid', function () {
    $this->service
        ->shouldReceive('regenerateRecoveryCodes')
        ->once()
        ->with($this->user, '000000')
        ->andReturn(null);
    expect($this->action->handle($this->user, '000000'))->toBeNull();
});
it('passes the exact authenticator code to the service', function () {
    $this->service
        ->shouldReceive('regenerateRecoveryCodes')
        ->once()
        ->with($this->user, '654321')
        ->andReturn(null);
    $this->action->handle($this->user, '654321');
});
it('delegates recovery code replacement entirely to the service', function () {
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
        ->shouldReceive('regenerateRecoveryCodes')
        ->once()
        ->with($this->user, '123456')
        ->andReturn($recoveryCodes);
    $result = $this->action->handle($this->user, '123456');
    expect($result)->toBe($recoveryCodes);
});
it('propagates exception when recovery code regeneration fails unexpectedly', function () {
    $this->service
        ->shouldReceive('regenerateRecoveryCodes')
        ->once()
        ->with($this->user, '123456')
        ->andThrow(new RuntimeException('Recovery code regeneration failed.'));
    expect(fn () => $this->action->handle($this->user, '123456'))
        ->toThrow(RuntimeException::class, 'Recovery code regeneration failed.');
});
