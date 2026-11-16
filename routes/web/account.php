<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('account')->name('account.')->group(function () {
    Route::view('/', 'account.dashboard')->name('dashboard');
    Route::view('/profile', 'account.profile')->name('profile');
    Route::view('/profile/edit', 'account.edit-profile')->name('profile.edit');
    Route::view('/personal-information', 'account.personal-information')->name('personal-information');
    Route::view('/change-password', 'account.change-password')->name('change-password');
    Route::view('/security', 'account.security')->name('security');
    Route::view('/privacy', 'account.privacy')->name('privacy');
    Route::view('/notifications', 'account.notifications')->name('notifications');
    Route::view('/connected-accounts', 'account.connected-accounts')->name('connected-accounts');
    Route::view('/sessions', 'account.sessions')->name('sessions');
    Route::view('/activity-log', 'account.activity-log')->name('activity-log');
    Route::view('/preferences', 'account.preferences')->name('preferences');
    Route::view('/appearance', 'account.appearance')->name('appearance');
    Route::view('/language', 'account.language')->name('language');
    Route::view('/delete-account', 'account.delete-account')->name('delete-account');
    Route::view('/subscription', 'account.subscription')->name('subscription');
    Route::view('/billing', 'account.billing')->name('billing');
    Route::view('/invoices', 'account.invoices')->name('invoices');
    Route::view('/errors/pending', 'account.errors.account-pending')->name('errors.pending');
    Route::view('/errors/restricted', 'account.errors.account-restricted')->name('errors.restricted');
    Route::view('/errors/suspended', 'account.errors.account-suspended')->name('errors.suspended');
});
