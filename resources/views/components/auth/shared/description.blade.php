@props([
    'text' => null,
    'alignment' => 'center',
    'size' => 'sm',
    'color' => 'muted',
    'width' => 'md',
])
@php
    $alignments = [
        'left' => 'text-left',
        'center' => 'text-center',
        'right' => 'text-right',
    ];
    $sizes = [
        'xs' => 'text-xs leading-5',
        'sm' => 'text-sm leading-6',
        'base' => 'text-base leading-7',
    ];
    $colors = [
        'muted' => 'text-muted',
        'subtle' => 'text-subtle',
        'foreground' => 'text-foreground',
        'primary' => 'text-primary',
    ];
    $widths = [
        'sm' => 'max-w-sm',
        'md' => 'max-w-md',
        'lg' => 'max-w-lg',
        'full' => 'max-w-none',
    ];
    $alignmentClass = $alignments[$alignment] ?? $alignments['center'];
    $sizeClass = $sizes[$size] ?? $sizes['sm'];
    $colorClass = $colors[$color] ?? $colors['muted'];
    $widthClass = $widths[$width] ?? $widths['md'];
    $text = is_string($text) ? trim($text) : null;
@endphp
<p {{ $attributes->class(['w-full', $alignmentClass, $sizeClass, $colorClass, $widthClass, 'text-pretty']) }}>
    @if ($text)
        {{ $text }}
    @else
        {{ $slot }}
    @endif
</p>
