@props([
    'size' => 'md',
    'showName' => true,
    'showTagline' => true,
    'logo' => null,
    'name' => 'SantriNexus',
    'tagline' => 'Core Digital Ecosystem',
    'logoClass' => '',
    'nameClass' => '',
    'taglineClass' => '',
])
@php
    $sizes = [
        'sm' => [
            'logo' => 'size-8',
            'dimension' => 32,
            'name' => 'text-base',
            'tagline' => 'text-[10px]',
            'gap' => 'gap-2',
        ],
        'md' => [
            'logo' => 'size-10',
            'dimension' => 40,
            'name' => 'text-lg',
            'tagline' => 'text-[11px]',
            'gap' => 'gap-2.5',
        ],
        'lg' => [
            'logo' => 'size-12',
            'dimension' => 48,
            'name' => 'text-xl',
            'tagline' => 'text-xs',
            'gap' => 'gap-3',
        ],
        'xl' => [
            'logo' => 'size-14',
            'dimension' => 56,
            'name' => 'text-2xl',
            'tagline' => 'text-sm',
            'gap' => 'gap-3.5',
        ],
    ];
    $styles = $sizes[$size] ?? $sizes['md'];
    $logo = is_string($logo) && trim($logo) !== '' ? $logo : asset('logo_santrinexus.svg');
    $name = is_string($name) && trim($name) !== '' ? trim($name) : 'SantriNexus';
    $tagline = is_string($tagline) ? trim($tagline) : '';
    $isDefaultName = $name === 'SantriNexus';
@endphp
<div {{ $attributes->class(['group/brand inline-flex min-w-0 items-center', $styles['gap']]) }}>
    <span class="relative grid shrink-0 place-items-center">
        <img src="{{ $logo }}" alt="" width="{{ $styles['dimension'] }}" height="{{ $styles['dimension'] }}"
            loading="eager" decoding="async"
            class="{{ $styles['logo'] }} {{ $logoClass }} object-contain transition-transform duration-300 group-hover/brand:scale-105">
    </span>
    @if ($showName || ($showTagline && $tagline !== ''))
        <span class="flex min-w-0 flex-col">
            @if ($showName)
                <span
                    class="block truncate {{ $styles['name'] }} {{ $nameClass }} font-extrabold leading-tight tracking-tight text-foreground">
                    @if ($isDefaultName)
                        Santri<span class="text-primary">Nexus</span>
                    @else
                        {{ $name }}
                    @endif
                </span>
            @endif
            @if ($showTagline && $tagline !== '')
                <span
                    class="block truncate {{ $styles['tagline'] }} {{ $taglineClass }} mt-0.5 font-medium leading-tight tracking-wide text-muted">
                    {{ $tagline }}
                </span>
            @endif
        </span>
    @endif
</div>
