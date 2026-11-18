<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);
function createLoginTestUser(array $attributes = []): User
{
    return User::forceCreate(array_merge([
        'name' => 'Pengguna Test',
        'username' => 'penggunatest',
        'email' => 'pengguna@example.com',
        'phone' => '+6281234567890',
        'password' => 'password-rahasia',
        'status' => 'active',
    ], $attributes));
}
test('user can login using email', function () {
    $user = createLoginTestUser();
    $response = $this->post(route('login.store'), [
        'identifier' => 'pengguna@example.com',
        'password' => 'password-rahasia',
    ]);
    $response->assertRedirect(route('account.dashboard'));
    $this->assertAuthenticatedAs($user, 'web');
    expect($user->fresh()->last_login_at)->not->toBeNull();
    expect($user->fresh()->last_login_ip)->toBe('127.0.0.1');
});
test('user can login using uppercase email', function () {
    $user = createLoginTestUser();
    $response = $this->post(route('login.store'), [
        'identifier' => 'PENGGUNA@EXAMPLE.COM',
        'password' => 'password-rahasia',
    ]);
    $response->assertRedirect(route('account.dashboard'));
    $this->assertAuthenticatedAs($user, 'web');
});
test('user can login using username', function () {
    $user = createLoginTestUser();
    $response = $this->post(route('login.store'), [
        'identifier' => 'penggunatest',
        'password' => 'password-rahasia',
    ]);
    $response->assertRedirect(route('account.dashboard'));
    $this->assertAuthenticatedAs($user, 'web');
});
test('user can login using phone number', function () {
    $user = createLoginTestUser();
    $response = $this->post(route('login.store'), [
        'identifier' => '081234567890',
        'password' => 'password-rahasia',
    ]);
    $response->assertRedirect(route('account.dashboard'));
    $this->assertAuthenticatedAs($user, 'web');
});
test('user can login using international phone number', function () {
    $user = createLoginTestUser();
    $response = $this->post(route('login.store'), [
        'identifier' => '+6281234567890',
        'password' => 'password-rahasia',
    ]);
    $response->assertRedirect(route('account.dashboard'));
    $this->assertAuthenticatedAs($user, 'web');
});
test('login fails with incorrect password', function () {
    createLoginTestUser();
    $response = $this->post(route('login.store'), [
        'identifier' => 'pengguna@example.com',
        'password' => 'password-salah',
    ]);
    $response->assertRedirect(route('login'));
    $this->assertGuest('web');
});
test('login fails when user does not exist', function () {
    $response = $this->post(route('login.store'), [
        'identifier' => 'tidakada@example.com',
        'password' => 'password-rahasia',
    ]);
    $response->assertRedirect(route('login'));
    $this->assertGuest('web');
});
test('inactive user cannot login', function () {
    createLoginTestUser(['status' => 'inactive']);
    $response = $this->post(route('login.store'), [
        'identifier' => 'pengguna@example.com',
        'password' => 'password-rahasia',
    ]);
    $response->assertRedirect(route('login'));
    $this->assertGuest('web');
});
test('suspended user cannot login', function () {
    createLoginTestUser(['status' => 'suspended']);
    $response = $this->post(route('login.store'), [
        'identifier' => 'pengguna@example.com',
        'password' => 'password-rahasia',
    ]);
    $response->assertRedirect(route('login'));
    $this->assertGuest('web');
});
test('pending user cannot login', function () {
    createLoginTestUser(['status' => 'pending']);
    $response = $this->post(route('login.store'), [
        'identifier' => 'pengguna@example.com',
        'password' => 'password-rahasia',
    ]);
    $response->assertRedirect(route('login'));
    $this->assertGuest('web');
});
test('soft deleted user cannot login', function () {
    $user = createLoginTestUser();
    $user->delete();
    $response = $this->post(route('login.store'), [
        'identifier' => 'pengguna@example.com',
        'password' => 'password-rahasia',
    ]);
    $response->assertRedirect(route('login'));
    $this->assertGuest('web');
    $this->assertSoftDeleted('users', ['id' => $user->id]);
});
test('login updates last login information', function () {
    $user = createLoginTestUser();
    $this->post(route('login.store'), [
        'identifier' => 'pengguna@example.com',
        'password' => 'password-rahasia',
    ]);
    $user->refresh();
    expect($user->last_login_at)->not->toBeNull();
    expect($user->last_login_ip)->toBe('127.0.0.1');
});
test('login remembers user when remember me is enabled', function () {
    $user = createLoginTestUser();
    $response = $this->post(route('login.store'), [
        'identifier' => 'pengguna@example.com',
        'password' => 'password-rahasia',
        'remember' => '1',
    ]);
    $response->assertRedirect(route('account.dashboard'));
    $this->assertAuthenticatedAs($user, 'web');
    expect($user->fresh()->getRememberToken())->not->toBeNull();
});
test('login rejects missing identifier', function () {
    $response = $this->post(route('login.store'), [
        'password' => 'password-rahasia',
    ]);
    $response->assertSessionHasErrors('identifier');
    $this->assertGuest('web');
});
test('login rejects missing password', function () {
    $response = $this->post(route('login.store'), [
        'identifier' => 'pengguna@example.com',
    ]);
    $response->assertSessionHasErrors('password');
    $this->assertGuest('web');
});
test('login rejects invalid email format', function () {
    $response = $this->post(route('login.store'), [
        'identifier' => 'email-tidak-valid@',
        'password' => 'password-rahasia',
    ]);
    $response->assertSessionHasErrors('identifier');
    $this->assertGuest('web');
});
test('login rejects invalid phone number', function () {
    $response = $this->post(route('login.store'), [
        'identifier' => '081234',
        'password' => 'password-rahasia',
    ]);
    $response->assertSessionHasErrors('identifier');
    $this->assertGuest('web');
});
test('login rejects invalid username characters', function () {
    $response = $this->post(route('login.store'), [
        'identifier' => 'username tidak valid',
        'password' => 'password-rahasia',
    ]);
    $response->assertSessionHasErrors('identifier');
    $this->assertGuest('web');
});
test('authenticated user cannot access login page', function () {
    $user = createLoginTestUser();
    $this->actingAs($user, 'web');
    $response = $this->get(route('login'));
    $response->assertRedirect('/');
});
