@props([
    'size' => 'md',
    'showName' => true,
    'showTagline' => true,
    'logo' => null,
    'name' => 'SantriNexus',
    'tagline' => 'Core Digital Ecosystem',
])
@php
    $sizes = [
        'sm' => [
            'logo' => 'size-8',
            'name' => 'text-base',
            'tagline' => 'text-[10px]',
            'gap' => 'gap-2',
        ],
        'md' => [
            'logo' => 'size-10',
            'name' => 'text-lg',
            'tagline' => 'text-xs',
            'gap' => 'gap-3',
        ],
        'lg' => [
            'logo' => 'size-12',
            'name' => 'text-xl',
            'tagline' => 'text-sm',
            'gap' => 'gap-3',
        ],
    ];
    $styles = $sizes[$size] ?? $sizes['md'];
    $logo = is_string($logo) && trim($logo) !== '' ? $logo : asset('logo_santrinexus.svg');
    $name = is_string($name) && trim($name) !== '' ? trim($name) : 'SantriNexus';
    $tagline = is_string($tagline) ? trim($tagline) : '';
@endphp
<div {{ $attributes->class(['flex min-w-0 items-center', $styles['gap']]) }} aria-hidden="true">
    <img src="{{ $logo }}" alt="" width="{{ $size === 'sm' ? 32 : ($size === 'lg' ? 48 : 40) }}"
        height="{{ $size === 'sm' ? 32 : ($size === 'lg' ? 48 : 40) }}" loading="eager" fetchpriority="high"
        decoding="async" class="{{ $styles['logo'] }} shrink-0 object-contain">
    @if ($showName || ($showTagline && $tagline !== ''))
        <div class="min-w-0">
            @if ($showName)
                <span class="block truncate {{ $styles['name'] }} font-bold leading-tight text-foreground">
                    @if ($name === 'SantriNexus')
                        Santri<span class="text-primary">Nexus</span>
                    @else
                        {{ $name }}
                    @endif
                </span>
            @endif
            @if ($showTagline && $tagline !== '')
                <span class="block truncate {{ $styles['tagline'] }} leading-tight text-muted">
                    {{ $tagline }}
                </span>
            @endif
        </div>
    @endif
</div>
