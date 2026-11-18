<?php

use App\Http\Controllers\Web\Auth\ForgotPasswordController;
use App\Http\Controllers\Web\Auth\GoogleAuthController;
use App\Http\Controllers\Web\Auth\LoginController;
use App\Http\Controllers\Web\Auth\LogoutController;
use App\Http\Controllers\Web\Auth\RegisterController;
use App\Http\Controllers\Web\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::controller(LoginController::class)->group(function () {
        Route::view('/login', 'auth.login')->name('login');
        Route::post('/login', 'store')->name('login.store');
    });
    Route::controller(RegisterController::class)->group(function () {
        Route::view('/register', 'auth.register')->name('register');
        Route::post('/register', 'store')->name('register.store');
    });
    Route::controller(ForgotPasswordController::class)->group(function () {
        Route::view('/forgot-password', 'auth.forgot-password')->name('password.request');
        Route::post('/forgot-password', '__invoke')->name('password.email');
    });
    Route::controller(ResetPasswordController::class)->group(function () {
        Route::view('/reset-password/{token}', 'auth.reset-password')->name('password.reset');
        Route::post('/reset-password', '__invoke')->name('password.update');
    });
    Route::view('/magic-link', 'auth.magic-link')->name('magic-link');
    Route::view('/accept-invitation/{token}', 'auth.accept-invitation')->name('invitation.accept');
    Route::prefix('auth/google')->name('auth.google.')->controller(GoogleAuthController::class)->group(function () {
        Route::get('/', 'redirect')->name('redirect');
        Route::get('/callback', 'callback')->name('callback');
    });
});
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LogoutController::class, 'destroy'])->name('logout');
    Route::view('/verify-email', 'auth.verify-email')->name('verification.notice');
    Route::view('/confirm-password', 'auth.confirm-password')->name('password.confirm');
    Route::view('/two-factor-challenge', 'auth.two-factor-challenge')->name('two-factor.login');
    Route::view('/verify-two-factor', 'auth.verify-two-factor')->name('two-factor.verify');
    Route::view('/account-disabled', 'auth.errors.account-disabled')->name('account.disabled');
    Route::view('/account-locked', 'auth.errors.account-locked')->name('account.locked');
    Route::view('/email-not-verified', 'auth.errors.email-not-verified')->name('account.email-not-verified');
});
