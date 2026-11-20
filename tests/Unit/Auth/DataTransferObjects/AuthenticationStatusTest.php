<?php

use App\Enums\Auth\AuthenticationStatus;

it('contains all supported authentication statuses', function () {
    expect(AuthenticationStatus::cases())->toHaveCount(2);
});
it('uses the correct authentication status values', function () {
    expect(AuthenticationStatus::AUTHENTICATED->value)->toBe('authenticated')
        ->and(AuthenticationStatus::TWO_FACTOR_REQUIRED->value)->toBe('two_factor_required');
});
