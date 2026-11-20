<?php

use App\DataTransferObjects\Auth\Authentication\PendingAuthenticationData;
use App\Enums\Auth\AuthenticationMethod;
use InvalidArgumentException;

function makePendingAuthenticationData(array $overrides = []): PendingAuthenticationData
{
    return new PendingAuthenticationData(
        userId: $overrides['userId'] ?? 1,
        method: $overrides['method'] ?? AuthenticationMethod::PASSWORD,
        remember: $overrides['remember'] ?? false,
        challengeId: $overrides['challengeId'] ?? 'challenge-123',
        expiresAt: $overrides['expiresAt'] ?? 2000000000,
    );
}
it('creates pending authentication data successfully', function () {
    $data = makePendingAuthenticationData();
    expect($data->userId)->toBe(1)
        ->and($data->method)->toBe(AuthenticationMethod::PASSWORD)
        ->and($data->remember)->toBeFalse()
        ->and($data->challengeId)->toBe('challenge-123');
});
it('converts pending authentication data to an array', function () {
    expect(makePendingAuthenticationData()->toArray())->toBe([
        'user_id' => 1,
        'method' => 'password',
        'remember' => false,
        'challenge_id' => 'challenge-123',
        'expires_at' => 2000000000,
    ]);
});
it('restores pending authentication data from an array', function () {
    $data = makePendingAuthenticationData();
    expect(PendingAuthenticationData::fromArray($data->toArray()))->toEqual($data);
});
it('returns null for missing required fields', function () {
    expect(PendingAuthenticationData::fromArray([]))->toBeNull();
});
it('returns null for an unsupported authentication method', function () {
    $data = makePendingAuthenticationData()->toArray();
    $data['method'] = 'unsupported';
    expect(PendingAuthenticationData::fromArray($data))->toBeNull();
});
it('returns null for invalid field types', function () {
    $data = makePendingAuthenticationData()->toArray();
    $data['remember'] = 'false';
    expect(PendingAuthenticationData::fromArray($data))->toBeNull();
});
it('rejects an empty challenge identifier', function () {
    expect(fn () => makePendingAuthenticationData(['challengeId' => '']))
        ->toThrow(InvalidArgumentException::class);
});
it('rejects an invalid expiration timestamp', function () {
    expect(fn () => makePendingAuthenticationData(['expiresAt' => 0]))
        ->toThrow(InvalidArgumentException::class);
});
it('detects expired pending authentication data', function () {
    $data = makePendingAuthenticationData(['expiresAt' => 1000]);
    expect($data->isExpired(1000))->toBeTrue()
        ->and($data->isExpired(999))->toBeFalse();
});
