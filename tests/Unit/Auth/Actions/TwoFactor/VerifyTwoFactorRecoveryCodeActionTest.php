<?php

use App\Actions\Auth\TwoFactor\VerifyTwoFactorRecoveryCodeAction;
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
    $this->action = new VerifyTwoFactorRecoveryCodeAction($this->service);
});
it('verifies a valid recovery code', function () {
    $this->service
        ->shouldReceive('verifyRecoveryCode')
        ->once()
        ->with($this->user, 'AB12345678')
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
it('rejects an invalid recovery code', function () {
    $this->service
        ->shouldReceive('verifyRecoveryCode')
        ->once()
        ->with($this->user, 'ZZ12345678')
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
it('does not call service when authenticator code verification is requested', function () {
    $this->service->shouldNotReceive('verifyRecoveryCode');
    $result = $this->action->handle(
        $this->user,
        new TwoFactorVerificationData('123456')
    );
    expect($result)->toBeFalse();
});
it('passes the exact recovery code to the service', function () {
    $this->service
        ->shouldReceive('verifyRecoveryCode')
        ->once()
        ->with($this->user, 'CD98765432')
        ->andReturn(true);
    expect($this->action->handle(
        $this->user,
        new TwoFactorVerificationData(
            code: '',
            recoveryCode: 'CD98765432',
        )
    ))->toBeTrue();
});
it('propagates exception when recovery code verification fails unexpectedly', function () {
    $this->service
        ->shouldReceive('verifyRecoveryCode')
        ->once()
        ->with($this->user, 'AB12345678')
        ->andThrow(new RuntimeException('Recovery code verification failed.'));
    expect(fn () => $this->action->handle(
        $this->user,
        new TwoFactorVerificationData(
            code: '',
            recoveryCode: 'AB12345678',
        )
    ))->toThrow(RuntimeException::class, 'Recovery code verification failed.');
});
