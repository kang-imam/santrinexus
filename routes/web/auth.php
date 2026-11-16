<?php

use Illuminate\Support\Facades\Route;

Route::view('/login', 'pages.auth.login')->name('login');
Route::view('/register', 'pages.auth.register')->name('register');
Route::view('/forgot-password', 'pages.auth.forgot-password')->name('password.request');
Route::view('/reset-password/{token?}', 'pages.auth.reset-password')->name('password.reset');
Route::view('/confirm-password', 'pages.auth.confirm-password')->name('password.confirm');
Route::view('/verify-email', 'pages.auth.verify-email')->name('verification.notice');
Route::view('/two-factor-challenge', 'pages.auth.two-factor-challenge')->name('two-factor.login');
Route::view('/two-factor-setup', 'pages.auth.two-factor-setup')->name('two-factor.setup');
Route::view('/recovery-codes', 'pages.auth.recovery-codes')->name('recovery-codes');
Route::view('/invitation/{token?}', 'pages.auth.invitation')->name('invitation');
Route::view('/logout', 'pages.auth.logout')->name('logout');
