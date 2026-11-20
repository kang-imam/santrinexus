<?php

use App\Models\MagicLinkToken;
use App\Models\User;
use App\Notifications\Auth\MagicLinkNotification;
use App\Services\Auth\MagicLinkService;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;

uses(RefreshDatabase::class);
beforeEach(function () {
    Notification::fake();
    $this->withoutMiddleware(PreventRequestForgery::class);
});
test('magic link page can be displayed', function () {
    $this->get(route('magic-link'))
        ->assertOk()
        ->assertViewIs('auth.magic-link');
});
test('registered user can request a magic link', function () {
    $user = User::factory()->create();
    $this->post(route('magic-link.send'), [
        'email' => $user->email,
    ])->assertRedirect()
        ->assertSessionHas('status');
    Notification::assertSentTo($user, MagicLinkNotification::class);
    expect(MagicLinkToken::query()->where('user_id', $user->id)->exists())->toBeTrue();
});
test('magic link token is stored as a hash', function () {
    $user = User::factory()->create();
    $this->post(route('magic-link.send'), [
        'email' => $user->email,
    ]);
    $notification = Notification::sent($user, MagicLinkNotification::class)->first();
    parse_str(parse_url($notification->url, PHP_URL_QUERY), $query);
    expect($query['token'])->toHaveLength(64);
    expect(MagicLinkToken::query()->where('token', $query['token'])->exists())->toBeFalse();
    expect(MagicLinkToken::query()->where('token', hash('sha256', $query['token']))->exists())->toBeTrue();
});
test('unknown email receives the same generic response', function () {
    $this->post(route('magic-link.send'), [
        'email' => 'unknown@example.com',
    ])->assertRedirect()
        ->assertSessionHas('status');
    Notification::assertNothingSent();
    expect(MagicLinkToken::query()->count())->toBe(0);
});
test('magic link request requires a valid email', function () {
    $this->post(route('magic-link.send'), [
        'email' => 'invalid-email',
    ])->assertSessionHasErrors('email');
});
test('magic link confirmation page can be displayed with a valid signature', function () {
    $user = User::factory()->create();
    $token = app(MagicLinkService::class)->issue($user);
    $url = URL::temporarySignedRoute(
        'magic-link.confirm',
        now()->addMinutes(15),
        ['token' => $token]
    );
    $this->get($url)
        ->assertOk()
        ->assertViewIs('auth.magic-link-confirm')
        ->assertViewHas('token', $token);
});
test('magic link confirmation rejects an invalid signature', function () {
    $token = bin2hex(random_bytes(32));
    $this->get(route('magic-link.confirm', ['token' => $token]))
        ->assertForbidden();
});
test('magic link confirmation rejects an expired signature', function () {
    $user = User::factory()->create();
    $token = app(MagicLinkService::class)->issue($user);
    $url = URL::temporarySignedRoute(
        'magic-link.confirm',
        now()->subMinute(),
        ['token' => $token]
    );
    $this->get($url)->assertForbidden();
});
test('magic link verification requires a token', function () {
    $this->post(route('magic-link.verify'), [])
        ->assertSessionHasErrors('token');
});
test('magic link verification rejects an invalid token', function () {
    $this->from(route('magic-link'))->post(route('magic-link.verify'), [
        'token' => str_repeat('a', 64),
    ])->assertRedirect(route('magic-link'))
        ->assertSessionHasErrors('token');
});
test('magic link verification rejects an expired token', function () {
    $user = User::factory()->create();
    $token = app(MagicLinkService::class)->issue($user, now()->subMinute());
    $this->from(route('magic-link'))->post(route('magic-link.verify'), [
        'token' => $token,
    ])->assertRedirect(route('magic-link'))
        ->assertSessionHasErrors('token');
    $this->assertGuest();
});
test('magic link verification rejects an already used token', function () {
    $user = User::factory()->create();
    $token = app(MagicLinkService::class)->issue($user);
    app(MagicLinkService::class)->consume($token);
    $this->from(route('magic-link'))->post(route('magic-link.verify'), [
        'token' => $token,
    ])->assertRedirect(route('magic-link'))
        ->assertSessionHasErrors('token');
    $this->assertGuest();
});
test('magic link verification rejects an inactive account', function () {
    $user = User::factory()->create();
    $token = app(MagicLinkService::class)->issue($user);
    $user->forceFill(['status' => 'inactive'])->save();
    $user->refresh();
    expect($user->status)->toBe('inactive');
    $this->from(route('magic-link'))->post(route('magic-link.verify'), [
        'token' => $token,
    ])->assertRedirect(route('magic-link'))
        ->assertSessionHasErrors('token');
    $this->assertGuest();
    expect(MagicLinkToken::query()
        ->where('user_id', $user->id)
        ->first()
        ->used_at)->not->toBeNull();
});
test('user can authenticate using a valid magic link', function () {
    $user = User::factory()->create();
    $token = app(MagicLinkService::class)->issue($user);
    $this->post(route('magic-link.verify'), [
        'token' => $token,
    ])->assertRedirect('/account');
    $this->assertAuthenticatedAs($user);
    $this->assertDatabaseHas('magic_link_tokens', [
        'user_id' => $user->id,
        'token' => hash('sha256', $token),
    ]);
    expect(MagicLinkToken::query()
        ->where('user_id', $user->id)
        ->first()
        ->used_at)->not->toBeNull();
    $user->refresh();
    expect($user->last_login_at)->not->toBeNull();
});
test('requesting a new magic link invalidates the previous token', function () {
    $user = User::factory()->create();
    $service = app(MagicLinkService::class);
    $firstToken = $service->issue($user);
    $secondToken = $service->issue($user);
    expect($firstToken)->not->toBe($secondToken);
    expect($service->consume($firstToken))->toBeNull();
    expect($service->consume($secondToken)?->is($user))->toBeTrue();
});
