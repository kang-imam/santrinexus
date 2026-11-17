@props([
    'href' => null,
    'route' => null,
    'parameters' => [],
    'variant' => 'primary',
    'size' => 'md',
    'icon' => null,
    'iconRight' => null,
    'external' => false,
    'newTab' => false,
    'active' => false,
    'disabled' => false,
    'underline' => false,
    'fullWidth' => false,
    'id' => null,
])
@php
    $variants = [
        'primary' => 'text-primary hover:text-cyan-300',
        'secondary' => 'text-secondary hover:text-sky-300',
        'accent' => 'text-accent hover:text-lime-300',
        'muted' => 'text-muted hover:text-foreground',
        'subtle' => 'text-subtle hover:text-foreground',
        'white' => 'text-foreground hover:text-primary',
        'danger' => 'text-danger hover:text-rose-300',
    ];
    $sizes = [
        'xs' => 'text-xs gap-1',
        'sm' => 'text-sm gap-1.5',
        'md' => 'text-sm gap-2',
        'lg' => 'text-base gap-2',
    ];
    $linkHref = $route ? route($route, $parameters) : $href;
    $variantClass = $variants[$variant] ?? $variants['primary'];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $isDisabled = $disabled || blank($linkHref);
    $linkId = $id ?: null;
    $target = $newTab ? '_blank' : null;
    $rel = $newTab ? 'noopener noreferrer' : ($external ? 'noopener noreferrer' : null);
@endphp
@if ($isDisabled)
    <span @if ($linkId) id="{{ $linkId }}" @endif aria-disabled="true"
        {{ $attributes->except(['class', 'id'])->class([
                'inline-flex items-center font-medium opacity-50 cursor-not-allowed',
                $sizeClass,
                $fullWidth ? 'w-full' : 'w-auto',
            ]) }}>
        @if ($icon)
            <x-shared.icon :name="$icon" class="size-4 shrink-0" aria-hidden="true" />
        @endif
        <span>{{ $slot }}</span>
        @if ($iconRight)
            <x-shared.icon :name="$iconRight" class="size-4 shrink-0" aria-hidden="true" />
        @endif
    </span>
@else
    <a href="{{ $linkHref }}" @if ($linkId) id="{{ $linkId }}" @endif
        @if ($target) target="{{ $target }}" @endif
        @if ($rel) rel="{{ $rel }}" @endif
        @if ($active) aria-current="page" @endif
        @if ($external) aria-label="{{ trim(strip_tags((string) $slot)) }} (tautan eksternal)" @endif
        {{ $attributes->except(['class', 'id', 'href', 'target', 'rel'])->class([
                'inline-flex items-center font-medium transition-colors duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 focus-visible:ring-offset-background rounded-sm',
                $variantClass,
                $sizeClass,
                $fullWidth ? 'w-full' : 'w-auto',
                $active ? 'font-semibold' : '',
                $underline ? 'underline underline-offset-4' : 'no-underline',
            ]) }}>
        @if ($icon)
            <x-shared.icon :name="$icon" class="size-4 shrink-0" aria-hidden="true" />
        @endif
        <span>{{ $slot }}</span>
        @if ($iconRight)
            <x-shared.icon :name="$iconRight" class="size-4 shrink-0" aria-hidden="true" />
        @endif
    </a>
@endif
