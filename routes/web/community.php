<?php

use Illuminate\Support\Facades\Route;

Route::prefix('careers')->name('careers.')->group(function () {
    Route::view('/', 'pages.careers.index')->name('index');
    Route::view('/application', 'pages.careers.application')->name('application');
    Route::view('/{slug}', 'pages.careers.show')->name('show');
});
Route::prefix('team')->name('team.')->group(function () {
    Route::view('/', 'pages.team.index')->name('index');
    Route::view('/{slug}', 'pages.team.show')->name('show');
});
Route::view('/testimonials', 'pages.testimonials.index')->name('testimonials.index');
