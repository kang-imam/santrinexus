@props([
    'size' => 'lg',
    'showTagline' => true,
    'tagline' => 'Core Digital Ecosystem',
    'alignment' => 'center',
    'description' => null,
])
@php
    $alignmentClasses = [
        'left' => 'items-start text-left',
        'center' => 'items-center text-center',
    ];
    $alignmentClass = $alignmentClasses[$alignment] ?? $alignmentClasses['center'];
    $description = is_string($description) ? trim($description) : null;
@endphp
<div {{ $attributes->class(['flex flex-col gap-4', $alignmentClass]) }}>
    <x-shared.brand :size="$size" :showTagline="$showTagline" :tagline="$tagline" class="justify-center" />
    @if ($description)
        <p class="max-w-sm text-sm leading-6 text-muted">{{ $description }}</p>
    @endif
</div>
