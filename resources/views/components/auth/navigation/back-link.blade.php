@props([
    'href' => null,
    'route' => null,
    'parameters' => [],
    'label' => 'Kembali',
    'icon' => 'heroicon-o-arrow-left',
    'variant' => 'muted',
    'size' => 'md',
    'history' => false,
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
    ];
    $sizes = [
        'sm' => 'gap-1.5 text-xs',
        'md' => 'gap-2 text-sm',
        'lg' => 'gap-2.5 text-base',
    ];
    $fallbackUrl = $route ? route($route, $parameters) : ($href ?: url('/'));
    $variantClass = $variants[$variant] ?? $variants['muted'];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $linkId = $id ?: null;
@endphp
<a @if ($linkId) id="{{ $linkId }}" @endif href="{{ $fallbackUrl }}"
    @if ($history) x-data
        x-on:click.prevent="if (window.history.length > 1) { window.history.back() } else { window.location.assign($el.href) }" @endif
    {{ $attributes->except(['class', 'id', 'href'])->class([
            'group inline-flex items-center font-medium transition-colors duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 focus-visible:ring-offset-background rounded-sm',
            $variantClass,
            $sizeClass,
            $fullWidth ? 'w-full' : 'w-fit',
        ]) }}>
    @if ($icon)
        <x-shared.icon :name="$icon"
            class="size-4 shrink-0 transition-transform duration-200 group-hover:-translate-x-0.5" aria-hidden="true" />
    @endif
    <span>{{ $slot->isNotEmpty() ? $slot : $label }}</span>
</a>
