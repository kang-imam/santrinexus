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
    $isHeroicon = $icon !== '' && str_starts_with($icon, 'heroicon-');
    $isFontAwesome =
        $icon !== '' &&
        (preg_match('/^(?:fa[srb]|fa-(?:solid|regular|brands|light|thin|duotone))\s/i', $icon) === 1 ||
            preg_match('/^fa-[a-z0-9-]+$/i', $icon) === 1);
    if ($isFontAwesome && preg_match('/^(?:fa[srb]|fa-(?:solid|regular|brands|light|thin|duotone))\s/i', $icon) !== 1) {
        $icon = 'fa-solid ' . $icon;
    }
    $variants = [
        'desktop' => 'px-3 py-2',
        'dropdown' => 'px-3 py-2.5',
        'mobile' => 'w-full px-3 py-3',
    ];
    $variant = array_key_exists($variant, $variants) ? $variant : 'desktop';
    $baseClasses =
        'group flex items-center gap-3 rounded-xl text-sm font-medium transition duration-200 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary';
    $stateClasses = $isActive
        ? 'bg-primary/10 text-primary'
        : 'text-muted hover:bg-surface-hover hover:text-foreground';
    $classes = $baseClasses . ' ' . $variants[$variant] . ' ' . $stateClasses;
    $relValues = preg_split('/\s+/', trim((string) $rel), -1, PREG_SPLIT_NO_EMPTY);
    if ($target === '_blank') {
        $relValues = array_unique(array_merge($relValues, ['noopener', 'noreferrer']));
    }
    $rel = implode(' ', $relValues);
@endphp
@if (is_string($url) && $url !== '')
    <a href="{{ $url }}" @if ($target) target="{{ $target }}" @endif
        @if ($rel !== '') rel="{{ $rel }}" @endif
        @if ($isActive && config('navigation.settings.show_active_state', true)) aria-current="page" @endif {{ $attributes->merge(['class' => $classes]) }}>
        @if ($showIcons && $isHeroicon)
            <x-dynamic-component :component="$icon" class="size-4 shrink-0" aria-hidden="true" />
        @elseif ($showIcons && $isFontAwesome)
            <span class="inline-flex size-4 shrink-0 items-center justify-center" aria-hidden="true">
                <i class="{{ $icon }}"></i>
            </span>
        @endif
        <span class="min-w-0 flex-1 truncate">{{ $label }}</span>
        @if ($badge !== null && $badge !== '')
            <span class="shrink-0 rounded-full bg-primary/10 px-2 py-0.5 text-[10px] font-semibold text-primary">
                {{ $badge }}
            </span>
        @endif
        @if ($variant === 'mobile')
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                class="size-4 shrink-0 text-subtle" aria-hidden="true">
                <path fill-rule="evenodd"
                    d="M7.21 14.77a.75.75 0 0 1 .02-1.06L10.94 10 7.23 6.29a.75.75 0 1 1 1.06-1.06l4.24 4.24a.75.75 0 0 1 0 1.06l-4.24 4.24a.75.75 0 0 1-1.08 0Z"
                    clip-rule="evenodd" />
            </svg>
        @endif
    </a>
@endif
