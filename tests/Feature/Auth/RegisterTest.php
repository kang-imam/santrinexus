<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);
function createRegisterTestUser(array $attributes = []): User
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
function validRegisterData(array $attributes = []): array
{
    return array_merge([
        'name' => 'Pengguna Baru',
        'username' => 'penggunabaru',
        'email' => 'penggunabaru@example.com',
        'phone' => '081234567891',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ], $attributes);
}
test('guest can register using valid data', function () {
    Notification::fake();
    $response = $this->post(route('register.store'), validRegisterData());
    $response->assertRedirect(route('verification.notice'));
    $response->assertSessionHas('success', 'Registrasi berhasil. Silakan periksa email untuk memverifikasi akun.');
    $this->assertDatabaseHas('users', [
        'name' => 'Pengguna Baru',
        'username' => 'penggunabaru',
        'email' => 'penggunabaru@example.com',
        'phone' => '+6281234567891',
        'status' => 'active',
        'email_verified_at' => null,
    ]);
    $this->assertAuthenticatedAs(User::where('email', 'penggunabaru@example.com')->firstOrFail(), 'web');
    Notification::assertSentTo(User::where('email', 'penggunabaru@example.com')->firstOrFail(), VerifyEmail::class);
});
test('registration normalizes name username and email', function () {
    Notification::fake();
    $response = $this->post(route('register.store'), validRegisterData([
        'name' => '  Pengguna Baru  ',
        'username' => '  pengguna_baru  ',
        'email' => '  PENGGUNABARU@EXAMPLE.COM  ',
    ]));
    $response->assertRedirect(route('verification.notice'));
    $this->assertDatabaseHas('users', [
        'name' => 'Pengguna Baru',
        'username' => 'pengguna_baru',
        'email' => 'penggunabaru@example.com',
    ]);
});
test('registration allows empty username', function () {
    Notification::fake();
    $response = $this->post(route('register.store'), validRegisterData(['username' => '']));
    $response->assertRedirect(route('verification.notice'));
    $this->assertDatabaseHas('users', [
        'email' => 'penggunabaru@example.com',
        'username' => null,
    ]);
});
test('registration allows null phone number', function () {
    Notification::fake();
    $response = $this->post(route('register.store'), validRegisterData(['phone' => '']));
    $response->assertRedirect(route('verification.notice'));
    $this->assertDatabaseHas('users', [
        'email' => 'penggunabaru@example.com',
        'phone' => null,
    ]);
});
test('registration normalizes local phone number to international format', function () {
    Notification::fake();
    $response = $this->post(route('register.store'), validRegisterData(['phone' => '081234567891']));
    $response->assertRedirect(route('verification.notice'));
    $this->assertDatabaseHas('users', [
        'email' => 'penggunabaru@example.com',
        'phone' => '+6281234567891',
    ]);
});
test('registration rejects duplicate email', function () {
    Notification::fake();
    createRegisterTestUser();
    $response = $this->post(route('register.store'), validRegisterData(['email' => 'pengguna@example.com']));
    $response->assertSessionHasErrors('email');
    $this->assertDatabaseCount('users', 1);
    $this->assertGuest('web');
    Notification::assertNothingSent();
});
test('registration rejects duplicate username', function () {
    Notification::fake();
    createRegisterTestUser();
    $response = $this->post(route('register.store'), validRegisterData(['username' => 'penggunatest']));
    $response->assertSessionHasErrors('username');
    $this->assertDatabaseCount('users', 1);
    $this->assertGuest('web');
    Notification::assertNothingSent();
});
test('registration rejects duplicate phone number', function () {
    Notification::fake();
    createRegisterTestUser();
    $response = $this->post(route('register.store'), validRegisterData(['phone' => '081234567890']));
    $response->assertSessionHasErrors('phone');
    $this->assertDatabaseCount('users', 1);
    $this->assertGuest('web');
    Notification::assertNothingSent();
});
test('registration rejects invalid phone number', function () {
    Notification::fake();
    $response = $this->post(route('register.store'), validRegisterData(['phone' => '081234']));
    $response->assertSessionHasErrors('phone');
    $this->assertDatabaseCount('users', 0);
    Notification::assertNothingSent();
});
test('registration rejects invalid email format', function () {
    Notification::fake();
    $response = $this->post(route('register.store'), validRegisterData(['email' => 'email-tidak-valid']));
    $response->assertSessionHasErrors('email');
    $this->assertDatabaseCount('users', 0);
    Notification::assertNothingSent();
});
test('registration rejects missing name', function () {
    Notification::fake();
    $response = $this->post(route('register.store'), validRegisterData(['name' => '']));
    $response->assertSessionHasErrors('name');
    $this->assertDatabaseCount('users', 0);
    Notification::assertNothingSent();
});
test('registration rejects name shorter than two characters', function () {
    Notification::fake();
    $response = $this->post(route('register.store'), validRegisterData(['name' => 'A']));
    $response->assertSessionHasErrors('name');
    $this->assertDatabaseCount('users', 0);
    Notification::assertNothingSent();
});
test('registration rejects missing email', function () {
    Notification::fake();
    $response = $this->post(route('register.store'), validRegisterData(['email' => '']));
    $response->assertSessionHasErrors('email');
    $this->assertDatabaseCount('users', 0);
    Notification::assertNothingSent();
});
test('registration rejects username shorter than three characters', function () {
    Notification::fake();
    $response = $this->post(route('register.store'), validRegisterData(['username' => 'ab']));
    $response->assertSessionHasErrors('username');
    $this->assertDatabaseCount('users', 0);
    Notification::assertNothingSent();
});
test('registration rejects username with invalid characters', function () {
    Notification::fake();
    $response = $this->post(route('register.store'), validRegisterData(['username' => 'pengguna baru!']));
    $response->assertSessionHasErrors('username');
    $this->assertDatabaseCount('users', 0);
    Notification::assertNothingSent();
});
test('registration rejects password shorter than eight characters', function () {
    Notification::fake();
    $response = $this->post(route('register.store'), validRegisterData([
        'password' => 'pass123',
        'password_confirmation' => 'pass123',
    ]));
    $response->assertSessionHasErrors('password');
    $this->assertDatabaseCount('users', 0);
    Notification::assertNothingSent();
});
test('registration rejects password without letters', function () {
    Notification::fake();
    $response = $this->post(route('register.store'), validRegisterData([
        'password' => '12345678',
        'password_confirmation' => '12345678',
    ]));
    $response->assertSessionHasErrors('password');
    $this->assertDatabaseCount('users', 0);
    Notification::assertNothingSent();
});
test('registration rejects password without numbers', function () {
    Notification::fake();
    $response = $this->post(route('register.store'), validRegisterData([
        'password' => 'passwordonly',
        'password_confirmation' => 'passwordonly',
    ]));
    $response->assertSessionHasErrors('password');
    $this->assertDatabaseCount('users', 0);
    Notification::assertNothingSent();
});
test('registration rejects mismatched password confirmation', function () {
    Notification::fake();
    $response = $this->post(route('register.store'), validRegisterData(['password_confirmation' => 'password456']));
    $response->assertSessionHasErrors('password');
    $this->assertDatabaseCount('users', 0);
    Notification::assertNothingSent();
});
test('registration rejects missing password confirmation', function () {
    Notification::fake();
    $response = $this->post(route('register.store'), validRegisterData(['password_confirmation' => '']));
    $response->assertSessionHasErrors('password_confirmation');
    $this->assertDatabaseCount('users', 0);
    Notification::assertNothingSent();
});
test('registration stores password as hash', function () {
    Notification::fake();
    $this->post(route('register.store'), validRegisterData());
    $user = User::where('email', 'penggunabaru@example.com')->firstOrFail();
    expect(Hash::check('password123', $user->password))->toBeTrue();
    expect($user->password)->not->toBe('password123');
});
test('registration automatically authenticates user', function () {
    Notification::fake();
    $response = $this->post(route('register.store'), validRegisterData());
    $response->assertRedirect(route('verification.notice'));
    $this->assertAuthenticated('web');
});
test('registration sends email verification notification', function () {
    Notification::fake();
    $this->post(route('register.store'), validRegisterData());
    $user = User::where('email', 'penggunabaru@example.com')->firstOrFail();
    Notification::assertSentTo($user, VerifyEmail::class);
});
test('registration leaves email unverified', function () {
    Notification::fake();
    $this->post(route('register.store'), validRegisterData());
    $user = User::where('email', 'penggunabaru@example.com')->firstOrFail();
    expect($user->email_verified_at)->toBeNull();
    expect($user->hasVerifiedEmail())->toBeFalse();
});
test('authenticated user cannot access registration page', function () {
    $user = createRegisterTestUser();
    $this->actingAs($user, 'web');
    $response = $this->get(route('register'));
    $response->assertRedirect('/');
});
