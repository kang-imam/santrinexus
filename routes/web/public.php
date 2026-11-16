<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.home.index')->name('home');
Route::view('/about', 'pages.about.index')->name('about');
Route::view('/contact', 'pages.contact.index')->name('contact');
Route::view('/contact/success', 'pages.contact.success')->name('contact.success');
Route::view('/cookies/preferences', 'pages.cookies.preferences')->name('cookies.preferences');
Route::view('/pages/{slug}', 'pages.pages.show')->name('pages.show');
