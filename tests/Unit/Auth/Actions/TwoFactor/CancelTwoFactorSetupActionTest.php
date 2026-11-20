<?php

use App\Actions\Auth\TwoFactor\CancelTwoFactorSetupAction;
use App\Contracts\Auth\TwoFactorAuthenticationServiceInterface;
use App\Models\User;
use Mockery;
use RuntimeException;

beforeEach(function () {
    $this->user = new User([
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]);
    $this->service = Mockery::mock(TwoFactorAuthenticationServiceInterface::class);
    $this->action = new CancelTwoFactorSetupAction($this->service);
});
it('cancels pending two factor setup successfully', function () {
    $this->service
        ->shouldReceive('cancelSetup')
        ->once()
        ->with($this->user)
        ->andReturn(true);
    expect($this->action->handle($this->user))->toBeTrue();
});
it('returns false when setup cancellation is rejected', function () {
    $this->service
        ->shouldReceive('cancelSetup')
        ->once()
        ->with($this->user)
        ->andReturn(false);
    expect($this->action->handle($this->user))->toBeFalse();
});
it('passes the correct user to the service exactly once', function () {
    $this->service
        ->shouldReceive('cancelSetup')
        ->once()
        ->with($this->user)
        ->andReturn(true);
    $result = $this->action->handle($this->user);
    expect($result)->toBeTrue();
});
it('propagates exception when setup cancellation fails unexpectedly', function () {
    $this->service
        ->shouldReceive('cancelSetup')
        ->once()
        ->with($this->user)
        ->andThrow(new RuntimeException('Two factor setup cancellation failed.'));
    expect(fn () => $this->action->handle($this->user))
        ->toThrow(RuntimeException::class, 'Two factor setup cancellation failed.');
});
