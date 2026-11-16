<?php

use Illuminate\Support\Facades\Route;

Route::prefix('portfolio')->name('portfolio.')->group(function () {
    Route::view('/', 'pages.portfolio.index')->name('index');
    Route::view('/category/{slug}', 'pages.portfolio.category')->name('category');
    Route::view('/{slug}', 'pages.portfolio.show')->name('show');
});
Route::prefix('services')->name('services.')->group(function () {
    Route::view('/', 'pages.services.index')->name('index');
    Route::view('/category/{slug}', 'pages.services.category')->name('category');
    Route::view('/process', 'pages.services.process')->name('process');
    Route::view('/{slug}', 'pages.services.show')->name('show');
});
Route::prefix('solutions')->name('solutions.')->group(function () {
    Route::view('/', 'pages.solutions.index')->name('index');
    Route::view('/category/{slug}', 'pages.solutions.category')->name('category');
    Route::view('/{slug}', 'pages.solutions.show')->name('show');
});
Route::prefix('pricing')->name('pricing.')->group(function () {
    Route::view('/', 'pages.pricing.index')->name('index');
    Route::view('/comparison', 'pages.pricing.comparison')->name('comparison');
    Route::view('/{slug}', 'pages.pricing.show')->name('show');
});
