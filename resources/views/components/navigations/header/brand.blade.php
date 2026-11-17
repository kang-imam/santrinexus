@props([
    'size' => 'md',
    'showName' => true,
    'showTagline' => true,
])
@php
    $homeUrl = \Illuminate\Support\Facades\Route::has('home') ? route('home') : url('/');
    $label = 'SantriNexus - Beranda';
@endphp
<a href="{{ $homeUrl }}" aria-label="{{ $label }}" title="{{ $label }}"
    {{ $attributes->class([
        'group inline-flex min-w-0 shrink-0 items-center rounded-xl transition-opacity duration-200',
        'hover:opacity-85 active:opacity-70',
        'focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-primary',
    ]) }}>
    <x-shared.brand :size="$size" :show-name="$showName" :show-tagline="$showTagline" />
</a>
