@props(['item', 'variant' => 'desktop'])
@php
    $isActive = (bool) ($item['active'] ?? false);
    $url = $item['url'] ?? null;
    $icon = is_string($item['icon'] ?? null) ? trim($item['icon']) : '';
    $label = $item['label'] ?? '';
    $target = $item['target'] ?? null;
    $rel = $item['rel'] ?? null;
    $badge = $item['badge'] ?? null;
    $showIcons = config('navigation.settings.show_icons', true);
    $showActiveState = config('navigation.settings.show_active_state', true);
    $isHeroicon = $icon !== '' && str_starts_with($icon, 'heroicon-');
    $isFontAwesome =
        $icon !== '' &&
        (preg_match('/^(?:fa[srb]|fa-(?:solid|regular|brands|light|thin|duotone))\s/i', $icon) === 1 ||
            preg_match('/^fa-[a-z0-9-]+$/i', $icon) === 1);
    if ($isFontAwesome && preg_match('/^(?:fa[srb]|fa-(?:solid|regular|brands|light|thin|duotone))\s/i', $icon) !== 1) {
        $icon = 'fa-solid ' . $icon;
    }
    $variant = in_array($variant, ['desktop', 'dropdown', 'mobile'], true) ? $variant : 'desktop';
    $variants = [
        'desktop' => 'min-h-10 px-3 py-2',
        'dropdown' => 'min-h-11 w-full px-3 py-2.5',
        'mobile' => 'min-h-12 w-full px-3.5 py-3',
    ];
    $baseClasses =
        'group relative flex items-center gap-3 overflow-hidden rounded-xl text-sm font-medium transition-all duration-200 ease-out focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary';
    $stateClasses = $isActive
        ? 'bg-primary/10 text-primary shadow-sm shadow-primary/5'
        : 'text-muted hover:bg-surface-hover hover:text-foreground active:scale-[0.98]';
    $classes = implode(' ', [$baseClasses, $variants[$variant], $stateClasses]);
    $relValues = preg_split('/\s+/', trim((string) $rel), -1, PREG_SPLIT_NO_EMPTY);
    if ($target === '_blank') {
        $relValues = array_unique(array_merge($relValues, ['noopener', 'noreferrer']));
    }
    $rel = implode(' ', $relValues);
@endphp
@if (is_string($url) && $url !== '')
    <a href="{{ $url }}" @if ($target) target="{{ $target }}" @endif
        @if ($rel !== '') rel="{{ $rel }}" @endif
        @if ($isActive && $showActiveState) aria-current="page" @endif {{ $attributes->merge(['class' => $classes]) }}>
        @if ($isActive && $showActiveState)
            <span class="absolute inset-y-2 left-0 w-0.5 rounded-full bg-primary" aria-hidden="true"></span>
        @endif
        @if ($showIcons && $isHeroicon)
            <x-dynamic-component :component="$icon"
                class="size-4 shrink-0 transition-transform duration-200 group-hover:scale-110" aria-hidden="true" />
        @elseif ($showIcons && $isFontAwesome)
            <span
                class="inline-flex size-4 shrink-0 items-center justify-center transition-transform duration-200 group-hover:scale-110"
                aria-hidden="true">
                <i class="{{ $icon }}"></i>
            </span>
        @endif
        <span class="min-w-0 flex-1 truncate">{{ $label }}</span>
        @if ($badge !== null && $badge !== '')
            <span
                class="inline-flex shrink-0 items-center rounded-full border border-primary/20 bg-primary/10 px-2 py-0.5 text-[10px] font-bold leading-none text-primary">
                {{ $badge }}
            </span>
        @endif
        @if ($variant === 'mobile')
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                class="size-4 shrink-0 text-subtle transition-transform duration-200 group-hover:translate-x-0.5 group-hover:text-primary"
                aria-hidden="true">
                <path fill-rule="evenodd"
                    d="M7.21 14.77a.75.75 0 0 1 .02-1.06L10.94 10 7.23 6.29a.75.75 0 1 1 1.06-1.06l4.24 4.24a.75.75 0 0 1 0 1.06l-4.24 4.24a.75.75 0 0 1-1.08 0Z"
                    clip-rule="evenodd" />
            </svg>
        @endif
    </a>
@endif
