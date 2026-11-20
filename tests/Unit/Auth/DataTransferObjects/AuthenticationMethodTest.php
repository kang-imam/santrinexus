<?php

use App\Enums\Auth\AuthenticationMethod;

it('contains all supported authentication methods', function () {
    expect(AuthenticationMethod::cases())->toHaveCount(3);
});
it('uses the correct authentication method values', function () {
    expect(AuthenticationMethod::PASSWORD->value)->toBe('password')
        ->and(AuthenticationMethod::GOOGLE->value)->toBe('google')
        ->and(AuthenticationMethod::MAGIC_LINK->value)->toBe('magic_link');
});
it('can resolve authentication methods from their values', function () {
    expect(AuthenticationMethod::from('password'))->toBe(AuthenticationMethod::PASSWORD)
        ->and(AuthenticationMethod::from('google'))->toBe(AuthenticationMethod::GOOGLE)
        ->and(AuthenticationMethod::from('magic_link'))->toBe(AuthenticationMethod::MAGIC_LINK);
});
