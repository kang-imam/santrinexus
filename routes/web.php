<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.home.index')->name('home');
require __DIR__.'/web/auth.php';
require __DIR__.'/web/public.php';
require __DIR__.'/web/account.php';
