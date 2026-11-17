@props([
    'title' => null,
    'description' => null,
    'variant' => 'default',
    'padding' => 'md',
    'width' => 'full',
    'border' => true,
    'glow' => true,
    'centered' => false,
    'id' => null,
])
@php
    $variants = [
        'default' => 'bg-surface/95 shadow-2xl shadow-black/20',
        'glass' => 'bg-surface/75 shadow-2xl shadow-black/20 backdrop-blur-xl',
        'elevated' => 'bg-surface-raised shadow-2xl shadow-black/30',
        'transparent' => 'bg-transparent shadow-none',
    ];
    $paddings = [
        'none' => 'p-0',
        'sm' => 'p-5 sm:p-6',
        'md' => 'p-6 sm:p-8',
        'lg' => 'p-7 sm:p-10',
        'xl' => 'p-8 sm:p-12',
    ];
    $widths = [
        'full' => 'w-full',
        'sm' => 'w-full max-w-sm',
        'md' => 'w-full max-w-md',
        'lg' => 'w-full max-w-lg',
        'xl' => 'w-full max-w-xl',
    ];
    $variantClass = $variants[$variant] ?? $variants['default'];
    $paddingClass = $paddings[$padding] ?? $paddings['md'];
    $widthClass = $widths[$width] ?? $widths['full'];
    $hasHeader = filled($title) || filled($description);
@endphp
<section @if ($id) id="{{ $id }}" @endif @class([
    'relative isolate overflow-hidden rounded-2xl sm:rounded-3xl',
    $variantClass,
    $paddingClass,
    $widthClass,
    'border border-border/80' => $border,
    'border-0' => !$border,
    'mx-auto' => $centered,
])
    {{ $attributes->except(['class', 'id']) }}>
    @if ($glow && $variant !== 'transparent')
        <div class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-primary/50 to-transparent"
            aria-hidden="true"></div>
        <div class="pointer-events-none absolute -right-24 -top-24 size-48 rounded-full bg-primary/[0.045] blur-3xl"
            aria-hidden="true"></div>
    @endif
    <div class="relative z-10">
        @if ($hasHeader)
            <header @class(['mb-6 sm:mb-8', 'text-center' => $centered])>
                @if (filled($title))
                    <h2 class="text-xl font-bold tracking-tight text-foreground sm:text-2xl">
                        {{ $title }}
                    </h2>
                @endif
                @if (filled($description))
                    <p class="mt-2 text-sm leading-6 text-muted">
                        {{ $description }}
                    </p>
                @endif
            </header>
        @endif
        {{ $slot }}
    </div>
</section>
