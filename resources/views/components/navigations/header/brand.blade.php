@props([
    'size' => 'md',
    'showName' => true,
    'showTagline' => true,
    'homeUrl' => null,
    'label' => 'SantriNexus - Beranda',
])
@php
    $homeUrl =
        is_string($homeUrl) && trim($homeUrl) !== ''
            ? $homeUrl
            : (\Illuminate\Support\Facades\Route::has('home')
                ? route('home')
                : url('/'));
@endphp
<a href="{{ $homeUrl }}" aria-label="{{ $label }}" title="{{ $label }}"
    {{ $attributes->class([
        'group/header-brand inline-flex min-w-0 shrink-0 items-center rounded-xl',
        'transition-opacity duration-200 hover:opacity-85 active:opacity-70',
        'focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-primary',
    ]) }}>
    <x-shared.brand :size="$size" :show-name="$showName" :show-tagline="$showTagline"
        class="transition-transform duration-200 group-hover/header-brand:translate-x-0.5" />
</a>
