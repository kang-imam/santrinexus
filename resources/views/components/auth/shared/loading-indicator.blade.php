@props([
    'type' => 'spinner',
    'size' => 'md',
    'color' => 'primary',
    'label' => 'Memuat...',
    'showLabel' => false,
    'layout' => 'horizontal',
    'overlay' => false,
    'fullscreen' => false,
    'visible' => true,
    'id' => null,
])
@php
    $sizes = [
        'xs' => ['spinner' => 'size-3', 'dots' => 'size-1', 'text' => 'text-xs', 'ring' => 'size-5'],
        'sm' => ['spinner' => 'size-4', 'dots' => 'size-1.5', 'text' => 'text-xs', 'ring' => 'size-6'],
        'md' => ['spinner' => 'size-6', 'dots' => 'size-2', 'text' => 'text-sm', 'ring' => 'size-8'],
        'lg' => ['spinner' => 'size-8', 'dots' => 'size-2.5', 'text' => 'text-sm', 'ring' => 'size-10'],
        'xl' => ['spinner' => 'size-10', 'dots' => 'size-3', 'text' => 'text-base', 'ring' => 'size-12'],
    ];
    $colors = [
        'primary' => ['main' => 'text-primary', 'dots' => 'bg-primary', 'ring' => 'border-primary'],
        'secondary' => ['main' => 'text-secondary', 'dots' => 'bg-secondary', 'ring' => 'border-secondary'],
        'accent' => ['main' => 'text-accent', 'dots' => 'bg-accent', 'ring' => 'border-accent'],
        'muted' => ['main' => 'text-muted', 'dots' => 'bg-muted', 'ring' => 'border-muted'],
        'white' => ['main' => 'text-white', 'dots' => 'bg-white', 'ring' => 'border-white'],
    ];
    $sizeConfig = $sizes[$size] ?? $sizes['md'];
    $colorConfig = $colors[$color] ?? $colors['primary'];
    $loadingType = in_array($type, ['spinner', 'dots', 'ring', 'pulse'], true) ? $type : 'spinner';
    $loadingId = $id ?: null;
    $layoutClass = $layout === 'vertical' ? 'flex-col' : 'flex-row';
@endphp
@if ($visible)
    @if ($fullscreen)
        <div @if ($loadingId) id="{{ $loadingId }}" @endif
            {{ $attributes->except(['class', 'id'])->class(['fixed inset-0 z-50 flex items-center justify-center bg-background/80 p-6 backdrop-blur-sm']) }}
            role="status" aria-live="polite" aria-label="{{ $label }}">
            <div
                class="flex flex-col items-center gap-4 rounded-2xl border border-border bg-surface-raised p-8 shadow-2xl">
                @includeWhen($loadingType === 'spinner', 'components.auth.shared.loading-indicator-spinner')
                @if ($loadingType === 'spinner')
                    <svg class="{{ $sizeConfig['spinner'] }} {{ $colorConfig['main'] }} animate-spin" viewBox="0 0 24 24"
                        fill="none" aria-hidden="true">
                        <circle class="opacity-20" cx="12" cy="12" r="9" stroke="currentColor"
                            stroke-width="3" />
                        <path d="M21 12a9 9 0 0 0-9-9" stroke="currentColor" stroke-width="3" stroke-linecap="round" />
                    </svg>
                @elseif ($loadingType === 'dots')
                    <span class="flex items-center gap-1.5" aria-hidden="true">
                        @for ($i = 0; $i < 3; $i++)
                            <span
                                class="{{ $sizeConfig['dots'] }} {{ $colorConfig['dots'] }} animate-bounce rounded-full"
                                style="animation-delay: {{ $i * 150 }}ms"></span>
                        @endfor
                    </span>
                @elseif ($loadingType === 'ring')
                    <span
                        class="{{ $sizeConfig['ring'] }} animate-spin rounded-full border-2 border-border border-t-current {{ $colorConfig['main'] }}"
                        aria-hidden="true"></span>
                @else
                    <span
                        class="{{ $sizeConfig['spinner'] }} {{ $colorConfig['main'] }} animate-pulse rounded-full bg-current opacity-70"
                        aria-hidden="true"></span>
                @endif
                <span class="{{ $sizeConfig['text'] }} font-medium text-foreground">{{ $label }}</span>
                @if ($slot->isNotEmpty())
                    <span class="text-xs text-muted">{{ $slot }}</span>
                @endif
            </div>
        </div>
    @else
        <div @if ($loadingId) id="{{ $loadingId }}" @endif
            {{ $attributes->except(['class', 'id'])->class([
                    'flex items-center gap-3',
                    $layoutClass,
                    $overlay ? 'absolute inset-0 z-20 justify-center bg-background/75 backdrop-blur-sm' : '',
                ]) }}
            role="status" aria-live="polite" aria-label="{{ $label }}">
            @if ($loadingType === 'spinner')
                <svg class="{{ $sizeConfig['spinner'] }} {{ $colorConfig['main'] }} shrink-0 animate-spin"
                    viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <circle class="opacity-20" cx="12" cy="12" r="9" stroke="currentColor"
                        stroke-width="3" />
                    <path d="M21 12a9 9 0 0 0-9-9" stroke="currentColor" stroke-width="3" stroke-linecap="round" />
                </svg>
            @elseif ($loadingType === 'dots')
                <span class="flex shrink-0 items-center gap-1.5" aria-hidden="true">
                    @for ($i = 0; $i < 3; $i++)
                        <span class="{{ $sizeConfig['dots'] }} {{ $colorConfig['dots'] }} animate-bounce rounded-full"
                            style="animation-delay: {{ $i * 150 }}ms"></span>
                    @endfor
                </span>
            @elseif ($loadingType === 'ring')
                <span
                    class="{{ $sizeConfig['ring'] }} {{ $colorConfig['main'] }} shrink-0 animate-spin rounded-full border-2 border-border border-t-current"
                    aria-hidden="true"></span>
            @else
                <span
                    class="{{ $sizeConfig['spinner'] }} {{ $colorConfig['main'] }} shrink-0 animate-pulse rounded-full bg-current opacity-70"
                    aria-hidden="true"></span>
            @endif
            @if ($showLabel || $slot->isNotEmpty())
                <span
                    class="{{ $sizeConfig['text'] }} font-medium text-muted">{{ $slot->isNotEmpty() ? $slot : $label }}</span>
            @endif
        </div>
    @endif
@endif
