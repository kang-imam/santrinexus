@props([
    'text' => 'Atau',
    'variant' => 'default',
    'spacing' => 'md',
    'alignment' => 'center',
    'line' => true,
])
@php
    $text = is_string($text) ? trim($text) : '';
    $variants = [
        'default' => [
            'line' => 'bg-border',
            'text' => 'text-muted',
        ],
        'subtle' => [
            'line' => 'bg-border/60',
            'text' => 'text-subtle',
        ],
        'accent' => [
            'line' => 'bg-primary/30',
            'text' => 'text-primary',
        ],
    ];
    $spacings = [
        'sm' => 'my-3',
        'md' => 'my-5',
        'lg' => 'my-7',
        'xl' => 'my-9',
        'none' => '',
    ];
    $alignments = [
        'center' => 'justify-center',
        'left' => 'justify-start',
        'right' => 'justify-end',
    ];
    $style = $variants[$variant] ?? $variants['default'];
    $spacingClass = $spacings[$spacing] ?? $spacings['md'];
    $alignmentClass = $alignments[$alignment] ?? $alignments['center'];
@endphp
<div {{ $attributes->class(['flex w-full items-center gap-4', $spacingClass, $alignmentClass]) }} role="separator"
    aria-orientation="horizontal">
    @if ($line && ($alignment !== 'right' || $text === ''))
        <span class="h-px min-w-0 flex-1 {{ $style['line'] }}" aria-hidden="true"></span>
    @endif
    @if ($text !== '' || $slot->isNotEmpty())
        <span class="shrink-0 text-xs font-medium tracking-wide {{ $style['text'] }}">
            @if ($slot->isNotEmpty())
                {{ $slot }}
            @else
                {{ $text }}
            @endif
        </span>
    @endif
    @if ($line && ($alignment !== 'left' || $text === ''))
        <span class="h-px min-w-0 flex-1 {{ $style['line'] }}" aria-hidden="true"></span>
    @endif
</div>
