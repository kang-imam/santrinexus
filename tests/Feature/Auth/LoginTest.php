<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);
function createLoginTestUser(array $attributes = []): User
{
    return User::forceCreate(array_merge([
        'name' => 'Pengguna Test',
        'username' => 'penggunatest',
        'email' => 'pengguna@example.com',
        'phone' => '+6281234567890',
        'password' => Hash::make('password-rahasia'),
        'email_verified_at' => now(),
        'status' => 'active',
    ], $attributes));
}
function validLoginData(array $attributes = []): array
{
    return array_merge([
        'identifier' => 'pengguna@example.com',
        'password' => 'password-rahasia',
    ], $attributes);
}
test('verified user can login using email', function () {
    $user = createLoginTestUser();
    $response = $this->post(route('login.store'), validLoginData());
    $response->assertRedirect(route('account.dashboard'));
    $this->assertAuthenticatedAs($user, 'web');
    expect($user->fresh()->last_login_at)->not->toBeNull();
    expect($user->fresh()->last_login_ip)->toBe('127.0.0.1');
});
test('user can login using uppercase email', function () {
    $user = createLoginTestUser();
    $response = $this->post(route('login.store'), validLoginData(['identifier' => 'PENGGUNA@EXAMPLE.COM']));
    $response->assertRedirect(route('account.dashboard'));
    $this->assertAuthenticatedAs($user, 'web');
});
test('user can login using username', function () {
    $user = createLoginTestUser();
    $response = $this->post(route('login.store'), validLoginData(['identifier' => 'penggunatest']));
    $response->assertRedirect(route('account.dashboard'));
    $this->assertAuthenticatedAs($user, 'web');
});
test('user can login using phone number', function () {
    $user = createLoginTestUser();
    $response = $this->post(route('login.store'), validLoginData(['identifier' => '081234567890']));
    $response->assertRedirect(route('account.dashboard'));
    $this->assertAuthenticatedAs($user, 'web');
});
test('user can login using international phone number', function () {
    $user = createLoginTestUser();
    $response = $this->post(route('login.store'), validLoginData(['identifier' => '+6281234567890']));
    $response->assertRedirect(route('account.dashboard'));
    $this->assertAuthenticatedAs($user, 'web');
});
test('unverified user is authenticated but redirected to email verification', function () {
    $user = createLoginTestUser(['email_verified_at' => null]);
    $response = $this->post(route('login.store'), validLoginData());
    $response->assertRedirect(route('verification.notice'));
    $response->assertSessionHas('warning', 'Silakan verifikasi alamat email sebelum melanjutkan.');
    $this->assertAuthenticatedAs($user, 'web');
    expect($user->fresh()->hasVerifiedEmail())->toBeFalse();
});
test('verified user is redirected to account dashboard', function () {
    $user = createLoginTestUser();
    $response = $this->post(route('login.store'), validLoginData());
    $response->assertRedirect(route('account.dashboard'));
    $this->assertAuthenticatedAs($user, 'web');
});
test('login fails with incorrect password', function () {
    createLoginTestUser();
    $response = $this->post(route('login.store'), validLoginData(['password' => 'password-salah']));
    $response->assertRedirect(route('login'));
    $this->assertGuest('web');
});
test('login fails when user does not exist', function () {
    $response = $this->post(route('login.store'), validLoginData(['identifier' => 'tidakada@example.com']));
    $response->assertRedirect(route('login'));
    $this->assertGuest('web');
});
test('inactive user cannot login', function () {
    createLoginTestUser(['status' => 'inactive']);
    $response = $this->post(route('login.store'), validLoginData());
    $response->assertRedirect(route('login'));
    $this->assertGuest('web');
});
test('suspended user cannot login', function () {
    createLoginTestUser(['status' => 'suspended']);
    $response = $this->post(route('login.store'), validLoginData());
    $response->assertRedirect(route('login'));
    $this->assertGuest('web');
});
test('pending user cannot login', function () {
    createLoginTestUser(['status' => 'pending']);
    $response = $this->post(route('login.store'), validLoginData());
    $response->assertRedirect(route('login'));
    $this->assertGuest('web');
});
test('soft deleted user cannot login', function () {
    $user = createLoginTestUser();
    $user->delete();
    $response = $this->post(route('login.store'), validLoginData());
    $response->assertRedirect(route('login'));
    $this->assertGuest('web');
    $this->assertSoftDeleted('users', ['id' => $user->id]);
});
test('login updates last login information', function () {
    $user = createLoginTestUser([
        'last_login_at' => null,
        'last_login_ip' => null,
    ]);
    $this->post(route('login.store'), validLoginData());
    $user->refresh();
    expect($user->last_login_at)->not->toBeNull();
    expect($user->last_login_ip)->toBe('127.0.0.1');
});
test('login remembers user when remember me is enabled', function () {
    $user = createLoginTestUser();
    $response = $this->post(route('login.store'), validLoginData(['remember' => '1']));
    $response->assertRedirect(route('account.dashboard'));
    $this->assertAuthenticatedAs($user, 'web');
    expect($user->fresh()->getRememberToken())->not->toBeNull();
});
test('login does not remember user when remember me is disabled', function () {
    $user = createLoginTestUser();
    $this->post(route('login.store'), validLoginData(['remember' => '0']));
    expect($user->fresh()->getRememberToken())->toBeEmpty();
});
test('login rejects missing identifier', function () {
    $response = $this->post(route('login.store'), ['password' => 'password-rahasia']);
    $response->assertSessionHasErrors('identifier');
    $this->assertGuest('web');
});
test('login rejects missing password', function () {
    $response = $this->post(route('login.store'), ['identifier' => 'pengguna@example.com']);
    $response->assertSessionHasErrors('password');
    $this->assertGuest('web');
});
test('login rejects invalid email format', function () {
    $response = $this->post(route('login.store'), validLoginData(['identifier' => 'email-tidak-valid@']));
    $response->assertSessionHasErrors('identifier');
    $this->assertGuest('web');
});
test('login rejects invalid phone number', function () {
    $response = $this->post(route('login.store'), validLoginData(['identifier' => '081234']));
    $response->assertSessionHasErrors('identifier');
    $this->assertGuest('web');
});
test('login rejects invalid username characters', function () {
    $response = $this->post(route('login.store'), validLoginData(['identifier' => 'username tidak valid']));
    $response->assertSessionHasErrors('identifier');
    $this->assertGuest('web');
});
test('authenticated user cannot access login page', function () {
    $user = createLoginTestUser();
    $this->actingAs($user, 'web');
    $response = $this->get(route('login'));
    $response->assertRedirect('/');
});
