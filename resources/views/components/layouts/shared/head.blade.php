@props([
    'title' => null,
    'description' => null,
    'robots' => 'index, follow',
    'themeColor' => '#030712',
])
@php
    $appName = config('app.name', 'SantriNexus');
    $pageTitle = $title ? "{$title} | {$appName}" : $appName;
    $pageDescription = $description ?: config('app.description', $appName);
@endphp
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="color-scheme" content="dark">
    <meta name="theme-color" content="{{ $themeColor }}">
    <meta name="robots" content="{{ $robots }}">
    <meta name="description" content="{{ $pageDescription }}">
    <title>{{ $pageTitle }}</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    @stack('head')
</head>