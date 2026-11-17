@props([
    'size' => 'md',
    'showName' => true,
    'showTagline' => true,
])
@php
    $homeUrl = \Illuminate\Support\Facades\Route::has('home') ? route('home') : url('/');
@endphp
<a href="{{ $homeUrl }}" aria-label="SantriNexus - Beranda" title="SantriNexus - Beranda"
    {{ $attributes->class([
        'group inline-flex min-w-0 shrink-0 items-center justify-center rounded-xl transition-opacity duration-200 hover:opacity-85 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-primary active:opacity-70',
    ]) }}>
    <x-shared.brand :size="$size" :show-name="$showName" :show-tagline="$showTagline" />
</a>
