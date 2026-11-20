<?php

use App\Actions\Auth\Login\CompleteWebLoginAction;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->action = app(CompleteWebLoginAction::class);
    $this->user = User::factory()->create([
        'status' => 'active',
        'password' => Hash::make('Password123!'),
        'last_login_at' => null,
        'last_login_ip' => null,
    ]);
    $this->request = Request::create('/login', 'POST');
    $this->request->server->set('REMOTE_ADDR', '127.0.0.1');
    $this->request->setLaravelSession(Session::driver());
});
it('authenticates an active user using the web guard', function () {
    $result = $this->action->execute($this->user, $this->request);
    expect($result->is($this->user))->toBeTrue()
        ->and(Auth::guard('web')->check())->toBeTrue()
        ->and(Auth::guard('web')->id())->toBe($this->user->getKey());
});
it('authenticates an active user with remember enabled', function () {
    $result = $this->action->execute($this->user, $this->request, true);
    expect($result->is($this->user))->toBeTrue()
        ->and(Auth::guard('web')->check())->toBeTrue()
        ->and(Auth::guard('web')->viaRemember())->toBeFalse();
});
it('updates the last login timestamp', function () {
    $this->action->execute($this->user, $this->request);
    $this->user->refresh();
    expect($this->user->last_login_at)->not->toBeNull()
        ->and($this->user->last_login_at->toDateTimeString())->toBe(now()->toDateTimeString());
});
it('updates the last login ip address', function () {
    $this->action->execute($this->user, $this->request);
    expect($this->user->refresh()->last_login_ip)->toBe('127.0.0.1');
});
it('rejects a user that no longer exists', function () {
    $user = clone $this->user;
    $this->user->delete();
    expect(fn () => $this->action->execute($user, $this->request))
        ->toThrow(AuthenticationException::class);
});
it('rejects an inactive user', function () {
    $this->user->forceFill(['status' => 'inactive'])->save();
    expect(fn () => $this->action->execute($this->user, $this->request))
        ->toThrow(AuthenticationException::class);
});
it('does not authenticate an inactive user', function () {
    $this->user->forceFill(['status' => 'inactive'])->save();
    try {
        $this->action->execute($this->user, $this->request);
    } catch (AuthenticationException) {
    }
    expect(Auth::guard('web')->check())->toBeFalse();
});
it('does not authenticate through the filament guard', function () {
    $this->action->execute($this->user, $this->request);
    expect(Auth::guard('web')->check())->toBeTrue()
        ->and(Auth::guard('web')->id())->toBe($this->user->getKey());
});
it('regenerates the session after successful authentication', function () {
    $session = $this->request->session();
    $session->put('session_test', 'before');
    $originalId = $session->getId();
    $this->action->execute($this->user, $this->request);
    expect($session->getId())->not->toBe($originalId)
        ->and($session->get('session_test'))->toBe('before');
});
it('does not update login metadata when authentication is rejected', function () {
    $this->user->forceFill([
        'status' => 'inactive',
        'last_login_at' => null,
        'last_login_ip' => null,
    ])->save();
    try {
        $this->action->execute($this->user, $this->request);
    } catch (AuthenticationException) {
    }
    $this->user->refresh();
    expect($this->user->last_login_at)->toBeNull()
        ->and($this->user->last_login_ip)->toBeNull();
});
