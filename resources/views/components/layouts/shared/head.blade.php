@props([
    'title' => null,
    'description' => null,
    'robots' => 'index, follow',
    'themeColor' => '#030712',
])

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="{{ $themeColor }}">
    <meta name="color-scheme" content="dark">
    <meta name="robots" content="{{ $robots }}">
    <meta name="description" content="{{ $description ?: config('app.name', 'SantriNexus') }}">
    <title>{{ $title ? $title . ' | ' . config('app.name', 'SantriNexus') : config('app.name', 'SantriNexus') }}</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    @stack('head')
</head>
