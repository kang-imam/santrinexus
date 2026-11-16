<?php

use Illuminate\Support\Facades\Route;

Route::prefix('account')->name('account.')->group(function () {
    Route::view('/', 'pages.account.index')->name('index');
    Route::view('/profile', 'pages.account.profile')->name('profile');
    Route::view('/security', 'pages.account.security')->name('security');
    Route::view('/settings', 'pages.account.settings')->name('settings');
    Route::view('/notifications', 'pages.account.notifications')->name('notifications');
    Route::view('/activity', 'pages.account.activity')->name('activity');
    Route::view('/sessions', 'pages.account.sessions')->name('sessions');
    Route::view('/connected-accounts', 'pages.account.connected-accounts')->name('connected-accounts');
    Route::view('/billing', 'pages.account.billing')->name('billing');
    Route::view('/subscriptions', 'pages.account.subscriptions')->name('subscriptions');
    Route::view('/invoices', 'pages.account.invoices')->name('invoices');
    Route::view('/privacy', 'pages.account.privacy')->name('privacy');
    Route::view('/preferences', 'pages.account.preferences')->name('preferences');
    Route::view('/support', 'pages.account.support')->name('support');
    Route::view('/delete-account', 'pages.account.delete-account')->name('delete-account');
});
