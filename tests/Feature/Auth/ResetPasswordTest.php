<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

uses(RefreshDatabase::class);
function createResetPasswordTestUser(array $attributes = []): User
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
function createResetPasswordToken(User $user): string
{
    return Password::broker()->createToken($user);
}
test('guest can access reset password page using token', function () {
    $user = createResetPasswordTestUser();
    $token = createResetPasswordToken($user);
    $response = $this->get(route('password.reset', [
        'token' => $token,
        'email' => $user->email,
    ]));
    $response->assertOk();
});
test('user can reset password using valid token', function () {
    $user = createResetPasswordTestUser();
    $token = createResetPasswordToken($user);
    $response = $this->post(route('password.update'), [
        'token' => $token,
        'email' => $user->email,
        'password' => 'password-baru123',
        'password_confirmation' => 'password-baru123',
    ]);
    $response->assertRedirect(route('login'));
    $response->assertSessionHas('status', 'Password berhasil diperbarui. Silakan login menggunakan password baru.');
    expect(Hash::check('password-baru123', $user->fresh()->password))->toBeTrue();
    $this->assertGuest('web');
});
test('password reset rotates remember token', function () {
    $user = createResetPasswordTestUser([
        'remember_token' => 'remember-token-lama',
    ]);
    $token = createResetPasswordToken($user);
    $this->post(route('password.update'), [
        'token' => $token,
        'email' => $user->email,
        'password' => 'password-baru123',
        'password_confirmation' => 'password-baru123',
    ]);
    expect($user->fresh()->getRememberToken())->not->toBe('remember-token-lama');
});
test('password reset token cannot be reused', function () {
    $user = createResetPasswordTestUser();
    $token = createResetPasswordToken($user);
    $this->post(route('password.update'), [
        'token' => $token,
        'email' => $user->email,
        'password' => 'password-baru123',
        'password_confirmation' => 'password-baru123',
    ]);
    $response = $this->post(route('password.update'), [
        'token' => $token,
        'email' => $user->email,
        'password' => 'password-baru456',
        'password_confirmation' => 'password-baru456',
    ]);
    $response->assertSessionHasErrors('email');
    expect(Hash::check('password-baru123', $user->fresh()->password))->toBeTrue();
});
test('password reset rejects invalid token', function () {
    $user = createResetPasswordTestUser();
    $response = $this->post(route('password.update'), [
        'token' => 'token-tidak-valid',
        'email' => $user->email,
        'password' => 'password-baru123',
        'password_confirmation' => 'password-baru123',
    ]);
    $response->assertSessionHasErrors('email');
    expect(Hash::check('password-rahasia', $user->fresh()->password))->toBeTrue();
});
test('password reset rejects email that does not match token', function () {
    $user = createResetPasswordTestUser();
    $otherUser = createResetPasswordTestUser([
        'username' => 'penggunakedua',
        'email' => 'kedua@example.com',
        'phone' => '+6281234567891',
    ]);
    $token = createResetPasswordToken($user);
    $response = $this->post(route('password.update'), [
        'token' => $token,
        'email' => $otherUser->email,
        'password' => 'password-baru123',
        'password_confirmation' => 'password-baru123',
    ]);
    $response->assertSessionHasErrors('email');
    expect(Hash::check('password-rahasia', $user->fresh()->password))->toBeTrue();
    expect(Hash::check('password-rahasia', $otherUser->fresh()->password))->toBeTrue();
});
test('password reset rejects missing token', function () {
    $user = createResetPasswordTestUser();
    $response = $this->post(route('password.update'), [
        'email' => $user->email,
        'password' => 'password-baru123',
        'password_confirmation' => 'password-baru123',
    ]);
    $response->assertSessionHasErrors('token');
});
test('password reset rejects missing email', function () {
    $response = $this->post(route('password.update'), [
        'token' => 'token',
        'password' => 'password-baru123',
        'password_confirmation' => 'password-baru123',
    ]);
    $response->assertSessionHasErrors('email');
});
test('password reset rejects invalid email format', function () {
    $response = $this->post(route('password.update'), [
        'token' => 'token',
        'email' => 'email-tidak-valid',
        'password' => 'password-baru123',
        'password_confirmation' => 'password-baru123',
    ]);
    $response->assertSessionHasErrors('email');
});
test('password reset rejects password shorter than eight characters', function () {
    $user = createResetPasswordTestUser();
    $token = createResetPasswordToken($user);
    $response = $this->post(route('password.update'), [
        'token' => $token,
        'email' => $user->email,
        'password' => 'pass123',
        'password_confirmation' => 'pass123',
    ]);
    $response->assertSessionHasErrors('password');
});
test('password reset rejects mismatched password confirmation', function () {
    $user = createResetPasswordTestUser();
    $token = createResetPasswordToken($user);
    $response = $this->post(route('password.update'), [
        'token' => $token,
        'email' => $user->email,
        'password' => 'password-baru123',
        'password_confirmation' => 'password-baru456',
    ]);
    $response->assertSessionHasErrors('password');
});
test('password reset rejects missing password confirmation', function () {
    $user = createResetPasswordTestUser();
    $token = createResetPasswordToken($user);
    $response = $this->post(route('password.update'), [
        'token' => $token,
        'email' => $user->email,
        'password' => 'password-baru123',
    ]);
    $response->assertSessionHasErrors('password');
});
test('soft deleted user cannot reset password', function () {
    $user = createResetPasswordTestUser();
    $token = createResetPasswordToken($user);
    $user->delete();
    $response = $this->post(route('password.update'), [
        'token' => $token,
        'email' => $user->email,
        'password' => 'password-baru123',
        'password_confirmation' => 'password-baru123',
    ]);
    $response->assertSessionHasErrors('email');
});
