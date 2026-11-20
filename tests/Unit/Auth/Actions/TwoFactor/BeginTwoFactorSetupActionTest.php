<?php

use App\Actions\Auth\TwoFactor\BeginTwoFactorSetupAction;
use App\Contracts\Auth\TwoFactorAuthenticationServiceInterface;
use App\DataTransferObjects\Auth\TwoFactor\TwoFactorSetupData;
use App\Models\User;
use LogicException;

beforeEach(function () {
    $this->user = new User([
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]);
    $this->setupData = new TwoFactorSetupData(
        secret: 'ABCDEFGHIJKLMNOP',
        provisioningUri: 'otpauth://totp/SantriNexus:test@example.com',
        qrCode: 'data:image/svg+xml;base64,dGVzdA==',
    );
    $this->service = Mockery::mock(TwoFactorAuthenticationServiceInterface::class);
    $this->action = new BeginTwoFactorSetupAction($this->service);
});
it('begins two factor setup and returns setup data', function () {
    $this->service
        ->shouldReceive('beginSetup')
        ->once()
        ->with($this->user)
        ->andReturn($this->setupData);
    $result = $this->action->handle($this->user);
    expect($result)->toBeInstanceOf(TwoFactorSetupData::class)
        ->and($result)->toBe($this->setupData);
});
it('propagates exception when setup cannot begin', function () {
    $this->service
        ->shouldReceive('beginSetup')
        ->once()
        ->with($this->user)
        ->andThrow(new LogicException('Autentikasi dua faktor sudah aktif.'));
    expect(fn () => $this->action->handle($this->user))
        ->toThrow(LogicException::class, 'Autentikasi dua faktor sudah aktif.');
});
