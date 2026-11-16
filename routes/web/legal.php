<?php

use Illuminate\Support\Facades\Route;

Route::prefix('legal')->name('legal.')->group(function () {
    Route::view('/terms-of-service', 'pages.legal.terms-of-service')->name('terms-of-service');
    Route::view('/privacy-policy', 'pages.legal.privacy-policy')->name('privacy-policy');
    Route::view('/cookie-policy', 'pages.legal.cookie-policy')->name('cookie-policy');
    Route::view('/copyright', 'pages.legal.copyright')->name('copyright');
    Route::view('/disclaimer', 'pages.legal.disclaimer')->name('disclaimer');
    Route::view('/refund-policy', 'pages.legal.refund-policy')->name('refund-policy');
    Route::view('/acceptable-use-policy', 'pages.legal.acceptable-use-policy')->name('acceptable-use-policy');
    Route::view('/data-processing-policy', 'pages.legal.data-processing-policy')->name('data-processing-policy');
    Route::view('/accessibility-statement', 'pages.legal.accessibility-statement')->name('accessibility-statement');
});
