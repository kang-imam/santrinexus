<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->password = 'SecurePassword123!';
    $this->user = User::factory()->create([
        'password' => Hash::make($this->password),
        'email_verified_at' => now(),
        'status' => 'active',
    ]);
});
it('redirects guests to login when accessing password confirmation', function () {
    $this->get(route('password.confirm'))
        ->assertRedirect(route('login'));
});
it('allows authenticated users to view password confirmation', function () {
    $this->actingAs($this->user)
        ->get(route('password.confirm'))
        ->assertOk()
        ->assertSee('Konfirmasi Keamanan')
        ->assertSee('Konfirmasi dan Lanjutkan');
});
it('confirms password with correct credentials', function () {
    $this->actingAs($this->user)
        ->post(route('password.confirm.store'), [
            'password' => $this->password,
        ])
        ->assertRedirect(route('account.dashboard'));
    expect(session('auth.password_confirmed_at'))->not->toBeNull();
});
it('rejects an incorrect password', function () {
    $this->actingAs($this->user)
        ->from(route('password.confirm'))
        ->post(route('password.confirm.store'), [
            'password' => 'IncorrectPassword123!',
        ])
        ->assertRedirect(route('password.confirm'))
        ->assertSessionHasErrors('password');
    expect(session('auth.password_confirmed_at'))->toBeNull();
});
it('rejects an empty password', function () {
    $this->actingAs($this->user)
        ->from(route('password.confirm'))
        ->post(route('password.confirm.store'), [
            'password' => '',
        ])
        ->assertRedirect(route('password.confirm'))
        ->assertSessionHasErrors('password');
});
it('redirects users to password confirmation when accessing protected pages', function () {
    $this->actingAs($this->user)
        ->get(route('account.security'))
        ->assertRedirect(route('password.confirm'));
});
it('allows users to access protected pages after password confirmation', function () {
    $this->actingAs($this->user)
        ->withSession([
            'auth.password_confirmed_at' => now()->timestamp,
        ])
        ->get(route('account.security'))
        ->assertOk();
});
it('requires password confirmation again after timeout', function () {
    $this->actingAs($this->user)
        ->withSession([
            'auth.password_confirmed_at' => now()->subSeconds(config('auth.password_timeout') + 1)->timestamp,
        ])
        ->get(route('account.security'))
        ->assertRedirect(route('password.confirm'));
});
it('allows users to access public account pages without password confirmation', function () {
    $this->actingAs($this->user)
        ->get(route('account.dashboard'))
        ->assertOk();
});
it('prevents unverified users from accessing account pages', function () {
    $this->user->forceFill([
        'email_verified_at' => null,
    ])->save();
    $this->actingAs($this->user)
        ->get(route('account.dashboard'))
        ->assertRedirect(route('verification.notice'));
});
