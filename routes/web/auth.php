<?php

use App\Http\Controllers\Web\Auth\ForgotPasswordController;
use App\Http\Controllers\Web\Auth\GoogleAuthController;
use App\Http\Controllers\Web\Auth\LoginController;
use App\Http\Controllers\Web\Auth\LogoutController;
use App\Http\Controllers\Web\Auth\MagicLinkController;
use App\Http\Controllers\Web\Auth\RegisterController;
use App\Http\Controllers\Web\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::controller(LoginController::class)->group(function () {
        Route::view('/login', 'auth.login')->name('login');
        Route::post('/login', 'store')->middleware('throttle:5,1')->name('login.store');
    });
    Route::controller(RegisterController::class)->group(function () {
        Route::view('/register', 'auth.register')->name('register');
        Route::post('/register', 'store')->middleware('throttle:3,1')->name('register.store');
    });
    Route::controller(ForgotPasswordController::class)->group(function () {
        Route::view('/forgot-password', 'auth.forgot-password')->name('password.request');
        Route::post('/forgot-password', '__invoke')->middleware('throttle:5,1')->name('password.email');
    });
    Route::controller(ResetPasswordController::class)->group(function () {
        Route::view('/reset-password/{token}', 'auth.reset-password')->name('password.reset');
        Route::post('/reset-password', '__invoke')->middleware('throttle:5,1')->name('password.update');
    });
    Route::controller(MagicLinkController::class)->group(function () {
        Route::get('/magic-link', 'create')->name('magic-link');
        Route::post('/magic-link', 'send')->middleware('throttle:5,1')->name('magic-link.send');
        Route::get('/magic-link/confirm', 'confirm')->middleware(['signed', 'throttle:10,1'])->name('magic-link.confirm');
        Route::post('/magic-link/verify', 'verify')->middleware('throttle:5,1')->name('magic-link.verify');
    });
    Route::view('/accept-invitation/{token}', 'auth.accept-invitation')->name('invitation.accept');
    Route::prefix('auth/google')->name('auth.google.')->controller(GoogleAuthController::class)->group(function () {
        Route::get('/', 'redirect')->middleware('throttle:10,1')->name('redirect');
        Route::get('/callback', 'callback')->middleware('throttle:10,1')->name('callback');
    });
});
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LogoutController::class, 'destroy'])->middleware('throttle:5,1')->name('logout');
    Route::view('/verify-email', 'auth.verify-email')->name('verification.notice');
    Route::view('/confirm-password', 'auth.confirm-password')->name('password.confirm');
    Route::view('/two-factor-challenge', 'auth.two-factor-challenge')->name('two-factor.login');
    Route::view('/verify-two-factor', 'auth.verify-two-factor')->name('two-factor.verify');
    Route::view('/account-disabled', 'auth.errors.account-disabled')->name('account.disabled');
    Route::view('/account-locked', 'auth.errors.account-locked')->name('account.locked');
    Route::view('/email-not-verified', 'auth.errors.email-not-verified')->name('account.email-not-verified');
});
