@props(['item' => [], 'variant' => 'desktop'])
@php
    $item = is_array($item) ? $item : [];
    $label = is_string($item['label'] ?? null) ? $item['label'] : '';
    $url = is_string($item['url'] ?? null) ? trim($item['url']) : '';
    $icon = $item['icon'] ?? null;
    $iconFallback = $item['icon_fallback'] ?? null;
    $badge = $item['badge'] ?? null;
    $target = is_string($item['target'] ?? null) ? $item['target'] : null;
    $rel = is_string($item['rel'] ?? null) ? $item['rel'] : '';
    $isActive = (bool) ($item['active'] ?? false);
    $isNavigable = (bool) ($item['navigable'] ?? true);
    $showIcons = (bool) config('navigation.settings.show_icons', true);
    $showActiveState = (bool) config('navigation.settings.show_active_state', true);
    $showBadge = (bool) config('navigation.settings.show_badges', true);
    $variant = in_array($variant, ['desktop', 'dropdown', 'mobile'], true) ? $variant : 'desktop';
    $variants = [
        'desktop' => 'min-h-10 px-3 py-2',
        'dropdown' => 'min-h-11 w-full px-3 py-2.5',
        'mobile' => 'min-h-12 w-full px-3.5 py-3',
    ];
    $baseClasses =
        'group relative flex items-center gap-3 overflow-hidden rounded-xl text-sm font-medium transition-colors duration-200 ease-out focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary';
    $stateClasses =
        $isActive && $showActiveState
            ? 'bg-primary/10 text-primary shadow-sm shadow-primary/5'
            : 'text-muted hover:bg-surface-hover hover:text-foreground';
    $classes = implode(' ', [$baseClasses, $variants[$variant], $stateClasses]);
    $relValues = preg_split('/\s+/', trim($rel), -1, PREG_SPLIT_NO_EMPTY) ?: [];
    if ($target === '_blank') {
        $relValues = array_merge($relValues, ['noopener', 'noreferrer']);
    }
    $rel = implode(' ', array_unique($relValues));
    $hasIcon =
        $showIcons &&
        ((is_string($icon) && trim($icon) !== '') || (is_string($iconFallback) && trim($iconFallback) !== ''));
    $hasBadge = $showBadge && ((is_string($badge) && trim($badge) !== '') || is_numeric($badge));
    $isLink = $url !== '' && $isNavigable;
    $attributes = $attributes->merge(['class' => $classes]);
@endphp
@if ($isLink)
    <a href="{{ $url }}" @if ($target) target="{{ $target }}" @endif
        @if ($rel !== '') rel="{{ $rel }}" @endif
        @if ($isActive && $showActiveState) aria-current="page" @endif
        @if ($label !== '') aria-label="{{ $label }}" @endif {{ $attributes }}>
        @if ($isActive && $showActiveState)
            <span class="absolute inset-y-2 left-0 w-0.5 rounded-full bg-primary" aria-hidden="true"></span>
        @endif
        @if ($hasIcon)
            <x-shared.icon :name="$icon" :fallback="$iconFallback" class="size-4 shrink-0" aria-hidden="true" />
        @endif
        <span class="min-w-0 flex-1 truncate">{{ $label }}</span>
        @if ($hasBadge)
            <span
                class="inline-flex shrink-0 items-center rounded-full border border-primary/20 bg-primary/10 px-2 py-0.5 text-[10px] font-bold leading-none text-primary">
                {{ $badge }}
            </span>
        @endif
        @if ($variant === 'mobile')
            <x-shared.icon name="heroicon-o-chevron-right" fallback="fa-solid fa-chevron-right"
                class="size-4 shrink-0 text-subtle transition-transform duration-200 group-hover:translate-x-0.5 group-hover:text-primary"
                aria-hidden="true" />
        @endif
    </a>
@endif
