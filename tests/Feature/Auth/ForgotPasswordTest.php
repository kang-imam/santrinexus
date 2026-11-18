<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Notifications\Auth\ResetPasswordNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);
function createForgotPasswordTestUser(array $attributes = []): User
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
test('guest can access forgot password page', function () {
    $response = $this->get(route('password.request'));
    $response->assertOk();
});
test('guest can request password reset link using registered email', function () {
    Notification::fake();
    $user = createForgotPasswordTestUser();
    $response = $this->post(route('password.email'), [
        'email' => $user->email,
    ]);
    $response->assertRedirect();
    $response->assertSessionHas('status');
    Notification::assertSentTo($user, ResetPasswordNotification::class);
});
test('forgot password returns generic response for unregistered email', function () {
    Notification::fake();
    $response = $this->post(route('password.email'), [
        'email' => 'tidakada@example.com',
    ]);
    $response->assertRedirect();
    $response->assertSessionHas('status', 'Jika alamat email terdaftar, kami akan mengirimkan tautan untuk mengatur ulang password.');
    Notification::assertNothingSent();
});
test('forgot password rejects missing email', function () {
    $response = $this->post(route('password.email'), [
        'email' => '',
    ]);
    $response->assertSessionHasErrors('email');
});
test('forgot password rejects invalid email format', function () {
    $response = $this->post(route('password.email'), [
        'email' => 'email-tidak-valid',
    ]);
    $response->assertSessionHasErrors('email');
});
test('forgot password rejects email longer than 255 characters', function () {
    $response = $this->post(route('password.email'), [
        'email' => str_repeat('a', 250).'@example.com',
    ]);
    $response->assertSessionHasErrors('email');
});
test('soft deleted user cannot request password reset link', function () {
    Notification::fake();
    $user = createForgotPasswordTestUser();
    $user->delete();
    $response = $this->post(route('password.email'), [
        'email' => $user->email,
    ]);
    $response->assertRedirect();
    $response->assertSessionHas('status');
    Notification::assertNothingSent();
});
test('authenticated user cannot access forgot password page', function () {
    $user = createForgotPasswordTestUser();
    $this->actingAs($user, 'web');
    $response = $this->get(route('password.request'));
    $response->assertRedirect('/');
});
