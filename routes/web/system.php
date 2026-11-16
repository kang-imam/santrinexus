<?php

use Illuminate\Support\Facades\Route;

Route::prefix('errors')->name('errors.')->group(function () {
    Route::view('/400', 'pages.errors.400')->name('400');
    Route::view('/401', 'pages.errors.401')->name('401');
    Route::view('/403', 'pages.errors.403')->name('403');
    Route::view('/404', 'pages.errors.404')->name('404');
    Route::view('/405', 'pages.errors.405')->name('405');
    Route::view('/408', 'pages.errors.408')->name('408');
    Route::view('/419', 'pages.errors.419')->name('419');
    Route::view('/422', 'pages.errors.422')->name('422');
    Route::view('/429', 'pages.errors.429')->name('429');
    Route::view('/500', 'pages.errors.500')->name('500');
    Route::view('/501', 'pages.errors.501')->name('501');
    Route::view('/502', 'pages.errors.502')->name('502');
    Route::view('/503', 'pages.errors.503')->name('503');
    Route::view('/504', 'pages.errors.504')->name('504');
    Route::view('/maintenance', 'pages.errors.maintenance')->name('maintenance');
});
