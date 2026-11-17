@props([
    'title' => null,
    'description' => null,
    'eyebrow' => null,
    'alignment' => 'center',
    'size' => '2xl',
])
@php
    $alignments = [
        'left' => 'items-start text-left',
        'center' => 'items-center text-center',
    ];
    $sizes = [
        'xl' => 'text-xl sm:text-2xl',
        '2xl' => 'text-2xl sm:text-3xl',
        '3xl' => 'text-3xl sm:text-4xl',
    ];
    $alignmentClass = $alignments[$alignment] ?? $alignments['center'];
    $sizeClass = $sizes[$size] ?? $sizes['2xl'];
    $title = is_string($title) ? trim($title) : null;
    $description = is_string($description) ? trim($description) : null;
    $eyebrow = is_string($eyebrow) ? trim($eyebrow) : null;
@endphp
<div {{ $attributes->class(['flex w-full flex-col gap-3', $alignmentClass]) }}>
    @if ($eyebrow)
        <span class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.2em] text-primary">
            <span class="size-1.5 rounded-full bg-primary" aria-hidden="true"></span>
            {{ $eyebrow }}
        </span>
    @endif
    @if ($title)
        <h1 class="{{ $sizeClass }} font-bold leading-tight tracking-tight text-foreground text-balance">
            {{ $title }}
        </h1>
    @else
        <h1 class="{{ $sizeClass }} font-bold leading-tight tracking-tight text-foreground text-balance">
            {{ $slot }}
        </h1>
    @endif
    @if ($description)
        <p class="max-w-md text-sm leading-6 text-muted text-pretty">
            {{ $description }}
        </p>
    @endif
</div>
