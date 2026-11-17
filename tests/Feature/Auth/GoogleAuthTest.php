<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\GoogleProvider;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;

uses(RefreshDatabase::class);
function mockGoogleCallback(array $profile): void
{
    $googleUser = new SocialiteUser;
    $googleUser->map($profile);
    $googleUser->user = $profile;
    $provider = Mockery::mock(GoogleProvider::class);
    $provider->shouldReceive('user')->once()->andReturn($googleUser);
    Socialite::shouldReceive('driver')->once()->with('google')->andReturn($provider);
}
function googleProfile(array $attributes = []): array
{
    return array_merge([
        'id' => 'google-123456',
        'name' => 'Pengguna Google',
        'email' => 'google@example.com',
        'avatar' => 'https://example.com/avatar.jpg',
        'verified_email' => true,
    ], $attributes);
}
test('user can login using an existing google account', function () {
    $user = User::create([
        'name' => 'Pengguna Google',
        'username' => 'pengguna123',
        'email' => 'google@example.com',
        'google_id' => 'google-123456',
        'email_verified_at' => now(),
        'password' => 'password-rahasia',
        'status' => 'active',
    ]);
    mockGoogleCallback(googleProfile());
    $response = $this->get('/auth/google/callback');
    $response->assertRedirect(route('account.dashboard'));
    $this->assertAuthenticatedAs($user, 'web');
    expect($user->fresh()->last_login_at)->not->toBeNull();
});
test('new google user can register automatically', function () {
    mockGoogleCallback(googleProfile());
    $response = $this->get('/auth/google/callback');
    $response->assertRedirect(route('account.dashboard'));
    $this->assertAuthenticated('web');
    $this->assertDatabaseHas('users', [
        'email' => 'google@example.com',
        'google_id' => 'google-123456',
        'status' => 'active',
    ]);
    $user = User::where('google_id', 'google-123456')->firstOrFail();
    expect($user->email_verified_at)->not->toBeNull();
    expect($user->username)->not->toBeEmpty();
});
test('existing verified local account can be linked with google', function () {
    $user = User::create([
        'name' => 'Pengguna Lokal',
        'username' => 'penggunalokal',
        'email' => 'google@example.com',
        'email_verified_at' => now(),
        'password' => 'password-rahasia',
        'status' => 'active',
    ]);
    mockGoogleCallback(googleProfile());
    $response = $this->get('/auth/google/callback');
    $response->assertRedirect(route('account.dashboard'));
    $this->assertAuthenticatedAs($user, 'web');
    expect($user->fresh()->google_id)->toBe('google-123456');
});
test('google login is rejected when google email is not verified', function () {
    mockGoogleCallback(googleProfile([
        'verified_email' => false,
    ]));
    $response = $this->get('/auth/google/callback');
    $response->assertRedirect(route('login'));
    $response->assertSessionHasErrors('google');
    $this->assertGuest('web');
    $this->assertDatabaseCount('users', 0);
});
test('inactive account cannot login using google', function () {
    User::create([
        'name' => 'Pengguna Nonaktif',
        'username' => 'penggunanonaktif',
        'email' => 'google@example.com',
        'google_id' => 'google-123456',
        'email_verified_at' => now(),
        'password' => 'password-rahasia',
        'status' => 'suspended',
    ]);
    mockGoogleCallback(googleProfile());
    $response = $this->get('/auth/google/callback');
    $response->assertRedirect(route('login'));
    $response->assertSessionHasErrors('google');
    $this->assertGuest('web');
});
test('soft deleted account cannot login using google', function () {
    $user = User::create([
        'name' => 'Pengguna Terhapus',
        'username' => 'penggunaterhapus',
        'email' => 'google@example.com',
        'google_id' => 'google-123456',
        'email_verified_at' => now(),
        'password' => 'password-rahasia',
        'status' => 'active',
    ]);
    $user->delete();
    mockGoogleCallback(googleProfile());
    $response = $this->get('/auth/google/callback');
    $response->assertRedirect(route('login'));
    $response->assertSessionHasErrors('google');
    $this->assertGuest('web');
    $this->assertSoftDeleted('users', ['id' => $user->id]);
});
test('google callback failure redirects to login', function () {
    $provider = Mockery::mock(GoogleProvider::class);
    $provider->shouldReceive('user')->once()->andThrow(new \RuntimeException('OAuth failed'));
    Socialite::shouldReceive('driver')->once()->with('google')->andReturn($provider);
    $response = $this->get('/auth/google/callback');
    $response->assertRedirect(route('login'));
    $response->assertSessionHasErrors('google');
    $this->assertGuest('web');
});
