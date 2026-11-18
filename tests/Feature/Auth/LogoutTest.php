<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->user = User::factory()->create();
});
it('allows authenticated user to logout', function () {
    $this->actingAs($this->user)
        ->post(route('logout'))
        ->assertRedirect(route('login'));
    $this->assertGuest();
});
it('redirects user to login page after logout', function () {
    $this->actingAs($this->user)
        ->post(route('logout'))
        ->assertRedirect(route('login'));
});
it('clears authenticated user after logout', function () {
    $this->actingAs($this->user);
    expect(Auth::check())->toBeTrue();
    $this->post(route('logout'));
    expect(Auth::check())->toBeFalse();
});
it('invalidates session after logout', function () {
    $this->actingAs($this->user);
    $session = Session::getId();
    $this->post(route('logout'));
    expect(Session::isStarted())->toBeFalse();
    expect(Auth::check())->toBeFalse();
});
it('regenerates csrf token after logout', function () {
    $this->actingAs($this->user);
    $token = Session::token();
    $this->post(route('logout'));
    expect(Session::token())->not->toBe($token);
});
it('does not allow guest to logout', function () {
    $this->post(route('logout'))
        ->assertRedirect(route('login'));
});
it('does not allow logout through get request', function () {
    $this->get(route('logout'))
        ->assertNotFound();
});
it('keeps user record after logout', function () {
    $userId = $this->user->id;
    $this->actingAs($this->user)
        ->post(route('logout'));
    $this->assertDatabaseHas('users', [
        'id' => $userId,
    ]);
});
