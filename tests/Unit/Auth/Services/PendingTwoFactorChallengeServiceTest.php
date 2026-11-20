<?php

use App\Contracts\Auth\PendingTwoFactorChallengeServiceInterface;
use App\DataTransferObjects\Auth\Authentication\PendingAuthenticationData;
use App\Enums\Auth\AuthenticationMethod;
use App\Models\User;
use Illuminate\Contracts\Session\Session;
use InvalidArgumentException;

beforeEach(function () {
    $this->session = app(Session::class);
    $this->session->flush();
    $this->service = app(PendingTwoFactorChallengeServiceInterface::class);
});
function createEligibleTwoFactorUser(): User
{
    $user = User::factory()->create();
    $user->forceFill([
        'status' => 'active',
        'two_factor_enabled' => true,
        'two_factor_secret' => encrypt('TESTSECRET'),
        'two_factor_confirmed_at' => now(),
    ])->save();

    return $user->refresh();
}
function createPendingChallenge(User $user, array $overrides = []): array
{
    return array_merge([
        'user_id' => $user->getKey(),
        'method' => AuthenticationMethod::PASSWORD->value,
        'remember' => false,
        'challenge_id' => bin2hex(random_bytes(32)),
        'expires_at' => now()->addMinutes(5)->timestamp,
    ], $overrides);
}
it('creates a pending challenge for an eligible user', function () {
    $user = createEligibleTwoFactorUser();
    $pending = $this->service->begin($user, AuthenticationMethod::PASSWORD);
    expect($pending)->toBeInstanceOf(PendingAuthenticationData::class)
        ->and($pending->userId)->toBe($user->getKey())
        ->and($pending->method)->toBe(AuthenticationMethod::PASSWORD)
        ->and($pending->remember)->toBeFalse()
        ->and($pending->challengeId)->toHaveLength(64)
        ->and($this->session->get('auth.two_factor.pending'))->toBe($pending->toArray());
});
it('creates a unique challenge id for every challenge', function () {
    $user = createEligibleTwoFactorUser();
    $first = $this->service->begin($user, AuthenticationMethod::PASSWORD);
    $second = $this->service->begin($user, AuthenticationMethod::GOOGLE);
    expect($first->challengeId)->not->toBe($second->challengeId);
});
it('sets the challenge expiration to five minutes', function () {
    $user = createEligibleTwoFactorUser();
    $before = now()->timestamp;
    $pending = $this->service->begin($user, AuthenticationMethod::PASSWORD);
    $after = now()->timestamp;
    expect($pending->expiresAt)->toBeGreaterThanOrEqual($before + 300)
        ->and($pending->expiresAt)->toBeLessThanOrEqual($after + 300);
});
it('preserves the remember preference', function () {
    $user = createEligibleTwoFactorUser();
    $pending = $this->service->begin($user, AuthenticationMethod::PASSWORD, true);
    expect($pending->remember)->toBeTrue();
});
it('supports every authentication method', function (AuthenticationMethod $method) {
    $user = createEligibleTwoFactorUser();
    $pending = $this->service->begin($user, $method);
    expect($pending->method)->toBe($method);
})->with([
    'password' => [AuthenticationMethod::PASSWORD],
    'google' => [AuthenticationMethod::GOOGLE],
    'magic link' => [AuthenticationMethod::MAGIC_LINK],
]);
it('rejects an inactive user', function () {
    $user = createEligibleTwoFactorUser();
    $user->forceFill(['status' => 'inactive'])->save();
    expect(fn () => $this->service->begin($user, AuthenticationMethod::PASSWORD))
        ->toThrow(InvalidArgumentException::class);
});
it('rejects a user with two factor authentication disabled', function () {
    $user = createEligibleTwoFactorUser();
    $user->forceFill(['two_factor_enabled' => false])->save();
    expect(fn () => $this->service->begin($user, AuthenticationMethod::PASSWORD))
        ->toThrow(InvalidArgumentException::class);
});
it('rejects a user whose two factor authentication is not confirmed', function () {
    $user = createEligibleTwoFactorUser();
    $user->forceFill(['two_factor_confirmed_at' => null])->save();
    expect(fn () => $this->service->begin($user, AuthenticationMethod::PASSWORD))
        ->toThrow(InvalidArgumentException::class);
});
it('returns the pending challenge from the session', function () {
    $user = createEligibleTwoFactorUser();
    $data = createPendingChallenge($user);
    $this->session->put('auth.two_factor.pending', $data);
    $pending = $this->service->getPending();
    expect($pending)->toBeInstanceOf(PendingAuthenticationData::class)
        ->and($pending->userId)->toBe($user->getKey())
        ->and($pending->challengeId)->toBe($data['challenge_id']);
});
it('returns null when no pending challenge exists', function () {
    expect($this->service->getPending())->toBeNull();
});
it('clears malformed pending challenge data', function () {
    $this->session->put('auth.two_factor.pending', ['invalid' => true]);
    expect($this->service->getPending())->toBeNull()
        ->and($this->session->has('auth.two_factor.pending'))->toBeFalse();
});
it('clears an expired pending challenge', function () {
    $user = createEligibleTwoFactorUser();
    $this->session->put('auth.two_factor.pending', createPendingChallenge($user, [
        'expires_at' => now()->subSecond()->timestamp,
    ]));
    expect($this->service->getPending())->toBeNull()
        ->and($this->session->has('auth.two_factor.pending'))->toBeFalse();
});
it('returns the pending user when the account remains eligible', function () {
    $user = createEligibleTwoFactorUser();
    $this->session->put('auth.two_factor.pending', createPendingChallenge($user));
    expect($this->service->getPendingUser()?->is($user))->toBeTrue();
});
it('clears the challenge when the pending user no longer exists', function () {
    $user = createEligibleTwoFactorUser();
    $this->session->put('auth.two_factor.pending', createPendingChallenge($user));
    $user->delete();
    expect($this->service->getPendingUser())->toBeNull()
        ->and($this->session->has('auth.two_factor.pending'))->toBeFalse();
});
it('clears the challenge when the pending user becomes inactive', function () {
    $user = createEligibleTwoFactorUser();
    $this->session->put('auth.two_factor.pending', createPendingChallenge($user));
    $user->forceFill(['status' => 'inactive'])->save();
    expect($this->service->getPendingUser())->toBeNull()
        ->and($this->session->has('auth.two_factor.pending'))->toBeFalse();
});
it('clears the challenge when two factor authentication is disabled', function () {
    $user = createEligibleTwoFactorUser();
    $this->session->put('auth.two_factor.pending', createPendingChallenge($user));
    $user->forceFill(['two_factor_enabled' => false])->save();
    expect($this->service->getPendingUser())->toBeNull()
        ->and($this->session->has('auth.two_factor.pending'))->toBeFalse();
});
it('clears the challenge when two factor authentication is no longer confirmed', function () {
    $user = createEligibleTwoFactorUser();
    $this->session->put('auth.two_factor.pending', createPendingChallenge($user));
    $user->forceFill(['two_factor_confirmed_at' => null])->save();
    expect($this->service->getPendingUser())->toBeNull()
        ->and($this->session->has('auth.two_factor.pending'))->toBeFalse();
});
it('identifies a valid pending challenge', function () {
    $user = createEligibleTwoFactorUser();
    $this->session->put('auth.two_factor.pending', createPendingChallenge($user));
    expect($this->service->isPending())->toBeTrue();
});
it('returns false when there is no pending challenge', function () {
    expect($this->service->isPending())->toBeFalse();
});
it('identifies an expired challenge', function () {
    $user = createEligibleTwoFactorUser();
    $this->session->put('auth.two_factor.pending', createPendingChallenge($user, [
        'expires_at' => now()->subSecond()->timestamp,
    ]));
    expect($this->service->isExpired())->toBeTrue();
});
it('returns false when there is no expired challenge', function () {
    expect($this->service->isExpired())->toBeFalse();
});
it('completes a challenge with the correct challenge id', function () {
    $user = createEligibleTwoFactorUser();
    $data = createPendingChallenge($user);
    $this->session->put('auth.two_factor.pending', $data);
    expect($this->service->complete($data['challenge_id']))->toBeTrue()
        ->and($this->session->has('auth.two_factor.pending'))->toBeFalse();
});
it('rejects an incorrect challenge id', function () {
    $user = createEligibleTwoFactorUser();
    $data = createPendingChallenge($user);
    $this->session->put('auth.two_factor.pending', $data);
    expect($this->service->complete('incorrect-challenge-id'))->toBeFalse()
        ->and($this->session->has('auth.two_factor.pending'))->toBeTrue();
});
it('rejects an empty challenge id', function () {
    $user = createEligibleTwoFactorUser();
    $this->session->put('auth.two_factor.pending', createPendingChallenge($user));
    expect($this->service->complete(''))->toBeFalse()
        ->and($this->session->has('auth.two_factor.pending'))->toBeTrue();
});
it('rejects completing a challenge when no challenge exists', function () {
    expect($this->service->complete('some-challenge-id'))->toBeFalse();
});
it('rejects completing an expired challenge', function () {
    $user = createEligibleTwoFactorUser();
    $this->session->put('auth.two_factor.pending', createPendingChallenge($user, [
        'expires_at' => now()->subSecond()->timestamp,
    ]));
    expect($this->service->complete('some-challenge-id'))->toBeFalse()
        ->and($this->session->has('auth.two_factor.pending'))->toBeFalse();
});
it('cancels an existing challenge', function () {
    $user = createEligibleTwoFactorUser();
    $this->session->put('auth.two_factor.pending', createPendingChallenge($user));
    expect($this->service->cancel())->toBeTrue()
        ->and($this->session->has('auth.two_factor.pending'))->toBeFalse();
});
it('returns false when cancelling without a challenge', function () {
    expect($this->service->cancel())->toBeFalse();
});
it('clears an expired challenge', function () {
    $user = createEligibleTwoFactorUser();
    $this->session->put('auth.two_factor.pending', createPendingChallenge($user, [
        'expires_at' => now()->subSecond()->timestamp,
    ]));
    expect($this->service->clearExpired())->toBeTrue()
        ->and($this->session->has('auth.two_factor.pending'))->toBeFalse();
});
it('does not clear a valid challenge', function () {
    $user = createEligibleTwoFactorUser();
    $this->session->put('auth.two_factor.pending', createPendingChallenge($user));
    expect($this->service->clearExpired())->toBeFalse()
        ->and($this->session->has('auth.two_factor.pending'))->toBeTrue();
});
it('returns false when clearing without a challenge', function () {
    expect($this->service->clearExpired())->toBeFalse();
});
