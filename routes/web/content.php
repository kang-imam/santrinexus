<?php

use Illuminate\Support\Facades\Route;

Route::prefix('blog')->name('blog.')->group(function () {
    Route::view('/', 'pages.blog.index')->name('index');
    Route::view('/archive', 'pages.blog.archive')->name('archive');
    Route::view('/search', 'pages.blog.search')->name('search');
    Route::view('/author/{slug}', 'pages.blog.author')->name('author');
    Route::view('/category/{slug}', 'pages.blog.category')->name('category');
    Route::view('/tag/{slug}', 'pages.blog.tag')->name('tag');
    Route::view('/{slug}', 'pages.blog.show')->name('show');
});
Route::view('/categories', 'pages.categories.index')->name('categories.index');
Route::view('/tags', 'pages.tags.index')->name('tags.index');
Route::view('/search', 'pages.search.index')->name('search.index');
