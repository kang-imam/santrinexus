@props([
    'href' => null,
    'label' => '',
    'icon' => null,
    'active' => false,
    'target' => null,
    'rel' => null,
    'badge' => null,
])
@php
    $label = is_scalar($label) ? trim((string) $label) : '';
    $hasLabel = $label !== '';
    $href = is_string($href) ? trim($href) : '';
    $hasUrl = $href !== '';
    $isActive = filter_var($active, FILTER_VALIDATE_BOOLEAN);
    $linkTarget = is_string($target) && trim($target) !== '' ? trim($target) : null;
    $linkRel = is_string($rel) && trim($rel) !== '' ? trim($rel) : null;
    $icon = is_string($icon) && trim($icon) !== '' ? trim($icon) : null;
    $badgeLabel = is_scalar($badge) ? trim((string) $badge) : '';
    $safeHref = $hasUrl && preg_match('/^(https?:\/\/|mailto:|tel:|\/|#)/i', $href);
    $hasUrl = (bool) $safeHref;
    if ($linkTarget === '_blank') {
        $linkRel = collect(preg_split('/\s+/', $linkRel ?? ''))
            ->filter()
            ->merge(['noopener', 'noreferrer'])
            ->unique()
            ->implode(' ');
    }
@endphp
@if ($hasLabel)
    @if ($hasUrl)
        <a href="{{ $href }}" @if ($linkTarget) target="{{ $linkTarget }}" @endif
            @if ($linkRel) rel="{{ $linkRel }}" @endif
            @if ($isActive) aria-current="page" @endif @class([
                'group inline-flex min-w-0 max-w-full items-center gap-2.5 text-sm transition-colors duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 focus-visible:ring-offset-background',
                'font-semibold text-primary' => $isActive,
                'text-muted hover:text-primary' => !$isActive,
            ])>
            @if ($icon)
                <x-dynamic-component :component="$icon" @class([
                    'h-4 w-4 shrink-0 transition-colors duration-200',
                    'text-primary' => $isActive,
                    'text-subtle group-hover:text-primary' => !$isActive,
                ]) aria-hidden="true" />
            @else
                <span @class([
                    'h-1.5 w-1.5 shrink-0 rounded-full transition-colors duration-200',
                    'bg-primary' => $isActive,
                    'bg-subtle group-hover:bg-primary' => !$isActive,
                ]) aria-hidden="true"></span>
            @endif
            <span class="min-w-0 truncate">{{ $label }}</span>
            @if ($badgeLabel !== '')
                <span
                    class="ml-auto shrink-0 rounded-full bg-primary/10 px-2 py-0.5 text-[10px] font-semibold leading-4 text-primary">
                    {{ $badgeLabel }}
                </span>
            @endif
            @if ($linkTarget === '_blank')
                <x-heroicon-o-arrow-top-right-on-square class="h-3.5 w-3.5 shrink-0 text-subtle" aria-hidden="true" />
                <span class="sr-only">(membuka tab baru)</span>
            @endif
        </a>
    @else
        <span @class([
            'inline-flex min-w-0 max-w-full items-center gap-2.5 text-sm',
            'font-semibold text-primary' => $isActive,
            'font-medium text-foreground' => !$isActive,
        ])>
            @if ($icon)
                <x-dynamic-component :component="$icon" class="h-4 w-4 shrink-0 text-subtle" aria-hidden="true" />
            @else
                <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-subtle" aria-hidden="true"></span>
            @endif
            <span class="min-w-0 truncate">{{ $label }}</span>
            @if ($badgeLabel !== '')
                <span
                    class="ml-auto shrink-0 rounded-full bg-primary/10 px-2 py-0.5 text-[10px] font-semibold leading-4 text-primary">
                    {{ $badgeLabel }}
                </span>
            @endif
        </span>
    @endif
@endif
