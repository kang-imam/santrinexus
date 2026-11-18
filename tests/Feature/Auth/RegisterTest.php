<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

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
    $response = $this->post(route('register.store'), validRegisterData());
    $response->assertRedirect(route('login'));
    $response->assertSessionHas('success', 'Registrasi berhasil. Silakan login.');
    $this->assertDatabaseHas('users', [
        'name' => 'Pengguna Baru',
        'username' => 'penggunabaru',
        'email' => 'penggunabaru@example.com',
        'phone' => '+6281234567891',
        'status' => 'active',
    ]);
    $this->assertGuest('web');
});
test('registration normalizes name username and email', function () {
    $response = $this->post(route('register.store'), validRegisterData([
        'name' => '  Pengguna Baru  ',
        'username' => '  pengguna_baru  ',
        'email' => '  PENGGUNABARU@EXAMPLE.COM  ',
    ]));
    $response->assertRedirect(route('login'));
    $this->assertDatabaseHas('users', [
        'name' => 'Pengguna Baru',
        'username' => 'pengguna_baru',
        'email' => 'penggunabaru@example.com',
    ]);
});
test('registration allows empty username', function () {
    $response = $this->post(route('register.store'), validRegisterData([
        'username' => '',
    ]));
    $response->assertRedirect(route('login'));
    $this->assertDatabaseHas('users', [
        'email' => 'penggunabaru@example.com',
        'username' => null,
    ]);
});
test('registration allows null phone number', function () {
    $response = $this->post(route('register.store'), validRegisterData([
        'phone' => '',
    ]));
    $response->assertRedirect(route('login'));
    $this->assertDatabaseHas('users', [
        'email' => 'penggunabaru@example.com',
        'phone' => null,
    ]);
});
test('registration normalizes local phone number to international format', function () {
    $response = $this->post(route('register.store'), validRegisterData([
        'phone' => '081234567891',
    ]));
    $response->assertRedirect(route('login'));
    $this->assertDatabaseHas('users', [
        'email' => 'penggunabaru@example.com',
        'phone' => '+6281234567891',
    ]);
});
test('registration rejects duplicate email', function () {
    createRegisterTestUser();
    $response = $this->post(route('register.store'), validRegisterData([
        'email' => 'pengguna@example.com',
    ]));
    $response->assertSessionHasErrors('email');
    $this->assertDatabaseCount('users', 1);
});
test('registration rejects duplicate username', function () {
    createRegisterTestUser();
    $response = $this->post(route('register.store'), validRegisterData([
        'username' => 'penggunatest',
    ]));
    $response->assertSessionHasErrors('username');
    $this->assertDatabaseCount('users', 1);
});
test('registration rejects duplicate phone number', function () {
    createRegisterTestUser();
    $response = $this->post(route('register.store'), validRegisterData([
        'phone' => '081234567890',
    ]));
    $response->assertSessionHasErrors('phone');
    $this->assertDatabaseCount('users', 1);
});
test('registration rejects invalid phone number', function () {
    $response = $this->post(route('register.store'), validRegisterData([
        'phone' => '081234',
    ]));
    $response->assertSessionHasErrors('phone');
    $this->assertDatabaseCount('users', 0);
});
test('registration rejects invalid email format', function () {
    $response = $this->post(route('register.store'), validRegisterData([
        'email' => 'email-tidak-valid',
    ]));
    $response->assertSessionHasErrors('email');
    $this->assertDatabaseCount('users', 0);
});
test('registration rejects missing name', function () {
    $response = $this->post(route('register.store'), validRegisterData([
        'name' => '',
    ]));
    $response->assertSessionHasErrors('name');
    $this->assertDatabaseCount('users', 0);
});
test('registration rejects name shorter than two characters', function () {
    $response = $this->post(route('register.store'), validRegisterData([
        'name' => 'A',
    ]));
    $response->assertSessionHasErrors('name');
    $this->assertDatabaseCount('users', 0);
});
test('registration rejects missing email', function () {
    $response = $this->post(route('register.store'), validRegisterData([
        'email' => '',
    ]));
    $response->assertSessionHasErrors('email');
    $this->assertDatabaseCount('users', 0);
});
test('registration rejects username shorter than three characters', function () {
    $response = $this->post(route('register.store'), validRegisterData([
        'username' => 'ab',
    ]));
    $response->assertSessionHasErrors('username');
    $this->assertDatabaseCount('users', 0);
});
test('registration rejects username with invalid characters', function () {
    $response = $this->post(route('register.store'), validRegisterData([
        'username' => 'pengguna baru!',
    ]));
    $response->assertSessionHasErrors('username');
    $this->assertDatabaseCount('users', 0);
});
test('registration rejects password shorter than eight characters', function () {
    $response = $this->post(route('register.store'), validRegisterData([
        'password' => 'pass123',
        'password_confirmation' => 'pass123',
    ]));
    $response->assertSessionHasErrors('password');
    $this->assertDatabaseCount('users', 0);
});
test('registration rejects password without letters', function () {
    $response = $this->post(route('register.store'), validRegisterData([
        'password' => '12345678',
        'password_confirmation' => '12345678',
    ]));
    $response->assertSessionHasErrors('password');
    $this->assertDatabaseCount('users', 0);
});
test('registration rejects password without numbers', function () {
    $response = $this->post(route('register.store'), validRegisterData([
        'password' => 'passwordonly',
        'password_confirmation' => 'passwordonly',
    ]));
    $response->assertSessionHasErrors('password');
    $this->assertDatabaseCount('users', 0);
});
test('registration rejects mismatched password confirmation', function () {
    $response = $this->post(route('register.store'), validRegisterData([
        'password_confirmation' => 'password456',
    ]));
    $response->assertSessionHasErrors('password');
    $this->assertDatabaseCount('users', 0);
});
test('registration rejects missing password confirmation', function () {
    $response = $this->post(route('register.store'), validRegisterData([
        'password_confirmation' => '',
    ]));
    $response->assertSessionHasErrors('password_confirmation');
    $this->assertDatabaseCount('users', 0);
});
test('registration stores password as hash', function () {
    $this->post(route('register.store'), validRegisterData());
    $user = User::where('email', 'penggunabaru@example.com')->firstOrFail();
    expect(Hash::check('password123', $user->password))->toBeTrue();
    expect($user->password)->not->toBe('password123');
});
test('registration does not automatically authenticate user', function () {
    $response = $this->post(route('register.store'), validRegisterData());
    $response->assertRedirect(route('login'));
    $this->assertGuest('web');
});
test('authenticated user cannot access registration page', function () {
    $user = createRegisterTestUser();
    $this->actingAs($user, 'web');
    $response = $this->get(route('register'));
    $response->assertRedirect('/');
});
