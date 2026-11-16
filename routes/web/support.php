<?php

use Illuminate\Support\Facades\Route;

Route::prefix('faq')->name('faq.')->group(function () {
    Route::view('/', 'pages.faq.index')->name('index');
    Route::view('/category/{slug}', 'pages.faq.category')->name('category');
});
Route::prefix('support')->name('support.')->group(function () {
    Route::view('/', 'pages.support.index')->name('index');
    Route::view('/search', 'pages.support.search')->name('search');
    Route::view('/knowledge-base', 'pages.support.knowledge-base')->name('knowledge-base');
    Route::view('/tickets/create', 'pages.support.create-ticket')->name('tickets.create');
    Route::view('/tickets/{id}', 'pages.support.show-ticket')->name('tickets.show');
});
