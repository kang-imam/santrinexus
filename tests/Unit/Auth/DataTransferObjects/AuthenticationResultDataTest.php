<?php

use App\DataTransferObjects\Auth\Authentication\AuthenticationResultData;
use App\Enums\Auth\AuthenticationStatus;
use App\Models\User;
use InvalidArgumentException;

it('creates an authenticated result with a user', function () {
    $user = new User;
    $result = AuthenticationResultData::authenticated($user);
    expect($result->status)->toBe(AuthenticationStatus::AUTHENTICATED)
        ->and($result->user)->toBe($user)
        ->and($result->isAuthenticated())->toBeTrue()
        ->and($result->requiresTwoFactorVerification())->toBeFalse();
});
it('creates a two factor required result without an authenticated user', function () {
    $result = AuthenticationResultData::requiresTwoFactor();
    expect($result->status)->toBe(AuthenticationStatus::TWO_FACTOR_REQUIRED)
        ->and($result->user)->toBeNull()
        ->and($result->isAuthenticated())->toBeFalse()
        ->and($result->requiresTwoFactorVerification())->toBeTrue();
});
it('rejects authenticated status without a user', function () {
    expect(fn () => new AuthenticationResultData(AuthenticationStatus::AUTHENTICATED))
        ->toThrow(InvalidArgumentException::class);
});
it('rejects two factor required status with a user', function () {
    expect(fn () => new AuthenticationResultData(AuthenticationStatus::TWO_FACTOR_REQUIRED, new User))
        ->toThrow(InvalidArgumentException::class);
});
it('preserves the exact user instance', function () {
    $user = new User(['name' => 'Test User']);
    expect(AuthenticationResultData::authenticated($user)->user)->toBe($user);
});
it('does not expose a user before two factor verification', function () {
    $result = AuthenticationResultData::requiresTwoFactor();
    expect($result->user)->toBeNull()
        ->and($result->status)->toBe(AuthenticationStatus::TWO_FACTOR_REQUIRED);
});
