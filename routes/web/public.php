<?php

use Illuminate\Support\Facades\Route;

Route::prefix('about')->name('about.')->group(function () {
    Route::view('/', 'pages.about.index')->name('index');
});
Route::prefix('services')->name('services.')->group(function () {
    Route::view('/', 'pages.services.index')->name('index');
    Route::get('/{slug}', fn (string $slug) => view('pages.services.detail.index', compact('slug')))->where('slug', '[a-z0-9]+(?:-[a-z0-9]+)*')->name('detail');
});
Route::prefix('products')->name('products.')->group(function () {
    Route::view('/', 'pages.products.index')->name('index');
    Route::get('/{slug}', fn (string $slug) => view('pages.products.detail.index', compact('slug')))->where('slug', '[a-z0-9]+(?:-[a-z0-9]+)*')->name('detail');
});
Route::prefix('solutions')->name('solutions.')->group(function () {
    Route::view('/', 'pages.solutions.index')->name('index');
});
Route::prefix('industries')->name('industries.')->group(function () {
    Route::view('/', 'pages.industries.index')->name('index');
});
Route::prefix('portfolios')->name('portfolios.')->group(function () {
    Route::view('/', 'pages.portfolios.index')->name('index');
    Route::get('/{slug}', fn (string $slug) => view('pages.portfolios.detail.index', compact('slug')))->where('slug', '[a-z0-9]+(?:-[a-z0-9]+)*')->name('detail');
});
Route::prefix('clients')->name('clients.')->group(function () {
    Route::view('/', 'pages.clients.index')->name('index');
});
Route::prefix('partners')->name('partners.')->group(function () {
    Route::view('/', 'pages.partners.index')->name('index');
});
Route::prefix('articles')->name('articles.')->group(function () {
    Route::view('/', 'pages.articles.index')->name('index');
    Route::get('/category/{slug}', fn (string $slug) => view('pages.articles.categories.index', compact('slug')))->where('slug', '[a-z0-9]+(?:-[a-z0-9]+)*')->name('categories');
    Route::get('/{slug}', fn (string $slug) => view('pages.articles.detail.index', compact('slug')))->where('slug', '[a-z0-9]+(?:-[a-z0-9]+)*')->name('detail');
});
Route::prefix('careers')->name('careers.')->group(function () {
    Route::view('/', 'pages.careers.index')->name('index');
    Route::get('/{slug}', fn (string $slug) => view('pages.careers.detail.index', compact('slug')))->where('slug', '[a-z0-9]+(?:-[a-z0-9]+)*')->name('detail');
});
Route::prefix('contact')->name('contact.')->group(function () {
    Route::view('/', 'pages.contact.index')->name('index');
});
Route::prefix('faq')->name('faq.')->group(function () {
    Route::view('/', 'pages.faq.index')->name('index');
});
Route::prefix('legal')->name('legal.')->group(function () {
    Route::view('/privacy-policy', 'pages.legal.privacy-policy.index')->name('privacy-policy');
    Route::view('/terms-of-service', 'pages.legal.terms-of-service.index')->name('terms-of-service');
    Route::view('/cookie-policy', 'pages.legal.cookie-policy.index')->name('cookie-policy');
});
Route::fallback(function () {
    abort(404);
});
