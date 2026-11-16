<?php

use Illuminate\Support\Facades\Route;

Route::prefix('newsletter')->name('newsletter.')->group(function () {
    Route::view('/subscribe', 'pages.newsletter.subscribe')->name('subscribe');
    Route::view('/success', 'pages.newsletter.success')->name('success');
    Route::view('/unsubscribe', 'pages.newsletter.unsubscribe')->name('unsubscribe');
});
Route::prefix('notifications')->name('notifications.')->group(function () {
    Route::view('/', 'pages.notifications.index')->name('index');
    Route::view('/preferences', 'pages.notifications.preferences')->name('preferences');
});
Route::prefix('onboarding')->name('onboarding.')->group(function () {
    Route::view('/', 'pages.onboarding.index')->name('index');
    Route::view('/profile', 'pages.onboarding.profile')->name('profile');
    Route::view('/preferences', 'pages.onboarding.preferences')->name('preferences');
    Route::view('/complete', 'pages.onboarding.complete')->name('complete');
});
Route::view('/status', 'pages.status.index')->name('status.index');
